<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("ticket_history") ?></h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <?php foreach ($event as $row) { ?>
                        <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                            <div class="timeline-block">
                                <span class="timeline-step badge-success">
                                    <i class="fas fa-clipboard-list"></i>
                                </span>
                                <div class="timeline-content" style="max-width: 100%;">
                                    <small class="text-muted font-weight-bold"><?php echo $row['creation']; ?></small>
                                    <h5 class=" mt-3 mb-0"><?php echo $row['last_name']; ?></h5>
                                    <p class=" text-sm mt-1 mb-0" style="white-space: break-spaces;"><?php echo $row['comment']; ?></p>
                                    <div class="mt-3">
                                        <span class="badge badge-pill badge-success" style="color: black; background-color: <?php echo $row['type_color']; ?>"><?php echo $row['type']; ?></span>
                                        <span class="badge badge-pill badge-success" style="color: black; background-color: <?php echo $row['status_color']; ?>"><?php echo $row['status']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-lg-8 mt-5">
                        <a href="<?php echo base_url(); ?>ticket">
                            <button class="btn btn-primary" type="submit"><?php echo $this->lang->line("ticket_btn_return") ?></button>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>