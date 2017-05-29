<?php    

    if( !function_exists( 'build_blog_blocks' ) ) {
        function build_blog_blocks( $block = 'block1', $options = array() ) {
            // Empty assignment
            $output = '';

            //Post Format
            $format = get_post_format();

            // Blocks Options
            $show_post_format_icon = isset( $options['show_post_format_icon'] ) ? $options['show_post_format_icon'] : 'yes';
            $show_category = isset( $options['show_category'] ) ? $options['show_category'] : 'yes';
            $title_length = isset( $options['title_length'] ) ? $options['title_length'] : 20;
            $excerpt_length = isset( $options['excerpt_length'] ) ? $options['excerpt_length'] : 140;
            $show_content = isset( $options['show_content'] ) ? $options['show_content'] : 'yes';
            $show_share = isset( $options['show_share'] ) ? $options['show_share'] : 'yes';
            $share = isset( $options['share'] ) ? $options['share'] : 'facebook, twitter, gplus, linkedin';

            //Shorten Blog Content 
            $post_title = composer_shorten_text( get_the_title(), $title_length );
            $content = composer_shorten_text( get_the_excerpt(), $excerpt_length );

            //Share
            $share = !empty( $share ) ? explode( ',', $share ) : array( 'facebook', 'twitter', 'gplus', 'linkedin' );

            // Featured Image Width and Height
            if( 'block1' == $block ) {
                $width = 600;
                $height = 600;
            }
            //elseif( 'block2' == $block ) {}

            // Blog blocks
            switch ( $block ) {
                case 'block1':
                    $output .= '<div class="row">';
                        //Featured Image
                        $output .= '<div class="col-md-6">';
                            $output .= composer_featured_thumbnail( $width, $height, 0, 1, 1 );
                        $output .= '</div>'; // col-md-6

                        //Content
                        $output .= '<div class="col-md-6">';

                            // Top Meta
                            $output .= '<div class="top-meta">';

                                if( 'yes' == $show_post_format_icon ) {
                                    $output .= composer_post_format_icon( $format );
                                }
                                if( 'yes' == $show_category ) {
                                    $output .= composer_blog_blocks_category();
                                }
                            $output .= '</div>'; // top-meta

                            // Bottom Meta
                            $output .= '<h2 class="">'. esc_html( $post_title ) .'</h2>';
                            $output .= '<div class="bottom-meta">';
                                $output .= composer_blog_blocks_meta( 'author' );
                                $output .= composer_blog_blocks_meta( 'date' );
                            $output .= '</div>'; // bottom-meta

                            // Content
                            if( 'yes' == $show_content ) {
                                $output .= '<p>'. esc_html( $content ) .'</p>';
                            }

                            // Content
                            if( 'yes' == $show_share ) {
                                $output .= composer_blog_blocks_share( '', $share ); // style, share options( facebook, twitter, gplus, linkedin.. )
                            }

                        $output .= '</div>'; // col-md-6
                    $output .= '</div>'; // block1
                break;
            }

            return $output;
        }

    }