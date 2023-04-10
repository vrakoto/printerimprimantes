function imprimante() {
    const tableImprimante = $('#table_imprimantes').DataTable({
        dom: '<"top-left"f>i<"top-right"p>rt',
        /* deferRender:    true,
        scrollY:        200,
        scrollCollapse: true,
        scroller:       true, */
        order: [[1, "ASC"]],
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        ajax: {
            'url': $('#table_imprimantes').data('table')
        },
        language: {
            info: "Total : _TOTAL_ copieurs",
            infoEmpty: "Aucun copieur",
            infoFiltered: " (Filtré sur un total de  _MAX_ copieurs)",
        },
        columns: [
            { className: 'ligne', data: "num_serie" },
            { className: 'ligne', data: "bdd" },
            { className: 'ligne', data: "modele" },
            { className: 'ligne', data: "statut" },
            { className: 'ligne', data: "site_installation" },
            { className: 'ligne', data: "num_ordo" },
            { visible: false, className: 'ligne details', data: "date_cde_minarm" },
            { visible: false, className: 'ligne details', data: "config" },
            { visible: false, className: 'ligne details', data: "num_oracle" },
            { visible: false, className: 'ligne details', data: "num_sfdc" },
            { visible: false, className: 'ligne details', data: "hostname" },
            { visible: false, className: 'ligne details', data: "reseau" },
            { visible: false, className: 'ligne details', data: "adresse_mac" },
            { visible: false, className: 'ligne details', data: "entite_beneficiaire" },
            { visible: false, className: 'ligne details', data: "localisation" },
            { visible: false, className: 'ligne details', data: "cp_insta" },
            { visible: false, className: 'ligne details', data: "dep_insta" },
            { visible: false, className: 'ligne details', data: "adresse" },
            { visible: false, className: 'ligne details', data: "credo_unite" },
            { visible: false, className: 'ligne details', data: "service_uf" },
            { visible: false, className: 'ligne details', data: "accessoires" }
        ]
    });

    /* $('#table_imprimantes tbody').on('click', 'td.ligne', function () {
        var tr = $(this).closest('tr');
        var row = $('#table_imprimantes').DataTable().row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(format(row.data()['num_ordo'])).show();
            tr.addClass('shown');

            $(`#${row.data()['num_ordo']}`).DataTable({
                dom: 't',
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
    }); */

    displayRowInPage(tableImprimante);
    searchBar(tableImprimante);
    btns(tableImprimante);

    $('#columns_plus').click('click', function () {
        let button_text = $(this).text();
        let new_button_text = button_text === "Afficher + d'infos sur les copieurs" ? 'Réduire les informations' : "Afficher + d'infos sur les copieurs";
        $(this).text(new_button_text);
        $('#large_table').toggleClass('container');

        // Désactive la visualisation au format PDF car l'affichage est pas correct
        if ($('#export-pdf').is(':hidden')) {
            $('#export-pdf').show();
        } else {
            $('#export-pdf').hide();
        }

        tableImprimante.columns().every(function() {
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

    /* const selector = '.table_imprimante_perimetre tr'
    console.log($(selector));
    $.contextMenu({
        selector: selector,
        trigger: 'right',
        items: {
            "view": {
                name: "Informations de l'imprimante",
                callback: function (key, options) {
                    const row = tableImprimante.row(options.$trigger)
                    const { num_ordo } = row.data()
                    window.open(`imprimante/${num_ordo}`, '_blank');
                }
            },
            "view_counters": {
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
            },
            "remove_machine_area": {
                name: "Retirer ce copieur de mon périmètre",
                callback: function (key, options) {
                    const row = tableImprimante.row(options.$trigger)
                    const { num_serie } = row.data()
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
                        error: function () {
                            $('#message').attr("class", "alert alert-danger");
                            $('#message').append("Impossible de trouver la requete");
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

    function format(num_ordo) {
        return (
            `<h3>Ses relevés de compteurs</h3>
            <table class="container table table-bordered personalTable" id="${num_ordo}">
            <thead>
                <tr>
                    <th>Numéro Série</th>
                    <th>BDD</th>
                    <th>Date de relevé</th>
                    <th>101 Total</th>
                    <th>112 Total</th>
                    <th>113 Total</th>
                    <th>122 Total</th>
                    <th>123 Total</th>
                    <th>Ajouté par</th>
                    <th>Mise à jour le</th>
                    <th>Type de relevé</th>
                </tr>
            </thead>
            </table>`
        )
    } */
}