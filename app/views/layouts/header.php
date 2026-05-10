<?php
$userLogin = htmlspecialchars($_SESSION['user_login'] ?? '');
$userInitial = strtoupper(substr($userLogin, 0, 1));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $pageTitle ?? 'AgendaPro' ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.10/index.global.min.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/app.css">
</head>
<body>

<nav class="navbar navbar-expand-md app-navbar px-3">
  <a class="navbar-brand brand-logo" href="<?= BASE_URL ?>/?c=activity&a=index">
    Agenda<span>Pro</span>
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="mainNav">
    <ul class="navbar-nav me-auto">
      <li class="nav-item">
        <a class="nav-link <?= (isset($activePage) && $activePage==='activities') ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/?c=activity&a=index">
          <i class="bi bi-list-task"></i> Atividades
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= (isset($activePage) && $activePage==='calendar') ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/?c=calendar&a=index">
          <i class="bi bi-calendar3"></i> Calendário
        </a>
      </li>
    </ul>
    <div class="d-flex align-items-center gap-2">
      <div class="user-avatar"><?= $userInitial ?></div>
      <span class="text-white fw-500 small"><?= $userLogin ?></span>
      <a href="<?= BASE_URL ?>/?c=auth&a=logout" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right"></i> Sair
      </a>
    </div>
  </div>
</nav>

<div class="app-wrapper">
