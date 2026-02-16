<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("replies_add_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("replies/save"); ?>
                    <div style="display: none">
                        <input type="hidden" name="type_reply" id="type_reply" value="">
                        <input type="hidden" name="type_view" id="type_view" value="add">
                    </div>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("replies_add_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-title"><?php echo $this->lang->line("replies_title") ?></label>
                                    <input type="text" id="input-title" name="input-title" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("replies_title_placeholder") ?>" />
                                    <?php echo form_error('input-title', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_input_title"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-tag"><?php echo $this->lang->line("replies_tag") ?></label>
                                    <input type="text" id="input-tag" name="input-tag" class="form-control" maxlength="25" placeholder="<?php echo $this->lang->line("replies_tag_placeholder") ?>" />
                                    <?php echo form_error('input-tag', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_input_tag"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" id="label_chose_type" style="margin-bottom: 0px;">
                                    <label class="form-control-label" for=""><?php echo $this->lang->line("replies_choose_type") ?></label>
                                </div>

                                <div class="d-flex flex-row-reverse change_type_icon">
                                    <div class="change_type_icon change_type_icon_img choose_media" id="change_type_icon_img">
                                        <img src="<?php echo base_url("assets/img/statusImage.png"); ?>" alt="">
                                    </div>
                                    <div class="change_type_icon change_type_icon_text choose_text" id="change_type_icon_text">
                                        <img src="<?php echo base_url("assets/img/statusText.png"); ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-6" id="option_media" style="display: block;">
                                <div class="form-group">
                                    <div class="box-img">
                                        <div class="container">
                                            <div class="text">
                                                <span><?php echo $this->lang->line("replies_type_photo") ?></span>
                                            </div>
                                            <div class="img">
                                                <img src="<?php echo base_url("assets/img/statusImage.png"); ?>" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert-field-validation" id="alert_input_type_reply"></div>
                                </div>
                            </div>

                            <div class="col-6" id="option_text" style="display: block;">
                                <div class="form-group">
                                    <div class="box-img">
                                        <div class="container">
                                            <div class="text">
                                                <span><?php echo $this->lang->line("replies_type_text") ?></span>
                                            </div>
                                            <div class="img">
                                                <img src="<?php echo base_url("assets/img/statusText.png"); ?>" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row" id="status_data" style="display: none;">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-content"><?php echo $this->lang->line("replies_text") ?></label>
                                    <textarea class="form-control" id="text-area-content" rows="7" resize="none" maxlength="4096" placeholder="<?php echo $this->lang->line("replies_answer_placeholder") ?>"></textarea>
                                    <input type="hidden" name="input-content" id="input-content" value="">
                                    <?php echo form_error('input-content', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_input_text"></div>
                                </div>
                            </div>
                        </div>

                        <div class="input-file">
                            <input type="file" id="fileElem" multiple accept="image/*,.mp4,.pdf,.ogg" style="display:none" onchange="handleFiles(this.files)">
                        </div>

                        <div class="row">
                            <div class="col-lg-12" id="input_files" style="display: none">
                                <a href="#" id="fileSelect">
                                    <div class="input-container" id="">
                                        <div class="header">
                                            <div class="title">
                                                <span><?php echo $this->lang->line("replies_loading_arquive") ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="drop-broadcast" id="dropBrodcast" style="display: none" multiple ondrop="dropHandler(event);" ondragover="dragOverHandler(event);">
                                    <div class="drop-inner-img">
                                        <img class="drop-icon" src="<?php echo base_url("assets/img/panel/image.png") ?>" alt="">
                                    </div>
                                    <div class="drop-inner-title">
                                        <b><?php echo $this->lang->line("replies_title_drop") ?></b>
                                    </div>
                                    <div class="drop-inner-text">
                                        <span><?php echo $this->lang->line("replies_subtitle_drop") ?></span>
                                    </div>
                                </div>
                                <div class="alert-field-validation" id="alert_input_media"></div>
                                <?php echo form_error('file', '<div class="alert-status">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="form-control-label" for="input-content"><?php echo $this->lang->line("replies_quick_answer") ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="custom-control custom-radio mb-3">
                                    <input class="custom-control-input" name="private" id="private" type="radio">
                                    <label class="custom-control-label" for="private"><?php echo $this->lang->line("replies_quick_answer_radio_yes") ?></label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="custom-control custom-radio mb-3">
                                    <input class="custom-control-input" name="public" id="public" type="radio" checked>
                                    <label class="custom-control-label" for="public"><?php echo $this->lang->line("replies_quick_answer_radio_no") ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>replies"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("replies_btn_cancel") ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("replies_btn_save") ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>