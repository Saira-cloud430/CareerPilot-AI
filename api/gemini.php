<?php

require_once __DIR__ . "/../config.php";

use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;

function askGemini($prompt)
{
    try {

        $client = new Client($_ENV['GEMINI_API_KEY']);

        $response = $client
            ->generativeModel("gemini-2.5-flash-lite")
            ->generateContent(
                new TextPart($prompt)
            );

        return $response->text();

    } 
    catch (Exception $e) {

    die("<pre>" . $e->getMessage() . "</pre>");

}
}