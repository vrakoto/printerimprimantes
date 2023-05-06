<style>
    li {
        margin: 10px 0;
    }
</style>

<div class="container accordion mt-5" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                Comment ajouter un relevé de compteur ?
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <ul style="list-style: decimal;">
                    <li>
                        Cliquez sur l'onglet "De mon périmètre" situé dans la barre de navigation verte à gauche
                    </li>
                    <li>
                        Cliquez ensuite sur le bouton bleu intitulé "Ajouter un relevé"
                    </li>
                    <li>
                        Un formulaire devrait apparaitre, sélectionnez le numéro de série de la machine puis renseignez les autres champs
                    </li>
                    <li>
                        Une fois renseigné, cliquez sur le bouton bleu de validation.
                        <br>
                        L'ajout est immédiat, vous pouvez constater les nouveaux changements dans votre tableau situé juste en dessous du formulaire.
                    </li>
                </ul>

                <div class="alert alert-warning">
                    <ul>
                        <li>La date de relevé ne doit pas être postérieur à la date actuelle.</li>
                        <li>Si le numéro de série que vous recherchez n'apparaît pas dans la liste déroulante, cela signifie que la machine ne fait pas partie de votre périmètre ou n'a pas encore été renseignée sur Sapollon.</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Comment ajouter une machine dans mon périmètre ?
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <h3>Ce guide ne concerne pas les COORDSIC.</h3>
                <br>
                <ul style="list-style: decimal;">
                    <li>
                        Cliquez sur l'onglet "Suivi des Copieurs" situé dans la barre de navigation verte à gauche
                    </li>
                    <li>
                        Cliquez sur "Copieurs de mon périmètre"
                    </li>
                    <li>
                        Enfin, cliquez sur le bouton bleu intitulé "Ajouter un copieur dans mon périmètre"
                    </li>
                    <li>
                        Sélectionnez le numéro de série de la machine et cliquez sur le bouton bleu de validation.
                    </li>
                </ul>
                <div class="alert alert-warning">
                    <ul>
                        <li>Si le numéro de série que vous recherchez n'apparaît pas dans la liste déroulante, cela signifie que la machine n'a pas encore été enregistrée dans Sapollon ou bien qu'elle a été affectée à une autre Base de Défense.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                En savoir plus concernant la page "Sans relevé depuis 3 Mois"
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Cette page affiche les machines qui n'ont pas eu de relevé enregistrée dans notre système de suivi pendant ce trimestre.
                Cela peut également indiquer qu'il y a eu un problème technique ou administratif qui a empêché la collecte des données de relevé pour ces machines.
            </div>
        </div>
    </div>
</div>