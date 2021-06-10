<?php
echo "";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Dorchester DT</title>
        <link rel="stylesheet" href="src/leaflet.css" />
        <link rel="stylesheet" href="src/css/bootstrap.css" />
        <link rel="stylesheet" href="src/plugins/L.Control.MousePosition.css" />
        <link rel="stylesheet" href="src/plugins/L.Control.Sidebar.css" />
        <link rel="stylesheet" href="src/plugins/Leaflet.PolylineMeasure.css" />
        <link rel="stylesheet" href="src/plugins/easy-button.css" />
        <link rel="stylesheet" href="src/css/font-awesome.min.css" />
        <link rel="stylesheet" href="src/plugins/leaflet.awesome-markers.css" />
        <link rel="stylesheet" href="src/plugins/MarkerCluster.css" />
        <link rel="stylesheet" href="src/plugins/MarkerCluster.Default.css" />
        <link rel="stylesheet" href="src/plugins/leaflet-legend.css" />
        <link rel="stylesheet" href="src/jquery-ui.min.css" />

        <script src="src/leaflet.js"></script>
        <script src="src/jquery-3.3.1.min.js"></script>
        <script src="src/plugins/L.Control.MousePosition.js"></script>
        <script src="src/plugins/L.Control.Sidebar.js"></script>
        <script src="src/plugins/Leaflet.PolylineMeasure.js"></script>
        <script src="src/plugins/easy-button.js"></script>
        <script src="src/plugins/leaflet-providers.js"></script>
        <script src="src/plugins/leaflet.ajax.min.js"></script>
        <script src="src/plugins/leaflet.awesome-markers.min.js"></script>
        <script src="src/plugins/leaflet.markercluster.js"></script>
        <script src="src/plugins/leaflet-legend.js"></script>
        <script src="src/jquery-ui.min.js"></script>
        <script src="src/turf.min.js"></script>

        <style>
            #mapdiv {
                height: 100vh;
            }

            .col-xs-12,
            .col-xs-6,
            .col-xs-4 {
                padding: 3px;
            }

            .text-labels {
                font-size: 16px;
                font-weight: bold;
                color: red;
                background: aqua;
                text-align: center;
                display: inline-block;
                padding: 5pt;
                border: 3px double darkred;
                border-radius: 5pt;
                margin-left: -15pt;
                margin-top: -8pt;
            }

            .eagle-labels {
                font-size: 12px;
                font-weight: bold;
                color: white;
                background: black;
                text-align: center;
                display: inline-block;
                padding: 5pt;
                border: 3px double red;
                border-radius: 5pt;
                margin-left: -20pt;
                margin-top: -32pt;
            }
        </style>
    </head>
    <body>


    <div id="side-bar" class="col-md-2">
            <button id="btnLocate" class="btn btn-primary btn-block">Locate</button>
            <button id="btnShowLegend" class="btn btn-primary btn-block">
                Show Legend
            </button>
            <div id="legend">
                <div id="lgndClientLinears">
                    <h4 class="float-left">
                        Legend / Key
                    </h4>
                    <div id="lgndLinearProjectsDetail">
                        <svg height="50" width="100%">
                            <line
                                x1="10"
                                y1="10"
                                x2="40"
                                y2="10"
                                style="stroke: black; stroke-width: 2"
                            />
                            <text
                                x="50"
                                y="15"
                                style="font-family: sans-serif; font-size: 16px"
                            >
                                Boundary
                            </text>
                            <line
                                x1="10"
                                y1="40"
                                x2="40"
                                y2="40"
                                style="stroke: pink; stroke-width: 2"
                            />
                            <text
                                x="50"
                                y="45"
                                style="font-family: sans-serif; font-size: 16px"
                            >
                                Roads
                            </text>
                            <div id="lgndRaptorNest">
                                <div id="lgndRaptorDetail">
                                    <svg height="100">
                                        <circle
                                            cx="25"
                                            cy="15"
                                            r="10"
                                            style="
                                                stroke-width: 4;
                                                stroke: deeppink;
                                                fill: cyan;
                                                fill-opacity: 0.5;
                                            "
                                        />
                                        <text
                                            x="50"
                                            y="20"
                                            style="font-family: sans-serif; font-size: 16px"
                                        >
                                            Live / working Address
                                        </text>
                                        <circle
                                            cx="25"
                                            cy="75"
                                            r="10"
                                            style="
                                                stroke-width: 4;
                                                stroke: black;
                                                fill: cyan;
                                                fill-opacity: 0.5; "/>
                                        <text
                                            x="50"
                                            y="80"
                                            style="font-family: sans-serif; font-size: 16px">
                                            Terminated Address
                                        </text>
                                    </svg>
                                </div>
                            </div>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
