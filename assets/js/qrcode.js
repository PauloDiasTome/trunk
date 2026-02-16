$(document).ready(function () {
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        width: $("#qrcode-size").val(),
        height: $("#qrcode-size").val()
    });

    qrcode.clear();
    qrcode.makeCode($("#qrcode-value").val());
    document.getElementById("qrcode").children[1].style.marginLeft = "50%";

    $("#build-qrcode").on("click", function () {
        document.getElementById("qrcode").innerHTML = "";
        qrcode = new QRCode(document.getElementById("qrcode"), {
            width: $("#qrcode-size").val(),
            height: $("#qrcode-size").val()
        });
        qrcode.clear();
        qrcode.makeCode($("#qrcode-value").val());

        const qr_code = document.getElementById("qrcode");

        if (qr_code.children[0].width == "64") {
            qr_code.style.marginLeft = "150%";
        }
        else if (qr_code.children[0].width == "128") {
            qr_code.style.marginLeft = "115%";
        }
        else if (qr_code.children[0].width == "256") {
            qr_code.style.marginLeft = "50%"
        }
        else if (qr_code.children[0].width == "512") {
            qr_code.style.marginLeft = "0%"
            document.getElementById("btn").style.textAlign = "center";
        }
    });

    $("#download-qrcode").on("click", function () {
        var a = document.createElement('a');
        a.href = document.getElementById("qrcode").children[1].src;
        a.download = a.href;
        a.click();
    });

    $("#print-qrcode").on("click", function () {
        const myImage = document.getElementById("qrcode").children[1].src;
        const myWindow = window.open("", "Image");
        myWindow.document.body.innerText = "";
        myWindow.document.write("<img src='" + myImage + "'>");
        myWindow.print();
    });
});