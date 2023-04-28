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