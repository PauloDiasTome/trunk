<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("myinvoice_addfile_payment") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open_multipart("myinvoice/save/$id"); ?>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-date-payment"><?php echo $this->lang->line("myinvoice_addfile_payment_date") ?></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        </div>
                                        <input type="text" id="input-date-payment" name="input-date-payment" autocomplete="off" class=" form-control datepicker" placeholder=<?php echo $this->lang->line("myinvoice_addfile_date") ?> value="<?php echo isset($_POST['input-date-payment']) ? $_POST['input-date-payment'] : ""; ?>">
                                    </div>
                                    <?php echo form_error('input-date-payment', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="file"><?php echo $this->lang->line("myinvoice_addfile_payment") ?></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="file" name="file">
                                        <label class="custom-file-label" for="file"><?php echo $this->lang->line("myinvoice_addfile_select_file") ?></label>
                                        <?php echo form_error('file', '<div class="alert-field-validation">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>myinvoice"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("myinvoice_addfile_btn_return") ?></button></a>
                            <button id="<?php echo $id; ?>" class="btn btn-success save-proof-of-payment"><?php echo $this->lang->line("myinvoice_addfile_btn_save") ?></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>