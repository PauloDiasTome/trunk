<div class="container mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("config_adv_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php 
                            $hidden = array(
                                'id_company' => $data['id_company'],
                            );
                            echo form_open("config/advanced",array('id' => 'company'),$hidden);
                        ?>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="name"><?php echo $this->lang->line("config_adv_company_name") ?></label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo $this->lang->line("config_adv_company_name_placeholder") ?>"
                                    value="<?php echo $data['name'] ?>" disabled>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-3">
                                    <input class="custom-control-input" name="profile_picture" id="profile_picture"
                                        type="checkbox" <?php echo $data['profile_picture'] == 2 ? "checked" : "" ?>>
                                    <label class="custom-control-label" for="profile_picture"><?php echo $this->lang->line("config_adv_hide_profile_pictures") ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-3">
                                    <input class="custom-control-input" name='contact_number' id="contact_number"
                                        type="checkbox" <?php echo $data['contact_number'] == 2 ? "checked" : "" ?>>
                                    <label class="custom-control-label" for="contact_number"><?php echo $this->lang->line("config_adv_hide_number") ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="cnpj"><?php echo $this->lang->line("config_adv_company_cnpj") ?></label>
                                <input type="text" name="cnpj" id="cnpj" class="form-control" placeholder="<?php echo $this->lang->line("config_adv_company_cnpj_placeholder") ?>"
                                    value="<?php echo $data['cnpj'] ?>" disabled>
                            </div>

                        </div>

                        <div class="col-12">
                            <input type="submit" value="<?php echo $this->lang->line("config_adv_btn_save_and_return") ?>" class="btn btn-primary">
                        </div>

                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>

    </div>
    <div class="row ">
        <div class="col-xl-12">
            <small class="d-flex justify-content-center">Talkall - Versão: <b><?php echo $this->config->config['application_version']; ?> </b> </small>
        </div>
    </div>
</div>