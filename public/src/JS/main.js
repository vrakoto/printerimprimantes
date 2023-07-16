$(function () {
    const navbar = document.querySelector('nav');
    const navbarOffsetTop = navbar.offsetTop; // Obtenir la position de la navbar

    window.onscroll = function () {
        // Vérifiez si l'utilisateur a scrollé en bas de la navbar
        if (window.pageYOffset >= navbarOffsetTop) {
            navbar.classList.add('fixed');
        }

        // Vérifiez si l'utilisateur est revenu tout en haut de la page
        if (window.pageYOffset <= navbarOffsetTop) {
            navbar.classList.remove('fixed');
        }
    };

    $('.select').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('.modal'),
        placeholder: 'Sélectionnez une option'
    });

    $("input[required], select[required]").attr("oninvalid", "this.setCustomValidity('Veuillez remplir ce champ.')");
    $("input[required], select[required]").attr("oninput", "setCustomValidity('')");

    let dateInputs = document.querySelectorAll("input[type='date']");
    dateInputs.forEach(function (input) {
        let currentDate = new Date().toISOString().slice(0, 10);
        input.value = currentDate;
    });
});