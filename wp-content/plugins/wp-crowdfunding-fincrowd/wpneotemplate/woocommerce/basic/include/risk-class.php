<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-risk-class">
  <div class="risk-class">
    Indice de risque
    <?php switch (wpneo_crowdfunding_get_risk_class(get_the_ID())) {
      case "risk_a":
        echo "A";
        break;
      case "risk_b":
        echo "B";
        break;
      case "risk_c":
        echo "C";
        break;
      case "risk_D":
        echo "D";
        break;
      case "risk_E":
        echo "E";
        break;
      }

      ?>
  </div>
</div>
