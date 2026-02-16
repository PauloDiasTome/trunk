<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-4 order-xl-2">
            <div class="card card-profile">
                <img src="<?php echo base_url(); ?>assets/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                            <a href="#">
                                <img id="user-picture" src="<?php echo "https://files.talkall.com.br:3000/p/"  . $data['id'] . ".jpeg"  ?>" class="rounded-circle profile_user" onerror="onErrorImg(this)">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">
                    </div>
                </div>
                <div class="card-body pt-0 info-contact-edit">
                    <div class="row">
                        <div class="col-12" style="position: relative;">
                            <div class="card-profile-stats d-flex justify-content-center"></div>
                            <div style="width: 100%;">
                                <div class="group-labels" data-id-contact="<?php echo $id; ?>" style="display: flex; flex-wrap: wrap; align-items: center; gap: 4px;">
                                    <input type="hidden" id="label-name" value="<?php echo $data['labels_name']; ?>">
                                    <input type="hidden" id="label-color" value="<?php echo $data['labels_color']; ?>">
                                    <div id="label-contact" style="display: contents;"></div>
                                    <i data-toggle="modal" data-target="#modal-label" id="modalLabel" class="fas fa-tags" style="cursor:pointer; color:#666; font-size: 16px; padding: 5px;" title="Editar etiquetas"></i>
                                </div>
                                <div class="group-spam b-top p-footer unblock-contact" id="btnUnblock" data-id="<?php echo $id ?>" style="display: <?php echo $data['spam'] == 2 ? 'block' : 'none' ?>">
                                    <i class='fas fa-unlock' style='cursor:pointer; color:#e72246; font-size: 15px; margin-left: 20px;'></i>
                                    <span class="" style="cursor:pointer; color: #e72246; font-size: 15px; margin-left: 5px; font-weight: 500;"><?php echo $this->lang->line("contact_delete_block_list") ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="ml-4"><?php echo $this->lang->line("contact_edit_title"); ?></h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <?php echo form_open("contact/save/$id"); ?>

                        <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("contact_edit_information") ?></h6>

                        <div class="pl-lg-1">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-channel"><?php echo $this->lang->line("contact_channel") ?></label>
                                        <input type="text" id="input-channel" class="form-control" disabled value="<?php echo $data['channel']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-talkallid"><?php echo $this->lang->line("contact_talkall") ?></label>
                                        <input type="text" id="input-talkallid" disabled class="form-control" placeholder="TalkAll ID" value="<?php echo $data['id']; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username"><?php echo $this->lang->line("contact_record") ?></label>
                                        <input type="text" id="input-creation" class="form-control" placeholder="Registro" disabled value="<?php echo $data['creation']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-full-name"><?php echo $this->lang->line("contact_name") ?></label>
                                        <input type="text" id="input-full-name" name="input-full-name" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("contact_name_placeholder") ?>" value="<?php echo $data['full_name'] == "" ? $data['id'] : $data['full_name']; ?>">
                                        <?php echo form_error('input-full-name', '<div class="alert-field-validation">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-order"><?php echo $this->lang->line("contact_order") ?></label>
                                        <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $this->lang->line("contact_edit_input_order_tooltip") ?>"></i>
                                        <input id="input-order" name="input-order" oninput="verifyInputOrder(this)" class="form-control" placeholder="<?php echo $this->lang->line("contact_order_placeholder") ?>" value="<?php echo $data['contact_order']; ?>">
                                        <?php echo form_error('input-order', '<div class="alert-field-validation">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email"><?php echo $this->lang->line("contact_email") ?></label>
                                        <input type="text" id="input-email" name="input-email" class="form-control" maxlength="55" placeholder="<?php echo $this->lang->line("contact_email_placeholder") ?>" value="<?php echo $data['email'] == "" ? "" : $data['email']; ?>">
                                        <?php echo form_error('input-email', '<div class="alert-field-validation">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="exampleFormControlSelect1"><?php echo $this->lang->line("contact_service_internship") ?></label>
                                    <select class="form-control" name="id_user_group">
                                        <?php foreach ($group as $row) { ?>
                                            <option value="<?php echo $row['id_user_group']; ?>" <?php echo isset($row['id_user_group']) && $row['id_user_group'] == $data['id_user_group'] ? "Selected" : ""; ?>>
                                                <?php echo $row['name']; ?> </option>
                                        <?php  } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="exampleFormControlSelect1"><?php echo $this->lang->line("contact_responsible") ?></label>
                                    <select class="form-control" name="user_key_remote_id">
                                        <option class="idGroup_0" value="0"><?php echo $this->lang->line("contact_responsible_placeholder") ?></option>
                                        <?php foreach ($users as $row) { ?>
                                            <option class="idGroup_<?php echo $row['id_user_group']; ?>" value="<?php echo $row['key_remote_id']; ?>" <?php echo isset($row['key_remote_id']) && $row['key_remote_id'] == $data['user_key_remote_id'] ? "Selected" : ""; ?>>
                                                <?php echo $row['last_name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-note"><?php echo $this->lang->line("contact_notes") ?>: <?php echo $this->lang->line("contact_notes_character") ?></label>
                                    <span id="count_character" class="form-control-label count-character-field"> 550 </span>
                                    <textarea class="form-control" id="input-note" name="input-note" rows="10" resize="none" maxlength="550" placeholder="<?php echo $this->lang->line("contact_notes_placeholder") ?>"><?php echo $data['note'] == "" ? "" : $data['note']; ?></textarea>
                                    <?php echo form_error('input-note', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8" style="margin-left: 23px;">
                            <a href="<?php echo base_url(); ?>contact"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("contact_btn_return") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("contact_btn_save") ?></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-label" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: #666; font-weight: 500;"><?php echo $this->lang->line("contact_modal_label_title") ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-0" style="max-height: 450px; overflow-y: auto;">
                <form id="form-labels-contact">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($labels as $label) :  $checked = in_array($label['id_label'], $contact_labels ?? []) ? 'checked' : ''; ?>

                            <li class="list-group-item d-flex align-items-center border-0 py-2">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" name="id_label[]" value="<?= $label['id_label'] ?>" class="custom-control-input" id="label-<?= $label['id_label'] ?>" <?= $checked ?>>
                                    <label class="custom-control-label" for="label-<?= $label['id_label'] ?>"></label>
                                </div>

                                <i class="fas fa-bookmark mr-3" style="color: <?= $label['color'] ?>; transform: rotate(90deg); font-size: 18px;"></i>
                                <span class="label-text-style"><?= $label['name'] ?></span>
                            </li>
                            
                        <?php endforeach; ?>
                    </ul>
                </form>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal" style="background-color: #6c757d; color: #ffff; border: none; font-weight: 600;"><?php echo $this->lang->line("contact_modal_label_btn_cancel") ?></button>
                <button type="button" id="btn-save-labels" class="btn btn-primary px-4" style="background-color: #2b6ede; border: none; font-weight: 600;"><?php echo $this->lang->line("contact_modal_label_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>

<input id="channel_selected" hidden />
<input id="btn-save-consolidate" hidden />
<input id="consolidated" hidden />
<input id="processing" hidden />
<input id="pending" hidden />
<input id="waiting" hidden />
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/util.js"></script>