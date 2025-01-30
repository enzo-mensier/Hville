<?php
if(isset($_POST['type_salle']) && isset($_POST['service']) && isset($_POST['numero_salle'])) {
    $type_salle = $_POST['type_salle'];
    $service = $_POST['service'];
    $numero_salle = $_POST['numero_salle'];
    
    // Paramètres de connexion à la base de données
    $Host = "localhost";
    $User = "AdminHville";
    $Passwd = "P@ssword";
    $BD = "hville";
    
    // Connexion à la base de données
    $connexion = mysqli_connect($Host, $User, $Passwd, $BD);
    
    if (!$connexion) {
        die("La connexion à la base de données a échoué : " . mysqli_connect_error());
    }
    
    // Configuration de l'encodage UTF-8
    mysqli_set_charset($connexion, "utf8");
    
    // Requête d'insertion de la salle
    $requete = "INSERT INTO salle (TYPE_SALLE, IDSERVICE, NUMERO_SALLE) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connexion, $requete);
    mysqli_stmt_bind_param($stmt, "sii", $type_salle, $service, $numero_salle);
    
    if (mysqli_stmt_execute($stmt)) {
        $id_salle = mysqli_insert_id($connexion);

        // Mise à jour de la table quantite_salle
        $requete_quantite = "INSERT INTO quantite_salle (IDSALLE, IDSERVICE, QUANTITE_SALLE) VALUES (?, ?, 1)";
        $stmt_quantite = mysqli_prepare($connexion, $requete_quantite);
        mysqli_stmt_bind_param($stmt_quantite, "ii", $id_salle, $service);
        mysqli_stmt_execute($stmt_quantite);
    } else {
        echo "<p style='color: red;'>Erreur lors de l'ajout de la salle : " . mysqli_error($connexion) . "</p>";
    }
    
    mysqli_close($connexion);
}

if(isset($_POST['ajouter_type']) && isset($_POST['code_type_salle']) && isset($_POST['libelle_type_salle'])) {
    $code_type_salle = $_POST['code_type_salle'];
    $libelle_type_salle = $_POST['libelle_type_salle'];
    
    // Paramètres de connexion à la base de données
    $Host = "localhost";
    $User = "AdminHville";
    $Passwd = "P@ssword";
    $BD = "hville";
    
    // Connexion à la base de données
    $connexion = mysqli_connect($Host, $User, $Passwd, $BD);
    
    if (!$connexion) {
        die("La connexion à la base de données a échoué : " . mysqli_connect_error());
    }
    
    // Configuration de l'encodage UTF-8
    mysqli_set_charset($connexion, "utf8");
    
    // Requête d'insertion du type de salle
    $requete = "INSERT INTO type_salle (TYPE_SALLE, LIBELLE_TYPE_SALLE) VALUES (?, ?)";
    $stmt = mysqli_prepare($connexion, $requete);
    mysqli_stmt_bind_param($stmt, "ss", $code_type_salle, $libelle_type_salle);
    
    if (mysqli_stmt_execute($stmt)) {
    } else {
        echo "<p style='color: red;'>Erreur lors de l'ajout du type de salle : " . mysqli_error($connexion) . "</p>";
    }
    
    mysqli_close($connexion);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style/main.css" rel="stylesheet" type="text/css">
    <title>Ajouter une salle</title>
</head>
<body>
    <fieldset>
        <legend>Ajouter une salle</legend>
        <form action="inserer_salle.php" method="POST">
            
            <label for="type_salle">Type de Salle</label>
            <select id="type_salle" name="type_salle">
                <?php
                // Connexion à la base de données
                $connexion = mysqli_connect("localhost", "AdminHville", "P@ssword", "hville");
                mysqli_set_charset($connexion, "utf8");
                
                // Récupération des types de salle
                $requete = "SELECT TYPE_SALLE, LIBELLE_TYPE_SALLE FROM type_salle";
                $resultats = mysqli_query($connexion, $requete);
                
                while ($type = mysqli_fetch_assoc($resultats)) {
                    echo "<option value='{$type['TYPE_SALLE']}'>{$type['LIBELLE_TYPE_SALLE']}</option>";
                }
                ?>
            </select><br>
            
            <label for="service">Service</label>
            <select id="service" name="service">
                <?php
                // Récupération des services
                $requete = "SELECT ser.IDSERVICE, tse.LIBELLE_TYPE_SERVICE 
                           FROM service ser 
                           JOIN type_service tse 
                           ON ser.TYPE_SERVICE = tse.TYPE_SERVICE";
                $resultats = mysqli_query($connexion, $requete);
                
                while ($service = mysqli_fetch_assoc($resultats)) {
                    echo "<option value='{$service['IDSERVICE']}'>{$service['LIBELLE_TYPE_SERVICE']}</option>";
                }
                
                mysqli_close($connexion);
                ?>
            </select><br>
            
            <label for="numero_salle">Numéro de Salle</label>
            <input type="number" id="numero_salle" name="numero_salle" placeholder="Séléctionnez un numéro de salle" required style="
                padding: 8px;
                font-size: 0.95em;
                border: 2px solid #ccc;
                border-radius: 5px;
                outline: none;
                transition: border-color 0.3s;
            "><br>
            
            <input type="submit" value="Enregistrer">
        </form>
    </fieldset>

    <fieldset>
        <legend>Ajouter un nouveau type de salle</legend>
        <form action="inserer_salle.php" method="POST">
            <label for="code_type_salle">Code du type de salle</label>
            <input type="text" id="code_type_salle" name="code_type_salle" placeholder="Ex : CA"maxlength="2" required pattern="[A-Za-z]{2}"><br>
            
            <label for="libelle_type_salle">Libellé du type de salle</label>
            <input type="text" id="libelle_type_salle" name="libelle_type_salle" placeholder="Ex : Cardiologie" required><br>
            
            <input type="submit" name="ajouter_type" value="Ajouter le type">
        </form>
    </fieldset>
</body>
</html>