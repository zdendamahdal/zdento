<?php

/* TODO: -  počet banů a dní od posledního banu
         -  Stav: Online / Offline / In-Game / Away / Busy.
         -  Datum vytvoření účtu
         -  celkový odehraný čas
*/

// steam-proxy.php



//FIXME: nenacítá se steam na webu, skončil jsem u problému s js pravděpodobnĚ
header('Content-Type: application/json');


$envPath = __DIR__ . '/.env';

if (!file_exists($envPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Soubor .env nenalezen']);
    exit;
}

$env = parse_ini_file($envPath);

if ($env === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Konfigurační soubor .env nebyl nalezen nebo je poškozen']);
    exit;
}
$apiKey = $env['STEAM_API_KEY'] ?? null;
$steamId = $env['STEAM_ID'] ?? null;

if (!$apiKey || !$steamId) {
    http_response_code(500);
    echo json_encode(['error' => 'Chybí konfigurace v .env']);
    exit;
}

// Pomocná funkce pro opakovaná cURL volání
function fetchSteamApi(string $url): ?array {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) return null;
    return json_decode($response, true);
}

// 1. Získání profilu
$profileData = fetchSteamApi("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$apiKey}&steamids={$steamId}");

// 2. Získání banů
/*$bansData = fetchSteamApi("https://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key={$apiKey}&steamids={$steamId}");*/

if (!$profileData /*||!$bansData*/) {
    http_response_code(500);
    echo json_encode(['error' => 'Chyba při komunikaci se Steam API']);
    exit;
}

$player = $profileData['response']['players'][0] ?? null;
/*$banInfo = $bansData['players'][0] ?? null;*/

//pošleme jen to co potřebujem
echo json_encode([
    'profile' => [
        'name' => $player['personaname'] ?? 'Unknown',
        'avatar' => $player['avatarmedium'] ?? '',
        'status' => $player['personastate'] ?? 0,
    ],
    /*'bans' => [
        'has_ban' => (
            ($banInfo['VACBanned'] ?? false) ||
            ($banInfo['CommunityBanned'] ?? false) ||
            ($banInfo['NumberOfGameBans'] ?? 0) > 0
        ),
        'total_bans' => ($banInfo['NumberOfVACBans'] ?? 0) + ($banInfo['NumberOfGameBans'] ?? 0),
        'days_since_last' => $banInfo['DaysSinceLastBan'] ?? 0,
    ]*/
]);