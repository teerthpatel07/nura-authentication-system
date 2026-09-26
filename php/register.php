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


    $username = trim($_POST['username'] ?? '');

    $email = trim($_POST['email'] ?? '');

    $password = $_POST['password'] ?? '';


    // Required fields

    if (
        $username === '' ||
        $email === '' ||
        $password === ''
    ) {

        echo json_encode([
            'success' => false,
            'message' => 'All fields are required.'
        ]);

        exit;
    }


    // Username validation

    if (!preg_match(
        '/^[a-zA-Z0-9_]{3,50}$/',
        $username
    )) {

        echo json_encode([
            'success' => false,
            'message' => 'Invalid username.'
        ]);

        exit;
    }


    // Email validation

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


    // Password validation

    if (strlen($password) < 6) {

        echo json_encode([
            'success' => false,
            'message' => 'Password must contain at least 6 characters.'
        ]);

        exit;
    }


    // Check existing user

    $check = $mysql->prepare(
        "SELECT id
         FROM users
         WHERE username = :username
         OR email = :email
         LIMIT 1"
    );


    $check->execute([
        ':username' => $username,
        ':email' => $email
    ]);


    if ($check->fetch()) {

        echo json_encode([
            'success' => false,
            'message' => 'Username or email already exists.'
        ]);

        exit;
    }


    // Hash password

    $passwordHash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    // Insert user

    $insert = $mysql->prepare(
        "INSERT INTO users
        (username, email, password_hash)
        VALUES
        (:username, :email, :password_hash)"
    );


    $insert->execute([
        ':username' => $username,
        ':email' => $email,
        ':password_hash' => $passwordHash
    ]);


    echo json_encode([
        'success' => true,
        'message' => 'Registration successful!'
    ]);


} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Something went wrong.'
    ]);
}