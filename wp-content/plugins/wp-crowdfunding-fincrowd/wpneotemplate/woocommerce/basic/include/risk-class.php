<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-risk-class">
    <ul>
        <li><p class="risk-class">
            <!--TODOMathieu affichage spécial-->
              <?php echo wpneo_crowdfunding_get_risk_class(get_the_ID()); ?>
                </p>
        </li>
    </ul>
</div>
