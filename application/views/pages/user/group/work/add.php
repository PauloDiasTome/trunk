<div class="container mt--5">
    <div class="row">

        <div class="col-xl-4 order-xl-2 participants">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h3><?php echo $this->lang->line("usergroup_work_participants") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <button class="btn btn-info" id="add-participants"><?php echo $this->lang->line("usergroup_work_participants_add"); ?></button>
                                    <label class="label-count" id="participants-count"><?php echo $this->lang->line("usergroup_work_participants_count") ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row list-participants">
                            <div class="col-lg-12" id="participants"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("usergroup_work_add_title") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open("user/group/work/save"); ?>
                <div class="card-body">
                    <input id="input-participants" name="input-participants" type="hidden" value="" />
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("usergroup_work_add_information"); ?></h6>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label" for="input-name"><?php echo $this->lang->line("usergroup_work_name") ?></label>
                                <input type="text" id="input-name" name="input-name" class="form-control" placeholder="<?php echo $this->lang->line("usergroup_work_placeholder"); ?>" value="">
                                <?php echo form_error('input-name', '<div class="alert-field-validation">', '</div>'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>user/group/work"><button class="btn btn-danger" type="button"><?php echo $this->lang->line("usergroup_work_btn_cancel") ?></button></a>
                            <button class="btn btn-success" type="submit"><?php echo $this->lang->line("usergroup_work_btn_save") ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
                
            </div>
        </div>
    </div>
</div>


<div class="modal fade show" id="modal-add-participants" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <span data-icon="x-light" data-dismiss="modal">
                    <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="position:absolute; cursor:pointer; right:15px; top:14px;">
                        <path fill="#FFF" d="M19.058 17.236l-5.293-5.293 5.293-5.293-1.764-1.764L12 10.178 6.707 4.885 4.942 6.649l5.293 5.293-5.293 5.293L6.707 19 12 13.707 17.293 19l1.765-1.764z">
                        </path>
                    </svg>
                </span>
                <h5 class="modal-title">
                    <?php echo $this->lang->line("usergroup_work_participants_add") ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>

            <div class="modal-body">

                <div class="modal-search">
                    <img src="<?php echo base_url("assets/icons/panel/search.svg") ?>" alt="">
                    <input type="text" class="form-control" id="input-search" name="input-search" placeholder="<?php echo $this->lang->line("usergroup_work_participants_add_placeholder") ?>">
                </div>

                <div class="modal-title">
                    <h3><?php echo $this->lang->line("usergroup_work_users") ?></h3>
                </div>

                <div class="main-modal">
                    
                    <div class="contact-list">
                        
                        <?php foreach ($data as $row) { ?>
                            <div class="item" id="<?php echo $row['key_remote_id']; ?>" data-name="<?php echo $row['full_name']; ?>" data-id="<?php echo $row['key_remote_id']; ?>">
                                <table>
                                    <tr>
                                        <td class="p-2">
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input class="custom-control-input" data-id="<?php echo $row['key_remote_id']; ?>" id="checked<?php echo $row['key_remote_id']; ?>" type="checkbox">
                                                <label class="custom-control-label" for="checked<?php echo $row['full_name']; ?>"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <img class="avatar rounded-circle mr-3" src="<?php echo $row['profile']; ?>" />
                                        </td>
                                        <td class="name">
                                            <span><?php echo $row['full_name']; ?></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success btn-add-participant" data-dismiss="modal"><?php echo $this->lang->line("usergroup_work_btn_confirm") ?></button>
                    </div>
                </div>
                <div style="height: 70px; border-top: solid 1px #e1e1e1;">    
                </div>
            </div>
            
        </div>
    </div>
</div>