const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.community.search) {
            document.getElementById("search").value = Filters.community.search;
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
            url: "community/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            }
        },
        columns: [
            {
                mData: 'name'
            },
            {
                mData: 'participantsCount'
            },

            {
                mData: 'base_time'
            },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {

                    return "<div class='kt-user-card-v2'>" + "<div class='kt-user-card-v2__pic'>" +
                        "<img src='" + document.location.origin + "/assets/img/community.png' class='avatar rounded-circle mr-3 m-img-rounded kt-marginless' alt='photo'>" +
                        "</div>" +
                        "<div class='kt-user-card-v2__details'>" +
                        "<b class='kt-user-card-v2__name'>" + full.name + "</b>" +
                        "</div>" +
                        "</div>";
                }
            },
            {
                orderable: false,
                targets: 2,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                            <i class="fa fa-ellipsis-v"></i>
                                       <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_community + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.community_column_edit}</span>
                                        </a>
                                        <a id="` + full.id_community + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.community_column_delete}</span>
                                        </a>
                                        <a id="` + full.id_community + `" href='#' class="dropdown-item table-btn-participant" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">    
                                                <i class="fa fa-eye" style="font-size: px"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.community_column_action_view}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                orderable: false,
                targets: [2]
            }
        ],
        pagingType: "numbers",
        pageLength: 10,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: false,
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

            const community = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.community = community;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}

$(document).ready(function () {

    $('#search').on('keyup', (e) => {
        if (e.which == 13) {
            find();
        }
    });
    find();


    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "community/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-participant", function () {
        window.location.href = "/community/participants/?id_community=" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.community_alert_delete_title,
            text: GLOBAL_LANG.community_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.community_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.community_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {

                $.post(`community/delete/${this.id}`, function (data) {

                    if (data.success?.status === true) {
                        swal({
                            title: GLOBAL_LANG.community_alert_delete_sucess,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary",
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    }

                    if (data.errors?.code === "PAD-002") {
                        swal({
                            title: GLOBAL_LANG.community_alert_delete_response_error_title,
                            text: `${GLOBAL_LANG.community_alert_delete_response_error_pad002} (${data.errors.code})`,
                            type: "error",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary",
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    }
                })
            }
        });
    });


    $("#modalFilter").one("click", () => modalFilter());
    $("#sendEmailExport").on("click", () => modalExport());
});


function modalFilter() {

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_growth = document.getElementById("check-growth");
    const select_growth = document.getElementById("select-growth");

    const btn_search = document.getElementById("btn-search");

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

    check_growth.addEventListener("click", () => {
        if (check_growth.checked) {
            select_growth.style.display = "block";
        } else {
            select_growth.value = "";
            select_growth.style.display = "none";
        }

    });

    btn_search.addEventListener("click", () => {

        const contact = document.getElementById("input-search");
        search.value = contact.value;

        find();
        search.value = "";
    });
}


document.querySelectorAll(".btn.btn-primary, .btn.btn-danger, #btn-return a").forEach(function (button) {
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
        case 1:
            column = "participantsCount";
            break;
        default:
            column = "name";
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &type=community`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.community_alert_export_title,
                text: GLOBAL_LANG.community_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.community_alert_export_confirmButtonText
            });

        }

    });
}

$("form").submit(event => event.preventDefault());
if (document.querySelector(".btn-success")) {
    document.querySelector(".btn-success").addEventListener("click", submit);
}

function submit() {
    const input_name = formValidation({ field: document.getElementById("input-name"), text: GLOBAL_LANG.community_name, required: true, min: 3, max: 100 });

    if (input_name) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}