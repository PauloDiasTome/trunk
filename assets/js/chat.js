var typingTimer;
var doneTypingInterval = 2000;
var quoted_id = 0;
var item_id = 0;
var bShowPopMenu = false;

function setChat(id) {

    $("#sub-more").hide();
    $(".popmenu").remove();

    if ($("#" + id).attr('class') == 'item itemSelected') {
        $(".input-text").focus();
        return;
    }

    creation = 0;
    last_date = "";

    $(".right").show();
    $(".option").show();
    $(".messages").html("");

    $(".messages").scrollTop(0);

    if (selected_chat != 0) {
        $("#" + selected_chat).removeClass("itemSelected");
        $("#" + selected_chat).addClass("item");
    }

    if ($(".input-text").html().length > 0) {
        localStorage.setItem(selected_chat, $(".input-text").html());
    } else {
        localStorage.removeItem(selected_chat);
    }

    $("#" + id).toggleClass("itemSelected");
    $("#" + id + " .no-read-message").hide();
    $("#" + id + " .no-read-message label ").html(0);

    var html = "";

    if ($("#" + id + " .color").val() != "") {

        var html = "<span style='float: left;'>" + $("#" + id + " .short_name span").html() + "</span>";

        var colors = $("#" + id + " .color").val();
        var names = $("#" + id + " .label").val();

        for (var i = 0; i < colors.split(",").length; i++) {
            html += "<div id='chat-labels' style= 'background: " + colors.split(",")[i] + "'>" + names.split(",")[i] + "</div>";
        }
    } else {
        html = "<span style='float: left;'>" + $("#" + id + " .short_name").html();
    }

    html += "<i class='fas fa-tags' style='margin-left: 10px;margin-top: 1px;'></i>";
    $(".caption").html(html);

    $(".status").html("");
    $(".right .head .picture img").attr("src", $("#" + id + " .picture img").attr("src"));
    $(".option .profile img").attr("src", $("#" + id + " .picture img").attr("src"));
    $(".input-text").focus();

    bUpdate = true;

    var json = { Cmd: "Presence", "key_remote_id": selected_chat, "type": "wait" };
    socket.send(JSON.stringify(json));

    selected_chat = id;

    $(".input-text").focus();

    if (localStorage.getItem(selected_chat) != null) {
        document.execCommand('insertText', false /*no UI*/, localStorage.getItem(selected_chat));
    } else {
        $(".input-text").html("");
    }

    $("#talkall_id").val(selected_chat);
    $("#name").val($("#" + id + " .short_name span").html());

    var json = { Cmd: "Chat", "key_remote_id": id, "creation": creation };
    socket.send(JSON.stringify(json));

    var json = { Cmd: "Read", "key_remote_id": id };
    socket.send(JSON.stringify(json));

    var json = { Cmd: "queryPresence", "key_remote_id": id };
    socket.send(JSON.stringify(json));

    if (parseInt($("#" + selected_chat + " .private").val()) == 2) {
        $("#info").hide();
        $("#info-note").hide();
        $("#chat-waiting").hide();
        $("#chat-trans").hide();
        $("#chat-labels").hide();
        $(".fa-tags").hide();
        $("#chat-ticket").hide();
        $("#chat-spam-report").hide();
        $("#chat-report-span").hide();
        $(".fa-user-edit").hide();
    } else {
        $("#info").show();
        $("#info-note").show();
        $("#chat-waiting").show();
        $("#chat-trans").show();
        $("#chat-labels").show();
        $(".fa-tags").show();
        $("#chat-ticket").show();
        $(".fa-user-edit").show();
        $("#chat-report-span").show();
        $("#chat-spam-report").show();
    }

    switch (parseInt($("#" + selected_chat + " .type").val())) {
        case 1:
            bReplyMsg = true;
            bDeleteMsg = true;
            $(".input .text").width("calc(100% - 86px)");
            $("#file-upload").show();
            $("#record-audio").show();
            break;
        case 2:
            bReplyMsg = true;
            bDeleteMsg = true;
            $(".input .text").width("calc(100% - 86px)");
            $("#file-upload").show();
            $("#record-audio").show();
            break;
        case 9:
            bReplyMsg = true;
            bDeleteMsg = false;
            $(".input .text").width("calc(100% - 86px)");
            $("#file-upload").show();
            $("#record-audio").show();
            break;
        case 11:
            bReplyMsg = true;
            bDeleteMsg = false;
            bViewPost = true;
            $("#file-upload").hide();
            $("#record-audio").hide();
            $(".input .text").width("calc(100% - 86px)");
            break;
        default:
            $(".input .text").width("calc(100% - 86px)");
            $("#file-upload").show();
            $("#record-audio").show();
            bReplyMsg = false;
            bDeleteMsg = false;
            bViewPost = false;
            break;
    }

    var json = { Cmd: "queryInfo", "key_remote_id": selected_chat };
    socket.send(JSON.stringify(json));

    updateMessageNoRead();
}

