function onChangeFileUpload() {
    var arquivo = $("#arq");
    var data = new FormData();
    data.append("arq", event.target.files[0]);
    $.ajax({
        type: "POST",
        url: "file/upload",
        data: data,
        success: function (response) {
            var json = JSON.parse(response);
            console.log(response);
            if (json.status == 200) {
                switch (json.media_type) {
                    case 2:
                        var json = { Cmd: "AudioMessage", "to": selected_chat, "media_caption": "", "media_url": json.media_url };
                        socket.send(JSON.stringify(json));
                        break;
                    case 3:
                        var json = { Cmd: "ImageMessage", "to": selected_chat, "media_caption": "", "thumb_image": json.thumb_image, "base64": json.base64 };
                        socket.send(JSON.stringify(json));
                        break;
                    case 4:
                        var json = { Cmd: "DocumentMessage", "to": selected_chat, "media_caption": json.media_caption, "thumb_image": json.thumb_image, "base64": json.base64,"file_name" : json.media_caption, "page_count" : "0"};
                        socket.send(JSON.stringify(json));
                        break;
                }
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        xhr: function () {
            var xmlHttp = $.ajaxSettings.xhr();
            xmlHttp.upload.onprogress = function (event) {

            };
            return xmlHttp;
        }
    });
}