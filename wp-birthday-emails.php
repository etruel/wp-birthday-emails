<?php
/**
 * Plugin Name: WP Birthday emails
 * Plugin URI: http://www.netmdp.com
 * Description: Send happy birthday emails to users.
 * Version: 1.0
 * Author: etruel
 * Author URI: https://etruel.com
 * License: GPL2+
 * Text Domain: wp-birthday-emails
 * Domain Path: /languages/
 */

define( 'WPBIRTHDAYEMAILS_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPBIRTHDAYEMAILS_URL', plugin_dir_url( __FILE__ ) );


require WPBIRTHDAYEMAILS_DIR . 'inc/class-wp-birthday-emails-composer.php';
require WPBIRTHDAYEMAILS_DIR . 'inc/class-wp-birthday-emails-cron.php';
require WPBIRTHDAYEMAILS_DIR . 'inc/class-wp-birthday-emails-user.php';
require WPBIRTHDAYEMAILS_DIR . 'inc/class-wp-birthday-emails-list-user.php';
require WPBIRTHDAYEMAILS_DIR . 'inc/class-wp-birthday-emails-widget.php';
new WPBirthdayemails_Composer;
new WPBirthdayemails_User;
new WPBirthdayemails_List_User;
new WPBirthdayemails_Cron;
