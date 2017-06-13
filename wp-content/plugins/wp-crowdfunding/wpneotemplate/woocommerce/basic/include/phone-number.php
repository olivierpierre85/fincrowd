<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-phone-number">
    <ul>
        <li><p class="phone-number">
            <!--TODOMat-->
              Numéro de téléphone : <?php echo wpneo_crowdfunding_get_phone_number(get_the_ID()); ?>
                </p>
        </li>
    </ul>
</div>
