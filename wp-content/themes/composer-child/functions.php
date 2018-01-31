<?php

add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);
function add_login_logout_link($items, $args) {
        ob_start();
        wp_loginout('index.php');
        $loginoutlink = ob_get_contents();
        ob_end_clean();
        $items .= '<li>'. $loginoutlink .'</li>';
    return $items;
}


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
