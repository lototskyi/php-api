<?php

readonly class TaskController
{
    public function __construct(private TaskGateway $taskGateway)
    {
    }

    public function processRequest(string $method, ?string $id): void
    {
        if ($id === null) {
            if ($method === 'GET') {
                echo json_encode($this->taskGateway->getAll());
            } elseif ($method === 'POST') {
                $data = (array) json_decode(file_get_contents("php://input"));

                $errors = $this->getValidationErrors($data);

                if (!empty($errors)) {
                    $this->respondUnprocessableEntity($errors);
                    return;
                }

                $id = $this->taskGateway->create($data);
                $this->respondCreated($id);
            } else {
                $this->respondMethodNotAllowed("GET, POST");
            }
        } else {
            $task = $this->taskGateway->get($id);

            if ($task === false) {
                $this->respondNotFound($id);
                return;
            }

            switch ($method) {
                case 'GET':
                    echo json_encode($task);
                    break;
                case 'PATCH':

                    $data = (array) json_decode(file_get_contents("php://input"));

                    $errors = $this->getValidationErrors($data, false);

                    if (!empty($errors)) {
                        $this->respondUnprocessableEntity($errors);
                        return;
                    }

                    $rows = $this->taskGateway->update($id, $data);
                    echo json_encode(["message" => "Task updated", "rows" => $rows]);
                    break;
                case 'DELETE':
                    echo "delete $id";
                    break;
                default:
                    $this->respondMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }

    private function respondUnprocessableEntity(array $errors): void
    {
        header("{$_SERVER['SERVER_PROTOCOL']} 422 Unprocessable Entity");
        echo json_encode(["errors" => $errors]);
    }

    private function respondMethodNotAllowed(string $allowed_methods): void
    {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }

    private function respondNotFound(string $id): void
    {
        http_response_code(404);
        echo json_encode(["message" => "Task with ID $id not found"]);
    }

    private function respondCreated(string $id): void
    {
        http_response_code(201);
        echo json_encode(["message" => "Task created", "id" => $id]);
    }

    private function getValidationErrors(array $data, bool $isNew = true): array
    {
        $errors = [];

        if ($isNew && empty($data["name"])) {
            $errors[] = "name is required";
        }

        if (!empty($data["priority"])) {
            if (filter_var($data["priority"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "priority must be an integer";
            }
        }

        return $errors;
    }
}