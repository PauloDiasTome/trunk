<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("report_protocol_topnav") ?></h6>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card" style="padding-bottom: 20px">
                <div class="card-header border-0">
                    <div class="row">
                        <div class="col-6">
                            <style>
                                .bootstrap-tagsinput input {
                                    display: none;
                                }

                                .bootstrap-tagsinput {
                                    color: transparent !important;
                                    border: none !important;
                                    background-color: transparent !important;
                                }
                            </style>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr role="row">
                                <th><?php echo $this->lang->line("report_protocol_column_date") ?></th>
                                <th><?php echo $this->lang->line("report_protocol_column_protocol") ?></th>
                                <th><?php echo $this->lang->line("report_protocol_column_contact") ?></th>
                                <th><?php echo $this->lang->line("report_protocol_column_id_talkall") ?></th>
                                <th><?php echo $this->lang->line("report_protocol_column_user") ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>