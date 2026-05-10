<?php

class AuthController {

    private UserModel $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function index(): void {
        if ($this->isLoggedIn()) {
            $this->redirect('?c=activity&a=index');
        }
        require BASE_PATH . '/app/views/auth/login.php';
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=auth&a=index');
        }

        $login = trim($_POST['login'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $user = $this->model->findByLogin($login);

        if (!$user || !$this->model->verifyPassword($senha, $user['senha'])) {
            $this->redirectWithError('?c=auth&a=index', 'Login ou senha incorretos.');
            return;
        }

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_login'] = $user['login'];
        $this->redirect('?c=activity&a=index');
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=auth&a=index');
        }

        $login  = trim($_POST['login'] ?? '');
        $senha  = $_POST['senha'] ?? '';
        $senha2 = $_POST['senha2'] ?? '';

        if (strlen($login) < 3) {
            $this->redirectWithError('?c=auth&a=index', 'Login deve ter pelo menos 3 caracteres.', 'register');
            return;
        }
        if (strlen($senha) < 6) {
            $this->redirectWithError('?c=auth&a=index', 'Senha deve ter pelo menos 6 caracteres.', 'register');
            return;
        }
        if ($senha !== $senha2) {
            $this->redirectWithError('?c=auth&a=index', 'As senhas não conferem.', 'register');
            return;
        }
        if ($this->model->loginExists($login)) {
            $this->redirectWithError('?c=auth&a=index', 'Este login já está em uso.', 'register');
            return;
        }

        $this->model->create($login, $senha);
        $_SESSION['flash_success'] = 'Conta criada com sucesso! Faça o login.';
        $this->redirect('?c=auth&a=index');
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('?c=auth&a=index');
    }

    private function isLoggedIn(): bool {
        return !empty($_SESSION['user_id']);
    }

    private function redirect(string $url): void {
        header('Location: ' . BASE_URL . '/' . $url);
        exit;
    }

    private function redirectWithError(string $url, string $msg, string $tab = 'login'): void {
        $_SESSION['flash_error'] = $msg;
        $_SESSION['flash_tab']   = $tab;
        $this->redirect($url);
    }
}
