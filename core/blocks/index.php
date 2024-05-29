<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

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