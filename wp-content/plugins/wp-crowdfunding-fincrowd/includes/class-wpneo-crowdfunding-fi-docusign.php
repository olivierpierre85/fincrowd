<?php
/**
 * Pdf creations
 *
 * @plugin wp-crowdfunding
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('Wpneo_Crowdfunding_Fi_Docusign')) {
    class Wpneo_Crowdfunding_Fi_Docusign
    {
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
         * Wpneo_Crowdfunding_Fi_Docusign constructor.
         */
        public function __construct() {
            //require_once('fpdf\fpdf.php');
            //require_once('fpdi\fpdi.php');

            //start fincrowd mails
            add_action('wpneo_fi_docusign_validate_campaign', array($this,'wpneo_fi_docusign_validate_campaign'));
            //endfincrowd

        }


        //FILL all tabs for a signer
        public function getTextTabs($idDocument,$descLender,$interestDuration,
          $interestRate,$conventionNumber,$totalAmount,$descBorrower,$dateValidation) {
        	//$idDocument = 1;
        	return array(
        		array(
        				"tabLabel"=> "descBorrower",
        				"value" => $descBorrower,
        				"locked" => "true",
        				"xPosition" => "35",
        				"yPosition" => "272",
        				"width" => "283",
        				"height" => "132",
        				"documentId" => $idDocument,
        				"pageNumber" => "1"
        		),
        		array(
        				"tabLabel"=> "\\*dateValidation",
        				"value" => $dateValidation,
        				"locked" => "true",
        				"xPosition" => "311",
        				"yPosition" => "187",
        				"documentId" => $idDocument,
        				"pageNumber" => "1"
        		),
        		array(
        				"tabLabel"=> "descLender",
        				"value" => $descLender,
        				"locked" => "true",
        				"xPosition" => "299",
        				"yPosition" => "272",
        				"width" => "283",
        				"height" => "132",
        				"documentId" => $idDocument,
        				"pageNumber" => "1"
        		),
        		array(
        				"tabLabel"=> "interestDuration",
        				"value" => $interestDuration,
        				"locked" => "true",
                "xPosition" => "310",
                "yPosition" => "277",
        				"documentId" => $idDocument,
        				"pageNumber" => "3"
        		),
        		array(
        				"tabLabel"=> "interestRate",
        				"value" => $interestRate,
        				"locked" => "true",
                "xPosition" => "258",
                "yPosition" => "290",
        				"documentId" => $idDocument,
        				"pageNumber" => "3"
        		),
        		array(
        				"tabLabel"=> "\\*conventionNumber",
        				"value" => $conventionNumber,
        				"locked" => "true",
        				"xPosition" => "339",
        				"yPosition" => "660",
        				"documentId" => $idDocument,
        				"pageNumber" => "5"
        		),
        		array(
        				"tabLabel"=> "\\*conventionNumber",
        				"value" => $conventionNumber,
        				"locked" => "true",
        				"xPosition" => "143",
        				"yPosition" => "166",
        				"documentId" => $idDocument,
        				"pageNumber" => "1"
        		),
        		array(
        				"tabLabel"=> "totalAmount",
        				"value" => $totalAmount,
        				"locked" => "true",
        				"xPosition" => "344",
        				"yPosition" => "235",
        				"documentId" => $idDocument,
        				"pageNumber" => "3"
        		),
        		array(
        				"tabLabel"=> "\\*dateValidation2",
        				"value" => $dateValidation,
        				"locked" => "true",
        				"xPosition" => "171",
        				"yPosition" => "451",
        				"documentId" => $idDocument,
        				"pageNumber" => "5"
        		),
            array(
                "value" => 'Naninne',
                "locked" => "true",
                "xPosition" => "156",
        				"yPosition" => "187",
                "documentId" => $idDocument,
                "pageNumber" => "1"
            ),
            array(
                "value" => 'Naninne',
                "locked" => "true",
                "xPosition" => "64",
                "yPosition" => "451",
                "documentId" => $idDocument,
                "pageNumber" => "5"
            ),
        	);

        }

        /**
         * @param $campaign_id
         * Call docusign api after validate campaign to ask every one to sign
         */
        function wpneo_fi_docusign_validate_campaign($campaign_id){

          if ( get_option( 'wpneo_enable_validate_campaign_email' ) == 'true' ) {

              global $wpdb;

              $product        = wc_get_product($campaign_id);

              if ($product->product_type === 'crowdfunding') {
                  $signersB = array();
                  $signersL = array();

                  $campaign_title  = get_post_field( 'post_title', $campaign_id );
                  //First get campaign creator
                  $post_author_id = get_post_field( 'post_author', $campaign_id );
                  $borrower         = get_userdata($post_author_id);

                  $descBorrower = 'La '.get_the_author_meta( 'fi_company_status', $borrower->ID ).' ' .get_the_author_meta( 'fi_company_name', $borrower->ID );
                  $descBorrower .= ' dont le siège social est établi à ' . get_the_author_meta( 'fi_user_address', $borrower->ID );
                  $descBorrower .= ' et immatriculé à la BCE sous le n° '. get_the_author_meta( 'fi_company_number', $borrower->ID );
                  $descBorrower .= ' représentée aux fins de la présente par '. $borrower->display_name .' ( ' . get_the_author_meta( 'fi_company_responsible_status', $borrower->ID ) . ' )';

                  $dateValidation = date("d-m-Y");

                  $signBorrower = array();
                  $documents = array();
                  $documentFileName = getcwd().'/convention_de_pret_modele_v2.pdf';
                  $documentData = base64_encode(file_get_contents($documentFileName));

                  //then get all order for the campaign
                  $post_id = $campaign_id;
                  $order_statuses = array_map( 'esc_sql', (array) get_option( 'wpcl_order_status_select', array('wc-completed') ) );
                  $order_statuses_string = "'" . implode( "', '", $order_statuses ) . "'";
                  $post_id = array_map( 'esc_sql', (array) $post_id );
                  $post_string = "'" . implode( "', '", $post_id ) . "'";

                  $item_sales = $wpdb->get_results( $wpdb->prepare(
                    "SELECT o.ID as order_id, oi.order_item_id FROM
                    {$wpdb->prefix}woocommerce_order_itemmeta oim
                    INNER JOIN {$wpdb->prefix}woocommerce_order_items oi
                    ON oim.order_item_id = oi.order_item_id
                    INNER JOIN $wpdb->posts o
                    ON oi.order_id = o.ID
                    WHERE oim.meta_key = %s
                    AND oim.meta_value IN ( $post_string )
                    AND o.post_status IN ( $order_statuses_string )
                    AND o.post_type NOT IN ('shop_order_refund')
                    ORDER BY o.ID DESC",
                    '_product_id'
                  ));

                  //Then get the author of the pledges
                  $iSigner = 1;
                  foreach ($item_sales as $item) {
                    $order          = new WC_Order($item->order_id);
                    $cart = $order->get_items();

                    $user = $order->get_user();
                    $user_adr = esc_attr( get_the_author_meta( 'fi_user_address', $user->ID ) );

                    $descLender = $user->display_name. " domicilié(s) ". $user_adr ;

                    $interestDuration = get_post_meta( $campaign_id, 'wpneo_fi_interest_rate', true );
                    $interestRate     = get_post_meta( $campaign_id, 'wpneo_fi_loan_duration', true );

                    $conventionNumber = substr ( $campaign_title , 0 , 20 ).'-'.$user->ID ;
                    $totalAmount = $cart[key($cart)]['line_total'];

                    $signersL[] = array(
                      "email" => $user->user_email,
                      "name" => $user->display_name,
                      "recipientId"=> $iSigner,
                      //"roleName" => 'lender',
                      "routingOrder" => "1",
                      "tabs" => array(
                        "signHereTabs" => array(
                            array(
                              "xPosition" => "410",
                              "yPosition" => "507",
                              "documentId" => $iSigner,
                              "pageNumber" => "5"
                            )
                          ),
                          "textTabs" => $this->getTextTabs($iSigner,$descLender,$interestDuration,$interestRate,$conventionNumber,$totalAmount,$descBorrower,$dateValidation)
                        )
                      );

                      //add a field to be signed by borrower for the lender
                      $signBorrower [] = array(
                                          "xPosition" => "93",
                                          "yPosition" => "507",
                                          "documentId" => $iSigner,
                                          "pageNumber" => "5"
                                        );

                      //add a document by lender
                      $documents[] =  array(
                            "documentBase64"=> $documentData,
                            "documentId"=> $iSigner,
                            "fileExtension"=> "pdf",
                            "name"=> "convention_de_pret.pdf"
                          );

                      $iSigner ++;
                  }

                  $signersB[] = array(
                    "email" => $borrower->user_email,
                    "name" => $borrower->display_name,
                    "recipientId"=> "99999",
                    "routingOrder" => "2",
                    //"roleName" => 'borrower',
                    "tabs" => array(
                      "signHereTabs" => $signBorrower,
                    )
                  );

                  $this->sendDocusign(array_merge($signersL,$signersB),$documents);
                }
          }
        }


        /**
         * Really send to docusign based on borrower and lender list
         */
        function sendDocusign($signers,$documents){
          //var TODO fincrowd olpi store somewhere else data docusign
          $url = "https://demo.docusign.net/restapi/v2/login_information"; // change for production

          $email = 'olivierpierre85@gmail.com';	// your account email.
          $password = 'ngi54JL&';		// your account password //not great
          $integratorKey = '5bf42f5f-d4f9-4b62-9c7e-57b2a1775a92'; // your account integrator key, found on (Preferences -> API page)
          //$templateId = '32503515-4bab-482d-95d3-dd0a87f8862e';
          $templateId = '0da1dcd6-4259-4d72-a9f0-e8adb0022a28';
          // Start...

          // construct the authentication header:
          $header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";

          /////////////////////////////////////////////////////////////////////////////////////////////////
          // STEP 1 - Login (to retrieve baseUrl and accountId)
          /////////////////////////////////////////////////////////////////////////////////////////////////
          $curl = curl_init($url);
          curl_setopt($curl, CURLOPT_HEADER, false);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));

          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//TODO not so good

          $json_response = curl_exec($curl);
          $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

          if ( $status != 200 ) {
            //return (['ok' => false, 'errMsg' => "Error calling DocuSign, status is: " . $status]);
            //TODO olpi gerer erreur !!! return curl_error($curl);
          }

          $response = json_decode($json_response, true);
          $accountId = $response["loginAccounts"][0]["accountId"];
          $baseUrl = $response["loginAccounts"][0]["baseUrl"];
          curl_close($curl);

          /////////////////////////////////////////////////////////////////////////////////////////////////
        	// STEP 2 - Create and send
        	/////////////////////////////////////////////////////////////////////////////////////////////////
          //Single template
          // $data = array("accountId" => $accountId,
          // 	"emailSubject" => "DocuSign API - Signature Request from Template",
          // 	"templateId" => $templateId,
          // 	"templateRoles" => $signers ,
          // 	"status" => "sent");

          $data = array (
        			"emailSubject" => "Convention de prêt ",
        			"documents" => $documents,
      			  "recipients" => array(
		              "signers" => $signers
                ),
        		"status" => "sent",
        		"enforceSignerVisibility" =>  true,
        	);



          $data_string = json_encode($data);
          $curl = curl_init($baseUrl . "/envelopes" );
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
          curl_setopt($curl, CURLOPT_HTTPHEADER, array(
          	'Content-Type: application/json',
          	'Content-Length: ' . strlen($data_string),
          	"X-DocuSign-Authentication: $header" )
          );

          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//TODO not so good

          $json_response = curl_exec($curl);
          $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
          if ( $status != 201 ) {

          	//echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
          	//print_r($json_response); echo "\n";
            return($status.'_'.$json_response);
          }

          $response = json_decode($json_response, true);
          $envelopeId = $response["envelopeId"];

        }


    }
}
Wpneo_Crowdfunding_Fi_Docusign::instance();
