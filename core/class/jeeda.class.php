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


/* * ***************************Includes********************************* */

use Illuminate\Support\Arr;

require_once __DIR__  . '/../../../../core/php/core.inc.php';

class jeeda extends eqLogic {

  /*     * *************************Attributs****************************** */

  /*
  * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
  * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
  public static $_widgetPossibility = array();
  */

  public static $ERROR_ID_INCONNU = 10;

  private static $_DATA = array("VIN"=>array("type"=>"info","subtype"=>"string","isVisible"=>0,"historique"=>0), 
                                "model"=>array("type"=>"info","subtype"=>"string","isVisible"=>1,"historique"=>0),
                                "year"=>array("type"=>"info","subtype"=>"string","isVisible"=>1,"historique"=>0),
                                "battery_level"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>1,"historique"=>1),
                                "battery_capacity"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>1,"historique"=>0),
                                "charge_max_ampere"=>array("type"=>"info","subtype"=>"string","isVisible"=>0,"historique"=>0),
                                "charge_rate"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>1,"historique"=>1),
                                "charging"=>array("type"=>"info","subtype"=>"binary","isVisible"=>1,"historique"=>1),
                                "charging_cable_connected"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "charging_cable_locked"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "charging_power"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>1,"historique"=>1),
                                "charging_time_left"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>0,"historique"=>1),
                                "climatisation_target_temperature"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>1,"historique"=>0),
                                "climatisation_time_left"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>0,"historique"=>0),
                                "departure1"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "departure2"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "distance"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>1,"historique"=>1),
                                "door_closed_left_back"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "door_closed_left_front"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "door_closed_right_back"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "door_closed_right_front"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "door_locked"=>array("type"=>"info","subtype"=>"binary","isVisible"=>1,"historique"=>0),
                                "electric_climatisation"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "electric_climatisation_attributes_status"=>array("type"=>"info","subtype"=>"string","isVisible"=>0,"historique"=>0),
                                "electric_range"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>1,"historique"=>1),
                                "engine_power"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>1,"historique"=>1),
                                "engine_type"=>array("type"=>"info","subtype"=>"string","isVisible"=>1,"historique"=>1),
                                "external_power"=>array("type"=>"info","subtype"=>"binary","isVisible"=>1,"historique"=>1),
                                "hood_closed"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "last_connected"=>array("type"=>"info","subtype"=>"string","isVisible"=>0,"historique"=>0),
                                "max_charging_power"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>0,"historique"=>0),
                                "min_charge_level"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>0,"historique"=>0),
                                "model_image_large"=>array("type"=>"info","subtype"=>"string","isVisible"=>0,"historique"=>0),
                                "model_image_small"=>array("type"=>"info","subtype"=>"string","isVisible"=>0,"historique"=>0),
                                "model_year"=>array("type"=>"info","subtype"=>"string","isVisible"=>0,"historique"=>0),
                                "parking_light"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "plug_autounlock"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "request_in_progress"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "requests_remaining"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>0,"historique"=>0),
                                "seat_heating_front_left"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "seat_heating_front_right"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "trunk_closed"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "vehicle_moving"=>array("type"=>"info","subtype"=>"binary","isVisible"=>1,"historique"=>1),
                                "window_closed_left_back"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "window_closed_left_front"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "window_closed_right_back"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "window_closed_right_front"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "window_heater_new"=>array("type"=>"info","subtype"=>"binary","isVisible"=>0,"historique"=>0),
                                "window_heater_attributes_Front"=>array("type"=>"info","subtype"=>"string","isVisible"=>0,"historique"=>0),
                                "window_heater_attributes_Rear"=>array("type"=>"info","subtype"=>"string","isVisible"=>0,"historique"=>0),
                                "windows_closed"=>array("type"=>"info","subtype"=>"binary","isVisible"=>1,"historique"=>0),
                                "position_lat"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>1,"historique"=>1),
                                "position_lng"=>array("type"=>"info","subtype"=>"numeric","isVisible"=>1,"historique"=>1),
                                "position_timestamp"=>array("type"=>"info","subtype"=>"string","isVisible"=>1,"historique"=>1)
            );
  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration du plugin
  */
  public static $_encryptConfigKey = array('login', 'pwd');
  

  /*     * ***********************Methode static*************************** */
  public static function deamon_info() {
    $return = array();
    $return['log'] = __CLASS__;
    $return['state'] = 'nok';
    $pid_file = jeedom::getTmpFolder(__CLASS__) . '/deamon.pid';
    if (file_exists($pid_file)) {
        if (@posix_getsid(trim(file_get_contents($pid_file)))) {
            $return['state'] = 'ok';
        } else {
            shell_exec(system::getCmdSudo() . 'rm -rf ' . $pid_file . ' 2>&1 > /dev/null');
        }
    }
    $return['launchable'] = 'ok';
    $user = config::byKey('login', __CLASS__); // exemple si votre démon à besoin de la config user,
    $pwd = config::byKey('pwd', __CLASS__); // password,
    $port = config::byKey('port', __CLASS__); // et clientId
    $standardCycle = config::byKey('standardCycle', __CLASS__); // et clientId
    $driveCycle = config::byKey('driveCycle', __CLASS__); // et clientId
    if ($user == '') {
        $return['launchable'] = 'nok';
        $return['launchable_message'] = __('Le nom d\'utilisateur n\'est pas configuré', __FILE__);
    } elseif ($pwd == '') {
        $return['launchable'] = 'nok';
        $return['launchable_message'] = __('Le mot de passe n\'est pas configuré', __FILE__);
    } elseif ($port == '') {
        $return['launchable'] = 'nok';
        $return['launchable_message'] = __('Le port de communication n\'est pas configuré', __FILE__);
    }elseif ($standardCycle == '') {
      $return['launchable'] = 'nok';
      $return['launchable_message'] = __('Le cycle standard n\'est pas configuré', __FILE__);
    }elseif ($driveCycle == '') {
      $return['launchable'] = 'nok';
      $return['launchable_message'] = __('Le cycle de conduite n\'est pas configuré', __FILE__);
    } 

    return $return;
}
/**
 * Lancement du demon
 */
