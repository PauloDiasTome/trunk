const Filters = JSON.parse(localStorage.getItem("filters"));


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.bot_access.search) {
            document.getElementById("search").value = Filters.bot_access.search;
        }

        Filters.btn_back = null;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
}

checkFilter();


function find() {

    let id = location.href.split("/").pop();

    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "../../bot/trainer/find_access/" + id,
            type: "POST",
            data: {
                text: $("#search").val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'option'
            },
            {
                mData: 'description'
            },
        ],
        columnDefs: [
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_chatbot + `" href='#' class="dropdown-item table-btn-access access_link" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">    
                                                <i class="far fa-hand-pointer"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.setting_bot_trainer_dt_columndefs_target2_title_access}</span>                                          
                                        </a>
                                        <a id="` + full.id_chatbot + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">       
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.setting_bot_trainer_dt_columndefs_target2_title_edit}</span>
                                        </a>
                                        <a id="` + full.id_chatbot + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.setting_bot_trainer_dt_columndefs_target2_title_delete}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                targets: 1,
                render: function (data, type, row) {
                    return data.substr(0, 170);
                }
            },
            {
                orderable: true,
                targets: [0]
            }
        ],
        pagingType: "numbers",
        pageLength: 5,
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

        drawCallback: function () {
            const bot_access = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.bot_access = bot_access;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}

$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });
    find();


    $("#datatable-basic").on("click", ".table-btn-access", function () {
        let nivel_access = localStorage.getItem('nivel');
        if (nivel_access !== null) {
            localStorage.setItem('nivel', this.id);
        }
        if (nivel_access === null) {
            let nivel_def = 1;
            if (nivel_def != parseInt(this.id)) {
                nivel_def = this.id;
            }
            localStorage.setItem('nivel', nivel_def);
        }
        window.location.href = "../../bot/trainer/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "../../bot/trainer/edit/" + this.id;
    });


    $("#timeline-level").on("click", function () {
        localStorage.setItem("nivel", this.href.split("/")[5]);
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.setting_bot_trainer_alert_delete_title,
            text: GLOBAL_LANG.setting_bot_trainer_alert_detete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.setting_bot_trainer_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.setting_bot_trainer_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("../../bot/trainer/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.setting_bot_trainer_alert_delete_two_title,
                        text: GLOBAL_LANG.setting_bot_trainer_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });
});


function validNumber(evt) {
    if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
        evt.preventDefault();
    }
}

if (document.querySelector("#input-option") != null) {
    document.querySelector("#input-option").addEventListener("keypress", validNumber);
}

document.querySelectorAll(".btn.btn-primary, .btn.btn-danger, #level-one").forEach(function (button) {
    button.addEventListener("click", function () {
        if (Filters) {
            Filters.btn_back = true;
            localStorage.setItem("filters", JSON.stringify(Filters));
        }
    });
});