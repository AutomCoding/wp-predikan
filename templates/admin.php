<?php
/**
 * Text Domain: predikan
 */
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Podcast settings', 'predikan' ); ?></h1>
	<form method="post" action="">
		<h2><?php esc_html_e( 'Channel and metadata', 'predikan' ); ?></h2>
		<label for="predikan_description"><?php esc_html_e( 'Channel description', 'predikan' ); ?></label>
		<textarea class="large-text" name="predikan_description"><?php print get_option( 'predikan_description', '' ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Describe, in a few sentences, what this podcast is about and what the listener can expect.', 'predikan' ); ?></p>

		<p class="submit"><input type="submit" name="podcast_settings_submit" class="button button-primary" value="<?php esc_attr_e( 'Save changes', 'predikan' ); ?>"></p>
	</form>
</div>
