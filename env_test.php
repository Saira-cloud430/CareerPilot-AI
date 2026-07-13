<?php

require_once "config.php";

echo "<pre>";

echo "ENV:\n";
var_dump($_ENV['GEMINI_API_KEY'] ?? null);

echo "\nSERVER:\n";
var_dump($_SERVER['GEMINI_API_KEY'] ?? null);

echo "\nGETENV:\n";
var_dump(getenv('GEMINI_API_KEY'));

echo "</pre>";