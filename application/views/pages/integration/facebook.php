<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-5 center-block col-centered">
            <div class="card card-pricing border-0 text-center mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_facebook_topnav") ?></h4>
                </div>
                <div class="card-body px-lg-7" style="display: flex; flex-direction: column; align-items: center;">
                    <div class="display-2" style="margin-bottom: 20px;">
                        <img src="../../assets/img/facebook2.svg" style="height: 100px;">
                    </div>
                    <span class="text-muted"><?php echo $this->lang->line("setting_integration_facebook_messenger") ?></span>
                    <ul class="list-unstyled my-4">
                        <li>
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="pl-2 text-sm"><?php echo $this->lang->line("setting_integration_facebook_talkall_allowed") ?></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <a href="<?php echo base_url(); ?>facebook/oauth">
                        <button type="button" class="btn btn-facebook btn-icon" style="background: #2374f2; display: flex; align-items: center;">
                            <span class="btn-inner--icon">
                                <i class="fab fa-facebook-f" style="background: white; padding: 10px 16px; border-radius: 30px; color: #2374f2; font-size: 30px;"></i>
                            </span>
                            <span class="btn-inner--text"><?php echo $this->lang->line("setting_integration_facebook_btn_facebook") ?></span>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>