<?php

require_once __DIR__ . "/dao/NhomQuyenModel.php";

$controller = new NhomQuyenController();

// Handle HTTP methods
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Get all NhomQuyen without paging
        $response = $controller->getAllNhomQuyen();
        echo $response;
        break;

    // case 'POST':
    //     // Read raw input data
    //     $inputData = file_get_contents('php://input');
    //     $decodedData = json_decode($inputData, true);

    //     if (isset($decodedData['name'])) {
    //         $response = $controller->createNhomQuyen($decodedData['name']);
    //         echo $response;
    //     } else {
    //         http_response_code(400); // Bad Request
    //         echo json_encode([
    //             "status" => 400,
    //             "message" => "Name is required to create NhomQuyen."
    //         ]);
    //     }
    //     break;

    // case 'PATCH':
    //     // Read raw input data
    //     $inputData = file_get_contents("php://input");
    //     $decodedData = json_decode($inputData, true);

    //     if (isset($decodedData['id']) && isset($decodedData['name'])) {
    //         $response = $controller->updateNhomQuyen($decodedData['id'], $decodedData['name']);
    //         echo $response;
    //     } else {
    //         http_response_code(400); // Bad Request
    //         echo json_encode([
    //             "status" => 400,
    //             "message" => "Both ID and name are required for updating NhomQuyen."
    //         ]);
    //     }
    //     break;

    // case 'DELETE':
    //     if (isset($_GET['id'])) {
    //         $response = $controller->deleteNhomQuyen($_GET['id']);
    //         echo $response;
    //     } else {
    //         http_response_code(400); // Bad Request
    //         echo json_encode([
    //             "status" => 400,
    //             "message" => "ID is required for deletion."
    //         ]);
    //     }
    //     break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode([
            "status" => 405,
            "message" => "Method not allowed."
        ]);
        break;
}

class NhomQuyenController
{
    private $NhomQuyenModel;

    public function __construct()
    {
        $this->NhomQuyenModel = new NhomQuyenModel();
    }

    public function getAllNhomQuyen()
    {
        $result = $this->NhomQuyenModel->getAllNhomQuyen();
        return $this->respond($result);
    }

    // public function createNhomQuyen($name)
    // {
    //     // Assuming your model has a method for creating NhomQuyen
    //     $result = $this->NhomQuyenModel->createNhomQuyen($name);
    //     return $this->respond($result);
    // }

    // public function updateNhomQuyen($id, $name)
    // {
    //     // Assuming your model has a method for updating NhomQuyen
    //     $result = $this->NhomQuyenModel->updateNhomQuyen($id, $name);
    //     return $this->respond($result);
    // }

    // public function deleteNhomQuyen($id)
    // {
    //     // Assuming your model has a method for deleting NhomQuyen
    //     $result = $this->NhomQuyenModel->deleteNhomQuyen($id);
    //     return $this->respond($result);
    // }

    private function respond($result)
    {
        http_response_code($result->status);
        $response = [
            "message" => $result->message,
            "data" => $result->data ?? null
        ];

        echo json_encode($response);
    }
}
