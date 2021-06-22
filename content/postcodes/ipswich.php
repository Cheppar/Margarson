<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<title>Ipswich IP</title>
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
		 <div id="side-bar" class="col-md-3">
            <button id="btnLocat" class="btn btn-primary btn-block">Locate</button>
            <button id="btnShowLegend" class="btn btn-primary btn-block">
                Show Legend
            </button>
            <div id="legend">
                <div id="lgndClientLinears">
                    <h4 class="text-center">
                        Linear Boundary<i id="btnLinearProjects"></i>
                    </h4>
                    <div id="lgndLinearProjectsDetail">
                        <svg height="50" width="100%">
                            <line x1="10"  y1="10"  x2="40" y2="10" style="stroke: blue; stroke-width: 6" />
                            <text x="50" y="15" style="font-family: sans-serif; font-size: 16px">
                                District Boundary
                            </text>
                            <line x1="10" y1="40" x2="40" y2="40" style="stroke: black; stroke-width: 6" />
                            <text  x="50" y="45" style="font-family: sans-serif; font-size: 16px">
                                Sector Boundary
                            </text>
                            <div id="lgndRaptorNest">
                                <div id="lgndRaptorDetail">
                                    <svg height="100">
                                        <circle cx="25" cy="15"  r="10" style="stroke-width: 4; stroke: deeppink; fill: green; fill-opacity: 0.5; "/>
                                        <text
                                            x="50" y="20" style="font-family: sans-serif; font-size: 16px">Live Addresses
                                        </text>

                                        <circle
                                            cx="25"cy="75" r="10" style="stroke-width: 4; stroke: black; fill: green;  fill-opacity: 0.5; " />
                                        <text x="50"  y="80" style="font-family: sans-serif; font-size: 16px"> Terminated </text> </svg>
                                        </div>
                                         </div>
                                     </svg>
                                     </div>
                                      </div>
                                  </div>
                              </div>

		<div id="mapdiv" class="col-md-12"></div>
		<script>
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
			var lyrSectorLines;
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
					center: [52.06, 1.14],
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
					'&copy; <a href="http://nakuplan.com">Godfrey Ejiofor</a>'
				);

				ctlScale = L.control
					.scale({ position: 'bottomleft', metric: false, maxWidth: 200 })
					.addTo(mymap);

				ctlMouseposition = L.control.mousePosition().addTo(mymap);

				ctlMeasure = L.control.polylineMeasure().addTo(mymap);

				//   *********** Layer Initialization **********

				lyrOSM = L.tileLayer.provider('OpenStreetMap.Mapnik');
				lyrTopo = L.tileLayer.provider('OpenTopoMap');
				lyrImagery = L.tileLayer.provider('Esri.WorldImagery');
				lyrOutdoors = L.tileLayer.provider('Thunderforest.Outdoors');
				lyrWatercolor = L.tileLayer.provider('Stamen.Watercolor');
				mymap.addLayer(lyrOSM);

				fgpDrawnItems = new L.FeatureGroup();
				fgpDrawnItems.addTo(mymap);

				//******* loading our database **********
				 //******* loading our database **********
               refreshDist();
               refreshSectors();
               refreshEagles();

                // ********* Setup Layer Control  ***************

                objBasemaps = {
                    "Open Street Maps": lyrOSM,
                    "Imagery":lyrImagery,

                };

                objOverlays = {

                };

                ctlLayers = L.control.layers(objBasemaps, objOverlays).addTo(mymap);

                mymap.on('zoomend', function(e) {
                    if (mymap.getZoom() < 11){
                        mymap.addLayer(lyrClientLines);
                         mymap.removeLayer(lyrSectorLines);
                    }else{

                         mymap.removeLayer(lyrEagleNests);
                    }
                    if(mymap.getZoom() >= 12){
                         mymap.addLayer(lyrSectorLines);

                    }else{
                        mymap.addLayer(lyrClientLines);
                    }

                });

// ************ Client Linears **********
           function processClientLinears(json, lyr) {
                var att = json.properties;
             lyr.bindPopup("<h4>District: "+att.postdist+"</h4>").addTo(mymap);
             arProjectIDs.push(att.postdist.toString());

            }

            function refreshDist() {
                    $.ajax({
                        url: 'load_allpostcodes.php',
                        data: { tbl: 'dist_eastern', flds: 'distid, postdist, postarea, x, y' , where:"postarea='IP'"},
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
                                color: 'blue',
                                dashArray: '5,5',
                                fillOpacity: 0,
                                opacity: 0.5,
                                onEachFeature: processClientLinears,
                            }).addTo(mymap);
                            ctlLayers.addOverlay(lyrClientLines, 'District');
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


            // ************ Sectors Linears **********
            function processSectorLinears(json, lyr) {
                var att = json.properties;
             lyr.bindPopup("<h4>Sector: "+att.rmsect+"</h4>");
             arProjectIDs.push(att.postdist.toString());
            }

            function refreshSectors() {
                    $.ajax({
                        url: 'load_allpostcodes.php',
                        data: { tbl:'sect_ipswich', flds: 'sectid, rmsect, postdist, postarea, x, y' },
                        type: 'GET',
                        success: function (response) {
                            arProjectIDs = [];
                            jsnSectors = JSON.parse(response);
                            if (lyrSectorLines) {
                                ctlLayers.removeLayer(lyrSectorLines);
                                lyrSectorLines.remove();

                            }

                            lyrSectorLines = L.geoJSON(jsnSectors, {
                                color: 'black',
                                dashArray: '5,5',
                                fillOpacity: 0,
                                opacity: 0.5,
                                onEachFeature: processSectorLinears,
                            }) ;
                            ctlLayers.addOverlay(lyrSectorLines, 'Sector');
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

				// *********  Postcode Functions *****************

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
						data: { tbl:'ipswitch', flds:'id, field_1, field_2, field_3, field_4, field_5' },
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
