<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('Wpneo_Crowdfunding_Frontend_Dashboard')) {

    class Wpneo_Crowdfunding_Frontend_Dashboard{

        /**
         * @var null
         *
         * Instance of this class
         */
        protected static $_instance = null;

        /**
         * @return null|Wpneo_Crowdfunding_Frontend_Dashboard
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Wpneo_Crowdfunding_Frontend_Dashboard constructor.
         *
         * @hook
         */

        public function __construct() {
            add_action( 'wp_ajax_wpneo_dashboard_form',      array($this, 'wpneo_dashboard_form_save'));
            add_action( 'wp_ajax_wpneo_profile_form',        array($this, 'wpneo_profile_form_save'));
            add_action( 'wp_ajax_wpneo_contact_form',        array($this, 'wpneo_contact_form_save'));
            add_action( 'wp_ajax_wpneo_password_form',       array($this, 'wpneo_password_form_save'));
            add_action( 'wp_ajax_wpneo_update_status_save',  array($this, 'wpneo_update_status_save'));

            //Fincrowd
            add_action( 'wp_ajax_wpneo_fi_cancel_order',          array($this, 'wpneo_fi_cancel_order'));
            add_action( 'wp_ajax_wpneo_fi_compute_interest',      array($this, 'wpneo_fi_compute_interest'));
            add_action( 'wp_ajax_wpneo_fi_validate_campaign',      array($this, 'wpneo_fi_validate_campaign'));
            add_action( 'wp_ajax_wpneo_fi_cancel_campaign',      array($this, 'wpneo_fi_cancel_campaign'));
            add_action( 'wp_ajax_wpneo_fi_reminder_mail',      array($this, 'wpneo_fi_reminder_mail'));

        }
        //Fincrowd
        //Validate campaign
        public function wpneo_fi_validate_campaign() {
          //TODO fincrowd admin check ?
          $campaign_id        = sanitize_text_field($_POST['campaign_id']);
          $result = true;//TODOwpneo_crowdfunding_update_post_meta_text($campaign_id, 'wpneo_fi_campaign_validated', true);
          if ($result){
            //FINCROWD ALL THE Things to do to update
            //Generate PDFs
            do_action('wpneo_fi_docusign_validate_campaign',$campaign_id );
            //Send mails
            do_action('wpneo_fi_after_validate_campaign',$campaign_id );
          }
          if ($result){
            //FINCROWD
              die(json_encode(array('success'=> 1, 'message' => __('Campagne validée !', 'wp-crowdfunding'))));
          }else{
              die(json_encode(array('success'=> 0, 'message' => __('Error updating, please try again', 'wp-crowdfunding'))));
          }
        }

        public function wpneo_fi_cancel_campaign() {
          //TODO fincrowd admin check ?
          $campaign_id        = sanitize_text_field($_POST['campaign_id']);
          //$result = wpneo_crowdfunding_update_post_meta_text($campaign_id, 'wpneo_fi_campaign_canceled', true);
          $post = array( 'ID' => $campaign_id, 'post_status' => 'draft' );
          $result = wp_update_post($post);
          if ($result){

            //Send mails
            do_action('wpneo_fi_after_cancel_campaign',$campaign_id );
          }
          if ($result){
            //FINCROWD
              die(json_encode(array('success'=> 1, 'message' => __('Campagne annulée.', 'wp-crowdfunding'))));
          }else{
              die(json_encode(array('success'=> 0, 'message' => __('Error updating, please try again', 'wp-crowdfunding'))));
          }
        }

        public function wpneo_fi_reminder_mail() {
          //TODO fincrowd admin check ?
          $campaign_id        = sanitize_text_field($_POST['campaign_id']);

          //Send reminder mail
          try {
            do_action('wpneo_fi_after_reminder_mail',$campaign_id );
            die(json_encode(array('success'=> 1, 'message' => __('Mail de rappel envoyé', 'wp-crowdfunding'))));
          } catch (Exception $e) {
            die(json_encode(array('success'=> 0, 'message' => __('Erreur dans l\'envoir du mail', 'wp-crowdfunding'))));
          }
        }


        //compute interest frontend
        public function wpneo_fi_compute_interest() {
          $campaign_id        = sanitize_text_field($_POST['campaign_id']);
          $total       = sanitize_text_field($_POST['total']);

          //$product = new WC_Product( $cart[key($cart)]['product_id']);//take first product (Normally always One and only one)


          $duration      = get_post_meta( $campaign_id, 'wpneo_fi_loan_duration', true );

          $interest                 = get_post_meta( $campaign_id, 'wpneo_fi_interest_rate', true );
          $monthly_payment          = wpneo_fi_compute_monthly_payment( $total, $interest, $duration );
          $total_interest           = round(($duration * $monthly_payment  ) - $total,2);

          // $insurance = get_post_meta( $campaign_id, 'wpneo_fi_loan_insurance', true );
          // if($insurance == "true"){
          //   $interest_insurance      = get_post_meta( $campaign_id, 'wpneo_fi_interest_rate_insurance', true );
          //   $monthly_payment_insurance  = wpneo_fi_compute_monthly_payment( $total, $interest_insurance, $duration );
          //   $total_interest_insurance = round(($duration * $monthly_payment_insurance  ) - $total,2);
          //
          //   die(__('Intérêts totaux :'.$total_interest.' ( '.$total_interest_insurance.' avec la garantie)', 'wp-crowdfunding'));
          // } else {

          die(__('Intérêts Bruts totaux : '.$total_interest. ' €', 'wp-crowdfunding'));

          //}


          //TODO error handling
          //die(json_encode(array('success'=> 1, 'message' => __('Itest', 'wp-crowdfunding'), 'redirect' => $redirect)));
        }

        // Delete of Order by client
        public function wpneo_fi_cancel_order() {
          global $woocommerce;
          $redirect = get_permalink(get_option('wpneo_crowdfunding_dashboard_page_id')).'?page_type=backed_campaigns';

          $order_id        = sanitize_text_field($_POST['order_id']);

          //Use this if not delete but cancel
          $order = new WC_Order($order_id);
          $result = $order->update_status('Cancelled', '');
          //$result = wc_delete_order_item( $order_id );

          //mail to cancel
          do_action('wpneo_fi_after_cancel_order',$order_id);

          if ($result){
              die(json_encode(array('success'=> 1, 'message' => __('Successfully updated', 'wp-crowdfunding'), 'redirect' => $redirect)));
          }else{
              die(json_encode(array('success'=> 0, 'message' => __('Error updating, please try again', 'wp-crowdfunding'), 'redirect' => $redirect)));
          }
        }

        // General Form Action for Dashboard
        public function wpneo_dashboard_form_save() {

            if (
                ! isset( $_POST['wpneo_crowdfunding_dashboard_nonce_field'] )
                || ! wp_verify_nonce( $_POST['wpneo_crowdfunding_dashboard_nonce_field'], 'wpneo_crowdfunding_dashboard_form_action' )
            ) {
                die(json_encode(array('success'=> 0, 'message' => __('Sorry, your nonce did not verify.', 'wp-crowdfunding'))));
            }

            $id             = get_current_user_id();
            $email          = ( $_POST['email'] ) ? sanitize_email($_POST['email']) : "";
            $firstname      = ( $_POST['firstname'] ) ? sanitize_text_field($_POST['firstname']) : "";
            $lastname       = ( $_POST['lastname'] ) ? sanitize_text_field($_POST['lastname']) : "";
            $website        = ( $_POST['website'] ) ? sanitize_url($_POST['website']) : "";
            //$description    = ( $_POST['description'] ) ? sanitize_text_field($_POST['description'] ): "";

            $userdata = array(
                'ID'                => $id,
                'user_email'        => $email,
                'first_name'        => $firstname,
                'last_name'         => $lastname,
                'user_url'          => $website,
                //'description'       => $description,
            );
            do_action('wpneo_crowdfunding_after_save_dashboard', $id);//fincrowd add id

            $update = wp_update_user( $userdata );
            $redirect = get_permalink(get_option('wpneo_crowdfunding_dashboard_page_id')).'?page_type=dashboard';
            if ($update){
                die(json_encode(array('success'=> 1, 'message' => __('Successfully updated', 'wp-crowdfunding'), 'redirect' => $redirect)));
            }else{
                die(json_encode(array('success'=> 0, 'message' => __('Error updating, please try again', 'wp-crowdfunding'), 'redirect' => $redirect)));
            }
        }

        // Profile Form Action for Dashboard
        public function wpneo_profile_form_save(){

            if (
                ! isset( $_POST['wpneo_crowdfunding_dashboard_nonce_field'] )
                || ! wp_verify_nonce( $_POST['wpneo_crowdfunding_dashboard_nonce_field'], 'wpneo_crowdfunding_dashboard_form_action' )
            ) {
                die(json_encode(array('success'=> 0, 'message' => __('Sorry, your nonce did not verify.', 'wp-crowdfunding'))));
            }

            $user_id             = get_current_user_id();
            $profile_name         = ( $_POST['profile_name'] ) ? sanitize_text_field($_POST['profile_name']) : "";
            $profile_website      = ( $_POST['profile_website'] ) ? sanitize_url($_POST['profile_website']) : "";
            $profile_about        = ( $_POST['profile_about'] ) ? implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['profile_about']))) : "";
            $profile_portfolio    = ( $_POST['profile_portfolio'] ) ? sanitize_text_field($_POST['profile_portfolio']) : "";
            $profile_bio          = ( $_POST['profile_bio'] ) ? implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['profile_bio']))) : "";
            $profile_mobile1      = ( $_POST['profile_mobile1'] ) ? sanitize_text_field($_POST['profile_mobile1']) : "";
            $profile_email1       = ( $_POST['profile_email1'] ) ? sanitize_email($_POST['profile_email1']) : "";
            $profile_fax          = ( $_POST['profile_fax'] ) ? sanitize_text_field($_POST['profile_fax']) : "";
            $profile_address      = ( $_POST['profile_address'] ) ? sanitize_text_field($_POST['profile_address']) : "";
            $profile_facebook     = ( $_POST['profile_facebook'] ) ? sanitize_text_field($_POST['profile_facebook']) : "";
            $profile_twitter      = ( $_POST['profile_twitter'] ) ? sanitize_text_field($_POST['profile_twitter']) : "";
            $profile_plus         = ( $_POST['profile_plus'] ) ? sanitize_text_field($_POST['profile_plus']) : "";
            $profile_linkedin     = ( $_POST['profile_linkedin'] ) ? sanitize_text_field($_POST['profile_linkedin']) : "";
            $profile_pinterest    = ( $_POST['profile_pinterest'] ) ? sanitize_text_field($_POST['profile_pinterest']) : "";
            $profile_image_id     = ( $_POST['profile_image_id'] ) ? sanitize_text_field($_POST['profile_image_id']) : "";

            //add_user_meta
            update_user_meta( $user_id,'profile_name',      $profile_name );
            update_user_meta( $user_id,'profile_website',   $profile_website );
            update_user_meta( $user_id,'profile_about',     $profile_about );
            update_user_meta( $user_id,'profile_portfolio', $profile_portfolio );
            update_user_meta( $user_id,'profile_bio',       $profile_bio );
            update_user_meta( $user_id,'profile_mobile1',   $profile_mobile1 );
            update_user_meta( $user_id,'profile_email1',    $profile_email1 );
            update_user_meta( $user_id,'profile_fax',       $profile_fax );
            update_user_meta( $user_id,'profile_address',   $profile_address );
            update_user_meta( $user_id,'profile_facebook',  $profile_facebook );
            update_user_meta( $user_id,'profile_twitter',   $profile_twitter );
            update_user_meta( $user_id,'profile_plus',      $profile_plus );
            update_user_meta( $user_id,'profile_linkedin',  $profile_linkedin );
            update_user_meta( $user_id,'profile_pinterest', $profile_pinterest );
            update_user_meta( $user_id,'profile_image_id',  intval($profile_image_id) );

            do_action('wpneo_crowdfunding_after_save_profile');
            $redirect = get_permalink(get_option('wpneo_crowdfunding_dashboard_page_id')).'?page_type=profile';

            //die(json_encode(array('success'=> 1, 'message' => __('Successfully updated'), 'redirect' => $redirect)));
            die(json_encode(array('success'=> 1, 'message' => __('Successfully updated'))));
        }

        // Profile Form Action for Dashboard
        public function wpneo_contact_form_save(){

            if (
                ! isset( $_POST['wpneo_crowdfunding_dashboard_nonce_field'] )
                || ! wp_verify_nonce( $_POST['wpneo_crowdfunding_dashboard_nonce_field'], 'wpneo_crowdfunding_dashboard_form_action' )
            ) {
                die(json_encode(array('success'=> 0, 'message' => __('Sorry, your nonce did not verify.', 'wp-crowdfunding'))));
            }

            $user_id                 = get_current_user_id();
            // Shipping Address
            $shipping_first_name     = ( $_POST['shipping_first_name'] ) ? sanitize_text_field($_POST['shipping_first_name']) : "";
            $shipping_last_name      = ( $_POST['shipping_last_name'] ) ? sanitize_text_field($_POST['shipping_last_name']) : "";
            $shipping_company        = ( $_POST['shipping_company'] ) ? sanitize_text_field($_POST['shipping_company']) : "";
            $shipping_address_1      = ( $_POST['shipping_address_1'] ) ? sanitize_text_field($_POST['shipping_address_1']) : "";
            $shipping_address_2      = ( $_POST['shipping_address_2'] ) ? sanitize_text_field($_POST['shipping_address_2']) : "";
            $shipping_city           = ( $_POST['shipping_city'] ) ? sanitize_text_field($_POST['shipping_city']) : "";
            $shipping_postcode       = ( $_POST['shipping_postcode'] ) ? intval(sanitize_text_field($_POST['shipping_postcode'])) : "";
            $shipping_country        = ( $_POST['shipping_country'] ) ? sanitize_text_field($_POST['shipping_country']) : "";
            $shipping_state          = ( $_POST['shipping_state'] ) ? sanitize_text_field($_POST['shipping_state']) : "";
            // Billing Address
            $billing_first_name     =  ( $_POST['billing_first_name'] ) ? sanitize_text_field($_POST['billing_first_name']) : "";
            $billing_last_name      =  ( $_POST['billing_last_name'] ) ? sanitize_text_field($_POST['billing_last_name']) : "";
            $billing_company        =  ( $_POST['billing_company'] ) ? sanitize_text_field($_POST['billing_company']) : "";
            $billing_address_1      =  ( $_POST['billing_address_1'] ) ? sanitize_text_field($_POST['billing_address_1']) : "";
            $billing_address_2      =  ( $_POST['billing_address_2'] ) ? sanitize_text_field($_POST['billing_address_2']) : "";
            $billing_city           =  ( $_POST['billing_city'] ) ? sanitize_text_field($_POST['billing_city']) : "";
            $billing_postcode       =  ( $_POST['billing_postcode'] ) ? intval(sanitize_text_field($_POST['billing_postcode'])) : "";
            $billing_country        =  ( $_POST['billing_country'] ) ? sanitize_text_field($_POST['billing_country']) : "";
            $billing_state          =  ( $_POST['billing_state'] ) ? sanitize_text_field($_POST['billing_state']) : "";
            $billing_phone          =  ( $_POST['billing_phone'] ) ? sanitize_text_field($_POST['billing_phone']) : "";
            $billing_email          =  ( $_POST['billing_email'] ) ? sanitize_email($_POST['billing_email']) : "";


            update_user_meta($user_id,'shipping_first_name', $shipping_first_name);
            update_user_meta($user_id,'shipping_last_name', $shipping_last_name);
            update_user_meta($user_id,'shipping_company', $shipping_company);
            update_user_meta($user_id,'shipping_address_1', $shipping_address_1);
            update_user_meta($user_id,'shipping_address_2', $shipping_address_2);
            update_user_meta($user_id,'shipping_city', $shipping_city);
            update_user_meta($user_id,'shipping_postcode', $shipping_postcode);
            update_user_meta($user_id,'shipping_country', $shipping_country);
            update_user_meta($user_id,'shipping_state', $shipping_state);

            //add_user_meta ( Billing )
            update_user_meta($user_id,'billing_first_name', $billing_first_name);
            update_user_meta($user_id,'billing_last_name', $billing_last_name);
            update_user_meta($user_id,'billing_company', $billing_company);
            update_user_meta($user_id,'billing_address_1', $billing_address_1);
            update_user_meta($user_id,'billing_address_2', $billing_address_2);
            update_user_meta($user_id,'billing_city', $billing_city);
            update_user_meta($user_id,'billing_postcode', $billing_postcode);
            update_user_meta($user_id,'billing_country', $billing_country);
            update_user_meta($user_id,'billing_state', $billing_state);
            update_user_meta($user_id,'billing_phone', $billing_phone);

            update_user_meta($user_id,'billing_email', $billing_email);

            $redirect = get_permalink(get_option('wpneo_crowdfunding_dashboard_page_id')).'?page_type=contact';

            die(json_encode(array('success'=> 1, 'message' => __('Successfully updated'), 'redirect' => $redirect)));
        }

        // Password Form Action for Dashboard
        public function wpneo_password_form_save() {

            if ( ! isset( $_POST['wpneo_crowdfunding_dashboard_nonce_field'] ) || ! wp_verify_nonce( $_POST['wpneo_crowdfunding_dashboard_nonce_field'], 'wpneo_crowdfunding_dashboard_form_action' )) {
                die(json_encode(array('success'=> 0, 'message' => __('Sorry, your nonce did not verify.', 'wp-crowdfunding'))));
            }

            $id                 = get_current_user_id();
            $password           = sanitize_text_field($_POST['password']);
            $new_password       = sanitize_text_field($_POST['new-password']);

            $retype_password    = sanitize_text_field($_POST['retype-password']);

            $redirect = get_permalink(get_option('wpneo_crowdfunding_dashboard_page_id')).'?page_type=password';
            if( isset($_POST['password']) && isset($_POST['new-password']) && isset($_POST['retype-password']) ){

                if( ( $new_password == $retype_password ) && ( $retype_password != "" ) ){
                    $user = get_user_by( 'id', $id );
                    if ( $user && wp_check_password( $password, $user->data->user_pass, $user->ID) ){
                        wp_set_password( $new_password, $id );
                        die(json_encode(array('success'=> 1, 'message' => __('Password successfully updated'), 'redirect' => $redirect)));
                    }
                }
                die(json_encode(array('success'=> 0, 'message' => __('Error updating, please try again'), 'redirect' => $redirect)));
            }
        }

        public function wpneo_update_status_save(){
            if ( ! empty($_POST['wpneo_prject_update_title_field'])){

                $post_id                            = $_POST['postid'];
                $wpneo_prject_update_title_field    = $_POST['wpneo_prject_update_title_field'];
                $wpneo_prject_update_date_field     = $_POST['wpneo_prject_update_date_field'];
                $wpneo_prject_update_details_field  = $_POST['wpneo_prject_update_details_field'];
                $total_update_field                 = count($wpneo_prject_update_title_field);

                $data = array();
                for ($i=0; $i<$total_update_field; $i++){
                    if (! empty($wpneo_prject_update_title_field[$i])) {
                        $data[] = array(
                            'date'      => sanitize_text_field( $wpneo_prject_update_date_field[$i] ),
                            'title'     => sanitize_text_field( $wpneo_prject_update_title_field[$i] ),
                            'details'   => esc_html( $wpneo_prject_update_details_field[$i] )
                        );
                    }
                }
                $data_json = json_encode($data);
                update_post_meta( $post_id, 'wpneo_campaign_updates', $data_json );

                $redirect = get_permalink(get_option('wpneo_crowdfunding_dashboard_page_id')).'?page_type=update&postid='.$post_id;
                die(json_encode(array('success'=> 1, 'message' => __('Successfully updated'), 'redirect' => $redirect)));
            }
        }

    }
}
//Call base class
Wpneo_Crowdfunding_Frontend_Dashboard::instance();
