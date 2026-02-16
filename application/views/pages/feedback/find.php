<!-- Header -->
<!-- Header -->
<div class="header bg-primary pb-4" style="background: linear-gradient(119deg, rgba(95,189,152,1) 00%, rgba(29,191,215,1) 100%);">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Comentários - Não respondidos</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container mt--5">
    <div class="row">
        <div class="col-sm-5 col-md-4 order-sm-1">
            <div class="card" style="width: 100%; margin-top:20px;">
                <div class="card-header" style="padding: 10px">
                    <h3 style="float: left; margin-left: 20px; margin-top: 8px;">Filtrar</h3>
                </div>
                <div class="card-body">
                    <label>Período</label>
                    <div class="input-daterange datepicker row align-items-center">
                        <div class="row--20" style="width: 100%;">
                            <div class="col-sm-5 col-md-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Data inicial" type="text" value="<?php echo date('d/m/Y'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row--20" style="width: 100%;">
                            <div class="col-sm-5 col-md-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Data final" type="text" value="<?php echo date('d/m/Y'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5 col-md-12">
                            <label>Canal</label>
                            <select class="form-control" id="exampleFormControlSelect1">
                                <option>Supermercado Viscardi</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5 col-md-12">
                            <button type="button" class="btn btn-primary" style="width: 100%; margin-top:32px;">Filtrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-5 col-md-8 order-sm-0" id="feedback">
            <?php foreach ($data as $row) { ?>
                <div class="card" style="width: 100%; margin-top:20px;">
                    <div class="card-header" style="padding: 10px;">
                        <div class="col-sm-5 col-md-12">
                            <img src="<?php echo $row['profile'];?>" style="float: left; width: 42px; border-radius: 50px;">
                            <h3 style="float: left; margin-left: 20px; margin-top: 8px;"><?php echo $row['full_name']. " - " . $row['name'] . " - " . $row['creation']; ?></h3>
                            <div class="avatar-group" style="float: right;"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                            <?php foreach ($row['messages'] as $message) { ?>
                                <div class="timeline-block">
                                    <span class="timeline-step" style="width: 64px;">
                                        <?php if ($message['key_from_me'] == 1) { ?>
                                            <img class="img-thumbnail" style="border: none;border-radius: 100%; width: 48px;" src="<?php echo $row['profile']; ?>">
                                        <?php } ?>
                                        <?php if ($message['key_from_me'] == 2) { ?>
                                            <img class="img-thumbnail" style="border: none;border-radius: 100%; width: 48px;" src="<?php echo base_url(); ?>profile/profile.svg">
                                        <?php } ?>
                                    </span>
                                    <div class="timeline-content">
                                        <small class="text-muted font-weight-bold"><?php echo $message['time']; ?></small>
                                        <p class=" text-sm mt-1 mb-0"><?php echo $message['data']; ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="mensagem" placeholder="Responder...">
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>