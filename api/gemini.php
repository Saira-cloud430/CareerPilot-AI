<?php

require_once __DIR__ . "/../config.php";

function askGemini($prompt)
{
    $apiKey = trim(
    $_ENV['GEMINI_API_KEY']
    ?? $_SERVER['GEMINI_API_KEY']
    ?? ''
);

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . urlencode($apiKey);

    $body = [
        "contents" => [
            [
                "parts" => [
                    [
                        "text" => $prompt
                    ]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return "Curl Error: " . curl_error($ch);
    }

    curl_close($ch);

    $json = json_decode($response, true);

    // If Google returns an error, show it.
    if (isset($json['error'])) {
        return "<pre>" . json_encode($json, JSON_PRETTY_PRINT) . "</pre>";
    }

    return $json['candidates'][0]['content']['parts'][0]['text'] ?? "No response.";
}