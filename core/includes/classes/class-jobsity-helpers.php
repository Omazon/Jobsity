<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Jobsity_Helpers
 *
 * This class contains repetitive functions that
 * are used globally within the plugin.
 *
 * @package		JOBSITY
 * @subpackage	Classes/Jobsity_Helpers
 * @author		Omar Boza
 * @since		1.0.0
 */
class Jobsity_Helpers{

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */

    function __construct(){
        $this->add_hooks();
    }
    private function add_hooks(){
        add_action('init', array($this,'handle_movie_route'));
        add_filter('query_vars', array($this,'add_query_var_movie'));
        add_action('template_redirect', array($this,'template_redirect_movie'));
    }

    public function handle_movie_route() {
        add_rewrite_rule('^movie/([^/]+)/?', 'index.php?movie_api=$matches[1]', 'top');
    }

    public function add_query_var_movie($vars) {
        $vars[] = 'movie_api';
        return $vars;
    }

    public function template_redirect_movie() {
        $movie_slug = get_query_var('movie_api');
        if ($movie_slug) {
            include(JOBSITY_PLUGIN_DIR . '/single-movie-api.php');
            exit;
        }
    }

}
