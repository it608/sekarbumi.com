<?php
function display_ai_chat_messages($post) {
    global $wpdb;

    // Number of messages per page
    $messages_per_page = 10;

    // Get current page from the query parameters, default to page 1 if not set
    $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // Calculate the offset for the query (for pagination)
    $offset = ($current_page - 1) * $messages_per_page;

    // Replace 'YPM_AI_CHAT_MESSAGES' with your actual table name constant if needed
    $table_name = $wpdb->prefix . 'YPM_AI_CHAT_MESSAGES'; 

    // Fetch messages from the table where chat_id matches the post ID, limited by pagination
    $messages = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM {$table_name} WHERE chat_id = %d LIMIT %d OFFSET %d", $post, $messages_per_page, $offset),
        ARRAY_A
    );

    // Count the total number of messages for the current post
    $total_messages = $wpdb->get_var(
        $wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE chat_id = %d", $post)
    );

    // Calculate the total number of pages
    $total_pages = ceil($total_messages / $messages_per_page);

    if (!empty($messages)) {
        echo '<table style="width: 100%; border-collapse: collapse;">';
        echo '<thead>
                <tr>
                    <th style="border: 1px solid #ddd; padding: 8px;">First Name</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Last Name</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Email</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Message</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Response</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Date</th>
                </tr>
              </thead>';
        echo '<tbody>';

        foreach ($messages as $message) {
            // If user_id exists, fetch user data (first name, last name, email)
            if ($message['user_id']) {
                $user = get_user_by('id', $message['user_id']);
                $first_name = $user ? $user->first_name : 'Unknown';
                $last_name = $user ? $user->last_name : 'Unknown';
                $email = $user ? $user->user_email : 'Unknown';
            } else {
                // If no user_id exists, display "Guest" for name and email
                $first_name = 'Guest';
                $last_name = '';
                $email = 'N/A';
            }

            echo '<tr>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($first_name) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($last_name) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($email) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($message['message']) . '</td>'; // Assuming 'message' stores the actual user message
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($message['response']) . '</td>'; // Displaying the response field
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($message['cDate']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';

        // Display pagination controls
        echo '<div class="pagination" style="margin-top: 20px;">';
        
        // Previous Page Link
        if ($current_page > 1) {
            echo '<a href="' . add_query_arg('page', $current_page - 1) . '" style="margin-right: 10px;">&laquo; Previous</a>';
        }

        // Next Page Link
        if ($current_page < $total_pages) {
            echo '<a href="' . add_query_arg('page', $current_page + 1) . '" style="margin-left: 10px;">Next &raquo;</a>';
        }

        echo '</div>';

    } else {
        echo '<p>No messages found for this post.</p>';
    }
}
$post = 0;
if (!empty($_GET['post'])) {
    $post = $_GET['post'];
}

display_ai_chat_messages($post);
