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
            <?php if(wpneo_crowdfunding_get_company_number(get_the_ID()) != 'false'){ ?>
              Numéro d'entreprise : <?php echo wpneo_crowdfunding_get_company_number(get_the_ID()); ?>
            <?php } ?>
            </p>
        </li>
    </ul>
</div>
