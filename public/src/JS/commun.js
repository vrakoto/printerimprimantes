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

function btns(table) {
    // Créer un bouton pour l'export Excel
    const buttonExcel = new $.fn.dataTable.Buttons(table, {
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Exporter en Excel',
                exportOptions: {
                    // columns: ':visible'
                }
            }
        ]
    });

    // Créer un bouton pour l'export CSV
    const buttonCsv = new $.fn.dataTable.Buttons(table, {
        buttons: [
            {
                extend: 'csvHtml5',
                text: 'Exporter en CSV',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    pages: '1-5',
                    // columns: ':visible'
                }
            }
        ]
    });

    // Créer un bouton pour l'export PDF
    const buttonPdf = new $.fn.dataTable.Buttons(table, {
        buttons: [
            {
                extend: 'pdfHtml5',
                text: 'Visualiser en PDF',
                exportOptions: {
                    // columns: ':visible'
                },
                download: 'open'
            }
        ]
    });

    buttonExcel.container().appendTo('#export-excel');
    buttonCsv.container().appendTo('#export-csv');
    buttonPdf.container().appendTo('#export-pdf');
}