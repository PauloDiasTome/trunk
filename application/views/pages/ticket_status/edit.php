<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("ticket_status_edit_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("ticket/status/save/$id"); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("ticket_status_edit_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-name"><?php echo $this->lang->line("ticket_status_name") ?></label>
                                    <input type="text" id="input-name" name="input-name" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("ticket_status_name_placeholder") ?>" value="<?php echo empty(set_value('input-name')) ? $data['name'] : set_value('input-name') ?>">
                                    <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert__input-name" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="form-control-label" for="input-color"><?php echo $this->lang->line("ticket_status_color") ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" style="border: 1px solid #dee2e6; border-radius: .25rem; padding: .625rem .75rem;">
                                    <input class="form-control" id="input-color" name="input-color" type="color" value="<?php echo empty(set_value('input-color')) ? $data['color'] : set_value('input-color') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("ticket_status_ticket_info") ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt--1">
                            <div class="col-lg-2">
                                <div class="custom-control custom-radio mb-2">
                                    <input class="custom-control-input" name="is_open" id="is_open" type="radio" <?php if (($data['is_open'] == 1 || set_value('is_open') == "true") && set_value('is_close') != "on") {
                                                                                                                        echo "checked";
                                                                                                                    } ?>>
                                    <label class="custom-control-label" for="is_open"><?php echo $this->lang->line("ticket_status_open") ?></label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="custom-control custom-radio mb-2">
                                    <input class="custom-control-input" name="is_close" id="is_close" type="radio" <?php if (($data['is_open'] == 2 || set_value('is_close') == "on") &&  set_value('is_open') != "true") {
                                                                                                                        echo "checked";
                                                                                                                    } ?>>
                                    <label class="custom-control-label" for="is_close"><?php echo $this->lang->line("ticket_status_closed") ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>ticket/status"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("ticket_status_btn_return") ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("ticket_status_btn_save") ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>