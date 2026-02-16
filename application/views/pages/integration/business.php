<div class="container-fluid mt--6" id="whatsapp-business">
    <div class="row justify-content-center">
        <div class="card card-pricing border-0 text-center mb-4">
            <div class="card-header bg-transparent">
                <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_waba_topnav") ?></h4>
            </div>
            <div class="card-body px-lg-7" style="display: flex; flex-direction: column; align-items: center;">
                <div class="display-2" style="margin-bottom: 20px;">
                    <img src="https://app.talkall.com.br/assets/img/whatsapp_integration_business.png" style="width: 100px;border-radius: 50px;">
                </div>

                <span class="text-muted">
                    <?php echo $this->lang->line("setting_integration_business_messenger") ?>
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

                <div class="text-center mb-4">
                    <button id="startSdkBtn" class="btn btn-facebook btn-icon" style="background: #2374f2; display: flex; align-items: center;" disabled>
                        <span class="btn-inner--icon">
                            <i class="fab fa-facebook-f" style="background: white; padding: 10px 16px; border-radius: 30px; color: #2374f2; font-size: 30px;"></i>
                        </span>
                        <span class="btn-inner--text">Carregando</span>
                    </button>
                </div>

                <input type="hidden" id="waba_id">
                <input type="hidden" id="phone_number_id">
            </div>
        </div>
    </div>
</div>

<script>
    const csrfName = '<?= $this->security->get_csrf_token_name() ?>';
    const csrfHash = '<?= $this->security->get_csrf_hash() ?>';
</script>