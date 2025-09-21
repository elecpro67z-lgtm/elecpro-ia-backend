<?php
// ask_general.php
header('Content-Type: application/json');

// Récupère la question
$question = $_POST['question'] ?? '';
if (!$question) {
    echo json_encode(['answer' => '⚠️ Aucune question reçue.']);
    exit;
}

// Récupérer la clé API depuis les variables d’environnement
$cohere_api_key = getenv('COHERE_API_KEY');
if (!$cohere_api_key) {
    echo json_encode(['answer' => '⚠️ Clé API manquante côté serveur.']);
    exit;
}

// Préparer la requête pour Cohere
$data = [
    "model" => "command-xlarge-nightly",
    "prompt" => "Tu es un assistant expert en électricité, finances, entrepreneuriat et pédagogie. Réponds clairement et de façon professionnelle:\n\nQuestion: $question\nRéponse:",
    "max_tokens" => 200,
    "temperature" => 0.7
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
    echo json_encode(['answer' => '⚠️ Erreur de connexion à Cohere.']);
    exit;
}
curl_close($ch);

$json = json_decode($result, true);
$answer = $json['generations'][0]['text'] ?? '⚠️ Pas de réponse de l’IA.';

echo json_encode(['answer' => trim($answer)]);
