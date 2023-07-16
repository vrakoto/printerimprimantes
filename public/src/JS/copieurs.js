function toggle_compteurs_from_copieurs_page(e, perimetre) {
    const num_serie = $(e).closest('tbody tr').data('num_serie');
    $('#modal_compteurs_title').text('Liste des compteurs pour ' + num_serie);
    
    perimetre = (perimetre === 1) ? "true" : "false";
    
    $.ajax({
        type: "get",
        url: "/ajax/getImprimanteCompteurs",
        data: "num_serie=" + num_serie + '&perimetre=' + perimetre,
        success: function (response) {
            if (response) {
                $('#modal_compteurs').find('.modal-body').empty();
                $('#modal_compteurs').find('.modal-body').append(response);
            }
        },
        error: function (response) {
            alert('erreur interne');
        }
    });
}

function toggle_form_add_counter_from_copieurs_page(e) {
    $(e).css({display: "none"});
    $('.hiddenForm').removeClass("d-none");
}

function add_counter_from_copieurs_page(e) {
    const num_serie = $(e).closest('table').data('num_serie');

    let row = $(e).closest('tr');
    let total_112 = row.find('.total_112').val();
    let total_113 = row.find('.total_113').val();
    let total_122 = row.find('.total_122').val();
    let total_123 = row.find('.total_123').val();

    $.ajax({
        type: "post",
        url: "/ajax/ajouterCompteur",
        data: "num_serie=" + num_serie + '&total_112=' + total_112 + '&total_113=' + total_113 + '&total_122=' + total_122 + '&total_123=' + total_123,
        success: function (response) {
            $('.msg').empty();
            if (!response) {
                $('.msg').append(`<div class='alert alert-success'>Le compteur a bien été ajouté / incrémenté.</div>`);
            } else {
                $('.msg').append(`<div class='alert alert-danger'>${response}.</div>`);
            }
        },
        error: function (response) {
            alert('erreur interne');
        }
    });
}