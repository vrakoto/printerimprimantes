// copieurs();
$('.triggerDT').DataTable({
    dom: '<t>',
    responsive: true,
    order: []
});

const selector = '#table_imprimantes tbody tr';
$.contextMenu({
    selector: selector,
    trigger: 'right',
    items: {
        "view": {
            name: "Informations de l'imprimante",
            callback: function (key, options) {
                // const row = tableImprimante.row(options.$trigger)
                // const { num_serie } = row.data()

                // window.location.href = `imprimante/${num_serie}`;

                const $trigger = options.$trigger;
                const num_serie = $trigger.find('.num_serie').text();
                window.location.href = `imprimante/${num_serie}`;
            }
        }
    },
    events: {
        show: function (options) {
            // Appliquer un fond de couleur jaune au tr lors du clic droit
            $(this).css('background-color', 'yellow');
        },
        hide: function (options) {
            // Supprimer le fond de couleur jaune lorsque le menu contextuel est fermé
            $(this).css('background-color', '');
        }
    }
});