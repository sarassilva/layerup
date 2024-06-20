<?php
//script
function enqueue_scripts() {
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/script.js', array(), null, true);
    
    // Passar a URL do site para o script.js
    wp_localize_script('custom-script', 'siteData', array(
        'siteUrl' => site_url(),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_scripts');

//endpoints
function register_endpoints() {
    error_log('Registering custom REST API endpoints'); // Log para verificar se a função está sendo chamada

    register_rest_route('layerup/v1', '/categories', array(
        'methods' => 'GET',
        'callback' => 'callback_get_categories',
    ));

    register_rest_route('layerup/v1', '/posts/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'callback_get_posts_by_category',
    ));

    register_rest_route('layerup/v1', '/post/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'callback_get_post_content',
    ));
}

add_action('rest_api_init', 'register_endpoints');

//callbacks
function callback_get_categories() {
    $categories = get_categories();
    $data = array();

    foreach ($categories as $category) {
        $data[] = array(
            'id' => $category->term_id,
            'name' => $category->name,
        );
    }

    return $data;
}

function callback_get_posts_by_category($data) {
    $args = array(
        'cat' => $data['id'],
        'numberposts' => -1
    );
    $posts = get_posts($args);
    $data = array();

    foreach ($posts as $post) {
        $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'thumbnail');

        $data[] = array(
            'id' => $post->ID,
            'thumbnail' => $thumbnail_url ? $thumbnail_url : '',
            'title' => get_the_title($post),
            
        );
    }

    return $data;
}

function callback_get_post_content($data) {
    $post = get_post($data['id']);
    if (!$post) {
        return new WP_Error('no_post', 'Post not found', array('status' => 404));
    }

    return array(
        'id' => $post->ID,
        'title' => get_the_title($post),
        'content' => apply_filters('the_content', $post->post_content),
    );
}
?>
