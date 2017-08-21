<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//FINCROWD after order page TODO olpi better handling
$cart = WC()->cart->get_cart();
$order = wc_get_order( $order_id );
$items = $order->get_items();
$iban = get_post_meta( $items[key($items)]['product_id'], 'wpneo_fi_account_number', true );

?>
<h2>Merci pour votre investissement !</h2>
Message message
<a href="<?php echo home_url(); ?>">Retour à l'accueil</a>
Numéro IBAN à transférer à <?php echo $iban; ?>
