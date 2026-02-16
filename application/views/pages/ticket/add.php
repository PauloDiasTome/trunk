<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-10">
                            <h3 class="mb-0"><?php echo $this->lang->line("ticket_add_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("ticket/save"); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("ticket_add_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input_name_company"><?php echo $this->lang->line("ticket_company") ?></label>
                                    <div class="dropdown dropdown-responsivo">
                                        <div class="input-group mb-3">
                                            <input type="hidden" class="value-select" id="input_ticket_company" name="input_ticket_company">
                                            <input type="text" class="form-control dropdown-toggle" id="input_name_company" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" placeholder="<?php echo $this->lang->line("ticket_select") ?>" autocomplete="off" readonly>
                                            <i class="fas fa-chevron-down"></i>
                                            <div class="dropdown-menu links-responsivo" id="dropdown_ticket_company">
                                                <a class="dropdown-item" id="0" data-toggle="modal" data-target="#modal-company" onclick="clearFormModal()" title="<?php echo $this->lang->line("ticket_dropdown_add_new_ticket_company_title") ?>">
                                                    <b><?php echo $this->lang->line("ticket_dropdown_add_new_ticket_company") ?></b>
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <?php foreach ($company as $row) { ?>
                                                    <a class="dropdown-item opt <?php echo isset($row['id_company']) && $row['id_company'] == set_value('input_ticket_company') ? "OptSelected" : ""; ?>" id="<?php echo $row['id_company']; ?>" onclick="optSelected(this)"><?php echo $row['fantasy_name']; ?></a>
                                                <?php } ?>
                                            </div>
                                            <div class="alert-field-validation" id="company_validation" style="margin-top:48px;"></div>
                                            <?php echo form_error('input_ticket_company', '<div class="alert-field-validation" style="margin-top:48px;">', '</div>'); ?>
                                            <div class="alert-field-validation" id="alert__input_ticket_company" style="display: none; margin-top:48px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 ticket">
                                <div class="form-group" id="add-contact" style="position:relative">
                                    <label class="form-control-label" for="input-contact"><?php echo $this->lang->line("ticket_contact") ?></label>
                                    <input type="text" class="form-control" id="input-contact" name="name_contact" maxlength="50" placeholder="<?php echo $this->lang->line("ticket_search") ?>" value="<?php echo empty(set_value('name_contact')) ? "" : set_value('name_contact') ?>">
                                    <?php echo empty(set_value('input-contact')) ? "" : '<input type="hidden" class="input-contact" name="input-contact" value="' . set_value('input-contact') . '">' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input_type_name"><?php echo $this->lang->line("ticket_type") ?></label>
                                    <div class="dropdown dropdown-responsivo">
                                        <div class="input-group mb-3">
                                            <input type="hidden" class="value-select" id="input_ticket_type" name="input_ticket_type">
                                            <input type="text" class="form-control dropdown-toggle" id="input_type_name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" placeholder="<?php echo $this->lang->line("ticket_select") ?>" autocomplete="off" readonly>
                                            <i class="fas fa-chevron-down"></i>
                                            <div class="dropdown-menu links-responsivo" id="dropdown_ticket_type">
                                                <a class="dropdown-item" id="0" data-toggle="modal" data-target="#modal-ticket-type" onclick="clearFormModal()" title="<?php echo $this->lang->line("ticket_dropdown_add_new_ticket_type_title") ?>">
                                                    <b><?php echo $this->lang->line("ticket_dropdown_add_new_ticket_type") ?></b>
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <?php foreach ($type as $row) { ?>
                                                    <a class="dropdown-item opt ticket_type_opt <?php echo isset($row['id_ticket_type']) && $row['id_ticket_type'] == set_value('input_ticket_type') ? "OptSelected" : ""; ?>" id="<?php echo $row['id_ticket_type']; ?>" onclick="optSelected(this)"><?php echo $row['name']; ?></a>
                                                <?php } ?>
                                            </div>
                                            <div class="alert-field-validation" id="type_validation" style="margin-top:48px;"></div>
                                            <?php echo form_error('input_ticket_type', '<div class="alert-field-validation" style="margin-top:48px;">', '</div>'); ?>
                                            <div class="alert-field-validation" id="alert__input_ticket_type" style="display: none; margin-top:48px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group" id="dropdown_ticket_subtype">
                                    <label class="form-control-label" for="input-ticket-subtype"><?php echo $this->lang->line("ticket_subtype") ?></label>
                                    <select class="form-control" name="input-ticket-subtype" id="input-ticket-subtype">
                                        <?php if (isset($recover)) { ?>
                                            <option value="0" class="ticket-subtype"><?php echo $this->lang->line("ticket_select") ?></option>
                                            <?php foreach ($recover['subtype'] as $row) { ?>
                                                <option class="ticket-subtype" <?php echo set_value('input-ticket-subtype') == $row['id_ticket_type'] ? "selected" : "" ?>><?php echo $row['name']; ?></option>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <option value="0" class="ticket-subtype"><?php echo $this->lang->line("ticket_select") ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input_status_name"><?php echo $this->lang->line("ticket_status") ?></label>
                                    <div class="dropdown dropdown-responsivo">
                                        <div class="input-group mb-3">
                                            <input type="hidden" class="value-select" id="input_ticket_status" name="input_ticket_status">
                                            <input type="text" class="form-control dropdown-toggle" id="input_status_name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" placeholder="<?php echo $this->lang->line("ticket_select") ?>" autocomplete="off" readonly>
                                            <i class="fas fa-chevron-down"></i>
                                            <div class="dropdown-menu links-responsivo" id="dropdown_ticket_status">
                                                <a class="dropdown-item" id="0" data-toggle="modal" data-target="#modal-ticket-status" onclick="clearFormModal()" title="<?php echo $this->lang->line("ticket_dropdown_add_new_ticket_status_title") ?>">
                                                    <b><?php echo $this->lang->line("ticket_dropdown_add_new_ticket_status") ?></b>
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <?php foreach ($status as $row) { ?>
                                                    <a class="dropdown-item opt <?php echo isset($row['id_ticket_status']) && $row['id_ticket_status'] == set_value('input_ticket_status') ? "OptSelected" : ""; ?>" id="<?php echo $row['id_ticket_status']; ?>" onclick="optSelected(this)"><?php echo $row['name']; ?></a>
                                                <?php } ?>
                                            </div>
                                            <div class="alert-field-validation" id="status_validation" style="margin-top:48px;"></div>
                                            <?php echo form_error('input_ticket_status', '<div class="alert-field-validation" style="margin-top:48px;">', '</div>'); ?>
                                            <div class="alert-field-validation" id="alert__input_ticket_status" style="display: none; margin-top:48px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="form-control-label" for="input-comment"><?php echo $this->lang->line("ticket_comment") ?> <span style="color: red" id="count_character">1500</span></label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <textarea class="form-control" id="input-comment" name="input-comment" rows="10" resize="none" maxlength="1500"><?php echo (set_value('input-comment')) ? set_value('input-comment') : "" ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>ticket"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("ticket_btn_return") ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("ticket_btn_save") ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal adicionar empresa -->
<div class="modal fade show" id="modal-company" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:950px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("ticket_modal_company_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label" for="modalCompany__creation"><?php echo $this->lang->line("ticket_modal_company_creation") ?></label>
                            <input type="text" class="form-control" disabled value="<?php echo date('d/m/Y'); ?>" id="modalCompany__creation" name="modalCompany__creation">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label class="form-control-label" for="modalCompany__corporate_name"><?php echo $this->lang->line("ticket_modal_company_corporate_name") ?></label>
                            <input type="text" class="form-control" id="modalCompany__corporate_name" name="modalCompany__corporate_name" placeholder="<?php echo $this->lang->line("ticket_modal_company_corporate_name_placeholder") ?>">
                            <div class="alert-field-validation" id="alertCompany__corporate_name"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label" for="modalCompany__cnpj"><?php echo $this->lang->line("ticket_modal_company_cnpj") ?></label>
                            <input type="text" class="form-control" maxlength="14" min="1" id="modalCompany__cnpj" name="modalCompany__cnpj" placeholder="<?php echo $this->lang->line("ticket_modal_company_cnpj_placeholder") ?>" min="1" max="100">
                            <div class="alert-field-validation" id="alertCompany__cnpj"></div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label class="form-control-label" for="modalCompany__fantasy_name"><?php echo $this->lang->line("ticket_modal_company_fantasy_name") ?></label>
                            <input type="text" class="form-control" id="modalCompany__fantasy_name" name="modalCompany__fantasy_name" placeholder="<?php echo $this->lang->line("ticket_modal_company_fantasy_name_placeholder") ?>" min="3" max="100">
                            <div class="alert-field-validation" id="alertCompany__fantasy_name"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("ticket_modal_company_btn_return") ?></button>
                <button type="button" class="btn btn-green" onclick="saveCompany()"><?php echo $this->lang->line("ticket_modal_company_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal tipo do ticket -->
<div class="modal fade show" id="modal-ticket-type" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:950px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("ticket_modal_type_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="modalTicketType__name"><?php echo $this->lang->line("ticket_modal_type_name") ?></label>
                            <input type="text" class="form-control" id="modalTicketType__name" name="modalTicketType__name" maxlength="100" placeholder="<?php echo $this->lang->line("ticket_modal_type_name_placeholder") ?>" value="">
                            <div class="alert-field-validation" id="alertTicketType__name"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="modalTicketType__group"><?php echo $this->lang->line("ticket_modal_type_sector") ?></label>
                            <select class="form-control" id="modalTicketType__group" name="modalTicketType__group">
                                <option value="0" selected><?php echo $this->lang->line("ticket_select") ?></option>
                                <?php foreach ($group as $row) { ?>
                                    <option value="<?php echo $row['id_user_group']; ?>">
                                        <?php echo $row['name']; ?> </option>
                                <?php  } ?>
                            </select>
                            <div class="alert-field-validation" id="alertTicketType__group"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="modalTicketType__sla"><?php echo $this->lang->line("ticket_modal_type_sla") ?></label>
                            <select class="form-control" id="modalTicketType__sla" name="modalTicketType__sla" <?php echo !empty($ticket_sla) ? '' : 'disabled' ?>>
                                <option value="0" selected><?php echo !empty($ticket_sla) ? $this->lang->line("ticket_select") : $this->lang->line("ticket_modal_type_sla_null") ?></option>
                                <?php foreach ($ticket_sla as $row) { ?>
                                    <option value="<?php echo $row['id_ticket_sla']; ?>">
                                        <?php echo $row['name']; ?> </option>
                                <?php  } ?>
                            </select>
                            <div class="alert-field-validation" id="alertTicketType__sla"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("ticket_modal_type_btn_return") ?></button>
                <button type="button" class="btn btn-green" onclick="saveTicketType()"><?php echo $this->lang->line("ticket_modal_type_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>


<!-- Modal adicionar status do ticket -->
<div class="modal fade show" id="modal-ticket-status" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:950px;">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("ticket_modal_status_title") ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="modalStatus__name"><?php echo $this->lang->line("ticket_modal_status_name") ?></label>
                            <input type="text" id="modalStatus__name" name="modalStatus__name" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("ticket_modal_status_name_placeholder") ?>" value="">
                            <div class="alert-field-validation" id="alertStatus__name"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="modalStatus__color"><?php echo $this->lang->line("ticket_modal_status_cor") ?></label>
                            <input type="color" class="form-control" id="modalStatus__color" name="modalStatus__color" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label"><?php echo $this->lang->line("ticket_status_ticket_info") ?></label>
                        </div>
                    </div>
                </div>
                <div class="row mt--1">
                    <div class="col-lg-2">
                        <div class="custom-control custom-radio mb-2">
                            <input class="custom-control-input" name="modalStatus__is_open" id="modalStatus__is_open" type="radio" checked="true">
                            <label class="custom-control-label" for="modalStatus__is_open"><?php echo $this->lang->line("ticket_status_open") ?></label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="custom-control custom-radio mb-2">
                            <input class="custom-control-input" name="is_close" id="is_close" type="radio">
                            <label class="custom-control-label" for="is_close"><?php echo $this->lang->line("ticket_status_closed") ?></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("ticket_modal_status_btn_return") ?></button>
                <button type="button" class="btn btn-green" onclick="saveTicketStatus()"><?php echo $this->lang->line("ticket_modal_status_btn_save") ?></button>
            </div>
        </div>
    </div>
</div>