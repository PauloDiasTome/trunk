<div class="container mt--5 integration-tv-screen">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12 text-center">
                            <h3 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_add_whatsapp_broadcast"); ?></h3>
                        </div>
                    </div>
                </div>

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

                    <div class="fist-phase">
                        <div class="row">
                            <div class="col-6" id="form-whatsapp">
                                <div class="box-left">
                                    <div class="title">
                                        <span id="title_form">Canal 1</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-name"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_name"); ?></label><span class="required-asterisk">*</span>
                                                <input type="text" id="input-name" name="input-name" maxlength="25" class="form-control _input-custom" placeholder="Ex: Ofertas Roxinho" value="<?php echo $this->lang->line("setting_intergration_broadcast_business_form_name_placeholder"); ?>">
                                                <span class="alert-field-validation" id="alert__input-name"></span>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-phone"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_number"); ?></label><span class="required-asterisk">*</span>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-mobile-alt icon"></i>
                                                    <input type="tel" id="input-phone" name="input-phone" class="form-control _input-custom" maxlength="15" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_form_number_placeholder"); ?>" value="">
                                                    <span class="alert-field-validation" id="alert__input-phone"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-5">
                                            <label class="form-control-label" for="input-file"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_profile"); ?></label><span class="required-asterisk">*</span>
                                            <div class="main-profile">
                                                <div class="_preview">
                                                    <input type="file" id="input-file" name="input-file" placeholder="Numero do telefone" value="" style="display:none;">
                                                    <input type="hidden" name="file" id="file" value="">
                                                    <input type="hidden" name="file" id="file" value="https://files.talkall.com.br:3000/v/202569/11rXYXeYr3HuQdbe0IwU2sBxUqMx82VAoehkhhqR1h2iDvohLJ1aTYddbODQEf8e.jpg">
                                                    <img id="preview_form" src="<?php echo base_url('assets/img/avatar.svg'); ?>" alt="Foto de Perfil">
                                                    <div class="profile-overlay">
                                                        <i class="fas fa-camera"></i>
                                                        <span><?php echo $this->lang->line("setting_intergration_broadcast_business_form_profile_placeholder"); ?></span>
                                                    </div>
                                                </div>
                                                <div class="_upload">
                                                    <div>
                                                        <span><?php echo $this->lang->line("setting_intergration_broadcast_business_form_profile_tooltip"); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="alert-field-validation" id="alert__file"></span>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="textarea-content"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_description"); ?></label><span class="required-asterisk">*</span>
                                                <textarea class="form-control" id="textarea-content" name="textarea-content" rows="2" resize="none" maxlength="139" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_form_description_placeholder"); ?>"></textarea>
                                                <span class="alert-field-validation" id="alert__textarea-content"></span>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-address"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_address"); ?></label>
                                                <input type="text" id="input-address" name="input-address" maxlength="256" class="form-control _input-custom" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_form_address_placeholder"); ?>" value="">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_email"); ?></label>
                                                <input type="text" id="input-email" name="input-email" maxlength="256" class="form-control _input-custom" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_form_email_placeholder"); ?>" value="">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-site"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_site"); ?></label>
                                                <input type="text" id="input-site" name="input-site" maxlength="256" class="form-control _input-custom" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_form_site_placeholder"); ?>" value="">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="groups-buttons">
                                                <div class="button disabled" id="btnDeleteChannel"><?php echo $this->lang->line("setting_intergration_broadcast_business_btn_delete"); ?></div>
                                                <div class="button efect" id="btnDuplicateChannel"><?php echo $this->lang->line("setting_intergration_broadcast_business_btn_duplicate"); ?> </div>
                                                <div class="button efect" id="btnNewChannel"><?php echo $this->lang->line("setting_intergration_broadcast_business_btn_new"); ?></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-6" id="preview-whatsapp">
                                <div class="box-right">
                                    <div class="main-preview">
                                        <div class="_container">
                                            <div class="iphone">
                                                <div class="dynamic-island"></div>
                                                <div class="power-btn"></div>
                                                <div class="volume-buttons"></div>
                                                <div class="screen">
                                                    <div class="whatsapp-header">
                                                        <div class="hour">
                                                            <span id="hourIPhone">00:00</span>
                                                        </div>
                                                        <div class="info-phone">
                                                            <img src="<?php echo base_url('assets/img/panel/info_iphone.PNG'); ?>" alt="image" class="preview_img">
                                                        </div>
                                                    </div>
                                                    <div class="whatsapp-content">
                                                        <div class="top">
                                                            <div class="waba">
                                                                <i class="fas fa-chevron-left"></i>
                                                                <span class="text"><?php echo $this->lang->line("setting_intergration_broadcast_business_preview_title"); ?></span>
                                                                <span></span>
                                                            </div>
                                                            <div class="info-channel">
                                                                <div class="box-avatar">
                                                                    <img id="preview_photo" src="<?php echo base_url('assets/img/avatar.svg'); ?>" alt="image">
                                                                </div>
                                                                <span class="name" id="preview_name"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_name_placeholder"); ?></span>
                                                                <span class="number" id="preview_phone"><?php echo $this->lang->line("setting_intergration_broadcast_business_preview_phone"); ?></span>
                                                            </div>
                                                            <div class="action">
                                                                <div class="share">
                                                                    <img src="<?php echo base_url('assets/icons/panel/whatsapp_share.svg'); ?>" alt="">
                                                                    <span><?php echo $this->lang->line("setting_intergration_broadcast_business_preview_share"); ?></span>
                                                                </div>
                                                                <div class="search">
                                                                    <img src="<?php echo base_url('assets/icons/panel/whatsapp_search.svg'); ?>" alt="">
                                                                    <span><?php echo $this->lang->line("setting_intergration_broadcast_business_preview_search"); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="body">
                                                            <div class="group">
                                                                <textarea id="preview_description" rows="1" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_preview_desciption"); ?>" disabled></textarea>
                                                            </div>
                                                            <div class="group">
                                                                <input id="preview_address" type="text" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_preview_address"); ?>" disabled>
                                                            </div>
                                                            <div class="group">
                                                                <input id="preview_email" type="text" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_preview_email"); ?>" disabled>
                                                            </div>
                                                            <div class="group last">
                                                                <input id="preview_site" type="text" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_preview_site"); ?>" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 mt-3">
                                <div class="_channel-flex" id="channel-flex-left">
                                    <div class=" _channel _selected first__">
                                        <span class="title">Canal 1</span>
                                        <span class="phone"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_name_placeholder"); ?></span>
                                        <span class="name"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_number_placeholder"); ?></span>
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 mt-3">
                                <div class="_channel-flex" id="channel-flex-right"></div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="button-advance">
                                    <button type="button" id="btnBookEvaluation" class="btn btn-primary"><?php echo $this->lang->line("setting_intergration_broadcast_business_btn_bookEvaluation") ?> <i class="fas fa-arrow-right" id="arrow_right_advance"></i><img style="display:none;" class="loadSave" id="load_advance" src="<?php echo base_url('/assets/img/loads/loading_1.gif'); ?>" width="20px" alt="IMG"></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="second-phase" style="display:none;">
                        <div class="row">
                            <div class="col-12">
                                <div class="box-second-phase">
                                    <div class="_main">
                                        <div class="_content">
                                            <h4 class="title"><?php echo $this->lang->line("setting_intergration_broadcast_business_conclusion"); ?></h4><br>
                                            <span class="text">
                                                <?php echo $this->lang->line("setting_intergration_broadcast_business_conclusion_text"); ?>
                                            </span>
                                        </div>

                                        <div class="_box">
                                            <img src="<?php echo base_url('assets/icons/panel/girl.svg'); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="button-schedule">
                                    <button type="button" id="btnCallBack" class="btn btn-primary"><?php echo $this->lang->line("setting_intergration_broadcast_business_btn_callBack") ?></button>
                                    <button type="button" id="btnBookMeeting" class="btn btn-primary"><?php echo $this->lang->line("setting_intergration_broadcast_business_btn_bookMeeting") ?> <i class="fas fa-arrow-right IconSave" id="arrow_right_book"></i><img style="display:none;" class="loadSave" id="load_book" src="<?php echo base_url('/assets/img/loads/loading_1.gif'); ?>" width="20px" alt="IMG"></button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>