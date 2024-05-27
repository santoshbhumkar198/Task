<?php
// Function to add admin menu page
function guest_post_admin_menu() {
    add_menu_page(
        'Guest Posts',
        'Guest Posts',
        'manage_options',
        'guest-posts',
        'guest_posts_admin_page',
        'dashicons-admin-post',
        6
    );
}

add_action('admin_menu', 'guest_post_admin_menu');

// Function to display the admin page content
function guest_posts_admin_page() {
    global $wpdb;

    // Pagination setup
    $limit = 10;
    $offset = (isset($_GET['paged']) && is_numeric($_GET['paged'])) ? ($_GET['paged'] - 1) * $limit : 0;

    $results = $wpdb->get_results(
        "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'guest_post' ORDER BY post_date DESC LIMIT $limit OFFSET $offset"
    );

    $total = $wpdb->get_var("SELECT COUNT(1) FROM {$wpdb->prefix}posts WHERE post_type = 'guest_post'");
    $total_pages = ceil($total / $limit);

    if (isset($_GET['approve'])) {
        $post_id = intval($_GET['approve']);
        wp_update_post(array('ID' => $post_id, 'post_status' => 'publish'));
    }

    if (isset($_GET['reject'])) {
        $post_id = intval($_GET['reject']);
        wp_delete_post($post_id);
    }

    ?>
    <div class="wrap">
        <h1>Guest Posts</h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Submission Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($results): ?>
                    <?php foreach ($results as $post): ?>
                        <tr>
                            <td><?php echo $post->ID; ?></td>
                            <td><?php echo $post->post_title; ?></td>
                            <td><?php echo get_post_meta($post->ID, 'guest_post_author', true); ?></td>
                            <td><?php echo $post->post_date; ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=guest-posts&approve=' . $post->ID); ?>">Approve</a> | 
                                <a href="<?php echo admin_url('admin.php?page=guest-posts&reject=' . $post->ID); ?>">Reject</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No guest posts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="tablenav">
            <div class="tablenav-pages">
                <?php echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => __('&laquo;'),
                    'next_text' => __('&raquo;'),
                    'total' => $total_pages,
                    'current' => max(1, isset($_GET['paged']) ? intval($_GET['paged']) : 1)
                )); ?>
            </div>
        </div>
    </div>
    <?php
}
?>
