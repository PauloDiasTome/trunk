<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_add_title") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart('publication/whatsapp/broadcast/newsletter/save', 'id="myform"'); ?>
                <div class="card-body">
                    <input type="hidden" id="type_broadcast" name="type_broadcast">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_title"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_title") ?></label>
                                <input type="text" class="form-control" name="input_title" id="input_title" maxlength="100" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_newsletter_title") ?>">
                                <div class="alert-field-validation" id="alert__input_title" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_date_scheduling") ?></label>
                                <input type="text" class="form-control datepicker date_start" maxlength="10" name="date_start" id="date_start" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_newsletter_date_scheduling_placeholder") ?>" value="<?php echo $date_start != "" ? $date_start : date('d/m/Y'); ?>">
                                <div class="alert-field-validation" id="alert_date_start" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_check_date") ?></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_hour_scheduling") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start" id="time_start" maxlength="5" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_newsletter_hour_scheduling_placeholder") ?>" value="<?php echo $time_start != "" ? $time_start : date('H:i'); ?>">
                                    <input type="hidden" class="form-control" id="timeStart" value="<?php echo isset($time_start); ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                                <?php echo form_error('time_start', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert__time_start" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="channel_id"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_select_channel") ?></label><br>
                                <div id="myselect">
                                    <select id="multiselect" name="select_segmented_newsletter[]" multiple="multiple" style="display:none">
                                        <?php foreach ($Channels as $value) : ?>
                                            <?php
                                            $disabled = ($value['status'] != 1) ? 'disabled' : '';
                                            $tooltip = ($value['status'] != 1) ? ' title="' . $this->lang->line("blocked_channel_message") . '"' : '';
                                            ?>
                                            <option value="<?php echo $value['id_newsletter']; ?>" <?php echo $disabled; ?><?php echo $tooltip; ?> data-status="<?php echo $value['status']; ?>"><?php echo $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="alert-field-validation" id="alert__multiselect" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="menu_type" style="display:none;">
                        <div class="col-12 mb-3">
                            <div class="menu-type">
                                <div class="box-img-buttom" id="button_media">
                                    <img src="<?php echo base_url("/assets/img/statusImage.png"); ?>" alt="image">
                                </div>
                                <div class="box-img-buttom" id="button_text">
                                    <img src="<?php echo base_url("/assets/img/statusText.png"); ?>" alt="image">
                                </div>
                                <?php if (false) : ?>
                                    <div class="box-img-buttom poll" id="button_poll">
                                        <img src="<?php echo base_url("/assets/icons/panel/poll.svg"); ?>" alt="image">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="select_type">
                        <div class="col-6" id="status_image">
                            <div class="form-group">
                                <div class="box-img">
                                    <div class="container">
                                        <div class="text">
                                            <span><?php echo $this->lang->line("whatsapp_broadcast_newsletter_type_photo") ?></span>
                                        </div>
                                        <div class="img">
                                            <img src="<?php echo base_url("assets/img/statusImage.png"); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="alert-field-validation" id="alert_input_img_text" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_alert_input_required") ?></div>
                            </div>
                        </div>

                        <div class="col-6" id="status_text">
                            <div class="form-group">
                                <div class="box-img">
                                    <div class="container">
                                        <div class="text">
                                            <span><?php echo $this->lang->line("whatsapp_broadcast_newsletter_type_text") ?></span>
                                        </div>
                                        <div class="img">
                                            <img src="<?php echo base_url("assets/img/statusText.png"); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (false) : ?>
                            <div class="col-4" id="status_poll">
                                <div class="form-group">
                                    <div class="box-img poll-img">
                                        <div class="container">
                                            <div class="text">
                                                <span><?php echo $this->lang->line("whatsapp_broadcast_newsletter_type_message_poll") ?></span>
                                            </div>
                                            <div class="img">
                                                <img src="<?php echo base_url("/assets/icons/panel/poll.svg"); ?>" alt="image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row" id="type_text" style="display:none;">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input-data"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_message") ?> <span style="color: red" id="count_caracter">1024</span></label>
                                <textarea class="form-control" name="input-data" id="input-data" rows="7" resize="none" maxlength="1024" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_newsletter_status_message_placeholder") ?>"></textarea>
                                <div class="alert-field-validation" id="alert__input-data" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="type_media" style="display:none;">
                        <div class="col-lg-12 form-group" id="input_files">
                            <div class="input-file">
                                <input type="file" id="fileElem" multiple accept="image/*,.mp4" style="display:none" onchange="handleFiles(this.files)">
                            </div>
                            <a href="#" id="fileSelect">
                                <div class="input-container" id="">
                                    <div class="header">
                                        <div class="title">
                                            <span><?php echo $this->lang->line("whatsapp_broadcast_newsletter_loading_arquive") ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div class="drop-broadcast" id="dropBrodcast" multiple ondrop="dropHandler(event);" ondragover="dragOverHandler(event);">
                                <div class="drop-inner-img">
                                    <img class="drop-icon" src="<?php echo base_url("assets/img/panel/image.png") ?>" alt="">
                                </div>
                                <div class="drop-inner-title">
                                    <b><?php echo $this->lang->line("whatsapp_broadcast_newsletter_title_drop") ?></b>
                                </div>
                                <div class="drop-inner-text">
                                    <span><?php echo $this->lang->line("whatsapp_broadcast_newsletter_subtitle_drop") ?></span>
                                </div>
                            </div>
                            <span class="alert-field-validation" id="alert_upload_media" style="display:none"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_validation_file")  ?></span>
                        </div>
                    </div>

                    <div class="row" id="type_message_poll" style="display:none;">
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

                    <div class="row">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast/newsletter"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_btn_cancel") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("whatsapp_broadcast_newsletter_btn_save") ?></button>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.1/Sortable.min.js"></script>