const tableProfil = $('#table_users_area').DataTable({
    dom: '<"top-left"f>i<"top-right"p>rt',
    order: [[1, "DESC"]],
    processing: true,
    serverSide: true,
    serverMethod: 'get',
    ajax: {
        'url': '/getGestionUtilisateurs'
    },
    columns: [
        { data: "gpn" },
        { data: "bdd" },
        { data: "courriel" },
        { data: "role" },
        { data: "unite" },
    ],
    language: {
        info: "Total : _TOTAL_ utilisateurs",
        infoEmpty: "Aucun utilisateur trouvé",
        infoFiltered: " (Filtré sur un total de  _MAX_ utilisateurs)",
    }
});

displayRowInPage(tableProfil);
searchBar(tableProfil);
btns(tableProfil);

// Form pour créer un utilisateur dynamiquement dans la page
$('#form_create_user').submit(function (e) {
    e.preventDefault();

    const gpn = $('#gpn').val();
    const courriel = $('#courriel').val();
    const role = $('#role').val();
    const mdp = $('#mdp').val();
    const unite = $('#unite').val();

    $.ajax({
        type: "post",
        url: "/creerUtilisateur",
        data: "gpn=" + gpn + "&courriel=" + courriel + "&role=" + role + "&mdp=" + mdp + "&unite=" + unite,
        success: function (e) {
            $('#message').empty();

            if (e.length <= 0) {
                $('#message').attr("class", "alert alert-success");
                $('#message').append(`Utilisateur créé.<br>GPN : ${gpn}, courriel : ${courriel}`);
                $('input').val('');
                tableProfil.ajax.reload();
            } else {
                $('#message').attr("class", "alert alert-danger");
                $('#message').append(e)
            }
        },
        error: function (e, r) {
            $('#message').attr("class", "alert alert-danger");
            $('#message').append("Impossible d'envoyer la requete pour la création d'un utilisateur.");
        }
    });
});