<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<!-- <div class="campaign-interest-rate"> -->
    <!-- <ul> -->
        <li class="campaign-interest-rate">
            <p class="interest-rate">
              Taux d'intérêts/an : <span><?php echo wpneo_crowdfunding_get_interest_rate(get_the_ID()); ?></span> %
            </p>
        </li>
    <!-- </ul> -->
<!-- </div> -->
