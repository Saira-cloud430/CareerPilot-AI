<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load Composer
require_once __DIR__ . '/vendor/autoload.php';

// Load Database
require_once __DIR__ . '/includes/db_connect.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Timezone
date_default_timezone_set("Asia/Karachi");