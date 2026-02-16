<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 d-inline-block mb-0 text-white"><?php echo $this->lang->line('community_participant_topnav'); ?></h6>
                </div>

                <div class="col-lg-6 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-filter" id="modalFilter"><?php echo $this->lang->line("community_participant_btn_filter") ?></button>
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-export" id="modalExport"><?php echo $this->lang->line('community_participant_btn_export'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card pb-3">
                <div class="card-header border-0">
                    <div class="management-rectangle">
                        <div class="profile">
                            <img src="<?php echo base_url("/assets/icons/panel/speaker.svg") ?>" alt="">
                        </div>
                        <div class="text">
                            <select class="name" id="select-community">
                                <option value="" data-count-participant="<?php echo $totalParticipant ?>"><?php echo $this->lang->line("community_participant_all") ?></option>
                                <?php foreach ($participant as $row) { ?>
                                    <option value="<?php echo $row['id_community'] ?>" data-count-participant="<?php echo $row['participantsCount'] ?>"><?php echo $row['name'] ?></option>
                                <?php } ?>
                            </select>
                            <span class="participant"><?php echo $totalParticipant ?> <?php echo $this->lang->line("community_participant") ?> </span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr role="row">
                                <th style="width: 12%"><?php echo $this->lang->line('community_participant_column_name'); ?></th>
                                <th style="width: 10%"><?php echo $this->lang->line('community_participant_column_contact'); ?></th>
                                <th style="width: 20%"><?php echo $this->lang->line('community_participant_column_community'); ?></th>
                                <th style="width: 10%"><?php echo $this->lang->line('community_participant_column_base_time'); ?></th>
                                <th style="width: 10%"><?php echo $this->lang->line('community_participant_column_accession_date'); ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="modal-filter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h6 class="modal-title" id="modal-title-notification"><?php echo $this->lang->line("community_participant_filter_title") ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" class="custom-control-input" id="check-search">
                                <label class="custom-control-label" for="check-search"><?php echo $this->lang->line("community_participant_filter_search") ?></label>
                            </div>
                            <input class="form-control" type="text" id="input-search" placeholder="<?php echo $this->lang->line("community_participant_filter_search_placeholder") ?>" id="input-title" style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="check-date">
                            <label class="custom-control-label" for="check-date"><?php echo $this->lang->line("community_participant_filter_period") ?></label>
                        </div>
                        <div class="row form-group mt-1">
                            <div class="col-6">
                                <input class="form-control" type="text" placeholder="<?php echo $this->lang->line("community_participant_filter_period_placeholder_date_start") ?>" id="dt-start" style="display: none;">
                            </div>
                            <div class="col-6">
                                <input class="form-control" type="text" disabled placeholder="<?php echo $this->lang->line("community_participant_filter_period_placeholder_date_end") ?>" id="dt-end" style="display: none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"><?php echo $this->lang->line("community_participant_filter_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="btn-search" data-dismiss="modal"><?php echo $this->lang->line("community_participant_filter_btn_search") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header b-bottom">
                <h5 class="modal-title"><?php echo $this->lang->line("community_participant_export_title"); ?></h5>
                <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pb-1">
                <div class="row">
                    <div class="form-group col-12" style="text-align:center;">
                        <img src="<?php echo base_url("assets/img/email.svg") ?>" style="width:60px;margin-bottom:-8px"><br>
                        <label class="form-control-label" for="emailExport"><?php echo $this->lang->line("community_participant_export_email") ?></label><br>
                        <b><?php echo $this->session->userdata('email_user') ?>?</b>
                        <input type="text" class="form-control" id="emailExport" placeholder="<?php echo $this->lang->line("community_participant_export_email_placeholder"); ?>" autocomplete="off" value="<?php echo $this->session->tempdata('email') ?>" disabled hidden>
                    </div>
                </div>
            </div>

            <div class="modal-footer b-top p-footer">
                <button type="button" class="btn btn-secondary btn-box-shadow" data-dismiss="modal"> <?php echo $this->lang->line("community_participant_export_btn_return") ?></button>
                <button type="button" class="btn btn-green" id="sendEmailExport" data-dismiss="modal"><?php echo $this->lang->line("community_participant_export_btn_confirm") ?></button>
            </div>
        </div>
    </div>
</div>