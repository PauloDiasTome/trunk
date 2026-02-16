/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function saveContact() {
    var groups = [];
    for (var i = 0; i < $('#input-groups').tagsinput()[0].itemsArray.length; i++) {
        groups[i] = $('#input-groups').tagsinput()[0].itemsArray[i];
    }
    var json = {
        "Cmd": "edit",
        "resource": $("#resource").val(),
        "info": {
            "key": $("#search").val(),
            "name": $("#input-name").val(),
            "lastname": $("#input-lastname").val(),
            "email": $("#input-email").val(),
            "sex": $("#select-sex").val(),
            "groups": {
                groups
            }
        },
    };
    socket.send(JSON.stringify(json));
}

function addContact() {
    var json = {
        "Cmd": "add",
        "resource": $("#resource").val(),
        "info": {
            "key": $("#search").val(),
            "name": $("#input-name").val(),
            "lastname": $("#input-lastname").val(),
            "email": $("#input-email").val(),
            "sex": $("#select-sex").val(),
        },
    };
    socket.send(JSON.stringify(json));
}

function addGroup(){
    var json = {
        "Cmd": "add",
        "resource": $("#resource").val(),
        "info": {
            "name": $("#input-name").val()
        },
    };
    socket.send(JSON.stringify(json));
}

function saveGroup() {
    var json = {
        "Cmd": "edit",
        "resource": $("#resource").val(),
        "info": {
            "key": $("#search").val(),
            "name": $("#input-name").val()
        },
    };
    socket.send(JSON.stringify(json));
}

$(document).ready(function () {
    $('#list-items').on('click', '.btn', function () {
        var page = $("#page").val();
        location.href = page + "/" + this.id;
    });
    $('#save').on('click', function () {
        switch ($("#cmd").val()) {
            case 'add':
                switch ($("#resource").val()) {
                    case 'contact':
                        addContact();
                        break;
                    case 'group':
                        addGroup();
                        break;
                        s
                }
                break;
            case 'info':
                switch ($("#resource").val()) {
                    case 'contact':
                        saveContact();
                        break;
                    case 'group':
                        saveGroup();
                        break;
                        s
                }
                break;
        }
    });
});

