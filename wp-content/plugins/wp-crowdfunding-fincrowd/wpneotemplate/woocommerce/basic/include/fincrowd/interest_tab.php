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

$total_interest = ($total * $interest) /100;
$total_interest_insurance = ($total * $interest_insurance) /100;

?>
<div class="fi-interest-tab">
  <h4 class="fi-interest-row"><?php echo __('Intérêts total (sans garantie)').': '.$total_interest;    ?></h4>
  <h4 class="fi-interest-insurance-row"><?php echo __('Intérêts total (avec garantie)').': '.$total_interest_insurance;    ?></h4>
  <h4><?php echo __('Tableau de remboursement') ;?></h4>
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
