copieurs();

const toggleBtn = $('#btn_add_machines_area');
const container = $('#form_add_machine_area');

toggleBtn.click(function () {
    toggleBtn.hide();
    container.toggleClass('d-none');
});

$('#cancel_input').click(function () {
    toggleBtn.show();
    container.toggleClass('d-none');
});

// Form ajouter un copieur dans le périmètre dynamiquement dans la page
$('#form_add_machine_area').submit(function (e) {
    e.preventDefault();
    const num_serie = $('#num_serie').val();

    $.ajax({
        type: "post",
        url: "ajouterCopieurPerimetre",
        data: "num_serie=" + num_serie,
        success: function (e) {
            $('#message').empty();

            if (e.length <= 0) {
                $('#message').attr("class", "alert alert-success");
                $('#message').append(`Le copieur ${num_serie} a bien été ajouté dans votre périmètre.`)
                tableImprimante.ajax.reload();
            } else {
                $('#message').attr("class", "alert alert-danger");
                $('#message').append(e)
            }
        },
        error: function (e, r) {
            $('#message').attr("class", "alert alert-danger");
            $('#message').append("Erreur interne")
        }
    });
});

const selector = '#table_imprimantes tbody tr';
$.contextMenu({
    selector: selector,
    trigger: 'left',
    items: {
        "view": {
            name: "Informations de l'imprimante",
            callback: function (key, options) {
                const row = tableImprimante.row(options.$trigger)
                const { num_serie } = row.data()

                window.location.href = `imprimante/${num_serie}`;
            }
        },
        /* "view_counters": {
            name: "Consulter ses relevés",
            callback: function () {
                var table = $('#table_imprimantes').DataTable();
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(format(row.data()['num_ordo'])).show();
                    tr.addClass('shown');

                    $(`#${row.data()['num_ordo']}`).DataTable({
                        dom: 't',
                        order: [[9, "desc"]],
                        processing: true,
                        serverSide: true,
                        serverMethod: 'get',
                        ajax: {
                            'url': `/getLesCompteursImprimante/${row.data()['num_serie']}`
                        },
                        columns: [
                            { data: "Numéro_série" },
                            { data: "BDD" },
                            { data: "Date" },
                            { data: "101_Total_1" },
                            { data: "112_Total" },
                            { data: "113_Total" },
                            { data: "122_Total" },
                            { data: "123_Total" },
                            { data: "modif_par" },
                            { data: "date_maj" },
                            { data: "type_relevé" }
                        ],
                        language: {
                            info: "Total : _TOTAL_ résultats",
                            infoEmpty: "Aucun relevé",
                            infoFiltered: " (Filtré sur un total de  _MAX_ relevés de compteurs)",
                        },
                    });
                }
            }
        }, */
        "remove_machine_area": allow_delete_machine_area
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