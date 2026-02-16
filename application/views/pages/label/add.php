<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("label_add_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("label/save"); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("label_add_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("label_name") ?></label>
                                    <input type="text" id="input-name" name="input-name" maxlength="30" class="form-control" placeholder="<?php echo $this->lang->line("label_name_placeholder") ?>" value="">
                                    <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert__input-name" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group m-0">
                                    <label class="form-control-label" for="input-tags"><?php echo $this->lang->line("label_color") ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" style="border: 1px solid #dee2e6; border-radius: .25rem; padding: .625rem .75rem;">
                                    <input class="form-control" id="input-color" name="input-color" type="color" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>label"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("label_btn_cancel"); ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("label_btn_save"); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>