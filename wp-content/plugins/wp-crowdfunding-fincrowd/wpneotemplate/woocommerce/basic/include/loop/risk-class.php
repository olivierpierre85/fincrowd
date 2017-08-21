<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-risk-class">
    <ul>
        <li><p class="risk-class">
            Indice de Risque
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
                    case "risk_d":
                      echo "D";
                      break;
                    case "risk_e":
                      echo "E";
                      break;
                    }

                          ?>
        </li>
    </ul>
</div>
