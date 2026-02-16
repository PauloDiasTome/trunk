<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("usercall_add_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("usercall/save"); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("usercall_add_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("usercall_nome") ?></label>
                                    <input type="text" id="input-name" name="input-name" class="form-control" maxlength="30" placeholder="<?php echo $this->lang->line("usercall_nome_placeholder") ?>" value="">
                                    <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert__input-name" style="display: none;"></div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-limit"><?php echo $this->lang->line("usercall_simultaneous_service_limit") ?></label>
                                    <input type="number" id="input-limit" name="input-limit" class="form-control" placeholder="<?php echo $this->lang->line("usercall__add_simultaneous_service_limit_placeholder") ?>" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="3" value="10" min="1" max="100">
                                    <?php echo form_error('input-limit', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert__input-limit" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>usercall"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("usercall_btn_cancel") ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("usercall_btn_save"); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>