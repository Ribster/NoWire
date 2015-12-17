/**
 * Created by Robbe on 3/12/15.
 */

$(function () {
    if($("#moduleOnlineGraph").length != 0) {


        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });

        if($("#moduleIdentif").length != 0) {
            // current page of the module is selected
            // online module id exists
            var modTxt = $("#moduleIdentif").text();

            // get if module is on- or offline
            $.getJSON( "json_getOnlineModule.php" + "?s=" + modTxt, function( data ) {

                var modID = parseInt(data.ID);

                $.getJSON('from-sql2.php?mod=' + modID + '&callback=?', function (dataj) {

                    // split the data set into ohlc and volume
                    var volume = [],
                        dataLength2 = dataj.length,
                        ii;

                    ii = 0;

                    for (ii; ii < dataLength2; ii += 1) {
                        volume.push([
                            dataj[ii][0], // the date
                            parseInt(dataj[ii][1]) // open
                        ]);
                    }

                    // create the chart
                    $('#moduleOnlineGraph').highcharts('StockChart', {

                        chart : {
                            events : {
                                load : function () {

                                    // set up the updating of the chart each second
                                    var series = this.series[0];
                                    setInterval(function () {
                                        var x = (new Date()).getTime();
                                        $.getJSON( "json_getOnlineModule.php" + "?s=" + modTxt, function( data ) {

                                            series.addPoint([x, parseInt(data.online)], true, true);
                                        })

                                    }, 5000);
                                }
                            }
                        },

                        rangeSelector: {
                            buttons: [{
                                count: 15,
                                type: 'minute',
                                text: '15M'
                            }, {
                                count: 1,
                                type: 'hour',
                                text: '1HR'
                            }, {
                                count: 2,
                                type: 'hour',
                                text: '2HR'
                            }, {
                                count: 5,
                                type: 'hour',
                                text: '5HR'
                            }, {
                                count: 1,
                                type: 'day',
                                text: '1D'
                            }, {
                                count: 1,
                                type: 'week',
                                text: '1W'
                            }, {
                                type: 'all',
                                text: 'All'
                            }],
                            inputEnabled: false,
                            selected: 5
                        },

                        yAxis: {
                            title: {
                                text: 'ONLINE'
                            },
                            opposite: false,
                            min: 0, // this sets minimum values of y to 0,
                            max: 1,
                        },

                        title : {
                            text : 'MODULE ONLINE'
                        },

                        xAxis: {
                            ordinal: false
                        },

                        exporting: {
                            enabled: false
                        },

                        colors: ['#8fee7c'],

                        series : [{
                            name : 'ONLINE',
                            data : volume,
                            type: 'area',
                            dataGrouping: {
                                enabled: false
                            }
                        }]

                    });

                });

            });

        };

    };

    if($("#sensorDataRealtime").length != 0) {


        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });

        $.getJSON("json_getSensorLiveValue.php", function (dataLive) {

            var sens_label = dataLive.label;
            var sens_Title = dataLive.title;
            var sensID = parseInt(dataLive.sensID);
            var pointName = dataLive.pointName;
            var timeIncrement = parseInt(dataLive.timeIncrement);
            var minVal = parseInt(dataLive.minVal);
            var maxVal = parseInt(dataLive.maxVal);
            var graphColor = dataLive.kleur;

            $.getJSON('from-sql.php?callback=?', function (data) {

                // split the data set into ohlc and volume
                var ohlc = [],
                    volume = [],
                    dataLength = data.length,
                    i = 0;

                for (i; i < dataLength; i += 1) {
                    ohlc.push([
                        data[i][0], // the date
                        parseInt(data[i][1]) // open
                    ]);
                }

                $.getJSON('from-sql2.php?callback=?', function (dataj) {

                    // split the data set into ohlc and volume
                    var volume = [],
                        dataLength2 = dataj.length,
                        ii;

                    ii = 0;

                    for (ii; ii < dataLength2; ii += 1) {
                        volume.push([
                            dataj[ii][0], // the date
                            parseInt(dataj[ii][1]) // open
                        ]);
                    }

                    // create the chart
                    $('#sensorDataRealtime').highcharts('StockChart', {

                        chart: {
                            events: {
                                load: function () {

                                    // set up the updating of the chart each second
                                    var series1 = this.series[0];
                                    var series2 = this.series[1];
                                    setInterval(function () {
                                        var x = (new Date()).getTime();

                                        $.get("json_getSensorValue.php", function (datay) {

                                            series1.addPoint([x, parseFloat(datay.value)], true, false);
                                            series2.addPoint([x, parseInt(datay.online)], true, false);

                                        }, "json");




                                    }, timeIncrement);

                                }
                            }
                        },

                        colors: [graphColor,'#8fee7c'],

                        navigator: {
                            series: {
                                type: 'areaspline'
                            }
                        },

                        rangeSelector: {
                            buttons: [{
                                count: 1,
                                type: 'minute',
                                text: '1M'
                            }, {
                                count: 5,
                                type: 'minute',
                                text: '5M'
                            }, {
                                count: 15,
                                type: 'minute',
                                text: '15M'
                            }, {
                                count: 30,
                                type: 'minute',
                                text: '30M'
                            }, {
                                count: 1,
                                type: 'hour',
                                text: '1HR'
                            }, {
                                count: 2,
                                type: 'hour',
                                text: '2HR'
                            }, {
                                count: 5,
                                type: 'hour',
                                text: '5HR'
                            }, {
                                count: 1,
                                type: 'day',
                                text: '1D'
                            }, {
                                count: 1,
                                type: 'week',
                                text: '1W'
                            }, {
                                type: 'all',
                                text: 'All'
                            }],
                            inputEnabled: false,
                            selected: 8
                        },

                        title: {
                            text: sens_Title
                        },

                        xAxis: {
                            gapGridLineWidth: 0,
                            ordinal: false
                        },

                        yAxis: [{
                            labels: {
                                align: 'right',
                                x: -3
                            },
                            title: {
                                text: sens_label
                            },
                            height: '60%',
                            lineWidth: 2,
                            gridLineDashStyle: 'longdash',
                            opposite: false,
                            min: 0 // this sets minimum values of y to 0
                        }, {
                            labels: {
                                align: 'right',
                                x: -3
                            },
                            title: {
                                text: 'ONLINE'
                            },
                            top: '65%',
                            height: '35%',
                            offset: 0,
                            lineWidth: 2,
                            opposite: false,
                            min: 0 // this sets minimum values of y to 0
                        }],

                        series: [{
                            name: pointName,
                            data: ohlc,
                            type: 'areaspline',
                            marker : {
                                enabled : true,
                                radius : 3
                            }
                        }, {
                            name: 'ONLINE',
                            data: volume,
                            type : 'area',
                            yAxis: 1,
                            dataGrouping: {
                                enabled: false
                            },
                            marker : {
                                enabled : true,
                                radius : 3
                            },
                            min: 0, // this sets minimum values of y to 0,
                            max: 1 // this sets minimum values of y to 0
                        }]
                    });

                });


            });
        });


    }

});