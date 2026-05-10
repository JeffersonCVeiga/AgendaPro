<?php

class ActivityController {

    private ActivityModel $model;
    private int           $userId;

    public function __construct() {
        $this->requireLogin();
        $this->model  = new ActivityModel();
        $this->userId = (int) $_SESSION['user_id'];
    }

    public function index(): void {
        $status     = $_GET['status'] ?? '';
        $search     = trim($_GET['q'] ?? '');
        $activities = $this->model->allByUser($this->userId, $status);

        if ($search !== '') {
            $activities = array_filter($activities, fn($a) =>
                stripos($a['nome'], $search)      !== false ||
                stripos($a['descricao'], $search) !== false
            );
        }

        $counts   = $this->model->countsByUser($this->userId);
        $upcoming = array_filter(
            $this->model->allByUser($this->userId, 'pendente'),
            fn($a) => strtotime($a['inicio']) >= time()
        );
        usort($upcoming, fn($a, $b) => strtotime($a['inicio']) <=> strtotime($b['inicio']));
        $upcoming = array_slice($upcoming, 0, 5);

        require BASE_PATH . '/app/views/activities/index.php';
    }

    public function create(): void {
        require BASE_PATH . '/app/views/activities/form.php';
    }

    public function store(): void {
        $this->requirePost();
        $data = $this->extractFormData();
        $err  = $this->validate($data);

        if ($err) {
            $_SESSION['flash_error'] = $err;
            $this->redirect('?c=activity&a=create');
            return;
        }

        $this->model->create($this->userId, $data);
        $_SESSION['flash_success'] = 'Atividade criada com sucesso!';
        $this->redirect('?c=activity&a=index');
    }

    public function edit(): void {
        $activity = $this->getOwnedActivity();
        require BASE_PATH . '/app/views/activities/form.php';
    }

    public function update(): void {
        $this->requirePost();
        $activity = $this->getOwnedActivity();
        $data     = $this->extractFormData();
        $err      = $this->validate($data);

        if ($err) {
            $_SESSION['flash_error'] = $err;
            $this->redirect('?c=activity&a=edit&id=' . $activity['id']);
            return;
        }

        $this->model->update($activity['id'], $this->userId, $data);
        $_SESSION['flash_success'] = 'Atividade atualizada!';
        $this->redirect('?c=activity&a=index');
    }

    public function delete(): void {
        $this->requirePost();
        $activity = $this->getOwnedActivity();
        $this->model->delete($activity['id'], $this->userId);
        $_SESSION['flash_success'] = 'Atividade excluída.';
        $this->redirect('?c=activity&a=index');
    }

    public function updateStatus(): void {
        header('Content-Type: application/json');
        $id     = (int) ($_POST['id']     ?? 0);
        $status = trim($_POST['status']   ?? '');

        $ok = $this->model->updateStatus($id, $this->userId, $status);
        echo json_encode(['success' => $ok]);
        exit;
    }

    private function extractFormData(): array {
        return [
            'nome'      => trim($_POST['nome']      ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'inicio'    => trim($_POST['inicio']    ?? ''),
            'termino'   => trim($_POST['termino']   ?? ''),
            'status'    => trim($_POST['status']    ?? 'pendente'),
        ];
    }

    private function validate(array $d): string {
        if (empty($d['nome']))    return 'O nome da atividade é obrigatório.';
        if (empty($d['inicio']))  return 'A data/hora de início é obrigatória.';
        if (empty($d['termino'])) return 'A data/hora de término é obrigatória.';
        if ($d['termino'] < $d['inicio']) return 'O término deve ser após o início.';
        $allowed = ['pendente', 'concluida', 'cancelada'];
        if (!in_array($d['status'], $allowed, true)) return 'Status inválido.';
        return '';
    }

    private function getOwnedActivity(): array {
        $id       = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        $activity = $this->model->findById($id, $this->userId);
        if (!$activity) {
            http_response_code(403);
            exit('Atividade não encontrada ou acesso negado.');
        }
        return $activity;
    }

    private function requireLogin(): void {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/?c=auth&a=index');
            exit;
        }
    }

    private function requirePost(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=activity&a=index');
        }
    }

    private function redirect(string $url): void {
        header('Location: ' . BASE_URL . '/' . $url);
        exit;
    }
}
