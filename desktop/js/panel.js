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

var globalEqLogic = $( "#eqlogic_select option:selected" ).val();
var jeeda = {
    vehiculeSelected : $( "#eqlogic_select option:selected" ).val(),
    display_battery_capacity : 0
}
$(".in_datepicker").datepicker();

loadData(travelsDate.start,travelsDate.end);

$('#bt_validChangeDate').on('click',function(){
    startDate = $('#in_startDate').value();
    endDate = $('#in_endDate').value();
    loadData(startDate,endDate);
});


document.getElementById('eqlogic_select').onchange = function(){
    console.log("Changement vehicule");
    jeeda.vehiculeSelected = document.getElementById('eqlogic_select').value;
    globalEqLogic = jeeda.vehiculeSelected;
    startDate = $('#in_startDate').value();
    endDate = $('#in_endDate').value();
    loadData(startDate,endDate);
};

function isInt(n) 
{
    return n != "" && !isNaN(n) && Math.round(n) == n;
}
function isFloat(n){
    return n != "" && !isNaN(n) && Math.round(n) != n;
}
function loadData(startDate, endDate){
    console.log('loadData for ' + globalEqLogic);
    // Recupere les informations du véhicule
    $.ajax({
        type: 'POST',
        url: 'plugins/jeeda/core/ajax/jeeda.ajax.php',
        data: {
            action: 'getCarData',
            VIN: globalEqLogic,
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            console.log('Erreur getCarData');
            $('#div_alert').showAlert({message: '{{Erreur chargement des données : }}'+status, level: 'warning'});
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            console.log(data.result);
            carData = JSON.parse(data.result)
            
            jeedom.cmd.update['logo_car'] = function(_options) {
                if (_options.isDisplayed=="1"){
                    document.getElementById("logo_car").src=_options.display_value;
                }
                else {
                    $('.FONC_image').hide();
                }
            }
            jeedom.cmd.update['logo_car']({display_value:carData['image'],isDisplayed:carData['display_image']});

            updateDOMInnerHTML('distance', carData['distance'], carData['display_distance'],carData['distance_id']);
            updateDOMInnerHTML('model', carData['model'], carData['display_model'],carData['model_id']);
            updateDOMInnerHTML('engine_type', carData['engine_type'], carData['display_engine_type'],carData['engine_type_id']);
            updateDOMInnerHTML('model_year', carData['model_year'], carData['display_model_year'],carData['model_year_id']);
            if (carData['has_moteur_thermique'] == '0'){
                $('.FONC_moteur_thermique').hide();
            }else{
                $('.FONC_moteur_thermique').show();
                updateDOMInnerHTML('combustion_range', carData['combustion_range'], carData['display_combustion_range'],carData['combustion_range_id']);
                updateDOMInnerHTML('fuel_level', carData['fuel_level'], carData['display_fuel_level'],carData['fuel_level_id']);
            }
            if (carData['has_moteur_electrique'] == '0'){
                $('.FONC_moteur_electrique').hide();
            }else{
                $('.FONC_moteur_electrique').show();
                updateDOMInnerHTML('electrique_range', carData['electrique_range'], carData['display_electrique_range'],carData['electrique_range_id']);
                jeeda.display_battery_capacity = carData['display_battery_capacity'];
                jeeda.battery_capacity = carData['battery_capacity'];
                updateDOMInnerHTMLBattery('battery_level', carData['battery_level'], carData['display_battery_level'],carData['battery_level_id'],'battery_capacity', carData['battery_capacity'], carData['display_battery_capacity'],carData['battery_capacity_id']);
            }
            if (carData['has_recharge'] == '0'){
                $('.FONC_recharge').hide();
                $('.TAB_charge').hide();
                $('.FONC_detail_trajet').hide();
            }else{
                $('.FONC_recharge').show();
                $('.TAB_charge').show();
                $('.FONC_detail_trajet').show();
                updateDOMIcon('charging_cable_connected', carData['charging_cable_connected'], carData['display_charging_cable_connected'], carData['charging_cable_connected_id'], 'charging', carData['charging'], carData['display_charging'], carData['charging_id']);
                updateDOMInnerHTML('charge_rate', carData['charge_rate'] , carData['display_charge_rate'],carData['charge_rate_id']);
                updateDOMInnerHTML('charging_power', carData['charging_power'] , carData['display_charging_power'],carData['charging_power_id']);
                updateDOMInnerHTML('min_charge_level', carData['min_charge_level'] , carData['display_min_charge_level'],carData['min_charge_level_id']);
                updateDOMInnerHTMLTime('charging_time_left', carData['charging_time_left'], carData['display_charging_time_left'],carData['charging_time_left_id']);
            }
            if (carData['has_entretien'] == '0'){
                $('.FONC_entretien').hide();
            }else{
                $('.FONC_entretien').show();
                updateDOMInnerHTML('service_inspection_distance', 'Entretien dans ' + carData['service_inspection_distance'] + ' km', carData['display_service_inspection_distance'],carData['service_inspection_distance_id']);
            }
            if (carData['has_clim'] == '0'){
                $('.FONC_clim').hide();
            }else{
                $('.FONC_clim').show();
                updateDOMInnerHTML('consigne', carData['consigne'] , carData['display_consigne'],carData['consigne_id']);
                updateDOMInnerHTMLTime('climatisation_time_left', carData['climatisation_time_left'], carData['display_climatisation_time_left'],carData['climatisation_time_left_id']);
            }
        }
    });

    // Recupere les trajets du vehicule
    $.ajax({
        type: 'POST',
        url: 'plugins/jeeda/core/ajax/jeeda.ajax.php',
        data: {
            action: 'getTravelData',
            VIN: globalEqLogic,
            startDate: startDate,
            endDate: endDate,
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            console.log('Erreur getTravelData');
            console.log({message: '{{Erreur chargement des données : }}'+status, level: 'warning'});
            $('#div_alert').showAlert({message: '{{Erreur chargement des données : }}'+status, level: 'warning'});
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            console.log('Success getTravelData');
            console.log(data);
            travelsData = JSON.parse(data.result);
            console.log(travelsData);
            var tr;

            $('#table_cmd thead').empty();
            tr = "<tr>";
            Object.entries(travelsData['header']).forEach(([key, value]) => {   
                tr += "<th>"+value+"</td>";
            });
            tr += "</tr>";
            $('#table_cmd thead').append(tr);

            tr='';
            $('#table_cmd tbody').empty();
            Object.entries(travelsData['data']).forEach(([key, value]) => {
                tr = "<tr onclick='showTravel(\" " + key + " \",\"" + value['dateFin'] + "\",\"" + convertTime(value['duree']) + "\",\"" + parseInt(value['distance']) + "\",\"" + parseFloat(value['kwConso']).toFixed(2) + "\",\"" + parseFloat(value['vitesseMoy']).toFixed(2) + "\",\"" + parseFloat(value['consoMoy']).toFixed(2) + "\",\"" + value['WLTP'] + "\"  )'>";
                tr += "<td>"+key+"</td>";
                Object.entries(travelsData['key']).forEach(([keyData, valueData]) => {
                    if (isFloat(value[valueData]))
                        tr += "<td>"+parseFloat(value[valueData]).toFixed(2)+"</td>";
                    else
                    tr += "<td>"+value[valueData]+"</td>";
                });
                tr += "</tr>";
                $('#table_cmd tbody').append(tr);
            });
            
            
        }
    });

    // Recupere les stat de charge
    $.ajax({
        type: 'POST',
        url: 'plugins/jeeda/core/ajax/jeeda.ajax.php',
        data: {
            action: 'getChargingData',
            VIN: globalEqLogic,
            startDate: startDate,
            endDate: endDate,
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            console.log('Erreur getChargingData');
            console.log({message: '{{Erreur chargement des données : }}'+status, level: 'warning'});
            $('#div_alert').showAlert({message: '{{Erreur chargement des données : }}'+status, level: 'warning'});
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            console.log('Success getChargingData');
            console.log(data);
            stat = JSON.parse(data.result);
            console.log(stat);
            document.getElementById("FONC_charging_total_energy").innerHTML = stat.general['totKW'];
            document.getElementById("FONC_charging_total_duration").innerHTML = convertTime(stat.general['duree']);
            document.getElementById("FONC_charging_count").innerHTML = stat.general['nbCharge'];
            document.getElementById("FONC_charging_avg_power").innerHTML = stat.general['avgChargingPower'];
            var tr;
            $('#table_cmdCharge tbody').empty();
            Object.entries(stat.detaillee).forEach(([key, value]) => {
                tr = "<tr>"; // "<tr onclick='showTravel(\" " + key + " \",\"" + value['dateFin'] + "\",\"" + convertTime(value['duree']) + "\",\"" + parseInt(value['distance']) + "\",\"" + parseFloat(value['kwConso']).toFixed(2) + "\",\"" + parseFloat(value['vitesseMoy']).toFixed(2) + "\",\"" + parseFloat(value['consoMoy']).toFixed(2) + "\",\"" + value['WLTP'] + "\"  )'>";
                tr += "<td>"+key+"</td>";
                tr += "<td>"+convertTime(value['duree'])+"</td>";
                tr += "<td>"+parseFloat(value['totKW']).toFixed(2)+"</td>";
                tr += "<td>"+parseFloat(value['avgChargingPower']).toFixed(0)+"</td>";
                tr += "</tr>";
                $('#table_cmdCharge tbody').append(tr);
            });

        }
    });
}

