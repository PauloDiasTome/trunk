<div class="container mt--5">
    <div class="row">
        <div class="col-xl-4 order-xl-2">
            <div class="card card-profile">
                <img src="<?php echo base_url(); ?>assets/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                            <a <?php echo $data['status'] < 3 ? 'href="#"' : ''; ?>>
                                <img id="<?php echo $data['status'] < 3 ? 'user-picture' : 'user'; ?>" src="<?php echo "https://files.talkall.com.br:3000/p/"  . $data['key_remote_id'] . ".jpeg"  ?>" class="rounded-circle profile_user" onerror="onErrorImg(this)">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pb-4 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-center pt-5">
                        <?php if ($data['status'] < 3 || $data['status'] == 4 ) : ?>
                            <button type="button" class="btn btn-default btn-sm" onclick="$('#user-picture').click()">
                                <span class="btn-inner--icon"><i class="ni ni-single-02"></i></span>
                                <span class="btn-inner--text"><?php echo $this->lang->line("user_edit_change_profile_picture") ?></span>
                            </button>
                            <button type="button" data-toggle="modal" data-target="#passwordRecovery" class="btn btn-default btn-sm">
                                <span class="btn-inner--icon"><i class="ni ni-key-25 "></i></span>
                                <span class="btn-inner--text"><?php echo $this->lang->line("user_edit_change_password") ?></span>
                            </button>
                        <?php else : ?>
                            <button type="button" onclick="SendValidationEmail('<?= $data['id_user'] ?>')" class="btn btn-default btn-sm">
                                <span class="btn-inner--icon"><i class="ni ni-key-25 "></i></span>
                                <span class="btn-inner--text"><?php echo $this->lang->line("user_edit_resend_validation_email") ?></span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("user_edit_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("user/save/" . $data['id_user']); ?>
                    <input type="hidden" id="key_remote_id" name="key_remote_id" value="<?php echo $data['key_remote_id']; ?>">
                    <input type="hidden" id="origin_key_remote_id" name="origin_key_remote_id" value="<?php echo $this->session->userdata('key_remote_id'); ?>">
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("user_edit_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-creation"><?php echo $this->lang->line("user_register") ?></label>
                                    <input type="text" id="input-creation" class="form-control" disabled value="<?php echo $data['creation']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-name"><?php echo $this->lang->line("user_first_name") ?></label>
                                    <input type="text" id="input-name" name="input-name" class="form-control" maxlength="30" placeholder="<?php echo $this->lang->line("user_first_name_placeholder") ?>" value="<?php echo $data['name']; ?>">
                                    <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert__input-name" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-last-name"><?php echo $this->lang->line("user_full_name") ?></label>
                                    <input type="text" id="input-last-name" name="input-last-name" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("user_full_name_placeholder") ?>" value="<?php echo $data['last_name']; ?>">
                                    <?php echo form_error('input-last-name', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert__input-last-name" style="display: none;"></div>
                                </div>
                            </div>

                        
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="visible_widget"><?php echo $this->lang->line("user_widget") ?></label>
                                        <select class="form-control" name="visible_widget" id="visible_widget">
                                            <option value="2" <?php echo $data['visible_widget'] == 2 ? 'selected' : ''; ?>><?php echo $this->lang->line("user_widget_option_no") ?></option>
                                            <option value="1" <?php echo $data['visible_widget'] == 1 ? 'selected' : ''; ?>><?php echo $this->lang->line("user_widget_option_yes") ?></option>
                                        </select>
                                    </div>
                                </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"><?php echo $this->lang->line("user_email") ?></label>
                                    <input type="text" id="input-email" name="input-email" disabled class="form-control" placeholder="<?php echo $this->lang->line("user_email_placeholder") ?>" value="<?php echo $data['email']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo $this->lang->line("user_sector_language") ?></label>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                                    <select class="fake-sel" name="sector_language" id="sector_language" data-languagen="<?php echo $data['language'] ?>">
                                        <option data-language="pt_br"><?php echo $this->lang->line("user_sector_language_portugues") ?></option>
                                        <option data-language="en_us"><?php echo $this->lang->line("user_sector_language_english") ?></option>
                                        <option data-language="es"><?php echo $this->lang->line("user_sector_language_spanish") ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input_user_group"><?php echo $this->lang->line("user_sector") ?></label>
                                        <div class="dropdown dropdown-responsivo">
                                            <div class="input-group mb-3">
                                                <input type="hidden" class="value-select" id="user_group" name="user_group" value="<?php echo $data['id_user_group']; ?>">
                                                <input type="text" class="form-control dropdown-toggle" id="input_user_group" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" placeholder="<?php echo $this->lang->line("user_input_select") ?>" autocomplete="off" readonly>
                                                <i class="fas fa-chevron-down"></i>
                                                <div class="dropdown-menu links-responsivo" id="dropdown_user_group" aria-labelledby="input_user_group">
                                                    <a class="dropdown-item" id="0" data-toggle="modal" data-target="#modal-user-sector" onclick="clearFormModal()" title="<?php echo $this->lang->line("user_dropdown_add_new_sector_title") ?>">
                                                        <b><?php echo $this->lang->line("user_dropdown_add_new_sector") ?></b>
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                    <?php foreach ($UserGroup as $row) { ?>
                                                        <a class="dropdown-item opt <?php echo $row['id_user_group'] == $data['id_user_group'] ? "OptSelected" : "" ?>" id="<?php echo $row['id_user_group']; ?>" onclick="userCallSelected(this)"><?php echo $row['name']; ?></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="alert-field-validation" id="user_group_validation" style="margin-top:48px;"></div>
                                                <?php echo form_error('user_group', '<div class="alert-field-validation" style="margin-top:48px;">', '</div>'); ?>
                                                <div class="alert-field-validation" id="alert__input_user_group" style="display: none;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input_user_call"><?php echo $this->lang->line("user_service_profile") ?></label>
                                        <div class="dropdown dropdown-responsivo">
                                            <div class="input-group mb-3">
                                                <input type="hidden" class="value-select" id="user_call" name="user_call" value="<?php echo $data['id_user_call']; ?>">
                                                <input type="text" class="form-control dropdown-toggle" id="input_user_call" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" placeholder="<?php echo $this->lang->line("user_input_select") ?>" autocomplete="off" readonly>
                                                <i class="fas fa-chevron-down"></i>
                                                <div class="dropdown-menu links-responsivo" id="dropdown_user_call" aria-labelledby="input_user_call">
                                                    <a class="dropdown-item" id="0" data-toggle="modal" data-target="#modal-user-call" onclick="clearFormModal()" title="<?php echo $this->lang->line("user_dropdown_add_service_limit_title") ?>">
                                                        <b><?php echo $this->lang->line("user_dropdown_add_service_limit") ?></b>
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                    <?php foreach ($UserCall as $row) { ?>
                                                        <a class="dropdown-item opt <?php echo $row['id_user_call'] == $data['id_user_call'] ? "OptSelected" : "" ?>" id="<?php echo $row['id_user_call']; ?>" onclick="userCallSelected(this)"><?php echo $row['name']; ?></a>
                                                    <?php } ?>
                                                </div>
                                                <?php echo form_error('user_call', '<div class="alert-field-validation" style="margin-top:48px;">', '</div>'); ?>
                                                <div class="alert-field-validation" id="alert__input_user_call" style="display: none;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="id_permission"><?php echo $this->lang->line("user_access_profile") ?></label>
                                    <select class="form-control" name="id_permission" id="id_permission">
                                        <?php foreach ($Permission as $row) { ?>
                                            <option value="<?php echo $row['id_permission']; ?>" <?php echo $row['id_permission'] == $data['id_permission'] ? "Selected" : "" ?>>
                                                <?php echo $row['name']; ?></option>
                                        <?php  } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="id_work_time"><?php echo $this->lang->line("user_time_table") ?></label>
                                        <select class="form-control" name="id_work_time" id="id_work_time">
                                            <?php foreach ($data['work_time'] as $row) { ?>
                                                <option value="<?php echo $row['id_work_time']; ?>" <?php echo $row['id_work_time'] == $data['id_work_time'] ? "Selected" : "" ?>>
                                                    <?php echo $row['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>user"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("user_btn_return") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("user_btn_save") ?></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade show" id="passwordRecovery" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line("user_password_recovery_title"); ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("user_password_recovery_email"); ?></label><br>
                        <b id="data-email-user"><?php echo $data['email'] ?></b><b>?</b>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <input type="hidden" id="data-user-key_remote_id" value="<?= $data['key_remote_id'] ?>">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("user_password_recovery_btn_return") ?></button>
                <button type="button" class="btn btn-green" onclick="passwordRecovery()" data-dismiss="modal"><?php echo $this->lang->line("user_password_recovery_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal adicionar limite de atendimento -->
<div class="modal fade show" id="modal-user-call" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:950px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("user_modal_call_user_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="modalUserCall__name"><?php echo $this->lang->line("user_modal_call_nome") ?></label>
                            <input type="text" class="form-control" id="modalUserCall__name" name="modalUserCall__name" placeholder="<?php echo $this->lang->line("user_modal_call_nome_placeholder") ?>">
                            <div class="alert-field-validation" id="alertUserCall__name"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="modalUserCall__limit"><?php echo $this->lang->line("user_modal_call_simultaneous_service_limit") ?></label>
                            <input type="number" class="form-control" id="modalUserCall__limit" name="modalUserCall__limit" placeholder="<?php echo $this->lang->line("user_modal_call_simultaneous_service_limit") ?>" min="1" max="100">
                            <div class="alert-field-validation" id="alertUserCall__limit"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("user_modal_call_user_btn_return") ?></button>
                <button type="button" class="btn btn-green" onclick="saveUserCall()"><?php echo $this->lang->line("user_modal_call_user_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal adicionar setor -->
<div class="modal fade show" id="modal-user-sector" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:950px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("user_modal_sector_user_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="modalSector__name"><?php echo $this->lang->line("user_modal_sector_nome") ?></label>
                            <input type="text" id="modalSector__name" name="modalSector__name" class="form-control" placeholder="<?php echo $this->lang->line("user_modal_sector_nome_placeholder") ?>">
                            <div class="alert-field-validation" id="alertSector__name"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("user_modal_sector_user_btn_return") ?></button>
                <button type="button" class="btn btn-green" onclick="saveUserSector()"><?php echo $this->lang->line("user_modal_sector_user_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>