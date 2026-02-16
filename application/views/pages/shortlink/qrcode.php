<div class="container-fluid mt--6">
    <input type="hidden" id="qrcode-value" value="<?php echo $link[0]['link']; ?>">
    <div class="row">
        <div class="col-xl-12 d-flex justify-content-center">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("shortlink_qrcode_title") ?> <a href='<?php echo $link[0]['link']; ?>'></a></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-4">
                                <div id="qrcode"></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo $this->lang->line("shortlink_qrcode_set_size") ?></label>
                                    <select class="form-control" id="qrcode-size" name="qrcode-size">
                                        <option value="64"><?php echo $this->lang->line("shortlink_qrcode_size_one") ?></option>
                                        <option value="128"><?php echo $this->lang->line("shortlink_qrcode_size_two") ?></option>
                                        <option value="256" selected><?php echo $this->lang->line("shortlink_qrcode_size_three") ?></option>
                                        <option value="512"><?php echo $this->lang->line("shortlink_qrcode_size_four") ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12" id="btn">
                                <button class="btn btn-primary" id="build-qrcode" type="submit"><?php echo $this->lang->line("shortlink_qrcode_btn_generate") ?></button>
                                <button class="btn btn-primary" id="download-qrcode" type="submit"><?php echo $this->lang->line("shortlink_qrcode_btn_download") ?></button>
                                <button class="btn btn-primary" id="print-qrcode" type="submit"><?php echo $this->lang->line("shortlink_qrcode_btn_print_out") ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>