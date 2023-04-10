function users_copieurs() {
    const tableUsersCopieurs = $('#table_users_copieurs').DataTable({
        dom: '<"top-left"f>i<"top-right"p>rt',
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        ajax: {
            'url': $('#table_users_copieurs').data('table')
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

    $('#table_select_nb_elements_par_pages').on('change', function () {
        const newPageLength = $(this).val();
        tableUsersCopieurs.page.len(newPageLength).draw();
    });

    $('#form_search_users_copieurs').submit(function (e) {
        e.preventDefault();
        tableUsersCopieurs.search($('#input_search_users_copieurs').val()).draw();
    });
}