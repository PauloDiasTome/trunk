<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("usergroup_edit_title"); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("usergroup/save/$id"); ?>
                    <input id="input-participants" name="input-participants" type="hidden" value="" />
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("usergroup_edit_information"); ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-name"><?php echo $this->lang->line("usergroup_nome"); ?></label>
                                    <input type="text" id="input-name" name="input-name" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("usergroup_nome_placeholder"); ?>" value="<?php echo $data['name']; ?>">
                                    <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert__input-name" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>usergroup"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("usergroup_return"); ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("usergroup_btn_save"); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>