public static function deamon_start() {
  self::deamon_stop();
  $deamon_info = self::deamon_info();
  if ($deamon_info['launchable'] != 'ok') {
      throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
  }

  $path = realpath(dirname(__FILE__) . '/../../resources/jeedad'); // répertoire du démon à modifier
  $cmd = 'python3 ' . $path . '/jeedad.py'; // nom du démon à modifier
  $cmd .= ' --loglevel ' . log::convertLogLevel(log::getLogLevel(__CLASS__));
  $cmd .= ' --socketport ' . config::byKey('socketport', __CLASS__, config::byKey('port', __CLASS__)); // port par défaut à modifier
  $cmd .= ' --callback ' . network::getNetworkAccess('internal', 'proto:127.0.0.1:port:comp') . '/plugins/jeeda/core/php/jeeJeeda.php'; // chemin de la callback url à modifier (voir ci-dessous)
  $cmd .= ' --user "' . trim(str_replace('"', '\"', config::byKey('login', __CLASS__))) . '"'; // on rajoute les paramètres utiles à votre démon, ici user
  $cmd .= ' --pwd "' . trim(str_replace('"', '\"', config::byKey('pwd', __CLASS__))) . '"'; // et password
  $cmd .= ' --standardCycle "' . trim(str_replace('"', '\"', config::byKey('standardCycle', __CLASS__))) . '"'; // cycle d'interrogation des serveurs Skoda en mode charge standard
  $cmd .= ' --driveCycle "' . trim(str_replace('"', '\"', config::byKey('driveCycle', __CLASS__))) . '"'; // cycle d'interrogation des serveurs Skoda en mode charge conduite
  $cmd .= ' --apikey ' . jeedom::getApiKey(__CLASS__); // l'apikey pour authentifier les échanges suivants
  $cmd .= ' --pid ' . jeedom::getTmpFolder(__CLASS__) . '/deamon.pid'; // et on précise le chemin vers le pid file (ne pas modifier)
  log::add(__CLASS__, 'info', 'Lancement démon');
  log::add(__CLASS__, 'debug', $cmd);
  $result = exec($cmd . ' >> ' . log::getPathToLog('jeeda_daemon') . ' 2>&1 &'); 
  $i = 0;
  while ($i < 20) {
      $deamon_info = self::deamon_info();
      if ($deamon_info['state'] == 'ok') {
          break;
      }
      sleep(1);
      $i++;
  }
  if ($i >= 30) {
      log::add(__CLASS__, 'error', __('Impossible de lancer le démon, vérifiez le log', __FILE__), 'unableStartDeamon');
      return false;
  }
  message::removeAll(__CLASS__, 'unableStartDeamon');
  return true;
}
/**
 * Arret du demon
 */
