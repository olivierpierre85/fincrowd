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
            //start Fincrowd new fields
            add_action( 'show_user_profile', array($this,'fincrowd_extra_profile_fields') );
            add_action( 'edit_user_profile', array($this,'fincrowd_extra_profile_fields') );

            add_action( 'personal_options_update', array($this,'fincrowd_save_extra_profile_fields_check') );
            add_action( 'edit_user_profile_update', array($this,'fincrowd_save_extra_profile_fields_check') );
            add_action( 'wpneo_crowdfunding_after_user_registration',array($this,'fincrowd_save_extra_profile_fields') ) ;

            //save extra fields in dashboard update ?
            add_action( 'wpneo_crowdfunding_after_save_dashboard',array($this,'fincrowd_save_extra_profile_fields') ) ;
            //end Fincrowd
        }

        //start fincrowd extra functions
        function fincrowd_extra_profile_fields( $user ) {
          //WARNING Keep consistant with registration.php && Dashboard.php
          ?>
          	<h3>Fincrowd</h3>

          	<table class="form-table">
          		<tr>
          			<th><label for="fi_birthday">Date de Naissance</label></th>
          			<td>
          				<input type="text" name="fi_birthday" id="fi_birthday" value="<?php echo esc_attr( get_the_author_meta( 'birthday', $user->ID ) ); ?>" class="regular-text" /><br />
          			</td>
          		</tr>
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
          //end Fincrowd

        // register a new user
        function wpneo_registration_function() {

            if( wp_verify_nonce(wpneo_post('_wpnonce'),'wpneo-nonce-registration') ){

                //Add some option
                do_action('wpneo_before_user_registration_action');

                $username = $password = $email = $website = $first_name = $last_name = $nickname = $bio = '';
                //start Fincrowd intiate vars
                $birthday = '';
                $conditions   = false ;
                //end Fincrowd

                // sanitize user form input
                //start change var fincrowd
                // $username   =   sanitize_user($_POST['username']);
                // $password   =   sanitize_text_field($_POST['password']);
                // $email      =   sanitize_email($_POST['email']);
                // $website    =   esc_url_raw($_POST['website']);
                // $first_name =   sanitize_text_field($_POST['fname']);
                // $last_name  =   sanitize_text_field($_POST['lname']);
                // $nickname   =   sanitize_text_field($_POST['nickname']);
                // $bio        =   implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['bio'])));
                //
                // $this->wpneo_registration_validation(
                //     $username ,
                //     $password ,
                //     $email ,
                //     $website ,
                //     $first_name ,
                //     $last_name ,
                //     $nickname ,
                //     $bio
                // );
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
                    $conditions
                );
                //end change fincrowd

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
                    'description'   =>  $bio
                );
                $user_id = wp_insert_user( $userdata );

                //On success
                if ( ! is_wp_error( $user_id ) ) {
                    do_action( 'wpneo_crowdfunding_after_user_registration', $user_id );
                    $redirect = esc_url(wpneo_post('current_page'));
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
        function wpneo_registration_validation( $username, $password,$password2, $email, $website, $first_name, $last_name, $nickname, $bio, $birthday,$conditions )  {
            global $reg_errors;
            $reg_errors = new WP_Error;

            if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
                $reg_errors->add('field', __('Required form field is missing','wp-crowdfunding'));
            }
/* fincrowd
            if ( strlen( $username ) < 4 ) {
                $reg_errors->add('username_length', __('Username too short. At least 4 characters is required','wp-crowdfunding'));
            }
*/
            if ( username_exists( $username ) )
                $reg_errors->add('user_name', __('Sorry, that username already exists!','wp-crowdfunding'));
/* fincrowd
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

            //start Fincrowd validation
            if ( ! (DateTime::createFromFormat('d/m/Y', $birthday)) !== FALSE && $_POST['fi_reg_type_person'] != 'society')  {
                $reg_errors->add('birthday_invalid', __('Date de naissance non valide','wp-crowdfunding'));
            }

            if ( $password != $password2 ) {
                $reg_errors->add('password', __('Deux mots de passe différents','wp-crowdfunding'));
            }

            if ( ! $conditions ) {
                $reg_errors->add('conditions', __('Vous devez accepter les conditions générales','wp-crowdfunding'));
            }
            //end fincrowd

        }

    }
}
//Call base class
Wpneo_Crowdfunding_User_Registration::instance();