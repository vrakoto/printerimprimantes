function compteurs() {
    const tableCompteurs = $('#table_compteurs').DataTable({
        dom: '<"top-left"f>i<"top-right"p>rt',
        order: [[9, 'desc']],
        responsive: true,
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        ajax: {
            'url': $('#table_compteurs').data('table')
        },
        columns: [
            { className: 'num_serie ligne', data: "num_serie" },
            { className: 'bdd ligne', data: "bdd", visible: false },
            {
                className: 'date_releve ligne details',
                data: "date_releve",
                render: function (data, type, row) {
                    let date = new Date(data);
                    let options = { day: '2-digit', month: '2-digit', year: 'numeric' };
                    return new Intl.DateTimeFormat('fr-FR', options).format(date);
                },
                visible: false
            },
            { className: 'total_101 ligne', data: "total_101" },
            { className: 'total_112 ligne', data: "total_112" },
            { className: 'total_113 ligne', data: "total_113" },
            { className: 'total_122 ligne', data: "total_122" },
            { className: 'total_123 ligne', data: "total_123" },
            { className: 'modif_par ligne details', data: "modif_par", visible: false },
            {
                data: "date_maj",
                className: 'date_maj ligne',
                render: function (data, type, row) {
                    let date = new Date(data);
                    let options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: 'numeric', minute: 'numeric' };
                    return new Intl.DateTimeFormat('fr-FR', options).format(date);
                }
            },
            { className: 'type_releve ligne', data: "type_releve" },

        ],
        initComplete: function(r, s) {
            const data = tableCompteurs.rows().data().toArray();
            console.log(data);
        },
        language: {
            info: "Total : _TOTAL_ résultats",
            infoEmpty: "Aucun relevé",
            infoFiltered: " (Filtré sur un total de  _MAX_ relevés de compteurs)",
        }
    });

    displayRowInPage(tableCompteurs);
    searchBar(tableCompteurs);
    btns(tableCompteurs);


    $('#test').click(function () {
        // tableCompteurs.page(5).draw(false);
        // const data = tableCompteurs.rows().data().toArray();
        // console.log(data);
    })

    tableCompteurs.on('draw.dt', function () {
        // const data = tableCompteurs.rows().data().toArray();
        // console.log(data);
    });

    // Form ajouter un compteur
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
                    tableCompteurs.ajax.reload();
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
                callback: function (key, options) {
                    const row = tableCompteurs.row(options.$trigger)
                    const { num_ordo } = row.data()
                    window.open(`imprimante/${num_ordo}`, '_blank');
                }
            },
            "edit": {
                name: "Modifier ce relevé",
                callback: function (key, options) {
                    // Récupération de la ligne de données correspondante à la ligne cliquée
                    const row = tableCompteurs.row(options.$trigger);

                    const { num_serie, date_releve, total_112, total_113, total_122, total_123, type_releve } = row.data();
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
                callback: function (key, options) {
                    const row = tableCompteurs.row(options.$trigger)
                    const { num_serie, date_releve } = row.data()

                    $.ajax({
                        type: "post",
                        url: "supprimerReleve",
                        data: "num_serie=" + num_serie + '&date_releve=' + date_releve,
                        success: function (response) {
                            if (response === '') {
                                tableCompteurs.ajax.reload();
                            }
                        },
                        error: function (response) {
                            alert('erreur interne');
                        }
                    });
                }
            },
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
            error: function(res, q) {
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
}