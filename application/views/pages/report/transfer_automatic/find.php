<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("report_transfer_automatic_topnav") ?></h6>
                </div>
                <div class="col-lg-6 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-filter" id="modalFilter"><?php echo $this->lang->line("report_transfer_automatic_btn_filter") ?></button>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-export" id="modalExport"><?php echo $this->lang->line("report_transfer_automatic_btn_export") ?></button>
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
                    <div class="row"></div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr role="row">
                                <th><?php echo $this->lang->line("report_transfer_automatic_column_number") ?></th>
                                <th><?php echo $this->lang->line("report_transfer_automatic_column_name") ?></th>
                                <th><?php echo $this->lang->line("report_transfer_automatic_column_user") ?></th>
                                <th><?php echo $this->lang->line("report_transfer_automatic_column_sector") ?></th>
                                <th></th>
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
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("report_transfer_automatic_filter_title") ?></h6>
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
                                <label class="custom-control-label" for="check-search"><?php echo $this->lang->line("report_transfer_automatic_filter_search") ?></label>
                            </div>
                            <input class="form-control" type="text" id="input-search" placeholder="<?php echo $this->lang->line("report_transfer_automatic_filter_search_placeholder") ?>" id="input-title" style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="hidden" id="verify-select" value="2">
                                <input type="checkbox" class="custom-control-input" id="check-select">
                                <label class="custom-control-label" for="check-select"><?php echo $this->lang->line("report_transfer_automatic_filter_user") ?></label>
                            </div>
                            <div id="mult-select" style="display: none;">
                                <select id="multiselect" name="others[]" multiple="multiple">
                                    <?php foreach ($users as $row) { ?>
                                        <option value="<?php echo $row['id_user']; ?>"> <?php echo $row['name'] ?> </option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="hidden" id="verify-select2" value="2">
                                <input type="checkbox" class="custom-control-input" id="check-select2">
                                <label class="custom-control-label" for="check-select2"><?php echo $this->lang->line("report_transfer_automatic_filter_sector") ?></label>
                            </div>
                            <div id="mult-select2" style="display: none;">
                                <select id="multiselect2" name="others[]" multiple="multiple">
                                    <?php foreach ($userGroup as $row) { ?>
                                        <option value="<?php echo $row['id_user_group']; ?>"> <?php echo $row['name'] ?> </option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check-date">
                                <label class="custom-control-label" for="check-date"><?php echo $this->lang->line("report_transfer_automatic_filter_period") ?></label>
                            </div>
                            <div class="row form-group mt-1">
                                <div class="col-6">
                                    <input class="form-control" type="text" placeholder="<?php echo $this->lang->line("report_transfer_automatic_filter_period_placeholder_date_start") ?>" id="dt-start" style="display: none;">
                                </div>
                                <div class="col-6">
                                    <input class="form-control" type="text" disabled placeholder="<?php echo $this->lang->line("report_transfer_automatic_filter_period_placeholder_date_end") ?>" id="dt-end" style="display: none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("report_transfer_automatic_filter_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-search" data-dismiss="modal"><?php echo $this->lang->line("report_transfer_automatic_filter_btn_search") ?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal " id="modal-chat" tabindex="-1" role="dialog" aria-labelledby="modal-chatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content" id="modal-content-chat">
            <div class="modal-header">

                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <img id="modal-profile-contact" class="rounded-circle profile-left" src="../../assets/img/avatar.svg">
                        <div class="d-flex flex-column justify-content-center">
                            <span id="modal-name-contact" class="font-weight-bold">Juliana</span>
                            <small id="modal-number-contact" class="d-block font-weight-400">5533654489</small>
                        </div>
                    </div>

                    <div>
                        <div id="modal-status" class="diamond"></div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <img class="settings" id="chat-settings" alt="">
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-body">
                <div class="chat"></div>
            </div>

            <div class="modal-footer" id="footer-transfer"></div>

        </div>
    </div>
</div>


<div class="modal fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line('report_transfer_automatic_export_title') ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("report_transfer_automatic_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("report_transfer_automatic_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="sendEmailExport" data-dismiss="modal"><?php echo $this->lang->line("report_transfer_automatic_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-export-talk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line('report_transfer_automatic_export_title') ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line('report_transfer_automatic_export_email') . " " . $this->session->userdata('email_user') ?> ?</label>
                        <input type="text" class="form-control" id="emailExport" placeholder="<?php echo $this->lang->line("report_transfer_automatic_export_email_placeholder") ?>" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("report_transfer_automatic_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="sendEmailExport" data-dismiss="modal"><?php echo $this->lang->line("report_transfer_automatic_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>