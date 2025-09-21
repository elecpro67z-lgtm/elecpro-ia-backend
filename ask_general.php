<?php
header('Content-Type: application/json');

$question = $_POST['question'] ?? '';
if (!$question) {
    echo json_encode(['answer' => 'Pas de question reçue']);
    exit;
}

$cohere_api_key = 'eyxIrNJwxD7ewdn2Ed8XHjfenfuWSyGfgWxbPi1Z';
$data = [
    "model" => "command-xlarge-nightly",
    "prompt" => "Réponds à la question de façon simple et claire:\n$question",
    "max_tokens" => 150
];

$ch = curl_init('https://api.cohere.ai/generate');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $cohere_api_key,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$result = curl_exec($ch);
curl_close($ch);

$json = json_decode($result, true);
$answer = $json['generations'][0]['text'] ?? 'Pas de réponse.';
echo json_encode(['answer' => trim($answer)]);
