<input type="hidden" name="block" id="block" value="<?= isset($data[0]['id_approval']) ? "0" : "1" ?>">
<?php if (isset($data[0]['id_approval'])) { ?>
    <div class="container mt--5">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0"><?php echo $this->lang->line("whatsapp_broadcast_waba_add_title") ?></h3>
                            </div>
                        </div>
                    </div>

                    <?php echo form_open_multipart('publication/whatsapp/broadcast/waba/save', 'id="myform"'); ?>
                    <div class="card-body">
                        <div style="display: none">
                            <input type="hidden" id="groups" name="groups" value="<?php echo isset($data[0]['groups']) ? $data[0]['groups'] : ""; ?>" data-toggle="tags" />
                            <input type="hidden" name="submitted_approval" id="submitted_approval" value="2">
                            <input type="hidden" name="direction" id="direction" value="1">
                            <input name="headerType" id="headerType" value="" hidden>
                            <input name="headerText" id="headerText" value="" hidden>
                            <input name="bodyText" id="bodyText" value="" hidden>
                            <input name="footerText" id="footerText" value="" hidden>
                            <input name="buttonsParameters" id="buttonsParameters" value="" hidden>
                            <input name="bodyParameters" id="bodyParameters" value="" hidden>
                            <input type="hidden" name="id_approval" id="id_approval" value="<?= $data[0]['id_approval'] ?>">
                        </div>

                        <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("whatsapp_broadcast_waba_add_information") ?></h6>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input_title"><?php echo $this->lang->line("whatsapp_broadcast_waba_title") ?></label>
                                    <input type="text" class="form-control" name="input_title" id="input_title" maxlength="50" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_waba_title") ?>" value="<?php echo $data[0]['title'] ?>">
                                    <?php echo form_error('input_title', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_input_title"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="date_start"><?php echo $this->lang->line("whatsapp_broadcast_waba_date_scheduling") ?></label>
                                    <input type="text" class="form-control datepicker" name="date_start" id="date_start" maxlength="10" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_waba_date_scheduling_placeholder") ?>" value="<?php echo explode(" ", $data[0]['schedule'])[0]; ?>">
                                    <?php echo form_error('date_start', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_date_start"></div>
                                </div>
                            </div>

                            <div class=" col-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="time_start"><?php echo $this->lang->line("whatsapp_broadcast_waba_hour_scheduling") ?></label>
                                    <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                        <input type="text" class="form-control time" name="time_start" id="time_start" maxlength="5" value="<?php echo explode(" ", $data[0]['schedule'])[1]; ?>">
                                        <input type="hidden" class="form-control" id="timeStart">
                                    </div>
                                    <?php echo form_error('time_start', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_time_start"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="display:none;">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="channel_id"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_channel") ?></label><br>
                                    <div id="myselect">
                                        <select id="multiselect" name="others[]" multiple="multiple" style="display:none">
                                            <?php foreach ($channel as $row) { ?>
                                                <option value="<?php echo $row['id_channel']; ?>"> <?php echo $row['name'] ?> </option>
                                            <?php  } ?>
                                        </select>
                                        <?php echo form_error('others[]', '<div class="alert-field-validation">', '</div>'); ?>
                                        <div class="alert-field-validation" id="alert_multiselect"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="select_channel"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_channel") ?></label>
                                    <select class="form-control" name="select_channel" id="select_channel">
                                        <option value="0">Selecionar</option>
                                        <?php foreach ($channel as $row) { ?>
                                            <option value="<?php echo $row['id_channel']; ?>" <?= $data[0]['id_channel'] == $row['id_channel'] ? 'selected' : '' ?>> <?php echo $row['name'] ?> </option>
                                        <?php  } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label class="form-control-label">Total de contatos</label>
                                    <input type="text" class="form-control" id="info_contacts" disabled placeholder="0">
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label class="form-control-label">Contatos dentro da janela de 24h</label>
                                    <input type="text" class="form-control" id="info_fora_24h" disabled placeholder="0">
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label class="form-control-label">Contatos fora da janela de 24h</label>
                                    <input type="text" class="form-control" id="info_dentro_24h" disabled placeholder="0">
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="form-group">
                                    <label class="form-control-label">Valor estimado</label>
                                    <input type="text" class="form-control" id="info_price" disabled placeholder="00,00">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label class="custom-toggle mb-2">
                                    <input type="checkbox" id="toggle-segmented-group">
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="<?php echo $this->lang->line("whatsapp_broadcast_waba_segments_no") ?>" data-label-on="<?php echo $this->lang->line("whatsapp_broadcast_waba_segments_yes") ?>"></span>
                                </label>
                                <label class="form-control-label title-toggle-view"><?php echo $this->lang->line("whatsapp_broadcast_segments_campaign") ?></label>
                            </div>
                        </div>

                        <div class="row" id="segmented_group" style="display: none;">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="select_segmented_group"><?php echo $this->lang->line("whatsapp_broadcast_segments_select_group") ?></label>
                                    <select class="form-control" name="select_segmented_group" id="select_segmented_group">
                                        <option value="0"><?php echo $this->lang->line("whatsapp_broadcast_segments_select_group_placeholder"); ?></option>
                                    </select>
                                    <div class="alert-field-validation" id="alert_segmented_group"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="select_template"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_template") ?></label>
                                    <select class="form-control" name="select_template" id="select_template">
                                        <option value="0"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_template_placeholder") ?></option>
                                        <?php foreach ($templates as $row) { ?>
                                            <option value="<?php echo $row['id_template']; ?>" <?= $data[0]['id_template'] ==  $row['id_template'] ? 'selected' : '' ?> data-text="<?php echo $row['text_body'] ?>">
                                                <?php echo $row['name']; ?></option>
                                        <?php  } ?>
                                    </select>
                                    <div class="alert-field-validation" id="alert_template"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="view-template" style="display:none;">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="message-template"><?php echo $this->lang->line("whatsapp_broadcast_waba_mensagem") ?></label>
                                    <i class="fa fa-eye" style="margin-left: 10px;" onclick="alterParametros()" title="<?php echo $this->lang->line("whatsapp_broadcast_waba_add_information_view") ?>"></i>
                                    <textarea class="form-control" id="message-template" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_waba_contact_placeholder") ?>"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast/waba"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("whatsapp_broadcast_waba_btn_cancel") ?></button></a>
                                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#modal-approval" style="margin-left: 10px" id="modalApproval"><?php echo $this->lang->line("whatsapp_broadcast_waba_btn_approval") ?></button>
                            </div>
                        </div>

                        <div class="modal fade" id="modal-approval" tabindex="-1" role="dialog" aria-labelledby="modal-approval" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">

                                    <div class="modal-header b-bottom">
                                        <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_title") ?></h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox mb-1">
                                                        <input type="checkbox" class="custom-control-input" name="check_email" id="check_email">
                                                        <label class="custom-control-label" for="check_email"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_email") ?></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox mb-1">
                                                        <input type="checkbox" class="custom-control-input" name="check_whatsapp" id="check_whatsapp">
                                                        <label class="custom-control-label" for="check_whatsapp"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_whatsapp") ?></label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="alert-field-validation" id="alert_checkbox_email_whatsapp"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for=""><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_group_contacts") ?></label>
                                                    <select class="form-control" name="select_segmented_group_approval" id="select_segmented_group_approval">
                                                        <option value="0"><?php echo $this->lang->line("whatsapp_broadcast_waba_segments_select_group_placeholder"); ?></option>
                                                        <?php for ($i = 0; $i < count($Groups); $i++) { ?>
                                                            <option value="<?php echo $Groups[$i]["id_group_contact"] ?>">
                                                                <?php echo $Groups[$i]["name"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="alert-field-validation" id="alert_select_segmented_group_approval"></div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="approval_message"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_message") ?></label>
                                                    <textarea class="form-control" name="approval_message" id="approval_message" cols="30" rows="5" style="resize: none;" maxlength="1024" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_message_placeholder") ?>"></textarea>
                                                    <div class="alert-field-validation" id="alert_approval_message"></div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <span class="text-sm"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_info") ?></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer b-top p-footer">
                                        <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_modal_return") ?></button>
                                        <button type="button" class="btn btn-green" id="btn-send"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_modal_modal_send") ?></button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } else { ?>

    <div class="modal fade" id="modal-no-registers" tabindex="-1" role="dialog" aria-labelledby="modal-no-registers" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header b-bottom text-center">
                    <h6 class="modal-title w-100" id="modal-title-notification"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_no_records_modal_title") ?></h6>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            <img src="https://app.talkall.com.br/assets/img/broadcast_approval/Warning-Computer-TalkAll.png" class="mb-4" alt="approval" width="80px">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-center font-weight-normal"><?php echo $this->lang->line("whatsapp_broadcast_waba_approval_no_records_modal_message") ?></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php }  ?>