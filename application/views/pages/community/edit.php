<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("community_edit_title") ?></h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <?php echo form_open("community/save/{$data['id_community']}") ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("community_edit_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12 col-profile">
                                <div class="box-picture-profile-config">
                                    <label class="form-control-label"><?php echo $this->lang->line("config_edit_channel_profile_title") ?></label>
                                    <div class="picture-profile transition-effect">
                                        <i class="fas fa-camera icon-add-photo" id="addProfile"></i>
                                        <span class="picture-profile-title"><?php echo $this->lang->line("config_edit_channel_change_profile"); ?></span>
                                        <img src="<?php echo $data['pictures'] ==  "" ? base_url("assets/icons/panel/profile_default.svg") : $data['pictures']  ?>" alt="preview">
                                        <input type="file" class="form-control" id="inputFile" accept="image/png, image/gif, image/jpeg, image/jpg, image/jfif" data-file="picture" onchange="handleFiles(this)" style="display: none;" />
                                        <input type="hidden" name="input-picture" id="input-picture" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3"></div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo $this->lang->line("community_creation") ?></label>
                                    <input type="text" class="form-control" value="<?php echo $data['creation']; ?>" disabled>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-name"><?php echo $this->lang->line("community_name") ?></label>
                                    <input type="text" id="input-name" name="input-name" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("community_name_placeholder") ?>" value="<?php echo $data['name_community']; ?>">
                                    <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                                    <div class="alert-field-validation" id="alert_input_name"></div>
                                    <div class="alert-field-validation" id="alert__input-name" style="display: none;"></div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo $this->lang->line("community_participant") ?></label>
                                    <input type="text" class="form-control" value="<?php echo $data['participantsCount']; ?>" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo $this->lang->line("community_channel") ?></label>
                                    <input type="text" class="form-control" value="<?php echo $data['name_channel']; ?>" disabled>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo $this->lang->line("community_community_id") ?></label>
                                    <input type="text" class="form-control" value="<?php echo $data['key_remote_id']; ?>" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo $this->lang->line("community_description") ?></label>
                                    <textarea class="form-control" cols="30" rows="4" style="resize: none;" maxlength="800" disabled><?php echo "" ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>community"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("community_btn_return") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("community_btn_save") ?></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>