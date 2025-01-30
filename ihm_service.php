<?php
// Connexion à la base de données avec encodage UTF-8
$conn = new mysqli("localhost", "AdminHville", "P@ssword", "hville");
// Vérifie si la connexion à la base de données a échoué
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error); // Arrête le script en cas d'erreur de connexion
}
// Définit l'encodage des données échangées avec la base de données à UTF-8
$conn->set_charset("utf8");

// Récupération des données fusionnées à partir de deux tables
$query = "
    SELECT 
        service.IDSERVICE,                     -- Identifiant unique d'un service
        service.TYPE_SERVICE,                 -- Type du service (clé étrangère)
        type_service.LIBELLE_TYPE_SERVICE     -- Libellé descriptif du type de service
    FROM service
    LEFT JOIN type_service 
    ON service.TYPE_SERVICE = type_service.TYPE_SERVICE
";
$result = $conn->query($query); // Exécute la requête SQL

// Vérifie si la requête SQL a réussi
if (!$result) {
    die("Erreur lors de l'exécution de la requête : " . $conn->error); // Affiche un message d'erreur et arrête le script
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Définit l'encodage de la page pour s'assurer que les caractères spéciaux sont affichés correctement -->
    <meta charset="UTF-8">
    <!-- Assure une mise en page adaptée pour les écrans mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Inclut une feuille de styles externe -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Liste des Services</h2>
    <table style="width: 60%">
        <thead>
            <tr> 
                <th>Services</th> 
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <!-- Parcourt chaque ligne du résultat de la requête -->
                    <tr>
                        <td>
                            <!-- Affiche le libellé du type de service. Si la valeur est nulle, affiche "Non défini". -->
                            <?= htmlspecialchars($row["LIBELLE_TYPE_SERVICE"] ?: "Non défini") ?>
                            <!-- `htmlspecialchars` protège contre les injections HTML -->
                        </td>
                    </tr>
                <?php endwhile; ?> <!-- Fin de la boucle de parcours des résultats -->
            <?php else: ?>
                <tr>
                    <td>Aucun service trouvé.</td> <!-- Message si aucun résultat -->
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>