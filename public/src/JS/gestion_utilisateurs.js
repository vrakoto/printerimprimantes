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
        ],
        language: {
            info: "Total : _TOTAL_ utilisateurs",
            infoEmpty: "Aucun utilisateur trouvé",
            infoFiltered: " (Filtré sur un total de  _MAX_ utilisateurs)",
        }
    });

    $('#table_select_nb_elements_par_pages').on('change', function () {
        const newPageLength = $(this).val();
        tableProfil.page.len(newPageLength).draw();
    });

    $('#form_search_users_area').submit(function (e) {
        e.preventDefault();
        tableProfil.search($('#input_search_users_area').val()).draw();
    });

    $('#form_create_user').submit(function (e) {
        e.preventDefault();

        const gpn = $('#gpn').val();
        const courriel = $('#courriel').val();
        const role = $('#role').val();
        const mdp = $('#mdp').val();
        const unite = $('#unite').val();

        $.ajax({
            type: "post",
            url: "/creerUtilisateur",
            data: "gpn=" + gpn + "&courriel=" + courriel + "&role=" + role + "&mdp=" + mdp + "&unite=" + unite,
            success: function (e) {
                $('#message').empty();

                if (e.length <= 0) {
                    $('#message').attr("class", "alert alert-success");
                    $('#message').append(`Utilisateur créé.<br>GPN : ${gpn}, courriel : ${courriel}`);
                    $('input').val('');
                    tableProfil.ajax.reload();
                } else {
                    $('#message').attr("class", "alert alert-danger");
                    $('#message').append(e)
                }
            },
            error: function (e, r) {
                $('#message').attr("class", "alert alert-danger");
                $('#message').append("Impossible d'envoyer la requete pour la création d'un utilisateur.");
            }
        });
    });

    // Créer un bouton pour l'export Excel
    const buttonExcel = new $.fn.dataTable.Buttons(tableProfil, {
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Exporter en Excel',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });

    // Créer un bouton pour l'export CSV
    const buttonCsv = new $.fn.dataTable.Buttons(tableProfil, {
        buttons: [
            {
                extend: 'csvHtml5',
                text: 'Exporter en CSV',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    pages: '1-5',
                    columns: ':visible'
                }
            }
        ]
    });

    // Créer un bouton pour l'export PDF
    const buttonPdf = new $.fn.dataTable.Buttons(tableProfil, {
        buttons: [
            {
                extend: 'pdfHtml5',
                text: 'Visualiser en PDF',
                exportOptions: {
                    columns: ':visible'
                },
                download: 'open'
            }
        ]
    });

    buttonExcel.container().appendTo('#export-excel');
    buttonCsv.container().appendTo('#export-csv');
    buttonPdf.container().appendTo('#export-pdf');
}