<?php
/**
 * Predikan
 *
 * Plugin Name: Predikan
 * Description: Ladda upp predikningar till din kyrkas webbplats som en poddsändning samt en tabell på någon av era sidor.
 * Version:     1.0.0
 * Author:      Filip Bengtsson
 * Author URI:  https://github.com/AutomCoding/
 * License:     GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: predikan
 */

defined("ABSPATH") or die("Access permission denied.");

class Predikan {
	public $plugin;

	public function __construct() {
		$this->plugin = plugin_basename(__FILE__);
	}

	public function register() {
		add_action("init", array($this, "custom_post_type"));
		add_action("init", array($this, "custom_taxonomy"));
		add_action("edit_form_top", array($this, "add_predikan_meta_boxes"));
		add_action("admin_menu", array($this, "add_admin_pages"));
		add_filter("plugin_action_links_".$this->plugin, array($this, "settings_link"));
		add_action("save_post", array($this, "date_meta_boxes_save"));
		add_shortcode("predikan", array($this, "episode_table"));
	}

	public function activate() {
		// Run when activating plugin from Wordpress
		$this->custom_post_type();
		flush_rewrite_rules();

		// Set podcast description to equal website tagline if not already set
		add_option("predikan_description", get_option("blogdescription"));
	}

	public function deactivate() {
		// Run when deactivating plugin from Wordpress
		flush_rewrite_rules();
	}

	public function custom_post_type() {
		// Register the 'predikan' post type
		$args = array(
			"labels" => array(
				"name" => "Predikningar",
				"singular_name" => "Predikan",
				"add_new" => "Lägg till ny",
				"add_new_item" => "Lägg till ny predikan",
				"edit_item" => "Redigera predikan",
				"new_item" => "Ny predikan",
				"view_item" => "Visa predikan",
				"view_items" => "Visa predikningar",
				"search_items" => "Sök predikningar",
				"not_found" => "Inga predikningar hittade",
				"not_found_in_trash" => "Inga predikningar i papperskorgen",
				"all_items" => "Alla predikningar",
				"attributes" => "Attribut för predikningar",
				"menu_name" => "Predikningar",
				"filter_items_list" => "Filtrera predikolista",
				"items_list_navigation" => "Navigation för lista över predikningar",
				"items_list" => "Lista över predikningar",
				"item_published" => "Predikan publicerad",
				"item_scheduled" => "Predikan är schemalagd för publicering",
				"item_updated" => "Predikan har uppdaterats"
			),
			"public" => true,
			"exclude_from_search" => true,
			"menu_position" => 19,
			"menu_icon" => "dashicons-microphone",
			//"map_meta_cap" => true,
			"delete_with_user" => false,
			"hierarchical" => false,
			"supports" => array("title", "editor"),
			"taxonomies" => array("predikan_speaker"),
			"has_archive" => false,
			"rewrite" => array(
				"slug" => "predikan",
				"feeds" => true
			)
		);
		register_post_type("predikan", $args);
	}

	public function custom_taxonomy() {
		// Register the 'predikan_speaker' taxonomy
		$args = array(
			"hierarchical" => false,
			"labels" => array(
				"name" => "Predikanter",
				"singular_name" => "Predikant",
				"add_new" => "Lägg till ny",
				"add_new_item" => "Lägg till ny predikant",
				"edit_item" => "Redigera predikant",
				"update_item" => "Uppdatera predikant",
				"new_item" => "Ny predikant",
				"new_item_name" => "Nytt predikantnamn",
				"view_item" => "Visa predikant",
				"view_items" => "Visa predikanter",
				"search_items" => "Sök predikanter",
				"popular_items" => "Vanliga predikanter",
				"not_found" => "Inga predikanter hittade",
				"not_found_in_trash" => "Inga predikanter i papperskorgen",
				"all_items" => "Alla predikanter",
				"add_or_remove_items" => "Lägg till eller ta bort predikanter",
				"menu_name" => "Predikanter",
				"filter_items_list" => "Filtrera lista över predikanter",
				"items_list_navigation" => "Navigation för lista över predikanter",
				"items_list" => "Lista över predikanter"
			),
			"show_ui" => true,
			"show_admin_column" => true,
			"query_var" => true
		);
		register_taxonomy("predikan_speaker", array("predikan"), $args);
	}

	public function add_predikan_meta_boxes() {
		// Add a meta box for the record date of CPT predikan
		add_meta_box(
			"predikan-rec-date",
			"Inspelningsdatum",
			array($this, "callback_date_meta_box"),
			"predikan",
			"side",
			"core"
		);

		// Add a meta box for the audio file for CPT predikan
		add_meta_box(
			"predikan-audio-file",
			"Ljudfil",
			array($this, "callback_audio_meta_box"),
			"predikan",
			"normal",
			"core"
		);
	}

	public function callback_date_meta_box($post) {
		// Callback for meta box for the record date of CPT predikan
		wp_nonce_field($this->plugin, "predikan_nonce");
		$date = get_post_meta($post->ID, "_predikan_rec_date", true);
		if (empty($date)) {$date = date("Y-m-d");}
		?>
			<input id="predikan_rec_date" name="predikan_rec_date" type="date" value="<?php echo $date; ?>"/>
			<p class="howto">Ange predikans inspelningsdatum</p>		
		<?php
	}

	public function callback_audio_meta_box($post) {
		// Callback for meta box for the audio file for CPT predikan
		$file = get_post_meta($post->ID, "_predikan_audio_file", true);
		?>
			<input id="predikan_audio_file" name="predikan_audio_file" type="url" value="<?php echo $file; ?>"/>
			<p class="howto">Fullständig URL till den ljudfil som ska inkluderas i poddsändningen och visas på webbplatsen.</p>
		<?php
	}

