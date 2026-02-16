var accessToken = document.currentScript.getAttribute("init");
var colorWidget = document.currentScript.getAttribute("color");
var text = document.currentScript.getAttribute("text");
var position = document.currentScript.getAttribute("position");

var show = false;

function widgetClick() {

    if (document.getElementById("widget_circle").style.maxWidth == "265px") {
        document.getElementById('button_text').style.display = 'none';
        document.getElementById("widget_circle").style.maxWidth = "60px";
        document.getElementById("widget_logo").style = "right: 10px; opacity: 0; transform: scale(0.5) rotate(-120deg);";
        document.getElementById("circle_close").style = "opacity: 1; transform: rotate(0deg) scale(1);";
        document.getElementById("widget_popup").style = "right: 20px; opacity: 0; display: block; transform: translate3d(0px, 0px, 0px); pointer-events: none; z-index: -1;";
        document.getElementById("whts_widget_box").style = "padding: 0px; display: block; transform: translate3d(0px, 0px, 0px); opacity: 1; pointer-events: auto; z-index: 100000000;"
        document.getElementById("button_text").style.opacity = "0";

    } else {
        document.getElementById("widget_circle").style.maxWidth = "265px";
        document.getElementById("widget_logo").style = "right: 10px; opacity: 1; transform: scale(1) rotate(0deg);";
        document.getElementById("circle_close").style = "opacity: 0; transform: rotate(120deg) scale(1);";
        document.getElementById("widget_popup").style = "right: 20px; opacity: 0; display: block; transform: translate3d(0px, 0px, 0px); pointer-events: none; z-index: -1;";
        document.getElementById("whts_widget_box").style = "padding: 0px; display: none; transform: translate3d(0px, 20px, 0px); opacity: 0; pointer-events: none; z-index: -1;"
        document.getElementById("button_text").style.opacity = "1";

        setTimeout(function () {
            document.getElementById('button_text').style.display = 'block';
        }, 500);
    }

    switch (position) {
        case "left":
            document.getElementById("whts_widget_box").style.left = "20px";
            break;

        case "center":
            document.getElementById("whts_widget_box").style.left = "50%";
            break;
        case "right":

            document.getElementById("whts_widget_box").style.right = "20px";
            break;
        default:
            document.getElementById("whts_widget_box").style.left = "20px";
            break;
    }
}

function init() {

    localStorage.setItem("widget-id", accessToken);

    var widget_logo = document.createElement("img");
    var s = document.createElement("script");

    var loadcss = document.createElement('link');
    loadcss.setAttribute("rel", "stylesheet");
    loadcss.setAttribute("href", "https://app.talkall.com.br/widget.css");
    loadcss.setAttribute("type", "text/css");
    document.getElementsByTagName("head")[0].appendChild(loadcss);

    var widget = document.createElement("div");
    widget.id = "whatsshare_widget";

    var widget_circle = document.createElement("div");
    widget_circle.id = "widget_circle";
    widget_circle.className = "right";
    widget_circle.style.maxWidth = "265px";
    widget_circle.style.width = "auto";
    widget_circle.style.opacity = 0;
    widget_circle.style.paddingRight = "1px";
    widget_circle.style.backgroundColor = colorWidget;

    switch (position) {
        case "left":
            widget_circle.style.marginLeft = "20px";
            widget_circle.style.right = "auto";
            break;

        case "center":
            widget_circle.style.left = "44%";
            widget_circle.style.right = "43%";
            break;
        case "right":

            widget_circle.style.right = "20px";
            break;
        default:
            widget_circle.style.left = "20px";
            break;
    }

    widget_logo.id = "widget_logo";
    widget_logo.style = "right: 10px; opacity: 1; transform: scale(1) rotate(0deg);";
    widget_logo.src = "https://app.talkall.com.br/assets/img/widget/chat.png";
    // widget_logo.src = "https://whts.co/img/widget_logo.svg";

    var circle_close = document.createElement("img");
    circle_close.id = "circle_close";
    circle_close.style = "opacity: 0; transform: rotate(120deg) scale(1);";
    circle_close.src = "https://app.talkall.com.br/assets/img/widget/close.png";
    // circle_close.src = "https://whts.co/img/icon_close.svg";

    var button_text = document.createElement("span");
    button_text.id = "button_text";
    button_text.className = "button_text";
    button_text.innerHTML = text;

    widget_circle.append(widget_logo);
    widget_circle.append(circle_close);
    widget_circle.append(button_text);

    var widget_popup = document.createElement("div");
    widget_popup.id = "widget_popup";
    widget_popup.style = "right: 20px; opacity: 0; display: block; transform: translate3d(0px, 0px, 0px); pointer-events: none; z-index: 0;";

    var title_message = document.createElement("p");
    title_message.innerHTML = "";
    title_message.id = "title_message";

    var body_message = document.createElement("p");
    body_message.id = "body_message";

    var welcome_message = document.createElement("div");
    welcome_message.id = "welcome_message";
    welcome_message.style = "opacity: 1;";
    welcome_message.innerHTML = "";

    var welcome_message_title = document.createElement("p");
    welcome_message_title.id = "welcome_message_title";
    welcome_message_title.innerHTML = "";

    var welcome_message_body = document.createElement("p");
    welcome_message_body.id = "welcome_message_body";
    welcome_message_body.innerHTML = "";

    welcome_message.append(welcome_message_title);
    welcome_message.append(welcome_message_body);

    welcome_message.append(title_message);
    welcome_message.append(body_message);

    var widget_close = document.createElement("div");
    widget_close.id = "widget_close";
    widget_close.onclick = "widget_popup.style.opacity = 0;";

    var whts_widget_box = document.createElement("div");
    whts_widget_box.id = "whts_widget_box";
    whts_widget_box.style = "display: none; transform: translate3d(0px, 20px, 0px); opacity: 0; pointer-events: none; z-index: -1;";
    whts_widget_box.innerHTML = "<iframe src='https://app.talkall.com.br/v1/widget/" + accessToken + "' style='width:100%; height: 100%; border: 0px; padding: 0px;'></iframe>";

    welcome_message.append(widget_close);
    widget_popup.append(welcome_message);

    widget.append(widget_circle);
    widget.append(widget_popup);
    widget.append(whts_widget_box);

    widget_circle.onclick = function () { widgetClick(); };

    document.body.append(widget);
}

setTimeout("init();", 1000);
setTimeout("widget_circle.style.opacity = 1;", 2000);