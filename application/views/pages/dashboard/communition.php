<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-11">
                    <h6 class="h2 text-white d-inline-block mb-0" id="dashboard_title"><?php echo $this->lang->line("dashboard_communication_title") ?></h6>
                </div>
                <div class="col-lg-1 text-right box-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-filter" id="modalFilter"><?php echo $this->lang->line("dashboard_communication_btn_filter") ?></button>
                </div>
            </div>
            <!-- <div class="row header">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_communication_lang_shortlink_today"); ?></h5>
                                </div>
                                <div class="progressbar">
                                    <div class="progress-icon" style="width: 70%;">
                                        <img class="icon" src="<?php echo base_url("/assets/icons/panel/emoji1.svg") ?>">
                                    </div>
                                    <div class="progress" style="width: 70%;"></div>
                                </div>
                                <div class="info">
                                    <span class="text"><?php echo $this->lang->line("dashboard_communication_header_contents"); ?></span>
                                    <span class="number"><?php echo $this->lang->line("dashboard_communication_header_comingsoon"); ?></span>
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
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_communication_lang_shortlink_this_week"); ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo isset($count_week); ?></span>
                                </div>
                                <div class="progressbar">
                                    <div class="progress-icon" style="width: 70%;">
                                        <img class="icon" src="<?php echo base_url("/assets/icons/panel/emoji2.svg") ?>">
                                    </div>
                                    <div class="progress" style="width: 70%;"></div>
                                </div>
                                <div class="info">
                                    <span class="text"> <?php echo $this->lang->line("dashboard_communication_header_interaction"); ?></span>
                                    <span class="number"><?php echo $this->lang->line("dashboard_communication_header_comingsoon"); ?></span>
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
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_communication_lang_shortlink_this_month"); ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo isset($count_month); ?></span>
                                </div>
                                <div class="progressbar">
                                    <div class="progress-icon" style="width: 60%;">
                                        <img class="icon" src="<?php echo base_url("/assets/icons/panel/emoji3.svg") ?>">
                                    </div>
                                    <div class="progress" style="width:60%;"></div>
                                </div>
                                <div class="info">
                                    <span class="text"> <?php echo $this->lang->line("dashboard_communication_header_public"); ?></span>
                                    <span class="number"><?php echo $this->lang->line("dashboard_communication_header_comingsoon"); ?></span>
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
                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $this->lang->line("dashboard_communication_lang_shortlink_total"); ?></h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo isset($count); ?></span>
                                </div>
                                <div class="progressbar">
                                    <div class="progress-icon" style="width: 50%;">
                                        <img class="icon" src="<?php echo base_url("/assets/icons/panel/emoji4.svg") ?>">
                                    </div>
                                    <div class="progress" style="width: 50%;"></div>
                                </div>
                                <div class="info">
                                    <span class="text"> <?php echo $this->lang->line("dashboard_communication_header_performance"); ?></span>
                                    <span class="number"><?php echo $this->lang->line("dashboard_communication_header_comingsoon"); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>


