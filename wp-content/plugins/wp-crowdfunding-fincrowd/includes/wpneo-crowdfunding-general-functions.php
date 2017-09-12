<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

//FINCROWD
// override core function of activation_failed
add_action( 'template_redirect', 'fincrowd_activate_user' );
function fincrowd_activate_user() {
    if ( is_page() && get_the_ID() == get_option('page_on_front') ) {
        $user_id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
        if ( $user_id ) {
            // get user meta activation hash field
            $code = get_user_meta( $user_id, 'activate_code', true );
            if ( $code == filter_input( INPUT_GET, 'key' ) ) {
                delete_user_meta( $user_id, 'activate_code' );
            }
        }
    }
}

if ( !function_exists('wp_authenticate') ) {
  function wp_authenticate($username, $password) {
      $username = sanitize_user($username);
      $password = trim($password);

      $user = apply_filters('authenticate', null, $username, $password);

      if ( $user == null ) {
          // TODO what should the error message be? (Or would these even happen?)
          // Only needed if all authentication handlers fail to return anything.
          $user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));
      } elseif ( property_exists($user, 'ID') ) {
        if(get_user_meta( $user->ID, 'activate_code', true ) == true){
          $user = new WP_Error('activation_failed', __('<strong>ERROR</strong>: Activez votre compte en cliquant sur le lien situé dans le mail d\'inscription.'));
        }
      }

      $ignore_codes = array('empty_username', 'empty_password');

      if (is_wp_error($user) && !in_array($user->get_error_code(), $ignore_codes) ) {
          do_action('wp_login_failed', $username);
      }

      return $user;
  }
}

//Show a table of interests
if (! function_exists('fi_interest_insurance_table')){
  function fi_interest_insurance_table($order_id = null) {

      $table = "";

      $total = WC()->cart->total;
      $cart = WC()->cart->get_cart();

      if($order_id != null) {
        $order = wc_get_order( $order_id );
        $cart = $order->get_items();
        $total = $cart[key($cart)]['line_total'];
      }

      $interest                 = get_post_meta( $cart[key($cart)]['product_id'], 'wpneo_fi_interest_rate', true );
      $duration      = get_post_meta( $cart[key($cart)]['product_id'], 'wpneo_fi_loan_duration', true );

      $monthly_payment            = round(wpneo_fi_compute_monthly_payment( $total, $interest, $duration ),2);

      $total_interest           = round(($duration * $monthly_payment  ) - $total,2);

      $table .= '<div id="wpneo_fi_interest_insurance"><h3>' . __('Tableau de remboursement') . '</h3>';

      $table .= '<div>' .__('Pour un prêt de ').'<b>'.$total.'</b>'.__(' euros, vous gagnez à terme : ').$total_interest.' Euros</div>';


      $table .= '</div>';
      $table .= '<div class="fi-interest-tab">';
      $table .= '<h4>'.__('Tableau de remboursement').'</h4>';

      $table .= '<table id="fi-interest-table"><tr><th>'
      . __('Mois') . '</th><th>'
      . __('Capital remboursé') . '</th><th>'
      . __('Intérêts bruts') . '</th><th>'
      . __('Remboursement par mois') . '</th></tr>';

      $monthly_interest_rate  = $interest / 12 / 100;
      $total_left_to_pay    = $total;
      for($i = 1 ; $i <= $duration; $i++ ) {
        $interest_by_month = round($total_left_to_pay * $monthly_interest_rate,2);
        $capital_by_month = round($monthly_payment - $interest_by_month,2);

        $table .= '<tr class="fi-interest-row">';
        $table .= '<td>'.__('Mois ').$i.'</td>';
        $table .= '<td>'.$capital_by_month.'</td>';
        $table .= '<td>'.$interest_by_month.'</td>';
        $table .= '<td>'.$monthly_payment.'</td>';
        $table .= '</tr>';

        $total_left_to_pay = $total_left_to_pay - $capital_by_month;
      }

      $table .= '</table>';

      return $table;

  }
}
//Return monthly payment
if (! function_exists('wpneo_fi_compute_monthly_payment')){
  function wpneo_fi_compute_monthly_payment( $amt , $i, $term ) {
    $int = $i/1200;
    $int1 = 1+$int;
    $r1 = pow($int1, $term);

    $pmt = $amt*($int*$r1)/($r1-1);

    return $pmt;
  }
}

if (! function_exists('wpneo_post')){
    function wpneo_post($post_item){
        if (!empty($_POST[$post_item])) {
            return $_POST[$post_item];
        }
        return null;
    }
}

if (! function_exists('wpneo_crowdfunding_update_option_text')){
    function wpneo_crowdfunding_update_option_text($option_name = '', $option_value = null){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        }
    }
}

if (! function_exists('wpneo_crowdfunding_update_option_checkbox')){
    function wpneo_crowdfunding_update_option_checkbox($option_name = '', $option_value = null, $checked_default_value = 'false'){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        } else{
            update_option($option_name, $checked_default_value);
        }
    }
}

if (! function_exists('wpneo_crowdfunding_update_post_meta_text')){
    function wpneo_crowdfunding_update_post_meta_text($post_id, $meta_name = '', $meta_value = null){
        //if (!empty($meta_value)) {
            return update_post_meta( $post_id, $meta_name, $meta_value);
        //}
    }
}

if (! function_exists('wpneo_crowdfunding_update_post_meta_checkbox')){
    function wpneo_crowdfunding_update_post_meta_checkbox($post_id, $meta_name = '', $meta_value = null, $checked_default_value = 'false'){
        if (!empty($meta_value)) {
            update_post_meta( $post_id, $meta_name, $meta_value);
        }else{
            update_post_meta( $post_id, $meta_name, $checked_default_value);
        }
    }
}

if (! function_exists('wpneo_get_published_pages')) {
    function wpneo_get_published_pages(){

        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'child_of' => 0,
            'parent' => -1,
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        return $pages;
    }
}
