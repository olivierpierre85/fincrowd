<?php
//FinCrowd
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="campaign-contact-person">
    <ul>
        <li><p class="contact-person">
            <!--TODOMat-->
              Personne de Contact : <?php echo wpneo_crowdfunding_get_contact_person(get_the_ID()); ?>
                </p>
        </li>
    </ul>
</div>
