<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("order_status_edit_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("order/status/save/$id"); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("order_status_edit_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-name"><?php echo $this->lang->line("order_status_name") ?></label>
                                    <input type="text" id="input-name" name="input-name" class="form-control" placeholder="<?php echo $this->lang->line("order_status_name_placeholder") ?>" value="<?php echo $data['name']; ?>">
                                    <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="form-control-label" for="input-tags"><?php echo $this->lang->line("order_status_color") ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" style="border: 1px solid #dee2e6; border-radius: .25rem; padding: .625rem .75rem;">
                                    <input class="form-control" id="input-color" name="input-color" type="color" value="<?php echo $data['color']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="form-control-label" for="input-message"><?php echo $this->lang->line("order_status_auto_message") ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <textarea class="form-control" id="input-message" name="input-message" rows="10" resize="none"><?php echo $data['message']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>order/status"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("order_status_btn_return") ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("order_status_btn_save") ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>