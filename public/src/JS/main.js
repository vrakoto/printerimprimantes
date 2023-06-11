$(function () {
    const navbar = document.querySelector('nav');

    const navbarOffsetTop = navbar.offsetTop; // Obtenir la position de la navbar

    window.onscroll = function() {
        // Vérifiez si l'utilisateur a scrollé en bas de la navbar
        if (window.pageYOffset >= navbarOffsetTop) {
            navbar.classList.add('fixed');
        }
        
        // Vérifiez si l'utilisateur est revenu tout en haut de la page
        if (window.pageYOffset <= navbarOffsetTop) {
            navbar.classList.remove('fixed');
        }
    };

    $(".selectize").selectize({
        sortField: 'text'
    });

    $("input[required], select[required]").attr("oninvalid", "this.setCustomValidity('Veuillez remplir ce champ.')");
    $("input[required], select[required]").attr("oninput", "setCustomValidity('')");

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

    let dateInputs = document.querySelectorAll("input[type='date']");
    dateInputs.forEach(function(input) {
        let currentDate = new Date().toISOString().slice(0, 10);
        input.value = currentDate;
    });
});