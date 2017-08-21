<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-interest-rate">
    <ul>
        <li><p class="interest-rate">
            Taux d'intérêts/an : <?php echo wpneo_crowdfunding_get_interest_rate(get_the_ID()); ?> %
                </p>
        </li>
    </ul>
</div>
