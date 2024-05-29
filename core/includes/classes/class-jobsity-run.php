<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Jobsity_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		JOBSITY
 * @subpackage	Classes/Jobsity_Run
 * @author		Omar Boza
 * @since		1.0.0
 */
class Jobsity_Run{

	/**
	 * Our Jobsity_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts_and_styles' ), 20 );
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */


	/**
	 * Enqueue the frontend related scripts and styles for this plugin.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_frontend_scripts_and_styles() {
		wp_enqueue_style( 'jobsity-frontend-styles', JOBSITY_PLUGIN_URL . 'core/includes/assets/css/frontend-styles.css', array(), JOBSITY_VERSION, 'all' );
		wp_enqueue_script( 'jobsity-frontend-scripts', JOBSITY_PLUGIN_URL . 'core/includes/assets/js/frontend-scripts.js', array(), JOBSITY_VERSION, 'all' );

        wp_localize_script('jobsity-frontend-scripts', 'siteData', array(
            'apiUrl' => home_url('/wp-json/jobsity/v2/'),
            'siteName' => get_bloginfo('name'),
        ));
	}


}
