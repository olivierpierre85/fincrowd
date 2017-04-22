<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-company-number">
    <ul>
        <li><p class="company-number">
            <!--TODOMat-->
              Numéro de société : <?php echo wpneo_crowdfunding_get_company_number(get_the_ID()); ?>
                </p>
        </li>
    </ul>
</div>
