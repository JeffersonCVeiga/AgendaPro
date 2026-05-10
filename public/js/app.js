const AgendaPro = (() => {

  function initStatusSelects() {
    $(document).on('change', '.status-quick-select', function () {
      const $sel   = $(this);
      const id     = $sel.data('id');
      const status = $sel.val();

      $.post(BASE_URL + '/?c=activity&a=updateStatus', { id, status })
        .done(res => {
          if (res.success) {
            showToast('Status atualizado!', 'success');
            const $card = $sel.closest('.activity-card');
            $card.removeClass('status-pendente status-concluida status-cancelada')
                 .addClass('status-' + status);

            const badgeMap = {
              pendente:  ['bg-warning-subtle', 'text-warning-emphasis', 'Pendente'],
              concluida: ['bg-success-subtle', 'text-success-emphasis', 'Concluída'],
              cancelada: ['bg-danger-subtle',  'text-danger-emphasis',  'Cancelada'],
            };
            const [bg, txt, label] = badgeMap[status];
            $card.find('.badge')
                 .removeClass('bg-warning-subtle text-warning-emphasis bg-success-subtle text-success-emphasis bg-danger-subtle text-danger-emphasis')
                 .addClass(bg + ' ' + txt)
                 .text(label);
          } else {
            showToast('Erro ao atualizar status.', 'error');
          }
        })
        .fail(() => showToast('Erro de comunicação.', 'error'));
    });
  }

  function showToast(msg, type = '') {
    const colMap  = { success: '#27ae60', error: '#c0392b', '': '#1a3a4a' };
    const $toast  = $('<div>')
      .css({
        position:     'fixed',
        bottom:       '1.5rem',
        right:        '1.5rem',
        zIndex:       9999,
        background:   colMap[type] || colMap[''],
        color:        '#fff',
        padding:      '.65rem 1.1rem',
        borderRadius: '8px',
        fontSize:     '.85rem',
        boxShadow:    '0 4px 14px rgba(0,0,0,.2)',
        animation:    'none',
        opacity:      0,
      })
      .text(msg)
      .appendTo('body');

    $toast.animate({ opacity: 1, bottom: '2rem' }, 250);
    setTimeout(() => $toast.animate({ opacity: 0 }, 300, () => $toast.remove()), 2800);
  }

  function initCalendar(eventsUrl, editBase) {
    const calEl = document.getElementById('cal');
    if (!calEl) return;

    const calendar = new FullCalendar.Calendar(calEl, {
      initialView:  'dayGridMonth',
      locale:       'pt-br',
      headerToolbar: {
        left:   'prev,next today',
        center: 'title',
        right:  'dayGridMonth,timeGridWeek,listWeek',
      },
      height:  'auto',
      events:  eventsUrl,

      eventClick(info) {
        const ev   = info.event;
        const props = ev.extendedProps;
        const statusLabel = { pendente: 'Pendente', concluida: 'Concluída', cancelada: 'Cancelada' };
        const statusBadgeClass = {
          pendente:  'bg-warning-subtle text-warning-emphasis',
          concluida: 'bg-success-subtle text-success-emphasis',
          cancelada: 'bg-danger-subtle text-danger-emphasis',
        };
        const cls = statusBadgeClass[props.status] || '';

        const fmt = dt => dt
          ? new Date(dt).toLocaleString('pt-BR', {
              day: '2-digit', month: '2-digit', year: 'numeric',
              hour: '2-digit', minute: '2-digit',
            })
          : '—';

        $('#evtTitle').text(ev.title);
        $('#evtBody').html(`
          <table class="table table-sm table-borderless mb-0">
            <tr><td class="text-muted" style="width:40%">Status</td>
                <td><span class="badge ${cls}">${statusLabel[props.status] || props.status}</span></td></tr>
            <tr><td class="text-muted">Início</td>
                <td><strong>${fmt(ev.start)}</strong></td></tr>
            <tr><td class="text-muted">Término</td>
                <td><strong>${fmt(ev.end)}</strong></td></tr>
          </table>
        `);
        $('#evtEditLink').attr('href', editBase + ev.id);

        new bootstrap.Modal(document.getElementById('eventModal')).show();
      },
    });

    calendar.render();
  }

  $(function () {
    initStatusSelects();
  });

  return { initCalendar, showToast };

})();
