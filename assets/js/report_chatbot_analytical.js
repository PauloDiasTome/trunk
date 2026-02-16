var meuId;
var id_chat;
var tokenMessage;
var last_date = "";
var load = true;
var tailOut = true;
var reverse = false;
var checkScroll = true;
var modalSettings = true;
var endMessages = true;


function FormatShortDate(timestamp) {
    let date = new Date(timestamp * 1000);
    let today = new Date();

    let currentDate = today.getFullYear() + '/' + today.getMonth() + '/' + today.getDate();
    let dt = date.getFullYear() + '/' + date.getMonth() + '/' + date.getDate();

    if (currentDate == dt) {
        return "Hoje";
    } else {
        let diff = this.dateDiffInDays(date, today);
        if (diff < 3) {
            switch (diff) {
                case 1:
                    return "Ontem";
                case 2:
                    return "Anteontem";
            }
        } else {
            if (diff < 7) {
                let semana = ["Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado"];
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
}


function FormatShortTime(timestamp) {
    let date = new Date(timestamp * 1000);
    let hours = date.getHours();
    let minutes = "0" + date.getMinutes();
    return hours + ':' + minutes.substr(-2);
}


const _MS_PER_DAY = 1000 * 60 * 60 * 24;


function dateDiffInDays(a, b) {
    const utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
    const utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());
    return Math.floor((utc2 - utc1) / _MS_PER_DAY);
}


function truncateString(str, num) {
    if (str.length > num) {
        return str.slice(0, num) + "...";
    }
    else {
        return str;
    }
}


function msgWait() {
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#0' d='M9.75 7.713H8.244V5.359a.5.5 0 0 0-.5-.5H7.65a.5.5 0 0 0-.5.5v2.947a.5.5 0 0 0 .5.5h.094l.003-.001.003.002h2a.5.5 0 0 0 .5-.5v-.094a.5.5 0 0 0-.5-.5zm0-5.263h-3.5c-1.82 0-3.3 1.48-3.3 3.3v3.5c0 1.82 1.48 3.3 3.3 3.3h3.5c1.82 0 3.3-1.48 3.3-3.3v-3.5c0-1.82-1.48-3.3-3.3-3.3zm2 6.8a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2v-3.5a2 2 0 0 1 2-2h3.5a2 2 0 0 1 2 2v3.5z'></path></svg>";
}


function msgSend() {
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#0' d='M10.91 3.316l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";
}


function msgReceived() {
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#0' d='M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.879a.32.32 0 0 1-.484.033l-.358-.325a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";
}


function msgRead() {
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#4FC3F7' d='M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.879a.32.32 0 0 1-.484.033l-.358-.325a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";
}


function message(json) {

    var data = "";
    let reaction = '';

    switch (parseInt(json.media_type)) {
        case 1:
            data = json.data;
            break;
        case 2:
            data = json.media_duration;
            break;
        case 3:
            data = json.media_caption;
            break;
        case 4:
            data = json.media_title;
            break;
        case 18:
            if (json.key_from_me == 1) {
                data = GLOBAL_LANG.report_waiting_service_function_message_if;
            } else {
                data = GLOBAL_LANG.report_waiting_service_function_message_else;
            }
            break;
        case 19:
            data = json.data;
            break;
        case 26:
            data = json.data;
            break;
        case 27:
            data = json.text_body;
            break;
        case 30:
        case 35:
            data = JSON.parse(json.data);
            break;
    }

    var textData = FormatShortDate(json.creation);

    if (last_date != textData) {

        if (textData != undefined) {

            $("." + textData.replace("/", "_").replace("/", "_")).remove();

            var item = document.createElement("div");
            item.className = "item " + textData.replace("/", "_").replace("/", "_");
            item.dataset.index = json.creation;
            item.id = json.creation;

            var message = document.createElement("div");
            message.className = "information";

            var bottom = document.createElement("div");
            bottom.className = "bottom";
            bottom.innerHTML = "<span>" + FormatShortDate(json.creation) + "</span>";

            message.appendChild(bottom);
            item.appendChild(message);

            $(".chat").append(item);

            last_date = textData;
        }
    }

    var item = document.createElement("div");
    item.className = "item";
    item.dataset.index = json.creation;
    item.id = json.token;

    var message = document.createElement("div");
    var bottom = document.createElement("div");
    bottom.className = "bottom";
    let buttons_template;

    if (json.participant != null && json.participant != "0" && json.media_type < 3) {

        var participant = document.createElement("span");
        participant.style = "font-size:12px; color: " + color[0];
        // participant.innerHTML = json.participant + "<br>";

        message.appendChild(participant);
    }

    if (json.quoted_row_id != null && json.quoted != null) {
        let spanMention = document.createElement("span");
        switch (parseInt(json.quoted.media_type)) {
            case 27:
            case 1:
                if (json.quoted.buttons == null) {

                    let container_message = document.createElement("div");
                    container_message.className = "container-quoted";
                    container_message.style.marginBottom = "10px";


                    container_message.id = json.token == undefined ? "mg_" + json.quoted_row_id : "mg_" + json.token;
                    if (json.creation == undefined) {
                        let quotedToken = container_message.id.split('_')[1];
                        container_message.setAttribute('name', $(`#${quotedToken}`).data('index'));
                    } else {
                        container_message.setAttribute('name', json.creation);
                    }

                    spanMention.textContent = json.quoted.data;

                    container_message.appendChild(spanMention);

                    container_message.style.borderRadius = "10px";
                    container_message.style.borderLeft = "4px solid rgb(78, 197, 69)";
                    container_message.style.backgroundColor = "rgb(84 83 83 / 22%)";
                    container_message.style.padding = "6px 38px 6px 33px";
                    container_message.style.marginTop = "4px";

                    message.appendChild(container_message);
                }

                break;
            case 2:

                let iconAudio = document.createElement("i");
                iconAudio.className = "fas fa-microphone icon-container";
                iconAudio.style.fontSize = "20px";
                iconAudio.style.marginTop = "17px";
                iconAudio.style.marginLeft = "-63px";
                iconAudio.style.color = "rgb(2 2 2 / 52%)";

                let spanAudio = document.createElement("span");
                spanAudio.textContent = "Audío";
                spanAudio.style.float = "left";
                spanAudio.style.marginTop = "16px";
                spanAudio.style.marginLeft = "22px";
                spanAudio.style.fontSize = "15px";

                let container_audio = document.createElement("div");
                container_audio.className = "container-audio container-quoted";
                container_audio.id = json.token == undefined ? "ad_" + json.quoted_row_id : "ad_" + json.token;
                if (json.creation == undefined) {
                    let quotedToken = container_message.id.split('_')[1];
                    contaicontainer_audioner_message.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                    container_audio.setAttribute('name', json.creation);
                }

                let audio = document.createElement("audio");
                audio.controls = true;
                audio.style.width = "264px";
                audio.style.marginTop = "5px";
                audio.style.alignSelf = "center";
                audio.style.paddingLeft = "15px";
                audio.style.marginRight = "4px";
                audio.style.float = "right";
                audio.src = json.media_url;

                let source = document.createElement("source");
                source.src = json.audio_url;

                audio.appendChild(source);
                container_audio.appendChild(audio);
                container_audio.appendChild(iconAudio);
                container_audio.appendChild(spanAudio);
                message.appendChild(container_audio);
                break;
            case 3:

                let iconImage = document.createElement("i");
                iconImage.className = "fas fa-camera icon-container";
                iconImage.style.fontSize = "22px";
                iconImage.style.display = "block";
                iconImage.style.float = "left";
                iconImage.style.marginTop = "30px";
                iconImage.style.color = "rgb(2 2 2 / 52%)";

                let spanImage = document.createElement("span");
                spanImage.textContent = "Foto";
                spanImage.style.float = "left";
                spanImage.style.marginTop = "32px";
                spanImage.style.marginLeft = "6px";
                spanImage.style.fontSize = "17px";

                let container_Image = document.createElement("div");
                container_Image.className = "container-img container-quoted";
                container_Image.style.height = "133px";
                container_Image.id = json.token == undefined ? "img_" + json.quoted_row_id : "img_" + json.token;
                if (json.creation == undefined) {
                    let quotedToken = container_message.id.split('_')[1];
                    container_Image.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                    container_Image.setAttribute('name', json.creation);
                }

                let container = document.createElement("div");
                container.style.width = "122px";
                container.style.height = "108px";
                container.style.float = "right";
                container.style.borderRadius = "8px";
                container.style.cursor = "pointer";

                let image = document.createElement("img");
                image.src = json.quoted.media_url;
                image.className = "imageLink";
                image.style.width = "100%";
                image.style.height = "100%";
                image.style.objectFit = "cover";
                image.style.borderRadius = "4px";

                let linkImage = document.createElement("a");
                linkImage.className = "cancel-click"
                linkImage.href = json.quoted.media_url;
                linkImage.target = "_blank";

                if (json.quoted.data !== null) {
                    spanMention.textContent = Util.doTruncarStr(json.quoted.data, 50);
                    spanMention.style.paddingLeft = "5px";
                    spanMention.style.paddingRight = "5px";
                    container_Image.appendChild(spanMention);
                }

                linkImage.appendChild(image);
                container.appendChild(linkImage);

                if (json.quoted.data == "0") {
                    container_Image.appendChild(iconImage);
                    container_Image.appendChild(spanImage);
                }

                container_Image.appendChild(iconImage);
                container_Image.appendChild(spanImage);

                container_Image.appendChild(container);
                message.appendChild(container_Image);
                break;
            case 4:

                let container_document = document.createElement("div");
                container_document.className = "container-document container-quoted";
                container_document.style.paddingBottom = "80px";
                container_document.id = json.quoted.token == undefined ? "docum_" + json.quoted_row_id : "docum_" + json.quoted.token;

                let imgDocument = document.createElement("img");
                imgDocument.src = location.origin + "/assets/img/download.svg";
                imgDocument.className = "icon-container";
                imgDocument.style.width = "35px";
                imgDocument.style.float = "right";
                imgDocument.style.marginRight = "22px";
                imgDocument.style.marginTop = "8px";

                let icon_document = document.createElement("img");
                let titleDocument = document.createElement("span");

                if (json.quoted.media_url.includes(".pdf")) {

                    icon_document.className = "icon_document";
                    icon_document.src = location.origin + "/assets/icons/pdf.svg";
                    icon_document.style.marginLeft = "-8px";
                    icon_document.style.marginTop = "9px";
                    icon_document.style.float = "left";
                    icon_document.style.width = "34px";
                    icon_document.style.height = "37px";
                    container_document.appendChild(icon_document);

                }

                if (json.quoted.media_url.includes(".xlsx")) {

                    icon_document.className = "icon_document";
                    icon_document.src = location.origin + "/assets/icons/excel.svg";
                    icon_document.style.marginLeft = "-8px";
                    icon_document.style.marginTop = "9px";
                    icon_document.style.float = "left";
                    icon_document.style.width = "34px";
                    icon_document.style.height = "37px";
                    container_document.appendChild(icon_document);

                }

                if (json.quoted.media_url.includes(".txt")) {

                    icon_document.className = "icon_document";
                    icon_document.src = location.origin + "/assets/icons/texto.svg";
                    icon_document.style.marginLeft = "-8px";
                    icon_document.style.marginTop = "9px";
                    icon_document.style.float = "left";
                    icon_document.style.width = "34px";
                    icon_document.style.height = "37px";
                    container_document.appendChild(icon_document);

                }

                if (!json.quoted.media_url.includes(".txt") && !json.quoted.media_url.includes(".svg") && !json.quoted.media_url.includes(".xlsx") && !json.quoted.media_url.includes(".pdf")) {

                    icon_document.className = "icon_document";
                    icon_document.src = location.origin + "/assets/icons/new-document.svg";
                    icon_document.style.marginLeft = "-8px";
                    icon_document.style.marginTop = "9px";
                    icon_document.style.float = "left";
                    icon_document.style.width = "34px";
                    icon_document.style.height = "37px";
                    container_document.appendChild(icon_document);

                }

                titleDocument.textContent = Util.doTruncarStr(json.quoted.data, 15);
                titleDocument.style.paddingLeft = "11px";
                titleDocument.style.paddingRight = "11px";
                titleDocument.style.position = "absolute";
                titleDocument.style.marginTop = "44px"

                let linkDocument = document.createElement("a");
                linkDocument.className = "cancel-click";
                linkDocument.href = json.quoted.media_url;
                linkDocument.target = "_blank";
                linkDocument.appendChild(imgDocument);

                container_document.appendChild(titleDocument);
                container_document.appendChild(linkDocument);
                message.appendChild(container_document);

                message.style.width = "35%";
                break;
            case 5:

                let container_video = document.createElement("div");
                container_video.className = "container-video container-quoted";
                container_video.style.paddingBottom = "30px";
                container_video.style.maxWidth = "370px";
                container_video.style.overflowWrap = "anywhere";
                container_video.id = json.quoted.token == undefined ? "vid_" + json.quoted_row_id : "vid_" + json.quoted.token;
                if (json.quoted.creation == undefined) {
                    let quotedToken = container_video.id.split('_')[1];
                    container_video.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                    container_video.setAttribute('name', json.quoted.creation);
                }

                let iconPlay = document.createElement("i");
                iconPlay.className = "fas fa-play-circle icon-container icon-focus cancel-click";
                iconPlay.style.fontSize = "30px";
                iconPlay.style.float = "right";
                iconPlay.style.position = "absolute";
                iconPlay.style.marginLeft = "25px";
                iconPlay.style.marginTop = "-45px";
                iconPlay.style.color = "rgb(2 2 2 / 52%)";

                let bodyVideo = document.createElement("div");
                bodyVideo.className = "icon-focus body-video";
                bodyVideo.style.width = "80px";
                bodyVideo.style.float = "right";
                bodyVideo.style.paddingTop = "65px";
                bodyVideo.style.marginRight = "1px";
                bodyVideo.style.marginLeft = "70px";
                bodyVideo.style.borderRadius = "7px";
                bodyVideo.style.backgroundColor = "rgb(190 183 183 / 56%)";

                let iconVideo = document.createElement("i");
                iconVideo.className = "fas fa-video icon-container";
                iconVideo.style.fontSize = "25px";
                iconVideo.style.marginLeft = "1px";
                iconVideo.style.marginTop = "20px";
                iconVideo.style.color = "rgb(2 2 2 / 52%)";

                let titleVideo = document.createElement("span");
                titleVideo.textContent = "Vídeo";
                titleVideo.style.position = "absolute";
                titleVideo.style.marginTop = "25px";
                titleVideo.style.marginLeft = "14px";
                titleVideo.style.fontSize = "13px";

                let linkVideo = document.createElement("a");
                linkVideo.href = json.quoted.media_url;
                linkVideo.target = "_blank";
                linkVideo.appendChild(iconPlay);

                bodyVideo.appendChild(linkVideo);
                container_video.appendChild(iconVideo);
                container_video.appendChild(titleVideo);
                container_video.appendChild(bodyVideo);
                message.appendChild(container_video);

                spanMention.style.paddingLeft = "5px";
                spanMention.style.paddingRight = "5px";

                break;
            case 6:
                break;
            case 7:

                let container_location = document.createElement("div");
                container_location.className = "container-location container-quoted";
                container_location.style.height = "33px";
                container_location.id = json.quoted.token == undefined ? "loc_" + json.quoted_row_id : "loc_" + json.quoted.token;
                if (json.quoted.creation == undefined) {
                    let quotedToken = container_message.id.split('_')[1];
                    container_location.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                    container_location.setAttribute('name', json.quoted.creation);
                }

                let thumbnailLocation = document.createElement("div");
                thumbnailLocation.style.width = "122px";
                thumbnailLocation.style.height = "108px";
                thumbnailLocation.style.float = "right";
                thumbnailLocation.style.borderRadius = "8px";
                thumbnailLocation.style.cursor = "pointer";

                let a_locontaion = document.createElement("a");
                a_locontaion.href = "http://maps.google.com/maps?q=" + json.latitude + "," + json.longitude + "&hl=pt-BR";
                a_locontaion.target = "_blank";

                let imgLocation = document.createElement("img");
                imgLocation.src = "./assets/img/localizacao.jpg";
                imgLocation.style.width = "100%";
                imgLocation.style.width = "100%";
                imgLocation.style.height = "100%";
                imgLocation.style.objectFit = "cover";
                imgLocation.style.borderRadius = "4px";

                let iconLocation = document.createElement("i");
                iconLocation.className = "fas fa-map-marked-alt icon-container";
                iconLocation.style.fontSize = "23px";
                iconLocation.style.float = "left";
                iconLocation.style.position = "";
                iconLocation.style.marginLeft = "-15px";
                iconLocation.style.marginTop = "40px";
                iconLocation.style.color = "rgb(2 2 2 / 52%)";

                let titleLocation = document.createElement("span");
                titleLocation.textContent = "Localização";
                titleLocation.style.float = "left";
                titleLocation.style.marginTop = "45px";
                titleLocation.style.marginLeft = "10px";
                titleLocation.style.fontSize = "13px";

                thumbnailLocation.appendChild(imgLocation);
                container_location.appendChild(iconLocation);
                container_location.appendChild(titleLocation);
                container_location.appendChild(a_locontaion);
                container_location.appendChild(thumbnailLocation);
                message.appendChild(container_location);
                break;
            case 8:
                break;
            case 9:

                let container_contact = document.createElement("div");
                container_contact.className = "container-contact container-quoted";
                container_contact.style.paddingBottom = "80px";
                container_contact.id = json.quoted.token == undefined ? "cont_" + json.quoted_row_id : "cont_" + json.quoted.token;
                if (json.quoted.creation == undefined) {
                    let quotedToken = container_contact.id.split('_')[1];
                    container_contact.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                    $("#" + json.quoted.token).find(".ContactMessage").find(".container-contact").attr("name", json.quoted.creation);
                }

                let iconContact = document.createElement("i");
                iconContact.className = "fas fa-user icon-container";
                iconContact.style.float = "left";
                iconContact.style.marginTop = "18px";
                iconContact.style.marginLeft = "-1px";

                let captionContact = document.createElement("span");
                captionContact.textContent = JSON.parse(json.quoted.data).firstName ? truncateString(JSON.parse(json.quoted.data).firstName, 20) : "Contato";
                captionContact.style.float = "left";
                captionContact.style.marginTop = "15px";
                captionContact.style.marginLeft = "10px";

                container_contact.appendChild(iconContact);
                container_contact.appendChild(captionContact);

                message.appendChild(container_contact);
                break;
            case 10:

                let container_archive = document.createElement("div");
                container_archive.className = "container-archive container-quoted";
                container_archive.style.paddingBottom = "80px";
                container_archive.id = json.quoted.token == undefined ? "arch_" + json.quoted_row_id : "arch_" + json.quoted.token;

                let imgArchive = document.createElement("img");
                imgArchive.src = location.origin + "/assets/img/download.svg";
                imgArchive.className = "icon-container";
                imgArchive.style.width = "35px";
                imgArchive.style.float = "right";
                imgArchive.style.marginRight = "22px";
                imgArchive.style.marginTop = "8px";

                switch (json.quoted.media_type) {
                    case "assets/icons/excel.svg":
                    case "assets/icons/texto.svg":
                        var verify_media_meme = json.quoted.media_type.replace("assets/icons/", "");
                        break;
                    default:
                        verify_media_meme = json.quoted.media_type;
                        break;
                }

                let icon_arquive = document.createElement("img");
                let titleArchive = document.createElement("span");

                switch (verify_media_meme) {
                    case "application/pdf":
                        icon_arquive.src = "assets/icons/pdf.svg";
                        icon_arquive.style.marginLeft = "-8px";
                        icon_arquive.style.marginTop = "9px";
                        icon_arquive.style.float = "left";
                        icon_arquive.style.width = "34px";
                        icon_arquive.style.height = "37px";
                        container_archive.appendChild(icon_arquive);
                        break;

                    case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                    case "application/vnd.ms-excel":
                    case "excel.svg":
                        icon_arquive.className = "icon_document";
                        icon_arquive.src = location.origin + "/assets/icons/excel.svg";
                        icon_arquive.style.marginLeft = "-9px";
                        icon_arquive.style.marginTop = "10px";
                        icon_arquive.style.float = "left";
                        icon_arquive.style.width = "37px";
                        icon_arquive.style.height = "36px";
                        titleArchive.style.marginTop = "44px"
                        container_archive.append(icon_arquive);
                        break;

                    case "application/octet-stream":
                    case "texto.svg":
                        icon_arquive.className = "icon_document";
                        icon_arquive.src = location.origin + "/assets/icons/texto.svg";
                        icon_arquive.style.marginLeft = "-9px";
                        icon_arquive.style.marginTop = "10px";
                        icon_arquive.style.float = "left";
                        icon_arquive.style.width = "37px";
                        icon_arquive.style.height = "36px";
                        titleArchive.style.marginTop = "44px"
                        container_archive.append(icon_arquive);
                        break;

                    case "text/plain":
                        icon_arquive.className = "icon_document";
                        icon_arquive.src = location.origin + "/assets/icons/txt.svg";
                        icon_arquive.style.marginLeft = "-9px";
                        icon_arquive.style.marginTop = "7px";
                        icon_arquive.style.float = "left";
                        icon_arquive.style.width = "44px";
                        icon_arquive.style.height = "41px";
                        titleArchive.style.marginTop = "44px"
                        container_archive.append(icon_arquive);

                        break;

                    default:

                        icon_arquive.className = "icon_document";
                        icon_arquive.src = location.origin + "/assets/icons/new-document.svg";
                        icon_arquive.style.marginLeft = "-9px";
                        icon_arquive.style.marginTop = "10px";
                        icon_arquive.style.float = "left";
                        icon_arquive.style.width = "37px";
                        icon_arquive.style.height = "36px";
                        titleArchive.style.marginTop = "44px"
                        container_archive.append(icon_arquive);
                        break;
                }

                titleArchive.textContent = json.quoted.title == null ? Util.doTruncarStr(json.quoted.file_name, 15) : Util.doTruncarStr(json.quoted.title, 15);
                titleArchive.style.paddingLeft = "11px";
                titleArchive.style.paddingRight = "40px";
                titleArchive.style.position = "absolute";

                let linkArchive = document.createElement("a");
                linkArchive.className = "cancel-click";
                linkArchive.target = "_blank";
                linkArchive.href = json.quoted.media_url;
                linkArchive.appendChild(imgArchive);

                container_archive.appendChild(titleArchive);
                container_archive.appendChild(linkArchive);
                message.appendChild(container_archive);
                break;
            case 30:
            case 35:
                var item_inter = JSON.parse(json.quoted.data);
                var body_inter = item_inter.interactive.body.text
                var footer_inter = item_inter.interactive.footer.text;
                let container_interactive = document.createElement("div");

                container_interactive.className = "container-quoted";
                container_interactive.style.marginBottom = "10px";
                container_interactive.style.maxWidth = "680px";

                container_interactive.id = json.token == undefined ? "mg_" + json.quoted_row_id : "mg_" + json.token;
                if (json.creation == undefined) {
                    let quotedToken = container_interactive.id.split('_')[1];
                    container_interactive.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                    container_interactive.setAttribute('name', json.creation);
                }

                spanMention.innerHTML = body_inter;
                spanMention.style.display = "block";
                spanMention.style.fontSize = '15px';
                spanMention.style.whiteSpace = 'break-spaces';

                var span_footer = document.createElement("span");
                span_footer.innerHTML = footer_inter;
                span_footer.style.fontSize = "0.7rem";
                span_footer.style.display = "block";
                span_footer.style.color = "rgb(134, 150, 160)";
                span_footer.style.whiteSpace = 'break-spaces';

                container_interactive.style.borderRadius = "10px";
                container_interactive.style.borderLeft = "4px solid rgb(78, 197, 69)";
                container_interactive.style.backgroundColor = "rgb(84 83 83 / 22%)";
                container_interactive.style.padding = "6px 38px 6px 33px";
                container_interactive.style.marginTop = "4px";

                container_interactive.appendChild(spanMention);
                container_interactive.appendChild(span_footer);
                message.appendChild(container_interactive);
                break;

        }
    }

    var time = document.createElement("span");
    time.className = "time";
    time.textContent = FormatShortTime(json.creation);

    switch (parseInt(json.media_type)) {
        case 1:
            message.className = "textMessage";

            var body = document.createElement("div");
            body.className = "body";

            if (json.source_id != null) {
                const url_default = document.location.origin + "/assets/img/referral.png";

                body.style.maxWidth = "500px";
                body.style.paddingBottom = "7px";
                bottom.style.paddingBottom = "7px";

                const referral = document.createElement("div");
                referral.className = "referral";

                const container = document.createElement("div");
                container.className = "container-referral";

                const container_text = document.createElement("div");
                container_text.className = "container-text";

                const box = document.createElement("div");
                box.className = "box";

                if (json.media_type_ads === "video") {
                    const video = document.createElement("video");
                    video.src = json.video_url ?? url_default;
                    video.addEventListener("click", () => window.open(json.source_url, '_blank'));
                    box.append(video);
                } else {
                    const link = document.createElement("a");
                    link.href = json.source_url;
                    link.target = "blank";

                    const img = document.createElement("img");
                    img.src = json.thumbnail_url ?? url_default;
                    img.className = "imageLink";

                    link.append(img);
                    box.append(link);
                }

                const headline = document.createElement("span");
                headline.textContent = Util.doTruncarStr(json.headline, 33);
                headline.className = "headline";

                const text_referral = document.createElement("span");
                text_referral.textContent = Util.doTruncarStr(json.body, 100);
                text_referral.className = "text-referral";

                const text = document.createElement("span");
                text.className = "text";
                text.textContent = json.data;
                text.style.display = "block";
                text.style.marginTop = "9px";
                text.style.marginBottom = "-10px";

                container_text.appendChild(headline);
                container_text.appendChild(text_referral);

                container.append(box);
                container.appendChild(container_text);
                referral.append(container);

                body.append(referral);
                body.append(text);

            } else {
                if (json.quoted?.buttons == null) {
                    let span = document.createElement("span");
                    if (json.quoted && json.quoted.media_type == 35) {
                        const parsedData = JSON.parse(json.data);
                        span.innerHTML = `${GLOBAL_LANG.report_chatbot_interactive_flow_message_client} ${parsedData.client ? GLOBAL_LANG.report_chatbot_interactive_flow_message_yes : GLOBAL_LANG.report_chatbot_interactive_flow_message_no}<br>CNPJ: ${parsedData.cnpj ?? ""}`;
                    } else {
                        span.innerHTML = json.data;
                    }
                    body.append(span);
                }
            }

            // Lógica do botão de avaliação (caso buttons não seja null)
            if (json.quoted?.buttons != null) {
                try {
                    const parsed = JSON.parse(json.data);
                    const checked = JSON.parse(json.quoted.buttons);

                    const fullNote = parsed.screen_0_Avaliar_experincia_0?.split("_Nota ")[1] ?? "N/A";
                    const evaluationNote = fullNote.split("(")[0].trim();
                    const comment = parsed.screen_0_Adicionar_comentrio_1 ?? "N/A";

                    for (let i = 0; i < checked.length; i++) {
                        const card = document.createElement("div");
                        Object.assign(card.style, {
                            display: "flex",
                            alignItems: "center",
                            backgroundColor: "rgb(180 215 152)",
                            color: "rgb(67 70 79)",
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
                        title.textContent = checked[i].text;
                        title.style.fontWeight = "bold";
                        title.style.fontSize = "14px";

                        const subtitle = document.createElement("div");
                        subtitle.textContent = GLOBAL_LANG.messenger_template_flow_message_subtitle;
                        subtitle.style.fontSize = "13px";
                        subtitle.style.color = "#d9fdd3";

                        texts.appendChild(title);
                        texts.appendChild(subtitle);

                        card.appendChild(icon);
                        card.appendChild(texts);

                        body.appendChild(card);
                    }
                } catch (e) {
                    console.error("Erro ao processar JSON do flow:", e);
                }
            }

            message.appendChild(body);
            break;

            message.className = "textMessage";

            var body = document.createElement("div");
            body.className = "body";

            if (json.source_id != null) {
                const url_default = document.location.origin + "/assets/img/referral.png";

                body.style.maxWidth = "500px";
                body.style.paddingBottom = "7px";
                bottom.style.paddingBottom = "7px";

                const referral = document.createElement("div");
                referral.className = "referral";

                const container = document.createElement("div");
                container.className = "container-referral";

                const container_text = document.createElement("div");
                container_text.className = "container-text";

                const box = document.createElement("div");
                box.className = "box";

                if (json.media_type_ads === "video") {
                    const video = document.createElement("video");
                    video.src = json.video_url ?? url_default;

                    video.addEventListener("click", () => window.open(json.source_url, '_blank'));
                    box.append(video);
                } else {
                    const link = document.createElement("a");
                    link.href = json.source_url;
                    link.target = "blank";

                    const img = document.createElement("img");
                    img.src = json.thumbnail_url ?? url_default;
                    img.className = "imageLink";

                    link.append(img);
                    box.append(link);
                }

                const headline = document.createElement("span");
                headline.textContent = Util.doTruncarStr(json.headline, 33);
                headline.className = "headline";

                const text_referral = document.createElement("span");
                text_referral.textContent = Util.doTruncarStr(json.body, 100);
                text_referral.className = "text-referral";

                const text = document.createElement("span");
                text.className = "text";
                text.textContent = json.data;
                text.style.display = "block";
                text.style.marginTop = "9px";
                text.style.marginBottom = "-10px";

                container_text.appendChild(headline);
                container_text.appendChild(text_referral);

                container.append(box);
                container.appendChild(container_text);
                referral.append(container);

                body.append(referral);
                body.append(text);

            } else {
                let span = document.createElement("span");
                if (json.quoted && json.quoted.media_type == 35)
                    span.innerHTML = `${GLOBAL_LANG.report_chatbot_interactive_flow_message_client} ${JSON.parse(json.data).client ? GLOBAL_LANG.report_chatbot_interactive_flow_message_yes : GLOBAL_LANG.report_chatbot_interactive_flow_message_no}<br>CNPJ: ${JSON.parse(json.data).cnpj ?? ""}`;
                else
                    span.innerHTML = json.data;

                body.append(span);
            }

            if (json.quoted?.buttons != null) {
                try {
                    const parsed = JSON.parse(json.data);
                    const checked = JSON.parse(json.quoted.buttons);

                    const fullNote = parsed.screen_0_Avaliar_experincia_0?.split("_Nota ")[1] ?? "N/A";
                    const evaluationNote = fullNote.split("(")[0].trim();
                    const comment = parsed.screen_0_Adicionar_comentrio_1 ?? "N/A";

                    for (let i = 0; i < checked.length; i++) {
                        const card = document.createElement("div");
                        Object.assign(card.style, {
                            display: "flex",
                            alignItems: "center",
                            backgroundColor: "rgb(220, 248, 198)",
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
                        title.textContent = checked[i].text;
                        title.style.fontWeight = "bold";
                        title.style.fontSize = "14px";

                        const subtitle = document.createElement("div");
                        subtitle.textContent = GLOBAL_LANG.messenger_template_flow_message_subtitle;
                        subtitle.style.fontSize = "13px";
                        subtitle.style.color = "#d9fdd3";

                        texts.appendChild(title);
                        texts.appendChild(subtitle);

                        card.appendChild(icon);
                        card.appendChild(texts);

                        card.onclick = () => openFlowModal({
                            title: GLOBAL_LANG.messenger_template_flow_message_title_modal,
                            evaluationNote,
                            comment
                        });

                        body.appendChild(card);
                    }
                } catch (e) {
                    console.error("Erro ao processar JSON do flow:", e);
                }
            }

            message.appendChild(body);
            break;
        case 2:
            message.className = "audioMessage";

            var audio = document.createElement("audio");
            audio.controls = true;
            audio.style.width = "100%";
            audio.src = json.media_url;
            audio.type = "audio/mp3";

            message.appendChild(audio);

            const media_id = json.token;
            const media_url = json.media_url;
            const mime_type = json.media_mime_type

            Util.audioToBlob(media_id, media_url, mime_type);
            let audioTranscribeIcon = document.createElement("img");
            audioTranscribeIcon.src = document.location.origin + "/assets/icons/messenger/stars_ai.svg";
            audioTranscribeIcon.title = GLOBAL_LANG.report_chatbot_chat_audio_icon_title;
            audioTranscribeIcon.className = "audioTranscribeIcon";

            message.appendChild(audioTranscribeIcon);

            audioTranscribeIcon.addEventListener("click", (event) => transcribeAudio(media_url, event));

            break;
        case 3:
            message.className = "imageMessage";
            message.dataset.url = json.media_url;

            var container = document.createElement("div");
            container.className = "image-container";

            var image = document.createElement("img");
            image.src = json.media_url;
            image.classList.add("urlImageMessage");
            image.dataset.url = json.media_url;

            var caption = document.createElement("span");
            caption.textContent = json.media_caption == 0 ? "" : json.media_caption;

            container.appendChild(image);
            message.appendChild(container);
            message.appendChild(caption);
            break;
        case 4:

            let container_document = document.createElement("div");
            container_document.className = "container-document-send";
            container_document.style.paddingBottom = "80px";
            container_document.id = json.token == undefined ? "docum_" + json.key_remote_id : "docum_" + json.token;


            let imgDocument = document.createElement("img");
            imgDocument.src = location.origin + "/assets/img/download.svg";
            imgDocument.className = "icon-container";
            imgDocument.style.width = "35px";
            imgDocument.style.float = "right";
            imgDocument.style.marginRight = "22px";
            imgDocument.style.marginTop = "8px";

            let icon_document = document.createElement("img");
            let titleDocument = document.createElement("span");

            if (json.media_url.includes(".pdf")) {

                icon_document.className = "icon_document";
                icon_document.src = location.origin + "/assets/icons/pdf.svg";
                icon_document.style.marginLeft = "10px";
                icon_document.style.marginTop = "9px";
                icon_document.style.height = "37px";
                container_document.appendChild(icon_document);

            }

            if (json.media_url.includes(".svg") || json.media_url.includes(".xlsx")) {

                icon_document.className = "icon_document";
                icon_document.src = location.origin + "/assets/icons/excel.svg";
                icon_document.style.marginLeft = "10px";
                icon_document.style.marginTop = "9px";
                icon_document.style.height = "37px";
                container_document.appendChild(icon_document);

            }

            if (json.media_url.includes(".txt")) {

                icon_document.className = "";
                icon_document.src = location.origin + "/assets/icons/texto.svg";
                icon_document.style.marginLeft = "10px";
                icon_document.style.marginTop = "9px";
                icon_document.style.height = "37px";
                container_document.appendChild(icon_document);

            }

            if (!json.media_url.includes(".txt") && !json.media_url.includes(".svg") && !json.media_url.includes(".xlsx") && !json.media_url.includes(".pdf")) {

                icon_document.className = "";
                icon_document.src = location.origin + "/assets/icons/new-document.svg";
                icon_document.style.marginLeft = "10px";
                icon_document.style.marginTop = "9px";
                icon_document.style.height = "37px";
                container_document.appendChild(icon_document);

            }

            titleDocument.textContent = Util.doTruncarStr(json.media_name, 30);
            titleDocument.style.paddingLeft = "11px";
            titleDocument.style.paddingRight = "11px";

            let linkDocument = document.createElement("a");
            linkDocument.className = "cancel-click";
            linkDocument.href = json.media_url;
            linkDocument.target = "_blank";
            linkDocument.appendChild(imgDocument);

            container_document.appendChild(titleDocument);
            container_document.appendChild(linkDocument);
            message.appendChild(container_document);

            bottom.style.marginTop = "-27px";
            bottom.style.marginLeft = "81%";
            bottom.style.fontSize = "10px";


            break;
        case 5:
            message.className = "videoMessage";
            message.dataset.url = json.media_url;

            var thumbnail = document.createElement("div");
            thumbnail.className = "thumbnail";

            var body = document.createElement("div");
            body.className = "body";

            var title = document.createElement("video");
            title.textContent = json.media_caption;
            title.src = json.media_url;
            title.controls = true
            title.style.width = "100%"

            body.appendChild(title);

            message.appendChild(thumbnail);
            message.appendChild(body);
            break;
        case 6:
            message.className = "gifMessage";

            var container = document.createElement("div");
            container.className = "video-container";

            var vid = document.createElement("video");
            vid.src = json.media_url;
            vid.type = "video/mp4";

            var body = document.createElement("div");
            body.className = "body";

            var caption = document.createElement("span");
            caption.textContent = json.media_caption == 0 ? "" : json.media_caption;

            body.appendChild(caption);

            container.appendChild(vid);
            message.appendChild(container);
            if (json.media_caption != 0) message.appendChild(body);
            break;
        case 7:
            message.className = "locationMessage";

            var thumbnail = document.createElement("div");
            thumbnail.className = "thumbnail";

            var a = document.createElement("a");
            var data = JSON.parse(json.data);
            a.href = "http://maps.google.com/maps?q=" + data.latitude + "," + data.longitude + "&hl=pt-BR";
            a.target = "_blank";

            var img = document.createElement("img");
            img.src = '/assets/img/localizacao.jpg';
            img.style.width = "250px";

            var body = document.createElement("div");
            body.className = "body";

            var title = document.createElement("span");
            title.textContent = json.title;

            a.appendChild(img);
            thumbnail.appendChild(a);
            body.appendChild(title);

            message.appendChild(thumbnail);
            message.appendChild(body);
            break;
        case 9:
            var data = JSON.parse(json.data);

            message.classList.add("ContactMessage");

            var body = document.createElement("div");
            body.className = "body";

            var img = document.createElement("img");
            img.src = location.origin + "/assets/img/avatar.svg";
            img.style.width = "64px!important";

            var caption = document.createElement("span");
            caption.textContent = data.firstName && data.firstName !== data.cellPhone ? truncateString(data.firstName, 20) : "Contato";
            var buttom = document.createElement("div");
            buttom.className = "buttom";

            var input = document.createElement("input");
            input.type = "button";
            input.value = data.cellPhone ? data.cellPhone : '';
            input.style.width = "100%";
            input.style.backgroundColor = "transparent";
            input.style.padding = "10px";

            buttom.appendChild(input);
            body.appendChild(img);
            body.appendChild(caption);

            message.appendChild(body);
            message.appendChild(buttom);
            break;
        case 10:
            message.className = "zipMessage";
            message.dataset.url = json.media_url;

            var a = document.createElement("a");
            a.download = json.file_name;
            a.href = json.media_url;

            var img = document.createElement("img");
            img.src = location.origin + "/assets/img/download.svg";

            var body = document.createElement("div");
            body.className = "body";

            var title = document.createElement("span");
            title.textContent = truncateString(json.title, 20);

            body.appendChild(a);
            a.appendChild(title);
            a.appendChild(img);

            message.appendChild(body);
            break;
        case 18:
            message.className = "revokeMessage";

            switch (parseInt(json.key_from_me)) {
                case 1:
                    message.classList.add("left");
                    message.innerHTML = `<svg xmlns='https://www.w3.org/2000/svg' viewBox='0 0 17 19'>
                                            <path fill='currentColor' d='M12.629 12.463a5.17 5.17 0 0 0-7.208-7.209l7.208 7.209zm-1.23 1.229L4.191 6.484a5.17 5.17 0 0 0 7.208 7.208zM8.41 2.564a6.91 6.91 0 1 1 0 13.82 6.91 6.91 0 0 1 0-13.82z'></path>
                                         </svg>
                                         <span>${GLOBAL_LANG.report_chatbot_message_deleted}</span>`;
                    break;
                case 2:
                    message.classList.add("right");
                    message.innerHTML = `<svg xmlns='https://www.w3.org/2000/svg' viewBox='0 0 17 19'>
                                            <path fill='currentColor' d='M12.629 12.463a5.17 5.17 0 0 0-7.208-7.209l7.208 7.209zm-1.23 1.229L4.191 6.484a5.17 5.17 0 0 0 7.208 7.208zM8.41 2.564a6.91 6.91 0 1 1 0 13.82 6.91 6.91 0 0 1 0-13.82z'></path>
                                         </svg>
                                         <span>${GLOBAL_LANG.report_chatbot_deleted_message}</span>`;
                    break;
            }
            break;
        case 19:
        case 20:
        case 21:
        case 22:
        case 23:
        case 24:
            message.className = "information";
            break;
        case 26:
            message.className = "StickerMessage";
            message.style.boxShadow = ' 0 1.2px 3px rgb(0 0 0 / 30%)';

            var sticker = document.createElement("img");
            sticker.src = json.media_url;
            sticker.style.background = "transparent";
            sticker.style.width = "100%";

            message.appendChild(sticker);
            break;
        case 27:
            message.className = "templateMessage";

            const corp = createTemplateMessage(json);

            message.appendChild(corp);

            if (json.buttons) {
                const component_buttons = createComponentsButtons(json);
                buttons_template = component_buttons;
            }

            break;
        case 30:
        case 35:
            message.className = "InteractiveMessage";
            message.style.paddingBottom = "4px";

            var body_inter = data.interactive.body.text;
            if (data.interactive.type == "flow")
                var button_inter = data.interactive.action.parameters.flow_cta;
            else
                var button_inter = data.interactive.action.button;
            var footer_inter = data.interactive.footer.text;

            var body = document.createElement("div");
            body.className = "body";

            span = document.createElement("span");
            span.innerHTML = Util.nl2br(body_inter, true);
            span.style.whiteSpace = 'break-spaces';
            span.style.fontSize = '15px';
            span.style.width = '370px';

            var div_footer = document.createElement("div");
            div_footer.className = "FooterContent"
            div_footer.innerHTML = Util.nl2br(footer_inter, true);
            div_footer.style.float = 'left';
            div_footer.style.fontSize = '12px';
            div_footer.style.color = '#8696a0';
            div_footer.style.whiteSpace = 'break-spaces';
            div_footer.style.maxWidth = '370px';

            bottom.style.float = 'right';

            var hr = document.createElement('hr');
            hr.style.marginBottom = '4px';
            hr.style.width = '100%';
            hr.style.borderColor = '#80808087';

            var div_button = document.createElement("a");
            if (data.interactive.type == "flow")
                div_button.innerHTML = `<i aria-hidden="true" style="margin-right: 7px; font-size: smaller; margin-bottom: 14px;"></i>` + Util.nl2br(button_inter, true);
            else
                div_button.innerHTML = `<i class="fa fa-list" aria-hidden="true" style="margin-right: 7px; font-size: smaller; margin-bottom: 14px;"></i>` + Util.nl2br(button_inter, true);
            div_button.className = 'button_inter';
            div_button.setAttribute("href", "#");
            div_button.style.marginTop = '56px';
            div_button.style.marginTop = '12px';
            div_button.style.padding = '6px 127px 10px 153px';

            body.append(span);
            message.appendChild(body);
            message.append(div_footer);
            message.append(bottom);
            message.append(hr);
            message.append(div_button);
            break;
        case 32:
            const is_reply_story = json.data ? true : false;

            var reply = document.createElement("span");
            reply.className = "is-reply";
            reply.innerHTML = is_reply_story ? json.data : "";

            message.classList.add("StoryMentionMessage");

            if (json.media_mime_type === "image/jpeg") {

                let container_img = document.createElement("div");

                let caption_img = document.createElement("span");
                caption_img.textContent = is_reply_story ? GLOBAL_LANG.report_chatbot_story_reply : GLOBAL_LANG.report_chatbot_story_mention;

                let image = document.createElement("img");
                image.src = json.media_url;
                image.classList.add("UrlStoryMention");
                image.dataset.url = json.media_url;

                var box_time = document.createElement("div");
                if (parseInt(json.key_from_me) == 1) {
                    if (json.media_caption == 0 || json.media_caption == null) box_time.className = "box-time-left";
                    else box_time.className = "onbox-time-left";
                }
                else {
                    if (json.media_caption == 0 || json.media_caption == null) box_time.className = "box-time-right";
                    else box_time.className = "onbox-time-right";
                }

                image.addEventListener("error", (event) => {
                    event.target.dataset.url = "";
                    event.target.remove();
                });

                message.appendChild(caption_img);
                message.appendChild(container_img);
                container_img.appendChild(image);
                message.appendChild(reply);
                message.appendChild(box_time);
                box_time.appendChild(time);

            }

            if (json.media_mime_type === "video/mp4") {

                let container_video = document.createElement("div");

                let caption_video = document.createElement("span");
                caption_video.textContent = is_reply_story ? GLOBAL_LANG.report_chatbot_story_reply : GLOBAL_LANG.report_chatbot_story_mention;

                let video = document.createElement("video");
                video.src = json.media_url;
                video.classList.add("UrlStoryMention");
                video.dataset.url = json.media_url;

                var box_time = document.createElement("div");
                if (parseInt(json.key_from_me) == 1) {
                    if (json.media_caption == 0 || json.media_caption == null) box_time.className = "box-time-left";
                    else box_time.className = "onbox-time-left";
                }
                else {
                    if (json.media_caption == 0 || json.media_caption == null) box_time.className = "box-time-right";
                    else box_time.className = "onbox-time-right";
                }

                video.addEventListener("error", (event) => {
                    event.target.dataset.url = "";
                    event.target.remove();
                });

                message.appendChild(caption_video);
                message.appendChild(container_video);
                container_video.appendChild(video);
                message.appendChild(reply);
                message.appendChild(box_time);
                box_time.appendChild(time);
            }
            break;

    }

    switch (parseInt(json.key_from_me)) {
        case 1:
            switch (parseInt(json.media_type)) {
                case 18:
                case 19:
                case 20:
                case 21:
                case 22:
                case 23:
                    break;
                default:
                    message.className += " messageLeft";

                    if (tailOut) {
                        let tailOutMessage = document.createElement("span");
                        message.append(tailOutMessage);

                        tailOutMessage.className = "tailOutMessageLeft";
                        tailOut = false;
                    }
                    break;
            }
            message.style.cssFloat = 'left';
            message.style.background = '#dcf8c6';

            break;
        case 2:
            message.className += " messageRight";
            message.style.cssFloat = 'right';

            if (!tailOut) {
                let tailOutMessage = document.createElement("span");
                tailOutMessage.className = "tailOutMessageRight";

                message.append(tailOutMessage);
                tailOut = true;
            }

            switch (parseInt(json.status)) {
                case 0:
                    bottom.innerHTML = msgWait();
                    break;
                case 1:
                    bottom.innerHTML = msgSend();
                    break;
                case 2:
                    bottom.innerHTML = msgReceived();
                    break;
                case 3:
                case 4:
                    bottom.innerHTML = msgRead();
                    break;
            }
            break;
    }

    switch (parseInt(json.media_type)) {
        case 1:
        case 2:
        case 3:
        case 4:
            bottom.prepend(time);
            message.appendChild(bottom);

            reaction = setReaction(json);

            item.appendChild(message);

            if (reaction) {
                item.className = 'item reaction';
                item.appendChild(reaction);
            }

            message.style.paddingTop = "12px";
            message.style.paddingTop = "12px";
            message.style.borderRadius = "10px";
            message.style.paddingLeft = "13px";
            // message.style.boxShadow = "0px 3px 5px #808080ab";
            break;
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        case 26:
        case 27:
            bottom.prepend(time);
            message.appendChild(bottom);

            reaction = setReaction(json);

            item.appendChild(message);

            if (reaction) {
                item.className = 'item reaction';
                item.appendChild(reaction);
            }

            message.style.paddingTop = "12px";
            break;
        case 18:
            item.appendChild(message);
            message.appendChild(bottom);
            bottom.prepend(time);
            break;
        case 19:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = json.data;

            message.appendChild(span);
            item.appendChild(message);
            break;
        case 20:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_chatbot_chat_transfer_attendance + FormatShortTime(json.creation) + GLOBAL_LANG.report_chatbot_chat_to;
            message.appendChild(span);
            item.appendChild(message);
            break;
        case 21:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_chatbot_chat_start_attendance + FormatShortTime(json.creation);

            message.appendChild(span);
            item.appendChild(message);
            break;
        case 22:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_chatbot_chat_waiting_attendance;

            message.appendChild(span);
            item.appendChild(message);
            break;
        case 23:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_chatbot_chat_closed_attendance + FormatShortTime(json.creation);

            message.appendChild(span);
            item.appendChild(message);
            break;
        case 24:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_chatbot_service_column_protocol + json.data;

            message.appendChild(span);
            item.appendChild(message);
            break;
        case 30:
        case 35:
            bottom.prepend(time);
            // message.appendChild(bottom);
            item.appendChild(message);
            message.style.paddingTop = "12px";
            message.style.paddingTop = "12px";
            message.style.borderRadius = "10px";
            message.style.paddingLeft = "13px";
            // message.style.boxShadow = "0px 3px 5px #808080ab";
            break;
        case 32:
            item.appendChild(message);
            break;
    }

    if (buttons_template) {
        message.appendChild(buttons_template);
    }

    $(".chat").append(item);
    // $(".messages").scrollTop($(".messages").prop("scrollHeight") + 999);

}


function getServiceTime(full) {
    let time = "";
    let hours = Math.floor(full.minutes / 60);
    let minutes = full.minutes % 60;

    if (hours > 72) {
        time = hours / 24;
        time = time.toString().split(".")[0] + " dias";
    } else {
        if (hours < 10) hours = "0" + hours;
        if (minutes < 10) minutes = "0" + minutes;

        time = hours.toString() + "h:" + minutes.toString();
    }
    return time;
}


function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "analytical/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                channel: $('#verify-select-channel').val() == '2' ? '' : ($('#multiselect-channel').val().length == 0 ? '' : $('#multiselect-channel').val()),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            }
        },
        columns: [
            {
                mData: 'full_name'
            },
            {
                mData: 'name'
            },
            {
                mData: 'options'
            },
            {
                mData: 'waiting_time'
            },
            {
                mData: 'name'
            }
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    let name = full.full_name == "" || full.full_name == null ? full.key_remote_id : full.full_name;

                    return `<div class='kt-user-card-v2'>
                                <div class='kt-user-card-v2__details'>
                                <b class='kt-user-card-v2__name' style='margin-bottom: 6px'>${name}</b>
                                <span data-toggle='modal' data-target='#modal-open-chat' class='modal-open-chat' style='cursor: pointer'>${full.key_remote_id}</span>
                                </div>
                           </div>`;
                }
            },
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    if (full.options == "Main_menu") {
                        return "Menu Principal";
                    }

                    return full.options;
                }
            },
            {
                targets: 3,
                render: function (data, type, full, meta) {

                    let time = "";
                    let hours = Math.floor(full.waiting_time / 60);
                    let minutes = full.waiting_time % 60;

                    if (hours > 72) {

                        time = hours / 24;
                        time = time.toString().split(".")[0] + " dias";
                    } else {
                        if (hours < 10) hours = "0" + hours;
                        if (minutes < 10) minutes = "0" + minutes;

                        time = hours.toString() + "h:" + minutes.toString();
                    }

                    return time;
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                       <i class="fa fa-ellipsis-v"></i>
                                       <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a href='#' id="${full.id_chat_list}" data-name="${full.full_name == null || full.full_name.trim() === '' ? full.key_remote_id : full.full_name}" data-number="${full.key_remote_id}" data-status="${full.status}" class="dropdown-item table-btn-comment" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-comments"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.report_chatbot_service_column_action_view}</span>
                                        </a>
                                    </div>
                                </div>`;
                }
            }
        ],
        order: [[0, 'asc']],
        pagingType: "numbers",
        pageLength: 10,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: true,
        searching: true,
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


$(document).ready(function () {

    $('#search').on('keyup', (e) => {
        if (e.which == 13) {
            document.getElementById("input-search").value = e.target.value;
            find();
        }
    });
    find();


    $("#datatable-basic").on("click", ".table-btn-comment", async function () {
        load = true, id_chat = this.id, meuId = this.id, modalSettings = true;

        document.getElementById("dt-start-chat").value = "";
        document.getElementById("dt-end-chat").value = "";

        document.getElementById("date-chat").innerHTML = "";

        document.getElementById("clear-date-chat").style.display = "none";
        document.getElementById("add-date-chat").style.display = "inline-block";

        document.querySelectorAll(".chat .item").forEach(elm => elm.remove());
        if (document.querySelector(".modal-settings-chat")) { document.querySelector(".modal-settings-chat").remove() };

        $("#modal-chat").modal();

        calendar();
        addLoad("center");
        const data = await getMessages(id_chat, 0, false);
        openChat(data);

        document.getElementById("modal-name-contact").innerHTML = this.attributes[2].nodeValue;
        document.getElementById("modal-number-contact").innerHTML = this.attributes[3].nodeValue;

        const status = this.attributes[4].nodeValue;
        statusModal(status);
    });


    $(".chat").on("scroll", async function () {

        const dt_start = document.getElementById("dt-start-chat").value;

        // ? se não existir scroll 
        if (this.scrollTop == 0 && this.clientHeight == this.scrollHeight) return;

        if (this.scrollTop == 0) {
            // ? scrool top

            if (dt_start === "") {

                const creation = $(".chat").find(".item").first()[0].attributes[1].nodeValue;
                const tokenMessage = $(".chat").find(".item")[0].attributes[2].nodeValue.length === 10 ? $(".chat").find(".item")[1].attributes[2].nodeValue : $(".chat").find(".item")[0].attributes[2].nodeValue;

                if (checkScroll && endMessages) {
                    addLoad("top");
                    let data = await getMessages(id_chat, creation, false);

                    checkScroll = true;
                    if (data.length <= 3) endMessages = false;

                    if (data.length <= 1) {
                        load = false;
                    } else {
                        for (let i = 0; i < data.length; i++) {
                            if (data[i]['token'] == tokenMessage) {
                                indexLastMessage = data.indexOf(data[i]);
                                data = data.filter((messages, indexMessages) => indexMessages > indexLastMessage);
                            }
                        }

                        for (let i = data.length - 1; i >= 0; i--) {
                            message(data[i]);
                        }
                    }

                    $(".chat").find('.item').sort(function (a, b) {
                        return $(a).attr('data-index') - $(b).attr('data-index');
                    }).appendTo(".chat");

                    if (load) {
                        $("#" + tokenMessage)[0].scrollIntoView();
                    }

                    $("#load_container_svg").remove();
                }
            }
        }


        let scrollSize = $(this).scrollTop() + $(this).innerHeight();

        if (Math.ceil(scrollSize) >= this.scrollHeight) {
            // ? scroll bottom

            if (dt_start !== "") {

                const creation = $(".chat").find(".item").last()[0].attributes[1].nodeValue;
                const tokenMessage = $(".chat").find(".item").last()[0].attributes[2].nodeValue;

                if (checkScroll && endMessages) {
                    addLoad("bottom");
                    let data = await getMessages(id_chat, creation, true);

                    checkScroll = true;
                    if (data.length <= 3) endMessages = false;

                    if (data.length <= 1) {
                        load = false;
                    } else {
                        for (let i = 0; i < data.length; i++) {
                            if (data[i]['token'] == tokenMessage) {
                                indexLastMessage = data.indexOf(data[i]);
                                data = data.filter((messages, indexMessages) => indexMessages > indexLastMessage);
                            }
                        }

                        for (let i = 0; i < data.length; i++) {
                            message(data[i]);
                        }
                    }

                    if (load) {
                        $("#" + tokenMessage)[0].scrollIntoView();
                    }

                    $("#load_container_svg").remove();
                }
            }
        }
    });


    $("#clear-date-chat").on("click", async function () {
        load = true;

        document.getElementById("date-chat").innerHTML = "";
        document.getElementById("clear-date-chat").style.display = "none";
        document.getElementById("add-date-chat").style.display = "inline-block";

        document.getElementById("dt-start-chat").value = "";
        document.getElementById("dt-end-chat").value = "";

        document.querySelectorAll(".chat .item").forEach(elm => elm.remove());

        addLoad("center");
        const data = await getMessages(id_chat, 0, false);
        openChat(data);
    });


    $("#datatable-basic").on("click", ".exportTalkClass", function () {
        meuId = "";
        meuId = this.id;
        $("#modal-icon-export-talk").modal();
    });


    $("#modalFilter").one("click", () => modalFilter());
    $('#sendEmailExport').on('click', () => modalExport());

    $("#add-date-chat").on("click", () => modalSettings = true);
    $("#modal-content-chat").on("click", function () { $(".modal-settings-chat").remove(), modalSettings = true });


    $("body").on("click", ".documentMessage", function () {
        const link = document.createElement("a");
        link.href = this.attributes[1].nodeValue;
        link.click();
    });

    $("#modal-chat").on("click", ".UrlStoryMention", function () {
        if ($(this).attr("data-url") != "") {
            window.open($(this).attr("data-url"));
        }
    });

    $("#modal-chat").on("click", ".urlImageMessage", function () {
        if ($(this).attr("data-url") != "") {
            window.open($(this).attr("data-url"));
        }
    });

});


function chatSettings(e) {
    if (modalSettings === true) {
        const modal = document.createElement("div");
        modal.className = "modal-settings-chat";

        const cluttered_list = document.createElement("ul");
        cluttered_list.className = "modal-settings-ul";

        const export_chat = document.createElement("li");
        export_chat.id = "exportTalkChat";
        export_chat.innerHTML = GLOBAL_LANG.report_chatbot_fuction_chatsetting_export;

        const icon_export = document.createElement("i");
        icon_export.id = "icon_export"
        icon_export.className = "fas fa-file-export icon-export";

        modal.appendChild(cluttered_list);
        cluttered_list.appendChild(export_chat);
        cluttered_list.appendChild(icon_export);

        $(".modal-settings-chat").remove();
        e.target.parentNode.appendChild(modal);

        export_chat.addEventListener("click", () => $("#modal-export-talk").modal());

        e.stopPropagation();
        modalSettings = false;
    } else {
        $(".modal-settings-chat").remove();
        modalSettings = true;
    }
}


function AlertNoDataAvailable() {
    const alert_img = document.createElement("img");
    alert_img.src = location.origin + "/assets/icons/panel/alert_calendar.svg";
    alert_img.id = "alert-img";
    alert_img.style.left = "50%";
    alert_img.style.top = "228px";
    alert_img.style.width = "210px";
    alert_img.style.position = "absolute";
    alert_img.style.transform = "translate(-50%, -50%)";

    const alert_text = document.createElement("span");
    alert_text.innerHTML = GLOBAL_LANG.report_chatbot_chat_no_record_found;
    alert_text.id = "alert-text";
    alert_text.style.left = "50%";
    alert_text.style.top = "345px";
    alert_text.style.fontSize = "16px";
    alert_text.style.color = "#596584";
    alert_text.style.position = "absolute";
    alert_text.style.transform = "translate(-50%, -50%)";

    document.querySelector(".chat").append(alert_img);
    document.querySelector(".chat").append(alert_text);
}


function openChat(data) {
    $("#alert-img").remove();
    $("#alert-text").remove();
    $("#load_container_svg").remove();

    if (data.length > 0) {
        document.querySelectorAll(".chat .item").forEach(elm => elm.remove());

        if (document.getElementById("dt-start-chat").value === "") {
            for (let i = data.length - 1; i >= 0; i--) message(data[i]);
            $(".chat").scrollTop($(".chat").prop("scrollHeight"));

        } else {
            for (let i = 0; i < data.length; i++) message(data[i]);
            $(".chat").scrollTop($(".chat").first("scrollHeight"));
        }

    } else {
        AlertNoDataAvailable();
    }
}


function getMessages(id_chat, creation, reverse) {
    return new Promise((resolve, reject) => {
        const dt_start = document.getElementById("dt-start-chat").value;
        const dt_end = document.getElementById("dt-end-chat").value;
        $.post("analytical/getMessages", {
            id: id_chat,
            creation: creation,
            dt_start: dt_start,
            dt_end: dt_end,
            reverse: reverse,
            csrf_talkall: Cookies.get("csrf_cookie_talkall")
        }, (data) => {
            resolve(data);
        });
    });
}


function addLoad(position) {
    const loading = document.createElement("div");
    loading.id = "load_container_svg";
    loading.innerHTML = `<i><img src="/assets/img/loads/loading_2.gif" class="load-img"></i>`;

    $("#alert-img").remove();
    $("#alert-text").remove();
    $("#load_container_svg").remove();

    if (load) {
        if (position === "top") {
            checkScroll = false;
            document.querySelector(".chat").prepend(loading);
        }
        else if (position === "bottom") {
            checkScroll = false;
            document.querySelector(".chat").append(loading);
            $(".chat").scrollTop($(".chat").prop("scrollHeight"));
        }
    }
    if (load || !load) {
        if (position === "center") {
            document.querySelector(".chat").append(loading);
            document.getElementsByClassName("load-img")[0].style.top = "50%";
            document.getElementsByClassName("load-img")[0].style.left = "50%";
            document.getElementsByClassName("load-img")[0].style.position = "absolute";
            document.getElementsByClassName("load-img")[0].style.transform = "translate(-50%, -50%)";
        }
    }
}


async function alertOutNinetyDays() {
    document.getElementById("date-chat").innerHTML = "";
    document.getElementById("clear-date-chat").style.display = "none";
    document.getElementById("add-date-chat").style.display = "inline-block";

    document.getElementById("dt-start-chat").value = "";
    document.getElementById("dt-end-chat").value = "";

    Swal.fire({
        title: GLOBAL_LANG.report_chatbot_alert_out_ninety_days_title,
        text: GLOBAL_LANG.report_chatbot_service_filter_period_notify,
        type: 'warning',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: GLOBAL_LANG.report_chatbot_alert_out_ninety_days_confirmButtonText
    });
    $('.swal2-container').css('z-index', 10000);

    const data = await getMessages(id_chat, 0, false);
    openChat(data);
}


function calendar() {
    moment.locale("pt-BR");
    let start = moment().subtract(7, "days");
    let end = moment();
    let search_period = false;

    async function cb(start, end, clicked_btn) {
        if (search_period) {
            if (clicked_btn) {
                $("#reportrange span").html(start.format("DD/MM/YYYY") + " - " + end.format("DD/MM/YYYY"));
                $("#dt-start-chat").val(start.format("YYYY-MM-DD"));
                $("#dt-end-chat").val(end.format("YYYY-MM-DD"));

                document.getElementById("add-date-chat").style.display = "none";
                document.getElementById("clear-date-chat").style.display = "inline-block";
                document.querySelectorAll(".chat .item").forEach(elm => elm.remove());

                addLoad("center");
                const data = await getMessages(id_chat, 0, false);
                if (data != "false") openChat(data);
                else alertOutNinetyDays();
            }
        }

        endMessages = true;
        search_period = true;
    }

    function ClickApplyBtn() {
        const start = $("#reportrange").data("daterangepicker").startDate;
        const end = $("#reportrange").data("daterangepicker").endDate;
        cb(start, end, true);
    }

    $("#reportrange").daterangepicker(
        {
            startDate: start,
            endDate: end,
            locale: {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": GLOBAL_LANG.report_chatbot_calendar_btn_search,
                "cancelLabel": GLOBAL_LANG.report_chatbot_calendar_btn_return,
                "daysOfWeek": [
                    GLOBAL_LANG.report_chatbot_calendar_week_day_sun,
                    GLOBAL_LANG.report_chatbot_calendar_week_day_mon,
                    GLOBAL_LANG.report_chatbot_calendar_week_day_tue,
                    GLOBAL_LANG.report_chatbot_calendar_week_day_wed,
                    GLOBAL_LANG.report_chatbot_calendar_week_day_thu,
                    GLOBAL_LANG.report_chatbot_calendar_week_day_fri,
                    GLOBAL_LANG.report_chatbot_calendar_week_day_sat
                ],
                "monthNames": [
                    GLOBAL_LANG.report_chatbot_calendar_month_january,
                    GLOBAL_LANG.report_chatbot_calendar_month_february,
                    GLOBAL_LANG.report_chatbot_calendar_month_march,
                    GLOBAL_LANG.report_chatbot_calendar_month_april,
                    GLOBAL_LANG.report_chatbot_calendar_month_may,
                    GLOBAL_LANG.report_chatbot_calendar_month_june,
                    GLOBAL_LANG.report_chatbot_calendar_month_july,
                    GLOBAL_LANG.report_chatbot_calendar_month_august,
                    GLOBAL_LANG.report_chatbot_calendar_month_september,
                    GLOBAL_LANG.report_chatbot_calendar_month_october,
                    GLOBAL_LANG.report_chatbot_calendar_month_november,
                    GLOBAL_LANG.report_chatbot_calendar_month_december
                ]
            },
        }, cb);

    cb(start, end);

    $("#reportrange").on("apply.daterangepicker", ClickApplyBtn);
};


function statusModal(status) {

    const status_modal = document.getElementById("modal-status");

    switch (status) {
        case "1":
            status_modal.className = "diamond bg-info";
            status_modal.innerHTML = GLOBAL_LANG.report_chatbot_chat_status_in_attendance;
            status_modal.style.top = "-18px";
            status_modal.style.fontSize = "12px";
            break;
        case "2":
            status_modal.className = "diamond bg-green";
            status_modal.innerHTML = GLOBAL_LANG.report_chatbot_chat_status_attendance_closed;
            status_modal.style.top = "-18px";
            status_modal.style.fontSize = "12px";
            break;
    }

}


function modalFilter() {

    const msf_multiselect_container = document.querySelectorAll(".msf_multiselect_container");

    for (elm of msf_multiselect_container) elm.remove();

    new MSFmultiSelect(
        document.querySelector('#multiselect-channel'), {
        selectAll: true,
        searchBox: true,
        width: '100%',
        placeholder: GLOBAL_LANG.report_chatbot_service_filter_channel_placeholder,
    });

    document.querySelectorAll('.msf_multiselect li').forEach((options) => {
        options.style.fontSize = '.875rem'
        options.style.display = 'flex';
        options.style.alignItems = 'center';
        options.style.color = '#8898aa';
    });

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_select_channel = document.getElementById("check-select-channel");
    const mult_select_channel = document.getElementById("mult-select-channel");
    const verify_select_channel = document.getElementById("verify-select-channel");

    const check_select = document.getElementById("check-select");
    const mult_select = document.getElementById("mult-select");
    const verify_select = document.getElementById("verify-select");

    const dt_end = document.getElementById("dt-end");
    const dt_start = document.getElementById("dt-start");

    const check_date = document.getElementById("check-date");
    const btn_search = document.getElementById("btn-search");

    const logger = document.querySelectorAll(".logger");
    const alert_filter_period = document.getElementById("alert-filter-period");

    check_search.checked = true;
    input_search.style.display = "block";

    const localDate = new Date();
    const dt_max = localDate.toISOString().split("T")[0];
    const millisecond_per_day = 1000 * 60 * 60 * 24;
    const difDate = new Date(localDate.getTime() - 90 * millisecond_per_day)

    const dt_min = difDate.toISOString().split("T")[0];

    check_search.addEventListener("click", () => {
        if (check_search.checked) {
            input_search.style.display = "block";
        }
        else {
            input_search.value = "";
            input_search.style.display = "none";
        }
    });

    check_select_channel.addEventListener("click", () => {
        if (check_select_channel.checked) {
            mult_select_channel.style.display = "block";
            verify_select_channel.value = "1";
        }
        else {
            mult_select_channel.style.display = "none";
            verify_select_channel.value = "2";
        }
    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = GLOBAL_LANG.report_chatbot_service_filter_period_placeholder_date_start;

            dt_end.type = "text";
            dt_end.value = "";
            // dt_end.disabled = true;
            dt_end.style.display = "block";

        } else {
            dt_start.style.display = "none";
            dt_end.style.display = "none";
            alert_filter_period.style.display = "none";
        }

    });

    check_date.addEventListener("change", () => {
        if (check_date.checked == false) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.placeholder = GLOBAL_LANG.contact_filter_period_placeholder_date_start;

            dt_end.type = "text";
            dt_end.value = "";

            btn_search.disabled = false;
        }

        if (check_date.checked && dt_end.value == '') {
            btn_search.disabled = true;
        }
    });

    dt_start.addEventListener("focus", () => {

        dt_start.type = "date";
        btn_search.disabled = true;
    });

    dt_start.addEventListener("change", () => {
        dt_end.disabled = false;
        dt_end.type = "date";
        btn_search.disabled = false;

        let date = new Date();
        let day = String(date.getDate()).padStart(2, '0');
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let year = date.getFullYear();
        current = year + '-' + month + '-' + day;

        dt_end.value = current;
    });

    btn_search.onclick = () => {
        search.value = input_search.value;
        find();
    };

    for (elm of logger) {
        elm.style.paddingLeft = "15px";
    }
}


function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "creation";
            break;

        case 1:
            column = "protocol";
            break;

        case 2:
            column = "full_name";
            break;

        case 4:
            column = "user";
            break;

        case 5:
            column = "channel_name";
            break;
    }

    $.get(`/export/xlsx?
            search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
            &channel=${$('#verify-select-channel').val() == "2" ? "" : $('#multiselect-channel').val()}
            &column=${column}
            &order=${order}
            &dt_start=${$('#dt-start').val()}
            &dt_end=${$('#dt-end').val()}
            &type=reportChatbotAnalytical`, function (response) {
        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.report_chatbot_alert_export_title,
                text: GLOBAL_LANG.report_chatbot_alert_export_text,
                type: 'success',
                reverseButtons: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.report_chatbot_alert_export_confirmButtonText
            });
        }
    });
}

function createTemplateMessage(json) {
    var body = document.createElement("div");

    body.className = "body";

    if (json.components && json.components != "null") {
        const components = (typeof json.components === 'string') ? JSON.parse(json.components) : json.components;

        for (let i = 0; i < components.length; i++) {
            if (components[i].type == "header") {
                body.style.maxWidth = "250px";
                const header_type = components[i].parameters[0].type;

                switch (header_type) {
                    case "text":
                        json.header = components[i].parameters[0].text;
                        break;

                    case "image":
                        const img = document.createElement("img");
                        img.src = components[i].parameters[0].image.link;
                        img.style.margin = "0px 5px 10px 5px";
                        img.style.width = "calc(100% - 10px)";

                        body.prepend(img);
                        break;

                    case "document":
                        const img_download = document.createElement("img");
                        img_download.src = document.location.origin + "/assets/img/download.svg";
                        img_download.style.width = "32px";
                        img_download.style.height = "32px";
                        img_download.style.float = "right";
                        img_download.style.marginRight = "10px";
                        img_download.style.marginTop = "12px";
                        img_download.style.cursor = "pointer";

                        const body_document = document.createElement("div");
                        body_document.className = "bodyDocument";
                        body_document.style.width = "100%";
                        body_document.style.height = "56px";
                        body_document.style.marginBottom = "10px";
                        body_document.style.paddingBottom = "40px";
                        body_document.style.backgroundColor = '#ced4da';

                        const icon = document.createElement("img");
                        icon.className = "icon_document";
                        icon.style.marginLeft = "18px";
                        icon.style.marginTop = "10px";
                        icon.style.float = "left";
                        icon.style.width = "37px";
                        icon.style.height = "36px";

                        switch (json.media_mime_type) {
                            case "application/pdf":
                                icon.src = document.location.origin + "/assets/icons/pdf.svg";
                                break;

                            case "application/vnd.ms-excel":
                            case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                                icon.src = document.location.origin + "/assets/icons/excel.svg";
                                break;

                            case "application/octet-stream":
                                icon.src = document.location.origin + "/assets/icons/texto.svg";
                                break;

                            case "application/octet-stream":
                                icon.src = document.location.origin + "/assets/icons/texto.svg";
                                break;

                            case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
                                icon.src = document.location.origin + "/assets/icons/texto.svg";
                                break;

                            default:
                                icon.src = document.location.origin + "/assets/icons/new-document.svg";
                                break;
                        }

                        const title = document.createElement("span");
                        title.textContent = GLOBAL_LANG.report_chatbot_document_message;
                        title.style.fontSize = "13px";
                        title.style.float = "left";
                        title.style.textAlign = "left";
                        title.style.marginTop = "22px";
                        title.style.marginLeft = "10px";

                        json.document = components[i].parameters[0].document.link;

                        body_document.appendChild(icon);
                        body_document.appendChild(title);
                        body_document.appendChild(img_download);

                        body.append(body_document);
                        break;

                    default:
                        break;
                }
            }

            if (components[i].type == "body") {
                const body_parameters = components[i].parameters;
                let data = json.text_body;

                for (let i = 0; i < body_parameters.length; i++) {
                    data = data.replaceAll(`{{${i + 1}}}`, body_parameters[i].text);
                }
                json.text_body = data;
            }

            if (components[i].type == "footer") {
                json.footer = components[i].parameters[0].text;
            }
        }
    }

    if (json.header) {
        const container_header = document.createElement("div");
        container_header.className = "templateHeader";

        const header_text = document.createElement("span");
        header_text.innerHTML = json.header;
        header_text.style.fontWeight = "bold";

        container_header.append(header_text)
        body.prepend(container_header);
    }


    const div_span = document.createElement("div");
    div_span.className = "templateBody";
    span = document.createElement("span");
    span.innerHTML = Util.nl2br(json.text_body, true);
    span.style.whiteSpace = 'break-spaces';

    div_span.append(span);
    body.append(div_span);

    let components = "";

    if (json.components) {
        components = typeof (json.components) == "string" ? JSON.parse(json.components) : json.components;
        components = typeof (components) == "string" ? JSON.parse(components) : components;
        button_component = components != "null" ? components.filter(elm => elm.type == "button") : "";
    }

    body.append(span);

    if (json.footer) {
        const container_footer = document.createElement("div");
        container_footer.className = "templateFooter";

        const footer = document.createElement("span");
        footer.innerHTML = json.footer;
        footer.style.color = "#a2a2a2";
        footer.style.fontSize = "13px";

        container_footer.append(footer);
        body.append(container_footer);
    }

    return body;
}

function createComponentsButtons(json) {
    const buttons = document.createElement("div");
    buttons.className = 'buttonMessage';

    if (json.buttons) {

        const content_button = JSON.parse(json.buttons);

        for (let i = 0; i < content_button.length; i++) {

            const button = document.createElement("button");
            button.className = "btnTemplate";
            button.style.fontSize = localStorage.getItem("fontBtnTemplateMessage");

            if (content_button[i].type == "QUICK_REPLY") {
                const svg = `<svg viewBox="0 0 28 28" height="12.8" width="12.8" preserveAspectRatio="xMidYMid meet" style="vertical-align: middle; margin: 0 3px 0 0;">
    <path fill="#007AFF" d="M13,15 C12.346,15 9.98,15.02 9.98,15.02 L9.98,20.39 L2.323,12 L9.98,3.6 L9.98,9.01 C9.98,9.01 12.48,8.98 13,9 C20.062,9.22 24.966,17.26 24.998,21.02 C22.84,18.25 17.17,15 13,15 Z M11.983,7.01 L11.983,1.11 C12.017,0.81 11.936,0.51 11.708,0.28 C11.312,-0.11 10.67,-0.11 10.274,0.28 L0.285,11.24 C0.074,11.45 -0.016,11.72 0,12 C-0.016,12.27 0.074,12.55 0.285,12.76 L10.219,23.65 C10.403,23.88 10.67,24.03 10.981,24.03 C11.265,24.03 11.518,23.91 11.7,23.72 C11.702,23.72 11.706,23.72 11.708,23.71 C11.936,23.49 12.017,23.18 11.983,22.89 C11.983,22.89 12,17.34 12,17 C18.6,17 24.569,21.75 25.754,28.01 C26.552,26.17 27,24.15 27,22.02 C27,13.73 20.276,7.01 11.983,7.01 Z"/>
    </svg>`;

                button.innerHTML = `${svg} ${content_button[i].text}`;
            }

            if (content_button[i].type == "PHONE_NUMBER") {
                const svg = `<svg viewBox="0 -2 18 22" height="18" width="18" preserveAspectRatio="xMidYMid meet" style="vertical-align: middle; margin: 0 3px 0 0;">
    <path d="M15.0075,11.535 C14.085,11.535 13.1925,11.385 12.36,11.115 C12.0975,11.025 11.805,11.0925 11.6025,11.295 L10.425,12.7725 C8.3025,11.76 6.315,9.8475 5.2575,7.65 L6.72,6.405 C6.9225,6.195 6.9825,5.9025 6.9,5.64 C6.6225,4.8075 6.48,3.915 6.48,2.9925 C6.48,2.5875 6.1425,2.25 5.7375,2.25 
    L3.1425,2.25 C2.7375,2.25 2.25,2.43 2.25,2.9925 C2.25,9.96 8.0475,15.75 15.0075,15.75 C15.54,15.75 15.75,15.2775 15.75,14.865 L15.75,12.2775 C15.75,11.8725 15.4125,11.535 15.0075,11.535 Z" 
    fill="#007AFF" fill-rule="nonzero"></path></svg>`;

                button.innerHTML = `${svg} ${content_button[i].text}`;
            }

            if (content_button[i].type == "URL") {
                const svg = `<svg viewBox="0 -2 18 22" height="18" width="18" preserveAspectRatio="xMidYMid meet" style="vertical-align: middle; margin: 0 3px 0 0;">
    <path d="M14,5.41421356 L9.70710678,9.70710678 C9.31658249,10.0976311 8.68341751,10.0976311 8.29289322,9.70710678 C7.90236893,9.31658249 7.90236893,8.68341751 8.29289322,8.29289322 L12.5857864,4 L10,4 C9.44771525,4 9,3.55228475 9,3 C9,2.44771525 9.44771525,2 10,2 L14,2 C15.1045695,2 16,2.8954305 16,4 L16,8 C16,8.55228475 15.5522847,9 15,9 C14.4477153,9 14,8.55228475 
    14,8 L14,5.41421356 Z M14,12 C14,11.4477153 14.4477153,11 15,11 C15.5522847,11 16,11.4477153 16,12 L16,13 C16,14.6568542 14.6568542,16 13,16 L5,16 C3.34314575,16 2,14.6568542 2,13 L2,5 C2,3.34314575 3.34314575,2 5,2 L6,2 C6.55228475,2 7,2.44771525 7,3 C7,3.55228475 6.55228475,4 6,4 L5,4 C4.44771525,4 4,4.44771525 4,5 L4,13 C4,13.5522847 4.44771525,14 5,14 L13,14 C13.5522847,14 14,13.5522847 14,13 L14,12 Z" 
    fill="#007AFF" fill-rule="nonzero"></path></svg>`;

                button.innerHTML = `${svg} ${content_button[i].text}`;

                if (content_button[i].url.includes("{{1}}")) {

                    button.dataset.url = content_button[i].url.replace("{{1}}", button_component[0]?.parameters[0].text);
                    button_component.shift();
                } else button.dataset.url = content_button[i].url;
            }

            buttons.append(button);
        }

        return buttons;
    }
}

async function requestOpenAI(media_url) {
    const key = localStorage.getItem("userToken");
    const errorMsg = GLOBAL_LANG.report_chatbot_chat_transcribe_audio_error_response;

    try {
        const myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");

        const requestOptions = {
            method: "POST",
            headers: myHeaders,
            body: JSON.stringify({ "audio_url": media_url }),
            redirect: 'follow'
        };

        const response = await fetch("https://services.talkall.com.br:4090/openai/transcription?key=" + key, requestOptions);
        const data = await response.json();

        if (data.reason === "api_key not found")
            return "error";

        return data.new || errorMsg;

    } catch (error) {
        return errorMsg;
    }
}

async function transcribeAudio(media_url, event) {
    const audioText = document.createElement("span");
    audioText.innerHTML = GLOBAL_LANG.report_chatbot_chat_audio_text;

    const message = event.target.parentElement;
    message.lastChild.remove();
    message.style.paddingBottom = "5px";
    message.appendChild(audioText);

    const text = await this.requestOpenAI(media_url);

    if (text === "error") {
        const url = document.createElement("a");
        url.href = document.location.origin + "/integration/add/openai";
        url.target = "_blank";
        url.textContent = "/integration/add/openai";

        audioText.innerHTML = GLOBAL_LANG.report_chatbot_chat_transcribe_audio_error_apy_key;
        audioText.appendChild(url);
    } else {
        audioText.innerHTML = text;
    }
}

function setReaction(json) {
    if (json.reaction !== null && json.reaction !== '') {
        const div_reaction_container = document.createElement("div");
        div_reaction_container.className = "messageRigth reaction-container";
        div_reaction_container.style.marginRight = "10px";

        const div_reaction = document.createElement("div");
        div_reaction.className = "reaction-box";

        const span_reaction = document.createElement("span");
        span_reaction.className = "scale-effect";
        span_reaction.innerText = json.reaction;

        div_reaction.appendChild(span_reaction);
        div_reaction_container.appendChild(div_reaction);

        return div_reaction_container;
    }
}