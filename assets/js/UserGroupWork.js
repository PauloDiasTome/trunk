var doneTypingInterval = 1000;
var typingTimer;

function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.location.origin + "/user/group/work/find",
            type: "POST",
            crossDomain: true,
            data: {
                text: $("#search").val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [{
            mData: 'id_group'
        },
        {
            mData: 'name'
        },
        {
            mData: 'qtda'
        },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return "<img src='assets/img/group.svg' class='avatar rounded-circle mr-3'>";
                }
            },
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    return data + " inscritos";
                }
            },
            {
                targets: 3,
                render: function (data, type, full, meta) {
                    return "<a href='#' id='" + full.id_group + "' class='table-action table-action-edit' data-toggle='tooltip' data-original-title='Editar'>" +
                        "<i class='far fa-edit'></i></a>" +
                        "<a id='" + full.id_group + "' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='Deletar'>" +
                        "<i class='far fa-trash-alt'></i>" +
                        "</a>";
                }
            },
            {
                orderable: false,
                targets: [0]
            }
        ],
        pagingType: "numbers",
        pageLength: 10,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: true,
        searching: false,
        paginate: true,
        info: true,
        language: {
            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        },

        drawCallback: function (settings) {
            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            } else {
                $("#modalExport").attr('disabled', false);
            }
        }
    });
}


function doneTyping() {
    var text = $("#input-search").val();
    if (text.length > 0) {
        for (var i = 0; i < $(".contact-list .item").length; i++) {
            var item = $(".contact-list .item")[i].id;
            if (item.indexOf(text) > 0) {
                $("#" + item).css("display", "block");
            } else {
                $("#" + item).css("display", "none");
            }
        }
        for (var i = 0; i < $(".contact-list .item-selected").length; i++) {
            var item = $(".contact-list .item-selected")[i].id;
            if (item.indexOf(text) > 0) {
                $("#" + item).css("display", "block");
            } else {
                $("#" + item).css("display", "none");
            }
        }
    } else {
        for (var i = 0; i < $(".contact-list .item").length; i++) {
            $("#" + $(".contact-list .item")[i].id).css("display", "block");
        }
        for (var i = 0; i < $(".contact-list .item-selected").length; i++) {
            $("#" + $(".contact-list .item-selected")[i].id).css("display", "block");
        }
    }
}

$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });


    if ($("#search").val() !== undefined) {
        find();
    }


    $("#datatable-basic").on("click", ".table-action-edit", function () {
        window.location.href = window.location.origin + "/user/group/work/edit/" + this.id;
    });


    $(".contact-list").on("click", ".custom-control-input", function () {
        if (this.checked) {
            $("#" + $(this).data("id")).removeClass('item').addClass('item-selected');
        } else {
            $("#" + $(this).data("id")).removeClass('item-selected').addClass('item');
        }
    });


    $("#input-search").keyup(function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });


    $('#participant').on('itemRemoved', function (event) {
        $("#checked" + event.item).prop('checked', false)
        $("#" + event.item).removeClass('item-selected').addClass('item');
    });


    $(".contact-list").on("click", ".item", function () {
        if ($("#" + this.id).attr('class') == "item") {
            $("#checked" + this.id).prop('checked', true)
            $("#" + this.id).removeClass('item').addClass('item-selected');
            $('#participant').tagsinput('add', this.id);
        } else {
            $("#checked" + this.id).prop('checked', false)
            $("#" + this.id).removeClass('item-selected').addClass('item');
            $('#participant').tagsinput('remove', this.id);
        }
        for (var i = 0; i < $(".contact-list .item").length; i++) {
            $("#" + $(".contact-list .item")[i].id).css("display", "block");
        }
        for (var i = 0; i < $(".contact-list .item-selected").length; i++) {
            $("#" + $(".contact-list .item-selected")[i].id).css("display", "block");
        }
        $("#input-search").val("");
        $("#input-search").focus();
    });


    $(".btn-add-participant").on("click", function () {
        var html = "";
        $("#participants").html();
        for (var i = 0; i < $(".contact-list .item-selected").length; i++) {
            html += "<div style=\"width: 100%; height: 64px; float: left;\">";
            html += "<img class=\"avatar rounded-circle\" style=\"margin-top:7px;\" src=\"" + $(".contact-list .item-selected img")[i].src + "\">";
            html += "<span style=\"margin-left:20px;margin-top:15px;\"><b>" + $(".contact-list .item-selected")[i].dataset.name + "</b></span>";
            html += "</div>";
        }
        $("#participants-count").html("" + $(".contact-list .item-selected").length + " PARTICIPANTES")
        $("#participants").html(html);
        $("#input-participants").val($("#participant").val());
    });


    $(".contact-list").on("click", ".item-selected", function () {
        if ($("#" + this.id).attr('class') == "item") {
            $("#checked" + this.id).prop('checked', true)
            $("#" + this.id).removeClass('item').addClass('item-selected');
            $('#participant').tagsinput('add', this.id);
        } else {
            $("#checked" + this.id).prop('checked', false)
            $("#" + this.id).removeClass('item-selected').addClass('item');
            $('#participant').tagsinput('remove', this.id);
        }
        for (var i = 0; i < $(".contact-list .item").length; i++) {
            $("#" + $(".contact-list .item")[i].id).css("display", "block");
        }
        $("#input-search").val("");
        $("#input-search").focus();
    });


    $("#datatable-basic").on("click", ".table-action-delete", function () {
        swal({
            title: "Você tem certeza?",
            text: "Que deseja remover esse grupo?",
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger",
            confirmButtonText: "Sim",
            cancelButtonClass: "btn btn-secondary",
            cancelButtonText: "Não",
        }).then(t => {
            if (t.value == true) {
                $.post(window.location.origin + "/user/group/work/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: "Deletado!",
                        text: "Grupo removido com sucesso!",
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-primary"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });


    $("#add-participants").on("click", function () {
        $("#modal-add-participants").modal('show');
    });

    $("#import-participants").on("click", function () {
        $("#modal-import-participants").modal('show');
    });

    $(".btn-import-participant").on("click", function () {

        var count = 0;
        var contact = "";
        var text = $("#list-contacts").val();

        for (var i = 0; i < text.length; i++) {
            if (text[i] == '\n') {
                alert(contact);
                contact = "";
            } else {
                contact += text[i];
            }
        }

    });

    $('#sendEmailExport').on('click', () => modalExport());

});


function modalExport() {

    $.get(`/export/xlsx?
        &type=userGroupWork`, function (response) {

        Swal.fire({
            title: 'Lista enviada!',
            text: 'O e-mail pode demorar até uma hora para chegar.',
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ok!'
        });
    });

}