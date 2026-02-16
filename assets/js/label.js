const Filters = JSON.parse(localStorage.getItem("filters"));


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.label.search) {
            document.getElementById("search").value = Filters.label.search;
        }

        Filters.btn_back = null;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
}

checkFilter(Filters);


function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "label/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'id_label'
            }
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return "<div class='kt-user-card-v2'>" + "<div class='kt-user-card-v2__color'>" +
                        "<div class='kt-user-card-v2__details'>" +
                        "<div class='kt-user-card-v2__tag' style='text-align:center; margin-top:10px; position:absolute; margin-left:40px;'>" + full.tag + "</div>" +
                        "</div>" +

                        "<div style='width: 32px; height:32px;'><svg xmlns='http://www.w3.org/2000/svg' style='fill:" + full.color + "' class='color' viewBox='0 0 18 12'><path d='M11.208,0.925H2.236C1.556,0.925,1,1.565,1,2.357V9.57C1,10.362,1.556,11,2.236,11h8.972 c0.414,0,0.785-0.237,1.007-0.604l2.701-4.433L12.215,1.53C11.993,1.162,11.622,0.925,11.208,0.925z' rounded-circle mr-3 m-img-rounded kt-marginless'>" +
                        "</div>" +
                        "</svg>" +

                        "</div>";
                }
            },
            {
                orderable: false,
                targets: 1,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                       <i class="fa fa-ellipsis-v"></i>
                                       <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_label + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">       
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.label_column_action_edit}</span>
                                        </a>
                                        <a id="` + full.id_label + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">   
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.label_column_action_delete}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            }
        ],
        pagingType: "numbers",
        pageLength: 10,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: true,
        searching: false,
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

            const label = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.label = label;

            localStorage.setItem("filters", JSON.stringify(filter));
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


    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "label/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function async() {
        swal({
            title: GLOBAL_LANG.label_alert_delete_title,
            text: GLOBAL_LANG.label_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.label_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.label_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                const id = this.id;

                $.post(document.location.origin + "/label/delete", { id: this.id }, function (data) {
                    if(data?.error){
                        swal({
                            title: GLOBAL_LANG.label_alert_delete_five_title,
                            text: GLOBAL_LANG.label_alert_delete_five_text,
                            type: "error",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    }
                    if (data.length < 1) {
                        t.value && swal({
                            title: GLOBAL_LANG.label_alert_delete_two_title,
                            text: GLOBAL_LANG.label_alert_delete_two_text,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    } else {
                        t.value && swal({
                            title: GLOBAL_LANG.label_alert_delete_three_title,
                            text: GLOBAL_LANG.label_alert_delete_three_text,
                            type: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            reverseButtons: true,
                            confirmButtonClass: "btn btn-success",
                            confirmButtonText: GLOBAL_LANG.label_alert_delete_confirmButtonText,
                            cancelButtonClass: "btn btn-danger",
                            cancelButtonText: GLOBAL_LANG.label_alert_delete_cancelButtonText,
                        }).then(async (t) => {
                            if (t.value) {
                                const formData = new FormData();
                                formData.append("id", id);
                                formData.append("delete", 1);
                                formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

                                const response = await fetch(`${document.location.origin}/label/delete`, {
                                    method: "POST",
                                    body: formData
                                });

                                const data = await response.json();

                                if (data?.success) {
                                    swal({
                                        title: GLOBAL_LANG.label_alert_delete_two_title,
                                        text: GLOBAL_LANG.label_alert_delete_two_text,
                                        type: "success",
                                        buttonsStyling: !1,
                                        confirmButtonClass: "btn btn-success"
                                    });
                                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                                }else{
                                    swal({
                                        title: GLOBAL_LANG.label_alert_delete_four_title,
                                        text: GLOBAL_LANG.label_alert_delete_four_text,
                                        type: "error",
                                        buttonsStyling: !1,
                                        confirmButtonClass: "btn btn-success"
                                    });
                                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                                }
                            }
                        });
                    }
                });
            }
        });
    });


    $("#sendEmailExport").on("click", modalExport);
    $("form").submit(event => event.preventDefault());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }

});


function submit() {

    const input_name = formValidation({ field: document.getElementById("input-name"), text: GLOBAL_LANG.label_name, required: true, min: 3, max: 30 });

    if (input_name) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
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
        case 0:
            column = "label";
            break;

        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=label`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.label_alert_export_title,
                text: GLOBAL_LANG.label_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.label_alert_export_confirmButtonText
            });

        }

    });
}