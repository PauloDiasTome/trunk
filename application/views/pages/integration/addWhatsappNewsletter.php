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

                                        <div class="col-5">
                                            <label class="form-control-label" for="input-file"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_profile"); ?></label><span class="required-asterisk">*</span>
                                            <div class="main-profile">
                                                <div class="_preview">
                                                    <input type="file" id="input-file" name="input-file" placeholder="Numero do telefone" value="" style="display:none;">
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
                                            <br>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-number-creator">
                                                    <?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_form_number_creator"); ?>
                                                </label>
                                                <span class="required-asterisk">*</span>
                                                <div class="input-wrapper">
                                                    <i class="fas fa-mobile-alt icon"></i>
                                                    <input disabled type="tel" id="input-number-creator" name="input-number-creator" class="form-control _input-custom" maxlength="15" placeholder="<?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_form_number_creator_placeholder'); ?>" value="">
                                                    <button type="button" class="btn-add-modal" id="btn-add-modal" data-modal-open="modalAddNumberCreator">
                                                        <?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_form_btn_creator"); ?>
                                                    </button>
                                                    <span class="alert-field-validation" id="alert__input-number-creator"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-number-Trigger">
                                                    <?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_form_number_trigger"); ?>
                                                </label>
                                                <span class="required-asterisk">*</span>
                                                <div class="input-wrapper">
                                                    <i class="fas fa-mobile-alt icon"></i>
                                                    <input disabled type="tel" id="input-number-trigger" name="input-number-trigger" class="form-control _input-custom" maxlength="15" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_form_number_trigger_placeholder"); ?>" value="">
                                                    <button type="button" class="btn-add-modal" data-modal-open="modalAddNumberTrigger"><?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_form_btn_trigger"); ?></button>
                                                    <span class="alert-field-validation" id="alert__input-number-trigger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-name"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_name"); ?></label><span class="required-asterisk">*</span>
                                                <input type="text" id="input-name" name="input-name" maxlength="25" class="form-control _input-custom" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_form_name_placeholder"); ?>" value="<?php echo $this->lang->line("setting_intergration_broadcast_business_form_name_placeholder"); ?>">
                                                <span class="alert-field-validation" id="alert__input-name"></span>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-desc">
                                                    <?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_form_desc"); ?>
                                                </label>
                                                <textarea maxlength="139" id="textarea-content" name="textarea-content" class="form-control _input-custom" rows="5" style="min-height:100px; resize: vertical;" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_form_desc_placeholder"); ?>"></textarea>
                                                <span class="alert-field-validation" id="alert__textarea-content"></span>
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
                                                                <span class="text"><?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_preview_title"); ?></span>
                                                                <span></span>
                                                            </div>
                                                            <div class="info-channel">
                                                                <div class="box-avatar">
                                                                    <img id="preview_photo" src="<?php echo base_url('assets/img/avatar.svg'); ?>" alt="image">
                                                                </div>
                                                                <span class="name" id="preview_name"><?php echo $this->lang->line("setting_intergration_broadcast_business_form_name_placeholder"); ?></span>
                                                                <span class="number" id="preview_phone"><?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_preview_subtitle"); ?></span>
                                                            </div>
                                                            <div class="action">
                                                                <div class="following">
                                                                    <img src="<?php echo base_url('assets/icons/panel/whatsapp_following.svg'); ?>" alt="">
                                                                    <span><?php echo $this->lang->line("setting_intergration_broadcast_business_preview_following"); ?></span>
                                                                </div>
                                                                <div class="forward">
                                                                    <img src="<?php echo base_url('assets/icons/panel/whatsapp_share.svg'); ?>" alt="">
                                                                    <span><?php echo $this->lang->line("setting_intergration_broadcast_business_preview_forward"); ?></span>
                                                                </div>
                                                                <div class="share">
                                                                    <img src="<?php echo base_url('assets/icons/panel/whatsapp_share_channel.svg'); ?>" alt="">
                                                                    <span><?php echo $this->lang->line("setting_intergration_broadcast_business_preview_share"); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="info-description">
                                                                <textarea disabled id="preview_description" name="input-desc" class="form-control _input-custom" rows="5" style="min-height:100px; resize: vertical;" placeholder="<?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_form_desc_placeholder"); ?>"></textarea>
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
<!-- Modal Número Criador -->
<div class="modal" id="modalAddNumberCreator" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Cabeçalho -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_title') ?></h5>
                <button type="button" class="btn-close-custom" data-modal-close="modalAddNumberCreator" aria-label="Fechar">
                    &times;
                </button>
            </div>

            <!-- Corpo -->
            <div class="modal-body">

                <!-- STEP 1: Adicionar -->
                <div class="step step-1">
                    <form id="formAddNumberCreator">
                        <div class="mb-3">
                            <label class="form-label"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_step1_name') ?><span class="text-danger">*</span></label>
                            <input id="input-name-creator-modal" name="input-name-creator-modal" type="text" class="form-control" placeholder="<?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_step1_name') ?>">
                            <span class="alert-field-validation" id="alert__input-name-creator-modal"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_step1_number') ?><span class="text-danger">*</span></label>
                            <input id="input-number-creator-modal" name="input-number-creator-modal" type="tel" class="form-control" placeholder="<?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_step1_placeholder_number') ?>" maxlength="15">
                            <span class="alert-field-validation" id="alert__input-number-creator-modal"></span>
                        </div>

                        <p class="small text-muted">
                            <?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_step1_footer') ?>
                        </p>
                    </form>
                </div>

                <!-- STEP 2: Escolher número -->
                <div class="step step-2 d-none">
                    <h5><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_step2_subtitle') ?></h5>

                    <div class="list-group">

                    </div>
                </div>

                <!-- STEP 3: Selecionar um número -->
                <div class="step step-3 d-none">
                    <!-- Card do número escolhido -->
                    <div class="list-group-item selected d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="phone-icon"></div>
                            <div>

                            </div>
                        </div>
                    </div>

                    <!-- Lista de comunidades -->
                    <p class="mt-3 mb-2 fw-semibold text-muted"></p>
                    <div class="list-group">

                    </div>
                </div>

            </div>

            <!-- Rodapé -->
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <!-- Botão genérico de voltar (para steps) -->
                    <button type="button" class="btn btn-outline-primary d-none" id="btnBack"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_btn_back') ?></button>
                </div>
                <div>
                    <!-- Step 1 -->
                    <button type="button" class="btn btn-outline-primary" id="btnReuse"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_btn_reuse') ?></button>
                    <button type="button" class="btn btn-primary" id="btnAdd"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_btn_add') ?></button>

                    <!-- Step 2 -->
                    <button type="button" class="btn btn-outline-primary d-none" id="btnBackToStep1"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_btn_back') ?></button>
                    <button type="button" class="btn btn-primary d-none" id="btnNextToStep3"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_btn_next') ?></button>

                    <!-- Step 3 -->
                    <button type="button" class="btn btn-outline-primary d-none" id="btnBackToStep2"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_btn_back') ?></button>
                    <button type="button" class="btn btn-primary d-none" id="btnSelect"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_creator_btn_select') ?></button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Número Disparador -->
