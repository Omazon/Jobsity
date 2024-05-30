<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

include_once( JOBSITY_PLUGIN_DIR.'/core/blocks/popular-actors/fields.php' );
include_once( JOBSITY_PLUGIN_DIR.'/core/blocks/upcoming-movies/fields.php' );

add_filter( 'block_categories_all' , function( $categories ) {

    // Adding a new category.
    $categories[] = array(
        'slug'  => 'omar-boza',
        'title' => 'Omar Boza',
    );

    return $categories;
} );

function register_blocks() {
   register_block_type( JOBSITY_PLUGIN_DIR.'/core/blocks/upcoming-movies' );
   register_block_type( JOBSITY_PLUGIN_DIR.'/core/blocks/popular-actors' );
}
add_action( 'init', 'register_blocks' );