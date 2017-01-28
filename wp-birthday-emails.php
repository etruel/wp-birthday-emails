<?php
/**
 * Plugin Name: WP Birthday emails
 * Plugin URI: http://www.netmdp.com
 * GIT URI: https://bitbucket.org/netmdp/wp-birthday-emails
 * Description: Send happy birthday emails to users.
 * Version: 1.0
 * Author: etruel
 * Author URI: https://etruel.com
 * License: GPL2+
 * Text Domain: wp-birthday-emails
 * Domain Path: /languages/
 */



if ( ! class_exists( 'wp_birthday_emails' ) ) :
class wp_birthday_emails {
	
	private static $instance = null;
	
	public static function getInstance() {
		if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
	}
	function __construct() {
		$this->setupGlobals();
		$this->includes();
		$this->loadTextDomain();
		
		//add_action( 'plugins_loaded', array($this,'repara_git_updater') );
	}
	
	private function includes() {
		require WPBIRTHDAYEMAILS_DIR . 'inc/class-wp-birthday-emails-composer.php';
		require WPBIRTHDAYEMAILS_DIR . 'inc/class-wp-birthday-emails-cron.php';
		require WPBIRTHDAYEMAILS_DIR . 'inc/class-wp-birthday-emails-user.php';
		require WPBIRTHDAYEMAILS_DIR . 'inc/class-wp-birthday-emails-list-user.php';
		require WPBIRTHDAYEMAILS_DIR . 'inc/class-wp-birthday-emails-widget.php';
		new WPBirthdayemails_Composer;
		new WPBirthdayemails_User;
		new WPBirthdayemails_List_User;
		new WPBirthdayemails_Cron;
		do_action('wp_birthday_emails_include_files');		
	}
	function repara_git_updater() {
		if ( is_admin() && !class_exists( 'GPU_Controller' ) ) {
			require_once dirname( __FILE__ ) . '/git-plugin-updates/git-plugin-updates.php';
			add_action( 'plugins_loaded', 'GPU_Controller::get_instance', 20 );
			add_filter( 'gpu_use_plugin_uri_header','__return_true' );
		}
	}
	private function setupGlobals() {

		// Plugin Folder Path
		if (!defined('WPBIRTHDAYEMAILS_DIR')) {
			define('WPBIRTHDAYEMAILS_DIR', plugin_dir_path( __FILE__ ));
		}

		// Plugin Folder URL
		if (!defined('WPBIRTHDAYEMAILS_URL')) {
			define('WPBIRTHDAYEMAILS_URL', plugin_dir_url(__FILE__));
		}

		// Plugin Root File
		if (!defined('WPBIRTHDAYEMAILS_PLUGIN_FILE')) {
			define('WPBIRTHDAYEMAILS_PLUGIN_FILE', __FILE__ );
		}
		
		// Plugin text domain
		if (!defined('WPBIRTHDAYEMAILS_TEXT_DOMAIN')) {
			define('WPBIRTHDAYEMAILS_TEXT_DOMAIN', 'wp-birthday-emails' );
		}

	}

	public function loadTextDomain() {
		// Set filter for plugin's languages directory
		$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
		$lang_dir = apply_filters('wp-birthday-emails_languages_directory', $lang_dir );

		// Traditional WordPress plugin locale filter
		$locale        = apply_filters( 'plugin_locale',  get_locale(), 'wp-birthday-emails' );
		$mofile        = sprintf( '%1$s-%2$s.mo', 'wp-birthday-emails', $locale );

		// Setup paths to current locale file
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/wp-birthday-emails/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/wp-birthday-emails/ folder
			load_textdomain( 'wp-birthday-emails', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/wp-birthday-emails/languages/ folder
			load_textdomain( 'wp-birthday-emails', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'wp-birthday-emails', false, $lang_dir );
		}		
	}
}
endif;

$wp_birthday_emails = null;
function getClasswp_birthday_emails() {
	global $wp_birthday_emails;
	if (is_null($wp_birthday_emails)) {
		$wp_birthday_emails = wp_birthday_emails::getInstance();
	}
	return $wp_birthday_emails;
}
getClasswp_birthday_emails();