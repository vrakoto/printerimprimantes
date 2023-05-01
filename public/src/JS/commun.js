let tableImprimante_obj;
let tableImprimante;
function copieurs() {
    tableImprimante_obj = $('#table_imprimantes');
    tableImprimante = tableImprimante_obj.DataTable({
        dom: '<"top-left"f>i<"top-right"p>rt',
        responsive: true,
        order: [[1, "ASC"]],
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        ajax: {
            'url': tableImprimante_obj.data('table')
        },
        language: {
            info: "Total : _TOTAL_ copieurs",
            infoEmpty: "Aucun copieur",
            infoFiltered: " (Filtré sur un total de  _MAX_ copieurs)",
        },
        columns: [
            { className: 'num_serie ligne', data: "num_serie" },
            { className: 'bdd ligne', data: "bdd" },
            { className: 'modele ligne', data: "modele" },
            { className: 'statut ligne', data: "statut" },
            { className: 'site_installation ligne', data: "site_installation" },
            { className: 'num_ordo ligne', data: "num_ordo" },
            { visible: false, className: 'date_cde_minarm ligne details', data: "date_cde_minarm" },
            { visible: false, className: 'config ligne details', data: "config" },
            { visible: false, className: 'num_oracle ligne details', data: "num_oracle" },
            { visible: false, className: 'num_sfdc ligne details', data: "num_sfdc" },
            { visible: false, className: 'hostname ligne details', data: "hostname" },
            { visible: false, className: 'reseau ligne details', data: "reseau" },
            { visible: false, className: 'adresse_mac ligne details', data: "adresse_mac" },
            { visible: false, className: 'entite_beneficiaire ligne details', data: "entite_beneficiaire" },
            { visible: false, className: 'localisation ligne details', data: "localisation" },
            { visible: false, className: 'cp_insta ligne details', data: "cp_insta" },
            { visible: false, className: 'dep_insta ligne details', data: "dep_insta" },
            { visible: false, className: 'adresse ligne details', data: "adresse" },
            { visible: false, className: 'credo_unite ligne details', data: "credo_unite" },
            { visible: false, className: 'service_uf ligne details', data: "service_uf" },
            { visible: false, className: 'accessoires ligne details', data: "accessoires" }
        ]
    });

    displayRowInPage(tableImprimante);
    searchBar(tableImprimante);
    btns(tableImprimante);

    const $lesChamps = $(':checkbox.leChamp');
    const $selectAll = $(':checkbox#all');
    const $selectAllText = $selectAll.next();

    $lesChamps.on('change', function() {
        const id = this.id;
        const column = tableImprimante.column('#' + id);
        column.visible(!column.visible());
    });

    $selectAll.on('change', function() {
        const isChecked = this.checked;
        $lesChamps.prop('checked', isChecked);
        $selectAllText.text(isChecked ? 'Tout décocher' : 'Tout cocher');
        tableImprimante.columns().visible(isChecked);
    });

    /* $('#table_imprimantes tbody').on('click', 'td.ligne', function () {
        var tr = $(this).closest('tr');
        var row = tableImprimante_obj.DataTable().row(tr);

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
}

let tableCompteurs_obj;
let tableCompteurs;
function compteurs() {
    tableCompteurs_obj = $('#table_compteurs');
    tableCompteurs = tableCompteurs_obj.DataTable({
        dom: '<"top-left"f>i<"top-right"p>rt',
        responsive: true,
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        ajax: {
            'url': tableCompteurs_obj.data('table')
        },
        columns: [
            { className: 'ligne', data: "num_serie" },
            { className: 'details ligne', data: "bdd" },
            {
                className: 'details ligne',
                data: "date_releve",
                render: function (data, type, row) {
                    let date = new Date(data);
                    let options = { day: '2-digit', month: '2-digit', year: 'numeric' };
                    return new Intl.DateTimeFormat('fr-FR', options).format(date);
                }
            },
            { className: 'ligne', data: "total_101" },
            { className: 'ligne', data: "total_112" },
            { className: 'ligne', data: "total_113" },
            { className: 'ligne', data: "total_122" },
            { className: 'ligne', data: "total_123" },
            { className: 'details ligne', data: "modif_par" },
            {
                className: 'ligne',
                data: "date_maj",
                render: function (data, type, row) {
                    let date = new Date(data);
                    let options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
                    return new Intl.DateTimeFormat('fr-FR', options).format(date);
                }
            },
            { className: 'ligne', data: "type_releve" }
        ],
        language: {
            info: "Total : _TOTAL_ résultats",
            infoEmpty: "Aucun relevé",
            infoFiltered: " (Filtré sur un total de  _MAX_ relevés de compteurs)",
        }
    });

    displayRowInPage(tableCompteurs);
    searchBar(tableCompteurs);
    btns(tableCompteurs);
}

let tableResponsables_obj;
let tableResponsables;
function responsables() {
    tableResponsables_obj = $('#table_users_copieurs');
    tableResponsables = tableResponsables_obj.DataTable({
        dom: '<"top-left"f>i<"top-right"p>rt',
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        ajax: {
            'url': tableResponsables_obj.data('table')
        },
        columns: [
            { data: "gpn" },
            { data: "num_serie" }
        ],
        language: {
            info: "Total : _TOTAL_ responsabilités",
            infoEmpty: "Aucun responsable trouvé",
            infoFiltered: " (Filtré sur un total de  _MAX_ responsabilités)",
        }
    });

    displayRowInPage(tableResponsables);
    searchBar(tableResponsables);
    btns(tableResponsables);
}

function displayRowInPage(table) {
    $('#table_select_nb_elements_par_pages').on('change', function () {
        const newPageLength = $(this).val();
        table.page.len(newPageLength).draw();
    });
}

function searchBar(table) {
    $('#form_search').submit(function (e) {
        e.preventDefault();
        table.search($('#table_search').val()).draw();
    });
}

function convertToCSV(objArray) {
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
    var str = '';

    for (var i = 0; i < array.length; i++) {
        var line = '';
        for (var index in array[i]) {
            if (line != '') line += ';'
            line += array[i][index];
        }
        str += line + '\r\n';
    }

    return str;
}

function btns(table) {
    const buttonCsv = new $.fn.dataTable.Buttons(table, {
        buttons: [
            {
                extend: 'csvHtml5',
                text: 'Exporter en CSV (toutes les colonnes)',
                className: 'btn btn-success',
                action: function (e, dt, node, config) {
                    const query = dt.ajax.params();
                    const filename = 'data.csv';
                    // query.draw = 0;
                    // query.length = -1;

                    $.ajax({
                        url: dt.ajax.url(),
                        data: 'csv=yes' + '&search_value=' + query.search.value,
                        type: 'GET',
                        dataType: 'text',
                        headers: {
                            'Content-Type': 'text/csv'
                        },
                        success: function (res) {
                            const csv = convertToCSV(res);
                            let csvData = new Blob([csv], { type: 'text/csv;charset=utf-8;' });

                            // Pour IE11 :
                            if (navigator.msSaveBlob) { // IE 10+
                                navigator.msSaveBlob(csvData, filename);
                            } else {
                                let link = document.createElement("a");
                                link.href = URL.createObjectURL(csvData);
                                link.setAttribute('download', filename);
                                document.body.appendChild(link);    
                                link.click();
                                document.body.removeChild(link);    
                            }
                        }
                    });
                }
            }
        ]
    });

    buttonCsv.container().appendTo('#export-csv');
}


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
}