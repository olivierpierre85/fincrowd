<?php
global $post;
$product = new WC_Product($post->ID);
echo '<div class="woocommerce">';
echo $product->get_rating_html();
echo '</div>';
?>