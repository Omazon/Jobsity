<?php
/**
 * Jobsity
 *
 * @package       JOBSITY
 * @author        Omar Boza
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Jobsity
 * Plugin URI:    http://omazon.github.io/resume
 * Description:   Jobsity plugin for Test by Omar Boza
 * Version:       1.0.0
 * Author:        Omar Boza
 * Author URI:    http://omazon.github.io/resume
 * Text Domain:   jobsity
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
// Plugin name
define( 'JOBSITY_NAME',			'Jobsity' );

// Plugin version
define( 'JOBSITY_VERSION',		'1.0.0' );

// Plugin Root File
define( 'JOBSITY_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'JOBSITY_PLUGIN_BASE',	plugin_basename( JOBSITY_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'JOBSITY_PLUGIN_DIR',	plugin_dir_path( JOBSITY_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'JOBSITY_PLUGIN_URL',	plugin_dir_url( JOBSITY_PLUGIN_FILE ) );

if ( defined( 'MY_ACF_PATH' ) ) {
    return;
}
// Define path and URL to the ACF plugin.
define( 'MY_ACF_PATH', JOBSITY_PLUGIN_DIR . '/core/includes/acf/' );
define( 'MY_ACF_URL', JOBSITY_PLUGIN_URL . '/core/includes/acf/' );

// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );

// Customize the URL setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}

/**
 * Load the main class for the core functionality
 */
require_once JOBSITY_PLUGIN_DIR . 'core/class-jobsity.php';

/**
 * Include options page using ACF
 */
require_once JOBSITY_PLUGIN_DIR . 'core/themes.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Omar Boza
 * @since   1.0.0
 * @return  object|Jobsity
 */
function JOBSITY() {
	return Jobsity::instance();
}

JOBSITY();
