<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <a id="btn-return" href="<?php echo base_url("/integration") ?>" class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("setting_integration_topnav") ?></a>
                    <span style="margin: 0px 3px; color: #e9ebef"> - </span>
                    <span class="h3 text-white d-inline-block mb-0 " style="color: #e9ebef"><?php echo $this->lang->line("setting_integration_new_topnav") ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row integration">

        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                <img src="https://app.talkall.com.br/assets/img/whasapp_inegration.png"> </a>
                        </div>
                        <div class="col ml--2">
                            <h4 class="mb-0">
                                <a href="#!">WhatsApp Cloud API</a>
                            </h4>
                            <p class="text-sm text-muted mb-0"><?php echo $this->lang->line("setting_integration_add_new_cloud_description") ?>
                            </p>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo base_url("/integration/add/cloud") ?>">
                                <button type="button" class="btn btn-sm btn-new-integration"><?php echo $this->lang->line("setting_integration_add_new") ?></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                <img src="https://app.talkall.com.br/assets/img/whatsapp_integration_business.png"> </a>
                        </div>
                        <div class="col ml--2">
                            <h4 class="mb-0">
                                <a href="#!">Whatsapp Coexistência</a>
                            </h4>
                            <p class="text-sm text-muted mb-0"><?php echo $this->lang->line("setting_integration_add_new_business_description") ?>
                            </p>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo base_url("/integration/add/business") ?>">
                                <button type="button" class="btn btn-sm btn-new-integration"><?php echo $this->lang->line("setting_integration_add_new") ?></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                <img src="https://app.talkall.com.br/assets/img/whasapp_inegration.png"> </a>
                        </div>
                        <div class="col ml--2">
                            <h4 class="mb-0">
                                <a href="#!">WhatsApp Canal</a>
                            </h4>
                            <p class="text-sm text-muted mb-0"><?php echo $this->lang->line("setting_integration_add_new_channel_description") ?>
                            </p>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo base_url("/integration/add/whatsapp/newsletter") ?>">
                                <button type="button" class="btn btn-sm btn-new-integration"><?php echo $this->lang->line("setting_integration_add_new") ?></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                <img src="https://app.talkall.com.br/assets/img/whasapp_inegration.png"> </a>
                        </div>
                        <div class="col ml--2">
                            <h4 class="mb-0">
                                <a href="#!">WhatsApp Comunidade</a>
                            </h4>
                            <p class="text-sm text-muted mb-0"><?php echo $this->lang->line("setting_integration_add_new_community_description") ?></p>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo base_url("/integration/add/whatsapp/community") ?>">
                                <button type="button" class="btn btn-sm btn-new-integration"><?php echo $this->lang->line("setting_integration_add_new") ?></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                <img src="https://app.talkall.com.br/assets/img/whasapp_inegration.png"> </a>
                        </div>
                        <div class="col ml--2">
                            <h4 class="mb-0">
                                <a href="#!">WhatsApp Broadcast</a>
                            </h4>
                            <p class="text-sm text-muted mb-0"><?php echo $this->lang->line("setting_integration_add_new_broadcast_description") ?>
                            </p>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo base_url("/integration/add/whatsapp/broadcast") ?>">
                                <button type="button" class="btn btn-sm btn-new-integration"><?php echo $this->lang->line("setting_integration_add_new") ?></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- add facebook -->
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="<?php echo base_url("/integration/add/facebook") ?>" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                <img src="../../assets/img/facebook2.svg"> </a>
                        </div>
                        <div class="col ml--2">
                            <h4 class="mb-0">
                                <a href="<?php echo base_url("/integration/add/facebook") ?>">Facebook</a>
                            </h4>
                            <p class="text-sm text-muted mb-0"><?php echo $this->lang->line("setting_integration_add_new_facebook_description") ?>
                            </p>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo base_url("/integration/add/facebook") ?>">
                                <button type="button" class="btn btn-sm btn-new-integration"><?php echo $this->lang->line("setting_integration_add_new") ?></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- add telegram -->
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                <img src="https://app.talkall.com.br/assets/img/telegram.svg"></a>
                        </div>
                        <div class="col ml--2">
                            <h4 class="mb-0">
                                <a href="#!">Telegram</a>
                            </h4>
                            <p class="text-sm text-muted mb-0"><?php echo $this->lang->line("setting_integration_add_new_telegram_description") ?></p>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo base_url("/integration/add/telegram") ?>">
                                <button type="button" class="btn btn-sm btn-new-integration"><?php echo $this->lang->line("setting_integration_add_new") ?></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- add widget -->
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                <img src="https://app.talkall.com.br/assets/img/widget.svg"></a>
                        </div>
                        <div class="col ml--2">
                            <h4 class="mb-0">
                                <a href="#!">Widget</a>
                            </h4>
                            <p class="text-sm text-muted mb-0"><?php echo $this->lang->line("setting_integration_add_new_widget_description") ?>
                            </p>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo base_url("/integration/add/widget") ?>">
                                <button type="button" class="btn btn-sm btn-new-integration"><?php echo $this->lang->line("setting_integration_add_new") ?></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- add TV -->
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="<?php echo base_url("/integration/add/tv") ?>" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                <img src="../../assets/img/tv.png"> </a>
                        </div>
                        <div class="col ml--2">
                            <h4 class="mb-0">
                                <a href="<?php echo base_url("/integration/add/tv") ?>">TV</a>
                            </h4>
                            <p class="text-sm text-muted mb-0"><?php echo $this->lang->line("setting_integration_add_new_tv_description") ?>
                            </p>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo base_url("/integration/add/tv") ?>">
                                <button type="button" class="btn btn-sm btn-new-integration"><?php echo $this->lang->line("setting_integration_add_new") ?></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- openAI -->
        <?php if (!$data[0]['hasOpenAi']) { ?>
            <div class="col-xl-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="<?php echo base_url("/integration/add/openai") ?>" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                    <img src="<?php echo base_url("assets/icons/panel/openai.svg") ?>"> </a>
                            </div>
                            <div class="col ml--2">
                                <h4 class="mb-0">
                                    <a href="<?php echo base_url("/integration/add/openai") ?>">OpenAI</a>
                                </h4>
                                <p class="text-sm text-muted mb-0"><?php echo $this->lang->line("setting_integration_add_new_openai_description") ?></p>
                            </div>
                            <div class="col-auto">
                                <a href="<?php echo base_url("/integration/add/openai") ?>">
                                    <button type="button" class="btn btn-sm btn-new-integration"><?php echo $this->lang->line("setting_integration_add_new") ?></button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
    <input type="hidden" id="oauth_response" name="oauth_response" value="<?php echo $resp['response']; ?>">
</div>