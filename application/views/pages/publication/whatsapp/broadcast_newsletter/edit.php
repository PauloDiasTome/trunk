<input type="hidden" id="edit" value="<?php echo $data["edit"] ?>">
<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_edit_title") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open('publication/whatsapp/broadcast/newsletter/save/' . $data['token']); ?>
                <div class="card-body">
                    <input type="hidden" id="type_broadcast" name="type_broadcast" value="<?php echo $data["type_broadcast"]; ?>">
                    <input type="hidden" name="id_channel" id="id_channel" value="<?php echo $data['id_channel'] ?>">
                    <input type="hidden" name="last_date" id="last_date" value="<?php echo $data['schedule'] ?>">
                    <input type="hidden" name="is_limited_time" id="is_limited_time" value="<?php echo $data['is_limited_time'] ?>">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_title"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_title") ?></label>
                                <input type="text" class="form-control" name="input_title" id="input_title" maxlength="100" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_newsletter_title") ?>" value="<?php echo $data['broadcast_title'] != "" ? $data['broadcast_title'] : "";  ?>">
                                <div class="alert-field-validation" id="alert__input_title" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_date_scheduling") ?></label>
                                <input type="text" class="form-control datepicker date_start" maxlength="10" name="date_start" id="date_start" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_newsletter_date_scheduling_placeholder") ?>" value="<?php echo $data['schedule'] != "" ? explode(" ", $data['schedule'])[0] : date('d/m/Y'); ?>">
                                <div class="alert-field-validation" id="alert_date_start" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_check_date") ?></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_hour_scheduling") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start" id="time_start" maxlength="5" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_newsletter_hour_scheduling_placeholder") ?>" value="<?php echo explode(" ", $data['schedule'])[1]; ?>">
                                    <input type="hidden" class="form-control" id="timeStart" value="<?php echo isset($time_start); ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                                <?php echo form_error('time_start', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_time_start" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_alert_input_required") ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="channel_id"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_selected_channel") ?></label><br>
                                <div id="myselect" style="display: none">
                                    <select id="multiselect" class="form-control" name="selected_channel" multiple="multiple" style="display:none">
                                        <option selected class="form-control" value="<?php echo $data['id_channel'] ?>"><?php echo $data['channel_name'] ?> </option>
                                    </select>
                                </div>
                                <select disabled class="form-control">
                                    <option selected class="form-control" value="<?php echo $data['id_channel'] ?>"><?php echo $data['channel_name'] ?></option>
                                </select>
                                <div class="alert-field-validation" id="alert_multi_selects" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_alert_input_required") ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_selected_newsletter_edit_view") ?></label><br>
                            <div id="myselect" style="display: none">
                                <select id="multiselect" class="form-control" name="select_segmented_newsletter[]" style="display: none">
                                    <option selected class="form-control" value="<?php echo $data['id_newsletter'] ?>"><?php echo $data['newsletter_name'] ?></option>
                                </select>
                            </div>
                            <select disabled class="form-control">
                                <option selected class="form-control" value="<?php echo $data['id_newsletter'] ?>"><?php echo $data['newsletter_name'] ?></option>
                            </select>
                            <div class="alert-field-validation" id="alert_multi_selects" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_alert_input_required") ?></div>
                        </div>
                    </div>

                    <?php if ($data["type_broadcast"] == "text") { ?>
                        <div class="row">
                            <div class="col-12 form-group">
                                <label class="form-control-label" for="input-data"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_message") ?> <span style="color: red" id="count_caracter">1024</span></label>
                                <textarea class="form-control" name="input-data" id="input-data" rows="7" resize="none" maxlength="1024" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_newsletter_status_message_placeholder") ?>"><?php echo $data['data'] != "" ? $data['data'] : ""; ?></textarea>
                                <div class="alert-field-validation" id="alert__input-data" style="display: none;"></div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($data["type_broadcast"] == "media") { ?>
                        <input type="hidden" id="media_campaign" value="<?php echo $data['data'] ?>" media_url="<?php echo $data['media_url'] ?>" media_type="<?php echo $data['media_type'] ?>">
                        <div class="row">
                            <div class="col-12 form-group">
                                <div class="drop-broadcast" id="dropBrodcast"></div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($data["type_broadcast"] == "poll") { ?>
                        <input type="hidden" id="json_poll" value="<?php echo htmlspecialchars($data['data']) ?>">
                        <div class="row" id="type_message_poll" style="display: flex;">
                            <div class="col-lg-6 form-group">
                                <div class="list"></div>
                                <span class="alert-field-validation" id="alert_message_poll" style="display:none"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_validation_poll")  ?></span>
                            </div>
                            <div class="col-lg-6 form-group">
                                <div class="main-preview">
                                    <div class="real-time-preview"></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast/newsletter"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_btn_return") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_btn_save") ?></button>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const typeBroadcast = document.getElementById("type_broadcast")?.value;
        if (typeBroadcast === "poll") {
            window.location.href = "<?php echo base_url('publication/whatsapp/broadcast/newsletter'); ?>";
        }
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.1/Sortable.min.js"></script>