<input type="hidden" id="edit" value="<?php echo $data['edit'] ?>">
<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("whatsapp_broadcast_waba_edit_title") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart("publication/whatsapp/broadcast/waba/save/" . $data['token']); ?>

                <div class="card-body">
                    <div id="parameters" style="display: none">
                        <input type="hidden" name="groups" id="groups" value="<?php echo isset($data['groups']) ? $data['groups'] : ""; ?>" data-toggle="tags">
                        <input type="hidden" name="direction" id="direction" value="1">
                        <input type="hidden" name="headerType" id="headerType" value="">
                        <input type="hidden" name="headerText" id="headerText" value="">
                        <input type="hidden" name="bodyText" id="bodyText" value="">
                        <input type="hidden" name="footerText" id="footerText" value="">
                        <input type="hidden" name="buttonsParameters" id="buttonsParameters" value="">
                        <input type="hidden" name="bodyParameters" id="bodyParameters" value="">
                        <input type="hidden" name="is_limited_time" id="is_limited_time" value="<?php echo $data['is_limited_time'] ?>">
                        <input type="hidden" name="id_channel" id="id_channel" value="<?php echo $data['id_channel'] ?>">
                        <input type="hidden" name="last_date" id="last_date" value="<?php echo $data['schedule'] ?>">
                        <input type="hidden" id="id_template" value="<?php echo $data['id_template'] ?>">
                        <input type="hidden" id="json_parameters" value='<?php echo json_encode($data['json_parameters']) ?>'>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input_title"><?php echo $this->lang->line("whatsapp_broadcast_waba_title") ?></label>
                                <input type="text" class="form-control" name="input_title" id="input_title" maxlength="100" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_waba_title") ?>" value="<?php echo $data['broadcast_title'] != "" ? $data['broadcast_title'] : "";  ?>">
                                <?php echo form_error('input_title', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_input_title"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date_start"><?php echo $this->lang->line("whatsapp_broadcast_waba_date_scheduling") ?></label>
                                <i class="far fa-question-circle fa-xs" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line("whatsapp_broadcast_waba_date_tooltip") ?>"></i>
                                <input type="text" class="form-control datepicker" name="date_start" id="date_start" maxlength="10" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_waba_date_scheduling_placeholder") ?>" value="<?php echo $data['schedule'] != "" ? explode(" ", $data['schedule'])[0] : date('d/m/Y'); ?>">
                                <?php echo form_error('date_start', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_date_start"></div>
                            </div>
                        </div>

                        <div class=" col-6">
                            <div class="form-group">
                                <label class="form-control-label" for="time_start"><?php echo $this->lang->line("whatsapp_broadcast_waba_hour_scheduling") ?></label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" class="form-control time" name="time_start" id="time_start" maxlength="5" placeholder="<?php echo $this->lang->line("whatsapp_broadcast_waba_hour_scheduling_placeholder") ?>" value="<?php echo $data['schedule'] != "" ? explode(" ", $data['schedule'])[1] : date('H:i') ?>">
                                </div>
                                <?php echo form_error('time_start', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_time_start"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="select_channel"><?php echo $this->lang->line("whatsapp_broadcast_waba_edit_selected_channel"); ?></label><br>
                                <div id="myselect" style="display: none">
                                    <select class="form-control" name="select_channel" id="select_channel" style="display:none">
                                        <option selected class="form-control" value="<?php echo $data['id_channel']; ?>"><?php echo $data['channel_name']; ?></option>
                                    </select>
                                </div>
                                <select disabled class="form-control">
                                    <option selected class="form-control" value="<?php echo $data['id_channel']; ?>"><?php echo $data['channel_name']; ?></option>
                                </select>
                                <div class="alert-field-validation" id="alert_multiselect"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display:<?php echo $data['persona_id'] != "0" ? "none" : "flex"; ?>">
                        <div class="col-12">
                            <label class="custom-toggle mb-2">
                                <input type="checkbox" id="toggle-segmented-group" disabled <?php $data['persona_id'] != "0" ? "checked" : "";  ?>>
                                <span class="custom-toggle-slider rounded-circle custom-toggle-slider-segmented" data-label-off="<?php echo $this->lang->line("whatsapp_broadcast_waba_segments_no"); ?>"></span>
                            </label>
                            <label class="form-control-label title-toggle-view" for=""><?php if ($data['persona_id'] == "0") echo $this->lang->line("whatsapp_broadcast_waba_edit_no_segmented") ?></label>
                        </div>
                    </div>

                    <div class="row" id="segmented_group" style="display:<?php echo $data['persona_id'] != "0" ? "flex" : "none"; ?>">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="select_segmented_group"><?php echo $this->lang->line("whatsapp_broadcast_waba_edit_selected_group"); ?></label>
                                <div id="myselect" style="display: none">
                                    <select class="form-control" name="select_segmented_group" id="select_segmented_group">
                                        <option selected class="form-control" value="<?php echo $data['persona_id']; ?>"></option>
                                    </select>
                                </div>
                                <select disabled class="form-control">
                                    <option selected class="form-control" value="<?php echo $data['persona_id']; ?>"><?php if ($data['persona_id'] != "0") echo $data['persona_name']; ?></option>
                                </select>
                                <div class="alert-field-validation" id="alert_segmented_group"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="select_template"><?php echo $this->lang->line("whatsapp_broadcast_waba_edit_selected_template") ?></label>
                                <div id="myselect" style="display: none">
                                    <select class="form-control" name="select_template" id="select_template">
                                        <?php foreach ($template as $row) { ?>
                                            <option selected class="channel_<?php echo $row['account_key_remote_id']; ?>" value="<?php echo $row['id_template'] ?>" data-buttons="<?php echo $row['buttons'] ?>" data-text="<?php echo $row['text_body'] ?>" data-footer="<?php echo $row['text_footer'] ?>" data-header-type="<?php echo $row['header_type'] ?>" data-header="<?php echo $row['header'] ?>"></option>
                                        <?php  } ?>
                                    </select>
                                </div>
                                <select disabled class="form-control">
                                    <option selected class="form-control" value="<?php echo $data['id_template'] ?>"><?php echo $data['template_name'] ?></option>
                                </select>
                                <div class="alert-field-validation" id="alert_template"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row template-whatsapp-broadcast-view">
                        <div class="wa-template">
                            <div class="col-12">

                                <div class="row justify-content-center">
                                    <div class="wa-header">
                                        <div class="col-12 col-img">
                                            <img src="https://app.talkall.com.br/assets/img/broadcast_approval/header_wa.jpeg" draggable="false" alt="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="wa-body">
                                        <div class="col align-self-end justify-content-end">
                                            <div class="box-broadcast-approval" draggable="false"></div>
                                            <div class="buttons-quick-reply"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="wa-footer">
                                        <div class="col col-img">
                                            <img src="https://app.talkall.com.br/assets/img/broadcast_approval/footer_wa.png" draggable="false" alt="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast/waba"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("whatsapp_broadcast_waba_btn_return") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("whatsapp_broadcast_waba_btn_save") ?></button>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('/assets/lib/build_pdf/build_pdf.js') ?>"></script>