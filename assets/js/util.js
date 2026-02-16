var ta = undefined;
var Util = {
    doTruncarStr(str, size) {
        if (str == undefined || str == 'undefined' || str == '' || size == undefined || size == 'undefined' || size == '') {
            return str;
        }
        var shortText = str;
        if (str.length >= size + 3) {
            shortText = str.substring(0, size).concat('...');
        }
        return shortText;
    }, dateDiffInDays(a, b) {
        const _MS_PER_DAY = 1000 * 60 * 60 * 24;
        const utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
        const utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());
        return Math.floor((utc2 - utc1) / _MS_PER_DAY);
    },
    nl2br(str, is_xhtml) {
        if (typeof str !== 'string' || !str) {
            return '';
        }
        str = str.replace(/\*(.*?)\*/g, function (match, p1) {
            return '<b>' + p1 + '</b>';
        });
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2').replace(/\\n|\\r/g, "<br>").replace("''", "'").replace('""', '"').replace(/\n\r/g, "<br>");
    },
    FormatShortDate(timestamp) {
        let date = new Date(timestamp * 1000);
        let today = new Date();

        let currentDate = today.getFullYear() + '/' + today.getMonth() + '/' + today.getDate();
        let dt = date.getFullYear() + '/' + date.getMonth() + '/' + date.getDate();

        if (currentDate == dt) {
            return GLOBAL_LANG.messenger_day_week_today;
        } else {
            let diff = this.dateDiffInDays(date, today);
            if (diff < 3) {
                switch (diff) {
                    case 1:
                        return GLOBAL_LANG.messenger_day_week_yesterday;
                    case 2:
                        return GLOBAL_LANG.messenger_day_week_day_before_yesterday;
                }
            } else {
                if (diff < 7) {
                    let semana = [GLOBAL_LANG.messenger_day_week_sunday, GLOBAL_LANG.messenger_day_week_monday, GLOBAL_LANG.messenger_day_week_tuesday, GLOBAL_LANG.messenger_day_week_wednesday, GLOBAL_LANG.messenger_day_week_thursday, GLOBAL_LANG.messenger_day_week_friday, GLOBAL_LANG.messenger_day_week_saturday];
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
    pad(num) {
        return ("0" + num).slice(-2);
    },
    toDataURL(src, callback, outputFormat) {
        var img = new Image();
        img.crossOrigin = 'Anonymous';
        img.onload = function () {
            var canvas = document.createElement('CANVAS');
            var ctx = canvas.getContext('2d');
            var dataURL;
            canvas.height = this.naturalHeight;
            canvas.width = this.naturalWidth;
            ctx.drawImage(this, 0, 0);
            dataURL = canvas.toDataURL(outputFormat);
            callback(dataURL);
        };
        img.src = src;
        if (img.complete || img.complete === undefined) {
            img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
            img.src = src;
        }
    },
    FormatDuration(input) {
        if (input == null || input == "null") {
            return 0
        }
        
        let seconds;

        // Se o input já for um número, use diretamente
        if (typeof input === 'number') {
            seconds = input;
        } else {
            // Caso contrário, remova emojis/caracteres especiais
            const cleanedInput = input.replace(/[^\d:]/g, '');

            // Verifica se o valor é no formato "00:13"
            if (cleanedInput.includes(":")) {
                const parts = cleanedInput.split(':');
                const minutes = parseInt(parts[0], 10);
                const sec = parseInt(parts[1], 10);
                seconds = (minutes * 60) + sec;
            } else {
                // Caso contrário, assume que é um número
                seconds = parseInt(cleanedInput, 10);
            }
        }

        return new Date(seconds * 1000).toISOString().substr(14, 5);
    },
    FormatShortTime(timestamp) {
        var date = new Date(timestamp * 1000);
        let hours = date.getHours();
        let minutes = date.getMinutes();
        return this.pad(hours) + ":" + this.pad(minutes)
    }, getTypeThumbMsg(type) {
        switch (parseInt(type)) {
            case 1:
                return "";
            case 2:
                return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 12 20\"><path fill=\"currentColor\" fill-opacity=\".4\" d=\"M6 11.745a2 2 0 0 0 2-2V4.941a2 2 0 0 0-4 0v4.803a2 2 0 0 0 2 2.001zm3.495-2.001c0 1.927-1.568 3.495-3.495 3.495s-3.495-1.568-3.495-3.495H1.11c0 2.458 1.828 4.477 4.192 4.819v2.495h1.395v-2.495c2.364-.342 4.193-2.362 4.193-4.82H9.495v.001z\"/></svg>";
            case 3:
                return "<span style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;'><svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 16 20\"><path fill=\"currentColor\" fill-opacity=\".4\" d=\"M13.822 4.668H7.14l-1.068-1.09a1.068 1.068 0 0 0-.663-.278H3.531c-.214 0-.51.128-.656.285L1.276 5.296c-.146.157-.266.46-.266.675v1.06l-.001.003v6.983c0 .646.524 1.17 1.17 1.17h11.643a1.17 1.17 0 0 0 1.17-1.17v-8.18a1.17 1.17 0 0 0-1.17-1.169zm-5.982 8.63a3.395 3.395 0 1 1 0-6.79 3.395 3.395 0 0 1 0 6.79zm0-5.787a2.392 2.392 0 1 0 0 4.784 2.392 2.392 0 0 0 0-4.784z\"/></svg></span>";
            case 4:
            case 10:
                return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 13 20\"><path fill=\"currentColor\" fill-opacity=\".4\" d=\"M10.2 3H2.5C1.7 3 1 3.7 1 4.5v10.1c0 .7.7 1.4 1.5 1.4h7.7c.8 0 1.5-.7 1.5-1.5v-10C11.6 3.7 11 3 10.2 3zm-2.6 9.7H3.5v-1.3h4.1v1.3zM9.3 10H3.5V8.7h5.8V10zm0-2.7H3.5V6h5.8v1.3z\"/></svg>";
            case 5:
                return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 16 14\"><path fill=\"currentColor\" d=\"M14.987 2.668l-3.48 3.091v-2.27c0-.657-.532-1.189-1.189-1.189H1.689C1.032 2.3.5 2.832.5 3.489v7.138c0 .657.532 1.189 1.189 1.189h8.629c.657 0 1.189-.532 1.189-1.189V8.328l3.48 3.09v-8.75z\"/></svg>";
            case 6:
                return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\"><path id=\"Combined-Shape\" fill=\"currentColor\" d=\"M4.878 3.9h10.285c1.334 0 1.818.139 2.306.4s.871.644 1.131 1.131c.261.488.4.972.4 2.306v4.351c0 1.334-.139 1.818-.4 2.306a2.717 2.717 0 0 1-1.131 1.131c-.488.261-.972.4-2.306.4H4.878c-1.334 0-1.818-.139-2.306-.4s-.871-.644-1.131-1.131-.4-.972-.4-2.306V7.737c0-1.334.139-1.818.4-2.306s.643-.87 1.131-1.131.972-.4 2.306-.4zm6.193 5.936c-.001-.783.002-1.567-.003-2.35a.597.597 0 0 0-.458-.577.59.59 0 0 0-.683.328.907.907 0 0 0-.062.352c-.004 1.492-.003 2.984-.002 4.476 0 .06.002.121.008.181a.592.592 0 0 0 .468.508c.397.076.728-.196.731-.611.004-.768.001-1.537.001-2.307zm-3.733.687c0 .274-.005.521.002.768.003.093-.031.144-.106.19a2.168 2.168 0 0 1-.905.292c-.819.097-1.572-.333-1.872-1.081a2.213 2.213 0 0 1-.125-1.14 1.76 1.76 0 0 1 1.984-1.513c.359.05.674.194.968.396a.616.616 0 0 0 .513.112.569.569 0 0 0 .448-.464c.055-.273-.055-.484-.278-.637-.791-.545-1.677-.659-2.583-.464-2.006.432-2.816 2.512-2.08 4.196.481 1.101 1.379 1.613 2.546 1.693.793.054 1.523-.148 2.2-.56.265-.161.438-.385.447-.698.014-.522.014-1.045.001-1.568-.007-.297-.235-.549-.51-.557a37.36 37.36 0 0 0-1.64-.001c-.21.004-.394.181-.446.385a.494.494 0 0 0 .217.559.714.714 0 0 0 .313.088c.296.011.592.004.906.004zm6.477-2.519h.171c.811 0 1.623.002 2.434-.001.383-.001.632-.286.577-.654-.041-.274-.281-.455-.611-.455h-3.074c-.474 0-.711.237-.711.713v4.479c0 .243.096.436.306.56.41.241.887-.046.896-.545.009-.504.002-1.008.002-1.511v-.177h.169c.7 0 1.4.001 2.1-.001a.543.543 0 0 0 .535-.388c.071-.235-.001-.488-.213-.611a.87.87 0 0 0-.407-.105c-.667-.01-1.335-.005-2.003-.005h-.172V8.004z\"/></svg>";
            case 7:
                return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 13 20\"><path fill=\"currentColor\" fill-opacity=\".4\" d=\"M6.487 3.305A4.659 4.659 0 0 0 1.8 7.992c0 3.482 4.687 8.704 4.687 8.704s4.687-5.222 4.687-8.704a4.659 4.659 0 0 0-4.687-4.687zm0 6.36c-.937 0-1.674-.737-1.674-1.674s.736-1.674 1.674-1.674 1.674.737 1.674 1.674c0 .938-.737 1.674-1.674 1.674z\"/></svg>";
            case 9:
                return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 14 20\"><path fill=\"currentColor\" fill-opacity=\".4\" d=\"M6.844 9.975a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5.759 3.087c-.884-.845-3.136-1.587-5.721-1.587-2.584 0-4.739.742-5.622 1.587-.203.195-.26.464-.26.746v1.679h12v-1.679c0-.282-.193-.552-.397-.746z\"></path></svg>";
            case 11:
                return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 13 20\"><path fill=\"currentColor\" fill-opacity=\".4\" d=\"M10.2 3H2.5C1.7 3 1 3.7 1 4.5v10.1c0 .7.7 1.4 1.5 1.4h7.7c.8 0 1.5-.7 1.5-1.5v-10C11.6 3.7 11 3 10.2 3zm-2.6 9.7H3.5v-1.3h4.1v1.3zM9.3 10H3.5V8.7h5.8V10zm0-2.7H3.5V6h5.8v1.3z\"/></svg>";
            case 18:
                return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: 0px;' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 17 19'><path fill='currentColor' d='M12.629 12.463a5.17 5.17 0 0 0-7.208-7.209l7.208 7.209zm-1.23 1.229L4.191 6.484a5.17 5.17 0 0 0 7.208 7.208zM8.41 2.564a6.91 6.91 0 1 1 0 13.82 6.91 6.91 0 0 1 0-13.82z'></path></svg>";
            case 26:
                return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 16 16\"><path id=\"Combined-Shape_1_\" fill=\"currentColor\" fill-opacity=\".4\" d=\"M9.179 14.637c.061-.14.106-.29.135-.45.031-.171.044-.338.049-.543a9.05 9.05 0 0 0 .003-.233l.001-.067v-.072l.002-.216c.01-.364.032-1.205.08-1.473.052-.287.136-.538.255-.771a2.535 2.535 0 0 1 1.125-1.111 2.8 2.8 0 0 1 .786-.255c.27-.048 1.098-.07 1.487-.08l.152.001h.047l.325-.004a3.63 3.63 0 0 0 .554-.048 2.06 2.06 0 0 0 .494-.151 4.766 4.766 0 0 1-1.359 2.429 143.91 143.91 0 0 1-2.057 1.924 4.782 4.782 0 0 1-2.079 1.12zm-1.821.16l-.474.012a9.023 9.023 0 0 1-1.879-.11 4.747 4.747 0 0 1-1.314-.428 4.376 4.376 0 0 1-1.123-.807 4.354 4.354 0 0 1-.816-1.11 4.584 4.584 0 0 1-.434-1.303 8.783 8.783 0 0 1-.12-1.356 29.156 29.156 0 0 1-.009-.617c-.002-.206-.002-.37-.002-.736v-.674l.001-.549.001-.182c.001-.223.004-.426.009-.62a8.69 8.69 0 0 1 .121-1.358c.087-.476.229-.903.434-1.301a4.399 4.399 0 0 1 1.936-1.916 4.7 4.7 0 0 1 1.315-.429 8.926 8.926 0 0 1 1.379-.12c.72-.009.989-.011 1.359-.011h.528c.896.003 1.143.005 1.366.011.55.015.959.046 1.371.12.482.085.913.226 1.314.428a4.396 4.396 0 0 1 1.937 1.915c.206.4.348.827.434 1.302.075.412.107.819.121 1.356.006.198.009.402.01.619v.024c0 .069-.001.132-.003.194a2.61 2.61 0 0 1-.033.391.902.902 0 0 1-.494.677 1.05 1.05 0 0 1-.29.094 2.734 2.734 0 0 1-.395.033l-.311.004h-.039l-.163-.001c-.453.012-1.325.036-1.656.096a3.81 3.81 0 0 0-1.064.348 3.541 3.541 0 0 0-.911.655c-.267.263-.49.566-.661.899-.166.324-.281.67-.351 1.054-.06.33-.085 1.216-.096 1.636l-.002.23v.069l-.001.067c0 .074-.001.143-.003.213-.004.158-.014.28-.033.388a.902.902 0 0 1-.494.676 1.054 1.054 0 0 1-.289.093 1.335 1.335 0 0 1-.176.024z\"/></svg>";
        }
    },
    makeId() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for (var i = 0; i < 32; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        return text;
    },
    msgWait() {
        return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#ffffff' d='M9.75 7.713H8.244V5.359a.5.5 0 0 0-.5-.5H7.65a.5.5 0 0 0-.5.5v2.947a.5.5 0 0 0 .5.5h.094l.003-.001.003.002h2a.5.5 0 0 0 .5-.5v-.094a.5.5 0 0 0-.5-.5zm0-5.263h-3.5c-1.82 0-3.3 1.48-3.3 3.3v3.5c0 1.82 1.48 3.3 3.3 3.3h3.5c1.82 0 3.3-1.48 3.3-3.3v-3.5c0-1.82-1.48-3.3-3.3-3.3zm2 6.8a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2v-3.5a2 2 0 0 1 2-2h3.5a2 2 0 0 1 2 2v3.5z'></path></svg>";
    },
    msgSend() {
        return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#ffffff' d='M10.91 3.316l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";
    },
    msgReceived() {
        return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#ffffff' d='M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.879a.32.32 0 0 1-.484.033l-.358-.325a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";
    },
    msgRead() {
        return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#00ffff' d='M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.879a.32.32 0 0 1-.484.033l-.358-.325a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";
    },
    decodeProfile(hash, media_url) {

        var settings = {
            "url": media_url,
            "method": "GET",
            "timeout": 0,
        };

        $.ajax(settings).done(function (response) {

            const blob = Util.b64toBlob(response.file.base64, response.type);
            const blobUrl = URL.createObjectURL(blob);

            $("#" + hash + " .contact-image .avatar").attr("src", blobUrl);
            $("#" + hash + "_imgProfileTeam").attr("src", blobUrl);
        });
    },
    b64toBlob(b64Data, contentType = '', sliceSize = 512) {

        const byteCharacters = atob(b64Data);
        const byteArrays = [];

        for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {

            const slice = byteCharacters.slice(offset, offset + sliceSize);
            const byteNumbers = new Array(slice.length);

            for (let i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            const byteArray = new Uint8Array(byteNumbers);
            byteArrays.push(byteArray);
        }

        const blob = new Blob(byteArrays, { type: contentType });
        return blob;
    },
    decodeProfile(hash, media_url) {

        var settings = {
            "url": media_url,
            "method": "GET",
            "timeout": 0,
        };

        $.ajax(settings).done(function (response) {

            const blob = Util.b64toBlob(response.file.base64, response.type);
            const blobUrl = URL.createObjectURL(blob);

            $("#" + hash + " .contact-image .avatar").attr("src", blobUrl);
            $("#" + hash + "_imgProfileTeam").attr("src", blobUrl);
        });
    },
    decodeFile(key_id, media_url, type) {

        var settings = {
            "url": media_url + "/base64",
            "method": "GET",
            "timeout": 0,
        };

        $.ajax(settings).done(function (response) {

            const blob = Util.b64toBlob(response.file.base64, type);
            const blobUrl = URL.createObjectURL(blob);

            switch (type) {
                case "image/jpeg":
                case "image/jpg":
                case "image/png":
                case "image/webp":
                    $("#" + key_id + " img")[0].src = blobUrl;
                    $("#" + key_id + " .ImageMessage")[0].dataset.url = blobUrl;
                    break;
                case "audio/ogg":
                case "audio/ogg; codecs=opus":
                    $("#" + key_id + " audio")[0].src = blobUrl;
                    $("#" + key_id + " .AudioMessage")[0].dataset.url = blobUrl;
                    break;
                case "application/pdf":
                    $("#" + key_id + " .DocumentMessage")[0].dataset.url = blobUrl;
                    break;
                case "video/mp4":
                    $("#" + key_id + " .VideoMessage")[0].dataset.url = blobUrl;
                    break;
                default:
                    $("#" + key_id + " .DocumentMessage")[0].dataset.url = blobUrl;
                    break;
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

                const standardize = Util.scapeId(media_id);

                const audios = document.querySelectorAll(`#${standardize} audio`);
                const sources = document.querySelectorAll(`#${standardize} source`);

                let index = audios.length > 1 ? 1 : 0;

                audios[index].src = blobUrl;
                if(sources[index]?.src)  sources[index].src = blobUrl;
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
}


// Notification sounds

let sound_file = document.querySelector('#notify-song');
let sound_remove = document.querySelector('#sound_remove');
let sound_player = document.querySelector('#sound_player');
let container_sounds = document.querySelector('#container_sounds');
let load_sound = document.querySelector('#load_sound');
let notification_alert = localStorage.getItem('notification_alert');

const showPlayer = () => {

    if (container_sounds !== null) {

        sound_player.innerHTML = `
        <audio controls id="play_alert_wav">                        
            <source src="${notification_alert}" type="audio/wav">
        </audio>`;

        document.querySelector('#url_sound').value = notification_alert;
        container_sounds.classList.add("hide");
        sound_remove.classList.remove("hide");
        load_sound.classList.add("hide");
    }
}


if (notification_alert !== null) {
    showPlayer();
}

if (ta != undefined) {
    if (notification_alert === null) {
        setTimeout(() => {
            ta.user.getNotificationUrl();
        }, 3000);
    }
}

if (sound_file !== null) {
    sound_file.addEventListener('change', (e) => {

        const file = e.target.files[0];
        console.log(file.type)
        const formdata = new FormData();

        if (file.size < 1500000 && (file.type == 'audio/wav' || file.type == 'audio/mpeg')) {

            load_sound.classList.remove("hide");
            container_sounds.classList.add("hide");

            formdata.append("filetoupload", file);

            const requestOptions = {
                method: 'POST',
                body: formdata,
                redirect: 'follow'
            };

            fetch("https://files.talkall.com.br:3000", requestOptions)
                .then(response => response.text())
                .then((result) => {

                    const resp = JSON.parse(result);

                    sound_player.innerHTML = `
                    <audio controls id="play_alert_wav">                        
                        <source src="${resp.url}" type="${resp.mimetype}">
                    </audio>`;

                    document.querySelector('#url_sound').value = resp.url;
                    localStorage.setItem('notification_alert', resp.url);

                    sound_remove.classList.remove("hide");
                    load_sound.classList.add("hide");

                    ta.user.setNotificationUrl(resp.url);
                })
                .catch(error => console.log('error', error));

        } else {
            sound_file.value = '';
            document.querySelector('#container_sounds .box span').innerHTML = 'Carregar o Som <i class="fas fa-music"></i>'
            sound_player.innerHTML = `<div class="alert_w"><strong>Atenção!</strong> Apenas arquivo <b>.wav ou .mp3</b> de até 1MB.</div>`
            setTimeout(() => { sound_player.innerHTML = '' }, 4000);
        }

        e.preventDefault();
    });
}


if (sound_remove !== null) {
    sound_remove.addEventListener('click', (e) => {

        localStorage.removeItem('notification_alert');

        sound_remove.classList.add("hide");
        load_sound.classList.remove("hide");

        const play_alert = document.querySelector('#play_alert_wav');
        play_alert.classList.add("hide");

        sound_file.value = '';

        setTimeout(() => {
            ta.user.removeNotification();
        }, 1000);

        e.preventDefault();
    });
}


function removeNotification(json) {

    if (json.success === true) {
        load_sound.classList.add("hide");
        container_sounds.classList.remove("hide");
    }
}


function getNotificationUrl(json) {
    if (json.url != null) {
        localStorage.setItem('notification_alert', json.url);
        showPlayer();
    }
}

