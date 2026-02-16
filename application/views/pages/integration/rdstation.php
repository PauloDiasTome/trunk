<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-5 center-block col-centered">
            <div class="card card-pricing border-0 text-center mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_rdstation_topnav") ?></h4>
                </div>
                <div class="card-body px-lg-7">
                    <div class="display-2 mb-4">
                        <img class="rounded-circle avatar avatar-xxl " src="<?php echo base_url('assets/img/rdstation.jpg'); ?>">
                    </div>
                    <span class="text-muted"><?php echo $this->lang->line("setting_integration_rdstation") ?></span>
                    <ul class="list-unstyled my-4">
                        <li>
                            <div class="d-flex">
                                <div>
                                    <span class="pl-2 text-sm"><?php echo $this->lang->line("setting_integration_rdstation_contact_allow") ?></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <a href="<?php echo $rd_url ?>">
                    <button type="button" class="btn" style="background-color: #364a65;color: white;" >
                        <span class="btn-inner--text"><?php echo $this->lang->line("setting_integration_rdstation_btn_station") ?> <b><?php echo $this->lang->line("setting_integration_rdstation_btn_rd") ?></b></span>
                    </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>