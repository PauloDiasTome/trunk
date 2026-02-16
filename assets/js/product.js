function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "product/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                situation: $('#select-situation').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'code'
            },
            {
                mData: 'title'
            },
            {
                mData: 'price'
            },
            {
                mData: 'status'
            }
        ],
        columnDefs: [
            {
                targets: 4,
                render: function (data, type, full, meta) {


                    if (full.is_rejected == 2 && full.is_appealed == 1) {
                        return "<a href='#' id='" + full.id_product + "' class='table-action table-action-edit' data-toggle='tooltip' data-original-title='" + GLOBAL_LANG.setting_catalog_dt_columndefs_target2_title_edit + "'>" +
                            "<i class='fas fa-user-edit'></i></a>" +

                            "<a id='" + full.id_product + "' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='" + GLOBAL_LANG.setting_catalog_dt_columndefs_target2_title_delete + "'>" +
                            "<i class='fas fa-trash'></i>" +
                            "</a>" +

                            "<a href='" + location.origin + '/product/appeal/' + full.id_product + "' class='table-action'>" +
                            "<i class='fas fa-exclamation-triangle'></i></a>";

                    } else {

                        return "<a href='#' id='" + full.id_product + "' class='table-action table-action-edit' data-toggle='tooltip' data-original-title='" + GLOBAL_LANG.setting_catalog_dt_columndefs_target2_title_edit + "'>" +
                            "<i class='fas fa-user-edit'></i></a>" +

                            "<a id='" + full.id_product + "' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='" + GLOBAL_LANG.setting_catalog_dt_columndefs_target2_title_delete + "'>" +
                            "<i class='fas fa-trash'></i>" +
                            "</a>";
                    }
                }
            },
            {
                orderable: false,
                targets: [3]
            },
            {
                orderable: true,
                targets: [0, 1, 2]
            }
        ],
        order: [[1, "asc"]],
        pagingType: "numbers",
        pageLength: 10,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: true,
        searching: true,
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


    $('#input-price').mask('000.000.000.000.000,00', { reverse: true });

    $("#datatable-basic").on("click", ".table-action-edit", function () {
        window.location.href = "product/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-action-delete", function () {
        swal({
            title: GLOBAL_LANG.setting_catalog_alert_delete_title,
            text: GLOBAL_LANG.setting_catalog_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.setting_catalog_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.setting_catalog_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("product/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.setting_catalog_alert_delete_two_title,
                        text: GLOBAL_LANG.setting_catalog_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });


    $("#modalFilter").one("click", () => modalFilter());
    $('#sendEmailExport').on('click', () => modalExport());

});

function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "Código";
            break;

        case 1:
            column = "Título";
            break;

        default:
            column = "Preço";
            break;
    }

    $.get(`/export/xlsx?email=${$('#emailExport').val()}
        &search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
        &situation=${$('#select-situation').val()}
        &column=${column}
        &order=${order}
        &type=product`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.setting_catalog_alert_export_two_title,
                text: GLOBAL_LANG.setting_catalog_alert_export_two_title,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.setting_catalog_alert_export_two_confirmButtonText,
            });
        }
    });
}


function modalFilter() {

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_situation = document.getElementById("check-situation");
    const select_situation = document.getElementById("select-situation");


    const btn_search = document.getElementById("btn-search");

    const logger = document.querySelectorAll(".logger");

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

    check_situation.addEventListener("click", () => {
        if (check_situation.checked) {
            select_situation.style.display = "block";
        }
        else {
            select_situation.value = "";
            select_situation.style.display = "none";
        }
    });


    btn_search.addEventListener("click", () => {

        const contact = document.getElementById("input-search");
        search.value = contact.value;

        find();
        search.value = "";
    });

    for (elm of logger) {
        elm.style.paddingLeft = "15px";
    }

}


Dropzone.autoDiscover = false;
var checkFile = 0;
var icon_cover = 0;
var fileEdit = 1;
var fileEdit2 = 0;
var fileAdd = 0;
var cover = 0;
var keyImg = 0;

