<?php
//MENU ITEMS HANDLING

//LOGIN LOGOUT ITEMS
add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);
function add_login_logout_link($items, $args) {
        ob_start();
        wp_loginout('index.php');
        $loginoutlink = ob_get_contents();
        ob_end_clean();
        $items .= '<li>'. $loginoutlink .'</li>';
    return $items;
}

add_filter( 'wp_get_nav_menu_items', 'wpneo_fi_exclude_menu_items', null, 3 );
function wpneo_fi_exclude_menu_items( $items, $menu, $args ) {
    // Iterate over the items to search and destroy
    foreach ( $items as $key => $item ) {
      if(is_user_logged_in()) {
        if ( $item->ID == 16 ) unset( $items[$key] );
        //and if not moral person, hide create campaign
        if(get_the_author_meta( 'physical_person', get_current_user_id() )){
          if ( $item->ID == 15 ) unset( $items[$key] );
        }
      } else {
        if ( $item->ID == 15 ) unset( $items[$key] );
        if ( $item->ID == 17 ) unset( $items[$key] );
      }
    }

    return $items;
}

//HIDE is not connected
// add_filter( 'wp_setup_nav_menu', function( \stdClass $item ) {
//     # Check conditionals, and invalidate an item in case
//     $item->_invalid = is_user_logged_in()
//         && 'post' === $item->object
//         && 'post_type' === $item->type
//         # && … whatever you need to check for your invalidation of an item
//     ;
//
//     return $item;
// } );


/* *********************************************************************************************
 PLEASE DO NOT DELETE THIS FUNCTION BECAUSE THIS FUNCTION IS CALLING CHILD THIME CSS FILE
********************************************************************************************* */

function composer_child_themestyles () {
	if (!is_admin()) {

		wp_register_style('child-theme-style', get_stylesheet_directory_uri() . '/child-theme-style.css', array('composer-plugins-stylesheet','composer-custom-css'), '1.0');

		wp_enqueue_style('child-theme-style');

	}
}
add_action('wp_enqueue_scripts', 'composer_child_themestyles');

/* ******************************************************************************************** */
