<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "AdminHville", "P@ssword", "hville");

// Vérifie si la connexion à la base de données a échoué
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
// Définit l'encodage des données échangées avec la base de données à UTF-8
$conn->set_charset("utf8");
// Requête SQL pour récupérer les données des employés
$query = "
    SELECT 
        e.NOM, 
        e.PRENOM, 
        fe.LIBELLE_TYPE_FONCTION, 
        ts.LIBELLE_TYPE_SERVICE
    FROM employe e
    LEFT JOIN fonction_employe fe ON e.FONCTION_EMPLOYE = fe.FONCTION_EMPLOYE
    LEFT JOIN service s ON e.IDSERVICE = s.IDSERVICE
    LEFT JOIN type_service ts ON s.TYPE_SERVICE = ts.TYPE_SERVICE
";
$result = $conn->query($query); // Exécute la requête SQL

// Vérifie si la requête SQL a réussi
if (!$result) {
    die("Erreur lors de l'exécution de la requête : " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h2>Liste des Employés</h2>
    <table style="width: 90%">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Fonction</th>
                <th>Service</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['NOM']) ?></td>
                        <td><?= htmlspecialchars($row['PRENOM']) ?></td>
                        <td><?= htmlspecialchars($row['LIBELLE_TYPE_FONCTION']) ?></td>
                        <td><?= htmlspecialchars($row['LIBELLE_TYPE_SERVICE'] ?: "Non défini") ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucun employé trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php
// Fermeture de la connexion à la base de données
$conn->close();
?>
