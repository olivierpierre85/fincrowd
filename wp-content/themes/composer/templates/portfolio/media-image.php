<?php

    $prefix = composer_get_prefix();

    $id = get_the_id();

    $portfolio_single_image = json_decode( composer_get_meta_value( $id, '_amz_portfolio_single_image', '' ) );

    // Assign width and height
    $layout = composer_get_meta_value( $id, '_amz_portfolio_layout', 'full_width' );
    if( 'full_width' === $layout ) {
        $width = 1200;
        $height = 500;
    }
    else {
        $width = 790;
        $height = 890;
    }

    //Assign Image
    echo '<div class="portfolio-img pix-post-gallery">';

        if( !empty( $portfolio_single_image ) ) {
            echo '<div class="portfolio-image-gallery">';
                echo composer_get_image_by_id( $width, $height, $portfolio_single_image[0]->itemId, 0, 0, 1 );
            echo '</div>';
        }

    echo '</div>';
?>