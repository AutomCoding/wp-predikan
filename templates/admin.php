<?php
/**
 * Text Domain: predikan
 */
?>
<div class="wrap">
	<h1><?php echo __("Podcast settings", "predikan"); ?></h1>
	<form method="post" action="">
		<h2><?php echo __("Channel and metadata", "predikan"); ?></h2>
		<label for="predikan_description"<?php echo __("Channel description", "predikan"); ?></label>
		<textarea class="large-text" name="predikan_description" style="height:12em;"><?php print get_option("predikan_description", ""); ?></textarea>
		<p class="description"><?php echo __("Describe, in a few sentences, what this podcast is about and what the listener can expect.", "predikan"); ?></p>

		<p class="submit"><input type="submit" name="podcast_settings_submit" class="button button-primary" value="<?php echo __("Save changes", "predikan"); ?>"></p>
	</form>
</div>
