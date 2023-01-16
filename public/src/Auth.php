<?php

readonly class Auth
{

    public function __construct(private UserGateway $userGateway)
    {
    }

    public function authenticateAPIKey(): bool
    {
        if (empty($_SERVER['HTTP_X_API_KEY'])) {
            http_response_code(400);
            echo json_encode(["message" => "missing API key"]);
            return false;
        }

        $api_key = $_SERVER['HTTP_X_API_KEY'];

        if ($this->userGateway->getByApiKey($api_key) === false) {
            http_response_code(401);
            echo json_encode(["message" => "invalid API key"]);
            return false;
        }

        return true;
    }
}