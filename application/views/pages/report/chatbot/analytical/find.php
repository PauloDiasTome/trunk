<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("report_chatbot_service_topnav") ?></h6>
                </div>
                <div class="col-lg-6 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-filter" id="modalFilter"><?php echo $this->lang->line("report_chatbot_service_btn_filter") ?></button>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-export" id="modalExport"><?php echo $this->lang->line("report_chatbot_service_btn_export") ?></button>
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
                                <th><?php echo $this->lang->line("report_chatbot_service_column_name") ?></th>
                                <th><?php echo $this->lang->line("report_chatbot_service_column_channel") ?></th>
                                <th><?php echo $this->lang->line("report_chatbot_service_column_option") ?></th>
                                <th><?php echo $this->lang->line("report_chatbot_service_column_situation") ?></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
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
                            <span id="modal-name-contact" class="font-weight-bold"></span>
                            <small id="modal-number-contact" class="d-block font-weight-400"></small>
                        </div>
                    </div>

                    <div>
                        <div id="modal-status" class="diamond"></div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <div id="reportrange">
                            <span class="date-chat" id="date-chat"></span>&nbsp;
                            <i id="add-date-chat" class="far fa-calendar-plus icon-calendar"></i>
                        </div>
                        <i id="clear-date-chat" class="far fa-calendar-minus icon-calendar" style="cursor: pointer; display: none"></i>
                        <input id="dt-start-chat" type="hidden" />
                        <input id="dt-end-chat" type="hidden" />
                        <div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="icon-close" aria-hidden="true">&times;</span>
                            </button>
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

<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="modal-filter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("report_chatbot_service_filter_title") ?></h6>
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
                                <label class="custom-control-label" for="check-search"><?php echo $this->lang->line("report_chatbot_service_filter_search") ?></label>
                            </div>
                            <input class="form-control" type="text" id="input-search" placeholder="<?php echo $this->lang->line("report_chatbot_service_filter_search_placeholder") ?>" id="input-title" style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="hidden" id="verify-select-channel" value="2">
                                <input type="checkbox" class="custom-control-input" id="check-select-channel">
                                <label class="custom-control-label" for="check-select-channel"><?php echo $this->lang->line("report_chatbot_service_column_channel") ?></label>
                            </div>
                            <div id="mult-select-channel" style="display: none;">
                                <select id="multiselect-channel" name="others[]" multiple="multiple">
                                    <?php foreach ($channels as $row) { ?>
                                        <option value="<?php echo $row['id_channel']; ?>"> <?php echo $row['name'] ?> </option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="check-date">
                            <label class="custom-control-label" for="check-date"><?php echo $this->lang->line("report_chatbot_service_filter_period") ?></label>
                        </div>
                        <div class="row form-group mt-1">
                            <div class="col-6">
                                <input class="form-control" type="text" placeholder="<?php echo $this->lang->line("report_chatbot_service_filter_period_placeholder_date_start") ?>" id="dt-start" style="display: none;">
                            </div>
                            <div class="col-6">
                                <input class="form-control" type="text" disabled placeholder="<?php echo $this->lang->line("report_chatbot_service_filter_period_placeholder_date_end") ?>" id="dt-end" style="display: none">
                            </div>
                            <span class="alert-field-validation ml" id="alert-filter-period"><?php echo $this->lang->line("report_chatbot_service_filter_period_notify") ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("report_chatbot_service_filter_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-search" data-dismiss="modal"><?php echo $this->lang->line("report_chatbot_service_filter_btn_search") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="height:37%">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("report_chatbot_service_export_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("report_chatbot_service_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("report_chatbot_service_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="sendEmailExport" data-dismiss="modal"><?php echo $this->lang->line("report_chatbot_service_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DO BOTÃO DE EXPORTAR CONVERSA, DENTRO DO HISTÓRICO DE MENSAGENS -->
<div class="modal fade" id="modal-export-talk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="height:37%">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("report_chatbot_service_export_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("report_chatbot_service_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("report_chatbot_service_export_btn_return") ?></button>
                <button type="button" class="btn btn-green sendEmailExportTalk" id="sendEmailExportTalk" data-dismiss="modal"><?php echo $this->lang->line("report_chatbot_service_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ÍCONE DE EXPORTAR -->
<div class="modal fade" id="modal-icon-export-talk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="height:37%">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("report_chatbot_service_export_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("report_chatbot_service_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("report_chatbot_service_export_btn_return") ?></button>
                <button type="button" class="btn btn-green iconSendEmailExportTalk" id="iconSendEmailExportTalk" data-dismiss="modal"><?php echo $this->lang->line("report_chatbot_service_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/util.js"></script>