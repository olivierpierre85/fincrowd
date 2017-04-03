<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('WPNEO_WC_Admin_Dashboard')) {

    class WPNEO_WC_Admin_Dashboard{

        protected static $_instance;
        public static function instance(){
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct(){
            add_action( 'wp_dashboard_setup',                   array( $this, 'init' ) );
            add_filter( 'manage_edit-product_columns',          array($this, 'wpneo_crowdfunding_order_custom_column'));
            add_action( 'manage_product_posts_custom_column' ,  array($this, 'wpneo_woocommerce_show_campaign_data_in_product_column'), 10, 2 );
            add_action( 'add_meta_boxes',                       array($this, 'wpneo_crowdfunding_register_meta_boxes') );

            add_action( 'add_meta_boxes',                       array($this, 'wpneo_crowdfunding_selected_reward_meta_box') );
        }

        public function init(){
            wp_add_dashboard_widget('wpneo_crowdfunding_overview', __('CrowdFunding Overview', 'wp-crowdfunding'), array($this, 'wpneo_crowdfunding_overview'));
        }

        /**
        * Get this info to wordpress dashboard
        */
        public function wpneo_crowdfunding_overview(){
            
            global $wpdb;
            $totalCampaigns = $total_orders = $on_hole_total_orders = $total_campaign_orders = 0;

            $query_args = array(
                'post_type' => 'product',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_type',
                        'field'    => 'slug',
                        'terms'    => 'crowdfunding',
                    ),
                ),
            );

            $campaigns = new WP_Query($query_args);

            $campaign_ids = array();
            if ($campaigns->have_posts()){
                global $post;
                while ($campaigns->have_posts()){ 
                    $campaigns->the_post();
                    $campaign_ids[] = $post->ID;
                }
            }
            
            if (count($campaign_ids) > 0) {
                $campaign_ids_string     = implode(',', $campaign_ids);
                $wp_sql                 = $this->totalOrdersSalesAmount($campaign_ids_string);
                $wp_sql_on_hold         = $this->totalOrdersSalesAmount($campaign_ids_string, 'wc-on-hold');
                $total_campaign_orders   = $wp_sql->total_sales_amount;
                $total_orders           = $wp_sql->total_orders ? $wp_sql->total_orders : 0;
                $totalCampaigns          = $campaigns->post_count ;
                $on_hole_total_orders   = $wp_sql_on_hold->total_orders;
            }

            $html = '';
            $html .= '<ul class="wpneo_d_status_list">';
                $html .= '<li><span> <strong>'.$totalCampaigns.' </strong> '.__( "Total Campaign","wp-crowdfunding" ).'</span></li>';
                $html .= '<li><span><strong>'.$total_orders .' </strong> '.__( "Completed Orders","wp-crowdfunding" ).'</span></li>';
                $html .= '<li><span><strong>'. $on_hole_total_orders.' </strong> '.__( "On-Hold Orders","wp-crowdfunding" ).'</span></li>';
                $html .= '<li><span><strong>'.get_woocommerce_currency_symbol().$total_campaign_orders.' </strong> '.__( "Total Donation Raised","wp-crowdfunding" ).'</span></li>';
            $html .= '</ul>';
            echo $html;
        }

        /**
         * @param $in_campaign_ids_string
         * @param string $order_status
         * @return array|null|object|void
         *
         * Get total order and amount by campaigns/products ids
         */
        public function totalOrdersSalesAmount($in_campaign_ids_string, $order_status = 'wc-completed'){
            
            global $wpdb;
            $query ="SELECT 
                        SUM(ltoim.meta_value) as total_sales_amount, COUNT(ltoim.meta_value) as total_orders 
                    FROM 
                        {$wpdb->prefix}woocommerce_order_itemmeta woim 
			        LEFT JOIN 
                        {$wpdb->prefix}woocommerce_order_items oi ON woim.order_item_id = oi.order_item_id
			        LEFT JOIN 
                        {$wpdb->prefix}posts wpposts ON order_id = wpposts.ID
			        LEFT JOIN 
                        {$wpdb->prefix}woocommerce_order_itemmeta ltoim ON ltoim.order_item_id = oi.order_item_id AND ltoim.meta_key = '_line_total'
			        WHERE 
                        woim.meta_key = '_product_id' AND woim.meta_value IN ({$in_campaign_ids_string}) AND wpposts.post_status = '{$order_status}';";

            $wp_sql = $wpdb->get_row($query);
            return $wp_sql;
        }


        /**
         * @param $columns
         * @return mixed
         *
         * Campaign owner column
         */
        public function wpneo_crowdfunding_order_custom_column( $columns ) {
            $date = $columns['date'];
            unset($columns['date']);
            $columns['campaign_owner'] = __('Owner', 'wp-crowdfunding');
            $columns['date'] = $date;
            return $columns;
        }

        /**
         * @param $column
         * @param $post_id
         */

        function wpneo_woocommerce_show_campaign_data_in_product_column( $column, $post_id ) {
            switch ( $column ) {
                case 'campaign_owner':
                    $post =  get_post($post_id);
                    $user = get_userdata($post->post_author);
                    echo $user->display_name;
                    break;
            }
        }

        /**
         * Register meta box(es).
         */
        function wpneo_crowdfunding_register_meta_boxes() {
            add_meta_box( 'meta-box-id', __( 'Campaign Summery', 'wp-crowdfunding' ), array($this, 'wpneo_crowdfunding_metabox_display_callback'), 'product', 'side', 'high' );
        }

        /**
         * Meta box display callback.
         *
         * @param WP_Post $post Current post object.
         */
        function wpneo_crowdfunding_metabox_display_callback( $post ) {
            include WPNEO_CROWDFUNDING_DIR_PATH.'admin/view/product_metabox_campaign_info.php';
        }

        /**
         * Reward meta box in shop order page right side
         */

        function wpneo_crowdfunding_selected_reward_meta_box(){
            global $post;
            //Check is reward selected
            $r = get_post_meta($post->ID, 'wpneo_selected_reward', true);
            if ( ! empty($r) && is_array($r) ) {
                add_meta_box('meta-box-selected-reward', __('Selected Reward', 'wp-crowdfunding'), array($this, 'wpneo_crowdfunding_selected_reward_meta_box_display_callback'), 'shop_order', 'side', 'high');
            }
        }

        function wpneo_crowdfunding_selected_reward_meta_box_display_callback(){
            include WPNEO_CROWDFUNDING_DIR_PATH.'admin/view/order_selected_reward_meta_box.php';
        }

    }
}
WPNEO_WC_Admin_Dashboard::instance();