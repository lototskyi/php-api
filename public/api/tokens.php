<?php

$payload = [
    "sub" => $user["id"],
    "name" => $user["name"],
    "exp" => time() + 20
];

$accessToken = $codec->encode($payload);

$refreshTokenExpiry = time() + 432000;

$refreshToken = $codec->encode([
    "sub" => $user["id"],
    "exp" => $refreshTokenExpiry
]);

echo json_encode([
    "access_token" => $accessToken,
    "refresh_token" => $refreshToken
]);