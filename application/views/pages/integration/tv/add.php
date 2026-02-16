<div class="container mt--5 integration-tv-screen">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12 text-center">
                            <h3 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_add_tv_new"); ?></h3>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="connection-code">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="step-progress">
                                <ul class="step-progress-list">
                                    <li class="step-progress-item current-item">
                                        <span class="progress-count">1</span>
                                        <span class="progress-label"><?php echo $this->lang->line("setting_integration_tv_label_registration"); ?></span>
                                    </li>
                                    <li class="step-progress-item">
                                        <span class="progress-count">2</span>
                                        <span class="progress-label"><?php echo $this->lang->line("setting_integration_tv_label_conclude"); ?></span>
                                    </li>
                                    <li class="step-progress-item" style="display: none">
                                        <span class="progress-count">2</span>
                                        <span class="progress-label"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row registration">
                        <div class="col-7">
                            <div class="form-group">
                                <label class="form-control-label" for="tv"><?php echo $this->lang->line("setting_integration_tv_label_name"); ?></label>
                                <input type="text" class="form-control" name="input-name" id="input-name" placeholder="<?php echo $this->lang->line("setting_integration_tv_placeholder_name"); ?>">
                                <div class="alert-field-validation" id="alert__input-name" style="display: none;"></div>
                            </div>

                            <div class="form-group">
                                <label class="form-control-label" for=""><?php echo $this->lang->line("setting_integration_tv_label_position"); ?></label>
                                <div class="form-group container-position">
                                    <div class="box-position">
                                        <input type="radio" class="position" name="position" id="landscape" value="landscape" checked>
                                        <label class="form-control-label" for="landscape"><?php echo $this->lang->line("setting_integration_tv_label_landscape"); ?></label>
                                    </div>
                                    <div class="box-position">
                                        <input type="radio" class="position" name="position" id="portrait" value="portrait">
                                        <label class="form-control-label" for="portrait"><?php echo $this->lang->line("setting_integration_tv_label_portrait"); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-5">
                            <div class="img-info">
                                <label class="form-control-label" for=""><?php echo $this->lang->line("setting_integration_tv_label_choose_img"); ?></label>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $this->lang->line("setting_integration_tv_tooltip_img") ?>"></i>
                            </div>

                            <div class="box-picture-default">
                                <div class="box-img tv-box-shadow">
                                    <img src="<?php echo base_url("assets/img/upload_img.png") ?>" alt="preview">
                                    <input type="file" id="inputFile" accept="image/png, image/gif, image/jpeg, image/jpg, image/jfif" data-file="picture" onchange="handleFiles(this)" style="display: none;" />
                                    <input type="hidden" name="input-picture" id="input-picture" />
                                    <i class="fas fa-camera icon-add-photo" id="addProfile" style="display: none"></i>
                                    <span class="picture-profile-title text-uppercase" style="display: none;"><?php echo $this->lang->line("setting_integration_add_tv_change_img"); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-7 mt-5">
                            <div class="form-group">
                                <a href="<?php echo base_url(); ?>integration/add"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("setting_integration_add_tv_btn_cancel") ?></button></a>
                                <button type="button" id="btn_save_tv" class="btn btn-success"><?php echo $this->lang->line("setting_integration_add_tv_btn_save") ?></button>
                            </div>
                        </div>
                    </div>

                    <div class="row conclude hidden">
                        <div class="col-6">
                            <div class="form-group steps">
                                <span><?php echo $this->lang->line("setting_integration_add_tv_steps_to_follow") ?></span>
                            </div>

                            <div class="form-group">
                                <span class="span-font"><?php echo $this->lang->line("setting_integration_add_tv_span_access") ?></span><br>
                                <span class="site">app.talkall.com.br/tv</span>
                            </div>

                            <div class="form-group mb-5" id="code-container">
                                <span class="span-font"><?php echo $this->lang->line("setting_integration_add_tv_span_insert_code") ?></span>
                            </div>
                        </div>

                        <div class="col-6 pt-3">
                            <div class="tv-preview tv-box-shadow">
                                <div id="tv">
                                    <div class="left">
                                        <div class="img"></div>
                                    </div>

                                    <div class="right">
                                        <div class="code-container">
                                            <div class="box-text">
                                                <span><?php echo $this->lang->line("setting_integration_add_tv_span_preview_connect") ?></span>
                                                <span><?php echo $this->lang->line("setting_integration_add_tv_span_preview_insert_code") ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="button" id="btn_cancel" class="btn btn-danger"><?php echo $this->lang->line("setting_integration_add_tv_btn_cancel") ?></button>
                            <button type="button" id="btn_conclude" class="btn btn-success"><?php echo $this->lang->line("setting_integration_add_tv_btn_conclude") ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>