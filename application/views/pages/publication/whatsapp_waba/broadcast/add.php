<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("whatsapp_broadcast_waba_add_title") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart('publication/whatsapp/broadcast/waba/save', 'id="myform"'); ?>

                <input type="hidden" id="media-name" name="media-name" value="">
                <div class="card-body">
                    <div id="parameters" style="display: none">
                        <input type="hidden" id="groups" name="groups" value="<?php echo isset($data['groups']) ? $data['groups'] : ""; ?>" data-toggle="tags" />
                        <input type="hidden" name="direction" id="direction" value="1">
                        <input name="headerType" id="headerType" value="" hidden>
                        <input name="headerText" id="headerText" value="" hidden>
                        <input name="bodyText" id="bodyText" value="" hidden>
                        <input name="footerText" id="footerText" value="" hidden>
                        <input name="buttonsParameters" id="buttonsParameters" value="" hidden>
                        <input name="bodyParameters" id="bodyParameters" value="" hidden>

                    </div>

                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("whatsapp_broadcast_waba_add_information") ?></h6>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_title"><?php echo $this->lang->line("whatsapp_broadcast_waba_title") ?></label>
                                <input type="text" class="form-control" name="input_title" id="input_title" maxlength="100" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_waba_title") ?>">
                                <?php echo form_error('input_title', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_input_title"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start"><?php echo $this->lang->line("whatsapp_broadcast_waba_date_scheduling") ?></label>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("whatsapp_broadcast_waba_date_tooltip") ?>"></i>
                                <input type="text" class="form-control datepicker" name="date_start" id="date_start" maxlength="10" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_waba_date_scheduling_placeholder") ?>" value="<?php echo $date_start ?>">
                                <?php echo form_error('date_start', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_date_start"></div>
                            </div>
                        </div>

                        <div class=" col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start"><?php echo $this->lang->line("whatsapp_broadcast_waba_hour_scheduling") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start" id="time_start" maxlength="5" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_waba_hour_scheduling_placeholder") ?>" value="<?php echo $time_start ?>">
                                    <input type="hidden" class="form-control" id="timeStart">
                                </div>
                                <?php echo form_error('time_start', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_time_start"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display:none;">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="channel_id"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_channel") ?></label><br>
                                <div id="myselect">
                                    <select id="multiselect" name="others[]" multiple="multiple" style="display:none">
                                        <?php foreach ($channel as $row) { ?>
                                            <option value="<?php echo $row['id_channel']; ?>"> <?php echo $row['name'] ?> </option>
                                        <?php  } ?>
                                    </select>
                                    <?php echo form_error('others[]', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="select_channel"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_channel") ?></label>
                                <select class="form-control" name="select_channel" id="select_channel">
                                    <option value="0"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_channel_placeholder") ?></option>
                                    <?php foreach ($channel as $row) { ?>
                                        <option channel="<?php echo $row['id']; ?>" value="<?php echo $row['id_channel']; ?>"> <?php echo $row['name'] ?> </option>
                                    <?php  } ?>
                                </select>
                                <div class="alert-field-validation" id="alert_multiselect"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label class="custom-toggle mb-2">
                                <input type="checkbox" id="toggle-segmented-group" value="1">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="<?php echo $this->lang->line("whatsapp_broadcast_waba_segments_no") ?>" data-label-on="<?php echo $this->lang->line("whatsapp_broadcast_waba_segments_yes") ?>"></span>
                            </label>
                            <label class="form-control-label title-toggle-view"><?php echo $this->lang->line("whatsapp_broadcast_waba_segments_campaign_option") ?></label>
                        </div>
                    </div>

                    <div class="row" id="segmented_group" style="display: none;">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="select_segmented_group"><?php echo $this->lang->line("whatsapp_broadcast_waba_segments_campaign") ?></label>
                                <select class="form-control" name="select_segmented_group" id="select_segmented_group">
                                    <option value="0"><?php echo $this->lang->line("whatsapp_broadcast_waba_segments_select_group_placeholder"); ?></option>
                                </select>
                                <div class="alert-field-validation" id="alert_segmented_group"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="select_template"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_template") ?></label>
                                <select class="form-control" name="select_template" id="select_template">
                                    <option class="channel_0" value="0"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_template_placeholder") ?></option>
                                    <?php foreach ($template as $row) { ?>
                                        <option class="channel_<?php echo $row['account_key_remote_id']; ?>" value="<?php echo $row['id_template']; ?>" data-buttons='<?php echo $row["buttons"] ?>' data-text="<?php echo $row['text_body'] ?>" data-footer="<?php echo $row['text_footer'] ?>" data-header-type="<?php echo $row['header_type'] ?>" data-header="<?php echo $row['header'] ?>">
                                            <?php echo $row['name']; ?></option>
                                    <?php  } ?>
                                </select>
                                <div class="alert-field-validation" id="alert_template"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row" hidden id="header">
                        <label id="label_header" class="form-control-label" for="select_channel" style="font-size: medium; color: black; margin-bottom: 20px; margin-left: 15px;" hidden><?php echo $this->lang->line("whatsapp_broadcast_waba_segments_header") ?></label>
                        <div class="col-lg-12">
                            <div class="form-group" id="header_temp_param"></div>
                        </div>
                    </div>

                    <hr style="color:gray; height: 3px; box-shadow: 0.5px 1px #888888; margin-top: -20px" hidden />

                    <label id="label_body" class="form-control-label" for="select_channel" style="font-size: medium; color: black; margin-bottom: 0px;" hidden><?php echo $this->lang->line("whatsapp_broadcast_waba_segments_body") ?></label>

                    <div class="row" hidden id="header_template_parameters">

                        <div class="col-lg-4">
                            <div class="form-group" id="body_temp_param"></div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group" id="body_template_parameters"></div>
                        </div>

                    </div>

                    <hr style="color:gray; height: 3px; box-shadow: 0.5px 1px #888888;" hidden />

                    <label id="label_buttons" class="form-control-label" for="select_channel" style="font-size: medium; color: black; margin-bottom: 0px;" hidden><?php echo $this->lang->line("whatsapp_broadcast_waba_segments_buttons") ?></label>

                    <div class="row" hidden id="buttons_template_parameters">
                        <div class="col-lg-8">
                            <div class="form-group" id="buttons_temp_url"></div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group" id="buttons_temp_param"></div>
                        </div>
                    </div>

                    <div class="row" id="view-template" hidden>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="message-template"><?php echo $this->lang->line("whatsapp_broadcast_waba_mensagem") ?></label>
                                <i hidden class="fa fa-eye-slash" id="preViewShow" style="margin-left: 10px; cursor: pointer" onclick="alterParametros(true)" title="<?php echo $this->lang->line("whatsapp_broadcast_waba_add_information_view") ?>"></i>
                                <i hidden class="fa fa-eye" id="preViewClose" style="margin-left: 10px; cursor: pointer" onclick="alterParametros(false)" title="<?php echo $this->lang->line("whatsapp_broadcast_waba_add_information_view") ?>"></i>
                            </div>

                            <div class="row template-whatsapp-broadcast-view" hidden>
                                <div class="wa-template form-group">
                                    <div class="col-12">
                                        <div class="row justify-content-center">
                                            <div class="wa-header">
                                                <div class="col-12">
                                                    <img src="https://app.talkall.com.br/assets/img/broadcast_approval/header_wa.jpeg" draggable="false" alt="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-end">
                                            <div class="wa-body">
                                                <div class="col align-self-end">

                                                    <div class="box-broadcast-approval" draggable="false">

                                                        <div class="textarea-status" style="width: 275px;">

                                                            <div class="header-message" id="img_fixed_header" style="background-color: white; border-radius: 8px; padding: 45px; margin: 8px;" hidden>
                                                                <div class="header-message-media">
                                                                    <i class="far fa-image fa-4x" style="color: #80808091; margin-left: 50px;"></i>
                                                                </div>
                                                            </div>

                                                            <img id="imgHeaderText" src="" />

                                                            <video id="video_header" controls></video>

                                                            <a href="" target="_blank" id="doc_header">
                                                                <canvas id="pdf-canva"></canvas>
                                                            </a>

                                                            <span class="tex-area" disabled id="tex_area_view_templateHeader" data-type="type-image"></span>

                                                            <p class="tex-area" disabled id="tex_area_view_template" data-type="type-image"></p>

                                                            <span class="tex-area" disabled id="tex_area_view_templateFooter" data-type="type-image"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="wa-footer">
                                                <div class="col">
                                                    <img src="https://app.talkall.com.br/assets/img/broadcast_approval/footer_wa.png" draggable="false" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal fade" id="modal-approval" tabindex="-1" role="dialog" aria-labelledby="modal-approval" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">

                                    <div class="modal-header b-bottom">
                                        <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_title") ?></h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox mb-1">
                                                        <input type="checkbox" class="custom-control-input" name="check_email" id="check_email">
                                                        <label class="custom-control-label" for="check_email"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_email") ?></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox mb-1">
                                                        <input type="checkbox" class="custom-control-input" name="check_whatsapp" id="check_whatsapp">
                                                        <label class="custom-control-label" for="check_whatsapp"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_whatsapp") ?></label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="alert-field-validation" id="alert_checkbox_email_whatsapp"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for=""><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_group_contacts") ?></label>
                                                    <select class="form-control" name="select_segmented_group_approval" id="select_segmented_group_approval">
                                                        <option value="0"><?php echo $this->lang->line("whatsapp_broadcast_waba_segments_select_group_placeholder"); ?></option>
                                                        <?php for ($i = 0; $i < count($Groups); $i++) { ?>
                                                            <option value="<?php echo $Groups[$i]["id_group_contact"] ?>">
                                                                <?php echo $Groups[$i]["name"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="alert-field-validation" id="alert_select_segmented_group_approval"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="approval_message"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_message") ?></label>
                                                    <textarea class="form-control" name="approval_message" id="approval_message" cols="30" rows="5" style="resize: none;" maxlength="1024" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_message_placeholder") ?>"></textarea>
                                                    <div class="alert-field-validation" id="alert_approval_message"></div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <span class="text-sm"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_info") ?></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer b-top p-footer">
                                        <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_modal_return") ?></button>
                                        <button type="button" class="btn btn-green" id="btn-send"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_modal_send") ?></button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modal-preview-campaign" tabindex="-1" role="dialog" aria-labelledby="modal-preview-campaign" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document" sytle="max-width: 465px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("whatsapp_broadcast_waba_campaign_preview") ?></h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="img-justify">
                                                    <img src="<?php echo base_url('assets/icons/panel/previewNotificationNumber.svg') ?>" draggable="false" alt="previewNotificationNumber">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <p style="color: black;"><?php echo $this->lang->line("whatsapp_broadcast_waba_campaign_preview_text_movel") ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12" style="margin-bottom: 10px">
                                                <input type="text" class="form-control" id="inputFone" name="inputfone" placeholder="(00) 00000-0000" pattern="\([0-9]{2}\)[0-9]{4,6}-[0-9]{3,4}$">
                                                <div class="alert-field-validation" id="alert_number_fone"><?php echo $this->lang->line("whatsapp_broadcast_waba_campaign_preview_text_number_fone") ?></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12" style="margin-top: 10px">
                                                <p style="font-size: 14px"><?php echo $this->lang->line("whatsapp_broadcast_waba_campaign_preview_text_optin") ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer p-footer" style="margin-top: -15px">
                                        <!-- <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("whatsapp_broadcast_waba_campaign_preview_btn_close") ?></button> -->
                                        <button type="button" class="btn btn-medium-blue" id="btn-send-preview"><?php echo $this->lang->line("whatsapp_broadcast_waba_campaign_preview_btn_send_preview") ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modal-preview-campaign-success" tabindex="-1" role="dialog" aria-labelledby="modal-preview-campaign-success" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document" sytle="max-width: 465px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title" id="modal-title-notification"></h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="img-justify">
                                                    <img src="<?php echo base_url('assets/icons/panel/previewSendSuccess.svg') ?>" draggable="false" alt="previewNotificationNumber">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <p style="font-weight: bold"><?php echo $this->lang->line("whatsapp_broadcast_waba_campaign_preview_text_preview_info_success") ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <p style="color: black;"><?php echo $this->lang->line("whatsapp_broadcast_waba_campaign_preview_text_preview_info_message") ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer p-footer" style="margin-top: -15px"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast/waba"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("whatsapp_broadcast_waba_btn_cancel") ?></button></a>
                            <!-- <button class="btn btn-info" type="button" data-toggle="modal" data-target="#modal-approval" style="margin-left: 10px" id="modalApproval"><?php echo $this->lang->line("whatsapp_broadcast_waba_btn_approval") ?></button> -->
                            <button class="btn btn-secondary" type="button"  style="margin-right: 0" id="preview-campaign"><?php echo $this->lang->line("whatsapp_broadcast_waba_btn_campaign_preview") ?></button>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("whatsapp_broadcast_waba_btn_save") ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div style="position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 9999;" id="gif_load" hidden>

    <img style="left: 41%; top: 35%; position: absolute;" src='<?php echo base_url("\assets\img\loads\loading_2.gif") ?>' />

</div>

<script src="<?php echo base_url('/assets/lib/build_pdf/build_pdf.js') ?>"></script>