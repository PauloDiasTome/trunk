<input type="hidden" id="cmd" value="query">
<input type="hidden" id="resource" value="<?php echo $title; ?>">
<input type="hidden" id="search" value="">
<!-- Header -->
<!-- Header -->
<div class="header bg-primary pb-4">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
            </div>
        </div>
    </div>
</div>
<div class="container mt--6">
    <div class="row">
        <div class="col-sm-5 col-md-4 order-sm-1">
            <div class="card" style="width: 100%; margin-top:20px;">
                <div class="card-header" style="padding: 10px">
                    <h3 style="float: left; margin-left: 20px; margin-top: 8px;">Filtrar</h3>
                </div>
                <div class="card-body">
                    <label >Período</label>  
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
                            <label >Canal</label>  
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
        </div>
    </div>
</div>