	public function date_meta_boxes_save($post_id) {
		// Save data from meta boxes
		$is_autosave = wp_is_post_autosave($post_id);
		$is_revision = wp_is_post_revision($post_id);
		$is_valid_nonce = (isset($_POST["predikan_nonce"]) && wp_verify_nonce($_POST["predikan_nonce"], $this->plugin)) ? "true" : "false";

		// Exit function if auto, rev or invalid
		if ($is_autosave || $is_revision || !$is_valid_nonce) {
			return;
		}
		if (isset($_POST["predikan_rec_date"])) {
			update_post_meta($post_id, "_predikan_rec_date", sanitize_text_field($_POST["predikan_rec_date"]));
		}
		if (isset($_POST["predikan_audio_file"])) {
			update_post_meta($post_id, "_predikan_audio_file", sanitize_text_field($_POST["predikan_audio_file"]));
			do_enclose(sanitize_text_field($_POST["predikan_audio_file"]), $post_id);
		}
	}

	public function add_admin_pages() {
		// Add link to the podcast admin page
		add_menu_page(
			"Poddsändningsinställningar",
			"Poddsändningsinställningar",
			"manage_options",
			"predikan",
			array($this, "admin_index"),
			"dashicons-microphone",
			null
		);
	}

	public function admin_index() {
		// Handle updates
		if (array_key_exists("podcast_settings_submit", $_POST)) {
			update_option("predikan_description", $_POST["predikan_description"]);
			print("<div id=\"setting-error-settings_updated\" class=\"updated settings-error notice is-dismissible\">Inställningarna har sparats</div>");
		}

		// Include the admin page template
		require_once plugin_dir_path(__FILE__) . "templates/admin.php";
	}

	public function settings_link($links) {
		// Add custom settings link to plugin manager
		$settings_link = "<a href=\"admin.php?page=predikan\">Inställningar</a>";
		array_push($links, $settings_link);
		return $links;
	}

	public function episodes_data(int $number_of_posts=10) {
		$data = array();
		$episodes = get_posts(array(
			"post_type" => "predikan",
			"orderby" => "ID",
			"order" => "DESC",
			"numberposts" => $number_of_posts
		));
		foreach($episodes as $episode) {
			// Concatenate all speakers to a single string
			$speaker_names = array();
			$speakers = wp_get_post_terms($episode->ID, "speaker");
			foreach($speakers as $speaker) {
				array_push($speaker_names, $speaker->name);
			}

			// Use meta data date if existent, otherwise use the publish date
			$date = get_post_meta($episode->ID, "_predikan_rec_date", true);
			if ($date == null) {
				$unix_time = date_create_from_format("Y-m-d H:i:s", $episode->post_date);
			} else {
				$unix_time = date_create_from_format("Y-m-d", $date)->getTimestamp();
			}

			// Append episode data to array
			array_push($data, array(
				"unix_time" => $unix_time,
				"date" => date_i18n(get_option("date_format"), $unix_time),
				"title" => $episode->post_title,
				"speakers" => $speaker_names,
				"speakers_string" => implode(', ', $speaker_names),
				"file" => get_post_meta($episode->ID, "_predikan_audio_file", true)
			));
		}
		usort($data, function($a, $b) {
			return $b['unix_time'] <=> $a['unix_time'];
		});
		return $data;
	}

	public function episode_table() {
		// Echo a table of the latest episodes
		$episodes=$this->episodes_data(30);
		$table = "<table id=\"predikningar\">";
		$table .= "<tr><th>Datum</th><th>Predikant</th><th>Ämne</th><th>Lyssna</th></tr>";
		foreach($episodes as $ep) {
			$table .= "<tr><td>".$ep["date"]."</td><td>".$ep["speakers_string"]."</td><td>".$ep["title"]."</td><td>";
			if ($ep["file"] == null) {
				$table .= "ingen fil tillgänglig";
			} else {
				$table .= "<audio controls=\"controls\" preload=\"none\"><source src=\"".$ep["file"]."\" type=\"audio/mpeg\"/></audio>";
			}
			$table .= "</td></tr>";
		}
		$table .= "</table>";
		return $table;
	}
}

// Initialize class
$predikan = new Predikan();
$predikan->register();

// Register event hooks for Wordpress
register_activation_hook(__FILE__, array($predikan, "activate"));
register_deactivation_hook(__FILE__, array($predikan, "deactivate"));

// Add custom feed content
function predikan_modify_feed_content($raw_content) {
	if(is_feed() && get_post_type() == "predikan") {
		$id = get_the_ID();
		$title = get_the_title();

		$date = get_post_meta(get_the_ID(), "_predikan_rec_date", true);
		$unix_time = date_create_from_format("Y-m-d", $date)->getTimestamp();
		$date = date_i18n(get_option("date_format"), $unix_time);

		$speaker_names = array();
		$speakers = wp_get_post_terms($id, "predikan_speaker");
		foreach($speakers as $speaker) {
			array_push($speaker_names, $speaker->name);
		}
		$speaker_names = implode(', ', $speaker_names);
		$content = sprintf("%s (%s)<br/><br/>%s", $speaker_names, $date, $raw_content);
	} else {
		$content = $raw_content;
	}
	return $content;
}

function predikan_modify_feed_description($old) {
	if(is_feed() && get_post_type() == "predikan") {
		return get_option("predikan_description");
	} else {
		return $old;
	}
}

add_filter("the_excerpt_rss", "predikan_modify_feed_content");
add_filter("bloginfo_rss", "predikan_modify_feed_description");
