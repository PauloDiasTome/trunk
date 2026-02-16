<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("client_company_edit_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("client/company/save/$id"); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("client_company_edit_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-creation"><?php echo $this->lang->line("client_company_criation") ?></label>
                                    <input type="text" id="input-creation" class="form-control" disabled value="<?php echo $data['creation'] ?>">
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-corporate-name"><?php echo $this->lang->line("client_company_corporate_name") ?></label>
                                    <input type="text" id="input-corporate-name" name="input-corporate-name" class="form-control" maxlength="60" placeholder="<?php echo $this->lang->line("client_company_corporate_name_placeholder") ?>" value="<?php echo $data['corporate_name'] ?>">
                                    <?php echo form_error('input-corporate-name', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-cnpj"><?php echo $this->lang->line("client_company_cnpj") ?></label>
                                    <input type="text" id="input-cnpj" name="input-cnpj" class="form-control" maxlength="18" min="18" max="18" placeholder="<?php echo $this->lang->line("client_company_cnpj_placeholder") ?>" value="<?php echo $data['cnpj'] ?>">
                                    <?php echo form_error('input-cnpj', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-fantasy-name"><?php echo $this->lang->line("client_company_fantasy_name") ?></label>
                                    <input type="text" id="input-fantasy-name" name="input-fantasy-name" class="form-control" maxlength="60" placeholder="<?php echo $this->lang->line("client_company_fantasy_name_placeholder") ?>" value="<?php echo $data['fantasy_name'] ?>">
                                    <?php echo form_error('input-fantasy-name', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>client/company"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("client_company_btn_return") ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("client_company_btn_save") ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>