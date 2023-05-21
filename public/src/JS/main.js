$(function () {
    $(".selectize").selectize({
        sortField: 'text'
    });

    $("input[required], select[required]").attr("oninvalid", "this.setCustomValidity('Veuillez remplir ce champ.')");
    $("input[required], select[required]").attr("oninput", "setCustomValidity('')");

    $("[id$='_filter']").remove();
    $("[id$='_length']").remove();

    var csvAdded = false;
    $('#downloadCSV').click(function (event) {
        event.preventDefault();
        if (!csvAdded) {
            var url = window.location.href;
            if (url.indexOf('?') > -1) {
                if (url.indexOf('csv=') === -1) {
                    url += '&csv=yes';
                }
            } else {
                url += '?csv=yes';
            }
            window.location.href = url;
            csvAdded = true;
        }
    });
});

function toggle_inputs_imprimante_details(e) {
    $(e).css('visibility', 'hidden');
    $('input').removeAttr("disabled");
}


function toggle_input_create_user(e) {
    $(e).css('display', 'none');
    $('#form_create_user').attr('class', '');
    $('#gpn').focus();
}

function cancelCreateUser(e) {
    $('#btn_toggle_input_create_user').css('display', 'block');
    $('#form_create_user').attr('class', 'd-none');
}