<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 d-inline-block mb-0 text-white"><?php echo $this->lang->line("setting_bot_trainer_topnav") ?></h6>
                </div>
                <div class="col-lg-6 text-right">
                    <a href="<?php echo isset($id) ? "./" . "{$id}/" : "trainer/"; ?>add"><button type="button" class="btn btn-sm btn-neutral mr-2"><?php echo $this->lang->line("setting_bot_trainer_btn_new") ?></button></a>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-export" id="modalExport"><?php echo $this->lang->line("setting_bot_trainer_btn_export") ?></button>
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
                        <div class="col-6">
                            <?php $level = $this->lang->line("setting_bot_trainer_level_one"); $level_two = $this->lang->line("setting_bot_trainer_level_two"); $level_option = $this->lang->line("setting_bot_trainer_option"); ?>
                            <?php $link_chat_find = isset($id_submenu) ? "<a href='../../bot/trainer' id='level-one'>$level</a> | <a href='../../bot/trainer/$id_submenu' id='timeline-level'>$level_two</a>" : "<a id='level-one' href='../../bot/trainer'>$level</a>"; ?>
                            <h3 class="mb-0"><?php echo isset($id) ? "$link_chat_find | $level_option $option" : ""; ?></h3>
                        </div>
                        <?php if (isset($id_submenu)) : ?>
                            <style>
                                .access_link {
                                    display: none;
                                }
                            </style>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("setting_bot_trainer_column_option") ?></th>
                                <th><?php echo $this->lang->line("setting_bot_trainer_column_description") ?></th>
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
                <h5 class="modal-title"><?php echo $this->lang->line("setting_bot_trainer_export_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("setting_bot_trainer_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("setting_bot_trainer_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="sendEmailExport" data-dismiss="modal"><?php echo $this->lang->line("setting_bot_trainer_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>