<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-4 d-flex align-items-center">
                    <h6 class="h2 text-white mb-0" id="dashboard_title">
                        <?php echo $this->lang->line('dashboard_attendance_title'); ?>
                    </h6>
                </div>

                <div class="col-lg-8 d-flex justify-content-end align-items-center">
                    <div class="form-group mb-0 mr-3">
                        <select id="selectattendanceSector" class="start-slim">
                            <option value=""><?php echo $this->lang->line("dashboard_attendance_filter_select") ?></option>
                            <?php foreach ($sectors as $row) { ?>
                                <option value="<?php echo $row['id_user_group']; ?>"> <?php echo $row['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group mb-0 mr-3">
                        <select id="selectattendanceChannel" class="start-slim">
                            <?php foreach ($channels as $row) { ?>
                                <option value="<?php echo $row['id_channel']; ?>"> <?php echo $row['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group mb-0 mr-3">
                        <select class="form-control filter-top" id="selectattendancePeriod">
                            <option value="today"><?php echo $this->lang->line("dashboard_attendance_filter_select_today") ?></option>
                            <option value="yesterday"><?php echo $this->lang->line("dashboard_attendance_filter_select_yesterday") ?></option>
                            <option value="week"><?php echo $this->lang->line("dashboard_attendance_filter_select_week") ?></option>
                            <option value="15_days"><?php echo $this->lang->line("dashboard_attendance_filter_select_fifteen_days") ?></option>
                            <option value="this_month"><?php echo $this->lang->line("dashboard_attendance_filter_select_this_month") ?></option>
                            <option value="last_month"><?php echo $this->lang->line("dashboard_attendance_filter_select_last_month") ?></option>
                            <option value="two_months_ago"><?php echo $this->lang->line("dashboard_attendance_filter_select_two_months_ago") ?></option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats circle">
                        <div class="card-body">
                            <div class="row flex-top">
                                <div class="col">
                                    <h5 class="card-title text-muted mb-0"><?php echo $this->lang->line('dashboard_attendance_header_avg_wait_time'); ?>
                                        <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_attendance_header_tooltip_avg_wait_time") ?>" style="margin-right: 3px;"></i>
                                    </h5>
                                    <span class='mr-2'>
                                        <span class="mb-5 font-weight-600 percent" id="avgWaitTime">0h 0m 0s</span>
                                        <img src="<?php echo base_url("assets/img/loads/loading_1.gif") ?>" alt="loading" style="width: 36px; height: 36px; display:none" id="loadingAvgWaitTime">
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats circle">
                        <div class="card-body">
                            <div class="row flex-top">
                                <div class="col">
                                    <h5 class="card-title text-muted mb-0"><?php echo $this->lang->line('dashboard_attendance_header_avg_response_time'); ?>
                                        <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_attendance_header_tooltip_avg_response_time") ?>" style="margin-right: 3px;"></i>
                                    </h5>
                                    <span class='mr-2'>
                                        <span class="mb-5 font-weight-600 percent" id="avgResponseTime">0h 0m 0s</span>
                                        <img src="<?php echo base_url("assets/img/loads/loading_1.gif") ?>" alt="loading" style="width: 36px; height: 36px; display:none" id="loadingAvgResponseTime">
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape  bg-gradient-red text-white rounded-circle shadow">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats circle">
                        <div class="card-body">
                            <div class="row flex-top">
                                <div class="col">
                                    <h5 class="card-title text-muted mb-0"><?php echo $this->lang->line('dashboard_attendance_header_avg_service_time'); ?>
                                        <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_attendance_header_tooltip_avg_service_time") ?>" style="margin-right: 3px;"></i>
                                    </h5>
                                    <span class='mr-2'>
                                        <span class="mb-5 font-weight-600 percent" id="avgServiceTime">0h 0m 0s</span>
                                        <img src="<?php echo base_url("assets/img/loads/loading_1.gif") ?>" alt="loading" style="width: 36px; height: 36px; display:none" id="loadingAvgServiceTime">
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow">
                                        <i class="fas fa-stopwatch"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats circle">
                        <div class="card-body">
                            <div class="row flex-top">
                                <div class="col">
                                    <h5 class="card-title text-muted mb-0"><?php echo $this->lang->line('dashboard_attendance_header_total_attendances'); ?>
                                        <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_attendance_header_tooltip_total_attendances") ?>" style="margin-right: 3px;"></i>
                                    </h5>
                                    <span class='mr-2'>
                                        <span class="mb-5 font-weight-600 percent" id="totalAttendances">0</span>
                                        <img src="<?php echo base_url("assets/img/loads/loading_1.gif") ?>" alt="loading" style="width: 36px; height: 36px; display:none" id="loadingTotalAttendances">
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-teal text-white rounded-circle shadow">
                                        <i class="fas fa-comments"></i>
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
        <!-- Gráfico de Barras -->
        <div class="col-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h2 class="mb-0">
                        <?php echo $this->lang->line('dashboard_attendance_graph_abandonment_title') ?>
                        <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_attendance_graph_abandonment_summary") ?>" style="margin-right: 3px;"></i>
                    </h2>
                </div>
                <div class="card-body">
                    <div style="position: relative; width: 100%; height: 415px;">
                        <canvas id="graph_chatbot_abandonment"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Pizza -->
        <div class="col-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h2 class="mb-0">
                        <?php echo $this->lang->line('dashboard_attendance_graph_chatbot_origin_no_title') ?>
                        <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_attendance_graph_chatbot_origin_summary") ?>" style="margin-right: 3px;"></i>
                    </h2>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: center; align-items: start; height: 415px;">
                        <canvas id="graph_attendance_origin" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h6 class="text-uppercase ls-1 mb-1"></h6>
                            <h2 class="mb-0">
                                <?php echo $this->lang->line('dashboard_attendance_graph_chatbot_quantitative_title') ?>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_attendance_graph_chatbot_quantitative_summary") ?>" style="margin-right: 3px;"></i>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: center; align-items: start; height: 415px;">
                        <canvas id="graph_chatbot_quantitative"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h6 class="text-uppercase ls-1 mb-1"></h6>
                            <h2 class="mb-0">
                                <?php echo $this->lang->line('dashboard_attendance_graph_started_end_closed_title') ?>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_attendance_graph_started_end_closed_summary") ?>" style="margin-right: 3px;"></i>
                                <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_monthly'); ?></span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: center; align-items: start; height: 412px;">
                        <canvas id="graph_started_end_closed"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h2 class="mb-0">
                                <?php echo $this->lang->line('dashboard_attendance_graph_category_title') ?>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_attendance_graph_category_summary") ?>" style="margin-right: 3px;"></i>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="" style="max-height: 415px;display: flex;justify-content: center;">
                        <div style="display: flex; justify-content: center; align-items: start; height: 415px;">
                            <canvas id="categoryDistributionChart" width="1054" height="405"></canvas>
                        </div>
                        <div id="categoryNoDataMessage" role="alert" aria-live="polite" style="display: none; text-align: center; padding: 1rem; font-size: 1.2rem;">
                            <?php echo $this->lang->line('dashboard_attendance_graph_category_no_data') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h2 class="mb-0">
                                <?php echo $this->lang->line('dashboard_attendance_graph_peak_service_title') ?>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('dashboard_attendance_graph_peak_service_tooltip') ?>" style="margin-left: 2px;"></i>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: center; align-items: start; height: 415px;">
                        <canvas id="graph_peak_service" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="pb-3">
                    <div class="row">
                        <div class="col">
                            <div class="card-header border-0 bg-transparent">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <h2 class="table-user-title mb-0">
                                        <?php echo $this->lang->line('dashboard_attendance_table_user_title') ?> <span id="total-users" style="font-weight: normal; font-size: 0.8em; color: gray;"></span>
                                    </h2>
                                    <input type="text" class="form-control" id="search-input" placeholder="<?php echo $this->lang->line('dashboard_attendance_table_placeholder_search') ?>" style="max-width: 250px;">
                                </div>
                            </div>
                            <hr style="margin-top:0rem; margin-bottom:1.5rem;">
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th><?php echo $this->lang->line("dashboard_attendance_table_user_name") ?></th>
                                            <th><?php echo $this->lang->line("dashboard_attendance_table_user_start") ?></th>
                                            <th><?php echo $this->lang->line("dashboard_attendance_table_user_in_service") ?></th>
                                            <th><?php echo $this->lang->line("dashboard_attendance_table_user_on_hold") ?></th>
                                            <th><?php echo $this->lang->line("dashboard_attendance_table_user_finished") ?></th>
                                            <th><?php echo $this->lang->line("dashboard_attendance_table_user_ast") ?></th>
                                            <th><?php echo $this->lang->line("dashboard_attendance_table_user_art") ?></th>
                                            <th><?php echo $this->lang->line("dashboard_attendance_table_user_rating_average") ?></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th><?php echo $this->lang->line("dashboard_attendance_table_user_name") ?></th>
                                    <th><?php echo $this->lang->line("dashboard_attendance_table_user_start") ?></th>
                                    <th><?php echo $this->lang->line("dashboard_attendance_table_user_in_service") ?></th>
                                    <th><?php echo $this->lang->line("dashboard_attendance_table_user_on_hold") ?></th>
                                    <th><?php echo $this->lang->line("dashboard_attendance_table_user_finished") ?></th>
                                    <th><?php echo $this->lang->line("dashboard_attendance_table_user_ast") ?></th>
                                    <th><?php echo $this->lang->line("dashboard_attendance_table_user_art") ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/docx@9.0.3/build/index.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://unpkg.com/docx@8.0.1/build/index.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>