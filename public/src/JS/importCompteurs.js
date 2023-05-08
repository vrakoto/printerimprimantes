// Détail fichier CSV Upload
$('#csv_file').change(function() {
    var fileName = $(this).val().split('\\').pop();
    $('#file_name').text(fileName);
});


$('#importCompteurs').submit(function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
        type: "post",
        url: "importCompteurs",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
        },
        error: function() {
            alert('erreur interne');
        }
    });
});