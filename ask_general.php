<?php
// ask_general.php
header('Content-Type: application/json');

// Récupère la question depuis POST
$question = $_POST['question'] ?? '';
if (!$question) {
    echo json_encode(['answer' => '❌ Pas de question reçue']);
    exit;
}

// Clé Cohere stockée dans Render (Settings → Environment Variables)
$cohere_api_key = getenv('COHERE_API_KEY');
if (!$cohere_api_key) {
    echo json_encode(['answer' => '❌ Clé API manquante']);
    exit;
}

// Préparer les données pour Cohere
$data = [
    "model" => "command-xlarge-nightly",
    "prompt" => "Réponds simplement et clairement à cette question :\n$question",
    "max_tokens" => 150
];

// Appel API Cohere
$ch = curl_init('https://api.cohere.ai/generate');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $cohere_api_key,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$result = curl_exec($ch);
if ($result === false) {
    echo json_encode(['answer' => '❌ Erreur API Cohere']);
    exit;
}
curl_close($ch);

// Décoder la réponse
$json = json_decode($result, true);
$answer = $json['generations'][0]['text'] ?? '❌ Pas de réponse.';

// Retourner au frontend
echo json_encode(['answer' => trim($answer)]);
