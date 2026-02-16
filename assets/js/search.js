$(document).ready(function() {
    $('#example').DataTable({
        serverSide: true,
        ordering: false,
        searching: false,
        dom: 'lfrti',
        lengthChange: false,
        info: false,
        ajax: {
            url: '/searchresult',
            type: 'POST',
            data: {
                "Parameters": $("#Parameters").val(),
                "Fnc": $("#Fnc").val()
            }
        },
        columns: colunas,
        scrollY: '60vh',
        scroller: {
            loadingIndicator: true,
            serverWait: 50
        },
        //stateSave: true
    });
} );

$("#search").keyup(function(e) {
	if (e.keyCode == 13) {
		var search = this.value
			.replace(/[/]/g, "-")
			.replace(/[&\/\\#,+()$~%.'":*?<>{}¨!]/g, "");

		window.location.href = "/search/" + encodeURIComponent(search);
	}
});
