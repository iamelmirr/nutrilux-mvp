<?php
// KREIRANJE ADMIN USERA ZA WORDPRESS
// Dodati na vrh functions.php ili pokrenuti kao standalone script

function create_emergency_admin() {
    $username = 'nutrilux';
    $password = 'nutrilux123';
    $email = 'admin@nutrilux.local';
    
    if (!username_exists($username) && !email_exists($email)) {
        $user_id = wp_create_user($username, $password, $email);
        if (!is_wp_error($user_id)) {
            $user = new WP_User($user_id);
            $user->set_role('administrator');
            error_log("Emergency admin created: $username / $password");
        }
    }
}

// Pokreni samo jednom
add_action('init', function() {
    if (isset($_GET['create_admin'])) {
        create_emergency_admin();
        echo "Admin kreiran: nutrilux / nutrilux123";
        exit;
    }
});
?>
