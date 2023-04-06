function format(num_ordo) {
    return (
        `<a class="btn btn-primary mx-2" href="imprimante/${num_ordo}">Voir les informations du copieur</a>`
    )
}


function imprimante(url) {
    const tableImprimante = $('#table_imprimantes').DataTable({
        // dom: '<"top"i>rt<"bottom"flp><"clear">',
        dom: '<"top-left"f>i<"top-right"p>rt',
        order: [[2, "DESC"]],
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        ajax: {
            'url': url
        },
        language: {
            info: "Total : _TOTAL_ copieurs",
            infoEmpty: "Aucun copieur",
            infoFiltered: " (Filtré sur un total de  _MAX_ copieurs)",
        },
        columns: [
            {
                data: null,
                defaultContent: '',
                orderable: false,
                className: 'dt-control'
            },
            { data: "num_serie" },
            { data: "bdd" },
            { data: "modele" },
            { data: "statut" },
            { data: "site_installation" },
            { data: "num_ordo" }
        ],
        initComplete: function () {
            var table = $('#table_imprimantes').DataTable();

            $('#table_imprimantes tbody').on('click', 'td.dt-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row's details
                    row.child(format(row.data()['num_ordo'])).show();
                    tr.addClass('shown');
                }
            });
        }
    });

    $('#table_imprimantes_select_nb_elements_par_pages').on('change', function () {
        const newPageLength = $(this).val();
        tableImprimante.page.len(newPageLength).draw();
    });

    // Recherche copieur
    $('#form_search_copieurs').submit(function (e) {
        e.preventDefault();
        tableImprimante.search($('#table_search_copieurs').val()).draw();
    });

    $('#table_imprimantes tbody').on('contextmenu', 'tr', function (e) {
        e.preventDefault();

        
        let tr = $(this).closest('tr');
        let row = $('#table_imprimantes').DataTable().row(tr);
        var {num_ordo, num_serie} = row.data();
        
        // Crée un menu contextuel avec des options personnalisées
        const selector = 'tbody tr'
        $.contextMenu('update', {
            selector: selector,
            items: {
                "view": {
                    name: "Informations de l'imprimante",
                    callback: function () {
                        window.location.href = `imprimante/${num_ordo}`;
                    }
                },
                "view_counters": {
                    name: "Consulter ses relevés",
                    callback: function () {

                    }
                },
                "remove_machine_area": {
                    name: "Retirer ce copieur de mon périmètre",
                    callback: function () {
                        $.ajax({
                            type: "post",
                            url: "/retirerCopieurPerimetre",
                            data: "num_serie=" + num_serie,
                            success: function (e) {
                                $('#message').empty();
                                tableImprimante.ajax.reload();
                                if (e.length <= 0) {
                                    $(selector).trigger('contextmenu:hide')
                                    $('#message').attr("class", "alert alert-success");
                                    $('#message').append(`Le copieur ${num_serie} a bien été retiré de votre périmètre.`)
                                } else {
                                    $('#message').attr("class", "alert alert-danger");
                                    $('#message').append(e)
                                }
                            },
                            error: function() {
                                $('#message').attr("class", "alert alert-danger");
                                $('#message').append("Impossible de trouver la requete");
                            }
                        });
                    }
                }
            }
        });
    });
}

function compteurs(url) {
    const tableCompteurs = $('#table_compteurs').DataTable({
        dom: '<"top-left"f>i<"top-right"p>rt',
        order: [[9, "desc"]],
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        columnDefs: [{ "targets": 9, "type": "date-eu" }],
        ajax: {
            'url': url
        },
        columns: [
            { data: "num_serie" },
            { data: "bdd" },
            { data: "date_releve" },
            { data: "total_101" },
            { data: "total_112" },
            { data: "total_113" },
            { data: "total_122" },
            { data: "total_123" },
            { data: "modif_par" },
            { data: "date_maj" },
            { data: "type_releve" }
        ],
        language: {
            info: "Total : _TOTAL_ résultats",
            infoEmpty: "Aucun relevé",
            infoFiltered: " (Filtré sur un total de  _MAX_ relevés de compteurs)",
        },
    });

    $('#table_compteurs_select_nb_elements_par_pages').on('change', function () {
        const newPageLength = $(this).val();
        tableCompteurs.page.len(newPageLength).draw();
    });

    // Search Bar pour les compteurs
    $('#form_search_compteurs').submit(function (e) {
        e.preventDefault();
        tableCompteurs.search($('#table_search_compteurs').val()).draw();
    });

    // Add event listener for opening and closing details
    $('#table_compteurs tbody').on('click', 'td.dt-control', function () {
        let tr = $(this).closest('tr');
        let row = tableCompteurs.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
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
}


function users_copieurs(url) {
    const tableUsersCopieurs = $('#table_users_copieurs').DataTable({
        dom: '<"top-left"f>i<"top-right"p>rt',
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        ajax: {
            'url': url
        },
        columns: [
            { data: "gpn" },
            { data: "num_serie" }
        ]
    });

    $('#form_search_users_copieurs').submit(function (e) {
        e.preventDefault();
        tableUsersCopieurs.search($('#input_search_users_copieurs').val()).draw();
    });
}

function gestion_utilisateurs() {
    const tableProfil = $('#table_users_area').DataTable({
        dom: '<"top-left"f>i<"top-right"p>rt',
        order: [[1, "DESC"]],
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        ajax: {
            'url': '/getGestionUtilisateurs'
        },
        columns: [
            { data: "gpn" },
            { data: "bdd" },
            { data: "courriel" },
            { data: "role" },
            { data: "unite" },
        ]
    });

    $('#form_search_users_area').submit(function (e) {
        e.preventDefault();
        tableProfil.search($('#input_search_users_area').val()).draw();
    });
}


$(function () {
    $(".selectize").selectize({
        sortField: 'text'
    });

    $("[id$='_filter']").remove();
    $("[id$='_length']").remove();
});

function toggle_inputs_imprimante_details(e) {
    $(e).css('visibility', 'hidden');
    $('input').removeAttr("disabled");
}


function toggle_inputs_releve(e) {
    $(e).css('display', 'none');
    $('#form_add_counter').attr('class', '');
    $('#num_serie').focus();
    // $('#container').attr('class', '');
}


function cancelReleve(e) {
    $('#btn_add_releve').css('display', 'block');
    $('#form_add_counter').attr('class', 'd-none');
    // $('#container').attr('class', 'container');
}