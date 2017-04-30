<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="wpneo-single-sidebar">
    <?php
    global $post, $woocommerce, $product;
    $currency = '$';
    if ($product->product_type == 'crowdfunding') {
      if ( is_user_logged_in() ) {
        if (WPNEOCF()->campaignValid()) {
            $recomanded_price = get_post_meta($product->id, 'wpneo_funding_recommended_price', true);
            //$min_price = get_post_meta($product->id, 'wpneo_funding_minimum_price', true);
            //$max_price = get_post_meta($product->id, 'wpneo_funding_maximum_price', true);

            //Fincrowd
            //if society, min is 5000
            $min_price = 500;// TODO global var
            $max_price = 5000;
            if(! get_the_author_meta( 'physical_person', get_current_user_id() )){
              $min_price = 5000;// TODO global var
              $max_price = 0;
            }
            //Fincrowd TODO $max price is the remainder of the campaing asking ?

            if(function_exists( 'get_woocommerce_currency_symbol' )){
                $currency = get_woocommerce_currency_symbol();
            }

            if (! empty($_GET['reward_min_amount'])){
                $recomanded_price = (int) esc_html($_GET['reward_min_amount']);
            } ?>

            <span class="wpneo-tooltip">
                <span class="wpneo-tooltip-min"><?php _e('Minimum amount is ','wp-crowdfunding'); echo $currency.$min_price; ?></span>
                <span class="wpneo-tooltip-max"><?php _e('Maximum amount is ','wp-crowdfunding'); echo $currency.$max_price; ?></span>
            </span>

            <form enctype="multipart/form-data" method="post" class="cart">
                <?php do_action('before_wpneo_donate_field'); ?>
                <!--Voulez-vous profiter de la garantie (taux réduit) ?
                <input type="checkbox" name="wpneo_fi_interest_insurance" value="false"><br>-->
                <input type="number" min="0" placeholder="<?php echo  get_woocommerce_currency_symbol().' '.$recomanded_price; ?>" name="wpneo_donate_amount_field" class="input-text amount wpneo_donate_amount_field text" value="<?php echo $recomanded_price; ?>" data-min-price="<?php echo $min_price ?>" data-max-price="<?php echo $max_price ?>" >
                <?php do_action('after_wpneo_donate_field'); ?>
                <input type="hidden" value="<?php echo esc_attr($product->id); ?>" name="add-to-cart">
                <button type="submit" class="<?php echo apply_filters('add_to_donate_button_class', 'wpneo_donate_button'); ?>"><?php _e('Back This Campaign', 'wp-crowdfunding'); ?>
            </form>

            <?php
        } else {
            _e('This campaigns is over.','wp-crowdfunding');
        }
      } else {
          _e('Vous devez vous connecter pour investir dans ce projet.','wp-crowdfunding');//TODO add link to registration
      }
    }

    ?>
</div>
