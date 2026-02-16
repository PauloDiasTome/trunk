"use strict";

var socket;

const host = "wss://app.talkall.com.br:5000";
const userToken = "kanban-communication-" + localStorage.getItem("userToken");
const webSessionToken = localStorage.getItem("WebSessionToken");
const appVersion = '2.2027.17';
const date = new Date();
const Components = new ComponentsDom();

let kanbanCache = new Object();
let selectedCampaign = new Object();

const calendar = {
    weeks: [
        GLOBAL_LANG.kanban_communication_week_day_sunday, GLOBAL_LANG.kanban_communication_week_day_monday,
        GLOBAL_LANG.kanban_communication_week_day_tuesday, GLOBAL_LANG.kanban_communication_week_day_wednesday,
        GLOBAL_LANG.kanban_communication_week_day_thursday, GLOBAL_LANG.kanban_communication_week_day_friday,
        GLOBAL_LANG.kanban_communication_week_day_saturday
    ],
    months: [
        GLOBAL_LANG.kanban_communication_month_january, GLOBAL_LANG.kanban_communication_month_february,
        GLOBAL_LANG.kanban_communication_month_march, GLOBAL_LANG.kanban_communication_month_april,
        GLOBAL_LANG.kanban_communication_month_may, GLOBAL_LANG.kanban_communication_month_june,
        GLOBAL_LANG.kanban_communication_month_july, GLOBAL_LANG.kanban_communication_month_august,
        GLOBAL_LANG.kanban_communication_month_september, GLOBAL_LANG.kanban_communication_month_october,
        GLOBAL_LANG.kanban_communication_month_november, GLOBAL_LANG.kanban_communication_month_december
    ]
}

document.getElementById("current_date").innerHTML = calendar.weeks[date.getDay()] + ", " + date.getDate() + " de " + calendar.months[date.getMonth()] + " de " + date.getFullYear();

const colorPalette = {
    ligth: ["#F9423A", "#FF6838", "#FFA800", "#732CE7", "#EE32D0", "#F11C82", "#4434FF", "#2263D3", "#2996FB", "#27C9BF", "#27BD51", "#BAC636", "#000000", "#798394", "#FFFFFF"],
    dark: ["#94191C", "#A93000", "#975B00", "#4A1C8A", "#981F85", "#971453", "#21188D", "#1B277C", "#134A9F", "#0E4F4B", "#105223", "#5F651B", "#000000", "#798394", "#FFFFFF"]
};

const checkEditModalEvent = new Event('enableEditModal');
const checkEditSaveEvent = new Event('enableEditSave');
const getLastBroadcastLog = new Event('getLastBroadcastLog');
const checkPartialCampaign = new Event('checkPartialCampaign');

