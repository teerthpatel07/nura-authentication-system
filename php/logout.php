<?php

header('Content-Type: application/json');

require_once __DIR__ . '/config.php';


try {

    $sessionToken =
        $_COOKIE['session_token'] ?? '';


    if ($sessionToken !== '') {

        // Delete Redis session

        $redis->del([
            'auth_session:' . $sessionToken
        ]);


        // Delete browser cookie

        setcookie(
            'session_token',
            '',
            [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );
    }


    echo json_encode([
        'success' => true,
        'message' => 'Logged out successfully.'
    ]);


} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Unable to logout.'
    ]);
}