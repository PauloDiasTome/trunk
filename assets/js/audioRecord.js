var audio_context;
var recorder;
var audio_stream;
var secounds = 0;
var minutes = 0;
var stop = false;

// Expose globally your audio_context, the recorder instance and audio_stream

/**
 * Patch the APIs for every browser that supports them and check
 * if getUserMedia is supported on the browser. 
 * 
 */
function Initialize() {
    try {
        // Monkeypatch for AudioContext, getUserMedia and URL
        window.AudioContext = window.AudioContext || window.webkitAudioContext;
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia;
        window.URL = window.URL || window.webkitURL;

        // Store the instance of AudioContext globally
        audio_context = new AudioContext;
        console.log('Audio context is ready !');
        console.log('navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));
    } catch (e) {
        alert('No web audio support in this browser!');
    }
}

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

/**
 * Starts the recording process by requesting the access to the microphone.
 * Then, if granted proceed to initialize the library and store the stream.
 *
 * It only stops when the method stopRecording is triggered.
 */
function startRecording() {
    $("#recording-time").html("0:00");
    // Access the Microphone using the navigator.getUserMedia method to obtain a stream
    navigator.getUserMedia({
        audio: true
    }, function (stream) {
        // Expose the stream to be accessible globally
        audio_stream = stream;
        // Create the MediaStreamSource for the Recorder library
        var input = audio_context.createMediaStreamSource(stream);
        console.log('Media stream succesfully created');

        recorder = new Recorder(input);
        console.log('Recorder initialised');

        recorder && recorder.record();
        console.log('Recording...');

        $("#record-audio").hide();
        $("#file-upload").hide();
        $("#stop-record").show();
        $("#ok-record").show();
        $("#recording-time").show();

        setTimeout('countSecounds()',1000);

    }, function (e) {
        console.error('No live audio input: ' + e);
    });
}

/**
 * Stops the recording process. The method expects a callback as first
 * argument (function) executed once the AudioBlob is generated and it
 * receives the same Blob as first argument. The second argument is
 * optional and specifies the format to export the blob either wav or mp3
 */
function stopRecording(callback, AudioFormat) {
    $("#recording-time").hide();
    stop = true;
    // Stop the recorder instance
    recorder && recorder.stop();
    console.log('Stopped recording.');

    // Stop the getUserMedia Audio Stream !
    audio_stream.getAudioTracks()[0].stop();

    // Disable Stop button and enable Record button !
    $("#record-audio").show();
    $("#file-upload").show();
    $("#stop-record").hide();
    $("#ok-record").hide();

    // Use the Recorder Library to export the recorder Audio as a .wav file
    // The callback providen in the stop recording method receives the blob
    if (typeof (callback) == "function") {

        /**
         * Export the AudioBLOB using the exportWAV method.
         * Note that this method exports too with mp3 if
         * you provide the second argument of the function
         */
        recorder && recorder.exportWAV(function (blob) {
            console.log(blob);
            callback(blob);

            // create WAV download link using audio data blob
            // createDownloadLink();

            // Clear the Recorder to start again !
            recorder.clear();
        }, (AudioFormat || "audio/wav"));
    }
}

function cancelRecording(callback, AudioFormat) {
    stop = true;
    $("#recording-time").hide();
    $("#record-audio").show();
    $("#file-upload").show();
    $("#stop-record").hide();
    $("#ok-record").hide();
    recorder && recorder.stop();
    recorder.clear();
}