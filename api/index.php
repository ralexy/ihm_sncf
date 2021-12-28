<?php
/**
 * Created by PhpStorm.
 * User: alexy
 * Date: 2019-03-20
 * Time: 10:59
 */

require_once 'ApiMethods.php';

$apiMethods = new ApiMethods();
$jsonResult = $apiMethods->getUndefinedError();

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'login':
            if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
                $jsonResult = $apiMethods->login($_REQUEST['username'], $_REQUEST['password']);
            }

        case 'synchronize':
            if (isset($_REQUEST['memberId']) && isset($_REQUEST['expenses'])) {
                $jsonResult = $apiMethods->synchronize($_REQUEST['memberId'], $_REQUEST['expenses']);
            }

        case 'suggestOriginStation':
            if (isset($_REQUEST['nameGare'])) {
                $jsonResult = $apiMethods->suggestOriginStation($_REQUEST['nameGare']);
            }
        break;

        case 'suggestDestinationStation':
            if (isset($_REQUEST['nameGare'])) {
                $jsonResult = $apiMethods->suggestDestinationStation($_REQUEST['nameGare']);
            }
        break;

        case 'getPrices':
            if(isset($_REQUEST['origin']) && isset($_REQUEST['destination'])) {
                $day = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
                $hour = isset($_REQUEST['hour']) ? $_REQUEST['hour'] : '';
                $frequence = isset($_REQUEST['frequence']) ? $_REQUEST['frequence'] : 1;

                $jsonResult = $apiMethods->getPrices($_REQUEST['origin'], $_REQUEST['destination'], $day, $hour, $frequence);
            }
        break;
    }
}

echo json_encode($jsonResult);