<input type="hidden" name="verify-add" id="verify-add" value="1">
<?php echo form_open('broadcast/sms/save'); ?>
<div class="container mt--5">
    <div class="row">
        <div class="col-xl-4 order-xl-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="h3 mb-0"><?php echo $this->lang->line("sms_broadcast_date_title") ?></h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush list my--3">
                        <li class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label" for="exampleDatepicker"><?php echo $this->lang->line("sms_broadcast_date") ?></label>
                                        <input class="form-control datepicker" id="date_start" name="date_start" type="text" value="<?php echo date('d/m/Y'); ?>" style="text-align: center!important;">
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: -20px; margin-bottom: 20px;">
                                <div class="col">
                                    <div class="alert-field-validation" id="alert_date_start"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label" for="exampleFormControlSelect1"><?php echo $this->lang->line("sms_broadcast_hour") ?></label>
                                        <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                            <input type="text" id="time_start" name="time_start" class="form-control" value="<?php echo date('H:i'); ?>" style="text-align: center!important;">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: -20px; margin-bottom: 10px;">
                                <div class="col">
                                    <div class="alert-field-validation" id="alert_time_start"></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("sms_broadcast_new") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display: none">
                        <input type="hidden" id="groups" name="groups" value="<?php echo isset($data['groups']) ? $data['groups'] : ""; ?>" data-toggle="tags" />
                    </div>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo $this->lang->line("sms_broadcast_title") ?></label>
                                    <input type="text" class="form-control" id="input-title" name="input-title" placeholder="<?php echo $this->lang->line("sms_broadcast_title_placeholder") ?>" autocomplete="off" value="<?php echo isset($data['input-title']) ? $data['input-title'] : ""; ?>">
                                    <div class="alert-field-validation" id="alert_input_title"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-control-label" for="group_id"><?php echo $this->lang->line("sms_broadcast_contacts") ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <?php
                                        $bActive = false;
                                        $groups = explode(",", isset($data['groups']) ? $data['groups'] : "");
                                        foreach ($groups as $group) {
                                            if ($group == "0") {
                                                $bActive = true;
                                            }
                                        }
                                        ?>
                                        <input class="custom-control-input group" data-id="0" id="Group0" type="checkbox" <?php echo $bActive == true ? "checked" : "" ?>>
                                        <label class="custom-control-label" for="Group0"><?php echo $this->lang->line("sms_broadcast_contacts_option_all") ?></label>
                                    </div>
                                </div>
                            </div>
                            <?php foreach ($Groups as $row) { ?>
                                <div class="col-5">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox mb-3">
                                            <?php
                                            $bActive = false;
                                            foreach ($groups as $group) {
                                                if ($group == $row['id_group_contact']) {
                                                    $bActive = true;
                                                }
                                            } ?>
                                            <input class="custom-control-input group" <?php echo $bActive == true ? "checked" : "" ?> data-id="<?php echo $row['id_group_contact']; ?>" id="Group<?php echo $row['id_group_contact']; ?>" type="checkbox">
                                            <label class="custom-control-label" for="Group<?php echo $row['id_group_contact']; ?>"><?php echo $row['name']; ?></label>
                                        </div>
                                    </div>
                                </div>
                            <?php  } ?>

                        </div>
                        <div class="alert-field-validation" id="alert_input_checkboxes"></div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-control-label" for="data"><?php echo $this->lang->line("sms_broadcast_message") ?></label>
                                    <textarea class="form-control" id="input-data" name="input-data" rows="3" maxlength="140" resize="none" placeholder="<?php echo $this->lang->line("sms_broadcast_message_placeholder") ?>"><?php echo isset($data['input-data']) ? $data['input-data'] : ""; ?></textarea>
                                    <div class="alert-field-validation" id="alert_input_data"></div>
                                </div>
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
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>