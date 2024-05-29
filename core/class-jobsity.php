<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'Jobsity' ) ) :

	/**
	 * Main Jobsity Class.
	 *
	 * @package		JOBSITY
	 * @subpackage	Classes/Jobsity
	 * @since		1.0.0
	 * @author		Omar Boza
	 */
	final class Jobsity {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Jobsity
		 */
		private static $instance;

		/**
		 * JOBSITY settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Jobsity_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'jobsity' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'jobsity' ), '1.0.0' );
		}

		/**
		 * Main Jobsity Instance.
		 *
		 * Insures that only one instance of Jobsity exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Jobsity	The one true Jobsity
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Jobsity ) ) {
				self::$instance					= new Jobsity;
				self::$instance->base_hooks();
				self::$instance->includes();
                self::$instance->helpers		= new Jobsity_Helpers();
				self::$instance->settings		= new Jobsity_Settings();
				self::$instance->rest		    = new Jobsity_REST();

				//Fire the plugin logic
				new Jobsity_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'JOBSITY/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
            require_once JOBSITY_PLUGIN_DIR . 'core/includes/classes/class-jobsity-helpers.php';
			require_once JOBSITY_PLUGIN_DIR . 'core/includes/classes/class-jobsity-settings.php';
			require_once JOBSITY_PLUGIN_DIR . 'core/includes/classes/class-jobsity-run.php';
			require_once JOBSITY_PLUGIN_DIR . 'core/includes/classes/class-jobsity-rest.php';
			require_once JOBSITY_PLUGIN_DIR . 'core/blocks/index.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'jobsity', FALSE, dirname( plugin_basename( JOBSITY_PLUGIN_FILE ) ) . '/languages/' );
		}
	}

endif; // End if class_exists check.