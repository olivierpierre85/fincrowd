<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$current_user = wp_get_current_user();

$html .= '<div class="wpneo-content">';

    $html .= '<form id="wpneo-dashboard-form" action="" method="" class="wpneo-form">';



        // type
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Personne physique ou autre:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';

            if(get_the_author_meta( 'physical_person', $current_user->ID )){
              $checkedPhysical = " checked ";
              $checkedSociety = " ";
            } else {
              $checkedSociety = " checked ";
              $checkedPhysical = " ";
            }
            $html .= '<input type="radio" id="fi_reg_physical_person"  name="fi_reg_type_person" value="physical"'.$checkedPhysical.' disabled />';
            $html .= __('Personne Physique', 'wp-crowdfunding');
            $html .= '<br/>';
            $html .= '<input type="radio" id="fi_reg_society"   name="fi_reg_type_person" value="society"'.$checkedSociety.' disabled />';
            $html .= __('Personne Morale', 'wp-crowdfunding');

            $html .= '</div>';
        $html .= '</div>';

        // User Name
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Username:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<input type="hidden" name="action" value="wpneo_dashboard_form">';
                $html .= '<input type="text" name="username" value="'.$current_user->user_login.'" disabled>';
            $html .= '</div>';
        $html .= '</div>';

        // Email Address
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Email:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<input type="email" name="email" value="'.$current_user->user_email.'" disabled>';
            $html .= '</div>';
        $html .= '</div>';

        // First Name
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "First Name:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<input type="text" name="firstname" value="'.$current_user->user_firstname.'" disabled>';
            $html .= '</div>';
        $html .= '</div>';

        // Last Name
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Last Name:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<input type="text" name="lastname" value="'.$current_user->user_lastname.'" disabled>';
            $html .= '</div>';
        $html .= '</div>';


        // Website
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Website:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<input type="text" name="website" value="'.$current_user->user_url.'" disabled>';
            $html .= '</div>';
        $html .= '</div>';


        if(get_the_author_meta( 'physical_person', $current_user->ID )){
        //si personne physique
          //Fincrowd - Birthday
          $html .= '<div class="wpneo-single">';
              $html .= '<div class="wpneo-name float-left">';
                  $html .= '<p>'.__( "Date de Naissance:" , "wp-crowdfunding" ).'</p>';
              $html .= '</div>';
              $html .= '<div class="wpneo-fields float-right">';
                  $html .= '<input type="text" name="fi_birthday" id="fi_birthday" value="'.esc_attr( get_the_author_meta( 'birthday', $current_user->ID ) ).'" disabled/>';
              $html .= '</div>';
          $html .= '</div>';



        } else {
          //si société
          //Status responsable
          $html .= '<div class="wpneo-single">';
              $html .= '<div class="wpneo-name float-left">';
                  $html .= '<p>'.__( "Status du Responsable" , "wp-crowdfunding" ).'</p>';
              $html .= '</div>';
              $html .= '<div class="wpneo-fields float-right">';
                  $html .= '<input type="text" name="fi_company_responsible_status" id="fi_company_responsible_status" value="'.esc_attr( get_the_author_meta( 'fi_company_responsible_status', $current_user->ID ) ).'" disabled/>';
              $html .= '</div>';
          $html .= '</div>';
          //nom société
          $html .= '<div class="wpneo-single">';
              $html .= '<div class="wpneo-name float-left">';
                  $html .= '<p>'.__( "Nom de la société" , "wp-crowdfunding" ).'</p>';
              $html .= '</div>';
              $html .= '<div class="wpneo-fields float-right">';
                  $html .= '<input type="text" name="fi_company_name" id="fi_company_name" value="'.esc_attr( get_the_author_meta( 'fi_company_name', $current_user->ID ) ).'" disabled/>';
              $html .= '</div>';
          $html .= '</div>';
          //status société
          // $html .= '<div class="wpneo-single">';
          //     $html .= '<div class="wpneo-name float-left">';
          //         $html .= '<p>'.__( "Status de la société" , "wp-crowdfunding" ).'</p>';
          //     $html .= '</div>';
          //     $html .= '<div class="wpneo-fields float-right">';
          //         $html .= '<input type="text" name="fi_company_status" id="fi_company_status" value="'.esc_attr( get_the_author_meta( 'fi_company_status', $current_user->ID ) ).'" disabled/>';
          //     $html .= '</div>';
          // $html .= '</div>';
          $all_cat = ['S.A.','S.P.R.L.','S.C.R.L.','A.S.B.L','SPRL - Starter','SCRI','SNC','SCS'];
          $html .= '<div class="wpneo-single">';
              $html .= '<div class="wpneo-name float-left">';
                  $html .= '<p>'.__( "Status de la société:" , "wp-crowdfunding" ).'</p>';
              $html .= '</div>';
              $html .= '<div class="wpneo-fields float-right">';
              $html .= '<select name="fi_company_status" id="fi_company_status" disabled>';
              foreach ($all_cat as $value) {
                //check if the category belongs to the product
                  if(get_the_author_meta( 'fi_company_status', $current_user->ID ) == $value) {
                    $selected = " selected";
                  } else {
                    $selected = "";
                  }
                  $html .= '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
              }

              $html .= '</select>';
              $html .= '</div>';
          $html .= '</div>';
          //numéro société
          $html .= '<div class="wpneo-single">';
              $html .= '<div class="wpneo-name float-left">';
                  $html .= '<p>'.__( "Numéro BCE de la société" , "wp-crowdfunding" ).'</p>';
              $html .= '</div>';
              $html .= '<div class="wpneo-fields float-right">';
                  $html .= '<input type="text" name="fi_company_number" id="fi_company_number" value="'.esc_attr( get_the_author_meta( 'fi_company_number', $current_user->ID ) ).'" disabled/>';
              $html .= '</div>';
          $html .= '</div>';

        }

        //Numéro de compte IBAN
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Numéro de compte IBAN:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<input type="text" name="fi_iban" id="fi_iban" value="'.esc_attr( get_the_author_meta( 'fi_iban', $current_user->ID ) ).'" disabled/>';
            $html .= '</div>';
        $html .= '</div>';

        //adresse
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Adresse:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                //$html .= '<input type="text" name="fi_user_address" id="fi_user_address" value="'.esc_attr( get_the_author_meta( 'fi_user_address', $current_user->ID ) ).'" disabled/>';
                $html .= '<textarea name="fi_user_address" id="fi_user_address" disabled/>'.esc_attr( get_the_author_meta( 'fi_user_address', $current_user->ID ) ).'</textarea>';
            $html .= '</div>';
        $html .= '</div>';

        //Fincrowd - Centre d'intérets
        $all_cat = get_terms('product_cat',array( 'hide_empty' => false ) );
        $cat_user = explode(";",get_the_author_meta( 'fi_category', $current_user->ID ));
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Centres d'intérêts:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
            foreach ($all_cat as $value) {
              //check if the category belongs to the product

                if(in_array($value->slug, $cat_user) ){
                  $selected = "checked";
                } else {
                  $selected = "";
                }

                $html .= '<input type="checkbox" name="fi_category[]" value="'.$value->slug.'" '.$selected.' disabled>'.$value->name.'<br>';
            }

            $html .= '</div>';
        $html .= '</div>';

  //Fincrowd - Newsletter
  $checked = '';
  if(get_the_author_meta( 'fi_newsletter', $current_user->ID ))
  {
    $checked = " checked ";
  }

  $html .= '<div class="wpneo-single">';
      $html .= '<div class="wpneo-name float-left">';
          $html .= '<p>'.__( "Abonné à la Newsletter:" , "wp-crowdfunding" ).'</p>';
      $html .= '</div>';
      $html .= '<div class="wpneo-fields float-right">';
        $html .= '<input type="checkbox" id="fi_newsletter"   name="fi_newsletter"  '.$checked.' disabled/>';
      $html .= '</div>';
  $html .= '</div>';

