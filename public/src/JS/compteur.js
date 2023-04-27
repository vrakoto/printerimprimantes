function compteurs() {
    var allData = []; // Initialiser un tableau pour stocker toutes les données
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
            { className: 'ligne', data: "modif_par" },
            {
                data: "date_maj",
                className: 'ligne',
                render: function (data, type, row) {
                    let date = new Date(data);
                    let options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
                    return new Intl.DateTimeFormat('fr-FR', options).format(date);
                }
            },
            { className: 'ligne', data: "type_releve" }
        ],
        initComplete: function (settings, json) {
            // Définir la page à afficher
            // nb pages : console.log($('#table_compteurs').DataTable().page.info());
            // current data page : var data = $('#table_compteurs').DataTable().rows({ page: 5 }).data();
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

    // écouter l'événement "draw.dt"
    // tableCompteurs.on('draw.dt', function() {
    /* tableCompteurs.on('page.dt', function () {
        const data = tableCompteurs.rows().data().toArray();
        if (data.length === 0) {
            console.log("Les données n'ont pas encore été chargées.");
        } else {
            console.log("Les données ont été chargées avec succès :", data);
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

}