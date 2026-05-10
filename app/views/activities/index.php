<?php
$pageTitle  = 'Atividades – AgendaPro';
$activePage = 'activities';
require BASE_PATH . '/app/views/layouts/header.php';

$flash_s = $_SESSION['flash_success'] ?? '';
$flash_e = $_SESSION['flash_error']   ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$currentStatus = $_GET['status'] ?? '';
$currentSearch = $_GET['q']      ?? '';

function statusBadge(string $s): string {
    $map = [
        'pendente'  => 'warning',
        'concluida' => 'success',
        'cancelada' => 'danger',
    ];
    $label = ucfirst($s);
    $cls   = $map[$s] ?? 'secondary';
    return "<span class=\"badge bg-{$cls}-subtle text-{$cls}-emphasis\">{$label}</span>";
}
function fmtDt(string $dt): string {
    return date('d/m/Y H:i', strtotime($dt));
}
?>

<div class="container-fluid py-4">

  <?php if ($flash_s): ?>
    <div class="alert alert-success alert-dismissible fade show small py-2" role="alert">
      <?= htmlspecialchars($flash_s) ?><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if ($flash_e): ?>
    <div class="alert alert-danger alert-dismissible fade show small py-2" role="alert">
      <?= htmlspecialchars($flash_e) ?><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="row g-3 mb-4">
    <?php
      $statItems = [
        ['label'=>'Total',      'value'=>$counts['total'],    'icon'=>'bi-journal-text',  'color'=>'primary'],
        ['label'=>'Pendentes',  'value'=>$counts['pendente'], 'icon'=>'bi-hourglass-split','color'=>'warning'],
        ['label'=>'Concluídas', 'value'=>$counts['concluida'],'icon'=>'bi-check-circle',  'color'=>'success'],
        ['label'=>'Canceladas', 'value'=>$counts['cancelada'],'icon'=>'bi-x-circle',      'color'=>'danger'],
      ];
      foreach ($statItems as $s):
    ?>
    <div class="col-6 col-md-3">
      <div class="stat-card">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon bg-<?= $s['color'] ?>-subtle text-<?= $s['color'] ?>-emphasis">
            <i class="bi <?= $s['icon'] ?>"></i>
          </div>
          <div>
            <div class="stat-value"><?= $s['value'] ?></div>
            <div class="stat-label"><?= $s['label'] ?></div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <h1 class="page-title mb-0">Minhas Atividades</h1>
    <a href="<?= BASE_URL ?>/?c=activity&a=create" class="btn btn-add">
      <i class="bi bi-plus-lg"></i> Nova Atividade
    </a>
  </div>

  <form method="GET" action="<?= BASE_URL ?>/" class="filter-bar mb-3">
    <input type="hidden" name="c" value="activity">
    <input type="hidden" name="a" value="index">

    <?php foreach ([''=>'Todas','pendente'=>'Pendentes','concluida'=>'Concluídas','cancelada'=>'Canceladas'] as $val=>$lbl): ?>
      <button type="submit" name="status" value="<?= $val ?>"
              class="filter-btn <?= $currentStatus===$val ? 'active' : '' ?>">
        <?= $lbl ?>
      </button>
    <?php endforeach; ?>

    <div class="ms-auto d-flex gap-2">
      <input type="text" name="q" class="form-control form-control-sm search-input"
             placeholder="🔍 Buscar..." value="<?= htmlspecialchars($currentSearch) ?>">
      <button type="submit" class="btn btn-sm btn-outline-secondary">Filtrar</button>
      <?php if ($currentSearch || $currentStatus): ?>
        <a href="<?= BASE_URL ?>/?c=activity&a=index" class="btn btn-sm btn-outline-danger">
          <i class="bi bi-x"></i>
        </a>
      <?php endif; ?>
    </div>
  </form>

  <?php if (empty($activities)): ?>
    <div class="empty-state">
      <i class="bi bi-journal-x"></i>
      <p>Nenhuma atividade encontrada.</p>
      <a href="<?= BASE_URL ?>/?c=activity&a=create" class="btn btn-add mt-2">
        <i class="bi bi-plus-lg"></i> Criar primeira atividade
      </a>
    </div>
  <?php else: ?>
    <div class="activity-grid">
      <?php foreach ($activities as $a): ?>
        <div class="activity-card status-<?= $a['status'] ?>">
          <div class="card-status-bar"></div>

          <div class="d-flex align-items-start justify-content-between mb-2">
            <div class="card-name"><?= htmlspecialchars($a['nome']) ?></div>
            <div class="d-flex gap-1">
              <a href="<?= BASE_URL ?>/?c=activity&a=edit&id=<?= $a['id'] ?>"
                 class="icon-btn" title="Editar"><i class="bi bi-pencil"></i></a>
              <button class="icon-btn danger" title="Excluir"
                      data-bs-toggle="modal" data-bs-target="#deleteModal"
                      data-id="<?= $a['id'] ?>" data-nome="<?= htmlspecialchars($a['nome']) ?>">
                <i class="bi bi-trash3"></i>
              </button>
            </div>
          </div>

          <div class="card-desc">
            <?= $a['descricao'] ? htmlspecialchars($a['descricao']) : '<em class="text-muted">Sem descrição</em>' ?>
          </div>

          <div class="card-meta">
            <div class="card-date"><i class="bi bi-play-circle"></i> <?= fmtDt($a['inicio']) ?></div>
            <div class="card-date"><i class="bi bi-stop-circle"></i> <?= fmtDt($a['termino']) ?></div>
            <div class="d-flex align-items-center gap-2 mt-2">
              <?= statusBadge($a['status']) ?>
              <select class="form-select form-select-sm status-quick-select"
                      data-id="<?= $a['id'] ?>" style="width:auto;font-size:.75rem;">
                <option value="pendente"  <?= $a['status']==='pendente'  ? 'selected':'' ?>>Pendente</option>
                <option value="concluida" <?= $a['status']==='concluida' ? 'selected':'' ?>>Concluída</option>
                <option value="cancelada" <?= $a['status']==='cancelada' ? 'selected':'' ?>>Cancelada</option>
              </select>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle"></i> Confirmar exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body small">
        Deseja excluir a atividade <strong id="deleteNome"></strong>?
      </div>
      <div class="modal-footer border-0 pt-0">
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <form method="POST" id="deleteForm" action="">
          <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
$extraJs = "
const BASE_URL = '" . BASE_URL . "';
document.getElementById('deleteModal').addEventListener('show.bs.modal', function(e) {
  const btn = e.relatedTarget;
  document.getElementById('deleteNome').textContent = btn.dataset.nome;
  document.getElementById('deleteForm').action = BASE_URL + '/?c=activity&a=delete&id=' + btn.dataset.id;
});
";
require BASE_PATH . '/app/views/layouts/footer.php';
?>
