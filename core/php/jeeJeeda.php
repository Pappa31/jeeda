<?php
/* This file is part of plugin Jeeda  for Jeedom.
 *
 * Jeeda is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeeda is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License. 
 * If not, see <http://www.gnu.org/licenses/>.
 */

try {
    require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
    log::add('jeeda','debug', 'Nouveau message du demon');
    if (!jeedom::apiAccess(init('apikey'), 'jeeda')) { //remplacez template par l'id de votre plugin
        echo __('Vous n\'etes pas autorisé à effectuer cette action', __FILE__);
        die();
    }
    if (init('test') != '') {
        echo 'OK';
        die();
    }
    $result = json_decode(file_get_contents("php://input"), true);
    if (!is_array($result)) {
        die();
    }

    if (isset($result['ping'])) {
        log::add('jeeda', 'info', 'ping ok'); 
    } elseif (isset($result['skodaData'])) {
        log::add('jeeda', 'info', 'recieved ' . $result['skodaData']); 
        $vehiculesJson = json_decode($result['skodaData']);
        jeeda::createVehiculeFromJSON($vehiculesJson);
    } else {
        log::add('jeeda', 'error', 'unknown message received from daemon'); 
    }
} catch (Exception $e) {
    log::add('jeeda', 'error', displayException($e)); 
}