function hidePopMenus() {
    $("#sub-contact-add").hide();
    $(".popmenu").hide();
}


function dialogUserBasicEdit() {

    $("#sub-more").hide();
    $("#modal .box .items").html("");

    var lbName = document.createElement("span");
    lbName.textContent = "Nome";
    lbName.style.width = "100%";
    lbName.style.cssFloat = "left";
    lbName.style.marginBottom = "10px";

    var txtName = document.createElement("input");
    txtName.type = "text";
    txtName.id = "edit-name";
    txtName.style.width = "100%";
    txtName.style.cssFloat = "left";
    txtName.style.marginBottom = "0px";
    txtName.value = $("#name").val();

    var lbEmail = document.createElement("span");
    lbEmail.textContent = "Email";
    lbEmail.style.width = "100%";
    lbEmail.style.cssFloat = "left";
    lbEmail.style.marginTop = "10px";
    lbEmail.style.marginBottom = "10px";

    var txtEmail = document.createElement("input");
    txtEmail.type = "text";
    txtEmail.id = "edit-email";
    txtEmail.style.width = "100%";
    txtEmail.style.cssFloat = "left";
    txtEmail.style.marginBottom = "0px";
    txtEmail.value = $("#email").val();

    var ok = document.createElement("input");
    ok.type = "button";
    ok.className = "success"
    ok.id = "btn-contact-edit-ok";
    ok.value = "Salvar";

    var cancel = document.createElement("input");
    cancel.type = "button";
    cancel.className = "cancel"
    cancel.id = "btn-contact-edit-cancel";
    cancel.value = "Cancelar";

    $("#modal .box .items").append(lbName);
    $("#modal .box .items").append(txtName);
    $("#modal .box .items").append(lbEmail);
    $("#modal .box .items").append(txtEmail);
    $("#modal .box .items").append(ok);
    $("#modal .box .items").append(cancel);
    $("#modal .box .head span").html("Editar contato");

    $("#modal").show();
}


