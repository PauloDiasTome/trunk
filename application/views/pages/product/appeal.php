<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("setting_catalog_appeal_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <?php echo form_open("product/appeal/save/$id"); ?>

                    <div class="row">

                        <div class="col-lg-2">
                            <div class="form-group">
                                <div style="height: 200px; width: 200px;">
                                    <img width="100%" src="<?php echo $data[0]["media_url"] ?>" style="border-radius: 7px;min-height:154px;max-height: 170px;">
                                    <input type="hidden" name="wa_product_id" value="<?php echo $data[0]["wa_product_id"] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-10">
                            <div class="form-group">
                                <div style="margin-left: 40px;margin-top: 28px;">
                                    <strong style="font-size: 18.3px;"><?php echo $data[0]["title"] ?></strong>
                                    <p>
                                        <span style="font-size: 14px;"><?php echo $this->lang->line("setting_catalog_appeal_product_denied") ?></span>
                                </div>

                                <div style="margin-left: 40px;margin-top: 28px;">
                                    <span><?php echo $this->lang->line("setting_catalog_appeal_product_denied_text") ?></span></br>
                                    <a target="_blank" href="https://www.whatsapp.com/policies/commerce-policy"><?php echo $this->lang->line("setting_catalog_appeal_know_more") ?></a>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input-description"><?php echo $this->lang->line("setting_catalog_appeal_request_reason") ?></label>
                                <textarea class="form-control" id="input-description" name="input-description" rows="5" placeholder="<?php echo $this->lang->line("setting_catalog_appeal_request_reason_placeholder") ?>" resize="none"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>product"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("setting_catalog_btn_return") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("setting_catalog_btn_save") ?></button>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 10px">
                        <div class="col-lg-12">
                            <?php echo form_error('input-description', '<div class="alert alert-danger alert-dismissible fade show">', '</div>'); ?>
                        </div>
                    </div>

                    <?php echo form_close(); ?>

                    </html>

                </div>

            </div>
        </div>
    </div>
</div>
</div>