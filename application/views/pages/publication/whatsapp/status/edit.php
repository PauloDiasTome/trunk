<input type="hidden" id="edit" value="<?php echo $data["edit"] ?>">
<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("whatsapp_status_edit_title") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart('publication/whatsapp/status/save/' . $data['token']); ?>
                <div class="card-body">
                    <div style="display: none">
                        <input type="hidden" id="groups" name="groups" value="<?php echo isset($data['groups']) ? $data['groups'] : ""; ?>" data-toggle="tags" />
                        <input type="hidden" name="type_status" id="type_status" value="<?php echo $type_status; ?>">
                        <input type="hidden" name="id_channel" id="id_channel" value="<?php echo $data['id_channel'] ?>">
                        <input type="hidden" name="is_limited_time" id="is_limited_time" value="<?php echo $data['is_limited_time'] ?>">
                        <input type="hidden" name="direction" id="direction" value="1">
                        <input type="hidden" name="last_date" id="last_date" value="<?php echo $data['schedule'] ?>">
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_title"><?php echo $this->lang->line("whatsapp_status_title") ?></label>
                                <input type="text" class="form-control" name="input_title" id="input_title" maxlength="100" placeholder="<?php echo $this->lang->line("whatsapp_status_title") ?>" value="<?php echo $data['broadcast_title'] != "" ? $data['broadcast_title'] : "";  ?>">
                                <?php echo form_error('input_title', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_input_title" style="display: none;"><?php echo $this->lang->line("whatsapp_status_alert_field_validation") ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start"><?php echo $this->lang->line("whatsapp_status_date_scheduling") ?></label>
                                <input type="text" class="form-control datepicker" name="date_start" id="date_start" maxlength="10" placeholder="<?php echo $this->lang->line("whatsapp_status_date_scheduling_placeholder") ?>" value="<?php echo $data['schedule'] != "" ? explode(" ", $data['schedule'])[0] : date('d/m/Y'); ?>">
                                <?php echo form_error('date_start', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_date_start" style="display: none;"><?php echo $this->lang->line("whatsapp_status_check_date") ?></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start"><?php echo $this->lang->line("whatsapp_status_hour_scheduling") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start" id="time_start" maxlength="5" placeholder="<?php echo $this->lang->line("whatsapp_status_hour_scheduling_placeholder") ?>" value="<?php echo $data['schedule'] != "" ? explode(" ", $data['schedule'])[1] : date('H:i') ?>">
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
                                <label class="form-control-label" for="channel_id"><?php echo $this->lang->line("whatsapp_status_edit_selected_channel") ?></label><br>
                                <div id="myselect" style="display: none">
                                    <select id="multiselect" class="form-control" name="others[]" multiple="multiple" style="display:none">
                                        <option selected class="form-control" value="<?php echo $data['id_channel'] ?>"><?php echo $data['channel_name'] ?> </option>
                                    </select>
                                </div>
                                <select disabled class="form-control">
                                    <option selected class="form-control" value="<?php echo $data['id_channel'] ?>"><?php echo $data['channel_name'] ?></option>
                                </select>
                                <?php echo form_error('others[]', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_multi_selects" style="display: none;"><?php echo $this->lang->line("whatsapp_status_alert_field_validation") ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if ($data['media_type'] == 1 || $data['media_type'] == null) { ?>
                        <div class="row" id="status_data">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-data"><?php echo $this->lang->line("whatsapp_status_message") ?> <span style="color: red" id="count_caracter">700</span></label>
                                    <textarea class="form-control" name="input_data" id="input-data" rows="7" resize="none" maxlength="700" placeholder="<?php echo $this->lang->line("whatsapp_status_message_placeholder") ?>"><?php echo $data['data'] != "" ? $data['data'] : ""; ?></textarea>
                                    <?php echo form_error('input_data', '<div class="alert-status">', '</div>'); ?>
                                    <span class="alert-field-validation" id="alert_textarea_message" style="display:none"><?php echo $this->lang->line("whatsapp_status_alert_field_validation")  ?></span>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" id="media_campaign" value="<?php echo $data['data'] ?>" media_url="<?php echo $data['media_url'] ?>" media_type="<?php echo $data['media_type'] ?>">
                        <div class="row">
                            <div class="col-12">
                                <div class="drop-status form-group" id="dropStatus"></div>
                                <?php echo form_error('file0', '<div class="alert-status">', '</div>'); ?>
                                <span class="alert-field-validation" id="alert_upload_media" style="display:none"><?php echo $this->lang->line("whatsapp_status_alert_field_validation")  ?></span>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>publication/whatsapp/status"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("whatsapp_status_btn_return") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("whatsapp_status_btn_save") ?></button>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>
