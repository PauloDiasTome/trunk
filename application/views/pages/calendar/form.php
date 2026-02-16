<?php echo form_open('calendar/save',array('id'=>'calendar-form')); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label class="form-control-label" for="exampleDatepicker">Data</label>
                <input class="form-control datepicker" name="date_start" id="date_start" placeholder="Selecionar data" type="text" value="<?php echo date('d/m/Y'); ?>" style="text-align: center!important;">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label class="form-control-label" for="exampleFormControlSelect1">Hora</label>
                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="time_start" id="time_start" class="form-control" readonly value="<?php echo date('H:i'); ?>" style="text-align: center!important;">
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
                <input type="text" id="input-title" name="input-title" class="form-control" placeholder="Título" value="" autcomplete="false">
                <?php echo form_error('input-title', '<div class="error">', '</div>'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label class="form-control-label" for="input-text">Texto</label>
                <textarea id="input-text" class="form-control" name="input-text" cols="4" rows="4" autcomplete="false"></textarea>
                <?php echo form_error('input-text', '<div class="error">', '</div>'); ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>