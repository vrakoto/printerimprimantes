$(function () {
    $(".selectize").selectize({
        sortField: 'text'
    });

    $("input[required], select[required]").attr("oninvalid", "this.setCustomValidity('Veuillez remplir ce champ.')");
    $("input[required], select[required]").attr("oninput", "setCustomValidity('')");

    if ($('#table_imprimantes').length) {
        imprimante();
    }
    if ($('#table_compteurs').length) {
        compteurs();
    }
    if ($('#table_users_area').length) {
        gestion_utilisateurs();
    }
    if ($('#table_users_copieurs').length) {
        users_copieurs();
    }

    $("[id$='_filter']").remove();
    $("[id$='_length']").remove();

    $('#display_menu_colonnes').text('Affichage des colonnes');

    $('#display_menu_colonnes').click(function (e) { 
        e.preventDefault();
        $('#lesCheckbox').toggleClass('d-none');
    });
});

function toggle_inputs_imprimante_details(e) {
    $(e).css('visibility', 'hidden');
    $('input').removeAttr("disabled");
}


function toggle_inputs_releve(e) {
    $(e).css('display', 'none');
    $('#form_add_counter').attr('class', '');
    $('#num_serie').focus();
    // $('#container').attr('class', '');
}


function cancelReleve(e) {
    $('#btn_add_releve').css('display', 'block');
    $('#form_add_counter').attr('class', 'd-none');
    // $('#container').attr('class', 'container');
}


function toggle_input_create_user(e) {
    $(e).css('display', 'none');
    $('#form_create_user').attr('class', '');
    $('#gpn').focus();
}

function cancelCreateUser(e) {
    $('#btn_toggle_input_create_user').css('display', 'block');
    $('#form_create_user').attr('class', 'd-none');
    // $('#container').attr('class', 'container');
}