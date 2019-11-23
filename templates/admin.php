<div class="wrap">
	<h1>Inställningar för podcast</h1>
	<form method="post" action="">
		<h2>Kanal och metadata</h2>
		<label for="predikan_description">Kanalbeskrivning</label>
		<textarea class="large-text" name="predikan_description" style="height:12em;"><?php print get_option("predikan_description", ""); ?></textarea>
		<p class="description">Berätta med några meningar vad den här podcasten handlar om och vad lyssnaren kan förvänta sig.</p>

		<p class="submit"><input type="submit" name="podcast_settings_submit" class="button button-primary" value="Spara ändringar"></p>
	</form>
</div>
