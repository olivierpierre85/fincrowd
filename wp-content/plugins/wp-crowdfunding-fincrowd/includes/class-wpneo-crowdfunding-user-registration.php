<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('Wpneo_Crowdfunding_User_Registration')) {

    class Wpneo_Crowdfunding_User_Registration{

        /**
         * @var null
         *
         * Instance of this class
         */
        protected static $_instance = null;

        /**
         * @return null|Wpneo_Crowdfunding
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Wpneo_Crowdfunding constructor.
         *
         * @hook
        */
        public function __construct() {
            add_action( 'init',                                             array($this, 'wpneo_registration_function') );
            add_action( 'wp_ajax_wpneo_crowdfunding_registration_action',   array($this, 'wpneo_registration_function') );

            //Fincrowd new fields
            add_action( 'show_user_profile', array($this,'fincrowd_extra_profile_fields') );
            add_action( 'edit_user_profile', array($this,'fincrowd_extra_profile_fields') );

            add_action( 'personal_options_update', array($this,'fincrowd_save_extra_profile_fields_check') );
            add_action( 'edit_user_profile_update', array($this,'fincrowd_save_extra_profile_fields_check') );
            add_action( 'wpneo_crowdfunding_after_user_registration',array($this,'fincrowd_save_extra_profile_fields') ) ;

            //save extra fields in dashboard update ?
            add_action( 'wpneo_crowdfunding_after_save_dashboard',array($this,'fincrowd_save_extra_profile_fields') ) ;
        }

        //fincrowd
        function fincrowd_extra_profile_fields( $user ) {
          //WARNING Keep consistant with registration.php && Dashboard.php
          ?>
          	<h3>Fincrowd</h3>

          	<table class="form-table">
              <tr>
                <th><label>Personne Physique ou morale</label></th>
                <td>
                  <input type="radio" id="fi_reg_physical_person"   name="fi_reg_type_person" value="physical" <?php if(get_the_author_meta( 'physical_person', $user->ID )){echo " checked ";} ?> />
                  <?php echo __('Personne Physique', 'wp-crowdfunding');?>
                  </br>
                  <input type="radio" id="fi_reg_society"   name="fi_reg_type_person" value="society" <?php if(! get_the_author_meta( 'physical_person', $user->ID )){echo " checked ";} ?> />
                  <?php echo __('Personne Morale', 'wp-crowdfunding');?>
                </td>
              </tr>
              <tr>
                <th><label for="fi_birthday">Date de Naissance (PP)</label></th>
                <td>
                  <input type="text" name="fi_birthday" id="fi_birthday" value="<?php echo esc_attr( get_the_author_meta( 'birthday', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
              </tr>
              <tr>
                <th><label for="fi_iban">IBAN (PM)</label></th>
                <td>
                  <input type="text" name="fi_iban" id="fi_iban" value="<?php echo esc_attr( get_the_author_meta( 'fi_iban', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
              </tr>
              <tr>
                <th><label for="fi_user_address">Adresse</label></th>
                <td>
                  <textarea name="fi_user_address" id="fi_user_address" class="regular-text" /><?php echo esc_attr( get_the_author_meta( 'fi_user_address', $user->ID ) ); ?></textarea><br />
                </td>
              </tr>
              <tr>
                <th><label for="fi_company_name">Nom de la société (PM)</label></th>
                <td>
                  <input type="text" name="fi_company_name" id="fi_company_name" value="<?php echo esc_attr( get_the_author_meta( 'fi_company_name', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
              </tr>
              <tr>
                <th><label for="fi_company_status">Status de la société (PM)</label></th>
                <td>
                  <select name="fi_company_status" id="fi_company_status" >
                  <?php
                  $all_cat = ['S.A.','S.P.R.L.','S.C.R.L.','A.S.B.L','SPRL - Starter','SCRI','SNC','SCS'];
                  foreach ($all_cat as $value) {
                    //check if the category belongs to the product
                      if(get_the_author_meta( 'fi_company_status', $user->ID ) == $value) {
                        $selected = " selected";
                      } else {
                        $selected = "";
                      }
                      echo '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
                  }
                  ?>
                  </select>
                </td>
              </tr>
              <tr>
                <th><label for="fi_company_responsible_status">Status du responsable de la société(PM)</label></th>
                <td>
                  <input type="text" name="fi_company_responsible_status" id="fi_company_responsible_status" value="<?php echo esc_attr( get_the_author_meta( 'fi_company_responsible_status', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
              </tr>
              <tr>
                <th><label for="fi_company_number">Numéro BCE de la société (PM)</label></th>
                <td>
                  <input type="text" name="fi_company_number" id="fi_company_number" value="<?php echo esc_attr( get_the_author_meta( 'fi_company_number', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
              </tr>


              <tr>
                <th><label>Secteurs d'intérêts</label></th>
                <td>
                  <?php
                  $all_cat = get_terms('product_cat',array( 'hide_empty' => false ) );
                  $cat_user = explode(";",get_the_author_meta( 'fi_category', $user->ID ));
                  foreach ($all_cat as $value) {
                    //check if the category belongs to the product

                      if(in_array($value->slug, $cat_user) ){
                        $selected = "checked";
                      } else {
                        $selected = "";
                      }
                      echo '<input type="checkbox" name="fi_category[]" value="'.$value->slug.'" '.$selected.'>'.$value->name.'<br>';
                  }

                   ?>
                </td>
              </tr>
              <tr>
                <th><label>Abonné à la Newsletter</label></th>
                <td>
                  <input type="checkbox" id="fi_newsletter"   name="fi_newsletter"  <?php if(get_the_author_meta( 'fi_newsletter', $user->ID )){echo " checked ";} ?> />
                </td>
              </tr>
          	</table>
          <?php
          }

          //fincrowd
          function fincrowd_save_extra_profile_fields_check( $user_id ) {
            if ( !current_user_can( 'edit_user', $user_id ) )
          		return false;
          	$this->fincrowd_save_extra_profile_fields( $user_id );
          }

          //All new fields for fincrowd
          function fincrowd_save_extra_profile_fields( $user_id ) {

            if(isset($_POST['fi_reg_type_person']) && $_POST['fi_reg_type_person'] == 'society' ){
              update_user_meta( $user_id, 'physical_person', false);
            } else {
              update_user_meta( $user_id, 'physical_person', true );
            }

            if(isset($_POST['fi_birthday'])){
              //TODO check for admin part ?
              update_user_meta( $user_id, 'birthday', $_POST['fi_birthday'] );
            }

            if(isset($_POST['fi_iban'])){
              update_user_meta( $user_id, 'fi_iban', $_POST['fi_iban'] );
            }

            //adresse
            if(isset($_POST['fi_user_address'])){
              update_user_meta( $user_id, 'fi_user_address', $_POST['fi_user_address'] );
            }
            //nom société
            if(isset($_POST['fi_company_name'])){
              update_user_meta( $user_id, 'fi_company_name', $_POST['fi_company_name'] );
            }

            //Status societe
            if(isset($_POST['fi_company_status'])){
              update_user_meta( $user_id, 'fi_company_status', $_POST['fi_company_status'] );
            }

            //status responsable
            if(isset($_POST['fi_company_responsible_status'])){
              update_user_meta( $user_id, 'fi_company_responsible_status', $_POST['fi_company_responsible_status'] );
            }

            //num bce
            if(isset($_POST['fi_company_number'])){
              update_user_meta( $user_id, 'fi_company_number', $_POST['fi_company_number'] );
            }

            if( $_POST['fi_category'] ){
              $category = $_POST['fi_category'];
              update_user_meta( $user_id, 'fi_category', implode(";",$category) );
              //TODO link category to user
            } else {
              update_user_meta( $user_id, 'fi_category', '' );
            }

            if(isset($_POST['fi_newsletter'])){
              update_user_meta( $user_id, 'fi_newsletter', true );
            } else {
              update_user_meta( $user_id, 'fi_newsletter', false );
            }
          }



        // register a new user
        function wpneo_registration_function() {

            if( wp_verify_nonce(wpneo_post('_wpnonce'),'wpneo-nonce-registration') ){

                //Add some option
                do_action('wpneo_before_user_registration_action');

                $username = $password = $password2 = $email = $website = $first_name = $last_name = $nickname = $bio = '';
                $birthday = '';
                $conditions   = false ;
                // sanitize user form input
                //$username   =   sanitize_user($_POST['username']);
                $password   =   sanitize_text_field($_POST['password']);
                $email      =   sanitize_email($_POST['email']);
                $website    =   sanitize_url($_POST['website']);
                $first_name =   sanitize_text_field($_POST['fname']);
                $last_name  =   sanitize_text_field($_POST['lname']);
                //$nickname   =   sanitize_text_field($_POST['nickname']);
                //$bio        =   implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['bio'])));
                //fincrowd
                $birthday   = sanitize_text_field($_POST['fi_birthday']);
                $username   =   $email;
                $password2   =   sanitize_text_field($_POST['password2']);
                $iban   =   sanitize_text_field($_POST['fi_iban']);
                $person_type = sanitize_text_field($_POST['fi_reg_type_person']);
                $fi_company_responsible_status = sanitize_text_field($_POST['fi_company_responsible_status']);
                $fi_company_name = sanitize_text_field($_POST['fi_company_name']);
                $fi_company_status = sanitize_text_field($_POST['fi_company_status']);
                $fi_company_number = sanitize_text_field($_POST['fi_company_number']);
                $fi_user_address = sanitize_text_field($_POST['fi_user_address']);

                if(isset($_POST['conditions'])){
                    $conditions   =   true;
                }


                $this->wpneo_registration_validation(
                    $username ,
                    $password ,
                    $password2 ,
                    $email ,
                    $website ,
                    $first_name ,
                    $last_name ,
                    $nickname ,
                    $bio,
                    $birthday,
                    $conditions,
                    $iban,
                    $person_type,
                    $fi_company_responsible_status,
                    $fi_company_name,
                    $fi_company_status,
                    $fi_company_number,
                    $fi_user_address
                );
                $this->wpneo_complete_registration( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio );
            }else{
                global $reg_errors;
                $reg_errors = new WP_Error;
                $reg_errors->add('security', __('Security Error','wp-crowdfunding'));
            }
        }

        function wpneo_complete_registration( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio ) {
            global $reg_errors;
            if ( count($reg_errors->get_error_messages()) < 1 ) {
                $userdata = array(
                    'user_login'    =>  $username,
                    'user_email'    =>  $email,
                    'user_pass'     =>  $password,
                    'user_url'      =>  $website,
                    'first_name'    =>  $first_name,
                    'last_name'     =>  $last_name,
                    'nickname'      =>  $nickname,
                    'description'   =>  $bio,
                );
                $user_id = wp_insert_user( $userdata );

                //On success
                if ( ! is_wp_error( $user_id ) ) {
                    do_action( 'wpneo_crowdfunding_after_user_registration', $user_id );
                    //$redirect = get_permalink(get_option('wpneo_crowdfunding_dashboard_page_id'));

                    $user = get_user_by( 'id', $user_id );
                    if( $user ) {
                        wp_set_current_user( $user_id, $user->user_login );
                        wp_set_auth_cookie( $user_id );
                        do_action( 'wp_login', $user->user_login );
                    }

                    $redirect = get_home_url();

                    die(json_encode(array('success'=> 1, 'message' => __('Registration complete.', 'wp-crowdfunding'), 'redirect' => $redirect )));
                } else {
                    $errors = '';
                    if ( is_wp_error( $reg_errors ) ) {
                        foreach ( $reg_errors->get_error_messages() as $error ) {
                            $errors .= '<strong>'.__('ERROR','wp-crowdfunding').'</strong>:'.$error.'<br />';
                        }
                    }
                    die(json_encode(array('success'=> 0, 'message' => $errors )));
                }
            } else {
                $errors = '';
                if ( is_wp_error( $reg_errors ) ) {
                    foreach ( $reg_errors->get_error_messages() as $error ) {
                        $errors .= '<strong>'.__('ERROR','wp-crowdfunding').'</strong>:'.$error.'<br />';
                    }
                }
                die(json_encode(array('success'=> 0, 'message' => $errors )));
            }
        }
        //Fincrowd
        function checkIBAN($iban)
        {
            $iban = strtolower(str_replace(' ','',$iban));
            $Countries = array('al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24);
            $Chars = array('a'=>10,'b'=>11,'c'=>12,'d'=>13,'e'=>14,'f'=>15,'g'=>16,'h'=>17,'i'=>18,'j'=>19,'k'=>20,'l'=>21,'m'=>22,'n'=>23,'o'=>24,'p'=>25,'q'=>26,'r'=>27,'s'=>28,'t'=>29,'u'=>30,'v'=>31,'w'=>32,'x'=>33,'y'=>34,'z'=>35);

            if(strlen($iban) == $Countries[substr($iban,0,2)]){

                $MovedChar = substr($iban, 4).substr($iban,0,4);
                $MovedCharArray = str_split($MovedChar);
                $NewString = "";

                foreach($MovedCharArray AS $key => $value){
                    if(!is_numeric($MovedCharArray[$key])){
                        $MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
                    }
                    $NewString .= $MovedCharArray[$key];
                }

                if(bcmod($NewString, '97') == 1)
                {
                    return TRUE;
                }
                else{
                    return FALSE;
                }
            }
            else{
                return FALSE;
            }
        }

        function wpneo_registration_validation( $username, $password,$password2, $email, $website, $first_name, $last_name, $nickname, $bio,
          $birthday,$conditions,$iban,$person_type,$fi_company_responsible_status,$fi_company_name,$fi_company_status,$fi_company_number,$fi_user_address )  {
            global $reg_errors;
            $reg_errors = new WP_Error;

            if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
                $reg_errors->add('field', __('Required form field is missing','wp-crowdfunding'));
            }
/*
            if ( strlen( $username ) < 4 ) {
                $reg_errors->add('username_length', __('Username too short. At least 4 characters is required','wp-crowdfunding'));
            }
*/
            if ( username_exists( $username ) )
                $reg_errors->add('user_name', __('Sorry, that username already exists!','wp-crowdfunding'));
/*
            if ( !validate_username( $username ) ) {
                $reg_errors->add('username_invalid', __('Sorry, the username you entered is not valid','wp-crowdfunding'));
            }
*/
            if ( strlen( $password ) < 6 ) {
                $reg_errors->add('password', __('Password length must be greater than 6','wp-crowdfunding'));
            }

            if ( !is_email( $email ) ) {
                $reg_errors->add('email_invalid', __('Email is not valid','wp-crowdfunding'));
            }

            if ( email_exists( $email ) ) {
                $reg_errors->add('email', __('Email Already in use','wp-crowdfunding'));
            }

            if ( !empty( $website ) ) {
                if ( !filter_var($website, FILTER_VALIDATE_URL) ) {
                    $reg_errors->add('website', __('Website is not a valid URL','wp-crowdfunding'));
                }
            }

            //Fincrowd
            //Societé
            if($person_type == 'society'){
              if ( empty($fi_company_responsible_status) ) {
                  $reg_errors->add('company_responsible_status', __('Veuillez introduire la qualification de la personne responsable','wp-crowdfunding'));
              }
              if ( empty( $fi_company_name ) ) {
                  $reg_errors->add('company_name', __('Veuillez introduire le nom de la société','wp-crowdfunding'));
              }
              if ( empty( $fi_company_status ) ) {
                  $reg_errors->add('company_status', __('Veuillez introduire le status de la société','wp-crowdfunding'));
              }
              //TODO olpi validité du numéro BCE
              if ( empty( $fi_company_number ) ) {
                  $reg_errors->add('company_number', __('Veuillez introduire le numéro BCE de la société','wp-crowdfunding'));
              }
            } else {
              //Personne physique
              if ( ! (DateTime::createFromFormat('d/m/Y', $birthday)) !== FALSE )  {
                  $reg_errors->add('birthday_invalid', __('Date de naissance non valide','wp-crowdfunding'));
              }
            }

            if ( empty( $fi_user_address ) ) {
                $reg_errors->add('adresse', __('Veuillez introduire une adresse','wp-crowdfunding'));
            }


            if ( $password != $password2 ) {
                $reg_errors->add('password', __('Deux mots de passe différents','wp-crowdfunding'));
            }

            if ( ! $conditions ) {
                $reg_errors->add('conditions', __('Vous devez accepter les conditions générales','wp-crowdfunding'));
            }

            //TODO olpi iban validation don't work
            //if($this->checkIBAN($iban)){
            //    $reg_errors->add('iban', __('Le numéro de compte n\'a pas un format correct IBAN','wp-crowdfunding'));
            //}

        }

    }
}
//Call base class
Wpneo_Crowdfunding_User_Registration::instance();
