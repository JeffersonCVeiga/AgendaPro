<?php

class CalendarController {

    private ActivityModel $model;
    private int           $userId;

    public function __construct() {
        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            exit(json_encode(['error' => 'Não autenticado']));
        }
        $this->model  = new ActivityModel();
        $this->userId = (int) $_SESSION['user_id'];
    }

    public function index(): void {
        require BASE_PATH . '/app/views/activities/calendar.php';
    }

    public function events(): void {
        header('Content-Type: application/json');
        echo json_encode($this->model->forCalendar($this->userId));
        exit;
    }
}
