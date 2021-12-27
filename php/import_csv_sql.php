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

$q = $pdo->query('SELECT * FROM tarifs_ter');

// Sélection de tous les trajets TER Intercités au tarif normal
while ($d = $q->fetch()) {
    if ($d['type_tarif'] == 'Tarif normal') {
        // Si la région n'existe pas on l'ajoute à notre table régions
        $q2 = $pdo->prepare('SELECT COUNT(idRegion) FROM regions WHERE nameRegion = :nameRegion');
        $q2->execute(['nameRegion' => $d['region']]);
        $r2 = $q2->fetchColumn();
        $q2->closeCursor();
        
        // Si aucune occurence n'a été trouvée on insère l'information en DB
        if ($r2 == 0) {
            $q3 = $pdo->prepare('INSERT INTO regions SET nameRegion = :nameRegion');
            $q3->bindParam('nameRegion', $d['region']);
            $q3->execute();
            $regions[$d['region']] = $pdo->lastInsertId();
            $q3->closeCursor();
        }

        //Si la gare d'origine n'existe pas on insère l'info en DB
        $q4 = $pdo->prepare('SELECT COUNT(idGare) FROM gares WHERE nameGare = :nameGare');
        $q4->execute(['nameGare' => $d['origine']]);
        $r4 = $q4->fetchColumn();

        // Si aucune occurence n'a été trouvée on insère l'information en DB
        if($r4 == 0) {
            $q5 = $pdo->prepare('INSERT INTO gares SET nameGare = :nameGare');
            $q5->bindValue('nameGare', $d['origine']);
            $q5->execute();
            $gares[$d['origine']] = $pdo->lastInsertId();
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
            $q7->execute();
            $gares[$d['destination']] = $pdo->lastInsertId();
            $q7->closeCursor();
        }
        
        $q8 = $pdo->prepare('INSERT INTO trajets SET idGareOrigine = (SELECT idGare FROM gares WHERE nameGare = :origine), idGareDestination = (SELECT idGare FROM gares WHERE nameGare = :destination), idRegion = (SELECT idRegion FROM regions WHERE nameRegion = :region), prix = :prix');
        $q8->bindValue('origine', $d['origine']);
        $q8->bindValue('destination', $d['destination']);
        $q8->bindValue('region', $d['region']);
        $q8->bindValue('prix', $d['prix']);
        $q8->execute();
    }
}

echo 'Le travail est fini :-)';
