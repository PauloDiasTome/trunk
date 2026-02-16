<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("config_edit_title"); ?></h3>
                        </div>
                    </div>
                </div>

                <div class="card-body edit-facebook_api">

                    <?php

                    $hidden = array(
                        'id_config' => $data[0]['id_config'],
                        'id_channel' => $data[0]['id_channel'],
                        'type' => $data[0]['type'],
                        'is_broadcast' => $data[0]['is_broadcast']
                    );
                    echo form_open_multipart("config/save", array('id' => 'config'), $hidden);

                    ?>

                    <input type="hidden" name="channel_id" value="<?php echo $data[0]['type'] ?>">
                    <div class="row">
                        <div class="form-group col-12">
                            <h6 class="heading-small text-muted mt-2"><?php echo $this->lang->line("config_profile_info") ?></h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="box-picture-profile-config">
                                <div class="picture-profile">
                                    <img src="<?php echo $data[0]['picture'] ?>" alt="">
                                </div>

                                <?php if ($data[0]["type"] == 8) { ?>
                                    <div class="picture-face">
                                        <img src="<?php echo base_url("assets/img/panel/" . "facebook2.png") ?>" alt="" style="width: 100%; border-radius: 20px; border:none">
                                    </div>
                                <?php } ?>

                                <?php if ($data[0]["type"] == 9) { ?>
                                    <div class="picture-insta">
                                        <img src="https://app.talkall.com.br/assets/img/instagram_integration.png" alt="" style="width: 100%; border-radius: 20px; border:none">
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group col-5">
                            <label class="form-control-label" for="input-commercial-name"><?php echo $this->lang->line("config_commercial_name") ?></label>
                            <input type="text" class="form-control" id="input-comercial-name" value="<?php echo $data[0]['name']; ?>" disabled>
                        </div>

                        <div class="form-group col-4">
                            <label class="form-control-label" for="channel_number"><?php echo $this->lang->line("config_talkall_id") ?></label>
                            <input type="text" class="form-control" id="channel_number" value="<?php echo $data[0]['id']; ?>" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-9">
                            <div class="form-group">
                                <label class="form-control-label" for="id_work_time"><?php echo $this->lang->line("config_time_zone"); ?></label>
                                <?php $this->load->helper('date');
                                echo timezone_menu(mysql_timezones($data[0]['timezone']), 'form-control', 'timezone', 'disabled'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="id_user_group"><?php echo $this->lang->line("config_default_sector"); ?></label>
                                <select class="form-control" name="id_user_group" id="id_user_group">
                                    <?php foreach ($data['user_group'] as $row) { ?>
                                        <option value="<?php echo $row['id_user_group'] ?>" <?php echo $data[0]['id_user_group'] == $row['id_user_group'] ? "selected" : ""  ?>>
                                            <?php echo $row['name'] ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="to_recover_id_user_group"></label>
                                <input type="hidden" name="to_recover_id_user_group" id="to_recover_id_user_group" value="<?php echo $data[0]['id_user_group']; ?>">

                                <label class="form-control-label" for="id_work_time"><?php echo lang('work_time'); ?></label>
                                <select class="form-control" id="id_work_time" name="id_work_time">
                                    <?php foreach ($data['work_time'] as $row) { ?>
                                        <option value="<?php echo $row['id_work_time']; ?>" <?php echo $row['id_work_time'] == $data[0]['id_work_time'] ? "Selected" : "" ?>><?php echo $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-12">
                            <label class="form-control-label" for="welcome_message"><?php echo $this->lang->line("config_welcome_message") ?></label> <span class="form-control-label count-character-field"></span>
                            <textarea class="form-control" name='welcome_message' id='welcome_message' style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_welcome_message_placeholder") ?>" maxlength="1024"><?php echo $data[0]['welcome'] ?? "" ?></textarea>
                        </div>

                        <div class="form-group col-12">
                            <label class="form-control-label" for="office_hours_end"><?php echo $this->lang->line("config_text_in_outside_office_hours") ?></label> <span class="form-control-label count-character-field"></span>
                            <textarea class="form-control" name='office_hours_end' id="office_hours_end" style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_text_in_outside_office_hours_placeholder") ?> <?php echo lang('work_time'); ?>" maxlength="1024"><?php echo $data[0]['office_hours_end'] ?? "" ?></textarea>
                        </div>

                        <div class="form-group col-12">
                            <label class="form-control-label" for="message_start_attendance"><?php echo $this->lang->line("config_text_in_service_start") ?></label> <span class="form-control-label count-character-field"></span>
                            <textarea class="form-control" name='message_start_attendance' id="message_start_attendance" style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_text_in_service_start_placeholder") ?>" maxlength="1024"><?php echo $data[0]['message_start_attendance'] ?? "" ?></textarea>
                        </div>

                        <div class="form-group col-12">
                            <label class="form-control-label" for="transfer_message"><?php echo $this->lang->line("config_service_transfer_text") ?></label> <span class="form-control-label count-character-field"></span>
                            <textarea class="form-control" name='transfer_message' id="transfer_message" style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_service_trasnfer_text_placeholder") ?>" maxlength="1024"><?php echo $data[0]['transfer_message'] ?? "" ?></textarea>
                        </div>
                        <div class="form-group col-12">
                            <label class="form-control-label" for="automatic_transfer_minute"><?php echo $this->lang->line("config_service_automatic_transfer_minute") ?></label>
                            <input type="number" class="form-control" name='automatic_transfer_minute' id="automatic_transfer_minute" style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_service_automatic_transfer_minute_placeholder") ?>" value="<?php echo $data[0]['automatic_transfer_minute'] ?? "" ?>" min="1">
                            <?php echo form_error('automatic_transfer_minute', '<div class="alert-field-validation">', '</div>'); ?>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12">
                            <?php $channel_type = ['']; ?>
                            <?php if (in_array($data[0]['type'], $channel_type)) { ?>
                                <div class="form-group " id="attendance">
                                    <input class="form-control" type="checkbox" id="checkbox-on-off-attendance" name="checkbox-on-off-attendance" value="<?= $data[0]['attendance_enable'] ?>" />
                                    <label style="margin-left: 20px;" class="form-control-label" for="attendance"><?php echo $this->lang->line("config_enable_service") ?></label>
                                </div>
                            <?php } ?>

                            <?php $channel_type = ['8', '9']; ?>
                            <?php if (in_array($data[0]['type'], $channel_type)) { ?>
                                <div class="form-group " id="protocol">
                                    <input class="form-control" type="checkbox" id="checkbox-on-off-protocol" name="checkbox-on-off-protocol" value="<?= $data[0]['enable_protocol'] ?>" />
                                    <label style="margin-left: 20px;" class="form-control-label" for="protocol"><?php echo $this->lang->line("config_enable_protocol"); ?></label>
                                </div>
                            <?php } ?>

                            <?php $channel_type = ['8', '9']; ?>
                            <?php if (in_array($data[0]['type'], $channel_type)) { ?>
                                <div class="form-group" id="chatbot">
                                    <input class="form-control" type="checkbox" id="checkbox-on-off-chatbot" name="checkbox-on-off-chatbot" value="<?= $data[0]['chatbot_enable'] ?>" />
                                    <label style="margin-left: 20px;" class="form-control-label" for="checkbox-on-off-chatbot"><?php echo $this->lang->line("config_enable_chatbot"); ?></label>
                                </div>
                            <?php } ?>

                            <?php $channel_type = ['8', '9']; ?>
                            <?php if (in_array($data[0]['type'], $channel_type)) { ?>
                                <div class="form-group " id="automatic_transfer">
                                    <input class="form-control" type="checkbox" id="checkbox-on-off-automaticTransfer" name="checkbox-on-off-automaticTransfer" value="<?php echo $data[0]['automatic_transfer'] ?>" />
                                    <label style="margin-left: 20px;" class="form-control-label" for="checkbox-on-off-automaticTransfer"><?php echo $this->lang->line("config_enable_automatic_transfer");  ?></label>
                                </div>
                            <?php } ?>

                            <div class="form-group" id="attendant_name_enable">
                                <input type="checkbox" class="form-control slider-button" id="checkbox-on-off-attendantName" name="checkbox-on-off-attendantName" value="<?php echo $data[0]['attendant_name_enable'] ?>" <?php echo $data[0]['attendant_name_enable'] == 1 ? 'checked' : '' ?>>
                                <label style="margin-left: 20px;" class="form-control-label"><?php echo $this->lang->line("config_enable_attendant_name") ?></label>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("config_enable_attendant_name_tooltip") ?>"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a href="<?php echo base_url(); ?>integration"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("config_btn_return") ?></button></a>
                            <input type="submit" value="<?php echo $this->lang->line("config_btn_save") ?>" class="btn btn-success">
                        </div>
                    </div>

                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>