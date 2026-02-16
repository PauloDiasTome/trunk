<div class="container mt--5">
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="mb-0"><?php echo $this->lang->line("persona_add_title") ?></h3>
                        </div>
                    </div>
                </div>

                <div class="card-body persona-card">
                    <?php echo form_open("persona/save"); ?>
                    <div class="row">
                        <div class="col-3 align-self-center">
                            <div class="box-picture-persona-config">
                                <div class="picture-persona transition-effect">
                                    <i class="fas fa-camera icon-add-photo" id="add-profile"></i>
                                    <span class="picture-persona-title"><?php echo $this->lang->line("persona_add_picture") ?></span>
                                    <img src="<?php echo base_url("assets/img/panel/image_placeholder.png") ?>">
                                    <input type="file" class="form-control" id="input-file" accept="image/png, image/gif, image/jpeg, image/jpg, image/jfif" style="display: none" />
                                    <input type="text" name="file" id="input-file-hidden" style="display: none" />
                                </div>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-name"><?php echo $this->lang->line("persona_name") ?></label>
                                        <input type="text" id="input-name" name="input-name" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("persona_name_placeholder") ?>">
                                        <div class="alert-field-validation" id="alert-input-name"></div>
                                        <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="select-channel"><?php echo $this->lang->line("persona_add_select_channel") ?></label>
                                        <select class="form-control" id="select-channel" name="select-channel">
                                            <option value=""><?php echo $this->lang->line("persona_add_select_channel_placeholder") ?></option>
                                            <?php foreach ($channel as $value) { ?>
                                                <option data-id="<?php echo $value["id"] ?>" value="<?php echo $value["id_channel"] ?>"><?php echo $value["name"] ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="alert-field-validation" id="alert-select-channel"></div>
                                        <?php echo form_error('select-channel', '<div class="alert-field-validation">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>

                <div class="card-footer persona-card">
                    <div class="row mb-3">
                        <div class="col-6">
                            <span class="link-add" id="add-contacts"><?php echo $this->lang->line("persona_link_add_contacts") ?></span>
                        </div>
                        <div class="col-6">
                            <span class="link-add float-right" id="import-contacts"><?php echo $this->lang->line("persona_link_import") ?></span>
                        </div>
                    </div>
                    <div class="row mb-3" id="contacts-table" style="display: none">
                        <div class="col-12">
                            <table class="table" id="persona-contact-table">
                            </table>
                        </div>
                    </div>
                    <div class="row" id="table-contacts-footer">
                        <div class="col-6">
                            <button class="btn btn-danger" type="button" id="cancel-persona"><?php echo $this->lang->line("persona_add_btn_cancel") ?></button>
                            <button class="btn btn-success" type="submit" id="btn-success"><?php echo $this->lang->line("persona_btn_save") ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-add-contacts" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?php echo $this->lang->line("persona_participants_title") ?></h5>
                <i class="fas fa-times close-modal" style="color: rgb(0 0 0 / 28%);" data-dismiss="modal" aria-label="Close"></i>
            </div>

            <div class="modal-body">

                <div class="modal-search">
                    <img src="<?php echo base_url("assets/icons/panel/search.svg") ?>">
                    <input type="text" class="form-control" id="search-contact" placeholder="<?php echo $this->lang->line("persona_participants_placeholder") ?>">
                </div>

                <div class="main-modal">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" id="clean-persona" data-dismiss="modal"><?php echo $this->lang->line("persona_participants_btn_cancel") ?></button>
                <button class="btn btn-green" id="btn-add-contacts" data-dismiss="modal"><?php echo $this->lang->line("persona_participants_btn_confirm") ?></button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="modal-import-contacts" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("persona_import_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 25px; font-weight: bolder;">×</span>
                </button>
            </div>

            <div class="modal-body mt--2 mb--5">

                <div class="form-group">
                    <label class="size-14"><?php echo $this->lang->line("persona_import_obs") ?></label><br>

                    <div class="divTextAreaImport">
                        <textarea id="list-contacts" class="form-control" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="alert-import">
                    <img id="icon-import" src="<?php echo base_url("assets/icons/panel/help_center.svg") ?>" title="<?php echo $this->lang->line('persona_alert_import_info') ?>">
                    <span class="alert-import-text"><?php echo $this->lang->line('persona_import_alert_import') ?></span>
                </div>
                <button type="button" class="btn btn-primary" id="btn-import-contacts">
                    <?php echo $this->lang->line("persona_import_btn_import_advance") ?> <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-preview-contacts" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("persona_import_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body mt--3">
                <div class="row">
                    <div class="col-12">
                        <label class="size-14"><?php echo $this->lang->line("persona_import_column_relate_data") ?></label><br>
                    </div>
                </div>

                <div class="row table-preview">
                    <div class="col-12">
                        <table class="table table-striped" id="table-for-preview">
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top">
                <div class="alert-import">
                    <img id="icon-preview" src="<?php echo base_url("assets/icons/panel/help_center.svg") ?>" title="<?php echo $this->lang->line('persona_alert_import_info_preview') ?>">
                    <span class="alert-import-text"><?php echo $this->lang->line('persona_import_alert_import') ?></span>
                </div>
                <button type="button" class="btn btn-primary" id="btn-import-confirm">
                    <?php echo $this->lang->line("persona_import_btn_import_contacts") ?> <i class="fas fa-user-plus"></i>
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-progress" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title" id="title-progress"></h5>
            </div>

            <div class="modal-body">
                <div class="row desc">
                    <span id="warning"><?php echo $this->lang->line('persona_progress_body') ?></span>
                    <span id="description-progress"></span>
                </div>
                <div class="row">
                    <div class="progress" id="progress-main">
                        <div class="light-effect"></div>
                        <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                    </div>
                    <div class="load" id="load-main">
                        <img src="/assets/img/loads/loading_2.gif" alt="image">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>