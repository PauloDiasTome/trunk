$(document).ready(function () {
    $("#search").on( "keydown", function(event) {
        if(event.which == 13) {
            alert('ok');
        }
    });
});