<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$current_user = wp_get_current_user();

$html .= '<div class="wpneo-content">';

    $html .= '<form id="wpneo-dashboard-form" action="" method="" class="wpneo-form">';

        // User Name
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Username:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<input type="hidden" name="action" value="wpneo_dashboard_form">';
                $html .= '<input type="text" name="username" value="'.$current_user->user_login.'" disabled>';
            $html .= '</div>';
        $html .= '</div>';
        
        // Email Address
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Email:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<input type="email" name="email" value="'.$current_user->user_email.'" disabled>';
            $html .= '</div>';
        $html .= '</div>';

        // First Name
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "First Name:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<input type="text" name="firstname" value="'.$current_user->user_firstname.'" disabled>';
            $html .= '</div>';
        $html .= '</div>';

        // Last Name
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Last Name:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<input type="text" name="lastname" value="'.$current_user->user_lastname.'" disabled>';
            $html .= '</div>';
        $html .= '</div>';

        // Website
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Website:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<input type="text" name="website" value="'.$current_user->user_url.'" disabled>';
            $html .= '</div>';
        $html .= '</div>';

        // Bio Info
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Bio:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<textarea name="description" rows="3" disabled>'.$current_user->description.'</textarea>';
            $html .= '</div>';
        $html .= '</div>';

        $html .= '<h3>'.__('Payment Info:', 'wp-crowdfunding').'</h3>';
        ob_start();
        do_action('wpneo_crowdfunding_dashboard_after_dashboard_form');
        $html .= ob_get_clean();

        $html .= wp_nonce_field( 'wpneo_crowdfunding_dashboard_form_action', 'wpneo_crowdfunding_dashboard_nonce_field', true, false );

        //Save Button
        $html .= '<div class="wpneo-buttons-group float-right">';
            $html .= '<button id="wpneo-edit" class="wpneo-edit-btn">'.__( "Edit" , "wp-crowdfunding" ).'</button>';
            $html .= '<button id="wpneo-dashboard-btn-cancel" class="wpneo-cancel-btn wpneo-hidden" type="submit">'.__( "Cancel" , "wp-crowdfunding" ).'</button>';
            $html .= '<button id="wpneo-dashboard-save" class="wpneo-save-btn wpneo-hidden" type="submit">'.__( "Save" , "wp-crowdfunding" ).'</button>';
        $html .= '</div>';
        $html .= '<div class="clear-float"></div>';

    $html .= '</form>';

$html .= '</div>';
