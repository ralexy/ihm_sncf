<?php
/**
 * Script permettant de normaliser les données brutes CSV issues des données publiques SNCF
 * L'idée est d'en faire une base de données SQL, pour ensuite pouvoir l'interroger,
 * Et créer manuellement une API JSON
 *
 * L'idée étant d'avoir la donnée triée rapidement,
 * De pouvoir la manipuler rapidement, facilement,
 * De l'extraire avec des temps d'accès raisonnables.
 */
$pdo = new PDO('mysql:host=localhost;dbname=kelcarte', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$q = $pdo->query('SELECT * FROM tarifs_ter');

$regions = [];
$gares = [];

// Sélection de tous les trajets TER Intercités au tarif normal
while($d = $q->fetch()) {
    if($d['libelle_tarif'] === 'Billet Tarif Normal Régional') {

        // Si la région n'existe pas on l'ajoute à notre table régions
        $q2 = $pdo->prepare('SELECT COUNT(idRegion) FROM regions WHERE nameRegion = :nameRegion');
        $q2->execute(['nameRegion' => $nameRegion]);
        $r2 = $q2->fetchColumn();
        $q2->closeCursor();

        //var_dump($r2);
        //exit();
        // Si aucune occurence n'a été trouvée on insère l'information en DB
        if($r2 == 0) {
            $q3 = $pdo->prepare('INSERT INTO regions SET nameRegion = :nameRegion');
            $q3->bindValue('nameRegion', $nameRegion);
            $regions[$d['region']] = $q3->lastInsertId();
            $q3->execute();
            $q3->closeCursor();
        }

        // Si la gare d'origine n'existe pas on insère l'info en DB
        $q4 = $pdo->prepare('SELECT COUNT(idGare) FROM gares WHERE nameGare = :nameGare');
        $q4->execute(['nameGare' => $d['origine']]);
        $r4 = $q4->fetchColumn();

        // Si aucune occurence n'a été trouvée on insère l'information en DB
        if($r4 == 0) {
            $q5 = $pdo->prepare('INSERT INTO gares SET nameGare = :nameGare');
            $q5->bindValue('nameGare', $d['origine']);
            $gares[$d['origine']] = $q5->lastInsertId();
            $q5->execute();
            $q5->closeCursor();
        }

        // Si la gare de destination n'existe pas on insère l'info en DB
        $q6 = $pdo->prepare('SELECT COUNT(idGare) FROM gares WHERE nameGare = :nameGare');
        $q6->execute(['nameGare' => $d['destination']]);
        $r6 = $q6->fetchColumn();

        // Si aucune occurence n'a été trouvée on insère l'information en DB
        if($r6 == 0) {
            $q7 = $pdo->prepare('INSERT INTO gares SET nameGare = :nameGare');
            $q7->bindValue('nameGare', $d['destination']);
            $gares[$d['destination']] = $q7->lastInsertId();
            $q7->execute();
            $q7->closeCursor();
        }

        $q8 = $pdo->prepare('INSERT INTO trajets SET idGareOrigine = :idGareOrigine, idGareDestination = :idGareDestination, idRegion = :idRegion, prix = :prix');
        $q8->bindValue('idGareOrigine', $gares['origine'], PDO::PARAM_INT);
        $q8->bindValue('idGareDestination', $gares['destination'], PDO::PARAM_INT);
        $q8->bindValue('idRegion', $regions['region'], PDO::PARAM_INT);
        $q8->bindValue('prix', $d['prix']);
    }
}

echo 'Le travail est fini :-)';

// Sélection des lignes
// Sélection de la région + ajout à un array
    // Si la région n'existe pas on l'ajoute

// Sélection de la ville de départ + ajout à un array
    // Si elle n'existe pas dans l'array on l'insère

// Insertion du trajet
    // INSERT INTO trajets VALUES ... (SELECT id FROM WHERE name = '')

// On ajoute de la logique et du JSON et on a notre API.
