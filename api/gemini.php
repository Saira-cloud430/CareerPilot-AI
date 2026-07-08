<?php

require_once __DIR__ . "/../config.php";

use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;

function askGemini($prompt)
{
    $client = new Client($_ENV['GEMINI_API_KEY']);

    $response = $client
    ->generativeModel("gemini-2.5-flash")
    ->generateContent(new TextPart($prompt));

    return $response->text();
}