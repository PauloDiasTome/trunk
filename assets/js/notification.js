$(document).ready(function () {

    setNotification();

});

function setNotification() {

    var userToken = window.localStorage.getItem('userToken');

    var url = document.location.origin + "/notification/getNotification";
    var dados = new FormData();
    dados.append('key_remote_id', userToken);

    $.ajax({
        type: "POST",
        url: url,
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data) {
                $("#modal-notification").attr("notificationType", data[0].type);
                $("#modal-notification-title").text(data[0].title);
                readMarkdownFile(data[0].media_url);
                modalType(data[0].type);
                $("#modal-notification").attr("notificationId", data[0].id_user_notification);
            }
        },
        error: function () {
            console.log("Error setNotification");
        }
    });


    function modalType(type) {
        if (type === '2') {
            $("#notification-accept").text("Aceitar").attr("disabled", 'disabled');
            let modalBody = document.getElementById('modal-body-notification');
            setTimeout(() => {
                if (modalBody.scrollHeight > 320) {
                    modalBody.addEventListener('scroll', function (e) {
                        if (modalBody.scrollTop >= (modalBody.scrollHeight - modalBody.offsetHeight - 12)) {
                            $("#notification-accept").removeAttr('disabled');
                        }
                    });
                } else {
                    $("#notification-accept").removeAttr('disabled');
                }
            }, 500);
        }
    }


    function readMarkdownFile(url) {

        $.ajax({
            url: url,
            type: "GET",
            data: dados,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data) {
                    target = document.getElementById('modal-notification-content'),
                        converter = new showdown.Converter(),
                        target.innerHTML = converter.makeHtml(data);
                    $("#modal-notification").modal({ backdrop: "static" });
                    $("#modal-notification").modal("show");
                }

            },
            error: function () {
                console.log("Error readMarkdownFile");
            }
        });
    }


    $("body").on("click", "#notification-accept", function () {
        $("#modal-notification").modal("hide");

        var dados = new FormData();
        var id = $("#modal-notification").attr("notificationId");
        var type = $("#modal-notification").attr("notificationType");
        var url = document.location.origin + "/notification/markasread";
        var showTimestamp = Date.now().toLocaleString().replace(/\./g, '').slice(0, 10);

        dados.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));
        dados.append('id_user_notification', id);
        dados.append('show_timestamp', showTimestamp);

        if (type === '2') {
            var acceptTimestamp = Date.now().toLocaleString().replace(/\./g, '').slice(0, 10);
            dados.append('accept', 2);
            dados.append('accept_timestamp', acceptTimestamp);
            dados.append('enable_scroll_bottom', 2);
        }

        $.ajax({
            url: url,
            type: "POST",
            data: dados,
            processData: false,
            contentType: false,
            success: function () {
                $("#modal-notification").remove();
            },
            error: function () {
                console.log("Erro notification-accept");
            }
        });

    });
}