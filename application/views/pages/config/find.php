<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("config_channel_config") ?></h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a id="integration-add" class="btn btn-sm btn-neutral"><?php echo $this->lang->line("config_btn_new") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card" style="padding-bottom: 20px">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0"><?php echo $this->lang->line("config_channel_list_to_config") ?></h3>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo $this->lang->line("config_name") ?></th>
                                <th><?php echo $this->lang->line("config_id_talkall") ?></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("config_notification_title") ?></h6>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <button type="button" class="btn  btn-icon">
                    <span class="btn-inner--icon"><i class="fas fa-globe-asia"></i></span>
                    <span class="btn-inner--text"><?php echo $this->lang->line("config_notification_widget") ?></span>
                </button>
                <button type="button" class="btn  btn-icon">
                    <span class="btn-inner--icon"><i class="fab fa-whatsapp"></i></span>
                    <span class="btn-inner--text"><?php echo $this->lang->line("config_notification_whatsapp") ?></span>
                </button>
                <a href="<?php echo base_url('facebook/oauth'); ?>" target="_blank">
                    <button type="button" class="btn  btn-icon">
                        <span class="btn-inner--icon"><i class="fab fa-facebook"></i></span>
                        <span class="btn-inner--text"><?php echo $this->lang->line("config_notification_facebook") ?></span>
                    </button>
                </a>
                <button type="button" class="btn  btn-icon" style="margin-top:10px;">
                    <span class="btn-inner--icon"><i class="fab fa-telegram"></i></span>
                    <span class="btn-inner--text"><?php echo $this->lang->line("config_notification_telegram") ?></span>
                </button>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal"><?php echo $this->lang->line("config_notification_btn_close") ?></button>
            </div> -->
        </div>
    </div>
</div>