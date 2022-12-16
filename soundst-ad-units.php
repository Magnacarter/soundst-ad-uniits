<?php
/**
 * The plugin bootstrap file
 *
 * @link              
 * @since             1.0.0
 * @package           SoundST/soundst-ad-units
 *
 * @wordpress-plugin
 * Plugin Name:       SoundST Ad Units
 * Plugin URI:        
 * Description:       Customize ad units to display in content, sidebar, header, footer.
 * Version:           1.0.0
 * Author:            Adam Carter
 * Author URI:        https://github.com/Magnacarter/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       soundst-ad-units
 * Domain Path:       /languages
 */
namespace Soundst\ad_units;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheatin&#8217?' );
}

$plugin_url = plugin_dir_url( __FILE__ );
define( 'SOUNDST_PLUGIN_URL', $plugin_url );
define( 'SOUNDST_PLUGIN_DIR', plugin_dir_path( __DIR__ ) );
define( 'SOUNDST_PLUGIN_VER', '1.0.0' );

new Init_Plugin();

/**
 * Class Init_Plugin
 */
class Init_Plugin {

	/**
	 * Construct function
	 *
	 * @return void
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( __CLASS__, 'activate_plugin' ) );
		register_deactivation_hook( __FILE__, array( __CLASS__, 'deactivate_plugin' ) );
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall_plugin' ) );

        // Load plugin scripts and styles.
        add_action( 'init', array( $this, 'public_scripts' ) );

        // Load plugin classes.
		$this->init_autoloader();
	}

    /**
	 * Enqueue public scripts and styles
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function public_scripts() {
        // Enqueue the styles.
		wp_enqueue_style(   'soundst_styles', SOUNDST_PLUGIN_URL . 'plugin.css', array(), SOUNDST_PLUGIN_VER );

		// // Enqueue font awesome.
		// wp_enqueue_script(  'soundst_font_awesome', 'https://kit.fontawesome.com/b2195895a2.js', array(), SOUNDST_PLUGIN_VER, false );

		// // Enqueue web component.
		// wp_enqueue_script(  'soundst_webcomponent', SOUNDST_PLUGIN_URL . 'assets/js/casino-list.js', array(), SOUNDST_PLUGIN_VER, false );
	}

	/**
	 * Plugin activation handler
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function activate_plugin() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		flush_rewrite_rules();
	}

	/**
	 * The plugin is deactivating. Delete out the rewrite rules option.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function deactivate_plugin() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		delete_option( 'rewrite_rules' );
	}

	/**
	 * Uninstall plugin handler
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function uninstall_plugin() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		check_admin_referer( 'bulk-plugins' );

		// Important: Check if the file is the one
		// that was registered during the uninstall hook.
		if ( __FILE__ != WP_UNINSTALL_PLUGIN ) {
			return;
		}
		delete_option( 'rewrite_rules' );
	}

	/**
	 * Kick off the plugin by initializing the plugin files.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_autoloader() {
        require_once 'classes/class-custom-post-types.php';
	}
}
