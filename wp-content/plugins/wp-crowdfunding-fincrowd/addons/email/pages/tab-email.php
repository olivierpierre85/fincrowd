<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}



// #Email Settings (Tab Settings)
$arr =  array(
    //------------------------------ Email Settings -------------------------------
    // #Listing Page Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('Email Settings','wp-crowdfunding'),
        'desc'      => __('You may Enable Email Settings','wp-crowdfunding'),
        'top_line'  => 'true',
    ),

    // #Enable Email for crowdfunding Plugin
    array(
        'id'        => 'wpneo_enable_email',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Email','wp-crowdfunding'),
        'desc'      => __('Enable email notifications for WP Crowdfunding plugin.','wp-crowdfunding'),
    ),

    // #Form Email
    array(
        'id'        => 'wpneo_smtp_form_email',
        'type'      => 'text',
        'value'     => '',
        'label'     => __('Form Text','wp-crowdfunding'),
        'desc'      => __('Set the email sender name.','wp-crowdfunding'),
    ),

    // #Form Text
    array(
        'id'        => 'wpneo_smtp_form_text',
        'type'      => 'text',
        'value'     => '',
        'label'     => __('Form Text','wp-crowdfunding'),
        'desc'      => __('Setup here email form text.','wp-crowdfunding'),
    ),


    //------------------------------ Email SMTP Settings -------------------------------
    // #Email notification after new backed Seperator
    array(
        'type'      => 'seperator',
        'label'     => '',
        'desc'      => __('SMTP Settings','wp-crowdfunding'),
        'top_line'  => 'true',
    ),

    // #Enable Email for crowdfunding Plugin
    array(
        'id'        => 'wpneo_enable_smtp',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable SMTP','wp-crowdfunding'),
        'desc'      => __('Enable SMTP for WP Crowdfunding Plugin','wp-crowdfunding'),
    ),

    // #SMTP Host
    array(
        'id'        => 'wpneo_smtp_host',
        'type'      => 'text',
        'value'     => '',
        'label'     => __('SMTP Host','wp-crowdfunding'),
        'desc'      => __('Define the SMTP host.','wp-crowdfunding'),
    ),

    // #SMTP Port
    array(
        'id'        => 'wpneo_smtp_port',
        'type'      => 'text',
        'value'     => '',
        'label'     => __('SMTP Port','wp-crowdfunding'),
        'desc'      => __('Enter  the SMTP port.','wp-crowdfunding'),
    ),

    // #Encription
    array(
        'id'        => 'wpneo_smtp_encription',
        'type'      => 'radio',
        'option'    => array(
            ''      => __("None","wp-crowdfunding"),
            'ssl'   => __("SSL","wp-crowdfunding"),
            'tls'   => __("TLS","wp-crowdfunding"),
        ),
        'value'     => '',
        'label'     => __( 'Encryption Type','wp-crowdfunding' ),
        'desc'      => __( 'Select the email encryption type.','wp-crowdfunding' ),
    ),

    // #SMTP Username
    array(
        'id'        => 'wpneo_smtp_username',
        'type'      => 'text',
        'value'     => '',
        'label'     => __('SMTP Username','wp-crowdfunding'),
        'desc'      => __('Your SMTP account username.','wp-crowdfunding'),
    ),

    // #SMTP Password
    array(
        'id'        => 'wpneo_smtp_password',
        'type'      => 'password',
        'value'     => '',
        'label'     => __('SMTP Password','wp-crowdfunding'),
        'desc'      => __('Your SMTP account password.','wp-crowdfunding'),
    ),

    //------------------------------ Email New User Registration -------------------------------
    // #New User Registration Seperator
    // #Listing Page Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('Template Settings','wp-crowdfunding'),
        'desc'      => __('Email Notification After New User Registration','wp-crowdfunding'),
        'top_line'  => 'true',
    ),

    // #Email Notification After New User Registration
    array(
        'id'        => 'wpneo_enable_new_user_registration_email',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email Notification After New User Registration','wp-crowdfunding'),
        'desc'      => __('Enable email notification after new user registration','wp-crowdfunding'),
    ),

    // #Email Notification New User Registration
    array(
        'id'        => 'wpneo_enable_new_user_registration_email_admin',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email to','wp-crowdfunding'),
        'desc'      => __('Admin','wp-crowdfunding'),
    ),

    // #Email Notification New User Registration
    array(
        'id'        => 'wpneo_enable_new_user_registration_email_user',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('','wp-crowdfunding'),
        'desc'      => __('User','wp-crowdfunding'),
    ),

    // #New User Email Subject
    array(
        'id'        => 'wpneo_new_user_email_subject',
        'type'      => 'text',
        'label'     => __('New User Email Subject','wp-crowdfunding'),
        'desc'      => __('Subject line for new user registration email.','wp-crowdfunding'),
    ),

    // #New User Registration Template
    array(
        'id'        => 'wpneo_user_registration_email_template',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('New User Registration Template','wp-crowdfunding'),
        'desc'      => __('Shortcode list for variable text.( [user_name], [site_title] )','wp-crowdfunding'),
    ),

    // #New User Registration Template
    array(
        'id'        => 'wpneo_user_registration_email_template_company',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('New User Registration Template for company','wp-crowdfunding'),
        'desc'      => __('Shortcode list for variable text.( [user_name], [site_title] )','wp-crowdfunding'),
    ),

    //------------------------------ Email New Backed -------------------------------
    // #Email notification after new backed Seperator
    array(
        'type'      => 'seperator',
        'label'     => '',
        'desc'      => __('Email Notification after New Backed','wp-crowdfunding'),
        'top_line'  => 'true',
    ),

    // #Email Notification After New Backed
    array(
        'id'        => 'wpneo_enable_new_backed_email',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email notification after new backed','wp-crowdfunding'),
        'desc'      => __('Enable email notification after new backed.','wp-crowdfunding'),
    ),

    // #Email Notification Admin User Registration
    array(
        'id'        => 'wpneo_enable_new_backed_email_admin',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email to','wp-crowdfunding'),
        'desc'      => __('Admin','wp-crowdfunding'),
    ),



    // #Email Notification User User Registration
    array(
        'id'        => 'wpneo_enable_new_backed_email_user',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('','wp-crowdfunding'),
        'desc'      => __('User','wp-crowdfunding'),
    ),

    // #New Backer Email Subject
    array(
        'id'        => 'wpneo_new_backer_email_subject',
        'type'      => 'text',
        'label'     => __('New Backer Email Subject','wp-crowdfunding'),
        'desc'      => __('Subject line for new backers email.','wp-crowdfunding'),
    ),

    // #New Backer Email Template
    array(
        'id'        => 'wpneo_backer_email_template',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('Message pour nouvel investisseur en début de campagne','wp-crowdfunding'),
        'desc'      => __('Below are shortcode list for variable text.( [user_name], [site_title], [total_amount], [campaign_title] )','wp-crowdfunding'),
    ),


    //Fincrowd new mails after backed campaign
    //------------------------------------------------------

    // #Percentage to be considered close to the end
    array(
        'id'        => 'wpneo_fi_end_campaign_percent',
        'type'      => 'text',
        'label'     => __('Pourcent à partir duquel nous sommes en fin de campagne','wp-crowdfunding'),
        'desc'      => __('Pourcentage (chiffres uniquement)','wp-crowdfunding'),
    ),

    // #New Backer Email Template
    array(
        'id'        => 'wpneo_backer_end_email_template',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('Message pour nouvel investisseur après certain montant atteint','wp-crowdfunding'),
        'desc'      => __('Below are shortcode list for variable text.( [user_name], [site_title], [total_amount], [campaign_title] )','wp-crowdfunding'),
    ),

    // #New Backer Email Template
    array(
        'id'        => 'wpneo_backer_reminder_email_template',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('Message de rappel envoyé à tous les investisseurs après un certain pourcentage atteint','wp-crowdfunding'),
        'desc'      => __('Below are shortcode list for variable text.( [user_name], [site_title], [total_amount], [campaign_title] )','wp-crowdfunding'),
    ),




    //end fincrowd

    //------------------------------ Email Submit Campaign -------------------------------
    // #Email Notification after Submit Campaign
    array(
        'type'      => 'seperator',
        'label'     => '',
        'desc'      => __('Email Notification after Submit Campaign','wp-crowdfunding'),
        'top_line'  => 'true',
    ),
    // #Email notification after submit campaign
    array(
        'id'        => 'wpneo_enable_new_campaign_email',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email notification after submit campaign','wp-crowdfunding'),
        'desc'      => __('Enable email notification after submit campaign.','wp-crowdfunding'),
    ),

    // #Email Notification after Submit Campaign
    array(
        'id'        => 'wpneo_enable_new_campaign_email_admin',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email to','wp-crowdfunding'),
        'desc'      => __('Admin','wp-crowdfunding'),
    ),

    // #Email Notification after Submit Campaign
    array(
        'id'        => 'wpneo_enable_new_campaign_email_user',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('','wp-crowdfunding'),
        'desc'      => __('User','wp-crowdfunding'),
    ),

    // #New Campaign Email Subject
    array(
        'id'        => 'wpneo_new_campaign_email_subject',
        'type'      => 'text',
        'value'     => '',
        'label'     => __('New Campaign Email Subject','wp-crowdfunding'),
        'desc'      => __('Subject line for new campaign email.','wp-crowdfunding'),
    ),

    // #New Campaign Email Template
    array(
        'id'        => 'wpneo_campaign_email_template',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('New Campaign Email Template','wp-crowdfunding'),
        'desc'      => __('Below are shortcode list for variable text.( [user_name], [campaign_title] )','wp-crowdfunding'),
    ),

    //------------------------------ Email Accept Campaign -------------------------------
    // #Email notification after submit accept
    array(
        'type'      => 'seperator',
        'label'     => '',
        'desc'      => __('Email Notification after Accept Campaign','wp-crowdfunding'),
        'top_line'  => 'true',
    ),

    // #Email Notification After Accept Campaign
    array(
        'id'        => 'wpneo_enable_accept_campaign_email',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email Notification After Accept Campaign','wp-crowdfunding'),
        'desc'      => __('Enable email notification after accept campaign.','wp-crowdfunding'),
    ),

    // #Email Notification After Accept Campaign
    array(
        'id'        => 'wpneo_enable_accept_campaign_email_admin',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email to','wp-crowdfunding'),
        'desc'      => __('Admin','wp-crowdfunding'),
    ),

    // #Email Notification After Accept Campaign
    array(
        'id'        => 'wpneo_enable_accept_campaign_email_user',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('','wp-crowdfunding'),
        'desc'      => __('User','wp-crowdfunding'),
    ),

    // #Accept Campaign Email Subject
    array(
        'id'        => 'wpneo_accept_campaign_email_subject',
        'type'      => 'text',
        'value'     => '',
        'label'     => __('Accept Campaign Email Subject','wp-crowdfunding'),
        'desc'      => __('Subject line for accept campaign email.','wp-crowdfunding'),
    ),

    // #Accept Campaign Email Template
    array(
        'id'        => 'wpneo_accept_campaign_email_template',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('Accept Campaign Email Template','wp-crowdfunding'),
        'desc'      => __('Below are shortcode list for variable text.( [user_name], [campaign_title] )','wp-crowdfunding'),
    ),

    // #Save Function
    array(
        'id'        => 'wpneo_varify_email_addons',
        'type'      => 'hidden',
        'value'     => 'true',
    ),
    //---------------------------------------- start fincrowd emails
    //------------------------------ Email Cancel Campaign -------------------------------
    // #Email notification after submit cancel
    array(
        'type'      => 'seperator',
        'label'     => '',
        'desc'      => __('Email Notification after cancel Campaign','wp-crowdfunding'),
        'top_line'  => 'true',
    ),

    // #Email Notification After cancel Campaign
    array(
        'id'        => 'wpneo_enable_cancel_campaign_email',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email Notification After cancel Campaign','wp-crowdfunding'),
        'desc'      => __('Enable email notification after cancel campaign.','wp-crowdfunding'),
    ),

    // #Email Notification After cancel Campaign
    array(
        'id'        => 'wpneo_enable_cancel_campaign_email_admin',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email to','wp-crowdfunding'),
        'desc'      => __('Admin','wp-crowdfunding'),
    ),

    // #Email Notification After cancel Campaign
    array(
        'id'        => 'wpneo_enable_cancel_campaign_email_user',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('','wp-crowdfunding'),
        'desc'      => __('User','wp-crowdfunding'),
    ),

    // #cancel Campaign Email Subject
    array(
        'id'        => 'wpneo_cancel_campaign_email_subject',
        'type'      => 'text',
        'value'     => '',
        'label'     => __('Cancel Campaign Email Subject','wp-crowdfunding'),
        'desc'      => __('Subject line for cancel campaign email.','wp-crowdfunding'),
    ),

    // #cancel Campaign Email Template
    array(
        'id'        => 'wpneo_cancel_campaign_email_template',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('Cancel Campaign Email Template','wp-crowdfunding'),
        'desc'      => __('Below are shortcode list for variable text.( [user_name], [campaign_title] )','wp-crowdfunding'),
    ),

    // #cancel Campaign Email Template
    array(
        'id'        => 'wpneo_cancel_campaign_client_email_template',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('Cancel Campaign Email client Template','wp-crowdfunding'),
        'desc'      => __('Below are shortcode list for variable text.( [user_name], [campaign_title] )','wp-crowdfunding'),
    ),
    //------------------------------ Email Cancel Campaign pledge -------------------------------
    // #Email notification after submit cancel
    array(
        'type'      => 'seperator',
        'label'     => '',
        'desc'      => __('Email Notification after cancel Campaign','wp-crowdfunding'),
        'top_line'  => 'true',
    ),

    // #Email Notification After cancel Campaign
    array(
        'id'        => 'wpneo_enable_cancel_campaign_pledge_email',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email Notification After cancel Campaign pledge','wp-crowdfunding'),
        'desc'      => __('Enable email notification after cancel campaign pledge.','wp-crowdfunding'),
    ),
    // #Email Notification After cancel Campaign pledge
    array(
        'id'        => 'wpneo_enable_cancel_campaign_pledge_email_admin',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email to','wp-crowdfunding'),
        'desc'      => __('Admin','wp-crowdfunding'),
    ),

    // #Email Notification After cancel Campaign
    array(
        'id'        => 'wpneo_enable_cancel_campaign_pledge_email_user',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('','wp-crowdfunding'),
        'desc'      => __('User','wp-crowdfunding'),
    ),

    // #cancel Campaign Email Subject
    array(
        'id'        => 'wpneo_cancel_campaign_pledge_email_subject',
        'type'      => 'text',
        'value'     => '',
        'label'     => __('Cancel Campaign pledge Email Subject','wp-crowdfunding'),
        'desc'      => __('Subject line for cancel campaign pledge email.','wp-crowdfunding'),
    ),

    // #cancel Campaign Email Template
    array(
        'id'        => 'wpneo_cancel_campaign_pledge_email_template',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('Cancel Campaign Email Template','wp-crowdfunding'),
        'desc'      => __('Below are shortcode list for variable text.( [user_name], [campaign_title] )','wp-crowdfunding'),
    ),
    //------------------------------ Email validate Campaign -------------------------------
    // #Email notification after submit validate
    array(
        'type'      => 'seperator',
        'label'     => '',
        'desc'      => __('Email Notification after validate Campaign','wp-crowdfunding'),
        'top_line'  => 'true',
    ),

    // #Email Notification After validate Campaign
    array(
        'id'        => 'wpneo_enable_validate_campaign_email',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email Notification After validate Campaign','wp-crowdfunding'),
        'desc'      => __('Enable email notification after validate campaign.','wp-crowdfunding'),
    ),

    // #Email Notification After validate Campaign
    array(
        'id'        => 'wpneo_enable_validate_campaign_email_admin',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Email to','wp-crowdfunding'),
        'desc'      => __('Admin','wp-crowdfunding'),
    ),

    // #Email Notification After validate Campaign
    array(
        'id'        => 'wpneo_enable_validate_campaign_email_user',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('','wp-crowdfunding'),
        'desc'      => __('User','wp-crowdfunding'),
    ),

    // #validate Campaign Email Subject
    array(
        'id'        => 'wpneo_validate_campaign_email_subject',
        'type'      => 'text',
        'value'     => '',
        'label'     => __('validate Campaign Email Subject','wp-crowdfunding'),
        'desc'      => __('Subject line for validate campaign email.','wp-crowdfunding'),
    ),
    // #validate Campaign Email Template
    array(
        'id'        => 'wpneo_validate_campaign_email_template',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('validate Campaign Email Template for creator','wp-crowdfunding'),
        'desc'      => __('Below are shortcode list for variable text.( [user_name], [campaign_title] )','wp-crowdfunding'),
    ),
    // #validate Campaign Email Template
    array(
        'id'        => 'wpneo_validate_campaign_client_email_template',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('validate Campaign Email Template for client','wp-crowdfunding'),
        'desc'      => __('Below are shortcode list for variable text.( [user_name], [campaign_title] )','wp-crowdfunding'),
    ),
    //------------------------------------------- end fincrowd

    // #Save Function
    array(
        'id'        => 'wpneo_varify_email_addons',
        'type'      => 'hidden',
        'value'     => 'true',
    ),
);
echo wpneo_crowdfunding_settings_generate_field( $arr );
