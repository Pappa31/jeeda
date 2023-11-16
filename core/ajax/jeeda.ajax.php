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

use Monolog\Handler\BrowserConsoleHandler;

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

  /* Fonction permettant l'envoi de l'entête 'Content-Type: application/json'
    En V3 : indiquer l'argument 'true' pour contrôler le token d'accès Jeedom
    En V4 : autoriser l'exécution d'une méthode 'action' en GET en indiquant le(s) nom(s) de(s) action(s) dans un tableau en argument
  */
    ajax::init();
    log::add('jeeda', 'debug', 'Ajax Call');
    /*
     * Prise en compte des commandes dans le panneau general du plugin (ie synchronisation avec le site Skoda)
    */
    switch (init('action')){
      case 'sync': // Synchronise les informations du compte, recuperation de tous les vehicules
        log::add('jeeda', 'debug', 'Appel createVehicule');
        jeeda::createVehicule();
        log::add('jeeda', 'debug', 'Appel createVehicule sucess');
        ajax::success();
      break;
      case 'setChargingMode': // Synchronise les informations du compte, recuperation de tous les vehicules
        log::add('jeeda', 'debug', 'Appel setChargingMode');
        jeeda::setCharingMode(init('id'),init('mode'));
        log::add('jeeda', 'debug', 'Appel setChargingMode sucess');
        ajax::success();
      break;
      case 'getCarData': // Synchronise les informations du compte, recuperation de tous les vehicules
        log::add('jeeda', 'debug', 'Appel getCarData');
        $data = jeeda::getCarDataFor(init('VIN'));
        log::add('jeeda', 'debug', 'Appel getCarData success');
        ajax::success($data);
      break;
      case 'getTravelData': // Synchronise les informations du compte, recuperation de tous les vehicules
        log::add('jeeda', 'debug', 'Appel getTravelData');
        $data = jeeda::getTravelDataFor(init('VIN'),init('startDate'), init('endDate'));
        log::add('jeeda', 'debug', 'Appel getTravelData success');
        ajax::success($data);
      break;
      case 'showTravel': // Synchronise les informations du compte, recuperation de tous les vehicules
        log::add('jeeda', 'debug', 'Appel showTravel');
        //$travel['date'] = init('startDate');
        $travel = jeeda::showTravelFor(init('VIN'),init('startDate'), init('endDate'));
        log::add('jeeda', 'debug', 'Appel showTravel success');
        ajax::success($travel);
      break;
      case 'getDriveStatistique': // Synchronise les informations du compte, recuperation de tous les vehicules
        log::add('jeeda', 'debug', 'Appel getDriveStatisqueFor');
        $stat = jeeda::getDriveStatisqueFor(init('VIN'),init('startDate'), init('endDate'));
        log::add('jeeda', 'debug', 'Appel getDriveStatisqueFor success');
        ajax::success($stat);
      break;
      default:
        log::add('jeeda', 'info', 'Cmd ' . init('action') . 'not yet implemented');
        ajax::error(init('action') . ' not yet implemented', 1);
    }
    throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
    /*     * *********Catch exeption*************** */
}
catch (Exception $e) {
    log::add('jeeda', 'error', 'Erreur appel AJAX ' . $e->getCode());
    ajax::error(displayException($e), $e->getCode());
}
