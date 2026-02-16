<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("dashboard_shortlink"); ?></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_shortlink_today"); ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo ($count_day); ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="far fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_shortlink_this_week"); ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo ($count_week); ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                        <i class="far fa-calendar-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_shortlink_this_month"); ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo ($count_month); ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                        <i class="far fa-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_shortlink_total"); ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo ($count); ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                        <i class="ni ni-chart-bar-32"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase ls-1 mb-1"><?php echo $this->lang->line("dashboard_shortlink_overview") ?></h6>
                            <h2 class="mb-0"><?php echo $this->lang->line("dashboard_shortlink_total_ticket") ?></h2>
                        </div>
                        <div class="col">
                            <ul class="nav nav-pills justify-content-end">
                                <li class="nav-item mr-2 mr-md-0" id='chart-semanal'>
                                    <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line("dashboard_shortlink_weekly") ?></span>
                                    </a>
                                </li>
                                <li class="nav-item" id='chart-mensal'>
                                    <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line("dashboard_shortlink_monthly") ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding-left: 5px;padding-right: 5px;">
                    <div class="chart">
                        <canvas id="line-chart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1"><?php echo $this->lang->line("dashboard_shortlink_performance_last_few_months") ?></h6>
                            <h2 class="mb-0"><?php echo $this->lang->line("dashboard_shortlink_by_month") ?></h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="bar_chart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<div class="header bg-light pb-7 pt-4 pt-md-4 rounded-top mt-5">
    <div class="container-fluid">
        <h6 class="h2 text-default d-inline-block mb-0"><?php echo $this->lang->line("dashboard_shortlink_period") ?></h6>
        <div id="reportrange" class='text-default' style="cursor: pointer;">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <input id="dt-start" type="hidden" />
    <input id="dt-end" type="hidden" />
    <div class="row">
        <div class="col-xl-4">
            <div class="card pb-3">
                <div class="card-header border-0">

                    <h5 class="h3 mb-0"><?php echo $this->lang->line("dashboard_shortlink_country") ?></h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-country" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_country") ?></th>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_country_click") ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card pb-3">
                <div class="card-header border-0">
                    <h5 class="h3 mb-0"><?php echo $this->lang->line("dashboard_shortlink_states"); ?></h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-region" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_states"); ?></th>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_states_click"); ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card pb-3">
                <div class="card-header border-0">
                    <h5 class="h3 mb-0"><?php echo $this->lang->line("dashboard_shortlink_city") ?></h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-city" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_city") ?></th>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_city_click") ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card pb-3">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo $this->lang->line("dashboard_shortlink_browser") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-agent" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_browser") ?></th>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_browser_visitors") ?></th>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_browser_percentage") ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card pb-3">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo $this->lang->line("dashboard_shortlink_devices") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-device-version" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_devices_name") ?></th>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_devices_count") ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card pb-3">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo $this->lang->line("dashboard_shortlink_traffic_origin") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-page-visit" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_page") ?></th>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_visits") ?></th>
                                <th><?php echo $this->lang->line("dashboard_shortlink_column_unique_visit") ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function() {
            $('#navbar-search-main').hide();
            <?php
            function ToComma($array)
            {
                $str = '';
                foreach ($array as $key => $item) {
                    $str .= "'" . $key . "',";
                }
                $str = rtrim($str, ",");
                return $str;
            }
            ?>


            $("#chart-mensal").click(function() {
                line_chart.data.datasets[0].data = [<?php echo implode(",", $month_chart); ?>];
                line_chart.data.labels = [<?php echo ToComma($month_chart); ?>];
                line_chart.options.title.text = GLOBAL_LANG.dashboard_shortlink_period + ': ' + moment().subtract(30, 'd').format('DD/MM/YYYY') + " a " + moment().format('DD/MM/YYYY');

                line_chart.update();
            });

            $("#chart-semanal").click(function() {
                line_chart.data.datasets[0].data = [<?php echo implode(",", $week_chart);  ?>];
                line_chart.data.labels = [<?php echo ToComma($week_chart); ?>];
                line_chart.options.title.text = GLOBAL_LANG.dashboard_shortlink_period + ': ' + moment().subtract(6, 'd').format('DD/MM/YYYY') + " a " + moment().format('DD/MM/YYYY');

                line_chart.update();
            });

            var line_chart = $('#line-chart');
            line_chart = new Chart(line_chart, {
                type: 'line',
                data: {
                    labels: [<?php echo ToComma($week_chart); ?>],
                    datasets: [{
                        data: [<?php echo implode(",", $week_chart); ?>],
                        label: GLOBAL_LANG.dashboard_shortlink_click,
                        borderColor: "#5e72e4",
                        fill: false
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: GLOBAL_LANG.dashboard_shortlink_period + ': ' + moment().subtract(7, 'd').format('DD/MM/YYYY') + " a " + moment().format('DD/MM/YYYY')
                    }
                }
            });

            var bar_chart = $('#bar_chart');

            var monthNumber = [<?php echo ToComma($year_chart); ?>]
            var monthName = [];

            monthNumber.forEach(
                function(name) {
                    monthName.push(moment.monthsShort(parseInt(name) - 1))
                }
            );

            bar_chart = new Chart(bar_chart, {
                type: 'bar',
                data: {
                    labels: monthName,
                    datasets: [{
                        label: GLOBAL_LANG.dashboard_shortlink_click,
                        data: [<?php echo implode(",", $year_chart); ?>]
                    }]
                }
            });

            function init() {
                try {
                    $('#datatable-device-version').DataTable({
                        ajax: {
                            type: "POST",
                            async: false,
                            data: {
                                "type": "count_device_version",
                                "dt-start": $('#dt-start').val(),
                                "dt-end": $('#dt-end').val()
                            }
                        },
                        columns: [{
                                "data": "device_version"
                            },
                            {
                                "data": "count"
                            }
                        ],
                        pagingType: "numbers",
                        pageLength: 5,
                        destroy: true,
                        fixedHeader: true,
                        responsive: true,
                        lengthChange: false,
                        searching: false,
                        paginate: true,
                        info: true,
                        language: {
                            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
                        }
                    });

                    $('#datatable-country').DataTable({
                        ajax: {
                            type: "POST",
                            async: false,
                            data: {
                                "type": "count_country",
                                "dt-start": $('#dt-start').val(),
                                "dt-end": $('#dt-end').val()
                            }
                        },
                        columns: [{
                                "data": "country"
                            },
                            {
                                "data": "count"
                            }
                        ],
                        pagingType: "numbers",
                        pageLength: 5,
                        destroy: true,
                        fixedHeader: true,
                        responsive: true,
                        lengthChange: false,
                        searching: false,
                        paginate: true,
                        info: true,
                        language: {
                            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
                        }
                    });

                    $('#datatable-region').DataTable({
                        ajax: {
                            type: "POST",
                            async: false,
                            data: {
                                "type": "count_region",
                                "dt-start": $('#dt-start').val(),
                                "dt-end": $('#dt-end').val()
                            }
                        },
                        columns: [{
                                "data": "region"
                            },
                            {
                                "data": "count"
                            }
                        ],
                        pagingType: "numbers",
                        pageLength: 5,
                        destroy: true,
                        fixedHeader: true,
                        responsive: true,
                        lengthChange: false,
                        searching: false,
                        paginate: true,
                        info: true,
                        language: {
                            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
                        }
                    });

                    $('#datatable-city').DataTable({
                        ajax: {
                            type: "POST",
                            async: false,
                            data: {
                                "type": "count_city",
                                "dt-start": $('#dt-start').val(),
                                "dt-end": $('#dt-end').val()
                            }
                        },
                        columns: [{
                                "data": "city"
                            },
                            {
                                "data": "count"
                            }
                        ],
                        pagingType: "numbers",
                        pageLength: 5,
                        destroy: true,
                        fixedHeader: true,
                        responsive: true,
                        lengthChange: false,
                        searching: false,
                        paginate: true,
                        info: true,
                        language: {
                            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
                        }
                    });

                    $('#datatable-page-visit').DataTable({
                        ajax: {
                            type: "POST",
                            async: false,
                            data: {
                                "type": "count_visitor",
                                "dt-start": $('#dt-start').val(),
                                "dt-end": $('#dt-end').val()
                            }
                        },
                        columns: [{
                                "data": "page"
                            },
                            {
                                "data": "visitor"
                            },
                            {
                                "data": "visitor_unique"
                            }
                        ],
                        pagingType: "numbers",
                        pageLength: 5,
                        destroy: true,
                        fixedHeader: true,
                        responsive: true,
                        lengthChange: false,
                        searching: false,
                        paginate: true,
                        info: true,
                        language: {
                            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
                        }
                    });

                    $('#datatable-agent').DataTable({
                        ajax: {
                            type: "POST",
                            async: false,
                            data: {
                                "type": "count_agent",
                                "dt-start": $('#dt-start').val(),
                                "dt-end": $('#dt-end').val()
                            }
                        },
                        columns: [{
                                "data": "agent"
                            },
                            {
                                "data": "count"
                            },
                            {
                                "data": "percent"
                            }
                        ],
                        columnDefs: [{
                            targets: 2,
                            render: function(data, type, full, meta) {
                                return "<div class='d-flex align-items-center'><span class='completion mr-2'>" + full.percent + "%</span><div>" +
                                    "<div class='progress'>" +
                                    "<div class='progress-bar bg-success' role='progressbar' aria-valuenow='60' aria-valuemin='0' aria-valuemax='100' style='width: " + full.percent + "%;'></div></div></div></div>";
                            }
                        }],
                        pagingType: "numbers",
                        pageLength: 5,
                        destroy: true,
                        fixedHeader: true,
                        responsive: true,
                        lengthChange: false,
                        searching: false,
                        paginate: true,
                        info: true,
                        language: {
                            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
                        }
                    });
                } catch (e) {
                    document.location.reload(true);
                }
            }

            $(function() {
                if (GLOBAL_LANG.topnav_language == "pt_br") moment.locale('pt-BR');
                else moment.locale('en-US');
                var start = moment().subtract(6, 'days');
                var end = moment();

                function cb(start, end) {
                    $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                    $('#dt-start').val(start.format('YYYY-MM-DD'));
                    $('#dt-end').val(end.format('YYYY-MM-DD'));

                    init();
                }

                if (GLOBAL_LANG.topnav_language == "pt_br") {
                    $('#reportrange').daterangepicker({
                        startDate: start,
                        endDate: end,
                        ranges: {
                            'Hoje': [moment(), moment()],
                            'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Últimos 7 dias': [moment().subtract(6, 'days'), moment()],
                            'Últimos 30 dias': [moment().subtract(29, 'days'), moment()],
                            'Este mês': [moment().startOf('month'), moment().endOf('month')],
                            'Mês passado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        locale: {
                            "format": GLOBAL_LANG.dashboard_days_of_week_date_format,
                            "separator": " - ",
                            "applyLabel": GLOBAL_LANG.dashboard_days_of_week_apply,
                            "cancelLabel": GLOBAL_LANG.dashboard_days_of_week_cancel,
                            "daysOfWeek": [
                                GLOBAL_LANG.dashboard_days_of_week_sun,
                                GLOBAL_LANG.dashboard_days_of_week_mon,
                                GLOBAL_LANG.dashboard_days_of_week_tue,
                                GLOBAL_LANG.dashboard_days_of_week_wed,
                                GLOBAL_LANG.dashboard_days_of_week_thu,
                                GLOBAL_LANG.dashboard_days_of_week_fri,
                                GLOBAL_LANG.dashboard_days_of_week_sat
                            ],
                            "monthNames": [
                                GLOBAL_LANG.dashboard_month_of_year_jan,
                                GLOBAL_LANG.dashboard_month_of_year_feb,
                                GLOBAL_LANG.dashboard_month_of_year_mar,
                                GLOBAL_LANG.dashboard_month_of_year_apr,
                                GLOBAL_LANG.dashboard_month_of_year_may,
                                GLOBAL_LANG.dashboard_month_of_year_jun,
                                GLOBAL_LANG.dashboard_month_of_year_jul,
                                GLOBAL_LANG.dashboard_month_of_year_aug,
                                GLOBAL_LANG.dashboard_month_of_year_sep,
                                GLOBAL_LANG.dashboard_month_of_year_oct,
                                GLOBAL_LANG.dashboard_month_of_year_nov,
                                GLOBAL_LANG.dashboard_month_of_year_dec
                            ],
                            "firstDay": 1,
                            "customRangeLabel": GLOBAL_LANG.dashboard_custom_range_label_set_period
                        }
                    }, cb);
                } else {
                    $('#reportrange').daterangepicker({
                        startDate: start,
                        endDate: end,
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 days': [moment().subtract(29, 'days'), moment()],
                            'This month': [moment().startOf('month'), moment().endOf('month')],
                            'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        locale: {
                            "format": GLOBAL_LANG.dashboard_days_of_week_date_format,
                            "separator": " - ",
                            "applyLabel": GLOBAL_LANG.dashboard_days_of_week_apply,
                            "cancelLabel": GLOBAL_LANG.dashboard_days_of_week_cancel,
                            "daysOfWeek": [
                                GLOBAL_LANG.dashboard_days_of_week_sun,
                                GLOBAL_LANG.dashboard_days_of_week_mon,
                                GLOBAL_LANG.dashboard_days_of_week_tue,
                                GLOBAL_LANG.dashboard_days_of_week_wed,
                                GLOBAL_LANG.dashboard_days_of_week_thu,
                                GLOBAL_LANG.dashboard_days_of_week_fri,
                                GLOBAL_LANG.dashboard_days_of_week_sat
                            ],
                            "monthNames": [
                                GLOBAL_LANG.dashboard_month_of_year_jan,
                                GLOBAL_LANG.dashboard_month_of_year_feb,
                                GLOBAL_LANG.dashboard_month_of_year_mar,
                                GLOBAL_LANG.dashboard_month_of_year_apr,
                                GLOBAL_LANG.dashboard_month_of_year_may,
                                GLOBAL_LANG.dashboard_month_of_year_jun,
                                GLOBAL_LANG.dashboard_month_of_year_jul,
                                GLOBAL_LANG.dashboard_month_of_year_aug,
                                GLOBAL_LANG.dashboard_month_of_year_sep,
                                GLOBAL_LANG.dashboard_month_of_year_oct,
                                GLOBAL_LANG.dashboard_month_of_year_nov,
                                GLOBAL_LANG.dashboard_month_of_year_dec
                            ],
                            "firstDay": 1,
                            "customRangeLabel": GLOBAL_LANG.dashboard_custom_range_label_set_period
                        }
                    }, cb);
                }

                cb(start, end);

            });

        });

    }, false);
</script>