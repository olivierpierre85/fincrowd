<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-loan-insurance">
    <ul>
        <li><p class="loan-insurance">
            <!--TODOMat-->
              <?php echo wpneo_crowdfunding_get_loan_insurance(get_the_ID()); ?>
                </p>
        </li>
    </ul>
</div>
