<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un employé</title>
</head>
<body>
    <?php
    // Vérification et insertion des données dans la base
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['nomEmploye']) && !empty($_POST['prenomEmploye']) && !empty($_POST['FONCTION_EMPLOYE']) && !empty($_POST['IDSERVICE'])) {
            $nomEmploye = trim($_POST['nomEmploye']);
            $prenomEmploye = trim($_POST['prenomEmploye']);
            $fonctionEmploye = $_POST['FONCTION_EMPLOYE'];
            $idService = $_POST['IDSERVICE'];

            try {
                // Connexion à la base de données
                $User = "AdminHville";
                $Passwd = "P@ssword";
                $dsn = "mysql:host=localhost;dbname=hville;charset=utf8mb4";
                $db = new PDO($dsn, $User, $Passwd);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Préparation de la requête pour insérer l'employé
                $stmtInsert = $db->prepare("
                    INSERT INTO employe (NOM, PRENOM, FONCTION_EMPLOYE, IDSERVICE) 
                    VALUES (:nom, :prenom, :fonction, :service)
                ");
                // Liaison des paramètres
                $stmtInsert->bindParam(':nom', $nomEmploye, PDO::PARAM_STR);
                $stmtInsert->bindParam(':prenom', $prenomEmploye, PDO::PARAM_STR);
                $stmtInsert->bindParam(':fonction', $fonctionEmploye, PDO::PARAM_STR);
                $stmtInsert->bindParam(':service', $idService, PDO::PARAM_INT);

                // Exécution de la requête
                if ($stmtInsert->execute()) {
                    // Redirection vers index.php après une insertion réussie
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<p style='color: red;'>Erreur lors de l'ajout de l'employé.</p>";
                }
            } catch (PDOException $e) {
                echo "<p style='color: red;'>Erreur de base de données : " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p style='color: red;'>Veuillez remplir tous les champs du formulaire.</p>";
        }
    }
    ?>

    <fieldset>
        <legend>Ajouter un employé</legend>
        <form action="inserer_employe.php" method="POST">
            <!-- Champ pour le nom de l'employé -->
            <label for="nomEmploye">Nom</label>
            <input type="text" id="nomEmploye" name="nomEmploye" placeholder="Ex : Mensier" required><br>
            
            <!-- Champ pour le prénom de l'employé -->
            <label for="prenomEmploye">Prénom</label>
            <input type="text" id="prenomEmploye" name="prenomEmploye" placeholder="Ex : Enzo" required><br>
            
            <!-- Menu déroulant pour sélectionner le service -->
            <label for="IDSERVICE">Service</label>
            <select id="IDSERVICE" name="IDSERVICE" required>
                <?php
                try {
                    // Connexion à la base de données
                    $User = "AdminHville";
                    $Passwd = "P@ssword";
                    $dsn = "mysql:host=localhost;dbname=hville;charset=utf8mb4";
                    $db = new PDO($dsn, $User, $Passwd);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Requête pour obtenir les services
                    $requeteServices = "
                        SELECT ser.IDSERVICE, tse.LIBELLE_TYPE_SERVICE 
                        FROM service ser 
                        JOIN type_service tse 
                        ON ser.TYPE_SERVICE = tse.TYPE_SERVICE 
                        ORDER BY tse.LIBELLE_TYPE_SERVICE";
                    
                    $stmtServices = $db->query($requeteServices);
                    
                    while ($service = $stmtServices->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($service['IDSERVICE']) . "'>" . htmlspecialchars($service['LIBELLE_TYPE_SERVICE']) . "</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option value=''>Erreur : " . htmlspecialchars($e->getMessage()) . "</option>";
                }
                ?>
            </select><br>

            <!-- Menu déroulant pour sélectionner la fonction -->
            <label for="FONCTION_EMPLOYE">Fonction</label>
            <select id="FONCTION_EMPLOYE" name="FONCTION_EMPLOYE" required>
                <?php
                try {
                    // Requête pour obtenir les fonctions
                    $requeteFonctions = "SELECT FONCTION_EMPLOYE, LIBELLE_TYPE_FONCTION FROM fonction_employe ORDER BY LIBELLE_TYPE_FONCTION";
                    $stmtFonctions = $db->query($requeteFonctions);
                    
                    while ($fonction = $stmtFonctions->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($fonction['FONCTION_EMPLOYE']) . "'>" . htmlspecialchars($fonction['LIBELLE_TYPE_FONCTION']) . "</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option value=''>Erreur : " . htmlspecialchars($e->getMessage()) . "</option>";
                }
                ?>
            </select><br>
            
            <!-- Bouton pour soumettre le formulaire -->
            <input type="submit" value="Enregistrer">
        </form>
    </fieldset>
</body>
</html>
