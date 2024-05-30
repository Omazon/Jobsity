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

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/movie/(?P<movie_id>\d+)', [
                'methods' => 'GET',
                'callback' => [$this,'get_single_movie'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/movie/(?P<movie_id>\d+)/alternative-titles', [
                'methods' => 'GET',
                'callback' => [$this,'get_alternative_titles'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/movie/(?P<movie_id>\d+)/videos', [
                'methods' => 'GET',
                'callback' => [$this,'get_trailer'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/movie/(?P<movie_id>\d+)/credits', [
                'methods' => 'GET',
                'callback' => [$this,'get_credits'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/movie/(?P<movie_id>\d+)/reviews', [
                'methods' => 'GET',
                'callback' => [$this,'get_reviews'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/movie/(?P<movie_id>\d+)/similar', [
                'methods' => 'GET',
                'callback' => [$this,'get_similar_movies'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/person/(?P<actor_id>\d+)', [
                'methods' => 'GET',
                'callback' => [$this,'get_single_actor'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/person/(?P<actor_id>\d+)/images', [
                'methods' => 'GET',
                'callback' => [$this,'get_actor_images'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/person/(?P<actor_id>\d+)/movie-credits', [
                'methods' => 'GET',
                'callback' => [$this,'get_actor_movies'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/person', [
                'methods' => 'GET',
                'callback' => [$this,'get_actor_query'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/movie', [
                'methods' => 'GET',
                'callback' => [$this,'get_movie_query'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/discover/movie/year', [
                'methods' => 'GET',
                'callback' => [$this,'get_movie_year'],
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('jobsity/v2', '/discover/movie/genre', [
                'methods' => 'GET',
                'callback' => [$this,'get_movie_genre'],
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

    function get_single_movie(WP_REST_Request $request) {
        $movie_id = $request->get_param('movie_id');
        $response = $this->client->request('GET', "movie/{$movie_id}?language=en-US");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_alternative_titles(WP_REST_Request $request) {
        $movie_id = $request->get_param('movie_id');
        $response = $this->client->request('GET', "movie/{$movie_id}/alternative_titles?language=en-US");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_trailer(WP_REST_Request $request) {
        $movie_id = $request->get_param('movie_id');
        $response = $this->client->request('GET', "movie/{$movie_id}/videos?language=en-US");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }
    function get_credits(WP_REST_Request $request) {
        $movie_id = $request->get_param('movie_id');
        $response = $this->client->request('GET', "movie/{$movie_id}/credits?language=en-US");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_reviews(WP_REST_Request $request) {
        $movie_id = $request->get_param('movie_id');
        $response = $this->client->request('GET', "movie/{$movie_id}/reviews?language=en-US");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_similar_movies(WP_REST_Request $request) {
        $movie_id = $request->get_param('movie_id');
        $response = $this->client->request('GET', "movie/{$movie_id}/similar?language=en-US");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_single_actor(WP_REST_Request $request) {
        $actor_id = $request->get_param('actor_id');
        $response = $this->client->request('GET', "person/{$actor_id}?language=en-US");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_actor_images(WP_REST_Request $request) {
        $actor_id = $request->get_param('actor_id');
        $response = $this->client->request('GET', "person/{$actor_id}/images?language=en-US");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_actor_movies(WP_REST_Request $request) {
        $actor_id = $request->get_param('actor_id');
        $response = $this->client->request('GET', "person/{$actor_id}/movie_credits?language=en-US");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_actor_query(WP_REST_Request $request) {
        $query = $request->get_param('query');
        if (!$query) {
            return new WP_Error('no_query', 'Query parameter is required', ['status' => 400]);
        }
        $response = $this->client->request('GET', "search/person?query={$query}&api_key=$this->access_token}");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }


    function get_movie_query(WP_REST_Request $request) {
        $query = $request->get_param('query');
        if (!$query) {
            return new WP_Error('no_query', 'Query parameter is required', ['status' => 400]);
        }
        $response = $this->client->request('GET', "search/movie?query={$query}&api_key=$this->access_token}");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_movie_year(WP_REST_Request $request) {
        $query = $request->get_param('query');
        if (!$query) {
            return new WP_Error('no_query', 'Query parameter is required', ['status' => 400]);
        }
        $response = $this->client->request('GET', "discover/movie?primary_release_year={$query}");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

    function get_movie_genre(WP_REST_Request $request) {
        $query = $request->get_param('query');
        if (!$query) {
            return new WP_Error('no_query', 'Query parameter is required', ['status' => 400]);
        }
        $response = $this->client->request('GET', "discover/movie?with_genres={$query}");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return new WP_REST_Response($data, 200);
    }

}

