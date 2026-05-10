<?php
$editing    = !empty($activity);
$pageTitle  = ($editing ? 'Editar' : 'Nova') . ' Atividade – AgendaPro';
$activePage = 'activities';

require BASE_PATH . '/app/views/layouts/header.php';

$flash_e = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);

$v = fn(string $k) => htmlspecialchars($activity[$k] ?? '');
$fmtDatetimeLocal = fn(string $dt): string => $dt ? date('Y-m-d\TH:i', strtotime($dt)) : '';

$postUrl = $editing
  ? BASE_URL . '/?c=activity&a=update&id=' . $activity['id']
  : BASE_URL . '/?c=activity&a=store';
?>

<div class="container py-4" style="max-width:620px;">

  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= BASE_URL ?>/?c=activity&a=index" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0"><?= $editing ? 'Editar Atividade' : 'Nova Atividade' ?></h1>
  </div>

  <?php if ($flash_e): ?>
    <div class="alert alert-danger small py-2"><?= htmlspecialchars($flash_e) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
      <form method="POST" action="<?= $postUrl ?>" novalidate>

        <div class="mb-3">
          <label class="form-label-sm">Nome *</label>
          <input type="text" name="nome" class="form-control"
                 value="<?= $v('nome') ?>" placeholder="Nome da atividade" required>
        </div>

        <div class="mb-3">
          <label class="form-label-sm">Descrição</label>
          <textarea name="descricao" class="form-control" rows="3"
                    placeholder="Descreva a atividade..."><?= $v('descricao') ?></textarea>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label-sm">Data/Hora Início *</label>
            <input type="datetime-local" name="inicio" class="form-control"
                   value="<?= $fmtDatetimeLocal($activity['inicio'] ?? '') ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label-sm">Data/Hora Término *</label>
            <input type="datetime-local" name="termino" class="form-control"
                   value="<?= $fmtDatetimeLocal($activity['termino'] ?? '') ?>" required>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label-sm">Status</label>
          <select name="status" class="form-select">
            <?php foreach (['pendente'=>'Pendente','concluida'=>'Concluída','cancelada'=>'Cancelada'] as $val=>$lbl): ?>
              <option value="<?= $val ?>" <?= ($activity['status'] ?? 'pendente')===$val ? 'selected':'' ?>>
                <?= $lbl ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="d-flex gap-2 justify-content-end">
          <a href="<?= BASE_URL ?>/?c=activity&a=index" class="btn btn-outline-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary-app">
            <i class="bi bi-check-lg"></i> <?= $editing ? 'Salvar Alterações' : 'Criar Atividade' ?>
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
