<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("dashboard_ticket") ?></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_ticket_today") ?></h5>
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
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_ticket_this_week") ?></h5>
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
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_ticket_this_month") ?></h5>
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
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_ticket_total"); ?> <?php echo  $this->lang->line("dashboard_ticket_open_close")  ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo ($count['open']); ?>/<?php echo ($count['close']); ?></span>
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
                            <h6 class="text-uppercase ls-1 mb-1"><?php echo  $this->lang->line("dashboard_ticket_overview") ?></h6>
                            <h2 class="mb-0"><?php echo $this->lang->line("dashboard_ticket_total_ticket") ?></h2>
                        </div>
                        <div class="col">
                            <ul class="nav nav-pills justify-content-end">
                                <li class="nav-item mr-2 mr-md-0" id='chart-semanal'>
                                    <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line("dashboard_ticket_weekly") ?></span>
                                    </a>
                                </li>
                                <li class="nav-item" id='chart-mensal'>
                                    <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line("dashboard_ticket_monthly") ?></span>
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
                            <h6 class="text-uppercase text-muted ls-1 mb-1"><?php echo $this->lang->line("dashboard_ticket_performance_last_few_months") ?></h6>
                            <h2 class="mb-0"><?php echo $this->lang->line("dashboard_ticket_by_month") ?></h2>
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
        <h6 class="h2 text-default d-inline-block mb-0"> <?php echo $this->lang->line("dashboard_ticket_period") ?></h6>
        <div id="reportrange" class='text-default'>
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
            <div class="card card pb-3">
                <div class="card-header border-0">
                    <h5 class="h3 mb-0"><?php echo $this->lang->line("dashboard_ticket_open_by_user") ?></h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-users" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("dashboard_ticket_column_user") ?></th>
                                <th><?php echo $this->lang->line("dashboard_ticket_column_qtd_user") ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card card pb-3">
                <div class="card-header border-0">
                    <h5 class="h3 mb-0"><?php echo $this->lang->line("dashboard_ticket_type_ticket") ?></h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-type" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("dashboard_ticket_column_type") ?></th>
                                <th><?php echo $this->lang->line("dashboard_ticket_column_qtd_tickek") ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card pb-3">
                <div class="card-header border-0">
                    <h5 class="h3 mb-0"><?php echo $this->lang->line("dashboard_ticket_status_ticket") ?></h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-status" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("dashboard_ticket_column_status") ?></th>
                                <th><?php echo $this->lang->line("dashboard_ticket_column_qtd_status") ?></th>
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

            <?php
            function ToComma($array)
            {
                $str = '';
                foreach ($array as $key => $item) {
                    $str .= "'" . $key . "',";
                }
                $str = rtrim($str, ',');
                return $str;
            }
            ?>


            $("#chart-mensal").click(function() {
                line_chart.data.datasets[0].data = [<?php echo implode(",", $month_chart); ?>];
                line_chart.data.labels = [<?php echo ToComma($month_chart); ?>];
                line_chart.options.title.text = GLOBAL_LANG.dashboard_ticket_period + ": " + moment().subtract(30, 'd').format('DD/MM/YYYY') + GLOBAL_LANG.dashboard_ticket_period_to + moment().format('DD/MM/YYYY');

                line_chart.update();
            });

            $("#chart-semanal").click(function() {
                line_chart.data.datasets[0].data = [<?php echo implode(",", $week_chart);  ?>];
                line_chart.data.labels = [<?php echo ToComma($week_chart); ?>];
                line_chart.options.title.text = GLOBAL_LANG.dashboard_ticket_period + ": " + moment().subtract(6, 'd').format('DD/MM/YYYY') + GLOBAL_LANG.dashboard_ticket_period_to + moment().format('DD/MM/YYYY');

                line_chart.update();
            });

            var line_chart = $('#line-chart');
            line_chart = new Chart(line_chart, {
                type: 'line',
                data: {
                    labels: [<?php echo ToComma($week_chart); ?>],
                    datasets: [{
                        data: [<?php echo implode(",", $week_chart); ?>],
                        label: "Tickets",
                        borderColor: "#5e72e4",
                        fill: false
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: GLOBAL_LANG.dashboard_ticket_period + ": " + moment().subtract('6', 'd').format('DD/MM/YYYY') + GLOBAL_LANG.dashboard_ticket_period_to + moment().format('DD/MM/YYYY')
                    }
                }
            });

            var bar_chart = $('#bar_chart');

            var monthNumber = [<?php echo ToComma($six_months_chart); ?>]
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
                        label: GLOBAL_LANG.dashboard_ticket_clicks,
                        data: [<?php echo implode(",", $six_months_chart); ?>]
                    }]
                }
            });

            function init() {
                try {
                    $('#datatable-users').DataTable({
                        ajax: {
                            type: "POST",
                            async: false,
                            data: {
                                "type": "count_users",
                                "dt-start": $('#dt-start').val(),
                                "dt-end": $('#dt-end').val()
                            }
                        },
                        columns: [{
                                "data": "user"
                            },
                            {
                                "data": "count"
                            }
                        ],
                        columnDefs: [{
                                targets: 0,
                                render: function(data, type, full, meta) {
                                    if (full.user_profile == "null") {
                                        return "<img src='assets/img/avatar.svg' class='avatar rounded-circle mr-3'>" + full.last_name;
                                    } else {
                                        return "<img src='" + full.user_profile + "' class='avatar rounded-circle mr-3'>" + full.last_name;
                                    }
                                }
                            },
                            {
                                orderable: false,
                                targets: [0]
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

                        },
                        order: [
                            [1, "desc"]
                        ]
                    });

                    $('#datatable-type').DataTable({
                        ajax: {
                            type: "POST",
                            async: false,
                            data: {
                                "type": "count_type",
                                "dt-start": $('#dt-start').val(),
                                "dt-end": $('#dt-end').val()
                            }
                        },
                        columns: [{
                                "data": "type"
                            },
                            {
                                "data": "count"
                            }
                        ],
                        columnDefs: [{
                                targets: 0,
                                render: function(data, type, full, meta) {
                                    return "<span class='badge badge-pill badge-success' style='color: black; background-color:" + full.color + ";'>" + full.type + "</span>";
                                }
                            },
                            {
                                orderable: false,
                                targets: [0]
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
                        },
                        order: [
                            [1, "desc"]
                        ]
                    });

                    $('#datatable-status').DataTable({
                        ajax: {
                            type: "POST",
                            async: false,
                            data: {
                                "type": "count_status",
                                "dt-start": $('#dt-start').val(),
                                "dt-end": $('#dt-end').val()
                            }
                        },
                        columns: [{
                                "data": "status"
                            },
                            {
                                "data": "count"
                            }
                        ],
                        columnDefs: [{
                                targets: 0,
                                render: function(data, type, full, meta) {
                                    return "<span class='badge badge-pill badge-success' style='color: black; background-color:" + full.color + ";'>" + full.status + "</span>";
                                }
                            },
                            {
                                orderable: false,
                                targets: [0]
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
                        },
                        order: [
                            [1, "desc"]
                        ]
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
                            "format": "DD/MM/YYYY",
                            "separator": " - ",
                            "applyLabel": "Aplicar",
                            "cancelLabel": "Cancelar",
                            "daysOfWeek": [
                                "Dom",
                                "Seg",
                                "Ter",
                                "Qua",
                                "Qui",
                                "Sex",
                                "Sab"
                            ],
                            "monthNames": [
                                "Janeiro",
                                "Fevereiro",
                                "Março",
                                "Abril",
                                "Maio",
                                "Junho",
                                "Julho",
                                "Agosto",
                                "Setembro",
                                "Outubro",
                                "Novembro",
                                "Dezembro"
                            ],
                            "firstDay": 1,
                            "customRangeLabel": "Determinar Período"
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
                            "format": "DD/MM/YYYY",
                            "separator": " - ",
                            "applyLabel": "Aplicar",
                            "cancelLabel": "Cancelar",
                            "daysOfWeek": [
                                "Sun",
                                "Mon",
                                "Ter",
                                "fou",
                                "Thu",
                                "Fri",
                                "Sat"
                            ],
                            "monthNames": [
                                "January",
                                "February",
                                "March",
                                "April",
                                "May",
                                "June",
                                "July",
                                "August",
                                "September",
                                "October",
                                "November",
                                "December"
                            ],
                            "firstDay": 1,
                            "customRangeLabel": "Determine Period"
                        }
                    }, cb);
                }

                cb(start, end);

            });

        });

    }, false);
</script>