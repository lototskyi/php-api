<?php

declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Allow: POST");
    exit;
}

$data = (array) json_decode(file_get_contents("php://input"));

if (!array_key_exists("username", $data) ||
    !array_key_exists("password", $data)) {
    http_response_code(400);
    echo json_encode(["message" => "Missing login credentials"]);
    exit;
}

$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);

$userGateway = new UserGateway($database);

$user = $userGateway->getByUsername($data["username"]);

if ($user === false) {
    http_response_code(401);
    echo json_encode(["message" => "invalid authentication"]);
    exit;
}

if (!password_verify($data["password"], $user["password_hash"])) {
    http_response_code(401);
    echo json_encode(["message" => "invalid authentication"]);
    exit;
}

$codec = new JWTCodec($_ENV["SECRET_KEY"]);

require __DIR__ . "/tokens.php";

$refreshTokenGateway = new RefreshTokenGateway($database, $_ENV["SECRET_KEY"]);
$refreshTokenGateway->create($refreshToken, $refreshTokenExpiry);