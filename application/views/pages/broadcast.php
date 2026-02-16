<input type="hidden" id="cmd" value="query">
<input type="hidden" id="resource" value="<?php echo $title; ?>">
<input type="hidden" id="search" value="">
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-sm-6 col-md-8">
                    <h6 class="h2 text-white d-inline-block mb-0">Broadcast</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                        </ol>
                    </nav>
                </div>
                <div class="col-sm-2 col-md-4 text-right">
                    <a href="<?php echo base_url() . "broadcast/add"; ?>" class="btn btn-sm btn-neutral">Adicionar</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container mt--6">
    <div class="row" id="list-items">
        <div class="col-sm-5 col-md-12">
            <div class="card">
                <div class="table-responsive py-4">
                    <table class="table table-flush" id="datatable-basic">
                        <thead class="thead-light">
                            <tr>
                                <th>Scheduled</th>
                                <th>channel</th>
                                <th>Message</th>
                                <th>status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>