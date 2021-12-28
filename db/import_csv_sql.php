<?php
ini_set('max_execution_time', '0'); // for infinite time of execution

/**
 * Script permettant de normaliser les données brutes CSV issues des données publiques SNCF
 * L'idée est d'en faire une base de données SQL, pour ensuite pouvoir l'interroger,
 * Et créer manuellement une API JSON
 *
 * L'idée étant d'avoir la donnée triée rapidement,
 * De pouvoir la manipuler rapidement, facilement,
 * De l'extraire avec des temps d'accès raisonnables.
 */
try {
    $pdo = new PDO('mysql:host=localhost;dbname=kelcarte', 'root', 'root');
} catch(Exception $e) {
    echo 'Exception -> ';
    var_dump($e->getMessage());
}

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('SET CHARACTER SET utf8');

$q = $pdo->query('SELECT DISTINCT region FROM tarifs_ter');

// On insère nos régions en premier avant d'effectuer tout autre traitement
while ($d = $q->fetch()) {
    $q3 = $pdo->prepare('INSERT INTO regions SET nameRegion = :nameRegion');
    $q3->bindParam('nameRegion', $d['region']);
    $q3->execute();
    $q3->closeCursor();
}

// On peut passer à l'insertion de nos trajets
$q = $pdo->query('SELECT * FROM tarifs_ter');

// Sélection de tous les trajets TER Intercités au tarif normal
while ($d = $q->fetch()) {
    if ($d['type_tarif'] == 'Tarif normal') {
        //Si la gare d'origine n'existe pas on insère l'info en DB
        $q4 = $pdo->prepare('SELECT COUNT(idGare) FROM gares WHERE nameGare = :nameGare');
        $q4->bindValue('nameGare', $d['origine']);
        $q4->execute();
        $r4 = $q4->fetchColumn();

        // Si aucune occurence n'a été trouvée on insère l'information en DB
        if($r4 == 0) {
            // Insertion de la région la plus fréquente pour une gare de départ donnée.

            $qSupp = $pdo->prepare('SELECT idRegion FROM regions WHERE nameRegion = (SELECT region FROM tarifs_ter WHERE origine = :nameGare GROUP BY region ORDER BY COUNT(region) DESC LIMIT 1)');
            $qSupp->bindValue('nameGare', $d['origine']);
            $qSupp->execute();
            $rSupp = $qSupp->fetchColumn();

            //echo 'Origine = ' . $d['origine']. ' nameRegion = '. $d['region']. ' Type Proéminent '. var_dump($rSupp). '<br />';

            if($qSupp) {
                $q5 = $pdo->prepare('INSERT INTO gares SET nameGare = :nameGare, idRegion = (SELECT idRegion FROM regions WHERE nameRegion = (SELECT region FROM tarifs_ter WHERE origine = :nameGare GROUP BY region ORDER BY COUNT(region) DESC LIMIT 1))');
                $q5->bindValue('nameGare', $d['origine']);
                //$q5->bindValue('nameRegion', $d['region']);
                //$q5->bindValue('nameRegion', $rSupp);
                $q5->execute();
                $gares[$d['origine']] = $pdo->lastInsertId();
                $q5->closeCursor();
            }
        }

        // Si la gare de destination n'existe pas on insère l'info en DB
        $q6 = $pdo->prepare('SELECT COUNT(idGare) FROM gares WHERE nameGare = :nameGare');
        $q6->bindValue('nameGare', $d['destination']);
        $q6->execute();
        $r6 = $q6->fetchColumn();

        // Si aucune occurence n'a été trouvée on insère l'information en DB
        if($r6 == 0) {
            $q7 = $pdo->prepare('INSERT INTO gares SET nameGare = :nameGare, idRegion = (SELECT idRegion FROM regions WHERE nameRegion = :nameRegion)');
            $q7->bindValue('nameGare', $d['destination']);
            $q7->bindValue('nameRegion', $d['region']);
            $q7->execute();
            $gares[$d['destination']] = $pdo->lastInsertId();
            $q7->closeCursor();
        }
        
        $q8 = $pdo->prepare('INSERT INTO trajets SET idGareOrigine = (SELECT idGare FROM gares WHERE nameGare = :origine), idGareDestination = (SELECT idGare FROM gares WHERE nameGare = :destination), prix = :prix');
        $q8->bindValue('origine', $d['origine']);
        $q8->bindValue('destination', $d['destination']);
        $q8->bindValue('prix', $d['prix']);
        $q8->execute();
    }
}

// On supprime de notre DB les trajets qui concernent paris et qui n'ont aucun intérêt.
$q9 = $pdo->exec('DELETE FROM `gares` WHERE `nameGare` LIKE `%PARIS%`');

echo 'Le travail est fini :-)';
