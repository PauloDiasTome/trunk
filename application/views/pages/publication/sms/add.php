<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("sms_broadcast_add_title") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart('broadcast/sms/save', 'id="myform"'); ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_title"><?php echo $this->lang->line("sms_broadcast_title") ?></label>
                                <input type="text" class="form-control" name="input_title" id="input_title" maxlength="100" placeholder="<?php echo $this->lang->line("sms_broadcast_title") ?>">
                                <div class="alert-field-validation" id="alert__input_title" style="display: none;"><?php echo $this->lang->line("sms_broadcast_alert_field_validation") ?></div>
                                <?php echo form_error('input_title', '<div class="alert-title">', '</div>'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start"><?php echo $this->lang->line("sms_broadcast_date_scheduling") ?></label>
                                <input type="text" class="form-control datepicker date_start" maxlength="10" name="date_start" id="date_start" placeholder="<?php echo $this->lang->line("sms_broadcast_date_scheduling_placeholder") ?>" value="<?php echo $date_start ?>">
                                <?php echo form_error('date_start', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert__date_start" style="display: none;"><?php echo $this->lang->line("sms_broadcast_check_date") ?></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start"><?php echo $this->lang->line("sms_broadcast_hour_scheduling") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start" id="time_start" maxlength="5" value="<?php echo $time_start ?>">
                                    <input type="hidden" class="form-control" id="timeStart" value="<?php echo isset($time_start); ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                                <div class="alert-field-validation" id="alert__time_start" style="display: none;"><?php echo $this->lang->line("sms_broadcast_check_date") ?></div>
                                <?php echo form_error('time_start', '<div class="alert-status">', '</div>'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="id_channel" name="id_channel">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="select_segmented_group"><?php echo $this->lang->line("sms_broadcast_select_channel") ?></label>
                                <select class="form-control form-select" name="select_channel" id="select_channel">
                                    <?php foreach ($channel as $channels) : ?>
                                        <option value="<?php echo $channels['id_channel'] ?>"><?php echo $channels['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <?php echo form_error('others[]', '<div class="alert-status">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert__select_channel" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_alert_field_validation") ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="segmented_group" name="segmented_group">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="select_segmented_group"><?php echo $this->lang->line("sms_broadcast_segments_select_group") ?></label>
                                <select class="form-control form-select" name="select_segmented_group" id="select_segmented_group">
                                    <option value="0"><?php echo $this->lang->line("sms_broadcast_segments_select_group_placeholder"); ?></option>
                                    <?php foreach ($groups as $row) : ?>
                                        <option value="<?php echo $row['id_group_contact'] ?>"><?php echo $row['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="status_data">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_data"><?php echo $this->lang->line("sms_broadcast_message") ?> <span style="color: red" id="count_caracter">140</span></label>
                                <textarea class="form-control" name="input_data" id="input_data" rows="7" resize="none" placeholder="<?php echo $this->lang->line("sms_broadcast_status_message_placeholder") ?>"></textarea>
                                <div class="alert-field-validation" id="alert__input_data" style="display: none;"><?php echo $this->lang->line("sms_broadcast_alert_field_validation") ?></div>
                                <?php echo form_error('input_data', '<div class="alert-status">', '</div>'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>broadcast/sms"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("sms_broadcast_btn_cancel") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("sms_broadcast_btn_save") ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>