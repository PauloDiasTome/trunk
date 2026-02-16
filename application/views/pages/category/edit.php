<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("category_edit_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("category/save/" . $category['id_category']); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("category_edit_information") ?></h6>

                    <div class="pl-lg-1">
                        <div class="row">
                            <!-- Nome da Categoria -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="name">
                                        <?php echo $this->lang->line("category_nome") ?>
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control" maxlength="30" placeholder="<?php echo $this->lang->line("category_nome_placeholder") ?>" value="<?php echo isset($category['name']) ? $category['name'] : ''; ?>">
                                    <?php echo form_error('name', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert__input-name" style="display: none;"></div>
                                </div>
                            </div>

                            <!-- Grupos de Usuário -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="multiselect">
                                        <?php echo $this->lang->line("category_select_user_group") ?>
                                    </label>
                                    <select id="multiselect" name="others[]" class="form-control" multiple="multiple" style="width: 100%">
                                        <?php
                                        if (!empty($selectedGroups)) {
                                            foreach ($userGroups as $key => $group) {
                                                if (in_array($group['id_user_group'], $selectedGroups)) {
                                                    echo '<option value="' . $group['id_user_group'] . '" selected>' . $group['name'] . '</option>';
                                                    unset($userGroups[$key]);
                                                }
                                            }
                                        }

                                        foreach ($userGroups as $group) {
                                            echo '<option value="' . $group['id_user_group'] . '">' . $group['name'] . '</option>';
                                        }
                                        ?>
                                    </select>

                                    <?php echo form_error('others[]', '<div class="alert-status">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_multi_selects" style="display: none;">
                                        <?php echo $this->lang->line("whatsapp_broadcast_alert_field_validation") ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label" for="input-data">
                                    <?php echo $this->lang->line("category_description") ?>
                                </label>
                                <textarea class="form-control" id="input-data" name="input-data" rows="3" maxlength="140" resize="none" placeholder="<?php echo $this->lang->line("category_description_placeholder") ?>"><?php echo isset($category['description']) ? $category['description'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a href="<?php echo base_url(); ?>category"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("category_btn_return") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("category_btn_save"); ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>