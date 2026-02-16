"use strict";

var socket;

const host = "wss://app.talkall.com.br:28192";
const userToken = "kanban-" + localStorage.getItem("userToken");
const webSessionToken = localStorage.getItem("WebSessionToken");
const appVersion = '2.2027.17';
const Components = new ComponentsDom();
const batteryFilterInfo = new Object();

let selectedAttendance = new Object();
let profileInfoCard, clearProfileInfoCard;
let typeInformation;

let collumnWaiting, collumnInService, collumnOnHold, collumnClosed, collumnUsers = [];
let collumnWaitingUserSearch = [], collumnInServiceUserSearch = [], collumnOnHoldUserSearch = [], collumnClosedUserSearch = [];
let collumnWaitingBatterySearch = [], collumnInServiceBatterySearch = [], collumnOnHoldBatterySearch = [], collumnClosedBatterySearch = [];
let collumnWaitingGenaralSearch = [], collumnInServiceGenaralSearch = [], collumnOnHoldGenaralSearch = [], collumnClosedGenaralSearch = [];

const colorPalette = {
    ligth: [
        "#F4F4F4", "#CACCD1", "#7D7D7C", "#515151", "#28292B", "#F1F6ED", "#D7E7D7", "#BBE7CF", "#B8D1C3", "#83B99C",
        "#F0ECC0", "#E7E6C0", "#DFE7C1", "#CCD1AE", "#B8B874", "#E5EAEC", "#CDEEEC", "#C6DCDE", "#90CFCD",
        "#0096A3", "#E9F5F8", "#D3EBF1", "#9FCFE9", "#558EBB", "#2B4F70", "#EBDAEA", "#EFBCD5", "#C6A3D5",
        "#A782B7", "#C0719E", "#E1C7BA", "#CFADA4", "#BB7561", "#927C72", "#56443C", "#F3D4DE", "#F5A8AC",
        "#DE767C", "#DC5C58", "#CD4244", "#F2E5E2", "#E7CFBE", "#D8BDAD", "#F3A29B", "#F5A892", "#EEE0C7",
        "#F8E7C4", "#F8DAC4", "#EF8A4E", "#C98B5A", "#F3F1E5", "#EEE9C9", "#F5E4BB", "#FFD973", "#E4C177"
    ],
    dark: [
        "#1D1D1D", "#8D8E91", "#585857", "#383838", "#1B1B1C", "#AAB0A7", "#98A896", "#84A693", "#82968C", "#5D8774",
        "#A7A47B", "#A2A17B", "#9DA47C", "#979B81", "#86864F", "#9FA4A6", "#8FA8A6", "#899A9B", "#659A98",
        "#006A75", "#A3B2B5", "#8DA3AE", "#6F98B2", "#3B6483", "#1E344A", "#A895A6", "#A67D95", "#8C779A",
        "#755C82", "#854E6F", "#9E877F", "#967D77", "#874E3F", "#655854", "#3B2F2A", "#A6848D", "#AC777A",
        "#9B5054", "#992D2A", "#8F2E2F", "#A89B98", "#9C8575", "#947A6D", "#A5645F", "#A86F5D", "#A1958F",
        "#A7917D", "#A47D6F", "#AC6536", "#8F6240", "#A8A697", "#A39E8C", "#A8937D", "#B39451", "#A1864E"
    ]
};