/*
        // Bio Info
        $html .= '<div class="wpneo-single">';
            $html .= '<div class="wpneo-name float-left">';
                $html .= '<p>'.__( "Bio:" , "wp-crowdfunding" ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wpneo-fields float-right">';
                $html .= '<textarea name="description" rows="3" disabled>'.$current_user->description.'</textarea>';
            $html .= '</div>';
        $html .= '</div>';
*/
/*
        $html .= '<h3>'.__('Payment Info:', 'wp-crowdfunding').'</h3>';
*/
        ob_start();
        do_action('wpneo_crowdfunding_dashboard_after_dashboard_form');
        $html .= ob_get_clean();

        $html .= wp_nonce_field( 'wpneo_crowdfunding_dashboard_form_action', 'wpneo_crowdfunding_dashboard_nonce_field', true, false );

        //Save Button
        $html .= '<div class="wpneo-buttons-group float-right">';
            $html .= '<button id="wpneo-edit" class="wpneo-edit-btn">'.__( "Edit" , "wp-crowdfunding" ).'</button>';
            $html .= '<button id="wpneo-dashboard-btn-cancel" class="wpneo-cancel-btn wpneo-hidden" type="submit">'.__( "Cancel" , "wp-crowdfunding" ).'</button>';
            $html .= '<button id="wpneo-dashboard-save" class="wpneo-save-btn wpneo-hidden" type="submit">'.__( "Save" , "wp-crowdfunding" ).'</button>';
        $html .= '</div>';
        $html .= '<div class="clear-float"></div>';

    $html .= '</form>';

$html .= '</div>';
