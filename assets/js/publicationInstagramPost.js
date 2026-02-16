const Filters = JSON.parse(localStorage.getItem("filters")) || null;

const maskBehavior = (val) => {

    val = val.split(":");
    return (parseInt(val[0]) > 19) ? "HZ:M0" : "H0:M0";
}


const spOptions = {
    onKeyPress: function (val, e, field, options) {
        field.mask(maskBehavior.apply({}, arguments), options);
    },
    translation: {
        'H': { pattern: /[0-2]/, optional: false },
        'Z': { pattern: /[0-3]/, optional: false },
        'M': { pattern: /[0-5]/, optional: false }
    }
};


const Campaign = {
    cancel_list: [],

    push(token) {
        this.cancel_list.push({ token: token });
    },
    remove(token) {
        let index = Campaign.cancel_list.findIndex(item => item.token === token);
        if (index !== -1)
            this.cancel_list.splice(index, 1);
    },
    clear() {
        this.cancel_list = [];
    }
}


const Table = {
    row: {
        click(e) {
            const row = document.getElementById(e.target.id);

            if (row.checked) {
                Campaign.push(row.dataset.token);
                Table.row.selected(row);
            } else {
                Campaign.remove(row.dataset.token);
                Table.row.deselected(row);
            }
        },
        selected(row) {
            row.checked = true;
            row.classList.add("selected");
            row.parentNode.parentNode.parentNode.style.background = "#cfd2dbba";
        },
        deselected(row) {
            row.checked = false;
            row.classList.remove("selected");
            row.parentNode.parentNode.parentNode.style.background = "#ffffff";
        }
    }
}


function check_hour(date_start, time_start) {

    let date = date_start;
    date = date.split("/")[2] + "-" + date.split("/")[1] + "-" + date.split("/")[0] + " " + time_start;

    let date_current = new Date();
    date_current.setMinutes(date_current.getMinutes() + 30);

    let year = date_current.getFullYear();
    let month = date_current.getMonth() + 1;
    let day = date_current.getDate();
    let hour = date_current.getHours();
    let minutes = date_current.getMinutes();

    date_current = year + "-" + month + "-" + day + " " + hour + ":" + minutes;

    const date__start = new Date(date);
    const date__current = new Date(date_current);

    if (date__start > date__current) {
        return true;
    } else {
        return false;
    }
}


function getCurrentTime() {
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
}


function check_date(date_start) {

    let date = date_start;
    date = date.split("/")[2] + "-" + date.split("/")[1] + "-" + date.split("/")[0];

    let date_current = new Date();

    let year = date_current.getFullYear();
    let month = date_current.getMonth() + 1;
    let day = date_current.getDate();

    if (month < 10) month = "0" + month;
    if (day < 10) day = "0" + day;

    date_current = year + "-" + month + "-" + day;

    if (date === date_current) {
        const current_time = getCurrentTime();
        const time_start = document.getElementById('time_start');

        if (time_start.value < current_time) {
            time_start.value = current_time;
        }
    }

    const date__start = new Date(date);
    const date__current = new Date(date_current);

    if (date__start >= date__current) {
        return true;
    } else {
        return false;
    }
}


function validation() {

    const input_title = document.getElementById("input_title").value;
    const date_start = document.getElementById("date_start").value;
    const time_start = document.getElementById("time_start").value;
    const multiselect = document.getElementById("multiselect").value;
    const input_files = document.getElementById("file");

    let form_validation_tile = true;
    let form_validation_date = true;
    let form_validation_time = true;
    let form_validation_select = true;
    let form_validation_files = true;

    if (!input_title) {
        form_validation_tile = false;
        document.getElementById("alert_input_title").style.display = "block";
        document.getElementById("alert_input_title").innerHTML = GLOBAL_LANG.instagram_post_validation_title;
    } else {
        document.getElementById("alert_input_title").style.display = "none";
    }

    if (!check_date(date_start)) {
        form_validation_date = false;
        document.getElementById("alert_date_start").style.display = "block";
        document.getElementById("alert_date_start").innerHTML = GLOBAL_LANG.instagram_post_validation_date;
    } else {
        document.getElementById("alert_date_start").style.display = "none";
    }

    // if (!check_hour(date_start, time_start)) {
    //     form_validation_time = false;
    //     document.getElementById("alert_time_start").style.display = "block";
    //     document.getElementById("alert_time_start").innerHTML = GLOBAL_LANG.instagram_post_validation_hour;
    // } else {
    //     document.getElementById("alert_time_start").style.display = "none";
    // }

    if (!multiselect) {
        form_validation_select = false;
        document.getElementById("alert_multiselect").style.display = "block";
        document.getElementById("alert_multiselect").innerHTML = GLOBAL_LANG.instagram_post_validation_channel;
    } else {
        document.getElementById("alert_multiselect").style.display = "none";
    }

    if (input_files === null) {
        form_validation_files = false;
        document.getElementById("alert_dropArea").style.display = "block";
        document.getElementById("alert_dropArea").innerHTML = GLOBAL_LANG.instagram_post_validation_imagem;
    } else {
        document.getElementById("alert_dropArea").style.display = "none";
    }


    if (form_validation_tile && form_validation_select && form_validation_date && form_validation_files) {
        return 'true';
    } else {
        return 'false';
    }
}


