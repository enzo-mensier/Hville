<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Hville</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <!-- En-tête de l'application -->
    <h1>Hville</h1>
    
    <!-- Navigation principale -->
    <div id="primary-btn">
        <button class="primary-btn-salle" onclick="charger('ihm_service')">Service</button>
        <button class="primary-btn-salle" onclick="charger('ihm_employe')">Employé</button>
        <button class="primary-btn-salle" onclick="charger('ihm_salle')">Salle</button>
    </div>

    <!-- Zone de contenu dynamique -->
    <div id="contenu" class="carte-default">
        <p class="message-default">Veuillez choisir une interface</p>
    </div>

    <script>
    /**
     * Charge dynamiquement le contenu d'une interface
     * @param {string} page - Nom de l'interface à charger (ihm_service, ihm_employe, ihm_salle)
     */
    function charger(page) {
        fetch(page + '.php')
            .then(response => response.text())
            .then(data => {
                const contenu = document.getElementById('contenu');
                // Mise à jour du contenu et des classes
                contenu.innerHTML = data;
                contenu.className = 'carte-' + page;
                // Ajout du bouton d'insertion si nécessaire
                afficherAjouter(page);
            });
    }

    /**
     * Ajoute un bouton d'insertion pour les interfaces principales
     * @param {string} page - Nom de l'interface courante
     */
    function afficherAjouter(page) {
        if (page === 'ihm_employe' || page === 'ihm_service' || page === 'ihm_salle') {
            const contenu = document.getElementById('contenu');
            const type = page.split('_')[1];
            
            const boutonAjouter = document.createElement('button');
            boutonAjouter.classList.add('secondary-btn-' + type);
            boutonAjouter.textContent = 'Insérer ' + type.charAt(0).toUpperCase() + type.slice(1);
            boutonAjouter.onclick = () => charger('inserer_' + type);
            
            contenu.appendChild(boutonAjouter);
        }
    }
    </script>
</body>
</html>
