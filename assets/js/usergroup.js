const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.user_group.search) {
            document.getElementById("search").value = Filters.user_group.search;
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
            url: "usergroup/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'name'
            }
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_user_group + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.usergroup_dt_target1_title_edit}</span>
                                        </a>
                                        <a id="` + full.id_user_group + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.usergroup_dt_target1_title_delete}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                orderable: true,
                targets: [0]
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

            const user_group = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.user_group = user_group;

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
        window.location.href = "usergroup/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.usergroup_alert_delete_title,
            text: GLOBAL_LANG.usergroup_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.usergroup_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.usergroup_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("usergroup/delete/" + this.id, function (data) {
                    if (data.length < 1) {
                        t.value && swal({
                            title: GLOBAL_LANG.usergroup_alert_delete_two_title,
                            text: GLOBAL_LANG.usergroup_alert_delete_two_text,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    } else {

                        let name = "", total = "";

                        for (let i = 0; i < data.length; i++) {
                            if (i < 2) {
                                name += data[i].name + ', '
                            }
                        }

                        name = name.substring(0, name.length - 2);

                        if (data.length > 2) {
                            total = data.length - 2;
                            total = "+" + total
                        }

                        t.value && swal({
                            title: GLOBAL_LANG.usergroup_alert_delete_three_title,
                            html: `${GLOBAL_LANG.usergroup_alert_delete_three_text_first_part} ${name} ${total == "" ? "" : GLOBAL_LANG.usergroup_alert_delete_three_text_second_part} ${total} ${GLOBAL_LANG.usergroup_alert_delete_three_text_third_part}`,
                            type: 'warning',
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary"
                        });

                    }
                });
            }
        });
    });

    $('#sendEmailExport').on('click', () => modalExport());
    $("form").submit(event => event.preventDefault());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }
});


function submit() {

    const input_name = formValidation({ field: document.getElementById("input-name"), text: GLOBAL_LANG.usergroup_nome, required: true, min: 2, max: 100 });

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
            column = "name";
            break;

        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=userGroup`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.usergroup_alert_export_title,
                text: GLOBAL_LANG.usergroup_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.usergroup_alert_export_confirmButtonText,
            });
        }
    });
}