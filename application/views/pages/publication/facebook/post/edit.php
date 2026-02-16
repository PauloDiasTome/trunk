<input type="hidden" name="statusBlock" id="statusBlock" value="<?= isset($view[0]['status']) ? $view[0]['status'] : 0 ?>">
<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">
                                <?php

                                use OAuth2\Encryption\FirebaseJwt;

                                echo $this->lang->line("facebook_post_edit_title");
                                if (isset($view[0]['status']) != 0) {
                                ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart('publication/facebook/post/save', 'id="myform"'); ?>
                <div class="card-body">

                    <input type="hidden" id="verify-edit" value="<?php echo isset($view) ? "edit" : "" ?>">
                    <input type="hidden" id="verify-view" value="<?php echo isset($view) ? "view" : "" ?>">
                    <input type="hidden" name="direction" id="direction" value="1">

                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("facebook_post_add_information") ?></h6>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_title"><?php echo $this->lang->line("facebook_post_title") ?></label>
                                <input type="text" class="form-control" name="input_title" id="input_title" maxlength="50" placeholder="<?php echo $this->lang->line("facebook_post_title") ?>" value="<?php echo $view[0]['title'] ?>">
                                <?php echo form_error('input_title', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_input_title"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start"><?php echo $this->lang->line("facebook_post_date_scheduling") ?></label>
                                <input type="text" class="form-control datepicker" name="date_start" id="date_start" maxlength="10" placeholder="<?php echo $this->lang->line("facebook_post_date_scheduling_placeholder") ?>" value="<?php echo explode(' ', $view[0]['schedule'])[0] ?>">
                                <?php echo form_error('date_start', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_date_start"></div>
                            </div>
                        </div>

                        <div class=" col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start"><?php echo $this->lang->line("facebook_post_hour_scheduling") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start" id="time_start" maxlength="5" value="<?php echo explode(' ', $view[0]['schedule'])[1] ?>">
                                    <input type="hidden" class="form-control" id="timeStart">
                                </div>
                                <?php echo form_error('time_start', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_time_start"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="channel_id"><?php echo $this->lang->line("facebook_post_select_channel") ?></label><br>
                                <div id="myselect">
                                    <select id="multiselect" name="others[]" multiple="multiple" style="display:none">
                                        <?php foreach ($channel as $row => $val) { ?>
                                            <option value="<?php echo $val['id_channel']; ?>" <?= $view[$row]['id_channel'] == $val['id_channel'] ? "selected" : '' ?>><?php echo $val["name"] ?> </option>
                                        <?php  } ?>
                                    </select>
                                    <?php echo form_error('others[]', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_multiselect"></div>
                                </div>
                            </div>
                            <div id="list_page" style="display: none;">
                                <?php foreach ($channel as $row) { ?>
                                    <input type="hidden" class="<?php echo $row["name"]; ?>" id="<?php echo $row["id"]; ?>" value="<?php echo $row["picture"] ?>">
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="input_data"><?php echo $this->lang->line("facebook_post_message") ?></label> (<label class="count-caracters" id="count_caracter">0</label>)
                                <textarea class="form-control input_data" name="input_data" id="input_data" rows="3" placeholder="<?php echo $this->lang->line("facebook_post_message_placeholder") ?>" maxlength="1024" resize="none"><?php echo $view[0]['data'] ?></textarea>
                                <?php echo form_error('input_data', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_input_data"></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="box-post">
                                <div class="container-preview-post">
                                    <div class="main-pw">
                                        <div class="preview-post">
                                            <div class="preview-post-header">
                                                <div class="profile">
                                                    <img src="<?php echo base_url("assets/img/panel/facebook.png"); ?>" alt="profile" id="picture_profile_page">
                                                </div>
                                                <div class="name-page" id=name_profile_page>
                                                    <span>Facebook</span>
                                                </div>
                                                <div class="time-publication">
                                                    <span>Agora mesmo</span>
                                                </div>
                                            </div>
                                            <div class="preview-post-description">
                                                <p id="description-post"></p>
                                            </div>
                                            <div class="preview-post-body">
                                                <div class="box-slider" id="box__slider">
                                                    <i class="fas fa-chevron-circle-right arrow-right" id="arrow-right"></i>
                                                    <i class="fas fa-chevron-circle-left arrow-left" id="arrow-left"></i>
                                                    <div class="background-no-picture">
                                                        <img src="<?php echo base_url("assets/img/panel/background_no_picture.jpg"); ?>" alt="" style="opacity:1;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="preview-post-footer">
                                                <img src="<?php echo base_url("assets/img/panel/footer_preview_facebook.png") ?>" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-file">
                            <input type="file" id="fileElem" multiple accept=".jpg, .jpeg, .mp4" style="display:none;" onchange="handleFiles(this.files)">
                        </div>

                        <div class="col-lg-6" id="input_files">
                            <a href="#" id="fileSelect">
                                <div class="input-files">
                                    <span><?php echo $this->lang->line("facebook_post_loading_arquive") ?></span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="row dp_row">
                        <div class="col-6">
                            <div class="drop-area" id="dropArea" multiple ondrop="dropHandler(event);" ondragover="dragOverHandler(event);">
                                <div class="drop-inner-img">
                                    <img class="drop-img" src="<?php echo base_url("assets/img/panel/image.png") ?>" alt="new image">
                                </div>
                                <div class="drop-inner-title">
                                    <strong><?php echo $this->lang->line("facebook_post_title_drop") ?></strong>
                                </div>
                                <div class="drop-inner-text">
                                    <span><?php echo $this->lang->line("facebook_post_subtitle_drop") ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php

                                    if ($view[0]['media_url'] != null) {
                                        foreach ($view as $row => $value) { ?>

                            <div class="recover">
                                <input type="hidden" class="thumbnail" value="<?php echo $value['thumb_image'] ?>" id="thumbnail">
                                <input type="hidden" class="url" value="<?php echo $value['media_url'] ?>" id="url">
                                <input type="hidden" class="media_type" value="<?php echo $value['media_type'] ?>" id="media_type">
                            </div>

                    <?php

                                        }
                                    }

                    ?>

                    <div class="alert-field-validation" id="alert_dropArea"></div>
                    <?php echo form_error('alert_drop', '<div class="alert-field-validation">', '</div>'); ?>

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>publication/facebook/post"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("facebook_post_btn_cancel") ?></button></a>
                            <!-- <button class="btn btn-success" type="submit"><?php echo $this->lang->line("facebook_post_btn_save") ?></button> -->
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php } else { ?>

    <div class="modal fade" id="modal-no-registers" tabindex="-1" role="dialog" aria-labelledby="modal-no-registers" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header b-bottom text-center">
                    <h6 class="modal-title w-100" id="modal-title-notification"><?php echo $this->lang->line("facebook_post_approval_no_records_modal_title") ?></h6>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            <img src="https://app.talkall.com.br/assets/img/broadcast_approval/Warning-Computer-TalkAll.png" class="mb-4" alt="approval" width="80px">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-center font-weight-normal"><?php echo $this->lang->line("facebook_post_approval_no_records_modal_message") ?></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php }  ?>