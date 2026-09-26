<?php
// steam-proxy.php
header('Content-Type: application/json');

// Načtení hodnot z .env
$env = parse_ini_file('.env');
$apiKey = $env['STEAM_API_KEY'] ?? null;
$steamId = $env['STEAM_ID'] ?? null;

if (!$apiKey || !$steamId) {
    http_response_code(500);
    echo json_encode(['error' => 'Chybí konfigurace v .env']);
    exit;
}

// URL pro získání dat o profilu
$url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$apiKey}&steamids={$steamId}";

// Inicializace a nastavení cURL požadavku
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Vrátí odpoveď jako string
curl_setopt($ch, CURLOPT_TIMEOUT, 10);          // Max čas čekání v sekundách

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || !$response) {
    http_response_code(500);
    echo json_encode(['error' => 'Chyba při komunikaci se Steam API']);
    exit;
}

// Odeslání dat na frontend
echo $response;