<div class="modal" id="modalAddNumberTrigger" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="width: 700px;">
        <div class="modal-content">

            <!-- Cabeçalho -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_trigger_title') ?></h5>
                <button type="button" class="btn-close-custom" data-modal-close="modalAddNumberTrigger" aria-label="Fechar">
                    &times;
                </button>
            </div>

            <!-- Corpo -->
            <div class="modal-body">

                <!-- STEP 1: Adicionar -->
                <div class="step step-1">
                    <form id="formAddNumberTrigger">

                        <div class="row">
                            <!-- Coluna esquerda: Nome e Número -->
                            <div class="col-7">
                                <!-- Nome -->
                                <div class="mb-3">
                                    <label class="form-label"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_trigger_step1_name') ?> <span class="text-danger">*</span></label>
                                    <input type="text" id="input-name-trigger" class="form-control" placeholder="<?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_trigger_step1_placeholder_name') ?> ">
                                    <span class="alert-field-validation" id="alert__input-name-trigger"></span>
                                </div>

                                <!-- Número do telefone -->
                                <div class="mb-3">
                                    <label class="form-label"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_trigger_step1_number') ?> <span class="text-danger">*</span></label>
                                    <input type="tel" id="input-number-trigger-modal" class="form-control" placeholder="(00) 00000-0000" maxlength="15">
                                    <span class="alert-field-validation" id="alert__input-number-trigger-modal"></span>

                                </div>
                            </div>

                            <div class="col-4 d-flex flex-column align-items-center">
                                <label class="form-label mb-2" for="input-file">
                                    <?php echo $this->lang->line("setting_intergration_broadcast_business_form_profile"); ?>
                                </label>
                                <div class="main-profile">
                                    <div class="_preview_trigger" style="cursor:pointer;">
                                        <input type="file" id="fileTrigger" name="fileTrigger" style="display:none;">
                                        <input type="hidden" name="fileTrigger" id="fileTrigger" value="https://files.talkall.com.br:3000/v/202569/11rXYXeYr3HuQdbe0IwU2sBxUqMx82VAoehkhhqR1h2iDvohLJ1aTYddbODQEf8e.jpg">
                                        <img title="<?php echo $this->lang->line("setting_intergration_broadcast_business_form_profile_tooltip"); ?>" id="preview_form_trigger" src="<?php echo base_url('assets/img/avatar.svg'); ?>" alt="Foto de Perfil" class="img-fluid rounded-circle">
                                        <div class="profile-overlay-trigger">
                                            <i class="fas fa-camera"></i>
                                            <span><?php echo $this->lang->line("setting_intergration_broadcast_business_form_profile_placeholder"); ?></span>
                                        </div>
                                    </div>
                                    <div class="_upload mt-2">
                                        <span><?php echo $this->lang->line("setting_intergration_broadcast_business_form_profile_tooltip"); ?></span>
                                    </div>
                                    <span class="alert-field-validation" id="alert__file-trigger"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Linha da descrição -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label"><?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_trigger_step1_desc') ?></label>
                                    <textarea id="textarea-content-trigger" class="form-control" rows="3" maxlength="139" placeholder="<?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_trigger_step1_placeholder_desc') ?> "></textarea>
                                    <span class="alert-field-validation" id="alert__textarea-content-trigger"></span>
                                </div>
                            </div>
                        </div>

                        <p class="small text-muted mt-3">
                            <?php echo $this->lang->line('setting_intergration_broadcast_business_newsletter_modal_trigger_step1_footer') ?>
                        </p>
                    </form>
                </div>

                <!-- STEP 2: Escolher número -->
                <div class="step step-2 d-none">
                    <h5><?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_modal_trigger_step2_subtitle") ?></h5>
                    <div class="list-group"></div>
                </div>

                <!-- STEP 3: Selecionar um número -->
                <div class="step step-3 d-none">
                    <!-- Card do número escolhido -->
                    <div class="list-group-item selected d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="phone-icon"></div>
                            <div></div>
                        </div>
                    </div>

                    <!-- Lista de comunidades -->
                    <p class="mt-3 mb-2 fw-semibold text-muted"></p>
                    <div class="list-group"></div>
                </div>

            </div>

            <!-- Rodapé -->
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-primary d-none" id="btnBackTrigger"><?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_modal_trigger_btn_back") ?></button>
                </div>
                <div>
                    <!-- Step 1 -->
                    <button type="button" class="btn btn-outline-primary" id="btnReuseTrigger"><?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_modal_trigger_btn_reuse") ?></button>
                    <button type="button" class="btn btn-primary" id="btnAddTrigger"><?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_modal_trigger_btn_add") ?></button>

                    <!-- Step 2 -->
                    <button type="button" class="btn btn-outline-primary d-none" id="btnBackToStepTrigger1"><?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_modal_trigger_btn_back") ?></button>
                    <button type="button" class="btn btn-primary d-none" id="btnNextToStepTrigger3"><?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_modal_trigger_btn_next") ?></button>

                    <!-- Step 3 -->
                    <button type="button" class="btn btn-outline-primary d-none" id="btnBackToStepTrigger2"><?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_modal_trigger_btn_back") ?></button>
                    <button type="button" class="btn btn-primary d-none" id="btnSelect"><?php echo $this->lang->line("setting_intergration_broadcast_business_newsletter_modal_trigger_btn_select") ?></button>
                </div>
            </div>

        </div>
    </div>
</div>