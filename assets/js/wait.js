function find() {
    $('#datatable-basic').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "wait/find",
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
                mData: 'key_remote_id'
            },
            {
                mData: 'full_name'
            },
            {
                mData: 'stage'
            },
            {
                mData: 'user_key_remote_id'
            }
        ],
        "columnDefs": [
            {
                "targets": 5,
                "render": function (data, type, full, meta) {
                    return "<a id='" + full.id_wait_list + "' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='Deletar'>" +
                        "<i class='fas fa-trash'></i>" +
                        "</a>";
                }
            },
            {
                orderable: false,
                targets: [0]
            }
        ],
        "pagingType": "numbers",
        "pageLength": 5,
        "destroy": true,
        "fixedHeader": true,
        "responsive": true,
        "lengthChange": false,
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


    if ($("#search").val() !== undefined) {
        find();
    }
});