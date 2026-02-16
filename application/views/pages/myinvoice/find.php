<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("myinvoice_topnav") ?></h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-export" id="modalExport"><?php echo $this->lang->line("myinvoice_btn_export") ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card" style="padding-bottom: 20px">
                <div class="card-header border-0"></div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("myinvoice_column_company") ?></th>
                                <th><?php echo $this->lang->line("myinvoice_column_work_description") ?></th>
                                <th><?php echo $this->lang->line("myinvoice_column_due_date") ?></th>
                                <th><?php echo $this->lang->line("myinvoice_column_value") ?></th>
                                <th><?php echo $this->lang->line("myinvoice_column_status") ?></th>
                                <th><?php echo $this->lang->line("myinvoice_column_files") ?></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("myinvoice_export_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-12" style="text-align: center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("myinvoice_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("myinvoice_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="sendEmailExport" data-dismiss="modal"><?php echo $this->lang->line("myinvoice_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>