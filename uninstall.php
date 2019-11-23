<?php
defined("WP_UNINSTALL_PLUGIN") or die("Failed to uninstall plugin: Access denied");
// Remove custom post types
$predikan_episodes = get_posts(array("post_type" => "predikan", "numberposts" => -1));
foreach($predikan_episodes as $predikan_episode) {wp_delete_post($predikan_episode, true);}

$predikan_speakers = get_posts(array("post_type" => "predikan", "numberposts" => -1));
foreach($predikan_speakers as $predikan_speaker) {wp_delete_post($predikan_speaker, true);}

// Remove settings
delete_option("predikan_description");
