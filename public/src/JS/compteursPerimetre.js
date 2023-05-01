compteurs();

// Masquer les colonnes par leur classe.
tableCompteurs.columns('.details').visible(false);

// Form ajouter un compteur dynamiquement dans la page
$('#form_add_counter').submit(function (e) {
    e.preventDefault();
    const num_serie = $('#num_serie').val();
    const date_releve = $('#date_releve').val();
    const total_112 = $('#total_112').val();
    const total_122 = $('#total_122').val();
    const total_113 = $('#total_113').val();
    const total_123 = $('#total_123').val();
    const type_releve = $('#type_releve').val();

    $.ajax({
        type: "post",
        url: "/ajouterReleve",
        data: "num_serie=" + num_serie + "&date_releve=" + date_releve + "&total_112=" + total_112 + "&total_122=" + total_122 + "&total_113=" + total_113 + "&total_123=" + total_123 + "&type_releve=" + type_releve,
        success: function (e) {
            $('#message').empty();

            if (e.length <= 0) {
                $('#message').attr("class", "alert alert-success");
                $('#message').append(`Relevé de compteur ajouté pour le copieur ${num_serie}.`)
                tableCompteurs_obj.DataTable().ajax.reload();
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

const selector = '.table_compteurs_perimetre tbody tr'
let selected_num_serie = '';
let selected_date_releve = '';
$.contextMenu({
    selector: selector,
    trigger: 'right',
    items: {
        "view_copieur": {
            name: "Informations de l'imprimante",
            callback: function(key, options) {
                const row = tableCompteurs.row(options.$trigger)
                const {
                    num_serie
                } = row.data()

                window.location.href = `imprimante/${num_serie}`;
            }
        },
        "edit": {
            name: "Modifier ce relevé",
            callback: function(key, options) {
                // Récupération de la ligne de données correspondante à la ligne cliquée
                const row = tableCompteurs.row(options.$trigger);
                const {
                    num_serie,
                    date_releve,
                    total_112,
                    total_113,
                    total_122,
                    total_123,
                    type_releve
                } = row.data();

                selected_num_serie = num_serie;
                selected_date_releve = date_releve;

                $('#exampleModal').modal('show');
                $('#modal-num_serie').text(num_serie);
                $('#modal-date_releve').text(date_releve);
                $('#modal-112_total').val(total_112);
                $('#modal-113_total').val(total_113);
                $('#modal-113_total').val(total_113);
                $('#modal-122_total').val(total_122);
                $('#modal-123_total').val(total_123);
                $('#modal-type_releve').val(type_releve);
            }
        },
        "delete": {
            name: "Supprimer ce relevé",
            callback: function(key, options) {
                const row = tableCompteurs.row(options.$trigger)
                const {
                    num_serie,
                    date_releve
                } = row.data()

                $.ajax({
                    type: "post",
                    url: "supprimerReleve",
                    data: "num_serie=" + num_serie + '&date_releve=' + date_releve,
                    success: function(response) {
                        if (response === '') {
                            tableCompteurs.ajax.reload();
                        }
                    },
                    error: function(response) {
                        alert('erreur interne');
                    }
                });
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

$('#modal-editReleve').submit(function (e) {
    e.preventDefault();

    $.ajax({
        type: "post",
        url: "editReleve",
        data: "num_serie=" + selected_num_serie + '&date_releve=' + selected_date_releve + '&total_112=' + $('#modal-112_total').val()
            + '&total_113=' + $('#modal-113_total').val() + '&total_122=' + $('#modal-122_total').val() +
            '&total_123=' + $('#modal-123_total').val() + '&type_releve=' + $('#modal-type_releve').val(),
        success: function (response) {
            if (response.length === 0) {
                tableCompteurs.ajax.reload();
                $('#exampleModal').modal('hide');
            } else {
                $('#modal-message').removeClass('d-none');
                $('#modal-message').empty();
                $('#message').text(response);
            }
        },
        error: function (res, q) {
            alert('erreur interne');
        }
    });
})

$('#columns_plus').click('click', function () {
    let button_text = $(this).text();
    let new_button_text = button_text === "Afficher + d'infos" ? 'Réduire les informations' : "Afficher + d'infos";
    $(this).text(new_button_text);
    $('#large_table').toggleClass('container');

    tableCompteurs.columns().every(function () {
        let column = this;
        if ($(column.header()).hasClass('details')) {
            if (!column.visible()) {
                column.visible(true);
            } else {
                column.visible(false);
            }
        }
    });
});