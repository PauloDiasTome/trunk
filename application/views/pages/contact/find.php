<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 d-inline-block mb-0 text-white"><?php echo $this->lang->line('contact_topnav'); ?></h6>
                </div>
                <div class="col-lg-6 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-filter" id="modalFilter"><?php echo $this->lang->line("contact_btn_filter") ?></button>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-export" id="modalExport"><?php echo $this->lang->line('contact_btn_export'); ?></button>
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
                    <div class="header-contact">
                        <div class="d-flex justify-content-between">
                            <div class="main-left">
                                <div class="dropdown">
                                    <input type="checkbox" id="dropdownMenuInput" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" title="Selecionar" style="display: none;">
                                    <i class="fas fa-caret-down" id="dropdownMenuIcon" style="display:none;"></i>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuInput">
                                        <a class="dropdown-item" id="all" href="#"><?php echo $this->lang->line('contact_dropdown_menu_all') ?></a>
                                        <a class="dropdown-item" id="empty" href="#"><?php echo $this->lang->line('contact_dropdown_menu_empty') ?></a>
                                    </div>
                                </div>
                                <div class="info-contact" id="infoContact" style="display: none;">
                                    <span class="text-count-info"><b id="countInfo">0</b></span>
                                </div>
                            </div>
                            <div class="group-btn" style="display:none">
                                <div class="ml-2" id="btn-add-persona" style="display:none">
                                    <button id="btn-persona" type="button" class="btn btn-success btn-sm"><?php echo $this->lang->line('contact_btn_add_persona') ?></button>
                                </div>
                                <div class="ml-2 block-contact" id="btn-block">
                                    <button type="button" class="btn btn-danger btn-sm"><?php echo $this->lang->line('contact_btn_block') ?></button>
                                </div>
                                <div class="ml-2 unblock-contact" id="btn-unblock">
                                    <button type="button" class="btn btn-success btn-sm"><?php echo $this->lang->line('contact_btn_unblock') ?></button>
                                </div>
                                <div class="ml-2" id="btn-delete">
                                    <button type="button" class="btn btn-warning btn-sm"><?php echo $this->lang->line('contact_btn_delete') ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr role="row">
                                <th style="width: 1%;"></th>
                                <th style="width: 20%"><?php echo $this->lang->line('contact_column_contact'); ?></th>
                                <th style="width: 20%"><?php echo $this->lang->line('contact_column_channel'); ?></th>
                                <th style="width: 10%"><?php echo $this->lang->line('contact_column_creation_date'); ?></th>
                                <th style="width: 10%"><?php echo $this->lang->line('contact_column_status'); ?></th>
                                <th style="width: 10%"></th>
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
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("contact_filter_title") ?></h6>
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
                                <label class="custom-control-label" for="check-search"><?php echo $this->lang->line("contact_filter_search") ?></label>
                            </div>
                            <div class="search-contact">
                                <div class="row">
                                    <div class="col-4">
                                        <select class="form-control" name="" id="search-type">
                                            <option value="contains"><?php echo $this->lang->line("contact_filter_search_option_contains") ?></option>
                                            <option value="starts_with"><?php echo $this->lang->line("contact_filter_search_option_starts_with") ?></option>
                                        </select>
                                    </div>
                                    <div class="col-8">
                                        <input class="form-control" type="text" id="input-search" placeholder="<?php echo $this->lang->line("contact_filter_search_placeholder") ?>" id="input-title" style="display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="hidden" id="verify-select" value="2">
                                <input type="checkbox" class="custom-control-input" id="check-select">
                                <label class="custom-control-label" for="check-select">
                                    <?php echo $this->lang->line("contact_filter_channel") ?>
                                </label>
                            </div>
                            <div id="mult-select" style="display: none;">
                                <select id="multiselect" name="channels[]" multiple="multiple">
                                    <?php foreach ($channels as $row) { ?>
                                        <option value="<?php echo $row['id_channel']; ?>">
                                            <?php echo $row['name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="hidden" id="verify-select-persona" value="2">
                                <input type="checkbox" class="custom-control-input" id="check-persona">
                                <label class="custom-control-label" for="check-persona">
                                    <?php echo $this->lang->line("contact_filter_persona") ?>
                                </label>
                            </div>
                            <div id="mult-select-persona" style="display: none;">
                                <select id="select-persona" name="persona[]" multiple="multiple">
                                    <?php foreach ($personas as $row) { ?>
                                        <option value="<?php echo $row['id_group_contact']; ?>">
                                            <?php echo $row['name'] ?>
                                        </option>
                                    <?php } ?>
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
                                <label class="custom-control-label" for="check-select2"><?php echo $this->lang->line("contact_filter_tag") ?></label>
                            </div>
                            <div id="mult-select2" style="display: none;">
                                <select id="multiselect2" name="languages[]" multiple="">
                                    <?php foreach ($labels as $row) { ?>
                                        <option value="<?php echo $row['id_label']; ?>"> <?php echo $row['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check-responsible">
                                <label class="custom-control-label" for="check-responsible"><?php echo $this->lang->line("contact_filter_responsible") ?></label>
                            </div>
                            <select class="form-control mt-1" id="select-responsible" style="display:none">
                                <option value=""><?php echo $this->lang->line("contact_filter_responsible_select") ?></option>
                                <?php foreach ($users as $row) { ?>
                                    <option value="<?php echo $row['key_remote_id']; ?>"> <?php echo $row['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check-situation">
                                <label class="custom-control-label" for="check-situation"><?php echo $this->lang->line("contact_filter_situation") ?></label>
                            </div>
                            <select class="form-control mt-1" id="select-situation" style="display:none">
                                <option value=""><?php echo $this->lang->line("contact_filter_situation_select") ?></option>
                                <option value="2"><?php echo $this->lang->line("contact_filter_situation_verified") ?></option>
                                <option value="1"><?php echo $this->lang->line("contact_filter_situation_not_verified") ?></option>
                                <option value="3"><?php echo $this->lang->line("contact_filter_situation_no_whatsapp_account") ?></option>
                                <option value="4"><?php echo $this->lang->line("contact_filter_situation_spam") ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="check-date">
                            <label class="custom-control-label" for="check-date"><?php echo $this->lang->line("contact_filter_period") ?></label>
                        </div>
                        <div class="row form-group mt-1">
                            <div class="col-6">
                                <input class="form-control" type="text" placeholder="<?php echo $this->lang->line("contact_filter_period_placeholder_date_start") ?>" id="dt-start" style="display: none;">
                            </div>
                            <div class="col-6">
                                <input class="form-control" type="text" disabled placeholder="<?php echo $this->lang->line("contact_filter_period_placeholder_date_end") ?>" id="dt-end" style="display: none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("contact_filter_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-search" data-dismiss="modal"><?php echo $this->lang->line("contact_filter_btn_search") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("contact_export_title"); ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("contact_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                        <input type="text" class="form-control" id="emailExport" placeholder="<?php echo $this->lang->line("contact_export_email_placeholder"); ?>" autocomplete="off" value="<?php echo $this->session->tempdata('email') ?>" disabled hidden>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"> <?php echo $this->lang->line("contact_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="sendEmailExport" data-dismiss="modal"><?php echo $this->lang->line("contact_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-open-chat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("contact_messenger_open") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row text-center">
                    <label class="h4 px-md-3" id="label-info-contact"></label>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("contact_messenger_btn_cancel") ?></button>
                <button type="button" class="btn btn-green" id="open-chat" data-dismiss="modal"><?php echo $this->lang->line("contact_messenger_btn_ok") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-process" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title" id="title-progress"></h5>
            </div>

            <div class="modal-body">
                <div class="row desc">
                    <span id="warning"><?php echo $this->lang->line("contact_info_description") ?></span>
                    <span id="description-progress"></span>
                    <span id="finalized-contacts"></span>
                </div>
                <div class="row">
                    <div class="progress" id="progress-main" style="display: none;">
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


<div class="modal fade" id="modal-add-persona" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background-color: transparent;">

            <div class="card" style="margin-bottom: 0;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="mb-0"><?php echo $this->lang->line("contact_modal_persona_title") ?></h3>
                        </div>
                    </div>
                </div>

                <div class="card-body persona-card">
                    <div class="row">
                        <div class="col-3 align-self-center">
                            <div class="box-picture-persona-config">
                                <div class="picture-persona transition-effect">
                                    <i class="fas fa-camera icon-add-photo" id="add-profile"></i>
                                    <span class="picture-persona-title"><?php echo $this->lang->line("contact_modal_persona_picture") ?></span>
                                    <img style="width: 11rem;">
                                    <input type="file" class="form-control" id="input-file" accept="image/png, image/gif, image/jpeg, image/jpg, image/jfif" style="display: none" />
                                    <input type="text" name="file" id="input-file-hidden" style="display: none" />
                                </div>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-name"><?php echo $this->lang->line("contact_modal_persona_name") ?></label>
                                        <input type="text" id="input-name" name="input-name" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("contact_modal_persona_name_placeholder") ?>">
                                        <div class="alert-field-validation" id="alert__input-name"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-channel"><?php echo $this->lang->line("contact_modal_persona_channel") ?></label>
                                        <input type="text" id="input-channel" readonly="true" name="input-channel" class="form-control" maxlength="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="padding: 0px 23px 14px 0;">
                    <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("contact_modal_persona_btn_return") ?></button>
                    <button type="button" id="save-persona" class="btn btn-green"><?php echo $this->lang->line("contact_modal_persona_btn_save") ?></button>
                </div>
            </div>

        </div>
    </div>
</div>


<div id="loadingOverlay" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
    pointer-events: all;
">
    <div class="text-center">
        <div class="mt-3 fs-5 text-dark"><?php echo $this->lang->line('contact_loading') ?></div>
    </div>
</div>