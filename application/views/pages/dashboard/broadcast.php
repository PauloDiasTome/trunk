<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-7 d-flex align-items-center">
                    <h6 class="h2 text-white mb-0" id="dashboard_title">
                        <?php echo $this->lang->line('dashboard_broadcast_title'); ?>
                    </h6>
                </div>

                <div class="col-lg-5 d-flex justify-content-end align-items-center">
                    <div class="form-group mb-0 mr-3">
                        <select class="form-control filter-top" id="selectBroadcastChannel">
                            <?php foreach ($channels as $row) { ?>
                                <option value="<?php echo $row['id_channel']; ?>"> <?php echo $row['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group mb-0 mr-3">
                        <select class="form-control filter-top" id="selectBroadcastPeriod">
                            <option value="today"><?php echo $this->lang->line("dashboard_broadcast_filter_select_today") ?></option>
                            <option value="yesterday"><?php echo $this->lang->line("dashboard_broadcast_filter_select_yesterday") ?></option>
                            <option value="week"><?php echo $this->lang->line("dashboard_broadcast_filter_select_week") ?></option>
                            <option value="15_days"><?php echo $this->lang->line("dashboard_broadcast_filter_select_fifteen_days") ?></option>
                            <option value="this_month"><?php echo $this->lang->line("dashboard_broadcast_filter_select_this_month") ?></option>
                            <option value="last_month"><?php echo $this->lang->line("dashboard_broadcast_filter_select_last_month") ?></option>
                            <option value="two_months_ago"><?php echo $this->lang->line("dashboard_broadcast_filter_select_two_months_ago") ?></option>
                        </select>
                    </div>

                    <button class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-export" id="modalExport">
                        <?php echo $this->lang->line("dashboard_broadcast_export_graphic") ?>
                    </button>
                </div>
            </div>

            <div class="row">

                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats circle">
                        <div class="card-body">
                            <div class="row flex-top">
                                <div class="col">
                                    <h5 class="card-title text-muted mb-0"><?php echo $this->lang->line('dashboard_broadcast_header_base_active'); ?>
                                        <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_broadcast_base_active_tooltip") ?>" style="margin-right: 3px;"></i>
                                    </h5>
                                    <p class="mt-2 mb-1 text-sm">
                                        <span class='text-danger mr-2'>
                                            <i class='fa fa-arrow-down' id='icon_base_active'></i>
                                            <span class="mb-5 text-large font-weight-500 percent" id="base_active">50%</span>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                        <i class="fas fa-check"></i>
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
                                    <h5 class="card-title text-muted mb-0"><?php echo $this->lang->line('dashboard_broadcast_header_base_inactive'); ?></h5>
                                    <p class="mt-2 mb-1 text-sm">
                                        <span class='text-success mr-2'>
                                            <i class='fa fa-arrow-up' id='icon_base_inactive'></i>
                                            <span class="mb-5 text-large font-weight-500 percent" id="base_inactive">50%</span>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="fas fa-exclamation"></i>
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
                                    <h5 class="card-title text-muted mb-0"><?php echo $this->lang->line('dashboard_broadcast_header_broadcast_send'); ?></h5>
                                    <p class="mt-2 mb-1 text-sm">
                                        <span class='text-success mr-2'>
                                            <i class='fa fa-arrow-up' id='icon_broadcast_send'></i>
                                            <span class="mb-5 text-large font-weight-500 percent" id="broadcast_send">50%</span>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-teal text-white rounded-circle shadow">
                                        <i class="fas fa-paper-plane"></i>
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
                                    <h5 class="card-title text-muted mb-0"><?php echo $this->lang->line('dashboard_broadcast_header_broadcast_received'); ?></h5>
                                    <p class="mt-2 mb-1 text-sm">
                                        <span class='text-danger mr-2'>
                                            <i class='fa fa-arrow-down' id='icon_broadcast_received'></i>
                                            <span class="mb-5 text-large font-weight-500 percent" id="broadcast_received">50%</span>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-indigo text-white rounded-circle shadow">
                                        <i class="fas fa-bullhorn"></i>
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
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h6 class="text-uppercase ls-1 mb-1"></h6>
                            <h2 class="mb-0">
                                <?php echo $this->lang->line('dashboard_broadcast_graph_title') ?>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_broadcast_graph_campaign_tooltip") ?>" style="margin-right: 3px;"></i>
                            </h2>
                        </div>
                        <!-- <div class="col-7">
                            <ul class="nav nav-pills justify-content-end">
                                <li class="nav-item mr-2 mr-md-0" id='chart-semanal'>
                                    <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_weekly'); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item" id='chart-mensal'>
                                    <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_monthly'); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <canvas id="graph_broadcast"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h6 class="text-uppercase ls-1 mb-1"></h6>
                            <h2 class="mb-0">
                                <?php echo $this->lang->line('dashboard_broadcast_graph_interaction_title') ?>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_broadcast_graph_interaction_tooltip") ?>" style="margin-right: 3px;"></i>
                                <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_monthly'); ?></span>
                            </h2>
                        </div>
                        <!-- <div class="col-7">
                            <ul class="nav nav-pills justify-content-end">
                                <li class="nav-item mr-2 mr-md-0" id='chart-contatos-semanal'>
                                    <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_weekly'); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item" id='chart-contatos-mensal'>
                                    <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_monthly'); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <canvas id="graph_interaction"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- <div class="col-xl-4"> -->
        <!-- <div class="card"> -->
        <!-- <div class="card-header bg-transparent"> -->
        <!-- <div class="row align-items-center"> -->
        <!-- <div class="col-12">
                            <h2 class="mb-0"><?php echo $this->lang->line('dashboard_broadcast_graph_reaction_title') ?></h2>
                        </div> -->
        <!-- <div class="col-7">
                            <ul class="nav nav-pills justify-content-end">
                                <li class="nav-item mr-2 mr-md-0" id='chart-semanal'>
                                    <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_weekly'); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item" id='chart-mensal'>
                                    <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_monthly'); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div> -->
        <!-- </div> -->
        <!-- </div> -->
        <!-- <div class="card-body">
                    <div class="">
                        <canvas id="graph_reaction"></canvas>
                    </div>
                </div> -->
        <!-- </div> -->
        <!-- </div> -->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h2 class="mb-0"><?php echo $this->lang->line('dashboard_broadcast_graph_active_title') ?>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="" style="margin-left: 2px;" data-original-title="<?php echo $this->lang->line('dashboard_broadcast_graph_active_tooltip'); ?>"></i>
                            </h2>
                        </div>
                        <!-- <div class="col-7">
                            <ul class="nav nav-pills justify-content-end">
                                <li class="nav-item mr-2 mr-md-0" id='chart-semanal'>
                                    <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_weekly'); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item" id='chart-mensal'>
                                    <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_monthly'); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <canvas id="graph_active"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h2 class="mb-0"><?php echo $this->lang->line('dashboard_broadcast_graph_inactive_title') ?>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="" style="margin-left: 2px;" data-original-title="<?php echo $this->lang->line('dashboard_broadcast_graph_inactive_tooltip') ?>"></i>
                            </h2>
                        </div>
                        <!-- <div class="col-7">
                            <ul class="nav nav-pills justify-content-end">
                                <li class="nav-item mr-2 mr-md-0" id='chart-semanal'>
                                    <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_weekly'); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item" id='chart-mensal'>
                                    <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line('dashboard_broadcast_monthly'); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <canvas id="graph_inactive"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xl-7">
            <h6 class="h1 d-inline-block mb-0" style="color:#666666;"><?php echo $this->lang->line('dashboard_broadcast_table_rank_channel'); ?></h6>
        </div>
        <div class="col-xl-5">
            <div class="row">
                <div class="col-9">
                    <div class="form-group">
                        <label class="form-control-label filter-top" style="position:relative;top:5px;float:right;left:15px;"><?php echo $this->lang->line('dashboard_broadcast_table_rank_period'); ?></label>
                    </div>
                </div>


                <div class="col-3">
                    <div class="form-group">
                        <select class="form-control mt-1 filter-top" id="selectEngagementPeriod">
                            <option value="today"><?php echo $this->lang->line("dashboard_broadcast_filter_select_today") ?></option>
                            <option value="yesterday"><?php echo $this->lang->line("dashboard_broadcast_filter_select_yesterday") ?></option>
                            <option value="week"><?php echo $this->lang->line("dashboard_broadcast_filter_select_week") ?></option>
                            <option value="15_days"><?php echo $this->lang->line("dashboard_broadcast_filter_select_fifteen_days") ?></option>
                            <option value="this_month"><?php echo $this->lang->line("dashboard_broadcast_filter_select_this_month") ?></option>
                            <option value="last_month"><?php echo $this->lang->line("dashboard_broadcast_filter_select_last_month") ?></option>
                            <option value="two_months_ago"><?php echo $this->lang->line("dashboard_broadcast_filter_select_two_months_ago") ?></option>
                            <option value="total"><?php echo $this->lang->line("dashboard_broadcast_filter_select_total") ?></option>
                        </select>
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
                            <h3 class="mb-0"><?php echo $this->lang->line("dashboard_broadcast_table_rank_contacts") ?><i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_broadcast_table_rank_contacts_most_tooltip") ?>" style="margin-left: 2px;"></i></h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" id="table-rank-most-contact">
                    <table class="table align-items-center table-flush" id="rank-most-contact">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_position") ?></th>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_channel") ?></th>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_progress") ?></th>
                            </tr>
                        </thead>
                        <tbody id="body-gan-contacts"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo $this->lang->line("dashboard_broadcast_table_rank_contacts") ?><i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_broadcast_table_rank_contacts_less_tooltip") ?>" style="margin-left: 2px;"></i></h3>
                        </div>
                        <!-- <div class="col text-right">
                            <a href="#!" class="btn btn-sm btn-primary">See all</a>
                        </div> -->
                    </div>
                </div>
                <div class="table-responsive" id="table-rank-less-contact">
                    <table class="table align-items-center table-flush" id="rank-less-contact">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_position") ?></th>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_channel") ?></th>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_progress") ?></th>
                            </tr>
                        </thead>
                        <tbody id='body-loss-contacts'>

                        </tbody>
                    </table>
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
                            <h3 class="mb-0"><?php echo $this->lang->line("dashboard_broadcast_table_rank_engagement") ?><i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_broadcast_base_active_tooltip") ?>" style="margin-left: 2px;"></i></h3>
                        </div>
                        <!-- <div class="col text-right">
                            <a href="#!" class="btn btn-sm btn-primary">See all</a>
                        </div> -->
                    </div>
                </div>
                <div class="table-responsive" id="table-rank-most-send">
                    <table class="table align-items-center table-flush" id="rank-most-send">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_position") ?></th>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_channel") ?></th>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_progress") ?></th>
                            </tr>
                        </thead>
                        <tbody id="body-best-send"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo $this->lang->line("dashboard_broadcast_table_rank_engagement") ?><i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("dashboard_broadcast_base_active_tooltip") ?>" style="margin-left: 2px;"></i></h3>
                        </div>
                        <!-- <div class="col text-right">
                            <a href="#!" class="btn btn-sm btn-primary">See all</a>
                        </div> -->
                    </div>
                </div>
                <div class="table-responsive" id="table-rank-less-send">
                    <table class="table align-items-center table-flush" id="rank-less-send">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_position") ?></th>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_channel") ?></th>
                                <th scope="col"><?php echo $this->lang->line("dashboard_broadcast_table_rank_progress") ?></th>
                            </tr>
                        </thead>
                        <tbody id="body-less-send">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="modal-filter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("dashboard_broadcast_filter_title") ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="hidden" id="verify-channel" value="1">
                                <input type="checkbox" class="custom-control-input" id="check-channel" checked>
                                <label class="custom-control-label" for="check-channel"><?php echo $this->lang->line("dashboard_broadcast_filter_channel") ?></label>
                            </div>
                            <div class="dropdown" id="dropdown-channel">
                                <i class="fas fa-chevron-down"></i>
                                <input type="hidden" id="selectedChannel" value="">
                                <input type="text" class="input-search" id="searchChannel" placeholder="<?php echo $this->lang->line("dashboard_broadcast_filter_channel_placeholder") ?>" autocomplete="off" onkeyup="filterDropdown(this)" onclick="openDropdown(this)" onblur="closeDropdown(this)">
                                <div class="dropdown-content">
                                    <a class="dropdown-item" data-id_channel="" onmousedown="selectedChannel(this)"><?php echo $this->lang->line("dashboard_communication_filter_all_channels") ?></a>
                                    <?php foreach ($channels as $elm) { ?>
                                        <a class="dropdown-item" data-id_channel="<?php echo $elm['id_channel'] ?>" onmousedown="selectedChannel(this)"><?php echo $elm['name'] ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check-period" checked>
                                <label class="custom-control-label" for="check-period"><?php echo $this->lang->line("dashboard_broadcast_filter_period") ?></label>
                            </div>
                            <select class="form-control mt-1" id="select-period">
                                <option value="today"><?php echo $this->lang->line("dashboard_broadcast_filter_select_today") ?></option>
                                <option value="yesterday"><?php echo $this->lang->line("dashboard_broadcast_filter_select_yesterday") ?></option>
                                <option value="week"><?php echo $this->lang->line("dashboard_broadcast_filter_select_week") ?></option>
                                <option value="15_days"><?php echo $this->lang->line("dashboard_broadcast_filter_select_fifteen_days") ?></option>
                                <option value="this_month"><?php echo $this->lang->line("dashboard_broadcast_filter_select_this_month") ?></option>
                                <option value="last_month"><?php echo $this->lang->line("dashboard_broadcast_filter_select_last_month") ?></option>
                                <option value="two_months_ago"><?php echo $this->lang->line("dashboard_broadcast_filter_select_two_months_ago") ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("dashboard_broadcast_filter_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-search" data-dismiss="modal"><?php echo $this->lang->line("dashboard_broadcast_filter_btn_search") ?></button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line('dashboard_broadcast_export_modal_title') ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12 text-center">
                        <i class="fas fa-download text-success mb-1 font-xl"></i><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line('dashboard_broadcast_export_modal_subtitle') ?></label><br>
                        <input type="text" class="form-control" id="emailExport" placeholder="Informe o e-mail para exportação" autocomplete="off" value="" disabled hidden>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow exportCancel" data-dismiss="modal"><?php echo $this->lang->line('dashboard_broadcast_export_modal_cancel') ?></button>
                <button type="button" class="btn btn-green" id="exportConfirmation" data-dismiss="modal"><?php echo $this->lang->line('dashboard_broadcast_export_modal_confirm') ?></button>
            </div>

        </div>
    </div>
</div>


<div id="dashboard-export-content" style="display:none;"></div>


<!-- Livrarias para exportar o doc -->
<script src="https://cdn.jsdelivr.net/npm/docx@9.0.3/build/index.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://unpkg.com/docx@8.0.1/build/index.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<style>
    .modal.fade .modal-dialog {
        transition: transform 0.6s ease-out;
    }
</style>