const Kanban = {
    attendance: {
        addTwoMoreWaiting: 0,
        addTwoMoreInService: 0,
        addTwoMoreOnHold: 0,
        addTwoMoreClosed: 0,
        tooltipsFocus: {},

        create(json, column, process, prepend = false) {

            if (Kanban.attendance.cardExist(json.id)) return;

            const attendance = Components.div({ class: "attendance", id: json.key_remote_id, customAttribute: ["user_id", json.user_key_remote_id, "card_id", json.id, "creation", json.timestamp] });
            const top = Components.div({ class: "top" });
            top.appendChild(Kanban.attendance.tag(json, "labels", 8, 2));

            const container = Components.div({ class: "_container" });
            const box_left = Components.div({ class: "box-left" });

            const profile = Components.img({ src: "https://files.talkall.com.br:3000/p/" + json.key_remote_id + ".jpeg" });
            profile.addEventListener("mouseover", Kanban.attendance.showProfileDescription);
            profile.addEventListener("mouseleave", Kanban.attendance.hideProfileDescription);

            const customer_profile_box = Components.div({ class: "box" });
            customer_profile_box.appendChild(profile);

            const customer_information = Components.div({ class: "info" });
            customer_information.appendChild(Components.span({ text: json.full_name == null ? json.key_remote_id.split("-")[0] : json.full_name == 0 ? json.key_remote_id.split("-")[0] : json.full_name }));
            customer_information.appendChild(Components.span({ text: json.key_remote_id.split("-")[0] }));

            box_left.appendChild(customer_profile_box);
            box_left.appendChild(customer_information);

            const box_right = Components.div({ class: "box-right" });

            const user_profile_box = Components.div({ class: "box" });
            const user_information = Components.div({ class: "info" });

            const user_name = Components.span({ text: Kanban.helpers.doTruncarStr(json.user_name, 22), id: "" });
            const group_name = Components.span({ text: json.departament });

            if (!json.user_name) {
                user_profile_box.appendChild(Components.img({ src: document.location.origin + "/assets/img/group.svg" }));
                group_name.className = "group-name";
            } else {
                user_profile_box.appendChild(Components.img({ src: "https://files.talkall.com.br:3000/p/" + json.user_key_remote_id + ".jpeg" }));
            }

            user_information.appendChild(user_name);
            user_information.appendChild(group_name);

            box_right.appendChild(user_information);
            box_right.appendChild(user_profile_box);

            container.appendChild(box_left);
            container.appendChild(box_right);

            const footer = Components.div({ class: "bottom" });
            const channel_name = Components.span({ class: "channel-name", text: json.name_channel });

            footer.appendChild(Kanban.attendance.icon(json, "1.1rem"));
            footer.appendChild(channel_name);

            attendance.appendChild(top);
            attendance.appendChild(container);
            attendance.appendChild(footer);

            if (!process)
                Kanban.attendance.appendFetchFound(column, attendance, prepend);
            else
                Kanban.attendance.appendScreenSizeLimit(column, attendance);

            customer_information.firstChild.innerText = Kanban.helpers.removeCharacters(customer_information.firstChild, customer_information, 1);

            attendance.addEventListener("click", Kanban.attendance.open);
        },
        tag(json, class_name, name_size, qtde_label) {
            const labels = json.labels_name?.split(",") || [];
            const colors = json.labels_color?.split(",") || [];
            const tags = Components.div({ class: "tags" });

            for (let i = 0; i < Math.min(labels.length, qtde_label); i++) {

                const tag_name = Components.span({ class: class_name, text: Kanban.helpers.doTruncarStr(labels[i], name_size) });
                tag_name.style.background = colors[i];
                tag_name.style.borderTopLeftRadius = i === 0 ? "6.5px" : "0";
                tag_name.style.borderBottomLeftRadius = i !== 0 ? "4px" : "0";
                tag_name.style.borderBottomRightRadius = "4px";
                tag_name.style.color = getTextColor(colors[i]);
                tags.appendChild(tag_name);
            };

            if (labels.length > qtde_label) {
                const qtde = labels.length - qtde_label;
                tags.appendChild(Components.span({ text: `+${qtde}`, class: "more-tags" }));
            }

            return tags;
        },
        icon(json, width) {
            const icon_paths = {
                2: "whatsapp",
                8: "facebook",
                9: "instagram",
                10: "telegram",
                12: "whatsapp",
                16: "whatsapp",
            };

            return Components.img({ src: `${document.location.origin}/assets/icons/kanban/${icon_paths[json.type]}.svg`, style: `width:${width}` });
        },
        count(type) {
            switch (type) {
                case "total":
                    document.getElementById("count__Waiting").innerHTML = collumnWaiting != undefined ? collumnWaiting.items.length : "0";
                    document.getElementById("count__InService").innerHTML = collumnInService != undefined ? collumnInService.items.length : "0";
                    document.getElementById("count__OnHold").innerHTML = collumnOnHold != undefined ? collumnOnHold.items.length : "0";
                    document.getElementById("count__Closed").innerHTML = collumnClosed != undefined ? collumnClosed.items.length : "0";
                    break;
                case "searchUser":
                    document.getElementById("count__Waiting").innerHTML = collumnWaitingUserSearch != undefined ? collumnWaitingUserSearch.items.length : "0";
                    document.getElementById("count__InService").innerHTML = collumnInServiceUserSearch != undefined ? collumnInServiceUserSearch.items.length : "0";
                    document.getElementById("count__OnHold").innerHTML = collumnOnHoldUserSearch != undefined ? collumnOnHoldUserSearch.items.length : "0";
                    document.getElementById("count__Closed").innerHTML = collumnClosedUserSearch != undefined ? collumnClosedUserSearch.items.length : "0";
                    break;
                case "searchGeneral":
                    document.getElementById("count__Waiting").innerHTML = collumnWaitingGenaralSearch != undefined ? collumnWaitingGenaralSearch.items.length : "0";
                    document.getElementById("count__InService").innerHTML = collumnInServiceGenaralSearch != undefined ? collumnInServiceGenaralSearch.items.length : "0";
                    document.getElementById("count__OnHold").innerHTML = collumnOnHoldGenaralSearch != undefined ? collumnOnHoldGenaralSearch.items.length : "0";
                    document.getElementById("count__Closed").innerHTML = collumnClosedGenaralSearch != undefined ? collumnClosedGenaralSearch.items.length : "0";
                    break;
                case "searchBattery":
                    document.getElementById("count__Waiting").innerHTML = collumnWaitingBatterySearch != undefined ? collumnWaitingBatterySearch.items.length : "0";
                    document.getElementById("count__InService").innerHTML = collumnInServiceBatterySearch != undefined ? collumnInServiceBatterySearch.items.length : "0";
                    document.getElementById("count__OnHold").innerHTML = collumnOnHoldBatterySearch != undefined ? collumnOnHoldBatterySearch.items.length : "0";
                    document.getElementById("count__Closed").innerHTML = collumnClosedBatterySearch != undefined ? collumnClosedBatterySearch.items.length : "0";
                    break;
                case "combineUserAndBattery":
                    document.getElementById("count__Waiting").innerHTML = Kanban.cache.get.combineUserAndBattery("Waiting").items.length;
                    document.getElementById("count__InService").innerHTML = Kanban.cache.get.combineUserAndBattery("InService").items.length;
                    document.getElementById("count__OnHold").innerHTML = Kanban.cache.get.combineUserAndBattery("OnHold").items.length;
                    document.getElementById("count__Closed").innerHTML = Kanban.cache.get.combineUserAndBattery("Closed").items.length;
                    break;
                case "combineUserAndGeneral":
                    document.getElementById("count__Waiting").innerHTML = Kanban.cache.get.combineUserAndGeneral("Waiting").items.length;
                    document.getElementById("count__InService").innerHTML = Kanban.cache.get.combineUserAndGeneral("InService").items.length;
                    document.getElementById("count__OnHold").innerHTML = Kanban.cache.get.combineUserAndGeneral("OnHold").items.length;
                    document.getElementById("count__Closed").innerHTML = Kanban.cache.get.combineUserAndGeneral("Closed").items.length;
                    break;
                default:
                    break;
            }
        },
        open() {
            const data = Kanban.attendance.get(this.getAttribute("card_id"))[0];
            const status = Kanban.attendance.status(this.parentElement.id);
            const collumn = this.parentElement.id;
            data.focus_collumn = collumn;

            document.getElementById("modal__status").innerHTML = status.text;
            document.getElementById("modal__icon-status").src = status.media_url;

            document.getElementById("modal__name-contact").innerHTML = Kanban.helpers.doTruncarStr(data.full_name == 0 ? data.key_remote_id.split("-")[0] : data.full_name, 20);
            document.getElementById("modal__number-contact").innerHTML = data.key_remote_id.split("-")[0];
            document.getElementById("modal__profile-contact").src = "https://files.talkall.com.br:3000/p/" + data.key_remote_id + ".jpeg";

            document.getElementById("modal__name-user").innerHTML = Kanban.helpers.doTruncarStr(data.user_name, 20);
            document.getElementById("modal__departament-user").innerHTML = data.departament;
            document.getElementById("modal__profile-user").src = Kanban.attendance.profileUser(data);

            document.getElementById("modal__name-channel").innerHTML = Kanban.helpers.doTruncarStr(data.name_channel, 35);
            document.getElementById("modal__name-channel").appendChild(Kanban.attendance.icon(data, "1.4rem"));

            document.querySelectorAll("#modal__label span").forEach((span) => span?.remove());
            document.getElementById("modal__label").append(Kanban.attendance.tag(data, "chat-labels", 30, 3));

            Kanban.chat.setColor();
            Kanban.chat.clearMsg();
            Kanban.chat.resetAttr();
            Kanban.chat.resetLock();
            Kanban.chat.load("100%");

            Kanban.chat.removeButtons(collumn);
            Kanban.chat.createButtons(collumn);

            Kanban.attendance.selected(data);
            Kanban.socket.queryUsersGroups();
            Kanban.socket.queryMessages();

            document.getElementById("modal__close").addEventListener("click", Kanban.attendance.deselect);
            document.getElementById("modal-chat").addEventListener("click", (e) => { if (e.target.id === "modal-chat") Kanban.attendance.deselect() });
            document.getElementById("modal__btn-transfer")?.addEventListener("click", Kanban.chat.showTransferFields);
            document.getElementById("modal__btn-concluded")?.addEventListener("click", Kanban.chat.closeService);
            $("#modal-chat").modal();
        },
        profileUser(data) {
            if (!data.user_name)
                return document.location.origin + "/assets/img/group.svg";
            else
                return "https://files.talkall.com.br:3000/p/" + data.user_key_remote_id + ".jpeg";
        },
        status(column) {
            const statusMap = {
                "column__Waiting": {
                    text: GLOBAL_LANG.kanban_attendance_chat_title_waiting,
                    media_url: "/assets/icons/kanban/arrow_waiting.svg"
                },
                "column__InService": {
                    text: GLOBAL_LANG.kanban_attendance_chat_title_in_progress,
                    media_url: "/assets/icons/kanban/chat.svg"
                },
                "column__OnHold": {
                    text: GLOBAL_LANG.kanban_attendance_chat_title_paused,
                    media_url: "/assets/icons/kanban/pause.svg"
                },
                "column__Closed": {
                    text: GLOBAL_LANG.kanban_attendance_chat_title_completed,
                    media_url: "/assets/icons/kanban/concluded.svg"
                }
            };

            return statusMap[column] || { text: "", media_url: "" };
        },
        appendScreenSizeLimit(column, attendance) {
            const window_height = window.innerHeight;
            const column_height = document.getElementById("column__" + column).clientHeight + 300;

            switch (column) {
                case "Waiting":
                    if (column_height >= window_height) {
                        if (this.addTwoMoreWaiting < 2)
                            document.getElementById("column__Waiting").appendChild(attendance);

                        this.addTwoMoreWaiting++;
                    } else
                        document.getElementById("column__Waiting").appendChild(attendance);
                    break;
                case "InService":
                    if (column_height >= window_height) {
                        if (this.addTwoMoreInService < 2)
                            document.getElementById("column__InService").appendChild(attendance);

                        this.addTwoMoreInService++;
                    } else
                        document.getElementById("column__InService").appendChild(attendance);
                    break;
                case "OnHold":
                    if (column_height >= window_height) {
                        if (this.addTwoMoreOnHold < 2)
                            document.getElementById("column__OnHold").appendChild(attendance);

                        this.addTwoMoreOnHold++;
                    } else
                        document.getElementById("column__OnHold").appendChild(attendance);
                    break;
                case "Closed":
                    if (column_height >= window_height) {
                        if (this.addTwoMoreClosed < 2)
                            document.getElementById("column__Closed").appendChild(attendance);

                        this.addTwoMoreClosed++;
                    } else
                        document.getElementById("column__Closed").appendChild(attendance);
                    break;
                default:
                    break;
            }
        },
        appendFetchFound(column, attendance, prepend) {
            const column_map = {
                Waiting: "column__Waiting",
                InService: "column__InService",
                OnHold: "column__OnHold",
                Closed: "column__Closed"
            };

            if (column_map[column]) {
                if (prepend) {
                    document.getElementById(column_map[column]).prepend(attendance);
                } else {
                    document.getElementById(column_map[column]).appendChild(attendance);
                }
            }
        },
        cardExist(card_id) {
            if (document.querySelector(`[card_id="${card_id}"]`)) {
                return true;
            }
        },
        userExist(user_id) {
            if (document.querySelector(`[id="${user_id}"]`)) {
                return true;
            }
        },
        showProfileDescription() {
            const card = this.closest(".attendance");
            const card_id = card.getAttribute("card_id");
            const data = Kanban.attendance.get(card_id)[0];

            const top = this.getBoundingClientRect().top + -130;
            const left = this.getBoundingClientRect().x;

            const tooltip = Components.div({ class: "tooltips-contact", style: `top:${top + "px"};left:${left + "px"}` });
            const container_desc = Components.div({ class: "container-desc" });

            const box = Components.div({ class: "box" });
            box.appendChild(Components.img({ src: this.src }));

            const main_left = Components.div({ class: "main-left" });
            main_left.appendChild(box);

            const main_right = Components.div({ class: "main-right" });
            const contact_name = Components.span({ class: "name", text: Kanban.helpers.doTruncarStr(data.full_name, 30), customAttribute: ["title", data.full_name, "type", "contact_name"] });
            const contact_email = Components.span({ class: "email", text: Kanban.helpers.doTruncarStr(data.email, 30), customAttribute: ["title", data.email, "type", "contact_email"] });
            const contact_number = Components.span({ class: "number", text: data.key_remote_id.split("-")[0], customAttribute: ["type", "contact_number"] });

            main_right.appendChild(contact_name);
            main_right.appendChild(contact_email);
            main_right.appendChild(contact_number);

            const labels = data.labels_name?.split(",");
            const color = data.labels_color?.split(",");

            for (let i = 0; i < labels?.length; i++) {
                const contact_label = Components.span({ class: "label", text: labels.length > 1 ? Kanban.helpers.doTruncarStr(labels[i], 8) : labels[i], style: `background:${color[i]}` });
                main_right.appendChild(contact_label);
            }

            const group_name = Components.div({ class: "group-info" });
            const group_number = Components.div({ class: "group-info" });
            const container_bottom = Components.div({ class: "container-bottom" });

            const name_icon = Components.i({ class: "fa fa-building icon-building" });
            const channel_name = Components.span({ class: "name", text: Kanban.helpers.doTruncarStr(data.name_channel, 35), customAttribute: ["type", "channel_name"] });

            const number_icon = Kanban.attendance.icon(data, "1.4rem");
            const channel_number = Components.span({ class: "number", text: data.key_remote_id.split("-")[1], customAttribute: ["type", "channel_number"] });

            container_bottom.appendChild(group_name);
            container_bottom.appendChild(group_number);

            group_name.appendChild(name_icon);
            group_name.appendChild(channel_name);

            group_number.appendChild(number_icon);
            group_number.appendChild(channel_number);

            container_desc.appendChild(main_left);
            container_desc.appendChild(main_right);

            tooltip.appendChild(container_desc);
            tooltip.appendChild(container_bottom);

            contact_name.addEventListener("click", Kanban.attendance.createPopCopy);
            channel_name.addEventListener("click", Kanban.attendance.createPopCopy);
            contact_email.addEventListener("click", Kanban.attendance.createPopCopy);
            contact_number.addEventListener("click", Kanban.attendance.createPopCopy);
            channel_number.addEventListener("click", Kanban.attendance.createPopCopy);

            tooltip.addEventListener("mouseover", () => clearTimeout(clearProfileInfoCard));
            tooltip.addEventListener("mouseleave", () => document.querySelector(".tooltips-contact")?.remove());

            Kanban.attendance.tooltipsFocus = data;
            profileInfoCard = setTimeout(() => document.querySelector("body").appendChild(tooltip), 300);
        },
        hideProfileDescription() {
            clearTimeout(profileInfoCard);
            clearProfileInfoCard = setTimeout(() => {
                document.querySelector(".tooltips-contact")?.remove();
            }, 50);
        },
        createPopCopy() {
            document.querySelector(".pop-copy")?.remove();

            const pop = Components.div({ class: "pop-copy" });
            const span = Components.span({ text: GLOBAL_LANG.kanban_attendance_message_pop_copy });
            const icon = Components.i({ class: "fas fa-check-circle", style: "margin-left: 0.2rem" });

            pop.appendChild(span);
            span.appendChild(icon);
            this.prepend(pop);

            Kanban.attendance.copyContactInfo(this);
        },
        copyContactInfo(elm) {
            switch (elm.getAttribute("type")) {
                case "contact_name":
                    navigator.clipboard.writeText(Kanban.attendance.tooltipsFocus.full_name);
                    break;
                case "contact_email":
                    navigator.clipboard.writeText(Kanban.attendance.tooltipsFocus.email);
                    break;
                case "contact_number":
                    navigator.clipboard.writeText(Kanban.attendance.tooltipsFocus.key_remote_id.split("-")[0]);
                    break;
                case "channel_name":
                    navigator.clipboard.writeText(Kanban.attendance.tooltipsFocus.name_channel);
                    break;
                case "channel_number":
                    navigator.clipboard.writeText(Kanban.attendance.tooltipsFocus.key_remote_id.split("-")[1]);
                    break;
                default:
                    break;
            }

            setTimeout(() => document.querySelector(".pop-copy")?.remove(), 1000);
        },
        get(card_id) {
            const collections = [collumnWaiting, collumnInService, collumnOnHold, collumnClosed];

            for (const collection of collections) {
                const item = collection.items.filter(elm => elm.id == card_id);
                if (item.length > 0) {
                    return item;
                }
            }

            return null;
        },
        move(card_id, column) {
            const attendance = document.querySelector(`[card_id="${card_id}"]`);
            document.getElementById(column).prepend(attendance);
        },
        setId(json) {
            json.items.forEach((elm) => {
                elm.id = Kanban.helpers.uuid(10000000);
            });

            return {
                Cmd: json.Cmd,
                event: json.event,
                items: json.items,
                listType: json.listType,
                status: json.status
            }
        },
        selected(obj) {
            selectedAttendance = obj;
        },
        deselect() {
            selectedAttendance = {};
        },
        remove(id, user) {
            const user_id = document.getElementById(id)?.getAttribute("user_id");
            if (user === user_id)
                document.getElementById(id)?.remove();

            if (!user)
                document.getElementById(id)?.remove();
        },
        removeAll() {
            const attendance = document.querySelectorAll(".column-list .attendance");
            attendance.forEach(elm => {
                elm.remove();
            });
        },
        resetLimitOfTwo() {
            this.addTwoMoreWaiting = 0;
            this.addTwoMoreInService = 0;
            this.addTwoMoreOnHold = 0;
            this.addTwoMoreClosed = 0;
        },
        removeTags(key_remote_id) {
            const id = CSS.escape(key_remote_id);
            const tags = id !== "modal__label" ? document.querySelectorAll(`#${id} .top .tags`) : document.querySelectorAll(`#${id} .tags`);

            tags.forEach(tag => tag.remove());
        },
        updateCard(json) {
            if (document.getElementById(json.key_remote_id)) {
                const id = CSS.escape(json.key_remote_id);
                this.removeTags(json.key_remote_id);

                const tops = document.querySelectorAll(`#${id} .top`);

                tops.forEach(top => {
                    top.appendChild(this.tag(json.label, "labels", 8, 2));
                });
            }
        },
        updateChatOpen(json) {
            if (selectedAttendance.key_remote_id === json.key_remote_id) {
                this.removeTags("modal__label");

                if (json.event == "Add")
                    document.getElementById("modal__label").append(Kanban.attendance.tag(json.label, "chat-labels", 30, 3));
            }
        }
    },

    chat: {
        reverse: null,
        lock_scroll_top: false,
        lock_scroll_bottom: true,
        limit_requests_top: true,
        limit_requests_bottom: null,

        hideTransferFields() {
            document.getElementById("modal__btn-concluded").style.display = "block";
            document.getElementById("modal__btn-cancel").style.display = "none";
            document.getElementById("modal__select-sector").parentNode.style.display = "none";
            document.getElementById("modal__select-attendance").parentNode.style.display = "none";
            document.getElementById("modal__btn-transfer").classList.remove("selected");
            document.querySelector("#modal-chat .modal-footer").style.height = "7rem";

            document.getElementById("modal__btn-transfer").addEventListener("click", Kanban.chat.showTransferFields);
            Kanban.chat.defaultSelectOption();
        },
        showTransferFields() {
            document.getElementById("modal__btn-concluded").style.display = "none";
            document.getElementById("modal__btn-cancel").style.display = "block";
            document.getElementById("modal__select-sector").parentNode.style.display = "flex";
            document.getElementById("modal__select-attendance").parentNode.style.display = "flex";
            document.getElementById("modal__btn-transfer").classList.add("selected");
            document.querySelector("#modal-chat .modal-footer").style.height = "10rem";

            document.querySelector("#modal__btn-transfer.selected").addEventListener("click", Kanban.chat.transferService);
            document.getElementById("modal__btn-transfer").removeEventListener("click", Kanban.chat.showTransferFields);
        },
        removeButtons(collumn) {
            if (collumn == "column__Closed") {
                document.getElementById("modal__btn-transfer")?.remove();
                document.getElementById("modal__btn-concluded")?.remove();
                document.getElementById("modal__btn-cancel").style.display = "none";
                document.getElementById("modal__select-sector").parentNode.style.display = "none";
                document.getElementById("modal__select-attendance").parentNode.style.display = "none";
                document.querySelector("#modal-chat .modal-footer").style.height = "4rem";
            }
        },
        createButtons(collumn) {
            if (collumn != "column__Closed" && !document.getElementById("modal__btn-transfer")) {
                const button_transfer = Components.button({ id: "modal__btn-transfer", text: GLOBAL_LANG.kanban_attendance_btn_transfer });

                const button_concluded = Components.button({ id: "modal__btn-concluded" });
                button_concluded.appendChild(Components.span({ text: GLOBAL_LANG.kanban_attendance_chat_btn_finish }));
                button_concluded.appendChild(Components.i({ class: "fas fa-check-circle" }));

                document.querySelector(".group-transfer").insertBefore(button_transfer, document.getElementById("modal__btn-cancel"));
                document.querySelector(".group-close-chat").appendChild(button_concluded);
                document.querySelector("#modal-chat .modal-footer").style.height = "7rem";
            }
        },
        *defaultTextOption() {
            yield GLOBAL_LANG.kanban_attendance_chat_placeholder_select;
            yield GLOBAL_LANG.kanban_attendance_chat_placeholder_any_attendant;
        },
        defaultSelectOption() {
            const select_sector = document.querySelector("#modal__select-sector");
            select_sector.selectedIndex = 0;

            const select_attendance = document.querySelector("#modal__select-attendance");
            select_attendance.selectedIndex = 0;
        },
        addGroupsToSelect(json) {
            Kanban.chat.defaultSelectOption();

            const option = Kanban.chat.defaultTextOption();
            const select_sector = document.querySelector("#modal__select-sector");
            const select_attendance = document.querySelector("#modal__select-attendance");

            while (select_sector.firstChild)
                select_sector.removeChild(select_sector.firstChild);

            select_sector.appendChild(Components.option({ value: "0", text: option.next().value }));
            select_attendance.appendChild(Components.option({ value: "0", text: option.next().value }));

            for (let i = 0; i < json.groups.length; i++) {
                select_sector.appendChild(Components.option({ value: json.groups[i].id_user_group, text: json.groups[i].name }));
            }
        },
        addUsersToSelect(json) {
            const select_attendance = document.querySelector("#modal__select-attendance");
            while (select_attendance.firstChild)
                select_attendance.removeChild(select_attendance.firstChild);

            const option = Kanban.chat.defaultTextOption();
            option.next();

            select_attendance.appendChild(Components.option({ value: "0", text: option.next().value }));
            select_attendance.selectedIndex = 0;

            for (let i = 0; i < json.users.length; i++) {
                select_attendance.appendChild(Components.option({ value: json.users[i].key_remote_id, text: json.users[i].last_name }));
            }
        },
        transferService() {
            const sector = document.querySelector("#modal__select-sector").value;
            const attendance = document.getElementById("modal__select-attendance").value;

            if (sector != 0) {
                Kanban.collumn.recompose(selectedAttendance.focus_collumn);
                Kanban.chat.hideTransferFields();

                const item = Kanban.cache.delete(selectedAttendance.key_remote_id);
                Kanban.cache.update(item, "Closed", "total");
                Kanban.attendance.remove(selectedAttendance.key_remote_id, selectedAttendance.user_key_remote_id);

                Kanban.socket.transfer(sector, attendance);
                Kanban.attendance.create(item, "Closed", false, true);

                const is_user_selected = filter.users.isUserSelected();
                const has_battery_search = filter.general.tooltips.option.isElementSelected();
                const has_general_search = document.getElementById("general-search").value.trim() != '' ? true : false;

                if (is_user_selected && has_battery_search) {
                    Kanban.attendance.count("combineUserAndBattery");
                }

                if (is_user_selected && has_general_search) {
                    Kanban.attendance.count("combineUserAndGeneral");
                    Kanban.cache.update(item, "Closed", "searchUser");
                    Kanban.cache.update(item, "Closed", "searchGeneral");
                }

                if (is_user_selected && !has_battery_search && !has_general_search) {
                    Kanban.cache.update(item, "Closed", "searchUser");
                    Kanban.attendance.count("searchUser");
                }

                if (has_battery_search && !is_user_selected && !has_general_search) {
                    Kanban.cache.update(item, "Closed", "searchBattery");
                    Kanban.attendance.count("searchBattery");
                }

                if (has_general_search && !is_user_selected && !has_battery_search) {
                    Kanban.cache.update(item, "Closed", "searchGeneral");
                    Kanban.attendance.count("searchGeneral");
                }

                if (!is_user_selected && !has_battery_search && !has_general_search) {
                    Kanban.attendance.count("total");
                }

                $("#modal-chat").modal("hide");
            }
        },
        closeService() {
            Kanban.alert.fire({
                "title": GLOBAL_LANG.kanban_attendance_chat_close_title,
                "message": GLOBAL_LANG.kanban_attendance_chat_close_message,
                "type": "action"
            }).then(res => {
                if (res == "yes") {
                    Kanban.collumn.recompose(selectedAttendance.focus_collumn);
                    Kanban.socket.close();
                    $("#modal-chat").modal("hide");
                }
                Kanban.alert.close();
            });
        },
        clearMsg() {
            document.querySelectorAll("#messages .item")
                .forEach(elm => {
                    elm?.remove();
                });
        },
        scrollTop() {
            if (this.scrollTop === 0) {
                if (Kanban.chat.lock_scroll_top) return;
                if (!Kanban.chat.limit_requests_top) {
                    const first_item = document.querySelectorAll(".messages .item")[0];
                    const last_id = first_item?.id;

                    Kanban.chat.load();
                    Kanban.chat.reverse = false;
                    Kanban.chat.creation = parseInt(first_item?.getAttribute("index"));
                    Kanban.message.last_id = last_id?.replace("date-", "");

                    Kanban.socket.queryMessages(Kanban.chat.creation, Kanban.chat.reverse);
                }
            } else {
                const messages = document.querySelector(".messages");
                const last_item = document.querySelector(".item:last-child");
                const last_item_bottom = last_item.offsetTop + last_item.clientHeight;
                const is_scrolled_to_bottom = last_item_bottom <= messages.clientHeight + messages.scrollTop;

                if (is_scrolled_to_bottom) {
                    if (Kanban.chat.lock_scroll_bottom) return;

                    if (!Kanban.chat.limit_requests_bottom) {
                        Kanban.chat.limit_requests_bottom = true;

                        Kanban.chat.load();
                        Kanban.chat.reverse = true;
                        Kanban.message.last_id = last_item?.id;
                        Kanban.chat.creation = parseInt(last_item?.getAttribute("index"));

                        Kanban.socket.queryMessages(Kanban.chat.creation, Kanban.chat.reverse);
                    }
                }
            }
        },
        load(position) {
            const box = Components.div({ class: "box-load", style: `height:${position};display:flex;justify-content:center;align-items:center` });
            const load = Components.img({ src: document.location.origin + "/assets/img/loads/loading_2.gif", id: "loadChat", style: "width:5rem" });

            box.appendChild(load);
            Kanban.chat.removeLoad();
            document.getElementById("messages").prepend(box);
        },
        removeLoad() {
            document.getElementById("loadChat")?.parentNode.remove();
        },
        resetAttr() {
            Kanban.chat.reverse = null;
            Kanban.chat.creation = 0;
            Kanban.chat.limit_requests_top = true;
        },
        setColor() {
            const color = document.querySelector("body").style.backgroundColor;
            const chat = document.querySelector(".modal-body .messages");

            chat.style.background = color;
        },
        checkLock(json) {
            if (json.items.length < 25) {
                if (Kanban.chat.reverse) this.lock_scroll_bottom = true;
                else this.lock_scroll_top = true;
            }
        },
        resetLock() {
            this.lock_scroll_top = false;
        },
        search() {
            const item_id = this.id.replace("quoted_", "");
            const item_focus = document.getElementById(item_id);

            if (item_focus) {
                item_focus.scrollIntoView({ behavior: "smooth", block: "start" });
                Kanban.message.init.dist.changeColor(item_focus);
            } else {
                Kanban.chat.clearMsg();
                Kanban.chat.reverse = true;
                Kanban.chat.lock_scroll_top = true;
                Kanban.chat.lock_scroll_bottom = false;
                Kanban.chat.creation = this.dataset.creation;

                Kanban.message.quoted_id = item_id;
                Kanban.socket.queryMessages(Kanban.chat.creation, Kanban.chat.reverse);
            }
        },
    },

    message: {
        search: null,
        last_id: null,
        quoted_id: null,
        process: {
            start(json) {
                Kanban.chat.checkLock(json);
                Kanban.chat.removeLoad();

                const data = json.items;

                if (Kanban.chat.reverse) {
                    data.reverse();
                }

                const object = this.organize(data);

                for (var i = object.length - 1; i >= 0; i--) {
                    this.create(object[i]);
                }

                Kanban.message.process.sortMsg();
                Kanban.message.init.dist.resetTail();
                Kanban.message.init.dist.addTail();

                if (Kanban.chat.limit_requests_top)
                    Kanban.message.init.dist.scrollHeight();
                else
                    Kanban.message.init.dist.scrollIntoView();

                Kanban.chat.limit_requests_bottom = false;
            },
            organize(object) {

                for (let i = 0; i < object.length; i++) {
                    if (object[i].media_type === 19) {

                        switch (object[i - 1]?.media_type) {
                            case 20:
                                if (object[i - 1].who == undefined)
                                    object[i - 1].who = object[i].data;

                                if (object[i - 1].forwhom == undefined)
                                    object[i - 1].forwhom = object[i - 2].data;
                                break;
                            case 21:
                            case 22:
                            case 23:
                            case 25:
                                object[i - 1].data = object[i].data;
                                break;
                            default:
                                break;
                        }
                    }

                    if (object[i].media_type === 24) {
                        const next_object = object[i + 1];
                        const current_object = object[i];

                        if (next_object)
                            next_object.protocol = current_object.data;
                    }
                }
                return object;
            },

            sortMsg() {
                $(".messages").find(".item").sort(function (a, b) {
                    return $(a).attr("index") - $(b).attr("index");
                }).appendTo(".messages");
            },
            msg(json) {
                if (selectedAttendance.key_remote_id == json.key_remote_id) {
                    if (!Kanban.chat.lock_scroll_bottom) return
                    Kanban.message.process.create(json);
                }
            },
            create(res) {
                if (document.getElementById(res.token)) return;
                switch (res.media_type) {
                    case 1:
                        Kanban.message.init.text(res);
                        break;
                    case 2:
                        Kanban.message.init.audio(res);
                        break;
                    case 3:
                        Kanban.message.init.image(res);
                        break;
                    case 4:
                        Kanban.message.init.document(res);
                        break;
                    case 5:
                        Kanban.message.init.video(res);
                        break;
                    case 7:
                        Kanban.message.init.location(res);
                        break;
                    case 9:
                        Kanban.message.init.contact(res);
                        break;
                    case 18:
                        Kanban.message.init.revoke(res);
                        break;
                    case 20:
                        Kanban.message.init.transfer(res);
                        break;
                    case 22:
                        Kanban.message.init.wait(res);
                        break;
                    case 21:
                        Kanban.message.init.start(res);
                        break;
                    case 23:
                        Kanban.message.init.closed(res);
                        break;
                    case 25:
                        Kanban.message.init.attendance(res);
                        break;
                    case 27:
                        Kanban.message.init.template(res);
                        break;
                    case 30:
                    case 35:
                        Kanban.message.init.interactive(res);
                        break;
                    case 32:
                        Kanban.message.init.storyMention(res);
                        break;
                    default:
                        break;
                }
            }
        },

        init: {
            user: null,
            last_color: null,
            last_user: null,
            last_date: null,
            tail_left: true,
            tail_right: true,
            msg: document.querySelector("#modal-chat .modal-body .messages"),
            color: randomColor({ luminosity: "dark", count: 257, hue: "random" })[0],
            dist: {
                item(res) {
                    this.lastDate(res);
                    const media_types = [19, 20, 21, 22, 23, 24, 25];
                    const item = Components.div({ class: "item", id: res.token, customAttribute: ["index", res.creation], style: "display: flex; flex-direction: column;" });

                    if (!media_types.includes(res.media_type))
                        item.style.alignItems = res.key_from_me == 1 ? "flex-start" : "flex-end";

                    return item;
                },
                top(res) {
                    const top = Components.div({ class: "top" });
                    const tail = Components.div({ class: "tail" });
                    const user = Components.div({ class: "user" });
                    const feed = Components.div({ class: "feed" });

                    top.appendChild(tail);
                    top.appendChild(user);

                    if (res.key_from_me == 2) {
                        user.appendChild(this.participant(res.from));
                    }

                    if (res?.quoted?.buttons == null) {
                        if (res.quoted_row_id != null) {
                            top.appendChild(feed);
                            feed.appendChild(this.quoted(res));
                        }
                    }
                    return top;
                },
                body() {
                    return Components.div({ class: "body" });
                },
                bottom() {
                    return Components.div({ class: "bottom" });
                },
                time(res) {
                    return Components.div({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });
                },
                ack(res) {
                    const ack = Components.span({ class: "ack" });
                    const status = parseInt(res.msgStatus);

                    const msgWait = () => "<svg xmlns='https://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#0' d='M9.75 7.713H8.244V5.359a.5.5 0 0 0-.5-.5H7.65a.5.5 0 0 0-.5.5v2.947a.5.5 0 0 0 .5.5h.094l.003-.001.003.002h2a.5.5 0 0 0 .5-.5v-.094a.5.5 0 0 0-.5-.5zm0-5.263h-3.5c-1.82 0-3.3 1.48-3.3 3.3v3.5c0 1.82 1.48 3.3 3.3 3.3h3.5c1.82 0 3.3-1.48 3.3-3.3v-3.5c0-1.82-1.48-3.3-3.3-3.3zm2 6.8a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2v-3.5a2 2 0 0 1 2-2h3.5a2 2 0 0 1 2 2v3.5z'></path></svg>";
                    const msgSend = () => "<svg xmlns='https://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#0' d='M10.91 3.316l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";
                    const msgReceived = () => "<svg xmlns='https://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#0' d='M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.879a.32.32 0 0 1-.484.033l-.358-.325a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";
                    const msgRead = () => "<svg xmlns='https://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#4FC3F7' d='M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.879a.32.32 0 0 1-.484.033l-.358-.325a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";

                    if (status === 0) ack.innerHTML = msgWait();
                    if (status === 1) ack.innerHTML = msgSend();
                    if (status === 2) ack.innerHTML = msgReceived();
                    if (status === 3) ack.innerHTML = msgRead();
                    if (status === 4) ack.innerHTML = msgRead();

                    return res.key_from_me == 1 ? Components.span({ class: "ack" }) : ack;
                },
                scrollHeight() {
                    const element = document.getElementById('messages');
                    element.scrollTop = element.scrollHeight;
                    Kanban.chat.limit_requests_top = false;
                },
                scrollIntoView() {
                    if (Kanban.message.quoted_id !== null) {

                        document.getElementById(Kanban.message.quoted_id)?.scrollIntoView({ behavior: "smooth", block: "start" });
                        Kanban.message.quoted_id ? Kanban.message.init.dist.changeColor(document.getElementById(Kanban.message.quoted_id)) : "";

                        document.getElementById("messages").scrollTop - 32;
                        Kanban.message.quoted_id = null;
                        Kanban.chat.lock_scroll_top = false;
                    } else {
                        document.getElementById(Kanban.message.last_id)?.scrollIntoView();

                        const element = document.getElementById("messages");
                        element.scrollTop = element.scrollTop - 32;
                    }
                },
                changeColor(elm) {
                    if (!elm) return;

                    const dinamic_class = elm.querySelector(".left-side") ? "left" : "right";
                    const div = elm.querySelector(`.${dinamic_class}-side`);
                    const tail = elm.querySelector(`.tail-${dinamic_class}`);
                    const detail = elm.querySelector(`.detail`);

                    setTimeout(() => div.classList.add(`focus`), 1000);
                    setTimeout(() => div.classList.remove(`focus`), 2000);

                    setTimeout(() => tail?.classList.add(`focus-svg`), 1000);
                    setTimeout(() => tail?.classList.remove(`focus-svg`), 2000);

                    setTimeout(() => detail?.classList.add(`focus-detail`), 1000);
                    setTimeout(() => detail?.classList.remove(`focus-detail`), 2000);
                },
                createTail(from_me) {
                    if (from_me === 1) {
                        return `<svg class="tail-left" width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 8C4.012 6.20882 5.71852 4.51139 8 0.5V11C4.4246 10.9422 2.6981 10.2329 0 8Z"/></svg>`;
                    } else {
                        return `<svg class="tail-right" width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 8C3.988 6.20882 2.28148 4.51139 0 0.5V11C3.5754 10.9422 5.3019 10.2329 8 8Z"/></svg>`;
                    }
                },
                addTail() {
                    this.removeTail();
                    document.querySelectorAll(".messages .item").forEach(elm => {
                        const is_right_side = elm.firstElementChild.classList.contains("right-side");
                        const is_left_side = elm.firstElementChild.classList.contains("left-side");

                        if (is_right_side && Kanban.message.init.tail_right) {
                            elm.style.marginTop = "1.8rem";
                            elm.firstElementChild.firstElementChild.firstElementChild.innerHTML = this.createTail(2);

                            Kanban.message.init.tail_right = false;
                            Kanban.message.init.tail_left = true;

                        } else if (is_left_side && Kanban.message.init.tail_left) {
                            elm.style.marginTop = "1.8rem";
                            elm.firstElementChild.firstElementChild.firstElementChild.innerHTML = this.createTail(1);

                            Kanban.message.init.tail_left = false;
                            Kanban.message.init.tail_right = true;
                        }
                    });
                },
                resetTail() {
                    Kanban.message.init.tail_left = true;
                    Kanban.message.init.tail_right = true;
                },
                removeTail() {
                    document.querySelectorAll(".messages .tail-left").forEach(elm => elm.remove());
                    document.querySelectorAll(".messages .tail-right").forEach(elm => elm.remove());
                    document.querySelectorAll(".messages .item").forEach(elm => elm.style.marginTop = "0rem");
                },
                verifyReaction(res) {
                    if (!document.getElementById(res.token)) return

                    const id = CSS.escape(res.token);
                    document.querySelector(`#${id} .reaction-box`)?.remove();

                    if (res.reaction)
                        this.addReaction({ elm: document.querySelector(`#${id}`), reaction: res.reaction });
                },
                addReaction(data) {
                    const reaction_box = Components.div({ class: "reaction-box" });
                    reaction_box.appendChild(Components.span({ text: data.reaction, class: "scale-effect" }));
                    data.elm.appendChild(reaction_box);
                },
                fromMe(from_me) {
                    return from_me === 1 ? `left-side` : `right-side`;
                },
                lastDate(res) {
                    const date = Kanban.helpers.formatShortDate(res.creation);

                    if (this.last_date != date) {
                        const formatted_date = date.replace("/", "_").replace("/", "_");
                        $("." + date.replace("/", "_").replace("/", "_")).remove();

                        const last_date = Components.div({ class: "lastDateMessage" });
                        const item = Components.div({ class: `item ${formatted_date}`, id: `date-${res.token}`, customAttribute: ["index", res.creation] });
                        const body = Components.div({ class: "body" });
                        const span = Components.span({ text: date });

                        body.appendChild(span);
                        last_date.appendChild(body);
                        item.appendChild(last_date);

                        this.last_date = date;
                        Kanban.message.init.msg.appendChild(item);
                    }
                },
                removeMsg(res) {
                    const item = document.getElementById(res.key_id);
                    const revoke_mssage = Components.div({ class: `revokeMessage left-side` });
                    const body = this.body(res);
                    const bottom = this.bottom(res);

                    const detail = Components.span({ class: "detail" });
                    const text = Components.span({ class: "text", text: GLOBAL_LANG.kanban_attendance_chat_message_deleted });
                    const box = Components.div({ class: "box", text: this.revokeIcon(1) });
                    const time = Components.span({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });

                    detail.appendChild(time);
                    body.appendChild(box);
                    body.appendChild(text);
                    body.appendChild(detail);

                    revoke_mssage.appendChild(body);
                    revoke_mssage.appendChild(bottom);

                    item.children[0].remove();
                    item.appendChild(revoke_mssage);
                },
                fileIcon(res) {
                    switch (res.media_mime_type) {
                        case "application/pdf":
                            return document.location.origin + "/assets/icons/pdf.svg"
                        case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                            return document.location.origin + "/assets/icons/excel.svg"
                        case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
                            return document.location.origin + "/assets/icons/texto.svg"
                        default:
                            return document.location.origin + "/assets/icons/new-document.svg"
                    }
                },
                errorPreview(event) {
                    event.target.parentNode.parentNode.parentNode.parentNode.classList.add("expire");
                    event.target.dataset.url = "";
                    event.target.remove();
                },
                revokeIcon(from_me) {
                    switch (parseInt(from_me)) {
                        case 1:
                            return `<svg xmlns='https://www.w3.org/2000/svg' viewBox='0 0 17 19'><path fill='currentColor' d='M12.629 12.463a5.17 5.17 0 0 0-7.208-7.209l7.208 7.209zm-1.23 1.229L4.191 6.484a5.17 5.17 0 0 0 7.208 7.208zM8.41 2.564a6.91 6.91 0 1 1 0 13.82 6.91 6.91 0 0 1 0-13.82z'></path></svg>`;
                        case 2:
                            return `<svg xmlns='https://www.w3.org/2000/svg' viewBox='0 0 17 19'><path fill='currentColor' d='M12.629 12.463a5.17 5.17 0 0 0-7.208-7.209l7.208 7.209zm-1.23 1.229L4.191 6.484a5.17 5.17 0 0 0 7.208 7.208zM8.41 2.564a6.91 6.91 0 1 1 0 13.82 6.91 6.91 0 0 1 0-13.82z'></path></svg>`;
                    }
                },
                participant(key_remote_id) {
                    return Components.span({ text: this.getPushName(key_remote_id), style: `color:${Kanban.message.init.color}` });
                },
                getPushName(key_remote_id) {
                    let i = 0;
                    let bFind = false;
                    for (i = 0; i < collumnUsers.length; i++) {
                        if (collumnUsers[i].key_remote_id == key_remote_id) {
                            bFind = true;
                            break;
                        }
                    }
                    return bFind == false ? null : collumnUsers[i].full_name;
                },
                quoted(res) {
                    const quoted = Components.div({ class: "quoted", id: "quoted_" + res.quoted.token, customAttribute: ["data-creation", res.quoted.creation], style: "cursor: pointer" });
                    quoted.addEventListener("click", Kanban.chat.search);

                    const text = () => {

                        const container = Components.div({ class: "container-text" });
                        const span = Components.span({ text: res.quoted.data });

                        container.appendChild(span);
                        quoted.appendChild(container);

                        return span;
                    }

                    const template = () => {
                        const container = Components.div({ class: "container-text" });
                        const span = Components.span({ text: res.quoted.data });

                        container.appendChild(span);
                        quoted.appendChild(container);

                        return span;
                    }

                    const audio = () => {
                        const container = Components.div({ class: "container-audio" });
                        const audio = Components.audio({ class: "audio", src: res.quoted.media_url, customAttribute: ["type", "audio/mp3", "controls", true] });

                        container.appendChild(audio);
                        quoted.appendChild(container);

                        return audio;
                    }

                    const image = () => {
                        const container = Components.div({ class: "container-img" });
                        const info = Components.div({ class: "info" });
                        const icon = Components.i({ class: "fas fa-camera" });
                        const text = Components.span({ class: "text", text: res.quoted.media_caption != null ? Kanban.helpers.doTruncarStr(res.quoted.media_caption, 48) : GLOBAL_LANG.kanban_attendance_chat_answer_photo });

                        const box = Components.div({ class: "box" });
                        const img = Components.img({ src: res.quoted.media_url });

                        info.appendChild(icon);
                        info.appendChild(text);
                        box.appendChild(img);

                        container.appendChild(info);
                        container.appendChild(box);

                        quoted.appendChild(container);
                        return container;
                    }

                    const video = () => {
                        const container = Components.div({ class: "container-video" });
                        const info = Components.div({ class: "info" });
                        const icon = Components.i({ class: "fas fa-video" });
                        const text = Components.span({ class: "text", text: res.quoted.data != null ? res.quoted.data : GLOBAL_LANG.kanban_attendance_chat_answer_video });
                        const box = Components.div({ class: "box" });

                        const video = Components.video({ src: res.quoted.media_url });

                        info.appendChild(icon);
                        info.appendChild(text);
                        box.appendChild(video);

                        container.appendChild(info);
                        container.appendChild(box);

                        quoted.appendChild(container);
                        return container;
                    }

                    const document = () => {
                        const container = Components.div({ class: "container-document" });
                        const icon = Components.img({ src: Kanban.message.init.dist.fileIcon(res.quoted) });
                        const text = Components.span({ class: "text", text: res.quoted.media_name != null ? res.quoted.media_name : GLOBAL_LANG.kanban_attendance_chat_answer_document });

                        container.appendChild(icon);
                        container.appendChild(text);

                        quoted.appendChild(container);
                        return container;

                    }

                    const contact = () => {
                        const container = Components.div({ class: "container-contact" });
                        const box = Components.div({ class: "box" });
                        const img = Components.img({ src: "/assets/img/avatar.png" });
                        const info = Components.div({ class: "info" });
                        const data = JSON.parse(res.quoted.data);
                        const first_name = Components.span({ class: "text", text: data.firstName });
                        const cell_phone = Components.span({ class: "cell", text: data.cellPhone });

                        box.appendChild(img);
                        info.appendChild(first_name);
                        info.appendChild(cell_phone);

                        container.appendChild(box);
                        container.appendChild(info);
                        quoted.appendChild(container);
                        return container;
                    }

                    const interactive = () => {
                        const container = Components.div({ class: "container-interactive" });
                        const data = JSON.parse(res.quoted.data);
                        const body = Components.span({ class: "interactive-body", text: data.interactive.body.text })

                        container.appendChild(body);

                        if (data.interactive.footer) {
                            const footer = Components.span({ class: "interactive-footer", text: data.interactive.footer.text })
                            container.appendChild(footer);
                        }

                        quoted.appendChild(container);

                        return container;
                    }
                    switch (res.quoted.media_type) {
                        case 1:
                            text();
                            break;
                        case 2:
                            audio();
                            break;
                        case 3:
                            image();
                            break;
                        case 4:
                            document();
                            break;
                        case 5:
                            video();
                            break;
                        case 7:
                            location();
                            break;
                        case 9:
                            contact();
                            break;
                        case 27:
                            template();
                            break;
                        case 30:
                        case 35:
                            interactive();
                            break;
                        default:
                            break;
                    }

                    return quoted;
                }
            },
            text(res) {
                const text_message = Components.div({ class: `textMessage ${this.dist.fromMe(res.key_from_me)}` });
                const item = this.dist.item(res);
                const top = this.dist.top(res);
                const body = this.dist.body(res);
                const ack = this.dist.ack(res);
                const bottom = this.dist.bottom(res);

                const detail = Components.span({ class: "detail" });
                let text;

                // Verifica se é um flow com botões (como no messenger)
                if (res?.quoted?.buttons != null) {
                    try {
                        const parsedButtons = JSON.parse(res.quoted.buttons);
                        const data = JSON.parse(res.data || "{}");

                        res.quoted = null;

                        const card = document.createElement("div");
                        Object.assign(card.style, {
                            display: "flex",
                            alignItems: "center",
                            backgroundColor: "#005c4d",
                            color: "#fff",
                            padding: "10px 12px",
                            borderRadius: "12px",
                            cursor: "pointer",
                            marginTop: "8px",
                            maxWidth: "320px",
                            fontFamily: "Arial, sans-serif"
                        });

                        const icon = document.createElement("div");
                        icon.innerHTML = "📄";
                        icon.style.fontSize = "22px";
                        icon.style.marginRight = "10px";

                        const texts = document.createElement("div");

                        const title = document.createElement("div");
                        title.textContent = parsedButtons[0].text || "Ação";
                        title.style.fontWeight = "bold";
                        title.style.fontSize = "14px";

                        const subtitle = document.createElement("div");
                        subtitle.textContent = GLOBAL_LANG.kanban_attendance_flow_message_subtitle;
                        subtitle.style.fontSize = "13px";
                        subtitle.style.color = "#d9fdd3";

                        texts.appendChild(title);
                        texts.appendChild(subtitle);

                        card.appendChild(icon);
                        card.appendChild(texts);

                        const flow = JSON.parse(res.data || "{}");

                        const evaluationNote = flow.screen_0_Escolha_um_0
                            ? flow.screen_0_Escolha_um_0.split("_")[1] // "★★★★★"
                            : "";

                        const comment = flow.screen_0_Deixe_um_comentrio_1 || "";

                        card.onclick = () => {
                            const flow = JSON.parse(res.data || "{}");

                            const evaluationNote = flow.screen_0_Escolha_um_0
                                ? flow.screen_0_Escolha_um_0.split("_")[1]
                                : "";

                            const comment = flow.screen_0_Deixe_um_comentrio_1 || "";

                            openFlowModalKanban({
                                title: parsedButtons[0].text || GLOBAL_LANG.kanban_template_flow_message_title_modal,
                                evaluationNote,
                                comment
                            });
                        };
                        body.appendChild(card);
                        text = null;

                    } catch (e) {
                        console.error("Erro ao processar JSON do flow no kanban:", e);
                        text = Components.span({ class: "text", text: res.data });
                    }
                }
                else if (res.quoted && res.quoted.media_type == 35) {
                    const data = JSON.parse(res.data);
                    text = Components.span({
                        class: "text",
                        text: `${GLOBAL_LANG.kanban_attendance_chat_interactive_flow_message_client} ${data.client ? GLOBAL_LANG.kanban_attendance_chat_interactive_flow_message_yes : GLOBAL_LANG.kanban_attendance_chat_interactive_flow_message_no}<br>CNPJ: ${data.cnpj ?? ""}`
                    });
                } else {
                    text = Components.span({ class: "text", text: res.data });
                }

                const time = Components.span({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });

                if (res.referral != null) {
                    const referral = this.referral(res);
                    body.style.maxWidth = "50rem";
                    body.appendChild(referral);
                }

                detail.appendChild(time);
                detail.appendChild(ack);

                if (text) body.appendChild(text);
                body.appendChild(detail);

                text_message.appendChild(top);
                text_message.appendChild(body);
                text_message.appendChild(bottom);

                item.appendChild(text_message);
                this.msg.appendChild(item);
                this.dist.verifyReaction(res);
            },
            template(res) {

                const template_message = Components.div({
                    class: `interactiveMessage ${this.dist.fromMe(res.key_from_me)}`,
                    style: "background-color:#00635d;border-radius:12px;padding:12px;color:#fff;font-family:sans-serif;max-width:340px;"
                });

                const item = this.dist.item(res);
                const top = this.dist.top(res);
                const body = this.dist.body(res);
                const ack = this.dist.ack(res);
                const bottom = this.dist.bottom(res);

                /* ================= COMPONENTS ================= */
                let components = [];
                try {
                    components = JSON.parse(res.components || '[]');
                } catch { }

                const applyTemplate = (text, parameters = []) => {
                    let i = 0;
                    return (text || '').replace(/{{\s*\d+\s*}}/g, () => {
                        return parameters[i++]?.text ?? '';
                    });
                };

                /* ================= HEADER ================= */
                let headerTemplate = null;
                const headerComponent = components.find(c => c.type === 'header');
                const param = headerComponent?.parameters?.[0];

                /* ================= HEADER ================= */

                // HEADER TEXTO
                if (res.header && (!param || param.type === 'text')) {

                    let headerText = res.header;

                    if (headerComponent?.parameters?.length) {
                        headerText = applyTemplate(
                            res.header,
                            headerComponent.parameters
                        );
                    }

                    headerTemplate = Components.div({
                        class: "template-header",
                        style: "font-size:16px;font-weight:600;margin-bottom:8px;"
                    });

                    headerTemplate.textContent = headerText;
                }

                // HEADER IMAGE
                if (param?.type === 'image' && param.image?.link) {

                    const img = Components.img({
                        src: param.image.link,
                        style: "width:100%;border-radius:8px;margin-bottom:8px;"
                    });

                    headerTemplate = Components.div({});
                    headerTemplate.appendChild(img);
                }

                // HEADER DOCUMENT
                if (param?.type === 'document' && param.document?.link) {

                    const docCard = Components.div({
                        style: "display:flex;align-items:center;justify-content:space-between;background:#00413d;border-radius:8px;padding:10px 12px;margin-bottom:8px;"
                    });

                    const left = Components.div({
                        style: "display:flex;align-items:center;gap:10px;color:#fff;font-weight:500;"
                    });

                    left.appendChild(
                        Components.div({ html: "📄", style: "font-size:22px;" })
                    );
                    left.appendChild(
                        Components.span({ text: param.document.filename || 'Documento' })
                    );

                    const download = Components.a({
                        href: param.document.link,
                        target: "_blank",
                        html: "⬇️",
                        style: "color:#fff;font-size:18px;text-decoration:none;"
                    });

                    docCard.appendChild(left);
                    docCard.appendChild(download);

                    headerTemplate = Components.div({});
                    headerTemplate.appendChild(docCard);
                }

                /* ================= BODY ================= */
                let bodyText = res.data || '';
                const bodyComponent = components.find(c => c.type === 'body');

                if (bodyComponent?.parameters?.length) {
                    bodyText = applyTemplate(bodyText, bodyComponent.parameters);
                }

                const text_body = Components.div({
                    class: "text-body-template",
                    style: "font-size:14px;margin-bottom:8px;"
                });

                text_body.innerHTML = bodyText.replace(/\n/g, "<br><br>");

                const detail = Components.span({
                    style: "display:flex;justify-content:flex-end;align-items:center;font-size:10px;"
                });

                detail.appendChild(
                    Components.span({
                        text: Kanban.helpers.formatShortTime(res.creation),
                        style: "margin-right:4px;"
                    })
                );
                detail.appendChild(ack);

                body.appendChild(text_body);

                /* ================= FOOTER ================= */
                const footerComponent = components.find(c => c.type === 'footer');

                if (res.footer) {

                    let footerText = res.footer;

                    if (footerComponent?.parameters?.length) {
                        footerText = applyTemplate(
                            res.footer,
                            footerComponent.parameters
                        );
                    }

                    const footerTemplate = Components.div({
                        class: "template-footer",
                        style: "font-size:12px;color:#cfd8dc;margin-top:6px;opacity:0.85;"
                    });

                    footerTemplate.textContent = footerText;
                    body.appendChild(footerTemplate);
                }

                body.appendChild(detail);

                /* ================= BUTTONS ================= */
                let buttons = null;

                if (res.buttons) {
                    try {
                        const parsed = JSON.parse(res.buttons);
                        if (Array.isArray(parsed) && parsed.length) {
                            buttons = parsed;
                        }
                    } catch { }
                }

                if (buttons) {

                    const buttonContainer = Components.div({
                        class: "button-container",
                        style: `margin-top:10px;
                                padding:8px 0;
                                display:flex;
                                flex-direction:column;
                                gap:6px;
                                align-items:center;`
                    });

                    buttons.forEach(btn => {

                        if (!btn?.text) return;

                        const button = Components.span({
                            class: "option",
                            text: btn.text,
                            style: `color:#00a5ff;
                                    font-weight:600;
                                    font-size:14px;
                                    display:inline-flex;
                                    align-items:center;
                                    cursor:pointer;
                                    font-family:'Open Sans', sans-serif;`
                        });

                        button.prepend(
                            Components.i({
                                class: "fa fa-share",
                                style: "transform:scaleX(-1); margin-right:6px; color:#00635d;"
                            })
                        );

                        buttonContainer.appendChild(button);
                    });

                    bottom.appendChild(buttonContainer);
                }

                template_message.appendChild(top);
                if (headerTemplate) template_message.appendChild(headerTemplate);
                template_message.appendChild(body);
                template_message.appendChild(bottom);

                item.appendChild(template_message);
                this.msg.appendChild(item);
                this.dist.verifyReaction(res);
            },
            referral(res) {
                const thumbnail_default = document.location.origin + "/assets/img/referral.png";

                const referral = Components.div({ class: "referral" });
                const container = Components.div({ class: "container" });
                const container_text = Components.div({ class: "container-text" });
                const box = Components.div({ class: "box" });

                if (res.referral.media_type_ads === "video") {
                    const video = Components.video({ src: res.referral.video_url ?? thumbnail_default });
                    video.addEventListener("click", () => window.open(res.referral.source_url, '_blank'));

                    box.append(video);
                } else {
                    const link = Components.a({ target: "blank", href: res.referral.source_url });
                    const img = Components.img({ src: res.referral.thumbnail_url ?? thumbnail_default });

                    link.append(img);
                    box.append(link);
                }

                const title = Components.span({ class: "title", text: Kanban.helpers.doTruncarStr(res.referral.headline, 33) });
                const text = Components.span({ class: "text", text: Kanban.helpers.doTruncarStr(res.referral.body, 100) });

                container_text.appendChild(title);
                container_text.appendChild(text);

                container.append(box);
                container.appendChild(container_text);

                referral.append(container);

                return referral;
            },
            async audio(res) {
                const audio_message = Components.div({ class: `audioMessage ${this.dist.fromMe(res.key_from_me)}` });
                const item = this.dist.item(res);
                const top = this.dist.top(res);
                const body = this.dist.body(res);
                const ack = this.dist.ack(res);
                const bottom = this.dist.bottom(res);

                const detail = Components.span({ class: "detail" });
                const audio = Components.audio({ class: "audio", src: res.media_url, customAttribute: ["type", "audio/ogg", "controls", true, "preload", "auto"] });
                const time = Components.span({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });

                const audio_transcribe = document.createElement("img");
                audio_transcribe.src = document.location.origin + "/assets/icons/messenger/stars_ai.svg";
                audio_transcribe.title = GLOBAL_LANG.kanban_attendance_chat_audio_icon_title;
                audio_transcribe.className = "audioTranscribeIcon";
                audio_transcribe.addEventListener("click", (event) => this.transcribeAudio(res, event));

                detail.appendChild(time);
                detail.appendChild(ack);

                body.appendChild(audio);
                body.appendChild(detail);

                audio_message.appendChild(top);
                audio_message.appendChild(body);
                audio_message.appendChild(bottom);
                audio_message.appendChild(audio_transcribe);

                this.audioToBlob(res.token, res.media_url, res.mime_type)

                item.appendChild(audio_message);
                this.msg.appendChild(item);
                this.dist.verifyReaction(res);
            },
            image(res) {
                const image_message = Components.div({ class: `imageMessage ${this.dist.fromMe(res.key_from_me)}` });
                const item = this.dist.item(res);
                const top = this.dist.top(res);
                const body = this.dist.body(res);
                const ack = this.dist.ack(res);
                const bottom = this.dist.bottom(res);

                const box = Components.div({ class: "box" });
                const link = Components.a({ href: res.media_url, customAttribute: ["target", "_blank"] });
                const image = Components.img({ class: "image", src: res.media_url });
                const text = Components.span({ class: "text", text: res.media_caption });
                const detail = Components.span({ class: `detail ${res.media_caption != null ? "subtitle" : ""}` });
                const time = Components.span({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });

                box.appendChild(image);
                link.appendChild(box);
                detail.appendChild(time);
                detail.appendChild(ack);

                body.appendChild(link);
                body.appendChild(text);
                body.appendChild(detail);

                image_message.appendChild(top);
                image_message.appendChild(body);
                image_message.appendChild(bottom);

                item.appendChild(image_message);
                this.msg.appendChild(item);
                this.dist.verifyReaction(res);
            },
            location(res) {
                const location_message = Components.div({ class: `locationMessage ${this.dist.fromMe(res.key_from_me)}` });
                const item = this.dist.item(res);
                const top = this.dist.top(res);
                const body = this.dist.body(res);
                const ack = this.dist.ack(res);
                const bottom = this.dist.bottom(res);

                const box = Components.div({ class: "box" });
                const data = JSON.parse(res.data);
                const link = Components.a({ href: `http://maps.google.com/maps?q=${data.latitude},${data.longitude}`, customAttribute: ["target", "_blank"] });
                const image = Components.img({ class: "image", src: "/assets/img/localizacao.jpg" });
                const detail = Components.span({ class: "detail" });
                const time = Components.span({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });

                box.appendChild(image);
                link.appendChild(box);
                detail.appendChild(time);
                detail.appendChild(ack);

                body.appendChild(link);
                body.appendChild(detail);

                location_message.appendChild(top);
                location_message.appendChild(body);
                location_message.appendChild(bottom);

                item.appendChild(location_message);
                this.msg.appendChild(item);
                this.dist.verifyReaction(res);
            },
            document(res) {
                const document_message = Components.div({ class: `documentMessage ${this.dist.fromMe(res.key_from_me)}` });
                const item = this.dist.item(res);
                const top = this.dist.top(res);
                const body = this.dist.body(res);
                const ack = this.dist.ack(res);
                const bottom = this.dist.bottom(res);

                const link = Components.a({ href: res.media_url, customAttribute: ["target", "_blank"] })
                const container = Components.div({ class: "_container" });
                const title = Components.span({ class: "title", text: res.media_name != null ? res.media_name : GLOBAL_LANG.kanban_attendance_chat_answer_document });
                const doc_icon = Components.img({ src: Kanban.message.init.dist.fileIcon(res) });
                const detail = Components.span({ class: "detail" });
                const text = Components.span({ class: "text", text: res.media_caption == res.media_name ? "" : res.media_caption });
                const time = Components.span({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });

                container.appendChild(doc_icon);
                container.appendChild(title);
                link.appendChild(container);

                detail.appendChild(time);
                detail.appendChild(ack);

                body.appendChild(link);
                body.appendChild(text);
                body.appendChild(detail);

                document_message.appendChild(top);
                document_message.appendChild(body);
                document_message.appendChild(bottom);

                item.appendChild(document_message);
                this.msg.appendChild(item);
                this.dist.verifyReaction(res);
            },
            video(res) {
                const video_message = Components.div({ class: `videoMessage ${this.dist.fromMe(res.key_from_me)}` });
                const item = this.dist.item(res);
                const top = this.dist.top(res);
                const body = this.dist.body(res);
                const ack = this.dist.ack(res);
                const bottom = this.dist.bottom(res);

                const box = Components.div({ class: "box" });
                const link = Components.a({ href: res.media_url, customAttribute: ["target", "_blank"] });
                const video = Components.video({ class: "video", src: res.media_url, customAttribute: ["controls", true] });
                const text = Components.span({ class: "text", text: res.media_caption });
                const detail = Components.span({ class: `detail ${res.media_caption != null ? "subtitle" : ""}` });
                const time = Components.span({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });

                box.appendChild(video);
                link.appendChild(box);
                detail.appendChild(time);
                detail.appendChild(ack);

                body.appendChild(link);
                body.appendChild(text);
                body.appendChild(detail);

                video_message.appendChild(top);
                video_message.appendChild(body);
                video_message.appendChild(bottom);

                item.appendChild(video_message);
                this.msg.appendChild(item);
                this.dist.verifyReaction(res);
            },
            contact(res) {
                const contact_message = Components.div({ class: `contactMessage ${this.dist.fromMe(res.key_from_me)}` });
                const item = this.dist.item(res);
                const top = this.dist.top(res);
                const body = this.dist.body(res);
                const ack = this.dist.ack(res);
                const bottom = this.dist.bottom(res);
                const data = JSON.parse(res.data);

                const box = Components.div({ class: "box" });
                const img = Components.img({ src: "/assets/img/avatar.png" });
                const info = Components.div({ class: "info" });
                const group = Components.div({ class: "group" });
                const first_name = Components.span({ class: "text", text: data.firstName });
                const cell_phone = Components.span({ class: "cell", text: data.cellPhone });

                if (data.cellPhone == `${GLOBAL_LANG.kanban_attendance_contact_card_without_number}`) {
                    cell_phone.style.fontStyle = "italic";
                }

                const detail = Components.span({ class: "detail" });
                const time = Components.span({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });

                box.appendChild(img);
                group.appendChild(first_name);
                group.appendChild(cell_phone);

                info.appendChild(group);
                info.appendChild(detail);

                detail.appendChild(time);
                detail.appendChild(ack);

                body.appendChild(box);
                body.appendChild(info);

                contact_message.appendChild(top);
                contact_message.appendChild(body);
                contact_message.appendChild(bottom);

                item.appendChild(contact_message);
                this.msg.appendChild(item);
                this.dist.verifyReaction(res);
            },
            interactive(res) {
                const interactive_message = Components.div({ class: `interactiveMessage ${this.dist.fromMe(res.key_from_me)}` });
                const item = this.dist.item(res);
                const top = this.dist.top(res);
                const body = this.dist.body(res);
                const ack = this.dist.ack(res);
                const bottom = this.dist.bottom(res);
                const data = JSON.parse(res.data);

                let text_header = null;
                let text_footer = null;

                if (data.interactive.header)
                    text_header = Components.div({ class: "text-header-interactive", text: data.interactive.header.text });

                const text_body = Components.div({ class: "text-body-interactive", text: data.interactive.body.text });

                if (data.interactive.footer)
                    text_footer = Components.div({ class: "text-footer-interactive", text: data.interactive.footer.text });

                const detail = Components.span({ class: "detail" });
                const time = Components.span({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });

                detail.appendChild(time);
                detail.appendChild(ack);

                if (data.interactive.header)
                    body.appendChild(text_header);

                body.appendChild(text_body);

                if (data.interactive.footer)
                    body.appendChild(text_footer);

                body.appendChild(detail);

                let icon = "";
                let option = "";

                switch (data.interactive.type) {
                    case "flow":
                        option = Components.span({ class: "option", text: data.interactive.action.parameters.flow_cta });
                        break;
                    case "list":
                        icon = Components.i({ class: "fas fa-list-ul" });
                        option = Components.span({ class: "option", text: data.interactive.action.button })
                        break;
                    case "location_request_message":
                        icon = Components.i({ class: "fa fa-map-marker-alt" });
                        option = Components.span({ class: "option", text: GLOBAL_LANG.kanban_attendance_chat_interactive_message_location }); // Aqui coloca a variável de linguagem, pois a msg é definida
                        break;
                    case "cta_url":
                        icon = Components.i({ class: "fa fa-external-link-alt" });
                        option = Components.span({ class: "option", text: data.interactive.action.parameters.display_text });
                        break;
                    case "button":
                        let buttons = data.interactive.action.buttons;
                        icon = Components.i({ class: "fa fa-share", style: "transform: scaleX(-1)" });
                        let buttonContainer = Components.div({ class: "button-container" });
                        buttons.forEach(button => {
                            let buttonElement = Components.span({ class: "option", style: "margin-right: 5px", text: button.reply.title });
                            buttonElement.prepend(icon.cloneNode(true));
                            buttonContainer.appendChild(buttonElement);
                        });
                        option = buttonContainer;
                        break;
                }

                if (data.interactive.type != "flow" && data.interactive.type != "button")
                    bottom.appendChild(icon);

                bottom.appendChild(option);
                bottom.addEventListener("click", () => Kanban.message.openInteractiveList(data));

                interactive_message.appendChild(top);
                interactive_message.appendChild(body);
                interactive_message.appendChild(bottom);

                item.appendChild(interactive_message);
                this.msg.appendChild(item);
                this.dist.verifyReaction(res);
            },
            revoke(res) {
                const revoke_mssage = Components.div({ class: `revokeMessage ${this.dist.fromMe(res.key_from_me)}` });
                const item = this.dist.item(res);
                const top = this.dist.top(res);
                const body = this.dist.body(res);
                const ack = this.dist.ack(res);
                const bottom = this.dist.bottom(res);

                const detail = Components.span({ class: "detail" });
                const text = Components.span({ class: "text", text: GLOBAL_LANG.kanban_attendance_chat_message_deleted });
                const box = Components.div({ class: "box", text: this.dist.revokeIcon(res.key_from_me) });
                const time = Components.span({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });

                detail.appendChild(time);
                detail.appendChild(ack);

                body.appendChild(box);
                body.appendChild(text);
                body.appendChild(detail);

                revoke_mssage.appendChild(top);
                revoke_mssage.appendChild(body);
                revoke_mssage.appendChild(bottom);

                item.appendChild(revoke_mssage);
                this.msg.appendChild(item);
            },
            start(res) {
                if (res.data == null) return;
                this.color = localStorage.getItem("attendanceKanbanTheme") === "dark" ? "#d6c50e" : randomColor({ luminosity: "dark", count: 257, hue: "random" })[0];

                const start_message = Components.div({ class: `startMessage` });
                const item = this.dist.item(res);
                const body = this.dist.body(res);

                const span = Components.span({ text: `${res.data} ${GLOBAL_LANG.kanban_attendance_chat_started_service} ${Kanban.helpers.formatShortTime(res.creation)} <span class="protocol">${res.protocol}</span>` });
                body.appendChild(span);

                start_message.appendChild(body);

                item.appendChild(start_message);
                this.msg.appendChild(item);
            },
            transfer(res) {
                if (res.who == null) return;
                if (res.forwhom == null) return;

                const start_message = Components.div({ class: `transferMessage` });
                const item = this.dist.item(res);
                const body = this.dist.body(res);

                const span = Components.span({ text: `${res.who} ${GLOBAL_LANG.kanban_attendance_chat_transferred_service} ${res.forwhom} ${GLOBAL_LANG.kanban_attendance_chat_transferred_service_at} ${Kanban.helpers.formatShortTime(res.creation)}` });
                body.appendChild(span);

                start_message.appendChild(body);

                item.appendChild(start_message);
                this.msg.appendChild(item);
            },
            attendance(res) {
                if (res.data == null) return;

                const start_message = Components.div({ class: `attendanceMessage` });
                const item = this.dist.item(res);
                const body = this.dist.body(res);

                const span = Components.span({ text: `${res.data} ${GLOBAL_LANG.kanban_attendance_chat_put_into_service} ${Kanban.helpers.formatShortTime(res.creation)}` });
                body.appendChild(span);

                start_message.appendChild(body);

                item.appendChild(start_message);
                this.msg.appendChild(item);
            },
            closed(res) {
                if (res.data == null) return;

                const closed_message = Components.div({ class: "closedMessage" });
                const item = this.dist.item(res);
                const body = this.dist.body(res);

                const span = Components.span({ text: `${res.data} ${GLOBAL_LANG.kanban_attendance_chat_ended_service} ${Kanban.helpers.formatShortTime(res.creation)}` });
                body.appendChild(span);

                closed_message.appendChild(body);

                item.appendChild(closed_message);
                this.msg.appendChild(item);
            },
            wait(res) {
                if (res.data == null) return;

                const wait_message = Components.div({ class: `waitMessage` });
                const item = this.dist.item(res);
                const body = this.dist.body(res);

                const span = Components.span({ text: `${res.data} ${GLOBAL_LANG.kanban_attendance_chat_on_hold_service} ${Kanban.helpers.formatShortTime(res.creation)}` });
                body.appendChild(span);

                wait_message.appendChild(body);

                item.appendChild(wait_message);
                this.msg.appendChild(item);
            },
            storyMention(res) {
                const story_mention_message = Components.div({ class: `storyMentionMessage ${this.dist.fromMe(res.key_from_me)}` });
                const item = this.dist.item(res);
                const top = this.dist.top(res);
                const body = this.dist.body(res);
                const ack = this.dist.ack(res);
                const bottom = this.dist.bottom(res);
                const is_reply_story = res.data ? true : false;

                const title = Components.span({ class: "title", text: is_reply_story ? GLOBAL_LANG.kanban_attendance_chat_story_reply : GLOBAL_LANG.kanban_attendance_chat_story_mention });
                const box = Components.div({ class: "box" });
                const text = Components.span({ class: "text", text: is_reply_story ? res.data : "" });
                const detail = Components.span({ class: `detail ${res.data != null ? "subtitle" : ""}` });
                const time = Components.span({ class: "time", text: Kanban.helpers.formatShortTime(res.creation) });
                const link = Components.a({ href: res.media_url, customAttribute: ["target", "_blank"] });

                if (res.media_mime_type == "image/jpeg")
                    var preview = Components.img({ class: "image", src: res.media_url });
                else
                    var preview = Components.video({ class: "image", src: res.media_url });

                preview.addEventListener("error", (event) => this.dist.errorPreview(event));

                box.appendChild(preview);
                link.appendChild(box);
                detail.appendChild(time);
                detail.appendChild(ack);

                body.appendChild(title);
                body.appendChild(link);
                body.appendChild(text);
                body.appendChild(detail);

                story_mention_message.appendChild(top);
                story_mention_message.appendChild(body);
                story_mention_message.appendChild(bottom);

                item.appendChild(story_mention_message);
                this.msg.appendChild(item);
            },
            transcribeAudio(res, event) {
                return new Promise(async (resolve, reject) => {
                    const audio_text = document.createElement("span");
                    audio_text.className = 'transcribe-text text-white'
                    audio_text.innerHTML = GLOBAL_LANG.kanban_attendance_chat_audio_text;

                    const message = event.target.parentElement;
                    message.lastChild.remove();
                    message.style.paddingBottom = "5px";
                    message.appendChild(audio_text);

                    const text = await this.requestOpenAI(res.media_url);

                    if (text === "error") {
                        const url = document.createElement("a");
                        url.href = document.location.origin + "/integration/add/openai";
                        url.target = "_blank";
                        url.textContent = "/integration/add/openai";

                        audio_text.innerHTML = GLOBAL_LANG.kanban_attendance_chat_transcribe_audio_error_apy_key;
                        audio_text.appendChild(url);
                    } else {
                        audio_text.innerHTML = text;
                    }
                    resolve()
                });

            },
            requestOpenAI(media_url) {
                return new Promise(async (resolve, reject) => {

                    const key = localStorage.getItem("userToken");
                    const errorMsg = GLOBAL_LANG.kanban_attendance_chat_transcribe_audio_error_response;

                    try {
                        const myHeaders = new Headers();
                        myHeaders.append("Content-Type", "application/json");

                        const requestOptions = {
                            method: "POST",
                            headers: myHeaders,
                            body: JSON.stringify({ "audio_url": media_url }),
                            redirect: 'follow'
                        };

                        const response = await fetch("https://app.talkall.com.br:4090/openai/transcription?key=" + key, requestOptions);
                        const data = await response.json();

                        if (data.reason === "api_key not found")
                            resolve("error");

                        resolve(data.new || errorMsg);

                    } catch (error) {
                        return errorMsg;
                    }
                });
            },
            audioToBlob(media_id, media_url, mime_type) {

                var xhr = new XMLHttpRequest();
                xhr.open('GET', media_url, true);
                xhr.responseType = 'arraybuffer';

                xhr.onload = function (e) {

                    if (this.status === 200) {

                        var arrayBuffer = this.response;
                        var blob = new Blob([arrayBuffer], { type: mime_type });
                        var blobUrl = URL.createObjectURL(blob);

                        const standardize = Kanban.message.init.scapeId(media_id);

                        const audios = document.querySelectorAll(`#${standardize} audio`);
                        const sources = document.querySelectorAll(`#${standardize} source`);

                        let index = audios.length > 1 ? 1 : 0;

                        audios[index].src = blobUrl;
                        sources[index].src = blobUrl;
                    }
                };

                xhr.send();
            },
            scapeId(id) {
                if (/^\d/.test(id)) {
                    return `\\3${id[0]} ${id.slice(1)}`;
                }
                return `${id}`;
            }
        },

        openInteractiveList(data) {
            if (data.interactive.type != "list")
                return;

            Kanban.message.clearOptInteractiveList();

            const button = data.interactive.action.button;
            const sections = data.interactive.action.sections;

            document.querySelector("#modal-list-interactive .modal-title").textContent = button;

            const section_body = document.querySelector("#modal-list-interactive .modal-body");

            sections.forEach(section => {

                const section_container = Components.div({ class: "section-list-container" });

                const section_title = Components.div({ class: "section-list-title", text: section.title });
                section_container.appendChild(section_title);

                section.rows.forEach(row => {

                    const row_container = Components.div({ class: "row-list-container" });
                    const option_container = Components.div({ class: "option-list-container" });
                    const title = Components.div({ class: "title-list", text: row.title });
                    const description = Components.div({ class: "description-list", text: row.description });

                    option_container.appendChild(title);
                    option_container.appendChild(description);

                    const radio_container = Components.div({ class: "radio-list-container" });
                    const radio = Components.input({ class: "radio-list", type: "radio" });

                    radio_container.appendChild(radio);

                    row_container.appendChild(option_container)
                    row_container.appendChild(radio_container)

                    row_container.addEventListener("click", Kanban.message.clickInteractiveList);

                    section_container.appendChild(row_container)
                });

                section_body.appendChild(section_container);
            })

            $("#modal-list-interactive").modal();
        },
        clickInteractiveList() {
            Kanban.message.deselectOptInteractiveList();
            this.querySelector('.radio-list').checked = true;
        },
        clearOptInteractiveList() {
            const section_body = document.querySelector("#modal-list-interactive .modal-body");
            while (section_body.firstChild)
                section_body.removeChild(section_body.firstChild);
        },
        deselectOptInteractiveList() {
            const options = document.querySelectorAll(".radio-list-container .radio-list");
            options.forEach(option => option.checked = false)
        },
    },

    socket: {
        queryUsersGroups() {
            const object = {
                Cmd: "queryUsersGroups"
            };

            socket.send(JSON.stringify(object));
        },
        queryUsers() {
            const object = {
                Cmd: "queryUsers",
                key_id: document.querySelector("#modal__select-sector").value
            };

            socket.send(JSON.stringify(object));
        },
        queryMessages() {
            const object = {
                Cmd: "queryMessages",
                key_remote_id: selectedAttendance.key_remote_id,
                user_key_remote_id: selectedAttendance.user_key_remote_id,
                kanban: true,
                creation: Kanban.chat.creation,
                reverse: Kanban.chat.reverse
            };

            socket.send(JSON.stringify(object));
        },
        transfer(sector, attendance) {
            const object = {
                Cmd: "action",
                chat: {
                    default: false,
                    from: selectedAttendance.key_remote_id,
                    group: sector,
                    kanban: true,
                    to: attendance,
                    type: "move",
                    user_key_remote_id: selectedAttendance.user_key_remote_id,
                    transfer_user: userToken.replace("kanban-", "")
                }
            };

            socket.send(JSON.stringify(object));
        },
        close() {
            const object = {
                Cmd: "action",
                chat: {
                    key_remote_id: selectedAttendance.key_remote_id,
                    user_key_remote_id: selectedAttendance.user_key_remote_id,
                    status: "close",
                    userInAttendance: localStorage.getItem("userToken"),
                    kanban: true
                }
            };

            socket.send(JSON.stringify(object));
        }
    },

    alert: {
        fire(options) {
            return new Promise((resolve, reject) => {

                try {

                    const modal_alert = Kanban.components.alerts.modalAlert();

                    const box_title = Kanban.components.alerts.boxTitle();
                    box_title.firstChild.innerHTML = options.title;

                    const box_description = Kanban.components.alerts.boxDescription();
                    box_description.firstChild.innerHTML = options.message;

                    const box_botton = Kanban.components.alerts.boxBotton();

                    switch (options.type) {
                        case "info-default":

                            var btn_to_close = Kanban.components.alerts.btnClose();
                            btn_to_close.addEventListener("click", () => resolve("close"), { once: true });
                            box_botton.appendChild(btn_to_close);

                            break;
                        case "info-blue":

                            var btn_to_close = Kanban.components.alerts.btnClose("#007AFF");
                            btn_to_close.addEventListener("click", () => resolve("close"), { once: true });
                            box_botton.appendChild(btn_to_close);

                            break;
                        case "error":

                            var btn_to_close = Kanban.components.alerts.btnClose("#FF6852");
                            btn_to_close.addEventListener("click", () => resolve("close"), { once: true });
                            box_botton.appendChild(btn_to_close);

                            break;
                        case "danger":

                            var btn_no = Kanban.components.alerts.btnNo("#FF6852");
                            var btn_yes = Kanban.components.alerts.btnYes();

                            btn_no.addEventListener("click", () => resolve("no"), { once: true });
                            btn_yes.addEventListener("click", () => resolve("yes"), { once: true });

                            box_botton.appendChild(btn_no);
                            box_botton.appendChild(btn_yes);

                            break;
                        case "action":

                            var btn_no = Kanban.components.alerts.btnNo();
                            var btn_yes = Kanban.components.alerts.btnYes("#007AFF");

                            btn_no.addEventListener("click", () => resolve("no"), { once: true });
                            btn_yes.addEventListener("click", () => resolve("yes"), { once: true });

                            box_botton.appendChild(btn_no);
                            box_botton.appendChild(btn_yes);

                            break;
                        default:
                            break;
                    }

                    modal_alert.appendChild(box_title);
                    modal_alert.appendChild(box_description);
                    modal_alert.appendChild(box_botton);

                    document.querySelector("body").appendChild(modal_alert);
                    document.querySelector("body").appendChild(Kanban.components.alerts.bgBox());

                } catch (err) {
                    console.log(err);
                    reject("erro");
                }
            })
        },
        close() {
            document.querySelector(".modal-alert").remove();
            document.querySelector(".alert-bg-box").remove();
        }
    },

    process: {
        cards(json) {
            const { items, listType } = json;

            for (const item of items) {

                if (listType != "Closed") {
                    document.querySelector("#column_InService #" + CSS.escape(item.key_remote_id))?.remove();
                    document.querySelector("#column_OnHold #" + CSS.escape(item.key_remote_id))?.remove();
                    document.querySelector("#column_Waiting #" + CSS.escape(item.key_remote_id))?.remove();
                }

                Kanban.attendance.create(item, listType, true);
            }
        },
        addImages(itens, type) {
            const imageExists = (url) => {
                return new Promise(resolve => {
                    const img = new Image();
                    img.addEventListener('load', () => resolve(true));
                    img.addEventListener('error', () => resolve(false));
                    img.src = url;
                });
            };

            itens.forEach(async element => {
                if (type === "card") {
                    imageExists("https://files.talkall.com.br:3000/p/" + element.user_key_remote_id + ".jpeg").then(async (exists) => {
                        if (exists) {
                            const response = await fetch("https://files.talkall.com.br:3000/p/" + element.user_key_remote_id + ".jpeg");
                            document.querySelector(`[card_id="${element.id}"]`).children[1].lastElementChild.lastElementChild.firstElementChild.src = element.user_key_remote_id == "" || "https://files.talkall.com.br:3000/p/" + element.user_key_remote_id + ".jpeg" == null ? document.location.origin + "/assets/icons/kanban/img-preview.svg" : response.url;
                        } else
                            document.querySelector(`[card_id="${element.id}"]`).children[1].lastElementChild.lastElementChild.firstElementChild.src = "/assets/icons/kanban/img-preview.svg";
                    });
                } else {
                    imageExists(element.profile).then(async (exists) => {
                        if (exists) {
                            if (element.profile != null && element.profile != "") {
                                const response = await fetch(element.profile);
                                document.getElementById(element.key_remote_id).firstChild.firstChild.src = element.profile == "" || element.profile == null ? document.location.origin + "/assets/icons/kanban/img-preview.svg" : response.url;
                            } else {
                                document.getElementById(element.key_remote_id).firstChild.firstChild.src = "/assets/icons/kanban/img-preview.svg";
                            }
                        } else
                            document.getElementById(element.key_remote_id).firstChild.firstChild.src = "/assets/icons/kanban/img-preview.svg";
                    });
                };
            });
        }
    },

    cache: {
        add(json) {
            switch (json.listType) {
                case "Waiting":
                    collumnWaiting = json;
                    break;
                case "InService":
                    collumnInService = json;
                    break;
                case "OnHold":
                    collumnOnHold = json;
                    break;
                case "Closed":
                    collumnClosed = json;
                    break;
                default:
                    break;
            }
        },
        update(item, column, type) {
            const total_update_map = {
                "Waiting": Kanban.cache.push.total.waiting,
                "InService": Kanban.cache.push.total.inService,
                "OnHold": Kanban.cache.push.total.onHold,
                "Closed": Kanban.cache.push.total.closed
            };

            const user_update_map = {
                "Waiting": Kanban.cache.push.user.waiting,
                "InService": Kanban.cache.push.user.inService,
                "OnHold": Kanban.cache.push.user.onHold,
                "Closed": Kanban.cache.push.user.closed
            };

            const battery_update_map = {
                "Waiting": Kanban.cache.push.battery.waiting,
                "InService": Kanban.cache.push.battery.inService,
                "OnHold": Kanban.cache.push.battery.onHold,
                "Closed": Kanban.cache.push.battery.closed
            };

            const general_update_map = {
                "Waiting": Kanban.cache.push.general.waiting,
                "InService": Kanban.cache.push.general.inService,
                "OnHold": Kanban.cache.push.general.onHold,
                "Closed": Kanban.cache.push.general.closed
            };

            if (type === "total" && total_update_map[column])
                total_update_map[column](item);

            if (type === "searchUser" && user_update_map[column])
                user_update_map[column](item);

            if (type === "searchBattery" && battery_update_map[column])
                battery_update_map[column](item);

            if (type === "searchGeneral" && general_update_map[column])
                general_update_map[column](item);
        },
        updateTags(json) {
            const columns = [collumnClosed, collumnInService, collumnOnHold];

            for (let column of columns) {
                const item = column.items.find(item => item.key_remote_id === json.key_remote_id);
                if (column && item) {
                    item.labels_color = json.label.labels_color;
                    item.labels_name = json.label.labels_name;
                }
            }
        },
        delete(key_remote_id) {
            let item = null;

            if (collumnWaiting && collumnWaiting.items !== undefined) {
                const index_waiting = collumnWaiting.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_waiting !== -1) item = collumnWaiting.items.splice(index_waiting, 1)[0];
            }


            if (collumnInService && collumnInService.items !== undefined) {
                const index_in_service = collumnInService.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_in_service !== -1) item = collumnInService.items.splice(index_in_service, 1)[0];
            }


            if (collumnOnHold && collumnOnHold.items !== undefined) {
                const index_on_hold = collumnOnHold.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_on_hold !== -1) item = collumnOnHold.items.splice(index_on_hold, 1)[0];
            }


            if (collumnWaitingUserSearch && collumnWaitingUserSearch.items !== undefined) {
                const index_waiting_user = collumnWaitingUserSearch.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_waiting_user !== -1) item = collumnWaitingUserSearch.items.splice(index_waiting_user, 1)[0];
            }


            if (collumnInServiceUserSearch && collumnInServiceUserSearch.items !== undefined) {
                const index_in_service_user = collumnInServiceUserSearch.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_in_service_user !== -1) item = collumnInServiceUserSearch.items.splice(index_in_service_user, 1)[0];
            }


            if (collumnOnHoldUserSearch && collumnOnHoldUserSearch.items !== undefined) {
                const index_on_hold_user = collumnOnHoldUserSearch.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_on_hold_user !== -1) item = collumnOnHoldUserSearch.items.splice(index_on_hold_user, 1)[0];
            }


            if (collumnWaitingBatterySearch && collumnWaitingBatterySearch.items !== undefined) {
                const index_waiting_user = collumnWaitingBatterySearch.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_waiting_user !== -1) item = collumnWaitingBatterySearch.items.splice(index_waiting_user, 1)[0];
            }


            if (collumnInServiceBatterySearch && collumnInServiceBatterySearch.items !== undefined) {
                const index_in_service_user = collumnInServiceBatterySearch.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_in_service_user !== -1) item = collumnInServiceBatterySearch.items.splice(index_in_service_user, 1)[0];
            }


            if (collumnOnHoldBatterySearch && collumnOnHoldBatterySearch.items !== undefined) {
                const index_on_hold_user = collumnOnHoldBatterySearch.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_on_hold_user !== -1) item = collumnOnHoldBatterySearch.items.splice(index_on_hold_user, 1)[0];
            }


            if (collumnWaitingGenaralSearch && collumnWaitingGenaralSearch.items !== undefined) {
                const index_waiting_user = collumnWaitingGenaralSearch.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_waiting_user !== -1) item = collumnWaitingGenaralSearch.items.splice(index_waiting_user, 1)[0];
            }


            if (collumnInServiceGenaralSearch && collumnInServiceGenaralSearch.items !== undefined) {
                const index_in_service_user = collumnInServiceGenaralSearch.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_in_service_user !== -1) item = collumnInServiceGenaralSearch.items.splice(index_in_service_user, 1)[0];
            }


            if (collumnOnHoldGenaralSearch && collumnOnHoldGenaralSearch.items !== undefined) {
                const index_on_hold_user = collumnOnHoldGenaralSearch.items.findIndex(item => item.key_remote_id === key_remote_id);
                if (index_on_hold_user !== -1) item = collumnOnHoldGenaralSearch.items.splice(index_on_hold_user, 1)[0];
            }

            return item;
        },
        search(collumn, creation) {
            const cards_items = collumn.items.sort((x, y) => {
                return y.timestamp - x.timestamp;
            });

            const cards = cards_items.filter((items) => {
                return items.timestamp < creation;
            });

            return {
                listType: collumn.listType,
                items: cards,
                event: "process",
                status: 200
            };
        },
        inActive(collumn) {
            const general_search = document.getElementById("general-search").value;
            const is_filter_battery_selected = filter.general.tooltips.option.isElementSelected();
            const is_user_selected = filter.users.isUserSelected();

            if (is_filter_battery_selected && is_user_selected)
                return Kanban.cache.get.combineUserAndBattery(collumn);

            if (general_search && is_user_selected)
                return Kanban.cache.get.combineUserAndGeneral(collumn);

            if (is_user_selected)
                return Kanban.cache.get.searchUser(collumn);

            if (is_filter_battery_selected)
                return Kanban.cache.get.searchBattery(collumn);

            if (general_search !== "")
                return Kanban.cache.get.searchGeneral(collumn);

            if (general_search === "")
                return Kanban.cache.get.total(collumn);
        },
        get: {
            total(collumn) {
                switch (collumn) {
                    case "Waiting":
                        return collumnWaiting;
                    case "InService":
                        return collumnInService;
                    case "OnHold":
                        return collumnOnHold;
                    case "Closed":
                        return collumnClosed;
                    default:
                        return null;
                }
            },
            searchUser(collumn) {
                switch (collumn) {
                    case "Waiting":
                        return collumnWaitingUserSearch;
                    case "InService":
                        return collumnInServiceUserSearch;
                    case "OnHold":
                        return collumnOnHoldUserSearch;
                    case "Closed":
                        return collumnClosedUserSearch;
                    default:
                        return null;
                }
            },
            searchGeneral(collumn) {
                switch (collumn) {
                    case "Waiting":
                        return collumnWaitingGenaralSearch;
                    case "InService":
                        return collumnInServiceGenaralSearch;
                    case "OnHold":
                        return collumnOnHoldGenaralSearch;
                    case "Closed":
                        return collumnClosedGenaralSearch;
                    default:
                        return null;
                }
            },
            searchBattery(collumn) {
                switch (collumn) {
                    case "Waiting":
                        return collumnWaitingBatterySearch;
                    case "InService":
                        return collumnInServiceBatterySearch;
                    case "OnHold":
                        return collumnOnHoldBatterySearch;
                    case "Closed":
                        return collumnClosedBatterySearch;
                    default:
                        return null;
                }
            },
            combineUserAndBattery(collumn) {
                const search_map = {
                    "Waiting": { user_search: collumnWaitingUserSearch, battery_search: collumnWaitingBatterySearch },
                    "InService": { user_search: collumnInServiceUserSearch, battery_search: collumnInServiceBatterySearch },
                    "OnHold": { user_search: collumnOnHoldUserSearch, battery_search: collumnOnHoldBatterySearch },
                    "Closed": { user_search: collumnClosedUserSearch, battery_search: collumnClosedBatterySearch }
                };

                if (search_map[collumn]) {
                    const { user_search, battery_search } = search_map[collumn];
                    const items = user_search.items.filter(item_user =>
                        battery_search.items.some(item_battery =>
                            item_battery.id_chat_list === item_user.id_chat_list &&
                            item_battery.key_remote_id === item_user.key_remote_id
                        )
                    );
                    return { event: "process", items: items, list_type: {}, status: 200 };
                }

                return null;
            },
            combineUserAndGeneral(collumn) {
                const search_map = {
                    "Waiting": { user_search: collumnWaitingUserSearch, general_search: collumnWaitingGenaralSearch },
                    "InService": { user_search: collumnInServiceUserSearch, general_search: collumnInServiceGenaralSearch },
                    "OnHold": { user_search: collumnOnHoldUserSearch, general_search: collumnOnHoldGenaralSearch },
                    "Closed": { user_search: collumnClosedUserSearch, general_search: collumnClosedGenaralSearch }
                };
                const search_value = document.getElementById("general-search").value.trim();
                if (search_map[collumn]) {
                    const { user_search, general_search } = search_map[collumn];
                    const items = user_search.items.filter(item_user =>
                        general_search.items.some(item_general =>
                            (item_general.departament && item_user.departament.includes(search_value)) ||
                            (item_general.key_remote_id && item_user.key_remote_id.includes(search_value)) ||
                            (item_general.full_name && item_user.full_name.includes(search_value)) ||
                            (item_general.name_channel && item_user.name_channel.includes(search_value)) ||
                            (item_general.user_name && item_user.user_name.includes(search_value))
                        )
                    );
                    return { event: "process", items: items, list_type: {}, status: 200 };
                }

                return null;
            },
        },
        push: {
            total: {
                waiting(item) {
                    Kanban.helpers.userServiceQuantity();
                    collumnWaiting.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnWaiting.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                inService(item) {
                    Kanban.helpers.userServiceQuantity();
                    collumnInService.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnInService.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                onHold(item) {
                    Kanban.helpers.userServiceQuantity();
                    collumnOnHold.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnOnHold.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                closed(item) {
                    Kanban.helpers.userServiceQuantity();
                    collumnClosed.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnClosed.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                }
            },
            user: {
                waiting(item) {
                    collumnWaitingUserSearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnWaitingUserSearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                inService(item) {
                    collumnInServiceUserSearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnInServiceUserSearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                onHold(item) {
                    collumnOnHoldUserSearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnOnHoldUserSearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                closed(item) {
                    collumnClosedUserSearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnClosedUserSearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                }
            },
            battery: {
                waiting(item) {
                    collumnWaitingBatterySearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnWaitingBatterySearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                inService(item) {
                    collumnInServiceBatterySearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnInServiceBatterySearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                onHold(item) {
                    collumnOnHoldBatterySearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnOnHoldBatterySearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                closed(item) {
                    collumnClosedBatterySearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnClosedBatterySearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                }
            },
            general: {
                waiting(item) {
                    collumnWaitingGenaralSearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnWaitingGenaralSearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                inService(item) {
                    collumnInServiceGenaralSearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnInServiceGenaralSearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                onHold(item) {
                    collumnOnHoldGenaralSearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnOnHoldGenaralSearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                },
                closed(item) {
                    collumnClosedGenaralSearch.items.push({
                        departament: item.departament,
                        full_name: item.full_name,
                        id_chat_list: item.id_chat_list,
                        is_group: item.is_group,
                        key_remote_id: item.key_remote_id,
                        labels_color: item.labels_color,
                        labels_name: item.labels_name,
                        timestamp: item.timestamp,
                        type: item.type,
                        id: item.id,
                        user_key_remote_id: item.user_key_remote_id,
                        user_name: item.user_name,
                        name_channel: item.name_channel,
                    });

                    collumnClosedGenaralSearch.items.sort(function (x, y) {
                        return y.timestamp - x.timestamp;
                    });
                }
            }
        }
    },

    collumn: {
        limitCard: 0,
        eventScroll() {
            const obj = {
                qtde: 5,
                collumn: this.id.replace("column__", ""),
                creation: parseInt(this.lastElementChild?.attributes.creation.nodeValue)
            };

            if (this.offsetHeight + this.scrollTop >= this.scrollHeight)
                Kanban.collumn.getNewCard(obj);
        },
        getNewCard(obj) {
            Kanban.collumn.limitCard = 0;
            const { collumn, creation, qtde } = obj;
            const active_items = Kanban.cache.inActive(collumn);
            const search_results = Kanban.cache.search(active_items, creation).items;

            for (const item of search_results) {
                if (Kanban.collumn.limitCard <= qtde) {
                    Kanban.attendance.create(item, collumn, false);
                    Kanban.collumn.limitCard++;
                }
            }
        },
        recompose(collumn) {
            const creation = parseInt(document.querySelectorAll(`#${collumn} .attendance:last-child`)[0]?.getAttribute("creation"));

            const obj = {
                qtde: 1,
                creation,
                collumn: collumn.replace("column__", "")
            };

            Kanban.collumn.getNewCard(obj);
        },
        removeCard() {
            $(`#${selectedAttendance.focus_collumn} #${selectedAttendance.key_remote_id}`)?.remove();
        }
    },

    components: {
        loading(padding_size) {
            const loading = document.createElement("img");
            loading.src = document.location.origin + "/assets/img/loads/loading_2.gif";
            loading.className = "loading-img";
            loading.style.padding = padding_size;

            return loading;
        },
        alerts: {
            modalAlert() {
                const alert = document.createElement("div");
                alert.className = "modal-alert";

                return alert;
            },
            boxTitle() {
                const title_box = document.createElement("div");
                title_box.className = "title-box";

                const title_lang = document.createElement("span");
                title_box.appendChild(title_lang);

                return title_box;
            },
            boxDescription() {
                const description_box = document.createElement("div");
                description_box.className = "description-box";

                const description_lang = document.createElement("span");
                description_box.appendChild(description_lang);

                return description_box;
            },
            boxBotton() {
                const button_box = document.createElement("div");
                button_box.className = "buttom-box";

                return button_box;
            },
            btnNo(color = false) {
                const btn_no = document.createElement("button");
                btn_no.className = "btn-no";
                btn_no.innerHTML = GLOBAL_LANG.kanban_attendance_btn_no;

                if (color) {
                    btn_no.style.color = "#FFF";
                    btn_no.style.backgroundColor = color;
                }

                return btn_no;
            },
            btnYes(color = false) {
                const btn_yes = document.createElement("button");
                btn_yes.className = "btn-yes";
                btn_yes.innerHTML = GLOBAL_LANG.kanban_attendance_btn_yes

                if (color) {
                    btn_yes.style.color = "#FFF";
                    btn_yes.style.backgroundColor = color;
                }

                return btn_yes;
            },
            btnClose(color = false) {
                const btn_to_close = document.createElement("button");
                btn_to_close.className = "btn-to-close";
                btn_to_close.innerHTML = GLOBAL_LANG.kanban_attendance_btn_close;

                if (color) {
                    btn_to_close.style.color = "#FFF";
                    btn_to_close.style.backgroundColor = color;
                }

                return btn_to_close;
            },
            bgBox() {
                const bg_box = document.createElement("div");
                bg_box.className = "alert-bg-box";

                return bg_box;
            }
        },
    },

    helpers: {
        millisecondsPerDay: 1000 * 60 * 60 * 24,
        uuid(number) {
            return Math.floor(Math.random() * number);
        },
        isTextAtEnd(element, parent_div, rows) {
            const container = element;

            if (container.scrollHeight - container.scrollTop === container.clientHeight) {
                console.log("O texto chegou ao final da div.");
            } else {
                console.log("O texto ainda não chegou ao final da div.");
            }

            let el = document.getElementById('theDiv');
            lines = el.innerHTML.replace(/ |^\s+|\s+$/g, '').split('\n'), lineCount = lines.length;
        },
        removeCharacters(element, parent_div, rows) {

            let temp_span = document.createElement("span");
            temp_span.innerText = element.innerText;
            temp_span.className = element.className;
            temp_span.style.position = "fixed";
            temp_span.style.whiteSpace = "nowrap";

            element.parentElement.prepend(temp_span);

            let text_width = temp_span.offsetWidth;
            let parent_width = parent_div.clientWidth;

            let proportion = text_width / parent_width;
            let text = element.textContent;

            proportion = proportion + 0.0090

            if (proportion > rows) {
                text = text.slice(0, text.length / proportion - 3);
                text = text.trim();
                text += "...";
            }

            temp_span.remove();
            return text;
        },
        doTruncarStr(str, size) {
            if (!str
                || !size
                || typeof str !== "string"
                || typeof size !== "number") {
                return str;
            }

            return str.length >= size + 3 ? str.substring(0, size) + "..." : str;
        },
        phoneMask(value) {
            const res = value.split("");
            const code = `+${res[0]}${res[1]}`;
            let phone = value.substring(2);

            phone = phone.replace(/\D/g, '');
            phone = phone.replace(/(\d{2})(\d)/, "($1) $2");
            phone = phone.replace(/(\d)(\d{4})$/, "$1-$2");

            return `${code} ${phone}`;
        },
        userServiceQuantity() {
            setTimeout(() => {
                const columns = [collumnInService];
                const userItems = document.querySelectorAll(".list-users .user-item");
                const userAttendanceContacts = document.querySelectorAll(".list-users .user-item .box .ball");
                userAttendanceContacts.forEach(userAttendance => {
                    userAttendance.innerHTML = "0";
                })

                columns.forEach(column => {
                    column.items.forEach(elm => {
                        const user = [...userItems].find(user => user.id === elm.user_key_remote_id);
                        if (user) {
                            const countElement = user.firstChild.lastChild;
                            countElement.textContent = parseInt(countElement.textContent) + 1;
                        }
                    });
                });
            }, 3000);
        },
        removeLoad() {
            document.querySelector(".loading-load").style.display = "none";
        },
        removeAccents(str) {
            const map = {
                '-': ' ',
                '-': '_',
                'a': 'á|à|ã|â|À|Á|Ã|Â',
                'e': 'é|è|ê|É|È|Ê',
                'i': 'í|ì|î|Í|Ì|Î',
                'o': 'ó|ò|ô|õ|Ó|Ò|Ô|Õ',
                'u': 'ú|ù|û|ü|Ú|Ù|Û|Ü',
                'c': 'ç|Ç',
                'n': 'ñ|Ñ'
            };

            for (let pattern in map) str = str.replace(new RegExp(map[pattern], 'g'), pattern);

            return str;
        },
        currentDateTime(date_time) {
            const date = new Date(),
                day = date.getDate().toString(),
                dayF = (day.length == 1) ? '0' + day : day,
                month = (date.getMonth() + 1).toString(),
                monthF = (month.length == 1) ? '0' + month : month,
                year = date.getFullYear(),
                hour = date.getHours().toString(),
                hourF = (hour.length == 1) ? '0' + hour : hour,
                minutes = date.getMinutes().toString(),
                minutesF = (minutes.length == 1) ? '0' + minutes : minutes;

            if (date_time == "date")
                return `${dayF}/${monthF}/${year}`;
            else if (date_time == "time")
                return `${hourF}:${minutesF}`;
            else
                return `${dayF}/${monthF}/${year} ${hourF}:${minutesF}`;
        },
        formatShortDate(timestamp) {
            let date = new Date(timestamp * 1000);
            let today = new Date();
            let currentDate = today.getFullYear() + "/" + today.getMonth() + "/" + today.getDate();
            let dt = date.getFullYear() + "/" + date.getMonth() + "/" + date.getDate();

            if (currentDate == dt) {
                return GLOBAL_LANG.kanban_attendance_chat_today;
            } else {
                let diff = Kanban.helpers.dateDiffInDays(date, today);
                if (diff < 3) {
                    switch (diff) {
                        case 1:
                            return GLOBAL_LANG.kanban_attendance_chat_yesterday;
                        case 2:
                            return GLOBAL_LANG.kanban_attendance_chat_before_yesterday;
                    }
                } else {
                    if (diff < 7) {
                        let semana = [
                            GLOBAL_LANG.kanban_attendance_chat_sunday,
                            GLOBAL_LANG.kanban_attendance_chat_monday,
                            GLOBAL_LANG.kanban_attendance_chat_tuesday,
                            GLOBAL_LANG.kanban_attendance_chat_wednesday,
                            GLOBAL_LANG.kanban_attendance_chat_thursday,
                            GLOBAL_LANG.kanban_attendance_chat_friday,
                            GLOBAL_LANG.kanban_attendance_chat_saturday
                        ];
                        return semana[date.getDay()];
                    } else {
                        let day = date.getDate();
                        let month = date.getMonth() + 1;

                        day = day <= 9 ? "0" + day : day;
                        month = month <= 9 ? "0" + month : month;

                        return day + "/" + month + "/" + date.getFullYear();
                    }
                }
            }
        },
        dateDiffInDays(a, b) {
            const utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
            const utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());
            return Math.floor((utc2 - utc1) / this.millisecondsPerDay);
        },
        formatShortTime(timestamp) {
            let date = new Date(timestamp * 1000);
            let hours = date.getHours();
            let minutes = date.getMinutes();
            return this.pad(hours) + ":" + this.pad(minutes);
        },
        timezone(datetime) {
            let day, month, year, time, year_time;
            const explode = datetime != undefined ? datetime.split("/") : "";
            const language = localStorage.getItem("language");

            if (explode.length > 1) {
                [day, month, year_time] = explode;
                [year, time] = year_time.split(" ");
            } else
                time = explode[0];

            switch (language) {
                case "pt_br":
                    if (explode.length > 1)
                        return Kanban.helpers.currentDateTime("date") == `${day}/${month}/${year}` ? time : `${day}/${month}/${year} ${time}`;
                    else
                        return time;
                case "en_us":
                    if (explode.length > 1)
                        return Kanban.helpers.currentDateTime("date") == `${day}/${month}/${year}` ? time : `${month}/${day}/${year} ${time}`;
                    else
                        return time;
                case "es":
                    if (explode.length > 1)
                        return Kanban.helpers.currentDateTime("date") == `${day}/${month}/${year}` ? time : `${day}/${month}/${year} ${time}`;
                    else
                        return time;
                default:
                    break;
            }

        },
        pad(num) {
            return ("0" + num).slice(-2);
        },
        clearBatteryFilter() {
            localStorage.setItem("batteryFilterCategory", null);
        }
    },
}


document.querySelectorAll(".kanban .column-list").forEach(elm => elm.addEventListener("scroll", Kanban.collumn.eventScroll));

document.getElementById("modal__btn-cancel").addEventListener("click", Kanban.chat.hideTransferFields);
document.getElementById("modal__select-sector").addEventListener("change", Kanban.socket.queryUsers);
document.getElementById("messages").addEventListener("scroll", Kanban.chat.scrollTop);

const canelExitModal = () => {
    document.querySelector(".modal-exit-bg-box").remove();
    document.querySelector(".modal-exit").remove();
}


const openExitModal = () => {
    closeSettingsWindow();

    Kanban.alert.fire({
        "title": GLOBAL_LANG.kanban_attendance_settings_logout_title,
        "message": GLOBAL_LANG.kanban_attendance_settings_logout_message,
        "type": "action"
    }).then(res => {
        if (res == "yes")
            window.location.href = "/account/logoff";

        Kanban.alert.close();
    });
}


const closeSettingsWindow = () => {
    document.querySelector(".settings-bg-box").remove();
    document.querySelector(".settings-option-modal").style.display = "none";

    if (document.querySelector(".internal-content-options") != null) document.querySelector(".internal-content-options").remove();
    if (document.querySelector(".internal-content-theme") != null) document.querySelector(".internal-content-theme").remove();
    if (document.querySelector(".internal-content-wallpape") != null) document.querySelector(".internal-content-wallpape").remove();
}


const backSettingsWindow = () => {
    closeSettingsWindow();
    openSettingsWindow();
}


const lastBorderItem = () => {
    const item = document.querySelector(".color-grid .item:last-child");

    if (item.attributes.selected === "false") {
        item.style.border = "solid 1px rgb(125, 125, 125)";
    }
}


const colorSelected = () => {
    [...document.querySelectorAll(".color-grid .item")].map((elm) => {
        if (elm.attributes.color.value === document.querySelector("body").attributes.color.value) {
            elm.style.border = "5px solid #014b6c";
            elm.setAttribute("selected", "true");
        }
    });

    document.querySelector("body").attributes.color.value;
}


const setWallpaper = (e) => {
    [...document.querySelectorAll(".color-grid .item")].map((elm) => {
        elm.setAttribute("selected", "false");
        elm.style.border = "none";
    });

    document.querySelector(".color-grid .item:last-child").style.border = "solid 1px rgb(125, 125, 125)";
    e.target.setAttribute("selected", "true");
    e.target.style.border = "5px solid #014b6c";
    localStorage.setItem("communicationKanbanColor", `${e.target.attributes.color.value}__${e.target.style.background}`);
    lastBorderItem();
}


const mouseoutWallpaper = () => {
    [...document.querySelectorAll(".color-grid .item")].map((elm) => {
        if (elm.attributes.selected.value === "true") {
            document.querySelector("body").style.background = elm.style.background;
            document.querySelector("body").attributes.color.value = elm.attributes.color.value;
        }
    });

    lastBorderItem();
}


const mousehoverWallpaper = (e) => {
    document.querySelector("body").style.background = e.target.style.background;
    document.querySelector("body").attributes.color.value = e.target.attributes.color.value;
    lastBorderItem();
}


const openWallpaper = () => {
    document.querySelector(".settings-header .title").innerHTML = GLOBAL_LANG.kanban_attendance_settings_title_wallpaper;
    document.querySelector(".internal-content-options").style.display = "none";
    document.querySelector(".settings-option-modal #selectLeft").style.display = "block";

    const createColor = (color) => {
        for (let i = 0; i < color.length; i++) {
            const item = Components.div({ class: "item", style: `background: ${color[i]}`, customAttribute: ["color", `hex${i}`, "selected", false] });
            item.getAttribute("color") == "hex0" ? item.innerHTML = GLOBAL_LANG.kanban_attendance_settings_default_color : "";
            item.addEventListener("click", setWallpaper);
            item.addEventListener("mouseover", mousehoverWallpaper);
            item.addEventListener("mouseout", mouseoutWallpaper);
            grid_color.appendChild(item);
        }
    }

    const attendanceKanbanTheme = localStorage.getItem("attendanceKanbanTheme");
    const internal_content_wallpaper = Components.div({ class: "internal-content-wallpape" });

    const grid_color = Components.div({ class: "color-grid" });

    switch (attendanceKanbanTheme) {
        case null:
        case "standard":
            if (window.matchMedia("(prefers-color-scheme: dark)").matches)
                createColor(colorPalette.dark);
            else
                createColor(colorPalette.ligth);
            break;
        case "dark":
            createColor(colorPalette.dark);
            break;
        case "ligth":
            createColor(colorPalette.ligth);
            break;
        default:
            break;
    }

    internal_content_wallpaper.appendChild(grid_color);
    document.querySelector(".settings-body").appendChild(internal_content_wallpaper);
    colorSelected();
}


const changewallpaper = () => {

    const search = (colorPalette) => {
        for (let i = 0; i < colorPalette.length; i++) {
            if (bodyKanban.attributes.color.value === `hex${i}`) {
                bodyKanban.style.background = colorPalette[i];
                localStorage.setItem("communicationKanbanColor", `${bodyKanban.attributes.color.value}__${colorPalette[i]}`);
            }
        }
    }

    const attendanceKanbanTheme = localStorage.getItem("attendanceKanbanTheme");
    const bodyKanban = document.querySelector("body");

    switch (attendanceKanbanTheme) {
        case "standard":
            if (window.matchMedia("(prefers-color-scheme: dark)").matches)
                search(colorPalette.dark);
            else
                search(colorPalette.ligth);
            break;
        case "dark":
            search(colorPalette.dark);
            break;
        case "ligth":
            search(colorPalette.ligth);
            break;
        default:
            break;
    }
}


const setThemeCheckbox = (e) => {
    if (e.target.id === "_dark") {
        if (document.getElementById("_dark").checked)
            document.getElementById("_dark").checked = true;
    }

    if (e.target.id === "_ligth") {
        if (document.getElementById("_ligth").checked)
            document.getElementById("_ligth").checked = true;
    }

    if (e.target.id === "_standard") {
        if (document.getElementById("_standard").checked)
            document.getElementById("_standard").checked = true;
    }
}


const addLightTheme = (e) => {

    const serve = () => {
        document.getElementById("_ligth").checked = true;
        document.getElementById("_dark").checked = false;
        document.getElementById("_standard").checked = false;

        document.querySelector("body").classList.remove("dark");
        localStorage.setItem("attendanceKanbanTheme", "ligth");
        changewallpaper();
    }

    if (e.target.type === "checkbox") {
        serve();
    } else if (!document.getElementById("_ligth").checked) {
        serve();
    }
}


const addDarkTheme = (e) => {

    const serve = () => {
        document.getElementById("_dark").checked = true;
        document.getElementById("_ligth").checked = false;
        document.getElementById("_standard").checked = false;

        document.querySelector("body").classList.add("dark");
        localStorage.setItem("attendanceKanbanTheme", "dark");
        changewallpaper();
    }

    if (e.target.type === "checkbox") {
        serve();
    } else if (!document.getElementById("_dark").checked) {
        serve();
    }
}


const addStandardTheme = (e) => {

    const serve = () => {
        document.getElementById("_standard").checked = true;
        document.getElementById("_dark").checked = false;
        document.getElementById("_ligth").checked = false;

        if (window.matchMedia("(prefers-color-scheme: dark)").matches)
            document.querySelector("body").classList.add("dark");
        else
            document.querySelector("body").classList.remove("dark");

        localStorage.setItem("attendanceKanbanTheme", "standard");
        changewallpaper();
    }

    if (e.target.type === "checkbox") {
        serve();
    } else if (!document.getElementById("_standard").checked) {
        serve();
    }
}


const setTheme = () => {
    if (localStorage.getItem("attendanceKanbanTheme") == null) {
        if (window.matchMedia("(prefers-color-scheme: dark)").matches)
            document.getElementById("_dark").checked = true;
        else
            document.getElementById("_ligth").checked = true;
    }

    if (localStorage.getItem("attendanceKanbanTheme") === "ligth")
        document.getElementById("_ligth").checked = true;

    if (localStorage.getItem("attendanceKanbanTheme") === "dark")
        document.getElementById("_dark").checked = true;

    if (localStorage.getItem("attendanceKanbanTheme") === "standard")
        document.getElementById("_standard").checked = true;
}


const openDarkMode = () => {
    document.querySelector(".settings-header .title").innerHTML = GLOBAL_LANG.kanban_attendance_settings_title_theme;
    document.querySelector(".internal-content-options").style.display = "none";
    document.querySelector(".settings-option-modal #selectLeft").style.display = "block";

    const internal_content_theme = Components.div({ class: "internal-content-theme" });

    const option_light = Components.div({ class: "option-theme", id: "opt__ligth" });
    option_light.appendChild(Components.checkbox({ id: "_ligth" }));
    option_light.appendChild(Components.label({ text: GLOBAL_LANG.kanban_attendance_settings_title_theme_light }));

    const option_dark = Components.div({ class: "option-theme", id: "opt__dark" });
    option_dark.appendChild(Components.checkbox({ id: "_dark" }));
    option_dark.appendChild(Components.label({ text: GLOBAL_LANG.kanban_attendance_settings_title_theme_dark }));

    const option_standard = Components.div({ class: "option-theme", id: "opt__standard" });
    option_standard.appendChild(Components.checkbox({ id: "_standard" }));
    option_standard.appendChild(Components.label({ text: GLOBAL_LANG.kanban_attendance_settings_title_theme_system_default }));

    option_light.addEventListener("click", addLightTheme);
    option_dark.addEventListener("click", addDarkTheme);
    option_standard.addEventListener("click", addStandardTheme);

    internal_content_theme.appendChild(option_light);
    internal_content_theme.appendChild(option_dark);
    internal_content_theme.appendChild(option_standard);

    document.querySelector(".settings-body").appendChild(internal_content_theme);
    setTheme();
}


const openSettingsWindow = () => {

    document.querySelector(".settings-header .title").innerHTML = GLOBAL_LANG.kanban_attendance_settings_title;
    document.querySelector(".settings-option-modal #selectLeft").style.display = "none";

    const internal_content_options = Components.div({ class: "internal-content-options" });
    const option_theme = Components.label({ class: "option" });
    const box_icon_theme = Components.div({ class: "box-icon" });
    const main_theme = Components.div({ class: "text" });

    box_icon_theme.appendChild(Components.img({ src: "/assets/icons/kanban/dark2.svg" }));
    main_theme.appendChild(Components.span({ text: GLOBAL_LANG.kanban_attendance_settings_title_theme }));

    option_theme.appendChild(box_icon_theme);
    option_theme.appendChild(main_theme);

    const option_wallpaper = Components.label({ class: "option" });
    const box_icon_wallpaper = Components.div({ class: "box-icon" });
    const main_wallpaper = Components.div({ class: "text" });

    box_icon_wallpaper.appendChild(Components.img({ src: "/assets/icons/kanban/wallpaper2.svg" }));
    main_wallpaper.appendChild(Components.span({ text: GLOBAL_LANG.kanban_attendance_settings_title_wallpaper }));

    option_wallpaper.appendChild(box_icon_wallpaper);
    option_wallpaper.appendChild(main_wallpaper);

    const option_to_go_out = Components.label({ class: "option" });
    const box_icon_to_go_out = Components.div({ class: "box-icon" });
    const main_to_go_out = Components.div({ class: "text" });

    box_icon_to_go_out.appendChild(Components.img({ src: "/assets/icons/kanban/close2.svg" }));
    main_to_go_out.appendChild(Components.span({ text: GLOBAL_LANG.kanban_attendance_settings_title_logout }));

    option_to_go_out.appendChild(box_icon_to_go_out);
    option_to_go_out.appendChild(main_to_go_out);

    internal_content_options.appendChild(option_theme);
    internal_content_options.appendChild(option_wallpaper);
    internal_content_options.appendChild(option_to_go_out);

    const bgBox = Components.div({ class: "settings-bg-box" });
    bgBox.addEventListener("click", closeSettingsWindow);

    document.querySelector(".settings-body").appendChild(internal_content_options);
    document.querySelector("body").appendChild(bgBox);

    option_theme.addEventListener("click", openDarkMode);
    option_wallpaper.addEventListener("click", openWallpaper);
    option_to_go_out.addEventListener("click", openExitModal);

    $(".settings-option-modal").slideToggle(0);
}

document.querySelector(".kanban .settings-icon").addEventListener("click", openSettingsWindow);

const adjustLayout = () => {
    const body_width = window.innerWidth;
    const mx_rev_cards = document.querySelectorAll(".mx-rev-card");
    const kanban_main_flex = document.querySelector(".kanban .container-fluid .main-flex");

    if (body_width <= 990) {
        mx_rev_cards.forEach((elm) => elm.className = "col-12 mx-rev-card");
        kanban_main_flex.style.height = "94vh";

    } else if (body_width <= 1100) {
        mx_rev_cards.forEach((elm) => elm.className = "col-5 mx-rev-card");

    } else if (body_width <= 1200) {
        mx_rev_cards.forEach((elm) => {
            elm.style.padding_right = "5px";
            elm.style.padding_left = "5px";
        });

        document.querySelector(".kanban .container-fluid").style.padding_right = "10px";
        document.querySelector(".kanban .container-fluid").style.padding_left = "10px";
    }
}

window.addEventListener("load", adjustLayout);
window.addEventListener("resize", adjustLayout);


const showUserFilter = () => {
    [...document.querySelectorAll(".kanban .user-window .user-item .information")].map(elm => elm.style.display = "block");
    [...document.querySelectorAll(".kanban .user-window .user-item .box")].map(elm => elm.style.marginRight = "15px");
    [...document.querySelectorAll(".kanban .user-window .user-item")].map(elm => elm.style.padding = "0.8rem 2rem");
    [...document.querySelectorAll(".kanban .user-window .user-item")].map(elm => elm.style.justifyContent = "left");

    document.querySelector(".kanban .drawer-side").style.display = "flex";
    document.querySelector(".kanban .drawer-side").style.width = "35rem";
    document.querySelector(".kanban .user-window .settings-header").style.display = "block";
};


const hideUserFilter = () => {
    [...document.querySelectorAll(".kanban .user-window .user-item .information")].map(elm => elm.style.display = "none");
    [...document.querySelectorAll(".kanban .user-window .user-item .box")].map(elm => elm.style.marginRight = "0px");
    [...document.querySelectorAll(".kanban .user-window .user-item")].map(elm => elm.style.padding = "0.8rem 0.6rem");
    [...document.querySelectorAll(".kanban .user-window .user-item")].map(elm => elm.style.justifyContent = "center");

    document.querySelector(".kanban .drawer-side").style.width = "8rem";
    document.querySelector(".kanban .user-window .settings-header").style.display = "none";
};


const userFilter = () => {
    const drawer_side = document.querySelector(".kanban .drawer-side");

    if (drawer_side.style.display === "" || drawer_side.style.width === "8rem") {
        showUserFilter();
        localStorage.setItem("openDrawer", true);
    } else {
        hideUserFilter();
        localStorage.setItem("openDrawer", false);
    }
};


document.querySelector(".kanban .header .filter").addEventListener("click", userFilter);


const filter = {
    users: {
        list(users) {
            document.querySelectorAll("#list__channel .user-item").forEach(elm => elm.remove());

            for (const elm of users) {

                if (!Kanban.attendance.userExist(elm.key_remote_id)) {

                    const item = Components.div({ class: "user-item", id: elm.key_remote_id });
                    const box = Components.div({ class: "box" });
                    const info = Components.div({ class: "information", style: "display:none" });

                    box.appendChild(Components.img({ src: document.location.origin + "/assets/img/loads/loading_2.gif" }));
                    box.appendChild(Components.span({ text: "0", class: elm.presence === "Online" ? "ball online" : "ball" }));
                    info.appendChild(Components.span({ text: elm.full_name, class: "user-name" }));
                    info.appendChild(Components.span({ text: elm.departament, class: "departament" }));
                    info.appendChild(Components.span({ text: filter.users.languageLastSeen(elm.presence), class: "last-seen" }));

                    item.appendChild(box);
                    item.appendChild(info);
                    item.addEventListener("click", filter.users.selected);

                    document.getElementById("list__users").appendChild(item);
                    Kanban.process.addImages(users, "user");
                    collumnUsers.push(elm);
                }

            }

            const openDrawer = localStorage.getItem("openDrawer");

            if (openDrawer === "true" || openDrawer === null)
                showUserFilter();
            else
                hideUserFilter();

            Kanban.helpers.userServiceQuantity();
            filter.users.header.default();
        },
        languageLastSeen(presence) {
            const lang = presence.split(":")[0];
            const datetime = presence.split("seen:")[1];

            switch (lang) {
                case "last_seen":
                    return GLOBAL_LANG.kanban_attendance_user_window_last_seen + Kanban.helpers.timezone(datetime);
                case "online":
                    return GLOBAL_LANG.kanban_attendance_user_window_online;
                default:
                    break;
            }
        },
        input() {
            if (this.value.length > 3 && this.value.slice(-3) === '...') {
                this.value = this.value.slice(0, -3);
            }

            filter.users.icon.show();
            filter.users.search.keyup(this.value);
        },
        selected() {
            Kanban.attendance.removeAll();
            Kanban.attendance.resetLimitOfTwo();

            collumnWaitingUserSearch = filter.users.search.click(collumnWaiting, this.attributes.id.value);
            collumnInServiceUserSearch = filter.users.search.click(collumnInService, this.attributes.id.value);
            collumnOnHoldUserSearch = filter.users.search.click(collumnOnHold, this.attributes.id.value);
            collumnClosedUserSearch = filter.users.search.click(collumnClosed, this.attributes.id.value);

            for (const item of collumnWaitingUserSearch.items)
                Kanban.attendance.create(item, collumnWaiting.listType, true);

            for (const item of collumnInServiceUserSearch.items)
                Kanban.attendance.create(item, collumnInService.listType, true);

            for (const item of collumnOnHoldUserSearch.items)
                Kanban.attendance.create(item, collumnOnHold.listType, true);

            for (const item of collumnClosedUserSearch.items)
                Kanban.attendance.create(item, collumnClosed.listType, true);

            filter.users.color.remove();
            filter.users.color.set(this);

            filter.users.clearGeneralInput();
            filter.users.header.selected(this);
            Kanban.attendance.count("searchUser");

            filter.users.hasBatteryFilter();
        },
        hasBatteryFilter() {
            if (filter.general.tooltips.option.isElementSelected()) {

                const battery_filter_category = localStorage.getItem("batteryFilterCategory");
                const placeholder_text = document.querySelector("#general-search").getAttribute("placeholder");

                switch (battery_filter_category) {
                    case "labels_name":
                        filter.general.tooltips.option.setTag(placeholder_text);
                        break;
                    case "name_channel":
                        filter.general.tooltips.option.setChannel(placeholder_text);
                        break;
                    case "departament":
                        filter.general.tooltips.option.setDepartament(placeholder_text);
                        break;
                    default:
                        break;
                }
            }
        },
        header: {
            default() {
                const item = Components.div({ class: "filter-item all-users" });
                const box = Components.div({ class: "box" });
                const info = Components.div({ class: "information" });

                const name = Components.span({ text: GLOBAL_LANG.kanban_attendance_user_window_all_users });
                const phone = Components.span({ text: "" });
                const down_arrow = Components.img({ src: document.location.origin + "/assets/icons/kanban/down_arrow.svg", id: "down_arrow_icon" });

                info.appendChild(name);
                info.appendChild(phone);
                info.appendChild(down_arrow);

                item.appendChild(box);
                item.appendChild(info);

                document.querySelector(".filter-item.all-users")?.remove();
                document.getElementsByClassName("filter")[0].appendChild(item);

                document.getElementById("general-search").setAttribute("selected-option", "");
                document.querySelector(".content-center .search .battery-filter-selector").classList.remove("selected");
                filter.users.clearGeneralInput();
                filter.users.search.clear();
                filter.general.icon.resetInput();
            },
            selected(user) {
                filter.users.header.removeImg();
                filter.users.header.removeArrow();

                const filter_item_box = document.querySelector(".kanban .header .content-right .filter .filter-item .box");
                filter_item_box.appendChild(Components.img({ src: document.location.origin + "/assets/icons/kanban/chat_green.svg" }));

                const filter_item_information = document.querySelector(".filter-item .information");
                const user_last_child = user.lastChild;
                filter_item_information.firstChild.innerHTML = Kanban.helpers.doTruncarStr(user_last_child.firstChild.innerHTML, 20);
                filter_item_information.children[1].innerHTML = user_last_child.children[1].innerHTML;

                const filter_item_box_img = filter_item_box.querySelector("img");
                filter_item_box_img.src = user.firstChild.firstChild.src;
            },
            remove() {
                document.querySelector(".filter .filter-item").remove();
            },
            removeImg() {
                const filter_item_box = document.querySelector(".kanban .header .content-right .filter .filter-item .box");
                if (filter_item_box.querySelector("img")) {
                    filter_item_box.querySelector("img").remove();
                }
            },
            removeArrow() {
                const down_arrow_icon = document.querySelector(".kanban .header .content-right #down_arrow_icon");
                if (down_arrow_icon) {
                    down_arrow_icon.remove();
                }
            }
        },
        color: {
            set(elm) {
                elm.classList.add("selected-user");
            },
            remove() {
                document.querySelectorAll(".list-users .user-item").forEach(elm => elm.classList.remove("selected-user"));
            },
        },
        search: {
            keyup(text) {
                if (text.length > 3 && text.slice(-3) === '...') {
                    text = text.slice(0, -3);
                }

                const list_users = [...document.querySelectorAll("#list__users .user-item")];
                const lower_case_text = Kanban.helpers.removeAccents(text.toLowerCase());
                let elementText;
                let element_get_user

                if (typeInformation == 'all') {
                    list_users.forEach((elm) => {
                        elementText = Kanban.helpers.removeAccents(elm.lastElementChild.firstElementChild.innerHTML.toLowerCase());

                        if (elementText.includes(lower_case_text)) {
                            elm.style.display = "flex";
                        } else {
                            elm.style.display = "none";
                        }
                    });
                } else {
                    list_users.forEach((elm) => {
                        element_get_user = Kanban.helpers.removeAccents(elm.lastElementChild.childNodes[0].innerHTML.toLowerCase());
                        elementText = Kanban.helpers.removeAccents(elm.lastElementChild.childNodes[1].innerHTML.toLowerCase()).replaceAll('  ', ' ');

                        if (elementText.includes(lower_case_text) || element_get_user.includes(lower_case_text)) {
                            elm.style.display = "flex";
                        } else {
                            elm.style.display = "none";
                        }
                    });
                }
            },
            click(collumn, user_key_remote_id) {
                if (!collumn) return null;

                const result = [];
                const selected_user_regexp = new RegExp(user_key_remote_id);

                for (const item of collumn.items) {
                    const user = new String(item.user_key_remote_id);

                    if (user.search(selected_user_regexp) === 0) {
                        result.push(item);
                    }
                }

                return {
                    list_type: collumn,
                    items: result,
                    event: "process",
                    status: 200
                };
            },
            all() {
                filter.users.show();
                filter.users.search.clear();
                filter.general.search.all();

                filter.users.header.remove();
                filter.users.header.default();
                filter.general.tooltips.deselectedFilter();
            },
            clear() {
                const search_input = document.getElementById("search-user");
                search_input.value = "";
                search_input.placeholder = GLOBAL_LANG.kanban_attendance_filter_search_user_placeholder;

                filter.users.show();
            }
        },
        icon: {
            show() {
                const search_user_input = document.getElementById("search-user");
                const clear_search_user_icon = document.getElementById("clear-search-user");

                if (search_user_input.value.length > 0)
                    clear_search_user_icon.style.display = "block";
                else
                    filter.users.icon.hide();
            },
            hide() {
                const clear_search_user = document.getElementById("clear-search-user");
                clear_search_user.style.display = "none";
            }
        },
        show() {
            const list_users = [...document.querySelectorAll("#list__users .user-item")];
            list_users.forEach(elm => {
                elm.style.display = "flex";
            });

            filter.users.color.remove();
            filter.users.icon.hide();
        },
        isUserSelected() {
            const list_users = [...document.querySelectorAll("#list__users .user-item")];
            for (const elm of list_users) {
                if (elm.classList.contains("selected-user")) {
                    return true;
                }
            }

            return false;
        },
        clearGeneralInput() {
            document.getElementById("general-search").value = "";
            document.getElementById("clear-search-general").style.display = "none";
        }
    },
    general: {
        input() {
            filter.general.icon.show();
            filter.general.search.type(this.value);

            Kanban.attendance.removeAll();
            Kanban.attendance.resetLimitOfTwo();
            filter.general.create();
        },
        create() {
            for (const item of collumnWaitingGenaralSearch.items)
                Kanban.attendance.create(item, collumnWaiting.listType, true);

            for (const item of collumnInServiceGenaralSearch.items)
                Kanban.attendance.create(item, collumnInService.listType, true);

            for (const item of collumnOnHoldGenaralSearch.items)
                Kanban.attendance.create(item, collumnOnHold.listType, true);

            for (const item of collumnClosedGenaralSearch.items)
                Kanban.attendance.create(item, collumnClosed.listType, true);

            Kanban.attendance.count("searchGeneral");
        },
        search: {
            type(text) {
                if (filter.users.isUserSelected()) {
                    collumnWaitingGenaralSearch = filter.general.search.found(collumnWaitingUserSearch, text);
                    collumnInServiceGenaralSearch = filter.general.search.found(collumnInServiceUserSearch, text);
                    collumnOnHoldGenaralSearch = filter.general.search.found(collumnOnHoldUserSearch, text);
                    collumnClosedGenaralSearch = filter.general.search.found(collumnClosedUserSearch, text);

                } else if (filter.general.tooltips.option.isElementSelected()) {
                    collumnWaitingGenaralSearch = filter.general.search.found(collumnWaitingBatterySearch, text, localStorage.getItem("batteryFilterCategory"));
                    collumnInServiceGenaralSearch = filter.general.search.found(collumnInServiceBatterySearch, text, localStorage.getItem("batteryFilterCategory"));
                    collumnOnHoldGenaralSearch = filter.general.search.found(collumnOnHoldBatterySearch, text, localStorage.getItem("batteryFilterCategory"));
                    collumnClosedGenaralSearch = filter.general.search.found(collumnClosedBatterySearch, text, localStorage.getItem("batteryFilterCategory"));

                } else {
                    collumnWaitingGenaralSearch = filter.general.search.found(collumnWaiting, text);
                    collumnInServiceGenaralSearch = filter.general.search.found(collumnInService, text);
                    collumnOnHoldGenaralSearch = filter.general.search.found(collumnOnHold, text);
                    collumnClosedGenaralSearch = filter.general.search.found(collumnClosed, text);
                }
            },
            found(column, text) {
                if (!column) return null;

                const normalized_text = text.replace(/\s+/g, ' ').trim().replace(/[.*+?^${}()|[\]\\]/g, '\\$&');;
                const typed_type_regexp = new RegExp(Kanban.helpers.removeAccents(normalized_text), "i");
                const result = [];

                for (const item of column.items) {
                    const properties_to_search = ["full_name", "user_name", "departament", "name_channel", "labels_name", "key_remote_id", "email"];
                    let should_add_item = false;

                    for (const property of properties_to_search) {
                        const value = String(item[property]).replace(/\s+/g, ' ').trim();
                        if (Kanban.helpers.removeAccents(value).match(typed_type_regexp)) {
                            should_add_item = true;
                            break;
                        }
                    }

                    if (should_add_item) {
                        result.push(item);
                    }
                }

                return {
                    list_type: column.listType,
                    items: result,
                    event: "process",
                    status: 200
                };
            },
            all() {
                Kanban.attendance.removeAll();
                Kanban.attendance.resetLimitOfTwo();

                for (const item of collumnWaiting.items)
                    Kanban.attendance.create(item, collumnWaiting.listType, true);

                for (const item of collumnInService.items)
                    Kanban.attendance.create(item, collumnInService.listType, true);

                for (const item of collumnOnHold.items)
                    Kanban.attendance.create(item, collumnOnHold.listType, true);

                for (const item of collumnClosed.items)
                    Kanban.attendance.create(item, collumnClosed.listType, true);

                Kanban.attendance.count("total");
                filter.users.color.remove();
            },
            allUser() {
                Kanban.attendance.removeAll();
                Kanban.attendance.resetLimitOfTwo();

                for (const item of collumnWaitingUserSearch.items)
                    Kanban.attendance.create(item, collumnWaiting.listType, true);

                for (const item of collumnInServiceUserSearch.items)
                    Kanban.attendance.create(item, collumnInService.listType, true);

                for (const item of collumnOnHoldUserSearch.items)
                    Kanban.attendance.create(item, collumnOnHold.listType, true);

                for (const item of collumnClosedUserSearch.items)
                    Kanban.attendance.create(item, collumnClosed.listType, true);

                Kanban.attendance.count("searchUser");
            }
        },
        icon: {
            show() {
                const general_user_input = document.getElementById("general-search");
                const clear_search_general = document.getElementById("clear-search-general");

                if (general_user_input.value.length > 0)
                    clear_search_general.style.display = "block";
                else
                    filter.general.icon.hide();
            },
            hide() {
                const clear_search_general = document.getElementById("clear-search-general");
                clear_search_general.style.display = "none";
            },
            clear() {
                filter.general.icon.resetInput();
                filter.general.icon.hide();

                if (filter.general.tooltips.option.isElementSelected()) {
                    filter.general.tooltips.search.all();
                } else {
                    filter.general.search.all();
                }
            },
            resetInput() {
                const general_user_input = document.getElementById("general-search");
                const selected_option = general_user_input.getAttribute("selected-option");
                general_user_input.placeholder = selected_option == "" ? GLOBAL_LANG.kanban_attendance_filter_search_placeholder : selected_option;
                general_user_input.value = "";
            }
        },
        tooltips: {
            hide() {
                document.querySelector(".content-center .search .tooltips").style.display = "none";
                document.querySelector(".settings-header .input-search .tooltips-user").style.display = "none";
            },
            show(typeInfo = 'generalSearch') {
                if (typeInfo == 'generalSearch') {
                    document.querySelector(".content-center .search .tooltips").style.display = "block";
                } else {
                    document.querySelector(".settings-header .input-search .tooltips-user").style.display = "block";
                }
            },
            createBgBox() {
                const bg_box = Components.div({ class: "tooltips-bg-box" });
                bg_box.addEventListener("click", filter.general.tooltips.removeBgBox);
                document.querySelector("body").appendChild(bg_box);
            },
            removeBgBox() {
                filter.general.tooltips.hide();
                filter.general.tooltips.option.remove();
                document.querySelector(".tooltips-bg-box")?.remove();
            },
            deselectedFilter() {
                localStorage.setItem("batteryFilterCategory", null);
                document.getElementById("general-search").setAttribute("selected-option", "");
                document.querySelector(".content-center .search .battery-filter-selector").classList.remove("selected");
                document.querySelector(".settings-header .input-search .filter-selector-user").classList.remove("selected");

                if (!filter.users.isUserSelected()) {
                    filter.general.search.all();
                    filter.general.icon.clear();
                } else {
                    filter.general.search.allUser();
                    filter.general.icon.resetInput();
                }
            },
            selectedFilter() {
                if (typeInformation == 'all') {
                    document.querySelector(".content-center .search .battery-filter-selector").classList.add("selected");
                } else {
                    document.querySelector(".settings-header .input-search .filter-selector-user").classList.add("selected");
                }
            },
            placeholderInput(text) {
                let search_input;
                if (typeInformation == 'all') {
                    search_input = document.getElementById("general-search");
                    if (search_input) {
                        search_input.value = "";
                        search_input.placeholder = text;
                        search_input.setAttribute("selected-option", text);
                    }
                } else {
                    search_input = document.getElementById("search-user");
                    if (search_input) {
                        search_input.value = "";
                        search_input.placeholder = text;
                        search_input.addEventListener("click", filter.users.search.keyup(text));
                        search_input.setAttribute("selected-option", text);
                    }
                }
            },
            option: {
                show(options, typeInfo = 'all') {
                    typeInformation = typeInfo;
                    if (options && Array.isArray(options) && options.length > 0) {
                        if (!filter.general.tooltips.option.isElementSelected()) {
                            options.forEach((data) => {
                                const tooltip_option = filter.general.tooltips.option.create(data);
                                const tooltips_container = typeInfo == 'all' ? document.querySelector(".content-center .search .tooltips") : document.querySelector(".settings-header .input-search .tooltips-user");
                                tooltips_container.appendChild(tooltip_option);
                            });

                            filter.general.tooltips.createBgBox(typeInfo == 'all' ? 'generalSearch' : 'userSearch');
                            setTimeout(() => filter.general.tooltips.show(typeInfo == 'all' ? 'generalSearch' : 'userSearch'), 90);
                        } else
                            filter.general.tooltips.deselectedFilter();
                    };
                },
                create(data) {
                    const row = Components.div({ class: `rows ${data.type}` });
                    const icon = Components.img({ src: data.icon, class: "icons" });
                    const option = Components.span({ class: "option", text: Kanban.helpers.doTruncarStr(data.name, 28) });

                    if (data.show_icon) row.appendChild(icon);
                    row.appendChild(option);
                    row.addEventListener("click", filter.general.tooltips.option.click);
                    return row;
                },
                click() {
                    if (this.classList.contains("tag"))
                        filter.general.tooltips.option.getTag();

                    if (this.classList.contains("channel"))
                        filter.general.tooltips.option.getChannel();

                    if (this.classList.contains("departament"))
                        filter.general.tooltips.option.getDepartament();

                    if (this.classList.contains("sub-tag"))
                        filter.general.tooltips.option.setTag(this.innerText);

                    if (this.classList.contains("sub-channel"))
                        filter.general.tooltips.option.setChannel(this.innerText);

                    if (this.classList.contains("sub-departament"))
                        filter.general.tooltips.option.setDepartament(this.innerText);
                },
                remove() {
                    document.querySelectorAll(".tooltips .rows").forEach(elm => {
                        elm.remove();
                    });

                    document.querySelectorAll(".tooltips-user .rows").forEach(elm => {
                        elm.remove();
                    });
                },
                getTag() {
                    filter.general.tooltips.option.remove();
                    batteryFilterInfo.labels.forEach((data) => {
                        const tooltip_option = filter.general.tooltips.option.create(data);
                        const tooltips_container = document.querySelector(".content-center .search .tooltips");
                        tooltips_container.appendChild(tooltip_option);
                    });
                },
                getChannel() {
                    filter.general.tooltips.option.remove();
                    batteryFilterInfo.channel.forEach((data) => {
                        const tooltip_option = filter.general.tooltips.option.create(data);
                        const tooltips_container = document.querySelector(".content-center .search .tooltips");
                        tooltips_container.appendChild(tooltip_option);
                    });
                },
                getDepartament() {
                    filter.general.tooltips.option.remove();
                    batteryFilterInfo.departament.forEach((data) => {
                        const tooltip_option = filter.general.tooltips.option.create(data);
                        const tooltips_container = document.querySelector(".content-center .search .tooltips");
                        tooltips_container.appendChild(tooltip_option);
                    });
                },
                setTag(text) {
                    const category = "labels_name";

                    filter.general.tooltips.hide();
                    filter.general.tooltips.removeBgBox();
                    filter.general.tooltips.selectedFilter();
                    filter.general.tooltips.placeholderInput(text);

                    filter.general.tooltips.search.battery(text, category);
                    localStorage.setItem("batteryFilterCategory", category);
                },
                setChannel(text) {
                    const category = "name_channel";

                    filter.general.tooltips.hide();
                    filter.general.tooltips.removeBgBox();
                    filter.general.tooltips.selectedFilter();
                    filter.general.tooltips.placeholderInput(text);

                    filter.general.tooltips.search.battery(text, category);
                    localStorage.setItem("batteryFilterCategory", category);
                },
                setDepartament(text) {
                    const category = "departament";

                    filter.general.tooltips.hide();
                    filter.general.tooltips.removeBgBox();
                    filter.general.tooltips.selectedFilter();
                    filter.general.tooltips.placeholderInput(text);

                    if (typeInformation == 'all') {
                        filter.general.tooltips.search.battery(text, category);
                    }

                    localStorage.setItem("batteryFilterCategory", category);
                },
                isElementSelected() {
                    if (typeInformation === 'all') {
                        return document.getElementById("battery-filter-selector").classList.contains("selected");
                    }
                    return document.getElementById("filter-selector-user").classList.contains("selected");
                }
            },
            search: {
                battery(text, category) {
                    if (text.length > 3 && text.slice(-3) === '...') {
                        text = text.slice(0, -3);
                    }

                    Kanban.attendance.removeAll();
                    Kanban.attendance.resetLimitOfTwo();

                    if (filter.users.isUserSelected()) {
                        collumnWaitingBatterySearch = filter.general.tooltips.search.found(collumnWaitingUserSearch, text, category);
                        collumnInServiceBatterySearch = filter.general.tooltips.search.found(collumnInServiceUserSearch, text, category);
                        collumnOnHoldBatterySearch = filter.general.tooltips.search.found(collumnOnHoldUserSearch, text, category);
                        collumnClosedBatterySearch = filter.general.tooltips.search.found(collumnClosedUserSearch, text, category);
                    } else {
                        collumnWaitingBatterySearch = filter.general.tooltips.search.found(collumnWaiting, text, category);
                        collumnInServiceBatterySearch = filter.general.tooltips.search.found(collumnInService, text, category);
                        collumnOnHoldBatterySearch = filter.general.tooltips.search.found(collumnOnHold, text, category);
                        collumnClosedBatterySearch = filter.general.tooltips.search.found(collumnClosed, text, category);
                    }

                    filter.general.tooltips.search.create();
                },
                create() {

                    for (const item of collumnWaitingBatterySearch.items)
                        Kanban.attendance.create(item, collumnWaiting.listType, true);

                    for (const item of collumnInServiceBatterySearch.items)
                        Kanban.attendance.create(item, collumnInService.listType, true);

                    for (const item of collumnOnHoldBatterySearch.items)
                        Kanban.attendance.create(item, collumnOnHold.listType, true);

                    for (const item of collumnClosedBatterySearch.items)
                        Kanban.attendance.create(item, collumnClosed.listType, true);

                    Kanban.attendance.count("searchBattery");
                },
                found(column, text, category) {
                    if (!column) return null;

                    const normalized_text = text.replace(/\s+/g, ' ').trim().replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                    const typed_type_regexp = new RegExp(Kanban.helpers.removeAccents(normalized_text), "i");
                    const result = [];

                    for (const item of column.items) {

                        const properties_to_search = [category];

                        let should_add_item = false;

                        for (const property of properties_to_search) {
                            const value = String(item[property]).replace(/\s+/g, ' ').trim();

                            if (Kanban.helpers.removeAccents(value).match(typed_type_regexp)) {
                                should_add_item = true;
                                break;
                            }
                        }

                        if (should_add_item) {
                            result.push(item);
                        }
                    }

                    return {
                        list_type: column.listType,
                        items: result,
                        event: "process",
                        status: 200
                    };
                },
                all() {
                    Kanban.attendance.removeAll();
                    Kanban.attendance.resetLimitOfTwo();
                    filter.general.tooltips.search.create();
                }
            }
        }
    }
}

document.getElementById("search-user").addEventListener("keyup", filter.users.keyup);
document.querySelector(".kanban .user-window .all-users").addEventListener("click", filter.users.search.all);
document.querySelector(".kanban .user-window .input-search input").addEventListener("keyup", filter.users.input);

document.getElementById("clear-search-user").addEventListener("click", filter.users.search.clear);
document.getElementById("general-search").addEventListener("keyup", filter.general.input);
document.getElementById("clear-search-general").addEventListener("mousedown", filter.general.icon.clear);
document.getElementById("battery-filter-selector").addEventListener("click", () => { if (filter.general.tooltips.option.isElementSelected()) { filter.general.tooltips.deselectedFilter(); filter.default(); return; } filter.general.tooltips.option.show(batteryFilterInfo.battery); });
document.getElementById("filter-selector-user").addEventListener("click", () => filter.general.tooltips.option.show(batteryFilterInfo.departament, 'departament'));


const checkPresenceStatus = (json) => {
    const users = document.querySelectorAll(".list-users .user-item");
    const { key_remote_id, type } = json;

    const addOnlineClass = (user) => {
        user.firstElementChild.lastElementChild.classList.add("online");
        user.lastElementChild.lastElementChild.innerHTML = GLOBAL_LANG.kanban_attendance_user_window_online;
    };

    const removeOnlineClass = (user) => {
        user.firstElementChild.lastElementChild.classList.remove("online");
        user.lastElementChild.lastElementChild.innerHTML = GLOBAL_LANG.kanban_attendance_user_window_last_seen + Kanban.helpers.timezone(Kanban.helpers.currentDateTime());
    };

    const updateUserStatus = () => {
        for (const user of users) {
            if (user.id.includes(key_remote_id)) {
                if (type === "available") {
                    addOnlineClass(user);
                } else if (type === "unavailable") {
                    removeOnlineClass(user);
                }
            }
        }
    };

    updateUserStatus();
};


const kanbanStart = (json) => {

    const processKanban = () => {
        const object = Kanban.attendance.setId(json);
        Kanban.helpers.removeLoad();
        Kanban.cache.add(object);
        Kanban.process.cards(object);
        Kanban.attendance.count("total");
        Kanban.helpers.clearBatteryFilter();
    }

    if (json.event === "process") {
        if (json.listType === "Users") {
            filter.users.list(json.items);
        } else {
            setTimeout(processKanban, 500);
        }
    }
}


const handleCardAction = (json) => {

    if (json.event == "close" && json.transfer_user == userToken.replace("kanban-", "")) return

    json.id = Kanban.helpers.uuid(10000000);

    const { key_remote_id, user_key_remote_id, event } = json;
    const prepend = true;
    const process = false;

    const isNewCard = (event) => event === "Add";

    const organizeTheColumns = (event) => {
        if (json.Cmd != "WaitList") {
            const columnMapping = {
                "Add": "InService",
                "attendance": "InService",
                "wait": "OnHold",
                "close": "Closed",
            };
            return columnMapping[event] || "";
        } else return "Waiting";
    };

    const isUserSelected = () => {
        const selectedUser = document.querySelector("#list__users .user-item.selected-user");
        return selectedUser ? selectedUser.id : null;
    };

    const itsTheSameUser = (found_id, user_key_remote_id) => {
        return found_id === user_key_remote_id;
    };

    const itsTheSameFilter = (contact) => {
        const filter_selected = document.querySelector("#general-search").getAttribute("placeholder");
        const filter_category = localStorage.getItem("batteryFilterCategory");
        let same_filter = false;

        switch (filter_category) {
            case "labels_name":
                if (contact.labels_name.includes(filter_selected))
                    same_filter = true;

                break;
            case "name_channel":
                if (contact.name_channel.includes(filter_selected))
                    same_filter = true;

                break;
            case "departament":
                if (contact.departament.includes(filter_selected))
                    same_filter = true;

                break;
            default:
                break;
        }

        return same_filter;
    };

    const itsTheSameSearch = (general) => {
        const search_general = document.getElementById("general-search").value.trim();

        return ['labels_name', 'name_channel', 'departament', 'full_name'].some(
            key => general[key]?.includes(search_general)
        );
    };

    const removeCard = (type_list) => {
        if (type_list != "Closed") {
            $("#column__InService #" + json.key_remote_id)?.remove();
            $("#column__OnHold #" + json.key_remote_id)?.remove();
            $("#column__Waiting #" + json.key_remote_id)?.remove();
        }
    }

    const createCard = (data) => {

        if (battery_filter_exists && is_user_selected) {


            if (itsTheSameFilter(data) && its_the_same_user) {
                Kanban.cache.update(data, type_list, "searchBattery");
                Kanban.cache.update(data, type_list, "searchUser");
                Kanban.attendance.create(data, type_list, process, prepend);
                Kanban.attendance.count("combineUserAndBattery");
            }
        } else if (general_filter_exists && is_user_selected) {

            if (its_the_same_user) {
                Kanban.cache.update(data, type_list, "searchGeneral");
                Kanban.cache.update(data, type_list, "searchUser");
                Kanban.attendance.create(data, type_list, process, prepend);
                Kanban.attendance.count("combineUserAndGeneral");
            }
        } else if (battery_filter_exists) {

            if (itsTheSameFilter(data)) {
                Kanban.cache.update(data, type_list, "searchBattery");
                Kanban.attendance.create(data, type_list, process, prepend);
                Kanban.attendance.count("searchBattery");
            }
        } else if (general_filter_exists) {
            if (itsTheSameSearch(data)) {
                Kanban.cache.update(data, type_list, "searchGeneral");
                Kanban.attendance.create(data, type_list, process, prepend);
                Kanban.attendance.count("searchGeneral");
            }
        } else if (is_user_selected) {

            if (its_the_same_user) {
                Kanban.attendance.create(data, type_list, process, prepend);
                Kanban.cache.update(data, type_list, "searchUser");
                Kanban.attendance.count("searchUser");
            }
        } else {
            Kanban.attendance.create(data, type_list, process, prepend);
            Kanban.attendance.count("total");
        }
    };

    const type_list = organizeTheColumns(event);
    const is_user_selected = isUserSelected();
    const battery_filter_exists = localStorage.getItem("batteryFilterCategory") == 'null' ? false : true;
    const general_filter_exists = document.getElementById("general-search").value.trim() != '' ? true : false;
    const its_the_same_user = itsTheSameUser(is_user_selected, user_key_remote_id);

    removeCard(type_list);

    if (isNewCard(event)) {
        Kanban.cache.delete(key_remote_id);
        Kanban.cache.update(json, type_list, "total");
        createCard(json);
    } else {
        const item = Kanban.cache.delete(key_remote_id);
        Kanban.cache.update(item, type_list, "total");
        Kanban.attendance.remove(key_remote_id, user_key_remote_id);
        createCard(item);
    }
};


const getBatteryFilterOptions = () => {
    return [
        {
            name: GLOBAL_LANG.kanban_attendance_filter_select_channel,
            type: "channel",
            icon: document.location.origin + "/assets/icons/kanban/channel.svg",
            show_icon: true
        },
        {
            name: GLOBAL_LANG.kanban_attendance_filter_select_label,
            type: "tag",
            icon: document.location.origin + "/assets/icons/kanban/tag.svg",
            show_icon: true
        },
        {
            name: GLOBAL_LANG.kanban_attendance_filter_select_department,
            type: "departament",
            icon: document.location.origin + "/assets/icons/kanban/departament.svg",
            show_icon: true
        }
    ];
};


const label = function (json) {
    if (json.event == "Add") {
        Kanban.cache.updateTags(json);
        Kanban.attendance.updateCard(json);
        Kanban.attendance.updateChatOpen(json);
    }

    if (json.event == "Remove") {
        Kanban.cache.updateTags(json);
        Kanban.attendance.removeTags(json.key_remote_id);
        Kanban.attendance.updateChatOpen(json);
    }
}


const getFilterInfo = (data) => {
    data.items.label.forEach(elm => elm.type = "sub-tag");
    data.items.channel.forEach(elm => elm.type = "sub-channel");
    data.items.departament.forEach(elm => elm.type = "sub-departament");

    batteryFilterInfo.battery = getBatteryFilterOptions();
    batteryFilterInfo.labels = data.items.label;
    batteryFilterInfo.channel = data.items.channel;
    batteryFilterInfo.departament = data.items.departament;
};


const checkConnection = (json) => {
    if (json.status == 1000) {
        if (json.reason == "connection replace") {
            window.location.href = window.location.origin + '/account/login';
        }
    }
}


const TAWebsocket = () => {

    try {
        socket = new WebSocket(host);

        socket.onopen = function () {
            const data = {
                Cmd: "login",
                account: userToken,
                pw: userToken,
                WebSessionToken: webSessionToken,
                version: appVersion
            };

            socket.send(JSON.stringify(data));
        };

        socket.onmessage = function (msg) {

            const json = JSON.parse(msg.data);

            if (json.status == 200) {
                switch (json.Cmd) {
                    case "kanban":
                        kanbanStart(json);
                        break;
                    case "queryPresence":
                        checkPresenceStatus(json);
                        break;
                    case "Chat":
                        handleCardAction(json);
                        break;
                    case "batteryFilterInfo":
                        getFilterInfo(json);
                        break;
                    case "blocklist":
                        $("#" + json.key_remote_id).remove();
                        break;
                    case 'Wait':
                        switch (json.event) {
                            case 'move':
                                putInService(json.key_remote_id);
                                break;
                        }
                    case "WaitList":
                        handleCardAction(json);
                        break;
                    case 'Ack':
                        // processAck(json);
                        break;
                    case 'Msg':
                        Kanban.message.process.msg(json);
                        switch (json.event) {
                            case 'Reaction':
                                Kanban.message.init.dist.verifyReaction(json);
                                break;
                        }
                        break;
                    case "Message":
                        Kanban.message.process.start(json);
                        break;
                    case "queryUsersGroups":
                        Kanban.chat.addGroupsToSelect(json);
                        break;
                    case 'ProfilePictureThumb':
                        break;
                    case "queryUsers":
                        Kanban.chat.addUsersToSelect(json);
                        break;
                    case "Revoke":
                        Kanban.message.init.dist.removeMsg(json);
                        break;
                    case "label":
                        label(json);
                        break;
                    case "Conn":
                        switch (json.type) {
                            case "disconnect":
                                $(".conn").show();
                                socket.close();
                                break;
                            case 'old_version':
                                alert(GLOBAL_LANG.kanban_attendance_message_old_version);
                                break;
                            case "close":
                                socket.close();
                                window.location = "account/logoff";
                                break;
                            default:
                                break;
                        }
                        break;
                    default:
                        break;
                }
            }

            checkConnection(json);
        };

        socket.onclose = function () {
            console.log("error onclose")
            setTimeout(() => TAWebsocket(), 5000);
        };

    } catch (exception) {
        console.log("error exception");
        setTimeout(() => TAWebsocket(), 5000);
    }
}

const getTextColor = (color) => {
    let r, g, b;

    if (color.startsWith("#")) {
        r = parseInt(color.substring(1, 3), 16);
        g = parseInt(color.substring(3, 5), 16);
        b = parseInt(color.substring(5, 7), 16);
    } else if (color.startsWith("rgb")) {
        let rgbValues = color.match(/\d+/g);
        r = parseInt(rgbValues[0], 10);
        g = parseInt(rgbValues[1], 10);
        b = parseInt(rgbValues[2], 10);
    } else {
        return "#000000";
    }

    // Calculo da luminância relativa
    let luminance = 0.2126 * r + 0.7152 * g + 0.0722 * b;

    return luminance > 186 ? "#000000" : "#b6c2cf";
}

setInterval(() => {
    Kanban.collumn.recompose("column__Waiting");
    Kanban.collumn.recompose("column__InService");
    Kanban.collumn.recompose("column__OnHold");
    Kanban.collumn.recompose("column__Closed");
}, 20000);

TAWebsocket();

function openFlowModalKanban({ title, evaluationNote, comment }) {

    function formatStars(starsText) {
        if (!starsText || starsText === "N/A") {
            return '<span style="color: #dee2e6;">☆☆☆☆☆</span>';
        }

        const filledStars = (starsText.match(/[★⭐]/g) || []).length;
        const emptyStars = 5 - filledStars;

        let starsHtml = '';

        for (let i = 0; i < filledStars; i++) {
            starsHtml += '<span style="color: #FFD700;">★</span>';
        }

        for (let i = 0; i < emptyStars; i++) {
            starsHtml += '<span style="color: #dee2e6;">☆</span>';
        }
        return starsHtml;
    }

    const modal = document.createElement("div");
    modal.className = "flow-modal";
    Object.assign(modal.style, {
        position: "fixed",
        top: "0",
        left: "0",
        width: "100vw",
        height: "100vh",
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        zIndex: "999999",
        backdropFilter: "blur(3px)",
        animation: "fadeIn 0.3s ease"
    });

    const content = document.createElement("div");
    content.className = "flow-modal-body";

    content.innerHTML = `
        <style>
            
        </style>
        
        <button class="modal-close-x" aria-label="Fechar">×</button>
        
        <h3 class="flow-modal-title">
            ${GLOBAL_LANG.kanban_modal_template_flow_message_title_modal}
        </h3>
        
        <div class="modal-section">
            <div class="modal-label">⭐ ${GLOBAL_LANG.kanban_modal_assessment}</div>
            <div class="modal-value"><span class="modal-stars">${formatStars(evaluationNote)}</span></div>
        </div>
        
        <div class="modal-section">
            <div class="modal-label">💬 ${GLOBAL_LANG.kanban_modal_comment}</div>
            <div class="modal-value">${comment}</div>
        </div>
    `;

    content.querySelector(".modal-close-x").onclick = () => {
        modal.style.animation = "fadeOut 0.2s ease";
        setTimeout(() => modal.remove(), 200);
    };

    modal.onclick = (e) => {
        if (e.target === modal) {
            modal.style.animation = "fadeOut 0.2s ease";
            setTimeout(() => modal.remove(), 200);
        }
    };

    modal.appendChild(content);
    document.body.appendChild(modal);
}
