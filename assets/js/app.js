let info;
var bConnected = false;
var bRConnected = true;
var doneCheckStatus;
var doneCheckStatusInterval = 60000;
var app_version = "2.2027.17";
var bReConnect = true;
var bLoggin = false;
var verify_tailOut;
var getMoreImageGallery = true;

//** Variáveis globais para o mecanismo do scroll **//
let ITEM_FOCUSED = "";
let SCROLL_BLOCK = true;
let SCROLL_TOKEN_TOP = "";
let QUERY_MESSAGES_CHAT = "";
let FORCE_SCROLL_DOWN = false;
let MORE_CHAT_MESSAGES = false;
let LOCK_FOCUS = false;
let CHECK_SCROLL = "";
let BUTTON_BOTTOM_SCROLL = false

var ta = {
    config: {
        ta: localStorage.getItem("userToken"),
        pw: localStorage.getItem("userToken"),
        WebSessionToken: localStorage.getItem("WebSessionToken"),
        host: "wss://app.talkall.com.br:28192"
    },
    socket: null,
    queue: [],
    tComposing: 0,
    ContactList: [],
    ChatList: [],
    key_remote_id: null,
    WorkerQueue() {
        for (let i = 0; i < ta.queue.length; i++) {
            if (bConnected == true && bLoggin == true) {
                if (ta.queue[i].status == 0) {
                    ta.conn.sendRequest(ta.queue[i].json);
                    $("#" + ta.queue[i].json.key_id + " .bottom svg").html(Util.msgSend());
                    ta.queue.splice(i, 1);
                    break;
                }
            }
        }
        setTimeout(function () { ta.WorkerQueue(); }, 50);
    },
    adminTest() {
        if (bConnected == true && bLoggin == true) {
            ta.conn.sendRequest({
                "Cmd": "ping"
            });
        }
        setTimeout(function () { ta.adminTest(); }, 30000);
    },
    init() {
        if (ta.config.ta != null && ta.config.pw != null) {
            this.conn.connect();
            setTimeout(function () { ta.WorkerQueue(); 50 });
            setTimeout(function () { ta.adminTest(); 15000 });
        }

        $('#checkbox-on-off').lc_switch('ONLINE', 'OFFLINE', '#2263d3', '#666666', '#1a4ea3', '#4d4d4d');

        if ((localStorage.getItem('status') === undefined) || (localStorage.getItem("status") === null)) {
            localStorage.setItem("status", "offline");
        }
        if (localStorage.getItem('status') == "online") {
            let chatbot = $('#checkbox-on-off');
            doneCheckStatus = setTimeout(doneSetStatus(1), doneCheckStatusInterval);
            chatbot.lcs_on();
        }

        $("body").on("click", ".lcs_switch.lcs_on", function () {
            clearTimeout(doneCheckStatus);
            ta.account.unavailable();
            localStorage.setItem("status", "offline");
        });

        $("body").on("click", ".lcs_switch.lcs_off", function () {
            ta.account.available();
            localStorage.setItem("status", "online");
        });
    },
    setKeyRemoteId(key_remote_id) {
        this.key_remote_id = key_remote_id;
    },
    account: {
        logon() {
            ta.conn.sendRequest({
                Cmd: "login",
                account: ta.config.pw,
                pw: ta.config.pw,
                WebSessionToken: ta.config.WebSessionToken,
                version: app_version,
            });
        },
        available() {
            ta.conn.makeRequest(
                { Cmd: "action", key_remote_id: ta.config.ta, presence: { type: "available" } }
            );
        },
        unavailable() {
            ta.conn.makeRequest(
                { Cmd: "action", key_remote_id: ta.config.ta, presence: { type: "unavailable" } }
            );
        }
    },
    contact: {
        pushName(key_remote_id) {
            let i = 0;
            let bFind = false;
            for (i = 0; i < ta.ContactList.length; i++) {
                if (ta.ContactList[i].key_remote_id == key_remote_id) {
                    bFind = true;
                    break;
                }
            }
            return bFind == false ? GLOBAL_LANG.messenger_system_message : ta.ContactList[i].push_name;
        },
        queryContact(data, pagination = 0) {
            ta.conn.makeRequest({
                Cmd: "queryContact",
                data: data,
                pagination
            });
        },
        queryContactForward() {
            ta.conn.makeRequest({
                Cmd: "queryContactForward"
            });
        },
        queryPresence() {
            ta.conn.makeRequest({
                Cmd: "queryPresence",
                key_remote_id: ta.key_remote_id
            }
            );
        },
        queryName(key_remote_id) {
            ta.conn.makeRequest({
                Cmd: "queryName", key_remote_id: key_remote_id
            });
        },
        queryProfilePicture(key_remote_id) {
            ta.conn.makeRequest({
                Cmd: "queryProfilePicture", key_remote_id: key_remote_id
            });
        },
        change(name, email) {
            ta.conn.makeRequest({
                Cmd: "action",
                key_remote_id: ta.key_remote_id,
                InfoContact: {
                    name: name,
                    email: email
                }
            });
        },
        queryInfo() {
            ta.conn.makeRequest(
                { Cmd: "queryInfo", "key_remote_id": ta.key_remote_id }
            );
        },
        Exist(key_remote_id, name, user_key_remote_id, channel_id) {
            ta.conn.makeRequest({
                Cmd: "queryExist",
                key_remote_id: key_remote_id,
                full_name: name,
                user: ta.config.ta,
                user_key_remote_id: user_key_remote_id,
                channel: channel_id
            });
        },
        querySubtype(id_ticket_type) {
            ta.conn.makeRequest(
                { Cmd: "querySubtype", "id_ticket_type": id_ticket_type }
            );
        }
    },
    blocklist: {
        block() {
            ta.conn.makeRequest({
                Cmd: "action", blocklist: { key_remote_id: ta.key_remote_id }
            });
        }
    },
    chat: {
        open(key_remote_id, isPrivate = true, attendance = false) {
            if (attendance) {
                ta.conn.makeRequest({
                    Cmd: "Open", to: key_remote_id, isPrivate, attendance
                });

            } else {
                ta.conn.makeRequest({
                    Cmd: "Open", to: key_remote_id, isPrivate, attendance: false
                });
            }
        },
        read() {
            ta.conn.makeRequest(
                { Cmd: "MakeRead", "key_remote_id": ta.key_remote_id }
            )
        },
        makeRead(key_remote_id) {
            ta.conn.makeRequest(
                { Cmd: "MakeRead", "key_remote_id": key_remote_id }
            )
        },
        makeFixed(key_remote_id) {
            ta.conn.makeRequest(
                { Cmd: "MakeFixed", "key_remote_id": key_remote_id, timestamp: Math.floor(Date.now() / 1000) }
            )
        },
        makeNotFixed(key_remote_id) {
            ta.conn.makeRequest(
                { Cmd: "MakeNotFixed", "key_remote_id": key_remote_id }
            )
        },
        makeUnRead(key_remote_id) {
            ta.conn.makeRequest(
                { Cmd: "MakeUnRead", "key_remote_id": key_remote_id }
            )
        },
        query(creation, reverse) {
            reverse = reverse ?? false;
            ta.conn.makeRequest(
                { Cmd: "Chat", "key_remote_id": ta.key_remote_id, "creation": creation, "reverse": reverse }
            )
        },
        searchMessage(creation, data) {
            ta.conn.makeRequest(
                { Cmd: "searchMessage", "key_remote_id": ta.key_remote_id, "is_private": messenger.Chat.is_private, "creation": creation, data: data }
            )
        },
        note(note) {
            ta.conn.makeRequest({
                Cmd: "action",
                key_remote_id: ta.key_remote_id,
                InfoNote: { note: note }
            });
        },
        composing() {
            let t = Date.now();
            let dif = (t - ta.tComposing);
            if (dif > 3000) {
                ta.conn.makeRequest({
                    Cmd: "Presence",
                    key_remote_id: ta.key_remote_id,
                    presence: {
                        type: "composing"
                    }
                });
            }
            ta.tComposing = t;
        },
        recording() {
            ta.conn.sendRequest({
                Cmd: "Presence",
                key_remote_id: ta.key_remote_id,
                presence: {
                    type: "recording"
                }
            });
        },
        stoped() {
            ta.conn.sendRequest({
                Cmd: "Presence", key_remote_id: ta.key_remote_id, presence: { type: "wait" }
            });
        },
        trans(user, user_group, bDefault) {
            ta.conn.makeRequest({
                Cmd: "action", chat: { type: "move", from: ta.key_remote_id, to: user, group: user_group, default: bDefault }
            });
        },
        wait() {
            ta.conn.makeRequest({
                Cmd: "action",
                chat: { key_remote_id: ta.key_remote_id, status: "wait" }
            });
        },
        attendance() {
            ta.conn.makeRequest({
                Cmd: "action",
                chat: { key_remote_id: ta.key_remote_id, status: "attendance" }
            });
        },
        close(id_category) {
            $(".messenger .right")[0].style = "width: calc(100% - 360px)";
            ta.conn.makeRequest({
                Cmd: "action", chat: { key_remote_id: ta.key_remote_id, status: "close", user_key_remote_id: ta.config.ta, id_category: id_category }
            });
        },
        queryCardList(order) {
            ta.conn.makeRequest({
                Cmd: "queryCardList",
                order: order
            });
        },
        queryOrderStatus() {
            ta.conn.makeRequest({
                Cmd: "queryOrderStatus",
            });
        },
        saveCardList(order, id_order_status) {
            ta.conn.makeRequest({
                Cmd: "saveCardList",
                order: order,
                id_order_status: id_order_status,
            });
        },
        queryGallery(creation, gallery) {
            ta.conn.makeRequest(
                { Cmd: "queryGallery", "key_remote_id": ta.key_remote_id, "is_private": messenger.Chat.is_private, "creation": creation, "gallery": gallery }
            )
        },
        queryPreviewGallery() {
            ta.conn.makeRequest(
                { Cmd: "queryPreviewGallery", "key_remote_id": ta.key_remote_id, "is_private": messenger.Chat.is_private }
            )
        },
        queryCatalog(data) {
            ta.conn.makeRequest(
                { Cmd: "queryCatalog", "data": data }
            )
        },
        queryChannel() {
            ta.conn.makeRequest(
                { Cmd: "queryChannel" }
            )
        },
        starred(key_id, status, key_from_me, total) {
            ta.conn.makeRequest(
                { Cmd: "starred", "key_id": key_id, "status": status, "key_from_me": key_from_me, "total": total, "key_remote_id": ta.key_remote_id }
            )
        },
        queryStarred(creation) {
            ta.conn.makeRequest(
                { Cmd: "queryStarred", "key_remote_id": ta.key_remote_id, "is_private": messenger.Chat.is_private, "creation": creation }
            )
        },
        queryMessages(key_remote_id, creation, reverse = false) {
            ta.conn.makeRequest(
                { Cmd: "queryMessages", "key_remote_id": key_remote_id, "creation": creation, "reverse": reverse }
            )
        },
        queryParamsContact() {
            ta.conn.makeRequest(
                { Cmd: "queryParamsContact" }
            )
        },
        queryChats() {
            ta.conn.makeRequest(
                { Cmd: "queryChats" }
            )
        },
        queryCreditConversation(channel) {
            ta.conn.makeRequest(
                { Cmd: "queryCreditConversation", "key_remote_id": ta.key_remote_id, "channel": channel }
            )
        },
        queryTemplates(channel) {
            ta.conn.makeRequest(
                { Cmd: "queryTemplates", "channel": channel }
            )
        },
        queryPaymentMessage(channel) {
            ta.conn.makeRequest(
                { Cmd: "queryPaymentMessage", "channel": channel }
            )
        },
        updatePaymentMessage(channel) {
            ta.conn.makeRequest(
                { Cmd: "updatePaymentMessage", "channel": channel }
            )
        },
        queryCategories(key_remote_id) {
            ta.conn.makeRequest(
                { Cmd: "queryCategories", "key_remote_id": key_remote_id }
            )
        },
    },
    group: {
        queryGroupParticipants() {
            ta.conn.makeRequest({ Cmd: "queryGroupParticipants", key_remote_id: ta.key_remote_id });
        }
    },
    userGroup: {
        queryUserGroup() {
            ta.conn.makeRequest({ Cmd: "queryUsersGroups" });
        }
    },
    user: {
        queryUsers(id) {
            ta.conn.makeRequest({ Cmd: "queryUsers", key_id: id });
        },
        setNotificationUrl(url) {
            ta.conn.makeRequest({ Cmd: "setNotificationUrl", "url": url });
        },
        getNotificationUrl() {
            ta.conn.makeRequest({ Cmd: "getNotificationUrl" });
        },
        removeNotification() {
            ta.conn.makeRequest({ Cmd: "removeNotification" });
        }
    },
    label: {
        set(info) {
            ta.conn.makeRequest({
                Cmd: "action",
                chat: {
                    key_remote_id: ta.key_remote_id,
                    label: info,
                }
            });
        },
        queryLabel() {
            ta.conn.makeRequest({ Cmd: "queryLabel" });
        },
    },
    ticket: {
        add(ticket_type, ticket_status, company, subtype, comment) {
            ta.conn.makeRequest({
                Cmd: "action", ticket: { event: "add", key_remote_id: ta.key_remote_id, type: ticket_type, status: ticket_status, company: company, subtype: subtype, comment: comment }
            });
        },
        edit(ticket_id, ticket_type, ticket_status, company, subtype, comment) {
            ta.conn.makeRequest({
                Cmd: "action", ticket: { event: "edit", id: ticket_id, key_remote_id: ta.key_remote_id, type: ticket_type, status: ticket_status, company: company, subtype: subtype, comment: comment }
            });
        },
        queryTicket() {
            ta.conn.makeRequest({ Cmd: "queryTicket" });
        },
        queryInfoTicket(id) {
            ta.conn.makeRequest({
                Cmd: "queryInfoTicket",
                id: id
            });
        },
    },
    teamTalkall: {
        queryTeam() {
            ta.conn.makeRequest({ Cmd: "queryTeam" });
        }
    },
    message: {
        sendText(key_remote_id, data, forwarded = 0) {

            let info = messenger.ChatList.findByKeyRemoteId(key_remote_id);
            if (info != null) {

                let creation = Math.floor(Date.now() / 1000);
                let key_id = Util.makeId();

                ta.queue.push({
                    json: {
                        Cmd: "TextMessage",
                        timestamp: creation,
                        key_id: key_id,
                        to: key_remote_id,
                        data: data,
                        forwarded: forwarded
                    },
                    status: 0,
                });

                messenger.Message.TextMessage({
                    'Cmd': 'Message',
                    'type': 'TextMessage',
                    'token': key_id,
                    'creation': creation,
                    'key_from_me': 2,
                    'key_remote_id': key_remote_id,
                    'data': data,
                    'media_type': 1,
                    'msgStatus': 0,
                    'forwarded': forwarded
                }, true);

                $("#" + info.hash + " .no-read-message").hide();
                $("#" + info.hash + " .no-read-message label").html(0);

                $("#" + info.hash + " .time").html(Util.FormatShortTime(creation));
                $("#" + info.hash + " .last-message").html(Util.doTruncarStr(data, 20));
                $("#" + info.chat).scrollTop($("#" + info.chat).prop("scrollHeight") + 999);

                messenger.ChatList.updateCountView();

                ta.chat.read();
            }
        },
        sendExtendedText(key_remote_id, data, quoted_id, media_type, media_url, media_title, participant, creation_message, media_mime_type) {

            let info = messenger.ChatList.findByKeyRemoteId(key_remote_id);
            if (info != null) {
                let creation = Math.floor(Date.now() / 1000);
                let key_id = Util.makeId();

                ta.queue.push({
                    json: {
                        "Cmd": "ExtendedTextMessage",
                        timestamp: creation,
                        'key_id': key_id,
                        "to": messenger.Chat.key_remote_id,
                        "data": data,
                        "media_type": media_type,
                        "quoted_row_id": quoted_id
                    },
                    status: 0,
                });

                messenger.Message.ExtendedTextMessage({
                    'Cmd': 'Message',
                    'type': 'ExtendedTextMessage',
                    'token': key_id,
                    'creation': creation,
                    'key_from_me': 2,
                    'key_remote_id': messenger.Chat.key_remote_id,
                    'data': data,
                    'media_type': media_type,
                    'quoted_row_id': quoted_id,
                    'quoted': {
                        data: Util.doTruncarStr($("#" + item_id + " .body span").html(), 128),
                        key_from_me: 2,
                        creation: creation_message,
                        quoted_id: quoted_id,
                        media_type: media_type,
                        media_url: media_url,
                        title: media_title,
                        thumb_image: null,
                        participant_key_remote_id: null,
                        participant: participant,
                        media_mime_type: media_mime_type
                    },
                    'msgStatus': 0
                }, true);

                $("#" + info.hash + " .no-read-message").hide();
                $("#" + info.hash + " .no-read-message label").html(0);

                $("#" + info.hash + " .time").html(Util.FormatShortTime(creation));
                $("#" + info.hash + " .last-message").html(Util.doTruncarStr(data, 20));
                setTimeout(() => { $('.messages:visible').scrollTop($('.messages:visible')[0].scrollHeight - $('.messages:visible')[0].clientHeight), 999; }, 180);

                messenger.ChatList.updateCountView();
                ta.chat.read();
            }
        },
        sendImage(key_remote_id, media_caption, media_url, media_mime_type, media_size, thumbnail, forwarded = 0) {

            let info = messenger.ChatList.findByKeyRemoteId(key_remote_id);
            if (info != null) {

                let creation = Math.floor(Date.now() / 1000);
                let key_id = Util.makeId();

                ta.queue.push({
                    json: {
                        Cmd: "ImageMessage",
                        timestamp: creation,
                        key_id: key_id,
                        to: key_remote_id,
                        media_caption: media_caption,
                        media_mime_type: media_mime_type,
                        media_url: media_url,
                        media_size: media_size,
                        forwarded: forwarded
                    },
                    status: 0,
                });

                messenger.Message.ImageMessage({
                    'Cmd': 'Message',
                    'type': 'ImageMessage',
                    'token': key_id,
                    'creation': creation,
                    'key_from_me': 2,
                    'media_type': 3,
                    'key_remote_id': key_remote_id,
                    'media_caption': media_caption,
                    'media_title': media_caption,
                    'media_mime_type': media_mime_type,
                    'media_size': media_size,
                    'thumbnail': thumbnail,
                    'media_url': media_url,
                    'msgStatus': 0,
                    'forwarded': forwarded
                }, true);

                $("#" + info.hash + " .no-read-message").hide();
                $("#" + info.hash + " .no-read-message label").html(0);

                $("#" + info.hash + " .time").html(Util.FormatShortTime(creation));
                $("#" + info.chat).scrollTop($("#" + info.chat).prop("scrollHeight") + 999);
                $("#" + info.hash + " .last-message").html(`📷 ${GLOBAL_LANG.messenger_media_types_photo}`);

                messenger.ChatList.updateCountView();
                ta.chat.read();
            }
        },

        sendVideo(key_remote_id, key_id, media_name, duration, media_size, media_mime_type, media_url, thumbnail, forwarded = 0) {

            let info = messenger.ChatList.findByKeyRemoteId(key_remote_id);
            if (info != null) {

                let creation = Math.floor(Date.now() / 1000);
                let key_id = Util.makeId();

                ta.queue.push({
                    json: {
                        Cmd: "VideoMessage",
                        timestamp: creation,
                        key_id: key_id,
                        to: key_remote_id,
                        media_duration: 0,
                        media_name: media_name,
                        media_mime_type: media_mime_type,
                        media_url: media_url,
                        media_size: media_size,
                        media_duration: duration,
                        forwarded: forwarded
                    },
                    status: 0,
                });

                messenger.Message.VideoMessage({
                    'Cmd': 'Message',
                    'type': 'VideoMessage',
                    'token': key_id,
                    'creation': creation,
                    'key_from_me': 2,
                    'media_type': 5,
                    'key_remote_id': key_remote_id,
                    'media_name': media_name,
                    'media_mime_type': media_mime_type,
                    'media_url': media_url,
                    'media_duration': duration,
                    'media_size': media_size,
                    'thumbnail': thumbnail,
                    'msgStatus': 0,
                    'forwarded': forwarded
                }, true);

                $("#" + info.hash + " .no-read-message").hide();
                $("#" + info.hash + " .no-read-message label").html(0);

                $("#" + info.hash + " .time").html(Util.FormatShortTime(creation));
                $("#" + info.hash + " .last-message").html(`🎥 ${GLOBAL_LANG.messenger_media_types_video}`);
                setTimeout(() => { $('.messages:visible').scrollTop($('.messages:visible')[0].scrollHeight - $('.messages:visible')[0].clientHeight), 999; }, 300);

                messenger.ChatList.updateCountView();
                ta.chat.read();
            }
        },

        sendAudio(key_remote_id, media_mime_type, media_size, media_duration, media_url, forwarded = 0) {

            let info = messenger.ChatList.findByKeyRemoteId(key_remote_id);
            if (info != null) {

                let creation = Math.floor(Date.now() / 1000);
                let key_id = Util.makeId();

                ta.queue.push({
                    json: {
                        Cmd: "AudioMessage",
                        timestamp: creation,
                        key_id: key_id,
                        to: key_remote_id,
                        media_key: "",
                        media_mime_type: media_mime_type,
                        media_duration: media_duration,
                        media_size: media_size,
                        media_url: media_url,
                        forwarded: forwarded
                    },
                    status: 0,
                });

                messenger.Message.AudioMessage({
                    'Cmd': 'Message',
                    'type': 'AudioMessage',
                    'token': key_id,
                    'creation': creation,
                    'key_from_me': 2,
                    'media_type': 2,
                    'key_remote_id': key_remote_id,
                    'media_duration': media_duration,
                    'media_mime_type': media_mime_type,
                    'media_url': media_url,
                    'media_size': media_size,
                    'msgStatus': 0,
                    'forwarded': forwarded
                }, true);

                $("#" + info.hash + " .no-read-message").hide();
                $("#" + info.hash + " .no-read-message label").html(0);

                $("#" + info.hash + " .time").html(Util.FormatShortTime(creation));
                $("#" + info.chat).scrollTop($("#" + info.chat).prop("scrollHeight") + 999);
                $("#" + info.hash + " .last-message").html(`🎤 ${Util.FormatDuration(media_duration)}`);

                messenger.ChatList.updateCountView();
                ta.chat.read();
            }
        },

        sendDocument(key_remote_id, key_id, file_name, media_size, page_count, media_url, media_mime_type, thumb_image, forwarded = 0) {

            let info = messenger.ChatList.findByKeyRemoteId(key_remote_id);
            if (info != null) {

                let creation = Math.floor(Date.now() / 1000);

                ta.queue.push({
                    json: {
                        Cmd: "DocumentMessage",
                        timestamp: creation,
                        key_id: key_id,
                        to: key_remote_id,
                        media_name: file_name,
                        media_caption: file_name,
                        media_title: file_name,
                        media_mime_type: media_mime_type,
                        media_size: media_size,
                        page_count: page_count,
                        media_url: media_url,
                        forwarded: forwarded
                    },
                    status: 0,
                });

                messenger.Message.DocumentMessage({
                    'Cmd': 'Message',
                    'type': 'DocumentMessage',
                    'token': key_id,
                    'creation': creation,
                    'key_from_me': 2,
                    'media_type': 4,
                    'key_remote_id': key_remote_id,
                    'media_name': file_name,
                    'media_mime_type': media_mime_type,
                    'media_url': media_url,
                    'media_size': media_size,
                    'msgStatus': 0,
                    'forwarded': forwarded
                }, true);

                $("#" + key_id).find(".DocumentMessage").attr("data-url", media_url);

                $("#" + info.hash + " .no-read-message").hide();
                $("#" + info.hash + " .no-read-message label").html(0);

                $("#" + info.hash + " .time").html(Util.FormatShortTime(creation));
                $("#" + info.hash + " .last-message").html(`📃 ${GLOBAL_LANG.messenger_media_types_document}`);
                setTimeout(() => { $('.messages:visible').scrollTop($('.messages:visible')[0].scrollHeight - $('.messages:visible')[0].clientHeight), 999; }, 180);

                messenger.ChatList.updateCountView();
                ta.chat.read();
            }
        },
        sendTemplate(key_remote_id, name, namespace, language, text_body, component, json) {

            let info = messenger.ChatList.findByKeyRemoteId(key_remote_id);
            if (info != null) {

                let creation = Math.floor(Date.now() / 1000);
                let key_id = Util.makeId();

                setTimeout(() => {
                    ta.queue.push({
                        json: {
                            Cmd: "TemplateMessage",
                            timestamp: creation,
                            key_id: key_id,
                            to: key_remote_id,
                            name: name,
                            namespace: namespace,
                            language: language,
                            text_body: text_body,
                            policy: "deterministic",
                            component: component,
                            header_text: json.header,
                            footer: json.text_footer,
                        },
                        status: 0,
                    });
                }, 100);

                messenger.Message.TemplateMessage({
                    'Cmd': 'Message',
                    'type': 'TemplateMessage',
                    'token': key_id,
                    'creation': creation,
                    'key_from_me': 2,
                    'key_remote_id': key_remote_id,
                    'data': text_body,
                    'media_type': 27,
                    'msgStatus': 0,
                    'buttons': json.buttons,
                    'components': component,
                    'name': json.name_to_request,
                    'header_text': json.header,
                    'footer': json.text_footer,
                }, true);

                $("#" + info.hash + " .no-read-message").hide();
                $("#" + info.hash + " .no-read-message label").html(0);
                $("#" + info.hash + " .time").html(Util.FormatShortTime(creation));
                $("#" + info.chat).scrollTop($("#" + info.chat).prop("scrollHeight") + 999);
                $("#" + info.hash + " .last-message").html(`📃 ${GLOBAL_LANG.messenger_media_types_template}`);
                setTimeout(() => { $('.messages:visible').scrollTop($('.messages:visible')[0].scrollHeight - $('.messages:visible')[0].clientHeight), 999; }, 180);

                messenger.ChatList.updateCountView();
                ta.chat.read();
            }
        },
        sendTag(key_remote_id, text_body) {

            let info = messenger.ChatList.findByKeyRemoteId(key_remote_id);
            if (info != null) {

                let creation = Math.floor(Date.now() / 1000);
                let key_id = Util.makeId();

                ta.queue.push({
                    json: {
                        Cmd: "TagMessage",
                        timestamp: creation,
                        key_id: key_id,
                        to: key_remote_id,
                        data: text_body
                    },
                    status: 0,
                });

                messenger.Message.TextMessage({
                    'Cmd': 'Message',
                    'type': 'TemplateMessage',
                    'token': key_id,
                    'creation': creation,
                    'key_from_me': 2,
                    'key_remote_id': key_remote_id,
                    'data': text_body,
                    'media_type': 27,
                    'msgStatus': 0
                }, true);

                $("#" + info.hash + " .no-read-message").hide();
                $("#" + info.hash + " .no-read-message label").html(0);
                $("#" + info.hash + " .time").html(Util.FormatShortTime(creation));
                $("#" + info.chat).scrollTop($("#" + info.chat).prop("scrollHeight") + 999);
                $("#" + info.hash + " .last-message").html(`📃 ${GLOBAL_LANG.messenger_media_types_template}`);

                messenger.ChatList.updateCountView();
                ta.chat.read();
            }
        },
        revoke(key_id) {
            ta.conn.makeRequest({
                Cmd: "Revoke",
                key_remote_id: messenger.Chat.key_remote_id,
                key_id: key_id
            });
        },
        queryInfoMsg(key_remote_id, key_id) {
            ta.conn.makeRequest({
                Cmd: "queryInfoMsg",
                key_remote_id: key_remote_id,
                key_id: key_id,
            });
        }
    },
    Instagram: {
        queryPost(key_id) {
            ta.conn.makeRequest({
                Cmd: "queryPost",
                key_remote_id: messenger.Chat.key_remote_id,
                key_id: key_id
            });
        }
    },
    Product: {
        queryProduct(data) {
            ta.conn.makeRequest({
                Cmd: "queryProduct",
                data: data
            });
        },
    },
    Quick: {
        queryQuick(data) {
            ta.conn.makeRequest({
                Cmd: "queryQuick",
                data: data
            });
        }
    },
    conn: {
        connect() {
            try {
                this.socket = new WebSocket(ta.config.host);
                this.socket.onopen = function () {
                    bLoggin = false;
                    bConnected = false;
                    if (ta.config.WebSessionToken != null) {
                        ta.account.logon();
                    }
                }
                this.socket.onmessage = function (msg) {
                    var json = JSON.parse(msg.data);

                    if (json.status == 200) {
                        switch (json.Cmd) {
                            case 'login':
                                bLoggin = true;
                                bConnected = true;
                                if (localStorage.getItem("status") === "online") {
                                    ta.account.available();
                                    $('#checkbox-on-off').lcs_on();
                                }
                                if (localStorage.getItem("chat_to") != "") {
                                    ta.chat.open(localStorage.getItem("chat_to"));
                                    localStorage.setItem("chat_to", "");
                                    setTimeout(() => {
                                        unblockChatLink()
                                    }, 400)
                                }
                                break;
                            case 'Contact':
                                switch (json.event) {
                                    case 'update':
                                        if (json.items.length > 0) {
                                            let i = 0;
                                            for (i = 0; i < ta.ContactList.length; i++) {
                                                ta.ContactList.splice(i, 1);
                                            }
                                            for (i = 0; i < json.items.length; i++) {
                                                let obj = {
                                                    key_remote_id: json.items[i].key_remote_id,
                                                    push_name: json.items[i].push_name
                                                };
                                                ta.ContactList.push(obj);
                                            }
                                        }
                                        break;
                                }
                                break;
                            case 'blocklist':
                                messenger.ChatList.Remove(json.key_remote_id);
                                break;
                            case 'queryTicket':
                                processTicket(json);
                                break;
                            case 'queryProduct':
                                processProduct(json);
                                break;
                            case 'queryInfoTicket':
                                processTicket(json);
                            case 'queryUsers':
                                processUsers(json);
                                break;
                            case 'queryUsersGroups':
                                processUserGroup(json);
                                break;
                            case 'queryQuick':
                                processQuickReplies(json);
                                break;
                            case 'queryPresence':
                                processLastSeen(json);
                                break;
                            case 'Chat':
                                switch (json.event) {
                                    case 'Add':
                                        messenger.ChatList.Add(json, true);
                                        break;
                                    case "Reply":
                                        const channel_cloud_types = json.items.filter(item => item.type == 12 || item.type == 16);
                                        const channel_other_types = json.items.filter(item => item.type != 12 && item.type != 16);

                                        for (let i = 0; i < channel_other_types.length; i++) {
                                            messenger.ChatList.Add(channel_other_types[i], false);
                                        }

                                        for (let i = 0; i < channel_cloud_types.length; i++) {
                                            messenger.ChatList.Add(channel_cloud_types[i], false);
                                        }
                                        break;
                                    case 'close':
                                        messenger.ChatList.Remove(json.key_remote_id);
                                        break;
                                    case 'Delete':
                                        messenger.ChatList.Remove(json.key_remote_id);
                                        break;
                                }
                                $('#list-active').find('.item').sort(function (a, b) {
                                    return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
                                }).appendTo('#list-active');

                                $('#list-internal').find('.item').sort(function (a, b) {
                                    return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
                                }).appendTo('#list-internal');

                                $('#list-wait').find('.item').sort(function (a, b) {
                                    return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
                                }).appendTo('#list-wait');
                                break;
                            case 'Exist':
                                $("#modal").hide();
                                switch (json.status) {
                                    case 200:
                                        alert(GLOBAL_LANG.messenger_contact_exist);
                                        break;
                                }
                                break;
                            case 'InfoNote':
                                document.getElementById('save-note').disabled = false;
                                swal({
                                    title: GLOBAL_LANG.messenger_contact_modal_title,
                                    text: GLOBAL_LANG.messenger_contact_info_note,
                                    type: 'success',
                                    confirmButtonColor: '#2263d3',
                                    confirmButtonText: GLOBAL_LANG.messenger_contact_modal_btn_ok,
                                    cancelButtonClass: "btn btn-secondary"
                                });
                                break;
                            case 'Loading':
                                $("#loading").hide();
                                break;
                            case 'openChat':
                                $("#list-find").hide();
                                $("#list-find").html("");
                                $(".find input").val("");

                                //open contact in input-search-contato
                                // if (json.isPrivate == true) {
                                messenger.ChatList.Open(json.key_remote_id);
                                //}

                                break;
                            case 'savedContact':
                                $(".find input").val(json.key_remote_id.split('-')[0]);

                                doneSearch();
                                $("#list-find").show();
                                break;
                            case 'Verify':
                                swal({
                                    title: GLOBAL_LANG.messenger_contact_title,
                                    text: GLOBAL_LANG.messenger_contact_verify_no_account.replace("{{key_remote_id}}", json.key_remote_id),
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: GLOBAL_LANG.messenger_contact_modal_btn_ok,
                                    cancelButtonClass: "btn btn-secondary"
                                });
                                break;
                            case 'Wait':
                                info = messenger.ChatList.findByKeyRemoteId(json.key_remote_id);
                                switch (json.event) {
                                    case 'move':
                                        let chat = $("#" + info.hash).clone();
                                        $("#" + info.hash).remove();
                                        $("#list-active").prepend(chat);
                                        messenger.ChatList.updateCountView();
                                        break;
                                }
                                break;
                            case 'searchMessage':
                                boxSearchMessage(json);
                                break;
                            case 'queryContact':
                                if (json.items.length > 0) {
                                    if (json.clear == true) {
                                        $("#list-find").html("");
                                    }
                                    for (var i = 0; i < json.items.length; i++) {
                                        messenger.Contact.add(json.items[i]);
                                    }
                                }
                                break;
                            case 'queryContactForward':
                                $(".loadContactForward").remove();
                                $(".modalContactForward").find('.body').empty();

                                if (json.items.length > 0) {

                                    for (var i = 0; i < json.items.length; i++) {
                                        if (new Date(json.items[i].last_timestamp_client * 1000) >= new Date().setHours(-24) || json.items[i].is_private == 2 || json.items[i].type == 2) {

                                            $(".modalContactForward").find('.body').append(`
                                                        <div class="cards-contact">
                                                            <input type="checkbox" class="input-send-message-forward" value="${json.items[i].key_remote_id}">
                                                            <img src="${document.location.origin + "/assets/img/avatar.svg"}" 
                                                            style="margin: 5px">
                                                            <span class="name-cards-contact" style="margin-top: 22px; margin-left: 10px;"> ${json.items[i].full_name}</span>
                                                        </div>
                                                    `);

                                        } else {

                                            $(".modalContactForward").find('.body').append(`
                                                        <div class="cards-contact" id="disabledTrue" disabled>
                                                            <input type="checkbox" class="input-send-message-forward" disabled>
                                                            <img src="${document.location.origin + "/assets/img/avatar.svg"}" 
                                                            style="margin: 5px; opacity: 0.6;">
                                                            <div>
                                                                <span class="name-cards-contact" dataset-disabledTrue="true" style=" display:block; margin-top: 22px; margin-left: 10px; color: #8080806b;" disabled> ${json.items[i].full_name}</span>
                                                                <span dataset-disabledTrue="true" style="font-size: 12px;color: #8080806b;font-style: italic; margin-left: 10px; display: block;">${GLOBAL_LANG.messenger_modal_forward_window_closed}</span>
                                                            </div>
                                                        </div>
                                                `);
                                        }
                                    }
                                }
                                break
                            case 'InfoContact':
                                $("#modal").hide();
                                swal({
                                    title: GLOBAL_LANG.messenger_contact_title,
                                    text: GLOBAL_LANG.messenger_contact_edited,
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: GLOBAL_LANG.messenger_contact_modal_btn_ok,
                                    cancelButtonClass: "btn btn-secondary"
                                });
                                break;
                            case 'PushName':
                                const contactNumber = json.key_remote_id.split('-')[0];
                                info = messenger.ChatList.findByKeyRemoteId(json.key_remote_id);
                                $("#" + info.hash + " .contact-name-label .contact-name-span").html(Util.doTruncarStr(json.push_name != "" && json.push_name != null && json.push_name != '0' ? json.push_name : contactNumber, 20));
                                for (let i = 0; i < messenger.Chats.length; i++) {
                                    if (messenger.Chats[i].key_remote_id == json.key_remote_id) {
                                        messenger.Chats[i].push_name = json.push_name != "" && json.push_name != null && json.push_name != '0' ? json.push_name : contactNumber;
                                        messenger.Chat.show();
                                    }
                                }
                                break;
                            case 'ProfilePictureThumb':
                                info = messenger.ChatList.findByKeyRemoteId(json.key_remote_id);

                                if (info != null) {
                                    $("#" + info.hash + " .contact-image .avatar").attr("src", json.eurl);
                                    $("#" + info.hash + "_imgProfileTeam").attr("src", json.eurl);
                                } else {
                                    $("#" + json.key_remote_id + " .contact-image .avatar").attr("src", json.eurl);
                                    $("#" + json.key_remote_id + "_imgProfileTeam").attr("src", json.eurl);
                                }
                                break;
                            case 'Revoke':
                                info = messenger.ChatList.findByKeyRemoteId(json.key_remote_id);
                                if (info != null) {
                                    if (item_id != json.key_id) {

                                        let fontSizeText = messenger.Message.message_item.style.fontSize = localStorage.getItem("fontRevokeMessage");

                                        $("#" + json.key_id).html(`
                                            <div class="forward" style="width: 0px; float: left; display: flex; align-items: center;">
                                                <input type="checkbox" class="checkbox-forward" style="cursor: pointer; display: none;">
                                            </div>
                                            <div class="revoke Left" style="float: left; font-size: ${fontSizeText}; padding-right: 0%; padding-bottom: 0.5%;">
                                                <i class="fas fa-angle-down dropdown" style="margin-top: 0px; width: 23px; position: absolute; right: 3px; font-size: 22px; color: rgba(90, 90, 90, 0.91); display: none;"></i>                                          
                                                <div class="body" style="width: 100%; float: left;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 19">
                                                        <path fill="currentColor" d="M12.629 12.463a5.17 5.17 0 0 0-7.208-7.209l7.208 7.209zm-1.23 1.229L4.191 6.484a5.17 5.17 0 0 0 7.208 7.208zM8.41 2.564a6.91 6.91 0 1 1 0 13.82 6.91 6.91 0 0 1 0-13.82z"></path>
                                                    </svg>
                                                    <span>${GLOBAL_LANG.messenger_message_deleted}</span>
                                                </div>
                                            </div>
                                        `);
                                    } else {
                                        $("#" + item_id).html(`
                                            <div class="forward" style="width: 0px; float: left; display: flex; align-items: center; flex: 1 1 0%;">
                                                <input type="checkbox" style="height: 25px; display: none;">
                                            </div>
                                            <div class='revoke right' style='float:right; display: block; padding-right: 0%; padding-bottom: 0.5%;'>
                                                <div class="body" style="float: left;">
                                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 17 19'>
                                                        <path fill='currentColor' d='M12.629 12.463a5.17 5.17 0 0 0-7.208-7.209l7.208 7.209zm-1.23 1.229L4.191 6.484a5.17 5.17 0 0 0 7.208 7.208zM8.41 2.564a6.91 6.91 0 1 1 0 13.82 6.91 6.91 0 0 1 0-13.82z'></path>
                                                    </svg>
                                                    <span>${GLOBAL_LANG.messenger_deleted_message}</span>
                                                </div>
                                            </div>
                                        `);
                                    }
                                    if (item_id != json.key_id) {
                                        $("#" + info.hash + " .last-message").html(`🚫 ${Util.doTruncarStr(GLOBAL_LANG.messenger_message_deleted, 20)}`);

                                    } else {
                                        $("#" + info.hash + " .last-message").html(`🚫 ${Util.doTruncarStr(GLOBAL_LANG.messenger_deleted_message, 20)}`);
                                        $(".popmenu").remove();
                                        $("#" + item_id + " .dropdown").hide();
                                        item_id = 0;
                                        bShowPopMenu = false;
                                    }
                                }
                                break;
                            case 'queryInfo':
                                if (json.items.length > 0) {

                                    let contactNumber = json.items[0].key_remote_id.split('-')[0];

                                    document.getElementById("name").innerHTML = Util.doTruncarStr(json.items[0].full_name != "" && json.items[0].full_name != null && json.items[0].full_name != '0' ? json.items[0].full_name : contactNumber, 29);
                                    document.getElementById("talkall_id").innerHTML = formatNumber(contactNumber);
                                    document.getElementById("full_name_info").innerHTML = json.items[0].full_name;

                                    let info = messenger.ChatList.findByKeyRemoteId(json.items[0].key_remote_id);

                                    if (info.type > 1) {
                                        document.getElementById("optionChannel").style.display = "flex";
                                        document.getElementById("nameChannel").style.display = "flex";
                                    } else {
                                        document.getElementById("optionChannel").style.display = "none";
                                        document.getElementById("nameChannel").style.display = "none";
                                    }

                                    switch (info.type) {
                                        case 8:
                                            document.querySelector("#nameChannel").childNodes[1].className = 'fa fa-facebook-official'
                                            break;
                                        case 9:
                                            document.querySelector("#nameChannel").childNodes[1].className = 'fa fa-instagram'
                                            break;
                                        case 12:
                                        case 16:
                                            document.querySelector("#nameChannel").childNodes[1].className = 'fa fa-whatsapp'
                                            break;

                                        default:
                                            break;
                                    }

                                    document.getElementById("type_channel").innerHTML = Util.doTruncarStr(json.items[0].channel_name, 30);
                                    document.getElementById("talkall_channel").innerHTML = info.channel_id;

                                    if (json.items[0].note === "" || json.items[0].note == null) {

                                        $("#infoNoteContact").val("");
                                        $(".note-contact").find(".subtitle").hide();
                                        $(".note-contact").find(".box-note").hide();
                                        $(".note-contact").find(".add-note-contact").show();
                                    } else {
                                        $(".info-note-contact").val(json.items[0].note);
                                        $(".note-contact").find(".subtitle").show();
                                        $(".note-contact").find(".box-note").hide();
                                        $("#infoNoteContact").val($("#note").val());
                                        $(".note-contact").find(".add-note-contact").hide();
                                    }

                                    verifyChannelContact(messenger.Chat.is_type);
                                    verifyEmailContact(json.items[0].email);

                                    if (json.items[0].crm_profile != null) {
                                        $("#crm-link").show();
                                        $("#crm-link").attr("href", json.items[0].crm_profile);
                                    } else {
                                        $("#crm-link").hide();
                                    }
                                    queryTicket(json);
                                    labelContactInfo();
                                }
                                break;
                            case 'queryInfoMsg':
                                switch (parseInt(json.message.media_type)) {
                                    case 1:
                                        ta.message.sendText(json.key_remote_id, json.message.data, 1);
                                        break;
                                    case 2:
                                        ta.message.sendAudio(json.key_remote_id, json.message.media_mime_type, json.message.media_size, json.message.media_duration, json.message.media_url, 1);
                                        break;
                                    case 3:
                                        ta.message.sendImage(json.key_remote_id, json.message.media_caption, json.message.media_url, json.message.media_mime_type, json.message.media_size, "", 1);
                                        break;
                                    case 4:
                                        ta.message.sendDocument(json.key_remote_id, Util.makeId(), json.message.media_name, json.message.media_size, json.message.page_count, json.message.media_url, json.message.media_mime_type, json.message.thumb_image, 1);
                                        break;
                                    case 5:
                                        ta.message.sendVideo(json.key_remote_id, json.message.key_id, json.message.media_name, json.message.media_duration, json.message.media_size, json.message.media_mime_type, json.message.media_url, json.message.thumb_image, 1);
                                        break;
                                }
                                break;
                            case 'queryGroupParticipants':
                                if (json.participants.length > 0) processParticipants(json);
                                break;
                            case 'Ack':
                                switch (parseInt(json.ack)) {
                                    case 1:
                                        if (parseInt($("#" + json.key_id + " .ackMessage")[0].innerHTML) < parseInt(json.ack)) {
                                            $("#" + json.key_id + " .ackMessage")[0].innerHTML = parseInt(json.ack);
                                            $("#" + json.key_id + " .bottom svg").html(Util.msgSend());
                                        }
                                        break;
                                    case 2:
                                        if (parseInt($("#" + json.key_id + " .ackMessage")[0].innerHTML) < parseInt(json.ack)) {
                                            $("#" + json.key_id + " .ackMessage")[0].innerHTML = parseInt(json.ack);
                                            $("#" + json.key_id + " .bottom svg").html(Util.msgReceived());
                                        }
                                        break;
                                    case 3:
                                        if (parseInt($("#" + json.key_id + " .ackMessage")[0].innerHTML) < parseInt(json.ack)) {
                                            $("#" + json.key_id + " .ackMessage")[0].innerHTML = parseInt(json.ack);
                                            $("#" + json.key_id + " .bottom svg").html(Util.msgRead());
                                        }
                                        break;
                                    case 4:
                                        const payment_message = {
                                            "Cmd": "paymentMessage",
                                            "items":
                                            {
                                                "title": GLOBAL_LANG.messenger_modal_error_payment_method_title,
                                                "text": GLOBAL_LANG.messenger_modal_error_payment_method_text,
                                                "url": "https://business.facebook.com/",
                                                "link_text": GLOBAL_LANG.messenger_modal_error_payment_method_url
                                            }
                                        };
                                        if (json.key_remote_id == messenger.Chat.key_remote_id)
                                            showAlertMessages(payment_message);
                                        break;
                                    case 98:
                                        alert(GLOBAL_LANG.messenger_alert_no_balance);
                                        break;
                                    case 99:
                                        alert(`${json.key_remote_id} ${GLOBAL_LANG.messenger_alert_not_whatsapp_user}`);
                                        break;
                                }
                                break;
                            case 'queryLabel':
                                processLabels(json);
                                break;
                            case 'Conn':
                                switch (json.type) {
                                    case 'disconnect':
                                        bReConnect = false;
                                        $("#conn-replace").show();
                                        break;
                                    case 'force_disconnect':
                                        bReConnect = false;
                                        localStorage.clear();
                                        close();
                                        break;
                                    case 'update_web_session':
                                        localStorage.setItem("WebSessionToken", json.web_session);
                                        break;
                                    case 'old_version':
                                        alert(GLOBAL_LANG.messenger_alert_connect_old_version);
                                        break;
                                    case 'close':
                                        socket.close();
                                        window.location = "account/logoff";
                                        break;
                                }
                                break;
                            case 'Message':
                                clearTimeout(CHECK_SCROLL);

                                if (BUTTON_BOTTOM_SCROLL) {
                                    $("#" + messenger.Chat.token).find(".item").remove();
                                }

                                if (json.items.length > 0) {
                                    if (json.reverse === true) {
                                        json.items.sort((x, y) => x.creation - y.creation);
                                    }
                                    for (let i = 0; i < json.items.length; i++) {
                                        switch (parseInt(json.items[i].media_type)) {
                                            case 1:
                                                messenger.Message.TextMessage(json.items[i], json.reverse);
                                                break;
                                            case 2:
                                                messenger.Message.AudioMessage(json.items[i], json.reverse);
                                                break;
                                            case 3:
                                                messenger.Message.ImageMessage(json.items[i], json.reverse);
                                                break;
                                            case 4:
                                                messenger.Message.DocumentMessage(json.items[i], json.reverse);
                                                break;
                                            case 5:
                                                messenger.Message.VideoMessage(json.items[i], json.reverse);
                                                break;
                                            case 6:
                                                messenger.Message.GifMessage(json.items[i], json.reverse);
                                                break;
                                            case 7:
                                                messenger.Message.LocationMessage(json.items[i], json.reverse);
                                                break;
                                            case 9:
                                                messenger.Message.ContactMessage(json.items[i], json.reverse);
                                                break;
                                            case 10:
                                                messenger.Message.ZipMessage(json.items[i], json.reverse);
                                                break;
                                            case 18:
                                                messenger.Message.RevokeMessage(json.items[i], json.reverse);
                                                break;
                                            case 19:
                                                messenger.Message.InformationMessage(json.items[i], json.reverse);
                                                break;
                                            case 20:
                                                messenger.Message.TransMessage(json.items[i], json.reverse);
                                                break;
                                            case 21:
                                                messenger.Message.StartMessage(json.items[i], json.reverse);
                                                break;
                                            case 22:
                                                messenger.Message.WaitMessage(json.items[i], json.reverse);
                                                break;
                                            case 23:
                                                messenger.Message.CloseMessage(json.items[i], json.reverse);
                                                break;
                                            case 24:
                                                messenger.Message.ProtocolMessage(json.items[i], json.reverse);
                                                break;
                                            case 25:
                                                messenger.Message.AttendanceMessage(json.items[i], json.reverse);
                                                break;
                                            case 26:
                                                messenger.Message.StickerMessage(json.items[i], json.reverse);
                                                break;
                                            case 27:
                                                messenger.Message.TemplateMessage(json.items[i], json.reverse);
                                                break;
                                            case 28:
                                                messenger.Message.CardListMessage(json.items[i], json.reverse);
                                                break;
                                            case 30:
                                            case 35:
                                                messenger.Message.InteractiveMessage(json.items[i], json.reverse);
                                                break;
                                            case 32:
                                                messenger.Message.StoryMentionMessage(json.items[i], json.reverse);
                                                break;
                                        }
                                    }

                                    for (i = 0; i < messenger.Chats.length; i++) {
                                        if (messenger.Chats[i].key_remote_id == messenger.Chat.key_remote_id) {
                                            messenger.Chats[i].create = json.items[json.items.length - 1].creation;
                                            break;
                                        }
                                    }

                                    if (BUTTON_BOTTOM_SCROLL) {

                                        BUTTON_BOTTOM_SCROLL = false;
                                        setTimeout(() => { $("#" + messenger.Chat.token).scrollTop($("#" + messenger.Chat.token).prop("scrollHeight") + 999) }, 100);
                                    }

                                    if (ITEM_FOCUSED !== "" && !LOCK_FOCUS) {

                                        SCROLL_BLOCK = true;
                                        setTimeout(() => {
                                            redirectMessage(ITEM_FOCUSED);
                                        }, 500);
                                    }

                                    if (SCROLL_TOKEN_TOP !== "") {
                                        messenger.Chats.tokenScroll = SCROLL_TOKEN_TOP;
                                        $("#" + SCROLL_TOKEN_TOP)[0].scrollIntoView();
                                        $("#" + messenger.Chat.token).scrollTop($("#" + messenger.Chat.token).scrollTop() - 70);
                                    }

                                    SCROLL_TOKEN_TOP = "";
                                }

                                if (FORCE_SCROLL_DOWN) {

                                    let create = 0, chat_id = "", element = "";

                                    $(".chat .messages").each((idx, elm) => {
                                        if (elm.style.display != "none") {
                                            element = elm;
                                            chat_id = elm.id;
                                            create = $(elm).find(".item").last()[0].attributes[1].value;
                                        }
                                    });

                                    CHECK_SCROLL = setTimeout(() => {

                                        const checkScroll = $("#" + chat_id)[0].clientHeight < $("#" + chat_id)[0].scrollHeight;

                                        if (!checkScroll) {

                                            const img = document.createElement("img");
                                            img.id = "load_bottom_chat";
                                            img.src = document.location.origin + "/assets/img/loads/loading_2.gif";
                                            img.style = "width:5%; margin:0 auto; text-align:center; padding:8px";
                                            element.append(img);

                                            ITEM_FOCUSED = "";

                                            clearTimeout(QUERY_MESSAGES_CHAT);
                                            QUERY_MESSAGES_CHAT = setTimeout(() => {

                                                LOCK_FOCUS = true;
                                                ta.chat.queryMessages(ta.key_remote_id, create, true);
                                                $("#load_bottom_chat").remove();

                                                for (item of document.querySelectorAll(".item-searched")) item.disabled = false;
                                                document.getElementById("view-tem-gallery").disabled = false;
                                            }, 3000);
                                        }
                                    }, 1500);

                                    FORCE_SCROLL_DOWN = false;
                                }

                                break;
                            case 'action':
                                info = messenger.ChatList.findByKeyRemoteId(json.key_remote_id);
                                if (json.Chat.type == "move") {
                                    $(".option").hide();
                                    $("#modal").hide();
                                    if (messenger.Chat.key_remote_id == info.key_remote_id) $(".right").hide();
                                    $("#" + info.hash).remove();
                                    $("#" + info.chat).remove();
                                    messenger.ChatList.Remove(json.key_remote_id);
                                    messenger.ChatList.updateCountView();
                                }
                                break;
                            case 'Msg':
                                if (json.key_remote_id != undefined) {
                                    updateKeyRemoteId(json.key_remote_id);
                                }

                                let data = "";
                                info = messenger.ChatList.findByKeyRemoteId(json.key_remote_id);

                                if (typeof json.telegram_read_confirmation !== 'undefined' && json.telegram_read_confirmation) {

                                    const satanize_hash = info.chat
                                        .replace(/[!"#$%&'()*+,./:;<=>?@[\\\]^`{|}~]/g, '\\$&')
                                        .replace(/^\d/, '\\3$& ');

                                    const read_confirmation = document.querySelectorAll("#" + satanize_hash + " .bottom svg");

                                    read_confirmation.forEach((item) => {
                                        item.innerHTML = Util.msgRead();
                                    });
                                }

                                if (info == null) {
                                    ta.chat.open(json.key_remote_id, false);
                                }

                                if (verify_tailOut) {
                                    verify_tailOut = false;
                                    tailOut = 0;
                                }

                                if (json.key_from_me == 1) {
                                    messenger.ChatList.setLastTimestampClient(json.key_remote_id, json.creation);
                                }

                                if (json.key_from_me == 2) {
                                    if (document.getElementById(json.token)) document.getElementById(json.token).dataset.index = json.creation;
                                }

                                switch (parseInt(json.media_type)) {
                                    case 1:
                                        data = Util.doTruncarStr(json.data.replace(/\\n\\r/g, " "), 20);
                                        messenger.Message.TextMessage(json, true);
                                        break;
                                    case 2:
                                        data = `🎤 ${Util.FormatDuration(json.media_duration)}`;
                                        messenger.Message.AudioMessage(json, true);
                                        break;
                                    case 3:
                                        data = `📷 ${GLOBAL_LANG.messenger_media_types_photo}`;
                                        messenger.Message.ImageMessage(json, true);
                                        break;
                                    case 4:
                                        data = `📃 ${GLOBAL_LANG.messenger_media_types_document}`;
                                        messenger.Message.DocumentMessage(json, true);
                                        break;
                                    case 5:
                                        data = `🎥 ${GLOBAL_LANG.messenger_media_types_video}`;
                                        messenger.Message.VideoMessage(json, true);
                                        break;
                                    case 6:
                                        data = `👾 ${GLOBAL_LANG.messenger_media_types_gif}`;
                                        messenger.Message.GifMessage(json, true);
                                        break;
                                    case 7:
                                        data = `📍 ${GLOBAL_LANG.messenger_media_types_location}`;
                                        messenger.Message.LocationMessage(json, true);
                                        break;
                                    case 9:
                                        data = `👤 ${GLOBAL_LANG.messenger_media_types_contact}`;
                                        messenger.Message.ContactMessage(json, true);
                                        break;
                                    case 10:
                                        data = `🗄 ${GLOBAL_LANG.messenger_media_types_file}`;
                                        messenger.Message.ZipMessage(json, true);
                                        break;
                                    case 26:
                                        data = `👾 ${GLOBAL_LANG.messenger_media_types_sticker}`;
                                        messenger.Message.StickerMessage(json, true);
                                        break;
                                    case 27:
                                        data = `📃 ${GLOBAL_LANG.messenger_media_types_template}`;
                                        messenger.Message.TemplateMessage(json, true);
                                    case 28:
                                        messenger.Message.CardListMessage(json, true);
                                        break;
                                    case 30:
                                    case 35:
                                        data = `📃 ${GLOBAL_LANG.messenger_media_types_interactive_message}`;
                                        messenger.Message.InteractiveMessage(json, true);
                                        break;
                                    case 32:
                                        data = `🎴 ${json.data != undefined ? GLOBAL_LANG.messenger_media_types_story_reply : GLOBAL_LANG.messenger_media_types_story_mention}`;
                                        messenger.Message.StoryMentionMessage(json, true);
                                        break;
                                    case 34:
                                        if (json.reaction) {
                                            data = `${GLOBAL_LANG.messenger_list_chat_reaction} ${json.reaction}`;
                                        } else {
                                            let chats = messenger.Chats;
                                            for (let i = 0; i < chats.length; i++) {
                                                const element = chats[i];
                                                if (element.key_remote_id === json.key_remote_id) {
                                                    data = messenger.ChatList.setLastMessage(element);
                                                    break;
                                                }
                                            }
                                        }
                                        messenger.Message.verifyReaction(json);
                                        break;
                                }

                                if (json.key_from_me == 1) {

                                    $("#" + info.hash + " .last-message").html(data);

                                    let messenger_selected = messenger.Chat.selected;

                                    if (messenger_selected == info.hash) {
                                        unblockChat();
                                    }

                                    if (json.media_type != 34) {

                                        $("#" + info.hash + " .no-read-message").show();
                                        $("#" + info.hash + " .no-read-message label").html(parseInt($("#" + info.hash + " .no-read-message label").html() == " " ? 0 : $("#" + info.hash + " .no-read-message label").html()) + 1).css({ 'color': 'White' });
                                        $("#" + info.hash + " .time").html(Util.FormatShortTime(json.creation));

                                        messenger.ChatList.updateCountView();

                                        if (messenger_selected != json.key_remote_id && json.event == "Add") {
                                            postNotification(
                                                ta.contact.pushName(json.key_remote_id),
                                                $("#" + info.hash + " img")[1].src,
                                                data,
                                                json.key_remote_id
                                            );
                                        }

                                        if (messenger.Chat.key_remote_id == json.key_remote_id) {
                                            $("#" + messenger.Chat.token).scrollTop($("#" + messenger.Chat.token).prop("scrollHeight") + 999);
                                        }
                                    }
                                }

                                if (json.media_type != 34) {
                                    messenger.ChatList.setLastMessageByKeyRemoteId(json.key_remote_id, data, json.media_type);

                                    if ($('#md_' + info.hash).parent().find('.iconFixar').hasClass('fixed') == false) {

                                        $('#' + info.hash).data('index', json.creation);
                                        $('#' + info.hash).data('last_timestamp_client', json.creation);

                                        $('#list-active').find('.item').sort(function (a, b) {
                                            return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
                                        }).appendTo('#list-active');

                                        $('#list-internal').find('.item').sort(function (a, b) {
                                            return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
                                        }).appendTo('#list-internal');
                                    }
                                }

                                if (json.message_sent_api) {
                                    $("#" + messenger.Chat.token).scrollTop($("#" + messenger.Chat.token).prop("scrollHeight") + 999);
                                }
                                break;
                            case 'ticket':
                                //connectWebSocketTicket();
                                break;
                            case 'queryCardList':
                                if (json.items.length > 0) openCardList(json.items);
                                break;
                            case 'queryOrderStatus':
                                if (json.items.length > 0) {
                                    var id_order_status = $("#order_status").val();
                                    $("#order_status").text("");
                                    for (let i = 0; i < json.items.length; i++) {
                                        $("#order_status").append(`<option value="${json.items[i].id_order_status}">${json.items[i].name}</option>`);
                                        if (id_order_status == json.items[i].id_order_status) {
                                            $(`#order_status>option[value='${id_order_status}']`).attr("selected", true);
                                        }
                                    }
                                }
                                break;
                            case 'queryGallery':
                                galleryMessenger(json);
                                break;
                            case 'queryPreviewGallery':
                                galleryPreviewMessenger(json);
                                break;
                            case 'queryCatalog':
                                CatalogMessenger(json);
                                break;
                            case 'queryChannel':
                                $(".bgboxCatalog").fadeOut("fast");
                                $(".modalCatalog").fadeOut("fast");

                                let channel = json.itens[0].id.replace("@c.us", "");
                                let url = $(".modalCatalog .body .container").eq(0).find(".container-inner").find(".box").find(".img").attr("src");

                                $(".reply-message .message").html(`<img class="img-link-catalog" width="110px" src="${url}"> 
                                <span class="title-link-catalog">${GLOBAL_LANG.messenger_open_catalog_on_whatsapp}</span> <span class="subtitle-link-catalog">${GLOBAL_LANG.messenger_learn_about_products}</span>`);

                                $(".text .input-text").text(GLOBAL_LANG.messenger_link_to_catalog.replace("{{channel}}", channel));
                                $(".messenger .right .chat .reply-message .message").css({ "border-left": "0px solid #4ec545" });
                                $(".reply-message").show("1000");
                                break;

                            case 'queryStarred':
                                $(".load_starred").remove();
                                $(".load_starred_footer").remove();

                                openStarred(json);
                                break;
                            case 'queryParamsContact':
                                processParamsContact(json);
                                break;
                            case 'querySubtype':
                                listSubtype(json);
                                break;
                            case 'queryAlertNotifications':
                            case 'alertMessage':
                                alertMessage(json);
                                break;
                            case 'queryTeam':
                                createListTeam(json);
                                break;
                            case 'queryTemplates':
                                openModalTemplete(json);
                                break;
                            case 'queryPaymentMessage':
                                checkPaymentMessage(json);
                                break;
                            case 'removeNotification':
                                removeNotification(json);
                                break;
                            case 'getNotificationUrl':
                                getNotificationUrl(json);
                                break;
                            case 'loadAttendanceProfile':
                                loadAttendanceProfile(json)
                                break;
                            case 'queryCategories':
                                setOptionCategories(json.items);
                                queryCategories(json);
                                break;
                        }
                    }
                    if (json.Cmd == "login") {
                        if (json.status == 501) {
                            bReConnect = false;
                            alert(GLOBAL_LANG.messenger_login_alert_invalid_access);
                        }
                        if (json.status == 1000) {
                            bReConnect = false;
                            if (json.reason == "connection replace") {
                                if (json.session === WebSessionBowser) {
                                    $("#conn-replace").show();
                                }
                                else {
                                    window.location.href = 'account/login';
                                }
                            }

                        }
                    }
                    if (json.Cmd == "Open") {
                        if (json.status == 401) {
                            if (contact_name != undefined) {
                                saveContact();
                            }
                        }
                    }
                    if (json.Cmd == "queryCreditConversation") {
                        localStorage.setItem('enabled_send', json.enabled_send);
                        if (json.enabled_send == false) {
                            creditBlock();
                        } else {
                            if (json.minimum_credit_limit == true) {
                                $("#alert-credit-minimum").show();
                                $(".chat")[0].style = "z-index: 1; height: calc(100% - 86px);";

                                $(".messenger .right .chat").css("transition", "none");
                                $(".messenger .right .chat").css({ "background-color": localStorage.getItem("colorWallpaper") || "" });
                                setTimeout(() => $(".messenger .right .chat").css("transition", "0.3s ease-in"), 500);
                            }

                            document.getElementById("bottomEntryRectangle").style.display = "flex";
                            blockToType(localStorage.getItem("last_timestamp_chat"), info);

                            $(".input-text").focus();
                        }
                    }
                    if (json.Cmd == "Verify") {
                        if (json.status == 404) {
                            swal({
                                title: GLOBAL_LANG.messenger_contact_title,
                                text: GLOBAL_LANG.messenger_contact_verify_no_account.replace("{{key_remote_id}}", json.key_remote_id),
                                type: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: GLOBAL_LANG.messenger_contact_modal_btn_ok,
                                cancelButtonClass: "btn btn-secondary"
                            });
                        }
                        if (json.status == 201) {
                            swal({
                                title: GLOBAL_LANG.messenger_contact_error_title,
                                text: GLOBAL_LANG.messenger_contact_not_verified.replace("{{key_remote_id}}", json.key_remote_id),
                                type: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: GLOBAL_LANG.messenger_contact_modal_btn_ok,
                                cancelButtonClass: "btn btn-secondary"
                            });
                        }

                        for (item of document.querySelectorAll("#list-find .item")) item.disabled = false;
                    }
                    if (json.Cmd == "Msg") {
                        if (json.status == 401) {
                            alert(GLOBAL_LANG.messenger_template_alert_check_account_balance);
                        }
                    }
                    if (json.Cmd == "Exist") {
                        if (json.status == 404) {
                            alert(GLOBAL_LANG.messenger_contact_no_whatsapp_account.replace("{{key_remote_id}}", json.key_remote_id));
                            $(".number").val("");
                            $(".number").focus();
                            $("#modal").hide();
                        }
                    }
                    if (json.status == 201) {
                        if (json.Cmd == "Exist") {
                            alert(GLOBAL_LANG.messenger_contact_already_registered.replace("{{key_remote_id}}", json.key_remote_id));
                            $(".number").val("");
                            $(".number").focus();
                            $("#modal").hide();
                        } else if (json.Cmd == "Spam") {
                            alert(GLOBAL_LANG.messenger_contact_reported_spam.replace("{{key_remote_id}}", json.key_remote_id));
                            $(".number").val("");
                            $(".number").focus();
                            $("#modal").hide();
                        }
                    }
                    if (json.status == 401) {
                        if (json.Cmd == "Exist") {
                            alert(GLOBAL_LANG.messenger_contact_error_title);
                            $(".number").val(GLOBAL_LANG.messenger_contact_generic_error);
                            $(".number").focus();
                            $("#modal").hide();
                            $("#modal").hide();
                        }
                        if (json.status == 201) {
                            alert(GLOBAL_LANG.messenger_contact_already_registered.replace("{{key_remote_id}}", json.key_remote_id));
                            $(".number").val("");
                            $(".number").focus();
                        }
                    }
                    if (json.status == 400) {
                        if (json.Cmd == "Chat") {
                            alert(GLOBAL_LANG.messenger_contact_in_service_with_other_user.replace("{{user_key_remote_id}}", json.user_key_remote_id));
                            $(".contact-add-chat").attr("value", GLOBAL_LANG.messenger_contact_send_message)
                            for (item of document.querySelectorAll("#list-find .item")) item.disabled = false;
                        }
                    }
                    if (json.Cmd == "searchMessage") {
                        if (json.status == 401) {

                            $("#load_search").find("img").remove();
                            $(".span-searched-message").remove();
                            $(".span-name-user-search").remove();
                            $(".span-search-empty").remove();

                            if ($(".list-message").find("div").hasClass("item-searched") == false) {
                                $(".list-message").prepend(`<span class="span-search-empty">${GLOBAL_LANG.messenger_window_search_message_not_found}</span>`);
                            }
                        }
                    }
                    if (json.Cmd == "queryContact") {
                        if (json.status == 401) {
                            $("#load_container_svg").remove();
                            // if ($(".text-search-contact").hasClass("verify-class") == false && $("#list-find").css("display", "block")) {
                            //     $(".left .find").append(`<span class="text-search-contact verify-class">Nenhum contato encontrado</span>`);
                            // }
                        }
                    }
                    if (json.Cmd == "queryGallery") {
                        if (json.status == 401) {

                            $(".window__gallery .load_gallery").remove();
                            $(".window__gallery .n_found_midia").remove();
                            $(".window__gallery #load_more_images_galley").remove();
                            setTimeout(() => getMoreImageGallery = false, 2000);

                            if ($("#col-gallery").find("div").hasClass("container-gallery") == false) {
                                $("#col-gallery").append(`<span class="n_found_midia" style="display: block; text-align: center; margin-top: 85px; font-size: 12px;
                                color: #9a9a9a;">${GLOBAL_LANG.messenger_window_gallery_not_found}</span>`);
                            }
                        }
                    }
                    if (json.Cmd == "queryDocument") {
                        if (json.status == 401) {
                            $(".load_gallery").remove();
                            $(".n_found_midia").remove();

                            if ($("#col-document").find("div").hasClass("container-documents") == false) {
                                $("#col-document").append(`<span class="n_found_midia" style="display: block; text-align: center; margin-top: 85px; font-size: 12px;
                                color: #9a9a9a;">${GLOBAL_LANG.messenger_window_gallery_not_found}</span>`);
                            }
                        }
                    }
                    if (json.Cmd == "queryPreviewGallery") {
                        if (json.status == 401) {
                            $("#previewGallery").find(".container").remove();
                        }
                    }
                    if (json.Cmd == "queryCatalog") {
                        if (json.status == 401) {
                            $(".not_found_catalog").remove();
                            $(".icon-search-catalog").show();
                            $(".load_search_catalog").remove();
                            $(".modalCatalog").find(".body").find(".container").remove();
                            $(".modalCatalog").find(".body").append(`<span class="not_found_catalog">${GLOBAL_LANG.messenger_no_catalog_found}</span>`);
                        }
                    }
                    if (json.Cmd == "queryStarred") {
                        if (json.status == 401) {

                            localStorage.setItem("load_starred", "false");

                            $(".load_starred").remove();
                            $(".load_starred_footer").remove();

                            if ($("#starred_box").find("div").hasClass("ongrups") == false) {
                                $("#starred_box").append(`<span class='none_starred'>${GLOBAL_LANG.messenger_window_favorite_no_messages}</span>`);
                            }

                        }
                    }
                    if (json.Cmd == "closeChat") {

                        let info = null;

                        for (let i = 0; i < messenger.Chats.length; i++) {
                            if (messenger.Chats[i].key_remote_id === json.key_remote_id) info = messenger.Chats[i];
                        }

                        if (info != null) {
                            $("#" + info.hash)[0].dataset.chat_open = false + "__" + json.user_name;
                        }
                    }

                }
                this.socket.onclose = function (e) {
                    console.log(e);
                    bLoggin = false;
                    bConnected = false;
                    if (bReConnect == true) {
                        setTimeout(function () { ta.conn.connect(); }, 5000);
                    }
                }
            } catch (exception) {
                bLoggin = false;
                bConnected = false;
                setTimeout(function () { ta.conn.connect(); }, 5000);
            }
        },
        makeRequest(json) {
            //this.socket.send(JSON.stringify(json));
            ta.queue.push(
                {
                    json: json,
                    status: 0
                }
            );
        },
        sendRequest(json) {
            this.socket.send(JSON.stringify(json));
        }
    },
};

function setCursorToEnd(element) {
    if (element && element.isContentEditable) {
        let range = document.createRange();
        let sel = window.getSelection();
        range.setStart(element, element.childNodes.length);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
    }
}

function doneSetStatus(type) {
    switch (type) {
        case 1:
            //ta.account.available();
            localStorage.setItem("status", "online");
            break;

        default:
            //ta.account.unavailable();
            localStorage.setItem("status", "offline");
            break;
    }
};

/*
function connectWebSocketTicket() {
    if (typeof io != 'undefined') {
        var socket;
        if (document.location.origin.indexOf('localhost') > -1)
            socket = io.connect(document.location.origin + ':4001', { query: `account=${'ticket-' + localStorage.getItem("userToken")}` });
        else
            socket = io.connect(document.location.origin + ':4001', { secure: true, query: `account=${'ticket-' + localStorage.getItem("userToken")}` });

        socket.emit('updateTicket');
    }
};
*/

$(document).ready(function () {
    ta.init();

    setTimeout(() => {
        for (var i = 0; i < document.querySelector("#list-active").childNodes.length; i++) {
            document.querySelector("#list-active").childNodes[i].setAttribute("data-numberChat", (i + 1));
        }
        for (var i = 0; i < document.querySelector("#list-internal").childNodes.length; i++) {
            document.querySelector("#list-internal").childNodes[i].setAttribute("data-numberChat", (i + 1));
        }
        for (var i = 0; i < document.querySelector("#list-wait").childNodes.length; i++) {
            document.querySelector("#list-wait").childNodes[i].setAttribute("data-numberChat", (i + 1));
        }
    }, 2000);
});