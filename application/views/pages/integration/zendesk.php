<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-5 center-block col-centered">
            <div class="card card-pricing border-0 text-center mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_zendesk_topnav") ?></h4>
                </div>
                <div class="card-body px-lg-7">
                    <div class="display-2 mb-4">
                        <img class="rounded-circle avatar avatar-xxl " src="<?php echo base_url('assets/img/zendesk_integration.png'); ?>">
                    </div>
                    <span class="text-muted"><?php echo $this->lang->line("setting_integration_zendesk") ?></span>
                    <ul class="list-unstyled my-4">
                        <li>
                            <div class="d-flex">
                                <div>
                                    <span class="pl-2 text-sm"><?php echo $this->lang->line("setting_integration_zendesk_contact_allow") ?></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="input-group mb-3">
                        <input id="zendesk-domain" type="text" class="form-control" placeholder="<?php echo $this->lang->line("setting_integration_zendesk_zendesk_domain_placeholder") ?>">
                        <div class="input-group-append">
                            <span class="input-group-text"><?php echo $this->lang->line("setting_integration_zendesk_zendesk_domain") ?></span>
                            <a id="zendesk-link" href="<?php echo $zendesk_url ?>" class="btn btn-primary disabled"><?php echo $this->lang->line("setting_integration_zendesk_btn_ok") ?></a>
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div>
</div>