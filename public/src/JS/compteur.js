function compteurs() {
    const tableCompteurs = $('#table_compteurs').DataTable({
        dom: '<"top-left"f>i<"top-right"p>rt',
        responsive: true,
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        ajax: {
            'url': $('#table_compteurs').data('table')
        },
        columns: [
            { className: 'ligne', data: "num_serie" },
            { className: 'ligne', data: "bdd" },
            {
                className: 'ligne',
                data: "date_releve",
                render: function(data, type, row) {
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
            { className: 'ligne', data: "modif_par" },
            {
                data: "date_maj",
                className: 'ligne',
                render: function(data, type, row) {
                    let date = new Date(data);
                    let options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
                    return new Intl.DateTimeFormat('fr-FR', options).format(date);
                    // let date = new Date(data);
                    // let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
                    // return date.toLocaleDateString('fr-FR', options);
                    // if (type === "sort") {
                    //   return new Date(data).getTime();
                    // } else {
                      
                    // }
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

     // Add event listener for opening and closing details
    /* $('#table_compteurs tbody').on('click', 'td.ligne', function () {
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
    }); */

    const selector = '.table_compteurs_perimetre tbody tr'
    $.contextMenu({
        selector: selector,
        trigger: 'right',
        items: {
            "view": {
                name: "Informations de l'imprimante",
                callback: function (key, options) {
                    const row = tableCompteurs.row(options.$trigger)
                    const { num_ordo } = row.data()
                    window.open(`imprimante/${num_ordo}`, '_blank');
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

    $('#columns_plus').on('click', function() {
        var type_releve = tableCompteurs.column(10);
        
        if (type_releve.visible()) {
            type_releve.visible(false);
        } else {
            type_releve.visible(true);
        }
    });

    function format(num_serie) {
        return (
            `<h3>Ses informations</h3>
            <table class="container table table-bordered personalTable" id="${num_serie}">
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
}