<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("instagram_post_topnav") ?></h6>
                </div>
                <div class="col-lg-6 text-right">
                    <a href="post/add"><button type="button" class="btn btn-sm btn-neutral mr-2"><?php echo $this->lang->line("instagram_post_btn_new") ?></button></a>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-filter" id="modalFilter"><?php echo $this->lang->line("instagram_post_btn_filter") ?></button>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-export" id="modalExport"><?php echo $this->lang->line("instagram_post_btn_export") ?></button>
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
                    <div class="row">
                        <button class="btn btn-sm btn-danger btn-cancel" id="btn-cancel" style="display:none"><?php echo $this->lang->line("instagram_post_column_btn_cancel") ?> &nbsp<b id="count_row">0</b></button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 1%;"></th>
                                <th style="width: 14%;"><?php echo $this->lang->line("instagram_post_column_scheduling") ?></th>
                                <th><?php echo $this->lang->line("instagram_post_column_channel") ?></th>
                                <th><?php echo $this->lang->line("instagram_post_column_title") ?></th>
                                <th><?php echo $this->lang->line("instagram_post_column_status") ?></th>
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
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("instagram_post_filter_title") ?></h6>
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
                                <label class="custom-control-label" for="check-search"><?php echo $this->lang->line("instagram_post_filter_search") ?></label>
                            </div>
                            <input class="form-control" type="text" id="input-search" placeholder="<?php echo $this->lang->line("instagram_post_filter_search_placeholder") ?>" id="input-title" style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="hidden" id="verify-select2" value="2">
                                <input type="checkbox" class="custom-control-input" id="check-select2">
                                <label class="custom-control-label" for="check-select2"><?php echo $this->lang->line("instagram_post_filter_channel") ?></label>
                            </div>
                            <div id="mult-select2" style="display: none;">
                                <select id="multiselect2" name="languages[]" multiple="">
                                    <?php foreach ($channel as $row) : ?>
                                        <?php
                                        $status = isset($row['status']) ? $row['status'] : null;
                                        $disabled = ($status !== 1) ? 'disabled' : '';
                                        $tooltip = ($status !== 1) ? ' title="' . $this->lang->line("blocked_channel_message") . '"' : '';
                                        ?>
                                        <option value="<?php echo $row['id_channel']; ?>" <?php echo $disabled; ?><?php echo $tooltip; ?> data-status="<?php echo $status; ?>"><?php echo $row['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check-status">
                                <label class="custom-control-label" for="check-status"><?php echo $this->lang->line("instagram_post_filter_status") ?></label>
                            </div>
                            <select class="form-control mt-1" id="select-status" style="display:none">
                                <option value=""><?php echo $this->lang->line("instagram_post_filter_status_select") ?></option>
                                <option value="3"><?php echo $this->lang->line("instagram_post_filter_status_processing") ?></option>
                                <option value="1"><?php echo $this->lang->line("instagram_post_filter_status_sending") ?></option>
                                <option value="2"><?php echo $this->lang->line("instagram_post_filter_status_send") ?></option>
                                <option value="4"><?php echo $this->lang->line("instagram_post_filter_status_canceling") ?></option>
                                <option value="5"><?php echo $this->lang->line("instagram_post_filter_status_called_off") ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="check-date">
                            <label class="custom-control-label" for="check-date"><?php echo $this->lang->line("instagram_post_filter_period") ?></label>
                        </div>
                        <div class="row form-group mt-1">
                            <div class="col-6">
                                <input class="form-control" type="text" placeholder="<?php echo $this->lang->line("instagram_post_filter_period_placeholder_date_start") ?>" id="dt-start" style="display: none;">
                            </div>
                            <div class="col-6">
                                <input class="form-control" type="text" disabled placeholder="<?php echo $this->lang->line("instagram_post_filter_period_placeholder_date_end") ?>" id="dt-end" style="display: none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("instagram_post_filter_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-search" data-dismiss="modal"><?php echo $this->lang->line("instagram_post_filter_btn_search") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("instagram_post_export_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("instagram_post_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("instagram_post_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="sendEmailExport" data-dismiss="modal"><?php echo $this->lang->line("instagram_post_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>