<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$location = wpneo_crowdfunding_get_campaigns_location();
?>
<div class="wpneo-location">
    <!-- <i class="wpneo-icon wpneo-icon-location"></i> -->
    <div >
      <textarea class="wpneo-fi-address-textarea" readonly>
      <?php echo $location; ?>
      </textarea>
    </div>
</div>
