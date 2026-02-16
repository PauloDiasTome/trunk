<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("setting_catalog_add_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <?php echo form_open_multipart('product/save') ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("setting_catalog_add_information") ?></h6>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-control-label" for="input-creation"><?php echo $this->lang->line("setting_catalog_register") ?></label>
                                <input type="text" id="input-creation" disabled="true" name="input-creation" class="form-control" value="<?php echo date('d/m/Y'); ?>">
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <label class="form-control-label" for="input-title"><?php echo $this->lang->line("setting_catalog_product_name") ?></label>
                                <input type="text" id="input-title" name="input-title" class="form-control" placeholder="<?php echo $this->lang->line("setting_catalog_product_name_placeholder") ?>" value="">
                                <?php echo form_error('input-title', '<div class="alert-field-validation">', '</div>'); ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label" for="input-code"><?php echo $this->lang->line("setting_catalog_code") ?></label>
                                <input type="text" id="input-code" name="input-code" class="form-control" placeholder="<?php echo $this->lang->line("setting_catalog_code_placeholder") ?>" value="">
                                <?php echo form_error('input-code', '<div class="alert-field-validation">', '</div>'); ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label" for="input-currency"><?php echo $this->lang->line("setting_catalog_coin") ?></label>
                                <select class="form-control" id="input-currency" name="input-currency">
                                    <option value="BRL"><?php echo $this->lang->line("setting_catalog_coin_type") ?><i class="fas fa-toggle-on"></i></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label" for="input-price"><?php echo $this->lang->line("setting_catalog_price") ?></label>
                                <input type="text" id="input-price" name="input-price" class="form-control" placeholder="<?php echo $this->lang->line("setting_catalog_price_placeholder") ?>" value="">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input-url"><?php echo $this->lang->line("setting_catalog_url") ?></label>
                                <input type="text" id="input-link" name="input-url" class="form-control" value="https://">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input-description"><?php echo $this->lang->line("setting_catalog_description") ?></label>
                                <textarea class="form-control" id="input-description" name="input-description" rows="5" resize="none"></textarea>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" class="input-hidden" id="addFile" name="addFile" value="mockFile">

                    <div class="dropzone" id="my-dropzone" name=""></div>

                    <div class="dropzone-previews dropzone" style="margin-top: 22px"></div><br><br>

                    <div id="input-files"></div>

                    <div class="row">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>product"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("setting_catalog_btn_cancel") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("setting_catalog_btn_save") ?></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>

                    </html>
                    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
                </div>

            </div>
        </div>
    </div>
</div>
</div>