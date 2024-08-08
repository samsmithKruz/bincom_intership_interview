<?php
class Api extends Controller
{
    private $data;
    public function __construct()
    {
        // Check if it is a cross-origin preflight request
        header('Access-Control-Allow-Origin: ' . DOMAIN);
        header('Access-Control-Allow-Methods: GET, POST');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        // header('Content-Type: application/json; charset=utf-8');
        if (isset($_SERVER['HTTP_ORIGIN'])) {

            $origin = $_SERVER['HTTP_ORIGIN'];
            $allowedOrigins = array(DOMAIN); // Add more allowed origins if needed

            if (in_array($origin, $allowedOrigins)) {
                // Allow the request from the specified origin

            } else {
                // Deny the request from other origins
                http_response_code(403); // Forbidden
                $response = [
                    'code' => 403,
                    'status' => false,
                    'error' => 'Forbidden',
                    'message' => 'Forbidden',
                    'origin' => 'constructor',
                    'from' => $origin
                ];
                echo json_encode($response);
                exit;
            }
        }

        $this->data = json_decode(file_get_contents("php://input"), true) ?? [];
        $this->model("api_model");
    }

    public function getLgas()
    {
        $lgas = $this->model->fetchLgas();
        $this->sendResponse($lgas);
    }
    public function getWards()
    {
        if (!isset($_GET['lga_id'])) {
            $this->sendError(400, 'Bad Request', 'LGA ID is required.');
            return;
        }

        $lgaId = intval($_GET['lga_id']);
        $wards = $this->model->fetchWards($lgaId);
        $this->sendResponse($wards);
    }
    public function getPollingUnits()
    {
        if (!isset($_GET['ward_id'])) {
            $this->sendError(400, 'Bad Request', 'Ward ID is required.');
            return;
        }

        $wardId = intval($_GET['ward_id']);
        $pollingUnits = $this->model->fetchPollingUnits($wardId);
        $this->sendResponse($pollingUnits);
    }
    public function getParty()
    {
        $this->sendResponse($this->model->getParty());
    }
    public function getVote()
    {
        if (!isset($_GET['lga']) || !isset($_GET['ward']) || !isset($_GET['polling_unit'])) {
            $this->sendError(400, 'Bad Request', 'Incomplete fields sent.');
            return;
        }
        $this->sendResponse($this->model->getVote());
    }
    public function collate()
    {
        if (!isset($_GET['lga']) || !isset($_GET['ward']) || !isset($_GET['poll']) || !isset($_GET['party']) || !isset($_GET['vote'])) {
            $this->sendError(400, 'Bad Request', 'Incomplete fields sent.');
            return;
        }
        $this->sendResponse($this->model->collate());
    }
    public function getResults()
    {
        if (!isset($_GET['polling_unit_id'])) {
            $this->sendError(400, 'Bad Request', 'Polling Unit ID is required.');
            return;
        }

        $pollingUnitId = intval($_GET['polling_unit_id']);
        $results = $this->model->fetchResults($pollingUnitId);
        $this->sendResponse($results);
    }
    public function getTotal(){
        if (!isset($_GET['lga_id'])) {
            $this->sendError(400, 'Bad Request', 'Polling Unit ID is required.');
            return;
        }

        $lga_id = intval($_GET['lga_id']);
        $results = $this->model->fetchTotal($lga_id);
        $this->sendResponse($results);
    }
    private function sendResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    private function sendError($statusCode, $statusText, $message)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'code' => $statusCode,
            'status' => false,
            'error' => $statusText,
            'message' => $message
        ]);
    }
}
