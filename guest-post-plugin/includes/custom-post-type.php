<?php
// Function to register the custom post type
function create_guest_post_type() {
    register_post_type('guest_post',
        array(
            'labels'      => array(
                'name'          => __('Guest Posts'),
                'singular_name' => __('Guest Post'),
            ),
            'public'      => true,
            'has_archive' => true,
            'supports'    => array('title', 'editor'),
            'show_in_rest' => true,
        )
    );
}

add_action('init', 'create_guest_post_type');
?>
