<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<?php if (wpneo_crowdfunding_get_campaigns_location()){ ?>
    <div class="wpneo-location-wrapper">
        <!-- <i class="wpneo-icon wpneo-icon-location"></i> -->
        <textarea class="wpneo-fi-address-textarea" readonly><?php echo wpneo_crowdfunding_get_campaigns_location(); ?></textarea>
    </div>
<?php } ?>
