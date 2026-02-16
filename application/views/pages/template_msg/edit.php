<input type="hidden" id="verifyEdit" value="true">
<div class="container mt--6">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("template_edit_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("templates/updateName"); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("template_edit_information") ?></h6>
                    <input type="hidden" id="id_template" name="id_template" value="<?php echo $id ?>">

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
                                <select class="form-control" name="channel" id="channel" disabled>
                                    <option value=""><?= $channel_name ?></option>
                                </select>
                            </div>
                            <input type="text" id="channel_type" name="channel_type" value="<?= $channel_type ?>" hidden>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <input type="hidden" value="<?= isset($category) ? $category : '' ?>" id="categoryRecover">
                                <label class="form-control-label" for="category"><?php echo $this->lang->line("template_column_category") ?></label>
                                <select class="form-control" disabled id="category" name="category">
                                    <option value="<?= $category ?>"><?= $category ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label class="form-control-label" for="language"><?php echo $this->lang->line("template_lang") ?></label>
                                <select class="form-control" disabled id="languageSelect" name="language">
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
                                    <select class="form-control" name="select_header" id="select_header" disabled>
                                        <option value="<?= isset($header_type_option_value) ? $header_type_option_value : '' ?>"><?= isset($header_option_text) ? $header_option_text : $this->lang->line("template_select_option_none") ?></option>
                                    </select>
                                </div>
                                <div class="col-9" id="div_text_header" style="display: none;">
                                    <input class="form-control" id="text-header" name="text-header" type="text" maxlength="60" disabled placeholder="<?php echo $this->lang->line("template_header_placeholder") ?>" value="<?= isset($header) ? $header : '' ?>">
                                    <div class="alert-field-validation" id="alert_input_header"></div>
                                </div>
                            </div>

                            <div class="row" id="div_media_header" style="display: none;">
                                <div class="col-3 pr-5 mr--5">
                                    <div class="div_media_radio_edit" id="div_media_image">
                                        <div class="custom-control custom-radio custom-control">
                                            <input class="custom-control-input" type="radio" name="media_header" id="media_header_image" value="IMAGE">
                                        </div>
                                        <i class="far fa-image fa-3x"></i>
                                        <span class="header_media_label"><?php echo $this->lang->line("template_header_media_image") ?></span>
                                    </div>
                                </div>
                                <div class="col-3 pr-5 mr--5">
                                    <div class="div_media_radio_edit" id="div_media_video">
                                        <div class="custom-control custom-radio custom-control">
                                            <input class="custom-control-input" type="radio" name="media_header" id="media_header_video" value="VIDEO">
                                        </div>
                                        <i class="far fa-play-circle fa-3x"></i>
                                        <span class="header_media_label"><?php echo $this->lang->line("template_header_media_video") ?></span>
                                    </div>
                                </div>
                                <div class="col-3 pr-5 mr--5">
                                    <div class="div_media_radio_edit" id="div_media_document">
                                        <div class="custom-control custom-radio custom-control">
                                            <input class="custom-control-input" type="radio" name="media_header" id="media_header_document" value="DOCUMENT">
                                        </div>
                                        <i class="far fa-file-alt fa-3x"></i>
                                        <span class="header_media_label"><?php echo $this->lang->line("template_header_media_document") ?></span>
                                    </div>
                                </div>
                                <div class="alert-field-validation" id="alert_input_media" style="bottom: -18px; left: 15px;"></div>
                            </div>
                            <input type="hidden" id="header_media_input_value" value="<?= isset($header_type) ? $header_type : '' ?>">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12">
                            <h3><?php echo $this->lang->line("template_body_label") ?></h3>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="text-body"><?php echo $this->lang->line("template_body_description") ?> <span style="color: red"><?php echo $this->lang->line("template_number") ?></span></label>
                                        <textarea class="form-control" id="text-body" name="text-body" rows="7" resize="none" maxlength="1024" disabled><?= isset($text_body) ? $text_body : '' ?></textarea>
                                        <div class="alert-field-validation" id="alert_input_body"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <h3><?php echo $this->lang->line("template_footer_label") ?></h3>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <input class="form-control" type="text" id="text-footer" name="text-footer" maxlength="60" disabled placeholder="<?php echo $this->lang->line("template_footer_placeholder") ?>" value="<?= isset($text_footer) ? $text_footer : '' ?>">
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
                                    <?php if ($buttons == '') { ?>
                                        <select class="form-control" name="select_button" id="select_button" disabled>
                                            <option value=""><?php echo $this->lang->line("template_select_option_none") ?></option>
                                        </select>
                                    <?php } else { ?>
                                        <?php for ($i = 0; $i < count($buttons); $i++) { ?>
                                            <button class="btn btn-default" type="button" disabled><?= $buttons[$i]->text ?></button>
                                    <?php }
                                    } ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>templates"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("template_btn_return") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("template_btn_save") ?></button>
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