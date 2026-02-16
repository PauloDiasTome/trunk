<input type="hidden" id="verifyAdd" value="true">
<div class="container mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("template_add_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("template_add_information") ?></h6>

                    <div class="row">
                        <div class="col-2">
                            <div class="form-group">
                                <label class="form-control-label" for="input-creation"><?php echo $this->lang->line("template_creation") ?></label>
                                <input type="text" id="input-creation" disabled="true" name="input-creation" class="form-control" value="<?php echo date('d/m/Y'); ?>">
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label class="form-control-label" for="channel"><?php echo $this->lang->line("template_channel") ?></label>
                                <select class="form-control" name="channel" id="channel">
                                    <?php foreach ($channels as $item) { ?>
                                        <option value="<?= $item['id'] ?>" channel_type="<?= $item['type'] ?>"><?= $item['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="text" id="channel_type" name="channel_type" hidden>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <input type="hidden" value="<?= isset($category) ? $category : '' ?>" id="categoryRecover">
                                <label class="form-control-label" for="category"><?php echo $this->lang->line("template_column_category") ?></label>

                                <select class="form-control" id="category_pt_br" name="category_pt_br">
                                    <?php foreach ($categories as $item) { ?>
                                        <option value="<?= $item[0] ?>"><?= $item[2] ?></option>
                                    <?php } ?>
                                </select>

                                <select class="form-control" id="category_en_us" name="category_en_us" style="display: none;">
                                    <?php foreach ($categories as $item) { ?>
                                        <option value="<?= $item[0] ?>"><?= $item[1] ?></option>
                                    <?php } ?>
                                </select>
                                
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label class="form-control-label" for="language"><?php echo $this->lang->line("template_lang") ?></label>
                                <select class="form-control" id="languageSelect" name="language">
                                    <option value="pt_BR" <?= isset($lang) ? ($lang == 'pt_BR' ? 'selected' : '') : '' ?>>
                                        <?php echo $this->lang->line("template_port") ?>
                                    </option>

                                    <option value="en_US" <?= isset($lang)  ? ($lang == 'en_US' ? 'selected' : '') : '' ?>>
                                        <?php echo $this->lang->line("template_en") ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="name"><?php echo $this->lang->line("template_name") ?></label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo $this->lang->line("template_name_placeholder") ?>" value="<?= isset($name) ? $name : '' ?>" maxlength="100">
                                <div class="alert-field-validation" id="alert_input_name"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h3><?php echo $this->lang->line("template_header") ?></h3>

                            <div class="row mb-3">
                                <div class="col-3">
                                    <select class="form-control" name="select_header" id="select_header">
                                        <option value=""><?php echo $this->lang->line("template_select_option_none") ?></option>
                                        <option value="option_header_text"><?php echo $this->lang->line("template_header_option_text") ?></option>
                                        <option value="option_header_media"><?php echo $this->lang->line("template_header_option_media") ?></option>
                                    </select>
                                </div>
                                <div class="col-9" id="div_text_header" style="display: none;">
                                    <input class="form-control" id="text-header" name="text-header" type="text" maxlength="60" placeholder="<?php echo $this->lang->line("template_header_placeholder") ?>">
                                    <div class="alert-field-validation" id="alert_input_header"></div>
                                </div>
                            </div>

                            <div class="row" id="div_media_header" style="display: none;">
                                <div class="col-3 pr-5 mr--5">
                                    <div class="div_media_radio" id="div_media_image">
                                        <div class="custom-control custom-radio custom-control">
                                            <input class="custom-control-input" type="radio" name="media_header" id="media_header_image" value="IMAGE">
                                        </div>
                                        <i class="far fa-image fa-3x"></i>
                                        <span class="header_media_label"><?php echo $this->lang->line("template_header_media_image") ?></span>
                                    </div>
                                </div>
                                <div class="col-3 pr-5 mr--5">
                                    <div class="div_media_radio" id="div_media_video">
                                        <div class="custom-control custom-radio custom-control">
                                            <input class="custom-control-input" type="radio" name="media_header" id="media_header_video" value="VIDEO">
                                        </div>
                                        <i class="far fa-play-circle fa-3x"></i>
                                        <span class="header_media_label"><?php echo $this->lang->line("template_header_media_video") ?></span>
                                    </div>
                                </div>
                                <div class="col-3 pr-5 mr--5">
                                    <div class="div_media_radio" id="div_media_document">
                                        <div class="custom-control custom-radio custom-control">
                                            <input class="custom-control-input" type="radio" name="media_header" id="media_header_document" value="DOCUMENT">
                                        </div>
                                        <i class="far fa-file-alt fa-3x"></i>
                                        <span class="header_media_label"><?php echo $this->lang->line("template_header_media_document") ?></span>
                                    </div>
                                </div>
                                <div class="alert-field-validation" id="alert_input_media" style="bottom: -18px; left: 15px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <h3><?php echo $this->lang->line("template_body_label") ?></h3>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="text-body"><?php echo $this->lang->line("template_body_description") ?> <span style="color: red" id="count-caracter"><?php echo $this->lang->line("template_number") ?></span></label>
                                        <textarea class="form-control" id="text-body" name="text-body" rows="7" resize="none" maxlength="1024"><?= isset($text_body) ? $text_body : '' ?></textarea>
                                        <div class="alert-field-validation" id="alert_input_body"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt--4 mb-2" id="div_variables_error" style="display: none;">
                                <div class="col-12">
                                    <ul class="ul_variables_error" id="variable_error_list">
                                    </ul>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 ">
                                    <button class="btn btn-default btn-sm float-right mt--3" id="add_variable"><?php echo $this->lang->line("template_body_button_add_variable") ?></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <h3><?php echo $this->lang->line("template_footer_label") ?></h3>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <input class="form-control" type="text" id="text-footer" name="text-footer" maxlength="60" placeholder="<?php echo $this->lang->line("template_footer_placeholder") ?>">
                                    <div class="alert-field-validation" id="alert_input_footer"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <h3><?php echo $this->lang->line("template_button_label") ?></h3>

                            <div class="row mb-3">
                                <div class="col-3">
                                    <select class="form-control" name="select_button" id="select_button">
                                        <option value=""><?php echo $this->lang->line("template_select_option_none") ?></option>
                                        <option value="option_button_cta"><?php echo $this->lang->line("template_button_option_cta") ?></option>
                                        <option value="option_button_quick_answer"><?php echo $this->lang->line("template_button_option_quick_answer") ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3" id="div_call_to_action" style="display: none;">
                                <div class="col-12">

                                    <div class="row">
                                        <div class="col-12 button-container">

                                            <div class="row draggable" draggable="true">

                                                <i class="fas fa-grip-vertical"></i>

                                                <div class="col-3">
                                                    <label class="form-control-label" for="select_button_action_type"><?php echo $this->lang->line("template_button_cta_type") ?></label>
                                                    <select class="form-control select_button_action_type" name="select_button_action_type" id="select_button_action_type">
                                                        <option value="PHONE_NUMBER"><?php echo $this->lang->line("template_button_cta_type_call") ?></option>
                                                        <option value="URL"><?php echo $this->lang->line("template_button_cta_type_url") ?></option>
                                                    </select>
                                                </div>

                                                <div class="col-3">
                                                    <label class="form-control-label" for="text-button"><?php echo $this->lang->line("template_button_text_label") ?></label>
                                                    <input class="form-control" type="text" id="text-button" name="text-button" maxlength="25" placeholder="<?php echo $this->lang->line("template_button_text_placeholder") ?>">
                                                </div>

                                                <div class="col-6" id="div_select_call">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <label class="form-control-label" for="select_country"><?php echo $this->lang->line("template_button_country_label") ?></label>
                                                            <select class="form-control" name="select_country" id="select_country">
                                                                <option value="+55">BR +55</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-8">
                                                            <label class="form-control-label" for="phone-button"><?php echo $this->lang->line("template_button_phone_label") ?></label>
                                                            <input class="form-control input-counter" type="text" draggable="false" id="phone-button" name="phone-button" maxlength="11" placeholder="00 0000-0000">
                                                            <div class="alert-field-validation" id="alert_phone_button" name="alert_phone_button"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6" id="div_select_url" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <label class="form-control-label" for="select_url_type_button"><?php echo $this->lang->line("template_button_url_type_label") ?></label>
                                                            <select class="form-control" name="select_url_type_button" id="select_url_type_button">
                                                                <option value="option_url_type_static"><?php echo $this->lang->line("template_button_url_type_option_static") ?></option>
                                                                <option value="option_url_type_dynamic"><?php echo $this->lang->line("template_button_url_type_option_dynamic") ?></option>
                                                            </select>
                                                        </div>
                                                        <div class="col-8">
                                                            <label class="form-control-label" for="url_button"><?php echo $this->lang->line("template_button_url_label") ?></label>
                                                            <input class="form-control" type="text" id="url_button" name="url_button" maxlength="20000" placeholder="https://website.com.br">
                                                            <div class="alert-field-validation" id="alert_url_button" name="alert_url_button"></div>
                                                        </div>
                                                    </div>
                                                    <span class="span_dynamic_url" id="span_dynamic_url" name="span_dynamic_url" style="display: none;">{{1}}</span>
                                                </div>

                                                <i class="fas fa-times" onclick="removeButtonType(this)"></i>
                                            </div>
                                            <div class="alert-field-validation" id="alert_cta_button" style="bottom: -12px; left: 35px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3" id="div_quick_answer" style="display: none;">
                                <div class="col-12 container_quick_answer">

                                    <div class="row mb-3 div_quick_answer_button">
                                        <div class="col-4">
                                            <label for="text_quick_answer_button" class="form-control-label"><?php echo $this->lang->line("template_button_text_label") ?></label>
                                            <input class="form-control input-counter" type="text" id="text_quick_answer_button" name="text_quick_answer_button" maxlength="25" placeholder="<?php echo $this->lang->line("template_button_text_placeholder") ?>">
                                            <i class="fas fa-times" onclick="removeButtonQuickAnswer(this)"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert-field-validation" id="alert_text_quick_reply_button" style="bottom: 35px; left: 15px;"></div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <button class="btn btn-default btn-sm" id="add_new_button" style="display: none;"><?php echo $this->lang->line("template_button_add_button") ?></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>templates"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("template_btn_cancel") ?></button></a>
                            <button class="btn btn-success" type="button" id="btn_submit"><?php echo $this->lang->line("template_btn_save") ?></button>
                            <div class="alert-field-validation" id="alert_input_general_request"></div>
                        </div>
                    </div>

                    <div class="row" style="<?= isset($apiError) ? 'display: block' : 'display: none' ?>; margin-top: 10px;">
                        <div class="col-lg-12">
                            <span style="color:red; font-size:11px;"><?= $this->lang->line("template_api_validation") ?></span>
                        </div>
                    </div>

                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="div_loading_req" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="loading-req">
            <img src="<?php echo base_url("assets/img/loads/loading_1.gif") ?>" alt="">
        </div>
    </div>
</div>