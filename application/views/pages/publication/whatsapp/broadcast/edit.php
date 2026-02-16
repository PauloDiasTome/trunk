<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("whatsapp_broadcast_edit_title") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart('publication/whatsapp/broadcast/save/' . $data['token']); ?>
                <div class="card-body">
                    <div style="display: none">
                        <input type="hidden" name="edit" id="edit" value="<?php echo $data["edit"] ?>">
                        <input type="hidden" id="groups" name="groups" value="<?php echo isset($data['groups']) ? $data['groups'] : ""; ?>" data-toggle="tags" />
                        <input type="hidden" name="type_broadcast" id="type_broadcast" value="<?php echo $type_broadcast; ?>">
                        <input type="hidden" name="id_channel" id="id_channel" value="<?php echo $data['id_channel'] ?>">
                        <input type="hidden" name="is_limited_time" id="is_limited_time" value="<?php echo $data['is_limited_time'] ?>">
                        <input type="hidden" name="direction" id="direction" value="1">
                        <input type="hidden" name="last_date" id="last_date" value="<?php echo $data['schedule'] ?>">
                        <input type="hidden" name="old_expire" id="old_expire" value="<?php echo $data['expire'] ?>">
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_title"><?php echo $this->lang->line("whatsapp_broadcast_title") ?></label>
                                <input type="text" class="form-control" name="input_title" id="input_title" maxlength="100" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_title") ?>" value="<?php echo $data['broadcast_title'] != "" ? $data['broadcast_title'] : ""; ?>" <?php echo $data['edit'] == 'expire' ? 'disabled' : ''; ?>>
                                <?php echo form_error('input_title', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_input_title" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_alert_field_validation") ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start"><?php echo $this->lang->line("whatsapp_broadcast_date_scheduling") ?></label>
                                <input type="text" class="form-control datepicker date_start" maxlength="10" name="date_start" id="date_start" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_date_scheduling_placeholder") ?>" value="<?php echo $data['schedule'] != "" ? explode(" ", $data['schedule'])[0] : date('d/m/Y'); ?>" <?php echo $data['edit'] == 'expire' ? 'disabled' : ''; ?>>
                                <?php echo form_error('date_start', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_date_start" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_check_date") ?></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start"><?php echo $this->lang->line("whatsapp_broadcast_hour_scheduling") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start" id="time_start" maxlength="5" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_hour_scheduling_placeholder") ?>" value="<?php echo $data['schedule'] != "" ? explode(" ", $data['schedule'])[1] : date('H:i') ?>" <?php echo $data['edit'] == 'expire' ? 'disabled' : ''; ?>>
                                    <input type="hidden" class="form-control" id="timeStart" value="<?php echo isset($time_start); ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                                <?php echo form_error('time_start', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_time_start" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_alert_input_required") ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="channel_id"><?php echo $this->lang->line("whatsapp_broadcast_edit_selected_channel") ?></label><br>
                                <div id="myselect" style="display: none">
                                    <select id="multiselect" class="form-control" name="others[]" multiple="multiple" style="display:none">
                                        <option selected class="form-control" value="<?php echo $data['id_channel'] ?>"><?php echo $data['channel_name'] ?> </option>
                                    </select>
                                </div>
                                <select disabled class="form-control">
                                    <option selected class="form-control" value="<?php echo $data['id_channel'] ?>"><?php echo $data['channel_name'] ?></option>
                                </select>
                                <?php echo form_error('others[]', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_multi_selects" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_alert_field_validation") ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="<?php if ($data['persona_id'] != '0') {echo 'display:none';} ?>">
                        <div class="col-12">
                            <label class="custom-toggle mb-2">
                                <input type="checkbox" id="toggle-segmented" name="toggle-segmented" disabled <?php if ($data['persona_id'] != '0') {echo 'checked';} ?>>
                                <span class="custom-toggle-slider rounded-circle custom-toggle-slider-segmented" data-label-off="<?php echo $this->lang->line("whatsapp_broadcast_segments_no") ?>" data-label-on="<?php echo $this->lang->line("whatsapp_broadcast_segments_yes") ?>"></span>
                            </label>
                            <label class="form-control-label title-toggle-view" for=""><?php if (!$data['persona_id'] != '0') {echo $this->lang->line("whatsapp_broadcast_unsegmented_campaign");}?></label>
                        </div>
                    </div>

                    <div class="row" id="segmented_group" style="<?php echo $data['persona_id'] != '0' ? 'display: flex' : 'display:none' ?>">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="select_segmented_group"><?php echo $this->lang->line("whatsapp_broadcast_edit_selected_group") ?></label><br>
                                <div id="myselect" style="display: none">
                                    <select class="form-control" name="select_segmented_group" id="select_segmented_group">
                                        <option selected class="form-control" value="<?php echo $data['persona_id'] ?>"></option>
                                    </select>
                                </div>
                                <select disabled class="form-control">
                                    <option selected class="form-control" value="<?php echo $data['persona_id'] ?>">
                                        <?php if ($data['persona_id'] != '0') {echo $data['persona_name'];} ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label class="custom-toggle mb-2">
                                <input type="checkbox" id="toggle_validity" name="toggle_validity" <?php if (!empty($data['expire'])) { echo 'checked'; } ?>>
                                <span class="custom-toggle-slider rounded-circle" data-label-off="<?php echo $this->lang->line("whatsapp_broadcast_automatic_response_no") ?>" data-label-on="<?php echo $this->lang->line("whatsapp_broadcast_automatic_response_yes") ?>"></span>
                            </label>
                            <label class="form-control-label title-toggle-view" for=""><?php echo $this->lang->line("whatsapp_broadcast_automatic_response") ?></label>
                        </div>
                    </div>

                    <div class="row" id="date-validity" style="<?php echo !empty($data['expire']) ? 'display: flex' : 'display: none' ?>">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start_validity"><?php echo $this->lang->line("whatsapp_broadcast_time_start_validity") ?></label>
                                <input type="text" class="form-control datepicker" name="date_start_validity" id="date_start_validity" maxlength="10" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_date_scheduling_placeholder") ?>" value="<?php echo $data['expire'] != "" ? explode(" ", $data['expire'])[0] : ""; ?>">
                                <?php echo form_error('date_start_validity', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_date_start_validity" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_check_date_validity") ?></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start_validity"><?php echo $this->lang->line("whatsapp_broadcast_hour_start_validity") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start_validity" id="time_start_validity" maxlength="5" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_hour_scheduling_placeholder") ?>" value="<?php echo $data['expire'] != "" ? explode(" ", $data['expire'])[1] : ""; ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                                <?php echo form_error('time_start_validity', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_time_start_validity" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_check_hour_validity") ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if ($data['media_type'] == 1 || $data['media_type'] == null) { ?>
                        <div class="row" id="status_data">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-data"><?php echo $this->lang->line("whatsapp_broadcast_message") ?> <span style="color: red" id="count_caracter">1024</span></label>
                                    <textarea class="form-control" name="input-data" id="input-data" rows="7" resize="none" maxlength="1024" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_newsletter_status_message_placeholder") ?>" <?php echo $data['edit'] == 'expire' ? 'disabled' : ''; ?>><?php echo $data['data'] != "" ? $data['data'] : ""; ?></textarea>
                                    <?php echo form_error('input-data', '<div class="alert-status">', '</div>'); ?>
                                    <span class="alert-field-validation" id="alert_textarea_message" style="display:none"><?php echo $this->lang->line("whatsapp_broadcast_alert_input_required") ?></span>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" id="media_campaign" value="<?php echo $data['data'] ?>" media_url="<?php echo $data['media_url'] ?>" media_type="<?php echo $data['media_type'] ?>">
                        <div class="row">
                            <div class="col-12">
                                <div class="drop-broadcast form-group" id="dropBrodcast"></div>
                                <?php echo form_error('file0', '<div class="alert-status">', '</div>'); ?>
                                <span class="alert-field-validation" id="alert_upload_media" style="display:none"><?php echo $this->lang->line("whatsapp_broadcast_alert_input_required") ?></span>
                            </div>
                        </div>
                    <?php } ?>

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

                    <div class="row">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("whatsapp_broadcast_btn_return") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("whatsapp_broadcast_btn_save") ?></button>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('/assets/lib/build_pdf/build_pdf.js') ?>"></script>