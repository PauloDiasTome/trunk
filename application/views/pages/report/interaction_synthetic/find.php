<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 d-inline-block mb-0 text-white"><?php echo $this->lang->line('report_interaction_synthetic_topnav'); ?></h6>
                </div>
                <div class="col-lg-6 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-filter" id="modalFilter"><?php echo $this->lang->line("report_interaction_synthetic_btn_filter") ?></button>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-export" id="modalExport"><?php echo $this->lang->line('report_interaction_synthetic_btn_export'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6" id="container-chatbot" style="display: <?php echo $is_bot == true ? "block" : "none" ?>;">
    <div class="row">
        <div class="col">
            <div class="card pb-1">
                <div class="card-header border-0"><strong><?php echo $this->lang->line("report_interaction_synthetic_chatbot_interaction") ?></strong><span style="float:right;"><b style="font-size:13.8px"><?php echo $this->lang->line("report_interaction_synthetic_period") ?> </b><span class="period-report" style="font-size: 13.8px"><?php echo $this->lang->line("report_interaction_synthetic_day") ?></span></span></div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-chatbot" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr role="row">
                                <th style="width:70%"><?php echo $this->lang->line('report_interaction_synthetic_column_chatbot'); ?></th>
                                <th style="width:30%"><?php echo $this->lang->line('report_interaction_synthetic_column_count') ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div><br><br><br>
</div>

<div class="container-fluid mt--6" id="container-waiting-service">
    <div class="row">
        <div class="col">
            <div class="card pb-1">
                <div class="card-header border-0"><strong><?php echo $this->lang->line("report_interaction_synthetic_waiting_service") ?></strong><span style="float:right;"><b style="font-size: 13.8px"><?php echo $this->lang->line("report_interaction_synthetic_period") ?> </b><span class="period-report" style="font-size: 13.8px"><?php echo $this->lang->line("report_interaction_synthetic_day") ?></span></span></div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-waiting-service" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr role="row">
                                <th style="width:70%"><?php echo $this->lang->line('report_interaction_synthetic_column_wating_service'); ?></th>
                                <th style="width:30%"><?php echo $this->lang->line('report_interaction_synthetic_column_count') ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div><br><br><br>
</div>

<div class="container-fluid mt--6" id="container-attendance">
    <div class="row">
        <div class="col">
            <div class="card pb-1">
                <div class="card-header border-0"><strong><?php echo $this->lang->line("report_interaction_synthetic_service") ?></strong><span style="float:right;"><b style="font-size: 13.8px"><?php echo $this->lang->line("report_interaction_synthetic_period") ?> </b><span class="period-report" style="font-size: 13.8px"><?php echo $this->lang->line("report_interaction_synthetic_day") ?></span></span></div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-attendance" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr role="row">
                                <th style="width:70%"><?php echo $this->lang->line('report_interaction_synthetic_column_attendance'); ?></th>
                                <th style="width:30%"><?php echo $this->lang->line('report_interaction_synthetic_column_count') ?></th>
                            </tr>
                        </thead>
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
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("report_interaction_synthetic_filter_title") ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check-situation">
                                <label class="custom-control-label" for="check-situation"><?php echo $this->lang->line("report_interaction_synthetic_filter_report") ?></label>
                            </div>
                            <select class="form-control mt-1" id="select-situation">
                                <option value="0"><?php echo $this->lang->line("report_interaction_synthetic_filter_report_placeholder") ?></option>
                                <option value="1"><?php echo $this->lang->line("report_interaction_synthetic_filter_report_interaction_chatbot") ?></option>
                                <option value="2"><?php echo $this->lang->line("report_interaction_synthetic_filter_report_wainting_attendance") ?></option>
                                <option value="3"><?php echo $this->lang->line("report_interaction_synthetic_filter_report_attendance") ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check-date" value="2">
                                <label class="custom-control-label" for="check-date"><?php echo $this->lang->line("report_interaction_synthetic_filter_period") ?></label>
                            </div>
                            <div class="row form-group mt-1">
                                <div class="col-6">
                                    <input class="form-control" type="text" placeholder="<?php echo $this->lang->line("report_interaction_synthetic_filter_period_placeholder_date_start") ?>" id="dt-start" style="display: none;">
                                </div>
                                <div class="col-6">
                                    <input class="form-control" type="text" disabled placeholder="<?php echo $this->lang->line("report_interaction_synthetic_filter_period_placeholder_date_end") ?>" id="dt-end" style="display: none">
                                </div>
                                <span class="alert-field-validation ml" id="alert-filter-period"><?php echo $this->lang->line("report_interaction_synthetic_filter_period_notify") ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check-channel">
                                <label class="custom-control-label" for="check-channel"><?php echo $this->lang->line("report_interaction_synthetic_modal_filter_channel") ?></label>
                            </div>
                            <select class="form-control mt-1" id="select-channel">
                                <option value="0"><?php echo $this->lang->line("report_interaction_synthetic_filter_report_placeholder") ?></option>
                                <?php foreach ($channels as $key => $value) { ?>
                                    <option data-id="<?php echo $value['id']; ?>" value="<?php echo $value['id_channel']; ?>"><?php echo $value['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("report_interaction_synthetic_filter_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-search" data-dismiss="modal"><?php echo $this->lang->line("report_interaction_synthetic_filter_btn_search") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="height:37%">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("report_interaction_synthetic_export_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("report_interaction_synthetic_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("report_interaction_synthetic_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="sendEmailExport" data-dismiss="modal"><?php echo $this->lang->line("report_interaction_synthetic_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-export-talk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="height: auto;" role="document">
        <div class="modal-content" style="height: auto;">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $this->lang->line("report_interaction_synthetic_history_title") ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("report_interaction_synthetic_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="sendEmailExportTalk"><?php echo $this->lang->line("report_interaction_synthetic_history_btn_send") ?></button>
            </div>
        </div>
    </div>
</div>