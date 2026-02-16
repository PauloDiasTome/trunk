<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-5 center-block col-centered">
            <div class="card card-pricing border-0 text-center mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_instagram_topnav") ?></h4>
                </div>
                <div class="card-body px-lg-7">
                    <div class="display-2" style="margin-bottom: 20px;">
                        <img src="../../assets/img/instagram_integration.png" width="64px">
                    </div>
                    <span class="text-muted"><?php echo $this->lang->line("setting_integration_instagram") ?></span>
                    <ul class="list-unstyled my-4">
                        <li>
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="pl-2 text-sm"><?php echo $this->lang->line("setting_integration_instagram_talkall_allow") ?></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <a href="<?php echo base_url(); ?>instagram/oauth">
                    <button type="button" class="btn btn-instagram btn-icon">
                        <span class="btn-inner--icon"><i class="fab fa-instagram"></i></span>
                        <span class="btn-inner--text"><?php echo $this->lang->line("setting_integration_instagram_btn_instagram") ?></span>
                    </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>