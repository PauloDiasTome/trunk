<?php

function htmlRender($type = "neutral", $valor = 0, $lang_neutral)
{
    if ($type == "neutral") {
        $html = "
            <span class='text-info mr-2'>
                <i class='far fa-circle mr-1'></i> {$lang_neutral}
            </span>
        ";
    }

    if ($type == "up") {
        $html = "
            <span class='text-success mr-2'>
                <i class='fa fa-arrow-up'></i> $valor %
            // </span>
        ";
    }

    if ($type == "down") {
        $html = "
            <span class='text-danger mr-2'>
                <i class='fa fa-arrow-down'></i> $valor %
            </span>
        ";
    }

    return $html;
}

function StatusType($recent = 0, $previous = 0, $lang_neutral)
{
    if ($recent == 0 && $previous == 0) {
        return htmlRender("neutral", 0, $lang_neutral);
    }

    if ($previous == 0) {
        $previous = $recent;
    }

    $result = (($recent * 100) / $previous);
    $result = $result > 0 ? ($result - 100) : $result;
    $result = number_format($result, 2, ',', '.');

    if ($result < 0) {
        return htmlRender("down", $result, $lang_neutral);
    } else {

        return htmlRender("up", $result, $lang_neutral);
    }
}
?>

<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">

            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 d-inline-block mb-0 text-white"><?php echo $this->lang->line('dashboard_attendance_calls'); ?></h6>
                </div>
            </div>

            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line('dashboard_attendance_today'); ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo $count_calls['hoje_aberto'] ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="far fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-1 text-sm">
                                <?php echo StatusType($count_calls['hoje_aberto'], $count_calls['ontem_aberto'], $this->lang->line('dashboard_attendance_neutral')); ?>
                                <span class="text-nowrap"><?php echo $this->lang->line('dashboard_attendance_since_yesterday'); ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line('dashboard_attendance_this_week'); ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo $count_calls['semana_aberto'] ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                        <i class="far fa-calendar-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-1 text-sm">
                                <?php echo StatusType($count_calls['semana_aberto'], $count_calls['semana_passada_aberto'], $this->lang->line('dashboard_attendance_neutral')); ?>
                                <span class="text-nowrap"><?php echo $this->lang->line('dashboard_attendance_since_last_week'); ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line('dashboard_attendance_this_month'); ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo $count_calls['mes_aberto'] ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                        <i class="far fa-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-1 text-sm">
                                <?php echo StatusType($count_calls['mes_aberto'], $count_calls['mes_passado_aberto'], $this->lang->line('dashboard_attendance_neutral')); ?>
                                <span class="text-nowrap"><?php echo $this->lang->line('dashboard_attendance_since_last_month'); ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line('dashboard_attendance_total'); ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo $count_calls['total_aberto'] . " / " . $count_calls['total_fechado']; ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                        <i class="ni ni-chart-bar-32"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0 text-sm">
                                <small class="small text-nowrap"><?php echo $this->lang->line('dashboard_attendance_open_close'); ?></small>
                                <br>
                                <span class="text-nowrap"><?php echo $this->lang->line('dashboard_attendance_performed'); ?>: </span>
                                <span class="text-success font-weight-bold  mr-2"> <?php echo $count_calls['total_fechado']; ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <h6 class="text-uppercase ls-1 mb-1"><?php echo $this->lang->line('dashboard_attendance_overview'); ?></h6>
                            <h2 class="mb-0"><?php echo $this->lang->line('dashboard_attendance_open_calls'); ?></h2>
                        </div>
                        <div class="col-7">
                            <ul class="nav nav-pills justify-content-end">
                                <li class="nav-item mr-2 mr-md-0" id='chart-semanal'>
                                    <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_attendance_weekly'); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item" id='chart-mensal'>
                                    <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_attendance_monthly'); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="chart-calls-group" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <h6 class="text-uppercase ls-1 mb-1"><?php echo $this->lang->line('dashboard_attendance_overview'); ?></h6>
                            <h2 class="mb-0"><?php echo $this->lang->line('dashboard_attendance_new_contacts'); ?></h2>
                        </div>
                        <div class="col-7">
                            <ul class="nav nav-pills justify-content-end">
                                <li class="nav-item mr-2 mr-md-0" id='chart-contatos-semanal'>
                                    <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_attendance_weekly'); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item" id='chart-contatos-mensal'>
                                    <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_attendance_monthly'); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="chart-contact-group" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <!-- Title -->
                    <h5 class="h3 mb-0"><?php echo $this->lang->line('dashboard_attendance_open_calls'); ?> - <?php echo $count_calls_open; ?> <?php echo $this->lang->line("dashboard_attendance_open_calls_contact") ?>(s)</h5>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <div class="avatar-group">
                        <?php
                        foreach ($list_calls_open as $row) { ?>
                            <a href="#" class="avatar avatar-lg rounded-circle" style='margin-left: 0px' data-toggle="tooltip" data-original-title="<?php echo $row['full_name']; ?>">
                                <img src="<?php echo $row['profile']; ?>">
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <!-- Title -->
                    <h5 class="h3 mb-0"><?php echo $this->lang->line('dashboard_attendance_waiting_for_service'); ?> - <?php echo $count_wait_list; ?> <?php echo $this->lang->line('dashboard_attendance_waiting_for_service_contact'); ?>(s)</h5>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <div class="avatar-group">
                        <?php
                        foreach ($list_wait_list as $row) { ?>
                            <a href="#" class="avatar avatar-lg rounded-circle" style='margin-left: 0px' data-toggle="tooltip" data-original-title="<?php echo $row['full_name']; ?>">
                                <img src="<?php echo $row['profile']; ?>">
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo $this->lang->line('dashboard_attendance_social_traffic'); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" align="center"><?php echo $this->lang->line('dashboard_attendance_platform'); ?></th>
                                <th scope="col" align="center"><?php echo $this->lang->line('dashboard_attendance_contacts'); ?></th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            foreach ($count_plataform as $plataform) {
                                $total = $total + (int)$plataform['data'];
                            } ?>
                            <?php foreach ($count_plataform as $plataform) { ?>
                                <tr>
                                    <th scope="row">
                                        <?php echo $plataform['label']; ?>
                                    </th>
                                    <td align="center">
                                        <?php echo $plataform['data']; ?>
                                    </td>
                                    <td align="center">
                                        <?php
                                        $resultado = round(((int)$plataform['data'] * 100) / $total, 2);
                                        ?>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2"><?php echo $resultado; ?>%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="<?php echo $resultado ?>" style="width: <?php echo $resultado ?>%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php  } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo $this->lang->line('dashboard_attendance_message_traffic'); ?></h3>
                        </div>
                    </div>
                </div>
                <?php
                $total = 0;
                foreach ($count_messages as $row) {
                    $total = $total + (int)$row['data'];
                } ?>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">

                            <tr>
                                <th scope="col" align="center"></th>
                                <th scope="col" align="center"><?php echo $this->lang->line('dashboard_attendance_message'); ?></th>
                                <th scope="col" align="center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($count_messages as $row) { ?>
                                <tr>
                                    <th scope="row">
                                        <?php echo $row['label'] == "send" ? $this->lang->line('dashboard_attendance_sent') : $this->lang->line('dashboard_attendance_received'); ?>
                                    </th>
                                    <td align="center">
                                        <?php echo $row['data']; ?>
                                    </td>
                                    <td align="center">
                                        <?php $resultado = round(((int)$row['data'] * 100) / $total, 2); ?>

                                        <div class="d-flex align-items-center">
                                            <span class="mr-2"><?php echo $resultado ?>%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar <?php echo $row['label'] == 'received' ? 'bg-success' : 'bg-danger'; ?> role=" progressbar aria-valuenow="<?php echo $resultado ?>" aria-valuemin="0" aria-valuemax="<?php echo $resultado ?>" style="width: <?php echo $resultado ?>%;"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th scope="row">Total</th>
                                <td align="center" colspan="2"><?php echo $total; ?>
                            </tr>
                        </tbody>
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
                            <h3 class="mb-0"><?php echo $this->lang->line('dashboard_attendance_user_average'); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush" id="datatable-user-presence" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line('dashboard_attendance_user'); ?></th>
                                <th><?php echo $this->lang->line('dashboard_attendance_average'); ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    <?php
    function ToLabel($arr)
    {
        $labels = "";
        $count = 0;
        foreach ($arr as $row) {
            if ($count == 0) {
                $labels = "'" . $row['label'] . "'";
            } else {
                $labels .= ",'" . $row['label'] . "'";
            }
            $count++;
        }
        return $labels;
    }

    function ToData($arr)
    {
        $data = "";
        $count = 0;
        foreach ($arr as $row) {
            if ($count == 0) {
                $data = $row['data'];
            } else {
                $data .= "," . $row['data'];
            }
            $count++;
        }
        return $data;
    }
    ?>

    document.addEventListener('DOMContentLoaded', function() {

        $('#navbar-search-main').hide();

        $("#chart-mensal").click(function() {
            chart_calls_group.data.datasets[0].data = [<?php echo ToData($count_calls_group_month); ?>];
            chart_calls_group.data.labels = [<?php echo  ToLabel($count_calls_group_month); ?>];
            chart_calls_group.options.title.text = GLOBAL_LANG.dashboard_attendance_period + ": " + moment().subtract(31, 'd').format('DD/MM/YYYY') + GLOBAL_LANG.dashboard_attendance_period_to + moment().format('DD/MM/YYYY');

            chart_calls_group.update();
        });

        $("#chart-semanal").click(function() {
            chart_calls_group.data.datasets[0].data = [<?php echo  ToData($count_calls_group_week);  ?>];
            chart_calls_group.data.labels = [<?php echo  ToLabel($count_calls_group_week); ?>];
            chart_calls_group.options.title.text = GLOBAL_LANG.dashboard_attendance_period + ": " + moment().subtract(7, 'd').format('DD/MM/YYYY') + GLOBAL_LANG.dashboard_attendance_period_to + moment().format('DD/MM/YYYY');

            chart_calls_group.update();
        });

        $("#chart-contatos-mensal").click(function() {
            chart_calls.data.datasets[0].data = [<?php echo ToData($count_contact_month); ?>];
            chart_calls.data.labels = [<?php echo  ToLabel($count_contact_month); ?>];
            chart_calls.options.title.text = GLOBAL_LANG.dashboard_attendance_period + ": " + moment().subtract(31, 'd').format('DD/MM/YYYY') + GLOBAL_LANG.dashboard_attendance_period_to + moment().format('DD/MM/YYYY');

            chart_calls.update();
        });

        $("#chart-contatos-semanal").click(function() {
            chart_calls.data.datasets[0].data = [<?php echo  ToData($count_contact_week);  ?>];
            chart_calls.data.labels = [<?php echo  ToLabel($count_contact_week); ?>];
            chart_calls.options.title.text = GLOBAL_LANG.dashboard_attendance_period + ": " + moment().subtract(7, 'd').format('DD/MM/YYYY') + GLOBAL_LANG.dashboard_attendance_period_to + moment().format('DD/MM/YYYY');

            chart_calls.update();
        });

        var labels_calls = [<?php echo ToLabel($count_calls_group_week); ?>];
        var data_calls = [<?php echo ToData($count_calls_group_week); ?>];

        var chart_calls_group = new Chart(document.getElementById("chart-calls-group"), {
            type: 'line',
            data: {
                labels: labels_calls,
                datasets: [{
                    label: GLOBAL_LANG.dashboard_attendance_attendances,
                    data: data_calls
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: GLOBAL_LANG.dashboard_attendance_period + ": " + moment().subtract(7, 'd').format('DD/MM/YYYY') + GLOBAL_LANG.dashboard_attendance_period_to + moment().format('DD/MM/YYYY')
                }
            }
        });

        var labels_contact = [<?php echo ToLabel($count_contact_week); ?>];
        var data_contact = [<?php echo ToData($count_contact_week); ?>];

        var chart_calls = new Chart(document.getElementById("chart-contact-group"), {
            type: 'line',
            data: {
                labels: labels_contact,
                datasets: [{
                    label: GLOBAL_LANG.dashboard_attendance_contacts,
                    data: data_contact
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: GLOBAL_LANG.dashboard_attendance_period + ": " + moment().subtract(7, 'd').format('DD/MM/YYYY') + GLOBAL_LANG.dashboard_attendance_period_to + moment().format('DD/MM/YYYY')
                }
            }
        });

        var jsonData = JSON.parse('<?php echo json_encode($user_avg_presence); ?>');
        $('#datatable-user-presence').DataTable({
            "data": jsonData,
            "columns": [{
                    "data": "last_name"
                },
                {
                    "data": "time_online"
                },
            ],
            "columnDefs": [{
                    "targets": 0,
                    "render": function(data, type, full, meta) {
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
            "pagingType": "numbers",
            "pageLength": 5,
            "destroy": true,
            "fixedHeader": true,
            "responsive": true,
            "lengthChange": false,
            "searching": false,
            "paginate": true,
            "info": true,
            "language": {
                "url": `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
            }
        });

    }, false);
</script>