</div>



        <div id="mapdiv" class="col-md-12"></div>
        <script type="module">
            var mymap;
            var lyrOSM;
            var lyrWatercolor;
            var lyrTopo;
            var lyrImagery;
            var lyrOutdoors;
            var lyrEagleNests;
            var lyrPagleNests;
            var lyrBagleNests;
            var lyrRaptorNests;
            var lyrClientLines;
            var lyrClientLinesBuffer;
            var lyrBUOWL;
            var lyrBUOWLbuffer;
            var jsnBUOWLbuffer;
            var lyrGBH;
            var lyrSearch;
            var lyrMarkerCluster;
            var mrkCurrentLocation;
            var fgpDrawnItems;
            var ctlAttribute;
            var ctlScale;
            var ctlMouseposition;
            var ctlMeasure;
            var ctlEasybutton;
            var ctlSidebar;
            var ctlLayers;
            var ctlStyle;
            var ctlLegend;
            var objBasemaps;
            var objOverlays;
            var arProjectIDs = [];
            var arHabitatIDs = [];
            var arEagleIDs = [];
            var arRaptorIDs = [];

            $(document).ready(function () {
                //  ********* Map Initialization ****************

                mymap = L.map('mapdiv', {
                    center: [50.71, -2.44],
                    zoom: 9,
                    attributionControl: false,
                });

                mymap.options.minZoom = 9;
                mymap.options.maxZoom = 19;

                ctlSidebar = L.control.sidebar('side-bar').addTo(mymap);

                ctlEasybutton = L.easyButton('glyphicon-transfer', function () {
                    ctlSidebar.toggle();
                }).addTo(mymap);

                ctlAttribute = L.control.attribution().addTo(mymap);
                ctlAttribute.addAttribution('OSM');
                ctlAttribute.addAttribution(
                    '&copy; <a href="#">Margarson</a>'
                );

                ctlScale = L.control
                    .scale({ position: 'bottomleft', metric: false, maxWidth: 200 })
                    .addTo(mymap);

                ctlMouseposition = L.control.mousePosition().addTo(mymap);

                ctlMeasure = L.control.polylineMeasure().addTo(mymap);

                //   *********** Layer Initialization **********

                lyrOSM = L.tileLayer.provider('OpenStreetMap.Mapnik');
                lyrImagery = L.tileLayer.provider('Esri.WorldImagery');
                mymap.addLayer(lyrOSM);

                fgpDrawnItems = new L.FeatureGroup();
                fgpDrawnItems.addTo(mymap);

                //******* loading our database **********
                // refreshEagles();
                refreshLinears();
                refreshEagles();

                // ********* Setup Layer Control  ***************

                objBasemaps = {
                    'Open Street Maps': lyrOSM,
                    Imagery: lyrImagery,
                };

                objOverlays = {};

                ctlLayers = L.control.layers(objBasemaps, objOverlays).addTo(mymap);

                // ************ Client Linears **********


                function processClientLinears(json, lyr) {
                 var att = json.properties;
                 lyr.bindPopup("<h4>Area Postcode: "+att.layer+"</h4> District Postcode: "+att.name+"<br>")
                 .addTo(mymap);
                 arProjectIDs.push(att.layer.toString());

                }

            function refreshLinears() {
                    $.ajax({
                        url: 'load_allpostcodes.php',
                        data: { tbl: 'merged', flds: 'layer' },
                        type: 'GET',
                        success: function (response) {
                            arProjectIDs = [];
                            jsnLinears = JSON.parse(response);
                            if (lyrClientLines) {
                                ctlLayers.removeLayer(lyrClientLines);
                                lyrClientLines.remove();
                                lyrClientLinesBuffer.remove();
                            }
                            lyrClientLinesBuffer = L.featureGroup();
                            lyrClientLines = L.geoJSON(jsnLinears, {
                                color: 'black',
                                dashArray: '5,5',
                                fillOpacity: 0,
                                opacity: 0.5,
                                onEachFeature: processClientLinears,
                            }).addTo(mymap);
                            ctlLayers.addOverlay(lyrClientLines, 'Boundary');
                            arProjectIDs.sort(function (a, b) {
                                return a - b;
                            });
                            $('#txtFindEagle').autocomplete({
                                source: arProjectIDs,
                            });
                        },
                        error: function (xhr, status, error) {
                            alert('ERROR: ' + error);
                        },
                    });
                }
                // *********  Eagle Functions *****************

                function returnEagleMarker(json, latlng) {
                    var att = json.properties;
                    if (att.field_2 == 'live') {
                        var clrNest = 'deeppink';
                    } else {
                        var clrNest = 'black';
                    }

                    arEagleIDs.push(att.field_2.toString());
                    return L.circle(latlng, {
                        radius: 2,
                        color: clrNest,
                        fillColor: 'chartreuse',
                        fillOpacity: 0.5,
                    });
                }

                function processPcodemarker(json, lyr) {
                    var att = json.properties;
                    lyr
                        .bindTooltip('<h4>Post Code: ' + att.field_1 + '</h4>')
                        .openPopup();
                }
                function processBaglemarker(json, lyr) {
                    var att = json.properties;
                    lyr
                        .bindTooltip('<h4>Post Code: ' + att.field_1 + '</h4>')
                        .openPopup();
                }

                function filterEagle(json) {
                    var att = json.properties;
                    var optFilter = $('input[name=fltEagle]:checked').val();
                    if (optFilter == 'ALL') {
                        return true;
                    } else {
                        return att.status == optFilter;
                    }
                }

                function refreshEagles() {
                    $.ajax({
                        url: 'load_allpostcodes.php',
                        data: { tbl:'dorchesterdt', flds:'id, field_1, field_2, field_3, field_4, field_5' },
                        type: 'GET',
                        success: function (response) {
                            arEagleIDs = [];
                            jsnEagles = JSON.parse(response);
                            if (lyrEagleNests) {
                                ctlLayers.removeLayer(lyrEagleNests);
                                ctlLayers.removeLayer(lyrMarkerCluster);
                                lyrEagleNests.remove();
                            }
                            lyrEagleNests = L.geoJSON(jsnEagles, {
                                onEachFeature: processPcodemarker,
                                pointToLayer: returnEagleMarker,
                                filter: filterEagle,
                            });

                            arEagleIDs.sort(function (a, b) {
                                return a - b;
                            });
                            $('#txtFindEagle').autocomplete({
                                source: arEagleIDs,
                            });
                            lyrMarkerCluster = L.markerClusterGroup();
                            lyrMarkerCluster.clearLayers();
                            lyrMarkerCluster.addLayer(lyrEagleNests);
                            lyrMarkerCluster.addTo(mymap);
                            ctlLayers.addOverlay(lyrMarkerCluster, 'Post codes');
                        },
                        error: function (xhr, status, error) {
                            alert('ERROR: ' + error);
                        },
                    });
                }

                function refreshPagles() {
                    $.ajax({
                        url: 'load_pcode.php',
                        data: { tbl: 'aberdeenab', flds: 'field_2' },
                        type: 'GET',
                        success: function (response) {
                            arEagleIDs = [];
                            jsnPagles = JSON.parse(response);
                            if (lyrPagleNests) {
                                ctlLayers.removeLayer(lyrPaglesNests);
                                ctlLayers.removeLayer(lyrMarkerCluster);
                                lyrPagleNests.remove();
                            }
                            lyrPagleNests = L.geoJSON(jsnPagles, {
                                onEachFeature: processPcodemarker,
                                pointToLayer: returnEagleMarker,
                                filter: filterEagle,
                            });
                            ctlLayers.addOverlay(lyrPagleNests, 'Post Codes');
                            arEagleIDs.sort(function (a, b) {
                                return a - b;
                            });
                            $('#txtFindEagle').autocomplete({
                                source: arEagleIDs,
                            });
                            lyrMarkerCluster = L.markerClusterGroup();
                            lyrMarkerCluster.clearLayers();
                            lyrMarkerCluster.addLayer(lyrPagleNests);
                            lyrMarkerCluster.addTo(mymap);
                            ctlLayers.addOverlay(lyrMarkerCluster, 'BOLTON');
                        },
                        error: function (xhr, status, error) {
                            alert('ERROR: ' + error);
                        },
                    });
                }

                //  ***********  General Functions *********

                $('btnLocate').click(function () {
                    mymap.locate();
                });

                //  ***********  General Functions *********

                function LatLngToArrayString(ll) {
                    return '[' + ll.lat.toFixed(5) + ', ' + ll.lng.toFixed(5) + ']';
                }

                function returnLayerByAttribute(lyr, att, val) {
                    var arLayers = lyr.getLayers();
                    for (i = 0; i < arLayers.length - 1; i++) {
                        var ftrVal = arLayers[i].feature.properties[att];
                        if (ftrVal == val) {
                            return arLayers[i];
                        }
                    }
                    return false;
                }

                function returnLayersByAttribute(lyr, att, val) {
                    var arLayers = lyr.getLayers();
                    var arMatches = [];
                    for (i = 0; i < arLayers.length - 1; i++) {
                        var ftrVal = arLayers[i].feature.properties[att];
                        if (ftrVal == val) {
                            arMatches.push(arLayers[i]);
                        }
                    }
                    if (arMatches.length) {
                        return arMatches;
                    } else {
                        return false;
                    }
                }

                function testLayerAttribute(ar, val, att, fg, err, btn) {
                    if (ar.indexOf(val) < 0) {
                        $(fg).addClass('has-error');
                        $(err).html('**** ' + att + ' NOT FOUND ****');
                        $(btn).attr('disabled', true);
                    } else {
                        $(fg).removeClass('has-error');
                        $(err).html('');
                        $(btn).attr('disabled', false);
                    }
                }

                function returnLength(arLL) {
                    var total = 0;

                    for (var i = 1; i < arLL.length; i++) {
                        total = total + arLL[i - 1].distanceTo(arLL[i]);
                    }

                    return total;
                }

                function returnMultiLength(arArLL) {
                    var total = 0;

                    for (var i = 0; i < arArLL.length; i++) {
                        total = total + returnLength(arArLL[i]);
                    }

                    return total;
                }

                function stripSpaces(str) {
                    return str.replace(/\s+/g, '');
                }
            });
        </script>
    </body>
</html>
