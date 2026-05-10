<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AgendaPro – Acesso</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/app.css">
</head>
<body class="auth-body">

<?php
  $error   = $_SESSION['flash_error']   ?? '';
  $success = $_SESSION['flash_success'] ?? '';
  $tab     = $_SESSION['flash_tab']     ?? 'login';
  unset($_SESSION['flash_error'], $_SESSION['flash_success'], $_SESSION['flash_tab']);
?>

<div class="auth-screen d-flex align-items-center justify-content-center min-vh-100">
  <div class="auth-card">

    <div class="brand-logo mb-1">Agenda<span>Pro</span></div>
    <p class="text-muted small mb-4">Sua agenda inteligente</p>

    <?php if ($success): ?>
      <div class="alert alert-success alert-sm py-2 small"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <ul class="nav nav-tabs auth-tabs mb-3" id="authTabs">
      <li class="nav-item flex-fill text-center">
        <button class="nav-link w-100 <?= $tab==='login' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#tabLogin">
          Entrar
        </button>
      </li>
      <li class="nav-item flex-fill text-center">
        <button class="nav-link w-100 <?= $tab==='register' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#tabRegister">
          Cadastrar
        </button>
      </li>
    </ul>

    <div class="tab-content">

      <div class="tab-pane fade <?= $tab==='login' ? 'show active' : '' ?>" id="tabLogin">
        <?php if ($error && $tab==='login'): ?>
          <div class="alert alert-danger alert-sm py-2 small"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= BASE_URL ?>/?c=auth&a=login" novalidate>
          <div class="mb-3">
            <label class="form-label-sm">Login</label>
            <input type="text" name="login" class="form-control" placeholder="Seu login" required autofocus autocomplete="username">
          </div>
          <div class="mb-3">
            <label class="form-label-sm">Senha</label>
            <input type="password" name="senha" class="form-control" placeholder="Sua senha" required autocomplete="current-password">
          </div>
          <button type="submit" class="btn btn-primary-app w-100">
            Entrar <i class="bi bi-arrow-right"></i>
          </button>
        </form>
      </div>

      <div class="tab-pane fade <?= $tab==='register' ? 'show active' : '' ?>" id="tabRegister">
        <?php if ($error && $tab==='register'): ?>
          <div class="alert alert-danger alert-sm py-2 small"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= BASE_URL ?>/?c=auth&a=register" novalidate>
          <div class="mb-3">
            <label class="form-label-sm">Login</label>
            <input type="text" name="login" class="form-control" placeholder="Escolha um login" required minlength="3">
          </div>
          <div class="mb-3">
            <label class="form-label-sm">Senha</label>
            <input type="password" name="senha" class="form-control" placeholder="Mínimo 6 caracteres" required minlength="6">
          </div>
          <div class="mb-3">
            <label class="form-label-sm">Confirmar Senha</label>
            <input type="password" name="senha2" class="form-control" placeholder="Repita a senha" required>
          </div>
          <button type="submit" class="btn btn-primary-app w-100">
            Criar conta <i class="bi bi-person-plus"></i>
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
