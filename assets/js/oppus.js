var base64data;
var secounds = 0;
var minutes = 0;
var stop = false;

const workerOptions = {
	OggOpusEncoderWasmPath: 'https://app.talkall.com.br/assets/OggOpusEncoder.wasm',
	WebMOpusEncoderWasmPath: 'https://app.talkall.com.br/assets/WebMOpusEncoder.wasm'
};

function countSecounds() {
    if( stop == true ){
        stop = false;
        secounds = 0;
        minutes = 0;
        return;
    }
    var sSecound = "";
    var sMinute = "";
    if( secounds > 60 ){
        minutes = minutes + 1;
    } else {
        secounds = secounds +1;
    }
    sSecound = secounds;
    sMinute = minutes;
    if( secounds < 9 ){
        sSecound = "0" + secounds.toString();
    } 
    $("#recording-time").html(sMinute+":"+sSecound);
    setTimeout('countSecounds()',1000);
}

function startRecording() {

    $("#recording-time").html("0:00");

    window.MediaRecorder = OpusMediaRecorder;

    navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {

        let options = { mimeType: 'audio/ogg' };
        recorder = new MediaRecorder(stream, options, workerOptions);
        recorder.start();
        
        $("#record-audio").hide();
        $("#file-upload").hide();
        $("#stop-record").show();
        $("#ok-record").show();
        $("#recording-time").show();

        setTimeout('countSecounds()',1000);

        recorder.addEventListener('dataavailable', (e) => {
            var reader = new FileReader();
            reader.readAsDataURL(e.data);
            reader.onloadend = function () {
                base64data = reader.result;
                var json = { Cmd: "AudioMessage", "to": selected_chat, "base64": base64data, "media_key": "", "media_duration": secounds };
                socket.send(JSON.stringify(json));
            }
        });
    });
}

function stopRecording() {

    stop = true;
    
    recorder.stop();
    
    $("#recording-time").hide();
    $("#record-audio").show();
    $("#file-upload").show();
    $("#stop-record").hide();
    $("#ok-record").hide();
}

function cancelRecording() {
    stop = true;

    $("#recording-time").hide();
    $("#record-audio").show();
    $("#file-upload").show();
    $("#stop-record").hide();
    $("#ok-record").hide();

    recorder.stop();
    recorder.clear();
}