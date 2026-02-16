<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("order_payment_method_add_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("payment/method/save"); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("order_payment_method_add_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("order_payment_method_name") ?></label>
                                    <input type="text" id="input-name" name="input-name" class="form-control" placeholder="<?php echo $this->lang->line("order_payment_method_name_placeholder") ?>" value="">
                                    <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>payment/method"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("order_payment_method_btn_cancel") ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("order_payment_method_btn_save") ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>