const Kanban = {
    campaign: {
        create(json, column, realTime = false) {

            const campaign = Components.div({ class: "campaign", id: json.token, customAttribute: ["id_channel", json.id_channel] });
            const container = Components.div({ class: "container-card" });
            const box_left = Components.div({ class: "box-left" });

            switch (json.media_type) {
                case 1:
                    box_left.style.display = "none";
                    break;
                case 27:
                    switch (json.header_type) {
                        case 1:
                        case null:
                            box_left.style.display = "none";
                            break;
                        default:
                            box_left.appendChild(Components.img({ class: "broadcast-media", style: "display:none" }));
                            box_left.appendChild(Kanban.components.loading("1.5rem"));
                            break;
                    }
                    break;
                default:
                    box_left.appendChild(Components.img({ class: "broadcast-media", style: "display:none" }));
                    box_left.appendChild(Kanban.components.loading("1.5rem"));
                    break;
            }

            const info = Components.div({ class: "information" });
            const box_right = Components.div({ class: "box-right" });
            const header = Components.div({ class: "header-info" });

            const campaign_type = Components.span({ text: Kanban.campaign.typeWriting(json.broadcast_type) });

            header.appendChild(campaign_type);
            header.appendChild(Components.span({ text: json.formated_schedule_time }));

            const title = Components.div({ class: "title" });
            const text = Components.textarea({ class: "title", text: json.title, rows: 2 });
            text.addEventListener("keydown", () => Kanban.helpers.resizeTexAreaTyping(text));
            text.addEventListener("keyup", () => Kanban.helpers.resizeTexAreaTyping(text));
            text.setAttribute("readonly", true);

            title.appendChild(text);

            let collections = "";

            if (json.broadcast_type != "business_status_text" && json.broadcast_type != "business_status_img") {
                collections = Kanban.components.ackBroadcastCollection(json, column);
            } else {
                collections = Kanban.components.ackStatusCollection(json, column);
            }

            const footer = Components.div({ class: "footer-card" });
            const main_bottom = Components.div({ class: "main-bottom" });
            const channel_box_img = Components.div({ class: "box-img" });

            channel_box_img.appendChild(Components.img({ class: "channel-img", style: "display:none" }));
            channel_box_img.appendChild(Kanban.components.loading("0.65rem"));

            const channel_info = Components.div({ class: "channel-info" });
            channel_info.appendChild(Components.span({ text: json.channel_name }));
            channel_info.appendChild(Components.span({ text: json.channel }));

            info.appendChild(box_left);
            info.appendChild(box_right);

            box_right.appendChild(header);
            box_right.appendChild(title);
            box_right.appendChild(collections);

            main_bottom.appendChild(channel_box_img);
            main_bottom.appendChild(channel_info);

            footer.appendChild(main_bottom);

            container.appendChild(info);
            campaign.appendChild(container);
            campaign.appendChild(footer);

            if (column === "waiting") {
                document.getElementById("column__waiting").appendChild(campaign);
                if (realTime) {
                    this.move(json.token, "waiting");
                    Kanban.process.addImages({ waiting: [json] }, "card");
                }
            }

            if (column === "ongoing")
                document.getElementById("column__ongoing").appendChild(campaign);

            if (column === "paused")
                document.getElementById("column__paused").appendChild(campaign);

            if (column === "complete")
                document.getElementById("column__complete").appendChild(campaign);

            campaign.addEventListener("click", Kanban.campaign.open);

            title.firstChild.innerText = Kanban.helpers.removeCharacters(title.firstChild, title.parentElement, 2);
            header.firstChild.innerText = Kanban.helpers.removeCharacters(header.firstChild, header.parentElement, 1);
        },
        count() {

            document.getElementById("waiting_count").innerHTML = document.getElementById("column__waiting").children.length - document.getElementById("column__waiting").getElementsByClassName("hidden").length;
            document.getElementById("ongoing_count").innerHTML = document.getElementById("column__ongoing").children.length - document.getElementById("column__ongoing").getElementsByClassName("hidden").length;
            document.getElementById("paused_count").innerHTML = document.getElementById("column__paused").children.length - document.getElementById("column__paused").getElementsByClassName("hidden").length;
            document.getElementById("complete_count").innerHTML = document.getElementById("column__complete").children.length - document.getElementById("column__complete").getElementsByClassName("hidden").length;
        },
        move(token, column) {

            let campaign = document.getElementById(token);
            let column_origin = campaign.parentNode.id.split("__")[1];
            let column_destiny = column;
            let previous_token = Kanban.process.moveInKanbanCache(token, column_origin, column_destiny);

            Kanban.process.changeStatusInCache(token, column_destiny);
            Kanban.process.addCampaignInSort(previous_token, campaign, column_destiny);
            Kanban.campaign.count();
        },
        open(token) {

            [...document.querySelectorAll("#modal-campaign .modal-body .content-campaign")].map(elm => elm.remove());
            [...document.querySelectorAll("#modal-campaign .modal-footer .btn-modal-kanban")].map(elm => elm.remove());

            let id = '';
            let column = '';
            let json = '';

            if (token.length > 0) {
                id = token;
                json = selectedCampaign;
                column = 'column__waiting';
            } else {
                id = this.id;
                column = this.parentNode.id;
                json = Kanban.campaign.getSelectedElement(column, id)[0];
            }

            Kanban.campaign.select(json);

            const content_campaign = Components.div({ class: "content-campaign" });

            if (json.is_wa_broadcast === 1 || json.is_wa_community === 1 || json.is_wa_channel === 1) {

                const uui = Kanban.helpers.uuid(100000);
                const box_left = Components.div({ class: "box-left" });
                const content_info = Components.div({ class: "content-info" });
                const box_preview = Components.div({ class: "box-preview", id: uui });
                const box_info = Components.div({ class: "group-info" });

                if (json.media_type == 33) {
                    const poll_message = JSON.parse(json.data);

                    box_preview.style = "height: 100%;";
                    box_info.style = "height: 100%; display: flex; flex-direction: column;";

                    box_info.appendChild(Kanban.campaign.updatePoll(poll_message, true));
                    box_preview.appendChild(box_info);
                } else {

                    const icon_edit = Kanban.components.iconEdit();
                    icon_edit.style.float = "right";
                    icon_edit.style.marginRight = "0px";
                    icon_edit.style.marginTop = "10px";
                    icon_edit.style.cursor = "pointer";

                    const description = Components.textarea({ class: "desc-campaign weight-six-hundred", text: json.data, rows: 1, disabled: true, placeholder: GLOBAL_LANG.kanban_communication_textarea_placeholder });
                    description.addEventListener("keydown", () => Kanban.helpers.resizeTexAreaTyping(description));
                    description.addEventListener("keyup", () => Kanban.helpers.resizeTexAreaTyping(description));

                    if (json.media_type === 4 || json.media_type === 5) {
                        Kanban.helpers.preview(json.media_url, json.media_type, uui);
                        box_preview.appendChild(Components.img({ src: document.location.origin + "/assets/img/loads/loading_2.gif", style: "padding:4rem 70px" }));
                    } else
                        box_preview.appendChild(Components.img({ src: json.media_url == null ? document.location.origin + "/assets/icons/kanban/preview_img.svg" : json.media_url }));

                    box_info.appendChild(icon_edit);
                    box_info.appendChild(description);
                }

                const collections = Kanban.components.ackBroadcastCollection(json, column.split("__")[1]);

                if (json.media_type !== 1) {
                    content_info.appendChild(box_preview);
                }

                if (json.media_type !== 33) {
                    content_info.appendChild(box_info);;
                }

                content_info.appendChild(collections);
                box_left.appendChild(content_info);

                content_campaign.appendChild(box_left);
            }

            if (json.is_wa_status === 1) {

                const box_left = Components.div({ class: "box-left" });
                const content_story = Components.div({ class: "content-story" });
                const box_preview_story = Components.div({ class: "box-preview-story" });

                const icon_edit = Kanban.components.iconEdit();
                icon_edit.src = document.location.origin + "/assets/icons/kanban/edit2.svg";

                const collections = Kanban.components.ackStatusCollection(json, column.split("__")[1]);
                content_story.style.backgroundColor = "#000";

                if (json.media_type === 3 || json.media_type === 5) {
                    var box_info = Components.div({ class: "group-info group-image-info" });

                    icon_edit.style = "position:absolute; bottom:44px; right:10px; z-index: 1; cursor:pointer";
                    box_info.appendChild(Components.textarea({ class: "desc-story weight-six-hundred", text: json.data, rows: 1, disabled: true, maxLength: 700 }));
                    box_info.appendChild(icon_edit);
                    box_info.appendChild(Components.div({ class: "box-shadow" }));

                    if (json.media_type === 3)
                        box_preview_story.appendChild(Components.img({ src: json.media_url == null ? document.location.origin + "/assets/icons/kanban/preview_img.svg" : json.media_url }));
                    else
                        box_preview_story.appendChild(Components.video({ src: json.media_url == null ? document.location.origin + "/assets/icons/kanban/preview_img.svg" : json.media_url }));

                    content_story.appendChild(box_preview_story);

                } else {

                    var box_info = Components.div({ class: "group-info group-text-info" });
                    icon_edit.style = "z-index: 1; cursor:pointer";

                    box_info.appendChild(Components.textarea({ class: "desc-story weight-six-hundred", text: json.data, rows: 1, disabled: true, maxLength: 700 }));
                    box_info.appendChild(icon_edit);
                }

                box_info.appendChild(collections);
                content_story.appendChild(box_info);

                box_left.appendChild(content_story);
                content_campaign.appendChild(box_left);
            }

            if (json.is_waba_broadcast === 1) {

                const uui = Kanban.helpers.uuid(100000);
                const box_left = Components.div({ class: "box-left" });
                const template_parameters = json.json_parameters;
                const header_parameter = JSON.parse(template_parameters)[0];

                const box_template = Components.div({ class: "box-template" });
                const content_template = Components.div({ class: "content-template" });

                if (json.broadcast_type === "waba_broadcast_text" && json.header !== null) {
                    const header_template = Components.div({ class: "header-template" });
                    header_template.appendChild(Components.span({ text: json.header, disabled: true }));
                    content_template.appendChild(header_template);
                } else
                    if (json.broadcast_type !== "waba_broadcast_text" && json.broadcast_type !== null && json.broadcast_type !== "") {
                        const header_template = Components.div({ class: "content-preview-template", id: uui });
                        switch (json.broadcast_type) {
                            case "waba_broadcast_img":
                                header_template.appendChild(Components.img({ src: header_parameter.parameters[0].image.link == null ? document.location.origin + "/assets/icons/kanban/preview_img.svg" : header_parameter.parameters[0].image.link }));
                                break;
                            case "waba_broadcast_video":
                                Kanban.helpers.preview(header_parameter.parameters[0].video.link, json.broadcast_type, uui);
                                header_template.appendChild(Components.img({ src: document.location.origin + "/assets/img/loads/loading_2.gif", style: "padding:4rem 70px" }));
                                break;
                            case "waba_broadcast_pdf":
                                Kanban.helpers.preview(header_parameter.parameters[0].document.link, json.broadcast_type, uui);
                                header_template.appendChild(Components.img({ src: document.location.origin + "/assets/img/loads/loading_2.gif", style: "padding:4rem 70px" }));
                                break;
                        }
                        content_template.appendChild(header_template);
                    }

                const body_template = Components.div({ class: "body-template" });
                body_template.appendChild(Components.span({ text: json.text_body, disabled: true }));

                const include_parameters = json.text_body.includes('{{');
                const qty_parameters = json.text_body.split('{{').length - 1;

                if (include_parameters == true) {
                    let body_parameters = [];
                    JSON.parse(template_parameters).forEach(c => { c["type"] == "body" ? body_parameters.push(c["parameters"]) : null });

                    for (let i = 0; i < qty_parameters; i++) {
                        if (body_parameters[0][i]["type"] == "date")
                            body_template.innerHTML = body_template.innerHTML.replace(`{{${(i + 1)}}}`, body_parameters[0][i]["date"]);
                        else if (body_parameters[0][i]["type"] == "currency")
                            body_template.innerHTML = body_template.innerHTML.replace(`{{${(i + 1)}}}`, body_parameters[0][i]["currency"]["amount_1000"]);
                        else
                            body_template.innerHTML = body_template.innerHTML.replace(`{{${(i + 1)}}}`, body_parameters[0][i]["text"]);
                    }

                    content_template.appendChild(body_template);
                } else
                    content_template.appendChild(body_template);

                if (json.text_footer !== null && json.text_footer !== "") {
                    const footer_template = Components.div({ class: "footer-template" });
                    footer_template.appendChild(Components.span({ text: json.text_footer, disabled: true }));
                    content_template.appendChild(footer_template);
                }

                const buttonsView = JSON.parse(json.buttons);

                if (buttonsView != null && buttonsView != "") {
                    if (buttonsView[0].type == "QUICK_REPLY") {
                        const buttons_qty = buttonsView.length;
                        const box_buttons = Components.div({ class: "buttons-quick-reply-container" })

                        switch (buttons_qty) {
                            case 1:
                                box_buttons.appendChild(Components.button({ class: "btn-quick-reply", text: buttonsView[0].text, disabled: true }));
                                break;
                            case 2:
                                box_buttons.appendChild(Components.button({ class: "btn-quick-reply", text: buttonsView[0].text, disabled: true }));
                                box_buttons.appendChild(Components.button({ class: "btn-quick-reply", text: buttonsView[1].text, disabled: true }));
                                break;
                            case 3:
                                box_buttons.appendChild(Components.button({ class: "btn-quick-reply", text: buttonsView[0].text, disabled: true }));
                                box_buttons.appendChild(Components.button({ class: "btn-quick-reply", text: buttonsView[1].text, disabled: true }));
                                box_buttons.appendChild(Components.button({ class: "btn-quick-reply", text: buttonsView[2].text, disabled: true }));
                                break;
                            default:
                                break;
                        }
                        content_template.appendChild(box_buttons);
                    } else {
                        const button_hr = Components.hr({ class: "button-hr", display: "margin: 5px" });
                        const box_phone_url = Components.div({ class: "buttons-phone-url" });

                        for (var i = 0; i < buttonsView.length; i++) {
                            switch (buttonsView[i].type) {
                                case "PHONE_NUMBER":
                                    const button_phone = Components.span({ text: ` ${buttonsView[i].text}` });
                                    const element_phone = Components.i({ class: "fas fa-phone" });
                                    button_phone.prepend(element_phone);
                                    box_phone_url.appendChild(button_phone);
                                    break;
                                case "URL":
                                    const button_url = Components.span({ text: ` ${buttonsView[i].text}` });
                                    const element_url = Components.i({ class: "fas fa-external-link-alt" });
                                    button_url.prepend(element_url);
                                    box_phone_url.appendChild(button_url);
                                    break;
                                default:
                                    break;
                            }
                        }
                        content_template.appendChild(button_hr);
                        content_template.appendChild(box_phone_url);
                    }
                }

                const collections = Kanban.components.ackBroadcastCollection(json, column.split("__")[1]);

                content_template.appendChild(collections);
                box_template.appendChild(content_template);
                box_left.appendChild(box_template);
                content_campaign.appendChild(box_left);
            }

            const box_right = Components.div({ class: "box-right" });
            const content_title = Components.div({ class: "content-title" });

            const group_flex__title = Components.div({ class: "group-flex" });
            const group_info__title = Components.div({ class: "group-info" });

            const campaign_type = Components.span({ text: Kanban.campaign.typeWriting(json.broadcast_type) });
            const campaign_title = Components.textarea({ class: "weight-six-hundred", text: json.title, disabled: true, maxLength: 55, rows: 1 });
            campaign_title.addEventListener("keydown", () => Kanban.helpers.resizeTexAreaTyping(campaign_title));

            group_flex__title.appendChild(campaign_type);
            group_flex__title.appendChild(Kanban.components.iconEdit());
            group_info__title.appendChild(campaign_title);

            content_title.appendChild(group_flex__title);
            content_title.appendChild(group_info__title);


            const content_date = Components.div({ class: "content-date" });
            const group_flex__date = Components.div({ class: "group-flex" });
            const group_info__date = Components.div({ class: "group-info" });

            group_flex__date.appendChild(Components.span({ text: GLOBAL_LANG.kanban_communication_modal_date }));
            group_flex__date.appendChild(Kanban.components.iconEdit());
            group_info__date.appendChild(Components.date({ class: "weight-six-hundred", value: json.formated_schedule_date, disabled: true }));

            content_date.appendChild(group_flex__date);
            content_date.appendChild(group_info__date);


            const content_hour = Components.div({ class: "content-hour" });
            const group_flex__hour = Components.div({ class: "group-flex" });
            const group_info__hour = Components.div({ class: "group-info" });

            group_flex__hour.appendChild(Components.span({ text: GLOBAL_LANG.kanban_communication_modal_hour }));
            group_flex__hour.appendChild(Kanban.components.iconEdit());
            group_info__hour.appendChild(Components.time({ class: "weight-six-hundred", value: json.formated_schedule_time, disabled: true }));

            content_hour.appendChild(group_flex__hour);
            content_hour.appendChild(group_info__hour);


            const content_channel = Components.div({ class: "content-channel" });
            const group_flex__channel = Components.div({ class: "group-flex" });
            const group_info__channel = Components.div({ class: "group-info" });

            const box_channel_image = Components.div({ class: "box-img" });
            const channel_info = Components.div({ class: "channel_info" });

            box_channel_image.appendChild(Components.img({ src: json.channel_picture == "" || json.channel_picture == null ? document.location.origin + "/assets/icons/kanban/img-preview.svg" : json.channel_picture }));
            channel_info.appendChild(Components.span({ text: json.channel_name }));
            channel_info.appendChild(Components.span({ text: json.channel }));

            group_flex__channel.appendChild(Components.span({ text: GLOBAL_LANG.kanban_communication_modal_channel }));
            group_info__channel.appendChild(box_channel_image);
            group_info__channel.appendChild(channel_info);

            content_channel.appendChild(group_flex__channel);
            content_channel.appendChild(group_info__channel);


            const content_status = Components.div({ class: "content-status" });
            const status = Kanban.campaign.status(json);
            content_status.appendChild(status.next().value);
            content_status.appendChild(status.next().value);

            const content_notice = Components.div({ class: "content-notice" });
            document.getElementById("modal-campaign").addEventListener("getLastBroadcastLog", () => {
                if (document.querySelector(".content-notice").firstChild != null)
                    content_notice.firstChild.remove();
                content_notice.appendChild(Kanban.campaign.scheduleLog(json));
            })

            Kanban.campaign.getLastBroadcastLog();

            box_right.appendChild(content_title);
            box_right.appendChild(content_date);
            box_right.appendChild(content_hour);
            box_right.appendChild(content_channel);
            box_right.appendChild(content_status);
            box_right.appendChild(content_notice);

            content_campaign.appendChild(box_right);
            document.querySelector("#modal-campaign .modal-body").appendChild(content_campaign);


            const btn__to_go_back = Components.button({ class: "btn-modal-kanban", id: "btn__to_go_back", text: GLOBAL_LANG.kanban_communication_modal_btn_to_go_back });
            btn__to_go_back.addEventListener("click", Kanban.campaign.close);

            const btn__cancel_campaign = Components.button({ class: "btn-modal-kanban", id: "btn__cancel_campaign", text: GLOBAL_LANG.kanban_communication_modal_btn_cancel_campaign });
            btn__cancel_campaign.addEventListener("click", Kanban.campaign.cancel);

            const btn__send_now = Components.button({ class: "btn-modal-kanban", id: "btn__send_now", text: GLOBAL_LANG.kanban_communication_modal_btn_send_now });
            btn__send_now.addEventListener("click", Kanban.campaign.sendNow);

            const btn__pause = Components.button({ class: "btn-modal-kanban", id: "btn__pause", text: GLOBAL_LANG.kanban_communication_modal_btn_pause });
            btn__pause.addEventListener("click", Kanban.campaign.pause);

            const btn__resume = Components.button({ class: "btn-modal-kanban", id: "btn__resume", text: GLOBAL_LANG.kanban_communication_modal_btn_resume });
            btn__resume.addEventListener("click", Kanban.campaign.resume);

            const btn__view_report = Components.button({ class: "btn-modal-kanban", id: "btn__view_report", text: GLOBAL_LANG.kanban_communication_modal_btn_view_report });
            btn__view_report.addEventListener("click", () => window.open(document.location.origin + "/report/send", "_blank"));

            switch (column) {
                case "column__waiting":
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__to_go_back);
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__cancel_campaign);
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__send_now);
                    break;
                case "column__ongoing":
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__to_go_back);
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__cancel_campaign);
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__pause);

                    if (json.is_wa_community === 1 || json.is_wa_status === 1 || json.is_waba_broadcast === 1 || json.is_wa_channel === 1)
                        document.querySelectorAll("#modal-campaign .modal-footer button").forEach(b => b.remove());

                    break;
                case "column__paused":
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__to_go_back);
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__cancel_campaign);
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__resume);

                    if (json.is_wa_community === 1 || json.is_wa_status === 1 || json.is_waba_broadcast === 1 || json.is_wa_channel === 1)
                        document.querySelectorAll("#modal-campaign .modal-footer button").forEach(b => b.remove());

                    break;
                case "column__complete":
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__to_go_back);
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__view_report);
                    document.querySelector("#modal-campaign .modal-footer").appendChild(btn__view_report);
                    if (json.is_wa_community === 1 || json.is_wa_status === 1 || json.is_waba_broadcast === 1 || json.is_wa_channel === 1)
                        document.querySelectorAll("#modal-campaign .modal-footer button").forEach(b => b.remove());
                    break;
                default:
                    break;
            }

            Kanban.helpers.checkTimestampDifference(selectedCampaign.schedule, "modal");
            document.getElementById("modal-campaign").addEventListener("enableEditModal", Kanban.campaign.showContent);

            document.getElementById("modal-campaign").addEventListener("click", (e) => {
                if (e.target.id === "modal-campaign") {
                    Kanban.campaign.deselect();
                }
            });

            $("#modal-campaign").modal();
            Kanban.campaign.minHeight(json);
            Kanban.campaign.load();
        },
        close() {
            $("#modal-campaign").modal("hide");

            Kanban.campaign.deselect();
            document.querySelector("#modal-campaign .modal-body").style.display = "none";
            document.querySelector("#modal-campaign .modal-footer").style.display = "none";
        },
        showContent() {
            if (!selectedCampaign.enable_edit)
                Kanban.campaign.disableEdition();

            document.querySelectorAll("#content__campaign .loading-img").forEach(elm => elm.remove());
            document.querySelector("#modal-campaign .modal-body").style.display = "block";
            document.querySelector("#modal-campaign .modal-footer").style.display = "flex";

            if (document.querySelector(".modal-body .group-info .desc-story") != null)
                Kanban.helpers.resizeTexArea(document.querySelector(".modal-body .group-info .desc-story"), 9);

            if (document.querySelector(".modal-body .group-info .desc-campaign") != null)
                Kanban.helpers.resizeTexArea(document.querySelector(".modal-body .group-info .desc-campaign"), 6);
        },
        load() {
            document.querySelector("#modal-campaign .modal-body").style.display = "none";
            document.querySelector("#modal-campaign .modal-footer").style.display = "none";

            document.getElementById("content__campaign").appendChild(Components.img(
                {
                    style: "width:50px; left:50%; top:50%; transform:translate(-50%, -50%);  position:absolute;",
                    src: document.location.origin + "/assets/img/loads/loading_2.gif",
                    class: "loading-img",
                }
            ));
        },
        minHeight(json) {
            if (json.is_wa_broadcast === 1)
                document.querySelector("#modal-campaign #content__campaign").style.minHeight = "482.5px";
            if (json.is_wa_community === 1 || json.is_wa_channel === 1)
                document.querySelector("#modal-campaign #content__campaign").style.minHeight = "417px";
            if (json.is_wa_status === 1)
                document.querySelector("#modal-campaign #content__campaign").style.minHeight = "500px";
        },
        edit: {
            enable() {
                Kanban.campaign.edit.recover();

                const setRange = (elm) => {
                    let pos = elm.value.length;
                    elm.setSelectionRange(pos, pos);
                }

                const field = this.parentNode.parentNode.className;

                const group_buttons = Kanban.components.groupButtons();
                group_buttons.appendChild(Kanban.components.btnCancel());
                group_buttons.appendChild(Kanban.components.btnSave());

                switch (field) {
                    case "content-title":

                        var textarea = document.querySelector(".content-title textarea");
                        textarea.disabled = false;
                        textarea.value = selectedCampaign.title;
                        setRange(textarea);
                        textarea.classList.remove("weight-six-hundred");
                        textarea.focus();

                        this.parentNode.parentNode.childNodes[1].appendChild(group_buttons);
                        break;

                    case "content-date":
                        var date = document.querySelector(".content-date input");
                        date.disabled = false;
                        date.value = selectedCampaign.formated_schedule_date;
                        date.classList.remove("weight-six-hundred");
                        date.focus();

                        this.parentNode.parentNode.childNodes[1].appendChild(group_buttons);
                        break;

                    case "content-hour":
                        var hour = document.querySelector(".content-hour input");
                        hour.disabled = false;
                        hour.value = selectedCampaign.formated_schedule_time;
                        hour.classList.remove("weight-six-hundred");
                        hour.focus();

                        this.parentNode.parentNode.childNodes[1].appendChild(group_buttons);
                        break;

                    case "content-info":
                        var textarea = document.querySelector(".content-info textarea");
                        textarea.disabled = false;
                        textarea.value = selectedCampaign.data;
                        setRange(textarea);
                        textarea.classList.remove("weight-six-hundred");
                        textarea.focus();

                        this.parentNode.appendChild(group_buttons);
                        break;

                    case "content-story":
                        var textarea = document.querySelector(".content-story textarea");
                        textarea.disabled = false;
                        textarea.value = selectedCampaign.data;
                        setRange(textarea);
                        textarea.classList.remove("weight-six-hundred");
                        textarea.focus();

                        group_buttons.style = "position:absolute;right:10px;bottom:8px";
                        this.parentNode.appendChild(group_buttons);
                        break;
                    default:
                        break;
                }

                this.style.display = "none";
            },
            save() {
                const button = this;
                document.getElementById("modal-campaign").addEventListener("enableEditSave", Kanban.campaign.edit.validateFields(button));
                Kanban.helpers.checkTimestampDifference(selectedCampaign.schedule, "save");
            },
            cancel() {

                [...document.querySelectorAll(".modal-body .group-buttons")].map(elm => elm.remove());
                [...document.querySelectorAll(".modal-body .icon-edit")].map(elm => elm.style.display = "inline-block");

                [...document.querySelectorAll(".modal-body .group-info input")].map(elm => elm.classList.add("weight-six-hundred"));
                [...document.querySelectorAll(".modal-body .group-info input")].map(elm => elm.disabled = true);

                [...document.querySelectorAll(".modal-body .group-info textarea")].map(elm => elm.disabled = true);
                [...document.querySelectorAll(".modal-body .group-info textarea")].map(elm => elm.classList.add("weight-six-hundred"));

                if (document.querySelector(".desc-story") != null) document.querySelector(".desc-story").scrollTop = 0;
                if (document.querySelector(".desc-campaign") != null) document.querySelector(".desc-campaign").scrollTop = 0;

                document.querySelectorAll(".modal-body .group-info textarea").forEach(element => {
                    Kanban.helpers.resizeTexAreaTyping(element);
                });
            },
            recover() {
                if (document.querySelector(".modal-body .content-title .group-info textarea") != null) document.querySelector(".modal-body .content-title .group-info textarea").value = selectedCampaign.title;
                if (document.querySelector(".modal-body .content-date .group-info input") != null) document.querySelector(".modal-body .content-date .group-info input").value = selectedCampaign.formated_schedule_date;
                if (document.querySelector(".modal-body .content-hour .group-info input") != null) document.querySelector(".modal-body .content-hour .group-info input").value = selectedCampaign.formated_schedule_time;
                if (document.querySelector(".modal-body .content-info .group-info textarea") != null) document.querySelector(".modal-body .content-info .group-info textarea").value = selectedCampaign.data;
                if (document.querySelector(".modal-body .content-story .group-info textarea") != null) document.querySelector(".modal-body .content-story .group-info textarea").value = selectedCampaign.data;

                Kanban.campaign.edit.cancel();
            },
            validateFields(button) {

                if (!selectedCampaign.enable_edit) {

                    Kanban.campaign.alert.fire({
                        "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                        "message": selectedCampaign.status != 3 ? GLOBAL_LANG.kanban_communication_edit_campaign_error_status : GLOBAL_LANG.kanban_communication_validation_timestamp_diff,
                        "type": "info-default"
                    }).then(res => {
                        if (res == "close") {
                            Kanban.campaign.alert.close();
                        }
                    });

                    Kanban.campaign.disableEdition();
                    return;
                }

                let validate = true;

                const field = button.parentNode.parentNode.parentNode.className;

                const data_to_save = {
                    db_column: "",
                    data: ""
                };

                switch (field) {
                    case "content-title":

                        data_to_save.db_column = "title";
                        data_to_save.data = button.parentNode.parentNode.firstElementChild.value;

                        if (!emptyText(button.parentNode.parentNode.firstElementChild.value)) {
                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_empty_field,
                                "type": "info-default"
                            }).then(res => {
                                if (res == "close") {
                                    Kanban.campaign.alert.close();
                                    validate = false;
                                    button.parentNode.parentNode.firstElementChild.focus();
                                }
                            });
                            return;
                        }

                        if (!min_length(button.parentNode.parentNode.firstElementChild.value, 3)) {
                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_min_length,
                                "type": "info-default"
                            }).then(res => {
                                if (res == "close") {
                                    Kanban.campaign.alert.close();
                                    validate = false;
                                    button.parentNode.parentNode.firstElementChild.focus();
                                }
                            });
                            return;
                        }

                        if (!max_length(button.parentNode.parentNode.firstElementChild.value, 55)) {
                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_max_length,
                                "type": "info-default"
                            }).then(res => {
                                if (res == "close") {
                                    Kanban.campaign.alert.close();
                                    validate = false;
                                    button.parentNode.parentNode.firstElementChild.focus();
                                }
                            });
                            return;
                        }

                        if (validate) {
                            Kanban.campaign.save(data_to_save);
                            Kanban.campaign.edit.cancel();
                        }

                        break;
                    case "content-date":

                        var input_date = button.parentNode.parentNode.firstElementChild.value;

                        var [year, month, day] = input_date.split("-");
                        var [hour, minute] = selectedCampaign.formated_schedule_time.split(":");
                        var timezone = selectedCampaign.timezone;

                        var sign = timezone[0] === "-" ? 1 : -1;
                        var hours_offset = parseInt(timezone.substring(1, 3), 10);
                        var minutes_offset = parseInt(timezone.substring(4, 6), 10);
                        var time_offset = sign * (hours_offset * 60 + minutes_offset) * 60;

                        var utc_timestamp = new Date(Date.UTC(year, month - 1, day, hour, minute, 0)).getTime() / 1000;
                        var timestamp_to_save = utc_timestamp + time_offset;

                        data_to_save.db_column = "schedule";
                        data_to_save.data = timestamp_to_save;
                        data_to_save.formated_schedule_date = input_date;
                        data_to_save.formated_schedule_time = selectedCampaign.formated_schedule_time;
                        data_to_save.remove_from_kanban = false;

                        if (timestamp_to_save < selectedCampaign.schedule) {

                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_lower_date,
                                "type": "info-default"
                            }).then(res => {
                                if (res == "close") {
                                    Kanban.campaign.alert.close();
                                }
                            });
                            input_date = selectedCampaign.formated_schedule_date;

                            return;

                        } else if (timestamp_to_save > selectedCampaign.schedule) {

                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_remove_from_kanban,
                                "type": "danger"
                            }).then(res => {
                                if (res == "no") {
                                    Kanban.campaign.alert.close();
                                    return;
                                } else {
                                    Kanban.campaign.alert.close();
                                    data_to_save.remove_from_kanban = true;
                                    if (validate) {
                                        Kanban.campaign.save(data_to_save);
                                        Kanban.campaign.edit.cancel();
                                        $("#modal-campaign").modal("hide");
                                    }
                                }
                            });

                        } else if (timestamp_to_save === selectedCampaign.schedule) {
                            if (validate) {
                                Kanban.campaign.save(data_to_save);
                                Kanban.campaign.edit.cancel();
                            }
                        }
                        break;
                    case "content-hour":

                        var input_time = button.parentNode.parentNode.firstElementChild.value;

                        var [year, month, day] = selectedCampaign.formated_schedule_date.split("-");
                        var [hour, minute] = input_time.split(":");
                        var timezone = selectedCampaign.timezone;

                        var sign = timezone[0] === "-" ? 1 : -1;
                        var hours_offset = parseInt(timezone.substring(1, 3), 10);
                        var minutes_offset = parseInt(timezone.substring(4, 6), 10);
                        var time_offset = sign * (hours_offset * 60 + minutes_offset) * 60;

                        var utc_timestamp = new Date(Date.UTC(year, month - 1, day, hour, minute, 0)).getTime() / 1000;
                        var timestamp_to_save = utc_timestamp + time_offset;

                        var difference = timestamp_to_save - selectedCampaign.current_date;

                        var days_difference = Math.floor(difference / 60 / 60 / 24);
                        difference -= days_difference * 60 * 60 * 24

                        var hours_difference = Math.floor(difference / 60 / 60);
                        difference -= hours_difference * 60 * 60

                        var minutes_difference = Math.floor(difference / 60);
                        difference -= minutes_difference * 60

                        if (days_difference < 0 || hours_difference < 1) {

                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_hour,
                                "type": "info-default"
                            }).then(res => {
                                if (res == "close") {
                                    Kanban.campaign.alert.close();
                                    validate = false;
                                }
                            });
                            return;
                        }

                        if (selectedCampaign.is_wa_broadcast === 1)
                            data_to_save.partial_campaign_validation = true;

                        data_to_save.db_column = "schedule";
                        data_to_save.data = timestamp_to_save;
                        data_to_save.formated_schedule_date = selectedCampaign.formated_schedule_date;
                        data_to_save.formated_schedule_time = input_time;

                        if (validate) {
                            Kanban.campaign.save(data_to_save);
                            Kanban.campaign.edit.cancel();
                        }

                        break;
                    case "content-info":

                        data_to_save.db_column = "data";
                        data_to_save.data = button.parentNode.parentNode.children[1].value;

                        if (!emptyText(button.parentNode.parentNode.children[1].value)) {
                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_empty_field,
                                "type": "info-default"
                            }).then(res => {
                                if (res == "close") {
                                    Kanban.campaign.alert.close();
                                    validate = false;
                                    button.parentNode.parentNode.children[1].focus();
                                }
                            });
                            return;
                        }

                        if (!min_length(button.parentNode.parentNode.children[1].value, 3)) {
                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_min_length,
                                "type": "info-default"
                            }).then(res => {
                                if (res == "close") {
                                    Kanban.campaign.alert.close();
                                    validate = false;
                                    button.parentNode.parentNode.children[1].focus();
                                }
                            });
                            return;
                        }

                        if (!max_length(button.parentNode.parentNode.children[1].value, 1054)) {
                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_max_length,
                                "type": "info-default"
                            }).then(res => {
                                if (res == "close") {
                                    Kanban.campaign.alert.close();
                                    validate = false;
                                    button.parentNode.parentNode.children[1].focus();
                                }
                            });
                            return;
                        }

                        if (validate) {
                            Kanban.campaign.save(data_to_save);
                            Kanban.campaign.edit.cancel();
                        }
                        break;
                    case "content-story":

                        data_to_save.db_column = "data";
                        data_to_save.data = button.parentNode.parentNode.children[0].value;

                        if (!emptyText(button.parentNode.parentNode.children[0].value)) {
                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_empty_field,
                                "type": "info-default"
                            }).then(res => {
                                if (res == "close") {
                                    Kanban.campaign.alert.close();
                                    validate = false;
                                    button.parentNode.parentNode.children[2].focus();
                                }
                            });
                            return;
                        }

                        if (!min_length(button.parentNode.parentNode.children[0].value, 3)) {
                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_min_length,
                                "type": "info-default"
                            }).then(res => {
                                if (res == "close") {
                                    Kanban.campaign.alert.close();
                                    validate = false;
                                    button.parentNode.parentNode.children[2].focus();
                                }
                            });
                            return;
                        }

                        if (!max_length(button.parentNode.parentNode.children[0].value, 1054)) {
                            Kanban.campaign.alert.fire({
                                "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                "message": GLOBAL_LANG.kanban_communication_validation_max_length,
                                "type": "info-default"
                            }).then(res => {
                                if (res == "close") {
                                    Kanban.campaign.alert.close();
                                    validate = false;
                                    button.parentNode.parentNode.children[2].focus();
                                }
                            });
                            return;
                        }

                        if (validate) {
                            Kanban.campaign.save(data_to_save);
                            Kanban.campaign.edit.cancel();
                        }
                        break;

                    default:
                        break;
                }

            }
        },
        save(data_to_save) {

            let diff = false;
            if (typeof data_to_save.data == "number") {
                if (data_to_save.data != selectedCampaign[data_to_save.db_column])
                    diff = true;
            }

            if (typeof data_to_save.data == "string") {
                if (data_to_save.data.trim() != selectedCampaign[data_to_save.db_column].trim())
                    diff = true;
            }

            if (diff) {

                document.getElementById("modal-campaign").removeEventListener("checkPartialCampaign", window.saveInFact);

                window.saveInFact = function afterCheck() {

                    data_to_save.is_limited_time = selectedCampaign.is_limited_time;

                    const json = {
                        Cmd: "kanban-communication",
                        action: "editBroadcast",
                        edit: data_to_save,
                        token: selectedCampaign.token,
                        key_remote_id: UserKeyRemoteId
                    };

                    if (selectedCampaign.token)
                        socket.send(JSON.stringify(json));
                }

                if (typeof data_to_save.partial_campaign_validation != "undefined" && data_to_save.partial_campaign_validation) {
                    document.getElementById("modal-campaign").addEventListener("checkPartialCampaign", window.saveInFact);
                    Kanban.campaign.checkPartialCampaign(data_to_save);
                } else {
                    window.saveInFact();
                }
            }
        },
        disableEdition() {
            const icons = [...document.querySelectorAll(".icon-edit")];
            icons.forEach(icon => {
                icon.style.opacity = "0.5";
                icon.style.cursor = "default";
                icon.removeEventListener("click", Kanban.campaign.edit.enable);
                icon.setAttribute("data-toggle", "tooltip");
                icon.setAttribute("title", selectedCampaign.status != 3 ? GLOBAL_LANG.kanban_communication_edit_campaign_error_status : GLOBAL_LANG.kanban_communication_edit_tooltip);
                icon.setAttribute("data-container", "body");
            });
        },
        getLastBroadcastLog() {
            const json = {
                Cmd: "kanban-communication",
                action: "getLastBroadcastLog",
                token: selectedCampaign.token
            };
            if (selectedCampaign.token)
                socket.send(JSON.stringify(json));
        },
        checkPartialCampaign(data_to_check) {
            const json = {
                Cmd: "kanban-communication",
                action: "checkPartialCampaign",
                data: data_to_check,
                token: selectedCampaign.token,
                id_channel: selectedCampaign.id_channel
            };
            if (selectedCampaign.token)
                socket.send(JSON.stringify(json));
        },
        handleLog(partial) {

            const contact_notice = document.getElementById("modal-campaign").querySelector(".content-notice");
            contact_notice.firstChild.remove();

            const group_notice = Kanban.campaign.scheduleLog({ is_limited_time: partial })
            contact_notice.appendChild(group_notice);
        },
        resume() {

            Kanban.campaign.alert.fire({
                "title": GLOBAL_LANG.kanban_communication_resume_campaign_title_confirmation,
                "message": GLOBAL_LANG.kanban_communication_resume_campaign_confirmation,
                "type": "action"
            }).then(res => {

                if (res === "yes") {

                    const json = {
                        Cmd: "kanban-communication",
                        action: "resumeBroadcast",
                        token: selectedCampaign.token,
                        key_remote_id: UserKeyRemoteId
                    };

                    if (selectedCampaign.token)
                        Kanban.campaign.load();
                    socket.send(JSON.stringify(json));
                }

                Kanban.campaign.alert.close();
            });
        },
        pause() {

            Kanban.campaign.alert.fire({
                "title": GLOBAL_LANG.kanban_communication_pause_campaign_title_confirmation,
                "message": GLOBAL_LANG.kanban_communication_pause_campaign_confirmation,
                "type": "action"
            }).then(res => {

                if (res === "yes") {

                    const json = {
                        Cmd: "kanban-communication",
                        action: "pauseBroadcast",
                        token: selectedCampaign.token,
                        key_remote_id: UserKeyRemoteId
                    };

                    if (selectedCampaign.token)
                        Kanban.campaign.load();
                    socket.send(JSON.stringify(json));
                }

                Kanban.campaign.alert.close();
            });
        },
        sendNow() {
            if (selectedCampaign.schedule_log.type == 8 || selectedCampaign.schedule_log.type == 4) {
                return;
            }

            Kanban.campaign.alert.fire({
                "title": GLOBAL_LANG.kanban_communication_send_now_campaign_title_confirmation,
                "message": GLOBAL_LANG.kanban_communication_send_now_campaign_confirmation,
                "type": "action"
            }).then(res => {

                if (res === "yes") {
                    document.getElementById("modal-campaign").removeEventListener("checkPartialCampaign", window.sendNowInFact);

                    const current_date = new Date();
                    const current_timestamp = Math.round(current_date.getTime() / 1000);

                    const data_partial_check = {
                        data: current_timestamp,
                        formated_schedule_date: selectedCampaign.formated_schedule_date,
                    }

                    window.sendNowInFact = function afterCheck() {

                        const json = {
                            Cmd: "kanban-communication",
                            action: "sendBroadcastNow",
                            token: selectedCampaign.token,
                            is_limited_time: selectedCampaign.is_limited_time,
                            key_remote_id: UserKeyRemoteId
                        };

                        if (selectedCampaign.token)
                            socket.send(JSON.stringify(json));
                    };

                    document.getElementById("modal-campaign").addEventListener("checkPartialCampaign", window.sendNowInFact);
                    Kanban.campaign.checkPartialCampaign(data_partial_check);
                    Kanban.campaign.load();
                }

                Kanban.campaign.alert.close();
            });
        },
        cancel() {

            Kanban.campaign.alert.fire({
                "title": GLOBAL_LANG.kanban_communication_cancel_campaign_title_confirmation,
                "message": GLOBAL_LANG.kanban_communication_cancel_campaign_confirmation,
                "type": "danger"
            }).then(res => {

                if (res === "yes") {
                    const json = {
                        Cmd: "kanban-communication",
                        action: "cancelBroadcast",
                        token: selectedCampaign.token,
                        key_remote_id: UserKeyRemoteId
                    };
                    if (selectedCampaign.token)
                        Kanban.campaign.load();
                    socket.send(JSON.stringify(json));
                }
                Kanban.campaign.alert.close();
            });
        },
        typeWriting(type) {
            switch (type) {
                case "business_status_text":
                    return GLOBAL_LANG.kanban_communication_type_writing_status_text;
                case "business_status_img":
                    return GLOBAL_LANG.kanban_communication_type_writing_status_img;
                case "business_status_video":
                    return GLOBAL_LANG.kanban_communication_type_writing_status_video;

                case "business_broadcast_text":
                    return GLOBAL_LANG.kanban_communication_type_writing_broascast_text;
                case "business_broadcast_audio":
                    return GLOBAL_LANG.kanban_communication_type_writing_broascast_audio;
                case "business_broadcast_img":
                    return GLOBAL_LANG.kanban_communication_type_writing_broascast_img;
                case "business_broadcast_video":
                    return GLOBAL_LANG.kanban_communication_type_writing_broascast_video;
                case "business_broadcast_pdf":
                    return GLOBAL_LANG.kanban_communication_type_writing_broascast_pdf;

                case "business_newsletter_text":
                    return GLOBAL_LANG.kanban_communication_type_writing_newsletter_text;
                case "business_newsletter_audio":
                    return GLOBAL_LANG.kanban_communication_type_writing_newsletter_audio;
                case "business_newsletter_img":
                    return GLOBAL_LANG.kanban_communication_type_writing_newsletter_img;
                case "business_newsletter_video":
                    return GLOBAL_LANG.kanban_communication_type_writing_newsletter_video;
                case "business_newsletter_pdf":
                    return GLOBAL_LANG.kanban_communication_type_writing_newsletter_pdf;
                case "business_newsletter_poll":
                    return GLOBAL_LANG.kanban_communication_type_writing_newsletter_poll;

                case "business_community_text":
                    return GLOBAL_LANG.kanban_communication_type_writing_community_text;
                case "business_community_audio":
                    return GLOBAL_LANG.kanban_communication_type_writing_community_audio;
                case "business_community_img":
                    return GLOBAL_LANG.kanban_communication_type_writing_community_img;
                case "business_community_video":
                    return GLOBAL_LANG.kanban_communication_type_writing_community_video;
                case "business_community_pdf":
                    return GLOBAL_LANG.kanban_communication_type_writing_community_pdf;
                case "business_community_poll":
                    return GLOBAL_LANG.kanban_communication_type_writing_community_poll;

                case "waba_broadcast":
                case "waba_broadcast_text":
                case "waba_broadcast_img":
                case "waba_broadcast_video":
                case "waba_broadcast_pdf":
                    return GLOBAL_LANG.kanban_communication_type_writing_waba;

                case "facebook_publication_text":
                case "facebook_publication_audio":
                case "facebook_publication_img":
                case "facebook_publication_video":
                case "facebook_publication_pdf":
                    return "";

                case "instagram_publication_text":
                case "instagram_publication_audio":
                case "instagram_publication_img":
                case "instagram_publication_video":
                case "instagram_publication_pdf":
                    return "";

                default:
                    return GLOBAL_LANG.kanban_communication_type_writing_broascast;
            }
        },
        getSelectedElement(column, id) {
            switch (column) {
                case "column__waiting":
                    return kanbanCache.waiting.filter((item) => item.token == id);
                case "column__ongoing":
                    return kanbanCache.ongoing.filter((item) => item.token == id);
                case "column__paused":
                    return kanbanCache.paused.filter((item) => item.token == id);
                case "column__complete":
                    return kanbanCache.complete.filter((item) => item.token == id);
                default:
                    break;
            }
        },
        select(json) {
            selectedCampaign = json;
        },
        deselect() {
            selectedCampaign = {};
        },
        updateSelected(json) {

            if (!selectedCampaign.token || selectedCampaign.token != json.token) return;

            switch (json.edit.db_column) {
                case "title":
                    selectedCampaign.title = json.edit.data;
                    document.getElementById(selectedCampaign.token).getElementsByClassName("title")[1].innerText = json.edit.data;
                    break;
                case "data":
                    selectedCampaign.data = json.edit.data;
                    break;
                case "schedule":
                    selectedCampaign.schedule = json.edit.data;
                    selectedCampaign.formated_schedule_date = json.edit.formated_schedule_date;
                    selectedCampaign.formated_schedule_time = json.edit.formated_schedule_time;

                    if (json.edit.remove_from_kanban) {
                        Kanban.process.removeFromKanban(json.token);
                        Kanban.campaign.count();
                    }

                    if (document.getElementById(selectedCampaign.token))
                        document.getElementById(selectedCampaign.token).getElementsByClassName("header-info")[0].lastChild.innerText = json.edit.formated_schedule_time;
                    break;
                default:
                    break;
            }

            if (document.querySelector(".content-title .group-info textarea") != null) document.querySelector(".content-title .group-info textarea").value = selectedCampaign.title;
            if (document.querySelector(".content-date .group-info input") != null) document.querySelector(".content-date .group-info input").value = selectedCampaign.formated_schedule_date;
            if (document.querySelector(".content-hour .group-info input") != null) document.querySelector(".content-hour .group-info input").value = selectedCampaign.formated_schedule_time;
            if (document.querySelector(".desc-campaign") != null) document.querySelector(".desc-campaign").value = selectedCampaign.data;
            if (document.querySelector(".desc-story") != null) document.querySelector(".desc-story").value = selectedCampaign.data;
            if (selectedCampaign.media_type == 33) Kanban.campaign.updatePoll(JSON.parse(selectedCampaign.data, false))
        },
        updatePoll(object, create) {
            document.querySelector(".poll-content")?.remove();

            const poll_content = Components.div({ class: "poll-content" });
            const poll_container = Components.div({ class: "poll-container" });
            const poll_question = Components.span({ class: "poll-question", text: object.question });

            const poll_multiple_answers = Components.div({ class: "poll-multiple-answers" });
            const poll_multiple_answers_svg = Components.img({ class: "poll-multiple-answers-svg", src: object.multiple_answers ? "/assets/icons/kanban/concluded_gray2.svg" : "/assets/icons/kanban/concluded_gray1.svg" });
            const poll_multiple_answers_span = Components.span({ text: object.multiple_answers ? GLOBAL_LANG.kanban_communication_poll_pollone_option_or_more : GLOBAL_LANG.kanban_communication_poll_one_option });

            poll_multiple_answers.appendChild(poll_multiple_answers_svg);
            poll_multiple_answers.appendChild(poll_multiple_answers_span);

            poll_container.appendChild(poll_question);
            poll_container.appendChild(poll_multiple_answers);

            for (let i = 0; i < object.option.length; i++) {
                const poll_item = Components.div({ class: "poll-item" });
                const poll_body = Components.div({ class: "poll-body" });
                const poll_checkbox = Components.input({ class: "poll-checkbox", disabled: true });
                const poll_option_container = Components.div({ class: "poll-option-container" });
                const poll_option = Components.span({ class: "poll-option", text: object.option[i] });
                const poll_response_number = Components.span({ class: "poll-response-number", text: "0" });
                const poll_progress_bar = Components.div({ class: "poll-progress-bar" });

                poll_option_container.appendChild(poll_option);
                poll_option_container.appendChild(poll_response_number);

                poll_body.appendChild(poll_checkbox);
                poll_body.appendChild(poll_option_container);

                poll_item.appendChild(poll_body);
                poll_item.appendChild(poll_progress_bar);

                poll_container.appendChild(poll_item);
            }

            const poll_show_votes = Components.div({ class: "poll-show-votes" });
            const poll_show_votes_text = Components.span({ class: "poll-show-votes-text", text: GLOBAL_LANG.kanban_communication_poll_show_votes });

            poll_show_votes.appendChild(poll_show_votes_text);

            poll_content.appendChild(poll_container);
            poll_content.appendChild(poll_show_votes);

            if (create)
                return poll_content;
            else
                document.querySelector(".content-campaign .box-left .group-info").appendChild(poll_content);
        },
        updateCards(data) {
            if (data.edit.remove_from_kanban) {
                Kanban.process.removeFromKanban(data.token);
                Kanban.campaign.count();
                $("#modal-campaign").modal("hide");
                return;
            }

            const elm = document.getElementById(data.token);

            if (elm) {
                if (data.edit.db_column === "title") {
                    elm.querySelector(".title textarea").innerText = data.edit.data;
                }
                if (data.edit.db_column === "schedule") {
                    const spans = elm.querySelectorAll(".header-info span");
                    spans[1].innerHTML = data.edit.formated_schedule_time;
                }
            }
            Kanban.campaign.updateCacheInfo(data);
        },
        updateCacheInfo(data) {
            const elm = kanbanCache.waiting.find(item => item.token === data.token);
            if (!elm) return;

            if (data.edit.db_column === "title") {
                elm.title = data.edit.data;
            }

            if (data.edit.db_column === "schedule") {
                elm.formated_schedule_time = data.edit.formated_schedule_time;
            }

            if (data.edit.db_column === "data") {
                elm.data = data.edit.data;
            }
        },
        *status(json) {

            const group_flex__status = Components.div({ class: "group-flex" });
            const group_info__status = Components.div({ class: "group-info" });

            const status_lang = Components.span({ text: "Status" });
            const box_status = Components.div();

            const icon_status = Components.img({ class: "icon-status" });
            const status = Components.span();

            if (json.is_wa_broadcast == 1) {
                json.status = json.status == 1 ? 3 : json.status;
            }

            switch (json.status) {
                case 3:
                    box_status.className = "box-status status-waiting";
                    status.innerHTML = GLOBAL_LANG.kanban_communication_modal_status_waiting;
                    icon_status.src = document.location.origin + "/assets/icons/kanban/arrow_waiting.svg";
                    break;
                case 1:
                case 6:
                    switch (json.is_paused) {
                        case 1:
                            box_status.className = "box-status status-paused";
                            status.innerHTML = GLOBAL_LANG.kanban_communication_modal_status_paused;
                            icon_status.src = document.location.origin + "/assets/icons/kanban/pause_orange.svg";
                            break;
                        case 2:
                            box_status.className = "box-status status-ongoing";
                            icon_status.src = document.location.origin + "/assets/icons/kanban/send.svg";
                            status.innerHTML = GLOBAL_LANG.kanban_communication_modal_status_ongoing;
                            break;
                        default:
                            break;
                    }
                    break;
                case 2:
                    box_status.className = "box-status status-complete";
                    status.innerHTML = GLOBAL_LANG.kanban_communication_modal_status_complete;
                    icon_status.src = document.location.origin + "/assets/icons/kanban/concluded.svg";
                    break;
                default:
                    break;
            }

            box_status.appendChild(icon_status);
            box_status.appendChild(status);

            group_flex__status.appendChild(status_lang);
            group_info__status.appendChild(box_status);

            yield group_flex__status;
            yield group_info__status;
        },
        scheduleLog(json) {

            if (json.is_limited_time == 1) {
                var group_notice = Kanban.components.logWarning();

            } else {
                var group_notice = Components.div({ class: "group-info info" });

                switch (selectedCampaign.schedule_log.type) {
                    case 1:
                    case 5:
                    case 6:
                        var action = GLOBAL_LANG.kanban_communication_schedule_log_action_created;
                        break;
                    case 2:
                        var action = GLOBAL_LANG.kanban_communication_schedule_log_action_paused;
                        break;
                    case 3:
                        var action = GLOBAL_LANG.kanban_communication_schedule_log_action_resumed;
                        break;
                    case 4:
                        var action = GLOBAL_LANG.kanban_communication_schedule_log_action_canceled;
                        break;
                    case 7:
                        var action = GLOBAL_LANG.kanban_communication_schedule_log_action_edited;
                        break;
                    case 8:
                        var action = GLOBAL_LANG.kanban_communication_schedule_log_action_send_now;
                        break;
                }

                const notice_description = Components.span({ text: GLOBAL_LANG.kanban_communication_schedule_log.replace("{{1}}", action).replace("{{2}}", selectedCampaign.schedule_log.day).replace("{{3}}", calendar.months[selectedCampaign.schedule_log.month - 1]).replace("{{4}}", selectedCampaign.schedule_log.user_name) });
                group_notice.appendChild(notice_description);
            }

            return group_notice;
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

            fireSelect(options) {
                const data = selectedCampaign;

                Kanban.campaign.close();

                const modal_alert = Kanban.components.alerts.modalAlert();
                modal_alert.style.width = "820px";

                const box_title = Kanban.components.alerts.boxTitle();
                box_title.firstChild.innerHTML = GLOBAL_LANG.kanban_communication_alert_select_text;

                const box_option = Components.div({ class: "option-box" });

                const message_partial = Components.div({ class: "select-options" });
                const message_span_text = Components.span({ text: GLOBAL_LANG.kanban_communication_alert_select_message })

                const suspend = Components.div({ class: "option" });

                const left_text = Components.span({ text: GLOBAL_LANG.kanban_communication_alert_select_suspend });
                const right_text = Components.span({ text: GLOBAL_LANG.kanban_communication_alert_select_cancel, style: 'margin-right: 238px;' });

                const alter_date = Components.div({ class: "option" });
                const alter_date_text_left = Components.span({ text: GLOBAL_LANG.kanban_communication_alert_select_alter_date });
                const alter_date_text_right = Components.span({ text: GLOBAL_LANG.kanban_communication_alert_select_alter_date_two, style: 'margin-right: 56px;' });

                const send_partial = Components.div({ class: "option" });
                const send_partial_text_left = Components.span({ text: GLOBAL_LANG.kanban_communication_alert_select_partial });
                const send_partial_text_rigth = Components.span({ text: GLOBAL_LANG.kanban_communication_alert_select_partial_two, style: 'margin-right: 56px;' });

                const select_hour = Components.div({ class: "option" });
                const select_hour_text_left = Components.span({ text: GLOBAL_LANG.kanban_communication_alert_select_hour });
                const select_hour_text_rigth = Components.span({ text: GLOBAL_LANG.kanban_communication_alert_select_hour_two, style: 'margin-right: 47px;' });

                message_partial.appendChild(message_span_text);

                suspend.appendChild(left_text);
                suspend.appendChild(right_text);

                alter_date.appendChild(alter_date_text_left);
                alter_date.appendChild(alter_date_text_right);

                send_partial.appendChild(send_partial_text_left);
                send_partial.appendChild(send_partial_text_rigth);

                select_hour.appendChild(select_hour_text_left);
                select_hour.appendChild(select_hour_text_rigth);

                selectedCampaign = data;

                suspend.addEventListener("click", () => {
                    Kanban.campaign.alert.close();
                    Kanban.campaign.cancel();
                });

                alter_date.addEventListener("click", () => {
                    Kanban.campaign.alert.close();
                    Kanban.campaign.open(selectedCampaign.token);
                });

                send_partial.addEventListener("click", () => {
                    Kanban.campaign.alert.fire({
                        "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                        "message": GLOBAL_LANG.kanban_communication_validation_change_partial,
                        "type": "danger"
                    }).then(res => {
                        Kanban.campaign.alert.close();
                        if (res == "no") {
                            Kanban.campaign.edit.recover();
                            Kanban.campaign.alert.close();
                            Kanban.campaign.open(selectedCampaign.token);
                            return;
                        } else {
                            Kanban.campaign.handleLog(1);
                            selectedCampaign.is_limited_time = 1;
                            Kanban.campaign.alert.close();
                            document.getElementById("modal-campaign").dispatchEvent(checkPartialCampaign);
                        }
                    });
                });

                select_hour.addEventListener("click", () => {
                    Kanban.campaign.alert.fire({
                        "title": GLOBAL_LANG.kanban_communication_alert_select_no_time,
                        "message": GLOBAL_LANG.kanban_communication_alert_select_no_time_two,
                        "type": "danger"
                    }).then(res => {
                        Kanban.campaign.alert.close();
                        if (res == "no") {
                            Kanban.campaign.edit.recover();
                            Kanban.campaign.alert.close();
                            Kanban.campaign.open(selectedCampaign.token);
                            return;
                        } else {
                            Kanban.campaign.handleLog(2);
                            selectedCampaign.is_limited_time = 2;
                            Kanban.campaign.alert.close();
                            document.getElementById("modal-campaign").dispatchEvent(checkPartialCampaign);
                        }
                    });
                });

                box_option.appendChild(suspend);
                box_option.appendChild(alter_date);
                box_option.appendChild(send_partial);
                box_option.appendChild(select_hour);

                modal_alert.appendChild(box_title);
                modal_alert.appendChild(message_partial);
                modal_alert.appendChild(box_option);

                document.querySelector("body").appendChild(modal_alert);
                document.querySelector("body").appendChild(Kanban.components.alerts.bgBox());
            },
            close() {
                document.querySelector(".modal-alert").remove();
                document.querySelector(".alert-bg-box").remove();
            },
        },
    },

    process: {
        cards(itens) {

            if (itens.waiting.length > 0)
                for (const json of itens.waiting) Kanban.campaign.create(json, "waiting");

            if (itens.ongoing.length > 0)
                for (const json of itens.ongoing) Kanban.campaign.create(json, "ongoing");

            if (itens.paused.length > 0)
                for (const json of itens.paused) Kanban.campaign.create(json, "paused");

            if (itens.complete.length > 0)
                for (const json of itens.complete) Kanban.campaign.create(json, "complete");

            this.addImages(itens, "card");
        },
        addImages(itens, type) {

            switch (type) {
                case "card":
                    for (const key in itens) {
                        itens[key].forEach(async element => {

                            if (element.media_type != 1) {

                                switch (element.broadcast_type) {

                                    case "waba_broadcast_img":
                                        const header_parameter = JSON.parse(element.json_parameters)[0];
                                        var response = await fetch(header_parameter.parameters[0].image.link);
                                        document.getElementById(element.token).getElementsByClassName("broadcast-media")[0].src = header_parameter.parameters[0].image.link == "" || header_parameter.parameters[0].image.link == null ? document.location.origin + "/assets/icons/kanban/img-preview.svg" : response.url;
                                        break;
                                    case "business_community_img":
                                    case "business_broadcast_img":
                                    case "business_newsletter_img":
                                    case "business_status_img":
                                    case "facebook_publication_img":
                                    case "instagram_publication_img":
                                        var response = await fetch(element.media_url);
                                        document.getElementById(element.token).getElementsByClassName("broadcast-media")[0].src = element.media_url == "" || element.media_url == null ? document.location.origin + "/assets/icons/kanban/img-preview.svg" : response.url;
                                        break;
                                    case "waba_broadcast_video":
                                    case "business_community_video":
                                    case "business_broadcast_video":
                                    case "business_newsletter_video":
                                    case "business_status_video":
                                        document.getElementById(element.token).getElementsByClassName("broadcast-media")[0].src = document.location.origin + "/assets/icons/kanban/video-preview.svg";
                                        break;
                                    case "waba_broadcast_pdf":
                                    case "business_community_pdf":
                                    case "business_broadcast_pdf":
                                    case "business_newsletter_pdf":
                                        document.getElementById(element.token).getElementsByClassName("broadcast-media")[0].src = document.location.origin + "/assets/icons/kanban/pdf-preview.svg";
                                        break;
                                    case "waba_broadcast_audio":
                                    case "business_community_audio":
                                    case "business_broadcast_audio":
                                    case "business_newsletter_audio":
                                        document.getElementById(element.token).getElementsByClassName("broadcast-media")[0].src = document.location.origin + "/assets/icons/kanban/audio-preview.svg";
                                        break;
                                    case "waba_broadcast_text":
                                        break;
                                    case "business_community_poll":
                                    case "business_newsletter_poll":
                                        document.getElementById(element.token).getElementsByClassName("broadcast-media")[0].src = document.location.origin + "/assets/icons/kanban/poll.svg";
                                        break;
                                    default:
                                        document.getElementById(element.token).getElementsByClassName("broadcast-media")[0].src = document.location.origin + "/assets/icons/kanban/img-preview.svg";
                                        break;

                                }

                                if (document.getElementById(element.token).getElementsByClassName("broadcast-media")[0] != null)
                                    document.getElementById(element.token).getElementsByClassName("broadcast-media")[0].style.display = "block";

                                if (document.getElementById(element.token).getElementsByClassName("loading-img")[0] != null)
                                    document.getElementById(element.token).getElementsByClassName("loading-img")[0].remove();
                            }

                            if (document.getElementById(element.token).getElementsByClassName("channel-img")[0] != null) {
                                var response = await fetch(element.channel_picture);
                                document.getElementById(element.token).getElementsByClassName("channel-img")[0].src = element.channel_picture == "" || element.channel_picture == null ? document.location.origin + "/assets/icons/kanban/img-preview.svg" : response.url;
                                document.getElementById(element.token).getElementsByClassName("channel-img")[0].style.display = "block";
                                if (document.getElementById(element.token).getElementsByClassName("loading-img")[0] != null)
                                    document.getElementById(element.token).getElementsByClassName("loading-img")[0].remove();
                            }

                        });
                    }
                    break;
                case "channel":
                    itens.forEach(async element => {
                        const imageExists = (url) => {
                            return new Promise(resolve => {
                                const img = new Image();
                                img.addEventListener('load', () => resolve(true));
                                img.addEventListener('error', () => resolve(false));
                                img.src = url;
                            });
                        }

                        imageExists(element.picture).then(async (exists) => {
                            if (exists) {
                                if (element.picture != null && element.picture != "") {
                                    const response = await fetch(element.picture);
                                    document.getElementById("list__channel").querySelector(`[id_channel="${element.id_channel}"]`).firstChild.firstChild.src = element.picture == "" || element.picture == null ? document.location.origin + "/assets/icons/kanban/img-preview.svg" : response.url;
                                } else {
                                    document.getElementById("list__channel").querySelector(`[id_channel="${element.id_channel}"]`).firstChild.firstChild.src = "/assets/icons/kanban/img-preview.svg";
                                }
                            } else
                                document.getElementById("list__channel").querySelector(`[id_channel="${element.id_channel}"]`).firstChild.firstChild.src = "/assets/icons/kanban/img-preview.svg";
                        });
                    });

                    break;
                default:
                    break;
            }

        },
        moveInKanbanCache(token, columnOrigin, columnDestiny) {
            let indexOfObject = kanbanCache[columnOrigin].findIndex(object => {
                return object.token === token;
            });

            let element = kanbanCache[columnOrigin].splice(indexOfObject, 1);
            let previous_token = this.pushInSort(element[0], columnDestiny);

            Kanban.campaign.count();

            return previous_token;
        },
        pushInSort(campaign, column) {

            let previous_campaign_index;
            let next_campaign_index;

            if (typeof kanbanCache[column][0] == "undefined" || kanbanCache[column][0].schedule >= campaign.schedule) {
                previous_campaign_index = -1;
            }

            kanbanCache[column].forEach((element, index) => {

                next_campaign_index = typeof kanbanCache[column][index + 1] != "undefined" ? index + 1 : kanbanCache[column].length - 1;

                if (element.schedule < campaign.schedule && kanbanCache[column][next_campaign_index].schedule >= campaign.schedule) {
                    previous_campaign_index = index;
                }

            });

            if (typeof previous_campaign_index == "undefined") {
                previous_campaign_index = kanbanCache[column].length - 1;
            }

            if (previous_campaign_index == -1) {
                kanbanCache[column].unshift(campaign);
                return -1;
            } else {
                kanbanCache[column].splice(previous_campaign_index + 1, 0, campaign);
                return kanbanCache[column][previous_campaign_index].token;
            }

        },
        addCampaignInSort(previous_token, campaign, column) {
            if (previous_token == -1) {
                document.getElementById("column__" + column).prepend(campaign);
            } else {
                document.getElementById(previous_token).after(campaign);
            }
        },
        changeStatusInCache(token, column_origin) {

            let status = 0;
            let is_paused = 0;

            switch (column_origin) {
                case "waiting":
                    status = 3;
                    is_paused = 2;
                    break;
                case "ongoing":
                    status = 6;
                    is_paused = 2;
                    break;
                case "paused":
                    status = 6;
                    is_paused = 1;
                    break;
                case "complete":
                    status = 2;
                    is_paused = 2;
                    break;
                default:
                    break;
            }

            kanbanCache[column_origin].forEach(element => {
                if (element.token == token) {
                    element.status = status;
                    element.is_paused = is_paused;
                }
            });

        },
        removeFromKanban(token) {
            let exist = false;
            for (const key in kanbanCache) {
                kanbanCache[key].forEach(elm => {
                    if (elm.token == token) {
                        var index = kanbanCache[key].indexOf(elm);
                        kanbanCache[key].splice(index, 1);
                        exist = true;
                    }
                });
            }

            if (exist)
                document.getElementById(token).remove();
        }
    },

    ack: {
        updateSent(token) {
            let campaign = document.getElementById(token);
            let current_count = campaign.getElementsByClassName("ack-sent")[0].innerText;
            let new_count = Number(current_count) + 1;
            campaign.getElementsByClassName("ack-sent")[0].innerText = new_count;
            this.animation.animate(campaign.getElementsByClassName("icon-ack-sent")[0]);

            if (selectedCampaign.token == token) {
                let campaign_modal = document.getElementById("modal-campaign");
                campaign_modal.getElementsByClassName("ack-sent")[0].innerText = new_count;
                this.animation.animate(campaign_modal.getElementsByClassName("icon-ack-sent")[0]);
            }

            Kanban.ack.updateKanbanCache(token, new_count, "ack_sent_count");
        },
        updateReceived(token) {
            let campaign = document.getElementById(token);
            let current_count = campaign.getElementsByClassName("ack-received")[0].innerText;
            let new_count = Number(current_count) + 1;
            let get_total_send = Number(campaign.getElementsByClassName("ack-sent")[0].innerText);

            if (new_count > get_total_send) {
                new_count = get_total_send;
            }

            campaign.getElementsByClassName("ack-received")[0].innerText = new_count;
            this.animation.animate(campaign.getElementsByClassName("icon-ack-received")[0]);

            if (selectedCampaign.token == token) {
                let campaign_modal = document.getElementById("modal-campaign");
                campaign_modal.getElementsByClassName("ack-received")[0].innerText = new_count;
                this.animation.animate(campaign_modal.getElementsByClassName("icon-ack-received")[0]);
            }

            Kanban.ack.updateKanbanCache(token, new_count, "ack_received_count");
        },
        updateRead(token) {
            let campaign = document.getElementById(token);
            let current_count = campaign.getElementsByClassName("ack-read")[0].innerText;
            let new_count = Number(current_count) + 1;
            campaign.getElementsByClassName("ack-read")[0].innerText = new_count;
            this.animation.animate(campaign.getElementsByClassName("icon-ack-read")[0]);

            if (selectedCampaign.token == token) {
                let campaign_modal = document.getElementById("modal-campaign");
                campaign_modal.getElementsByClassName("ack-read")[0].innerText = new_count;
                this.animation.animate(campaign_modal.getElementsByClassName("icon-ack-read")[0]);
            }

            Kanban.ack.updateKanbanCache(token, new_count, "ack_read_count");
        },
        updateReaction(token) {
            let campaign = document.getElementById(token);
            let current_count = campaign.getElementsByClassName("ack-reaction")[0].innerText;
            let new_count = Number(current_count) + 1;
            campaign.getElementsByClassName("ack-reaction")[0].innerText = new_count;
            this.animation.bubbleHeart(campaign.getElementsByClassName("hearts")[0]);

            if (selectedCampaign.token == token) {
                let campaign_modal = document.getElementById("modal-campaign");
                campaign_modal.getElementsByClassName("ack-reaction")[0].innerText = new_count;
                this.animation.bubbleHeart(campaign_modal.getElementsByClassName("hearts")[0]);
            }

            Kanban.ack.updateKanbanCache(token, new_count, "ack_reaction_count");
        },
        animation: {
            animate(icon) {
                icon.classList.add("animate-ack");
                setTimeout(() => {
                    icon.classList.remove("animate-ack");
                }, 1000);
            },
            bubbleHeart(hearts) {
                hearts.classList.add("animate-heart");
                initparticles();
                hearts.classList.remove("animate-heart");

                setTimeout(() => {
                    for (let i = 0; i < 5; i++) {
                        hearts.firstChild.remove();
                    }

                }, 10000);
            }
        },
        updateKanbanCache(token, new_count, ack) {
            for (const column in kanbanCache) {
                kanbanCache[column].forEach(campaign => {
                    if (campaign.token == token)
                        campaign[ack] = new_count;
                });
            }
        }
    },

    components: {
        iconEdit() {
            const icon_edit = document.createElement("img");
            icon_edit.src = document.location.origin + "/assets/icons/kanban/edit.svg";
            icon_edit.addEventListener("click", Kanban.campaign.edit.enable);
            icon_edit.id = Kanban.helpers.uuid(100000);
            icon_edit.className = "icon-edit";

            return icon_edit;
        },
        groupFlex() {
            const group_flex = document.createElement("div");
            group_flex.className = "group-flex";

            return group_flex;
        },
        groupInfo() {
            const group_info = document.createElement("div");
            group_info.className = "group-info";

            return group_info;
        },
        groupButtons() {
            const group_buttons = document.createElement("div");
            group_buttons.className = "group-buttons";

            return group_buttons;
        },
        textareaEdit() {
            const textarea_dit = document.createElement("textarea");
            textarea_dit.className = "textarea-edit";

            return textarea_dit;
        },
        inputDate() {
            const input_date = document.createElement("textarea");
            input_date.className = "input-date";

            return textarea_dit;
        },
        inputHour() {
            const input_hour = document.createElement("textarea");
            input_hour.className = "input-hour";

            return input_hour;
        },
        loading(padding_size) {
            const loading = document.createElement("img");
            loading.src = document.location.origin + "/assets/img/loads/loading_2.gif";
            loading.className = "loading-img";
            loading.style.padding = padding_size;

            return loading;
        },
        btnSave() {
            const btn_save = document.createElement("button");
            btn_save.className = "btn-save";
            btn_save.innerHTML = GLOBAL_LANG.kanban_communication_btn_save;
            btn_save.addEventListener("click", Kanban.campaign.edit.save);

            return btn_save;
        },
        btnCancel() {
            const btn_cancel = document.createElement("button");
            btn_cancel.className = "btn-cancel";
            btn_cancel.innerHTML = GLOBAL_LANG.kanban_communication_btn_cancel;
            btn_cancel.addEventListener("click", Kanban.campaign.edit.recover);

            return btn_cancel;
        },
        ackBroadcastCollection(json, column) {
            const collections = Components.div({ class: "collections" });

            const div_ack_sent = Components.div({ class: "ack-group" });
            const div_ack_read = Components.div({ class: "ack-group" });
            const div_ack_received = Components.div({ class: "ack-group" });
            const div_ack_reaction = Components.div({ class: "ack-group" });

            const ack_sent = Components.img({ class: "icon-ack icon-ack-sent", src: document.location.origin + "/assets/icons/kanban/ack_sent.svg" });
            const ack_sent_count = Components.span({ class: "ack-count ack-sent", text: json.ack_sent_count });

            const ack_received = Components.img({ class: "icon-ack icon-ack-received", src: document.location.origin + "/assets/icons/kanban/ack_received.svg" });
            const ack_received_count = Components.span({ class: "ack-count ack-received", text: json.ack_received_count > json.ack_sent_count ? json.ack_sent_count : json.ack_received_count });

            const ack_read = Components.img({ class: "icon-ack icon-ack-read", src: document.location.origin + "/assets/icons/kanban/ack_read.svg" });
            const ack_read_count = Components.span({ class: "ack-count ack-read", text: json.ack_read_count });

            const reaction_icon_container = Components.div({ class: "reaction-icon-container" });
            const ack_reaction = Components.img({ class: "icon-ack icon-ack-reaction", src: document.location.origin + "/assets/icons/kanban/ack_reaction.svg" })

            const particle_heart = document.createElement("span");
            particle_heart.className = "particleheart hearts";

            reaction_icon_container.appendChild(ack_reaction);
            reaction_icon_container.appendChild(particle_heart);

            const ack_reaction_count = document.createElement("span");
            ack_reaction_count.className = "ack-count ack-reaction";
            ack_reaction_count.innerHTML = json.ack_reaction_count;

            div_ack_sent.appendChild(ack_sent);
            div_ack_sent.appendChild(ack_sent_count);

            div_ack_received.appendChild(ack_received);
            div_ack_received.appendChild(ack_received_count);

            div_ack_read.appendChild(ack_read);
            div_ack_read.appendChild(ack_read_count);

            div_ack_reaction.appendChild(reaction_icon_container);
            div_ack_reaction.appendChild(ack_reaction_count);

            collections.appendChild(div_ack_sent);
            collections.appendChild(div_ack_received);
            collections.appendChild(div_ack_read);
            collections.appendChild(div_ack_reaction);

            if (column === "waiting") {
                collections.style.display = "none";
            };

            if (json.is_wa_community === 1) {
                div_ack_reaction.style.display = "none";
            };

            if (json.is_waba_broadcast === 1) {
                div_ack_reaction.style.display = "none";
            };

            if (json.is_wa_channel === 1) {
                div_ack_sent.style.display = "none";
                div_ack_received.style.display = "none";
                div_ack_read.style.display = "none";
                div_ack_reaction.style.display = "none";
            };

            return collections;
        },
        ackStatusCollection(json) {
            const collections = Components.div({ class: "collections" });

            const div_ack_viewed = Components.div({ class: "ack-group" });
            const ack_viewed = Components.img({ class: "icon-ack icon-ack-read icon-ack-viewed", src: document.location.origin + "/assets/icons/kanban/ack_viewed.svg" });
            const ack_viewed_count = Components.span({ class: "ack-count ack-read ack-viewed", text: json.ack_read_count });

            div_ack_viewed.appendChild(ack_viewed);
            div_ack_viewed.appendChild(ack_viewed_count);

            collections.appendChild(div_ack_viewed);

            return collections;
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
                btn_no.innerHTML = GLOBAL_LANG.kanban_communication_btn_no;

                if (color) {
                    btn_no.style.color = "#FFF";
                    btn_no.style.backgroundColor = color;
                }

                return btn_no;
            },
            btnYes(color = false) {
                const btn_yes = document.createElement("button");
                btn_yes.className = "btn-yes";
                btn_yes.innerHTML = GLOBAL_LANG.kanban_communication_btn_yes

                if (color) {
                    btn_yes.style.color = "#FFF";
                    btn_yes.style.backgroundColor = color;
                }

                return btn_yes;
            },
            btnClose(color = false) {
                const btn_to_close = document.createElement("button");
                btn_to_close.className = "btn-to-close";
                btn_to_close.innerHTML = GLOBAL_LANG.kanban_communication_btn_close;

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
        logWarning() {
            let group_notice = Components.div({ class: "group-info warning" });
            let icon_notice = Components.img({ class: "icon-notice", src: document.location.origin + "/assets/icons/kanban/warning.svg" });
            let notice_description = Components.span({ text: `A previsão de envio ultrapassa os horários estabelecidos.` });

            group_notice.appendChild(icon_notice);
            group_notice.appendChild(notice_description);

            return group_notice;
        }
    },

    helpers: {
        uuid(number) {
            return Math.floor(Math.random() * number);
        },
        convertToInt(json) {

            for (const key in json) {
                switch (key) {
                    case "ack_reaction_count":
                    case "ack_read_count":
                    case "ack_received_count":
                    case "ack_sent_count":
                    case "id_channel":
                    case "is_Ig_publication":
                    case "is_fb_publication":
                    case "is_wa_broadcast":
                    case "is_wa_community":
                    case "is_wa_status":
                    case "is_waba_broadcast":
                    case "is_wa_channel":
                    case "is_paused":
                    case "is_limited_time":
                    case "media_type":
                    case "schedule":
                    case "status":
                        json[key] = parseInt(json[key]);
                        break;
                }
            }

            return json;
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

            if (proportion > rows) {
                text = text.slice(0, text.length / proportion - 3);
                text = text.trim();
                text += "...";
            }

            temp_span.remove();
            return text;

        },
        resizeTexArea(txtArea, maxRows) {
            $(txtArea).each(function () {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;');
            }).on('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        },
        resizeTexAreaTyping(txtArea) {
            [txtArea].forEach(function (el) {
                el.addEventListener("input", function () {
                    let cs = window.getComputedStyle(this);
                    this.style.height = "auto";
                    this.style.height = (this.scrollHeight + parseInt(cs.getPropertyValue("border-top-width")) + parseInt(cs.getPropertyValue("border-bottom-width"))) + "px";
                });
            });
        },
        checkTimestampDifference(timestamp, type) {
            //? retorna true para > que 1h e false para < que 1h //
            const json = {
                Cmd: "kanban-communication",
                action: "checkTimeToEdit",
                timestamp: timestamp,
                type: type
            };
            socket.send(JSON.stringify(json));
        },
        async preview(url, type, id) {
            switch (type) {
                case 4:
                case "waba_broadcast_pdf":
                    setTimeout(() => {
                        const pdfjsLib = window['pdfjs-dist/build/pdf'];
                        pdfjsLib.GlobalWorkerOptions.workerSrc = document.location.origin + "/assets/lib/build_pdf/pdf_build_pdf.worker.js";

                        var loadingTask = pdfjsLib.getDocument(url);
                        loadingTask.promise.then(function (pdf) {

                            var pageNumber = 1;
                            pdf.getPage(pageNumber).then(function (page) {

                                var desiredWidth = 240;
                                var viewport = page.getViewport({ scale: 1, });
                                var scale = desiredWidth / viewport.width;
                                var scaledViewport = page.getViewport({ scale: scale, });

                                var canvas = Components.canvas({ id: "pdf-canva" });
                                var context = canvas.getContext('2d');

                                canvas.height = 180;
                                canvas.width = 240;

                                var renderContext = {
                                    canvasContext: context,
                                    viewport: scaledViewport
                                };
                                var renderTask = page.render(renderContext);
                                renderTask.promise.then(function () {
                                    document.getElementById(id).children[0].remove();
                                    var pdf_link = Components.a({ href: url, customAttribute: ["target", "_blank"] });
                                    pdf_link.appendChild(canvas);
                                    document.getElementById(id).appendChild(pdf_link);
                                });
                            });
                        }, function (reason) {
                            console.error(reason);
                        });
                    }, 1000);

                    break;
                case 5:
                case "waba_broadcast_video":
                    setTimeout(() => {
                        const video = Components.video({ src: url, customAttribute: ["controls", "controls"] });
                        document.getElementById(id).children[0].remove();
                        document.getElementById(id).appendChild(video);
                    }, 2000);
                    break;
                default:
                    break;
            }

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
        }
    }
}


const canelExitModal = () => {
    document.querySelector(".modal-exit-bg-box").remove();
    document.querySelector(".modal-exit").remove();
}


const openExitModal = () => {
    closeSettingsWindow();

    Kanban.campaign.alert.fire({
        "title": GLOBAL_LANG.kanban_communication_exit_kanban_title,
        "message": GLOBAL_LANG.kanban_communication_exit_kanban_message,
        "type": "action"
    }).then(res => {
        if (res == "yes")
            window.location.href = "/account/logoff";

        Kanban.campaign.alert.close();
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


const mouseoutWallpaper = (e) => {
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
    document.querySelector(".settings-header .title").innerHTML = GLOBAL_LANG.kanban_communication_settings_title_wallpaper;
    document.querySelector(".internal-content-options").style.display = "none";
    document.querySelector(".settings-option-modal #selectLeft").style.display = "block";

    const createColor = (color) => {
        for (let i = 0; i < color.length; i++) {
            const item = Components.div({ class: "item", style: `background: ${color[i]}`, customAttribute: ["color", `hex${i}`, "selected", false] });
            item.addEventListener("click", setWallpaper);
            item.addEventListener("mouseover", mousehoverWallpaper);
            item.addEventListener("mouseout", mouseoutWallpaper);
            grid_color.appendChild(item);
        }
    }

    const communicationKanbanTheme = localStorage.getItem("communicationKanbanTheme");
    const internal_content_wallpaper = Components.div({ class: "internal-content-wallpape" });

    const grid_color = Components.div({ class: "color-grid" });

    switch (communicationKanbanTheme) {
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

    const communicationKanbanTheme = localStorage.getItem("communicationKanbanTheme");
    const bodyKanban = document.querySelector("body");

    switch (communicationKanbanTheme) {
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
        localStorage.setItem("communicationKanbanTheme", "ligth");
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
        localStorage.setItem("communicationKanbanTheme", "dark");
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

        localStorage.setItem("communicationKanbanTheme", "standard");
        changewallpaper();
    }

    if (e.target.type === "checkbox") {
        serve();
    } else if (!document.getElementById("_standard").checked) {
        serve();
    }
}


const setTheme = () => {
    if (localStorage.getItem("communicationKanbanTheme") == null) {
        if (window.matchMedia("(prefers-color-scheme: dark)").matches)
            document.getElementById("_dark").checked = true;
        else
            document.getElementById("_ligth").checked = true;
    }

    if (localStorage.getItem("communicationKanbanTheme") === "ligth")
        document.getElementById("_ligth").checked = true;

    if (localStorage.getItem("communicationKanbanTheme") === "dark")
        document.getElementById("_dark").checked = true;

    if (localStorage.getItem("communicationKanbanTheme") === "standard")
        document.getElementById("_standard").checked = true;
}


const openDarkMode = () => {
    document.querySelector(".settings-header .title").innerHTML = GLOBAL_LANG.kanban_communication_settings_title_theme;
    document.querySelector(".internal-content-options").style.display = "none";
    document.querySelector(".settings-option-modal #selectLeft").style.display = "block";

    const internal_content_theme = Components.div({ class: "internal-content-theme" });

    const option_light = Components.div({ class: "option-theme", id: "opt__ligth" });
    option_light.appendChild(Components.checkbox({ id: "_ligth" }));
    option_light.appendChild(Components.label({ text: GLOBAL_LANG.kanban_communication_settings_title_theme_light }));

    const option_dark = Components.div({ class: "option-theme", id: "opt__dark" });
    option_dark.appendChild(Components.checkbox({ id: "_dark" }));
    option_dark.appendChild(Components.label({ text: GLOBAL_LANG.kanban_communication_settings_title_theme_dark }));

    const option_standard = Components.div({ class: "option-theme", id: "opt__standard" });
    option_standard.appendChild(Components.checkbox({ id: "_standard" }));
    option_standard.appendChild(Components.label({ text: GLOBAL_LANG.kanban_communication_settings_title_theme_system_default }));

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

    document.querySelector(".settings-header .title").innerHTML = GLOBAL_LANG.kanban_communication_settings_title;
    document.querySelector(".settings-option-modal #selectLeft").style.display = "none";

    const internal_content_options = Components.div({ class: "internal-content-options" });
    const option_theme = Components.label({ class: "option" });
    const box_icon_theme = Components.div({ class: "box-icon" });
    const main_theme = Components.div({ class: "text" });

    box_icon_theme.appendChild(Components.img({ src: "/assets/icons/kanban/dark2.svg" }));
    main_theme.appendChild(Components.span({ text: GLOBAL_LANG.kanban_communication_settings_title_theme }));

    option_theme.appendChild(box_icon_theme);
    option_theme.appendChild(main_theme);

    const option_wallpaper = Components.label({ class: "option" });
    const box_icon_wallpaper = Components.div({ class: "box-icon" });
    const main_wallpaper = Components.div({ class: "text" });

    box_icon_wallpaper.appendChild(Components.img({ src: "/assets/icons/kanban/wallpaper2.svg" }));
    main_wallpaper.appendChild(Components.span({ text: GLOBAL_LANG.kanban_communication_settings_title_wallpaper }));

    option_wallpaper.appendChild(box_icon_wallpaper);
    option_wallpaper.appendChild(main_wallpaper);

    const option_to_go_out = Components.label({ class: "option" });
    const box_icon_to_go_out = Components.div({ class: "box-icon" });
    const main_to_go_out = Components.div({ class: "text" });

    box_icon_to_go_out.appendChild(Components.img({ src: "/assets/icons/kanban/close2.svg" }));
    main_to_go_out.appendChild(Components.span({ text: GLOBAL_LANG.kanban_communication_settings_title_to_go_out }));

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

window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", (event) => {

    if (localStorage.getItem("communicationKanbanTheme") === "ligth") return;
    if (localStorage.getItem("communicationKanbanTheme") === "dark") return;

    if (event.matches) {
        document.querySelector("body").classList.add("dark");
    } else {
        document.querySelector("body").classList.remove("dark");
    }
    changewallpaper();
});


const openChannelFilter = () => {
    document.querySelector(".kanban .drawer-side").classList.add("show-tab");
    document.querySelector(".kanban .bgbox").style.display = "block";

    setTimeout(() => {
        document.querySelector(".kanban .channel-window").classList.add("pull-drawer");
    }, 10);

    const channels_names = [...document.querySelectorAll(".channel-item .information .channel-name")];
    channels_names.forEach(item => {
        item.innerText = Kanban.helpers.removeCharacters(item, item.parentElement, 1);
    });

    const channels_ids = [...document.querySelectorAll(".channel-item .information .channel-id")];
    channels_ids.forEach(item => {
        item.innerText = Kanban.helpers.removeCharacters(item, item.parentElement, 1);
    });
}


const closeChannelFilter = () => {
    document.querySelector(".bgbox").style.display = "none";
    document.querySelector(".kanban .channel-window").classList.remove("pull-drawer");
    document.querySelector(".kanban .drawer-side").classList.remove("show-tab");
}


document.querySelector(".kanban .header .filter").addEventListener("click", openChannelFilter);
document.querySelector(".kanban .bgbox").addEventListener("click", closeChannelFilter);


const filter = {
    channels: {
        list(channels) {
            document.querySelectorAll("#list__channel .channel-item").forEach(elm => elm.remove());

            for (const elm of channels) {

                const item = Components.div({ class: "channel-item", customAttribute: ["id_channel", elm.id_channel] });
                const box = Components.div({ class: "box" });
                const info = Components.div({ class: "information" });

                box.appendChild(Components.img({ src: document.location.origin + "/assets/img/loads/loading_2.gif" }));
                info.appendChild(Components.span({ text: elm.name, class: "channel-name" }));
                info.appendChild(Components.span({ text: elm.id, class: "channel-id" }));

                item.appendChild(box);
                item.appendChild(info);

                document.getElementById("list__channel").appendChild(item);
                item.addEventListener("click", filter.channels.setSelected);
            }

            Kanban.process.addImages(channels, "channel");
            filter.channels.setSelectedHeader();
        },
        setSelectedHeader() {
            const data = {
                name: arguments[0] != null ? Kanban.helpers.removeCharacters(arguments[0].lastChild.firstChild, arguments[0].lastChild, 1) : GLOBAL_LANG.kanban_communication_filter_total_channel,
                phone: arguments[0] != null ? Kanban.helpers.removeCharacters(arguments[0].lastChild.lastChild, arguments[0].lastChild, 1) : "",
                src: arguments[0] != null ? arguments[0].firstChild.firstChild.src : "/assets/icons/kanban/announcement.svg",
                className: arguments[0] != null ? "filter-item selected-channel" : "filter-item all-channels"
            }

            if (document.querySelector(".filter-item") != null) document.querySelector(".filter-item").remove();

            const item = Components.span({ class: data.className });
            const box = Components.div({ class: "box" });
            const info = Components.div({ class: "information" });

            const name = Components.span({ text: data.name });
            const phone = Components.span({ text: data.phone });

            box.appendChild(Components.img({ src: data.src }));

            info.appendChild(name);
            info.appendChild(phone);

            item.appendChild(box);
            item.appendChild(info);

            document.getElementsByClassName("filter")[0].appendChild(item);
            document.getElementById("all__channels").addEventListener("click", filter.channels.showAll);

            item.lastChild.firstChild.innerText = Kanban.helpers.removeCharacters(item.lastChild.firstChild, item.lastChild, 1);
        },
        showAll() {
            for (const e of [...document.querySelectorAll(".column-list .campaign")]) {
                e.style.display = "block";
                e.lastChild.style.display = "block";
                e.classList.remove("hidden");
            }

            document.querySelector(".header .filter .filter-item").classList.add("all-channels");
            document.querySelector(".header .filter .filter-item").classList.remove("selected-channel");
            document.querySelector(".header .filter").firstChild.firstChild.firstChild.src = "/assets/icons/kanban/announcement.svg";
            document.querySelector(".header .filter").firstChild.lastChild.firstChild.textContent = GLOBAL_LANG.kanban_communication_filter_total_channel;
            document.querySelector(".header .filter").firstChild.lastChild.lastChild.textContent = "";

            document.getElementById("search-channel").value = "";
            filter.channels.deselected();
            closeChannelFilter();
        },
        setSelected() {
            filter.general.clearSearchGeneral();
            for (const e of [...document.querySelectorAll(".column-list .campaign")]) {

                if (filter.search([Kanban.helpers.removeAccents(e.getAttribute("id_channel").toLowerCase())], this.getAttribute("id_channel")).length > 0) {
                    e.style.display = "block";
                    e.classList.remove("hidden");
                } else {
                    e.style.display = "none";
                    e.classList.add("hidden");
                }

                e.lastChild.style.display = "none";
            }

            filter.channels.deselected();
            filter.channels.setSelectedHeader(this);
            this.classList.add("selected-channel");

            closeChannelFilter();
            Kanban.campaign.count();
        },
        deselected() {
            document.getElementById("search-channel").value = "";
            document.getElementById("clear-search-channel").style.display = "none";

            for (const e of [...document.querySelectorAll(".channel-item")]) {
                e.className = "channel-item";
                e.style.display = "flex";
            }
        },
        keyup() {
            const text = Kanban.helpers.removeAccents(this.value).toLowerCase();
            const channels = [...document.querySelectorAll(".channel-item")];
            channels.shift();

            for (const e of channels) {

                const word = [
                    Kanban.helpers.removeAccents(e.lastChild.firstChild.innerText.toLowerCase()) + " " +
                    Kanban.helpers.removeAccents(e.lastChild.lastChild.innerText.toLowerCase())
                ];

                if (filter.search(word, text.trim()).length > 0)
                    e.style.display = "flex";
                else
                    e.style.display = "none";
            }

            filter.channels.showCloseIconChannel(text);
        },
        showCloseIconChannel(text) {
            if (text != "") {
                document.getElementById("clear-search-channel").style.display = "block";
            } else {
                document.getElementById("clear-search-channel").style.display = "none";
                filter.channels.showAll();
            }
        },
        clearSearchChannel() {
            filter.channels.deselected();
            filter.channels.showAll();
        }
    },
    general: {
        keyup() {
            const text = Kanban.helpers.removeAccents(this.value).toLowerCase();

            for (const e of [...document.querySelectorAll(".column-list .campaign")]) {

                if (document.querySelector(".channel-item.selected-channel") != null)
                    if (e.attributes.id_channel.value !== document.querySelector(".channel-item.selected-channel").attributes.id_channel.value)
                        continue

                const word = [
                    Kanban.helpers.removeAccents(e.firstChild.firstChild.lastChild.children[0].firstChild.innerHTML.toLowerCase()) + " " +
                    Kanban.helpers.removeAccents(e.firstChild.firstChild.lastChild.children[1].firstChild.innerHTML.toLowerCase()) + " " +
                    Kanban.helpers.removeAccents(e.lastChild.firstChild.lastChild.lastChild.innerHTML.toLowerCase()) + " " +
                    Kanban.helpers.removeAccents(e.lastChild.firstChild.lastChild.firstChild.innerHTML.toLowerCase()) + " " +
                    Kanban.helpers.removeAccents(e.lastChild.firstChild.lastChild.lastChild.innerHTML.toLowerCase().replace(/ /g, "")) + " " +
                    Kanban.helpers.removeAccents(e.lastChild.firstChild.lastChild.lastChild.innerHTML.toLowerCase())
                ];

                if (filter.search(word, text.trim()).length > 0) {
                    e.style.display = "block";
                    e.classList.remove("hidden");
                }
                else {
                    e.style.display = "none";
                    e.classList.add("hidden");
                }
            }

            filter.general.showCloseIconGeneral(text);
            Kanban.campaign.count();
        },
        showCloseIconGeneral(text) {
            if (text != "")
                document.getElementById("clear-search-general").style.display = "block";
            else
                document.getElementById("clear-search-general").style.display = "none";
        },
        clearSearchGeneral() {
            filter.general.showCloseIconGeneral("");
            filter.general.showAll();

            document.getElementById("general-search").value = "";
            Kanban.campaign.count();
        },
        showAll() {
            for (const e of [...document.querySelectorAll(".column-list .campaign")]) {
                e.style.display = "block";
                e.lastChild.style.display = "block";
                e.classList.remove("hidden");
            }
        },
    },
    search(word, text) {
        return word.filter((word) => {
            if (word.indexOf(text) != -1)
                return true;
            else
                return false;
        });
    },
}

document.getElementById("search-channel").addEventListener("keyup", filter.channels.keyup);
document.getElementById("clear-search-channel").addEventListener("click", filter.channels.clearSearchChannel);

document.getElementById("clear-search-general").addEventListener("mousedown", filter.general.clearSearchGeneral);
document.getElementById("general-search").addEventListener("keyup", filter.general.keyup);


function verifyNoCampaigns(columns) {

    let no_campaigns;

    if (columns.waiting.length == 0 && columns.ongoing.length == 0
        && columns.paused.length == 0 && columns.complete.length == 0) {

        document.getElementsByClassName("no-campaign")[0].style.display = "flex";
        document.getElementsByClassName("main-flex")[0].style.display = "none";
        no_campaigns = true;

    } else {
        document.getElementsByClassName("main-flex")[0].style.display = "flex";
        document.getElementsByClassName("no-campaign")[0].style.display = "none";
        no_campaigns = false;
    }

    return no_campaigns;
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

            if (json.status == 1000) {
                if (json.reason == "connection replace") {
                    if (json.session != WebSessionBowser) {
                        socket.close();
                        window.close();
                    }
                }
            }

            if (json.status == 200) {
                switch (json.Cmd) {
                    case "kanban-communication":
                        switch (json.request) {
                            case "process":
                                document.querySelectorAll(".column-list .campaign").forEach(elm => elm.remove());
                                kanbanCache = json.items;

                                if (document.querySelector(".loading-load") != null)
                                    document.querySelector(".loading-load").remove();

                                if (!verifyNoCampaigns(kanbanCache)) {
                                    Kanban.process.cards(json.items);
                                    Kanban.campaign.count();
                                }

                                break;
                            case "checkTimeToEdit":

                                selectedCampaign.enable_edit = json.res && selectedCampaign.status == 3 ? true : false;
                                selectedCampaign.current_date = json.current_date;

                                switch (json.type) {
                                    case "modal":
                                        document.getElementById("modal-campaign").dispatchEvent(checkEditModalEvent);
                                        break;
                                    case "save":
                                        document.getElementById("modal-campaign").dispatchEvent(checkEditSaveEvent);
                                        break;
                                }

                                break;
                            case "sendBroadcastNow":
                                if (json.res) {

                                    Kanban.campaign.close();
                                    Kanban.campaign.alert.fire({
                                        "title": GLOBAL_LANG.kanban_communication_modal_title_action_finished,
                                        "message": GLOBAL_LANG.kanban_communication_send_now_campaign_finished,
                                        "type": "info-blue"
                                    }).then(res => {
                                        if (res === "close") {
                                            Kanban.campaign.alert.close();
                                        }
                                    });

                                } else {

                                    if (selectedCampaign.token == json.token) {
                                        Kanban.campaign.alert.fire({
                                            "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                            "message": GLOBAL_LANG.kanban_communication_send_now_campaign_error,
                                            "type": "error"
                                        }).then(res => {
                                            if (res === "close") {
                                                Kanban.campaign.alert.close();
                                            }
                                        });
                                    }
                                }
                                break;
                            case "cancelBroadcast":
                                if (json.res != false) {

                                    Kanban.process.removeFromKanban(json.token);
                                    Kanban.campaign.count();
                                    if (selectedCampaign.token == json.token) {

                                        Kanban.campaign.close();
                                        Kanban.campaign.alert.fire({
                                            "title": GLOBAL_LANG.kanban_communication_modal_title_action_finished,
                                            "message": GLOBAL_LANG.kanban_communication_cancel_campaign_finished,
                                            "type": "info-blue"
                                        }).then(res => {
                                            if (res == "close") {
                                                Kanban.campaign.alert.close();
                                            }
                                        });
                                    }
                                } else {

                                    if (selectedCampaign.token == json.token) {
                                        Kanban.campaign.alert.fire({
                                            "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                            "message": GLOBAL_LANG.kanban_communication_cancel_campaign_error,
                                            "type": "error"
                                        }).then(res => {
                                            if (res == "close") {
                                                Kanban.campaign.alert.close();
                                            }
                                        });
                                    }
                                }
                                break;
                            case "pauseBroadcast":
                                if (json.res != false) {

                                    if (selectedCampaign.token == json.token) {

                                        Kanban.campaign.close();

                                        setTimeout(() => Kanban.campaign.move(json.token, "paused"), 250);

                                        setTimeout(() => {
                                            Kanban.campaign.alert.fire({
                                                "title": GLOBAL_LANG.kanban_communication_modal_title_action_finished,
                                                "message": GLOBAL_LANG.kanban_communication_pause_campaign_finished,
                                                "type": "info-blue"
                                            }).then(res => {
                                                if (res == "close") {
                                                    Kanban.campaign.alert.close();
                                                }
                                            });
                                        }, 750);
                                    } else {
                                        Kanban.campaign.move(json.token, "paused");
                                    }

                                } else {

                                    if (selectedCampaign.token == json.token) {
                                        Kanban.campaign.alert.fire({
                                            "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                            "message": GLOBAL_LANG.kanban_communication_pause_campaign_error,
                                            "type": "error"
                                        }).then(res => {
                                            if (res == "close") {
                                                Kanban.campaign.alert.close();
                                            }
                                        });
                                    }
                                }
                                break;
                            case "resumeBroadcast":
                                if (json.res != false) {

                                    if (selectedCampaign.token == json.token) {
                                        Kanban.campaign.close();

                                        setTimeout(() => {
                                            Kanban.campaign.move(json.token, "ongoing");
                                        }, 250);

                                        setTimeout(() => {
                                            Kanban.campaign.alert.fire({
                                                "title": GLOBAL_LANG.kanban_communication_modal_title_action_finished,
                                                "message": GLOBAL_LANG.kanban_communication_resume_campaign_finished,
                                                "type": "info-blue"
                                            }).then(res => {
                                                if (res == "close") {
                                                    Kanban.campaign.alert.close();
                                                }
                                            });

                                        }, 750);
                                    } else {
                                        Kanban.campaign.move(json.token, "ongoing");
                                    }

                                } else {

                                    if (selectedCampaign.token == json.token) {
                                        Kanban.campaign.alert.fire({
                                            "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                            "message": GLOBAL_LANG.kanban_communication_resume_campaign_error,
                                            "type": "error"
                                        }).then(res => {
                                            if (res == "close") {
                                                Kanban.campaign.alert.close();
                                            }
                                        });
                                    }
                                }
                                break;
                            case "editBroadcast":
                                if (json.res) {
                                    Kanban.campaign.updateCards(json.data);
                                    Kanban.campaign.updateSelected(json.data);
                                } else {
                                    Kanban.campaign.alert.fire({
                                        "title": GLOBAL_LANG.kanban_communication_modal_title_atention,
                                        "message": GLOBAL_LANG.kanban_communication_edit_campaign_error,
                                        "type": "info-default"
                                    }).then(res => {
                                        if (res == "close") {
                                            Kanban.campaign.alert.close();
                                        }
                                    });
                                }
                                break;
                            case "getLastBroadcastLog":
                                if (json.res) {
                                    selectedCampaign.schedule_log = json.last_log;
                                    document.getElementById("modal-campaign").dispatchEvent(getLastBroadcastLog);
                                }
                                break;
                            case "checkPartialCampaign":
                                if (json.res) {
                                    if (json.partial == 3 || json.partial == 1) {
                                        Kanban.campaign.alert.fireSelect();
                                        return;
                                    } else {
                                        if (json.partial == 2)
                                            Kanban.campaign.handleLog(json.partial);

                                        selectedCampaign.is_limited_time = json.partial;
                                        document.getElementById("modal-campaign").dispatchEvent(checkPartialCampaign);
                                    }
                                } else {
                                    document.getElementById("modal-campaign").dispatchEvent(checkPartialCampaign);
                                }
                                break;
                            case "listChannels":
                                filter.channels.list(json.items);
                                break;
                        }

                        switch (json.event) {
                            case "startBroadcast":
                                if (selectedCampaign.token === json.token) $("#modal-campaign").modal("hide");
                                document.getElementById(json.token).getElementsByClassName("collections")[0].style.display = "flex";
                                Kanban.campaign.move(json.token, "ongoing");
                                break;

                            case "createKanbanBroadcast":
                                Kanban.process.removeFromKanban(json.item.token);
                                json.item = Kanban.helpers.convertToInt(json.item);

                                kanbanCache.waiting.push(json.item);
                                Kanban.campaign.create(json.item, "waiting", true);

                                verifyNoCampaigns(kanbanCache);

                                break;
                            case "completeBroadcast":
                                if (selectedCampaign.token === json.token) $("#modal-campaign").modal("hide");
                                document.getElementById(json.token).getElementsByClassName("collections")[0].style.display = "flex";
                                Kanban.campaign.move(json.token, "complete");
                                break;

                            case "Ack":
                                if (json.acks.message_send != undefined) {
                                    if (document.getElementById(json.token).getElementsByClassName("ack-sent")[0] != undefined) {
                                        json.acks.message_send = json.acks.message_send - parseInt(document.getElementById(json.token).getElementsByClassName("ack-sent")[0].innerText);
                                        for (let i = 0; i < json.acks.message_send; i++) {
                                            Kanban.ack.updateSent(json.token);
                                        }
                                    }
                                }
                                if (json.acks.message_receipt != undefined) {
                                    if (document.getElementById(json.token).getElementsByClassName("ack-received")[0] != undefined) {
                                        json.acks.message_receipt = json.acks.message_receipt - parseInt(document.getElementById(json.token).getElementsByClassName("ack-received")[0].innerText);
                                        for (let i = 0; i < json.acks.message_receipt; i++) {
                                            setTimeout(function (a) {
                                                Kanban.ack.updateReceived(json.token);
                                            }, i * 2, i);
                                        }
                                    }
                                }
                                if (json.acks.message_read != undefined) {
                                    if (document.getElementById(json.token).getElementsByClassName("ack-read")[0] != undefined) {
                                        json.acks.message_read = json.acks.message_read - parseInt(document.getElementById(json.token).getElementsByClassName("ack-read")[0].innerText);
                                        for (let i = 0; i < json.acks.message_read; i++) {
                                            setTimeout(function (a) {
                                                Kanban.ack.updateRead(json.token);
                                            }, i * 2, i);
                                        }
                                    }
                                }
                                if (json.acks.message_reactions != undefined) {
                                    if (document.getElementById(json.token).getElementsByClassName("ack-reaction")[0] != undefined) {
                                        json.acks.message_reactions = json.acks.message_reactions - parseInt(document.getElementById(json.token).getElementsByClassName("ack-reaction")[0].innerText);
                                        for (let i = 0; i < json.acks.message_reactions; i++) {
                                            setTimeout(function (a) {
                                                Kanban.ack.updateReaction(json.token);
                                            }, i * 2, i);
                                        }
                                    }
                                }
                                break;
                        }
                        break;
                }
            }
        }

        socket.onclose = function () {
            console.log("error onclose");
            // setTimeout(() => TAWebsocket(), 5000);
        };

    } catch (exception) {
        console.log("error exception");
        // setTimeout(() => TAWebsocket(), 5000);
    }
}

TAWebsocket();


function initparticles() {
    hearts();
}


function hearts() {
    $.each($(".particleheart.hearts.animate-heart"), function () {
        let heartcount = 4;
        for (let i = 0; i <= heartcount; i++) {
            let size = ($.rnd(60, 120) / 15);
            $(this).append('<span class="particle" style="top:' + 50 + '%; left:' + 0 + '%;width:' + size + 'px; height:' + size + 'px;animation-delay: ' + ($.rnd(0, 30) / 10) + 's;"></span>');
        }
    });
}


jQuery.rnd = function (m, n) {
    m = parseInt(m);
    n = parseInt(n);
    return Math.floor(Math.random() * (n - m + 1)) + m;
}


function adjustLayout() {
    const body_width = document.body.clientWidth;
    const mx_rev_cards = document.querySelectorAll(".mx-rev-card");
    const content_left_logo = document.querySelector(".content-left .logo");
    const content_center_icon = document.querySelector(".content-center .icon-search");
    const kanban_main_flex = document.querySelector(".kanban .container-fluid .main-flex");

    if (body_width <= 360) {
        mx_rev_cards.forEach((elm) => {
            elm.className = "col-12 mx-rev-card";
        });

        content_left_logo.style.display = "none";
        content_center_icon.style.display = "none";
        kanban_main_flex.style.height = "94vh";
    } else if (body_width <= 640) {
        mx_rev_cards.forEach((elm) => {
            elm.className = "col-6 mx-rev-card";
        });

        content_left_logo.style.display = "none";
        content_center_icon.style.display = "none";
        kanban_main_flex.style.height = "94vh";
    } else if (body_width <= 1100) {
        mx_rev_cards.forEach((elm) => {
            elm.className = "col-5 mx-rev-card";
        });

        content_left_logo.style.display = "none";
    } else if (body_width <= 1200) {
        mx_rev_cards.forEach((elm) => {
            elm.style.padding_right = "5px";
            elm.style.padding_left = "5px";
        });

        document.querySelector(".kanban .container-fluid").style.padding_right = "10px";
        document.querySelector(".kanban .container-fluid").style.padding_left = "10px";
    }
}


window.addEventListener("resize", adjustLayout);
window.addEventListener("load", adjustLayout);
