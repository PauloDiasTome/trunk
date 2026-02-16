
<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Novo</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open_multipart("invoice/save"); ?>
                    <h6 class="heading-small text-muted mb-4">Informações da Fatura</h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-creation">Registro</label>
                                    <input type="text" id="input-creation" class=" form-control" disabled value="<?php echo date('d/m/Y'); ?>">
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">                      
                                    <label class="form-control-label" for="select-company">Empresa</label>
                                    <select class="form-control" name="select-company" required>
                                        <option value=""> Selecione uma empresa</option>
                                        <?php foreach ($data['company'] as $row) { ?>
                                            <option value="<?php echo $row['id_company']; ?>">
                                                <?php echo $row['corporate_name']; ?></option>
                                        <?php  } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-expire">Vencimento</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        </div>
                                        <input type="text" id="input-expire" name="input-expire" class=" form-control datepicker" autocomplete="off" placeholder="dd/mm/aaaa" required value="<?php echo isset($_POST['input-expire']) ? $_POST['input-expire'] : ""; ?>">                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label">Status</label>
                                    <select class=" form-control" name="select-status">
                                        <option value="1" selected>Aberto</option>
                                        <option value="2" >Fechado</option>
                                        <option value="3" >Cancelado</option>
                                        <option value="4" >Revisão</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-description">Descrição</label>
                                    <input type="text" id="input-description" name="input-description" class=" form-control" placeholder="Descrição" required value="<?php echo isset($_POST['input-description']) ? $_POST['input-description'] : ""; ?>">
                                    <?php echo form_error('input-description', '<div class="error">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-value">Valor</label>
                                    <input type="text" id="input-value" name="input-value" class=" form-control" placeholder="Valor" required value="<?php echo isset($_POST['input-value']) ? $_POST['input-value'] : ""; ?>">
                                    <?php echo form_error('input-value', '<div class="error">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-date-payment">Data de Pagamento</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        </div>
                                        <input type="text" id="input-date-payment" name="input-date-payment" class=" form-control datepicker" autocomplete="off" placeholder="dd/mm/aaaa" value="<?php echo isset($_POST['input-date-payment']) ? $_POST['input-date-payment'] : ""; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>                      
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="file1">Boleto</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="file1" name="file1">
                                        <label class="custom-file-label" for="file1">Selecione o arquivo</label>
                                    </div>
                                </div>                             
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="file2">Nota fiscal</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="file2" name="file2">
                                        <label class="custom-file-label" for="file2">Selecione o arquivo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="file3">Comprovante Pagamento</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="file3" name="file3">
                                        <label class="custom-file-label" for="file3">Selecione o arquivo</label>
                                    </div>
                                </div>
                            </div>                  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <button class="btn btn-primary" type="submit">Salvar</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>