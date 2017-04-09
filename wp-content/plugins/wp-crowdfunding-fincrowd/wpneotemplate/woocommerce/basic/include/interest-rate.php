<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-interest-rate">
    <ul>
        <li><p class="interest-rate">
            <!--TODOMat-->
              <?php echo wpneo_crowdfunding_get_interest_rate(get_the_ID()); ?>
                </p>
        </li>
    </ul>
</div>
