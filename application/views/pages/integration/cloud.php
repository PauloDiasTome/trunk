<div class="container-fluid mt--6" id="whatsapp-cloud">
    <div class="row justify-content-center">
        <div class="col-lg-5 center-block col-centered">
            <div class="card card-pricing border-0 text-center mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_waba_topnav") ?></h4>
                </div>
                <div class="card-body px-lg-7" style="display: flex; flex-direction: column; align-items: center;">
                    <div class="display-2" style="margin-bottom: 20px;">
                        <img src="../../assets/img/whasapp_inegration.png" style="width: 100px;border-radius: 50px;">
                    </div>

                    <input type="hidden" name="error" id="error" value="<?php echo isset($errors) && !empty($errors) ? htmlspecialchars(json_encode($errors)) : '' ?>">

                    <?php echo form_open("integration/add/cloud"); ?>
                    <input type="hidden" name="code" id="code">
                    <input type="hidden" name="phone_number_id" id="phone_number_id">
                    <input type="hidden" name="waba_id" id="waba_id">
                    <input type="hidden" name="pin_code" id="pin_code">

                    <span class="text-muted">
                        <?php echo $this->lang->line("setting_integration_waba_messenger") ?>
                    </span>
                    <ul class="list-unstyled my-4">
                        <li>
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="pl-2 text-sm"><?php echo $this->lang->line("setting_integration_waba_talkall_allowed") ?></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <?php echo form_close(); ?>
                    <a>
                        <button class="btn btn-facebook btn-icon" style="background: #2374f2; display: flex; align-items: center;" onclick="launchWhatsAppSignup()">
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


    <!-- Modal  -->
    <div class="modal fade" id="modal-pin" tabindex="-1" role="dialog" aria-labelledby="modal-pin-label" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-pin-label"></h5>
                </div>

                <div class="modal-body d-flex flex-column justify-content-center" style="min-height:124px; max-height:500px; overflow-y: auto; padding: 0.5rem 1.5rem;">
                        <span></span>
                        <div class="mt-2 d-flex justify-content-between" style="max-width: 50%; gap: 5px">

                            <?php for ($i = 1; $i <= 6; $i++) : ?>
                                <input type="text" class="form-control pin-input" style="border: solid 1px #9f9b9b; height: 40px" maxlength="1" id="pin-<?php echo $i; ?>" />
                            <?php endfor; ?>
                        </div>
                        <div class="alert-field-validation" style="margin-top: 108px;"></div>
                </div>

                <div class="modal-footer" style="padding-top:0">
                    <button type="button" class="btn btn-success" id="btn-pin-next"> <?php  echo $this->lang->line("setting_integration_cloud_modal_pin_btn_next") ?>
                    <button type="button" class="btn btn-secondary btn-box-shadow" id="btn-pin-return" style="display: none;"><?php  echo $this->lang->line("setting_integration_cloud_modal_pin_btn_return") ?></button>
                    <button type="button" class="btn btn-success" id="btn-integration-conclude" style="display: none;"><?php  echo $this->lang->line("setting_integration_cloud_modal_pin_btn_conclude") ?></button>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>