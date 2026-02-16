<div class="container mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("setting_worktime_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($data['id_work_time'])) {
                        $hidden = array(
                            'id_work_time' => $data['id_work_time'],
                        );
                        echo form_open("worktime/edit/{$data['id_work_time']}", array('id' => 'worktime'), $hidden);
                    } else {
                        echo form_open("worktime/edit", array('id' => 'worktime'));
                    }

                    ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("setting_worktime_information") ?></h6>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label" for="name"><?php echo $this->lang->line("setting_worktime_subtitle_label") ?></label>
                                <input type="text" class="form-control <?php echo form_error("name") != "" ? 'is-invalid' : "" ?>" name="name" maxlength="100" id="input-name" value="<?php echo isset($data['name']) ? $data['name'] : ""  ?>" placeholder="<?php echo $this->lang->line("setting_worktime_subtitle_placeholder") ?>">
                                <?php echo form_error('name', '<div class="alert-field-validation">', '</div>'); ?>
                            </div>
                            <div class="alert-field-validation" id="alert__input-name" style="display: none; margin-top: -21px;"></div>
                        </div>

                        <div class="col-10 mt-3 mb-4">
                            <?php
                            $diasemana = array(7 => $this->lang->line("setting_worktime_sunday"), 1 => $this->lang->line("setting_worktime_monday"), 2 => $this->lang->line("setting_worktime_tuesday"), 3 => $this->lang->line("setting_worktime_wednesday"), 4 => $this->lang->line("setting_worktime_thursday"), 5 => $this->lang->line("setting_worktime_friday"), 6 => $this->lang->line("setting_worktime_saturday"));
                            foreach ($diasemana as $key => $value) {
                                $start = "";
                                $end = "";
                            ?>
                                <div class="form-group mb-4">
                                    <div class="input-group">
                                        <div class="custom-control custom-control-alternative custom-checkbox pt-2" style="width: 100px;">
                                            <input class="custom-control-input" onchange="inputGroup('<?php echo $key ?>')" id="<?php echo $key ?>-checkbox" type="checkbox" 
                                            <?php if (isset($data['work_time_week'])) {
                                                foreach ($data['work_time_week'] as $dados) {
                                                    if ((int)$dados["week"] == $key) {
                                                        echo "checked";
                                                        $start = $dados["start"];
                                                        $end = $dados["end"];
                                                    }
                                                }
                                            } ?>>
                                            <label class="custom-control-label" for="<?php echo $key ?>-checkbox"><?php echo $value ?></label>
                                        </div>
                                        <input type="text" value="<?php echo $start ?>" class="form-control mx-2 time <?php echo form_error("{$key}-end") != "" ? 'is-invalid' : "" ?>" name="<?php echo $key ?>-start" id="<?php echo $key ?>-start" placeholder="<?php echo $this->lang->line("setting_worktime_start_placeholder") ?>" <?php echo $start == "" ? "disabled" : "" ?> />
                                        <?php echo form_error("{$key}-start", '<div class="alert-field-validation" style="margin-left: 110px; margin-top: 47px;">', '</div>'); ?>
                                        <div class="alert-field-validation" id="alert__<?php echo "{$key}-start"?>" style="display:none; margin-left: 110px; margin-top: 47px;"></div>
                                        <input type="text" value="<?php echo $end ?>" class="form-control mx-2 time <?php echo form_error("{$key}-end") != "" ? 'is-invalid' : "" ?>" name="<?php echo $key ?>-end" id="<?php echo $key ?>-end" placeholder="<?php echo $this->lang->line("setting_worktime_end_placeholder") ?>" <?php echo $start == "" ? "disabled" : "" ?> />
                                        <?php echo form_error("{$key}-end", '<div class="alert-field-validation" style="margin-left: 500px; margin-top: 47px;">', '</div>'); ?>
                                        <div class="alert-field-validation" id="alert__<?php echo "{$key}-end" ?>" style="display:none; margin-left: 500px; margin-top: 47px;"></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <a href="<?php echo base_url(); ?>worktime"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("setting_worktime_btn_return") ?></button></a>
                        <input type="submit" value="<?php echo $this->lang->line("setting_worktime_btn_save") ?>" class="btn btn-success">
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>