<?php

$key = "AQ.Ab8RN6I-rchZkJQKrm53E5QLJwY-0B3QcB-iNpYvIvocQvhMJA";

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=".$key;

$data = [
    "contents" => [
        [
            "parts" => [
                [
                    "text" => "Say Hello"
                ]
            ]
        ]
    ]
];

$ch = curl_init($url);

curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_HTTPHEADER,[
    "Content-Type: application/json"
]);
curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($data));

echo curl_exec($ch);