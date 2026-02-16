<div class="container-fluid mt--6 integration-open-ai">
    <div class="row justify-content-center">
        <div class="col-lg-5 center-block col-centered">
            <div class="card card-pricing border-0 text-center mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_facebook_topnav") ?></h4>
                </div>
                <div class="card-body px-lg-7" style="display: flex; flex-direction: column; align-items: center;">
                    <div class="display-2" style="margin-bottom: 20px;">
                        <img src="<?php echo base_url("assets/icons/panel/openai.svg") ?>" style="height: 100px;"> </a>
                    </div>
                    <span class="text-muted"><?php echo $this->lang->line("setting_integration_openai") ?></span>
                    <ul class="list-unstyled my-4">
                        <li>
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="pl-2 text-sm" id="provide-info"><?php echo $this->lang->line("setting_integration_openai_insert_token") ?></span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center justify-content-center">
                                <input type="text" class="form-control" id="input-token" placeholder="<?php echo $this->lang->line("setting_integration_openai_token_placeholder")?>">
                                <div class="alert-field-validation" id="alert__input-token" style="margin-top: 65px;"></div>

                                <div class="form-group gpt-version hidden">
                                    <input type="text" name="input-version" id="input-version" placeholder="<?php echo $this->lang->line("setting_integration_openai_version_placeholder")?>" autocomplete="off" class="form-control">
                                    <input class="hidden" type="text" name="id-version" id="id-version">
                                    <span class="list-container">
                                        <ul class="version-list">
                                        </ul>
                                    </span>
                                    <div class="alert-field-validation hidden" id="alert__input-version"></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <button class="btn btn-success" id="btn-connect"><?php echo $this->lang->line("setting_integration_openai_btn_connect") ?></button>
                        <button class="btn btn-danger hidden"><?php echo $this->lang->line("setting_integration_openai_btn_cancel") ?></button>
                        <button class="btn btn-success hidden" id="btn-save"><?php echo $this->lang->line("setting_integration_openai_btn_save") ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>