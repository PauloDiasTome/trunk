<html>
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/argon.min.css?v=1.0.0" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/messenger.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/kanban.css" type="text/css" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <title>TalkAll | Kanban</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
</head>
<body>
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-xl col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0 bg-light">
                <div class="card-body">
                    <div class="row d-flex justify-content-between pl-3 pr-3">
                        <div>
                            <h5 class="card-title text-uppercase text-muted mb-0">AGUARDANDO ATENDIMENTO</h5>
                            <span id="waiting-service-count" class="h2 font-weight-bold mb-0">0</span>
                        </div>
                        <div>
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0 bg-light">
                <div class="card-body">
                    <div class="row d-flex justify-content-between pl-3 pr-3">
                        <div>
                            <h5 class="card-title text-uppercase text-muted mb-0">Em atendimento</h5>
                            <span id="in-service-count" class="h2 font-weight-bold mb-0">0</span>
                        </div>
                        <div>
                            <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                                <i class="fas fa-headset"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0 bg-light">
                <div class="card-body">
                    <div class="row d-flex justify-content-between pl-3 pr-3">
                        <div>
                            <h5 class="card-title text-uppercase text-muted mb-0">Em espera</h5>
                            <span id="on-hold-count" class="h2 font-weight-bold mb-0">0</span>
                        </div>
                        <div>
                            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                <i class="fas fa-pause-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg-6 d-none d-md-block closed-column">
            <div class="card card-stats mb-4 mb-xl-0 bg-light">
                <div class="card-body">
                    <div class="row d-flex justify-content-between pl-3 pr-3">
                        <div>
                            <h5 class="card-title text-uppercase text-muted mb-0">Encerrados hoje</h5>
                            <span id="closed-count" class="h2 font-weight-bold mb-0">0</span>
                        </div>
                        <div>
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0 bg-light">
                <div class="card-body">
                    <div class="row d-flex justify-content-between pl-3 pr-3">
                        <div>
                            <h5 class="card-title text-uppercase text-muted mb-0">USUÁRIOS ONLINE/TOTAL</h5>
                            <span id="online-users-count" class="h2 font-weight-bold mb-0">0</span>
                            <span class="h2 font-weight-bold mb-0">de</span>
                            <span id="total-users-count" class="h2 font-weight-bold mb-0">0</span>
                        </div>
                        <div>
                            <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row flex-row flex-sm-nowrap py-3">
       <div class="col-lg-6 col-xl">
            <div class="card bg-light list">
                <div id="waiting-service" class="card-body">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl">
            <div class="card bg-light list">
                <div id="in-service" class="card-body">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl">
            <div class="card bg-light list">
                <div id="on-hold" class="card-body">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl closed-column">
            <div class="card bg-light list">
                <div id="closed-today" class="card-body">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl">
            <div class="card bg-light list">
                <div id="online-users" class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- conn -->
<div class="conn" style="display: none; background: #eee;z-index: 9999;position: fixed;width: 100%;height: 100%;top: 0px;left: 0px;">
    <div class="box">
        <div class="info" style="background-color: #fff;border-radius: 3px;box-shadow: 0 17px 50px 0 rgba(0,0,0,.19), 0 12px 15px 0 rgba(0,0,0,.24);padding: 22px 24px 20px;box-sizing: border-box;display: -webkit-flex;display: flex;overflow: hidden;width: 400px;-webkit-flex-direction: column;flex-direction: column;-webkit-flex: none;flex: none;margin: 19% auto;">
            <span style="font: inherit;font-size: 100%;vertical-align: baseline;outline: none;margin: 0;padding: 23px;border: 0">O Kanban está aberto em outra janela. Clique em "Usar aqui" para usar o Kanban nesta janela.</span>
            <div style="display: -webkit-flex;display: flex;-webkit-flex-wrap: wrap-reverse;flex-wrap: wrap-reverse;-webkit-justify-content: flex-end;justify-content: flex-end;overflow: hidden;white-space: nowrap;">
                <input type="button" class="conn-close" value="Fechar" style="cursor:pointer; color: #07bc4c;padding: 10px 24px;text-transform: uppercase;position: relative;font-size: 14px;transition: box-shadow .18s ease-out,background .18s ease-out,color .18s ease-out;margin-right: 10px;background: white;border: 0px;">
                <input type="button" class="conn-refresh" value="USAR AQUI" style="cursor:pointer; text-transform: uppercase;position: relative;font-size: 14px;transition: box-shadow .18s ease-out,background .18s ease-out,color .18s ease-out;">
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal " id="modal-chat" chatId="" tabindex="-1" role="dialog" aria-labelledby="modal-chatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex justify-content-between" >
                    <div class="d-flex">
                        <img id="modal-profile-contact" class="rounded-circle profile-left" src="assets/img/avatar.svg">
                        <div class="d-flex flex-column justify-content-center">
                            <span id="modal-name-contact" class="font-weight-bold"></span>
                            <small id="modal-number-contact" class="d-block font-weight-400"></small>
                        </div>
                    </div>   
                    <div>
                        <div id="modal-status" class="diamond"></div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="user-info d-flex flex-column justify-content-center">
                            <span id="modal-name-user" class="font-weight-bold"></span>
                            <small id="modal-departament-user" class="d-block font-weight-400"></small>
                        </div>
                       
                        <div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="messenger">
                    <div class="right">
                        <div class="body">
                            <div class="chat">
                                <div class="messages"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="btn-options-footer">
                    <button id="transfer-chat" type="button" class="btn btn-primary">Transferir Atendimento</button>
                    <button id="close-chat" type="button" class="btn btn-light">Fechar Atendimento</button>
                </div>
                <div id="form-transfer">
                    <div>
                        <div>
                            <label class="form-control-label" for="select-user-group">Setor</label>
                            <select id="select-user-group" class="form-control"></select>
                        </div>
                        <div>
                            <label class="form-control-label" for="select-user">Atendente</label>
                            <select id="select-user"class="form-control"></select>
                        </div>
                        <button id="btn-transfer-ok" type="button" class="btn btn-primary">Transferir</button>
                        <button id="btn-transfer-cancel" type="button" class="btn btn-light">cancelar</button>
                    </div>
                </div>
                <div id="confirm-close-chat">
                    <div>
                        <span>Você tem certeza que deseja encerrar esse atendimento?</span>
                        <div>
                            <button id="close-chat-yes" type="button" class="btn btn-primary">Sim</button>
                            <button id="close-chat-no" type="button" class="btn btn-light">Não</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- JS -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/randomColor.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/kanbanTicket.js"></script>
<script>
    userToken = "kanban-" + localStorage.getItem("userToken");
    if (userToken == null) {
        window.location.href = "account/login";
    }
</script>
</body>
</html>