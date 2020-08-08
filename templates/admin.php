<?php
/**
 * Text Domain: predikan
 */
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Sermon and podcast settings', 'predikan' ); ?></h1>
	<form method="post" action="">
		<h2><?php esc_html_e( 'Sermon table', 'predikan' ); ?></h2>
		<input type="checkbox" id="predikan_link_sermon" name="predikan_link_sermon" value="Yes" <?php if ( get_option( 'predikan_link_sermon', '' ) == 'Yes' ) print 'checked '; ?>/>
		<label for="predikan_link_sermon"><?php esc_html_e( 'Link to the sermon’s page', 'predikan' ); ?></label>

		<h2><?php esc_html_e( 'Podcast', 'predikan' ); ?></h2>
		<label for="predikan_description"><?php esc_html_e( 'Channel description', 'predikan' ); ?></label>
		<textarea class="large-text" name="predikan_description"><?php print get_option( 'predikan_description', '' ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Describe, in a few sentences, what this podcast is about and what the listener can expect.', 'predikan' ); ?></p>

		<p class="submit"><input type="submit" name="podcast_settings_submit" class="button button-primary" value="<?php esc_attr_e( 'Save changes', 'predikan' ); ?>"></p>
	</form>
</div>
