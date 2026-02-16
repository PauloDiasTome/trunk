<div class="container mt--5">
    <div class="row">

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("tv_broadcast_add_title") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart('publication/tv/broadcast/save', 'id="myform"'); ?>
                <div class="card-body">
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("tv_broadcast_add_information") ?></h6>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_title"><?php echo $this->lang->line("tv_broadcast_title") ?></label>
                                <input type="text" class="form-control" name="input_title" id="input_title" maxlength="100" placeholder="<?php echo $this->lang->line("tv_broadcast_title") ?>">
                                <div class="alert-field-validation" id="alert__input_title"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="datetime_start"><?php echo $this->lang->line("tv_broadcast_date_scheduling") ?></label>
                                <input type="datetime-local" class="form-control" min="4" name="datetime_start" id="datetime_start" value="2024-04-12T12:00">
                                <div class="alert-field-validation" id="alert__datetime_start"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="datetime_end"><?php echo $this->lang->line("tv_broadcast_date_end") ?></label>
                                <input type="datetime-local" class="form-control" min="4" name="datetime_end" id="datetime_end" value="2024-04-12T12:00">
                                <div class="alert-field-validation" id="alert__datetime_end"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="channel_id"><?php echo $this->lang->line("tv_broadcast_select_channel") ?></label><br>
                                <div id="myselect">
                                    <select id="multiselect" name="others[]" multiple="multiple" style="display:none">
                                        <?php foreach ($channels as $row) { ?>
                                            <option value="<?php echo $row['id_channel']; ?>"> <?php echo $row['name'] ?> </option>
                                        <?php  } ?>
                                    </select>
                                    <div class="alert-field-validation" id="alert__multiselect"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="input-file">
                        <input type="file" id="file-elm" multiple accept="image/*,.mp4" style="display:none;" onchange="handleFiles(this.files)">
                    </div>
                    <div class="row">
                        <div class="col-lg-12" id="input_files">
                            <a href="#" id="file-select">
                                <div class="input-container" id="">
                                    <div class="header">
                                        <div class="title">
                                            <span><?php echo $this->lang->line("tv_broadcast_loading_arquive"); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="alert-field-validation" id="alert__input_file" style="display:none"><?php echo $this->lang->line("tv_broadcast_validation_input_file") ?></div>

                    <div class="row" id="row-preview" style="display:none">
                        <div class="col-lg-12">
                            <div class="container-preview">
                                <div class="preview" id="box-preview" data-toggle="modal" data-target="#modal-preview">
                                    <img class="video" src="/assets/img/video1.png" alt="image">
                                    <span><?php echo $this->lang->line("tv_broadcast_watch_preview"); ?></span>
                                </div>

                                <div class="edit" id="box-edit" data-toggle="modal" data-target="#modal-upload">
                                    <img class="edit" src="/assets/img/editar1.png" alt="image">
                                    <span><?php echo $this->lang->line("tv_broadcast_edit_preview"); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>publication/tv/broadcast"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("tv_broadcast_btn_cancel") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("tv_broadcast_btn_save") ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-preview" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-slider" id="container-slider"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-upload" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <div class="space-upload">
                    <div class="input-files">
                        <input type="file" id="file-elm-modal" multiple accept="image/*,.mp4" style="display:none;" onchange="handleFiles(this.files)">
                    </div>

                    <div class="row">
                        <div class="col-lg-12" id="input_files">
                            <a href="#" id="file-select-modal">
                                <div class="input-container mb-3">
                                    <div class="header">
                                        <div class="title">
                                            <span><?php echo $this->lang->line("tv_broadcast_loading_arquive"); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-12">
                            <div class="drop-broadcast" id="dropBrodcast" multiple ondrop="dropHandler(event);" ondragover="dragOverHandler(event);">
                                <div class="drop-group-desc">
                                    <div class="drop-inner-img">
                                        <img class="drop-icon" src="<?php echo base_url("assets/img/panel/image.png") ?>" alt="image">
                                    </div>
                                    <div class="drop-inner-title">
                                        <b><?php echo $this->lang->line("tv_broadcast_title_drop") ?></b>
                                    </div>
                                    <div class="drop-inner-text">
                                        <span><?php echo $this->lang->line("tv_broadcast_subtitle_drop") ?></span>
                                    </div>
                                </div>
                                <div class="drop-group-slider" style="display:none;">
                                    <ul class="slider" id="slider"></ul>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="group">
                                <div class="info">
                                    <div class="duration">
                                        <strong id="totalDuration">0</strong>
                                        <span><?php echo $this->lang->line("tv_broadcast_drop_grup_duration") ?></span>
                                    </div>
                                    <div class="total-files">
                                        <strong id="totalImage">0</strong>
                                        <span><?php echo $this->lang->line("tv_broadcast_drop_grup_arquive") ?></span>
                                    </div>
                                </div>
                                <div class="buttons">
                                    <button class="btns cancel" data-dismiss="modal"><?php echo $this->lang->line("tv_broadcast_btn_cancel") ?></button>
                                    <button class="btns finish"><?php echo $this->lang->line("tv_broadcast_btn_finished") ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row space-load">
                    <div class="text">
                        <span><?php echo $this->lang->line("tv_broadcast_loading_preview_text") ?></span>
                    </div>
                    <div class="progress" id="progress-main" style="">
                        <div class="light-effect"></div>
                        <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>