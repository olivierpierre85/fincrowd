<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-website">
    <ul>
        <li><p class="website">
            <!--TODOMat-->
              Site Web : <?php echo wpneo_crowdfunding_get_website(get_the_ID()); ?>
                </p>
        </li>
    </ul>
</div>
