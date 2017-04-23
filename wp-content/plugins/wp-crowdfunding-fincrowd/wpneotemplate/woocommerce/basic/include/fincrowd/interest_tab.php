<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$total = WC()->cart->total;
$cart = WC()->cart->get_cart();

//$product = new WC_Product( $cart[key($cart)]['product_id']);//take first product (Normally always One and only one)
$interest                 = get_post_meta( $cart[key($cart)]['product_id'], 'wpneo_fi_interest_rate', true );
$interest_insurance      = get_post_meta( $cart[key($cart)]['product_id'], 'wpneo_fi_interest_rate_insurance', true );
$duration      = get_post_meta( $cart[key($cart)]['product_id'], 'wpneo_fi_loan_duration', true );

?>
<div class="fi-interest-tab">
  <h2><?php echo __('Tableau d\'intérêts') ;?></h2>
<table id="fi-interest-table">
  <tr>
    <th>Mois</th>
    <th>Capital</th>
    <th>Intérêts</th>
  </tr>
  <?php
  for($i = 0 ; $i < $duration && $i < 4; $i++ ) {
    echo '<tr class="fi-interest-row">';
    echo '<td>mois '.$i.'</td>';
    echo '<td>Todo capital</td>';
    echo '<td>'.$interest.'</td>';
    echo '</tr>';
  }

  for($i = 0 ; $i < $duration && $i < 4; $i++ ) {
    echo '<tr class="fi-interest-insurance-row">';
    echo '<td>mois '.$i.'</td>';
    echo '<td>Todo capital</td>';
    echo '<td>'.$interest_insurance.'</td>';
    echo '</tr>';
  }

  ?>
</table>
