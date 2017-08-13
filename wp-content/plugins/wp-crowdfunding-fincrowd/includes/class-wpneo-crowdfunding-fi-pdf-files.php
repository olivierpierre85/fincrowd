<?php
/**
 * Pdf creations
 *
 * @plugin wp-crowdfunding
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists('Wpneo_Crowdfunding_Fi_Pdf')) {
    class Wpneo_Crowdfunding_Fi_Pdf
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
         * Wpneo_Crowdfunding_Fi_Pdf constructor.
         */
        public function __construct(){
            require_once('fpdf\fpdf.php');
            require_once('fpdi\fpdi.php');


            //start fincrowd mails
            add_action('wpneo_fi_pdf_validate_campaign', array($this,'wpneo_fi_pdf_validate_campaign'));
            //endfincrowd

        }


        /**
         * @param $campaign_id
         * FINCROWD FCT
         * Create PDF after validate campaign
         */
        function wpneo_fi_pdf_validate_campaign($campaign_id){
          if ( get_option( 'wpneo_enable_validate_campaign_email' ) == 'true' ) {

              global $wpdb;

              $product        = wc_get_product($campaign_id);

              if ($product->product_type === 'crowdfunding') {
                //1 PDF pour l'emprunteur

                $pdfDir = plugin_dir_path( __DIR__ ).'contrats\\';
                $lenderFileName = "CampagneNamez".$campaign_id."Emprunteur".".pdf";

                //PDF pour les prêteurs (un par user)
                $lenderPdf =& new FPDI();
                // get the page count
                $pageCount = $lenderPdf->setSourceFile($pdfDir."convention_de_pret_modele.pdf");
                // iterate through all pages
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    // import a page
                    $templateId = $lenderPdf->importPage($pageNo);
                    // get the size of the imported page
                    $size = $lenderPdf->getTemplateSize($templateId);

                    // create a page (landscape or portrait depending on the imported page size)
                    if ($size['w'] > $size['h']) {
                        $lenderPdf->AddPage('L', array($size['w'], $size['h']));
                    } else {
                        $lenderPdf->AddPage('P', array($size['w'], $size['h']));
                    }

                    // use the imported page
                    $lenderPdf->useTemplate($templateId);

                    $lenderPdf->SetFont('Helvetica');
                    $lenderPdf->SetXY(5, 5);
                    $lenderPdf->Write(8, 'YAHA je suis un chat');
                }

                $lenderPdf->Output($pdfDir.$lenderFileName,'F');

              }
          }

        }


    }
}
Wpneo_Crowdfunding_Fi_Pdf::instance();
