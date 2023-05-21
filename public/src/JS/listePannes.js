var table = $('table');
var ths = table.find('thead th');
var trs = table.find('tbody tr');

/* function sortTable(column, direction) {
    var tbody = table.find('tbody');
    var rows = tbody.find('tr').get();

    rows.sort(function (a, b) {
        var aValue = $(a).find('td:eq(' + column + ')').text();
        var bValue = $(b).find('td:eq(' + column + ')').text();
        if (aValue.match(/\d{2}\/\d{2}\/\d{4}/) && bValue.match(/\d{2}\/\d{2}\/\d{4}/)) {
            // convert dates to Date objects for comparison
            var aDate = new Date(aValue.split('/').reverse().join('-'));
            var bDate = new Date(bValue.split('/').reverse().join('-'));
            if (direction == 'asc') {
                return aDate - bDate;
            } else {
                return bDate - aDate;
            }
        } else {
            if (direction == 'asc') {
                return aValue.localeCompare(bValue, undefined, { numeric: true });
            } else {
                return bValue.localeCompare(aValue, undefined, { numeric: true });
            }
        }
    });

    $.each(rows, function (index, row) {
        tbody.append(row);
    });
}

ths.click(function () {
    var direction = $(this).data('direction');
    var column = ths.index(this);
    if (direction == 'desc') {
        sortTable(column, 'asc');
        $(this).data('direction', 'asc');
    } else {
        sortTable(column, 'desc');
        $(this).data('direction', 'desc');
    }
}); */

$('table').DataTable({
    dom: 't',
    responsive: true,
});