public static function deamon_stop() {
  $pid_file = jeedom::getTmpFolder(__CLASS__) . '/deamon.pid'; // ne pas modifier
  if (file_exists($pid_file)) {
      $pid = intval(trim(file_get_contents($pid_file)));
      system::kill($pid);
  }
  system::kill('jeedad.py'); // nom du démon à modifier
  sleep(1);
}  
  
  /*
  * Fonction qui recupere les données d'un vehicule
  */
  public static function getCarDataFor($id){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    log::add('jeeda','debug', $id);
    $vehicule = eqLogic::byId($id,'jeeda',false);
    if (is_object($vehicule)){
      return $vehicule->getCarData();
    }else{
      log::add('jeeda','warning', 'ID '. $id . ' inconnu');  
      throw new Exception('ID '. $id . ' inconnu', jeeda::$ERROR_ID_INCONNU);
    }
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
  }

  public static function getDriveStatisqueFor($id,$debut,$fin){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    log::add('jeeda','debug', $id);
    $vehicule = eqLogic::byId($id,'jeeda',false);
    if (is_object($vehicule)){
      return $vehicule->getDriveStatisque($debut, $fin);
    }else{
      log::add('jeeda','warning', 'ID '. $id . ' inconnu');  
      throw new Exception('ID '. $id . ' inconnu', jeeda::$ERROR_ID_INCONNU);
    }
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
  }

  /**
   * Recupere les trajets d'un véhicle
   */
  public static function getTravelDataFor($id,$startDate,$endDate){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    log::add('jeeda','debug', $id);
    $vehicule = eqLogic::byId($id,'jeeda',false);
    if (is_object($vehicule)){
      // Recupere les changements d'état de deplacement du vehicule
      $cmd = $vehicule->getCmd(null, 'engine_type');
      if($cmd->execCmd()=="iV")
        return $vehicule->getElectricTravelData($startDate,$endDate);
      else
        return $vehicule->getThermicTravelData($startDate,$endDate);

    }else{
      log::add('jeeda','warning', 'VIN '. $id . ' inconnu');  
      throw new Exception('VIN '. $id . ' inconnu', jeeda::$ERROR_ID_INCONNU);
    }
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
  }
  /**
   * Recupere le trajet d'un véhicle
   */
  public static function showTravelFor($id,$startDate,$endDate){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    log::add('jeeda','debug', $id);
    $vehicule = eqLogic::byId($id,'jeeda',false);
    if (is_object($vehicule)){
      return $vehicule->showTravel($startDate,$endDate);
    }else{
      log::add('jeeda','warning', 'VIN '. $id . ' inconnu');  
      throw new Exception('VIN '. $id . ' inconnu', jeeda::$ERROR_ID_INCONNU);
    }
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
  }
  /**
   * Recupere les stats de chargement d'un véhicle
   */
  public static function getChargingInfoFor($id,$startDate,$endDate){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    log::add('jeeda','debug', $id);
    $vehicule = eqLogic::byId($id,'jeeda',false);
    if (is_object($vehicule)){
      $cmd = $vehicule->getCmd(null, 'engine_type');
      if($cmd->execCmd()=="iV")
        return $vehicule->getChargingInfo($startDate,$endDate);
      else
      {
        $stat['general'] = array();
        $stat['detaillee'] = array();
        return json_encode($stat);
      }
    }else{
      log::add('jeeda','warning', 'ID '. $id . ' inconnu');  
      throw new Exception('ID '. $id . ' inconnu', jeeda::$ERROR_ID_INCONNU);
    }
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
  }
  /**
   * Fonction qui demande l'arret ou le lancement du chargement
   */
  public static function setCharingMode($id, $mode){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    // recuperation du vehicule
    $jeeda = eqLogic::byId($id);
    if (null!=$jeeda){
      $logicalId = $jeeda->getLogicalID();
      /* Interrogation des API SKoda */   
      $strCmd = "python3 " . dirname(__FILE__) . "/../../resources/setChargingMode.py " . config::byKey('login', 'jeeda') . " " . config::byKey('pwd', 'jeeda') . " " . $logicalId . " " . $mode;
      $cmdPi = escapeshellcmd($strCmd);
      log::add('jeeda','debug', 'Execute ' . $cmdPi);
      exec($cmdPi, $output, $retourCode);
      if($retourCode != 0){
        log::add('jeeda','error', 'Erreur appel ' . __CLASS__ . ':' . $retourCode);
        for ($i=0;$i<count($output);$i++){
          log::add('jeeda','error', $output[$i]);
        }
        throw new Exception("Jeeda error " .$output[0],  $retourCode);
      }
    }
    else {
      log::add('jeeda','error', $id . ' non trouvé !!!');  
    }
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
  }

  /**
   * Fonction qui cree les véhicules du compte depuis le demon
   */
  public static function createVehiculeFromJSON($vehiculesJson){
    log::add('jeeda','debug', 'Nb vehicules ' . count($vehiculesJson));
    for($i=0;$i<count($vehiculesJson);$i++){
      log::add('jeeda','debug', 'Trouver vehicule ' . $vehiculesJson[$i]->{"attrs"}->{'VIN'});
      // test si le vehicule existe
      $jeeda = eqLogic::byLogicalId($vehiculesJson[$i]->{"attrs"}->{'VIN'},'jeeda',false);
      if (!is_object($jeeda)) {
        log::add('jeeda','debug', 'Create new vehicule');
        $jeeda = new jeeda();
        $jeeda->setEqType_name('jeeda');
        $jeeda->setLogicalId($vehiculesJson[$i]->{"attrs"}->{'VIN'});
        $jeeda->setName($vehiculesJson[$i]->{"attrs"}->{'model'});
        $jeeda->setIsEnable(1);
        $jeeda->setIsVisible(1);
      }
      $jeeda->save();

      /**
       * Creation des données
       */
      foreach($vehiculesJson[$i]->{"attrs"} as $key=>$value){
        $cmd = $jeeda->getCmd(null, $key);
        if (!is_object($cmd)) {
          $cmd = new jeedaCmd();
          $cmd->setName($key);
          $cmd->setIsVisible(jeeda::$_DATA[$key]["isVisible"]);
          $cmd->setIsHistorized(jeeda::$_DATA[$key]["historique"]);
          if (1==jeeda::$_DATA[$key]["historique"]){
            $cmd->setConfiguration("historizeMode", "none");
            $cmd->setConfiguration("historyPurge","");
          }
        }
        $cmd->setEqLogic_id($jeeda->getId());
        $cmd->setLogicalId($key);        
        if (jeeda::$_DATA[$key]["subtype"]!=null){
          $cmd->setType(jeeda::$_DATA[$key]["type"]);
          $cmd->setSubType(jeeda::$_DATA[$key]["subtype"]);
        }
        else
        {
          log::add('jeeda','warning', 'Missing meta data for ' . $key);
          $cmd->setType('info');
          $cmd->setSubType("string");
          $cmd->setIsVisible(0);
          $cmd->setIsHistorized(0);
        }  
        $cmd->save();
        // Mise à jour de la valeur
        $jeeda->checkAndUpdateCmd($key, $value);
      }

      /**
       * Creation des actions
       */
      foreach($vehiculesJson[$i]->{"methods"} as $key=>$value){
        $cmd = $jeeda->getCmd(null, $value);
        if (!is_object($cmd)) {
          $cmd = new jeedaCmd();
          $cmd->setName($value);
        }
        $cmd->setEqLogic_id($jeeda->getId());
        $cmd->setLogicalId($value);
        $cmd->setType('action');
        $cmd->setSubType('other');
        $cmd->setIsVisible(0);
    
        $cmd->save();
        
      }
      /**
       * Creation des fonctionnalités
       */
      foreach($vehiculesJson[$i]->{"features"} as $key=>$value){
        log::add('jeeda','debug', 'Add configuration '.$key);
        $jeeda->setConfiguration($key,$value);
      }
      $jeeda->save();
      
    } 
  }
  /*
  * Fonction qui crée les véhicules du compte
  */
  public static function createVehicule(){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    /* Interrogation des API SKoda */   
    $strCmd = "python3 " . dirname(__FILE__) . "/../../resources/fetchVehicule.py " . config::byKey('login', 'jeeda') . " " . config::byKey('pwd', 'jeeda');
    $cmdPi = escapeshellcmd($strCmd);
    log::add('jeeda','debug', 'Execute ' . $cmdPi);
    exec($cmdPi, $output, $retourCode);

    if($retourCode != 0){
      log::add('jeeda','error', 'Erreur appel ' . __CLASS__ . ':' . $retourCode);
      for ($i=0;$i<count($output);$i++){
        log::add('jeeda','error', $output[$i]);
      }
      throw new Exception("Jeeda error " .$output[0],  $retourCode);
    }

    // recuperation de la ligne des data
    $outputJSON = $output[0];
    log::add('jeeda', 'debug','Recieve ' . $outputJSON);
    $vehiculesJson = json_decode($outputJSON);
    jeeda::createVehiculeFromJSON($vehiculesJson);
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
  }


  /*
  * Fonction exécutée automatiquement toutes les minutes par Jeedom
  */
  /*public static function cron() {
    log::add('jeeda','info','cron');
      foreach (self::byType('jeeda', true) as $vehicules) { //parcours tous les équipements actifs du plugin vdm
        $cmd = $vehicules->getCmd(null, 'refresh'); //retourne la commande "refresh" si elle existe
        if (!is_object($cmd)) { //Si la commande n'existe pas
        continue; //continue la boucle
      }
      $cmd->execCmd(); //la commande existe on la lance
    }
  }*/
  

  /*
  * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
  public static function cron5() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
  */
  /*public static function cron10() {
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    try{
      jeeda::createVehicule();
    }
    catch (Exception $e) {
      log::add('jeeda','error', __CLASS__ . '.' . __FUNCTION__ . ' erreur recuperation donnée : ' . $e->getCode() . ' ' . $e->getMessage());

    }
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
  }*/
  

  /*
  * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
  public static function cron15() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
  */
 /* public static function cron30() {
    log::add('jeeda', 'debug','start '. __FUNCTION__);
    jeeda::createVehicule();
    log::add('jeeda', 'debug','exit '. __FUNCTION__);
  }*/
  

  /*
  * Fonction exécutée automatiquement toutes les heures par Jeedom
  public static function cronHourly() {}
  */

  /*
  * Fonction exécutée automatiquement tous les jours par Jeedom
  public static function cronDaily() {}
  */
  
  /*
  * Permet de déclencher une action avant modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function preConfig_param3( $value ) {
    // do some checks or modify on $value
    return $value;
  }
  */

  /*
  * Permet de déclencher une action après modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function postConfig_param3($value) {
    // no return value
  }
  */

  /*
   * Permet d'indiquer des éléments supplémentaires à remonter dans les informations de configuration
   * lors de la création semi-automatique d'un post sur le forum community
   public static function getConfigForCommunity() {
      return "les infos essentiel de mon plugin";
   }
   */

  /*     * *********************Méthodes d'instance************************* */

  /*
  * Fonction qui active la charge ou non pour un vehicule
  */
  public function setChargingMode($actualMode){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    log::add('jeeda','debug', $actualMode);
    /* Interrogation des API SKoda */   
    $strCmd = "python3 " . dirname(__FILE__) . "/../../resources/setChargingVehicule.py " . config::byKey('login', 'jeeda') . " " . config::byKey('pwd', 'jeeda') . " " . $this->getLogicalId() . " " . (1-$actualMode);
    $cmdPi = escapeshellcmd($strCmd);
    log::add('jeeda','debug', 'Execute ' . $cmdPi);
    //exec($cmdPi, $output, $retourCode);

    // recuperation de la ligne des data
    /*$outputJSON = $output[0];
    log::add('jeeda', 'debug','Recieve ' . $outputJSON);
    $vehiculesJson = json_decode($outputJSON);
    */
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
  }

  // Fonction exécutée automatiquement avant la création de l'équipement
  public function preInsert() {
  }

  // Fonction exécutée automatiquement après la création de l'équipement
  public function postInsert() {
  }

  // Fonction exécutée automatiquement avant la mise à jour de l'équipement
  public function preUpdate() {
  }

  // Fonction exécutée automatiquement après la mise à jour de l'équipement
  public function postUpdate() {
  }

  // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
  public function preSave() {
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function postSave() {
  }

  // Fonction exécutée automatiquement avant la suppression de l'équipement
  public function preRemove() {
  }

  // Fonction exécutée automatiquement après la suppression de l'équipement
  public function postRemove() {
  }

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration des équipements
  * Exemple avec le champ "Mot de passe" (password)
  public function decrypt() {
    $this->setConfiguration('password', utils::decrypt($this->getConfiguration('password')));
  }
  public function encrypt() {
    $this->setConfiguration('password', utils::encrypt($this->getConfiguration('password')));
  }
  */

  public function getConfiguration($_key = '', $_default = ''){
    $retour = parent::getConfiguration($_key,$_default);
    if ($retour == '')
      $retour = $_default;
    return $retour;
  }

  public function getDriveStatisque($debut,$fin){
    $cmdDistance = $this->getCmd(null, 'distance');
    if (is_object($cmdDistance)){
      return json_encode(history::getStatistique($cmdDistance->getId(),$debut,$fin));
    }
    return "";
  }
  public function getChargingInfo($debut,$fin){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    $cmdCharging = $this->getCmd(null, 'charging');
    $cmdBatteryLevel = $this->getCmd(null, 'battery_level');
    // detail du trajet basé sur l'evolution de la distance
    $values = history::all($cmdCharging->getId(),$debut,$fin);
    $cmdBatteryCapacity = $this->getCmd(null, 'battery_capacity');
    $cmdChargingPower = $this->getCmd(null, 'charging_power');
    $capa = is_object($cmdBatteryCapacity) ? $cmdBatteryCapacity->execCmd() : 0;
    $statGeneral = array();
    $statDetaillee = array();
    $statGeneral['totKW'] = 0;
    $statGeneral['duree'] = 0;
    $statGeneral['avgChargingPower'] = 0;
    $nbCharge = 0;
    $initLevelBattery = 0;
    $initDate = $debut;
    $start = False;
    foreach ($values as $value) {
      $batteryLevel = history::byCmdIdAtDatetime($cmdBatteryLevel->getId(),$value->getDatetime());
      $chargingPower = history::byCmdIdAtDatetime($cmdChargingPower->getId(),$value->getDatetime());
      $date = $value->getDatetime();
      // debut d'une charge
      if ($value->getValue() == 1){
        $nbCharge ++;
        $initLevelBattery = $batteryLevel->getValue();
        $start = True;
        $initDate = $date;
        $statGeneral['avgChargingPower'] = $statGeneral['avgChargingPower'] + $chargingPower->getValue();
        $statDetaillee[$date]['avgChargingPower']= $chargingPower->getValue();
      }
      else{
        // Detection que nous avons détecté un debut de charge sur la période
        if ($start ==  True){
          $statGeneral['totKW'] = $statGeneral['totKW'] + ($batteryLevel->getValue() - $initLevelBattery) * $capa / 100;
          $statDetaillee[$initDate]['totKW'] = ($batteryLevel->getValue() - $initLevelBattery) * $capa / 100;
          $statGeneral['duree'] = $statGeneral['duree'] + strval(floor((strtotime($date) - strtotime($initDate))/60));
          $statDetaillee[$initDate]['duree'] = strval(floor((strtotime($date) - strtotime($initDate))/60));
        }
      }
    }
    $statGeneral['nbCharge'] = $nbCharge;
    $statGeneral['totKW'] = number_format($statGeneral['totKW'], 2);
    if(0!= $nbCharge)
      $statGeneral['avgChargingPower'] = number_format($statGeneral['avgChargingPower'] / $nbCharge, 2);
    else  
      $statGeneral['avgChargingPower'] = 0;
    
    krsort($statDetaillee);
    $stat['general'] = $statGeneral;
    $stat['detaillee'] = $statDetaillee;
      

    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
    return json_encode($stat);
  }
  public function showTravel($debut,$fin){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    $dateTravel = array();
    $trajet=null;
    $cmdDistance = $this->getCmd(null, 'distance');
    $trajet[0]['name'] = 'km parcouru';
    $trajet[0]['data'] = array();
    $cmdBatteryLevel = $this->getCmd(null, 'battery_level');
    $trajet[1]['name'] = 'Niveau batterie';
    $trajet[1]['data'] = array();
    $cmdElectricRange = $this->getCmd(null, 'electric_range');
    $trajet[2]['name'] = 'Autonomie perdue';
    $trajet[2]['data'] = array();
    $trajet[3]['name'] = 'Vitesse';
    $trajet[3]['data'] = array();
    // detail du trajet basé sur l'evolution de la distance
    $values = history::all($cmdDistance->getId(),$debut,$fin);
    $index = 0;
    $previousValue = array();
    $initValue = array();
    $nbVal = count($values);
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__. ' nb date : ' . $nbVal);
    foreach ($values as $value) {
      array_push($dateTravel, $value->getDatetime());
      // recuperation des informations pour chaque evolution de la distance
      $batteryLevel = history::byCmdIdAtDatetime($cmdBatteryLevel->getId(),$value->getDatetime());
      $electricRange = history::byCmdIdAtDatetime($cmdElectricRange->getId(),$value->getDatetime());
      if ($index == 0){
        $initValue['distance'] = (float) $value->getValue();
        $initValue['battery'] = (float) $batteryLevel->getValue();
        $initValue['electric_range'] = (float) $electricRange->getValue();
        $initValue['heure'] = $value->getDatetime();
        $previousValue['distance'] = (float) $value->getValue();
        $previousValue['battery'] = (float) $batteryLevel->getValue();
        $previousValue['electric_range'] = (float) $electricRange->getValue();
        $previousValue['heure'] = $value->getDatetime();
        $index++;
      }else{
        array_push($trajet[0]['data'], (float) $value->getValue() - $initValue['distance']);
        $distance = (float) $value->getValue() - $previousValue['distance'];
        $previousValue['distance'] = (float) $value->getValue();
        array_push($trajet[1]['data'], (float) $batteryLevel->getValue());
        $previousValue['battery'] = (float) $batteryLevel->getValue();
        array_push($trajet[2]['data'], $initValue['electric_range'] - (float) $electricRange->getValue());
        $previousValue['electric_range'] = (float) $electricRange->getValue();

        $duree = strval(floor((strtotime($value->getDatetime()) - strtotime($previousValue['heure']))/60));
        log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__ . 'previous : ' . $previousValue['heure'] . ' current '.$value->getDatetime());
        if ($duree != 0)
          array_push($trajet[3]['data'], $distance / (floor($duree/60)+($duree%60)/60));
        else
          array_push($trajet[3]['data'], null);
        $previousValue['heure'] = $value->getDatetime();
      }
    }
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
    $showTravel['date'] = $dateTravel;
    $showTravel['data'] = $trajet;
    return $showTravel;
  }
  public function showTravel1($debut,$fin){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    //$trajet = array();
    $trajet=null;
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' debut ' . $debut);
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' fin ' . $fin);
    
    // Recupere les changements d'état de deplacement du vehicule
    $cmdDistance = $this->getCmd(null, 'distance');
    $cmdBatteryLevel = $this->getCmd(null, 'battery_level');
    $cmdElectricRange = $this->getCmd(null, 'electric_range');
    $trajet = $this->dumpHistory($cmdDistance, "distance", $trajet, $debut, $fin);
    $trajet = $this->dumpHistory($cmdBatteryLevel, "batteryLevel", $trajet, $debut, $fin);
    $trajet = $this->dumpHistory($cmdElectricRange, "electricRange", $trajet, $debut, $fin);

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__ . ' Nb etapes trajet ' . count($trajet));
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
    return $trajet;
  }

  private function  dumpHistory($cmd, $key, $trajet, $debut, $fin){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    $values = history::all($cmd->getId(),$debut,$fin);
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__ . ' dump ' . count($values). ' items');
    $index = count($trajet);
    $trajet[$index] = array();
    $valuesSerie = array();
    foreach ($values as $value) {
      array_push($valuesSerie, (float)$value->getValue());
    }
    $trajet[$index]['name'] = $key;
    $trajet[$index]['data'] = $valuesSerie;
    return $trajet;
  }

  public function getThermicTravelData($debut,$fin){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    /*$debut = date("Y-m-d H:i:s", strtotime("-7 day"));
    $fin = date("Y-m-d H:i:s", strtotime("Now"));*/
    $trajets = array();
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' debut ' . $debut);
        
    // Recupere les changements d'état de deplacement du vehicule
    $cmd = $this->getCmd(null, 'vehicle_moving');
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' id ' . $cmd->getId());
    $cmdDistance = $this->getCmd(null, 'distance');
    $cmdRange = $this->getCmd(null, 'combustion_range');
    $cmdCapacity = $this->getCmd(null, 'fuel_level');
    $values = history::all($cmd->getId(),$debut,$fin);
    $dateDebut = null;
    $header = ['date','Durée','distance','Vitesse Moy.','Impact autonomie','Efficience','l/100 km','Autonomie'];
    $key = ['duree','distance','vitesseMoy','kwConso','kmConso','delta','consoMoy','WLTP'];
    foreach ($values as $value) {
      $date = $value->getDatetime();
      log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' manage history '.$date);
      $distance = history::byCmdIdAtDatetime($cmdDistance->getId(),$value->getDatetime());
      $fuelLevel = history::byCmdIdAtDatetime($cmdCapacity->getId(),$value->getDatetime());
      $Range = history::byCmdIdAtDatetime($cmdRange->getId(),$value->getDatetime());
      // detection debut trajet
      if ($value->getValue() == 1){
        log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' detected begining ');
        $dateDebut = $date;
        $trajets[$dateDebut]['distanceDebut'] = $distance->getValue();
        $trajets[$dateDebut]['LevelDebut'] = $fuelLevel->getValue();
        $trajets[$dateDebut]['RangeDebut'] = $Range->getValue();
        $trajets[$dateDebut]['dateDebut'] = $dateDebut;
      } else {
        log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' detected end ');
        // prise en compte d'une premiere ligne historique qui correspond à la fin du trajet
        if (null!=$dateDebut){
          $trajets[$dateDebut]['distanceFin'] = $distance->getValue();        
          $trajets[$dateDebut]['LevelFin'] = $fuelLevel->getValue();
          $trajets[$dateDebut]['RangeFin'] = $Range->getValue();
          $trajets[$dateDebut]['dateFin'] = $date;
          $trajets[$dateDebut]['distance'] = $trajets[$dateDebut]['distanceFin'] - $trajets[$dateDebut]['distanceDebut'];
          $trajets[$dateDebut]['duree'] = strval(floor((strtotime($date) - strtotime($dateDebut))/60));
          $trajets[$dateDebut]['kmConso'] = $trajets[$dateDebut]['RangeDebut'] - $trajets[$dateDebut]['RangeFin'];
          if ($trajets[$dateDebut]['distance'] == 0){
            $trajets[$dateDebut]['consoMoy'] = 0;
            $trajets[$dateDebut]['delta'] = "";
          }else{
            $trajets[$dateDebut]['consoMoy'] = $trajets[$dateDebut]['distance'] / $trajets[$dateDebut]['distance'] * 100;
            $trajets[$dateDebut]['delta'] = ($trajets[$dateDebut]['kmConso'] / $trajets[$dateDebut]['distance'])*100;
          }
          $trajets[$dateDebut]['vitesseMoy'] = $trajets[$dateDebut]['distance'] / (floor($trajets[$dateDebut]['duree']/60)+($trajets[$dateDebut]['duree']%60)/60);         
          if ($trajets[$dateDebut]['kmConso'] == 0){
            $trajets[$dateDebut]['WLTP'] = "";
          } else{
            $trajets[$dateDebut]['WLTP'] = round($trajets[$dateDebut]['distance']  / $trajets[$dateDebut]['kmConso']);
          }
        }
      }
    }
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' end manage data ');    
    krsort($trajets);
    $retour['data'] = $trajets;
    $retour['header'] = $header;
    $retour['key'] = $key;
    $retour = json_encode($retour);
    log::add('jeeda','debug', 'Data travels' . $retour);
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
    return $retour;
    
  }
  public function getElectricTravelData($debut,$fin){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    /*$debut = date("Y-m-d H:i:s", strtotime("-7 day"));
    $fin = date("Y-m-d H:i:s", strtotime("Now"));*/
    $trajets = array();
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' debut ' . $debut);
        
    // Recupere les changements d'état de deplacement du vehicule
    $cmd = $this->getCmd(null, 'vehicle_moving');
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' id ' . $cmd->getId());
    $cmdDistance = $this->getCmd(null, 'distance');
    $cmdBatteryLevel = $this->getCmd(null, 'battery_level');
    $cmdBatteryCapacity = $this->getCmd(null, 'battery_capacity');
    $cmdElectricRange = $this->getCmd(null, 'electric_range');
    $values = history::all($cmd->getId(),$debut,$fin);
    $dateDebut = null;
    $capa = is_object($cmdBatteryCapacity) ? $cmdBatteryCapacity->execCmd() : 0;
    $header = ['date','Durée','distance','Vitesse Moy.','kW Conso','Impact autonomie','Efficience','kW/100 km','Autonomie WLTP'];
    $key = ['duree','distance','vitesseMoy','kwConso','kmConso','delta','consoMoy','WLTP'];
    foreach ($values as $value) {
      $date = $value->getDatetime();
      log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' manage history '.$date);
      $distance = history::byCmdIdAtDatetime($cmdDistance->getId(),$value->getDatetime());
      $batteryLevel = history::byCmdIdAtDatetime($cmdBatteryLevel->getId(),$value->getDatetime());
      $electricRange = history::byCmdIdAtDatetime($cmdElectricRange->getId(),$value->getDatetime());
      // detection debut trajet
      if ($value->getValue() == 1){
        log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' detected begining ');
        $dateDebut = $date;
        $trajets[$dateDebut]['distanceDebut'] = $distance->getValue();
        $trajets[$dateDebut]['batteryLevelDebut'] = $batteryLevel->getValue();
        $trajets[$dateDebut]['electricRangeDebut'] = $electricRange->getValue();
        $trajets[$dateDebut]['dateDebut'] = $dateDebut;
      } else {
        log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' detected end ');
        // prise en compte d'une premiere ligne historique qui correspond à la fin du trajet
        if (null!=$dateDebut){
          $trajets[$dateDebut]['distanceFin'] = $distance->getValue();        
          $trajets[$dateDebut]['batteryLevelFin'] = $batteryLevel->getValue();
          $trajets[$dateDebut]['electricRangeFin'] = $electricRange->getValue();
          $trajets[$dateDebut]['dateFin'] = $date;
          $trajets[$dateDebut]['distance'] = $trajets[$dateDebut]['distanceFin'] - $trajets[$dateDebut]['distanceDebut'];
          $trajets[$dateDebut]['duree'] = strval(floor((strtotime($date) - strtotime($dateDebut))/60));
          $trajets[$dateDebut]['kmConso'] = $trajets[$dateDebut]['electricRangeDebut'] - $trajets[$dateDebut]['electricRangeFin'];
          $trajets[$dateDebut]['kwConso'] = ($trajets[$dateDebut]['batteryLevelDebut'] - $trajets[$dateDebut]['batteryLevelFin'])/100*$capa;
          if ($trajets[$dateDebut]['distance'] == 0){
            $trajets[$dateDebut]['consoMoy'] = 0;
            $trajets[$dateDebut]['delta'] = "";
          }else{
            $trajets[$dateDebut]['consoMoy'] = $trajets[$dateDebut]['kwConso'] / $trajets[$dateDebut]['distance'] * 100;
            //$trajets[$dateDebut]['delta'] = ($trajets[$dateDebut]['kmConso'] - $trajets[$dateDebut]['distance'])/$trajets[$dateDebut]['distance']*100;
            $trajets[$dateDebut]['delta'] = ($trajets[$dateDebut]['kmConso'] / $trajets[$dateDebut]['distance'])*100;
          }
          $trajets[$dateDebut]['vitesseMoy'] = $trajets[$dateDebut]['distance'] / (floor($trajets[$dateDebut]['duree']/60)+($trajets[$dateDebut]['duree']%60)/60);         
          if ($trajets[$dateDebut]['kwConso'] == 0){
            $trajets[$dateDebut]['WLTP'] = "";
          } else{
            $trajets[$dateDebut]['WLTP'] = round($trajets[$dateDebut]['distance']  / abs($trajets[$dateDebut]['kwConso'])*$capa);
          }
        }
      }
    }
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.' end manage data ');    
    krsort($trajets);
    $retour['data'] = $trajets;
    $retour['header'] = $header;
    $retour['key'] = $key;
    $retour = json_encode($retour);
    log::add('jeeda','debug', 'Data travels' . $retour);
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);

    return $retour;
  }
  public function getCarData(){
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    $a = array();
		$cmd = $this->getCmd(null, 'model_image_small');
    $a['display_image'] = $this->getConfiguration("is_model_image_small_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['image'] = is_object($cmd) ? $cmd->execCmd() : '';

    $cmd = $this->getCmd(null, 'distance');
    $a['display_distance'] = $this->getConfiguration("is_distance_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['distance'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['distance_id'] = is_object($cmd) ? $cmd->getId() : '';

    $cmd = $this->getCmd(null, 'model');
    $a['display_model'] = $this->getConfiguration("is_model_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['model'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['model_id'] = is_object($cmd) ? $cmd->getId() : '';

    $cmd = $this->getCmd(null, 'engine_type');
    $a['display_engine_type'] = $this->getConfiguration("is_engine_type_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['engine_type'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['engine_type_id'] = is_object($cmd) ? $cmd->getId() : '';

    $cmd = $this->getCmd(null, 'model_year');
    $a['display_model_year'] = $this->getConfiguration("is_model_year_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['model_year'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['model_year_id'] = is_object($cmd) ? $cmd->getId() : '';

    $a['has_moteur_thermique'] = $this->getConfiguration("is_combustion_range_supported","0");
    $cmd = $this->getCmd(null, 'combustion_range');
    $a['display_combustion_range'] = $this->getConfiguration("is_combustion_range_supported","0") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['combustion_range'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['combustion_range_id'] = is_object($cmd) ? $cmd->getId() : '';
    $cmd = $this->getCmd(null, 'fuel_level');
    $a['display_fuel_level'] = $this->getConfiguration("is_fuel_level_supported","0") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['fuel_level'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['fuel_level_id'] = is_object($cmd) ? $cmd->getId() : '';
    

    $a['has_moteur_electrique'] = $this->getConfiguration("is_electric_range_supported","0");
    $cmd = $this->getCmd(null, 'electric_range');
    $a['display_electrique_range'] = $this->getConfiguration("is_electric_range_supported","0") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['electrique_range'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['electrique_range_id'] = is_object($cmd) ? $cmd->getId() : '';

    $cmd = $this->getCmd(null, 'battery_level');
    $a['display_battery_level'] = $this->getConfiguration("is_battery_level_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['battery_level'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['battery_level_id'] = is_object($cmd) ? $cmd->getId() : '';

    $cmd = $this->getCmd(null, 'battery_capacity');
    $a['display_battery_capacity'] = $this->getConfiguration("is_battery_capacity_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['battery_capacity'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['battery_capacity_id'] = is_object($cmd) ? $cmd->getId() : '';

    $a['has_recharge'] = $this->getConfiguration("is_external_power_supported","0");

    $cmd = $this->getCmd(null, 'charging_cable_connected');
    $a['display_charging_cable_connected'] = $this->getConfiguration("is_charging_cable_connected_supported","0") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['charging_cable_connected'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['charging_cable_connected_id'] = is_object($cmd) ? $cmd->getId() : '';

    $cmd = $this->getCmd(null, 'charging');
    $a['display_charging'] = $this->getConfiguration("is_charging_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['charging'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['charging_id'] = is_object($cmd) ? $cmd->getId() : '';

    $cmd = $this->getCmd(null, 'charge_rate');
    $a['display_charge_rate'] = $this->getConfiguration("is_charge_rate_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['charge_rate'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['charge_rate_id'] = is_object($cmd) ? $cmd->getId() : '';

    $cmd = $this->getCmd(null, 'charging_power');
    $a['display_charging_power'] = $this->getConfiguration("is_charging_power_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['charging_power'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['charging_power_id'] = is_object($cmd) ? $cmd->getId() : '';

    $cmd = $this->getCmd(null, 'min_charge_level');
    $a['display_min_charge_level'] = $this->getConfiguration("is_min_charge_level_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['min_charge_level'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['min_charge_level_id'] = is_object($cmd) ? $cmd->getId() : '';

    $cmd = $this->getCmd(null, 'charging_time_left');
    $a['display_charging_time_left'] = $this->getConfiguration("is_charging_time_left_supported") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['charging_time_left'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['charging_time_left_id'] = is_object($cmd) ? $cmd->getId() : '';

    $a['has_entretien'] = $this->getConfiguration("is_service_inspection_distance_supported","0");
    $cmd = $this->getCmd(null, 'service_inspection_distance');
    $a['display_service_inspection_distance'] = $this->getConfiguration("is_service_inspection_distance_supported","0") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['service_inspection_distance'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['service_inspection_distance_id'] = is_object($cmd) ? $cmd->getId() : '';

    $a['has_clim'] = $this->getConfiguration("is_climatisation_supported","0");
    $cmd = $this->getCmd(null, 'climatisation_target_temperature');
    $a['display_consigne'] = $this->getConfiguration("is_climatisation_target_temperature_supported","0") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['consigne'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['consigne_id'] = is_object($cmd) ? $cmd->getId() : '';
    
    $cmd = $this->getCmd(null, 'climatisation_time_left');
    $a['display_climatisation_time_left'] = $this->getConfiguration("is_climatisation_time_left_supported","0") && is_object($cmd) ? $cmd->getIsVisible() : 0;
		$a['climatisation_time_left'] = is_object($cmd) ? $cmd->execCmd() : '';
    $a['climatisation_time_left_id'] = is_object($cmd) ? $cmd->getId() : '';


    $retour = json_encode($a);
    log::add('jeeda','debug', 'Data car ' . $retour);
    log::add('jeeda','debug', 'Sortie ' . __CLASS__ . '.' . __FUNCTION__);
    return $retour;
  }
  /*
  * Permet de modifier l'affichage du widget (également utilisable par les commandes)
  */
 public function toHtml($_version = 'dashboard') {
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    /** @TODO Pour le dev, à supprimer en prod */
    $this->emptyCacheWidget();

    
    $replace = $this->preToHtml($_version);
		log::add('jeeda','debug',$replace);
		//if (!is_array($replace)) {
		//	return $replace;
		//}
    $version = jeedom::versionAlias($_version);
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get small image');
    $cmd = $this->getCmd(null, 'model_image_small');
    $cmd->setTemplate($version,'image');
    $cmd->save();
    $replace['#imageSmall#'] = is_object($cmd) ? $cmd->toHtml($_version, '') : '';
    
    
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get distance');
    $cmd = $this->getCmd(null, 'distance');
    $cmd->setTemplate($version,'distance');
    $cmd->save();
    $replace['#distance#'] = is_object($cmd) ? $cmd->toHtml($_version, '') : '';

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get engine type');
    $cmd = $this->getCmd(null, 'engine_type');
    $cmd->setTemplate($version,'label');
    $cmd->save();
    $typeMoteur = $cmd->execCmd();
    $label['#title#'] = "Type de moteur";
    $replace['#engine_type#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get model');
    $cmd = $this->getCmd(null, 'model');
		$cmd->setTemplate($version,'label');
    $cmd->save();    
    $label['#title#'] = "Modèle";
    $replace['#modele#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get engine capacity');
    $cmd = $this->getCmd(null, 'engine_power');
		$cmd->setTemplate($version,'label');
    if ($typeMoteur=="iV"){
      $cmd->setUnite("kw");
    }
    else{
      $cmd->setUnite("CV");
    }
    $cmd->save();    
    $label['#title#'] = "Puissance";
    $replace['#engine_power#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');


    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get model year');
    $cmd = $this->getCmd(null, 'model_year');
    $cmd->setTemplate($version,'label');
    $cmd->save();    
    $label['#title#'] = "Année";
    $replace['#model_year#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
		
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get thermique range');
    $replace['#thermique_range#'] = '';
    if ($this->getConfiguration("is_combustion_range_supported","0")!=0){
      $cmd = $this->getCmd(null, 'combustion_range');
      $cmd->setUnite("Km");
      $cmd->setTemplate($version,'label');
      $cmd->save();    
      $label['#title#'] = "Autonomie";
      $replace['#thermique_range#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get fuel capacity');
    $replace['#reservoir#']='';
    if ($this->getConfiguration("is_fuel_level_supported","0")!=0){
      $cmd = $this->getCmd(null, 'fuel_level');
      $cmd->setTemplate($version,'label');
      $cmd->setUnite("l");
      $cmd->save();    
      $label['#title#'] = "Reservoir";
      $replace['#reservoir#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get drive');
    $replace['#drive#']='';
    if ($this->getConfiguration("is_primary_drive_supported","0")!=0){
      $cmd = $this->getCmd(null, 'primary_drive');
      $cmd->setTemplate($version,'label');
      $cmd->save();    
      $label['#title#'] = "Nombre de rapport";
      $replace['#drive#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }
    

    $replace['#is_thermique_range_id#'] = $this->getConfiguration("is_combustion_range_supported","0") + $this->getConfiguration("is_fuel_level_supported","0")+
    $this->getConfiguration("is_primary_drive_supported","0");
    

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get electrique range');
    $replace['#electric_range#']='';
    if ($this->getConfiguration("is_electric_range_supported","0")!=0){
      $cmd = $this->getCmd(null, 'electric_range');
      $cmd->setTemplate($version,'label');
      $cmd->setUnite("Km");
      $cmd->save();    
      $label['#title#'] = "Autonomie";
      $replace['#electric_range#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }
    
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get battery level');
    $replace['#battery_level#'] = '';
    //$replace['#is_battery_level_supported#'] = $this->getConfiguration("is_battery_level_supported","0");
    if ($this->getConfiguration("is_battery_level_supported","0") != "0"){
      $cmd = $this->getCmd(null, 'battery_level');
      log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get battery capacity');
      if ($this->getConfiguration("is_battery_capacity_supported","0") != "0"){
        log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::has battery capacity');
        $cmd->setTemplate($version,'batteryCapa');
        $cmdCapa = $this->getCmd(null, 'battery_capacity');
        $label['#capa#'] = $cmdCapa->execCmd();  
      }
      else {
        log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::has no battery capacity');
        $cmd->setTemplate($version,'label');
      }
      $cmd->setUnite("%");
      $cmd->save();    
      $label['#title#'] = "Niveau batterie";
      $replace['#battery_level#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get charge rate');
    $replace['#gain_charge#'] = '';
    if ($this->getConfiguration("is_charge_rate_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'charge_rate');
      $cmd->setTemplate($version,'label');
      $cmd->setUnite("kw/h");
      $cmd->save();    
      $label['#title#'] = "Gain de charge";
      $replace['#gain_charge#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get charging power');
    $replace['#charging_power#']='';
    if ($this->getConfiguration("is_charging_power_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'charging_power');
      $cmd->setTemplate($version,'label');
      $cmd->setUnite("w");
      $cmd->save();    
      $label['#title#'] = "Puissance de charge";
      $replace['#charging_power#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get charging time left');
    $replace['#charging_time_left#'] = '';
    if ($this->getConfiguration("is_charging_time_left_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'charging_time_left');
      $cmd->setTemplate($version,'time');
      $cmd->save();    
      $label['#title#'] = "Temps de charge restant";
      $replace['#charging_time_left#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get min charge level');
    $replace['#min_charge_level#'] = '';
    if ($this->getConfiguration("is_min_charge_level_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'min_charge_level');
      $cmd->setTemplate($version,'label');
      $cmd->setUnite("%");
      $cmd->save();    
      $label['#title#'] = "Limite de charge";
      $replace['#min_charge_level#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get cable connected');
    $replace['#charging_cable_connected#']='';
    if ($this->getConfiguration("is_charging_cable_connected_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'charging_cable_connected');
      $cmd->setTemplate($version,'image');
      $cmd->save();    
      if (1==(is_object($cmd) ? $cmd->execCmd() : 0)){
        $label['#title#'] = "Cable connecté";
        $label['#color#'] = "rgb(0,208,0)";
      }
      else {
        $label['#title#'] = "Cable non connecté";
        $label['#color#'] = "rgb(208,208,208)";
      }
      $label['#icon#'] = "fa-plug";
      $replace['#charging_cable_connected#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get cable connected');
    $replace['#charging#']='';
    if ($this->getConfiguration("is_charging_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'charging');
      $cmd->setTemplate($version,'image');
      $cmd->save();    
      if (1==(is_object($cmd) ? $cmd->execCmd() : 0)){
        $label['#title#'] = "En charge";
        $label['#color#'] = "rgb(208,0,0)";
      }
      else {
        $label['#title#'] = "Pas de charge";
        $label['#color#'] = "rgb(208,208,208)";
      }
      $label['#icon#'] = "kiko-electricity";
      $replace['#charging#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    

    $replace['#is_electric_bloc_id#'] = $this->getConfiguration("is_electric_range_supported","0")+$this->getConfiguration("is_battery_level_supported","0")+
      $this->getConfiguration("is_charge_rate_supported","0") + $this->getConfiguration("is_charging_power_supported","0")+
      $this->getConfiguration("is_charging_time_left_supported",0)+$this->getConfiguration("is_min_charge_level_supported",0)+
      $this->getConfiguration("is_charging_cable_connected_supported",0)+$this->getConfiguration("is_charging_supported",0);


    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get door locked');
    $replace['#door_locked#']='';
    if ($this->getConfiguration("is_door_locked_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'door_locked');
      $cmd->setTemplate($version,'image');
      $cmd->save();    
      if (1==(is_object($cmd) ? $cmd->execCmd() : 0)){
        $label['#title#'] = "Portes fermées";
        $label['#color#'] = "rgb(108,108,108)";
        $label['#icon#'] = "jeedom-lock-ferme";
      }
      else {
        $label['#title#'] = "Portes ouvertes";
        $label['#color#'] = "rgb(0,208,0)";
        $label['#icon#'] = "jeedom-lock-ouvert";
      }
      $replace['#door_locked#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get window locked');
    $replace['#windows_closed#']='';
    if ($this->getConfiguration("is_windows_closed_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'windows_closed');
      $cmd->setTemplate($version,'image');
      $cmd->save();    
      if (1==(is_object($cmd) ? $cmd->execCmd() : 0)){
        $label['#title#'] = "Fenêtres fermées";
        $label['#color#'] = "rgb(108,108,108)";
        $label['#icon#'] = "jeedom-fenetre-ferme";
      }
      else {
        $label['#title#'] = "Fenêtres ouvertes";
        $label['#color#'] = "rgb(0,208,0)";
        $label['#icon#'] = "jeedom-fenetre-ouverte";
      }
      $replace['#windows_closed#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get hood locked');
    $replace['#hood_closed#']='';
    if ($this->getConfiguration("is_hood_closed_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'hood_closed');
      $cmd->setTemplate($version,'image');
      $cmd->save();    
      if (1==(is_object($cmd) ? $cmd->execCmd() : 0)){
        $label['#title#'] = "Capot fermé";
        $label['#color#'] = "rgb(108,108,108)";
      }
      else {
        $label['#title#'] = "Capot ouvert";
        $label['#color#'] = "rgb(0,208,0)";
      }
      $label['#icon#'] = "kiko-car";
      $replace['#hood_closed#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    /*log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get engine power');
    $cmd = $this->getCmd(null, 'engine_power');
		$replace['#engine_power#'] = is_object($cmd) ? $cmd->execCmd() : '';
    $replace['#engine_power_ch#'] = is_object($cmd) ? round($cmd->execCmd()*1.36) : '';
    
    */ 
  
    /** Information sur la partie climatisation */
    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get climatisation');
    $replace['#target_temp#'] = '';
    if ($this->getConfiguration("is_climatisation_target_temperature_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'climatisation_target_temperature');
      $cmd->setTemplate($version,'label');
      $cmd->setUnite("°");
      $cmd->save();    
      $label['#title#'] = "Consigne";
      $replace['#target_temp#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get climatisation time left');
    $replace['#climatisation_time_left#'] = '';
    if ($this->getConfiguration("is_climatisation_time_left_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'climatisation_time_left');
      $cmd->setTemplate($version,'time');
      $cmd->save(); 
      $label['#title#'] = "Temps restant";   
      $replace['#climatisation_time_left#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get climatisation');
    $replace['#climatisation_status#']='';
    if ($this->getConfiguration("is_climatisation_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'electric_climatisation_attributes_status');
      $cmd->setTemplate($version,'clim');
      $cmd->save();    
      if ("On"==(is_object($cmd) ? $cmd->execCmd() : 0)){
        $label['#title#'] = "Climatisation allumée";
        $label['#color#'] = "rgb(0,129,150)";
      }
      else {
        $label['#title#'] = "Climatisation etteinte";
        $label['#color#'] = "rgb(208,208,208)";
      }
      $label['#icon#'] = "jeedomapp-weather";
      $replace['#climatisation_status#'] = template_replace($label, is_object($cmd) ? $cmd->toHtml($_version, '') : '');
    }

    
    $replace['#climatisation_bloc_id#'] = $this->getConfiguration("is_climatisation_target_temperature_supported","0")+
      $this->getConfiguration("is_climatisation_time_left_supported",0) + $this->getConfiguration("is_climatisation_supported",0);
    
 
    /*   log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get climatisation');
    $replace['#is_climatisation_supported#'] = $this->getConfiguration("is_climatisation_supported","0");
    if ($this->getConfiguration("is_climatisation_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'electric_climatisation_attributes_status');
      $replace['#climatisation_status#'] = is_object($cmd) ? $cmd->execCmd() : '';
      $replace['#uid_climatisation_status#'] = is_object($cmd) ? $cmd->getId() : '';
      $replace['#climatisation_status_visible#'] = is_object($cmd) ? $cmd->getIsVisible() : 0;
    }

    log::add('jeeda','debug', __CLASS__ . '.' . __FUNCTION__.'::Get clim target temp');
    $replace['#is_climatisation_target_temperature_supported#'] = $this->getConfiguration("is_climatisation_target_temperature_supported","0");
    if ($this->getConfiguration("is_climatisation_target_temperature_supported",0) != "0"){
      $cmd = $this->getCmd(null, 'climatisation_target_temperature');
      $replace['#climatisation_target_temperature#'] = is_object($cmd) ? $cmd->execCmd() : '';
      $replace['#uid_climatisation_target_temperature#'] = is_object($cmd) ? $cmd->getId() : '';
      $replace['#climatisation_target_temperature_visible#'] = is_object($cmd) ? $cmd->getIsVisible() : 0;
      $forcast_template = getTemplate('core', $version, 'cmd.action.slider.button');
      $replaceTargetClim['#id#']=$cmd->getId();
      $replaceTargetClim['#uid#']=$cmd->getId();
      $replaceTargetClim['#version#']=$version;
      $replaceTargetClim['#eqLogic_id#']=$this->getId();
      $replaceTargetClim['#name_display#']="Consigne";
      $replaceTargetClim['#hide_name#']="Consigne";
      $replaceTargetClim['#step#']=1;
      $replaceTargetClim['#maxValue#']=24;
      $replaceTargetClim['#minValue#']=18;
      $replaceTargetClim['#state#']=$cmd->execCmd();
      $replaceTargetClim['#unite#']=$cmd->getUnite();
      $replace['#template_target_temp#'] .= template_replace($replaceTargetClim, $forcast_template);
    }
*/

    return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'widget', 'jeeda')));
  }
  

  /*     * **********************Getteur Setteur*************************** */
}

class jeedaCmd extends cmd {
  /*     * *************************Attributs****************************** */

  /*
  public static $_widgetPossibility = array();
  */

  /*     * ***********************Methode static*************************** */


  /*     * *********************Methode d'instance************************* */

  /*
  * Permet d'empêcher la suppression des commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
  public function dontRemoveCmd() {
    return true;
  }
  */

  // Exécution d'une commande
  public function execute($_options = array()) {
    log::add('jeeda','debug', 'Entrer ' . __CLASS__ . '.' . __FUNCTION__);
    $eqlogic = $this->getEqLogic(); //récupère l'éqlogic de la commande $this
    switch ($this->getLogicalId()) {
      case 'refresh': //LogicalId de la commande rafraîchir que l’on a créé dans la méthode Postsave de la classe jeeda.
        //code pour rafraîchir ma commande
        log::add('jeeda','debug','execute refresh function');
        //$eqlogic->checkAndUpdateCmd('batterie', 50);
        jeeda::createVehicule();
        break;
      case 'set_charger':
        log::add('jeeda','debug','execute set_climatisation pour '.$eqlogic->getLogicalId());
        // recupere l'etat actuel de charge
        $cmd = $eqlogic->getCmd(null,'charging');
        if (! is_object($cmd))
          log::add('jeeda','error','Impossible de recuperer l\'état de charge');
        else{
          $value = $cmd->execCmd();
          log::add('jeeda','debug', 'Charging value = ' . $value);
          $eqlogic->setChargingMode($value);
        }
        break;
      default:
        log::add('jeeda','info',$this->getLogicalId() . ' not yet implemented !!!');
    }    
    log::add('jeeda','debug', 'Sortir ' . __CLASS__ . '.' . __FUNCTION__);
  }

  /*     * **********************Getteur Setteur*************************** */
}
