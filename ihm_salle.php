<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style/main.css" rel="stylesheet" type="text/css">
    <title>Gestion des Salles - Hôpital de Ville</title>
</head>
<body>
    <?php
    /**
     * Interface de gestion des salles
     * Affiche la liste des salles avec leurs caractéristiques
     */

    // Configuration de l'affichage des erreurs
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Configuration de la base de données
    $config = [
        'host' => 'localhost',
        'user' => 'AdminHville',
        'password' => 'P@ssword',
        'database' => 'hville'
    ];

    try {
        // Connexion à la base de données
        $connexion = mysqli_connect(
            $config['host'],
            $config['user'],
            $config['password'],
            $config['database']
        );
        
        mysqli_set_charset($connexion, "utf8");

        // Vérifier si un service spécifique est demandé
        $service_filter = isset($_GET['service']) ? $_GET['service'] : null;

        // Requête de sélection des salles
        $requete = "
            SELECT 
                tsa.LIBELLE_TYPE_SALLE,
                s.NUMERO_SALLE,
                s.TEMPERATURE,
                tse.LIBELLE_TYPE_SERVICE 
            FROM salle s 
            JOIN type_salle tsa ON s.TYPE_SALLE = tsa.TYPE_SALLE
            JOIN service ser ON s.IDSERVICE = ser.IDSERVICE 
            JOIN type_service tse ON ser.TYPE_SERVICE = tse.TYPE_SERVICE 
            ORDER BY s.NUMERO_SALLE
        ";

        $resultats = mysqli_query($connexion, $requete);

        // Affichage du tableau des salles
        echo "<h2>Gestion des Salles</h2>";
        
        if (mysqli_num_rows($resultats) > 0) {
            echo "
            <table>
                <thead>
                    <tr>
                        <th>Type de Salle</th>
                        <th>Numéro</th>
                        <th>Température</th>
                        <th>Service</th>
                    </tr>
                </thead>
                <tbody>";

            while ($row = mysqli_fetch_assoc($resultats)) {
                echo "<tr>
                    <td>{$row['LIBELLE_TYPE_SALLE']}</td>
                    <td>{$row['NUMERO_SALLE']}</td>
                    <td>{$row['TEMPERATURE']}°C</td>
                    <td>{$row['LIBELLE_TYPE_SERVICE']}</td>
                </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>Aucune salle n'a été trouvée.</p>";
        }

    // Obtenir tous les types de salle distincts
    $requete_types = "SELECT DISTINCT LIBELLE_TYPE_SALLE FROM type_salle ORDER BY LIBELLE_TYPE_SALLE";
    $resultats_types = mysqli_query($connexion, $requete_types);

    if (!$resultats_types) {
        die("Erreur lors de la récupération des types de salle : " . mysqli_error($connexion));
    }

    $types_salle = [];
    while ($type = mysqli_fetch_assoc($resultats_types)) {
        $types_salle[] = $type['LIBELLE_TYPE_SALLE'];
    }

    if (!$service_filter) {
        // Construire la requête dynamique pour compter chaque type de salle par service
        $select_clauses = [];
        foreach ($types_salle as $type_salle) {
            $select_clauses[] = "SUM(CASE WHEN tsa.LIBELLE_TYPE_SALLE = '$type_salle' THEN 1 ELSE 0 END) AS `$type_salle`";
        }
        $select_clause = implode(", ", $select_clauses);

        $requete_count = "SELECT tse.LIBELLE_TYPE_SERVICE, $select_clause
                          FROM salle s
                          JOIN service ser ON s.IDSERVICE = ser.IDSERVICE
                          JOIN type_service tse ON ser.TYPE_SERVICE = tse.TYPE_SERVICE
                          JOIN type_salle tsa ON s.TYPE_SALLE = tsa.TYPE_SALLE
                          GROUP BY tse.LIBELLE_TYPE_SERVICE";

        $resultats_count = mysqli_query($connexion, $requete_count);

        if (!$resultats_count) {
            die("Erreur lors du comptage des salles : " . mysqli_error($connexion));
        }

        if (mysqli_num_rows($resultats_count) > 0) {
            echo "<h2>Nombre de chaque type de salle par service</h2>";
            echo "<table>
                    <tr>
                        <th>Service</th>";
            foreach ($types_salle as $type_salle) {
                echo "<th>$type_salle</th>";
            }
            echo "</tr>";

            while ($stat = mysqli_fetch_assoc($resultats_count)) {
                echo "<tr>
                        <td>{$stat['LIBELLE_TYPE_SERVICE']}</td>";
                foreach ($types_salle as $type_salle) {
                    echo "<td>{$stat[$type_salle]}</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    }

    } catch (Exception $e) {
        echo "<p class='error'>Une erreur est survenue : " . htmlspecialchars($e->getMessage()) . "</p>";
    } finally {
        if (isset($connexion)) {
            mysqli_close($connexion);
        }
    }
    ?>
</body> 
</html>