<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Jobsity_Settings
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		JOBSITY
 * @subpackage	Classes/Jobsity_Settings
 * @author		Omar Boza
 * @since		1.0.0
 */
class Jobsity_Settings{

	/**
	 * The plugin name
	 *
	 * @var		string
	 * @since   1.0.0
	 */
	private $plugin_name;

	/**
	 * Our Jobsity_Settings constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){

		$this->plugin_name = JOBSITY_NAME;
        add_action( 'init', array($this, 'remove_divi_project_cpt'));
	}

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */

	/**
	 * Return the plugin name
	 *
	 * @access	public
	 * @since	1.0.0
	 * @return	string The plugin name
	 */
	public function get_plugin_name(){
		return apply_filters( 'JOBSITY/settings/get_plugin_name', $this->plugin_name );
	}

    public function remove_divi_project_cpt(){
        unregister_post_type( 'project' );
        unregister_taxonomy( 'project_category' );
        unregister_taxonomy( 'project_tag' );
    }
}
