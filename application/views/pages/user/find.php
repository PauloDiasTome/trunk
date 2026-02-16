<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("user_topnav") ?></h6>
                </div>
                <div class="col-lg-6 text-right">
                    <a href="user/add"><button type="button" class="btn btn-sm btn-neutral mr-2"><?php echo $this->lang->line("user_btn_new") ?></button></a>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-filter" id="modalFilter"><?php echo $this->lang->line("user_btn_filter") ?></button>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-export" id="modalExport"><?php echo $this->lang->line("user_btn_export") ?></button>
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
                            <tr>
                                <th><?php echo $this->lang->line("user_column_name") ?></th>
                                <th><?php echo $this->lang->line("user_column_sector") ?></th>
                                <th><?php echo $this->lang->line("user_column_email") ?></th>
                                <th><?php echo $this->lang->line("user_column_situaion") ?></th>
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
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("user_filter_title") ?></h6>
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
                                <label class="custom-control-label" for="check-search"><?php echo $this->lang->line("user_filter_name") ?></label>
                            </div>
                            <input class="form-control" type="text" id="input-search" placeholder="<?php echo $this->lang->line("user_filter_name_placeholder") ?>" id="input-title" style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="hidden" id="verify-select2" value="2">
                                <input type="checkbox" class="custom-control-input" id="check-select2">
                                <label class="custom-control-label" for="check-select2"><?php echo $this->lang->line("user_filter_sector") ?></label>
                            </div>
                            <div id="mult-select2" style="display: none;">
                                <select id="multiselect2" name="languages[]" multiple="">
                                    <?php foreach ($department as $row) { ?>
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
                                <input type="checkbox" class="custom-control-input" id="check-situation">
                                <label class="custom-control-label" for="check-situation"><?php echo $this->lang->line("user_filter_situation") ?></label>
                            </div>
                            <select class="form-control mt-1" id="select-situation" style="display:none">
                                <option value=""><?php echo $this->lang->line("user_filter_situation_select") ?></option>
                                <option value="1"><?php echo $this->lang->line("user_filter_situation_verified") ?></option>
                                <option value="3"><?php echo $this->lang->line("user_filter_situation_not_verified") ?></option>
                                <option value="4"><?php echo $this->lang->line("user_filter_situation_blocked") ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("user_filter_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-search" data-dismiss="modal"><?php echo $this->lang->line("user_filter_btn_search") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("user_export_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("user_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("user_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="sendEmailExport" data-dismiss="modal"><?php echo $this->lang->line("user_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-user-transfer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("user_delete_confirm_title"); ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">

                        <i class="fas fa-exclamation-triangle" style="color: orange; font-size: 30px; margin: 10px;"></i>

                        <div class="information mb-4">
                            <input type="hidden" id="id-user" value="">
                            <input type="hidden" id="id-user" value="">
                            <label class="form-control-label"><?php echo $this->lang->line("user_delete_confirm_text") . " " ?> <strong id="name-user"></strong> <?php echo " " . $this->lang->line("user_delete_confirm_two_text") ?><br>
                                <?php echo $this->lang->line("user_delete_confirm_three_text") ?></label>
                        </div>

                        <label class="form-control-label" for="select-user"><?php echo $this->lang->line("user_delete_confirm_four_text") ?></label>
                        <select class="form-control" name="select-user" id="select-user">
                            <option value="0"><?php echo $this->lang->line("user_delete_confirm_five_text") ?></option>
                            <?php foreach ($user as $row) { ?>
                                <option value="<?php echo $row['id_user']; ?>"> <?php echo $row['name'] ?> </option>
                            <?php  } ?>
                        </select>

                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" id="btn-alert-return" data-dismiss="modal"> <?php echo $this->lang->line("user_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-alert-confirm" data-dismiss="modal"><?php echo $this->lang->line("user_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>