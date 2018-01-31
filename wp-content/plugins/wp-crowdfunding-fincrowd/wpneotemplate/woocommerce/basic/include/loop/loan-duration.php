<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<!-- <div class="campaign-loan-duration"> -->
    <!-- <ul> -->
        <li class="campaign-loan-duration">
            <p class="loan-duration">
            <!--TODOMat-->
              Durée du prêt : <span><?php echo wpneo_crowdfunding_get_loan_duration(get_the_ID()); ?></span> mois
            </p>
        </li>
    </ul>
<!-- </div> -->
