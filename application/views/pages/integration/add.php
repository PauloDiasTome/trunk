<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <?php
                            $url = explode("/", $_SERVER['REQUEST_URI']);
                            $id = $url[count($url) - 1];

                            if (is_int(intval($id)) and intval($id) == 12) : ?>
                                <h3 class="mb-0"><i class="fab fa-whatsapp"><?php echo $this->lang->line("setting_integration_add_topnav") ?></i></h3>
                            <?php else : ?>
                                <h3 class="mb-0"><?php echo $this->lang->line("setting_integration_add_new_integration") ?></h3>
                            <?php endif;
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php //echo form_open_multipart(""); 
                    ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("setting_integration_add_integration") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-token"><?php echo $this->lang->line("setting_integration_add_integration_key") ?></label>
                                    <input type="text" id="input-token" name="input-token" class=" form-control" placeholder="<?php echo $this->lang->line("setting_integration_add_integration_key_placeholder") ?>">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>integration"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("setting_integration_add_btn_cancel") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("setting_integration_add_btn_save") ?></button>
                        </div>
                    </div>
                    <?php //echo form_close(); 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>