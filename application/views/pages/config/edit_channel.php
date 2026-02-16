<?php echo form_open("config/channel/save/{$data['id_config']}") ?>

<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("config_edit_channel_title") ?></h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <input type="hidden" name="id_config" value="<?php echo $data['id_config'] ?>">
                    <input type="hidden" name="id_channel" value="<?php echo $data['id_channel'] ?>">
                    <input type="hidden" name="type" value="<?php echo $data['type'] ?>">
                    <input type="hidden" name="is_broadcast" value="<?php echo $data['is_broadcast'] ?>">

                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-name"><?php echo $this->lang->line("config_edit_channel_name") ?></label>
                                    <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("config_edit_channel_name_tooltip_informative") ?>"></i>
                                    <input type="text" id="input-name" name="input-name" class="form-control" placeholder="<?php echo $this->lang->line("config_edit_channel_name_placeholder") ?>" value="<?php echo $data['name']; ?>" maxlength="250">
                                    <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_input_name"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-phone"><?php echo $this->lang->line("config_edit_channel_number") ?></label>
                                    <input type="text" id="input-phone" class="form-control" onkeyup="maskPhone(this)" disabled placeholder="<?php echo $this->lang->line("config_edit_channel_number_placeholder") ?>" value="<?php echo $data['id']; ?>" maxlength="250">
                                    <?php echo form_error('input-phone', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_input_phone"></div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-profile">
                                <div class="box-picture-profile-config">
                                    <label class="form-control-label"><?php echo $this->lang->line("config_edit_channel_profile_title") ?></label>
                                    <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("config_edit_channel_profile_tooltip_informative") ?>"></i>
                                    <div class="picture-profile transition-effect">
                                        <i class="fas fa-camera icon-add-photo" id="addProfile"></i>
                                        <span class="picture-profile-title"><?php echo $this->lang->line("config_edit_channel_change_profile"); ?></span>
                                        <img src="<?php echo $data['picture'] ==  "" ? base_url("assets/icons/panel/profile_default.svg") : $data['picture']  ?>" alt="preview">
                                        <input type="file" class="form-control" id="inputFile" accept="image/png, image/gif, image/jpeg, image/jpg, image/jfif" data-file="picture" onchange="handleFiles(this)" style="display: none;" />
                                        <input type="hidden" name="input-picture" id="input-picture" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group reset-mb">
                                    <label class="form-control-label" for="textarea-description"><?php echo $this->lang->line("config_edit_channel_description") ?></label>
                                    <textarea class="form-control" name='textarea-description' id='textarea-description' style="resize: none;" rows="6" placeholder="<?php echo $this->lang->line("config_edit_channel_description_placeholder") ?>" maxlength="1024"><?php echo $data['company_description'] ?? "" ?></textarea>
                                    <?php echo form_error('textarea-description', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_textarea_description"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("config_edit_channel_email") ?></label>
                                    <input type="text" id="input-email" name="input-email" class="form-control" placeholder="<?php echo $this->lang->line("config_edit_channel_email_placeholder") ?>" value="<?php echo $data['email']; ?>" maxlength="250">
                                    <?php echo form_error('input-email', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_input_email"></div>
                                </div>
                                <div class="">
                                    <label class="form-control-label" for="input-site"><?php echo $this->lang->line("config_edit_channel_site") ?></label>
                                    <input type="text" id="input-site" name="input-site" class="form-control" placeholder="<?php echo $this->lang->line("config_edit_channel_site_placeholder") ?>" value="<?php echo $data['social_media']; ?>" maxlength="250">
                                    <?php echo form_error('input-site', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_input_site"></div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row welcome-message">
                            <div class="col-lg-6 right">
                                <div class="form-group">
                                    <h4 class="mb-0"><?php echo $this->lang->line("config_edit_channel_welcome_message") ?></h4>
                                    <span><?php echo $this->lang->line("config_edit_channel_welcome_message_description") ?></span>
                                    <button class="btn" type="button" data-toggle="modal" data-target="#modal-welcome-message"><?php echo $this->lang->line("config_btn_edit") ?></button>
                                </div>
                            </div>
                            <div class="col-lg-6 left">
                                <div class="form-group reset-mb">
                                    <div class="messageText">
                                        <div class="body">
                                            <div class="tex">
                                                <span><?php echo $data["template_wa_business_welcome"] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row privacy-policy">
                            <div class="col-lg-6 right">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4 class="mb-0 title"><?php echo $this->lang->line("config_edit_channel_privacy_policy") ?></h4>
                                            <label class="custom-toggle mb-2">
                                                <input type="checkbox" id="toggle-privacy-policy" name="toggle-privacy-policy">
                                                <span class="custom-toggle-slider rounded-circle custom-toggle-slider-privacy-policy" data-label-off="<?php echo $this->lang->line("config_edit_channel_privacy_policy_no") ?>" data-label-on="<?php echo $this->lang->line("config_edit_channel_privacy_policy_yes") ?>"></span>
                                            </label>
                                            <label class="form-control-label title-toggle-view" for=""><?php echo $this->lang->line("whatsapp_broadcast_automatic_response") ?></label>
                                        </div>
                                    </div>
                                    <span class="privacy-policy-description" style="display: <?php echo $data["is_privacy_term"] == true ? "block" : "none" ?>;"><?php echo $this->lang->line("config_edit_channel_privacy_policy_description") ?></span>
                                    <button class="btn" type="button" data-toggle="modal" data-target="#modal-privacy-policy" style="display: <?php echo $data["is_privacy_term"] == true ? "block" : "none" ?>;"><?php echo $this->lang->line("config_btn_edit") ?></button>
                                </div>
                            </div>
                            <div class="col-lg-6 left" style="display: <?php echo $data["is_privacy_term"] == true ? "flex" : "none" ?>;">
                                <div class="form-group reset-mb">
                                    <div class="messageText">
                                        <div class="body">
                                            <div class="tex">
                                                <span><?php echo $data["template_wa_business_description"] ?></span>
                                            </div>
                                            <div class="link">
                                                <a href="<?php echo $data["template_wa_business_term_link"] ?>" target="_blank" rel="noopener noreferrer">
                                                    <i class="fas fa-external-link-alt"></i>
                                                    <span><?php echo $this->lang->line("config_edit_channel_privacy_policy_message_link") ?></span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="bottom">
                                            <div class="info">
                                                <span class="text"><?php echo $this->lang->line("config_edit_channel_privacy_policy_agree") ?></span>
                                                <div class="btn-msg">
                                                    <span><?php echo $this->lang->line("config_edit_channel_privacy_policy_btn_agree") ?></span>
                                                    <span><?php echo $this->lang->line("config_edit_channel_privacy_policy_btn_dont_agree") ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row opt-in">
                            <div class="col-lg-6 right">
                                <div class="form-group">
                                    <h4 class="mb-0"><?php echo $this->lang->line("config_edit_channel_opt_in") ?></h4>
                                    <span><?php echo $this->lang->line("config_edit_channel_opt_in_description") ?></span>
                                    <button class="btn" type="button" data-toggle="modal" data-target="#modal-opt-in"><?php echo $this->lang->line("config_btn_edit") ?></button>
                                </div>
                            </div>
                            <div class="col-lg-6 left">
                                <div class="form-group reset-mb">
                                    <div class="messageText">
                                        <div class="body">
                                            <div class="tex invitation">
                                                <span><?php echo $data["template_wa_business_optin_term_accept_yes"] ?></span>
                                            </div>
                                            <div class="tex options">
                                                <span><?php echo $data["template_wa_business_optin_description"] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="messageImage" style="display:<?php echo isset($data["template_wa_business_optin_term_accept_yes_media_url"]) ? "block" : "none" ?>">
                                        <img src="<?php echo isset($data["template_wa_business_optin_term_accept_yes_media_url"]) ? $data["template_wa_business_optin_term_accept_yes_media_url"] : base_url("/assets/img/panel/background_no_picture.jpg") ?>" alt="preview">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row opt-out">
                            <div class="col-lg-6 right">
                                <div class="form-group">
                                    <h4 class="mb-0"><?php echo $this->lang->line("config_edit_channel_opt_out") ?></h4>
                                    <span><?php echo $this->lang->line("config_edit_channel_opt_out_description") ?></span>
                                    <button class="btn" type="button" data-toggle="modal" data-target="#modal-opt-out"><?php echo $this->lang->line("config_btn_edit") ?></button>
                                </div>
                            </div>
                            <div class="col-lg-6 left">
                                <div class="form-group reset-mb">
                                    <div class="messageText">
                                        <div class="body">
                                            <div class="tex">
                                                <span><?php echo isset($optout_confirm_list_text) ? $optout_confirm_list_text : "" ?></span>
                                            </div>
                                            <div class="list" data-toggle="modal" data-target="#modal-list">
                                                <img src="<?php echo base_url("/assets/icons/panel/options.svg") ?>" alt="preview">
                                                <span><?php echo $this->lang->line("config_edit_channel_opt_out_list") ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row closed-message">
                            <div class="col-lg-6 right">
                                <div class="form-group">
                                    <h4 class="mb-0"><?php echo $this->lang->line("config_edit_channel_closed_message") ?></h4>
                                    <span><?php echo $this->lang->line("config_edit_channel_closed_message_description") ?></span>
                                    <button class="btn" type="button" data-toggle="modal" data-target="#modal-closed-message"><?php echo $this->lang->line("config_btn_edit") ?></button>
                                </div>
                            </div>
                            <div class="col-lg-6 left">
                                <div class="form-group reset-mb">
                                    <div class="messageText">
                                        <div class="body">
                                            <div class="tex call-closed">
                                                <span><?php echo $data["template_wa_business_optout_list_selected_title"] ?></span>
                                            </div>
                                            <div class="tex contact-return">
                                                <span><?php echo $data["template_wa_business_optout_list_selected_description"] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row automatic-messages mb-5">
                            <div class="col-lg-6 right">
                                <div class="form-group">
                                    <h4 class="mb-0"><?php echo $this->lang->line("config_edit_channel_automatic_messages") ?></h4>
                                    <span><?php echo $this->lang->line("config_edit_channel_automatic_messages_description") ?></span>
                                    <button class="btn" type="button" data-toggle="modal" data-target="#modal-automatic-messages"><?php echo $this->lang->line("config_btn_edit") ?></button>
                                </div>
                            </div>
                            <div class="col-lg-6 left">
                                <div class="form-group reset-mb">
                                    <div class="messageText">
                                        <div class="body">
                                            <div class="tex info-attendance">
                                                <span><?php echo $data["template_wa_business_automatic_message_title"] ?></span>
                                            </div>
                                            <div class="tex about-attendance">
                                                <span><?php echo $data["template_wa_business_automatic_message_description"] ?></span>
                                            </div>
                                            <div class="tex phone-attendance">
                                                <a href="<?php echo "https://wa.me/" . $data["template_wa_business_automatic_message_phone"] ?>" target="_blank" rel="noopener noreferrer">
                                                    <i class="fas fa-external-link-alt"></i>
                                                    <span><?php echo $this->lang->line("config_edit_channel_automatic_messages_phone_attendance") ?></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>integration"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("config_btn_return") ?></button></a>
                                <button class="btn btn-success" type="button"><?php echo $this->lang->line("config_btn_save") ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-welcome-message" tabindex="-1" role="dialog" aria-labelledby="modal-privacy-policy" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h6 class="modal-title"><?php echo $this->lang->line("config_edit_channel_welcome_message_modal_title") ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body pb-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="welcome-message__textarea-desc"><?php echo $this->lang->line("config_edit_channel_welcome_message_modal_description") ?></label>
                            <textarea class="form-control" name='welcome-message__textarea-desc' id='welcome-message__textarea-desc' style="resize: none;" rows="6" maxlength="1024" placeholder="<?php echo $this->lang->line("config_edit_channel_welcome_message_modal_description_placeholder"); ?>"><?php echo $data["template_wa_business_welcome"] ?></textarea>
                            <div class="alert-field-validation" id="alert_welcome-message__textarea-desc"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-green" id="btn-welcome-message"><?php echo $this->lang->line("config_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-privacy-policy" tabindex="-1" role="dialog" aria-labelledby="modal-privacy-policy" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h6 class="modal-title"><?php echo $this->lang->line("config_edit_channel_privacy_policy_modal_title") ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body pb-3">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="privacy-policy__info"><?php echo $this->lang->line("config_edit_channel_privacy_policy_modal_msg_info_privacy") ?></label>
                            <input type="text" id="privacy-policy__info" name="privacy-policy__info" class="form-control" value="<?php echo $data["template_wa_business_description"] ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_privacy_policy_modal_msg_info_privacy_placeholder") ?>" maxlength="250">
                            <div class="alert-field-validation" id="alert_privacy-policy__info"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="privacy-policy__link"><?php echo $this->lang->line("config_edit_channel_privacy_policy_modal_link_policy") ?></label>
                            <input type="text" id="privacy-policy__link" name="privacy-policy__link" class="form-control" value="<?php echo $data["template_wa_business_term_link"] ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_privacy_policy_modal_link_policy_placeholder") ?>" maxlength="250">
                            <div class="alert-field-validation" id="alert_privacy-policy__link"></div>
                        </div>
                    </div>
                </div>

                <div class="row reactions-term">
                    <div class="col-12">
                        <label class="toggle">
                            <input class="toggle-checkbox" name="input-term-agree" type="checkbox">
                            <div class="toggle-switch toggle__agree"></div>
                            <span class="toggle-label"><?php echo $this->lang->line("config_edit_channel_privacy_policy_modal_toggle") ?></span>
                        </label>
                        <div class="option-emoji optTermAgree">
                            <textarea class="textarea-emoji" name="emoji_term_agre" id="term_agree" style="display: none"><?php echo $data['template_wa_business_term_accept_yes_reaction'] ?></textarea>
                        </div>
                        <div class="text">
                            <span><?php echo $this->lang->line("config_edit_channel_privacy_policy_modal_toggle_term_agree") ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="toggle">
                            <input class="toggle-checkbox" name="input-term-dont-agree" type="checkbox">
                            <div class="toggle-switch toggle__dont__agree"></div>
                            <span class="toggle-label"><?php echo $this->lang->line("config_edit_channel_privacy_policy_modal_toggle") ?></span>
                        </label>
                        <div class="option-emoji optTermDontAgree">
                            <textarea class="textarea-emoji" name="emoji_term_dont_agre" id="term_dont_agree" style="display: none"><?php echo $data['template_wa_business_term_accept_no_reaction'] ?></textarea>
                        </div>
                        <div class="text">
                            <span><?php echo $this->lang->line("config_edit_channel_privacy_policy_modal_toggle_term_dont_agree") ?></span>
                        </div>
                    </div>
                </div>
                <div class="alert-field-validation" id="alert_emoji_term_dont"></div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-green" id="btn-privacy-policy"><?php echo $this->lang->line("config_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-opt-in" tabindex="-1" role="dialog" aria-labelledby="modal-opt-in" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h6 class="modal-title"><?php echo $this->lang->line("config_edit_channel_opt_in_modal_title") ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body pb-3">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="opt-in__invitation"><?php echo $this->lang->line("config_edit_channel_opt_in_modal_customer_invitation") ?></label>
                            <input type="text" id="opt-in__invitation" name="opt-in__invitation" class="form-control" value="<?php echo $data["template_wa_business_optin_term_accept_yes"] ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_opt_in_modal_customer_invitation_placeholder") ?>" maxlength="250">
                            <div class="alert-field-validation" id="alert_opt-in__invitation"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="opt-in__textarea-desc"><?php echo $this->lang->line("config_edit_channel_opt_in_modal_complete_opt_in") ?></label>
                            <textarea class="form-control" name='opt-in__textarea-desc' id='opt-in__textarea-desc' style="resize: none;" rows="3" maxlength="1024" placeholder="<?php echo $this->lang->line("config_edit_channel_opt_in_modal_complete_opt_in_placeholder"); ?>"><?php echo $data["template_wa_business_optin_description"] ?></textarea>
                            <div class="alert-field-validation" id="alert_opt-in__textarea_desc"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb- reset-ml">
                            <label class="form-control-label"><?php echo $this->lang->line("config_edit_channel_opt_in_modal_add_imagem") ?></label>
                            <span class="warning"><?php echo $this->lang->line("config_edit_channel_opt_in_modal_add_imagem_description") ?></span>
                            <div class="file-content">
                                <div class="left">
                                    <div class="preview" id="preview_opt_in">
                                        <img src="<?php echo isset($data["template_wa_business_optin_term_accept_yes_media_url"]) ? $data["template_wa_business_optin_term_accept_yes_media_url"] : base_url("/assets/img/panel/background_no_picture.jpg") ?>" alt="preview">
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="btn-add">
                                        <img src="<?php echo base_url("/assets/icons/panel/background-upload.svg") ?>" alt="preview">
                                        <span><?php echo $this->lang->line("config_edit_channel_opt_in_modal_add_imagem_buttom") ?></span>
                                    </div>
                                    <div class="btn-remove">
                                        <img src="<?php echo base_url("/assets/icons/panel/remove-img.svg") ?>" alt="preview">
                                        <span><?php echo $this->lang->line("config_edit_channel_opt_in_modal_remove_imagem_buttom") ?></span>
                                    </div>
                                </div>
                                <input type="file" id="opt-in__file" data-file="optin" onchange="handleFiles(this)" style="display:none;">
                                <input type="hidden" name="opt-in-file" id="optInFile" value="<?php echo isset($data["template_wa_business_optin_term_accept_yes_media_url"]) ? $data["template_wa_business_optin_term_accept_yes_media_url"] : "" ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-green" id="btn-opt-in"><?php echo $this->lang->line("config_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-opt-out" tabindex="-1" role="dialog" aria-labelledby="modal-opt-out" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h6 class="modal-title"><?php echo $this->lang->line("config_edit_channel_opt_out_modal_title") ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body pb-3">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="opt-out__leave_opt_out"><?php echo $this->lang->line("config_edit_channel_opt_out_modal_leave_opt_out") ?></label>
                            <input type="text" id="opt-out__leave_opt_out" name="opt-out__leave_opt_out" class="form-control" value="<?php echo $data["template_wa_business_optout"] ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_opt_out_modal_leave_opt_out_placeholder") ?>" maxlength="250">
                            <div class="alert-field-validation" id="alert_opt-out__leave_opt_out"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal labels">
                            <label class="form-control-label" for="opt-out__keyword"><?php echo $this->lang->line("config_edit_channel_opt_out_modal_keyword") ?></label>
                            <input class="form-control" name="opt-out__keyword" id="opt-out__keyword" data-toggle="tags" maxlength="1024" value="<?php echo $data["opt_out_key"] ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_opt_out_modal_leave_opt_out_value_placeholder") ?>" />
                            <div class="alert-field-validation" id="alert_opt-out__keyword"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="opt-out__question_reason"><?php echo $this->lang->line("config_edit_channel_opt_out_modal_question_reason") ?></label>
                            <textarea class="form-control" name='opt-out__question_reason' id='opt-out__question_reason' style="resize: none;" rows="2" maxlength="1024" placeholder="<?php echo $this->lang->line("config_edit_channel_opt_out_modal_question_reason_placeholder") ?>"><?php echo isset($optout_confirm_list_text) ? $optout_confirm_list_text : "" ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="opt-out__reason_one"><?php echo $this->lang->line("config_edit_channel_opt_out_modal_reason_one") ?></label>
                            <input type="text" id="opt-out__reason_one" name="opt-out__reason_one" class="form-control opt-list" value="<?php echo isset($optout_confirm_list_option[0]) ? $optout_confirm_list_option[0]['title'] : "" ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_opt_out_modal_reason_one_placeholder") ?>" maxlength="250">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="opt-out__reason_two"><?php echo $this->lang->line("config_edit_channel_opt_out_modal_reason_two") ?></label>
                            <input type="text" id="opt-out__reason_two" name="opt-out__reason_two" class="form-control opt-list" value="<?php echo isset($optout_confirm_list_option[1]) ? $optout_confirm_list_option[1]['title'] : "" ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_opt_out_modal_reason_two_placeholder") ?>" maxlength="250">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="opt-out__reason_three"><?php echo $this->lang->line("config_edit_channel_opt_out_modal_reason_three") ?></label>
                            <input type="text" id="opt-out__reason_three" name="opt-out__reason_three" class="form-control opt-list" value="<?php echo isset($optout_confirm_list_option[2]) ? $optout_confirm_list_option[2]['title'] : "" ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_opt_out_modal_reason_three_placeholder") ?>" maxlength="250">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="opt-out__reason_four"><?php echo $this->lang->line("config_edit_channel_opt_out_modal_reason_four") ?></label>
                            <input type="text" id="opt-out__reason_four" name="opt-out__reason_four" class="form-control opt-list" value="<?php echo isset($optout_confirm_list_option[3]) ? $optout_confirm_list_option[3]['title'] : "" ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_opt_out_modal_reason_four_placeholder") ?>" maxlength="250">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="opt-out__reason_five"><?php echo $this->lang->line("config_edit_channel_opt_out_modal_reason_five") ?></label>
                            <input type="text" id="opt-out__reason_five" name="opt-out__reason_five" class="form-control opt-list" value="<?php echo isset($optout_confirm_list_option[4]) ? $optout_confirm_list_option[4]['title'] : "" ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_opt_out_modal_reason_five_placeholder") ?>" maxlength="250">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-green" id="btn-opt-out"><?php echo $this->lang->line("config_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-list" tabindex="-1" role="dialog" aria-labelledby="modal-list" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 440px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h6 class="modal-title"><?php echo $this->lang->line("config_edit_channel_opt_out_list") ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>

        </div>
    </div>
</div>


<div class="modal fade" id="modal-closed-message" tabindex="-1" role="dialog" aria-labelledby="modal-closed-message" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h6 class="modal-title"><?php echo $this->lang->line("config_edit_channel_closed_message_modal_title") ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body pb-3">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="closed-message__call_closed"><?php echo $this->lang->line("config_edit_channel_closed_message_modal_call_closed") ?></label>
                            <input type="text" id="closed-message__call_closed" name="closed-message__call_closed" class="form-control" value="<?php echo $data["template_wa_business_optout_list_selected_title"] ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_closed_message_modal_call_closed_placeholder") ?>" maxlength="250">
                            <div class="alert-field-validation" id="alert_closed-message__call_closed"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="closed-message__contact_return"><?php echo $this->lang->line("config_edit_channel_closed_message_modal_contact_return") ?></label>
                            <textarea class="form-control" name='closed-message__contact_return' id='closed-message__contact_return' style="resize: none;" rows="4" maxlength="1024" placeholder="<?php echo $this->lang->line("config_edit_channel_closed_message_modal_contact_return_placeholder") ?>"><?php echo $data["template_wa_business_optout_list_selected_description"] ?></textarea>
                            <div class="alert-field-validation" id="alert_closed-message__contact_return"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-green" id="btn-closed-message"><?php echo $this->lang->line("config_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-automatic-messages" tabindex="-1" role="dialog" aria-labelledby="modal-automatic-messages" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h6 class="modal-title"><?php echo $this->lang->line("config_edit_channel_automatic_messages_modal_title") ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body pb-3">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="automatic-messages__info_attendance"><?php echo $this->lang->line("config_edit_channel_automatic_messages_modal_info_attendance") ?></label>
                            <input type="text" id="automatic-messages__info_attendance" name="automatic-messages__info_attendance" class="form-control" value="<?php echo $data["template_wa_business_automatic_message_title"] ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_automatic_messages_modal_info_attendance_placeholder") ?>" maxlength="250">
                            <div class="alert-field-validation" id="alert_automatic-messages__info_attendance"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="automatic-messages__about_attendance"><?php echo $this->lang->line("config_edit_channel_automatic_messages_modal_about_attendance") ?></label>
                            <input type="text" id="automatic-messages__about_attendance" name="automatic-messages__about_attendance" class="form-control" value="<?php echo $data["template_wa_business_automatic_message_description"] ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_automatic_messages_modal_about_attendance_placeholder") ?>" maxlength="250">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group reset-mb-modal">
                            <label class="form-control-label" for="automatic-messages__phone_attendance"><?php echo $this->lang->line("config_edit_channel_automatic_messages_modal_phone_attendance") ?></label>
                            <input type="text" id="automatic-messages__phone_attendance" name="automatic-messages__phone_attendance" class="form-control" value="<?php echo $data["template_wa_business_automatic_message_phone"] ?>" placeholder="<?php echo $this->lang->line("config_edit_channel_automatic_messages_modal_phone_attendance_placeholder") ?>" maxlength="250">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-green" id="btn-automatic-messages"><?php echo $this->lang->line("config_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>