function submit() {
    if (validation() === 'true') {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}


function advanceMin() {

    // let minutes = 0, date_current = new Date();
    // let timestamp = date_current.setMinutes(date_current.getMinutes() + minutes);

    // let tamp = timestamp, date = new Date(tamp);
    // let hour = date.getHours(), min = date.getMinutes();

    // if (min < 10) min = "0" + min; if (hour < 10) hour = "0" + hour;

    // let early_date = hour + ":" + min;

    // document.getElementById("time_start").value = early_date;
}


function callAlerts(alert) {

    document.getElementById("fileElem").value = null;

    switch (alert) {
        case "limit":
            swal({
                title: GLOBAL_LANG.instagram_post_alert_dropzone_limit_title,
                text: GLOBAL_LANG.instagram_post_alert_dropzone_limit_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.instagram_post_alert_dropzone_limit_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "limit_video":
            swal({
                title: GLOBAL_LANG.instagram_post_alert_dropzone_limit_video_title,
                text: GLOBAL_LANG.instagram_post_alert_dropzone_limit_video_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.instagram_post_alert_dropzone_limit_video_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "maxSize":
            swal({
                title: GLOBAL_LANG.instagram_post_alert_dropzone_maxSize_title,
                text: GLOBAL_LANG.instagram_post_alert_dropzone_maxSize_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.instagram_post_alert_dropzone_maxSize_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "not_allowed_image":
            swal({
                title: GLOBAL_LANG.instagram_post_alert_dropzone_not_allowed_image_title,
                text: GLOBAL_LANG.instagram_post_alert_dropzone_not_allowed_image_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.instagram_post_alert_dropzone_not_allowed_image_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "not_allowed_video":
            swal({
                title: GLOBAL_LANG.instagram_post_alert_dropzone_not_allowed_video_title,
                text: GLOBAL_LANG.instagram_post_alert_dropzone_not_allowed_video_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.instagram_post_alert_dropzone_not_allowed_video_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "maxHeight":
            swal({
                title: GLOBAL_LANG.instagram_post_alert_dropzone_out_of_proportion_title,
                text: GLOBAL_LANG.instagram_post_alert_dropzone_out_of_proportion_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.instagram_post_alert_dropzone_out_of_proportion_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "maxWidth":
            swal({
                title: GLOBAL_LANG.instagram_post_alert_dropzone_out_of_proportion_title,
                text: GLOBAL_LANG.instagram_post_alert_dropzone_out_of_proportion_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.instagram_post_alert_dropzone_out_of_proportion_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "pdf":
            swal({
                title: GLOBAL_LANG.instagram_post_alert_dropzone_pdf_title,
                text: GLOBAL_LANG.instagram_post_alert_dropzone_pdf_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.instagram_post_alert_dropzone_pdf_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "png":
            swal({
                title: GLOBAL_LANG.instagram_post_alert_dropzone_png_title,
                text: GLOBAL_LANG.instagram_post_alert_dropzone_png_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.instagram_post_alert_dropzone_png_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "small":
            swal({
                title: GLOBAL_LANG.instagram_post_alert_dropzone_small_title,
                text: GLOBAL_LANG.instagram_post_alert_dropzone_small_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.instagram_post_alert_dropzone_small_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "type":
            swal({
                title: GLOBAL_LANG.instagram_post_alert_dropzone_type_title,
                text: GLOBAL_LANG.instagram_post_alert_dropzone_type_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.instagram_post_alert_dropzone_type_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        default:
            break;
    }
}


function addPreviewVideo(data) {

    const img = document.getElementById("iden_" + data.ta_id);

    const id_slider_item = img.parentNode.attributes.id.nodeValue;

    const video = document.createElement("video");
    video.src = data.url;
    video.className = "video selected";

    img.remove();

    const slider = document.getElementById(id_slider_item);

    slider.appendChild(video);

}


function addPreview(data) {

    const sliderImg = document.querySelector(".slider-item");
    const background_picture = document.querySelector(".background-no-picture");

    if (background_picture != null) {
        background_picture.remove();
    }

    if (sliderImg != null) {
        sliderImg.remove();
    }

    const preview_post_body = document.querySelector(".preview-post-body");
    preview_post_body.style.display = "block";

    const slider = document.createElement("div");
    slider.className = "slider-item";
    slider.id = Math.floor(Math.random() * 100000);

    const img = document.createElement("img");
    img.className = data.recover == true ? (data.media_type == "p_image" ? "ml-media" : "ml-media loading load") : data.mimetype == "video/mp4" ? "ml-media loading load" : "ml-media";
    img.id = "iden_" + data.ta_id;

    if (data.media_type === "p_video" || data.mimetype == "video/mp4") {

        img.src = document.location.origin + "/assets/img/loads/loading_2.gif";
        setTimeout(() => addPreviewVideo(data), 500);
    } else {

        img.src = "data:image/jpeg;base64," + data.thumbnail;
    }
    img.draggable = false;

    slider.appendChild(img);
    document.getElementById("box__slider").appendChild(slider);
}


function closeImg() {

    last_id = $(".box-post").last().attr("id");
    document.getElementById(this.parentNode.parentNode.id).remove();

    if (document.querySelectorAll(".post").length == 0) {

        $(".slider-item").remove();

        document.querySelector(".drop-inner-img").style.display = "block";
        document.querySelector(".drop-inner-text").style.display = "block";
        document.querySelector(".drop-inner-title").style.display = "block";

        const background_no_picture = document.createElement("div");
        background_no_picture.className = "background-no-picture";

        const img = document.createElement("img");
        img.src = document.location.origin + "/assets/img/panel/background_no_picture.jpg";
        img.style.opacity = 1;
        img.draggable = false;

        background_no_picture.appendChild(img);
        document.getElementById("box__slider").appendChild(background_no_picture);
    }
}


function mouseout() {
    const post = document.getElementById(this.id);
    post.children[0].children[1].style.display = "none";
}


function mouseover() {
    const post = document.getElementById(this.id);
    post.children[0].children[1].style.display = "block";
}


function addthumbail(data) {

    const elm = document.getElementById(data.ta_id);
    elm.children[0].className = data.recover == true ? (data.media_type === "p_image" ? "img" : "video") : "img";
    elm.children[0].draggable = false;
    elm.children[0].src = data.recover == true ? (data.media_type === "p_image" ? "data:image/jpeg;base64," + data.thumbnail : data.url) : "data:image/jpeg;base64," + data.thumbnail;
    elm.children[2].value = data.url;

    const post = document.querySelectorAll(".post");
    const close = document.querySelectorAll(".close");

    post.forEach((elm) => elm.addEventListener("mouseover", mouseover));
    post.forEach((elm) => elm.addEventListener("mouseout", mouseout));

    close.forEach((img) => img.addEventListener("click", closeImg));

    addPreview(data);
}


function createPost(data) {

    document.querySelector(".drop-inner-img").style.display = "none";
    document.querySelector(".drop-inner-title").style.display = "none";
    document.querySelector(".drop-inner-text").style.display = "none";

    let post = document.createElement("div");
    post.className = "col-4 post";
    post.id = Math.floor(Math.random() * 100000);

    let boxPost = document.createElement("div");
    boxPost.className = "box-post";
    boxPost.classList.add(data.media_type === "p_image" ? "p_image" : "p_video");
    boxPost.id = data.ta_id;
    boxPost.draggable = false;

    let archive = document.createElement(data.media_type === "p_image" ? "img" : "video");
    archive.className = "load";
    archive.src = document.location.origin + "/assets/img/loads/loading_2.gif";

    let close = document.createElement("img");
    close.className = "close";
    close.src = document.location.origin + "/assets/img/statusClose.png";

    let file = document.createElement("input");
    file.id = "file";
    file.type = "hidden";
    file.name = "file";
    file.className = "file";

    boxPost.appendChild(archive);
    boxPost.appendChild(close);
    boxPost.appendChild(file);

    post.appendChild(boxPost);

    const dropArea = document.getElementById("dropArea");
    dropArea.appendChild(post);
}


function validateProportion(myFiles, mywidth, myheight) {

    let alert = false;
    const height = myheight - 200;
    const maxFileSize = 4 * 1024 * 1024;
    const post = document.querySelectorAll(".post");

    if (height > mywidth) {
        if (!alert) callAlerts("maxHeight"); alert = true;

    } else if (myheight < 300 || mywidth < 300) {
        if (!alert) callAlerts("small"); alert = true;

    } else if (myFiles.type != 'image/png' && myFiles.type != 'image/jpg' && myFiles.type != 'image/jpeg' && myFiles.type != 'video/mp4' && myFiles.type != 'application/pdf') {
        if (!alert) callAlerts("type"); alert = true;

    } else {

        const distanceFrom16by9 = Math.abs(mywidth / myheight - 1);
        const distanceFrom3by1 = Math.abs(mywidth / myheight - 3);

        const ratio = distanceFrom16by9 < distanceFrom3by1 ? "1:1" : "3:1";

        switch (ratio) {
            case "1:1":

                if (post.length === 1) {
                    if (!alert) callAlerts("limit"); alert = true;

                } else if (myFiles.size > maxFileSize) {
                    if (!alert) callAlerts("maxSize"); alert = true;

                } else if (myFiles.type === "application/pdf") {
                    if (!alert) callAlerts("pdf"); alert = true;

                } else if (myFiles.type === "image/png") {
                    if (!alert) callAlerts("png"); alert = true;

                } else {
                    upload(myFiles);
                }

                break;
            case "3:1":
                if (!alert) callAlerts("maxHeight"); alert = true;
                break;
            default:
                break;
        }

    }
}


function upload(myFiles) {

    const formData = new FormData();
    const id = Math.floor(Math.random() * 100000);

    const data = {
        "ta_id": id,
        "recover ": false,
        "media_type": "p_image"
    }

    createPost(data);

    formData.append("filetoupload", myFiles);
    formData.append("ta_id", id);

    const settings = {
        "url": "https://files.talkall.com.br:3000",
        "method": "POST",
        "timeout": 0,
        "crossDomain": true,
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        "data": formData
    };

    $.ajax(settings).done(function (response) {
        addthumbail(JSON.parse(response));
    });
}


function handleFiles(files) {

    const myFiles = [];
    const _URL = window.URL;

    if (files[0].type === "image/jpeg" || files[0].type === "image/jpg" || files[0].type === "video/mp4") {

        myFiles.push(files[0])

        if (files[0].type === "video/mp4") {

            const reader = new FileReader();
            reader.onload = function (e) {

                const url = URL.createObjectURL(files[0]);
                const video = document.createElement('video');

                video.onloadedmetadata = evt => {

                    URL.revokeObjectURL(url);
                    validateProportion(files[0], video.videoWidth, video.videoWidth);
                };

                video.src = url;
                video.load();

            };

            reader.readAsDataURL(files[0]);

        } else if (files[0].type === "image/jpeg" || files[0].type === "image/jpg") {

            if (files[0].name.includes("jfif")) {
                callAlerts("type");
                return;
            }

            let img;

            if ((element = files[0])) {

                img = new Image();
                img.onload = function () {

                    if ((file = files[0])) {
                        validateProportion(files[0], this.width, this.height);
                    }
                }
            }

            img.src = _URL.createObjectURL(element);

        }
    } else if (files[0].type === "image/png" || files[0].type === "application/pdf") {

        if (files[0].type === "image/png") {
            callAlerts("png");

        } else if (files[0].type === "application/pdf") {
            callAlerts("pdf");

        }

    } else {
        validateProportion(files[0], this.width, this.height);
    }

}


function dragOverHandler(ev) {
    ev.preventDefault();
}


function dropHandler(ev) {
    ev.preventDefault();

    if (ev.dataTransfer.items) {

        var files = [];

        for (let i = 0; i < ev.dataTransfer.items.length; i++) {
            if (ev.dataTransfer.items[i].type == 'image/jpeg' ||
                ev.dataTransfer.items[i].type == 'image/jpg' ||
                ev.dataTransfer.items[i].type == 'image/png' ||
                ev.dataTransfer.items[i].type == 'application/pdf' ||
                ev.dataTransfer.items[i].type == 'video/mp4') {
                let file = ev.dataTransfer.items[i].getAsFile();
                files.push(file);
            } else {
                callAlerts("type");
                return;
            }
        }

    } else {
        for (let i = 0; i < ev.dataTransfer.files.length; i++) {
            console.log('... file[' + i + '].name = ' + ev.dataTransfer.files[i].name);
            console.log('... file[' + i + '].name = ' + ev.dataTransfer.files[i]);
        }
    }

    handleFiles(files);

}


function cancelAllPost() {

    let tokens = new FormData();

    Campaign.cancel_list.forEach(element => {
        tokens.append("tokens[]", element.token);
    });

    swal({
        title: GLOBAL_LANG.instagram_post_alert_group_delete_title,
        text: GLOBAL_LANG.instagram_post_alert_group_delete_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.instagram_post_alert_group_delete_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.instagram_post_alert_group_delete_cancelButtonText,
    }).then(t => {
        if (t.value == true) {
            $.ajax({
                type: "POST",
                url: document.location.origin + "/publication/instagram/post/cancelgroup",
                data: tokens,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    t.value && swal({
                        title: GLOBAL_LANG.instagram_post_alert_group_delete_two_title,
                        text: GLOBAL_LANG.instagram_post_alert_group_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });

                    Campaign.clear();
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                    document.getElementById("btn-cancel").style.display = "none";
                },
                error: function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.instagram_post_validation_error_title,
                        text: GLOBAL_LANG.instagram_post_validation_error_message,
                        type: "error",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });

                    Campaign.clear();
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                    document.getElementById("btn-cancel").style.display = "none";
                }
            });
        }
    });
}


function cancelPost(e) {

    swal({
        title: GLOBAL_LANG.instagram_post_alert_delete_title,
        text: GLOBAL_LANG.instagram_post_alert_delete_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.instagram_post_alert_delete_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.instagram_post_alert_delete_cancelButtonText,
    }).then(t => {
        if (t.value == true) {
            $.post(`post/cancel/${e.dataset.token}`, function (data) {
                if (data.success?.status === true) {
                    t.value && swal({
                        title: GLOBAL_LANG.instagram_post_alert_delete_two_title,
                        text: GLOBAL_LANG.instagram_post_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                } else {
                    t.value && swal({
                        title: GLOBAL_LANG.instagram_post_validation_error_title,
                        text: GLOBAL_LANG.instagram_post_validation_error_message,
                        type: "error",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                }

                Campaign.clear();
                $("#datatable-basic").DataTable().ajax.reload(null, false);
                document.getElementById("btn-cancel").style.display = "none";
            });
        }
    });
}


function viewPost(e) {

    const elm = document.getElementById(e.id);

    const id = elm.dataset.id;
    const token = elm.dataset.token;

    window.location.href = "post/view/" + id + "_" + token;
}


function removeCharacter() {

    const list = document.querySelectorAll(".list-channel span");
    const last_elm = [...list].pop();

    const character = last_elm.innerHTML;
    const text = character.substring(0, character.length - 1);

    last_elm.innerHTML = text;
}


function onErrorImg(img) {
    console.log(img)
    if (error1) {
        return img.src = document.location.origin = "/assets/img/panel/instagram.png";
    }
}


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.instagram.search) {
            document.getElementById("search").value = Filters.instagram.search;
        }

        if (Filters.instagram.input_search) {
            document.getElementById("input-search").value = Filters.instagram.input_search;
        }

        if (Filters.instagram.channel.length !== 0) {
            modalFilter();
            document.getElementById("check-select2").click();

            document.querySelectorAll("#mult-select2 .cust_").forEach((element, index) => {

                if (Filters.instagram.channel.includes(element.value))
                    document.querySelectorAll("#mult-select2 .cust_")[index].click();
            })
        }

        if (Filters.instagram.status) {
            modalFilter();
            document.getElementById("check-status").click();
            document.getElementById("select-status").value = Filters.instagram.status;
        }

        if (Filters.instagram.dt_start) {
            modalFilter();

            document.getElementById("check-date").click();

            document.getElementById("dt-end").disabled = false;
            document.getElementById("dt-end").type = "date";
            document.getElementById("dt-start").type = "date";

            document.getElementById("dt-start").value = Filters.instagram.dt_start;
            document.getElementById("dt-end").value = Filters.instagram.dt_end;
        }

        Filters.btn_back = null;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
}

checkFilter();


function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "post/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                channel: $('#verify-select2').val() == "2" ? "" : $('#multiselect2').val(),
                status: $('#select-status').val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            },
        },
        columns: [
            {
                mData: ''
            },
            {
                mData: 'schedule'
            },
            {
                mData: 'name'
            },
            {
                mData: 'title'
            },
            {
                mData: 'status'
            },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return `<div class='${full.status == 2 || full.status == 4 || full.status == 5 ? "checkbox-table-disabled" : "checkbox-table"}'>
                                <input type='checkbox' class='check-box' ${full.status == 2 || full.status == 4 || full.status == 5 ? "disabled" : ""} id='${Math.floor(Math.random() * 100000)}' data-id='${full.id_channel}' data-token='${full.token}' name='verify_check_box[]'>
                            </div>`;
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    let ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.instagram_post_datatable_column_status_processing}</span>`
                    switch (full.status) {
                        case '1':
                        case '6':
                            ret = `<span class="badge badge-sm badge-info">${GLOBAL_LANG.instagram_post_datatable_column_status_sending}</span>`
                            break;
                        case '2':
                            ret = `<span class="badge badge-sm badge-success">${GLOBAL_LANG.instagram_post_datatable_column_status_send}</span>`
                            break;
                        case '3':
                            ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.instagram_post_datatable_column_status_processing}</span>`
                            break;
                        case '4':
                            ret = `<span class="badge badge-sm badge-danger">${GLOBAL_LANG.instagram_post_datatable_column_status_canceling}</span>`
                            break;
                        case '5':
                            ret = `<span class="badge badge-sm badge-danger">${GLOBAL_LANG.instagram_post_datatable_column_status_called_off}</span>`
                            break;
                        default:
                            ret = ret
                            break;
                    }
                    return `<b>${ret}</b>`;
                }
            },
            {
                targets: 5,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                        <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                            <i class="fa fa-ellipsis-v"></i>
                                        <div class="ripple-container"></div>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                            <a id="${Math.floor(Math.random() * 100000)}" data-id="${full.id_channel}"data-token="${full.token}" class="dropdown-item table-action-view" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="fa fa-eye" style="font-size: 11pt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.instagram_post_datatable_column_action_view}</span>
                                            </a>
                                            <a id="${Math.floor(Math.random() * 100000)}" data-id="${full.id_channel}" data-token="${full.token}" class="dropdown-item ${full.status == 2 || full.status == 4 || full.status == 5 ? 'table-action-deleted disabled' : 'table-action-delete'}" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="fas fa-times" style="font-size: 12pt; margin-left: 3px"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.instagram_post_datatable_column_action_cancel}</span>
                                            </a>
                                        </div>
                                    </div>`
                }
            },
            {
                orderable: false,
                targets: [0, 4]
            }
        ],
        order: [[1, 'desc']],
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
            for (const row of [...document.querySelectorAll(".check-box")]) {
                for (const campaign of Campaign.cancel_list) {
                    if (campaign.token == row.dataset.token) {
                        Table.row.selected(row);
                    }
                }
            }

            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            } else {
                $("#modalExport").attr('disabled', false);
            }

            const instagram = {
                search: $("#search").val(),
                input_search: $("#input-search").val(),
                channel: $("#multiselect2").val(),
                status: $("#select-status").val(),
                dt_start: $("#dt-start").val(),
                dt_end: $("#dt-end").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.instagram = instagram;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}


$(document).ready(function () {

    const multiselectAdd = document.getElementById("multiselect");
    const verifyView = document.getElementById("verify-view");
    const verifyEdit = document.getElementById("verify-edit");

    if (multiselectAdd != null) {

        //** ADD **//

        let select = new MSFmultiSelect(
            document.querySelector('#multiselect'), {
            theme: 'theme1',
            selectAll: true,
            searchBox: true,
            width: "100%",
            height: 47,
            placeholder: GLOBAL_LANG.instagram_post_placeholder_channel,
            onChange: function (checked, value, instance) {
                if (select == "") select = value;

                if (checked == false) {
                    $(".ignore.add").children().prop('checked', false);
                };
            },
        });


        let fileSelect = document.getElementById("fileSelect"),
            fileElem = document.getElementById("fileElem");

        fileSelect.addEventListener("click", (e) => {
            if (fileElem) {
                fileElem.click();
            }
            e.preventDefault();
        }, false);


        $("#input_data").each(function () {
            this.setAttribute("style", "height:" + (this.scrollHeight) + "px;overflow-y:hidden;");
        }).on("input", function () {
            this.style.height = "auto";
            this.style.height = (this.scrollHeight) + "px";

            let caractrs = $("#input_data").val();
            let amount = document.getElementById("count_caracter");

            amount.textContent = 1024 - `${caractrs.length}`;
            amount.style.color = "red";
            amount.style.fontSize = ".875 rem";

            const description_post = document.getElementById("description-post");

            description_post.children[0].innerHTML = document.getElementById("input_data").value;

            let card_body = $(".card-body").css("height");
            let main_pw = document.querySelector(".main-pw");

            if (caractrs.length > 100) {
                $(".dp_row").css({ "height": "250px" });
            }

            let height_card_body = card_body.split("px")[0];

            if (parseInt(height_card_body) == 907) main_pw.style.height = "525px";
            if (parseInt(height_card_body) > 938) main_pw.style.height = "542px";
            if (parseInt(height_card_body) > 958) main_pw.style.height = "560px";
            if (parseInt(height_card_body) > 1000) main_pw.style.height = "570px";
            if (parseInt(height_card_body) == 1012) main_pw.style.height = "590px";
            if (parseInt(height_card_body) > 1100) main_pw.style.height = "600px";
            if (caractrs < 350) main_pw.style.height = "605px";

            $(".main-pw").scrollTop($(".main-pw").prop("scrollHeight"));
        });


        $("body").on("change", "label li input", function () {

            if ($(".logger").val() != "") {

                const id = $(".logger").val().split(",")[0].split("(")[1].split(")")[0];
                const list_insta = document.querySelectorAll("#list_insta input");

                let word = [];

                for (elm of list_insta) {

                    const id_insta = elm.attributes.id.nodeValue;

                    word = [id_insta];

                    word.filter((word) => {
                        if (word.indexOf(id) != -1) {

                            const name_insta = elm.attributes.class.nodeValue.split("(")[0];

                            const picture_profile_insta = document.getElementById("picture_profile_insta");
                            const name_profile_insta = document.querySelector("#name_profile_insta span");

                            name_profile_insta.innerHTML = name_insta;
                            picture_profile_insta.src = document.location.origin = "/assets/img/panel/instagram.png";

                        }
                    });
                }

            } else {

                const picture_profile_insta = document.getElementById("picture_profile_insta");
                const name_profile_insta = document.querySelector("#name_profile_insta span");

                name_profile_insta.innerHTML = "Instagram";
                picture_profile_insta.src = document.location.origin = "/assets/img/panel/instagram.png";

            }
        });


        if (verifyView == null) {

            advanceMin();

            $("form").submit(event => event.preventDefault());
            document.querySelector(".btn-success").addEventListener("click", submit);
        }


        $('.time').mask(maskBehavior, spOptions);


    } else {

        //** FIND **//

        $('#search').on('keyup', (e) => {
            if (e.which == 13) {
                document.getElementById("input-search").value = e.target.value;
                find();
            }
        });
        find();


        $("#btn-cancel").on("click", function () {
            cancelAllPost();
        });


        $("#datatable-basic").on("click", ".table-action-delete", function () {
            cancelPost(this);
        });


        $("#datatable-basic").on("click", ".checkbox-table", function (e) {
            this.firstElementChild.click();
        });


        $("#datatable-basic").on("click", ".check-box", function (e) {
            Table.row.click(e);
            e.stopPropagation();

            let cancel_list = Campaign.cancel_list.length;

            if (cancel_list > 0) {
                $("#btn-cancel").show();
            } else {
                $("#btn-cancel").hide();
            }

            $("#count_row").text(cancel_list);
        });


        $("#datatable-basic").on("click", ".table-action-view", function () {
            viewPost(this);
        });


        $('#sendEmailExport').on('click', () => modalExport());
        $("#modalFilter").one("click", () => modalFilter());
    }

    if (verifyEdit != null) {

        //** EDIT **//

        $(".recover").each(function (idx, elm) {


            let id = Math.floor(Math.random() * 100000);

            let data = {
                "ta_id": id,
                "thumbnail": elm.children[0].value,
                "url": elm.children[1].value,
                "recover": true,
                "media_type": elm.children[2].value == 5 ? "p_video" : "p_image"
            }

            createPost(data);
            addthumbail(data);

        });

        const input_data = document.getElementById("input_data");
        const description_post = document.getElementById("description-post");

        description_post.children[0].innerHTML = input_data.value;

    }

    if ($("#statusBlock").val() == 0) {
        $("#modal-no-registers").modal({ backdrop: 'static', keyboard: false });
    }

});


function modalFilter() {

    if (!$(".msf_multiselect_container").hasClass("msf_multiselect_container")) {
        const msf_multiselect_container = document.querySelectorAll(".msf_multiselect_container");

        for (elm of msf_multiselect_container) elm.remove();

        var select2 = new MSFmultiSelect(
            document.querySelector('#multiselect2'), {
            theme: 'theme2',
            selectAll: true,
            searchBox: true,
            width: 'auto',
            height: 47,
            onChange: function (checked, value, instance) {
                if (select2 == "") select2 = value;

                if (checked == false) {
                    $(".ignore.add").children().prop('checked', false);
                };
            },
        });
    }

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_select2 = document.getElementById("check-select2");
    const mult_select2 = document.getElementById("mult-select2");
    const verify_select2 = document.getElementById("verify-select2");

    const check_status = document.getElementById("check-status");
    const select_status = document.getElementById("select-status");

    const dt_end = document.getElementById("dt-end");
    const dt_start = document.getElementById("dt-start");

    const check_date = document.getElementById("check-date");
    const btn_search = document.getElementById("btn-search");

    const logger = document.querySelectorAll(".logger");

    check_search.checked = true;
    input_search.style.display = "block";

    check_search.addEventListener("click", () => {
        if (check_search.checked) {
            input_search.style.display = "block";
        }
        else {
            input_search.value = "";
            input_search.style.display = "none";
        }

    });

    check_select2.addEventListener("click", () => {
        if (check_select2.checked) {
            mult_select2.style.display = "block";
            verify_select2.value = "1";
        }
        else {
            mult_select2.style.display = "none";
            verify_select2.value = "2";
        }
    });

    check_status.addEventListener("click", () => {
        if (check_status.checked) {
            select_status.style.display = "block";
        } else {
            select_status.value = "";
            select_status.style.display = "none";
        }

    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = "Data inicío";

            dt_end.type = "text";
            dt_end.value = "";
            dt_end.disabled = true;
            dt_end.style.display = "block";

        } else {
            dt_start.style.display = "none";
            dt_end.style.display = "none";
        }

    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = "Data inicío";

            dt_end.type = "text";
            dt_end.value = "";
            dt_end.disabled = true;
            dt_end.style.display = "block";

        } else {
            dt_start.style.display = "none";
            dt_end.style.display = "none";
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

    });

    dt_start.addEventListener("focus", () => {

        dt_start.type = "date";
        btn_search.disabled = true;
    });

    dt_start.addEventListener("change", () => {

        if (dt_start.value != "") dt_end.disabled = false; else dt_end.disabled = true;

        dt_end.type = "date";
        btn_search.disabled = false;

        let date = new Date();
        let day = String(date.getDate()).padStart(2, '0');
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let year = date.getFullYear();
        current = year + '-' + month + '-' + day;

        dt_end.value = current;
    });

    dt_end.addEventListener("change", () => {
        if (dt_end.value != "") btn_search.disabled = false; else btn_search.disabled = true;
    });

    btn_search.addEventListener("click", () => {
        search.value = input_search.value;
        find();
    });

    for (elm of logger) {
        elm.style.paddingLeft = "15px";
    }


}


document.querySelectorAll(".btn.btn-primary, .btn.btn-danger").forEach(function (button) {
    button.addEventListener("click", function () {
        if (Filters) {
            Filters.btn_back = true;
            localStorage.setItem("filters", JSON.stringify(Filters));
        }
    });
});


function modalExport() {

    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 1:
            column = "schedule";
            break;
        case 2:
            column = "channel";
            break;
        case 3:
            column = "title";
            break;
        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
        &channel=${$('#verify-select2').val() == "2" ? "" : $('#multiselect2').val()}
        &status=${$('#select-status').val()}
        &dt_start=${$('#dt-start').val()}
        &column=${column}
        &order=${order}
        &dt_end=${$('#dt-end').val()}
        &type=publicationInstagramPost`, function (response) {
        Swal.fire({
            title: GLOBAL_LANG.instagram_post_alert_export_title,
            text: GLOBAL_LANG.instagram_post_alert_export_text,
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.instagram_post_alert_export_confirmButtonText
        });
    });
}


function addResendToTimeline(data) {

    let timeline = $(".timeline");

    let timeline_block = document.createElement("div");
    timeline_block.className = "timeline-block mb-3";

    let timeline_step = document.createElement("span");
    timeline_step.className = "timeline-step";

    let timeline_icon = document.createElement("i");
    timeline_icon.className = "fas fa-redo";

    let timeline_content = document.createElement("div");
    timeline_content.className = "timeline-content";

    let timeline_text = document.createElement("h6");
    timeline_text.className = "text-dark text-sm font-weight-bold mb-0";
    timeline_text.innerText = GLOBAL_LANG.instagram_post_resent_broadcast_timeline_view;

    let timeline_creation = document.createElement("p");
    timeline_creation.className = "font-weight-bold text-xs mt-1 mb-0";
    timeline_creation.innerText = data.creation;

    timeline_step.append(timeline_icon);
    timeline_block.append(timeline_step);
    timeline_content.append(timeline_text);
    timeline_content.append(timeline_creation);
    timeline_block.append(timeline_content);
    timeline.prepend(timeline_block);
}


function addMinutes(date, minutes) {
    date.setMinutes(date.getMinutes() + minutes);
    return date;
}
