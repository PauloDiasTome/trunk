<?php echo form_open('calendar/save'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label class="form-control-label" for="exampleDatepicker">Data</label>
                <input class="form-control datepicker" name="date_start" placeholder="Selecionar data" type="text" value="<?php echo date('d/m/Y'); ?>" style="text-align: center!important;">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label class="form-control-label" for="exampleFormControlSelect1">Hora</label>
                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="time_start" class="form-control" readonly value="<?php echo date('H:i'); ?>" style="text-align: center!important;">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label class="form-control-label" for="input-title">Título</label>
                <input type="text" id="input-title" name="input-title" class="form-control" placeholder="Título" value="">
                <?php echo form_error('input-title', '<div class="error">', '</div>'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label class="form-control-label" for="input-text">Texto</label>
                <input type="text" id="input-text" name="input-text" class="form-control" placeholder="Texto" value="">
                <?php echo form_error('input-text', '<div class="error">', '</div>'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <button class="btn btn-primary" type="submit" data-dismiss="modal">Salvar</button>
        </div>
    </div>
</div>

<?php echo form_close(); ?>