<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Produto status</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="status/add" class="btn btn-sm btn-neutral">Novo</a>
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
                            <h3 class="mb-0">Produto status</h3>
                        </div>
                        <div class="col-6" align="right">
                            <button id="export" class="btn btn-success" data-toggle="modal" data-target="#modal-export">Exportar para CSV</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="datatable-basic" cellspacing="0" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th>Nome</th>
                                <th>Menssagem</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informe um e-mail para a exportação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="min-height: auto">
                <div class="row">
                    <div class="form-group col-12">
                        <label class="form-control-label">E-mail</label>
                        <input type="text" class="form-control" id="emailExport" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="sendEmailExport">Enviar</button>
            </div>
        </div>
    </div>
</div>