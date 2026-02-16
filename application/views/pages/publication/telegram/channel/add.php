<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-1 d-flex align-items-center">
                            <h3 class="mb-0"><?php echo $this->lang->line("telegram_channel_add_title") ?></h3>
                        </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <div class="good-practice-guide-new">
                                👉&nbsp;<span>
                                    <?php echo $this->lang->line("whatsapp_broadcas_guia_1") ?><a href="https://files.talkall.com.br:3000/p/s/boas_praticas_talkall.pdf" target="_blank" rel="noopener noreferrer"><?php echo $this->lang->line("whatsapp_broadcas_guia_2") ?></a>
                                </span>👈
                            </div>
                        </div>

                    </div>
                </div>
                <?php echo form_open_multipart('publication/telegram/channel/save', 'id="myform"'); ?>
                <div class="card-body">
                    <div style="display: none">
                        <input type="hidden" id="groups" name="groups" value="<?php echo isset($data['groups']) ? $data['groups'] : ""; ?>" data-toggle="tags" />
                        <input type="hidden" name="type_broadcast" id="type_broadcast" value="<?php echo $type_broadcast; ?>">
                        <input type="hidden" name="direction" id="direction" value="1">
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_title"><?php echo $this->lang->line("telegram_channel_title") ?></label>
                                <input type="text" class="form-control" name="input_title" id="input_title" maxlength="100" placeholder="<?php echo $this->lang->line("telegram_channel_title") ?>" value="<?php echo $input_title != "" ? $input_title : "";  ?>">
                                <?php echo form_error('input_title', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_input_title" style="display: none;"><?php echo $this->lang->line("telegram_channel_alert_field_validation") ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start"><?php echo $this->lang->line("telegram_channel_date_scheduling") ?></label>
                                <input type="text" class="form-control datepicker date_start" maxlength="10" name="date_start" id="date_start" placeholder="<?php echo $this->lang->line("telegram_channel_date_scheduling_placeholder") ?>" value="<?php echo $date_start ?>">
                                <?php echo form_error('date_start', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_date_start" style="display: none;"><?php echo $this->lang->line("telegram_channel_check_date") ?></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start"><?php echo $this->lang->line("telegram_channel_hour_scheduling") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start" id="time_start" maxlength="5" placeholder="<?php echo $this->lang->line("telegram_channel_hour_scheduling_placeholder") ?>" value="<?php echo $time_start ?>">
                                    <input type="hidden" class="form-control" id="timeStart" value="<?php echo isset($time_start); ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                                <?php echo form_error('time_start', '<div class="alert-status">', '</div>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="channel_id"><?php echo $this->lang->line("telegram_channel_select_channel") ?></label><br>
                                <div id="myselect">
                                    <select id="multiselect" name="others[]" multiple="multiple" style="display:none">
                                        <?php foreach ($Channels as $row) : ?>
                                            <?php
                                            $selected = (isset($others) && $others != "" && in_array($row['id_channel'], $others)) ? 'selected' : '';
                                            $disabled = ($row['status'] != 1) ? 'disabled' : '';
                                            $tooltip = ($row['status'] != 1) ? ' title="' . $this->lang->line("blocked_channel_message") . '"' : '';
                                            ?>
                                            <option value="<?php echo $row['id_channel']; ?>" <?php echo $selected; ?> <?php echo $disabled; ?><?php echo $tooltip; ?> data-status="<?php echo $row['status']; ?>"><?php echo $row['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('others[]', '<div class="alert-status">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_multi_selects" style="display: none;"><?php echo $this->lang->line("telegram_channel_alert_field_validation") ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display: none;">
                        <div class="col-12">
                            <label class="custom-toggle mb-2">
                                <input type="checkbox" id="toggle_validity" name="toggle_validity">
                                <span class="custom-toggle-slider rounded-circle <?php echo $toggle_validity == 'off' ? '' : 'custom-toggle-slider-validity' ?>" data-label-off="<?php echo $this->lang->line("telegram_channel_automatic_response_no") ?>" data-label-on="<?php echo $this->lang->line("telegram_channel_automatic_response_yes") ?>"></span>
                            </label>
                            <label class="form-control-label title-toggle-view" for=""><?php echo $this->lang->line("telegram_channel_automatic_response") ?></label>
                        </div>
                    </div>

                    <div class="row" id="date-validity" style="display: none">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start_validity"><?php echo $this->lang->line("telegram_channel_time_start_validity") ?></label>
                                <input type="text" class="form-control datepicker" name="date_start_validity" id="date_start_validity" maxlength="10" placeholder="<?php echo $this->lang->line("telegram_channel_date_scheduling_placeholder") ?>" value="<?php echo $date_start_validity != "" ? $date_start_validity : date('d/m/Y'); ?>">
                                <?php echo form_error('date_start_validity', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_date_start_validity" style="display: none;"><?php echo $this->lang->line("telegram_channel_check_date_validity") ?></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start_validity"><?php echo $this->lang->line("telegram_channel_hour_start_validity") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start_validity" id="time_start_validity" maxlength="5" placeholder="<?php echo $this->lang->line("telegram_channel_hour_scheduling_placeholder") ?>" value="<?php echo  $time_start_validity != "" ? $time_start_validity : date('H:i'); ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                                <?php echo form_error('time_start_validity', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_time_start_validity" style="display: none;"><?php echo $this->lang->line("telegram_channel_check_hour_validity") ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" id="label_chose_type" style="margin-bottom: 0px;">
                                <label class="form-control-label" for=""><?php echo $this->lang->line("telegram_channel_choose_type") ?></label>
                            </div>

                            <div class="d-flex flex-row-reverse change_type_icon">
                                <div class="change_type_icon change_type_icon_img choose_media" id="change_type_icon_img">
                                    <img src="<?php echo base_url("assets/img/statusImage.png"); ?>" alt="">
                                </div>
                                <div class="change_type_icon change_type_icon_text choose_text" id="change_type_icon_text">
                                    <img src="<?php echo base_url("assets/img/statusText.png"); ?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6" id="status_image" style="display: <?php echo $status_image ?>;">

                            <div class="form-group">
                                <div class="box-img">
                                    <div class="container">
                                        <div class="text">
                                            <span><?php echo $this->lang->line("telegram_channel_type_photo") ?></span>
                                        </div>
                                        <div class="img">
                                            <img src="<?php echo base_url("assets/img/statusImage.png"); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="alert-field-validation" id="alert_input_img_text" style="display: none;"><?php echo $this->lang->line("telegram_channel_alert_field_validation") ?></div>
                            </div>
                        </div>

                        <div class="col-6" id="status_text" style="display: <?php echo $status_text ?>;">
                            <div class="form-group">
                                <div class="box-img">
                                    <div class="container">
                                        <div class="text">
                                            <span><?php echo $this->lang->line("telegram_channel_type_text") ?></span>
                                        </div>
                                        <div class="img">
                                            <img src="<?php echo base_url("assets/img/statusText.png"); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row" id="status_data" style="display: <?php echo $status_data ?>;">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input-data"><?php echo $this->lang->line("telegram_channel_message") ?> <span style="color: red" id="count_caracter">1024</span></label>
                                <textarea class="form-control" name="input-data" id="input-data" rows="7" resize="none" placeholder="<?php echo $this->lang->line("telegram_channel_status_message_placeholder") ?>"><?php echo $input_data != "" ? $input_data : ""; ?></textarea>
                                <?php echo form_error('input-data', '<div class="alert-status">', '</div>'); ?>
                                <span class="alert-field-validation" id="alert_textarea_message" style="display:none"><?php echo $this->lang->line("telegram_channel_alert_field_validation") ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-file">
                        <input type="file" id="fileElem" multiple accept="image/*,.mp4,.pdf,.ogg" style="display:none" onchange="handleFiles(this.files)">
                    </div>

                    <div class="row">
                        <div class="col-lg-12" id="input_files" style="display:<?php echo $input_files; ?>">
                            <a href="#" id="fileSelect">
                                <div class="input-container" id="">
                                    <div class="header">
                                        <div class="title">
                                            <span><?php echo $this->lang->line("telegram_channel_loading_arquive") ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="drop-broadcast form-group" id="dropBrodcast" style="display:<?php echo $dropBroadcast ?>" multiple ondrop="dropHandler(event);" ondragover="dragOverHandler(event);">
                                <div class="drop-inner-img">
                                    <img class="drop-icon" src="<?php echo base_url("assets/img/panel/image.png") ?>" alt="">
                                </div>
                                <div class="drop-inner-title">
                                    <b><?php echo $this->lang->line("telegram_channel_title_drop") ?></b>
                                </div>
                                <div class="drop-inner-text">
                                    <span><?php echo $this->lang->line("telegram_channel_subtitle_drop") ?></span>
                                </div>
                            </div>
                            <?php echo form_error('dropBrodcast', '<div class="alert-status">', '</div>'); ?>
                            <span class="alert-field-validation" id="alert_upload_media" style="display:none; margin-top: -21px;"><?php echo $this->lang->line("telegram_channel_alert_field_validation") ?></span>
                        </div>
                    </div>

                    <div class="modal fade show" id="modal-campaign-estimate" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:840px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title" style="margin-left: 4px;"><?php echo $this->lang->line("campaign_estimate_title") ?></h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12" style="margin-top: -33px;">
                                            <span><?php echo $this->lang->line('campaign_estimate_notify_on_modal') ?></span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12" id="campaign_estimate_channel_info">
                                            <div style="margin-top: 10px;">
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_estimate_channel_info') ?>
                                                </span>
                                                <span id="list-numbers">
                                                    <?php echo $this->lang->line('campaign_estimate_suspend_campaign_extension') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 campaign-estimate" id="campaign_estimate_suspend">
                                            <div class="btn-modal">
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_estimate_suspend_campaign') ?>
                                                </span>
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_estimate_suspend_campaign_extension') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 campaign-estimate" id="campaign_estimate_change">
                                            <div class="btn-modal">
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_estimate_change_date') ?>
                                                </span>
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_estimate_change_date_extension') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 campaign-estimate" id="campaign_estimate_send_partially">
                                            <div class="btn-modal">
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_estimate_send_partially') ?>
                                                </span>
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_estimate_send_partially_extension') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 campaign-estimate" id="campaign_estimate_send_after">
                                            <div class="btn-modal">
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_estimate_send_after_hours') ?>
                                                </span>
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_estimate_send_after_hours_extension') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade show" id="modal-campaign-overlap" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:840px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title" style="margin-left: 4px;"><?php echo $this->lang->line("campaign_overlap_title") ?></h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12" style="margin-top: -33px;">
                                            <span><?php echo $this->lang->line('campaign_overlap_notify_on_modal') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12" id="campaign_overlap_channel_info">
                                            <div style="margin-top: 10px;">
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_overlap_channel_info') ?>
                                                </span>
                                                <span class="list-numbers"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 campaign-estimate" id="campaign_overlap_suspend">
                                            <div class="btn-modal">
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_overlap_suspend_campaign') ?>
                                                </span>
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_overlap_suspend_campaign_extension') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 campaign-estimate" id="campaign_overlap_change">
                                            <div class="btn-modal">
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_overlap_change_date') ?>
                                                </span>
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_overlap_change_date_extension') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 campaign-estimate" id="campaign_overlap_send_after">
                                            <div class="btn-modal">
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_overlap_send_after_hours') ?>
                                                </span>
                                                <span class="text">
                                                    <?php echo $this->lang->line('campaign_overlap_send_after_hours_extension') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade modal" id="modal-preview-campaign" tabindex="-1" role="dialog" aria-labelledby="modal-preview-campaign" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" sytle="max-width: 465px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("telegram_channel_campaign_preview") ?></h6>
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
                                            <p style="color: black;"><?php echo $this->lang->line("telegram_channel_campaign_preview_text_movel") ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="channel_id_preview"><?php echo $this->lang->line("telegram_channel_campaign_preview_channel_send") ?></label><br>
                                                <div id="myselect-preview-modal">
                                                    <select class="form-control" id="select-channel-preview" name="select-channel-preview" style="display:block">
                                                        <option value="0" style="display:block" selected="selected"><?php echo $this->lang->line("telegram_channel_campaign_preview_select") ?></option>
                                                        <?php if (isset($others) && $others != "") { ?>
                                                            <?php } else {
                                                            foreach ($Channels as $row) { ?>
                                                                <option style="display: none" value="<?php echo $row['id_channel']; ?>"><?php echo $row['name']; ?> </option>
                                                        <?php  }
                                                        } ?>
                                                    </select>
                                                    <?php echo form_error('others[]', '<div class="alert-status">', '</div>'); ?>
                                                    <div class="alert-field-validation" id="alert_select_channel"><?php echo $this->lang->line("telegram_channel_campaign_preview_text_number_fone") ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12" style="margin-bottom: 10px">
                                            <label class="form-control-label" for="channel_id_preview"><?php echo $this->lang->line("telegram_channel_campaign_preview_channel_receive") ?></label><br>
                                            <input type="text" class="form-control" id="inputFone" name="inputfone" placeholder="(00) 00000-0000" pattern="\([0-9]{2}\)[0-9]{4,6}-[0-9]{3,4}$">
                                            <input type="hidden" class="form-control" id="checkPreviewValidation" name="checkPreviewValidation" value="0">
                                            <div class="alert-field-validation" id="alert_number_fone"><?php echo $this->lang->line("telegram_channel_campaign_preview_text_number_fone") ?></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12" style="margin-top: 10px">
                                            <p style="font-size: 14px"><?php echo $this->lang->line("telegram_channel_campaign_preview_text_optin") ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer p-footer" style="margin-top: -15px">
                                    <button type="button" class="btn btn-medium-blue" id="btn-send-preview"><?php echo $this->lang->line("telegram_channel_campaign_preview_btn_send_preview") ?></button>
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
                                            <p style="font-weight: bold"><?php echo $this->lang->line("telegram_channel_campaign_preview_text_preview_info_success") ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p style="color: black;"><?php echo $this->lang->line("telegram_channel_campaign_preview_text_preview_info_message") ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer p-footer" style="margin-top: -15px"></div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($file)) {
                        foreach ($file as $key => $val) { ?>
                            <div class="retrieve">
                                <input type="hidden" class="retrieve-file" value="<?php echo $file[$key]; ?>">
                                <input type="hidden" class="retrieve-text" value="<?php echo isset($text[$key]) ? $text[$key] : '' ?>">
                                <input type="hidden" class="retrieve-byte" value="<?php echo $byte[$key]; ?>">
                                <input type="hidden" class="retrieve-thumbnail" value="<?php echo $thumb[$key]; ?>">
                                <input type="hidden" class="media_type" value="<?php echo $media_type[$key] ?>">
                            </div>
                    <?php }
                    } ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>publication/telegram/channel"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("telegram_channel_btn_cancel") ?></button></a>
                            <!-- <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="" style="margin-right: 0" id="preview-campaign"><?php echo $this->lang->line("telegram_channel_campaign_preview") ?></button> -->
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("telegram_channel_btn_save") ?></button>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>