<?php

declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Allow: POST");
    exit;
}

$data = (array) json_decode(file_get_contents("php://input"));

if (!array_key_exists("token", $data)) {
    http_response_code(400);
    echo json_encode(["message" => "missing token"]);
    exit;
}

$codec = new JWTCodec($_ENV["SECRET_KEY"]);

try {
    $payload = $codec->decode($data["token"]);
} catch(Exception) {
    http_response_code(400);
    echo json_encode(["message" => "invalid token"]);
    exit;
}

$userID = $payload["sub"];

$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);

$refreshTokenGateway = new RefreshTokenGateway($database, $_ENV["SECRET_KEY"]);

$refreshToken = $refreshTokenGateway->getByToken($data["token"]);

if ($refreshToken === false) {
    http_response_code(400);
    echo json_encode(["message" => "invalid token (not on whitelist)"]);
    exit;
}

$userGateway = new UserGateway($database);

$user = $userGateway->getByID($userID);

if ($user === false) {
    http_response_code(401);
    echo json_encode(["message" => "invalid authentication"]);
    exit;
}

require __DIR__ . "/tokens.php";


$refreshTokenGateway->delete($data["token"]);
$refreshTokenGateway->create($refreshToken, $refreshTokenExpiry);