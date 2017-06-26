<?php
    class style2 extends portfolio_blocks {
        public function build_style( $options = array(), $size = array() ) {

            if( !empty( $options ) ) {
                extract( $options );
            }

            // Empty assignment
            $output = '';

            // Portfolio gallery meta value
            $portfolio_thumb = composer_get_meta_value( get_the_id(), '_amz_portfolio_image' );
            $image_gallery = htmlspecialchars_decode( $portfolio_thumb );
            $image = json_decode( $image_gallery, true );

            $img_fullsize = $this->get_image_by_id( 'full', 'full', $image[0]['itemId'], 1, 1 );

            $output .= '<div class="portfolio-style2-img">';

                // Image
                $output .= '<div class="portfolio-img">';
                    $output .= $this->get_image_by_id( $size['width'], $size['height'], $image[0]['itemId'], 0, 1 );
                $output .= '</div>';

                $output .=  '<div class="portfolio-hover">';
                
                    $output .= '<a href="'. esc_url( $img_fullsize ) .'" class="inner-content popup-gallery">';

                        $output .= '<div class="portfolio-link">';
                    
                            $output .= '<div class="portfolio-content">';
                                
                                // Lightbox
                                $output .= $this->lightbox( $img_fullsize, $show_lightbox );

                            $output .= '</div>';

                        $output .= '</div>'; // portfolio-link

                    $output .= '</a>'; // inner-content

                $output .=  '</div>'; // portfolio-hover

            $output .= '</div>'; // portfolio-style2-img
                        
            $output .= '<div class="portfolio-style2-content">';
            
                // Title
                $output .= $this->title( 'title', $title_tag, 'yes' ); // title

                // Terms
                $output .= $this->terms( $show_terms );

                // Like
                $output .= $this->like( $show_like );

            $output .= '</div>'; // portfolio-style2-content

            return $output;
            
        }
    }