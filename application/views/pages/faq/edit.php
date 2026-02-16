<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("setting_faq_edit_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("faq/save/$id"); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("setting_faq_edit_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-title"><?php echo $this->lang->line("setting_faq_title") ?></label>
                                    <input type="text" id="input-title" name="input-title" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("setting_faq_title_placeholder") ?>" value="<?php echo $data['title']; ?>">
                                    <?php echo form_error('input-title', '<div class="alert-field-validation">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="form-control-label" for="input-content"><?php echo $this->lang->line("setting_faq_edit_text") ?></label>
                                </div>
                            </div>
                        </div>
                        <div id="editor" data-toggle="quill" data-quill-placeholder="Quill WYSIWYG" class="ql-container ql-snow">
                            <div class="ql-editor ql-blank" data-gramm="false" contenteditable="true" data-placeholder="Quill WYSIWYG">
                                <p><?php echo $data['content']; ?></p>
                            </div>
                        </div>
                        <input type="hidden" id="input-content" name="input-content">
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>faq"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("setting_faq_btn_return") ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("setting_faq_btn_save") ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>