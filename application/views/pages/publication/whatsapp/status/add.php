<?php echo form_open_multipart('publication/whatsapp/status/save', 'id="myform"'); ?>
<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("whatsapp_status_add_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display: none">
                        <input type="hidden" id="groups" name="groups" value="<?php echo isset($data['groups']) ? $data['groups'] : ""; ?>" data-toggle="tags" />
                        <input type="hidden" name="type_status" id="type_status" value="<?php echo $type_status; ?>">
                        <input type="hidden" name="direction" id="direction" value="1">
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_title"><?php echo $this->lang->line("whatsapp_status_title") ?></label>
                                <input type="text" class="form-control" name="input_title" id="input_title" maxlength="100" placeholder="<?php echo $this->lang->line("whatsapp_status_title") ?>" value="<?php echo $input_title != "" ? $input_title : "";  ?>">
                                <?php echo form_error('input_title', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_input_title" style="display: none;"><?php echo $this->lang->line("whatsapp_status_alert_field_validation") ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start"><?php echo $this->lang->line("whatsapp_status_date_scheduling") ?></label>
                                <input type="text" class="form-control datepicker" name="date_start" id="date_start" maxlength="10" placeholder="<?php echo $this->lang->line("whatsapp_status_date_scheduling_placeholder") ?>" value="<?php echo $date_start != "" ? $date_start : date('d/m/Y'); ?>">
                                <?php echo form_error('date_start', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_date_start" style="display: none;"><?php echo $this->lang->line("whatsapp_status_check_date") ?></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start"><?php echo $this->lang->line("whatsapp_status_hour_scheduling") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start" id="time_start" maxlength="5" placeholder="<?php echo $this->lang->line("whatsapp_status_hour_scheduling_placeholder") ?>" value="<?php echo $time_start != "" ? $time_start : date('H:i'); ?>">
                                    <input type="hidden" class="form-control" id="timeStart" value="<?php echo isset($time_start); ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                                <?php echo form_error('time_start', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_time_start" style="display: none;"><?php echo $this->lang->line("whatsapp_status_check_hour") ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="channel_id"><?php echo $this->lang->line("whatsapp_status_select_channel") ?></label><br>
                                <div id="myselect">
                                    <select id="multiselect" name="others[]" multiple="multiple" style="display:none">
                                        <?php foreach ($Channels as $row) : ?>
                                            <?php
                                            $selected = (isset($others) && $others != "" && in_array($row['id_channel'], $others)) ? 'selected' : '';
                                            $disabled = ($row['status'] != 1) ? 'disabled' : '';
                                            $tooltip = ($row['status'] != 1) ? ' title="' . $this->lang->line("blocked_channel_message") . '"' : '';
                                            ?><option value="<?php echo $row['id_channel']; ?>" <?php echo $selected; ?> <?php echo $disabled; ?><?php echo $tooltip; ?> data-status="<?php echo $row['status']; ?>"><?php echo $row['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('others[]', '<div class="alert-status">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_multi_selects" style="display: none;"><?php echo $this->lang->line("whatsapp_status_alert_field_validation") ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="segmented" style="display:none">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="segmented"><?php echo $this->lang->line("whatsapp_broadcast_segments_select") ?></label>
                                <select class="form-control" name="select_segmented" id="select_segmented">
                                    <option value="0"><?php echo $this->lang->line("whatsapp_broadcast_segments_select_placeholder"); ?></option>
                                    <?php if (isset($campaignSegment)) { ?>
                                        <?php for ($i = 0; $i < count($campaignSegment); $i++) { ?>
                                            <option value="<?php echo $campaignSegment[$i]->id ?>"><?php echo $campaignSegment[$i]->matriz ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-12">
                            <label class="custom-toggle mb-2">
                                <input type="checkbox" id="toggle-segmented-group" name="<?php echo $Channels == "null" ? "toggle-segmented-group" : "toggle-segmented" ?>">
                                <span class="custom-toggle-slider rounded-circle custom-toggle-slider-segmented" data-label-off="<?php echo $this->lang->line("whatsapp_status_segments_no") ?>" data-label-on="<?php echo $this->lang->line("whatsapp_status_segments_yes") ?>"></span>
                            </label>
                            <label class="form-control-label title-toggle-view" for=""><?php echo $this->lang->line("whatsapp_status_segments_campaign") ?></label>
                        </div>
                    </div> -->

                    <div class="row" id="segmented_group" style="display:none">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="select_segmented_group"><?php echo $this->lang->line("whatsapp_status_segments_select_group") ?></label>
                                <select class="form-control" name="select_segmented_group" id="select_segmented_group">
                                    <option value="0"><?php echo $this->lang->line("whatsapp_status_segments_select_group_placeholder"); ?></option>
                                    <?php if (isset($Groups)) { ?>
                                        <?php for ($i = 0; $i < COUNT($Groups); $i++) { ?>
                                            <option value="<?php echo $Groups[$i]['id_group_contact']; ?>"><?php echo $Groups[$i]['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" id="label_chose_type" style="margin-bottom: 0px;">
                                <label class="form-control-label" for=""><?php echo $this->lang->line("whatsapp_status_choose_type") ?></label>
                            </div>

                            <div class="d-flex flex-row-reverse change_type_icon">
                                <div class="change_type_icon change_type_icon_img choose_media" id="change_type_icon_img">
                                    <img src="<?php echo base_url("assets/img/statusImage.png"); ?>" alt="">
                                </div>
                                <!-- <div class="change_type_icon change_type_icon_text choose_text" id="change_type_icon_text">
                                    <img src="<?php echo base_url("assets/img/statusText.png"); ?>" alt="">
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12" id="status_image" style="display:none">

                            <div class="form-group">
                                <div class="box-img">
                                    <div class="container">
                                        <div class="text">
                                            <span><?php echo $this->lang->line("whatsapp_status_type_photo") ?></span>
                                        </div>
                                        <div class="img">
                                            <img src="<?php echo base_url("assets/img/statusImage.png"); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="alert-field-validation" id="alert_input_img_text" style="display: none;"><?php echo $this->lang->line("whatsapp_status_alert_field_validation") ?></div>
                            </div>
                        </div>

                        <!-- <div class="col-6" id="status_text" style="display: <?php echo $status_text ?>;">
                            <div class="form-group">
                                <div class="box-img">
                                    <div class="container">
                                        <div class="text">
                                            <span><?php echo $this->lang->line("whatsapp_status_type_text") ?></span>
                                        </div>
                                        <div class="img">
                                            <img src="<?php echo base_url("assets/img/statusText.png"); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                    </div>

                    <div class="row" id="status_data" style="display: <?php echo $status_data ?>;">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input-data"><?php echo $this->lang->line("whatsapp_status_message") ?> <span style="color: red" id="count_caracter">700</span></label>
                                <textarea class="form-control" placeholder="Digite aqui..." name="input_data" id="input-data" rows="7" resize="none" maxlength="700"><?php echo $input_data != "" ? $input_data : ""; ?></textarea>
                                <?php echo form_error('input_data', '<div class="alert-status">', '</div>'); ?>
                                <span class="alert-field-validation" id="alert_textarea_message" style="display:none"><?php echo $this->lang->line("whatsapp_status_alert_field_validation")  ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-file">
                        <input type="file" id="fileElem" multiple accept="image/*,.mp4" style="display:none" onchange="handleFiles(this.files)">
                    </div>

                    <div class="row">
                        <div class="col-lg-12" id="input_files" style="display:<?php echo $input_files; ?>">
                            <a href="#" id="fileSelect">
                                <div class="input-container" id="">
                                    <div class="header">
                                        <div class="title">
                                            <span><?php echo $this->lang->line("whatsapp_status_loading_arquive") ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="drop-status form-group" id="dropStatus" style="display:<?php echo $dropStatus ?>" multiple ondrop="dropHandler(event);" ondragover="dragOverHandler(event);">
                                <div class="drop-inner-img">
                                    <img class="drop-icon" src="<?php echo base_url("assets/img/panel/image.png") ?>" alt="">
                                </div>
                                <div class="drop-inner-title">
                                    <b><?php echo $this->lang->line("whatsapp_status_title_drop") ?></b>
                                </div>
                                <div class="drop-inner-text">
                                    <span><?php echo $this->lang->line("whatsapp_status_subtitle_drop") ?></span>
                                </div>
                            </div>
                            <?php echo form_error('file0', '<div class="alert-status">', '</div>'); ?>
                            <span class="alert-field-validation" id="alert_upload_media" style="display:none; margin-top: -21px;"><?php echo $this->lang->line("whatsapp_status_alert_field_validation")  ?></span>
                        </div>
                    </div>

                    <?php

                    if (isset($file)) {

                        foreach ($file as $key => $val) { ?>
                            <div class="retrieve">
                                <input type="hidden" class="retrieve_file" value="<?php echo $file[$key]; ?>">
                                <input type="hidden" class="retrieve_text" value="<?php echo $text[$key]; ?>">
                                <input type="hidden" class="retrieve_thumb" value="<?php echo isset($thumb[$key]) ? $thumb[$key] : '' ?>">
                                <input type="hidden" class="media_type" value="<?php echo $media_type[$key] ?>">
                                <input type="hidden" class="media_size" value="<?php echo $media_size[$key] ?>">
                            </div>
                    <?php }
                    } ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>publication/whatsapp/status"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("whatsapp_status_btn_cancel") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("whatsapp_status_btn_save") ?></button>
                        </div>
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
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>