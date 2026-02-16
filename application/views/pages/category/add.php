<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("category_add_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("category/save"); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("category_add_information") ?></h6>
                    <div class="row">
                        <!-- Campo Nome da Categoria -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input-name">
                                    <?php echo $this->lang->line("category_nome") ?>
                                </label>
                                <input type="text" id="name" name="name" class="form-control" maxlength="30" placeholder="<?php echo $this->lang->line("category_nome_placeholder") ?>" value="">
                                <?php echo form_error('name', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert__input-name" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="channel_id"><?php echo $this->lang->line("category_select_user_group") ?></label><br>
                                <div id="myselect">
                                    <select id="multiselect" name="others[]" multiple="multiple" style="display:none">
                                        <?php if (isset($others) && $others != "") {
                                            foreach ($userGroups as $userGroup => $row) {
                                                foreach ($others as $other => $val) {
                                                    if ($row["id_user_group"] == $val) { ?>
                                                        <option value="<?php echo $row['id_user_group']; ?>" selected><?php echo $row['name']; ?> </option>
                                                <?php unset($userGroups[$userGroup]);
                                                    }
                                                }
                                            }
                                            foreach ($userGroups as $value) { ?>
                                                <option value="<?php echo $value['id_user_group']; ?>"><?php echo $value['name']; ?> </option>
                                            <?php }
                                        } else {
                                            foreach ($userGroups as $row) { ?>
                                                <option value="<?php echo $row['id_user_group']; ?>"><?php echo $row['name']; ?> </option>
                                        <?php  }
                                        } ?>
                                    </select>
                                    <?php echo form_error('others[]', '<div class="alert-status">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_multi_selects" style="display: none;"><?php echo $this->lang->line("whatsapp_broadcast_alert_field_validation") ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input-data">
                                    <?php echo $this->lang->line("category_description") ?>
                                </label>
                                <textarea class="form-control" id="input-data" name="input-data" rows="3" maxlength="140" placeholder="<?php echo $this->lang->line("category_description_placeholder") ?>"><?php echo isset($data['input-data']) ? $data['input-data'] : ""; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a href="<?php echo base_url(); ?>category">
                                <button class="btn btn-danger" type="button"><?php echo $this->lang->line("category_btn_cancel") ?></button>
                            </a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("category_btn_save"); ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>