<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("report_copacol_synthetic_topnav") ?></h6>
                </div>
                <div class="col-lg-6 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-filter" id="modalFilter"><?php echo $this->lang->line("report_copacol_synthetic_btn_filter") ?></button>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-export" id="modalExport"><?php echo $this->lang->line("report_copacol_synthetic_btn_export") ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card pb-3">

                <div class="card-header border-0">
                    <h3 class="mb-0 report-bot">Bot</h3>
                </div>

                <div class="report-bot">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="datatable-basic-bot" cellspacing="0" style="width:100%;">
                            <thead class="thead-light">
                                <tr role="row">
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_stage") ?></th>
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_finish_count") ?></th>
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_lgpd_count") ?></th>
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_abandonment") ?></th>
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_effective_rating") ?></th>
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_total_quantity") ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="report-ticket">
                    <h3 class="mb-0" style="margin-left: 18px; padding:15px">Ticket</h3>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="datatable-basic-ticket" cellspacing="0" style="width:100%;">
                            <thead class="thead-light">
                                <tr role="row">
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_type") ?></th>
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_quantity_open") ?></th>
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_quantity_finished") ?></th>
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_pending") ?></th>
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_time_average") ?></th>
                                    <th><?php echo $this->lang->line("report_copacol_synthetic_column_total_quantity") ?></th>
                                </tr>
                            </thead>
                        </table>
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
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("report_copacol_synthetic_filter_title") ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" class="custom-control-input" id="check-search">
                                <label class="custom-control-label" for="check-search"><?php echo $this->lang->line("report_copacol_synthetic_filter_search") ?></label>
                            </div>
                            <input class="form-control" type="text" id="input-search" placeholder="<?php echo $this->lang->line("report_copacol_synthetic_filter_search_placeholder") ?>" id="input-title" style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check-type">
                                <label class="custom-control-label" for="check-type"><?php echo $this->lang->line("report_copacol_synthetic_filter_type") ?></label>
                            </div>
                            <select class="form-control mt-1" id="select-type" style="display:none">
                                <option value=""><?php echo $this->lang->line("report_copacol_synthetic_filter_type_placeholder") ?></option>
                                <option value="3"><?php echo $this->lang->line("report_copacol_synthetic_filter_type_bot") ?></option>
                                <option value="1"><?php echo $this->lang->line("report_copacol_synthetic_filter_type_ticket") ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check-situation">
                                <label class="custom-control-label" for="check-situation"><?php echo $this->lang->line("report_copacol_synthetic_filter_situation") ?></label>
                            </div>
                            <select class="form-control mt-1" id="select-situation" style="display:none">
                                <option value=""><?php echo $this->lang->line("report_copacol_synthetic_filter_situation_placeholder") ?></option>
                                <option value=""><?php echo $this->lang->line("report_copacol_synthetic_filter_situation_data_collect") ?></option>
                                <option value="3"><?php echo $this->lang->line("report_copacol_synthetic_filter_situation_other_information") ?></option>
                                <option value="1"><?php echo $this->lang->line("report_copacol_synthetic_filter_situation_have_route") ?></option>
                                <option value="1"><?php echo $this->lang->line("report_copacol_synthetic_filter_situation_finished") ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="check-date">
                            <label class="custom-control-label" for="check-date"><?php echo $this->lang->line("report_copacol_synthetic_filter_period") ?></label>
                        </div>
                        <div class="row form-group mt-1">
                            <div class="col-6">
                                <input class="form-control" type="text" placeholder="<?php echo $this->lang->line("report_copacol_synthetic_filter_period_placeholder_date_start") ?>" id="dt-start" style="display: none;">
                            </div>
                            <div class="col-6">
                                <input class="form-control" type="text" disabled placeholder="<?php echo $this->lang->line("report_copacol_synthetic_filter_period_placeholder_date_end") ?>" id="dt-end" style="display: none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("report_copacol_synthetic_filter_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-search" data-dismiss="modal"><?php echo $this->lang->line("report_copacol_synthetic_filter_btn_search") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("report_copacol_synthetic_export_title"); ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("report_copacol_synthetic_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"> <?php echo $this->lang->line("report_copacol_synthetic_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="sendEmailExport" data-dismiss="modal"><?php echo $this->lang->line("report_copacol_synthetic_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>