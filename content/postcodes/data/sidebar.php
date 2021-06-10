<?php
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
        ?>
