<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("permission_add_title")  ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("permission/save", array('onsubmit' => 'Send();')) ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("permission_add_information") ?></h6>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label" for="name"><?php echo $this->lang->line("permission_name") ?></label>
                                <input type="text" class="form-control" id="name" name="name" maxlength="100" placeholder="<?php echo $this->lang->line('permission_name_placeholder'); ?>">
                                <?php echo form_error('name', '<div class="alert-field-validation">', '</div>'); ?>
                                <div class="alert-field-validation" id="alert_name"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="container-permission">
                                <div class="title">
                                    <span><?php echo $this->lang->line("available_selected") ?></span>
                                </div>
                                <div class="button-group mb-3">
                                    <span class="predefined-groups management-group"><?php echo $this->lang->line("permission_add_btn_manager") ?></span>
                                    <span class="predefined-groups attendant-group"><?php echo $this->lang->line("permission_add_btn_attendance") ?></span>
                                    <span class="predefined-groups financial-group"><?php echo $this->lang->line("permission_add_btn_financier") ?></span>
                                </div>
                                <!-- Dashboard -->
                                <div class="permissionBox">
                                    <div class="title-group single-option" onclick="showGroup(this)">
                                        <i class="far fa-chart-bar" style="width: 9px"></i>
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'dashboard') { ?>
                                                <label class="form-control-label" id="<?php echo $key ?>"><?php echo lang($key); ?></label>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- Contatos -->
                                <div class="permissionBox">
                                    <div class="title-group multiple-options" onclick="showGroup(this)">
                                        <i class="fas fa-id-badge text-primary"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('contact') ?></label>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'contact' || $key == 'persona' || $key == 'label' || $key == 'block_list') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="<?php echo $key; ?>" onclick="selectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- Comunidade -->
                                <div class="permissionBox">
                                    <div class="title-group single-option" onclick="showGroup(this)">
                                        <i class="fas fa-users" style="color:#FFAB00; width: 12px"></i>
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'community') { ?>
                                                <label class="form-control-label" id="<?php echo $key; ?>"><?php echo lang($key); ?></label>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- Usuários -->
                                <div class="permissionBox">
                                    <div class="title-group multiple-options" onclick="showGroup(this)">
                                        <i class="fas fa-user-friends text-orange"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('user') ?></label>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'user' || $key == 'replies' || $key == 'permission' || $key == 'usergroup' || $key == 'usercall') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="<?php echo $key; ?>" onclick="selectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- Messenger -->
                                <div class="permissionBox">
                                    <div class="title-group single-option" onclick="showGroup(this)">
                                        <i class="fas fa-comment-dots text-info"></i>
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'messenger') { ?>
                                                <label class="form-control-label" id="<?php echo $key; ?>"><?php echo lang($key); ?></label>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- Publicações -->
                                <div class="permissionBox">
                                    <div class="title-group multiple-options" onclick="showGroup(this)">
                                        <i class="fas fa-bullhorn" style="color: #71b093!important;"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('publication') ?></label>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'publication_facebook' || $key == 'publication_instagram' || $key == 'publication_whatsapp_newsletter'|| $key == 'publication_whatsapp_community' 
                                                || $key == 'publication_whatsapp_broadcast' || $key == 'publication_whatsapp_status' || $key == 'publication_whatsapp_waba') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="<?php echo $key; ?>" onclick="selectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- ticket -->
                                <div class="permissionBox">
                                    <div class="title-group single-option" onclick="showGroup(this)">
                                        <i class="fas fa-clipboard-list"></i>
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'ticket') { ?>
                                                <label class="form-control-label" id="<?php echo $key; ?>"><?php echo lang($key); ?></label>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- kanban -->
                                <div class="permissionBox">
                                    <div class="title-group multiple-options" onclick="showGroup(this)">
                                        <i class="ni ni-archive-2 text-red"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('kanban') ?></label>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'kanban_attendance' || $key == 'kanban_communication') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="<?php echo $key; ?>" onclick="selectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- report -->
                                <div class="permissionBox">
                                    <div class="title-group multiple-options" onclick="showGroup(this)">
                                        <i class="ni ni-single-copy-04 text-success"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('report') ?></label>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'report_call' || $key == 'evaluate_report' || $key == 'report_send') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="<?php echo $key; ?>" onclick="selectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- configuração -->
                                <div class="permissionBox">
                                    <div class="title-group multiple-options" onclick="showGroup(this)">
                                        <i class="ni ni-settings-gear-65"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('config') ?></label>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'chatbot' || $key == 'faq' || $key == 'work_time' || $key == 'templates' || $key == 'company' || $key == 'integration') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="<?php echo $key; ?>" onclick="selectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="permissionBox">
                                    <div class="title-group single-option" onclick="showGroup(this)">
                                        <i class="fas fa-donate"></i>
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'myinvoice') { ?>
                                                <label class="form-control-label" id="<?php echo $key; ?>"><?php echo lang($key); ?></label>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--/// Preview das permissões ///-->
                        <div class="col-6">
                            <div class="container-permission-preview">
                                <div class="title mb-3">
                                    <span><?php echo $this->lang->line("permission_selected") ?></span>
                                </div>

                                <!-- Dashboard -->
                                <div class="permissionBox" style="display:none">
                                    <div class="title-group single-option">
                                        <i class="far fa-chart-bar" style="width: 9px"></i>
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'dashboard') { ?>
                                                <label class="form-control-label" id="option__<?php echo $key; ?>" onclick="deselectGroup(this)"><?php echo lang($key); ?></label>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Contatos -->
                                <div class="permissionBox" style="display:none">
                                    <div class="title-group multiple-options" onclick="showGroupPreview(this)">
                                        <i class="fas fa-id-badge text-primary"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('contact') ?></label>
                                        <i class="fas fa-angle-right icon-angle"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'contact' || $key == 'persona' || $key == 'label' || $key == 'block_list') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="option__<?php echo $key; ?>" onclick="deselectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php  } ?>
                                    </div>
                                </div>

                                <!-- Comunidade -->
                                <div class="permissionBox" style="display:none">
                                    <div class="title-group single-option">
                                        <i class="fas fa-users" style="color:#FFAB00; width: 12px"></i>
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'community') { ?>
                                                <label class="form-control-label" id="option__<?php echo $key; ?>" onclick="deselectGroup(this)"><?php echo lang($key); ?></label>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Usuários -->
                                <div class="permissionBox" style="display:none">
                                    <div class="title-group multiple-options" onclick="showGroupPreview(this)">
                                        <i class="fas fa-user-friends text-orange"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('user') ?></label>
                                        <i class="fas fa-angle-right icon-angle"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'user' || $key == 'replies' || $key == 'usergroup' || $key == 'permission' || $key == 'usercall') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="option__<?php echo $key; ?>" onclick="deselectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Messenger -->
                                <div class="permissionBox" style="display:none">
                                    <div class="title-group single-option">
                                        <i class="fas fa-comment-dots text-info"></i>
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'messenger') { ?>
                                                <label class="form-control-label" id="option__<?php echo $key; ?>" onclick="deselectGroup(this)"><?php echo lang($key); ?></label>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Publicações -->
                                <div class="permissionBox" style="display:none">
                                    <div class="title-group multiple-options" onclick="showGroupPreview(this)">
                                        <i class="fas fa-bullhorn" style="color: #71b093!important;"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('publication') ?></label>
                                        <i class="fas fa-angle-right icon-angle"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'publication_facebook' || $key == 'publication_instagram' || $key == 'publication_whatsapp_newsletter'|| $key == 'publication_whatsapp_community' 
                                            || $key == 'publication_whatsapp_broadcast' || $key == 'publication_whatsapp_status' || $key == 'publication_whatsapp_waba') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="option__<?php echo $key; ?>" onclick="deselectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- ticket -->
                                <div class="permissionBox" style="display:none">
                                    <div class="title-group single-option">
                                        <i class="fas fa-clipboard-list"></i>
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'ticket') { ?>
                                                <label class="form-control-label" id="option__<?php echo $key; ?>" onclick="deselectGroup(this)"><?php echo lang($key); ?></label>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- kanban -->
                                <div class="permissionBox" style="display:none">
                                    <div class="title-group multiple-options" onclick="showGroupPreview(this)">
                                        <i class="ni ni-archive-2 text-red"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('kanban') ?></label>
                                        <i class="fas fa-angle-right icon-angle"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'kanban_attendance' || $key == 'kanban_communication') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="option__<?php echo $key; ?>" onclick="deselectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- report -->
                                <div class="permissionBox" style="display:none">
                                    <div class="title-group multiple-options" onclick="showGroupPreview(this)">
                                        <i class="ni ni-single-copy-04 text-success"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('report') ?></label>
                                        <i class="fas fa-angle-right icon-angle"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'report_call' || $key == 'evaluate_report' || $key == 'report_send') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="option__<?php echo $key; ?>" onclick="deselectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php  } ?>
                                    </div>
                                </div>
                                <!-- configuração -->
                                <div class="permissionBox mb-1" style="display:none">
                                    <div class="title-group multiple-options" onclick="showGroupPreview(this)">
                                        <i class="ni ni-settings-gear-65"></i>
                                        <label class="form-control-label"><?php echo $this->lang->line('config') ?></label>
                                        <i class="fas fa-angle-right icon-angle"></i>
                                    </div>
                                    <div class="col-12">
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'chatbot' || $key == 'faq' || $key == 'work_time' || $key == 'templates' || $key == 'company' || $key == 'integration') { ?>
                                                <div class="form-check" style="display:none;">
                                                    <li class="form-check-label" id="option__<?php echo $key; ?>" onclick="deselectOption(this)"><?php echo lang($key); ?></li>
                                                </div>
                                            <?php  } ?>
                                        <?php  } ?>
                                    </div>
                                </div>
                                <div class="permissionBox" style="display:none">
                                    <div class="title-group single-option">
                                        <i class="fas fa-donate"></i>
                                        <?php foreach ($data as $key => $value) {
                                            if ($key == 'myinvoice') { ?>
                                                <label class="form-control-label" id="option__<?php echo $key; ?>" onclick="deselectGroup(this)"><?php echo lang($key); ?></label>
                                            <?php  } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4 mb-2">
                        <div class="col-12">
                            <label class="custom-toggle mb-2">
                                <input type="checkbox" id="advanced-settings">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="<?php echo $this->lang->line("permission_advanced_no") ?>" data-label-on="<?php echo $this->lang->line("permission_advanced_yes") ?>"></span>
                            </label>
                            <label class="form-control-label title-toggle-view" style="position:absolute;left:90px;top:3px;"><?php echo $this->lang->line("permission_advanced_title") ?></label>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <h5 class="text-center" style="margin-bottom: -5px;"><?php echo $this->lang->line("permission_advanced_settings_desc") ?></h5>
                                <input type="text" data-toggle="tags" maxlength="15" class="ip_address form-control" value="<?php echo $data['ip_list']; ?>" name="ip_list" id="ip_list" disabled>
                            </div>
                        </div>
                    </div>

                    <a href="<?php echo base_url(); ?>permission"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("permission_btn_cancel"); ?></button></a>
                    <button class="btn btn-success" type="submit"><?php echo $this->lang->line("permission_btn_save"); ?></button>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>