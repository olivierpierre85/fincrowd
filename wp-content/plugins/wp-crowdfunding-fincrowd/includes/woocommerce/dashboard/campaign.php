<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$page_numb = max( 1, get_query_var('paged') );
$posts_per_page = get_option( 'posts_per_page',10 );
$args = array(
            'post_type' 		=> 'product',
            'post_status'		=> array('publish', 'draft'),
            'author'    		=> get_current_user_id(),
            'tax_query' 		=> array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'crowdfunding',
                ),
            ),
            'posts_per_page'    => $posts_per_page,
            'paged'             => $page_numb
    );


$html .= '<div class="wpneo-content">';
    $html .= '<div class="wpneo-form campaign-listing-page">';
        $html .= '<a href="'.get_permalink(get_option('wpneo_form_page_id')).'" class="dashboard-btn-link float-right">'.__("Add Campaign", "wp-crowdfunding").'</a>';
        $the_query = new WP_Query( $args );
        if ( $the_query->have_posts() ) :
            global $post;
            $i = 1;
            while ( $the_query->have_posts() ) : $the_query->the_post();
                ob_start();
                $status = '';
                if (get_post_status() == 'draft'){
                    $status='<strong class="wpneo-campaign-status">[Draft]</strong>';
                }
                ?>
                <div class="wpneo-listings-dashboard">
                    <?php do_action('wpneo_dashboard_campaign_loop_item_before_content'); ?>
                    <div class="wpneo-listing-content">
                        <?php echo $status; ?>
                        <?php do_action('wpneo_dashboard_campaign_loop_item_content'); ?>
                    </div>
                    <?php
                        $operation_btn = '';
                        $operation_btn .= '<div class="wpneo-fields-action float-right">';
                        //Check order status
                        if (get_post_status() != 'draft') {
                            $operation_btn .= '<span><a href="' . get_permalink() . '">'. __("View", "wp-crowdfunding") .'</a></span>';
                            $page_id = get_option('wpneo_form_page_id');
                            if ($page_id != '') {
                                $operation_btn .= '<span><a href="' . get_permalink($page_id) . '?action=edit&postid=' . get_the_ID() . '">' . __("Edit", "wp-crowdfunding") . '</a></span>';
                                $operation_btn .= '<span><a href="?page_type=update&postid=' . get_the_ID() . '">'.__("Update", "wp-crowdfunding").'</a></span>';
                            }
                        }
                        $operation_btn .= '</div>';
                        echo $operation_btn;
                    ?>
                    <?php do_action('wpneo_dashboard_campaign_loop_item_after_content'); ?>
                    <div style="clear: both"></div>
                </div>
                <?php $i++;
                $html .= ob_get_clean();
            endwhile;
            wp_reset_postdata();
        else :
            $html .= "<p>".__( 'Sorry, no Campaign Found.','wp-crowdfunding' )."</p>";
        endif;

        $html .= wpneo_crowdfunding_pagination( $page_numb , $the_query->max_num_pages );

    $html .= '<div style="clear: both;"></div>';
    $html .= '</div>';
$html .= '</div>';