<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-6 mb-5 mb-xl-0 interaction-graph">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center layout-header">
                        <div>
                            <h2 class="mb-0"><?php echo $this->lang->line("dashboard_communication_interaction_graph_title") ?></h2>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding:5px">
                    <div class="main">
                        <div class="left">
                            <?php foreach ($interaction as $key => $val) { ?>
                                <div class="info">
                                    <div class="icon">
                                        <?php $name = "";
                                        $tooltip = "";

                                        switch ($key) {
                                            case 0:
                                                $name = $this->lang->line("dashboard_communication_interaction_graph_send");
                                                $tooltip = $this->lang->line("dashboard_communication_interaction_graph_tooltip_sent");
                                                echo '<img src="' . base_url("/assets/icons/panel/email.svg") . '" alt="emoji">';

                                                break;
                                            case 1:
                                                $name = $this->lang->line("dashboard_communication_interaction_graph_receipt");
                                                $tooltip = $this->lang->line("dashboard_communication_interaction_graph_tooltip_receipt");
                                                echo '<img src="' . base_url("/assets/icons/panel/email2.svg") . '" alt="emoji">';
                                                break;
                                            case 2:
                                                $name = $this->lang->line("dashboard_communication_interaction_graph_read");
                                                $tooltip = $this->lang->line("dashboard_communication_interaction_graph_tooltip_read");
                                                echo '<img src="' . base_url("/assets/icons/panel/emoji5.svg") . '" alt="emoji">';
                                                break;
                                            case 3:
                                                $name = $this->lang->line("dashboard_communication_interaction_graph_reactions");
                                                $tooltip = $this->lang->line("dashboard_communication_interaction_graph_tooltip_reactions");
                                                echo '<img src="' . base_url("/assets/icons/panel/emoji1.svg") . '" alt="emoji">';
                                                break;
                                                // case 4:
                                                //     $name = $this->lang->line("dashboard_communication_interaction_graph_valid_key");
                                                //     echo '<img src="' . base_url("/assets/icons/panel/emoji7.svg") . '" alt="emoji">';
                                                //     break;
                                        } ?>
                                    </div>
                                    <div class="broadcast">
                                        <span class="text"><?php echo $name; ?></span>
                                        <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $tooltip; ?>"></i>
                                        <span class="<?php echo $key == 3 ? "number reactions" : "number" ?>"><?php echo $key != 3 ? $val->percent : ""; ?></span>

                                    </div>
                                    <div class="total">
                                        <span class="number"><?php echo $val->total; ?></span>
                                        <!-- <span class="number">1,24%</span> -->
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="right">
                            <?php foreach ($interaction as $val) { ?>
                                <div class="progressbar">
                                    <div class="progress" style="width:<?php echo $val->percent; ?>"></div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="info-footer">
                        <span><?php echo $this->lang->line("dashboard_communication_interaction_graph_footer") ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-5 mb-xl-0 audience-graph">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center layout-header">
                        <div>
                            <h2 class="mb-0"><?php echo $this->lang->line("dashboard_communication_audience_graph_title") ?></h2>
                        </div>
                        <div>
                            <ul class="nav nav-pills justify-content-end audience-graph">
                                <li class="nav-item" id="chartPeriod" style="display: none;">
                                    <a href="#" class="nav-link py-2 px-3">
                                        <span class="d-none d-md-block" id="button-date-filtered"></span>
                                    </a>
                                </li>
                                <li class="nav-item" id="chartSevenDays">
                                    <a href="#" class="nav-link py-2 px-3">
                                        <span class="d-none d-md-block" id="button-seven-days"><?php echo $this->lang->line("dashboard_communication_audience_period_seven") ?></span>
                                    </a>
                                </li>
                                <li class="nav-item" id="chartTotal">
                                    <a href="#" class="nav-link py-2 px-3 selected">
                                        <span class="d-none d-md-block"><?php echo $this->lang->line("dashboard_communication_audience_total") ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body" style="padding:5px 0px;">
                    <div class="main">
                        <div class="container-graphic">
                            <input type="hidden" id="totalContact" value="<?php echo $audience->total ?>">
                            <canvas id="audienceChart" width="180" height="180"></canvas>
                        </div>
                        <div class="container-title">
                            <div class="subtitle">
                                <span><?php echo $this->lang->line("dashboard_communication_audience_graph_subtitle") ?></span>
                            </div>
                            <div class="info" style="display: none;">
                                <span class="fans">10%</span>
                                <span>450</span>
                                <span><?php echo $this->lang->line("dashboard_communication_audience_graph_fans") ?></span>
                                <!-- <span>1,24%</span> -->
                            </div>
                            <div class="info">
                                <span class="active"><?php echo $audience->active_percent ?>%</span>
                                <span><?php echo $audience->active ?></span>
                                <span><?php echo $this->lang->line("dashboard_communication_audience_graph_active") ?></span>
                                <!-- <span>1,24%</span> -->
                            </div>
                            <div class="info">
                                <span class="inactive"><?php echo $audience->inactive_percent ?>%</span>
                                <span><?php echo $audience->inactive ?></span>
                                <span><?php echo $this->lang->line("dashboard_communication_audience_graph_inactive") ?></span>
                                <!-- <span>1,24%</span> -->
                            </div>
                        </div>
                    </div>
                    <div class="wartnings">
                        <div style="display:none">
                            <img src="<?php echo base_url("/assets/icons/panel/emoji8.svg") ?>" alt="emoji"><?php echo $this->lang->line("dashboard_communication_audience_alert_fans_one"); ?>
                        </div>
                        <div style="display:none">
                            <img src="<?php echo base_url("/assets/icons/panel/emoji8.svg") ?>" alt="emoji"><?php echo $this->lang->line("dashboard_communication_audience_alert_fans_two"); ?>
                        </div>
                        <div style="display:<?php echo $audience->alert->inactive_one ?>">
                            <img src="<?php echo base_url("/assets/icons/panel/emoji9.svg") ?>" alt="emoji"><?php echo $this->lang->line("dashboard_communication_audience_alert_inactive_one"); ?>
                        </div>
                        <div style="display:<?php echo $audience->alert->inactive_two ?>">
                            <img src="<?php echo base_url("/assets/icons/panel/emoji9.svg") ?>" alt="emoji"><?php echo $this->lang->line("dashboard_communication_audience_alert_inactive_two"); ?>
                        </div>
                        <div style="display:<?php echo $audience->alert->inactive_three ?>">
                            <img src="<?php echo base_url("/assets/icons/panel/emoji9.svg") ?>" alt="emoji"><?php echo $this->lang->line("dashboard_communication_audience_alert_inactive_three"); ?>
                        </div>
                        <div style="display:<?php echo $audience->alert->inactive_four ?>">
                            <img src="<?php echo base_url("/assets/icons/panel/emoji10.svg") ?>" alt="emoji"><?php echo $this->lang->line("dashboard_communication_audience_alert_inactive_four"); ?>
                        </div>
                        <div style="display:<?php echo $audience->alert->total_one ?>">
                            <img src="<?php echo base_url("/assets/icons/panel/emoji11.svg") ?>" alt="emoji"><?php echo $this->lang->line("dashboard_communication_audience_alert_contact_one"); ?>
                        </div>
                        <div style="display:<?php echo $audience->alert->total_two ?>">
                            <img src="<?php echo base_url("/assets/icons/panel/emoji12.svg") ?>" alt="emoji"><?php echo $this->lang->line("dashboard_communication_audience_alert_contact_two"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-5 mb-xl-0 scheduled-campaigns">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center layout-header">
                        <div>
                            <h2 class="mb-0"><?php echo $this->lang->line("dashboard_communication_scheduled_campaigns_title") ?></h2>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding:5px 0px; margin-bottom: -40px;">

                    <div class="row">
                        <div class="col">
                            <div class="card pb-3">
                                <div class="card-header border-0"></div>
                                <div class="table-responsive">
                                    <table class="table table-sm align-items-center table-flush" id="datatable-scheduled-campaigns" cellspacing="0" style="width:100%; height: 192.5px;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="display: none;"></th>
                                                <th style="display: none;"></th>
                                                <th style="display: none;"></th>
                                                <th style="display: none;"></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-5 mb-xl-0 scheduled-published">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center layout-header">
                        <div>
                            <h2 class="mb-0"><?php echo $this->lang->line("dashboard_communication_scheduled_published_title") ?></h2>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding:5px 0px; margin-bottom: -40px;">

                    <div class="row">
                        <div class="col">
                            <div class="card pb-3">
                                <div class="card-header border-0"></div>
                                <div class="table-responsive">
                                    <table class="table table-sm align-items-center table-flush" id="datatable-published-campaigns" cellspacing="0" style="width:100%; height: 192.5px;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="display: none;"></th>
                                                <th style="display: none;"></th>
                                                <th style="display: none;"></th>
                                                <th style="display: none;"></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="modal-filter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("dashboard_communication_filter_title") ?></h6>
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
                                <label class="custom-control-label" for="check-channel"><?php echo $this->lang->line("dashboard_communication_filter_channel") ?></label>
                            </div>
                            <div class="dropdown" id="dropdown-channel">
                                <i class="fas fa-chevron-down"></i>
                                <input type="hidden" id="selectedChannel" value="">
                                <input type="text" class="input-search" id="searchChannel" placeholder="<?php echo $this->lang->line("dashboard_communication_filter_channel_placeholder") ?>" autocomplete="off" onkeyup="filterDropdown(this)" onclick="openDropdown(this)" onblur="closeDropdown(this)">
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
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="hidden" id="verify-broadcast" value="1">
                                <input type="checkbox" class="custom-control-input" id="check-broadcast" checked>
                                <label class="custom-control-label" for="check-broadcast"><?php echo $this->lang->line("dashboard_communication_filter_broadcast") ?></label>
                            </div>
                            <div class="dropdown" id="dropdown-broadcast">
                                <i class="fas fa-chevron-down"></i>
                                <input type="hidden" id="selectedBroadcast" value="">
                                <input type="text" class="input-search" id="searchBroadcast" placeholder="<?php echo $this->lang->line("dashboard_communication_filter_broadcast_placeholder") ?>" autocomplete="off" onkeyup="filterDropdown(this)" onclick="openDropdown(this)" onblur="closeDropdown(this)">
                                <div class="dropdown-content">
                                    <?php foreach ($broadcast as $elm) { ?>
                                        <a class="dropdown-item" data-id_channel="<?php echo $elm['id_channel'] ?>" data-id_broadcast="<?php echo $elm['id_broadcast_schedule'] ?>" onmousedown="selectedBroadcast(this)" style="display: none;"><?php echo $elm['name'] ?></a>
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
                                <input type="checkbox" class="custom-control-input" id="check-period">
                                <label class="custom-control-label" for="check-period"><?php echo $this->lang->line("dashboard_communication_filter_period") ?></label>
                            </div>
                            <div class="row form-group mt-1">
                                <div class="col-6">
                                    <input class="form-control" type="date" placeholder="<?php echo $this->lang->line("report_send_filter_period_placeholder_date_start") ?>" id="dt-start" style="display: none;">
                                </div>
                                <div class="col-6">
                                    <input class="form-control" type="date" disabled placeholder="<?php echo $this->lang->line("report_send_filter_period_placeholder_date_end") ?>" id="dt-end" style="display: none">
                                </div>
                                <span class="alert-field-validation ml" id="alert-filter-period"><?php //echo $this->lang->line("dashboard_communication_filter_period_notify") 
                                                                                                    ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("dashboard_communication_filter_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-search" data-dismiss="modal"><?php echo $this->lang->line("dashboard_communication_filter_btn_search") ?></button>
            </div>
        </div>
    </div>
</div>