<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-interest-rate-insurance">
    <ul>
        <li><p class="interest-rate-insurance">
            <!--TODOMat-->
              Intérêts avec garantie : <?php echo wpneo_crowdfunding_get_interest_rate_insurance(get_the_ID()); ?>
                </p>
        </li>
    </ul>
</div>
