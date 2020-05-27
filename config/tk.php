<?php

return [
    // Seeder file location
    'seeder_file_path' => '/database/seeds/tk_seeds.xlsx',
    // For non-production environments, Recipient Address for certain e-mails sent by application for testing purposes and to avoid spamming real users.
    'mail_to_test_account' => 'info@tournamentkings.com',
    // match image file location
    'match_image_path' => env('APP_ENV').'/matches',
];
