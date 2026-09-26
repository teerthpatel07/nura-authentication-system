<?php

header('Content-Type: application/json');

require_once __DIR__ . '/config.php';


try {

    /*
    |--------------------------------------------------------------------------
    | Get Redis session
    |--------------------------------------------------------------------------
    */

    $sessionToken =
        $_COOKIE['session_token'] ?? '';


    if ($sessionToken === '') {

        echo json_encode([
            'success' => false,
            'message' => 'You are not logged in.'
        ]);

        exit;
    }


    $sessionData = $redis->get(
        'auth_session:' . $sessionToken
    );


    if (!$sessionData) {

        echo json_encode([
            'success' => false,
            'message' => 'Session expired.'
        ]);

        exit;
    }


    $session = json_decode(
        $sessionData,
        true
    );


    $userId = (int) $session['user_id'];


    /*
    |--------------------------------------------------------------------------
    | Save profile to MongoDB
    |--------------------------------------------------------------------------
    */

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $fullName =
            trim($_POST['full_name'] ?? '');

        $phone =
            trim($_POST['phone'] ?? '');

        $bio =
            trim($_POST['bio'] ?? '');


        $profiles =
            $mongoDb->profiles;


        $profiles->updateOne(

            [
                'user_id' => $userId
            ],

            [
                '$set' => [
                    'user_id' => $userId,
                    'full_name' => $fullName,
                    'phone' => $phone,
                    'bio' => $bio,
                    'updated_at' =>
                        new MongoDB\BSON\UTCDateTime()
                ]
            ],

            [
                'upsert' => true
            ]

        );


        echo json_encode([
            'success' => true,
            'message' => 'Profile saved successfully!'
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Get account data from MySQL
    |--------------------------------------------------------------------------
    */

    $stmt = $mysql->prepare(
        "SELECT
            id,
            username,
            email
         FROM users
         WHERE id = :id
         LIMIT 1"
    );


    $stmt->execute([
        ':id' => $userId
    ]);


    $user = $stmt->fetch();


    if (!$user) {

        echo json_encode([
            'success' => false,
            'message' => 'User not found.'
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Get profile data from MongoDB
    |--------------------------------------------------------------------------
    */

    $profiles =
        $mongoDb->profiles;


    $profile = $profiles->findOne([
        'user_id' => $userId
    ]);


    echo json_encode([

        'success' => true,

        'user' => [

            'id' => $user['id'],

            'username' =>
                $user['username'],

            'email' =>
                $user['email']

        ],

        'profile' => $profile ? [

            'full_name' =>
                $profile['full_name'] ?? '',

            'phone' =>
                $profile['phone'] ?? '',

            'bio' =>
                $profile['bio'] ?? ''

        ] : [

            'full_name' => '',

            'phone' => '',

            'bio' => ''

        ]

    ]);


} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Unable to process profile.'
    ]);
}