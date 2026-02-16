<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("report_broadcast_analytical_topnav") ?></h6>
                </div>
                <div class="col-lg-6 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-filter" id="modalFilter"><?php echo $this->lang->line("report_broadcast_analytical_btn_filter") ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card pb-3">
                <div class="card-header border-0"></div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr role="row">
                                <th><?php echo $this->lang->line("report_broadcast_analytical_column_channel") ?></th>
                                <th><?php echo $this->lang->line("report_broadcast_analytical_column_schedule") ?></th>
                                <th><?php echo $this->lang->line("report_broadcast_analytical_column_send_timestamp") ?></th>
                                <th><?php echo $this->lang->line("report_broadcast_analytical_column_status") ?></th>
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
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("report_broadcast_analytical_filter_title") ?></h6>
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
                                <label class="custom-control-label" for="check-search"><?php echo $this->lang->line("report_broadcast_analytical_filter_search") ?></label>
                            </div>
                            <input class="form-control" type="text" id="input-search" placeholder="<?php echo $this->lang->line("report_broadcast_analytical_filter_search_placeholder") ?>" id="input-title" style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="hidden" id="verify-select1" value="2">
                                <input type="checkbox" class="custom-control-input" id="check-select1">
                                <label class="custom-control-label" for="check-select1"><?php echo $this->lang->line("report_broadcast_analytical_column_channel") ?></label>
                            </div>
                            <div id="mult-select1" style="display: none;">
                                <select id="multiselect1" name="channels[]" multiple="multiple">
                                    <?php foreach ($channels as $row) { ?>
                                        <option value="<?php echo $row['id_channel']; ?>"> <?php echo $row['name'] ?> </option>
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
                                <label class="custom-control-label" for="check-select2"><?php echo $this->lang->line("report_broadcast_analytical_filter_situation") ?></label>
                            </div>
                            <div id="mult-select2" style="display: none;">
                                <select id="multiselect2" name="status[]" multiple="multiple">
                                    <option value="1"><?php echo $this->lang->line("report_broadcast__filter_situation_pending") ?></option>
                                    <option value="2"><?php echo $this->lang->line("report_broadcast__filter_situation_sent") ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("report_broadcast_analytical_filter_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-search" data-dismiss="modal"><?php echo $this->lang->line("report_broadcast_analytical_filter_btn_search") ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/util.js"></script>