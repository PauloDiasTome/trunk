<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-5 center-block col-centered">
            <div class="card card-pricing border-0 text-center mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_waba_topnav") ?></h4>
                </div>
                <div class="card-body px-lg-7">
                    <div class="display-2" style="margin-bottom: 20px;text-align: center;">
                        <img src="../../assets/img/whasapp_inegration.png" style="width: 100px;border-radius: 50px;">
                    </div>
                    <span class="text-muted"><?php echo $this->lang->line("setting_integration_waba_messenger") ?></span>
                    <ul class="list-unstyled my-4">
                        <li>
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="pl-2 text-sm"><?php echo $this->lang->line("setting_integration_waba_talkall_allowed") ?></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <a href="<?php echo base_url(); ?>waba/oauth">
                        <button type="button" class="btn btn-whatsapp btn-icon">
                            <span class="btn-inner--icon"><i class="fab fa-waba"></i></span>
                            <span class="btn-inner--text"><?php echo $this->lang->line("setting_integration_waba_btn_waba") ?></span>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>