function showTravel(start, end, duree, distance,kwConso,vitesseMoy,consoMoy,wltp){
    console.log("showTravel");
    $.ajax({
        type: 'POST',
        url: 'plugins/jeeda/core/ajax/jeeda.ajax.php',
        data: {
            action: 'showTravel',
            VIN: globalEqLogic,
            startDate: start,
            endDate: end,
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            console.log('Erreur showTravel');
            console.log({message: '{{Erreur chargement des données du trajet : }}'+status, level: 'warning'});
            $('#div_alert').showAlert({message: '{{Erreur chargement des données du trajet : }}'+status, level: 'warning'});
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            console.log(data.result);
            $dt = data.result.date[0].trim().split(" ");
            $d=$dt[0].split("-");
            $t=$dt[1].split(":");
            var chart = {
                type: 'spline'
            };
            var title =  {
                text: '{{Detail du trajet du }}' + data.result.date[0],
                style: {
                    fontSize: '10px'
                }
            }
            var xAxis= {
                type: 'datetime',
                labels: {
                    overflow: 'justify',
                    rotation : -80
                },
                dateTimeLabelFormats: {
                    minute: ['%H:%M', '%H:%M', '-%H:%M']
                    /*minute: ['%m/%e/%y %H:%M', '%m/%e/%y %H:%M', '-%H:%M'],
                    hour: ['%m/%e/%y %H:%M', '%m/%e/%y %H:%M', '-%H:%M'],
                    day: ['%m/%e/%y %H:%M', '%m/%e/%y %H:%M', '-%H:%M'],
                    week: ['%m/%e/%y %H:%M', '%m/%e/%y %H:%M', '-%H:%M'],
                    month: ['%m/%e/%y %H:%M', '%m/%e/%y %H:%M', '-%H:%M'],
                    year: ['%m/%e/%y %H:%M', '%m/%e/%y %H:%M', '-%H:%M']*/
                },
                categories : data.result.date
            };
            var yAxis = [{
               
                title: {
                    text: '{{Distance}}'
                }
            },{
                labels: {
                    format: '{value}%',
                  },
                title: {
                    text: '{{Batterie}}'
                },
                opposite: true
            }];
            var plotOptions= {
                spline: {
                    lineWidth: 4,
                    states: {
                        hover: {
                            lineWidth: 5
                        }
                    },
                    marker: {
                        enabled: false
                    },
                    pointInterval: 120000, // 5 mn
                    pointStart: Date.UTC($d[0],$d[1]-1,$d[2], $t[0],$t[1])
                    
                }
            };
            var exporting = {
                enabled: false
            };
            var credits= {
                text: '',
                href: '',
            };
            var series =  data.result.data;
            var json = {};
            json.chart = chart;
            json.title = title;
            json.xAxis = xAxis;
            json.yAxis = yAxis;  
            json.series = series;
            json.exporting = exporting;
            json.credits = credits;
            json['series'][1].yAxis=1;
            //json['series'][3].lineColor = '#000';
            //json.plotOptions = plotOptions;
            $('#travelChart').highcharts(json);

            document.getElementById("FONC_travel_range").innerHTML = distance;
            document.getElementById("FONC_travel_duration").innerHTML = duree;
            document.getElementById("FONC_travel_speed").innerHTML = vitesseMoy;
            document.getElementById("FONC_travel_energy").innerHTML = kwConso;
            document.getElementById("FONC_travel_wltp").innerHTML = wltp;
            document.getElementById("FONC_travel_conso").innerHTML = consoMoy;
        }
    });
}

