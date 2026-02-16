<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Novo</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo form_open("product/status/save"); ?>
                    <h6 class="heading-small text-muted mb-4">Produto status</h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">Nome</label>
                                    <input type="text" id="input-name" name="input-name" class="form-control" placeholder="Nome do status do produto(Exemplo: em análise)" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="textarea-message">Mensagem</label>
                                    <textarea class="form-control" id="textarea-message" name="textarea-message" rows="3" resize="none"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="form-control-label" for="input-tags">Cor</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group" style="border: 1px solid #dee2e6; border-radius: .25rem; padding: .625rem .75rem;">
                                    <input class="form-control" id="input-color" name="input-color" type="color" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">Esse status significa que o Produto está?</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt--1">
                            <div class="col-lg-2">
                                <div class="custom-control custom-radio mb-2">
                                    <input class="custom-control-input" name="is_open" id="is_open" type="radio" checked="true">
                                    <label class="custom-control-label" for="is_open">Aberto</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="custom-control custom-radio mb-2">
                                    <input class="custom-control-input" name="is_close" id="is_close" type="radio">
                                    <label class="custom-control-label" for="is_close">Fechado</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-8">
                                <button class="btn btn-success" type="submit">Salvar</button>
                                <a href="<?php echo base_url(); ?>product/status"><button class="btn btn-danger" type="button">Cancelar</button></a>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-lg-12">
                                <?php echo form_error('input-name', '<div class="alert alert-danger alert-dismissible fade show">', '</div>'); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>