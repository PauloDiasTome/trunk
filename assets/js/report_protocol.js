function find() {
    $('#datatable-basic').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "protocol/find",
            "type": "POST",
            "data": {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        "columns": [
            {
                mData: 'creation'
            },
            {
                mData: 'protocol'
            },
            {
                mData: 'full_name'
            },
            {
                mData: 'key_remote_id'
            },
            {
                mData: 'last_name'
            },
        ],
        "columnDefs": [
            {
                orderable: true,
                targets: [0, 1, 2, 3, 4]
            }
        ],
        "pagingType": "numbers",
        "pageLength": 10,
        "destroy": true,
        "fixedHeader": true,
        "responsive": true,
        "lengthChange": true,
        "searching": false,
        "paginate": true,
        "info": true,
        "language": {
            "url": `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        }
    });
}

$(document).ready(function () {
    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find()
        }
    });
    find();
});