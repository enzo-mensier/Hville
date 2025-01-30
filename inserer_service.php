<?php
// Connexion à la base de données avec encodage UTF-8
$conn = new mysqli("localhost", "AdminHville", "P@ssword", "hville");
// Vérifie si la connexion à la base de données a échoué
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error); // Arrête le script si la connexion échoue
}
// Définit l'encodage des données échangées avec la base de données à UTF-8
$conn->set_charset("utf8");

// Ajout d'un nouveau service si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    // Récupère et nettoie les données du formulaire
    $type_service = strtoupper(trim($_POST["type_service"])); // Convertit l'abréviation en majuscules
    $libelle_type_service = trim($_POST["libelle_type_service"]); // Supprime les espaces inutiles

    // Validation des données saisies
    if (!empty($type_service) && strlen($type_service) == 2 && !empty($libelle_type_service)) {
        // Vérifie si l'abréviation existe déjà dans la table `type_service`
        $check_type_service = $conn->query("SELECT * FROM type_service WHERE TYPE_SERVICE = '$type_service'");
        if ($check_type_service->num_rows == 0) {
            // Si l'abréviation n'existe pas, insère une nouvelle entrée dans `type_service`
            $conn->query("INSERT INTO type_service (TYPE_SERVICE, LIBELLE_TYPE_SERVICE) VALUES ('$type_service', '$libelle_type_service')");
        }

        // Insère un nouveau service en liant l'abréviation dans la table `service`
        $conn->query("INSERT INTO service (TYPE_SERVICE) VALUES ('$type_service')");
        $message = "Service ajouté avec succès."; // Message de confirmation
    } else {
        // Message d'erreur en cas de données invalides
        $message = "Erreur : Veuillez entrer un libellé valide et une abréviation de 2 lettres.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Service</title> <!-- Titre de la page -->
</head>
<body>
    <fieldset>
        <legend>Ajouter un Service</legend> <!-- En-tête de la page -->
        <form method="POST" action=""> 
            <label for="libelle_type_service">Service</label>
            <input type="text" name="libelle_type_service" id="libelle_type_service" 
               placeholder="Ex : Cardiologie" required>
            <!-- Champ pour saisir une abréviation de 2 lettres -->
            <label for="type_service">Abréviation (2 lettres) :</label>
            <input type="text" name="type_service" id="type_service" 
               placeholder="Ex : CA" maxlength="2" required>
        <br>
        <!-- Bouton pour soumettre le formulaire -->
        <input type="submit" value="Ajouter un service">
    </form>
    </fieldset>
    
</body>
</html>