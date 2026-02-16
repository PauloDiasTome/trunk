<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-5 center-block col-centered">
            <div class="card card-pricing border-0 text-center mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_add_telegram_topnav") ?></h4>
                </div>
                <div class="card-body px-lg-7">
                    <div class="display-2" style="margin-bottom: 20px;">
                        <img src="../../assets/img/telegram.svg" width="128px">
                    </div>
                    <span class="text-muted"><?php echo $this->lang->line("setting_integration_telegram") ?></span>
                    <?php echo form_open("telegram/oauth"); ?>
                    <div class="row" style="margin-top:20px">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="text" id="input-token" name="input-token" class="form-control" placeholder="<?php echo $this->lang->line("setting_integration_telegram_token_placeholder") ?>" value="">
                                <?php echo form_error('input-token', '<div class="error">', '</div>'); ?>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success"><?php echo $this->lang->line("setting_integration_telegram_btn_connect") ?></button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>