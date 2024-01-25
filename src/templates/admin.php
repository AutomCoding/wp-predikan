<?php
/**
 * Text Domain: predikan
 */
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Sermon and podcast settings', 'predikan' ); ?></h1>
	<form method="post" action="">
		<h2><?php esc_html_e( 'Sermon table', 'predikan' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Link to sermon pages', 'predikan' ); ?></th>
				<td>
					<fieldset>
						<input type="checkbox" id="predikan_link_sermon" name="predikan_link_sermon" value="Yes" <?php if ( get_option( 'predikan_link_sermon', '' ) == 'Yes' ) echo 'checked '; ?>/>
						<label for="predikan_link_sermon"><?php esc_html_e( 'Include a link to sermon pages from the table', 'predikan' ); ?></label>
					</fieldset>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Podcast', 'predikan' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Channel title', 'predikan' ); ?></th>
				<td><input type="text" class="regular-text" name="predikan_title" value="<?php echo esc_attr( get_option( 'predikan_title', '' ) ); ?>"/></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Creator', 'predikan' ); ?></th>
				<td>
					<input type="text" class="regular-text" name="predikan_author" value="<?php echo esc_attr( get_option( 'predikan_author', '' ) ); ?>"/>
					<p class="description"><?php esc_html_e( 'Name of the creator used by iTunes, this will be publicly visible.', 'predikan' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Channel description', 'predikan' ); ?></th>
				<td>
					<textarea class="large-text" name="predikan_description" rows="4"><?php echo esc_textarea( get_option( 'predikan_description', '' ) ); ?></textarea>
					<p class="description"><?php esc_html_e( 'Describe, in a few sentences, what this podcast is about and what the listener can expect.', 'predikan' ); ?></p>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Contact person', 'predikan' ); ?></h2>
		<p><?php esc_html_e( 'Name and email address used by iTunes for ownership verification, this will be publicly accessible.', 'predikan' ); ?></p>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Name', 'predikan' ); ?></th>
				<td><input type="text" name="predikan_owner_name" value="<?php echo esc_attr( get_option( 'predikan_owner_name', '' ) ); ?>"/></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Email address', 'predikan' ); ?></th>
				<td><input type="text" name="predikan_owner_email" value="<?php echo esc_attr( get_option( 'predikan_owner_email', '' ) ); ?>"/></td>
			</tr>
		</table>

		<p class="submit"><input type="submit" name="podcast_settings_submit" class="button button-primary" value="<?php esc_attr_e( 'Save changes', 'predikan' ); ?>"></p>
	</form>
</div>
