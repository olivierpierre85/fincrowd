<?php
/**
 * Options for the import (step 1).
 */

$this->render_header();

$generator = $data->generator;
if ( preg_match( '#^http://wordpress\.org/\?v=(\d+\.\d+\.\d+)$#', $generator, $matches ) ) {
	$generator = sprintf( __( 'WordPress %s', 'wordpress-importer' ), $matches[1] );
}

?>
<div class="welcome-panel">
	<div class="welcome-panel-content">
		<h2><?php esc_html_e( 'Step 2: Import Settings', 'wordpress-importer' ) ?></h2>
		<p><?php esc_html_e( 'Your import is almost ready to go. Before your content is imported, pick exactly how you want your data imported.', 'wordpress-importer' ) ?></p>

		<div class="welcome-panel-column-container">
			<div class="welcome-panel-column">
				<h3><?php esc_html_e( 'Import Summary', 'wordpress-importer' ) ?></h3>
				<ul>
					<li>
						<span class="dashicons dashicons-admin-post"></span>
						<?php
						echo esc_html( sprintf(
							_n( '%d post (including CPTs)', '%d posts (including CPTs)', $data->post_count, 'wordpress-importer' ),
							$data->post_count
						));
						?>
					</li>
					<li>
						<span class="dashicons dashicons-admin-media"></span>
						<?php
						echo esc_html( sprintf(
							_n( '%d media item', '%d media items', $data->media_count, 'wordpress-importer' ),
							$data->media_count
						));
						?>
					</li>
					<li>
						<span class="dashicons dashicons-admin-users"></span>
						<?php
						echo esc_html( sprintf(
							_n( '%d user', '%d users', count( $data->users ), 'wordpress-importer' ),
							count( $data->users )
						));
						?>
					</li>
					<li>
						<span class="dashicons dashicons-admin-comments"></span>
						<?php
						echo esc_html( sprintf(
							_n( '%d comment', '%d comments', $data->comment_count, 'wordpress-importer' ),
							$data->comment_count
						));
						?>
					</li>
					<li>
						<span class="dashicons dashicons-category"></span>
						<?php
						echo esc_html( sprintf(
							_n( '%d term', '%d terms', $data->term_count, 'wordpress-importer' ),
							$data->term_count
						));
						?>
					</li>
				</ul>
			</div>
			<div class="welcome-panel-column">
				<h3><?php esc_html_e( 'Facts About Your Import', 'wordpress-importer' ) ?></h3>
				<ul>
					<li>
						<?php
						echo wp_kses( sprintf(
							__( 'Exported from <a href="%1$s">%2$s</a>', 'wordpress-importer' ),
							esc_url( $data->home ),
							esc_html( $data->title )
						), 'data' );
						?>
					</li>
					<li>
						<?php
						echo esc_html( sprintf(
							__( 'Generated by %s', 'wordpress-importer' ),
							$generator
						));
						?>
					</li>
					<li>
						<?php
						echo esc_html( sprintf(
							__( 'Format: WXR v%s', 'wordpress-importer' ),
							$data->version
						));
						?>
					</li>
				</ul>
			</div>
			<div class="welcome-panel-column">
				<h3><?php esc_html_e( 'Facts About the Sea Lion', 'wordpress-importer' ) ?></h3>
				<ul>
					<li><?php esc_html_e( 'Unlike seals, sea lions have external ear flaps.', 'wordpress-importer' ) ?></li>
					<li><?php esc_html_e( 'Sea lions are carnivorous and primarily eat fish and squid.', 'wordpress-importer' ) ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<form action="<?php echo esc_url( $this->get_url( 2 ) ) ?>" method="post">

	<?php if ( ! empty( $data->users ) ) : ?>

		<h3><?php esc_html_e( 'Assign Authors', 'wordpress-importer' ) ?></h3>
		<p><?php
			echo wp_kses(
				__( 'To make it easier for you to edit and save the imported content, you may want to reassign the author of the imported item to an existing user of this site. For example, you may want to import all the entries as <code>admin</code>s entries.', 'wordpress-importer' ),
				'data'
			);
		?></p>

		<?php if ( $this->allow_create_users() ) : ?>

			<p><?php printf( esc_html__( 'If a new user is created by WordPress, a new password will be randomly generated and the new user&#8217;s role will be set as %s. Manually changing the new user&#8217;s details will be necessary.', 'wordpress-importer' ), esc_html( get_option( 'default_role' ) ) ) ?></p>

		<?php endif; ?>

		<ol id="authors">

			<?php foreach ( $data->users as $index => $users ) : ?>

				<li><?php $this->author_select( $index, $users['data'] ); ?></li>

			<?php endforeach ?>

		</ol>

	<?php endif; ?>

	<?php if ( $this->allow_fetch_attachments() ) : ?>

		<h3><?php esc_html_e( 'Import Attachments', 'wordpress-importer' ) ?></h3>
		<p>
			<input type="checkbox" value="1" name="fetch_attachments" id="import-attachments" />
			<label for="import-attachments"><?php
				esc_html_e( 'Download and import file attachments', 'wordpress-importer' ) ?></label>
		</p>

	<?php endif; ?>

	<input type="hidden" name="import_id" value="<?php echo esc_attr( $this->id ) ?>" />
	<?php wp_nonce_field( sprintf( 'wxr.import:%d', $this->id ) ) ?>

	<?php submit_button( __( 'Start Importing', 'wordpress-importer' ) ) ?>

</form>

<?php

$this->render_footer();
