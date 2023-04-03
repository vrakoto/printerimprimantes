function format(d) {
    console.log(d);

    return (
        `<a class="btn btn-info" href="imprimante/">Voir les informations du copieur</a>`
    )
    /* return (
        '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
        '<tr>' +
        '<td>Full name:</td>' +
        '<td>' +
        d.num_serie +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td>Extension number:</td>' +
        '<td>' +
        d.extn +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td>Extra info:</td>' +
        '<td>And any further details here (images etc)...</td>' +
        '</tr>' +
        '</table>'
    ); */
}


function imprimante(url) {
    const tableImprimante = $('#table_imprimantes').DataTable({
        // dom: '<"top"i>rt<"bottom"flp><"clear">',
        dom: '<"top-left"f>i<"top-right"p>rt',
        order: [[0, "DESC"]],
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
            {data: "num_serie"},
            {data: "modele"},
            {data: "statut"},
            {data: "bdd"},
            {data: "site_installation"}
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
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });
        }
    });

    $('#table_imprimantes_select_nb_elements_par_pages').on('change', function () {
        const newPageLength = $(this).val();
        tableImprimante.page.len(newPageLength).draw();
    });

    // Search Bar pour les imprimantes
    $('form').submit(function (e) { 
        e.preventDefault();
        tableImprimante.search($('#table_search_copieurs').val()).draw();
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
            { data: "101" },
            { data: "112" },
            { data: "113" },
            { data: "122" },
            { data: "123" },
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

    // Search Bar for Users_Copieurs
    $('#table_search_users_copieurs').keydown(function () {
        tableUsersCopieurs.search($(this).val()).draw();
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