var myDropzone = new Dropzone("#my-dropzone", {
    url: document.location.origin + "/product/upload",
    dictDefaultMessage: GLOBAL_LANG.settings_catalog_js_dicdefaultmessage_image,
    previewsContainer: ".dropzone-previews",
    acceptedFiles: "image/*",
    autoProcessQueue: true,
    createImageThumbnails: true,
    uploadMultiple: false,
    parallelUploads: 1,

    success: function (file, response) {

        var key_id = $("#id_product").attr("value");

        var generator_code = Math.floor(Math.random() * 100000);

        response = response.replace(/"/gi, '');

        if (key_id == undefined) {

            //* Adicionando product *//
            $("#input-files").append(`<input type="hidden" class="${generator_code}" name="${fileAdd == 0 ? "cover" : "file" + fileAdd}" value="${response}">`);
            $(".dz-image")[fileAdd].id = generator_code;
            fileAdd++;

        } else {

            //* Editando product *//
            if (checkFile >= 1) {
                $("#input-files").append(`<input type="hidden" class="${generator_code}" name="file${fileEdit}" value="${response}">`);

                $(".dz-image")[keyImg].id = generator_code;
                $(".dz-image")[keyImg].attributes[0].nodeValue = "dz-image add";
                $("input-hidden").remove();

                keyImg++;
                fileEdit++;

            } else {
                $("#input-files").append(`<input type="hidden" class="${generator_code}" name="${fileEdit2 == 0 ? "cover" : "file" + fileEdit2}" value="${response}">`);

                $(".dz-image")[fileEdit2].id = generator_code;
                $(".dz-image")[keyImg].attributes[0].nodeValue = "dz-image add";

                $("input-hidden").remove();
                keyImg++;
                fileEdit2++;

            }
        }

        $(".dz-message").find("button").css({ "background-color": "#2020208f", "color": "white" });
    },
    init: function () {

        if ($(".card-body").hasClass("add") == false) {

            var key_id = $("#id_product").attr("value");
            var me = this;
            var mockFile;

            //* Listando product, apenas para tela de Edit *//
            if (key_id != undefined) {

                $.ajax({
                    type: "GET",
                    url: document.location.origin + "/product/list_files/" + key_id,
                    dataType: 'JSON',
                    success: function (response) {
                        $.each(response, function (key, value) {

                            mockFile = value;
                            me.emit("addedfile", mockFile);
                            me.emit("thumbnail", mockFile, response[key].thumbnail == undefined ? response[key].cover_thumbnail : response[key].thumbnail);
                            me.emit("complete", mockFile);

                            let id_img = Math.floor(Math.random() * 1000);

                            $(".dz-image")[key].id = id_img;
                            $(".dz-image")[keyImg].attributes[0].nodeValue = "dz-image edit";

                            $("#" + id_img).append(`<input class="id_product_picture" type="hidden" value="${response[key].id_product_picture == undefined ? response[key].cover_id : response[key].id_product_picture}">`);
                            $("#" + id_img).append(`<input class="media_url_product" type="hidden" value="${response[key].media_url == undefined ? response[key].cover_url : response[key].media_url}">`);

                            $(".dz-image").find("img").css({ "width": 120, "height": 120, "object-fit": "cover" });
                            $(".dz-message").find("button").css({ "background-color": "#2020208f", "color": "white" });
                            if (cover == 0) {
                                $(".dz-default").append(`<div class="containerCatalog"><img class="coverCatalog" src="${response[key].cover_thumbnail}"</div>`);
                                cover = 1;
                            }

                            $(".dz-details .dz-size").remove();
                            $(".dz-details .dz-filename").remove();
                            $(".iconCatalog").css({ "margin-top": "-19px" });

                            checkFile = $(".id_product_picture").length;
                            keyImg = $(".dz-image").length;
                        });
                    }
                });
            }

        }

        this.on("addedfile", function (file) {

            //* Limita quantidade de arquivos aceitos *//
            var amount_existing = $(".edit").length;
            var files_allowed = 10 - amount_existing;

            if (this.files[files_allowed] != null) {
                this.removeFile(this.files[files_allowed]);

                Swal.fire({
                    title: GLOBAL_LANG.setting_catalog_alert_file_title,
                    text: GLOBAL_LANG.setting_catalog_alert_file_text,
                    type: 'warning',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: GLOBAL_LANG.setting_catalog_alert_file_confirmButtonText,

                });
            }


            //* Não permite imagens repetidas *//
            if (this.files.length) {
                var index, _len;
                for (index = 0, _len = this.files.length; index < _len - 1; index++) {
                    if (this.files[index].name === file.name && this.files[index].size === file.size && this.files[index].lastModifiedDate.toString() === file.lastModifiedDate.toString()) {
                        Swal.fire({
                            title: GLOBAL_LANG.setting_catalog_alert_picture_title,
                            text: GLOBAL_LANG.setting_catalog_alert_picture_text,
                            type: 'warning',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: GLOBAL_LANG.setting_catalog_alert_picture_confirmButtonText,

                        });
                        this.removeFile(file);
                    }
                }
            }


            //* Adiciona name="cover_product" no icone do product de capa apenas*//
            if (icon_cover == 0) {
                iconRemove = Dropzone.createElement("<i class='fas fa-times iconCatalog' name='cover_product'></i>");
                icon_cover = 1;

            } else {
                iconRemove = Dropzone.createElement("<i class='fas fa-times iconCatalog' name=''></i>");

            }

            iconRemove.id = Math.floor(Math.random() * 1000);

            iconRemove.addEventListener("click", function (e) {

                e.preventDefault();
                e.stopPropagation();


                var url = "";
                var icon_id = this.id;
                var exist_cover = $(this).attr('name');


                var idImage = $("#" + icon_id).parent().parent().find(".dz-image").attr("id");
                var key_id = $("#id_product").attr("value");

                $("." + idImage).remove();


                //* Removendo product em Add *//
                if (key_id == undefined) {

                    myDropzone.removeFile(file);

                    let files = myDropzone.files[0];

                    if (files == undefined) {
                        cover = 0;

                        $(".dz-message").find("button").css({ "background-color": "", "color": "#58585a" });
                        $(".coverCatalog").remove();

                    } else {

                        $(".coverCatalog").remove();
                        $(".dz-default").append(`<div class="containerCatalog"><img class="coverCatalog" src="${files.dataURL}"</div>`);

                    }

                    //* Reordena a ordem de input-files ocultos, após exclusão de algum product Add*//
                    let count_files = $(".dz-image").length;

                    for (let i = 0; i < count_files; i++) {
                        if (i == 0) {
                            $("#input-files").find("input")[i].attributes.name.nodeValue = "cover";

                        } else {
                            $("#input-files").find("input")[i].attributes.name.nodeValue = "file" + i;

                        }
                    }

                } else {

                    //* Removendo product em Edit *//
                    var name = $("#" + icon_id).parent().parent().find(".dz-image").find(".media_url_product").attr("value");
                    var id_product_picture = $("#" + icon_id).parent().parent().find(".dz-image").find(".id_product_picture").attr("value");

                    if (name != undefined) {
                        var name_file = name.split("br/")[1];
                    }

                    myDropzone.removeFile(file);

                    if ($(".dz-image")[0] == undefined) {

                        url = undefined;
                        cover = 0;

                        $(".dz-message").find("button").css({ "background-color": "", "color": "#58585a" });
                        $(".coverCatalog").remove();

                    } else {

                        if ($(".dz-image").hasClass("edit") == false) {

                            let files = myDropzone.files[0];

                            $(".coverCatalog").remove();
                            $(".dz-default").append(`<div><img class="coverCatalog" src="${files.dataURL}"</div>`);

                        } else {

                            url = $(".dz-image")[0].childNodes[0].attributes[2];

                            $(".coverCatalog").remove();
                            $(".dz-default").append(`<div><img class="coverCatalog" src="${url.nodeValue}"</div>`);
                        }

                    }

                    $.ajax({
                        type: "post",
                        url: document.location.origin + "/product/removeUpload",
                        data: {
                            file: name_file,
                            id_product_picture: id_product_picture
                        },
                        dataType: 'html'
                    });


                    //* caso a imagem excluida seja a capa *//
                    if (exist_cover == "cover_product" && $(".edit").length >= 1) {

                        name = $(".dz-image")[0].children[2].defaultValue;
                        id_product_picture = $(".dz-image")[0].children[1].defaultValue;

                        name_file = name.split("/products/")[1];

                        $.ajax({
                            type: "post",
                            url: document.location.origin + "/product/removeUpload",
                            data: {
                                file: name_file,
                                id_product_picture: id_product_picture
                            },
                            dataType: 'html'
                        });

                        $("#delete_files").append(`<input type="hidden" class="new_cover" name="cover" value="${name_file}">`);

                        $(".new_cover").remove();

                        $(".iconCatalog")[0].attributes[1].nodeValue = "cover_product";
                        $("#new_cover").append(`<input type="hidden" class="new_cover" name="cover" value="${name_file}">`);

                    } else {

                        if ($(".edit").length < 1) {
                            if ($(".dz-image").length >= 1) {
                                $("#input-files").find("input")[0].attributes.name.nodeValue = "cover";
                            }

                        }

                        if ($(".edit").length == 0) {
                            $(".new_cover").remove();
                        }

                    }


                    let files_add = $(".add").length;


                    //* Reordena a ordem de input-files ocultos, após exclusão de algum product Edit *//
                    if (files_add >= 1) {
                        for (let i = 0; i < files_add; i++) {

                            if ($(".edit").length == 0) {

                                if (i == 0) {
                                    $("#input-files").find("input")[i].attributes.name.nodeValue = "cover";

                                } else {
                                    $("#input-files").find("input")[i].attributes.name.nodeValue = "file" + i;

                                }
                            } else {

                                let _key;
                                _key = i + 1;
                                $("#input-files").find("input")[i].attributes.name.nodeValue = "file" + _key;
                            }

                        }
                    }


                }

                keyImg = $(".dz-image").length;

                fileAdd = $(".dz-image").length;

                fileEdit2 = $(".dz-image").length;

                checkFile = $(".id_product_picture").length;

                fileEdit = $("#input-files").find("input").length;

                fileEdit = fileEdit + 1;

                if ($(".dz-image").length == 0) {
                    $("#id_product").append(`<input type="hidden" class="input-hidden" id="editFile" name="editFile" value="mockFile">`);
                }

            });


            file.previewElement.childNodes[3].appendChild(iconRemove);

        });

    },
});
