<?php
/**
 * Send email after various event
 *
 * @package : mail
 * @plugin wp-crowdfunding
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('Wpneo_Crowdfunding_Email')) {
    class Wpneo_Crowdfunding_Email
    {
        /**
         * @var null
         *
         * Instance of this class
         */
        protected static $_instance = null;

        /**
         * @return null|Wpneo_Crowdfunding
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Wpneo_Crowdfunding_Email constructor.
         */
        public function __construct(){
            add_option( 'wpneo_new_user_email_subject', 'Congratulation [user_name]');
            add_action( 'init', array($this, 'wpneo_social_share_save_settings') ); //Do
            add_filter( 'wpneo_crowdfunding_settings_panel_tabs', array($this, 'add_email_tab_to_wpneo_crowdfunding_settings')); //Hook to add social share field with user registration form
            if (get_option('wpneo_enable_email') === 'true' && get_option('wpneo_enable_new_user_registration_email') === 'true'){
                add_action('wpneo_crowdfunding_after_user_registration', array($this,'send_email_to_new_user'));  //Send Email if new user registration
            }
            add_action( 'woocommerce_thankyou', array($this, 'wpneo_crowdfunding_custom_order_process') ); //Add action after got a backed
            add_action( 'phpmailer_init', array($this, 'wpneo_phpmailer_init') ); // PHP Mailer Settings
            register_activation_hook(__FILE__, array( $this, 'initial_plugin_setup' )); // Installation Hook
            add_action( 'wpneo_crowdfunding_after_campaign_email', array( $this, 'wpneo_campaign_submit_process' ) ); // Submit Campaign Action
            add_action( 'publish_product', array( $this, 'wpneo_campaign_published_process' ), 10, 2 ); // Published Campaign Action

            //start fincrowd mails
            add_action('wpneo_fi_after_cancel_order', array($this,'wpneo_fi_send_email_cancel_order'));
            add_action('wpneo_fi_after_validate_campaign', array($this,'wpneo_fi_send_email_validate_campaign'));
            //endfincrowd

        }

        public function initial_plugin_setup(){
            // Email Notification After New User Registration
            add_option('wpneo_new_user_email_subject', 'Congratulation [user_name]');
            add_option('wpneo_user_registration_email_template', "Congratulation [user_name], \n Email from [site_title]" );
            // Email notification after new backed
            add_option('wpneo_new_backer_email_subject', 'You have placed backer successfully');
            add_option('wpneo_backer_email_template', 'Hi [user_name],\n You have got a backed [total_amount] on [campaign_title]' );
            // Email notification after submit campaign
            add_option('wpneo_new_campaign_email_subject', 'New Campaign Submitted.');
            add_option('wpneo_campaign_email_template', 'A campaign has been submitted by [user_name],\n Name of the campaign is "[campaign_title]"' );
            // Campaign Published Campaign Author
            add_option('wpneo_accept_campaign_email_subject', 'Your campaign has been published.');
            add_option('wpneo_accept_campaign_email_template', 'Hi [user_name],\n "[campaign_title]" this campaign has been published .');
        }


        // PHPMailer Settings
        public function wpneo_phpmailer_init($phpmailer) {
            if( 'true' == get_option( 'wpneo_enable_smtp' )){
                $form_email     = get_option( 'wpneo_smtp_form_email' );
                $form_text      = get_option( 'wpneo_smtp_form_text' );
                $host           = get_option( 'wpneo_smtp_host' );
                $port           = get_option( 'wpneo_smtp_port' );
                $username       = get_option( 'wpneo_smtp_username' );
                $password       = get_option( 'wpneo_smtp_password' );
                $encription     = get_option( 'wpneo_smtp_encription' );

                $phpmailer->Mailer      = 'smtp';
                $phpmailer->From        = $form_email;
                $phpmailer->FromName    = $form_text;
                $phpmailer->SMTPSecure  = $encription;
                $phpmailer->Host        = $host;
                $phpmailer->Port        = $port;

                // If usrname option is not blank we have to use authentication
                if ($username != '') {
                    $phpmailer->SMTPAuth = true;
                    $phpmailer->Username = $username;
                    $phpmailer->Password = $password;
                }
            }
        }

        public function add_email_tab_to_wpneo_crowdfunding_settings($tabs){
            //Defining page location into variable
            $load_email_page = plugin_dir_path(__FILE__).'pages/tab-email-demo.php';
            if (WPNEO_CROWDFUNDING_TYPE === 'free'){
              //fincrowd I paied so it's ok $email_page = $load_email_page;
              $email_page = plugin_dir_path(__FILE__).'pages/tab-email.php';
            }else{
                $email_page = plugin_dir_path(__FILE__).'pages/tab-email.php';
            }

            $tabs['email'] = array(
                'tab_name' => __('Email','wp-crowdfunding'),
                'load_form_file' => $email_page
            );
            return $tabs;
        }

        /**
         * All settings will be save in this method
         */
        public function wpneo_social_share_save_settings(){
            if (isset($_POST['wpneo_admin_settings_submit_btn']) && isset($_POST['wpneo_varify_email_addons']) && wp_verify_nonce( $_POST['wpneo_settings_page_nonce_field'], 'wpneo_settings_page_action' ) ){

                // Checkbox
                $wpneo_enable_email = sanitize_text_field(wpneo_post('wpneo_enable_email'));
                wpneo_crowdfunding_update_option_checkbox('wpneo_enable_email', $wpneo_enable_email);

                $wpneo_enable_smtp = sanitize_text_field(wpneo_post('wpneo_enable_smtp'));
                wpneo_crowdfunding_update_option_checkbox('wpneo_enable_smtp', $wpneo_enable_smtp);

                $wpneo_enable_new_user_registration_email = sanitize_text_field(wpneo_post('wpneo_enable_new_user_registration_email'));
                wpneo_crowdfunding_update_option_checkbox('wpneo_enable_new_user_registration_email', $wpneo_enable_new_user_registration_email);

                $wpneo_enable_new_user_registration_email_admin = sanitize_text_field(wpneo_post('wpneo_enable_new_user_registration_email_admin'));
                wpneo_crowdfunding_update_option_checkbox('wpneo_enable_new_user_registration_email_admin', $wpneo_enable_new_user_registration_email_admin);

                $wpneo_enable_new_user_registration_email_user = sanitize_text_field(wpneo_post('wpneo_enable_new_user_registration_email_user'));
                wpneo_crowdfunding_update_option_checkbox('wpneo_enable_new_user_registration_email_user', $wpneo_enable_new_user_registration_email_user);

                $wpneo_smtp_encription = sanitize_text_field(wpneo_post('wpneo_smtp_encription'));
                wpneo_crowdfunding_update_option_checkbox('wpneo_smtp_encription', $wpneo_smtp_encription);

                $wpneo_enable_new_campaign_email = sanitize_text_field( wpneo_post('wpneo_enable_new_campaign_email') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_new_campaign_email', $wpneo_enable_new_campaign_email );

                $wpneo_enable_new_campaign_email_admin = sanitize_text_field( wpneo_post('wpneo_enable_new_campaign_email_admin') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_new_campaign_email_admin', $wpneo_enable_new_campaign_email_admin );

                $wpneo_enable_new_campaign_email_user = sanitize_text_field( wpneo_post('wpneo_enable_new_campaign_email_user') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_new_campaign_email_user', $wpneo_enable_new_campaign_email_user );

                $wpneo_enable_accept_campaign_email = sanitize_text_field( wpneo_post('wpneo_enable_accept_campaign_email') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_accept_campaign_email', $wpneo_enable_accept_campaign_email );

                $wpneo_enable_accept_campaign_email_admin = sanitize_text_field( wpneo_post('wpneo_enable_accept_campaign_email_admin') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_accept_campaign_email_admin', $wpneo_enable_accept_campaign_email_admin );

                $wpneo_enable_accept_campaign_email_user = sanitize_text_field( wpneo_post('wpneo_enable_accept_campaign_email_user') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_accept_campaign_email_user', $wpneo_enable_accept_campaign_email_user );
                //Start fincrowd
                //Cancel pledge tot campaign
                $wpneo_enable_cancel_campaign_email = sanitize_text_field( wpneo_post('wpneo_enable_cancel_campaign_email') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_cancel_campaign_email', $wpneo_enable_cancel_campaign_email );

                $wpneo_enable_cancel_campaign_email_admin = sanitize_text_field( wpneo_post('wpneo_enable_cancel_campaign_email_admin') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_cancel_campaign_email_admin', $wpneo_enable_cancel_campaign_email_admin );

                $wpneo_enable_cancel_campaign_email_user = sanitize_text_field( wpneo_post('wpneo_enable_cancel_campaign_email_user') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_cancel_campaign_email_user', $wpneo_enable_cancel_campaign_email_user );
                //Validate campaign after goal reached
                $wpneo_enable_validate_campaign_email = sanitize_text_field( wpneo_post('wpneo_enable_validate_campaign_email') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_validate_campaign_email', $wpneo_enable_validate_campaign_email );

                $wpneo_enable_validate_campaign_email_admin = sanitize_text_field( wpneo_post('wpneo_enable_validate_campaign_email_admin') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_validate_campaign_email_admin', $wpneo_enable_validate_campaign_email_admin );

                $wpneo_enable_validate_campaign_email_user = sanitize_text_field( wpneo_post('wpneo_enable_validate_campaign_email_user') );
                wpneo_crowdfunding_update_option_checkbox( 'wpneo_enable_validate_campaign_email_user', $wpneo_enable_validate_campaign_email_user );
                //end fincrowd

                //Text, TextBox
                $wpneo_new_campaign_email_subject = sanitize_text_field(wpneo_post('wpneo_new_campaign_email_subject'));
                wpneo_crowdfunding_update_option_text( 'wpneo_new_campaign_email_subject', $wpneo_new_campaign_email_subject );

                $wpneo_campaign_email_template = sanitize_text_field(wpneo_post('wpneo_campaign_email_template'));
                wpneo_crowdfunding_update_option_text( 'wpneo_campaign_email_template', $wpneo_campaign_email_template );

                $wpneo_accept_campaign_email_subject = sanitize_text_field(wpneo_post('wpneo_accept_campaign_email_subject'));
                wpneo_crowdfunding_update_option_text( 'wpneo_accept_campaign_email_subject', $wpneo_accept_campaign_email_subject );

                $wpneo_accept_campaign_email_template = sanitize_text_field(wpneo_post('wpneo_accept_campaign_email_template'));
                wpneo_crowdfunding_update_option_text( 'wpneo_accept_campaign_email_template', $wpneo_accept_campaign_email_template );

                //start fincrowd save email template
                //cancel pledge to campaign
                $wpneo_cancel_campaign_email_subject = sanitize_text_field(wpneo_post('wpneo_cancel_campaign_email_subject'));
                wpneo_crowdfunding_update_option_text( 'wpneo_cancel_campaign_email_subject', $wpneo_cancel_campaign_email_subject );

                $wpneo_cancel_campaign_email_template = sanitize_text_field(wpneo_post('wpneo_cancel_campaign_email_template'));
                wpneo_crowdfunding_update_option_text( 'wpneo_cancel_campaign_email_template', $wpneo_cancel_campaign_email_template );
                //Validate campaign
                $wpneo_validate_campaign_email_subject = sanitize_text_field(wpneo_post('wpneo_validate_campaign_email_subject'));
                wpneo_crowdfunding_update_option_text( 'wpneo_validate_campaign_email_subject', $wpneo_validate_campaign_email_subject );

                $wpneo_validate_campaign_email_template = sanitize_text_field(wpneo_post('wpneo_validate_campaign_email_template'));
                wpneo_crowdfunding_update_option_text( 'wpneo_validate_campaign_email_template', $wpneo_validate_campaign_email_template );
                //end fincrowd

                $wpneo_smtp_form_email = sanitize_text_field(wpneo_post('wpneo_smtp_form_email'));
                wpneo_crowdfunding_update_option_text( 'wpneo_smtp_form_email', $wpneo_smtp_form_email );

                $wpneo_smtp_form_text = sanitize_text_field(wpneo_post('wpneo_smtp_form_text'));
                wpneo_crowdfunding_update_option_text( 'wpneo_smtp_form_text', $wpneo_smtp_form_text );

                $wpneo_smtp_host = sanitize_text_field(wpneo_post('wpneo_smtp_host'));
                wpneo_crowdfunding_update_option_text( 'wpneo_smtp_host', $wpneo_smtp_host );

                $wpneo_smtp_port = sanitize_text_field(wpneo_post('wpneo_smtp_port'));
                wpneo_crowdfunding_update_option_text( 'wpneo_smtp_port', $wpneo_smtp_port );

                $wpneo_smtp_username = sanitize_text_field(wpneo_post('wpneo_smtp_username'));
                wpneo_crowdfunding_update_option_text( 'wpneo_smtp_username', $wpneo_smtp_username );

                $wpneo_smtp_password = sanitize_text_field(wpneo_post('wpneo_smtp_password'));
                wpneo_crowdfunding_update_option_text( 'wpneo_smtp_password', $wpneo_smtp_password );

                $wpneo_user_registration_email_template = wpneo_post('wpneo_user_registration_email_template');
                wpneo_crowdfunding_update_option_text('wpneo_user_registration_email_template', $wpneo_user_registration_email_template);

                $wpneo_new_user_email_subject = sanitize_text_field(wpneo_post('wpneo_new_user_email_subject'));
                wpneo_crowdfunding_update_option_text('wpneo_new_user_email_subject', $wpneo_new_user_email_subject);

                $wpneo_enable_new_backed_email = sanitize_text_field(wpneo_post('wpneo_enable_new_backed_email'));
                wpneo_crowdfunding_update_option_checkbox('wpneo_enable_new_backed_email', $wpneo_enable_new_backed_email);

                $wpneo_enable_new_backed_email_admin = sanitize_text_field(wpneo_post('wpneo_enable_new_backed_email_admin'));
                wpneo_crowdfunding_update_option_checkbox('wpneo_enable_new_backed_email_admin', $wpneo_enable_new_backed_email_admin);

                $wpneo_enable_new_backed_email_user = sanitize_text_field(wpneo_post('wpneo_enable_new_backed_email_user'));
                wpneo_crowdfunding_update_option_checkbox('wpneo_enable_new_backed_email_user', $wpneo_enable_new_backed_email_user);

                $wpneo_new_backer_email_subject = sanitize_text_field(wpneo_post('wpneo_new_backer_email_subject'));
                wpneo_crowdfunding_update_option_text('wpneo_new_backer_email_subject', $wpneo_new_backer_email_subject);

                $wpneo_backer_email_template = wpneo_post('wpneo_backer_email_template');
                wpneo_crowdfunding_update_option_text('wpneo_backer_email_template', $wpneo_backer_email_template);
            }
        }


        /**
         * @param $user_id
         *
         * Send Email Notification To Newlty Created Account
         */
        public function send_email_to_new_user($user_id){
            if (get_option('wpneo_enable_new_user_registration_email') == 'true') {

                $email          = array();
                $user           = get_userdata($user_id);
                $dislay_name    = $user->display_name;
                if( 'true' == get_option( "wpneo_enable_new_user_registration_email_user" ) ){
                    $email[]        = $user->user_email;
                }
                if( 'true' == get_option( "wpneo_enable_new_user_registration_email_admin" ) ){
                    $admin_email    = get_option( 'admin_email' );
                    $email[]        = $admin_email;
                }

                $hash           = md5(microtime() . rand(111111, 999999));
                add_user_meta( $user_id, 'activate_code', $hash );
                $activate_link  = home_url('/') . 'user_activate?id=' . $user_id . '&key=' . $hash;
                $shortcode      = array('[user_name]', '[user_activate_link]', '[site_title]');
                $replace_str    = array($dislay_name, $activate_link, get_option('blogname'));
                $str            = wp_unslash(get_option('wpneo_user_registration_email_template'));
                $email_str      = str_replace($shortcode, $replace_str, $str);
                $subject        = str_replace($shortcode, $replace_str, get_option('wpneo_new_user_email_subject'));
                $headers        = array('Content-Type: text/html; charset=UTF-8'); //Set Headers content type to HTML

                //Send email now using wp_email();
                if(!empty($email)){
                    wp_mail( $email, $subject, $email_str, $headers );
                }
            }
        }

        /**
         * @param $order_id
         *
         * Send Email Notification Right After Backed Campaign
         */
        function wpneo_crowdfunding_custom_order_process($order_id){

            if ( get_option('wpneo_enable_new_backed_email') == 'true' ) {

                global $wpdb;
                $order          = new WC_Order($order_id);
                $line_items     = $order->get_items('line_item');
                $ids            = array();
                foreach ($line_items as $item_id => $item) {
                    $product_id = $wpdb->get_var("select meta_value from {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = {$item_id} AND meta_key = '_product_id'");
                    $ids[]      = $product_id;
                }
                $product        = wc_get_product($product_id);

                if ($product->product_type === 'crowdfunding') {
                    $email          = array();
                    // //start fincrowd
                    //$author         = get_userdata($product->post->post_author);
                    $post_author_id = get_post_field( 'post_author', $product_id );
                    $author         = get_userdata($post_author_id);
                    //end fincrowd
                    $dislay_name    = $author->display_name;
                    if( 'true' == get_option( "wpneo_enable_new_backed_email_user" ) ){
                        $email[]    = $author->user_email;
                    }
                    if( 'true' == get_option( "wpneo_enable_new_backed_email_admin" ) ){
                        $admin_email= get_option( 'admin_email' );
                        $email[]    = $admin_email;
                    }
                    //start fincrowd get logged in user Adress TOO !
                    $current_user = wp_get_current_user();
                    $email[]    = $current_user->user_email;
                    //end fincrowd

                    $total_amount   = get_woocommerce_currency_symbol() . $order->get_total();
                    //$campaign_title  = $product->post->post_title;
                    $campaign_title  = get_post_field( 'post_title', $product_id );
                    $shortcode      = array('[user_name]', '[site_title]', '[total_amount]', '[campaign_title]');
                    $replace_str    = array($dislay_name, get_option('blogname'), $total_amount, $campaign_title);
                    $str            = wp_unslash(get_option('wpneo_backer_email_template'));
                    $email_str      = str_replace($shortcode, $replace_str, $str);
                    $subject        = str_replace($shortcode, $replace_str, get_option('wpneo_new_backer_email_subject'));
                    $headers        = array('Content-Type: text/html; charset=UTF-8'); // Set Headers content type to HTML

                    //Send email now using wp_email();
                    if(!empty( $email )){
                        wp_mail( $email, $subject, $email_str, $headers );
                    }
                }
            }
        }



        // Campaign Submit Admin Email Action
        // Campaign Submit Campaign Author Email Action
        public function wpneo_campaign_submit_process( $post_id ){
            if ( get_option('wpneo_enable_new_campaign_email') == 'true' ) {
                $post_type      = get_post_type($post_id);
                if ( "product" != $post_type ){ return; }
                if ( wp_is_post_revision( $post_id ) ){ return; }
                if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){ return; }

                $email          = array();
                $product        = wc_get_product( $post_id );
            	$author         = get_userdata( $product->post->post_author );
                $dislay_name    = $author->display_name;
                if( 'true' == get_option( "wpneo_enable_new_campaign_email_user" ) ){
                    $email[]    = $author->user_email;
                }
                if( 'true' == get_option( "wpneo_enable_new_campaign_email_admin" ) ){
                    $admin_email= get_option( 'admin_email' );
                    $email[]    = $admin_email;
                }

                $campaign_title  = $product->post->post_title;
                $shortcode      = array( '[user_name]', '[campaign_title]' );
                $replace_str    = array( $dislay_name, $campaign_title );
                $str            = wp_unslash( get_option( 'wpneo_campaign_email_template' ) );
                $email_str      = str_replace( $shortcode, $replace_str, $str );
                $subject        = str_replace( $shortcode, $replace_str, get_option( 'wpneo_new_campaign_email_subject' ) );
                $headers        = array('Content-Type: text/html; charset=UTF-8'); // Set Headers content type to HTML

                // Send To Author Email
                if(!empty($email)){
                    wp_mail( $email, $subject, $email_str, $headers );
                }
            }
        }


        // Campaign Published Campaign Author
        public function wpneo_campaign_published_process( $ID, $post ) {
            if ( get_option( 'wpneo_enable_accept_campaign_email' ) == 'true' ) {
                $post_id = $post->ID;

                $product = wc_get_product( $post_id );
                if (!$product->is_type('crowdfunding')){ return; }

                $email          = array();
                $author         = get_userdata( $product->post->post_author );
                $dislay_name    = $author->display_name;
                if( 'true' == get_option( "wpneo_enable_accept_campaign_email_user" ) ){
                    $email[]    = $author->user_email;
                }
                if( 'true' == get_option( "wpneo_enable_accept_campaign_email_admin" ) ){
                    $admin_email= get_option( 'admin_email' );
                    $email[]    = $admin_email;
                }

                $campaign_title  = $product->post->post_title;
                $shortcode      = array( '[user_name]', '[campaign_title]' );
                $replace_str    = array( $dislay_name, $campaign_title );
                $str            = wp_unslash( get_option( 'wpneo_accept_campaign_email_template' ) );
                $email_str      = str_replace( $shortcode, $replace_str, $str );
                $subject        = str_replace( $shortcode, $replace_str, get_option( 'wpneo_accept_campaign_email_subject' ) );
                $headers        = array('Content-Type: text/html; charset=UTF-8'); // Set Headers content type to HTML

                // Send To Author Email
                if(!empty( $email )){
                    wp_mail( $email, $subject, $email_str, $headers );
                }
            }
        }

        /**
         * @param $order_id
         * FINCROWD FCT
         * Send Email after cancel order
         */
        function wpneo_fi_send_email_cancel_order($order_id){

            if ( get_option( 'wpneo_enable_cancel_campaign_email' ) == 'true' ) {

                global $wpdb;
                $order          = new WC_Order($order_id);
                $line_items     = $order->get_items('line_item');
                $ids            = array();
                foreach ($line_items as $item_id => $item) {
                    $product_id = $wpdb->get_var("select meta_value from {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = {$item_id} AND meta_key = '_product_id'");
                    $ids[]      = $product_id;
                }
                $product        = wc_get_product($product_id);

                if ($product->product_type === 'crowdfunding') {
                    $email          = array();
                    //$author         = get_userdata($product->post->post_author);
                    $post_author_id = get_post_field( 'post_author', $product_id );
                    $author         = get_userdata($post_author_id);

                    if( 'true' == get_option( "wpneo_enable_cancel_campaign_email_user" ) ){
                        $email[]    = $author->user_email;
                    }
                    if( 'true' == get_option( "wpneo_enable_cancel_campaign_email_admin" ) ){
                        $admin_email= get_option( 'admin_email' );
                        $email[]    = $admin_email;
                    }
                    //start fincrowd get logged in user Adress TOO !
                    $current_user = wp_get_current_user();
                    $email[]    = $current_user->user_email;
                    //end fincrowd

                    $campaign_title  = $product->post->post_title;
                    $shortcode      = array( '[user_name]', '[campaign_title]' );
                    $replace_str    = array( $dislay_name, $campaign_title );
                    $str            = wp_unslash( get_option( 'wpneo_cancel_campaign_email_template' ) );
                    $email_str      = str_replace( $shortcode, $replace_str, $str );
                    $subject        = str_replace( $shortcode, $replace_str, get_option( 'wpneo_cancel_campaign_email_subject' ) );
                    $headers        = array('Content-Type: text/html; charset=UTF-8'); // Set Headers content type to HTML

                    //Send email now using wp_email();
                    if(!empty( $email )){
                        wp_mail( $email, $subject, $email_str.'CANCEL TODO', $headers );
                    }
                }
            }
        }

        /**
         * @param $campaign_id
         * FINCROWD FCT
         * Send Email after validate campaign
         */
        function wpneo_fi_send_email_validate_campaign($campaign_id){
          if ( get_option( 'wpneo_enable_validate_campaign_email' ) == 'true' ) {

              global $wpdb;

              $product        = wc_get_product($product_id);

              if ($product->product_type === 'crowdfunding') {
                  $email          = array();
                  //$author         = get_userdata($product->post->post_author);
                  $post_author_id = get_post_field( 'post_author', $product_id );
                  $author         = get_userdata($post_author_id);

                  if( 'true' == get_option( "wpneo_enable_validate_campaign_email_user" ) ){
                      $email[]    = $author->user_email;
                  }
                  if( 'true' == get_option( "wpneo_enable_validate_campaign_email_admin" ) ){
                      $admin_email= get_option( 'admin_email' );
                      $email[]    = $admin_email;
                  }
                  //start fincrowd get logged in user Adress TOO !
                  $current_user = wp_get_current_user();
                  $email[]    = $current_user->user_email;
                  //end fincrowd

                  $campaign_title  = $product->post->post_title;
                  $shortcode      = array( '[user_name]', '[campaign_title]' );
                  $replace_str    = array( $dislay_name, $campaign_title );
                  $str            = wp_unslash( get_option( 'wpneo_validate_campaign_email_template' ) );
                  $email_str      = str_replace( $shortcode, $replace_str, $str );
                  $subject        = str_replace( $shortcode, $replace_str, get_option( 'wpneo_validate_campaign_email_subject' ) );
                  $headers        = array('Content-Type: text/html; charset=UTF-8'); // Set Headers content type to HTML

                  //Send email now using wp_email();
                  if(!empty( $email )){
                      wp_mail( $email, $subject, $email_str.'validate TODO', $headers );
                  }
              }
          }

        }


    }
}
Wpneo_Crowdfunding_Email::instance();
