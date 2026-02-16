<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("shortlink_add_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("shortlink/save"); ?>
                    <input id="input-participants" name="input-participants" type="hidden" value="" />
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("shortlink_add_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("shortlink_link_name") ?></label>
                                    <input type="text" id="input-name" name="input-name" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("shortlink_link_name_placeholder") ?>" value="">
                                    <div class="alert-field-validation" id="name-field" style="display: none;"><?php echo $this->lang->line("shortlink_alert_field_validation") ?> </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("shortlink_number") ?></label>
                                    <input type="text" id="input-phone" name="input-phone" class="form-control" maxlength="600" placeholder="<?php echo $this->lang->line("shortlink_number_placeholder") ?>" value="">
                                    <div class="alert-field-validation" id="phone-field" style="display: none;"><?php echo $this->lang->line("shortlink_alert_field_validation") ?> </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("shortlink_link") ?></label>
                                    <input type="text" id="input-link" readonly="false" name="input-link" class="form-control" value="<?php echo $link; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("shortlink_title") ?></label>
                                    <input type="text" id="input-title" name="input-title" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("shortlink_title_placeholder") ?>" value="">
                                    <?php echo form_error('input-title', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("shortlink_description") ?></label>
                                    <input type="text" id="input-description" name="input-description" class="form-control" maxlength="140" placeholder="<?php echo $this->lang->line("shortlink_description_placeholder") ?>" value="">
                                    <?php echo form_error('input-description', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo $this->lang->line("shortlink_user_link") ?></label>
                                    <select class="form-control" name="select-user">
                                        <?php foreach ($users as $row) { ?>
                                            <option value="<?php echo $row['id_user']; ?>">
                                                <?php echo $row['last_name']; ?> </option>
                                        <?php  } ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("shortlink_default_message") ?></label>
                                    <input type="text" id="input-message" name="input-message" class="form-control" maxlength="140" placeholder="<?php echo $this->lang->line("shortlink_default_message_placeholder") ?>" value="">
                                    <?php echo form_error('input-message', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>shortlink"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("shortlink_btn_cancel") ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("shortlink_btn_save") ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>