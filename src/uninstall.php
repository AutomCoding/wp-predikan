<?php
defined( 'WP_UNINSTALL_PLUGIN' ) or die( 'Failed to uninstall plugin: Access denied' );

// Remove custom post types
$predikan_episodes = get_posts( array(
	'post_type'   => 'predikan',
	'numberposts' => -1
) );
foreach ( $predikan_episodes as $predikan_episode ) {
	wp_delete_post( $predikan_episode->ID, true );
}

$predikan_speakers = get_terms( array(
	'taxonomy'   => 'predikan_speaker',
	'hide_empty' => false
) );
foreach ( $predikan_speakers as $predikan_speaker ) {
	wp_delete_term( $predikan_speaker->term_id, $predikan_speaker->taxonomy );
}

// Remove settings
delete_option( 'predikan_link_sermon' );
delete_option( 'predikan_title' );
delete_option( 'predikan_author' );
delete_option( 'predikan_description' );
delete_option( 'predikan_owner_name' );
delete_option( 'predikan_owner_email' );
