<?php
/**
 * Text Domain: predikan
 */
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Sermon and podcast settings', 'predikan' ); ?></h1>
	<form method="post" action="">
		<h2><?php esc_html_e( 'Sermon table', 'predikan' ); ?></h2>
		<input type="checkbox" id="predikan_link_sermon" name="predikan_link_sermon" value="Yes" <?php if ( get_option( 'predikan_link_sermon', '' ) == 'Yes' ) echo 'checked '; ?>/>
		<label for="predikan_link_sermon"><?php esc_html_e( 'Link to the sermon’s page', 'predikan' ); ?></label>

		<h2><?php esc_html_e( 'Podcast', 'predikan' ); ?></h2>
		<label for="predikan_title"><?php esc_html_e( 'Channel title', 'predikan' ); ?></label><br />
		<input type="text" name="predikan_title" value="<?php echo esc_attr( get_option( 'predikan_title', '' ) ); ?>"/><br /><br />

		<label for="predikan_author"><?php esc_html_e( 'Creator', 'predikan' ); ?></label><br />
		<input type="text" name="predikan_author" value="<?php echo esc_attr( get_option( 'predikan_author', '' ) ); ?>"/>
		<p class="description"><?php esc_html_e( 'Name of the creator used by iTunes, this will be publicly visible.', 'predikan' ); ?></p>

		<label for="predikan_description"><?php esc_html_e( 'Channel description', 'predikan' ); ?></label>
		<textarea class="large-text" name="predikan_description"><?php echo esc_textarea( get_option( 'predikan_description', '' ) ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Describe, in a few sentences, what this podcast is about and what the listener can expect.', 'predikan' ); ?></p>

		<h3><?php esc_html_e( 'Contact person', 'predikan' ); ?></h3>
		<p class="description"><?php esc_html_e( 'Name and email address used by iTunes for ownership verification, this will be publicly accessible.', 'predikan' ); ?></p>
		<label for="predikan_owner_name"><?php esc_html_e( 'Name', 'predikan' ); ?></label><br />
		<input type="text" name="predikan_owner_name" value="<?php echo esc_attr( get_option( 'predikan_owner_name', '' ) ); ?>"/><br />

		<label for="predikan_owner_email"><?php esc_html_e( 'Email address', 'predikan' ); ?></label><br />
		<input type="text" name="predikan_owner_email" value="<?php echo esc_attr( get_option( 'predikan_owner_email', '' ) ); ?>"/><br />

		<p class="submit"><input type="submit" name="podcast_settings_submit" class="button button-primary" value="<?php esc_attr_e( 'Save changes', 'predikan' ); ?>"></p>
	</form>
</div>
