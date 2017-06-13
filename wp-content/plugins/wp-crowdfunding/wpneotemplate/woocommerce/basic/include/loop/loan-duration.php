<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-loan-duration">
    <ul>
        <li><p class="loan-duration">
            <!--TODOMat-->
              Durée du prêt :<?php echo wpneo_crowdfunding_get_loan_duration(get_the_ID()); ?>
                </p>
        </li>
    </ul>
</div>
