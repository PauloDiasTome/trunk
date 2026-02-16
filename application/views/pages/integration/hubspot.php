<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-5 center-block col-centered">
            <div class="card card-pricing border-0 text-center mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_hubspot_topnav") ?></h4>
                </div>
                <div class="card-body px-lg-7">
                    <div class="display-2 mb-4">
                        <img class="rounded-circle avatar avatar-xxl " src="<?php echo base_url('assets/img/hubspot.jpg'); ?>">
                    </div>
                    <span class="text-muted"><?php echo $this->lang->line("setting_integration_hubspot") ?></span>
                    <ul class="list-unstyled my-4">
                        <li>
                            <div class="d-flex">
                                <div>
                                    <span class="pl-2 text-sm"><?php echo $this->lang->line("setting_integration_hubspot_text_confirm") ?></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <a href="<?php echo $hubspot_url ?>">
                    <button type="button" class="btn btn-hubspot btn-icon" style="background-color: #FF7A59;color: #33475B;" >
                        <span class="btn-inner--icon"><i class="fab fa-hubspot"></i></span>
                        <span class="btn-inner--text"><?php echo $this->lang->line("setting_integration_hubspot_btn_hubspot") ?></span>
                    </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>