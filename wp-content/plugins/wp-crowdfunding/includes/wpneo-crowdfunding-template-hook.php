<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'wpneo_before_crowdfunding_single_campaign_summery', 'wpneo_crowdfunding_campaign_single_feature_image');
add_action( 'wpneo_crowdfunding_after_feature_img', 'wpneo_crowdfunding_campaign_single_description');
/**
 * Single campaign Template hook
 * Fincrowd Mathieu, ici se trouve l'ordre des champs pour l'affichage global d'un projet
 */
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_campaign_title');
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_campaign_author');
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_risk_class'); //Fincrowd
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_interest_rate'); //Fincrowd
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_interest_rate_insurance'); //Fincrowd
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_loan_duration'); //Fincrowd
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_loan_insurance'); //Fincrowd
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_company_number'); //Fincrowd
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_contact_person'); //Fincrowd
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_phone_number'); //Fincrowd
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_website'); //Fincrowd
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_loop_item_rating_html');
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_campaign_single_fund_raised_html');
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_campaign_single_loop_item_fund_raised_percent');
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_campaign_single_fund_this_campaign_btn');
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_template_campaign_location');
add_action( 'wpneo_crowdfunding_single_campaign_summery' , 'wpneo_crowdfunding_campaign_creator_info', 12);


/**
 * Allow it set to WooCommerce single page
 * @since 01-02-17
 * @v. 10.9
 */

add_action( 'wp', 'wpneo_crowdfunding_singlepage_custom' );

function wpneo_crowdfunding_singlepage_custom(){

    if (is_product()){
        global $post;
        $product = wc_get_product($post->ID);
        if ($product->get_type() == 'crowdfunding'){
            //add_action( 'woocommerce_single_product_summary' , 'wpneo_crowdfunding_template_campaign_author');
            //add_action('woocommerce_single_product_summary', 'wpneo_crowdfunding_loop_item_rating_html', 20);
            add_action('woocommerce_single_product_summary', 'wpneo_crowdfunding_campaign_single_fund_raised_html', 20);
            add_action('woocommerce_single_product_summary', 'wpneo_crowdfunding_campaign_single_loop_item_fund_raised_percent', 20);
            add_action('woocommerce_single_product_summary', 'wpneo_crowdfunding_campaign_single_fund_this_campaign_btn', 20);
            add_action('woocommerce_single_product_summary', 'wpneo_crowdfunding_template_campaign_location', 20);
            add_action('woocommerce_single_product_summary', 'wpneo_crowdfunding_campaign_creator_info', 20);

            add_filter('woocommerce_single_product_image_html', 'wpneo_crowdfunding_overwrite_product_feature_image', 20);
        }
    }

}

/**
 * ##ENd Campaign Template hook
 */


add_filter( 'wpneo_crowdfunding_default_single_campaign_tabs', 'wpneo_crowdfunding_default_single_campaign_tabs', 10);
add_action( 'wpneo_after_crowdfunding_single_campaign_summery', 'wpneo_crowdfunding_campaign_single_tab');

//Campaign Story Right Sidebar
add_action( 'wpneo_campaign_story_right_sidebar', 'wpneo_campaign_story_right_sidebar');


//Listing Loop
//fincrowd Mathieu, ici se trouve l'ordre des champs pour l'affichage réduit (listing)
add_action( 'wpneo_campaign_loop_item_before_content', 'wpneo_crowdfunding_loop_item_thumbnail');
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_rating_html');
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_title');
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_author');
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_risk_class');//Fincrowd
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_interest_rate');//Fincrowd
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_interest_rate_insurance');//Fincrowd
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_loan_duration');//Fincrowd
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_loan_insurance');//Fincrowd
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_location');
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_short_description');
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_fund_raised_percent');
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_funding_goal');
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_time_remaining');
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_fund_raised');
add_action( 'wpneo_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_button');

//Dashboard Campaigns
add_action( 'wpneo_dashboard_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_title');
add_action( 'wpneo_dashboard_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_author');
add_action( 'wpneo_dashboard_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_location');
add_action( 'wpneo_dashboard_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_fund_raised_percent' );
add_action( 'wpneo_dashboard_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_funding_goal');
add_action( 'wpneo_dashboard_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_time_remaining');
add_action( 'wpneo_dashboard_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_fund_raised');
add_action( 'wpneo_dashboard_campaign_loop_item_content' , 'wpneo_crowdfunding_loop_item_button');
add_action( 'wpneo_dashboard_campaign_loop_item_before_content', 'wpneo_crowdfunding_loop_item_thumbnail');

//Filter Search for Crowdfunding campaign
add_filter( 'pre_get_posts' , 'wpneo_crowdfunding_search_shortcode_filter');