function convertTime(minutes){
    retour = '';
    if (typeof minutes === 'undefined'){
        retour = '';
    }else{
        retour = parseInt(minutes / 60).toLocaleString(undefined, {minimumIntegerDigits: 2})+"h"+parseInt(minutes % 60).toLocaleString(undefined, {minimumIntegerDigits: 2});
    }
    return retour;
}

function updateDOMIcon(key_plugged, etat_plugged, isDisplayed_plugged, id_plugged, key_charging, etat_charging, isDisplayed_charging, id_charging){
    if (isDisplayed_plugged=="0"){
        $('.logo_'+key_plugged).hide();
    }
    else {
        $(".logo_"+key_plugged).attr("data-cmd_id", id_plugged)
        $(".logo_"+key_charging).attr("data-cmd_id", id_charging)
        jeedom.cmd.update[id_plugged] = function (_options){
            if (_options.display_value == 0)
                $('span[data-cmd_id='+_options.cmd_id+']').hide();
            else
                $('span[data-cmd_id='+_options.cmd_id+']').show();
        }
        jeedom.cmd.update[id_plugged]({cmd_id:id_plugged,display_value:etat_plugged});  
        if (isDisplayed_charging=="0"){
            $('.logo_'+key_plugged).hide();
        }
        else {
            jeedom.cmd.update[id_charging] = function (_options){
                if (_options.display_value == 0)
                    $('span[data-cmd_id='+_options.cmd_id+']').hide();
                else
                    $('span[data-cmd_id='+_options.cmd_id+']').show();
            }
            jeedom.cmd.update[id_charging]({cmd_id:id_charging,display_value:etat_charging});  
        }
        
    }
}
function updateDOMInnerHTML(key, value, isDisplayed, id){
    if (isDisplayed=="0"){
        $('.FONC_'+key).hide();
    }
    else {
        $(".FONC_"+key).attr("data-cmd_id", id) 
        jeedom.cmd.update[id] = function(_options) {
            $('span[data-cmd_id='+_options.cmd_id+']').empty().append(_options.display_value);
        };
        jeedom.cmd.update[id]({cmd_id:id,display_value:value});    
    }
}
function updateDOMInnerHTMLBattery(key_level, value_level, isDisplayed_level, id_level,key_capa, value_capa, isDisplayed_capa, id_capa){
    if (isDisplayed_level=="0"){
        $('.FONC_'+key_level).hide();
    }
    else {
        $(".FONC_"+key_level).attr("data-cmd_id", id_level) 
        jeedom.cmd.update[id_level] = function(_options) {
            $('span[id=FONC_battery_level]').empty().append(_options.display_value);
            if (jeeda.display_battery_capacity == 1)
                $('span[id=FONC_battery_level_W]').empty().append(parseInt(_options.display_value*jeeda.battery_capacity/100));
        };
        jeedom.cmd.update[id_level]({cmd_id:id_level,display_value:value_level});    
    }
    if (isDisplayed_capa=="0"){
        $('.FONC_battery_level_detail').hide();
    } else {
        $('span[id=FONC_battery_level_capacity]').empty().append(value_capa);
    }
}
function updateDOMInnerHTMLTime(key, value, isDisplayed, id){
    if (isDisplayed=="0"){
        $('.FONC_'+key).hide();
    }
    else {
        $(".FONC_"+key).attr("data-cmd_id", id) 
        jeedom.cmd.update[id] = function(_options) {
            $('span[data-cmd_id='+_options.cmd_id+']').empty().append(convertTime(_options.display_value));
        };
        jeedom.cmd.update[id]({cmd_id:id,display_value:value});    
    }
}