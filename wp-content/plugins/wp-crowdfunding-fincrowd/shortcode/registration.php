<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_shortcode( 'wpneo_registration', 'wpneo_registration_shortcode' );

function wpneo_registration_shortcode() {

    if ( is_user_logged_in() ) { ?>
        <h3 class="wpneo-center"><?php _e("You are already logged in.","wp-crowdfunding"); ?></h3>
    <?php } else {
      global $reg_errors,$reg_success;
      $nonce = wp_create_nonce( 'wpneo-nonce-registration' );
      //Get the link to the cgu document
      $attachment_id = 258;
      $cguFile = wp_get_attachment_url( $attachment_id );
      $cguLink = "J'accepte les conditions générales <a href='". $cguFile . "' target='_blank'>(Télécharger les conditions d'utilisation)</a>";
      ?>
        <div class="wpneo-user-registration-wrap">
            <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" id="wpneo-registration" method="post">
                <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>">
                <?php
                $wpneo_user_regisration_meta_array = array(
                  array(
                      'id'            => 'fi_reg_type_person',
                      'label'         => __( "Personne Physique ou Morale" , "wp-crowdfunding" ),
                      'type'          => 'physical',
                      'placeholder'   => __('Personne Physique ou Morale', 'wp-crowdfunding'),
                      'value'         => '',
                      'class'         => '',
                      'warpclass'     => '',
                      'autocomplete'  => 'off',
                  ),
                  // array(
                  //     'id'            => 'fi_company_responsible_status',
                  //     'label'         => __( "Qualification de la personne responsable" , "wp-crowdfunding" ),
                  //     'type'          => 'select',
                  //     'placeholder'   => __('Entrez la qualification de la personne responsable', 'wp-crowdfunding'),
                  //     'value'         => '',
                  //     'warpclass'     => 'fi_reg_pm',
                  //     'class'         => '',
                  //     'autocomplete'  => 'off',
                  //     'choices'       => [__( "Directeur" , "wp-crowdfunding" ),__( "Gérant" , "wp-crowdfunding" ),__( "Autre" , "wp-crowdfunding" ),__( "Admin" , "wp-crowdfunding" )]
                  // ),
                    array(
                       'id'            => 'fi_company_responsible_status',
                       'label'         => __( "Qualification de la personne responsable" , "wp-crowdfunding" ),
                       'type'          => 'text',
                       'placeholder'   => __('Entrez la qualification de la personne responsable (Directeur,Gérant, Admin, Autre (précisez))', 'wp-crowdfunding'),
                       'value'         => '',
                       'warpclass'     => 'fi_reg_pm',
                       'class'         => '',
                       'autocomplete'  => 'off',
                   ),
                  array(
                      'id'            => 'fname',
                      'label'         => __( "First Name" , "wp-crowdfunding" ),
                      'type'          => 'text',
                      'placeholder'   => __('Enter First Name', 'wp-crowdfunding'),
                      'value'         => '',
                      'class'         => '',
                      'warpclass'     => 'wpneo-first-half',
                      'autocomplete'  => 'off',
                  ),
                  array(
                      'id'            => 'lname',
                      'label'         => __( "Last Name" , "wp-crowdfunding" ),
                      'type'          => 'text',
                      'placeholder'   => __('Enter Last Name', 'wp-crowdfunding'),
                      'value'         => '',
                      'class'         => '',
                      'warpclass'     => 'wpneo-second-half',
                      'autocomplete'  => 'off',
                  ),
                  array(
                      'id'            => 'fi_reg_birthday',
                      'label'         => __( "Date de Naissance" , "wp-crowdfunding" ),
                      'type'          => 'birthday',
                      'placeholder'   => __('jj/mm/aaaa', 'wp-crowdfunding'),
                      'value'         => '',
                      'class'         => '',
                      'warpclass'     => 'fi_reg_pp',
                      'autocomplete'  => 'off',
                  ),
                  array(
                      'id'            => 'fi_company_name',
                      'label'         => __( "Nom de la société" , "wp-crowdfunding" ),
                      'type'          => 'text',
                      'placeholder'   => __('Entrez le nom de la société', 'wp-crowdfunding'),
                      'value'         => '',
                      'warpclass'     => 'fi_reg_pm',
                      'class'         => '',
                      'autocomplete'  => 'off',
                  ),
                  array(
                      'id'            => 'fi_company_status',
                      'label'         => __( "Status de la société" , "wp-crowdfunding" ),
                      'type'          => 'select',
                      'placeholder'   => __('Entrez le status de la société', 'wp-crowdfunding'),
                      'value'         => '',
                      'warpclass'     => 'fi_reg_pm',
                      'class'         => '',
                      'autocomplete'  => 'off',
                      'choices'       => ['S.A.','S.P.R.L.','S.C.R.L.','A.S.B.L','SPRL - Starter','SCRI','SNC','SCS']
                  ),
                  array(
                      'id'            => 'fi_company_number',
                      'label'         => __( "Numéro BCE de la société" , "wp-crowdfunding" ),
                      'type'          => 'text',
                      'placeholder'   => __('Entrez le numéro BCE de la société', 'wp-crowdfunding'),
                      'value'         => '',
                      'warpclass'     => 'fi_reg_pm',
                      'class'         => '',
                      'autocomplete'  => 'off',
                  ),
                  array(
                      'id'            => 'fi_user_address',
                      'label'         => __( "Adresse" , "wp-crowdfunding" ),
                      'type'          => 'text',
                      'placeholder'   => __('Entrez l\'adresse', 'wp-crowdfunding'),
                      'value'         => '',
                      'class'         => '',
                      'warpclass'     => '',
                      'autocomplete'  => 'off',
                  ),
                  array(
                      'id'            => 'email',
                      'label'         => __( "Email *" , "wp-crowdfunding" ),
                      'type'          => 'text',
                      'placeholder'   => __('Enter Email', 'wp-crowdfunding'),
                      'value'         => '',
                      'warpclass'     => '',
                      'class'         => 'required',
                      'autocomplete'  => 'off',
                  ),
 /*
                    array(
                        'id'            => 'username',
                        'label'         => __( "Username *" , "wp-crowdfunding" ),
                        'type'          => 'text',
                        'placeholder'   => __('Enter Username', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => 'required',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                    ),*/
                    array(
                        'id'            => 'password',
                        'label'         => __('Password *', 'wp-crowdfunding'),
                        'type'          => 'password',
                        'placeholder'   => __('Enter Password', 'wp-crowdfunding'),
                        'class'         => 'required',
                        'warpclass'     => 'wpneo-first-half',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'password2',
                        'label'         => __('Password *', 'wp-crowdfunding'),
                        'type'          => 'password',
                        'placeholder'   => __('Répéter mot de passe', 'wp-crowdfunding'),
                        'class'         => 'required',
                        'warpclass'     => 'wpneo-second-half',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'category',
                        'label'         => __('Secteurs d\'intérêt', 'wp-crowdfunding'),
                        'type'          => 'category',
                        'placeholder'   => __('Secteurs', 'wp-crowdfunding'),
                        'class'         => '',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'website',
                        'label'         => __( "Website" , "wp-crowdfunding" ),
                        'type'          => 'text',
                        'placeholder'   => __('Enter Website', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => '',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'fi_iban',
                        'label'         => __( "Numéro de compte (IBAN)" , "wp-crowdfunding" ),
                        'type'          => 'text',
                        'placeholder'   => __('Entrez votre numéro de compte IBAN', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => '',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'fi_newsletter',
                        'label'         => __( "Recevoir Newsletter" , "wp-crowdfunding" ),
                        'type'          => 'checkbox',
                        'placeholder'   => __('', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => '',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                        'checked'       => 'checked',
                    ),
                    array(
                        'id'            => 'conditions',
                        'label'         => __( $cguLink , "wp-crowdfunding" ),
                        'type'          => 'checkbox',
                        'placeholder'   => __('', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => '',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                        'checked'       => '',
                    ),
                    /*
                    array(
                        'id'            => 'nickname',
                        'label'         => __( "Nickname" , "wp-crowdfunding" ),
                        'type'          => 'text',
                        'placeholder'   => __('Enter Nickname', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => '',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                    ),
                    array(
                        'id'            => 'bio',
                        'label'         => __( "About / Bio" , "wp-crowdfunding" ),
                        'type'          => 'textarea',
                        'placeholder'   => __('Enter About / Bio', 'wp-crowdfunding'),
                        'value'         => '',
                        'class'         => '',
                        'warpclass'     => '',
                        'autocomplete'  => 'off',
                    )
                    */
                );

                $wpneo_user_regisration_meta = apply_filters('wpneo_user_registration_fields',$wpneo_user_regisration_meta_array);

                foreach($wpneo_user_regisration_meta as $item){ ?>
                    <div class="wpneo-single <?php echo (isset($item['warpclass'])? $item['warpclass'] : "" ); ?>">
                    <div class="wpneo-name"><?php echo (isset($item['label'])? $item['label'] : "" ); ?></div>
                    <div class="wpneo-fields">
                    <?php
                    switch ($item['type']){
                        case 'text':
                          echo '<input type="text" id="'.$item['id'].'" autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'" name="'.$item['id'].'" placeholder="'.$item['placeholder'].'">';
                            break;
                        case 'password':
                          echo '<input type="password" id="'.$item['id'].'" autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'" name="'.$item['id'].'" placeholder="'.$item['placeholder'].'">';
                            break;
                        case 'textarea':
                          echo '<textarea id="'.$item['id'].'" autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'" name="'.$item['id'].'" ></textarea>';
                            break;
                        case 'submit':
                          echo '<input type="submit" id="'.$item['id'].'"  class="'.$item['class'].'" name="'.$item['id'].'" />';
                            break;
                            //fincrowd
                        case 'physical':
                            echo '<input type="radio" id="fi_reg_physical_person"  class="'.$item['class'].'" name="'.$item['id'].'" value="physical" checked />'.__('Personne Physique', 'wp-crowdfunding');
                            echo '</br>';
                            echo '<input type="radio" id="fi_reg_society"  class="'.$item['class'].'" name="'.$item['id'].'" value="society" />'.__('Personne Morale', 'wp-crowdfunding');
                            break;
                        case 'birthday':
                          echo '<input type="text" id="fi_birthday" autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'" name="fi_birthday" placeholder="'.$item['placeholder'].'">';
                            break;
                        case 'category':
                            $all_cat = get_terms('product_cat',array( 'hide_empty' => false ) );
                            foreach ($all_cat as $value) {
                              //check if the category belongs to the product
                                $selected = "";//($value->name inArray($category)) ? 'checked':'';//TO prefill at some point ? not sure
                                echo '<input type="checkbox" name="fi_category[]" value="'.$value->slug.'" '.$selected.'>'.$value->name.'<br>';
                            }
                            break;
                        case 'checkbox':
                          echo '<input type="checkbox" id="'.$item['id'].'" autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'" name="'.$item['id'].'" placeholder="'.$item['placeholder'].'" '.$item['checked'].'>';
                            break;
                        case 'select':
                          echo '<select id="'.$item['id'].'" autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'" name="'.$item['id'].'">';
                          foreach ($item['choices'] as $choice) {
                            echo '<option value="'.$choice.'">'.$choice.'</option>';
                          }
                          echo '</select>';
                          break;
                        case 'shortcode':
                          echo do_shortcode($item['shortcode']);
                            break;
                    } ?>
                    </div>
                    </div>
                <?php } ?>



                <div class="wpneo-single wpneo-register">
                    <a href="<?php echo get_home_url(); ?>" class="wpneo-cancel-campaign"><?php _e("Cancel","wp-crowdfunding"); ?></a>
                    <input type="hidden" name="action" value="wpneo_crowdfunding_registration_action" />
                    <input type="hidden" name="current_page" value="<?php echo get_the_permalink(); ?>" />
                    <input type="submit" class="wpneo-submit-campaign" id="user-registration-btn" value="<?php _e('Sign UP', 'wp-crowdfunding'); ?>" name="submits" />
                </div>

            </form>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD0qS90zf49aZHK0dFxwdH8IcyIijJJv3k&libraries=places"></script>
        </div>
        <?php
    }
    return ob_get_clean();
}
