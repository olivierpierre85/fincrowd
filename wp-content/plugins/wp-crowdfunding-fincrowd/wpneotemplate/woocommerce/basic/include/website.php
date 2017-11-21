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
            <?php if(wpneo_crowdfunding_get_website(get_the_ID()) != 'false'){
                    echo '<a href ="'.wpneo_crowdfunding_get_website(get_the_ID()).'" >'.wpneo_crowdfunding_get_website(get_the_ID()).'</a>';
                  }
              ?>
                </p>
        </li>
    </ul>
</div>
