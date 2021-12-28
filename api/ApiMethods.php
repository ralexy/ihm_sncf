<?php
/**
 * Created by PhpStorm.
 * User: alexy
 * Date: 2021-12-28
 * Time: 11:03
 */

require_once 'PdoKelCarte.php';

class ApiMethods
{
    /**
     * Constantes contenant les messages API
     * Utile pour débogguer l'Api et comprendre précisément ce qui ne va pas en cas de problème
     */
    const UNDEFINED_ERROR = 'Erreur non définie';
    const CARDPRICE = 49; // Prix annuel de la carte
    const TWENTYFIVEPERCENT = 0.75;
    const THIRTYPERCENT = 0.70;
    const FIFTYPERCENT = 0.5;
    const BLANCHE = 'blanche';
    const BLEUE = 'bleue';

    /**
     * Constantes permettant de déterminer si on créé ou non une FDF
     * et des frais HF
     */
    private $createRecord;
    private $createRecordHf;
    private $pdo;
    private $result = ['result' => false];
    private $idEtat = null;

    public function __construct()
    {
        $this->pdo = PdoKelCarte::getInstance();
    }

    /**
     * Méthode permettant de récupérer la liste des gares à partir d'un bout de chaîne de caractères
     * @param $nameGare
     * @return array|false
     */
    public function suggestOriginStation($nameGare) {
        $q = $this->pdo->prepare('SELECT * FROM gares WHERE nameGare LIKE :nameGare');

        $q->bindValue('nameGare', $nameGare. '%', PDO::PARAM_STR);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode permettant de retourner la liste des gares de destination en précisant une gare d'origine
     * @param $nameGare
     * @return array|false
     */
    public function suggestDestinationStation($nameGare) {
        $q = $this->pdo->prepare('SELECT nameGare AS nameGareDestination FROM gares WHERE idGare IN (SELECT idGareDestination FROM trajets WHERE idGareOrigine = (SELECT idGare FROM gares WHERE nameGare = :nameGare AND idRegion = (SELECT idRegion FROM gares WHERE nameGare = :nameGare)))');

        $q->bindValue('nameGare', $nameGare, PDO::PARAM_STR);
        $q->execute();

        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode permettant de calculer la réduction jeune SNCF en fonction
     *
     * @param $origin
     * @param $destination
     * @param string $day
     * @param string $hour
     * @param int $frequence
     * @return mixed
     */
    public function getPrices($origin, $destination, $day = '', $hour = '', $frequence = 1) {
        // Calcul des tarifs SNCF (sans prendre en compte les jours spéciaux)
        // Grand Est & Hauts-de-France & Centre = 25%
        // Pays de la Loire = 30%
        // Le reste
            // Bleu (50% réduction)
            // Lundi, Mardi, Mercredi, Jeudi, Vendredi 0h00 - 6h30
            // Lundi, Mardi, Mercredi, Jeudi, Vendredi 8h00 - 17h
            // Lundi, Mardi, Mercredi, Jeudi 18h30 - 0h00
            // Vendredi 8h00 - 14h
            // Vendredi 20h - 0h00
            // Samedi 0h00 - 23h59
            // Dimanche 0h00 - 15h
            // Dimanche 20h - 0h00

        // Blanc (25% réduction)
            // Lundi, Mardi, Mercredi, Jeudi, Vendredi 6h30 - 8h
            // Lundi, Mardi, Mercredi, Jeudi 17h00 - 18h30
            // Vendredi 14h-20h
            // Dimanche 15h - 20h

        $q = $this->pdo->prepare('SELECT t.prix AS normalPrice, r.nameRegion FROM trajets t INNER JOIN gares g ON t.idGareOrigine = g.idGare INNER JOIN regions r ON g.idRegion = r.idRegion WHERE t.idGareOrigine = (SELECT idGare FROM gares WHERE nameGare = :origin) AND t.idGareDestination = (SELECT idGare FROM gares WHERE nameGare = :destination)');
        $q->bindValue('origin', $origin, PDO::PARAM_STR);
        $q->bindValue('destination', $destination, PDO::PARAM_STR);
        $q->execute();

        $d = $q->fetch(PDO::FETCH_ASSOC);

        /**
         * Calendrier période blanche / bleue applicable pour 2021
         */
        switch ($d['nameRegion']) {
            case 'GRAND EST':
            case 'HAUTS DE FRANCE':
                $d['pricePromo'] = round($d['normalPrice'] * self::TWENTYFIVEPERCENT, 2); // 25% de remise toujours
                break;

            case 'PAYS DE LA LOIRE':
                $d['pricePromo'] = round($d['normalPrice'] * self::THIRTYPERCENT, 2); // 30% de remise toujours
                break;

            default:
                $d['pricePromo'] = $d['normalPrice'];

                switch($day) {
                    case 1: // Lundi
                    case 2: // Mardi
                    case 3: // Mercredi
                    case 4: // Jeudi
                        // Période blanche (moins de cas = moins de conditions)
                        if(($hour >= 630  && $hour < 800) || ($hour >= 1700 && $hour < 1830)) {
                            $d['periode'] = self::BLANCHE;
                        }
                        // Période bleue
                        else {
                            $d['periode'] = self::BLEUE;
                        }
                    break;

                    case 5: // Vendredi
                        // Période blanche (moins de cas = moins de conditions)
                        if(($hour >= 630  && $hour < 800) || ($hour >= 1400 && $hour < 2000)) {
                            $d['periode'] = self::BLANCHE;
                        }
                        // Période bleue
                        else {
                            $d['periode'] = self::BLEUE;
                        }
                    break;

                    case 6: // Samedi
                        // Période bleue toujours
                        $d['periode'] = self::BLEUE;
                        break;

                    case 7: // Dimanche
                        // Période blanche (moins de cas = moins de conditions)
                        if(($hour >= 1500 && $hour < 2000)) {
                            $d['periode'] = self::BLANCHE;
                        }
                        // Période bleue
                        else {
                            $d['periode'] = self::BLEUE;
                        }
                    break;
                }

                $d['pricePromo'] = ($d['periode'] == self::BLANCHE) ? round($d['normalPrice'] * self::TWENTYFIVEPERCENT, 2) : round($d['normalPrice'] * self::FIFTYPERCENT, 2); // 25% ou 50% de remise selon la période blanche ou bleue
                break;
        }

        $i = 0;
        $interesting = false;
        do {
            $d['simulation'][$i]['normal'] = $i * $d['normalPrice'];
            $d['simulation'][$i]['discount'] = $i * $d['pricePromo'] + self::CARDPRICE;
            $d['simulation'][$i]['interesting'] = $d['simulation'][$i]['discount'] < $d['simulation'][$i]['normal'];
            $interesting = $d['simulation'][$i]['discount'] < $d['simulation'][$i]['normal'];
            $i++;
        } while(!$interesting);

        $d['savedMoney'] = ($frequence * ($d['normalPrice']-$d['pricePromo']) - self::CARDPRICE > 0) ? $frequence * ($d['normalPrice']-$d['pricePromo']) - self::CARDPRICE : 0;
        $d['totalPrice'] = ($frequence * $d['normalPrice']);
        $d['totalDiscountPrice'] = ($frequence * $d['pricePromo']) + self::CARDPRICE;

        return $d;
    }

    /**
     * Méthode permettant de retourner une erreur générique
     *
     * @return array
     */
    public function getUndefinedError()
    {
        $result['message'] = self::UNDEFINED_ERROR;

        return $result;
    }
}