<?php

class VillasApiController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->call->database();
        $this->call->model('VillasApiModel', 'villas');
    }

    private function respond($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($data);
    }

    private function get_input()
    {
        $data = $this->request->all();

        if (!is_array($data) || empty($data)) {
            $raw = file_get_contents('php://input');
            $json = json_decode($raw, true);

            if (is_array($json)) {
                $data = $json;
            } else {
                $data = [];
            }
        }

        return $data;
    }

    public function health()
    {
        $this->respond([
            'success' => true,
            'message' => 'LLV Online API is running.'
        ]);
    }

    public function dashboard()
    {
        $this->respond([
            'success' => true,
            'data' => $this->villas->dashboard_summary()
        ]);
    }

    public function index($resource = null)
    {
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 100;
        $limit = max(1, min($limit, 500));

        $records = $this->villas->all_records($resource, $limit);

        if ($records === false) {
            $this->respond([
                'success' => false,
                'message' => 'Invalid resource or unable to retrieve records.'
            ], 400);
            return;
        }

        $this->respond([
            'success' => true,
            'data' => $records
        ]);
    }

    public function show($resource = null, $id = null)
    {
        if (!is_numeric($id) || (int) $id < 1) {
            $this->respond([
                'success' => false,
                'message' => 'A valid record ID is required.'
            ], 400);
            return;
        }

        $record = $this->villas->find_record(
            $resource,
            (int) $id
        );

        if ($record === false) {
            $this->respond([
                'success' => false,
                'message' => 'Invalid resource.'
            ], 400);
            return;
        }

        if (empty($record)) {
            $this->respond([
                'success' => false,
                'message' => 'Record not found.'
            ], 404);
            return;
        }

        $this->respond([
            'success' => true,
            'data' => $record
        ]);
    }

    public function store($resource = null)
    {
        $data = $this->get_input();

        if (empty($data)) {
            $this->respond([
                'success' => false,
                'message' => 'Request data is required.'
            ], 400);
            return;
        }

        $result = $this->villas->create_record($resource, $data);

        if ($result === false) {
            $this->respond([
                'success' => false,
                'message' => 'Unable to create record. Check the resource and submitted fields.'
            ], 400);
            return;
        }

        $this->respond([
            'success' => true,
            'message' => 'Record created successfully.',
            'id' => $result
        ], 201);
    }

    public function update($resource = null, $id = null)
    {
        if (!is_numeric($id) || (int) $id < 1) {
            $this->respond([
                'success' => false,
                'message' => 'A valid record ID is required.'
            ], 400);
            return;
        }

        $data = $this->get_input();

        if (empty($data)) {
            $this->respond([
                'success' => false,
                'message' => 'Request data is required.'
            ], 400);
            return;
        }

        $result = $this->villas->update_record(
            $resource,
            (int) $id,
            $data
        );

        if ($result === false) {
            $this->respond([
                'success' => false,
                'message' => 'Unable to update record. Check the resource and submitted fields.'
            ], 400);
            return;
        }

        $this->respond([
            'success' => true,
            'message' => 'Record updated successfully.'
        ]);
    }

    public function destroy($resource = null, $id = null)
    {
        if (!is_numeric($id) || (int) $id < 1) {
            $this->respond([
                'success' => false,
                'message' => 'A valid record ID is required.'
            ], 400);
            return;
        }

        $result = $this->villas->delete_record(
            $resource,
            (int) $id
        );

        if ($result === false) {
            $this->respond([
                'success' => false,
                'message' => 'Unable to delete record. Check the resource and record ID.'
            ], 400);
            return;
        }

        $this->respond([
            'success' => true,
            'message' => 'Record deleted successfully.'
        ]);
    }
}