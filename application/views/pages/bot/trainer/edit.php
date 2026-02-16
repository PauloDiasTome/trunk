<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("setting_bot_trainer_edit_title") ?></h3>
                        </div>
                    </div>
                </div>
                <?php echo form_open(isset($id_nav) ? "bot/trainer/save-access/$id" : "bot/trainer/save/$id"); ?>
                <input type="hidden" id="chatbot_type" name="chatbot_type">
                <input type="hidden" id="edit" name="edit" value="<?php echo htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="card-body">
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("setting_bot_trainer_edit_information") ?></h6>
                    <div class="row">

                        <?php if (isset($id_nav)) : ?>
                            <input type="hidden" name="id_submenu" id="id_submenu" value="<?= $id_nav ?>">
                        <?php endif; ?>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-control-label" for="input_option"><?php echo $this->lang->line("setting_bot_trainer_option") ?></label>
                                <input type="text" class="form-control" name="input_option" id="input_option_bot" aria-describedby="Options" placeholder="<?php echo $this->lang->line("setting_bot_trainer_option_placeholder") ?>" value="<?php echo isset($_POST["input_option"]) ? $_POST["input_option"] : $data['option']; ?>">
                                <?php echo form_error('input_option', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert__input_option_bot" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <label class="form-control-label" for="input_content"><?php echo $this->lang->line("setting_bot_trainer_text") ?></label>
                                <input type="text" class="form-control" name="input_content" id="input_content" aria-describedby="Texto" maxlength="100" placeholder="<?php echo $this->lang->line("setting_bot_trainer_text_placeholder") ?>" value="<?php echo isset($_POST["input_content"]) ? $_POST["input_content"] : $data['description']; ?>">
                                <?php echo form_error('input_content', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert__input_content" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <div id="label_choose_type">
                        <label class="form-control-label"><?php echo $this->lang->line("setting_bot_trainer_add_choose_type") ?></label>
                    </div>

                    <div class="row hidden" id="menu_type">
                        <div class="col-lg-12">
                            <div class="choose-type-icon">
                                <div class="box-icon choose-text" id="choose_text">
                                    <img src="<?php echo base_url("assets/img/statusText.png"); ?>" alt="">
                                </div>
                                <div class="box-icon choose-media" id="choose_media">
                                    <img src="<?php echo base_url("assets/img/statusImage.png"); ?>" alt="">
                                </div>
                                <div class="box-icon choose-contact" id="choose_contact">
                                    <img src="<?php echo base_url("assets/img/chatbot-contact.png"); ?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row <?php echo isset($data['media_type']) ? 'hidden' : ''; ?>">
                        <div class="col-4" id="text_option">
                            <div class="form-group">
                                <div class="box-img">
                                    <div class="container">
                                        <div class="text">
                                            <span><?php echo $this->lang->line("setting_bot_trainer_add_text_option") ?></span>
                                        </div>
                                        <div class="img">
                                            <img src="<?php echo base_url("assets/img/statusText.png"); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="alert-field-validation hidden" id="alert_input_chatbot_option"><?php echo $this->lang->line("setting_bot_trainer_required_field") ?></div>
                                <?php echo form_error('chatbot_type', '<div class="alert-field-validation">', '</div>'); ?>
                                <?php echo form_error('file_hidden', '<div class="alert-field-validation">', '</div>'); ?>
                                <?php echo form_error('input_text', '<div class="alert-field-validation">', '</div>'); ?>
                                <?php echo form_error('input_name', '<div class="alert-field-validation">', '</div>'); ?>
                                <?php echo form_error('input_phone', '<div class="alert-field-validation">', '</div>'); ?>
                                <?php echo form_error('media_description', '<div class="alert-field-validation">', '</div>'); ?>
                            </div>
                        </div>
                        <div class="col-4" id="media_option">
                            <div class="form-group">
                                <div class="box-img">
                                    <div class="container">
                                        <div class="text">
                                            <span><?php echo $this->lang->line("setting_bot_trainer_add_media_option") ?></span>
                                        </div>
                                        <div class="img">
                                            <img src="<?php echo base_url("assets/img/statusImage.png"); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4" id="contact_option">
                            <div class="form-group">
                                <div class="box-img">
                                    <div class="container">
                                        <div class="text">
                                            <span><?php echo $this->lang->line("setting_bot_trainer_add_contact_option") ?></span>
                                        </div>
                                        <div class="img">
                                            <img src="<?php echo base_url("assets/img/chatbot-contact.png"); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 hidden" id="text_data"></div>
                    </div>

                    <div class="row">
                        <div class="input-file">
                            <input type="file" id="fileElem" multiple accept="image/*,.mp4,.pdf,.ogg" style="display:none" onchange="handleFiles(this.files)">
                        </div>

                        <div class="col-lg-12 hidden" id="input_files">
                            <a href="#" id="fileSelect">
                                <div class="input-container" id="">
                                    <div class="header">
                                        <div class="title">
                                            <span><?php echo $this->lang->line("setting_bot_trainer_add_loading_arquive") ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <input type="hidden" id="file-name" name="file_name" />
                    <div class="row">
                        <div class="col-lg-12 form-group hidden" id="drop_chatbot"></div>
                    </div>

                    <div class="row" id="contact_chatbot"></div>

                    <div class="row">
                        <div class="col-lg-12 transfer-sector">
                            <label class="custom-toggle mb-2">
                                <input type="checkbox" id="sector_toggle" name="sector_toggle" <?php echo isset($data['id_user_group']) ? 'checked' : ''; ?>>
                                <span class="custom-toggle-slider" data-label-off="<?php echo $this->lang->line("setting_bot_trainer_no") ?>" data-label-on="<?php echo $this->lang->line("setting_bot_trainer_yes") ?>"></span>
                            </label>
                            <label class="form-control-label"><?php echo $this->lang->line("setting_bot_trainer_sector_change") ?></label>
                        </div>
                        <div class="col-lg-12" style="<?php echo isset($data['id_user_group']) ? 'display: block;' : 'display: none;'; ?>" id="sector_select">
                            <label class="form-control-label" for="selected_sector"><?php echo $this->lang->line("setting_bot_trainer_sector_select") ?></label>
                            <select class="form-control" name="selected_sector">
                                <?php foreach ($submenu as $row) { ?>
                                    <option value="<?php echo $row['id_user_group']; ?>" <?php echo isset($data['id_user_group']) && $row['id_user_group'] == $data['id_user_group'] ? "Selected" : ""; ?>>
                                        <?php echo $row['name']; ?> </option>
                                <?php  } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 15px;">
                        <div class="col-lg-8">
                            <a id="back_nivel" class="btn btn-primary" type="button"><?php echo $this->lang->line("setting_bot_trainer_btn_return") ?></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("setting_bot_trainer_btn_save") ?></button>
                        </div>

                        <script>
                            (() => {
                                let nivel_access = localStorage.getItem('nivel');

                                if (nivel_access === null) {
                                    localStorage.setItem('nivel', 0);
                                }

                                let nivel_to_back = '/' + localStorage.getItem('nivel');

                                if (localStorage.getItem('nivel') == 0) {
                                    nivel_to_back = '';
                                }

                                document.querySelector('#back_nivel').href = `<?php echo base_url(); ?>bot/trainer${nivel_to_back}`;
                            })();
                        </script>
                    </div>

                    <div class="row" style="margin-top: 10px">
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>