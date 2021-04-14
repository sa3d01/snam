<?php
/**
 * @see https://github.com/Edujugon/PushNotification
 */

return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
    ],
    'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        // 'apiKey' => 'AAAA8LTaCM8:APA91bG3gTHQ0RHKBhimS9ILKAI0WYrYgmqKdwFX5eAxEmGtKcl3o3uU8lJ9ojEpk2rCrWmTp4X8nDIZCMbX1-aaxAl9I_N9VpVmlVbgGynXorJ8g9NYduRBdrm_6K4kn0p-yTJYGQW7',
        'apiKey' => 'AAAAbynyft0:APA91bHGJkmxRIqPsv1skBapw4-tKe30Ex4FTyD8A4oB8bhNNdYTsUXKGgLfD1pmFFMeukpksuQWGtAFFQeqUOYl_IB_-u2JDoPx-lyaDpYfwJchMWnx1czSEYRLe5x0_WyJOFEQh9Wa',

    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
        'passPhrase' => 'secret', //Optional
        'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
        'dry_run' => true,
    ],
];
