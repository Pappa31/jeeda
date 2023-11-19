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

if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$date = array(
	'start' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). ' -7 days')),
	'end' => date('Y-m-d H:i:s'),
);
sendVarToJS('eqType', 'jeeda');
sendVarToJS('travelsDate', $date);
$eqLogics = eqLogic::byType('jeeda');

?>

<div class="row row-overflow " id="bckgd" style="padding:10px;">
    <div style="font-size: 1.5em;"> 
        <span id="spanTitreResume">{{Mes véhicules}}
        </span>
        <select id="eqlogic_select" style="font-size: 15px;border-radius: 3px;border:1px solid #ccc;">
                        <?php
                        $currentEqLogic = null;
                        foreach ($eqLogics as $eqLogic) {
                            echo '<option value="' . $eqLogic->getId() . '">"' . $eqLogic->getName() . '"</option>';
                            $currentEqLogic = $eqLogic;
                        }
                        ?>
        </select>            
    </div>
    <div class="container-fluid jeeda_panel_flat">
        <div>
            <!-- Column N°1 données -->
            <div class="col-xs-6 jeeda_panel_flat_column" style="width:470px; ">
                <div class="col-xs-12 jeeda_panel_flat_tile FONC_image" style="height:250px;width:440px; ">
                        <div class="jeeda_panel_flat_picture" style="height: 250px; ">
                            <img class="logo_car" id="logo_car" style="height: 250px; " src="https://ip-modcwp.azureedge.net/modcwp5azv23200005/8X8XPUwFvBgM0CK-U9pbg0iQDkeurY6N8mR-9Y0vJAb2INPUxtyog6_B-Mwqxg0_cFBhHk3YT16uJsaRolW8-DKGNEBJ-700390dayvext_front1080.png"></img> 
                        </div>
                </div>                
                
                <div class="col-xs-12 jeeda_panel_flat_tile" style="height:50px;width:440px;">
                    
                    <div class="col-xs-3 " style="padding:0px !important;"><span class="FONC_distance" id="FONC_distance">0000</span> km</div>
                    <div class="col-xs-3 " style="padding:0px !important;"><span class="FONC_model" id="FONC_model">--</span></div>
                    <div class="col-xs-3 " style="padding:0px !important;"><span class="FONC_engine_type" id="FONC_engine_type">--</span></div>
                    <div class="col-xs-3 " style="padding:0px !important;"><span class="FONC_model_year" id="FONC_model_year">20..</span></div>
                    
                </div>
                <div class="col-xs-4 jeeda_panel_flat_tile FONC_moteur_thermique" id="FONC_moteur_thermique"  style="width:140px;">
                    <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{Moteur thermique}}</div>
                    <div class="jeeda_panel_flat_value_tile ">{{Autonomie}} : <span class="FONC_thermique_range" id="FONC_thermique_range">---</span> km </div>
                    <div class="jeeda_panel_flat_value_tile ">{{Réservoir}} : <span class="FONC_engine_capacity" id="FONC_engine_capacity">--</span> l</div>
                </div>
                <div class="col-xs-4 jeeda_panel_flat_tile FONC_moteur_electrique" id="FONC_moteur_electrique"  style="width:140px;">
                    <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{Moteur electrique}}</div>
                    <div class="jeeda_panel_flat_value_tile " >{{Autonomie}} : <span class="FONC_electrique_range" id="FONC_electrique_range">000</span> km </div>
                    <div class="jeeda_panel_flat_value_tile " >{{Batterie}} : <span class="FONC_battery_level" id="FONC_battery_level">--</span> % <span class="FONC_battery_level_detail" id="FONC_battery_level_detail">(<span class="FONC_battery_level_W" id="FONC_battery_level_W">--</span>/<span class="FONC_battery_level_capacity" id="FONC_battery_level_capacity">--</span> kW)</span></div>
                </div>
                <div class="col-xs-4 jeeda_panel_flat_tile FONC_recharge" id="FONC_recharge"  style="width:140px;">
                    <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{Recharge}}<span class="logo_charging_cable_connected">&nbsp;<i class="fas fa-plug"  style="color:rgb(150,150,150)" title="Branché"></i> <span class="logo_charging">&nbsp;<i class="fas kiko-electricity"  style="color:rgb(0,208,0)" title="En charge"></i></span></span></div>
                    <div class="jeeda_panel_flat_value_tile " >{{Charge}} : <span class="FONC_charge_rate" id="FONC_charge_rate">----</span> km/h</div>
                    <div class="jeeda_panel_flat_value_tile " >{{Puissance}} : <span class="FONC_charging_power" id="FONC_charging_power">----</span> W/h</div>
                    <div class="jeeda_panel_flat_value_tile " >{{Limite}} : <span class="FONC_min_charge_level" id="FONC_min_charge_level">--</span> %</div>
                    <div class="jeeda_panel_flat_value_tile " >{{Temps}} : <span class="FONC_charging_time_left" id="FONC_charging_time_left">--h--</span></div>
                </div>
                <div class="col-xs-4 jeeda_panel_flat_tile FONC_clim" id="FONC_clim"  style="width:140px;">
                    <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{Climatisation}}</div>
                    <div class="jeeda_panel_flat_value_tile " >{{Consigne}} <span class="FONC_consigne" id="FONC_consigne">--</span>°C</div>
                    <div class="jeeda_panel_flat_value_tile " >{{Temps}} : <span class="FONC_climatisation_time_left" id="FONC_climatisation_time_left">--h--</span></div>
                </div>
                <div class="col-xs-4 jeeda_panel_flat_tile FONC_entretien" id="FONC_entretien"  style="width:140px;">
                    <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{Entretien}}</div>
                    <div class="jeeda_panel_flat_value_tile FONC_oil_inspection_distance" id="FONC_oil_inspection_distance">{{Entretien dans}} --- km</div>
                </div>
            </div>

            <!-- Column N°2 Trajet -->
            <div class="col-xs-6 jeeda_panel_flat_column" style="width: 945px;">
                <div class="col-xs-12 jeeda_panel_flat_tile FONC_trajet">
                    <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{Trajets}}
                        <a style="margin-right:5px;" class="pull-right btn btn-success btn-sm tooltips" id='bt_validChangeDate' title="{{Attention une trop grande plage de dates peut mettre très longtemps à être calculée ou même ne pas s'afficher}}">{{Ok}}</a>
                        <input id="in_endDate" class="pull-right form-control input-sm in_datepicker" style="display : inline-block; width: 87px;" value="<?php echo $date['end']?>"/>
                        <input id="in_startDate" class="pull-right form-control input-sm in_datepicker" style="display : inline-block; width: 87px;" value="<?php echo $date['start']?>"/>
                    </div>
                    <div >
                        <table class="fixed_header tableCmd" id="table_cmd">
                            <thead>
                                <tr>
                                    <th>{{Date}}</th>
                                    <th>{{Durée}}</th>
                                    <th>{{Distance}}</th>
                                    <th>{{Vitesse Moy.}}</th>
                                    <th>{{kW Conso}}</th>
                                    <th>{{Impact autonomie}}</th>
                                    <th>{{D(conso/reel)}}</th>
                                    <th>{{kW/100 km}}</th>
                                    <th>{{Autonomie WLTP}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                <div class="col-xs-12 jeeda_panel_flat_tile FONC_trajet">
                    <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{Detail du trajet}}</div>
                    <div class="col-xs-12">
                        <div class="col-xs-4 jeeda_panel_flat_tile " style="width:140px;">
                            <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{Distance}}</div>
                            <center><span class="FONC_travel_range" id="FONC_travel_range">---</span> km </center>
                        </div>
                        <div class="col-xs-4 jeeda_panel_flat_tile " style="width:140px;">
                            <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{kW Conso}}</div>
                            <center><span class="FONC_travel_energy" id="FONC_travel_energy">---</span> kw </center>
                        </div>
                        <div class="col-xs-4 jeeda_panel_flat_tile " style="width:140px;">
                            <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{Durée}}</div>
                            <center><span class="FONC_travel_duration" id="FONC_travel_duration">--h--</span> </center>
                        </div>
                        <div class="col-xs-4 jeeda_panel_flat_tile " style="width:140px;">
                            <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{Vitesse}}</div>
                            <center><span class="FONC_travel_speed" id="FONC_travel_speed">---</span> km / h </center>
                        </div>
                        <div class="col-xs-4 jeeda_panel_flat_tile " style="width:140px;">
                            <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{Consommation}}</div>
                            <center><span class="FONC_travel_conso" id="FONC_travel_conso">---</span> kw/100 </center>
                        </div>
                        <div class="col-xs-4 jeeda_panel_flat_tile " style="width:140px;">
                            <div class="jeeda_panel_flat_titre_tile " id="titre_tile">{{WLTP}}</div>
                            <center><span class="FONC_travel_wltp" id="FONC_travel_wltp">---</span> km </center>
                        </div>
                    </div>
                    <div class="col-xs-12" id="travelChart">
                    {{Selectionner un trajet pour voir le détail.}}
                    </div>
                </div>
            </div>	
        </div>
    </div>
</div>


<?php include_file('desktop', 'panel', 'js', 'jeeda'); ?>
<?php include_file('desktop', 'panel', 'css', 'jeeda'); ?>
