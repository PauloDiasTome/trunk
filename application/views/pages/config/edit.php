<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h3 class="mb-0"><?php echo $this->lang->line("config_edit_title"); ?></h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <?php
                    $hidden = array(
                        'id_config' => $data[0]['id_config'],
                        'id_channel' => $data[0]['id_channel'],
                        'type' => $data[0]['type'],
                        'is_broadcast' => $data[0]['is_broadcast'],
                        'channel_number' => $data[0]['id'],
                    );
                    echo form_open_multipart("config/save", array('id' => 'config'), $hidden);
                    ?>

                    <input type="hidden" name="channel_id" value="<?php echo $data[0]['type'] ?>">
                    <input type="hidden" name="url_picture" id="url_picture" value="NULL" />

                    <?php $channel_type = ['12', '16']; ?>

                    <?php if (in_array($data[0]['type'], $channel_type)) { ?>

                        <div class="row">
                            <div class="form-group col-12">
                                <h6 class="heading-small text-muted mt-2"><?php echo $this->lang->line("config_profile_info") ?></h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">

                                <div class="box-picture-profile-config">
                                    <div class="picture-profile transition-effect">
                                        <i class="fas fa-camera icon-add-photo" id="addProfile"></i>
                                        <span class="picture-profile-title"><?php echo $this->lang->line("config_profile_photo"); ?></span>

                                        <img src="<?php echo $data[0]['picture'] ==  "" ? base_url("assets/img/avatar.svg") : $data[0]['picture']  ?>" alt="">
                                        <input type="file" name="file" class="form-control" id="inputFile" accept="image/png, image/gif, image/jpeg, image/jpg, image/jfif" style="display: none;" />
                                    </div>
                                </div>

                            </div>

                            <div class="form-group col-4">
                                <label class="form-control-label" for="input-commercial-name"><?php echo $this->lang->line("config_commercial_name") ?></label>
                                <input type="text" class="form-control" id="input-comercial-name" name="input-commercial-name" value="<?php echo $data[0]['name']; ?>" disabled>
                            </div>

                            <div class="form-group col-5" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                <label class="form-control-label" for="input-email"><?php echo $this->lang->line("config_email") ?></label>
                                <input type="email" class="form-control" id="input-email" name="input-email" placeholder="<?php echo $this->lang->line("config_email_placeholder") ?>" value="<?php echo $data[0]['email']; ?>">
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-3"></div>

                            <div class="form-group col-9" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                <label class="form-control-label" for="input-address"><?php echo $this->lang->line("config_address") ?></label>
                                <input type="text" class="form-control" id="input-address" name="input-address" placeholder="<?php echo $this->lang->line("config_address_placeholder") ?>" value="<?php echo $data[0]['address']; ?>">
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-6" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                <label class="form-control-label" for="input-website-social"><?php echo $this->lang->line("config_website_social") ?></label>
                                <input type="text" class="form-control" id="input-website-social" name="input-website-social" placeholder="<?php echo $this->lang->line("config_website_social_placeholder") ?>" value="<?php echo isset($data["social_media01"]) ? $data["social_media01"] : "" ?>">
                            </div>

                            <div class="form-group col-6" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                <label class="form-control-label" for="input-website-social-second"><?php echo $this->lang->line("config_website_social") ?></label>
                                <input type="text" class="form-control" id="input-website-social-second" name="input-website-social-second" placeholder="<?php echo $this->lang->line("config_website_social_placeholder") ?>" value="<?php echo isset($data["social_media02"]) ? $data["social_media02"] : "" ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="textarea-description"><?php echo $this->lang->line("config_description") ?></label> <span class="form-control-label count-character-field">512</span>
                                    <textarea class="form-control" id="textarea-description" name="textarea-description" rows="4" resize="none" maxlength="512"><?php echo $data[0]["company_description"]; ?></textarea>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <h6 class="heading-small text-muted"><?php echo $this->lang->line("config_service_info") ?></h6>
                            </div>
                        </div>
                    </div>

                    <?php if ($data[0]['type'] == 2 && $data[0]['is_broadcast'] == 1) { ?>
                        <div class="row">
                            <div class="col-4" id="">
                                <div class=" form-group">
                                    <label class="form-control-label" for="channel_number"><?php echo $this->lang->line("config_talkall_id") ?></label>
                                    <input type="text" class="form-control" name="channel_number" id="channel_number" value="<?php echo $data[0]['id']; ?>" disabled>
                                </div>
                            </div>

                            <div class="col-4" id="">
                                <div class="form-group">
                                    <label class="form-control-label" for="channel_name"><?php echo $this->lang->line("config_channel_name") ?></label>
                                    <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("tooltip_informative") ?>"></i>
                                    <input type="text" id="channel_name" class="form-control" name="channel_name" maxlength="55" value="<?php echo $data[0]['name']; ?>">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="to_recover_id_work_time"><?php echo $this->lang->line("config_worktime_title") ?></label>
                                    <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("config_worktime_tooltip") ?>"></i>
                                    <div class="dropdown dropdown-responsivo">
                                        <div class="input-group mb-3">
                                            <input type="hidden" class="value-select" id="to_recover_id_work_time" name="id_work_time" value="<?php echo $data[0]['id_work_time']; ?>">
                                            <input type="text" class="form-control dropdown-toggle" id="input_work_time" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" placeholder="<?php echo $this->lang->line("config_input_select") ?>" autocomplete="off" readonly>
                                            <i class="fas fa-chevron-down"></i>
                                            <div class="dropdown-menu links-responsivo" id="dropdown_work_time" aria-labelledby="input_work_time">
                                                <a class="dropdown-item" id="0" data-toggle="modal" data-target="#modal-work-time" onclick="clearFormModal()" title="<?php echo $this->lang->line("config_dropdown_add_new_work_time_title") ?>">
                                                    <b><?php echo $this->lang->line("config_dropdown_add_new_work_time") ?></b>
                                                    <i class="fas fa-plus"></i>
                                                </a>

                                                <?php if ($data[0]['id_work_time'] == null) { ?>
                                                    <a class="dropdown-item opt OptSelected" id="none" onclick="workTimeSelected(this)"><?php echo $this->lang->line("config_worktime_none") ?></a>
                                                <?php } else { ?>
                                                    <a class="dropdown-item opt" id="none" onclick="workTimeSelected(this)"><?php echo $this->lang->line("config_worktime_none") ?></a>
                                                <?php } ?>

                                                <?php foreach ($data['work_time'] as $row) { ?>
                                                    <a class="dropdown-item opt <?php echo $row['id_work_time'] == $data[0]['id_work_time'] ? "OptSelected" : "" ?>" id="<?php echo $row['id_work_time']; ?>" onclick="workTimeSelected(this)"><?php echo $row['name']; ?></a>
                                                <?php } ?>
                                            </div>
                                            <div class="alert-field-validation" id="user_group_validation" style="margin-top:48px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>

                        <div class="row">
                            <div class="col-6" id="">
                                <div class=" form-group">
                                    <label class="form-control-label" for="channel_number"><?php echo $this->lang->line("config_talkall_id") ?></label>
                                    <input type="text" class="form-control" name="channel_number" id="channel_number" value="<?php echo $data[0]['id']; ?>" disabled>
                                </div>
                            </div>

                            <div class="col-6" id="">
                                <div class="form-group">
                                    <label class="form-control-label" for="channel_name"><?php echo $this->lang->line("config_channel_name") ?></label>
                                    <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("tooltip_informative") ?>"></i>
                                    <input type="text" id="channel_name" class="form-control" name="channel_name" maxlength="55" value="<?php echo $data[0]['name']; ?>">
                                </div>
                            </div>
                        </div>

                    <?php } ?>


                    <?php if ($data[0]['is_broadcast'] == 1) {  ?>

                        <div class="row">
                            <div class="form-group col-12" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                <label class="form-control-label" for="welcome_message"><?php echo $this->lang->line("config_welcome_message") ?></label> <span class="form-control-label count-character-field"></span>
                                <textarea class="form-control" name='welcome_message' id='welcome_message' style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_welcome_message_placeholder") ?>" maxlength="1024"><?php echo $data[0]['welcome'] ?? "" ?></textarea>
                                <input type="hidden" name="integration_type" value="<?php echo $data[0]['integration_type'] ?>">
                            </div>

                            <div class="form-group col-12" style="display:none">
                                <label class="form-control-label" for="automatic_message"><?php echo $this->lang->line("config_automatic_message") ?></label>
                                <textarea class="form-control" name='automatic_message' id='automatic_message' style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_automatic_message_placeholder") ?>" maxlength="1024"><?php echo $data[0]['automatic_message'] ?? "" ?></textarea>
                            </div>

                            <?php if ($data[0]['integration_type'] == 2) { ?>
                                <div class="form-group col-12" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                    <label class="form-control-label" for="template_wa_business_contains_broadcast"><?php echo $this->lang->line("config_template_wa_business_contains_broadcast") ?></label> <span class="form-control-label count-character-field"></span>
                                    <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("tooltip_informative_template_wa_business_contains_broadcast") ?>"></i>
                                    <textarea class="form-control" name='template_wa_business_contains_broadcast' id='template_wa_business_contains_broadcast' style="resize: none;" rows="2" placeholder="<?php echo $this->lang->line("config_template_wa_business_contains_broadcast_placeholder") ?>" maxlength="200"><?php echo $data[0]['template_wa_business_contains_broadcast'] ?></textarea>
                                    <?php echo form_error('template_wa_business_contains_broadcast', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>

                                <div class="form-group col-12" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                    <label class="form-control-label" for="template_wa_business_no_contains_broadcast"><?php echo $this->lang->line("config_template_wa_business_no_contains_broadcast") ?></label> <span class="form-control-label count-character-field"></span>
                                    <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("tooltip_informative_template_wa_business_no_contains_broadcast") ?>"></i>
                                    <textarea class="form-control" name='template_wa_business_no_contains_broadcast' id='template_wa_business_no_contains_broadcast' style="resize: none;" rows="2" placeholder="<?php echo $this->lang->line("config_template_wa_business_no_contains_broadcast_placeholder") ?>" maxlength="200"><?php echo $data[0]['template_wa_business_no_contains_broadcast'] ?></textarea>
                                    <?php echo form_error('template_wa_business_no_contains_broadcast', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <h6 class="heading-small text-muted"><?php echo $this->lang->line("config_exit_label") ?></h6>
                                    </div>
                                </div>

                                <div class="form-group col-12" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                    <label class="form-control-label" for="opt_out_key"><?php echo $this->lang->line("config_opt_out_key") ?></label>
                                    <input class="form-control" name='opt_out_key' id='opt_out_key' data-toggle="tags" value="<?= $data[0]['opt_out_key'] ?? "" ?>">
                                </div>


                                <div class="form-group col-12" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                    <label class="form-control-label" for="opt_out_message"><?php echo $this->lang->line("config_opt_out_message") ?></label> <span class="form-control-label count-character-field"></span>
                                    <textarea class="form-control" name='opt_out_message' id='opt_out_message' style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_opt_out_message_placeholder") ?>" maxlength="1024"><?php echo $data[0]['opt_out_message'] ?? "" ?></textarea>
                                </div>
                            <?php } ?>

                            <div class="form-group col-12" style="display:none">
                                <label class="form-control-label" for="return_to_channel_message"><?php echo $this->lang->line("config_return_to_channel_message") ?></label>
                                <textarea class="form-control" name='return_to_channel_message' id='return_to_channel_message' style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_return_to_channel_message_placeholder") ?>" maxlength="1024"><?php echo $data[0]['return_to_channel_message'] ?? "" ?></textarea>
                            </div>
                        </div>

                    <?php } else if($data[0]['is_broadcast'] == 2 && $data[0]['type'] != 31){ ?>

                        <div class="row">
                            <div class="col-4">
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

                            <div class="col-4">
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

                            <div class="col-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="id_work_time"><?php echo $this->lang->line("config_time_zone"); ?></label>
                                    <?php
                                    $this->load->helper('date');
                                    echo timezone_menu(mysql_timezones($data[0]['timezone']), 'form-control', 'timezone', 'disabled');
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-12" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                <label class="form-control-label" for="welcome_message"><?php echo $this->lang->line("config_welcome_text") ?></label> <span class="form-control-label count-character-field"></span>
                                <textarea class="form-control" name='welcome_message' id='welcome_message' style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_welcome_text_placeholder") ?>" maxlength="1024"><?php echo $data[0]['welcome'] ?? "" ?></textarea>
                                <?php echo form_error('welcome_message', '<div class="alert-field-validation">', '</div>'); ?>
                            </div>

                            <?php
                            $channel_type = ['MercadoLivre', 'Facebook Comments', 'mercadolivre', 'facebook comments'];
                            if (!in_array($data[0]['name'], $channel_type)) : ?>

                                <?php if ($data[0]["webhook"] != "" && false) { ?>
                                    <div class="form-group col-12" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                        <label class="form-control-label" for="welcome"><?php echo $this->lang->line("config_welcome_text") ?></label>
                                        <textarea class="form-control" name='welcome' id='welcome' style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_welcome_text_placeholder") ?>" maxlength="1024"><?php echo $data[0]['welcome'] ?? "" ?></textarea>
                                    </div>
                                <?php } ?>

                                <div class="form-group col-12" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                    <label class="form-control-label" for="office_hours_end"><?php echo $this->lang->line("config_text_in_outside_office_hours") ?></label> <span class="form-control-label count-character-field"></span>
                                    <textarea class="form-control" name='office_hours_end' id="office_hours_end" style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_text_in_outside_office_hours_placeholder") ?> <?php echo lang('work_time'); ?>" maxlength="1024"><?php echo $data[0]['office_hours_end'] ?? "" ?></textarea>
                                </div>

                                <div class="form-group col-12" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                    <label class="form-control-label" for="message_start_attendance"><?php echo $this->lang->line("config_text_in_service_start") ?></label> <span class="form-control-label count-character-field"></span>
                                    <textarea class="form-control" name='message_start_attendance' id="message_start_attendance" style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_text_in_service_start_placeholder") ?>" maxlength="1024"><?php echo $data[0]['message_start_attendance'] ?? "" ?></textarea>
                                    <?php echo form_error('message_start_attendance', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>

                                <div class="form-group col-12" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none"; ?>">
                                    <label class="form-control-label" for="transfer_message"><?php echo $this->lang->line("config_service_transfer_text") ?></label> <span class="form-control-label count-character-field"></span>
                                    <textarea class="form-control" name='transfer_message' id="transfer_message" style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_service_trasnfer_text_placeholder") ?>" maxlength="1024"><?php echo $data[0]['transfer_message'] ?? "" ?></textarea>
                                </div>
                                <div class="form-group col-12" style="<?php echo $data[0]['type'] != 9 ? "" : "display:none";  ?>">
                                    <label class="form-control-label" for="automatic_transfer_minute"><?php echo $this->lang->line("config_service_automatic_transfer_minute") ?></label>
                                    <input type="number" class="form-control" name='automatic_transfer_minute' id="automatic_transfer_minute" style="resize: none;" rows="5" placeholder="<?php echo $this->lang->line("config_service_automatic_transfer_minute_placeholder") ?>" value="<?php echo $data[0]['automatic_transfer_minute'] ?? "" ?>" min="1">
                                    <?php echo form_error('automatic_transfer_minute', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>

                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">

                                <?php $channel_type = ['']; ?>
                                <?php if (in_array($data[0]['type'], $channel_type)) { ?>
                                    <div class="form-group " id="attendance">
                                        <input class="form-control slider-button" type="checkbox" id="checkbox-on-off-attendance" name="checkbox-on-off-attendance" value="<?= $data[0]['attendance_enable'] ?>" />
                                        <label style="margin-left: 20px;" class="form-control-label" for="attendance"><?php echo $this->lang->line("config_enable_service") ?></label>
                                    </div>
                                <?php } ?>

                                <?php $channel_type = ['2', '10', '12', '16']; ?>
                                <?php if (in_array($data[0]['type'], $channel_type)) { ?>
                                    <div class="form-group " id="protocol">
                                        <input class="form-control slider-button" type="checkbox" id="checkbox-on-off-protocol" name="checkbox-on-off-protocol" value="<?= $data[0]['enable_protocol'] ?>" />
                                        <label style="margin-left: 20px;" class="form-control-label" for="protocol"><?php echo $this->lang->line("config_enable_protocol"); ?></label>
                                    </div>
                                <?php } ?>

                                <?php $channel_type = ['2', '10', '12', '16']; ?>
                                <?php if (in_array($data[0]['type'], $channel_type)) { ?>
                                    <div class="form-group" id="chatbot">
                                        <input class="form-control slider-button" type="checkbox" id="checkbox-on-off-chatbot" name="checkbox-on-off-chatbot" value="<?= $data[0]['chatbot_enable'] ?>" />
                                        <label style="margin-left: 20px;" class="form-control-label" for="checkbox-on-off-chatbot"><?php echo $this->lang->line("config_enable_chatbot"); ?></label>
                                    </div>
                                <?php } ?>

                                <?php $channel_type = ['2', '10', '12', '16']; ?>
                                <?php if (in_array($data[0]['type'], $channel_type)) { ?>
                                    <div class="form-group " id="automatic_transfer">
                                        <input class="form-control slider-button" type="checkbox" id="checkbox-on-off-automaticTransfer" name="checkbox-on-off-automaticTransfer" value="<?php echo $data[0]['automatic_transfer'] ?>" />
                                        <label style="margin-left: 20px;" class="form-control-label" for="checkbox-on-off-automaticTransfer"><?php echo $this->lang->line("config_enable_automatic_transfer");  ?></label>
                                    </div>
                                <?php } ?>
                            </div>

                            <?php $channel_type = ['10','12', '16']; ?>
                            <?php if (in_array($data[0]['type'], $channel_type)) { ?>
                                <div class="col-lg-12">
                                    <div class="form-group" id="attendant_name_enable">
                                        <input type="checkbox" class="form-control slider-button" id="checkbox-on-off-attendantName" name="checkbox-on-off-attendantName" value="<?php echo $data[0]['attendant_name_enable'] ?>" <?php echo $data[0]['attendant_name_enable'] == 1 ? 'checked' : '' ?>>
                                        <label style="margin-left: 20px;" class="form-control-label"><?php echo $this->lang->line("config_enable_attendant_name") ?></label>
                                        <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("config_enable_attendant_name_tooltip") ?>"></i>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="checkbox" class="form-control slider-button" id="checkbox-on-off-aiEvaluation" name="checkbox-on-off-aiEvaluation" value="<?= $data[0]['ai_evaluation'] ?>" <?php if ($data[0]['ai_evaluation'] == 1) echo 'checked'; ?>>
                                    <label style="margin-left: 20px;" class="form-control-label"><?php echo $this->lang->line("config_ai_evaluation_enable"); ?></label>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group" id="dropdown-ai-evaluation">
                                    <label class="form-control-label"><?php echo $this->lang->line("config_ai_evaluation_service_selection"); ?></label>
                                    <select class="form-control" name="ai_options" id="ai_options">
                                        <option value=""> <?php echo $this->lang->line("config_ai_evaluation_select") ?> </option>
                                        <option value="2" <?= $data[0]['ai_options'] == '2' ? 'selected' : ''; ?>> <?php echo sprintf($this->lang->line("config_ai_evaluation_specific_quantity_services"), '2'); ?> </option>
                                        <option value="5" <?= $data[0]['ai_options'] == '5' ? 'selected' : ''; ?>> <?php echo sprintf($this->lang->line("config_ai_evaluation_specific_quantity_services"), '5'); ?> </option>
                                        <option value="10" <?= $data[0]['ai_options'] == '10' ? 'selected' : ''; ?>> <?php echo sprintf($this->lang->line("config_ai_evaluation_specific_quantity_services"), '10'); ?> </option>
                                        <option value="15" <?= $data[0]['ai_options'] == '15' ? 'selected' : ''; ?>> <?php echo sprintf($this->lang->line("config_ai_evaluation_specific_quantity_services"), '15'); ?> </option>
                                        <option value="30" <?= $data[0]['ai_options'] == '30' ? 'selected' : ''; ?>> <?php echo sprintf($this->lang->line("config_ai_evaluation_specific_quantity_services"), '30'); ?> </option>
                                        <option value="50" <?= $data[0]['ai_options'] == '50' ? 'selected' : ''; ?>> <?php echo sprintf($this->lang->line("config_ai_evaluation_specific_quantity_services"), '50'); ?> </option>
                                        <option value="unlimited" <?= $data[0]['ai_options'] == 'unlimited' ? 'selected' : ''; ?>> <?php echo $this->lang->line("config_ai_evaluation_all_services"); ?> </option>
                                    </select>
                                    <?php echo form_error('ai_options', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-12">
                            <a href="<?php echo base_url(); ?>integration"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("config_btn_return") ?></button></a>
                            <input type="submit" value="<?php echo $this->lang->line("config_btn_save") ?>" class="btn btn-success">
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>

            </div>

        </div>
    </div>
</div>
</div>

<!-- Modal adicionar tabela de horário -->
<div class="modal fade show" id="modal-work-time" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:550px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("config_worktime_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="modalWorkTime__name"><?php echo $this->lang->line("config_worktime_subtitle_label") ?></label>
                            <input type="text" id="modalWorkTime__name" name="modalWorkTime__name" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("config_worktime_subtitle_placeholder") ?>">
                            <div class="alert-field-validation" id="alertWorkTime__name"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">

                        <?php $diasemana = array(
                            7 => $this->lang->line("config_worktime_sunday"),
                            1 => $this->lang->line("config_worktime_monday"),
                            2 => $this->lang->line("config_worktime_tuesday"),
                            3 => $this->lang->line("config_worktime_wednesday"),
                            4 => $this->lang->line("config_worktime_thursday"),
                            5 => $this->lang->line("config_worktime_friday"),
                            6 => $this->lang->line("config_worktime_saturday")
                        );

                        foreach ($diasemana as $key => $value) {

                            $start = "";
                            $end = "";

                        ?>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="custom-control custom-control-alternative custom-checkbox pt-2" style="width: 100px;">
                                        <input class="custom-control-input" onchange="inputGroup('<?= $key ?>')" id="<?= $key ?>-checkbox" type="checkbox">
                                        <label class="custom-control-label" for="<?= $key ?>-checkbox"><?= $value ?></label>
                                    </div>
                                    <input type="text" class="form-control mx-2 time" name="<?= $key ?>-start" id="<?= $key ?>-start" placeholder="<?= $this->lang->line("config_worktime_start_placeholder") ?>" disabled />
                                    <input type="text" class="form-control mx-2 time" name="<?= $key ?>-end" id="<?= $key ?>-end" placeholder="<?= $this->lang->line("config_worktime_end_placeholder") ?>" disabled />
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="alert-field-validation" id="alertWorkTime__fields"></div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("config_worktime_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-work-time" onclick="saveWorkTime()"><?php echo $this->lang->line("config_worktime_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>

<script src="sweetalert2.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($this->session->flashdata('error')) {  ?>
            Swal.fire(
                'Erro!',
                "<?php echo $this->session->flashdata('error') ?>",
                'error'
            );

        <?php } ?>

    });
</script>