<?php
/**
 * Predikan
 *
 * Plugin Name: Predikan
 * Plugin URI:  https://github.com/AutomCoding/wp-predikan/
 * Description: Upload sermons to your church’s website as a podcast and include them, in a table, on any of your pages.
 * Version:     __STABLE_TAG__
 * Author:      Filip Bengtsson
 * Author URI:  https://autom.zone/
 * License:     GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: predikan
 */

defined( 'ABSPATH' ) or die( 'Access permission denied.' );

class Predikan {
	public $plugin;

	public function __construct() {
		$this->plugin = plugin_basename( __FILE__ );
	}

	public function register() {
		add_action( 'init', array( $this, 'custom_post_type' ) );
		add_action( 'init', array( $this, 'custom_taxonomy' ) );
		add_action( 'init', array( $this, 'custom_feed' ) );
		add_action( 'edit_form_top', array( $this, 'add_predikan_meta_boxes' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
		add_filter( 'plugin_action_links_' . $this->plugin, array( $this, 'settings_link' ) );
		add_action( 'save_post', array( $this, 'date_meta_boxes_save' ) );
		add_shortcode( 'predikan', array( $this, 'episode_table' ) );
		add_shortcode( 'predikan-feed', array( $this, 'feed_link' ) );
	}

	public function activate() {
		// Run when activating plugin from WordPress
		$this->custom_post_type();
		$this->custom_taxonomy();
		$this->custom_feed();
		flush_rewrite_rules();

		// Set podcast title to equal website name if not already set
		add_option( 'predikan_title', bloginfo( 'name' ) );

		// Set podcast description to equal website tagline if not already set
		add_option( 'predikan_description', get_option( 'blogdescription' ) );
	}

	public function deactivate() {
		// Run when deactivating plugin from WordPress
		flush_rewrite_rules();
	}

	public function custom_post_type() {
		// Register the 'predikan' post type
		$args = array(
			'labels'              => array(
				'name'                  => esc_html__( 'Sermons', 'predikan' ),
				'singular_name'         => esc_html__( 'Sermon', 'predikan' ),
				'add_new'               => esc_html__( 'Add New', 'predikan' ),
				'add_new_item'          => esc_html__( 'Add New Sermon', 'predikan' ),
				'edit_item'             => esc_html__( 'Edit Sermon', 'predikan' ),
				'new_item'              => esc_html__( 'New Sermon', 'predikan' ),
				'view_item'             => esc_html__( 'View Sermon', 'predikan' ),
				'view_items'            => esc_html__( 'View Sermons', 'predikan' ),
				'search_items'          => esc_html__( 'Search Sermons', 'predikan' ),
				'not_found'             => esc_html__( 'No Sermons Found', 'predikan' ),
				'not_found_in_trash'    => esc_html__( 'No Sermons Found in Trash', 'predikan' ),
				'all_items'             => esc_html__( 'All Sermons', 'predikan' ),
				'attributes'            => esc_html__( 'Attributes for Sermons', 'predikan' ),
				'menu_name'             => esc_html__( 'Sermons', 'predikan' ),
				'filter_items_list'     => esc_html__( 'Filter List of Sermons', 'predikan' ),
				'items_list_navigation' => esc_html__( 'Navigation for List of Sermons', 'predikan' ),
				'items_list'            => esc_html__( 'List of Sermons', 'predikan' ),
				'item_published'        => esc_html__( 'Sermons published', 'predikan' ),
				'item_scheduled'        => esc_html__( 'Sermon is scheduled for publication', 'predikan' ),
				'item_updated'          => esc_html__( 'Sermon updated', 'predikan' )
			),
			'public'              => true,
			'exclude_from_search' => true,
			'menu_position'       => 19,
			'menu_icon'           => 'dashicons-microphone',
			'delete_with_user'    => false,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor' ),
			'taxonomies'          => array( 'predikan_speaker' ),
			'has_archive'         => false,
			'rewrite'             => array(
				'slug'  => 'predikan',
				'feeds' => true
			)
		);
		register_post_type( 'predikan', $args );
	}

	public function custom_taxonomy() {
		// Register the 'predikan_speaker' taxonomy
		$args = array(
			'hierarchical'      => false,
			'labels'            => array(
				'name'                  => esc_html__( 'Preachers', 'predikan' ),
				'singular_name'         => esc_html__( 'Preacher', 'predikan' ),
				'add_new'               => esc_html__( 'Add New', 'predikan' ),
				'add_new_item'          => esc_html__( 'Add New Preacher', 'predikan' ),
				'edit_item'             => esc_html__( 'Edit Preacher', 'predikan' ),
				'update_item'           => esc_html__( 'Update Preacher', 'predikan' ),
				'new_item'              => esc_html__( 'New Preacher', 'predikan' ),
				'new_item_name'         => esc_html__( 'New Preacher name', 'predikan' ),
				'view_item'             => esc_html__( 'View Preacher', 'predikan' ),
				'view_items'            => esc_html__( 'View Preachers', 'predikan' ),
				'search_items'          => esc_html__( 'Search Preachers', 'predikan' ),
				'popular_items'         => esc_html__( 'Popular Preachers', 'predikan' ),
				'not_found'             => esc_html__( 'No Preachers Found', 'predikan' ),
				'not_found_in_trash'    => esc_html__( 'No Preachers Found in Trash', 'predikan' ),
				'all_items'             => esc_html__( 'All Preachers', 'predikan' ),
				'add_or_remove_items'   => esc_html__( 'Add or Remove Preachers', 'predikan' ),
				'menu_name'             => esc_html__( 'Preachers', 'predikan' ),
				'filter_items_list'     => esc_html__( 'Filter List of Preachers', 'predikan' ),
				'items_list_navigation' => esc_html__( 'Navigation for List of Preachers', 'predikan' ),
				'items_list'            => esc_html__( 'List of Preachers', 'predikan' )
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true
		);
		register_taxonomy( 'predikan_speaker', array( 'predikan' ), $args );
	}

	public function add_predikan_meta_boxes() {
		// Add a meta box for the record date of CPT predikan
		add_meta_box(
			'predikan-rec-date',
			esc_html__( 'Recording date', 'predikan' ),
			array( $this, 'callback_date_meta_box' ),
			'predikan',
			'side',
			'core'
		);

		// Add a meta box for the audio file for CPT predikan
		add_meta_box(
			'predikan-audio-file',
			esc_html__( 'Audio file', 'predikan' ),
			array( $this, 'callback_audio_meta_box' ),
			'predikan',
			'normal',
			'core'
		);
	}

	public function callback_date_meta_box( $post ) {
		// Callback for meta box for the record date of CPT predikan
		wp_nonce_field( $this->plugin, 'predikan_nonce' );
		$date = get_post_meta( $post->ID, '_predikan_rec_date', true );
		if ( empty( $date ) ) {
			$date = date( 'Y-m-d' );
		}
		?>
			<input id="predikan_rec_date" name="predikan_rec_date" type="date" value="<?php echo $date; ?>"/>
			<p class="howto"><?php echo esc_html__( 'Enter the sermon’s date of recording.', 'predikan' ); ?></p>		
		<?php
	}

	public function callback_audio_meta_box( $post ) {
		// Callback for meta box for the audio file for CPT predikan
		$file = get_post_meta( $post->ID, '_predikan_audio_file', true );
		?>
			<audio id="predikan_audio_preview" preload="none" style="width: 100%;" controls="controls" src="<?php echo $file; ?>"></audio>
			<br />
			<input id="predikan_audio_file" name="predikan_audio_file" style="width: 100%;" type="url" value="<?php echo $file; ?>"/>
			<p class="howto">
				<?php echo esc_html__( 'Complete URL of the audiofile that will be posted in the podcast and displayed on the website.', 'predikan' ); ?>
			</p>
			<script>
				jQuery( '#predikan_audio_file' ).change( function() {
					jQuery( '#predikan_audio_preview' ).attr( 'src' , jQuery( '#predikan_audio_file' ).val() );
				} );
			</script>
		<?php
	}

	public function date_meta_boxes_save( $post_id ) {
		// Save data from meta boxes
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'predikan_nonce' ] ) && wp_verify_nonce( $_POST[ 'predikan_nonce' ], $this->plugin ) ) ? true : false;

		// Exit function if auto, rev or invalid
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}
		if ( isset( $_POST[ 'predikan_rec_date' ] ) ) {
			update_post_meta( $post_id, '_predikan_rec_date', sanitize_text_field( $_POST[ 'predikan_rec_date' ] ) );
		}
		if ( isset( $_POST[ 'predikan_audio_file' ] ) ) {
			update_post_meta( $post_id, '_predikan_audio_file', sanitize_text_field( $_POST[ 'predikan_audio_file' ] ) );
			do_enclose( sanitize_text_field( $_POST[ 'predikan_audio_file' ] ), $post_id );
		}
	}

	public function add_admin_pages() {
		// Add link to the plugin's admin page
		add_options_page(
			esc_html__( 'Sermon and podcast settings', 'predikan' ),
			esc_html_x( 'Sermons & podcast', 'Short text for admin menu', 'predikan' ),
			'manage_options',
			'predikan-settings',
			array( $this, 'admin_page' )
		);
	}

	public function admin_page() {
		// Handle updates
		if ( array_key_exists( 'podcast_settings_submit', $_POST ) ) {
			update_option( 'predikan_link_sermon', ( $_POST[ 'predikan_link_sermon' ] == 'Yes' ) ? 'Yes' : 'No' );
			update_option( 'predikan_title', $_POST[ 'predikan_title' ] );
			update_option( 'predikan_author', $_POST[ 'predikan_author' ] );
			update_option( 'predikan_description', $_POST[ 'predikan_description' ] );
			update_option( 'predikan_owner_name', $_POST[ 'predikan_owner_name' ] );
			update_option( 'predikan_owner_email', $_POST[ 'predikan_owner_email' ] );

			echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">';
			esc_html_e( 'The settings have been saved', 'predikan' );
			echo '</div>';
		}

		// Include the admin page template
		require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
	}

	public function settings_link( $links ) {
		// Add custom settings link to plugin manager
		$settings_link = '<a href="options-general.php?page=predikan-settings">' . esc_html_x( 'Settings', 'Link in the plugin manager', 'predikan' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	public function episodes_data( int $number_of_posts=10 ) {
		$data = array();
		$episodes = get_posts( array(
			'post_type'   => 'predikan',
			'orderby'     => 'ID',
			'order'       => 'DESC',
			'numberposts' => $number_of_posts
		) );
		foreach( $episodes as $episode ) {
			// Concatenate all speakers to a single string
			$speaker_names = array();
			$speakers = wp_get_post_terms( $episode->ID, 'predikan_speaker' );
			foreach( $speakers as $speaker ) {
				array_push( $speaker_names, $speaker->name );
			}

			// Use meta data date if existent, otherwise use the publish date
			$date = get_post_meta( $episode->ID, '_predikan_rec_date', true );
			if ( $date == null ) {
				$unix_time = date_create_from_format( 'Y-m-d H:i:s', $episode->post_date );
			} else {
				$unix_time = date_create_from_format( 'Y-m-d', $date )->getTimestamp();
			}

			// Append episode data to array
			array_push( $data, array(
				'unix_time'       => $unix_time,
				'date'            => wp_date( get_option( 'date_format' ), $unix_time ),
				'title'           => $episode->post_title,
				'content'         => $episode->post_content,
				'guid'            => $episode->guid,
				'permalink'       => get_post_permalink( $episode),
				'speakers'        => $speaker_names,
				'speakers_string' => implode( ', ', $speaker_names ),
				'enclosure'       => explode( "\n", get_post_meta( $episode->ID, 'enclosure', true ) )
			) );
		}
		usort( $data, function( $a, $b ) {
			return $b[ 'unix_time' ] <=> $a[ 'unix_time' ];
		} );
		return $data;
	}

	public function episode_table() {
		// Enque JavaScript
		wp_enqueue_script( 'predikan-table-mobile', plugins_url( '/js/mobile-table.js' , __FILE__ ), array( 'jquery' ) );

		// Fetch settings
		$link_sermon = ( get_option ( 'predikan_link_sermon', 'No' ) == 'Yes' );

		// Echo a table of the latest episodes
		$episodes = $this->episodes_data( 30 );
		$table = '<table class="predikan-table">';
		$table .= '<thead>';
		$table .= sprintf(
			'<tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr>',
			 esc_html_x( 'Date', 'Table header', 'predikan' ),
			 esc_html_x( 'Preacher', 'Table header', 'predikan' ),
			 esc_html_x( 'Subject', 'Table header', 'predikan' ),
			 esc_html_x( 'Listen', 'Table header', 'predikan' )
		);
		$table .= '</thead>';
		$table .= '<tbody>';
		foreach( $episodes as $ep ) {
			$table .= '<tr><td>' . $ep[ 'date' ] . '</td><td>' . $ep[ 'speakers_string' ] . '</td><td>';
			$table .= ( $link_sermon ) ? '<a href="' . $ep[ 'permalink' ] . '">' . $ep[ 'title' ] . '</a>' : $ep[ 'title' ];
			$table .= '</td><td>';
			if ( empty( $ep[ 'enclosure' ][ 0 ] ) ) {
				$table .= esc_html_x( 'no file available', 'Displayed in table instead of audio player', 'predikan' );
			} else {
				$table .= '<audio controls="controls" preload="none"><source src="' . $ep[ 'enclosure' ][ 0 ] . '" type="' . $ep[ 'enclosure' ][ 2 ] . '"/></audio>';
			}
			$table .= '</td></tr>';
		}
		$table .= '</tbody>';
		$table .= '</table>';
		return $table;
	}

	public function custom_feed() {
		// Register RSS feed for podcast
		add_feed( 'predikan-podcast', array( $this, 'render_custom_feed' ) );
	}

	public function render_custom_feed() {
		// Render RSS podcast feed
		header( 'Content-Type: application/rss+xml' );
		require_once plugin_dir_path( __FILE__ ) . 'templates/predikan-podcast.php';
	}

	public function feed_link( $attributes ) {
		// Return link to the podcast feed
		$link = site_url( '/feed/predikan-podcast/' );
		$attributes = shortcode_atts( array( 'text' => esc_html_x( 'podcast feed', 'Default link text for podcast feed', 'predikan' ) ), $attributes );
		return '<a href="' . $link . '">' . esc_html( $attributes[ 'text' ] ) . '</a>';
	}
}

// Initialize class
$predikan = new Predikan();
$predikan->register();

// Register event hooks for Wordpress
register_activation_hook( __FILE__, array( $predikan, 'activate' ) );
register_deactivation_hook( __FILE__, array( $predikan, 'deactivate' ) );

// Add custom feed content
function predikan_modify_feed_content( $raw_content ) {
	if( is_feed() && get_post_type() == 'predikan' ) {
		$id = get_the_ID();
		$title = get_the_title();

		$date = get_post_meta( $id, '_predikan_rec_date', true );
		$unix_time = date_create_from_format( 'Y-m-d', $date )->getTimestamp();
		$date = wp_date( get_option( 'date_format' ), $unix_time );

		$speaker_names = array();
		$speakers = wp_get_post_terms( $id, 'predikan_speaker' );
		foreach( $speakers as $speaker ) {
			array_push( $speaker_names, $speaker->name );
		}
		$speaker_names = implode( ', ', $speaker_names );
		$content = sprintf( '%s (%s)<br /><br />%s', $speaker_names, $date, $raw_content );
	} else {
		$content = $raw_content;
	}
	return $content;
}

function predikan_modify_feed_description( $old ) {
	if( is_feed() && get_post_type() == 'predikan' ) {
		return get_option( 'predikan_description' );
	} else {
		return $old;
	}
}

add_filter( 'the_excerpt_rss', 'predikan_modify_feed_content' );
add_filter( 'bloginfo_rss', 'predikan_modify_feed_description' );
