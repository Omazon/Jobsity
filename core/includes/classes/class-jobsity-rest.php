<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
require_once JOBSITY_PLUGIN_DIR.'vendor/autoload.php';




/**
 * Jobsity REST
 *
 * @package		JOBSITY
 * @subpackage	Classes/Jobsity_REST
 * @author		Omar Boza
 * @since		1.0.0
 */
class Jobsity_REST {
    private $client;
    private $access_token;

    function __construct() {

        $this->register_routes();
        $this->initialize_guzzle_client();
    }

    private function initialize_guzzle_client() {
        $this->access_token = get_field('token', 'options');
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.themoviedb.org/3/',
            'headers' => [
                'Authorization' => 'Bearer '.$this->access_token,
                'accept' => 'application/json',
            ]
        ]);
    }

    private function register_routes() {
        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/upcoming-movies', [
                'methods' => 'GET',
                'callback' => [$this,'get_upcoming_movies'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/genres', [
                'methods' => 'GET',
                'callback' => [$this,'get_genres'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/popular-actors', [
                'methods' => 'GET',
                'callback' => [$this,'get_popular_actors'],
            ]);
        });
    }

    function get_upcoming_movies(WP_REST_Request $request) {
        $response = $this->client->request('GET', 'movie/upcoming?language=en-US&page=1');

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_genres(WP_REST_Request $request) {
        $response = $this->client->request('GET', 'genre/movie/list?language=en');

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_popular_actors(WP_REST_Request $request) {
        $response = $this->client->request('GET', 'person/popular?language=en-US&page=1');

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }



}

