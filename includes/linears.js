       var arProjectIDs = [];

        export function processClientLinears(json, lyr) {
                 var att = json.properties;
                 lyr.bindPopup("<h4>Area Postcode: "+att.layer+"</h4> District Postcode: "+att.name+"<br>")
                 .addTo(mymap);


                }

           export function refreshLinears() {
                    $.ajax({
                        url: 'load_allpostcodes.php',
                        data: { tbl: 'merged', flds: 'id' },
                        type: 'GET',
                        success: function (response) {

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

