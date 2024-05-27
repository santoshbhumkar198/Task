<?php
// Function to create the guest post submission form
function guest_post_submission_form() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guest_post_submit'])) {
        // Validate form inputs
        $title = sanitize_text_field($_POST['guest_post_title']);
        $content = wp_kses_post($_POST['guest_post_content']);
        $author = sanitize_text_field($_POST['guest_post_author']);
        $email = sanitize_email($_POST['guest_post_email']);

        $errors = array();
        if (empty($title)) {
            $errors[] = 'Title is required.';
        }
        if (empty($content)) {
            $errors[] = 'Content is required.';
        }
        if (empty($author)) {
            $errors[] = 'Author name is required.';
        }
        if (empty($email) || !is_email($email)) {
            $errors[] = 'Valid email address is required.';
        }

        if (empty($errors)) {
            // Insert guest post as a custom post type
            $post_data = array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'pending', // Set to 'pending' for admin approval
                'post_type'    => 'guest_post',
                'meta_input'   => array(
                    'guest_post_author' => $author,
                    'guest_post_email'  => $email
                )
            );
            $post_id = wp_insert_post($post_data);

            if ($post_id) {
                echo '<p>Thank you for your submission! Your post is pending approval.</p>';
            } else {
                echo '<p>There was an error submitting your post. Please try again.</p>';
            }
        } else {
            foreach ($errors as $error) {
                echo '<p>' . esc_html($error) . '</p>';
            }
        }
    }

    ob_start(); ?>

    <form class="guest-post-form" method="post">
        <label for="guest_post_title">Title:</label>
        <input type="text" id="guest_post_title" name="guest_post_title" required><br>
        <label for="guest_post_content">Content:</label>
        <textarea id="guest_post_content" name="guest_post_content" required></textarea><br>
        <label for="guest_post_author">Author Name:</label>
        <input type="text" id="guest_post_author" name="guest_post_author" required><br>
        <label for="guest_post_email">Email Address:</label>
        <input type="email" id="guest_post_email" name="guest_post_email" required><br>
        <input type="submit" name="guest_post_submit" value="Submit Post">
    </form>

    <?php return ob_get_clean();
}

add_shortcode('guest_post_form', 'guest_post_submission_form');
?>
