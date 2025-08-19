<?php
/**
 * Contact Form AJAX Handler
 * Handles contact form submissions via AJAX
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle contact form submission (logged in users)
 */
function nutrilux_handle_contact_form() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['contact_nonce'], 'nutrilux_contact')) {
        wp_send_json_error('Sigurnosna provjera neuspješna. Molimo osvježite stranicu i pokušajte ponovo.');
    }
    
    // Sanitize and validate input
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $message = sanitize_textarea_field($_POST['contact_message']);
    
    // Validation
    $errors = array();
    
    if (empty($name)) {
        $errors[] = 'Ime je obavezno';
    }
    
    if (empty($email) || !is_email($email)) {
        $errors[] = 'Ispravna email adresa je obavezna';
    }
    
    if (empty($message)) {
        $errors[] = 'Poruka je obavezna';
    }
    
    if (!empty($errors)) {
        wp_send_json_error(implode(', ', $errors));
    }
    
    // Prepare email
    $to = get_option('admin_email'); // Send to site admin
    $subject = 'Nova poruka sa Nutrilux kontakt forme';
    
    $email_message = "Nova poruka sa kontakt forme:\n\n";
    $email_message .= "Ime: " . $name . "\n";
    $email_message .= "Email: " . $email . "\n";
    $email_message .= "Datum: " . current_time('d.m.Y H:i') . "\n\n";
    $email_message .= "Poruka:\n" . $message . "\n\n";
    $email_message .= "---\n";
    $email_message .= "Ova poruka je poslana sa kontakt forme na " . home_url();
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: Nutrilux Kontakt <' . get_option('admin_email') . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );
    
    // Send email
    $mail_sent = wp_mail($to, $subject, $email_message, $headers);
    
    if ($mail_sent) {
        // Log successful contact
        error_log(sprintf(
            'Nutrilux Contact Form: Message sent from %s (%s) at %s',
            $name,
            $email,
            current_time('Y-m-d H:i:s')
        ));
        
        // Store contact in database (optional - for future reference)
        nutrilux_store_contact_submission($name, $email, $message);
        
        wp_send_json_success('Poruka je uspješno poslana. Hvala vam!');
    } else {
        error_log('Nutrilux Contact Form: Failed to send email from ' . $email);
        wp_send_json_error('Došlo je do greške prilikom slanja poruke. Molimo pokušajte ponovo ili nas kontaktirajte direktno.');
    }
}

/**
 * Handle contact form submission (non-logged in users)
 */
function nutrilux_handle_contact_form_nopriv() {
    nutrilux_handle_contact_form();
}

// Register AJAX handlers
add_action('wp_ajax_nutrilux_contact', 'nutrilux_handle_contact_form');
add_action('wp_ajax_nopriv_nutrilux_contact', 'nutrilux_handle_contact_form_nopriv');

/**
 * Store contact submission in database (optional)
 */
function nutrilux_store_contact_submission($name, $email, $message) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'nutrilux_contacts';
    
    // Create table if it doesn't exist
    nutrilux_create_contacts_table();
    
    // Insert contact submission
    $wpdb->insert(
        $table_name,
        array(
            'name' => $name,
            'email' => $email,
            'message' => $message,
            'submitted_at' => current_time('mysql'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ),
        array(
            '%s', '%s', '%s', '%s', '%s', '%s'
        )
    );
    
    return $wpdb->insert_id;
}

/**
 * Create contacts table
 */
function nutrilux_create_contacts_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'nutrilux_contacts';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        message text NOT NULL,
        submitted_at datetime NOT NULL,
        ip_address varchar(45) DEFAULT '',
        user_agent text DEFAULT '',
        status varchar(20) DEFAULT 'new',
        PRIMARY KEY (id),
        KEY email (email),
        KEY submitted_at (submitted_at)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * Admin page to view contact submissions
 */
function nutrilux_contact_admin_menu() {
    add_management_page(
        'Nutrilux Kontakti',
        'Kontakt Poruke',
        'manage_options',
        'nutrilux-contacts',
        'nutrilux_contact_admin_page'
    );
}
add_action('admin_menu', 'nutrilux_contact_admin_menu');

/**
 * Admin page content
 */
function nutrilux_contact_admin_page() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'nutrilux_contacts';
    
    // Handle status updates
    if (isset($_POST['update_status']) && isset($_POST['contact_id']) && isset($_POST['new_status'])) {
        if (wp_verify_nonce($_POST['status_nonce'], 'update_contact_status')) {
            $contact_id = intval($_POST['contact_id']);
            $new_status = sanitize_text_field($_POST['new_status']);
            
            $wpdb->update(
                $table_name,
                array('status' => $new_status),
                array('id' => $contact_id),
                array('%s'),
                array('%d')
            );
            
            echo '<div class="notice notice-success"><p>Status je ažuriran.</p></div>';
        }
    }
    
    // Get contacts
    $contacts = $wpdb->get_results("SELECT * FROM $table_name ORDER BY submitted_at DESC LIMIT 50");
    
    ?>
    <div class="wrap">
        <h1>Nutrilux Kontakt Poruke</h1>
        
        <?php if (empty($contacts)): ?>
            <p>Nema poslanih poruka.</p>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Ime</th>
                        <th>Email</th>
                        <th>Poruka</th>
                        <th>Status</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?php echo esc_html(date('d.m.Y H:i', strtotime($contact->submitted_at))); ?></td>
                            <td><?php echo esc_html($contact->name); ?></td>
                            <td>
                                <a href="mailto:<?php echo esc_attr($contact->email); ?>">
                                    <?php echo esc_html($contact->email); ?>
                                </a>
                            </td>
                            <td>
                                <?php echo esc_html(wp_trim_words($contact->message, 10)); ?>
                                <?php if (strlen($contact->message) > 100): ?>
                                    <br><small><a href="#" onclick="alert('<?php echo esc_js($contact->message); ?>'); return false;">Prikaži cijelu poruku</a></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-<?php echo esc_attr($contact->status); ?>">
                                    <?php echo ucfirst(esc_html($contact->status)); ?>
                                </span>
                            </td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <?php wp_nonce_field('update_contact_status', 'status_nonce'); ?>
                                    <input type="hidden" name="contact_id" value="<?php echo $contact->id; ?>">
                                    <select name="new_status">
                                        <option value="new" <?php selected($contact->status, 'new'); ?>>Nova</option>
                                        <option value="replied" <?php selected($contact->status, 'replied'); ?>>Odgovoreno</option>
                                        <option value="closed" <?php selected($contact->status, 'closed'); ?>>Zatvoreno</option>
                                    </select>
                                    <input type="submit" name="update_status" value="Ažuriraj" class="button-secondary">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <style>
    .status-new { color: #d63638; font-weight: bold; }
    .status-replied { color: #00a32a; }
    .status-closed { color: #646970; }
    </style>
    <?php
}

/**
 * Initialize contacts table on theme activation
 */
function nutrilux_init_contacts_table() {
    nutrilux_create_contacts_table();
}
add_action('after_switch_theme', 'nutrilux_init_contacts_table');