$(document).ready(function () {

    $("#list-find .item").live("click", function () {
        var json = { Cmd: "Open", "to": this.id };
        socket.send(JSON.stringify(json));
    });

    $(".fa-user-edit").live("click", function () {
        dialogUserBasicEdit();
    });

    $("#list-wait .item").live("click", function () {
        hidePopMenus();
        setChat(this.id);
    });

    $("#quoted").live("click", function () {
        quoted_id = item_id;
        $(".reply-message .message").html($("#" + item_id + " .textMessage span").html());
        $(".reply-message").show();
        $(".input-text").focus();
        $(".popmenu").remove();
        $("#" + item_id + " .dropdown").hide();
        bShowPopMenu = false;
    });

    $("#list-comment .item").live("click", function () {
        $("#more").hide();
        setChat(this.id);
    });

    $("#list-active .item").live("click", function () {
        $("#more").show();
        setChat(this.id);
    });

    $("#btn-contact-edit-ok").live("click", function () {
        var json = {
            Cmd: "action",
            "key_remote_id": selected_chat,
            "InfoContact": {
                "name": $("#edit-name").val(),
                "email": $("#edit-email").val()
            }
        };
        socket.send(JSON.stringify(json));
    });

    $("#save-note").live("click", function () {
        var json = {
            Cmd: "action",
            "key_remote_id": selected_chat,
            "InfoNote": {
                "note": $("#note").val()
            }
        };
        socket.send(JSON.stringify(json));
    });

    $("#chat-active").live("click", function () {

        $("#chat-wait span").css("border-bottom", "0px");
        $("#chat-comment span").css("border-bottom", "0px");
        $("#chat-active span").css("border-bottom", "4px solid #1da1f2");

        $("#chat-active .title").css("color", "");
        $("#chat-active span").css("color", "");

        $("#chat-wait .title").css("color", "#b7b7b7");
        $("#chat-wait span").css("color", "#b7b7b7");

        $("#chat-comment .title").css("color", "#b7b7b7");
        $("#chat-comment span").css("color", "#b7b7b7");

        $("#list-wait").hide();
        $("#list-comment").hide();
        $("#list-active").show();
    });

    $("#chat-wait").live("click", function () {

        $("#chat-active span").css("border-bottom", "0px");
        $("#chat-comment span").css("border-bottom", "0px");
        $("#chat-wait span").css("border-bottom", "4px solid #1da1f2");

        $("#chat-wait .title").css("color", "");
        $("#chat-wait span").css("color", "");

        $("#chat-active .title").css("color", "#b7b7b7");
        $("#chat-active span").css("color", "#b7b7b7");

        $("#chat-comment .title").css("color", "#b7b7b7");
        $("#chat-comment span").css("color", "#b7b7b7");

        $("#list-wait").show();
        $("#list-comment").hide();
        $("#list-active").hide();
    });


    $("#chat-comment").live("click", function () {

        $("#chat-active span").css("border-bottom", "0px");
        $("#chat-wait span").css("border-bottom", "0px");
        $("#chat-comment span").css("border-bottom", "4px solid #1da1f2");

        $("#chat-comment .title").css("color", "");
        $("#chat-comment span").css("color", "");

        $("#chat-wait .title").css("color", "#b7b7b7");
        $("#chat-wait span").css("color", "#b7b7b7");

        $("#chat-active .title").css("color", "#b7b7b7");
        $("#chat-active span").css("color", "#b7b7b7");

        $("#list-wait").hide();
        $("#list-comment").show();
        $("#list-active").hide();

    });

    $("#mod-night").live("click", function () {
        if (bNight == "false") {
            bNight = "true";
            $("body").css('background-color', 'rgb(21, 32, 43)');
            $(".messenger").addClass("dark");
            $(".modal").addClass("dark");
            $('#night').css({ fill: "white" });
            localStorage.setItem("night", true);
        } else {
            bNight = "false";
            $("body").css('background-color', 'transparent');
            $(".messenger").removeClass("dark");
            $(".modal").removeClass("dark");
            $('#night').css({ fill: "black" });
            localStorage.setItem("night", false);
        }
    });

    $("#emoji").live("click", function () {
        if ($(".emojipicker").css("display") == "none") {
            $(".emojipicker").css("display", "block");
        } else {
            $(".emojipicker").css("display", "none");
        }
    });

    $(".emojipicker .items .item").live("click", function () {
        $(".input-text").append(processEmoji(parseInt($(this).attr("data-emoji"))));
        $(".input-text").focus();
    });

    $("#send-image").live("click", function () {
        $('#arq').attr("accept", "image/jpg,image/jpeg");
        document.getElementById('arq').click();
    });

    $("#send-document").live("click", function () {
        $('#arq').attr("accept", "document/pdf");
        document.getElementById('arq').click();
    });

    $("#caption").live("keypress", function (e) {
        if (e.keyCode == 13) {
            var canvasData = document.getElementById("clipboard").toDataURL("image/png");
            var json = { Cmd: "ImageMessage", "to": selected_chat, "thump_image": "", "media_caption": $("#caption").val(), "base64": canvasData };
            socket.send(JSON.stringify(json));
            $(".clipboard").hide();
            $(".messages").show();
            $(".input").show();
        }
    });

    $("#clipboard-close").live("click", function () {
        $(".clipboard").hide();
        $(".messages").show();
        $(".input").show();
    });

    $("#clipboard-send").live("click", function () {
        var canvasData = document.getElementById("clipboard").toDataURL("image/png");
        var json = { Cmd: "ImageMessage", "to": selected_chat, "thump_image": "", "media_caption": $("#caption").val(), "base64": canvasData };
        socket.send(JSON.stringify(json));
        $(".clipboard").hide();
        $(".messages").show();
        $(".input").show();
    });

    $("#file-upload").live("click", function () {
        if ($("#box-clip").is(":hidden")) {
            $("#box-clip").show();
        } else {
            $("#box-clip").hide();
        }
    });

    $("#record-audio").live("click", function () {
        navigator.permissions.query({
            name: 'microphone'
        }).then(function (result) {
            if (result.state == 'granted') {
                startRecording();
            } else if (result.state == 'prompt') {
                navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => { });
                console.log(result.state);
            } else if (result.state == 'denied') {
                console.log(result.state);
            }
            result.onchange = function () { };
        });
    });

    $("#ok-record").live("click", function () {
        stopRecording();
    });

    $("#stop-record").live("click", function () {
        cancelRecording();
    });

    $("#revoke").live("click", function () {
        var json = { Cmd: "Revoke", "key_remote_id": selected_chat, "key_id": item_id };
        socket.send(JSON.stringify(json));
    });

    $("#post").live("click", function () {
        var json = { Cmd: "queryPost", "key_remote_id": selected_chat, "key_id": item_id };
        socket.send(JSON.stringify(json));
    });

    $(".messages").on('scroll', function () {
        if (this.scrollTop == 0) {
            var json = { Cmd: "Chat", "key_remote_id": selected_chat, "creation": creation };
            socket.send(JSON.stringify(json));
        }
    });

    $(".input-text").live("keyup", function (e) {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    $(".textMessage").live({
        mouseenter: function () {
            $("#" + this.parentElement.id + " .dropdown").show();
        },
    });

    $(".messages .item").live({
        mouseleave: function () {
            if (this.id != item_id) {
                $("#" + this.id + " .dropdown").hide();
            }
        }
    });

    $(".dropdown").live("click", function (e) {

        if (bShowPopMenu == false) {

            $("#" + item_id + " .dropdown").hide();
            item_id = this.parentElement.id;

            $("#" + this.parentElement.id + " .dropdown").show();
            $(".popmenu").remove();

            var popmenu = document.createElement("div");
            popmenu.className = "popmenu";
            popmenu.style.top = event.clientY + "px";
            popmenu.style.left = event.clientX + "px";

            switch (parseInt($("#" + selected_chat + " .type").val())) {
                case 1:
                case 2:
                    if ($("#" + item_id + " div")[0].className == "textMessage messageRight") {
                        if (bDeleteMsg == true) {
                            var item = document.createElement("span");
                            item.className = "item";
                            item.id = "revoke";
                            item.textContent = "Apagar mensagem";
                            popmenu.appendChild(item);
                        }
                    } else {
                        if (bReplyMsg == true) {
                            var item = document.createElement("span");
                            item.className = "item";
                            item.id = "quoted";
                            item.textContent = "Responder mensagem";
                            popmenu.appendChild(item);
                        }
                    }
                    break;
                case 9:
                case 11:
                    if ($("#" + item_id + " div")[0].className == "textMessage messageLeft") {
                        if (bReplyMsg == true) {
                            var item = document.createElement("span");
                            item.className = "item";
                            item.id = "quoted";
                            item.textContent = "Responder mensagem";
                            popmenu.appendChild(item);
                        }
                        if (bViewPost == true) {
                            var item = document.createElement("span");
                            item.className = "item";
                            item.id = "post";
                            item.textContent = "Ver post";
                            popmenu.appendChild(item);
                        }
                    }
                    break;
            }
            document.body.appendChild(popmenu);
            bShowPopMenu = true;
        } else {
            $(".popmenu").remove();
            $("#" + item_id + " .dropdown").hide();
            bShowPopMenu = false;
        }
    });

    $(".input-text").live("keypress", function (e) {

        if ($(".input-text").text().length > 0) {

            var json = { Cmd: "Presence", "key_remote_id": selected_chat, "type": "composing" };
            socket.send(JSON.stringify(json));

            if (e.keyCode == 13) {

                e.preventDefault();

                if (quoted_id != 0) {

                    switch (parseInt($("#" + selected_chat + " .type").val())) {
                        case 1:
                        case 2:
                        case 9:
                        case 11:
                            var json = { Cmd: "ExtendedTextMessage", "to": selected_chat, "data": $(".input-text").text(), "quoted": quoted_id };
                            socket.send(JSON.stringify(json));
                            break;
                    }

                } else {
                    switch (parseInt($("#" + selected_chat + " .type").val())) {
                        case 9:
                        case 11:
                            alert('Você precisa selecionar a mensagem que deseja responder!');
                            return;
                            break;
                    }
                    var json = { Cmd: "TextMessage", "to": selected_chat, "data": $(".input-text").text() };
                    socket.send(JSON.stringify(json));
                }

                quoted_id = 0;

                var json = { Cmd: "Read", "key_remote_id": selected_chat };
                socket.send(JSON.stringify(json));

                $(".input-text").text("");
                $(".reply-message .quoted").html("");
                $(".reply-message").hide();

                $("#" + selected_chat + " .no-read-message").hide();
                $("#" + selected_chat + " .no-read-message label").html(0);

                localStorage.removeItem(selected_chat);

                $(".emojipicker").hide();
            }
        } else {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        }
    });

    $(".input-text").live("keyup", function () {
        if ($(".input-text").text().indexOf("/") != -1) {
            if ($(".input-text").text().length < 10) {
                var json = { Cmd: "queryQuick", "data": $(".input-text").text() };
                socket.send(JSON.stringify(json));
            }
        } else {
            $(".quick").hide();
        }
    });

    $(".find input").live("keyup", function () {
        if (this.value.length > 3) {
            $("#list-find").show();
            var json = { Cmd: "queryContact", "data": this.value };
            socket.send(JSON.stringify(json));
        } else {
            $("#list-find").hide();
        }
    });

    $("#chat-waiting").live("click", function () {

        var html = "<div class='item' data-index id='" + selected_chat + "'>" + $("#" + selected_chat).html() + "</div>";
        $("#" + selected_chat).remove();
        $("#list-wait").append(html);
        $("#sub-more").hide();

        var json = { Cmd: "action", "chat": { "key_remote_id": selected_chat, "status": "wait" } };
        socket.send(JSON.stringify(json));

        updateCountChats();
    });
    $("#more").live("click", function () {
        if ($('#sub-more').css('display') == "none") {
            $("#sub-more").show();
        } else {
            hidePopMenus();
        }
    });
    $("#contact").live("click", function () {
        if ($('#sub-contact-add').css('display') == "none") {
            $("#sub-contact-add").show();
        } else {
            hidePopMenus();
        }
    });
    $("#chat-labels").live("click", function () {
        var json = { Cmd: "queryLabel" };
        socket.send(JSON.stringify(json));
    });
    $("#chat-ticket").live("click", function () {
        var json = { Cmd: "queryTicket" };
        socket.send(JSON.stringify(json));
    });
    $(".fa-tags").live("click", function () {
        var json = { Cmd: "queryLabel" };
        socket.send(JSON.stringify(json));
    });
    $(".select-label").live("click", function () {
        if ($("#" + this.id + " .checkbox").prop("checked") == true) {
            $("#" + this.id + " .checkbox").prop("checked", false);
        } else {
            $("#" + this.id + " .checkbox").prop("checked", true);
        }
    });

    $("#btn-ticket-edit").live("click", function () {

        var ticket_type = $(".select-ticket-type").val();
        var ticket_status = $(".select-ticket-status").val();

        if (ticket_type != 0) {

            var json = { Cmd: "action", "ticket": { "event": "edit", "id": ticket_id, "key_remote_id": selected_chat, "type": ticket_type, "status": ticket_status, "comment": $("#comment").val(), } };
            socket.send(JSON.stringify(json));

            $("#modal").hide();
        } else {
            alert('Você deve selecionar o tipo do ticket');
        }
    });

    $("#btn-ticket-ok").live("click", function () {

        var ticket_type = $(".select-ticket-type").val();
        var ticket_status = $(".select-ticket-status").val();

        if (ticket_type != 0) {

            var json = { Cmd: "action", "ticket": { "event": "add", "key_remote_id": selected_chat, "type": ticket_type, "status": ticket_status, "comment": $("#comment").val(), } };
            socket.send(JSON.stringify(json));

            $("#modal").hide();
        } else {
            alert('Você deve selecionar o tipo do ticket');
        }
    });

    $("#select-label-confirm").live("click", function () {

        var first = 0;
        var ids = "";
        var iCount = 0;
        var colors = "";
        var names = "";

        for (var i = 0; i < $(".select-label").length; i++) {
            if ($("#" + $(".select-label")[i].id + " .checkbox").prop("checked") == true) {
                if (iCount > 0) {
                    ids += "," + $(".select-label")[i].id;
                    colors += "," + $("#" + $(".select-label")[i].id).data('color');
                    names += "," + $("#" + $(".select-label")[i].id).data('name');
                } else {
                    first = $(".select-label")[i].id;
                    ids = $(".select-label")[i].id;
                    colors = $("#" + $(".select-label")[i].id).data('color');
                    names = $("#" + $(".select-label")[i].id).data('name');
                }
                iCount++;
            }
        }

        var json = { Cmd: "action", "chat": { "key_remote_id": selected_chat, "label": ids } };
        socket.send(JSON.stringify(json));

        $("#" + selected_chat + " .short_name div").show();
        $("#" + selected_chat + " .label").val(names);
        $("#" + selected_chat + " .color").val(colors);
        $("#" + selected_chat + " .body .short_name svg").css("fill", $("#" + first + " .color svg").css('fill'));

        var html = "<span style='float: left;'>" + $("#" + selected_chat + " .short_name span").html() + "</span>";

        for (var i = 0; i < $(".select-label").length; i++) {
            if ($("#" + $(".select-label")[i].id + " .checkbox").prop("checked") == true) {
                html += "<div id='chat-labels' style='background: " + $("#" + $(".select-label")[i].id + " .color svg").css('fill') + ";'>" +
                    $("#" + $(".select-label")[i].id + " .name").html() + "</div>";
            }
        }
        html += "<i class='fas fa-tags' style='margin-left: 10px;margin-top: 1px;'></i>";

        $(".caption").html(html);
        $("#modal").hide();
    });
    $("#modal").live("click", function () {

    });

    $("#btn-trans-cancel").live("click", function () {
        $("#modal").hide();
    });

    $("#btn-trans-ok").live("click", function () {
        var id_user_group = $(".select-user-group").val();
        var user = $(".select-user").val();
        if (id_user_group != 0) {
            var json = { Cmd: "action", "chat": { "type": "move", "from": selected_chat, "to": user, "group": id_user_group, "default": $("#checkbox_default_setor").is(":checked") } };
            socket.send(JSON.stringify(json));
        }
    });
    $("#contact-add").live("click", function () {

        $("#modal .box .items").html("");

        hidePopMenus();

        var lbName = document.createElement("span");
        lbName.textContent = "Nome";
        lbName.style.cssFloat = "left";
        lbName.style.width = "100%";

        var inputName = document.createElement("input");
        inputName.type = "text";
        inputName.className = "contact name";
        inputName.placeholder = "Nome do contato";
        inputName.style.cssFloat = "left";
        inputName.style.width = "100%";

        var lbNumber = document.createElement("span");
        lbNumber.textContent = "Número do contato";
        lbNumber.style.cssFloat = "left";
        lbNumber.style.width = "100%";

        var inputNumber = document.createElement("input");
        inputNumber.type = "tel";
        inputNumber.pattern = "\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}";
        inputNumber.className = "contact number";
        inputNumber.placeholder = "Número do contato";
        inputNumber.style.cssFloat = "left";
        inputNumber.style.width = "100%";

        var btnOk = document.createElement("input");
        btnOk.type = "button";
        btnOk.className = "btn ok";
        btnOk.value = "Salvar";

        var btnCancel = document.createElement("input");
        btnCancel.type = "button";
        btnCancel.className = "btn cancel";
        btnCancel.value = "Cancelar";

        $("#modal .box .items").append(lbName);
        $("#modal .box .items").append(inputName);
        $("#modal .box .items").append(lbNumber);
        $("#modal .box .items").append(inputNumber);
        $("#modal .box .items").append(btnOk);
        $("#modal .box .items").append(btnCancel);
        $("#modal .box .head span").html("Novo contato");

        $("#modal").show();

        $(".number").mask("(99)99999-999?9");
        $(".name").focus();
    });
    $("#chat-trans").live("click", function () {
        var json = { Cmd: "queryUsersGroups" };
        socket.send(JSON.stringify(json));
    });
    $("#calendar-add").live("click", function () {
        $("#calendar").show();
    });
    $("#calendar-add-button").live("click", function () {
        var json = {
            "Cmd": "calendar",
            "event": "add",
            "date": document.getElementById('form-calendar').contentDocument.getElementById("date_start").value,
            "time": document.getElementById('form-calendar').contentDocument.getElementById("time_start").value,
            "title": document.getElementById('form-calendar').contentDocument.getElementById("input-title").value,
            "text": document.getElementById('form-calendar').contentDocument.getElementById("input-text").value,
        };
        socket.send(JSON.stringify(json));
    });
    $('.select-user-group').live('change', function () {
        var json = { Cmd: "queryUsers", "key_id": this.value };
        socket.send(JSON.stringify(json));
    });
    $(".conn-refresh").live("click", function () {
        location.reload();
    });
    $(".conn-close").live("click", function () {
        window.location.href = "account/logoff";
    });
    $(".cancel").live("click", function () {
        $("#modal").hide();
    });
    $(".ok").live("click", function () {
        var name = $(".name").val();
        var number = "55" + $(".number").val().replace(/[^\d]+/g, '');
        if (name.length > 0 && number.length >= 12) {
            var json = { Cmd: "queryExist", "key_remote_id": number, "full_name": name, "user": userToken };
            socket.send(JSON.stringify(json));
        }
    });
    $(".DocumentMessage").live("click", function () {
        window.open($(this).attr("data-url"));
    });
    $(".ZipMessage").live("click", function () {
        //window.open($(this).attr("data-url"));
    });
    $(".ImageMessage").live("click", function () {
        window.open($(this).attr("data-url"));
    });
    $(".VideoMessage").live("click", function () {
        window.open($(this).attr("data-url"));
    });
    $(".GifMessage video").live("click", function () {
        this.play();
    });
    $("#close-chat").live("click", function () {

        $("#sub-more").hide();
        $("#modal .box .items").html("");

        var ok = document.createElement("input");
        ok.type = "button";
        ok.id = "chat-close";
        ok.className = "success";
        ok.value = "Sim";

        var cancel = document.createElement("input");
        cancel.type = "button";
        cancel.className = "cancel"
        cancel.value = "Não";

        $("#modal .box .items").append(ok);
        $("#modal .box .items").append(cancel);
        $("#modal .box .head span").html("Você tem certeza que deseja encerrar esse atendimento?");
        $("#modal").show();

    });
    $("#chat-report-span").live("click", function () {

        $("#sub-more").hide();
        $("#modal .box .items").html("");

        var ok = document.createElement("input");
        ok.type = "button";
        ok.id = "blocklist-ok";
        ok.className = "success";
        ok.value = "Sim";

        var cancel = document.createElement("input");
        cancel.type = "button";
        cancel.className = "cancel"
        cancel.value = "Não";

        $("#modal .box .items").append(ok);
        $("#modal .box .items").append(cancel);
        $("#modal .box .head span").html("<p>Denunciar este contato ao TalkAll?</p>Se você denunciar, sua conversa com esse contato será apagada e o contato será bloqueado.");
        $("#modal").show();
    });

    $("#blocklist-ok").live("click", function () {
        var json = { Cmd: "action", "blocklist": { "key_remote_id": selected_chat } };
        socket.send(JSON.stringify(json));
    });

    $("#chat-close").live("click", function () {
        $("#modal").hide();
        $(".right").hide();
        $(".option").hide();
        $("#sub-more").hide();
        $("#" + selected_chat).remove();
        var json = { Cmd: "action", "chat": { "key_remote_id": selected_chat, "status": "close" } };
        socket.send(JSON.stringify(json));
        updateCountChats();
    });
    $('.input-text').on('paste', function () {
        event.preventDefault();
        var clipboarddata = window.event.clipboardData.getData('text');
        document.execCommand("insertHTML", false, clipboarddata);
    });
    $(".quick .items .item").live("click", function () {
        $(".quick").hide();
        $(".input-text").html($(this).attr("data-content"));
        $(".input-text").focus();
    });
    $(".quick .head span").live("click", function () {
        $(".quick").hide();
    });
    $(".ticket .items .item").live("click", function () {
        ticket_id = $(this).attr("data-content");
        var json = { Cmd: "queryInfoTicket", "id": ticket_id };
        socket.send(JSON.stringify(json));
    });
});

function doneTyping() {
    if (selected_chat != 0) {
        var json = { Cmd: "Presence", "key_remote_id": selected_chat, "type": "wait" };
        socket.send(JSON.stringify(json));
    }
}