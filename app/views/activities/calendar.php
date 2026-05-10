<?php
$pageTitle  = 'Calendário – AgendaPro';
$activePage = 'calendar';
require BASE_PATH . '/app/views/layouts/header.php';
?>

<div class="container-fluid py-4">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <h1 class="page-title mb-0">Calendário</h1>
    <div class="d-flex gap-2 align-items-center flex-wrap">
      <span class="badge bg-warning-subtle text-warning-emphasis">● Pendente</span>
      <span class="badge bg-success-subtle text-success-emphasis">● Concluída</span>
      <span class="badge bg-danger-subtle  text-danger-emphasis" >● Cancelada</span>
      <a href="<?= BASE_URL ?>/?c=activity&a=create" class="btn btn-add ms-2">
        <i class="bi bi-plus-lg"></i> Nova
      </a>
    </div>
  </div>

  <div class="card shadow-sm border-0 rounded-3 p-3">
    <div id="cal"></div>
  </div>

</div>

<div class="modal fade" id="eventModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="evtTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="evtBody"></div>
      <div class="modal-footer border-0 pt-0">
        <a id="evtEditLink" href="#" class="btn btn-sm btn-primary-app">
          <i class="bi bi-pencil"></i> Editar
        </a>
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<?php
$eventsUrl = BASE_URL . '/?c=calendar&a=events';
$editBase  = BASE_URL . '/?c=activity&a=edit&id=';
$extraJs   = "
const EVENTS_URL = '{$eventsUrl}';
const EDIT_BASE  = '{$editBase}';
AgendaPro.initCalendar(EVENTS_URL, EDIT_BASE);
";
require BASE_PATH . '/app/views/layouts/footer.php';
?>
