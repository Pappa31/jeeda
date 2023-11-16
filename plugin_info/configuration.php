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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
  include_file('desktop', '404', 'php');
  die();
}
?>
<form class="form-horizontal">
  <fieldset>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Login Skoda Connect}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Renseignez le login utilisé pour SkodaConnect}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="login"/>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Mot de passe}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Renseignez le mot de passe}}"></i></sup>
      </label>
      <div class="col-md-4 input-group">
        <input class="configKey form-control inputPassword" data-l1key="pwd"/>
        <span class="input-group-btn">
            <a class="btn btn-default form-control bt_showPass roundedRight"><i class="fas fa-eye"></i></a>
        </span>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Demon port}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Renseignez le port de communication avec le démon}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="port" value="51978"/>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Cycle standard}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Durée en seconde du cycle d'interogation quand le véhicule ne roule pas}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="standardCycle" value="500"/>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Cycle mode conduite}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Durée en seconde du cycle d'interogation quand le véhicule roule}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="driveCycle" value="120"/>
      </div>
    </div>
    <div class="input-group">
  
    </div>

  </fieldset>
</form>

