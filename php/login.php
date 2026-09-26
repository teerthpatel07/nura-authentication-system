<?php

header('Content-Type: application/json');

require_once __DIR__ . '/config.php';


try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

        echo json_encode([
            'success' => false,
            'message' => 'Invalid request.'
        ]);

        exit;
    }


    $email = trim($_POST['email'] ?? '');

    $password = $_POST['password'] ?? '';


    if (
        $email === '' ||
        $password === ''
    ) {

        echo json_encode([
            'success' => false,
            'message' => 'Email and password are required.'
        ]);

        exit;
    }


    if (!filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )) {

        echo json_encode([
            'success' => false,
            'message' => 'Invalid email address.'
        ]);

        exit;
    }


    // Find user

    $stmt = $mysql->prepare(
        "SELECT
            id,
            username,
            email,
            password_hash
         FROM users
         WHERE email = :email
         LIMIT 1"
    );


    $stmt->execute([
        ':email' => $email
    ]);


    $user = $stmt->fetch();


    // Verify password

    if (
        !$user ||
        !password_verify(
            $password,
            $user['password_hash']
        )
    ) {

        echo json_encode([
            'success' => false,
            'message' => 'Invalid email or password.'
        ]);

        exit;
    }


    // Create session token

    $sessionToken = bin2hex(
        random_bytes(32)
    );


    // Store session in Redis

    $redis->setex(
        'auth_session:' . $sessionToken,
        3600,
        json_encode([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email']
        ])
    );


    // Store token in browser cookie

    setcookie(
        'session_token',
        $sessionToken,
        [
            'expires' => time() + 3600,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]
    );


    echo json_encode([
        'success' => true,
        'message' => 'Login successful!'
    ]);


} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Something went wrong.'
    ]);
}