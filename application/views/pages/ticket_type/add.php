<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("ticket_type_add_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open($is_subtype == "true" ? "ticket/type/subtype/save/{$id}" : "ticket/type/save"); ?>
                    <input type="hidden" name="screen" value="<?php echo $is_subtype == "true" ? "Add" : "" ?>">
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("ticket_type_add_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-name"><?php echo $this->lang->line("ticket_type_name") ?></label>
                                    <input type="text" id="input-name" name="input-name" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("ticket_type_name_placeholder") ?>" value="<?php echo empty(set_value('input-name')) ? "" : set_value('input-name') ?>">
                                    <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert__input-name" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <?php if ($is_subtype == "false") { ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="user_group"><?php echo $this->lang->line("ticket_type_sector") ?></label>
                                        <select class="form-control" name="user_group" id="user_group">
                                            <option value=""><?php echo $this->lang->line("ticket_type_select") ?></option>
                                            <?php foreach ($group as $row) { ?>
                                                <option value="<?php echo $row['id_user_group']; ?>" <?php echo isset($row['id_user_group']) && $row['id_user_group'] == set_value('user_group') ? "Selected" : ""; ?>>
                                                    <?php echo $row['name']; ?> </option>
                                            <?php  } ?>
                                        </select>
                                        <?php echo form_error('user_group', '<div class="alert-field-validation">', '</div>'); ?>
                                        <div class="alert-field-validation" id="alert__user_group" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                        <?php  } ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="ticket_sla"><?php echo $this->lang->line("ticket_type_ticket_sla") ?></label>
                                    <select class="form-control" name="ticket_sla" id="ticket_sla">
                                        <option value=""><?php echo $this->lang->line("ticket_type_select") ?></option>
                                        <?php foreach ($ticket_sla as $row) { ?>
                                            <option value="<?php echo $row['id_ticket_sla']; ?>" <?php echo isset($row['id_ticket_sla']) && $row['id_ticket_sla'] == set_value('ticket_sla') ? "Selected" : ""; ?>>
                                                <?php echo $row['name']; ?> </option>
                                        <?php  } ?>
                                    </select>
                                    <?php echo form_error('ticket_sla', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert__ticket_sla" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo $id != 0 ? " " . base_url() . "ticket/type/{$id}" : " " . base_url() . "ticket/type" ?>"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("ticket_type_btn_cancel") ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("ticket_type_btn_save") ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>