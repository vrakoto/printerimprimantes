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

    const tousLesImprimantes = $('#table_content_imprimantes').DataTable({
        "order": [[ 2, "desc" ]],
        dom: '<"top"i>rt<"bottom"flp><"clear">'
    });

    // Search Bar
    $('#table_search_imprimantes').keyup(function() {
        tousLesImprimantes.search($(this).val()).draw();
    });

    /* $(document).on('click', '#table_content_imprimantes tbody tr', function() {
        $("#modaldata tbody tr").html("");
        $("#modaldata tbody tr").html($(this).closest("tr").html());
        $("#exampleModal").modal("show");
    }); */

    // Add event listener for opening and closing details
    $('#table_content_imprimantes tbody').on('click', 'td.dt-control', function () {
        let tr = $(this).closest('tr');
        let row = tousLesImprimantes.row(tr);
 
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

    const newImprimantes = $('#copieurs_new_added').DataTable({
        "order": [[ 4, "desc" ]],
        dom: '<"top"i>rt<"bottom"flp><"clear">'
    });

    // Search Bar
    $('#customSearch').keyup(function() {
        newImprimantes.search($(this).val()).draw();
    });

    // Add event listener for opening and closing details
    $('#copieurs_new_added tbody').on('click', 'td.dt-control', function () {
        let tr = $(this).closest('tr');
        let row = newImprimantes.row(tr);
 
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


/* $('#add_releve').submit(function (e) {
    e.preventDefault()
    const num_serie = $('#num_serie').val();
    const date_releve = $('#date_releve').val();
    const total_112 = $('#112_total').val();
    const total_113 = $('#113_total').val();
    const total_123 = $('#123_total').val();
    const type_releve = $('#type_releve').val();

    $.ajax({
        type: "post",
        url: "/ajouterReleve",
        data: "num_serie=" + num_serie + "&date_releve=" + date_releve + "&total_112=" + total_112 + "&total_113=" + total_113 + "&total_123=" + total_123 + "&type_releve=" + type_releve,
        success: function (e) {
            const message = JSON.parse(e)
            $('#message').empty()
            if (message.error) {
                $('#message').append(message.error)
            } else {
                $('#message').append(message.success)
            }
        },
        error: function (e, r) {
            console.log(e);
        }
    });
}) */

 //Exemple de données de relevés d'imprimante
/* var relevesImprimantes = [12, 18, 15, 10];

//Créer un graphique en barres
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Imprimante 1', 'Imprimante 2', 'Imprimante 3', 'Imprimante 4'],
        datasets: [{
            label: 'Relevés de ce mois',
            data: relevesImprimantes,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
}); */