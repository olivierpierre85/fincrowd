<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<?php if ( is_product() ) { ?>
    <p class="wpneo-full-description">
        <?php echo get_the_content() ?>
    </p>
<?php } else { ?>
    <p class="wpneo-short-description">
        <?php echo the_excerpt(); ?>
    </p>
<?php } ?>
<?php
// echo WPNEOCF()->limit_word_text(strip_tags(get_the_content()), 450);
// echo wp_trim_words( get_the_content(), 30, '...' );
// echo get_the_content();
// echo the_excerpt();
?>

