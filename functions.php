<?php

function enqueue_styles_scripts(): void
{
    my_theme_styles();
    my_theme_script();
}
function my_theme_styles(): void
{
    wp_register_style( 'my-styles', get_stylesheet_directory_uri() . '/assets/css/index.css');
    wp_enqueue_style( 'my-styles', get_stylesheet_directory_uri() . '/assets/css/index.css');
}

function my_theme_script(): void
{
    wp_register_script( 'my-scripts', get_stylesheet_directory_uri() . '/assets/js/index.js');
    wp_enqueue_script( 'my-scripts', get_stylesheet_directory_uri() . '/assets/js/index.js');
    wp_localize_script('my-scripts', 'myInfoVars', array(
        'rest_nonce'           => wp_create_nonce( 'wp_rest' ),
        'site_url'           => get_site_url().'/',
    ));
}
add_action( 'wp_enqueue_scripts', 'enqueue_styles_scripts' );

function register_books_routes(): void
{
    $namespace = 'get_books/v1';
    $books_base = '/books';
    register_rest_route(
        $namespace,
        $books_base,
        array(
            'method'  => WP_REST_Server::READABLE,
            'callback' => 'prefix_get_endpoint_phrase',
            'permission_callback' => function(){
                return current_user_can( 'read' );
            },
        ),
    );
}

function prefix_get_endpoint_phrase( WP_REST_Request $request ): WP_Error|WP_REST_Response|WP_HTTP_Response
{

    $nonce = $request->get_header('X-WP-Nonce' );
    if ( !wp_verify_nonce( $nonce, 'wp_rest' ) ) {
        return new WP_Error('invalid_nonce', __('Invalid nonce.', 'books-plugin'), array('status' => 403));
    }
    $args = array(
        'post_type' => 'book',
        'posts_per_page' => -1,
    );

    $query = new WP_Query( $args );
    $books = [];
    $post_ids = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $books[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'content' => get_the_content(),
                'image' => get_the_post_thumbnail_url(),
                'meta' => get_post_meta(get_the_ID()),
            );
            $post_ids = get_the_ID();
        }
        wp_reset_postdata();
    }

    $result = array(
        'data' => $books,
        'ids' => $post_ids,
    );

    return rest_ensure_response( $books );
}


add_action( 'rest_api_init', 'register_books_routes' );
//add_action('wp_enqueue_scripts', 'custom_enqueue_scripts');