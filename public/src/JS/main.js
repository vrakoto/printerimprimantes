function format(d) {
    return (
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
    );
}



$(function () {
    $(".selectize").selectize({
        sortField: 'text'
    });

    function optionsImprimantes(urlAjax) {
        return {
            dom: '<"top"i>rt<"bottom"flp><"clear">',
            processing: true,
            ajax: urlAjax,
            columns: [
                {
                    class: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                },
                { data: "num_serie", "searchable": true },
                { data: "modele", "searchable": false },
                { data: "bdd", "searchable": false },
                { data: "site_installation", "searchable": false },
                { data: "date_ajout", "searchable": false },
            ],
        }
    }

    const tableImprimante = $('#table_imprimantes').DataTable({
        "order": [[ 2, "desc" ]],
        dom: '<"top"i>rt<"bottom"flp><"clear">'
    });

    // Search Bar
    $('#table_search').keyup(function() {
        tableImprimante.search($(this).val()).draw();
    });

    /* $(document).on('click', '#table_imprimantes tbody tr', function() {
        $("#modaldata tbody tr").html("");
        $("#modaldata tbody tr").html($(this).closest("tr").html());
        $("#exampleModal").modal("show");
    }); */

    // Add event listener for opening and closing details
    $('#table_imprimantes tbody').on('click', 'td.dt-control', function () {
        let tr = $(this).closest('tr');
        let row = tableImprimante.row(tr);
 
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

    const tableCompteurs = $('#table_compteurs').DataTable({
        "order": [[ 8, "desc" ]],
        dom: '<"top"i>rt<"bottom"flp><"clear">'
    });

    // Search Bar
    $('#customSearch').keyup(function() {
        tableCompteurs.search($(this).val()).draw();
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
});


function test (e) {
    // $(e).hide();
    $(e).css('visibility', 'hidden');
    var donnees = [
        '<button type="submit" onclick="addreleve()">Add</button><button onclick="cancelReleve(this)">Cancel</button>',
        '<input type="text" id="num_serie">',
        '<input type="text" id="date_releve">',
        '<input type="text" id="bdd">',
        '<input type="text" id="total_101">',
        '<input type="text" id="total_112">',
        '<input type="text" id="total_113">',
        '<input type="text" id="total_123">',
        '<input type="text" id="maj">',
        '<input type="text" id="modif_par">',
        '<input type="text" id="type_releve">',
    ];
    $('#table_compteurs').DataTable().row.add(donnees).order([1, 'asc']).draw();
}

function cancelReleve(e) {
    // $('#btn_add_releve').show();
    $('#btn_add_releve').css('visibility', 'visible');
    $('#table_compteurs').DataTable().row( $(e).parent().parent('tr') ).remove().draw();
}

function addreleve() {
    const num_serie = $('#num_serie').val();
    const date_releve = $('#date_releve').val();
    const total_112 = $('#total_112').val();
    const total_113 = $('#total_113').val();
    const total_123 = $('#total_123').val();
    const type_releve = $('#type_releve').val();

    $.ajax({
        type: "post",
        url: "/test",
        data: "num_serie=" + num_serie + "&date_releve=" + date_releve + "&total_112=" + total_112 + "&total_113=" + total_113 + "&total_123=" + total_123 + "&type_releve=" + type_releve,
        success: function (e) {
            location.reload();
        },
        error: function (e, r) {
            console.log(e);
        }
    });
}