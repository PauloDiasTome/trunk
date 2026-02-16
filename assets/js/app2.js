var typingTimer;
var searchTimer;
var ProductTimer;
var typingSearchQuick;
var typingSearchProduct;

var doneTypingInterval = 250;
var doneSearchTimer = 250;
var doneSearchProductInterval = 150;
var doneSearchQuickInterval = 150;

var ticket_id = 0;
var item_id = 0;
var quoted_id = 0;
var bShowPopMenu = false;
var contact_name;
var contact_number;
var tailOut = 0;
var processUser = [];

var groupStarred = 1;
var checkDataStar = 0;
var creationGroup = "true";
var time = null;

let search_is_active = false;

const bgColor = {
  light: [
    "#CACCD1", "#7D7D7C", "#515151", "#28292B", "#F1F6ED", "#D7E7D7", "#BBE7CF", "#B8D1C3", "#83B99C",
    "#F0ECC0", "#E7E6C0", "#DFE7C1", "#CCD1AE", "#B8B874", "#E5EAEC", "#CDEEEC", "#C6DCDE", "#90CFCD",
    "#0096A3", "#E9F5F8", "#D3EBF1", "#9FCFE9", "#558EBB", "#2B4F70", "#EBDAEA", "#EFBCD5", "#C6A3D5",
    "#A782B7", "#C0719E", "#E1C7BA", "#CFADA4", "#BB7561", "#927C72", "#56443C", "#F3D4DE", "#F5A8AC",
    "#DE767C", "#DC5C58", "#CD4244", "#F2E5E2", "#E7CFBE", "#D8BDAD", "#F3A29B", "#F5A892", "#EEE0C7",
    "#F8E7C4", "#F8DAC4", "#EF8A4E", "#C98B5A", "#F3F1E5", "#EEE9C9", "#F5E4BB", "#FFD973", "#E4C177"
  ],
  dark: [
    "#8D8E91", "#585857", "#383838", "#1B1B1C", "#AAB0A7", "#98A896", "#84A693", "#82968C", "#5D8774",
    "#A7A47B", "#A2A17B", "#9DA47C", "#979B81", "#86864F", "#9FA4A6", "#8FA8A6", "#899A9B", "#659A98",
    "#006A75", "#A3B2B5", "#8DA3AE", "#6F98B2", "#3B6483", "#1E344A", "#A895A6", "#A67D95", "#8C779A",
    "#755C82", "#854E6F", "#9E877F", "#967D77", "#874E3F", "#655854", "#3B2F2A", "#A6848D", "#AC777A",
    "#9B5054", "#992D2A", "#8F2E2F", "#A89B98", "#9C8575", "#947A6D", "#A5645F", "#A86F5D", "#A1958F",
    "#A7917D", "#A47D6F", "#AC6536", "#8F6240", "#A8A697", "#A39E8C", "#A8937D", "#B39451", "#A1864E"
  ]
};

const rgbToHex = (rgb) => {
  const rgbArray = rgb.match(/\d+/g);
  if (!rgbArray || rgbArray.length < 3) return rgb;
  return `#${rgbArray.map(x => parseInt(x).toString(16).padStart(2, '0')).join('')}`.toUpperCase();
};

const getDefaultColor = () => {
  const is_dark_mode = bNight;
  return is_dark_mode ? '#1D1D1D' : '#F4F4F4';
};

const changeBgColor = (color) => {
  color = color.startsWith("rgb") ? rgbToHex(color) : color;
  const elements_to_change = document.querySelectorAll('[data-change-bg]');
  elements_to_change.forEach(el => {
    el.style.backgroundColor = color;
  });
  localStorage.setItem("colorWallpaper", color);
};

const restoreBgColor = () => {
  let saved_color = localStorage.getItem('colorWallpaper') || getDefaultColor();
  changeBgColor(saved_color);
};

const enablePreviewBgColor = () => {
  const buttons = document.querySelectorAll('.btnWallpaper');
  buttons.forEach(button => {
    button.addEventListener('mouseenter', () => {
      let color = button.classList.contains('def')
        ? getDefaultColor()
        : button.style.backgroundColor;
      color = color.startsWith("rgb") ? rgbToHex(color) : color;
      document.querySelectorAll('[data-change-bg]').forEach(el => {
        el.style.backgroundColor = color;
      });
    });

    button.addEventListener('mouseleave', () => {
      let saved_color = localStorage.getItem('colorWallpaper') || getDefaultColor();
      document.querySelectorAll('[data-change-bg]').forEach(el => {
        el.style.backgroundColor = saved_color;
      });
    });

    button.addEventListener('click', () => {
      let color = button.classList.contains('def')
        ? getDefaultColor()
        : button.style.backgroundColor;
      color = color.startsWith("rgb") ? rgbToHex(color) : color;
      changeBgColor(color);
    });
  });
};

restoreBgColor();
enablePreviewBgColor();

Notification.requestPermission();

var notification = [];

function postNotification(title, icon, body, key_remote_id) {

  for (var i = 0; i < notification.length; i++) {
    notification[i].close();
  }

  var notification_alert = localStorage.getItem('notification_alert');

  let silent_song = false;

  if (notification_alert !== null) {
    let audio = new Audio(notification_alert);
    audio.play();
    silent_song = true;
  }

  var notify = new Notification(title, {
    icon: icon,
    body: body.replace(/<br>/g, "\n\r"),
    silent: silent_song,
    tag: key_remote_id
  });
  notification.push(notify);
  notify.addEventListener('click', function () {
    window.location.href = document.location.origin + "/chat/" + notify.tag + "?text=''";
  });
}

function unblock() {
  document.querySelector('.inputMsg').innerHTML = '';
  document.querySelector('.inputMsg').style.display = 'none';
  document.querySelector('.input #emoji').style.display = 'flex';
  document.querySelector('.input .text').style.display = 'flex';
  document.querySelector('.input #file-upload').style.display = 'flex';
  document.querySelector('.input #record-audio').style.display = 'block';
}

function blockToType(lastTime, info) {
  alertCloseChat(info);

  if (info.type == 2) return

  const $input_msg = document.querySelector('.inputMsg');
  const $input_emoji = document.querySelector('.input #emoji');
  const $input_text = document.querySelector('.input .text');
  const $input_attach = document.querySelector('.input #file-upload');
  const $input_record = document.querySelector('.input #record-audio');
  const $bottom_entry_rectangle = document.getElementById("bottomEntryRectangle");

  const msgBlock = flag => {

    if (flag == 1) {

      $input_emoji.style.display = 'none';
      $input_text.style.display = 'none';
      $input_attach.style.display = 'none';
      $input_record.style.display = 'none';
      $bottom_entry_rectangle.style.display = "none";
      $input_msg.style.display = 'flex';
      $input_msg.innerHTML = `<div class="msgBlock">${GLOBAL_LANG.messenger_input_block_to_type}<input type="submit" onclick="queryTemplates(${info.type})" class="template-button" value="${GLOBAL_LANG.messenger_input_btn_send_notification}"></div>`;

      $("#alert-credit-minimum").hide();

      $(".chat")[0].style = "z-index: 1; height: calc(100% - 59px);";

      $(".messenger .right .chat").css("transition", "none");
      setTimeout(() => $(".messenger .right .chat").css("transition", "0.3s ease-in"), 500);

      setTimeout(() => { $('.messages:visible').scrollTop($('.messages:visible')[0].scrollHeight - $('.messages:visible')[0].clientHeight); }, 10);
    } else {
      $input_msg.style.display = 'none';
      $input_msg.innerHTML = '';
    }
  }

  if (lastTime != null) {
    if (Util.FormatShortDate(lastTime) != GLOBAL_LANG.messenger_day_week_today) {

      let lastDate = new Date(lastTime * 1000);
      let lastHours = lastDate.getHours();
      let lastMinutes = ("0" + lastDate.getMinutes()).substr(-2);
      let dateNow = new Date();

      if (Util.FormatShortDate(lastTime) != GLOBAL_LANG.messenger_day_week_yesterday) {
        msgBlock(1);

      } else {
        if (lastHours < (dateNow.getUTCHours() - 3)) {
          msgBlock(1);
        }
        else if (lastHours == (dateNow.getUTCHours() - 3)) {
          if (lastMinutes < dateNow.getUTCMinutes()) {
            msgBlock(1);
          }
        }
      }
    }
    else {
      msgBlock(0);
      unblockChat();
    }
  } else {
    msgBlock(1);
  }
}

function unblockChat() {

  if (document.querySelector(".msgBlock") === null) return;

  const $input_msg = document.querySelector('.inputMsg');
  const $input_emoji = document.querySelector('.input #emoji');
  const $input_text = document.querySelector('.input .text');
  const $input_attach = document.querySelector('.input #file-upload');
  const $input_record = document.querySelector('.input #record-audio');
  const $bottom_entry_rectangle = document.getElementById("bottomEntryRectangle");

  $input_msg.innerHTML = '';
  $input_msg.style.display = 'none';
  $input_emoji.style.display = 'flex';
  $input_text.style.display = 'flex';
  $input_attach.style.display = 'flex';
  $input_record.style.display = 'block';
  $bottom_entry_rectangle.style.display = "flex";
}

function creditBlock() {

  const $input_msg = document.querySelector('.inputMsg');
  document.querySelector('.input #emoji').style.display = "none";
  document.querySelector('.input .text').style.display = "none";
  document.querySelector('.input #file-upload').style.display = "none";
  document.querySelector('.input #record-audio').style.display = "none";
  document.getElementById("bottomEntryRectangle").style.display = "none";

  $input_msg.innerHTML = '';
  $input_msg.style.display = 'flex';
  $input_msg.innerHTML = `<div class="msgBlock">${GLOBAL_LANG.messenger_template_alert_check_insufficient_balance}</div>`;

  $(".messages").scrollTop($(".messages").prop("scrollHeight"));
}

function scrollChatMessenger() {

  // SCROLL_BLOCK -> para evitar que evento scroll seja acionado em momento não desejados...
  SCROLL_BLOCK = true;

  document.querySelector(".buttonBottomScroll").style.display = "none";

  setTimeout(() => {
    $("#" + messenger.Chat.token).scrollTop($("#" + messenger.Chat.token).prop("scrollHeight") + 999);
    $("#" + messenger.Chat.token).scrollTop($("#" + messenger.Chat.token).scrollTop() - 4);
  }, 450);

  $(".chat .messages").each(function (idx, elm) {
    if (elm.style.display != "none") {
      elm.onscroll = function () {

        //Carregar mensagens a cima//
        if (!SCROLL_BLOCK) {

          if ($(this).scrollTop() + $(this).innerHeight() <= this.scrollHeight - 200) {
            document.querySelector(".buttonBottomScroll").style.display = "block";
            buttonBottomScroll();
          }

          if (this.scrollTop == 0) {

            document.querySelector(".buttonBottomScroll").style.display = "none";

            let info = messenger.ChatList.findByKeyRemoteId(ta.key_remote_id);
            let create = $("#" + info.chat).find("div")[0].attributes[1].nodeValue;

            if (info !== null) {

              ITEM_FOCUSED = "";

              ta.chat.queryMessages(ta.key_remote_id, create, false);
              SCROLL_TOKEN_TOP = $("#" + info.chat).find(".item")[1].attributes.id.value;
            }
          }
        }

        //Carregar mensagens abaixo//
        if (!SCROLL_BLOCK) {
          if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {

            document.querySelector(".buttonBottomScroll").style.display = "none";

            let create = "";
            ITEM_FOCUSED = "";
            SCROLL_TOKEN_TOP = "";

            $(".chat .messages").each((idx, elm) => {
              if (elm.style.display != "none") {
                create = $(elm).find(".item").last()[0].attributes[1].value;
              }
            });

            ta.chat.queryMessages(ta.key_remote_id, create, true);
          }
        }
        SCROLL_BLOCK = false;
      }
    }
  });
}

function lastSelectedItem() {
  let last_item = document.querySelector(`.itemSelected`);
  let last_info, last_message = null;

  if (last_item) {
    last_info = messenger.ChatList.find(last_item.id);
    last_message = document.querySelector(`#${CSS.escape(last_item.id)} .body .last-message`);
  }

  return { last_message, last_info };
}

function getPlatformIcon(type) {
  switch (parseInt(type)) {
    case 1: return "assets/img/talkall.png";
    case 3: return "assets/img/widget.png";
    case 8: return "assets/img/facebook.png";
    case 9: return "assets/img/instagram_integration.png";
    case 10: return "assets/img/telegram.png";
    case 11: return "assets/img/facebook.png";
    case 2:
    case 12:
    case 16: return "assets/icons/messenger/whatsapp-business.svg";
    default: return "";
  }
}


var messenger = {
  count: {
    call: 0,
    comment: 0,
    wait: 0,
  },
  Chats: [],
  Contacts: [],
  Chat: {
    selected: null,
    creation: null,
    token: null,
    key_id: null,
    push_name: null,
    key_remote_id: null,
    is_type: null,
    is_private: null,
    is_group: null,
    last_timestamp_client: null,
    labels: null,
    revoke: false,
    reply: false,
    show() {
      let info = messenger.ChatList.find(messenger.Chat.selected);
      if (info != null) {

        if (info.is_private == 2) {
          $("#info").hide();
          $("#info-note").hide();
          $("#chat-waiting").hide();
          $("#chat-attendance").hide();
          $("#chat-trans").hide();
          $("#chat-labels").hide();
          $(".fa-tags").hide();
          $("#chat-ticket").hide();
          $("#chat-spam-report").hide();
          $("#chat-report-span").hide();
          $("#contact-edit").hide();
        }

        if (info.is_private == 1) {
          $("#info").show();
          $("#info-note").show();
          $("#chat-trans").show();
          $("#chat-labels").show();
          $(".fa-tags").show();
          $("#chat-ticket").show();
          $("#contact-edit").show();
          $("#chat-report-span").show();
          $("#chat-spam-report").show();
        }

        if (info.is_private == 2 && info.is_group == 2) {
          $("#info-participants").show();
          $('#close-chat').hide();
        } else {
          $("#info-participants").hide();
          $('#close-chat').show();
        }

        switch (info.type) {
          case 1:
            $(".input .text").width("calc(100% - 86px)");
            $("#file-upload").show();
            $("#record-audio").show();
            break;
          case 2:
            $(".input .text").width("calc(100% - 86px)");
            if (!$('.inputMsg').find('.msgBlock').hasClass('msgBlock')) {
              $("#file-upload").show();
              $("#record-audio").show();
            }
            break;
          case 9:
            $(".input .text").width("calc(100% - 86px)");
            $("#file-upload").show();
            $("#record-audio").hide();
            break;
          case 11:
            $(".input .text").width("calc(100% - 86px)");
            $("#file-upload").hide();
            $("#record-audio").hide();
            break;
          default:
            $(".input .text").width("calc(100% - 86px)");
            if (!$('.inputMsg').find('.msgBlock').hasClass('msgBlock')) {
              $("#file-upload").show();
              $("#record-audio").show();
            }
            break;
        }

        if ($(".input-text").text().trim().length > 0) {
          localStorage.setItem(messenger.Chat.key_remote_id, $(".input-text").html());
        } else {
          localStorage.removeItem(messenger.Chat.key_remote_id);
        }

        ta.setKeyRemoteId(info.key_remote_id);

        messenger.Chat.is_type = info.type;
        messenger.Chat.is_private = info.is_private;
        messenger.Chat.is_group = info.is_group;
        messenger.Chat.revoke = info.revoke;
        messenger.Chat.labels = info.labels_name;
        messenger.Chat.reply = info.reply;
        messenger.Chat.push_name = info.push_name;
        messenger.Chat.token = info.chat;
        messenger.Chat.key_remote_id = info.key_remote_id;
        messenger.Chat.last_timestamp_client = info.last_timestamp_client;

        $("#" + info.chat).show();
        $(".right").show();
        $(".caption").html("");
        $(".label-contact").html("");

        let span = document.createElement("span");
        span.style.float = 'left';
        span.style.cursor = 'pointer';
        span.id = 'contact_full_name';
        span.channel_id = 'channel_id';
        span.innerHTML = Util.doTruncarStr(messenger.Chat.push_name, 72);

        let span_hide = document.createElement("span");
        span_hide.style.float = 'left';
        span_hide.style.cursor = 'pointer';
        span_hide.style.display = 'none';
        span_hide.id = 'compressed_contact_name';
        span_hide.innerHTML = Util.doTruncarStr(messenger.Chat.push_name, 28);

        $(".caption").append(span);
        $(".caption").append(span_hide);

        if (info.is_group != 2 && info.is_private != 2) {

          if (info.labels_name) {

            let colors = info.label_color;
            let names = info.labels_name.toString();

            for (let i = 0; i < colors.split(",").length; i++) {
              if (names !== "") {

                let div = document.createElement('div');
                div.id = 'chat-labels';
                div.className = 'chat-labels';
                div.style.cursor = 'pointer';
                let bg_color = colors.split(",")[i];
                div.style.background = bg_color;
                div.style.color = getTextColor(bg_color);
                div.innerHTML = Util.doTruncarStr(names.split(",")[i], 20);
                $(".label-contact").append(div);

                if (i > 2) {
                  div.style.display = 'none';
                }
              }
            }

            if (colors.split(",").length > 3) {
              let count = colors.split(",").length - 3

              let span = document.createElement('span');
              span.id = 'label-hidden';
              span.className = 'label-hidden';
              span.style.cursor = 'pointer';
              span.style.fontStyle = "bold";
              span.style.fontSize = "17px";
              span.innerHTML = "+ " + count;
              $(".label-contact").append(span);
            }

          }

          const svg = `
          <div class="label-contact-tag">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="4 4 19.5 17.5" style="cursor: pointer;">
                <path d="M13.4763 4.21134L6.99575 6.13402C6.87486 6.16984 6.76437 6.2357 6.67471 6.32535C6.58506 6.415 6.51921 6.52549 6.48339 6.64638L4.56071 13.127C4.44791 13.5071 4.4381 13.9081 4.53231 14.2883C4.62652 14.6685 4.82132 15.0142 5.09652 15.2895L11.1801 21.373C11.863 22.0543 12.7949 22.4302 13.7712 22.4184C14.7476 22.4067 15.6888 22.0081 16.3884 21.3102L21.6596 16.039C22.3575 15.3395 22.756 14.3983 22.7678 13.4219C22.7796 12.4455 22.4036 11.5136 21.7224 10.8307L15.6388 4.74716C15.3635 4.47196 15.0179 4.27716 14.6377 4.18295C14.2574 4.08874 13.8565 4.09854 13.4763 4.21134ZM20.6681 11.8849C21.0775 12.2943 21.3036 12.8536 21.2965 13.4396C21.2894 14.0257 21.0498 14.5905 20.6304 15.0099L15.3593 20.2811C14.9399 20.7005 14.375 20.9401 13.789 20.9471C13.2029 20.9542 12.6437 20.7282 12.2343 20.3188L6.15075 14.2352C6.05848 14.1434 5.99312 14.0279 5.96149 13.9008C5.92987 13.7737 5.93314 13.6396 5.97096 13.5125L7.77793 7.42857L13.8624 5.62106C13.9895 5.58341 14.1235 5.58028 14.2505 5.61199C14.3775 5.64371 14.4929 5.70911 14.5846 5.80139L20.6681 11.8849Z" fill="#666666"></path>
                <path d="M11.0484 10.6992C11.4851 10.2625 11.4936 9.56298 11.0673 9.13671C10.641 8.71044 9.94146 8.71888 9.50478 9.15556C9.06811 9.59223 9.05967 10.2918 9.48594 10.7181C9.91221 11.1443 10.6118 11.1359 11.0484 10.6992Z" fill="#666666"></path>
            </svg>
          </div>`;

          $(".label-contact").append(svg);
        }

        $(".status").html("");
        setTimeout(() => {
          $(".right .head .head-left .picture img").attr("src", $("#" + info.hash + " .contact-image .avatar").attr("src"));
          $(".option .profile .avatar").attr("src", $("#" + info.hash + " .contact-image .avatar").attr("src"));
        }, 200);
        $(".input-text").focus();

        if (info.type == 1) {
          ta.contact.queryPresence();
        }

        ta.contact.queryInfo();

        if (info.is_group == 2 && info.is_private == 2) {
          ta.group.queryGroupParticipants();
        }

        info.is_private == 2 && info.is_group == 2 ? $("#participants").show() : $("#participants").hide();

        $("#" + messenger.Chat.selected + " .no-read-message").hide();
        $("#" + messenger.Chat.selected + " .no-read-message label").html(0);

        messenger.ChatList.updateCountView();
        ta.chat.read();

        $(".input-text").html("");
        $(".input-text").text("");

        let storage_text = localStorage.getItem(messenger.Chat.key_remote_id);
        if (storage_text && storage_text.trim().length > 0) {
          $(".input-text").text(storage_text);
          setCursorToEnd($(".input-text")[0]);
          localStorage.removeItem(messenger.Chat.key_remote_id);
        } else {
          $(".input-text").html("");
        }

        let last_message = document.querySelector(`#${CSS.escape(info.hash)} .body .last-message`);
        last_message.innerHTML = messenger.ChatList.setLastMessage(info);
      }

      $("#" + messenger.Chat.token).scrollTop($("#" + messenger.Chat.token).prop("scrollHeight") + 999);
      $("#" + messenger.Chat.token).scrollTop($("#" + messenger.Chat.token).scrollTop() - 50);
    },
    hide() {
      for (let i = 0; i < $(".messages").length; i++) {
        $("#" + $(".messages")[i].id).hide();
      }
      $(".right").hide();
    },
    wait() {

      let item = $("#" + messenger.Chat.selected).clone();
      $("#" + messenger.Chat.selected).remove();
      // let countWait = $("#list-wait").find(".fixed").length
      // if (countWait > 0) {
      //     $("#list-wait").prepend(item);
      // } else {

      $("#list-wait").prepend(item);

      ta.chat.wait();
      messenger.ChatList.updateCountView();
      $("#chat-attendence").show();
      $("#chat-waiting").hide();
    },
    attendance() {
      let item = $("#" + messenger.Chat.selected).clone();
      $("#" + messenger.Chat.selected).remove();
      $("#list-active").prepend(item);
      ta.chat.attendance();
      messenger.ChatList.updateCountView();
      $("#chat-active").click();
      $("#chat-attendence").hide();
      $("#chat-waiting").show();
    },
    close() {
      let info = messenger.ChatList.find(messenger.Chat.selected);
      if (info != null) {
        $(".option")[0].style.display = "none";
        $(".messenger .right")[0].style = "width: calc(100% - 360px)";
        $(".right").hide();
        $(".option").hide();
        $("#modal").hide();
        $("#sub-more").hide();
        $("#" + info.hash).remove();
        $("#" + info.chat).remove();
        messenger.ChatList.updateCountView();
      }
    },
  },
  Contact: {
    add(json) {
      const card = document.createElement("div");
      card.dataset.index = json.id_contact;
      card.classList.add("item");
      card.id = json.key_remote_id;

      const contact_container = document.createElement("div");
      contact_container.classList.add("contact-container");

      const image_container = document.createElement("div");
      image_container.classList.add("contact-image");

      const avatar = document.createElement("img");
      avatar.classList.add("avatar");
      avatar.src = "assets/img/avatar.svg";
      avatar.alt = "Avatar";

      const platform_icon_container = document.createElement("span");
      platform_icon_container.classList.add("platform-icon");

      const platform_icon = document.createElement("img");
      platform_icon.src = getPlatformIcon(json.type);
      platform_icon.alt = "Platform icon";

      const contact_info = document.createElement("div");
      contact_info.classList.add("body");

      const contact_header = document.createElement("div");
      contact_header.classList.add("contact-header");

      const contact_name = document.createElement("span");
      contact_name.classList.add("contact-name-span");
      contact_name.textContent = json.full_name;

      platform_icon_container.appendChild(platform_icon);
      image_container.appendChild(avatar);
      image_container.appendChild(platform_icon_container);

      contact_header.appendChild(contact_name);

      contact_info.appendChild(contact_header);

      contact_container.appendChild(image_container);
      contact_container.appendChild(contact_info);

      card.appendChild(contact_container);

      document.getElementById("list-find").appendChild(card);

      ta.contact.queryProfilePicture(json.key_remote_id);
    }
  },
  Message: {
    item: null,
    message_item: null,
    body: null,
    bottom: null,
    time: null,
    info: null,
    reaction: null,
    contact_timestamp: 0,
    appendItem(reverse) {
      if (reverse === true) {
        $("#" + messenger.Message.info.chat).append(messenger.Message.item);
      }
      else {
        $("#" + messenger.Message.info.chat).prepend(messenger.Message.item);
      }
    },
    setDate(json, reverse) {
      messenger.Message.info = messenger.ChatList.findByKeyRemoteId(json.key_remote_id);

      const data = Util.FormatShortDate(json.creation);

      let item = document.createElement("div");
      item.className = "item " + data.replace("/", "_").replace("/", "_");

      // Conversa fixada //
      if (item.dataset.index != '99999999') {
        item.dataset.index = json.creation;
      }

      let message = document.createElement("div");
      message.className = "information";

      let bottom = document.createElement("div");
      bottom.className = "bottom";
      bottom.innerHTML = "<span>" + Util.FormatShortDate(json.creation) + "</span>";

      item.id = Math.floor(Math.random() * 100000);

      message.appendChild(bottom);
      item.appendChild(message);

      if (reverse)
        $("#" + messenger.Message.info.chat).append(item);
      else
        $("#" + messenger.Message.info.chat).prepend(item);

      messenger.ChatList.setLastDateByKeyRemoteId(json.key_remote_id, data);
    },
    setReaction(reaction_container, data) {

      const reaction_box = document.createElement("div");
      reaction_box.classList.add("reaction-box");

      const reaction_data = document.createElement("span");
      reaction_data.innerText = data;

      reaction_box.appendChild(reaction_data);
      reaction_container.appendChild(reaction_box);

      reaction_data.classList.add("scale-effect");
    },
    removeRepeatedDate(json, reverse) {

      const date = Util.FormatShortDate(json.creation);
      const myclass = "." + date.replace("/", "_").replace("/", "_");

      let i = 0, y = 0;
      let element = "";

      if (!reverse) {
        $("#" + messenger.Message.info.chat).find(myclass).each((idx, elm) => {
          if ($(elm).text() === date) {
            if (i !== 0) elm.remove();
            i++;
          }
        });
      } else {

        $("#" + messenger.Message.info.chat).find(myclass).each((idx, elm) => {
          if ($(elm).text() === date) {

            element = document.getElementById(json.token);
            document.getElementById(json.token).remove();

            elm.insertAdjacentElement("afterend", element);
          }
        });

        $("#" + messenger.Message.info.chat).find(myclass).each((idx, elm) => {
          if ($(elm).text() === date) {
            if (y !== 0) elm.remove();
            y++;
          }
        });
      }
    },
    async requestOpenAI(media_url) {
      const key = localStorage.getItem("userToken");
      const errorMsg = GLOBAL_LANG.messenger_transcribe_audio_error_response;

      try {
        const myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");

        const requestOptions = {
          method: "POST",
          headers: myHeaders,
          body: JSON.stringify({ "audio_url": media_url }),
          redirect: 'follow'
        };

        const response = await fetch("https://services.talkall.com.br:4090/openai/transcription?key=" + key, requestOptions);
        const data = await response.json();

        if (data.reason === "api_key not found")
          return "error";

        return data.new || errorMsg;

      } catch (error) {
        return errorMsg;
      }
    },
    async transcribeAudio(media_url, event) {
      const audioText = document.createElement("span");
      audioText.innerHTML = GLOBAL_LANG.messenger_transcribe_audio_text;

      const message = event.target.parentElement;
      message.lastChild.remove();
      message.style.paddingBottom = "5px";
      message.appendChild(audioText);

      const text = await this.requestOpenAI(media_url);

      if (text === "error") {
        const url = document.createElement("a");
        url.href = document.location.origin + "/integration/add/openai";
        url.target = "_blank";
        url.textContent = "/integration/add/openai";

        audioText.innerHTML = GLOBAL_LANG.messenger_transcribe_audio_error_apy_key;
        audioText.appendChild(url);
      } else {
        audioText.innerHTML = text;
      }
    },
    starred(json) {

      if (json.starred == 1) {
        const targetElement = $("#" + json.token).find(".bottom");

        if (targetElement.length > 0) {
          const starClass = json.key_from_me == 1
            ? "fas fa-star icon-starred"
            : "fas fa-star icon-starred white-star";

          targetElement.append(`<i class="${starClass}"></i>`);
        }
      }
    },
    init(json) {
      messenger.Chats.forEach(chat => {
        if (chat.key_remote_id === json.key_remote_id) {
          if (!chat.last_message_timestamp || chat.last_message_timestamp < json.creation) {
            chat.last_message_timestamp = json.creation;

            try {
              if (!chat.hash || typeof chat.hash !== 'string') {
                return;
              }

              const satanize_hash = chat.hash
                .replace(/[!"#$%&'()*+,./:;<=>?@[\\\]^`{|}~]/g, '\\$&')
                .replace(/^\d/, '\\3$& ');

              const selector = `#${satanize_hash} .contact-time`;

              const contact_time_element = document.querySelector(selector);

              if (contact_time_element) {
                contact_time_element.textContent = Util.FormatShortTime(json.creation);
              }

            } catch (error) {
              console.error(chat, error);
            }
          }
        }
      });

      messenger.Message.info = messenger.ChatList.findByKeyRemoteId(json.key_remote_id);

      messenger.Message.item = document.createElement("div");
      messenger.Message.item.className = "item";
      messenger.Message.item.dataset.index = json.creation;
      messenger.Message.item.id = json.token;
      messenger.Message.item.style.alignItems = json.key_from_me == 1 ? "flex-start" : "flex-end";

      messenger.Message.message_item = document.createElement("div");
      messenger.Message.message_item.template = document.createElement("div");
      messenger.Message.message_item.interactive = document.createElement("div");
      messenger.Message.message_item.buttons = document.createElement("div");

      messenger.Message.reaction = document.createElement("div");
      messenger.Message.reaction.className = "reaction-container";
      messenger.Message.reaction.style = json.key_from_me == 1 ? "margin-left: 10px" : "margin-right: 10px";

      let isForward = $('.messages:visible').find('.item').find('.forward').find('input:visible').length > 0 ? true : false;
      let forward = document.createElement("div");
      forward.className = "forward";
      forward.style.position = "absolute";
      forward.style.top = "50%";
      forward.style.left = "12px";
      forward.style.transform = "translateY(-50%)";

      let forwardCheckBox = document.createElement("input");
      forwardCheckBox.type = "checkbox";
      forwardCheckBox.className = "checkbox-forward";
      forwardCheckBox.style.cursor = "pointer";

      if (json.media_type != 19) {
        forwardCheckBox.style.display = isForward ? "block" : "none";
      } else {
        forwardCheckBox.style.display = "none";
      }

      const no_forward_media_types = [18, 19, 20, 21, 22, 23, 24, 25, 27, 30, 33, 35];

      if (!no_forward_media_types.includes(json.media_type)) {
        forward.append(forwardCheckBox);
        messenger.Message.item.append(forward);
      }

      if (json.media_type < 17 || json.media_type == 27 || json.media_type == 30 || json.media_type == 35) {

        let participant = document.createElement("span");
        participant.className = "participant_message";
        participant.style = "font-size: 12px; float: left; width: 100%; margin-bottom: 5px; height: 14px;";

        participant.innerHTML = json.key_from_me == 1 ? "" : typeof json.from == "undefined" ? ta.contact.pushName(localStorage.getItem("userToken")) : ta.contact.pushName(json.from);

        if (json.media_type == 27) {
          messenger.Message.message_item.template.append(participant);

        }
        else if (json.media_type == 30 || json.media_type == 35) {

          messenger.Message.message_item.interactive.append(participant);
        }
        else {
          messenger.Message.message_item.append(participant);

        }

        if (json.quoted_row_id != null && json.quoted_row_id != '' && json.quoted != null) {

          let mention = document.createElement("div");
          mention.className = "mention";
          mention.style.width = "100%";

          let mentionFrom = document.createElement("span");
          participant.style.paddingLeft = "10px";

          if (json.data != null && json.data.participant != null && json.data.participant != undefined) {
            mentionFrom.textContent = json.quoted.participant;
          }

          if (json.quoted != null) {
            let spanMention = document.createElement("span");
            switch (parseInt(json.quoted.media_type)) {
              case 27:
              case 1:
                if (json.quoted.buttons == null) {
                  let container_message = document.createElement("div");
                  container_message.className = "container-message container-quoted";
                  container_message.style.paddingBottom = "0px";
                  container_message.style.paddingLeft = "0px";
                  container_message.style.float = "left";
                  container_message.style.height = "auto";
                  container_message.style.marginTop = "0px";
                  container_message.style.width = "calc(100% - 4px)";

                  container_message.id = json.quoted.token == undefined ? "mg_" + json.quoted_row_id : "mg_" + json.quoted.token;
                  if (json.quoted.creation == undefined) {
                    let quotedToken = container_message.id.split('_')[1];
                    container_message.setAttribute('name', $(`#${quotedToken}`).data('index'));
                  } else {
                    container_message.setAttribute('name', json.quoted.creation);
                  }

                  spanMention.innerHTML = Util.nl2br(json.quoted.data);
                  spanMention.style.float = "left";
                  spanMention.style.paddingTop = "10px";
                  spanMention.style.paddingBottom = "10px";
                  spanMention.style.cursor = "pointer";
                  spanMention.style.marginLeft = "18px";
                  spanMention.style.wordBreak = "break-all";

                  container_message.appendChild(spanMention);
                  messenger.Message.message_item.appendChild(container_message);
                }
                break;
              case 2:
                let media_url = json.quoted.media_url;
                let mime_type = json.quoted.media_mime_type;
                let media_id = json.quoted.token == undefined ? "ad_" + json.quoted_row_id : "ad_" + json.quoted.token;

                let container_audio = document.createElement("div");
                container_audio.className = "container-audio container-quoted";
                container_audio.id = media_id;
                if (json.quoted.creation == undefined) {
                  let quotedToken = container_audio.id.split('_')[1];
                  contaicontainer_audioner_message.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                  container_audio.setAttribute('name', json.quoted.creation);
                }

                let audio = document.createElement("audio");
                audio.controls = true;
                audio.controlsList = "nodownload";
                audio.style.width = "100%";
                audio.src = json.quoted.media_url;

                let source = document.createElement("source");
                source.src = json.quoted.media_url;

                audio.appendChild(source);
                container_audio.appendChild(audio);
                messenger.Message.message_item.appendChild(container_audio);

                Util.audioToBlob(media_id, media_url, mime_type)
                break;
              case 3:

                let iconImage = document.createElement("i");
                iconImage.className = "fas fa-camera icon-container";
                iconImage.style.fontSize = "22px";
                iconImage.style.float = "left";
                iconImage.style.marginTop = "44px";
                iconImage.style.marginLeft = "18px";
                iconImage.style.color = "#666666";

                let spanImage = document.createElement("span");
                spanImage.textContent = GLOBAL_LANG.messenger_media_types_photo;
                spanImage.style.float = "left";
                spanImage.style.marginTop = "46px";
                spanImage.style.marginLeft = "6px";
                spanImage.style.fontSize = "14px";

                let container_Image = document.createElement("div");
                container_Image.className = "container-img container-quoted";
                container_Image.style.height = "110px";
                container_Image.id = json.quoted.token == undefined ? "img_" + json.quoted_row_id : "img_" + json.quoted.token;
                if (json.quoted.creation == undefined) {
                  let quotedToken = container_Image.id.split('_')[1];
                  container_Image.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                  container_Image.setAttribute('name', json.quoted.creation);
                }

                let container = document.createElement("div");
                container.style.width = "122px";
                container.style.height = "108px";
                container.style.float = "right";
                container.style.marginTop = "1px";
                container.style.marginRight = "1px";
                container.style.cursor = "pointer";

                let image = document.createElement("img");
                image.src = json.quoted.media_url;
                image.className = "imageLink";
                image.style.width = "100%";
                image.style.height = "100%";
                image.style.objectFit = "cover";
                image.style.borderRadius = "3px";

                let linkImage = document.createElement("a");
                linkImage.className = "cancel-click"
                linkImage.href = json.quoted.media_url;
                linkImage.target = "_blank";

                if (json.quoted.data !== "0") {
                  spanMention.textContent = Util.doTruncarStr(json.quoted.data, 50);
                  spanMention.style.paddingLeft = "5px";
                  spanMention.style.paddingRight = "5px";
                  container_Image.appendChild(spanMention);
                }

                linkImage.appendChild(image);
                container.appendChild(linkImage);

                container_Image.appendChild(iconImage);
                container_Image.appendChild(spanImage);

                if (json.quoted.data === undefined) {
                  container_Image.appendChild(iconImage);
                  container_Image.appendChild(spanImage);
                }

                container_Image.appendChild(container);
                messenger.Message.message_item.appendChild(container_Image);
                break;
              case 4:
                messenger.Message.message_item.dataset.url = json.quoted.media_url;

                let container_document = document.createElement("div");
                container_document.className = "container-document container-quoted";
                container_document.style.paddingBottom = "58px";
                container_document.id = json.quoted.token == undefined ? "docum_" + json.quoted_row_id : "docum_" + json.quoted.token;

                if (json.quoted.creation == undefined) {
                  let quotedToken = container_document.id.split('_')[1];
                  container_document.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                  container_document.setAttribute('name', json.quoted.creation);
                }

                const download_icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                download_icon.setAttribute("id", "Layer_1");
                download_icon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
                download_icon.setAttribute("viewBox", "0 0 34 34");
                download_icon.classList.add("download-icon");
                download_icon.style.width = "34px";
                download_icon.style.float = "right";
                download_icon.style.marginRight = "10px";
                download_icon.style.marginTop = "12px";
                download_icon.style.cursor = "pointer";

                const path1 = document.createElementNS("http://www.w3.org/2000/svg", "path");
                path1.setAttribute("fill", "#666666");
                path1.setAttribute(
                  "d",
                  "M17 2c8.3 0 15 6.7 15 15s-6.7 15-15 15S2 25.3 2 17 8.7 2 17 2m0-1C8.2 1 1 8.2 1 17s7.2 16 16 16 16-7.2 16-16S25.8 1 17 1z"
                );

                const path2 = document.createElementNS("http://www.w3.org/2000/svg", "path");
                path2.setAttribute("fill", "#666666");
                path2.setAttribute(
                  "d",
                  "M22.4 17.5h-3.2v-6.8c0-.4-.3-.7-.7-.7h-3.2c-.4 0-.7.3-.7.7v6.8h-3.2c-.6 0-.8.4-.4.8l5 5.3c.5.7 1 .5 1.5 0l5-5.3c.7-.5.5-.8-.1-.8z"
                );

                download_icon.appendChild(path1);
                download_icon.appendChild(path2);

                switch (json.quoted.media_mime_type) {
                  case "assets/icons/pdf.svg":
                    var verify_media_meme = json.quoted.media_mime_type.replace("assets/icons/", "");
                    break;
                  default:
                    verify_media_meme = json.quoted.media_mime_type;
                    break;
                }

                let icon_document = document.createElement("img");
                let titleDocument = document.createElement("span");
                switch (verify_media_meme) {
                  case "application/pdf":
                  case "pdf.svg":
                    icon_document.src = "assets/icons/pdf.svg";
                    icon_document.style.marginLeft = "18px";
                    icon_document.style.marginTop = "12px";
                    icon_document.style.float = "left";
                    icon_document.style.width = "34px";
                    icon_document.style.height = "34px";
                    container_document.appendChild(icon_document);

                    break;
                  case "":
                    // case "application/octet-stream":
                    icon_document.className = "icon_document";
                    icon_document.src = "assets/icons/texto.svg";
                    icon_document.style.marginLeft = "-9px";
                    icon_document.style.marginTop = "10px";
                    icon_document.style.float = "left";
                    icon_document.style.width = "37px";
                    icon_document.style.height = "36px";
                    titleDocument.style.marginTop = "44px"
                    container_document.append(icon_document);

                    break;
                  case "":
                    // case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                    // case "application/vnd.ms-excel":
                    // case "excel.svg":
                    icon_document.className = "icon_document";
                    icon_document.src = "assets/icons/excel.svg";
                    icon_document.style.marginLeft = "-9px";
                    icon_document.style.marginTop = "10px";
                    icon_document.style.float = "left";
                    icon_document.style.width = "37px";
                    icon_document.style.height = "36px";
                    titleDocument.style.marginTop = "44px"
                    container_document.append(icon_document);

                    break;
                  default:
                    icon_document.className = "icon_document";
                    icon_document.src = "assets/icons/new-document.svg";
                    icon_document.style.marginLeft = "18px";
                    icon_document.style.marginTop = "12px";
                    icon_document.style.float = "left";
                    icon_document.style.width = "34px";
                    icon_document.style.height = "34px";
                    container_document.append(icon_document);
                    break;
                }

                titleDocument.textContent = Util.doTruncarStr(json.quoted.media_name ?? json.quoted.media_caption, 15);
                titleDocument.style.paddingLeft = "11px";
                titleDocument.style.paddingRight = "11px";
                titleDocument.style.position = "absolute";
                titleDocument.style.marginTop = "21px"
                titleDocument.style.fontSize = "14px"

                let linkDocument = document.createElement("a");
                linkDocument.className = "cancel-click";
                linkDocument.href = json.quoted.media_url;
                linkDocument.target = "_blank";
                linkDocument.appendChild(download_icon);

                container_document.appendChild(titleDocument);
                container_document.appendChild(linkDocument);
                messenger.Message.message_item.appendChild(container_document);
                break;
              case 5:
                messenger.Message.message_item.dataset.url = json.quoted.media_url;

                let container_video = document.createElement("div");
                container_video.className = "container-video container-quoted";
                container_video.style.height = "110px";
                container_video.id = json.quoted.token == undefined ? "vid_" + json.quoted_row_id : "vid_" + json.quoted.token;
                if (json.quoted.creation == undefined) {
                  let quotedToken = container_video.id.split('_')[1];
                  container_video.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                  container_video.setAttribute('name', json.quoted.creation);
                }

                let iconPlay = document.createElement("i");
                iconPlay.className = "fas fa-play-circle icon-container icon-focus cancel-click";
                iconPlay.style.fontSize = "30px";
                iconPlay.style.position = "relative";
                iconPlay.style.left = "calc(50% - 15px)";
                iconPlay.style.top = "calc(50% - 15px)";
                iconPlay.style.color = "#666666";

                let bodyVideo = document.createElement("div");
                bodyVideo.className = "icon-focus body-video";
                bodyVideo.style.width = "122px";
                bodyVideo.style.height = "108px";
                bodyVideo.style.float = "right";
                bodyVideo.style.marginRight = "1px"
                bodyVideo.style.marginTop = "1px"
                bodyVideo.style.borderRadius = "3px";
                bodyVideo.style.backgroundColor = "#beb7b78f";

                let iconVideo = document.createElement("i");
                iconVideo.className = "fas fa-video icon-container";
                iconVideo.style.fontSize = "22px";
                iconVideo.style.float = "left";
                iconVideo.style.marginLeft = "18px";
                iconVideo.style.marginTop = "44px";
                iconVideo.style.color = "#666666";

                let titleVideo = document.createElement("span");
                titleVideo.textContent = "Vídeo";
                titleVideo.style.float = "left";
                titleVideo.style.marginTop = "46px";
                titleVideo.style.marginLeft = "6px";
                titleVideo.style.fontSize = "14px";

                let linkVideo = document.createElement("a");
                linkVideo.href = json.quoted.media_url;
                linkVideo.target = "_blank";
                linkVideo.appendChild(iconPlay);

                bodyVideo.appendChild(linkVideo);
                container_video.appendChild(iconVideo);
                container_video.appendChild(titleVideo);
                container_video.appendChild(bodyVideo);
                messenger.Message.message_item.appendChild(container_video);

                spanMention.style.paddingLeft = "5px";
                spanMention.style.paddingRight = "5px";
                break;
              case 6:
                break;
              case 7:

                let container_location = document.createElement("div");
                container_location.className = "container-location container-quoted";
                container_location.style.height = "110px";
                container_location.id = json.quoted.token == undefined ? "loc_" + json.quoted_row_id : "loc_" + json.quoted.token;
                if (json.quoted.creation == undefined) {
                  let quotedToken = container_location.id.split('_')[1];
                  container_location.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                  container_location.setAttribute('name', json.quoted.creation);
                }

                let thumbnailLocation = document.createElement("div");
                thumbnailLocation.style.width = "122px";
                thumbnailLocation.style.height = "108px";
                thumbnailLocation.style.float = "right";
                thumbnailLocation.style.marginTop = "1px";
                thumbnailLocation.style.marginRight = "1px";
                thumbnailLocation.style.cursor = "pointer";

                let a_locontaion = document.createElement("a");
                a_locontaion.href = "http://maps.google.com/maps?q=" + json.latitude + "," + json.longitude + "&hl=pt-BR";
                a_locontaion.target = "_blank";

                let imgLocation = document.createElement("img");
                imgLocation.src = "./assets/img/localizacao.jpg";
                imgLocation.style.width = "100%";
                imgLocation.style.height = "100%";
                imgLocation.style.objectFit = "cover";
                imgLocation.style.borderRadius = "3px";

                let iconLocation = document.createElement("i");
                iconLocation.className = "fas fa-map-marked-alt icon-container";
                iconLocation.style.fontSize = "22px";
                iconLocation.style.float = "left";
                iconLocation.style.marginLeft = "18px";
                iconLocation.style.marginTop = "44px";
                iconLocation.style.color = "#666666";

                let titleLocation = document.createElement("span");
                titleLocation.textContent = GLOBAL_LANG.messenger_media_types_location;
                titleLocation.style.float = "left";
                titleLocation.style.marginTop = "50px";
                titleLocation.style.marginLeft = "10px";
                titleLocation.style.fontSize = "14px";

                thumbnailLocation.appendChild(imgLocation);
                container_location.appendChild(iconLocation);
                container_location.appendChild(titleLocation);
                container_location.appendChild(a_locontaion);
                container_location.appendChild(thumbnailLocation);
                messenger.Message.message_item.appendChild(container_location);
                break;
              case 8:
                break;
              case 9:

                let container_contact = document.createElement("div");
                container_contact.className = "container-contact container-quoted";
                container_contact.style.height = "80px";
                container_contact.id = json.quoted.token == undefined ? "cont_" + json.quoted_row_id : "cont_" + json.quoted.token;
                if (json.quoted.creation == undefined) {
                  let quotedToken = container_contact.id.split('_')[1];
                  container_contact.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                  container_contact.setAttribute('name', json.quoted.creation);
                }

                let iconContact = document.createElement("i");
                iconContact.className = "fas fa-user icon-container";
                iconContact.style.float = "left";
                iconContact.style.marginTop = "30px";
                iconContact.style.marginLeft = "18px";
                iconContact.style.fontSize = "22px";
                iconContact.style.color = "#666666";
                iconContact.style.pointerEvents = "none";

                let captionContact = document.createElement("span");
                captionContact.textContent = JSON.parse(json.quoted.data) ? (JSON.parse(json.quoted.data).firstName ? Util.doTruncarStr(JSON.parse(json.quoted.data).firstName, 20) : GLOBAL_LANG.messenger_media_types_contact) : GLOBAL_LANG.messenger_media_types_contact;
                captionContact.style.float = "left";
                captionContact.style.fontSize = "14px";
                captionContact.style.marginTop = "35px";
                captionContact.style.marginLeft = "10px";
                captionContact.style.pointerEvents = "none";

                container_contact.appendChild(iconContact);
                container_contact.appendChild(captionContact);
                messenger.Message.message_item.appendChild(container_contact);
                break;
              case 10:
                messenger.Message.message_item.dataset.url = json.quoted.media_url;

                let container_archive = document.createElement("div");
                container_archive.className = "container-archive container-quoted";
                container_archive.style.paddingBottom = "80px";
                container_archive.id = json.quoted.token == undefined ? "arch_" + json.quoted_row_id : "arch_" + json.quoted.token;
                if (json.quoted.creation == undefined) {
                  let quotedToken = container_archive.id.split('_')[1];
                  container_archive.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                  container_archive.setAttribute('name', json.quoted.creation);
                }

                let imgArchive = document.createElement("img");
                imgArchive.src = "assets/img/download.svg";
                imgArchive.className = "icon-container";
                imgArchive.style.width = "35px";
                imgArchive.style.float = "right";
                imgArchive.style.marginRight = "22px";
                imgArchive.style.marginTop = "8px";

                switch (json.quoted.media_mime_type) {
                  case "assets/icons/excel.svg":
                  case "assets/icons/texto.svg":
                    var verify_media_meme = json.quoted.media_mime_type.replace("assets/icons/", "");
                    break;
                  default:
                    verify_media_meme = json.quoted.media_mime_type;
                    break;
                }

                let icon_arquive = document.createElement("img");
                let titleArchive = document.createElement("span");

                switch (verify_media_meme) {
                  case "application/pdf":
                    icon_arquive.src = "assets/icons/pdf.svg";
                    icon_arquive.style.marginLeft = "-8px";
                    icon_arquive.style.marginTop = "9px";
                    icon_arquive.style.float = "left";
                    icon_arquive.style.width = "34px";
                    icon_arquive.style.height = "37px";
                    container_archive.appendChild(icon_arquive);
                    break;

                  case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                  case "application/vnd.ms-excel":
                  case "excel.svg":
                    icon_arquive.className = "icon_document";
                    icon_arquive.src = "assets/icons/excel.svg";
                    icon_arquive.style.marginLeft = "-9px";
                    icon_arquive.style.marginTop = "10px";
                    icon_arquive.style.float = "left";
                    icon_arquive.style.width = "37px";
                    icon_arquive.style.height = "36px";
                    titleArchive.style.marginTop = "44px"
                    container_archive.append(icon_arquive);
                    break;

                  case "application/octet-stream":
                  case "texto.svg":
                    icon_arquive.className = "icon_document";
                    icon_arquive.src = "assets/icons/texto.svg";
                    icon_arquive.style.marginLeft = "-9px";
                    icon_arquive.style.marginTop = "10px";
                    icon_arquive.style.float = "left";
                    icon_arquive.style.width = "37px";
                    icon_arquive.style.height = "36px";
                    titleArchive.style.marginTop = "44px"
                    container_archive.append(icon_arquive);
                    break;

                  case "text/plain":
                    icon_arquive.className = "icon_document";
                    icon_arquive.src = "assets/icons/txt.svg";
                    icon_arquive.style.marginLeft = "-9px";
                    icon_arquive.style.marginTop = "7px";
                    icon_arquive.style.float = "left";
                    icon_arquive.style.width = "44px";
                    icon_arquive.style.height = "41px";
                    titleArchive.style.marginTop = "44px"
                    container_archive.append(icon_arquive);

                    break;

                  default:

                    icon_arquive.className = "icon_document";
                    icon_arquive.src = "assets/icons/new-document.svg";
                    icon_arquive.style.marginLeft = "-9px";
                    icon_arquive.style.marginTop = "10px";
                    icon_arquive.style.float = "left";
                    icon_arquive.style.width = "37px";
                    icon_arquive.style.height = "36px";
                    titleArchive.style.marginTop = "44px"
                    container_archive.append(icon_arquive);
                    break;
                }

                titleArchive.textContent = json.quoted.title == null ? Util.doTruncarStr(json.quoted.file_name, 15) : Util.doTruncarStr(json.quoted.title, 15);
                titleArchive.style.paddingLeft = "11px";
                titleArchive.style.paddingRight = "40px";
                titleArchive.style.position = "absolute";

                let linkArchive = document.createElement("a");
                linkArchive.className = "cancel-click";
                linkArchive.target = "_blank";
                linkArchive.href = json.quoted.media_url;
                linkArchive.appendChild(imgArchive);

                container_archive.appendChild(titleArchive);
                container_archive.appendChild(linkArchive);
                messenger.Message.message_item.appendChild(container_archive);
                break;
              case 30:
              case 35:
                let container_message_inter = document.createElement("div");
                container_message_inter.className = "container-message container-quoted";
                container_message_inter.style.paddingBottom = "0px";
                container_message_inter.style.paddingLeft = "0px";
                container_message_inter.style.float = "left";
                container_message_inter.style.height = "auto";
                container_message_inter.style.marginTop = "0px";
                container_message_inter.style.width = "calc(100% - 4px)";
                container_message_inter.id = json.quoted.token == undefined ? "mg_" + json.quoted_row_id : "mg_" + json.quoted.token;

                if (json.quoted.creation == undefined) {
                  let quotedToken = container_message_inter.id.split('_')[1];
                  container_message_inter.setAttribute('name', $(`#${quotedToken}`).data('index'));
                } else {
                  container_message_inter.setAttribute('name', json.quoted.creation);
                }

                spanMention.innerHTML = (text = Util.nl2br(JSON.parse(json.quoted.data).interactive.body.text)).length > 80 ? text.substring(0, 80) + '...' : text;
                spanMention.style.display = "inline-block";
                spanMention.style.paddingTop = "10px";
                spanMention.style.paddingBottom = "10px";
                spanMention.style.cursor = "pointer";
                spanMention.style.marginLeft = "18px";
                spanMention.style.wordBreak = "break-all";

                container_message_inter.appendChild(spanMention);

                if (JSON.parse(json.quoted.data).interactive.footer) {
                  spanMention.style.paddingBottom = "2px";
                  var span_footer = document.createElement("div");
                  span_footer.className = "interactive-option";
                  span_footer.innerHTML = Util.nl2br(JSON.parse(json.quoted.data).interactive.footer.text);
                  span_footer.style.fontSize = "0.7rem";
                  span_footer.style.display = "block";
                  span_footer.style.color = "#8696a0";
                  span_footer.style.whiteSpace = 'break-spaces';
                  span_footer.style.marginLeft = "18px";
                  span_footer.style.paddingBottom = "10px";
                  container_message_inter.appendChild(span_footer);
                }

                messenger.Message.message_item.appendChild(container_message_inter);
                break;
            }
          }
        }

      }

      if ((messenger.Message.info.revoke == true) || (messenger.Message.info.reply == true)) {
        if (json.media_type != 27 && json.media_type != 30 && json.media_type != 33 && json.media_type != 35) {


          let option = document.createElement("i");
          option.className = json.key_from_me == 1 ? "fas fa-angle-down dropdown dropdown-left" : "fas fa-angle-down dropdown dropdown-right";
          option.style.display = "none";
          messenger.Message.message_item.prepend(option);
        }
      }

      messenger.Message.body = document.createElement("div");
      messenger.Message.body.className = "body";
      messenger.Message.body.style.width = "100%";
      messenger.Message.body.style.cssFloat = "left";

      messenger.Message.bottom = document.createElement("div");
      messenger.Message.bottom.className = "bottom";

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.style.cssFloat = 'left';
          messenger.Message.message_item.className += "messageLeft";
          break;
        case 2:
          messenger.Message.message_item.style.cssFloat = 'right';
          messenger.Message.message_item.className += "messageRight";

          let msgStatus = document.createElement("div");
          msgStatus.className = "ackMessage";
          msgStatus.style.display = "none";
          msgStatus.innerHTML = json.msgStatus;

          messenger.Message.body.appendChild(msgStatus);

          switch (parseInt(json.msgStatus)) {
            case 0:
              messenger.Message.bottom.innerHTML = Util.msgWait();
              break;
            case 1:
              messenger.Message.bottom.innerHTML = Util.msgSend();
              break;
            case 2:
              messenger.Message.bottom.innerHTML = Util.msgReceived();
              break;
            case 3:
            case 4:
              messenger.Message.bottom.innerHTML = Util.msgRead();
              break;
          }
          break;
      }

      if (parseInt(json.media_type) < 19 || parseInt(json.media_type) == 27 || parseInt(json.media_type) == 30 || parseInt(json.media_type) == 32 || parseInt(json.media_type) == 35) {

        messenger.Message.time = document.createElement("span");
        messenger.Message.time.className = "time";
        messenger.Message.time.textContent = Util.FormatShortTime(json.creation);
        messenger.Message.time.style.cssFloat = 'right';

      }

      if (json.media_type == 27)
        messenger.Message.message_item.template.append(messenger.Message.body);
      else if (json.media_type == 30 || json.media_type == 35)
        messenger.Message.message_item.interactive.append(messenger.Message.body);
      else
        messenger.Message.message_item.append(messenger.Message.body);

      if (json.media_type == 27)
        messenger.Message.message_item.template.append(messenger.Message.bottom);
      else if (json.media_type == 30 || json.media_type == 35)
        messenger.Message.message_item.interactive.append(messenger.Message.bottom);
      else {
        if (json.media_type != 18)
          messenger.Message.message_item.append(messenger.Message.bottom)
      }

      if (json.media_type < 18 || json.media_type == 27 || json.media_type == 30 || json.media_type == 32 || json.media_type == 35) {
        messenger.Message.bottom.append(messenger.Message.time);
      }

      if (json.media_type == 27) {
        messenger.Message.item.append(messenger.Message.message_item);
        messenger.Message.message_item.append(messenger.Message.message_item.template);
        messenger.Message.message_item.append(messenger.Message.message_item.buttons);
      } else if (json.media_type == 30 || json.media_type == 35) {
        messenger.Message.item.append(messenger.Message.message_item);
        messenger.Message.message_item.append(messenger.Message.message_item.interactive);
        messenger.Message.message_item.append(messenger.Message.message_item.buttons);
      } else
        messenger.Message.item.append(messenger.Message.message_item);

      messenger.Message.item.append(messenger.Message.reaction);
    },
    verifyReaction(json) {

      if (document.getElementById(json.token)) {

        const id = CSS.escape(json.token);
        const element = document.querySelector(`#${id} .reaction-box`);

        element?.remove();

        if (json.reaction)
          messenger.Message.setReaction(document.querySelector(`#${id} .reaction-container`), json.reaction);
      }
    },
    TextMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      if (json.referral != null) {
        const url_default = document.location.origin + "/assets/img/referral.png";

        const referral = document.createElement("div");
        referral.className = "referral";

        const container = document.createElement("div");
        container.className = "container";

        const container_text = document.createElement("div");
        container_text.className = "container-text";

        const box = document.createElement("div");
        box.className = "box";

        if (json.referral.media_type_ads === "video") {

          const video = document.createElement("video");
          video.src = json.referral.video_url ?? url_default;

          video.addEventListener("click", () => window.open(json.referral.source_url, '_blank'));
          box.append(video);
        } else {

          const link = document.createElement("a");
          link.href = json.referral.source_url;
          link.target = "blank";

          const img = document.createElement("img");
          img.src = json.referral.thumbnail_url ?? url_default;
          img.className = "imageLink";

          link.append(img);
          box.append(link);
        }

        const headline = document.createElement("span");
        headline.textContent = Util.doTruncarStr(json.referral.headline, 33);
        headline.className = "headline";

        const body = document.createElement("span");
        body.textContent = Util.doTruncarStr(json.referral.body, 100);
        body.className = "text-referral";

        const text = document.createElement("span");
        text.className = "text";
        text.textContent = json.data;
        text.style.display = "block";
        text.style.marginTop = "9px";
        text.style.marginBottom = "-10px";

        container_text.appendChild(headline);
        container_text.appendChild(body);

        container.append(box);
        container.append(container_text);
        referral.append(container);

        messenger.Message.message_item.style.maxWidth = "470px";

        messenger.Message.body.append(referral);
        messenger.Message.body.append(text);
        messenger.Message.appendItem(reverse);

      } else if (json.quoted?.buttons != null) {

        const parsed = (json.data && typeof json.data === 'string' && json.data.startsWith('{')) ? JSON.parse(json.data) : (typeof json.data === 'object' ? json.data : null);
        const checked = JSON.parse(json.quoted.buttons);

        if (checked) {

          if (checked[0].type == "FLOW") {
            function getValueByPatterns(obj, patterns, transform = null) {
              for (const pattern of patterns) {
                const key = Object.keys(obj).find(k => k.includes(pattern));
                if (key && obj[key]) {
                  return transform ? transform(obj[key]) : obj[key];
                }
              }
              return "N/A";
            }

            function extractStarsOnly(text) {
              if (!text || text === "N/A") return "N/A";

              const stars = text.match(/[★⭐]/g);

              return stars ? stars.join("") : "N/A";
            }

            const notePatterns = ["Avaliar", "Escolha", "Nota"];
            const commentPatterns = ["comentrio", "Comentrio", "Deixe", "Adicionar"];

            const fullNoteValue = getValueByPatterns(parsed, notePatterns, (value) => {
              return value.split("_Nota ")[1] || value;
            });

            const evaluationNote = extractStarsOnly(fullNoteValue);
            const comment = getValueByPatterns(parsed, commentPatterns);

            for (i = 0; i < checked.length; i++) {
              const card = document.createElement("div");
              card.classList.add('response-flow');

              const icon = document.createElement("div");
              icon.innerHTML = "📄";
              icon.style.fontSize = "22px";
              icon.style.marginRight = "10px";

              const texts = document.createElement("div");

              const title = document.createElement("div");
              title.textContent = checked[i].text;
              title.style.fontWeight = "bold";
              title.style.fontSize = "14px";

              const subtitle = document.createElement("div");
              subtitle.textContent = GLOBAL_LANG.messenger_template_flow_message_subtitle;
              subtitle.style.fontSize = "13px";
              subtitle.style.color = "#848484";

              texts.appendChild(title);
              texts.appendChild(subtitle);

              card.appendChild(icon);
              card.appendChild(texts);

              card.onclick = () => openFlowModal({
                title: GLOBAL_LANG.messenger_template_flow_message_title_modal,
                evaluationNote,
                comment
              });

              messenger.Message.body.append(card);
            }
            messenger.Message.appendItem(reverse);

          } else if (checked[0].type == "QUICK_REPLY") {

            let span = document.createElement("span");
            span.style.whiteSpace = 'normal';
            span.style.marginLeft = "0px";
            span.innerHTML = Util.nl2br(json.data, true);

            messenger.Message.body.append(span);
            messenger.Message.appendItem(reverse);
          }
        } else {
          console.log('Invalid data');
        }

      } else {

        let span = document.createElement("span");

        if (json.quoted && json.quoted.media_type == 35) {
          const data = JSON.parse(json.data);
          span.innerHTML = `${GLOBAL_LANG.messenger_interactive_flow_message_client} ${data.client ? GLOBAL_LANG.messenger_interactive_flow_message_yes : GLOBAL_LANG.messenger_interactive_flow_message_no}<br> CNPJ: ${data.cnpj ?? ""}`;
        } else {
          span.innerHTML = Util.nl2br(json.data, true);
        }

        span.style.whiteSpace = 'normal';
        span.style.marginLeft = "0px";

        if (json.forwarded == 1) {
          let forwardSpan = document.createElement("span");

          forwardSpan.style = "font-style: italic; font-size: 11.4px; margin-left: 5px; margin-bottom: 5px;";
          forwardSpan.textContent = GLOBAL_LANG.messenger_forwarded_message;

          let iconForward = document.createElement("i");
          iconForward.className = "iconForward fas fa-share";
          iconForward.style.float = "left";
          iconForward.style.fontSize = "14px";
          iconForward.style.marginBottom = "3px";

          $(messenger.Message.body.parentElement).find('.participant_message').after(iconForward);
          $(messenger.Message.body.parentElement).find('.participant_message').after(forwardSpan);
        }

        messenger.Message.body.append(span);
        messenger.Message.appendItem(reverse);

      }

      const tail_out_message = document.createElement("div");
      const svg_element = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg_element.setAttribute("width", "29");
      svg_element.setAttribute("height", "19");
      svg_element.setAttribute("viewBox", "0 0 29 19");
      svg_element.setAttribute("fill", "none");

      const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path_element.setAttribute("d", "M12.8722 17.4704C13.6712 18.4164 15.1292 18.4164 15.9282 17.4704L27.904 3.29047C29.0021 1.99028 28.0779 0 26.376 0H2.42437C0.72252 0 -0.201688 1.99028 0.896405 3.29047L12.8722 17.4704Z");

      svg_element.appendChild(path_element);
      tail_out_message.appendChild(svg_element);

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "textMessage left";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 0) {
            tail_out_message.className = "tailOutMessageLeft";
            path_element.setAttribute("fill", "#ffffff");
            messenger.Message.message_item.prepend(tail_out_message);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "textMessage right";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 1) {
            tail_out_message.className = "tailOutMessageRight";
            path_element.setAttribute("fill", "#2263d3");
            messenger.Message.message_item.prepend(tail_out_message);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      messenger.Message.starred(json);
      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

    },
    ExtendedTextMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      const tail_out_message = document.createElement("div");
      const svg_element = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg_element.setAttribute("width", "29");
      svg_element.setAttribute("height", "19");
      svg_element.setAttribute("viewBox", "0 0 29 19");
      svg_element.setAttribute("fill", "none");

      const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path_element.setAttribute("d", "M12.8722 17.4704C13.6712 18.4164 15.1292 18.4164 15.9282 17.4704L27.904 3.29047C29.0021 1.99028 28.0779 0 26.376 0H2.42437C0.72252 0 -0.201688 1.99028 0.896405 3.29047L12.8722 17.4704Z");

      svg_element.appendChild(path_element);
      tail_out_message.appendChild(svg_element);

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "textMessage left";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 0) {
            tail_out_message.className = "tailOutMessageLeft";
            path_element.setAttribute("fill", "#ffffff");
            messenger.Message.message_item.prepend(tail_out_message);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "textMessage right";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 1) {
            tail_out_message.className = "tailOutMessageRight";
            path_element.setAttribute("fill", "#2263d3");
            messenger.Message.message_item.prepend(tail_out_message);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      let span = document.createElement("span");
      span.innerHTML = json.data;
      span.style.whiteSpace = 'normal';
      span.style.marginLeft = "5px";

      messenger.Message.body.append(span);
      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

    },
    ImageMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      const tail_out_message = document.createElement("div");
      const svg_element = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg_element.setAttribute("width", "29");
      svg_element.setAttribute("height", "19");
      svg_element.setAttribute("viewBox", "0 0 29 19");
      svg_element.setAttribute("fill", "none");

      const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path_element.setAttribute("d", "M12.8722 17.4704C13.6712 18.4164 15.1292 18.4164 15.9282 17.4704L27.904 3.29047C29.0021 1.99028 28.0779 0 26.376 0H2.42437C0.72252 0 -0.201688 1.99028 0.896405 3.29047L12.8722 17.4704Z");

      svg_element.appendChild(path_element);
      tail_out_message.appendChild(svg_element);

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "ImageMessage left";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 0) {
            tail_out_message.className = "tailOutMessageLeft";
            path_element.setAttribute("fill", "#ffffff");
            messenger.Message.message_item.prepend(tail_out_message);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "ImageMessage right";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 1) {
            tail_out_message.className = "tailOutMessageRight";
            path_element.setAttribute("fill", "#2263d3");
            messenger.Message.message_item.prepend(tail_out_message);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      if (json.forwarded == 1) {
        let forwardSpan = document.createElement("span");

        forwardSpan.style = "font-style: italic; font-size: 11.4px; margin-left: 5px; margin-bottom: 5px;";
        forwardSpan.textContent = GLOBAL_LANG.messenger_forwarded_message;

        let iconForward = document.createElement("i");
        iconForward.className = "iconForward fas fa-share";
        iconForward.style.float = "left";
        iconForward.style.fontSize = "14px";
        iconForward.style.marginBottom = "3px";

        $(messenger.Message.body.parentElement).find('.participant_message').after(iconForward);
        $(messenger.Message.body.parentElement).find('.participant_message').after(forwardSpan);
      }

      let image = document.createElement("img");
      image.src = json.media_url;
      image.classList.add("UrlImageMessage");
      image.dataset.url = json.media_url;

      image.addEventListener("error", (event) => {
        event.target.removeAttribute("data-url");
        event.target.remove();
      });

      messenger.Message.body.appendChild(image);

      if (json.media_caption != "") {
        let caption = document.createElement("span");
        caption.textContent = json.media_caption == 0 ? "" : json.media_caption;
        messenger.Message.body.append(caption);
      }

      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

    },
    VideoMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      const tail_out_message = document.createElement("div");
      const svg_element = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg_element.setAttribute("width", "29");
      svg_element.setAttribute("height", "19");
      svg_element.setAttribute("viewBox", "0 0 29 19");
      svg_element.setAttribute("fill", "none");

      const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path_element.setAttribute("d", "M12.8722 17.4704C13.6712 18.4164 15.1292 18.4164 15.9282 17.4704L27.904 3.29047C29.0021 1.99028 28.0779 0 26.376 0H2.42437C0.72252 0 -0.201688 1.99028 0.896405 3.29047L12.8722 17.4704Z");

      svg_element.appendChild(path_element);
      tail_out_message.appendChild(svg_element);

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "VideoMessage left";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 0) {
            tail_out_message.className = "tailOutMessageLeft";
            path_element.setAttribute("fill", "#ffffff");
            messenger.Message.message_item.prepend(tail_out_message);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "VideoMessage right";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 1) {
            tail_out_message.className = "tailOutMessageRight";
            path_element.setAttribute("fill", "#2263d3");
            messenger.Message.message_item.prepend(tail_out_message);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      if (json.forwarded == 1) {
        let forwardSpan = document.createElement("span");

        forwardSpan.style = "font-style: italic; font-size: 11.4px; margin-left: 5px; margin-bottom: 5px;";
        forwardSpan.textContent = GLOBAL_LANG.messenger_forwarded_message;

        let iconForward = document.createElement("i");
        iconForward.className = "iconForward fas fa-share";
        iconForward.style.float = "left";
        iconForward.style.fontSize = "14px";
        iconForward.style.marginBottom = "3px";

        $(messenger.Message.body.parentElement).find('.participant_message').after(iconForward);
        $(messenger.Message.body.parentElement).find('.participant_message').after(forwardSpan);
      }

      let videoo = document.createElement("video");
      videoo.src = json.media_url
      videoo.controls = true;

      let iconPlay = document.createElement("i");
      iconPlay.className = "fas fa-play-circle icon-play";
      iconPlay.style.cursor = "pointer";

      var thumbnail = document.createElement("div");
      thumbnail.className = "thumbnail";

      let img = document.createElement("img");
      img.src = 'data:image/jpeg;base64,' + json.thumb_image;

      var body = document.createElement("div");
      body.className = "body";

      messenger.Message.body.appendChild(videoo);

      if (json.media_caption != "") {
        let caption = document.createElement("span");
        caption.textContent = json.media_caption == 0 ? "" : json.media_caption;
        messenger.Message.body.append(caption);
      }

      thumbnail.appendChild(img);

      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

    },
    StoryMentionMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      const is_reply_story = json.data ? true : false;
      var tailOutMessage = document.createElement("span");

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "StoryMentionMessage left";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontStoryMentionMessage");
          if (tailOut == 0) {
            tailOutMessage.className = "tailOutStoryMentionMessageLeft";
            messenger.Message.message_item.prepend(tailOutMessage);
            tailOut = 1;
          }
          break;
      }

      let reply = document.createElement("span");
      reply.className = "is-reply";
      reply.style.fontSize = localStorage.getItem("fontIsReplyStory");
      reply.innerHTML = is_reply_story ? json.data : "";

      let caption = document.createElement("span");
      caption.textContent = is_reply_story ? GLOBAL_LANG.messenger_story_reply : GLOBAL_LANG.messenger_story_mention;


      if (json.media_mime_type === "image/jpeg") {

        let container_img = document.createElement("div");

        let image = document.createElement("img");
        image.src = json.media_url;
        image.classList.add("UrlStoryMention");
        image.dataset.url = json.media_url;

        image.addEventListener("error", (event) => {
          event.target.removeAttribute("data-url");
          event.target.remove();
        });

        messenger.Message.body.appendChild(caption);
        messenger.Message.body.appendChild(container_img);
        container_img.appendChild(image);
        messenger.Message.body.appendChild(reply);

      }

      if (json.media_mime_type === "video/mp4") {

        let container_video = document.createElement("div");

        let video = document.createElement("video");
        video.src = json.media_url;
        video.classList.add("UrlStoryMention");
        video.dataset.url = json.media_url;

        video.addEventListener("error", (event) => {
          event.target.removeAttribute("data-url");
          event.target.remove();
        });

        messenger.Message.body.appendChild(caption);
        messenger.Message.body.appendChild(container_video);
        container_video.appendChild(video);
        messenger.Message.body.appendChild(reply);

      }

      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);
      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);

    },
    GifMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);
      var tailOutMessage = document.createElement("span");

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "GifMessage left";
          if (tailOut == 0) {
            tailOutMessage.className = "tailOutMessageLeft";
            messenger.Message.message_item.prepend(tailOutMessage);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "GifMessage right";
          if (tailOut == 1) {
            tailOutMessage.className = "tailOutMessageRight";
            messenger.Message.message_item.prepend(tailOutMessage);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      let vid = document.createElement("video");
      vid.src = json.media_url;
      vid.type = "video/mp4";

      let caption = document.createElement("span");
      caption.textContent = json.media_caption;

      messenger.Message.body.append(vid);
      messenger.Message.body.append(caption);

      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

    },
    StickerMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);
      var tailOutMessage = document.createElement("span");

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "StickerMessage left";
          if (tailOut == 0) {
            tailOutMessage.className = "tailOutMessageLeft";
            tailOutMessage.style.top = "0px";
            messenger.Message.message_item.prepend(tailOutMessage);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "StickerMessage right";
          if (tailOut == 1) {
            tailOutMessage.className = "tailOutMessageRight";
            messenger.Message.message_item.prepend(tailOutMessage);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      var sticker = document.createElement("img");
      sticker.src = json.media_url;
      sticker.style.background = "transparent";
      sticker.style.width = "100%";

      messenger.Message.body.append(sticker);
      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

    },
    AudioMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      let media_id = json.token;
      let media_url = json.media_url;
      let mime_type = json.media_mime_type;

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      const tail_out_message = document.createElement("div");
      const svg_element = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg_element.setAttribute("width", "29");
      svg_element.setAttribute("height", "19");
      svg_element.setAttribute("viewBox", "0 0 29 19");
      svg_element.setAttribute("fill", "none");

      const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path_element.setAttribute("d", "M12.8722 17.4704C13.6712 18.4164 15.1292 18.4164 15.9282 17.4704L27.904 3.29047C29.0021 1.99028 28.0779 0 26.376 0H2.42437C0.72252 0 -0.201688 1.99028 0.896405 3.29047L12.8722 17.4704Z");

      svg_element.appendChild(path_element);
      tail_out_message.appendChild(svg_element);

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "AudioMessage left";
          if (tailOut == 0) {
            tail_out_message.className = "tailOutMessageLeft";
            path_element.setAttribute("fill", "#ffffff");
            messenger.Message.message_item.prepend(tail_out_message);
            tailOut = 1;
          }

          let audioTranscribeIcon = document.createElement("img");
          audioTranscribeIcon.src = document.location.origin + "/assets/icons/messenger/stars_ai.svg";
          audioTranscribeIcon.title = GLOBAL_LANG.messenger_transcribe_audio_icon_title;
          audioTranscribeIcon.className = "audioTranscribeIcon";

          messenger.Message.message_item.appendChild(audioTranscribeIcon);
          audioTranscribeIcon.addEventListener("click", (event) => this.transcribeAudio(media_url, event));

          break;
        case 2:
          messenger.Message.message_item.className = "AudioMessage right";
          if (tailOut == 1) {
            tail_out_message.className = "tailOutMessageRight";
            path_element.setAttribute("fill", "#2263d3");
            messenger.Message.message_item.prepend(tail_out_message);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      if (json.forwarded == 1) {
        let forwardSpan = document.createElement("span");

        forwardSpan.style = "font-style: italic; font-size: 11.4px; margin-left: 5px; margin-bottom: 5px;";
        forwardSpan.textContent = GLOBAL_LANG.messenger_forwarded_message;

        let iconForward = document.createElement("i");
        iconForward.className = "iconForward fas fa-share";
        iconForward.style.float = "left";
        iconForward.style.fontSize = "14px";
        iconForward.style.marginBottom = "3px";

        $(messenger.Message.body.parentElement).find('.participant_message').after(iconForward);
        $(messenger.Message.body.parentElement).find('.participant_message').after(forwardSpan);
      }


      let audio = document.createElement("audio");
      audio.controls = true;
      audio.controlsList = "nodownload";
      audio.style.width = "100%";
      audio.src = json.media_url;

      let source = document.createElement("source");
      source.src = json.media_url;

      audio.appendChild(source);

      messenger.Message.body.append(audio);
      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

      Util.audioToBlob(media_id, media_url, mime_type)

    },
    DocumentMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      const tail_out_message = document.createElement("div");
      const svg_element = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg_element.setAttribute("width", "29");
      svg_element.setAttribute("height", "19");
      svg_element.setAttribute("viewBox", "0 0 29 19");
      svg_element.setAttribute("fill", "none");

      const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path_element.setAttribute("d", "M12.8722 17.4704C13.6712 18.4164 15.1292 18.4164 15.9282 17.4704L27.904 3.29047C29.0021 1.99028 28.0779 0 26.376 0H2.42437C0.72252 0 -0.201688 1.99028 0.896405 3.29047L12.8722 17.4704Z");

      svg_element.appendChild(path_element);
      tail_out_message.appendChild(svg_element);

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "DocumentMessage left";
          if (tailOut == 0) {
            tail_out_message.className = "tailOutMessageLeft";
            path_element.setAttribute("fill", "#ffffff");
            messenger.Message.message_item.prepend(tail_out_message);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "DocumentMessage right";
          if (tailOut == 1) {
            tail_out_message.className = "tailOutMessageRight";
            path_element.setAttribute("fill", "#2263d3");
            messenger.Message.message_item.prepend(tail_out_message);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      if (json.forwarded == 1) {
        let forwardSpan = document.createElement("span");

        forwardSpan.style = "font-style: italic; font-size: 11.4px; margin-left: 5px; margin-bottom: 5px;";
        forwardSpan.textContent = GLOBAL_LANG.messenger_forwarded_message;

        let iconForward = document.createElement("i");
        iconForward.className = "iconForward fas fa-share";
        iconForward.style.float = "left";
        iconForward.style.fontSize = "14px";
        iconForward.style.marginBottom = "3px";

        $(messenger.Message.body.parentElement).find('.participant_message').after(iconForward);
        $(messenger.Message.body.parentElement).find('.participant_message').after(forwardSpan);
      }

      messenger.Message.message_item.dataset.url = json.media_url;

      const download_icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      download_icon.setAttribute("id", "Layer_1");
      download_icon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
      download_icon.setAttribute("viewBox", "0 0 34 34");
      download_icon.classList.add("download-icon");
      download_icon.style.width = "34px";
      download_icon.style.float = "right";
      download_icon.style.marginRight = "10px";
      download_icon.style.marginTop = "12px";
      download_icon.style.cursor = "pointer";

      const path1 = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path1.setAttribute("fill", "#666666");
      path1.setAttribute(
        "d",
        "M17 2c8.3 0 15 6.7 15 15s-6.7 15-15 15S2 25.3 2 17 8.7 2 17 2m0-1C8.2 1 1 8.2 1 17s7.2 16 16 16 16-7.2 16-16S25.8 1 17 1z"
      );

      const path2 = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path2.setAttribute("fill", "#666666");
      path2.setAttribute(
        "d",
        "M22.4 17.5h-3.2v-6.8c0-.4-.3-.7-.7-.7h-3.2c-.4 0-.7.3-.7.7v6.8h-3.2c-.6 0-.8.4-.4.8l5 5.3c.5.7 1 .5 1.5 0l5-5.3c.7-.5.5-.8-.1-.8z"
      );

      download_icon.appendChild(path1);
      download_icon.appendChild(path2);

      let body = document.createElement("div");
      body.className = "bodyDocument";

      let icon = document.createElement("img");
      icon.className = "icon_document";
      icon.style.marginLeft = "18px";
      icon.style.marginTop = "10px";
      icon.style.float = "left";
      icon.style.width = "37px";
      icon.style.height = "36px";

      switch (json.media_mime_type) {
        case "application/pdf":
          icon.src = document.location.origin + "/assets/icons/pdf.svg";
          break;

        case "application/vnd.ms-excel":
        case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
          icon.src = document.location.origin + "/assets/icons/excel.svg";
          break;

        case "application/octet-stream":
          icon.src = document.location.origin + "/assets/icons/texto.svg";
          break;

        case "application/octet-stream":
          icon.src = document.location.origin + "/assets/icons/texto.svg";
          break;

        case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
          icon.src = document.location.origin + "/assets/icons/texto.svg";
          break;

        default:
          icon.src = document.location.origin + "/assets/icons/new-document.svg";
          break;
      }

      let title = document.createElement("span");
      title.textContent = Util.doTruncarStr(json.media_name, 20) == undefined ? GLOBAL_LANG.messenger_document_message : Util.doTruncarStr(json.media_name, 15);

      body.appendChild(icon);
      body.appendChild(title);
      body.appendChild(download_icon);

      messenger.Message.body.append(body);
      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

    },
    TemplateMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      const body = messenger.Message.body;

      const tail_out_message = document.createElement("div");
      const svg_element = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg_element.setAttribute("width", "29");
      svg_element.setAttribute("height", "19");
      svg_element.setAttribute("viewBox", "0 0 29 19");
      svg_element.setAttribute("fill", "none");

      const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path_element.setAttribute("d", "M12.8722 17.4704C13.6712 18.4164 15.1292 18.4164 15.9282 17.4704L27.904 3.29047C29.0021 1.99028 28.0779 0 26.376 0H2.42437C0.72252 0 -0.201688 1.99028 0.896405 3.29047L12.8722 17.4704Z");

      svg_element.appendChild(path_element);
      tail_out_message.appendChild(svg_element);

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "textMessage left";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 0) {
            tail_out_message.className = "tailOutMessageLeft";
            path_element.setAttribute("fill", "#ffffff");
            messenger.Message.message_item.prepend(tail_out_message);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "textMessage main-template right";
          messenger.Message.message_item.template.className = "templateMessage";
          messenger.Message.message_item.template.style.height = "auto";
          messenger.Message.message_item.buttons.className = "buttonMessage";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 1) {
            tail_out_message.className = "tailOutMessageRight";
            path_element.setAttribute("fill", "#2263d3");
            messenger.Message.message_item.prepend(tail_out_message);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      if (json.components && json.components != "null") {
        const components = (typeof json.components === 'string') ? JSON.parse(json.components) : json.components;

        for (let i = 0; i < components.length; i++) {
          if (components[i].type == "header") {
            messenger.Message.message_item.style.maxWidth = "391px";
            const header_type = components[i].parameters[0].type;

            switch (header_type) {

              case "image":
                const img = document.createElement("img");
                img.src = components[i].parameters[0].image.link;
                img.style.marginBottom = "10px";
                img.style.borderRadius = "4px";
                img.style.width = "100%";

                messenger.Message.message_item.firstElementChild.style.cursor = "pointer";
                messenger.Message.message_item.firstElementChild.dataset.url = components[i].parameters[0].image.link;

                body.prepend(img);
                break;
              case "document":
                const img_download = document.createElement("img");
                img_download.src = "assets/img/download.svg";
                img_download.style.width = "34px";
                img_download.style.height = "34px";
                img_download.style.float = "right";
                img_download.style.marginRight = "10px";
                img_download.style.marginTop = "12px";
                img_download.style.cursor = "pointer";

                const body_document = document.createElement("div");
                body_document.className = "bodyDocument";
                body_document.style.width = "100%";
                body_document.style.marginBottom = "10px";

                const icon = document.createElement("img");
                icon.className = "icon_document";
                icon.style.marginLeft = "18px";
                icon.style.marginTop = "12px";
                icon.style.float = "left";
                icon.style.width = "34px";
                icon.style.height = "34px";

                switch (json.media_mime_type) {
                  case "application/pdf":
                    icon.src = document.location.origin + "/assets/icons/pdf.svg";
                    break;

                  case "application/vnd.ms-excel":
                  case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                    icon.src = document.location.origin + "/assets/icons/excel.svg";
                    break;

                  case "application/octet-stream":
                    icon.src = document.location.origin + "/assets/icons/texto.svg";
                    break;

                  case "application/octet-stream":
                    icon.src = document.location.origin + "/assets/icons/texto.svg";
                    break;

                  case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
                    icon.src = document.location.origin + "/assets/icons/texto.svg";
                    break;

                  default:
                    icon.src = document.location.origin + "/assets/icons/new-document.svg";
                    break;
                }

                const title = document.createElement("span");
                title.textContent = Util.doTruncarStr(json.media_name, 20) == undefined ? GLOBAL_LANG.messenger_document_message : Util.doTruncarStr(json.media_name, 15);

                messenger.Message.message_item.firstElementChild.dataset.url = components[i].parameters[0].document.link;

                body_document.appendChild(icon);
                body_document.appendChild(title);
                body_document.appendChild(img_download);

                body.append(body_document);
                break;
              default:
                break;
            }
          }

          if (components[i].type == "body") {
            const body_parameters = components[i].parameters;
            let data = json.data;

            for (let i = 0; i < body_parameters.length; i++) {
              data = data.replaceAll(`{{${i + 1}}}`, body_parameters[i].text);
            }
            json.data = data;
          }
        }
      }

      if (json.header && json.components) {

        const components = JSON.parse(json.components);
        const headerComponent = components.find(c => c.type === 'header');
        const params = headerComponent?.parameters || [];

        let i = 0;
        const headerRendered = json.header.replace(/{{\s*\d+\s*}}/g, () => {
          return params[i++]?.text ?? '';
        });

        createElementTemplateHeader(headerRendered);
      }

      if (json.header_text != null) {

        if (json.header_text.includes('{{') && json.components) {
          const components = json.components;

          let i = 0;
          const headerRendered = json.header_text.replace(/{{\s*\d+\s*}}/g, () => {
            return components[i++]?.parameters?.[0]?.text ?? '';
          });

          createElementTemplateHeader(headerRendered);
        } else {
          createElementTemplateHeader(json.header_text);

        }

      }

      if (json.footer) {
        const footer = document.createElement("span");
        footer.innerHTML = json.footer;
        footer.className = "templateFooter";

        messenger.Message.message_item.template.insertBefore(footer, messenger.Message.body.nextSibling);
      }

      const div_span = document.createElement("div");
      div_span.className = "templateBody";
      const span = document.createElement("span");
      span.innerHTML = Util.nl2br(json.data, true);
      span.style.whiteSpace = 'break-spaces';

      div_span.append(span);
      body.append(div_span);

      let button_component = "";
      let components = "";

      if (json.components) {
        components = typeof (json.components) == "string" ? JSON.parse(json.components) : json.components;
        components = typeof (components) == "string" ? JSON.parse(components) : components;
        button_component = components != "null" ? components.filter(elm => elm.type == "button") : "";
      }

      if (json.buttons) {

        const content_button = JSON.parse(json.buttons);

        for (let i = 0; i < content_button.length; i++) {

          const button = document.createElement("button");
          button.className = "btnTemplate";
          button.style.fontSize = localStorage.getItem("fontBtnTemplateMessage");

          if (content_button[i].type == "QUICK_REPLY") {
            const svg = `<svg viewBox="0 0 28 28" height="12.8" width="12.8" preserveAspectRatio="xMidYMid meet" style="vertical-align: middle; margin: 0 3px 0 0;">
            <path fill="#ffffff" d="M13,15 C12.346,15 9.98,15.02 9.98,15.02 L9.98,20.39 L2.323,12 L9.98,3.6 L9.98,9.01 C9.98,9.01 12.48,8.98 13,9 C20.062,9.22 24.966,17.26 24.998,21.02 C22.84,18.25 17.17,15 13,15 Z M11.983,7.01 L11.983,1.11 C12.017,0.81 11.936,0.51 11.708,0.28 C11.312,-0.11 10.67,-0.11 10.274,0.28 L0.285,11.24 C0.074,11.45 -0.016,11.72 0,12 C-0.016,12.27 0.074,12.55 0.285,12.76 L10.219,23.65 C10.403,23.88 10.67,24.03 10.981,24.03 C11.265,24.03 11.518,23.91 11.7,23.72 C11.702,23.72 11.706,23.72 11.708,23.71 C11.936,23.49 12.017,23.18 11.983,22.89 C11.983,22.89 12,17.34 12,17 C18.6,17 24.569,21.75 25.754,28.01 C26.552,26.17 27,24.15 27,22.02 C27,13.73 20.276,7.01 11.983,7.01 Z"/>
            </svg>`;

            button.innerHTML = `${svg} ${content_button[i].text}`;
          }

          else if (content_button[i].type == "PHONE_NUMBER") {
            const svg = `<svg viewBox="0 -2 18 22" height="18" width="18" preserveAspectRatio="xMidYMid meet" style="vertical-align: middle; margin: 0 3px 0 0;">
            <path d="M15.0075,11.535 C14.085,11.535 13.1925,11.385 12.36,11.115 C12.0975,11.025 11.805,11.0925 11.6025,11.295 L10.425,12.7725 C8.3025,11.76 6.315,9.8475 5.2575,7.65 L6.72,6.405 C6.9225,6.195 6.9825,5.9025 6.9,5.64 C6.6225,4.8075 6.48,3.915 6.48,2.9925 C6.48,2.5875 6.1425,2.25 5.7375,2.25 
            L3.1425,2.25 C2.7375,2.25 2.25,2.43 2.25,2.9925 C2.25,9.96 8.0475,15.75 15.0075,15.75 C15.54,15.75 15.75,15.2775 15.75,14.865 L15.75,12.2775 C15.75,11.8725 15.4125,11.535 15.0075,11.535 Z" 
            fill="#ffffff" fill-rule="nonzero"></path></svg>`;

            button.innerHTML = `${svg} ${content_button[i].text}`;
          }

          else if (content_button[i].type == "URL") {
            const svg = `<svg viewBox="0 -2 18 22" height="18" width="18" preserveAspectRatio="xMidYMid meet" style="vertical-align: middle; margin: 0 3px 0 0;">
            <path d="M14,5.41421356 L9.70710678,9.70710678 C9.31658249,10.0976311 8.68341751,10.0976311 8.29289322,9.70710678 C7.90236893,9.31658249 7.90236893,8.68341751 8.29289322,8.29289322 L12.5857864,4 L10,4 C9.44771525,4 9,3.55228475 9,3 C9,2.44771525 9.44771525,2 10,2 L14,2 C15.1045695,2 16,2.8954305 16,4 L16,8 C16,8.55228475 15.5522847,9 15,9 C14.4477153,9 14,8.55228475 
            14,8 L14,5.41421356 Z M14,12 C14,11.4477153 14.4477153,11 15,11 C15.5522847,11 16,11.4477153 16,12 L16,13 C16,14.6568542 14.6568542,16 13,16 L5,16 C3.34314575,16 2,14.6568542 2,13 L2,5 C2,3.34314575 3.34314575,2 5,2 L6,2 C6.55228475,2 7,2.44771525 7,3 C7,3.55228475 6.55228475,4 6,4 L5,4 C4.44771525,4 4,4.44771525 4,5 L4,13 C4,13.5522847 4.44771525,14 5,14 L13,14 C13.5522847,14 14,13.5522847 14,13 L14,12 Z" 
            fill="#ffffff" fill-rule="nonzero"></path></svg>`;

            button.innerHTML = `${svg} ${content_button[i].text}`;

            if (content_button[i].url.includes("{{1}}")) {

              button.dataset.url = content_button[i].url.replace("{{1}}", button_component[0]?.parameters[0].text);
              button_component.shift();
            } else button.dataset.url = content_button[i].url;
          } else {
            button.innerHTML = `${content_button[i].text}`;
          }

          messenger.Message.message_item.buttons.appendChild(button);
        }
      }

      messenger.Message.appendItem(reverse);
      messenger.Message.verifyReaction(json);
      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);

      info = messenger.ChatList.findByKeyRemoteId(json.key_remote_id);

      $("#" + info.chat).scrollTop($("#" + info.chat).prop("scrollHeight") + 999);

    },
    InteractiveMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      const data = JSON.parse(json.data);
      const type = data.interactive.type;

      const tail_out_message = document.createElement("div");
      const svg_element = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg_element.setAttribute("width", "29");
      svg_element.setAttribute("height", "19");
      svg_element.setAttribute("viewBox", "0 0 29 19");
      svg_element.setAttribute("fill", "none");

      const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path_element.setAttribute("d", "M12.8722 17.4704C13.6712 18.4164 15.1292 18.4164 15.9282 17.4704L27.904 3.29047C29.0021 1.99028 28.0779 0 26.376 0H2.42437C0.72252 0 -0.201688 1.99028 0.896405 3.29047L12.8722 17.4704Z");

      svg_element.appendChild(path_element);
      tail_out_message.appendChild(svg_element);

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "textMessage left";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 0) {
            tail_out_message.className = "tailOutMessageLeft";
            path_element.setAttribute("fill", "#ffffff");
            messenger.Message.message_item.prepend(tail_out_message);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "textMessage main-interactive right";
          messenger.Message.message_item.interactive.className = "interactiveMessage";
          messenger.Message.message_item.interactive.style.height = "auto";
          messenger.Message.message_item.buttons.className = "buttonMessage";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontMessenger");
          if (tailOut == 1) {
            tail_out_message.className = "tailOutMessageRight";
            path_element.setAttribute("fill", "#2263d3");
            messenger.Message.message_item.prepend(tail_out_message);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      if (data.interactive.header) {

        const container_header = document.createElement("div");
        container_header.className = "interactiveHeader";

        if (data.interactive.header.type == "text") {
          container_header.style.fontSize = localStorage.getItem("fontInteractiveHeader");

          const header_text = document.createElement("span");
          header_text.innerHTML = Util.nl2br(data.interactive.header.text, true);

          container_header.append(header_text);
        }

        if (data.interactive.header.type == "document") {
          const media_url = data.interactive.header.document.link;

          const pdfjsLib = window['pdfjs-dist/build/pdf'];
          pdfjsLib.GlobalWorkerOptions.workerSrc = document.location.origin + "/assets/lib/build_pdf/pdf_build_pdf.worker.js";

          const loadingTask = pdfjsLib.getDocument(media_url);
          loadingTask.promise.then(function (pdf) {

            const pageNumber = 1;
            pdf.getPage(pageNumber).then(function (page) {

              let desiredWidth = 381;
              let desiredHeight = 150;
              let viewport = page.getViewport({ scale: 1 });

              let scale = desiredWidth / viewport.width;
              let scaledViewport = page.getViewport({ scale: scale });

              let canvas = document.createElement("canvas");
              canvas.id = "pdf-canvas";

              let context = canvas.getContext('2d');

              canvas.width = desiredWidth;
              canvas.height = desiredHeight;

              const renderContext = {
                canvasContext: context,
                viewport: scaledViewport
              };

              const renderTask = page.render(renderContext);
              renderTask.promise.then(function () {
                const thumbImage = document.querySelector("#thumb_image");
                if (thumbImage) {
                  thumbImage.remove();
                }

                let tempCanvas = document.createElement("canvas");
                tempCanvas.width = desiredWidth;
                tempCanvas.height = desiredHeight;

                let tempContext = tempCanvas.getContext("2d");
                tempContext.drawImage(canvas, 0, 0, desiredWidth, desiredHeight);

                let pdf_link = document.createElement("a");
                pdf_link.href = media_url;
                pdf_link.setAttribute("target", "_blank");
                pdf_link.appendChild(tempCanvas);

                container_header.appendChild(pdf_link);
              });
            });
          }, function (reason) {
            console.error("Erro ao carregar o documento PDF:", reason);
          });
        }

        if (data.interactive.header.type === "image") {
          const media_url = data.interactive.header.image.link;

          const img = document.createElement("img");
          img.src = media_url;
          img.style.maxWidth = "100%";
          img.style.height = "auto";
          img.style.borderRadius = "4px";

          container_header.appendChild(img);
        }

        messenger.Message.body.append(container_header);
      }

      const div_span = document.createElement("div");
      div_span.className = "interactiveBody";
      const span = document.createElement("span");
      span.innerHTML = Util.nl2br(data.interactive.body.text, true);
      span.style.whiteSpace = 'break-spaces';

      div_span.append(span);
      messenger.Message.body.append(div_span);

      if (data.interactive.footer) {
        const footer = document.createElement("span");
        footer.innerHTML = Util.nl2br(data.interactive.footer.text, true);
        footer.className = "interactiveFooter";
        footer.style.fontSize = localStorage.getItem("fontInteractiveFooter");

        messenger.Message.message_item.interactive.insertBefore(footer, messenger.Message.body.nextSibling);
      }

      let button_inter = "";
      let icon_element = "";

      if (type === "flow") {
        button_inter = data.interactive.action.parameters.flow_cta;

        button_element = document.createElement("button");
        button_element.style.fontSize = localStorage.getItem("fontBtnTemplateMessage");
        button_element.innerHTML = `${Util.nl2br(button_inter, true)}`;
        button_element.className = 'btnInteractive';

        messenger.Message.message_item.buttons.append(button_element);

      } else if (type === "location_request_message") {
        button_inter = GLOBAL_LANG.messenger_interactive_location_request_message;
        icon_element = `<i class="fa fa-map-marker" aria-hidden="true" style="margin-right: 7px; margin-top: 4px; font-size: smaller;"></i>`;

        button_element = document.createElement("button");
        button_element.style.fontSize = localStorage.getItem("fontBtnTemplateMessage");
        button_element.innerHTML = `${icon_element} ${Util.nl2br(button_inter, true)}`;
        button_element.className = 'btnInteractive';

        messenger.Message.message_item.buttons.append(button_element);

      } else if (type === "list") {
        button_inter = data.interactive.action.button;
        icon_element = `<i class="fa fa-list" aria-hidden="true" style="margin-right: 7px; margin-top: 4px; font-size: smaller;"></i>`;

        button_element = document.createElement("button");
        button_element.style.fontSize = localStorage.getItem("fontBtnTemplateMessage");
        button_element.innerHTML = `${icon_element} ${Util.nl2br(button_inter, true)}`;
        button_element.className = 'btnInteractive';

        messenger.Message.message_item.buttons.append(button_element);

      } else if (type === 'button') {
        let buttons = data.interactive.action.buttons;

        buttons.forEach(function (button) {
          button_inter = button.reply.title;
          button_element = document.createElement("button");
          button_element.style.fontSize = localStorage.getItem("fontBtnTemplateMessage");

          icon_element = `<svg viewBox="0 0 28 28" height="12.8" width="12.8" preserveAspectRatio="xMidYMid meet" style="vertical-align: middle; margin: 0 3px 0 0;">
                  <path fill="#ffffff" d="M13,15 C12.346,15 9.98,15.02 9.98,15.02 L9.98,20.39 L2.323,12 L9.98,3.6 L9.98,9.01 C9.98,9.01 12.48,8.98 13,9 C20.062,9.22 24.966,17.26 24.998,21.02 C22.84,18.25 17.17,15 13,15 Z M11.983,7.01 L11.983,1.11 C12.017,0.81 11.936,0.51 11.708,0.28 C11.312,-0.11 10.67,-0.11 10.274,0.28 L0.285,11.24 C0.074,11.45 -0.016,11.72 0,12 C-0.016,12.27 0.074,12.55 0.285,12.76 L10.219,23.65 C10.403,23.88 10.67,24.03 10.981,24.03 C11.265,24.03 11.518,23.91 11.7,23.72 C11.702,23.72 11.706,23.72 11.708,23.71 C11.936,23.49 12.017,23.18 11.983,22.89 C11.983,22.89 12,17.34 12,17 C18.6,17 24.569,21.75 25.754,28.01 C26.552,26.17 27,24.15 27,22.02 C27,13.73 20.276,7.01 11.983,7.01 Z"/>
                  </svg>`;

          button_element.innerHTML = `${icon_element} ${Util.nl2br(button_inter, true)}`;
          button_element.className = 'btnInteractive';

          messenger.Message.message_item.buttons.append(button_element);
        });
      } else if (type === 'cta_url') {
        button_inter = data.interactive.action.parameters.display_text;
        icon_element = `<i class="fa fa-external-link" aria-hidden="true" style="margin-right: 7px; margin-top: 4px; font-size: smaller;"></i>`;

        button_element = document.createElement("button");
        button_element.style.fontSize = localStorage.getItem("fontBtnTemplateMessage");
        button_element.innerHTML = `${icon_element} ${Util.nl2br(button_inter, true)}`;
        button_element.className = 'btnInteractive';

        messenger.Message.message_item.buttons.append(button_element);
      }

      messenger.Message.appendItem(reverse);

      messenger.Message.starred(json);
      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);

      messenger.Message.verifyReaction(json);
    },
    LocationMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      const data = JSON.parse(json.data);

      const tail_out_message = document.createElement("div");
      const svg_element = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg_element.setAttribute("width", "29");
      svg_element.setAttribute("height", "19");
      svg_element.setAttribute("viewBox", "0 0 29 19");
      svg_element.setAttribute("fill", "none");

      const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path_element.setAttribute("d", "M12.8722 17.4704C13.6712 18.4164 15.1292 18.4164 15.9282 17.4704L27.904 3.29047C29.0021 1.99028 28.0779 0 26.376 0H2.42437C0.72252 0 -0.201688 1.99028 0.896405 3.29047L12.8722 17.4704Z");

      svg_element.appendChild(path_element);
      tail_out_message.appendChild(svg_element);

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "LocationMessage left";
          if (tailOut == 0) {
            tail_out_message.className = "tailOutMessageLeft";
            path_element.setAttribute("fill", "#ffffff");
            messenger.Message.message_item.prepend(tail_out_message);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "LocationMessage right";
          if (tailOut == 1) {
            tail_out_message.className = "tailOutMessageRight";
            path_element.setAttribute("fill", "#2263d3");
            messenger.Message.message_item.prepend(tail_out_message);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      let thumbnail = document.createElement("div");
      thumbnail.className = "thumbnail";

      let a = document.createElement("a");
      a.href = "http://maps.google.com/maps?q=" + data.latitude + "," + data.longitude + "&hl=pt-BR";
      a.target = "_blank";

      let img = document.createElement("img");
      // img.src = 'http://maps.googleapis.com/maps/api/staticmap?center=' + data.latitude + '%2c%' + data.longitude + '&zoom=12&size=400x400&key=YOUR_API_KEY';
      img.src = "./assets/img/localizacao.jpg";
      img.style.width = "100%";

      let title = document.createElement("span");
      title.textContent = json.title;

      a.appendChild(img);
      thumbnail.appendChild(a);
      messenger.Message.body.appendChild(title);
      messenger.Message.body.appendChild(thumbnail);

      if (data.name) {
        const name = document.createElement("span");
        name.innerHTML = Util.nl2br(data.name, true);
        name.className = "locationName";
        name.style.fontSize = localStorage.getItem("fontMessenger");

        messenger.Message.body.appendChild(name);
      }

      if (data.address) {
        const address = document.createElement("span");
        address.innerHTML = Util.nl2br(data.address, true);
        address.className = "locationAddress";
        address.style.fontSize = localStorage.getItem("fontInteractiveFooter");

        messenger.Message.body.appendChild(address);
      }

      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

    },
    ZipMessage(json, reverse) {

      if (document.getElementById(json.token) != null) { return; }

      messenger.Message.init(json, reverse);
      var tailOutMessage = document.createElement("span");

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "ZipMessage left";
          if (tailOut == 0) {
            tailOutMessage.className = "tailOutMessageLeft";
            messenger.Message.message_item.prepend(tailOutMessage);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "ZipMessage right";
          if (tailOut == 1) {
            tailOutMessage.className = "tailOutMessageRight";
            messenger.Message.message_item.prepend(tailOutMessage);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }
      messenger.Message.message_item.dataset.url = json.media_url;

      let img_download = document.createElement("img");
      img_download.src = "assets/img/download.svg";
      img_download.style.width = "32px";
      img_download.style.height = "32px";
      img_download.style.marginRight = "10px";
      img_download.style.marginTop = "6px";
      img_download.style.cursor = "pointer";

      let icon;
      let title = document.createElement("span");

      title.style.position = "absolute";
      title.style.marginTop = "13px";

      switch (json.media_mime_type) {
        // case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
        // case "application/vnd.ms-excel":
        case "":
          icon = document.createElement("img");
          icon.className = "icon_document";
          icon.src = "assets/icons/excel.svg";
          icon.style.marginLeft = "18px";
          icon.style.marginTop = "1px";
          icon.style.float = "left";
          icon.style.width = "37px";
          icon.style.height = "36px";
          messenger.Message.body.append(icon);
          break;

        // case "application/octet-stream":
        case "":
          icon = document.createElement("img");
          icon.className = "icon_document";
          icon.src = "assets/icons/texto.svg";
          icon.style.marginLeft = "18px";
          icon.style.marginTop = "1px";
          icon.style.float = "left";
          icon.style.width = "37px";
          icon.style.height = "36px";
          messenger.Message.body.append(icon);
          break;

        // case "text/plain":
        case "":
          icon = document.createElement("img");
          icon.className = "icon_document";
          icon.src = "assets/icons/txt.svg";
          icon.style.marginLeft = "15px";
          icon.style.marginTop = "1px";
          icon.style.float = "left";
          icon.style.width = "48px";
          icon.style.height = "48px";
          title.style.marginLeft = "2px";
          title.style.marginTop = "20px";
          title.style.positon = "absolute";
          messenger.Message.body.append(icon);
          break;

        // case "load":
        case "":
          icon = document.createElement("img");
          icon.className = "icon_document";
          icon.src = "assets/img/loads/loading_1.gif";
          icon.className = "icon_document";
          icon.style.marginLeft = "18px";
          icon.style.marginTop = "1px";
          icon.style.float = "left";
          icon.style.width = "37px";
          icon.style.height = "36px";
          messenger.Message.body.append(icon);
          break;

        default:
          icon = document.createElement("img");
          icon.className = "icon_document";
          icon.src = "assets/icons/new-document.svg";
          icon.style.marginLeft = "18px";
          icon.style.marginTop = "1px";
          icon.style.float = "left";
          icon.style.width = "37px";
          icon.style.height = "36px";
          messenger.Message.body.append(icon);
          break;

      }

      if (json.media_title == null || json.media_title == 0 || json.media_title == undefined) {
        title.textContent = json.file_name == 0 ? Util.doTruncarStr(json.title, 15) : Util.doTruncarStr(json.file_name, 15);
      } else {
        title.textContent = Util.doTruncarStr(json.media_title, 15);
      }

      messenger.Message.body.append(title);
      messenger.Message.body.append(img_download);

      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

    },
    LiveLocationMessage(json) {

    },
    ContactMessage(json, reverse) {

      if (document.getElementById(json.token) != null) { return; }

      messenger.Message.init(json, reverse);

      const tail_out_message = document.createElement("div");
      const svg_element = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg_element.setAttribute("width", "29");
      svg_element.setAttribute("height", "19");
      svg_element.setAttribute("viewBox", "0 0 29 19");
      svg_element.setAttribute("fill", "none");

      const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path_element.setAttribute("d", "M12.8722 17.4704C13.6712 18.4164 15.1292 18.4164 15.9282 17.4704L27.904 3.29047C29.0021 1.99028 28.0779 0 26.376 0H2.42437C0.72252 0 -0.201688 1.99028 0.896405 3.29047L12.8722 17.4704Z");

      svg_element.appendChild(path_element);
      tail_out_message.appendChild(svg_element);

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "ContactMessage left";
          if (tailOut == 0) {
            tail_out_message.className = "tailOutMessageLeft";
            path_element.setAttribute("fill", "#ffffff");
            messenger.Message.message_item.prepend(tail_out_message);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "ContactMessage right";
          if (tailOut == 1) {
            tail_out_message.className = "tailOutMessageRight";
            path_element.setAttribute("fill", "#2263d3");
            messenger.Message.message_item.prepend(tail_out_message);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      var vcard = json.data;
      let jsonContact = JSON.parse(vcard);

      let img = document.createElement("img");
      img.src = "assets/img/avatar.svg";

      let caption = document.createElement("span");
      caption.textContent = Util.doTruncarStr(jsonContact.firstName, 15);

      caption.id = "name_" + json.token;
      caption.className = "contact-name";
      caption.style.cursor = "pointer";
      let button = document.createElement("div");
      button.className = "button";

      if (vcard.split("waid")[1] !== undefined) {

        let separateNumber = vcard.split("waid")[1];
        let numberDD = separateNumber.split(":")[0];
        var number_whatsapp = numberDD.split("=55")[1];

      } else {
        let regExp = /[a-zA-Z]/g;
        let str = jsonContact.cellPhone;

        if (regExp.test(str)) {
          var number_residencial = jsonContact.cellPhone;
        } else {
          var number_residencial = jsonContact.cellPhone.substring(2);
        }
      }

      let input = document.createElement("input");
      input.type = "button";
      input.className = "contact-add-chat";
      input.id = "view_" + json.creation;
      input.name = number_whatsapp == undefined ? number_residencial : number_whatsapp;
      input.value = "Enviar mensagem";
      input.style.width = "100%";
      input.style.padding = "15px";
      input.style.fontSize = "16px";
      input.style.color = "#00a3cc";
      input.style.display = "none";

      button.appendChild(input);

      messenger.Message.body.appendChild(img);
      messenger.Message.body.appendChild(caption);
      messenger.Message.body.appendChild(button);

      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

    },
    RevokeMessage(json, reverse) {

      if (document.getElementById(json.token) != null) { return; }

      messenger.Message.init(json, reverse);

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "revoke Left";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontRevokeMessage");
          messenger.Message.body.innerHTML = `<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 17 19'>
                                               <path fill='currentColor' d='M12.629 12.463a5.17 5.17 0 0 0-7.208-7.209l7.208 7.209zm-1.23 1.229L4.191 6.484a5.17 5.17 0 0 0 7.208 7.208zM8.41 2.564a6.91 6.91 0 1 1 0 13.82 6.91 6.91 0 0 1 0-13.82z'></path>
                                              </svg>
                                              <span>${GLOBAL_LANG.messenger_message_deleted}</span>`;
          break;
        case 2:
          messenger.Message.message_item.className = "revoke Right";
          messenger.Message.message_item.style.fontSize = localStorage.getItem("fontRevokeMessage");
          messenger.Message.body.innerHTML = `<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 17 19'>
                                                <path fill='currentColor' d='M12.629 12.463a5.17 5.17 0 0 0-7.208-7.209l7.208 7.209zm-1.23 1.229L4.191 6.484a5.17 5.17 0 0 0 7.208 7.208zM8.41 2.564a6.91 6.91 0 1 1 0 13.82 6.91 6.91 0 0 1 0-13.82z'></path>
                                              </svg>
                                              <span>${GLOBAL_LANG.messenger_deleted_message}</span>`;
          break;
      }

      messenger.Message.appendItem(reverse);
    },
    InformationMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      messenger.Message.message_item.className = "information";
      messenger.Message.message_item.style.cssFloat = 'left';
      messenger.Message.message_item.style.background = 'transparent';
      messenger.Message.message_item.style.color = 'black';

      let span = document.createElement("span");
      span.innerHTML = json.data;

      messenger.Message.body.append(span);
      messenger.Message.appendItem(reverse);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);

    },
    StartMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      messenger.Message.message_item.className = "information";
      messenger.Message.message_item.style.cssFloat = 'left';
      messenger.Message.message_item.style.background = 'transparent';
      messenger.Message.message_item.style.color = 'black';

      let span = document.createElement("span");
      span.innerHTML = GLOBAL_LANG.messenger_auto_system_messages_started_attendance;

      messenger.Message.body.append(span);
      messenger.Message.appendItem(reverse);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);

    },
    CloseMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      messenger.Message.message_item.className = "information";
      messenger.Message.message_item.style.cssFloat = 'left';
      messenger.Message.message_item.style.background = 'transparent';
      messenger.Message.message_item.style.color = 'black';

      let span = document.createElement("span");
      span.innerHTML = GLOBAL_LANG.messenger_auto_system_messages_ended_attendance;

      messenger.Message.body.append(span);
      messenger.Message.appendItem(reverse);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);

    },
    ProtocolMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      messenger.Message.message_item.className = "information";
      messenger.Message.message_item.style.cssFloat = 'left';
      messenger.Message.message_item.style.background = 'transparent';
      messenger.Message.message_item.style.color = 'black';

      let span = document.createElement("span");
      span.innerHTML = GLOBAL_LANG.messenger_auto_system_messages_protocol.replace("{{protocol}}", json.data);

      messenger.Message.body.append(span);
      messenger.Message.appendItem(reverse);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);

    },
    WaitMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      messenger.Message.message_item.className = "information";
      messenger.Message.message_item.style.cssFloat = 'left';
      messenger.Message.message_item.style.background = 'transparent';
      messenger.Message.message_item.style.color = 'black';

      let span = document.createElement("span");
      span.innerHTML = GLOBAL_LANG.messenger_auto_system_messages_put_on_hold;

      messenger.Message.body.append(span);
      messenger.Message.appendItem(reverse);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);

    },
    AttendanceMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json);

      messenger.Message.message_item.className = "information";
      messenger.Message.message_item.style.cssFloat = 'left';
      messenger.Message.message_item.style.background = 'transparent';
      messenger.Message.message_item.style.color = 'black';

      let span = document.createElement("span");
      span.innerHTML = GLOBAL_LANG.messenger_auto_system_messages_placed_in_service;

      messenger.Message.body.append(span);
      messenger.Message.appendItem(reverse);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);

    },
    TransMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);

      messenger.Message.message_item.className = "information";
      messenger.Message.message_item.style.cssFloat = 'left';
      messenger.Message.message_item.style.background = 'transparent';
      messenger.Message.message_item.style.color = 'black';

      let span = document.createElement("span");
      span.innerHTML = GLOBAL_LANG.messenger_auto_system_messages_transferred_attendance.replace("{{hours}}", Util.FormatShortTime(json.creation));

      messenger.Message.body.append(span);
      messenger.Message.appendItem(reverse);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
    },
    PagamentMessage(json) {

    },
    CardListMessage(json, reverse) {

      if (document.getElementById(json.token) != null) return;

      messenger.Message.init(json, reverse);
      var tailOutMessage = document.createElement("span");

      switch (parseInt(json.key_from_me)) {
        case 1:
          messenger.Message.message_item.className = "CardListMessage left";
          if (tailOut == 0) {
            tailOutMessage.className = "tailOutMessageLeft";
            tailOutMessage.style.top = "0px";
            messenger.Message.message_item.prepend(tailOutMessage);
            tailOut = 1;
          }
          break;
        case 2:
          messenger.Message.message_item.className = "CardListMessage right";
          if (tailOut == 1) {
            tailOutMessage.className = "tailOutMessageRight";
            messenger.Message.message_item.prepend(tailOutMessage);
            verify_tailOut = true;
            tailOut = 0;
          }
          break;
      }

      let container_product = document.createElement("div");
      container_product.className = "container_product";

      let thumbnail = document.createElement("div");
      thumbnail.className = "container_img";
      thumbnail.style.height = "60px";
      thumbnail.style.width = "100px";

      let img = document.createElement("img");
      img.src = json.order[0].first_picture_product;
      img.style.width = "100%";

      let order_title = document.createElement("span");
      order_title.textContent = json.order[0].message;
      order_title.style.position = "absolute";
      order_title.style.marginTop = "6px";

      let container_card = document.createElement("div");
      container_card.className = "container_card";

      let icon_cart = document.createElement("i");
      icon_cart.className = "fas fa-shopping-cart icon_card font-card";

      let quantity_itens = document.createElement("span");
      quantity_itens.textContent = json.order[0].item_count + " itens";
      quantity_itens.className = "font-card";
      quantity_itens.style.position = "absolute";
      quantity_itens.style.marginTop = "30px";
      quantity_itens.style.marginLeft = "32px";

      let message_card = document.createElement("div");
      message_card.textContent = json.order[0].order_title == "0" ? "" : json.order[0].order_title;
      message_card.style.marginTop = json.order[0].order_title == "0" ? "0px" : "10px";
      message_card.style.fontSize = json.order[0].order_title == "0" ? "0px" : "15px";

      let button = document.createElement("div");
      button.className = "button";

      let input = document.createElement("input");
      input.id = json.order[0].id_messages_order;
      input.className = "openCardList";
      input.type = "button";
      input.value = "Ver carrinho";
      input.style.width = "100%";
      input.style.padding = "15px";
      input.style.fontSize = "16px";
      input.style.color = "#00a3cc";

      button.appendChild(input);

      thumbnail.appendChild(img)
      container_product.appendChild(thumbnail);
      container_card.appendChild(order_title);
      container_card.appendChild(icon_cart);
      container_card.appendChild(quantity_itens);
      container_product.appendChild(container_card);
      button.prepend(message_card);

      messenger.Message.body.appendChild(container_product);
      messenger.Message.body.appendChild(button);

      messenger.Message.appendItem(reverse);
      messenger.Message.starred(json);

      messenger.Message.setDate(json, reverse);
      messenger.Message.removeRepeatedDate(json, reverse);
      messenger.Message.verifyReaction(json);

    }
  },
  ChatList: {
    Add(json, first) {

      let bAdd = true;

      for (let i = 0; i < messenger.Chats.length; i++) {
        if (messenger.Chats[i].key_remote_id == json.key_remote_id) {
          bAdd = false;
          break;
        }
      }

      if (bAdd == true) {
        const id = Util.makeId();

        const card = document.createElement("div");
        card.id = id;
        card.classList.add("item");
        card.dataset.index = json.short_timestamp || Date.now();
        card.dataset.fixed_timestamp = json.fixed_timestamp ?? null;
        card.dataset.last_timestamp_client = json.last_timestamp_client ?? null;
        card.dataset.chat_open = true;

        const contact_container = document.createElement("div");
        contact_container.classList.add("contact-container");

        const image_container = document.createElement("div");
        image_container.classList.add("contact-image");

        const avatar = document.createElement("img");
        avatar.classList.add("avatar");
        avatar.src = "assets/img/avatar.svg";
        avatar.alt = "Avatar";

        const platform_icon_container = document.createElement("span");
        platform_icon_container.classList.add("platform-icon");

        const platform_icon = document.createElement("img");
        platform_icon.src = getPlatformIcon(json.type);
        platform_icon.alt = "Platform icon";

        const contact_info = document.createElement("div");
        contact_info.classList.add("body");

        const contact_header = document.createElement("div");
        contact_header.classList.add("contact-header");

        const contact_footer = document.createElement("div");
        contact_footer.classList.add("contact-footer");

        const contact_config = document.createElement("span");
        contact_config.classList.add("contact-config");

        const contact_actions = document.createElement("span");
        contact_actions.classList.add("contact-actions");

        const contact_config_actions = document.createElement("div");
        contact_config_actions.classList.add("contact-config-actions");

        const div_fixed_icon = document.createElement("div");
        div_fixed_icon.classList.add("fixed-icon");

        const div_icon = document.createElement("div");
        div_icon.classList.add("contact-actions-icon");

        const contact_name_label = document.createElement("div");
        contact_name_label.classList.add("contact-name-label");

        const contact_number = json.key_remote_id.split('-')[0];

        const contact_name = document.createElement("span");
        contact_name.classList.add("contact-name-span");
        contact_name.textContent = Util.doTruncarStr(json.full_name != "" && json.full_name != null && json.full_name != '0' ? json.full_name : contact_number, 20);

        const contact_time = document.createElement("span");
        contact_time.classList.add("contact-time");
        contact_time.textContent = Util.FormatShortTime(json.short_timestamp);

        const last_message = document.createElement("div");
        last_message.classList.add("last-message");
        last_message.innerHTML = messenger.ChatList.setLastMessage(json);

        const labels = document.createElement("div");
        labels.style.cssFloat = "left";
        labels.style.display = json.labels_color ? "block" : "none";
        labels.innerHTML = `<svg xmlns='http://www.w3.org/2000/svg' style='fill:${json.labels_color ? json.labels_color.split(",")[0] : "transparent"
          }' viewBox='0 0 18 12'><path d='M11.208,0.925H2.236C1.556,0.925,1,1.565,1,2.357V9.57C1,10.362,1.556,11,2.236,11h8.972 c0.414,0,0.785-0.237,1.007-0.604l2.701-4.433L12.215,1.53C11.993,1.162,11.622,0.925,11.208,0.925z'/></svg>`;

        const message_no_read = document.createElement("div");
        message_no_read.classList.add("no-read-message", "checkModal");
        message_no_read.id = `ball_${id}`;

        const span_message_no_read = document.createElement("label");
        span_message_no_read.textContent = json.message_no_read == "-1" ? " " : json.message_no_read;
        span_message_no_read.classList.add("numberLabel");

        if (parseInt(json.message_no_read) == 0) {
          message_no_read.style.display = "none";
        }

        message_no_read.appendChild(span_message_no_read);

        const openModal = `openM('${id}')`;

        const icone = document.createElement("i");
        icone.classList.add("fas", "fa-angle-down", "icone");
        icone.setAttribute("onclick", openModal);

        const fixed_icon = document.createElement("i");
        fixed_icon.classList.add("fas", "fa-thumbtack", "iconFixar", json.fixed_timestamp && "fixed");
        fixed_icon.style.display = json.fixed_timestamp ? "block" : "none";

        const modalUser = document.createElement("div");
        modalUser.id = `md_${id}`;
        modalUser.classList.add("md_geral");

        const openIcone = `openIcon('${id}')`;
        card.setAttribute("onmouseover", openIcone);

        platform_icon_container.appendChild(platform_icon);
        image_container.appendChild(avatar);
        image_container.appendChild(platform_icon_container);

        contact_name_label.appendChild(labels);
        contact_name_label.appendChild(contact_name);
        contact_header.appendChild(contact_name_label);
        contact_header.appendChild(contact_time);

        div_icon.appendChild(icone);

        div_fixed_icon.appendChild(fixed_icon);

        contact_config.appendChild(div_fixed_icon);
        contact_config.appendChild(message_no_read);
        contact_actions.appendChild(div_icon);
        contact_actions.appendChild(modalUser);

        contact_config_actions.appendChild(contact_config);
        contact_config_actions.appendChild(contact_actions);

        contact_footer.appendChild(last_message);
        contact_footer.appendChild(contact_config_actions);

        contact_info.appendChild(contact_header);
        contact_info.appendChild(contact_footer);

        contact_container.appendChild(image_container);
        contact_container.appendChild(contact_info);

        card.appendChild(contact_container);

        let bReplyMsg = false;
        let bDeleteMsg = false;

        switch (parseInt(json.type)) {
          case 1:
          case 2:
          case 10:
          case 12:
          case 16:
            bReplyMsg = true;
            bDeleteMsg = true;
            break;
          case 9:
          case 11:
            bReplyMsg = true;
            break;
          default:
            break;
        }

        const container_id = json.is_private === 2 ? "#list-internal" : json.is_wait === 1 ? "#list-active" : "#list-wait";
        const container = document.querySelector(container_id);
        first ? container.prepend(card) : container.append(card);

        if (parseInt(json.message_no_read) == 0) {
          const no_read_message = document.getElementById(json.key_remote_id)?.querySelector('.no-read-message');
          if (no_read_message) {
            no_read_message.style.display = "none";
          }
        }

        const chat_id = Util.makeId();

        const msg_box = document.createElement("div");
        msg_box.id = chat_id;
        msg_box.style.display = "none";
        msg_box.classList.add("messages");
        msg_box.dataset.changeBg = "";
        msg_box.style.backgroundImage = localStorage.getItem("colorWallpaper") || "";
        msg_box.style.backgroundColor = localStorage.getItem("colorWallpaper") || "";

        document.querySelector(".messenger .right .body .chat").prepend(msg_box);

        messenger.Chats.push({
          hash: id,
          chat: chat_id,
          channel_id: json.channel_id,
          create: json.messages.length > 0 ? json.messages[json.messages.length - 1].creation : 0,
          key_remote_id: json.key_remote_id,
          push_name: json.full_name && json.full_name != '0' ? json.full_name : contact_number,
          labels_name: json.labels_name,
          last_message: json.last_message,
          last_type_message: json.last_type_message,
          last_key_id: json.last_key_id,
          label_color: json.labels_color,
          data: json.data,
          last_timestamp_client: json.last_timestamp_client,
          t: json.t,
          type: json.type,
          is_private: json.is_private,
          is_group: json.is_group,
          reply: bReplyMsg,
          last_date: null,
          revoke: bDeleteMsg
        });

        json.is_wait === 1 && messenger.count.call++;
        json.is_private === 2 && messenger.count.private++;
        json.is_wait === 2 && messenger.count.wait++;
        json.type === 8 && messenger.count.comment++;
      }

      ta.contact.queryProfilePicture(json.key_remote_id);
      ta.chat.queryMessages(json.key_remote_id, 0, false);
      this.updateCountView();

      for (var i = 0; i < document.querySelector("#list-active").childNodes.length; i++) {
        document.querySelector("#list-active").childNodes[i].setAttribute("data-numberChat", (i + 1));
      }

      info = messenger.ChatList.findByKeyRemoteId(json.key_remote_id);

      if (messenger.Chat.selected == info.hash) {
        unblockChat();
      }

      messenger.ChatList.updateCountView();
      messenger.ChatList.setLastMessageByKeyRemoteId(json.key_remote_id, json.last_message, json.last_type_message);

      if (messenger.Chat.selected != json.key_remote_id && messenger.Chat.selected && info.reply == false) {
        postNotification(
          ta.contact.pushName(json.key_remote_id),
          document.getElementById(info.hash).querySelectorAll("img")[1].src,
          json.last_message,
          json.key_remote_id
        );
      }


    },
    setLastMessage(json) {
      if (localStorage.getItem(json.key_remote_id)) {
        return `${GLOBAL_LANG.messenger_list_chat_draft}: ${Util.doTruncarStr(localStorage.getItem(json.key_remote_id).replace(/\\n\\r/g, " "), 10)}`;
      }

      if (json.reaction) {
        return `${GLOBAL_LANG.messenger_list_chat_reaction} ${json.reaction}`;
      }

      switch (json.last_type_message) {
        case 1:
          if (json.last_message && (json.last_message.startsWith('{"screen') || json.last_message.startsWith('{"flow_token'))) {
            return "📃 flow";
          } else {
            return json.last_message ? `${Util.doTruncarStr(json.last_message.replace(/\\n\r/g, " "), 20)}` : '';
          }
        case 2:
          return `🎤 ${Util.FormatDuration(json.last_message)}`;
        case 3:
          return `📷 ${GLOBAL_LANG.messenger_media_types_photo}`;
        case 4:
          return `📃 ${GLOBAL_LANG.messenger_media_types_document}`;
        case 5:
          return `🎥 ${GLOBAL_LANG.messenger_media_types_video}`;
        case 6:
          return `👾 ${GLOBAL_LANG.messenger_media_types_gif}`;
        case 7:
          return `📍 ${GLOBAL_LANG.messenger_media_types_location}`;
        case 9:
          return `👤 ${GLOBAL_LANG.messenger_media_types_contact}`;
        case 10:
          return `🗄 ${GLOBAL_LANG.messenger_media_types_file}`;
        case 18:
          return `🚫 ${Util.doTruncarStr(GLOBAL_LANG.messenger_message_deleted, 20)}`;
        case 26:
          return `👾 ${GLOBAL_LANG.messenger_media_types_sticker}`;
        case 27:
          return `📃 ${GLOBAL_LANG.messenger_media_types_template}`;
        case 30:
        case 35:
          return `📃 ${GLOBAL_LANG.messenger_media_types_interactive_message}`;
        case 32:
          return `🎴 ${Util.doTruncarStr(GLOBAL_LANG.messenger_media_types_mention_reply, 20)}`;
        default:
          return "";
      }
    },
    Remove(key_remote_id) {
      for (let i = 0; i < messenger.Chats.length; i++) {
        if (messenger.Chats[i].key_remote_id == key_remote_id) {
          if (messenger.Chats[i].hash == messenger.Chat.selected) {
            messenger.Chat.close();
            messenger.Chat.selected = '';
          } else {
            let info = messenger.ChatList.findByKeyRemoteId(key_remote_id);
            if (info != null) {
              $(".option")[0].style.display = "none";
              if (messenger.Chat.key_remote_id == key_remote_id) $(".right").hide();
              $(".option").hide();
              $("#modal").hide();
              $("#sub-more").hide();
              $("#" + info.hash).remove();
              $("#" + info.chat).remove();
              messenger.ChatList.updateCountView();
            }
          }
          messenger.Chats.splice(i, 1);
          break;
        }
      }
    },
    Open(key_remote_id) {
      let info = messenger.ChatList.findByKeyRemoteId(key_remote_id);
      if (info != null) {
        $(".contact-add-chat").attr("value", "Enviar mensagem")
        setTimeout(() => {
          $("#" + info.hash).click();
        }, 250);
        // this.openCardList();
      }
    },
    find(hash) {
      let i = 0;
      let bFind = false;
      for (i = 0; i < messenger.Chats.length; i++) {
        if (messenger.Chats[i].hash == hash) {
          bFind = true;
          break;
        }
      }
      return bFind == false ? null : messenger.Chats[i];
    },
    findByKeyRemoteId(key_remote_id) {
      let i = 0;
      let bFind = false;
      for (i = 0; i < messenger.Chats.length; i++) {
        if (messenger.Chats[i].key_remote_id == key_remote_id) {
          bFind = true;
          break;
        }
      }
      return bFind == false ? null : messenger.Chats[i];
    },
    setLastMessageByKeyRemoteId(key_remote_id, data, media_type) {
      for (let i = 0; i < messenger.Chats.length; i++) {
        if (messenger.Chats[i].key_remote_id == key_remote_id) {
          messenger.Chats[i].last_message = data;
          messenger.Chats[i].last_type_message = media_type;
          break;
        }
      }
    },
    setLastDateByKeyRemoteId(key_remote_id, data) {
      let i = 0;
      for (i = 0; i < messenger.Chats.length; i++) {
        if (messenger.Chats[i].key_remote_id == key_remote_id) {
          messenger.Chats[i].last_date = data;
          break;
        }
      }
    },
    setLastTimestampClient(key_remote_id, timestmp) {
      let i = 0;
      for (i = 0; i < messenger.Chats.length; i++) {
        if (messenger.Chats[i].key_remote_id == key_remote_id) {
          messenger.Chats[i].last_timestamp_client = timestmp;
          break;
        }
      }
    },
    updateCountView() {

      $("#chat-active .title").html($("#list-active .item").length);
      $("#chat-internal .title").html($("#list-internal .item").length);
      $("#chat-wait .title").html($("#list-wait .item").length);

      let i = 0;
      let count = 0;
      let total = 0;
      for (i = 0; i < $("#list-active .item .no-read-message label").length; i++) {
        count += $("#list-active .item .no-read-message label")[i].innerHTML == " " ? 1 : parseInt($("#list-active .item .no-read-message label")[i].innerHTML);
      }
      if (count > 0) {
        $("#chat-active .no-read label").html(count);
        $("#chat-active .no-read").show();
        total = count;
      } else {
        $("#chat-active .no-read label").html("0");
        $("#chat-active .no-read").hide();
      }
      count = 0;
      for (i = 0; i < $("#list-internal .item .no-read-message label").length; i++) {
        count += $("#list-internal .item .no-read-message label")[i].innerHTML == " " ? 1 : parseInt($("#list-internal .item .no-read-message label")[i].innerHTML);
      }
      if (count > 0) {
        $("#chat-internal .no-read label").html(count);
        $("#chat-internal .no-read").show();
        total = total + count;
      } else {
        $("#chat-internal .no-read label").html("0");
        $("#chat-internal .no-read").hide();
      }
      count = 0;
      for (i = 0; i < $("#list-wait .item .no-read-message label").length; i++) {
        count += $("#list-wait .item .no-read-message label")[i].innerHTML == " " ? 1 : parseInt($("#list-wait .item .no-read-message label")[i].innerHTML);
      }
      if (count > 0) {
        $("#chat-wait .no-read label").html(count);
        $("#chat-wait .no-read").show();
        total = total + count;
      } else {
        $("#chat-wait .no-read label").html("0");
        $("#chat-wait .no-read").hide();
      }
      if (total > 0) {
        document.title = `(${total}) TalkAll | Messenger`;
      } else {
        document.title = "TalkAll | Messenger";
      }

      $('#list-active').find('.item').sort(function (a, b) {
        return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
      }).appendTo('#list-active');

      $('#list-internal').find('.item').sort(function (a, b) {
        return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
      }).appendTo('#list-internal');

      $('#list-wait').find('.item').sort(function (a, b) {
        return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
      }).appendTo('#list-wait');

    }
  }
};

var bNight = false;

function LoadMode() {

  if (bNight == true) {

    $("body").addClass("darkMessenger");
    $(".messenger").addClass("dark");
    $(".modal").addClass("dark");
    $("#night").css({ fill: "white" });
    $(".modalMessenger").addClass("dark");
    $(".selectModal").addClass("dark");

    $(".item").find(".left").css({ "background-color": "#141414" });
    $(".item").find(".right").css({ "background-color": "#1b277c" });
    $(".item").find(".right .btnTemplate").css({ "background-color": "#1b277c" });
    $(".item").find(".right .btnInteractive").css({ "background-color": "#1b277c" });
    $(".item").find(".tailOutMessageLeft svg path").css({ "fill": "#141414" });
    $(".item").find(".tailOutMessageRight svg path").css({ "fill": "#1b277c" });

    if (localStorage.getItem("mode_load") === null) {
      localStorage.setItem("mode_load", "dark");
    }

    if (localStorage.getItem("colorWallpaper") == "#F4F4F4") {
      const elements_to_change = document.querySelectorAll("[data-change-bg]");
      elements_to_change.forEach(el => {
        el.style.backgroundColor = "#1D1D1D";
      });
      localStorage.setItem("colorWallpaper", "#1D1D1D");
    } else {
      let color = localStorage.getItem("colorWallpaper");
      let index = bgColor.light.indexOf(color);

      if (index !== -1) {
        color = bgColor.dark[index];
      }

      const elements_to_change = document.querySelectorAll("[data-change-bg]");
      elements_to_change.forEach(el => {
        el.style.backgroundColor = color;
      });
      localStorage.setItem("colorWallpaper", color);
    }

    switchToDark("windowSearch");
    switchToDark("windowOption");
    switchToDark("windowFavorite");
  } else {

    $("body").removeClass("darkMessenger");
    $(".messenger").removeClass("dark");
    $(".modal").removeClass("dark");
    $("#night").css({ fill: "black" });
    $(".modalMessenger").removeClass("dark");
    $(".selectModal").removeClass("dark");

    $(".item").find(".left").css({ "background-color": "#ffffff" });
    $(".item").find(".right").css({ "background-color": "#2263d3" });
    $(".item").find(".right .btnTemplate").css({ "background-color": "#2263d3" });
    $(".item").find(".right .btnInteractive").css({ "background-color": "#2263d3" });
    $(".item").find(".tailOutMessageLeft svg path").css({ "fill": "#ffffff" });
    $(".item").find(".tailOutMessageRight svg path").css({ "fill": "#2263d3" });

    if (localStorage.getItem("mode_load") !== null) {
      localStorage.removeItem("mode_load");
    }

    if (localStorage.getItem("colorWallpaper") == "#1D1D1D") {
      const elements_to_change = document.querySelectorAll("[data-change-bg]");
      elements_to_change.forEach(el => {
        el.style.backgroundColor = "#F4F4F4";
      });
      localStorage.setItem("colorWallpaper", "#F4F4F4");
    } else {
      let color = localStorage.getItem("colorWallpaper");
      let index = bgColor.dark.indexOf(color);

      if (index !== -1) {
        color = bgColor.light[index];
      }

      const elements_to_change = document.querySelectorAll("[data-change-bg]");
      elements_to_change.forEach(el => {
        el.style.backgroundColor = color;
      });
      localStorage.setItem("colorWallpaper", color);
    }

    switchToDark("windowSearch");
    switchToDark("windowOption");
    switchToDark("windowFavorite");
  }
}

function processParamsContact(json) {

  const bgBoxMessenger = document.createElement("div");
  bgBoxMessenger.className = "bg-box-messenger def__closeModal";
  bgBoxMessenger.id = "bgBoxMessenger";

  const modal = document.createElement("div");
  modal.className = "def-modal";

  const header = document.createElement("div");
  header.className = "def-header";

  const title = document.createElement("div");
  title.className = "title";
  title.innerHTML = `<span>${GLOBAL_LANG.messenger_modal_new_contact_title}</span>`;

  const svg_close_icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
  svg_close_icon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
  svg_close_icon.setAttribute("width", "15");
  svg_close_icon.setAttribute("height", "15");
  svg_close_icon.setAttribute("viewBox", "0 0 22 22");
  svg_close_icon.setAttribute("class", "icon-close-right");

  const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
  path_element.setAttribute(
    "d",
    "M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z"
  );
  path_element.setAttribute("fill", "#666666");

  svg_close_icon.appendChild(path_element);

  const iconClose = document.createElement("div");
  iconClose.className = "close def__closeModal";
  iconClose.innerHTML = "";
  iconClose.appendChild(svg_close_icon);

  header.appendChild(title);
  header.appendChild(iconClose);

  const body = document.createElement("div");
  body.className = "def-body";

  const lbName = document.createElement("label");
  lbName.textContent = GLOBAL_LANG.messenger_modal_new_contact_name;
  lbName.className = "def-label";

  const inputName = document.createElement("input");
  inputName.type = "text";
  inputName.className = "contact name def-input";
  inputName.id = "contact_name";
  inputName.maxLength = 100;
  inputName.placeholder = GLOBAL_LANG.messenger_modal_new_contact_name_placeholder;

  const group = document.createElement("div");

  const dv_label_number = document.createElement("div");

  const lbNumber = document.createElement("label");
  lbNumber.textContent = GLOBAL_LANG.messenger_modal_new_contact_number;
  lbNumber.className = "def-label";

  dv_label_number.appendChild(lbNumber);

  const inputNumberDdi = document.createElement("input");
  inputNumberDdi.type = "tel";
  inputNumberDdi.className = "def-input input-ddi-number";
  inputNumberDdi.id = "input-ddi-number";
  inputNumberDdi.value = localStorage.getItem("lastSelectedFlag") == null ? "+55" : localStorage.getItem("lastSelectedFlag");
  inputNumberDdi.maxLength = 8;
  inputNumberDdi.addEventListener("input", removeDdiCharacters);
  inputNumberDdi.addEventListener("keyup", setDdiInputKeyup);

  const inputNumber = document.createElement("input");
  inputNumber.type = "tel";
  inputNumber.pattern = "\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}";
  inputNumber.className = "def-input input-number";
  inputNumber.id = "contact_number";
  inputNumber.placeholder = GLOBAL_LANG.messenger_modal_new_contact_number;
  inputNumber.addEventListener("focus", maskInputFocus);

  group.appendChild(dv_label_number);
  group.appendChild(inputNumberDdi);
  group.appendChild(inputNumber);

  const lbResponsible = document.createElement("label");
  lbResponsible.textContent = GLOBAL_LANG.messenger_modal_new_contact_responsible;
  lbResponsible.className = "def-label";

  const selectResponsible = document.createElement("select");
  selectResponsible.id = 'user_key_remote_id';
  selectResponsible.className = 'def-select';

  const lbChannel = document.createElement("label");
  lbChannel.textContent = GLOBAL_LANG.messenger_modal_new_contact_channel;
  lbChannel.className = "def-label";

  const selectChannel = document.createElement("select");
  selectChannel.id = "channel_id";
  selectChannel.className = "def-select";

  body.appendChild(lbName);
  body.appendChild(inputName);
  body.appendChild(group);
  body.appendChild(lbResponsible);
  body.appendChild(selectResponsible);
  body.appendChild(lbChannel);
  body.appendChild(selectChannel);

  const footer = document.createElement("div");
  footer.className = "def-footer";

  const btnSave = document.createElement("button");
  btnSave.className = "def-btn-save btn ok";
  btnSave.innerHTML = GLOBAL_LANG.messenger_modal_new_contact_btn_save;

  const btnCancel = document.createElement("button");
  btnCancel.id = "btn-trans-cancel";
  btnCancel.className = "def-btn-cancel def__closeModal btn cancel";
  btnCancel.innerHTML = GLOBAL_LANG.messenger_modal_new_contact_btn_cancel;

  footer.appendChild(btnCancel);
  footer.appendChild(btnSave);

  modal.appendChild(header);
  modal.appendChild(body);
  modal.appendChild(footer);

  $("#bgBoxMessenger").remove();
  $(".def-modal").remove();

  document.querySelector("html body").appendChild(modal);
  document.querySelector("html body").appendChild(bgBoxMessenger);

  let optionNenhum = document.createElement('option');
  optionNenhum.text = GLOBAL_LANG.messenger_modal_new_contact_responsible_placeholder;
  optionNenhum.value = '0';

  $(selectResponsible).append(optionNenhum);

  json.users.forEach(x => {
    var option = document.createElement('option');
    option.text = x.last_name;
    option.value = x.key_remote_id;
    $(selectResponsible).append(option);
  });

  json.channels.forEach(x => {
    var option = document.createElement('option');
    option.text = x.name;
    option.value = x.id;
    $(selectChannel).append(option);
  });

  $(".name").focus();
  setTimeout(() => { $(".iti__country-list li").on("click", () => clearInputContact()); }, 1000);

  contactCountry();

  document.getElementById("contact-add").disabled = false;
}


function contactCountry() {

  var phoneInputField = document.querySelector(".input-ddi-number");
  window.intlTelInput(phoneInputField, {
    utilsScript: document.location.origin + "/assets/dist/intl-tell-input/js/utils.js",
    separateDialCode: false,
    nationalMode: true,
    initialCountry: "auto",
    geoIpLookup: callback => {
      fetch("https://ipapi.co/json")
        .then(res => res.json())
        .then(data => callback(data.country_code))
        .catch(() => callback("br"));
    },
    utilsScript: "/intl-tel-input/js/utils.js?1690975972744"
  });

}


function processLabels(json) {

  const bgBoxMessenger = document.createElement("div");
  bgBoxMessenger.className = "bg-box-messenger def__closeModal";
  bgBoxMessenger.id = "bgBoxMessenger";

  const modal = document.createElement("div");
  modal.className = "def-modal";

  const header = document.createElement("div");
  header.className = "def-header";

  const title = document.createElement("div");
  title.className = "title";
  title.innerHTML = `<span>${GLOBAL_LANG.messenger_modal_to_label_title}</span>`;

  const svg_close_icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
  svg_close_icon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
  svg_close_icon.setAttribute("width", "15");
  svg_close_icon.setAttribute("height", "15");
  svg_close_icon.setAttribute("viewBox", "0 0 22 22");
  svg_close_icon.setAttribute("class", "icon-close-right");

  const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
  path_element.setAttribute(
    "d",
    "M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z"
  );
  path_element.setAttribute("fill", "#666666");

  svg_close_icon.appendChild(path_element);

  const iconClose = document.createElement("div");
  iconClose.className = "close def__closeModal";
  iconClose.innerHTML = "";
  iconClose.appendChild(svg_close_icon);

  const body = document.createElement("div");
  body.className = "def-body def-checkbox-body";

  const chat_labels = document.querySelectorAll(".chat-labels");

  let checkbox = "";
  let mark = "";

  for (let i = 0; i < json.labels.length; i++) {
    let label_title = Util.doTruncarStr(json.labels[i].name, 20);
    for (let x = 0; x < chat_labels.length; x++) {

      if (label_title === chat_labels[x].innerHTML) {
        checkbox = "checked";
        mark = "mark";
      }
    }

    body.innerHTML += "<div class='select select-label' data-name='" + json.labels[i].name + "' data-color='" + json.labels[i].color + "' id='" + json.labels[i].id_label + "'>" +
      "<input type='checkbox' " + checkbox + " class='checkbox " + mark + "' id='lb_" + json.labels[i].id_label + "'style='cursor:pointer'>" +
      "<div class='color'><svg xmlns='http://www.w3.org/2000/svg' style='fill:" + json.labels[i].color + "' viewBox='0 0 18 12'><path d='M11.208,0.925H2.236C1.556,0.925,1,1.565,1,2.357V9.57C1,10.362,1.556,11,2.236,11h8.972 c0.414,0,0.785-0.237,1.007-0.604l2.701-4.433L12.215,1.53C11.993,1.162,11.622,0.925,11.208,0.925z'/></svg></div>" +
      "<div class='name'>" + json.labels[i].name + "</div>" +
      "</div>";

    checkbox = "";
    mark = "";
  }

  const footer = document.createElement("div");
  footer.className = "def-footer";

  const btnSave = document.createElement("button");
  btnSave.id = "select-label-confirm";
  btnSave.className = "def-btn-save";
  btnSave.innerHTML = GLOBAL_LANG.messenger_modal_to_label_btn_save;

  const btnCancel = document.createElement("button");
  btnCancel.className = "def-btn-cancel def__closeModal";
  btnCancel.innerHTML = GLOBAL_LANG.messenger_modal_to_label_btn_cancel;

  header.appendChild(title);
  header.appendChild(iconClose);

  footer.appendChild(btnCancel);
  footer.appendChild(btnSave);

  modal.appendChild(header);
  modal.appendChild(body);
  modal.appendChild(footer);

  $("#bgBoxMessenger").remove();
  $(".def-modal").remove();

  document.querySelector("html body").appendChild(modal);
  document.querySelector("html body").appendChild(bgBoxMessenger);

  const icon_label_info = document.getElementsByClassName("icon-label-info");
  const iconAddLabelInfo = document.getElementById("iconAddLabelInfo");

  if (iconAddLabelInfo != null) {
    iconAddLabelInfo.disabled = false;
  }

  if (icon_label_info[0] != undefined) {
    icon_label_info[0].disabled = false;
  }
}

function processParticipants(json) {

  $("#participants").html("");

  for (let i = 0; i < json.participants.length; i++) {

    let item = document.createElement('div');
    item.className = "item";

    let profile = document.createElement('div');
    profile.className = "profile";

    let img = document.createElement('img');
    img.src = json.participants[i].profile;

    let body = document.createElement('div');
    body.className = "body";

    let push_name = document.createElement('div');
    push_name.className = "push_name";
    push_name.innerHTML = ta.contact.pushName(json.participants[i].key_remote_id);

    profile.appendChild(img);
    body.appendChild(push_name);
    item.appendChild(profile);
    item.appendChild(body);

    $("#participants").append(item);
  }
}

processUserGroup = userGroup => processUser = userGroup;


function boxSearchMessage(json) {

  let dateFormat = "";
  let word = $(".search-input").attr("value").trim();

  $(".span-searched-message").remove();
  $("#load_search").find("img").remove();

  for (let i = 0; i < json.items.length; i++) {

    dateFormat = Util.FormatShortDate(json.items[i].creation);

    const list_message = document.querySelector(".list-message");
    list_message.style.textAlign = "justify";

    const itemSearched = document.createElement("div");
    itemSearched.className = "item-searched";
    itemSearched.setAttribute("name", json.items[i].creation);
    itemSearched.dataset.id = json.items[i].token;

    const timeSearch = document.createElement("span");
    timeSearch.className = "time-search";
    timeSearch.name = json.items[i].creation;
    timeSearch.innerHTML = dateFormat;

    const br = document.createElement("br");

    if (json.items[i].data != null || json.items[i].data != undefined) {

      const iconImg = document.createElement("i");
      iconImg.className = "fas fa-camera";

      const textSearch = document.createElement("span");
      textSearch.className = "text-search";
      textSearch.innerHTML = Util.doTruncarStr(removeAccents(json.items[i].data.toLowerCase()), 45).replace(removeAccents(word.toLowerCase()), `<span class='wor-bold'>${removeAccents(word.toLowerCase())}</span>`, Util.doTruncarStr(removeAccents(json.items[i].data), 45));

      itemSearched.appendChild(timeSearch);
      itemSearched.appendChild(br);
      if (json.items[i].media_type === 3) itemSearched.appendChild(iconImg);
      itemSearched.appendChild(textSearch);
      list_message.appendChild(itemSearched);
    }

    if (json.items[i].text_body != null || json.items[i].text_body != undefined) {

      const textSearch = document.createElement("span");
      textSearch.className = "text-search";
      textSearch.innerHTML = Util.doTruncarStr(removeAccents(json.items[i].text_body.toLowerCase()), 45).replace(removeAccents(word.toLowerCase()), `<span class='wor-bold'>${removeAccents(word.toLowerCase())}</span>`, Util.doTruncarStr(removeAccents(json.items[i].text_body), 45));

      itemSearched.appendChild(timeSearch);
      itemSearched.appendChild(br);
      itemSearched.appendChild(textSearch);

      list_message.appendChild(itemSearched);
    }
  }
}


function removeAccents(str) {
  var map = {
    '-': ' ',
    '-': '_',
    'a': 'á|à|ã|â|À|Á|Ã|Â',
    'e': 'é|è|ê|É|È|Ê',
    'i': 'í|ì|î|Í|Ì|Î',
    'o': 'ó|ò|ô|õ|Ó|Ò|Ô|Õ',
    'u': 'ú|ù|û|ü|Ú|Ù|Û|Ü',
    'c': 'ç|Ç',
    'n': 'ñ|Ñ'
  };

  for (var pattern in map) {
    str = str.replace(new RegExp(map[pattern], 'g'), pattern);
  };

  return str;
};


function saveContact() {

  $("#modal .box .items").html("");
  let name = contact_name;
  let numberContact = contact_number;

  $.ajax({
    url: document.location.origin = '/messenger/getResponsible',
    dataType: 'JSON',
    success: function (response) {

      let lbName = document.createElement("span");
      lbName.textContent = "Nome";
      lbName.style.cssFloat = "left";
      lbName.style.width = "100%";

      let inputName = document.createElement("input");
      inputName.type = "text";
      inputName.className = "contact name ";
      inputName.placeholder = "Nome do contato";
      inputName.value = name;
      inputName.style.cssFloat = "left";
      inputName.style.width = "100%";

      let lbNumber = document.createElement("span");
      lbNumber.textContent = "Número do contato";
      lbNumber.style.cssFloat = "left";
      lbNumber.style.width = "100%";

      let inputNumber = document.createElement("input");
      inputNumber.type = "tel";
      inputNumber.pattern = "\([0-9]{2}\)[\s][0-9]{4}-[0-9]{4,5}";
      inputNumber.className = "contact number";
      inputNumber.placeholder = "Número do contato";

      inputNumber.value = numberContact;
      inputNumber.disabled = "disabled";
      inputNumber.style.cssFloat = "left";
      inputNumber.style.width = "100%";
      inputNumber.style.backgroundColor = "rgb(118 118 118 / 22%)";

      let lbResponsible = document.createElement("span");
      lbResponsible.textContent = "Responsável";
      lbResponsible.style.cssFloat = "left";
      lbResponsible.style.width = "100%";

      let selectResponsible = document.createElement("select");
      selectResponsible.id = 'user_key_remote_id';
      selectResponsible.style.cssFloat = "left";
      selectResponsible.style.width = "100%";

      let btnOk = document.createElement("input");
      btnOk.type = "button";
      btnOk.className = "btn ok";
      btnOk.value = "Salvar";

      let btnCancel = document.createElement("input");
      btnCancel.type = "button";
      btnCancel.className = "btn cancel";
      btnCancel.value = "Cancelar";

      $("#modal .box .items").append(lbName);
      $("#modal .box .items").append(inputName);
      $("#modal .box .items").append(lbNumber);
      $("#modal .box .items").append(inputNumber);
      $("#modal .box .items").append(lbResponsible);
      $("#modal .box .items").append(selectResponsible);
      $("#modal .box .items").append(btnOk);
      $("#modal .box .items").append(btnCancel);
      $("#modal .box .head span").html(`<i class="fas fa-address-book"></i> Novo contato`);

      let optionNenhum = document.createElement('option');
      optionNenhum.text = 'Nenhum';
      optionNenhum.value = '0';

      $(selectResponsible).append(optionNenhum);

      response.forEach(x => {
        var option = document.createElement('option');
        option.text = x.last_name;
        option.value = x.key_remote_id;
        $(selectResponsible).append(option);
      });

      $("#modal").show();
      $(".number").mask("(99)99999-999?9");
      $(".name").focus();

    },
    error: function () {
      alert(GLOBAL_LANG.messenger_contact_alert_save_contact_error);
    }
  });
  $(".contact-add-chat").attr("value", "Enviar mensagem")
}


function processTicket(json) {

  const bgBoxMessenger = document.createElement("div");
  bgBoxMessenger.className = "bg-box-messenger def__closeModal";
  bgBoxMessenger.id = "bgBoxMessenger";

  const modal = document.createElement("div");
  modal.className = "def-modal";

  const header = document.createElement("div");
  header.className = "def-header";

  const title = document.createElement("div");
  title.className = "title";
  title.innerHTML = `<span>${GLOBAL_LANG.messenger_modal_ticket_title}</span>`;

  const svg_close_icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
  svg_close_icon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
  svg_close_icon.setAttribute("width", "15");
  svg_close_icon.setAttribute("height", "15");
  svg_close_icon.setAttribute("viewBox", "0 0 22 22");
  svg_close_icon.setAttribute("class", "icon-close-right");

  const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
  path_element.setAttribute(
    "d",
    "M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z"
  );
  path_element.setAttribute("fill", "#666666");

  svg_close_icon.appendChild(path_element);

  const iconClose = document.createElement("div");
  iconClose.className = "close def__closeModal";
  iconClose.innerHTML = "";
  iconClose.appendChild(svg_close_icon);

  const body = document.createElement("div");
  body.className = "def-body";

  const lbCompany = document.createElement("label");
  lbCompany.textContent = GLOBAL_LANG.messenger_modal_ticket_company;
  lbCompany.className = "def-label";

  const cbCompany = document.createElement("select");
  cbCompany.className = "def-select select select-ticket-company";

  const optionCompany = document.createElement("option");
  optionCompany.value = 0;
  optionCompany.text = GLOBAL_LANG.messenger_modal_ticket_select;

  cbCompany.appendChild(optionCompany);

  for (let i = 0; i < json.company.length; i++) {
    let optionCompany = document.createElement("option");
    optionCompany.value = json.company[i].id_company;
    optionCompany.text = json.company[i].fantasy_name;
    optionCompany.selected = json.info != undefined && json.info[0].id_company == json.company[i].id_company ? true : false;
    cbCompany.appendChild(optionCompany);
  }

  const lbSubtype = document.createElement("label");
  lbSubtype.textContent = GLOBAL_LANG.messenger_modal_ticket_subtype;
  lbSubtype.className = "def-label";

  const cbSubtype = document.createElement("select");
  cbSubtype.className = "def-select select select-ticket-subtype";
  cbSubtype.id = "select_subtype";

  const optionSubtype = document.createElement("option");
  optionSubtype.value = 0;
  optionSubtype.text = GLOBAL_LANG.messenger_modal_ticket_select;

  cbSubtype.appendChild(optionSubtype);

  if (typeof (json.subtype) != "undefined") {

    for (let i = 0; i < json.subtype.length; i++) {
      let optionSubtype = document.createElement("option");
      optionSubtype.value = json.subtype[i].id_ticket_type;
      optionSubtype.text = json.subtype[i].name;
      optionSubtype.className = "option-subtype-ticket";
      optionSubtype.selected = json.info != undefined && json.subtype[i].id_ticket_type == json.info[0].id_subtype ? true : false;
      cbSubtype.appendChild(optionSubtype);
    }
  }

  const lbTicketType = document.createElement("label");
  lbTicketType.textContent = GLOBAL_LANG.messenger_modal_ticket_type;
  lbTicketType.className = "def-label";

  const cbTicketType = document.createElement("select");
  cbTicketType.className = "def-select select select-ticket-type";
  cbTicketType.id = "select_ticket_type";

  const optionType = document.createElement("option");
  optionType.value = 0;
  optionType.text = GLOBAL_LANG.messenger_modal_ticket_select;

  cbTicketType.appendChild(optionType);

  for (let i = 0; i < json.ticket_type.length; i++) {
    let optionType = document.createElement("option");
    optionType.value = json.ticket_type[i].id_ticket_type;
    optionType.text = json.ticket_type[i].name;
    optionType.selected = json.info != undefined && json.info[0].id_ticket_type == json.ticket_type[i].id_ticket_type ? true : false;
    cbTicketType.appendChild(optionType);
  }

  const lbComment = document.createElement("label");
  lbComment.textContent = GLOBAL_LANG.messenger_modal_ticket_comment;
  lbComment.className = "def-label";

  const comment = document.createElement("textarea");
  comment.id = "comment";
  comment.innerHTML = json.info != undefined ? json.info[0].comment : "";
  comment.className = "def-textarea";

  const lbTicketStatus = document.createElement("label");
  lbTicketStatus.textContent = GLOBAL_LANG.messenger_modal_ticket_status;
  lbTicketStatus.className = "def-label";

  const cbTicketStatus = document.createElement("select");
  cbTicketStatus.className = "def-select select select-ticket-status";

  const optionStatus = document.createElement("option");
  optionStatus.value = 0;
  optionStatus.text = GLOBAL_LANG.messenger_modal_ticket_select;

  cbTicketStatus.appendChild(optionStatus);

  for (let i = 0; i < json.ticket_status.length; i++) {
    let optionStatus = document.createElement("option");
    optionStatus.value = json.ticket_status[i].id_ticket_status;
    optionStatus.text = json.ticket_status[i].name;
    optionStatus.selected = json.info != undefined && json.info[0].id_ticket_status == json.ticket_status[i].id_ticket_status ? true : false;
    cbTicketStatus.appendChild(optionStatus);
  }

  body.appendChild(lbCompany)
  body.appendChild(cbCompany)
  body.appendChild(lbTicketType)
  body.appendChild(cbTicketType)
  body.appendChild(lbSubtype)
  body.appendChild(cbSubtype)
  body.appendChild(lbComment)
  body.appendChild(comment)
  body.appendChild(lbTicketStatus)
  body.appendChild(cbTicketStatus)

  const footer = document.createElement("div");
  footer.className = "def-footer";

  const btnSave = document.createElement("button");
  btnSave.id = json.info != undefined ? "btn-ticket-edit" : "btn-ticket-ok";
  btnSave.className = "def-btn-save";
  btnSave.innerHTML = GLOBAL_LANG.messenger_modal_ticket_btn_save.toUpperCase();

  const btnCancel = document.createElement("button");
  btnCancel.id = "btn-ticket-cancel";
  btnCancel.className = "def-btn-cancel def__closeModal";
  btnCancel.innerHTML = GLOBAL_LANG.messenger_modal_ticket_btn_cancel.toUpperCase();

  header.appendChild(title);
  header.appendChild(iconClose);

  footer.appendChild(btnCancel);
  footer.appendChild(btnSave);

  modal.appendChild(header);
  modal.appendChild(body);
  modal.appendChild(footer);

  $("#bgBoxMessenger").remove();
  $(".def-modal").remove();

  document.querySelector("html body").appendChild(modal);
  document.querySelector("html body").appendChild(bgBoxMessenger);

  document.getElementById("chat-ticket").disabled = false;

}

function processProduct(json) {

  $(".product .items").html("");

  if (json.items.length > 0) {

    for (let i = 0; i < json.items.length; i++) {

      let item = document.createElement("div");
      item.className = "item";
      item.dataset.content = json.items[i].id_product;
      item.dataset.media_url = json.items[i].picture;
      item.dataset.url = json.items[i].url;
      item.dataset.description = json.items[i].short_description;

      let picture = document.createElement("img");
      picture.src = json.items[i].picture;
      picture.style.width = "48px";
      picture.style.height = "48px";
      picture.style.cssFloat = "left";
      picture.style.margin = "10px";
      picture.style.borderRadius = "3px";

      let title = document.createElement("span");
      title.className = "span";
      title.style.width = "90%";
      title.style.marginBottom = "0px";
      title.style.paddingBottom = "0px";
      title.textContent = json.items[i].title;

      let description = document.createElement("span");
      description.className = "span";
      description.style.width = "90%";
      description.textContent = Util.doTruncarStr(json.items[i].short_description, 300);

      item.appendChild(picture);
      item.appendChild(title);
      item.appendChild(description);

      $(".product .items").append(item);
    }
  }
}

function dialogUserBasicEdit() {

  const bgBoxMessenger = document.createElement("div");
  bgBoxMessenger.className = "bg-box-messenger def__closeModal";
  bgBoxMessenger.id = "bgBoxMessenger";

  const modal = document.createElement("div");
  modal.className = "def-modal";

  const header = document.createElement("div");
  header.className = "def-header";

  const title = document.createElement("div");
  title.className = "title";
  title.innerHTML = `<span>${GLOBAL_LANG.messenger_modal_edit_contact_title}</span>`;

  const svg_close_icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
  svg_close_icon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
  svg_close_icon.setAttribute("width", "15");
  svg_close_icon.setAttribute("height", "15");
  svg_close_icon.setAttribute("viewBox", "0 0 22 22");
  svg_close_icon.setAttribute("class", "icon-close-right");

  const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
  path_element.setAttribute(
    "d",
    "M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z"
  );
  path_element.setAttribute("fill", "#666666");

  svg_close_icon.appendChild(path_element);

  const iconClose = document.createElement("div");
  iconClose.className = "close def__closeModal";
  iconClose.innerHTML = "";
  iconClose.appendChild(svg_close_icon);

  header.appendChild(title);
  header.appendChild(iconClose);

  const body = document.createElement("div");
  body.className = "def-body";

  const lbName = document.createElement("label");
  lbName.textContent = GLOBAL_LANG.messenger_modal_edit_contact_name;
  lbName.className = "def-label";

  const txtName = document.createElement("input");
  txtName.type = "text";
  txtName.id = "edit-name";
  txtName.maxLength = 100;
  txtName.className = "def-input";
  txtName.placeholder = GLOBAL_LANG.messenger_modal_edit_contact_name_placeholder;
  txtName.value = document.getElementById("full_name_info").innerText;

  const lbEmail = document.createElement("label");
  lbEmail.textContent = GLOBAL_LANG.messenger_modal_edit_contact_email;
  lbEmail.className = "def-label";

  const txtEmail = document.createElement("input");
  txtEmail.type = "text";
  txtEmail.id = "edit-email";
  txtEmail.maxLength = 55;
  txtEmail.className = "def-input";
  txtEmail.placeholder = GLOBAL_LANG.messenger_modal_edit_contact_email_placeholder;
  txtEmail.value = document.getElementById("email").innerText;

  body.appendChild(lbName)
  body.appendChild(txtName)
  body.appendChild(lbEmail)
  body.appendChild(txtEmail)

  const footer = document.createElement("div");
  footer.className = "def-footer";

  const btnSave = document.createElement("button");
  btnSave.id = "btn-contact-edit-ok";
  btnSave.className = "def-btn-save";
  btnSave.innerHTML = GLOBAL_LANG.messenger_modal_edit_contact_btn_save;

  const btnCancel = document.createElement("button");
  btnCancel.id = "btn-contact-edit-cancel";
  btnCancel.className = "def-btn-cancel def__closeModal";
  btnCancel.innerHTML = GLOBAL_LANG.messenger_modal_edit_contact_btn_cancel;

  footer.appendChild(btnCancel);
  footer.appendChild(btnSave);

  modal.appendChild(header);
  modal.appendChild(body);
  modal.appendChild(footer);

  $("#bgBoxMessenger").remove();
  $(".def-modal").remove();

  document.querySelector("html body").appendChild(modal);
  document.querySelector("html body").appendChild(bgBoxMessenger);

  document.getElementById("contact-edit").disabled = false;
}

function processUsers(json) {
  $(".select-user").empty();
  $(".select-user").append(new Option(GLOBAL_LANG.messenger_modal_transfer_service_user_option_any, "0"));
  for (let i = 0; i < json.users.length; i++) {
    let o = new Option(json.users[i].last_name, json.users[i].key_remote_id);
    $(".select-user").append(o);
  }
}

function queryTicket(json) {

  $(".ticket .items").html("");

  if (json.ticket.length > 0) {

    for (let i = 0; i < json.ticket.length; i++) {

      let item = document.createElement("div");
      item.className = "item";
      item.dataset.content = json.ticket[i].id_ticket;

      let creation = document.createElement("span");
      creation.className = "span";
      creation.textContent = json.ticket[i].creation;

      let last_name = document.createElement("span");
      last_name.className = "span";
      last_name.textContent = json.ticket[i].last_name;

      let type = document.createElement("span");
      type.className = "span";
      type.textContent = json.ticket[i].type;

      let status = document.createElement("span");
      status.className = "span";
      status.textContent = json.ticket[i].status;

      item.appendChild(creation);
      item.appendChild(last_name);
      item.appendChild(type);
      item.appendChild(status);

      $(".ticket .items").append(item);
    }
    if (window.getComputedStyle(document.querySelector(".field-ai")).display === "none" || document.querySelector(".window__option").style.display === "block") {
      $(".ticket").show();
      buttonBottomScroll();
    }
  } else {
    $(".ticket").hide();
  }
}

function processQuickReplies(json) {
  $(".quick .items").html("");

  if (json.items.length > 0) {
    $(".quick").show();

    for (let i = 0; i < json.items.length; i++) {
      let tag = document.createElement("span");
      tag.className = "span tag";
      tag.textContent = json.items[i].tag;

      let item = document.createElement("div");
      item.className = "item";

      if (json.items[i].media_url == null || json.items[i].media_url === "") {
        item.dataset.type = "text";
        item.dataset.content = json.items[i].content;

        var content = document.createElement("span");
        content.className = "span";
        content.textContent = json.items[i].content;
      } else {
        item.dataset.type = "media";

        let mediaType = parseInt(json.items[i].media_type);
        var mimetype = "";
        var content;

        switch (mediaType) {
          case 2:
            mimetype = `audio/ogg`;
            content = document.createElement("div");
            content.className = "audio-reply-preview";
            let miniAudio = document.createElement("audio");
            miniAudio.src = json.items[i].media_url;
            miniAudio.controls = true;
            miniAudio.style.height = "30px";
            content.appendChild(miniAudio);
            break;
          case 3:
            mimetype = `image/jpeg`;
            content = document.createElement("img");
            content.className = "img-reply";
            content.src = json.items[i].media_url;
            break;
          case 4:
            mimetype = "application/pdf";
            content = document.createElement("img");
            content.className = "img-reply";
            content.src = "https://app.talkall.com.br/assets/img/panel/pdf_icon.png";
            break;
          case 5:
            mimetype = "video/mp4";
            content = document.createElement("video");
            content.className = "video-reply";
            content.src = json.items[i].media_url;
            content.muted = true;
            break;
        }

        item.dataset.content = json.items[i].media_url;
        item.setAttribute("media_title", json.items[i].media_title || "");
        item.setAttribute("media_size", json.items[i].media_size || "");
        item.setAttribute("media_duration", json.items[i].media_duration || "");
        item.setAttribute("mimetype", mimetype);
      }

      item.appendChild(tag);
      item.appendChild(content);
      $(".quick .items").append(item);
    }

    addClickToMediaPreview();
  } else {
    $(".quick").hide();
  }
}

function addClickToMediaPreview() {
  let media_replies = document.querySelectorAll(".quick .items .item");

  media_replies.forEach(c => {
    c.onclick = function (e) {
      const target = e.currentTarget;

      if (target.dataset.type === "text") {
        return;
      }

      $(".quick").hide();

      var url = target.dataset.content;
      var media_title = target.getAttribute("media_title");
      var media_size = target.getAttribute("media_size");
      var media_duration = target.getAttribute("media_duration");
      var mimetype = target.getAttribute("mimetype");

      $(".input").hide();
      $("#" + messenger.Chat.token).hide();
      $(".emojipicker").hide();
      $(".quick-answer").show();

      var media_preview_content = document.getElementById("media-preview-content");

      const activeMedia = media_preview_content.querySelector("audio, video");
      if (activeMedia) {
        activeMedia.pause();
        activeMedia.src = "";
        activeMedia.load();
      }

      while (media_preview_content.firstChild) {
        media_preview_content.removeChild(media_preview_content.firstChild);
      }

      $("#caption-quick-reply").hide();
      $("#quick-answer-send").css("border-radius", "30px");

      var media;

      if (mimetype.includes("image")) {
        media = document.createElement("img");
        media.src = url;
        $("#caption-quick-reply").show();
        $("#quick-answer-send").css("border-radius", "0px 30px 30px 0px");
      } else if (mimetype === "application/pdf") {
        media = document.createElement("img");
        media.src = "https://app.talkall.com.br/assets/img/panel/pdf_icon.png";
      } else if (mimetype.includes("video")) {
        media = document.createElement("video");
        media.src = url;
        media.controls = true;
        media.autoplay = true;
        media.playsInline = true;
        media.style.background = "#000";
        media.style.maxHeight = "300px";
      } else if (mimetype.includes("audio")) {
        media = document.createElement("audio");
        media.src = url;
        media.setAttribute("controls", true);
        media.autoplay = true;
      }

      if (media) {
        media.id = "media-preview-media";
        media.setAttribute("file-to-send", url);
        media.setAttribute("media_title", media_title);
        media.setAttribute("media_size", media_size);
        media.setAttribute("media_duration", media_duration);
        media.setAttribute("mimetype", mimetype);
        media_preview_content.append(media);

        if (typeof media.play === "function") {
          media.play().catch(err => console.log("Autoplay aguardando interação..."));
        }
      }
    };
  });
}

function processLastSeen(json) {
  if (messenger.Chat.key_remote_id == json.key_remote_id) {
    if (json.t != undefined) {
      const lang = json.t.split(":")[0];
      const datetime = json.t.split("seen:")[1];
      switch (lang) {
        case "last_seen":
          return $(".messenger .right .status").html(GLOBAL_LANG.messenger_team_last_seen + datetime);
        case "online":
          return $(".messenger .right .status").html(GLOBAL_LANG.messenger_team_online);
        default:
          break;
      }
    }
  }

  info = messenger.ChatList.findByKeyRemoteId(json.key_remote_id);
  if (info != null) {
    switch (json.type) {
      case 'wait':
      case 'available':
        switch (parseInt(info.last_type_message)) {
          case 1:
            $("#" + info.hash + " .last-message").html(Util.doTruncarStr(info.last_message, 20));
            break;
          case 2:
            $("#" + info.hash + " .last-message").html(Util.doTruncarStr(info.last_message, 20));
            break;
          case 3:
            $("#" + info.hash + " .last-message").html(Util.doTruncarStr(info.last_message, 20));
            break;
        }
        break;
      case 'composing':
        $("#" + info.hash + " .last-message").html('<div  class="event" style="display: block; margin: 0px">' + GLOBAL_LANG.messenger_team_typing + '</div>');
        break;
      case 'recording':
        $("#" + info.hash + " .last-message").html('<div  class="event" style="display: block; margin: 0px">' + GLOBAL_LANG.messenger_team_recording + '</div>');
        break;
    }
  }
}

function addClickToMediaPreview() {
  let media_replies = document.querySelectorAll(".quick .items .item");

  media_replies.forEach(c => {
    c.onclick = function (e) {
      const target = e.currentTarget;

      if (target.dataset.type === "text") {
        return;
      }

      $(".quick").hide();

      var url = target.dataset.content;
      var media_title = target.getAttribute("media_title");
      var media_size = target.getAttribute("media_size");
      var media_duration = target.getAttribute("media_duration");
      var mimetype = target.getAttribute("mimetype");

      $(".input").hide();
      $("#" + messenger.Chat.token).hide();
      $(".emojipicker").hide();
      $(".quick-answer").show();

      var media_preview_content = document.getElementById("media-preview-content");

      while (media_preview_content.firstChild) {
        media_preview_content.removeChild(media_preview_content.firstChild);
      }

      $("#caption-quick-reply").hide();
      $("#quick-answer-send").css("border-radius", "30px");

      var media;

      if (mimetype.includes("image")) {
        media = document.createElement("img");
        media.src = url;
        $("#caption-quick-reply").show();
        $("#quick-answer-send").css("border-radius", "0px 30px 30px 0px");
      } else if (mimetype === "application/pdf") {
        media = document.createElement("img");
        media.src = "https://app.talkall.com.br/assets/img/panel/pdf_icon.png";
      } else if (mimetype.includes("video")) {
        media = document.createElement("video");
        media.src = url;
        media.controls = true;
        media.autoplay = true;
        media.playsInline = true;
        media.style.background = "#000";
      } else if (mimetype.includes("audio")) {
        media = document.createElement("audio");
        media.src = url;
        media.setAttribute("controls", true);
      }

      if (media) {
        media.id = "media-preview-media";
        media.setAttribute("file-to-send", url);
        media.setAttribute("media_title", media_title);
        media.setAttribute("media_size", media_size);
        media.setAttribute("media_duration", media_duration);
        media.setAttribute("mimetype", mimetype);
        media_preview_content.append(media);
      }
    };
  });
}

function getDataUrl(img) {
  const canvas = document.createElement('canvas');
  const ctx = canvas.getContext('2d');
  canvas.width = img.width;
  canvas.height = img.height;
  ctx.drawImage(img, 0, 0);
  return canvas.toDataURL('image/jpeg');
}

function activeChatNow() {
  if ($('.right .head').hasClass('hide_max')) {
    $('.right .head').removeClass('hide_max');
    $('.body .chat').removeClass('hide_max');
  }
}

function openModalTemplete(json, type = null) {

  $("#bodyTemplate").show();
  $(".modalBoxTemplete .icon-close").hide();

  $("#selecTemplate").val("");
  $("#templateSelected").val("");

  $(".selectTemplete").find(".opt_t").remove();
  $(".modalBoxTemplete .body .list-template").hide();
  $(".modalBoxTemplete .body .list-template .item").remove();

  $(".variable-template .item").remove();
  $(".modalBoxTemplete .preview-template").hide();

  $(".modalBoxTemplete #previewMsg").remove();
  $(".modalBoxTemplete #box-img-template").remove();
  $(".modalBoxTemplete #previewMsgHeader").remove();
  $(".modalBoxTemplete #previewMsgFooter").remove();
  $(".modalBoxTemplete #uploadFileTemplate").remove();


  if (json.type == 8) {

    $(".modalBoxTemplete .box-select input").show();
    document.querySelector("#sendTemplete") != null ? document.querySelector("#sendTemplete").setAttribute("id", "sendMessageTag") : null;
    document.querySelector("#selecTemplate") != null ? document.querySelector("#selecTemplate").setAttribute("id", "inputMessageTag") : null;
    $(".modalBoxTemplete .footer .send img").show();
    $(".modalBoxTemplete .box-select label").html(`Digite sua notificação: `);
    document.querySelector("#inputMessageTag").setAttribute("placeholder", "Mensagem...");


  } else {

    const res = json.items;
    const listTemplate = document.getElementById("listTemplate");

    for (var i = 0; res.length > i; i++) {

      const item = document.createElement("div");
      item.className = "item";
      item.style.display = "none";

      const input_hidden = document.createElement("input");
      input_hidden.type = "hidden";
      input_hidden.value = `${res[i].namespace}#${res[i].name_to_request}#${res[i].language}#${res[i].text_body}#${res[i].name}#${res[i].text_footer}#${res[i].header}`;

      const header_type = document.createElement("input");
      header_type.type = "hidden";
      header_type.id = "header_type";
      header_type.value = `${res[i].header_type}`;

      const template_json = document.createElement("input");
      template_json.type = "hidden";
      template_json.id = "template_json";
      template_json.value = JSON.stringify(res[i]);

      const name_template = document.createElement("span");
      name_template.innerHTML = res[i].name;

      item.appendChild(name_template);
      item.appendChild(input_hidden);
      item.appendChild(header_type);
      item.appendChild(template_json);

      listTemplate.appendChild(item);
    }

    if (res.length === 0) {
      $(".modalBoxTemplete .box-select input").hide();
      $(".modalBoxTemplete .footer .send img").hide();
      $(".modalBoxTemplete .body description span").text(GLOBAL_LANG.messenger_modal_send_notification_description);
      $(".modalBoxTemplete .box-select label").html(`${GLOBAL_LANG.messenger_modal_send_notification_without_registered_template} <a href='${document.location.origin}/templates' target='_blank'>${GLOBAL_LANG.messenger_modal_send_notification_create_template}.</a>`);
    } else {
      $(".modalBoxTemplete .box-select input").show();
      $(".modalBoxTemplete .footer .send img").show();
      $(".modalBoxTemplete .box-select label").html(`${GLOBAL_LANG.messenger_modal_send_notification_label}`);
      document.querySelector("#sendMessageTag") != null ? document.querySelector("#sendMessageTag").setAttribute("id", "sendTemplete") : null;
      document.querySelector("#inputMessageTag") != null ? document.querySelector("#inputMessageTag").setAttribute("id", "selecTemplate") : null;
      document.querySelector("#selecTemplate").setAttribute("placeholder", `${GLOBAL_LANG.messenger_modal_send_notification_placeholder}`);
    }

  }

  $(".ticket").hide();
  $(".bgbox").fadeIn("fast");
  $(".modalBoxTemplete").fadeIn("fast");

}

function checkPaymentMessage(json) {

  if (json.items[0].payment_error != null) {
    const payment_message = {
      "Cmd": "paymentMessage",
      "items":
      {
        "title": GLOBAL_LANG.messenger_modal_error_payment_method_title,
        "text": GLOBAL_LANG.messenger_modal_error_payment_method_text,
        "url": "https://business.facebook.com/",
        "link_text": GLOBAL_LANG.messenger_modal_error_payment_method_url
      }
    };

    showAlertMessages(payment_message);
  }
}

function openModalTag() {

  $(".bgbox").fadeIn("fast");
  $(".modalBoxTag").fadeIn("fast");

}

function queryTemplates(type) {

  if (type == 8) {
    openModalTemplete({ type: 8 })
  } else {
    ta.chat.queryTemplates(messenger.Chat.key_remote_id.split("-")[1]);
    switchToDark("modalBoxTemplete");
  }


}

function queryTags() {
  openModalTag();
  switchToDark("modalBoxTag");
}

$(document).ready(function () {

  previewsProfile(ta.config.ta);
  ta.userGroup.queryUserGroup();

  if (!localStorage.getItem("night")) {
    localStorage.setItem("night", false);
  }

  bNight = eval(localStorage.getItem("night"));


  $(".item-searched").live("click", function () {

    for (elm of document.querySelectorAll(".item-searched")) elm.disabled = true;

    clearTimeout(QUERY_MESSAGES_CHAT);

    ITEM_FOCUSED = this.dataset.id;

    SCROLL_BLOCK = true;
    FORCE_SCROLL_DOWN = true;
    LOCK_FOCUS = false;

    $(".chat .messages").each((idx, elm) => {
      if (elm.style.display != "none") {
        $(elm).find("div").remove();
      }
    });

    ta.chat.queryMessages(ta.key_remote_id, $(this).attr('name'), false);
    $("#load_bottom_chat").remove();
  });


  $('body').on("click", ".container-quoted", function () {
    const id_parts = this.id.split('_');
    ITEM_FOCUSED = id_parts.slice(1).join('_');
    ta.chat.queryMessages(ta.key_remote_id, $(this).attr('name'));
  });


  $('body').on("click", ".cancel-click", function (e) {
    e.stopPropagation();
  });


  $("#iconArrowLeft").on("click", function (e) {

    $(".window__search .search-input").val("");

    $(".window__search .icon-clear-left").addClass("rotate").removeClass("show");
    $(".window__search .icon-search-left").removeClass("rotate").addClass("show");

    $(".window__search .item-searched").remove();
    $(".window__search .search-input").attr("placeholder", GLOBAL_LANG.messenger_window_search_placeholder);

    $(".window__search .search-input").blur();

    e.preventDefault();
    e.stopPropagation();
  });


  $(".search-input").on("keyup", function () {

    if (this.value.length < 1) {
      $(".icon-search-left").removeClass("rotate").addClass("show");
      $(".icon-clear-left").addClass("rotate").removeClass("show");
    } else {
      $(".icon-search-left").addClass("rotate").removeClass("show");
      $(".icon-clear-left").removeClass("rotate").addClass("show");
    }

  });


  $(".search-input").live("blur", function () {

    if ($(".search-input").val().length < 1) {

      $(".span-search-empty").remove();
      $(".span-name-user-search").remove();

      $(".list-message").css({ "text-align": "center" });
      $(".list-message").prepend(`<span class="span-name-user-search">${GLOBAL_LANG.messenger_window_search_span_search_with.replace("{{name}}", messenger.Chat.push_name)}</span>`);
    }
  });


  $(".search-input").live("keyup", function () {

    let typed_text = "", timetamp = 0;

    typed_text = $(".search-input").attr("value");
    typed_text.normalize('NFD').replace(/[\u0300-\u036f]/g, "");

    $(".span-searched-message").remove();
    $(".span-search-empty").remove();

    if ($("#load_search").find("img").hasClass("load_search") === false) {

      $("#load_search").find("img").remove();
      $("#load_search").prepend(`<img src="./assets/img/loads/loading_1.gif" class="load_search">`);

      if ($(".list-message").find("div").hasClass("item-searched") === false) {
        $(".span-name-user-search").remove();
        $(".list-message").prepend(`<span class="span-searched-message">${GLOBAL_LANG.messenger_window_search_searching}</span>`);
      }
    }

    if (typed_text.length > 1) {
      clearTimeout(time);

      time = setTimeout(() => {

        $(".list-message").find("div").remove();
        ta.chat.searchMessage(timetamp, removeAccents(typed_text.trim()));

      }, 600);

    } else {

      clearTimeout(time);

      $(".item-searched").remove();
      $(".span-searched-message").remove();
      $(".span-name-user-search").remove();
      $("#load_search").find("img").remove();

      $(".list-message").css({ "text-align": "center" });
      $(".list-message").prepend(`<span class="span-name-user-search">${GLOBAL_LANG.messenger_window_search_span_search_with.replace("{{name}}", messenger.Chat.push_name)}</span>`);
    }
  });


  $('.window__search .list-message').bind('scroll', function () {

    let creation = 0;
    let typed_text = $(".search-input").val();

    if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
      if (typed_text != "" && typed_text.length > 1) {

        creation = parseInt($(".list-message").find(".item-searched").eq(-1)[0].attributes.name.nodeValue);

        $("#load_search").prepend(`<i><img src="./assets/img/loads/loading_1.gif" class="load_search"></i>`);
        if (creation != null) {
          ta.chat.searchMessage(creation, removeAccents(typed_text.trim()));
        }
      }
    }
  });



  // Modal de template //
  $(".bgbox").live("click", function () {
    if ($('.ticket').find('.items').find('.item').hasClass('item')) {
      $(".ticket").show(1100);
    }

    $(".bgbox").fadeOut("fast");
    $(".modalBoxTemplete").fadeOut("fast");
  });

  $(".bgboxTag").live("click", function () {
    if ($('.ticket').find('.items').find('.item').hasClass('item')) {
      $(".ticket").show(1100);
    }

    $(".bgboxTag").fadeOut("fast");
    $(".modalBoxTag").fadeOut("fast");
  });

  $("#iconTempleteCancel").live("click", function () {
    if ($('.ticket').find('.items').find('.item').hasClass('item')) {
      $(".ticket").show(1100);
    }

    $(".bgbox").fadeOut("fast");
    $(".modalBoxTemplete").fadeOut("fast");
  });

  $("#selecTemplate").live("click", function (e) {
    $(".modalBoxTemplete .body .list-template").show();
    $(".modalBoxTemplete .body .list-template .item").show();

    e.preventDefault();
    e.stopPropagation();
  });

  $(".modalBoxTemplete .body .box-select img").live("click", function (e) {
    $(".modalBoxTemplete #selecTemplate").val("");
    $(".modalBoxTemplete #templateSelected").val("");

    $(".modalBoxTemplete .preview-template").hide();
    $(".modalBoxTemplete .body .box-select img").hide();

    $(".modalBoxTemplete #previewMsg").remove();
    $(".modalBoxTemplete #previewMsgFooter").remove();
    $(".modalBoxTemplete #previewMsgHeader").remove();
    $(".modalBoxTemplete #uploadFileTemplate").remove();
    $(".modalBoxTemplete .variable-template .group-variable").remove();
  });

  $(".modalBoxTemplete .icon-close-variable").live("click", function () {
    const id = $(this).parent().find("input").attr("id") + "__";
    $("#" + id)[0].innerHTML = $("#" + id)[0].dataset.variable;
    $(this).parent().find("input").val("");
    $(this).hide();
  });

  $(".modalBoxTemplete .body").live("click", function () {
    $(".modalBoxTemplete .body .list-template").hide();
    $(".modalBoxTemplete .body .list-template .item").hide();
  });

  $(".modalBoxTemplete .main-variable").live("click", function () {
    $(".modalBoxTemplete .body .list-template").hide();
  });

  $(".variable-template .item").live("keyup blur", function () {
    if ($(this).val() === "") {
      $(this).parent().find("img").hide();
      $("#" + this.id + "__").text($("#" + this.id + "__")[0].dataset.variable);
    } else {
      if ($(this).val().length > 100 && e.keyCode != 8 && e.keyCode != 9) {
        return false;
      } else {
        $(this).removeClass("alert__error");
        $(this).parent().find("img").show();
        $("#" + this.id + "__").text($(this).val());
      }
    }
  });

  $(".variable-template .item").live("paste", function () {
    setTimeout(() => {
      if ($(this).val() === "") {
        $("#" + this.id + "__").text($("#" + this.id + "__")[0].dataset.variable);
      } else {
        $("#" + this.id + "__").text($(this).val());
      }
    }, 350);
  });

  $(".list-template .item").live("click", function () {

    $(".modalBoxTemplete #previewMsg").remove();
    $(".modalBoxTemplete #box-img-template").remove();
    $(".modalBoxTemplete #previewMsgHeader").remove();
    $(".modalBoxTemplete #previewMsgFooter").remove();
    $(".modalBoxTemplete .button-preview-container").remove();
    $(".modalBoxTemplete #uploadFileTemplate").remove();
    $(".modalBoxTemplete .variable-template .group-variable").remove();
    $(".modalBoxTemplete .preview-template").show();

    const selectTemplete = $(this).find("input").val();
    const header_type = $(this).find("#header_type").val();
    const template_json = $(this).find("#template_json").val();
    const boxArquive = document.createElement("div");

    let text_body = selectTemplete.split("#")[3];
    let title = selectTemplete.split("#")[4];
    let text_footer = selectTemplete.split("#")[5];
    let text_header = selectTemplete.split("#")[6];

    let templateBreak = text_body.split("{{");

    for (let i = 0; i < (templateBreak.length); i++) {

      if (i !== 0) {

        const groupVariable = document.createElement("div");
        groupVariable.className = "group-variable";
        groupVariable.style.position = "relative";

        const variable = document.createElement("input");
        variable.className = "item";
        variable.id = `inputVariable${i}`;
        variable.setAttribute("maxlength", "100");
        variable.setAttribute("placeholder", "Digite aqui a variável {{" + i + "}}");

        const iconClose = document.createElement("img");
        iconClose.src = bNight === true ? `${document.location.origin}/assets/icons/messenger/dark/close2.svg` : `${document.location.origin}/assets/icons/messenger/close2.svg`;
        iconClose.className = "icon-close-variable";

        groupVariable.append(variable);
        groupVariable.append(iconClose);
        document.querySelector(".variable-template").append(groupVariable);
      }

      text_body = text_body.replace(`{{${i + 1}}}`, `<span data-variable='{{${i + 1}}}' id='inputVariable${i + 1}__'>{{${i + 1}}}</span>`);
    }

    if (text_header && text_header.includes("{{")) {
      let headerBreak = text_header.split("{{");

      for (let i = 0; i < headerBreak.length; i++) {

        if (i !== 0) {
          const groupVariable = document.createElement("div");
          groupVariable.className = "group-variable";
          groupVariable.style.position = "relative";

          const variable = document.createElement("input");
          variable.className = "item";
          variable.id = `inputHeaderVariable${i}`;
          variable.setAttribute("maxlength", "100");
          variable.setAttribute("placeholder", "Digite aqui a variável {{" + i + "}}");

          const iconClose = document.createElement("img");
          iconClose.src = bNight === true ? `${document.location.origin}/assets/icons/messenger/dark/close2.svg` : `${document.location.origin}/assets/icons/messenger/close2.svg`;
          iconClose.className = "icon-close-variable";

          groupVariable.append(variable);
          groupVariable.append(iconClose);
          document.querySelector(".variable-template").append(groupVariable);
        }

        text_header = text_header.replace(`{{${i + 1}}}`, `<span data-variable='{{${i + 1}}}' id='inputHeaderVariable${i + 1}__'>{{${i + 1}}}</span>`);
      }
    }

    if (header_type == 3 || header_type == 5 || header_type == 10) {

      const boxFile = document.createElement("div");
      boxFile.className = "file";
      boxFile.id = "uploadFileTemplate";

      const file = document.createElement("input");
      file.hidden = true;
      file.type = "file";

      switch (header_type) {
        case "3":
          file.setAttribute("accept", ".jpeg,.jpg,.jfif");
          file.dataset.header_type = "3";
          break;
        case "5":
          file.setAttribute("accept", ".mp4");
          file.dataset.header_type = "5";
          break;
        case "10":
          file.setAttribute("accept", "application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document");
          file.dataset.header_type = "10";
          break;
        default:
          break;
      }
      file.id = "inputFileTemplate";

      const text = document.createElement("span");
      text.className = "text-file";

      text.innerHTML = `<span>${GLOBAL_LANG.messenger_modal_send_notification_add_media} ${header_type == 3 ? GLOBAL_LANG.messenger_media_types_photo : `${header_type == 5 ? GLOBAL_LANG.messenger_media_types_video : GLOBAL_LANG.messenger_media_types_file} `} </span><svg style="margin-bottom:-5px;transform:rotate(-46deg);"xmlns="https://www.w3.org/2000/svg"
      viewBox="0 0 24 24" width="24" height="24"><path d="M1.816 15.556v.002c0 1.502.584 2.912 1.646 3.972s2.472 1.647 3.974 1.647a5.58 5.58 0 0 0 
      3.972-1.645l9.547-9.548c.769-.768 1.147-1.767 1.058-2.817-.079-.968-.548-1.927-1.319-2.698-1.594-1.592-4.068-1.711-5.517-.262l-7.916 
      7.915c-.881.881-.792 2.25.214 3.261.959.958 2.423 1.053 3.263.215l5.511-5.512c.28-.28.267-.722.053-.936l-.244-.244c-.191-.191-.567-.349-.957.04l-5.506
      5.506c-.18.18-.635.127-.976-.214-.098-.097-.576-.613-.213-.973l7.915-7.917c.818-.817 2.267-.699 3.23.262.5.501.802 1.1.849 1.685.051.573-.156 1.111-.589 
      1.543l-9.547 9.549a3.97 3.97 0 0 1-2.829 1.171 3.975 3.975 0 0 1-2.83-1.173 3.973 3.973 0 0 1-1.172-2.828c0-1.071.415-2.076 1.172-2.83l7.209-7.211c.157-.157.264-.579.028-.814L11.5 4.36a.572.572 
      0 0 0-.834.018l-7.205 7.207a5.577 5.577 0 0 0-1.645 3.971z"></path>`;

      boxFile.append(text);
      boxFile.append(file);
      document.querySelector(".variable-template").append(boxFile);

      boxArquive.className = "box-arquive";
      boxArquive.id = "box-img-template";

      const arquive = document.createElement("img");
      arquive.src = "/assets/img/panel/image.png";
      arquive.style.borderRadius = "8px";
      boxArquive.append(arquive);

      const url = document.createElement("input");
      url.id = "url-arquive-template";
      url.type = "hidden";
      boxArquive.append(url);

      $(".modalBoxTemplete .template-message-preview").append(boxArquive);
    }

    const header = document.createElement("span");
    header.className = "header-msg";
    header.innerHTML = text_header == "null" ? "" : text_header;
    header.id = "previewMsgHeader";
    header.style.marginBottom = text_header == "null" ? "0px" : "8px";

    const msg = document.createElement("span");
    msg.innerHTML = Util.nl2br(text_body, true);
    msg.style.whiteSpace = 'break-spaces';
    msg.id = "previewMsg";

    const footer = document.createElement("span");
    footer.className = "footer-msg";
    footer.innerHTML = text_footer == "null" ? "" : text_footer;
    footer.id = "previewMsgFooter";
    footer.style.marginTop = text_footer == "null" ? "0px" : "12px";

    const button_container = document.createElement("div");
    button_container.className = "button-preview-container";

    if (JSON.parse(template_json).buttons) {
      const content_button = JSON.parse(JSON.parse(template_json).buttons);

      for (let i = 0; i < content_button.length; i++) {

        let button = document.createElement("button");
        button.className = "button-preview";

        if (content_button[i].type == "QUICK_REPLY") {
          const svg = `<svg viewBox="0 0 28 28" height="12.8" width="12.8" preserveAspectRatio="xMidYMid meet" style="vertical-align: middle; margin: 0 3px 0 0;">
        <path fill="#007AFF" d="M13,15 C12.346,15 9.98,15.02 9.98,15.02 L9.98,20.39 L2.323,12 L9.98,3.6 L9.98,9.01 C9.98,9.01 12.48,8.98 13,9 C20.062,9.22 24.966,17.26 24.998,21.02 C22.84,18.25 17.17,15 13,15 Z M11.983,7.01 L11.983,1.11 C12.017,0.81 11.936,0.51 11.708,0.28 C11.312,-0.11 10.67,-0.11 10.274,0.28 L0.285,11.24 C0.074,11.45 -0.016,11.72 0,12 C-0.016,12.27 0.074,12.55 0.285,12.76 L10.219,23.65 C10.403,23.88 10.67,24.03 10.981,24.03 C11.265,24.03 11.518,23.91 11.7,23.72 C11.702,23.72 11.706,23.72 11.708,23.71 C11.936,23.49 12.017,23.18 11.983,22.89 C11.983,22.89 12,17.34 12,17 C18.6,17 24.569,21.75 25.754,28.01 C26.552,26.17 27,24.15 27,22.02 C27,13.73 20.276,7.01 11.983,7.01 Z"/>
        </svg>`;

          button.innerHTML = `${svg} ${content_button[i].text}`;
        }

        if (content_button[i].type == "PHONE_NUMBER") {
          const svg = `<svg viewBox="0 -2 18 22" height="18" width="18" preserveAspectRatio="xMidYMid meet" style="vertical-align: middle; margin: 0 3px 0 0;">
        <path d="M15.0075,11.535 C14.085,11.535 13.1925,11.385 12.36,11.115 C12.0975,11.025 11.805,11.0925 11.6025,11.295 L10.425,12.7725 C8.3025,11.76 6.315,9.8475 5.2575,7.65 L6.72,6.405 C6.9225,6.195 6.9825,5.9025 6.9,5.64 C6.6225,4.8075 6.48,3.915 6.48,2.9925 C6.48,2.5875 6.1425,2.25 5.7375,2.25 
        L3.1425,2.25 C2.7375,2.25 2.25,2.43 2.25,2.9925 C2.25,9.96 8.0475,15.75 15.0075,15.75 C15.54,15.75 15.75,15.2775 15.75,14.865 L15.75,12.2775 C15.75,11.8725 15.4125,11.535 15.0075,11.535 Z" 
        fill="#007AFF" fill-rule="nonzero"></path></svg>`;

          button.innerHTML = `${svg} ${content_button[i].text}`;
        }

        if (content_button[i].type == "URL") {
          const svg = `<svg viewBox="0 -2 18 22" height="18" width="18" preserveAspectRatio="xMidYMid meet" style="vertical-align: middle; margin: 0 3px 0 0;">
        <path d="M14,5.41421356 L9.70710678,9.70710678 C9.31658249,10.0976311 8.68341751,10.0976311 8.29289322,9.70710678 C7.90236893,9.31658249 7.90236893,8.68341751 8.29289322,8.29289322 L12.5857864,4 L10,4 C9.44771525,4 9,3.55228475 9,3 C9,2.44771525 9.44771525,2 10,2 L14,2 C15.1045695,2 16,2.8954305 16,4 L16,8 C16,8.55228475 15.5522847,9 15,9 C14.4477153,9 14,8.55228475 
        14,8 L14,5.41421356 Z M14,12 C14,11.4477153 14.4477153,11 15,11 C15.5522847,11 16,11.4477153 16,12 L16,13 C16,14.6568542 14.6568542,16 13,16 L5,16 C3.34314575,16 2,14.6568542 2,13 L2,5 C2,3.34314575 3.34314575,2 5,2 L6,2 C6.55228475,2 7,2.44771525 7,3 C7,3.55228475 6.55228475,4 6,4 L5,4 C4.44771525,4 4,4.44771525 4,5 L4,13 C4,13.5522847 4.44771525,14 5,14 L13,14 C13.5522847,14 14,13.5522847 14,13 L14,12 Z" 
        fill="#007AFF" fill-rule="nonzero"></path></svg>`;

          button.innerHTML = `${svg} ${content_button[i].text}`;
        }

        button_container.append(button);
      }
    }

    $(".modalBoxTemplete .template-message-preview").append(header);
    $(".modalBoxTemplete .template-message-preview").append(msg);
    $(".modalBoxTemplete .template-message-preview").append(footer);
    $(".modalBoxTemplete .template-message-preview").append(button_container);

    $(".modalBoxTemplete #selecTemplate").val(title);
    $(".modalBoxTemplete #templateSelected").val(selectTemplete);
    $(".modalBoxTemplete #selecTemplate").attr("data-header_type", header_type);
    $(".modalBoxTemplete #selecTemplate").attr("data-template_json", template_json);

    $(".modalBoxTemplete .body .list-template").hide();
    $(".modalBoxTemplete .body .list-template .item").hide();
    $(".modalBoxTemplete .body .box-select img").show();
  });

  $("#selecTemplate").live("keyup", function () {

    let list_template = document.querySelectorAll(".list-template .item");
    let search = document.getElementById("selecTemplate").value;
    let word = [], title;

    if (search.length > 1) {

      for (elm of list_template) {

        title = elm.children[0].innerHTML;

        title = removeAccents(title);
        search = removeAccents(search);

        title = title.toLowerCase();
        search = search.toLowerCase();

        word = [title];

        word.filter((word) => {
          if (word.indexOf(search) != -1) {
            elm.style.display = "block";
            $(".modalBoxTemplete .list-template").show();
          } else {
            elm.style.display = "none";
          }
        });
      }
    }

    if (search === "") {
      $(".modalBoxTemplete #selecTemplate").val("");
      $(".modalBoxTemplete #templateSelected").val("");

      $(".modalBoxTemplete .preview-template").hide();
      $(".modalBoxTemplete .body .box-select img").hide();

      $(".modalBoxTemplete #previewMsg").remove();
      $(".modalBoxTemplete #previewMsgFooter").remove();
      $(".modalBoxTemplete #previewMsgHeader").remove();
      $(".modalBoxTemplete #uploadFileTemplate").remove();
      $(".modalBoxTemplete .variable-template .group-variable").remove();
      $(".modalBoxTemplete .list-template").find(".item").css({ "display": "block" });
    } else {
      $(".modalBoxTemplete .body .box-select img").show();
    }
  });

  $("#sendTemplete").live("click", function () {

    const template_json = JSON.parse($("#selecTemplate")[0].dataset.template_json);
    let selectTemplete = $("#templateSelected").val();
    let header_type = $("#selecTemplate")[0].dataset.header_type;

    if (selectTemplete === "") {
      Swal.fire({
        title: 'Opss',
        text: GLOBAL_LANG.messenger_modal_send_notification_template_not_selected,
        type: 'warning',
        confirmButtonColor: '#2dce89',
        confirmButtonText: GLOBAL_LANG.messenger_modal_send_notification_btn_ok,
        cancelButtonClass: "btn btn-secondary",
      });
      return;
    }

    // Seleciona todos os grupos de variáveis, tanto do Header quanto do Body

    const listAllVariableTemplate = document.querySelectorAll(".group-variable");

    for (variable of listAllVariableTemplate) {
      if (variable.children[0].value == "") {
        Swal.fire({
          title: 'Opss',
          text: GLOBAL_LANG.messenger_modal_send_notification_fields_not_filled,
          type: 'warning',
          confirmButtonColor: '#2dce89',
          confirmButtonText: GLOBAL_LANG.messenger_modal_send_notification_btn_ok,
          cancelButtonClass: "btn btn-secondary",
        });
        variable.children[0].classList.add("alert__error");
        return;
      }
    }

    const img = $(".box-arquive").find("img").attr("src");

    if (img != undefined) {
      if (img == "/assets/img/panel/image.png") {
        Swal.fire({
          title: 'Opss',
          text: GLOBAL_LANG.messenger_modal_send_notification_file_not_uploaded,
          type: 'warning',
          confirmButtonColor: '#2dce89',
          confirmButtonText: GLOBAL_LANG.messenger_modal_send_notification_btn_ok,
          cancelButtonClass: "btn btn-secondary",
        });
        return;
      }
    }

    let namespace = selectTemplete.split('#')[0];
    let name_to_request = selectTemplete.split('#')[1];
    let language = selectTemplete.split('#')[2];
    let text_body = $("#previewMsg").text();

    Swal.fire({
      title: GLOBAL_LANG.messenger_send_notification_title,
      text: GLOBAL_LANG.messenger_send_notification_text,
      type: 'warning',
      confirmButtonColor: '#3085d6',
      confirmButtonText: GLOBAL_LANG.messenger_send_notification_button,
      cancelButtonText: GLOBAL_LANG.messenger_send_notification_button_cancel,
      showCancelButton: true,
      cancelButtonClass: "btn btn-secondary",
      reverseButtons: true

    }).then(t => {
      if (t.value == true) {

        const component = [];
        const url = $("#url-arquive-template").val();
        let headerComponent = null;
        let bodyComponent = null;
        let footerComponent = null;

        // 1. Variáveis de Texto do Header (Procura por inputs que começam com 'inputHeaderVariable')

        const headerVars = [];
        document.querySelectorAll("[id^='inputHeaderVariable']").forEach(variable => {
          headerVars.push({
            type: "text",
            text: variable.value
          });
        });

        // 2. MONTAGEM DO COMPONENTE HEADER (Mídia OU Texto)

        switch (header_type) {
          case "3": // Imagem (Mídia)
            if (url) {
              headerComponent = {
                "type": "header",
                "parameters": [
                  { "type": "image", "image": { "link": url } }
                ]
              };
            }
            break;

          case "5": // Vídeo (Mídia)
            if (url) {
              headerComponent = {
                "type": "header",
                "parameters": [
                  { "type": "video", "video": { "link": url } }
                ]
              };
            }
            break;

          case "10": // Documento (Mídia)
            if (url) {
              headerComponent = {
                "type": "header",
                "parameters": [
                  { "type": "document", "document": { "link": url } }
                ]
              };
            }
            break;

          default: // Header de Texto (Se não for Mídia e tiver variáveis)
            if (headerVars.length > 0) {
              headerComponent = {
                type: "header",
                parameters: headerVars
              };
            }
            break;
        }

        const bodyParameters = [];
        const listBodyVariableTemplate = document.querySelectorAll(".group-variable:not(#uploadFileTemplate):has(input:not([id^='inputHeaderVariable']))");

        if (listBodyVariableTemplate.length > 0) {
          listBodyVariableTemplate.forEach(variableGroup => {
            const variableInput = variableGroup.children[0];
            bodyParameters.push({
              "type": "text",
              "text": variableInput.value
            });
          });
        }

        if (bodyParameters.length > 0) {
          bodyComponent = {
            "type": "body",
            "parameters": bodyParameters
          };
        }

        // 4. MONTAGEM DO COMPONENTE FOOTER

        if (template_json.text_footer && template_json.text_footer.trim() !== "") {
          footerComponent = {
            "type": "footer",
            "parameters": [
              {
                "type": "text",
                "text": template_json.text_footer
              }
            ]
          };
        }

        // 5. MONTA O ARRAY FINAL DE COMPONENTES

        if (headerComponent) {
          component.push(headerComponent);
        }

        if (bodyComponent) {
          component.push(bodyComponent);
        }

        if (footerComponent) {
          component.push(footerComponent);
        }

        const data = text_body.replace(/'/g, '');

        // ENVIA TEMPLATE
        ta.message.sendTemplate(
          messenger.Chat.key_remote_id,
          name_to_request,
          namespace,
          language,
          data,
          component,
          template_json
        );

        $(".bgbox").fadeOut("fast");
        $(".modalBoxTemplete").fadeOut("fast");
      }
    });
  });

  $("#sendMessageTag").live("click", function () {

    let messageNotify = $("#inputMessageTag").val();

    if (messageNotify === undefined || messageNotify === '') {
      Swal.fire({
        title: 'Opss',
        text: GLOBAL_LANG.messenger_modal_send_notification_not_provide_message,
        type: 'warning',
        confirmButtonColor: '#2dce89',
        confirmButtonText: GLOBAL_LANG.messenger_modal_send_notification_btn_ok,
        cancelButtonClass: "btn btn-secondary",
      })
    } else {

      ta.message.sendTag(
        messenger.Chat.key_remote_id,
        messageNotify
      );

      $(".bgbox").fadeOut("fast");
      $(".modalBoxTemplete").fadeOut("fast");
      document.querySelector("#inputMessageTag").value = "";
    }
  });

  $("#uploadFileTemplate").live("click", function () {
    document.getElementById("inputFileTemplate").click();
  });

  $("#inputFileTemplate").live("change", function () {

    const file_type = this.files[0].type;
    const maxFileSize = 10 * 1024 * 1024;

    if (this.files[0].size > maxFileSize) {
      Swal.fire({
        title: GLOBAL_LANG.messenger_modal_send_notification_title_attention,
        text: GLOBAL_LANG.messenger_modal_send_notification_maximum_limit_mb_file,
        type: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: GLOBAL_LANG.messenger_modal_send_notification_btn_ok,
        cancelButtonClass: "btn btn-success",
      });
      return;
    }

    switch (this.dataset.header_type) {
      case "3":
        if (file_type != "image/jpeg") {
          Swal.fire({
            title: GLOBAL_LANG.messenger_modal_send_notification_title_attention,
            text: GLOBAL_LANG.messenger_modal_send_notification_img_format_not_accepted,
            type: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: GLOBAL_LANG.messenger_modal_send_notification_btn_ok,
            cancelButtonClass: "btn btn-success",
          });
          return;
        }
        break;
      case "5":
        if (file_type != "video/mp4") {
          Swal.fire({
            title: GLOBAL_LANG.messenger_modal_send_notification_title_attention,
            text: GLOBAL_LANG.messenger_modal_send_notification_video_format_not_accepted,
            type: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: GLOBAL_LANG.messenger_modal_send_notification_btn_ok,
            cancelButtonClass: "btn btn-success",
          });
          return;
        }

        break;
      case "10":
        if (file_type != "application/pdf") {
          Swal.fire({
            title: GLOBAL_LANG.messenger_modal_send_notification_title_attention,
            text: GLOBAL_LANG.messenger_modal_send_notification_file_format_not_accepted,
            type: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: GLOBAL_LANG.messenger_modal_send_notification_btn_ok,
            cancelButtonClass: "btn btn-success",
          });
          return;
        }
        break;
      default:
        break;
    }

    $("#box-img-template").find("img").attr("class", "_load");
    $("#box-img-template").find("img").attr("src", document.location.origin + "/assets/img/loads/loading_2.gif");

    const ta_id = Math.floor(Math.random() * 100000);
    const formData = new FormData();
    formData.append("filetoupload", this.files[0]);
    formData.append("ta_id", ta_id);

    $.ajax({
      type: "POST",
      url: "https://files.talkall.com.br:3000",
      data: formData,
      success: function (json) {
        $("#box-img-template").find("input").val(json.url);
        $("#box-img-template").find("img").attr("class", "_preview");
        $("#box-img-template").find("img").attr("src", "data:image/jpeg;base64," + json.thumbnail);

      },
      cache: false,
      contentType: false,
      processData: false
    });
  });

  $("#chat-active").live("click", function () {

    $("#chat-wait span").css("border-bottom", "0px");
    $("#chat-comment span").css("border-bottom", "0px");
    $("#chat-internal span").css("border-bottom", "0px");
    $("#chat-active span").css("border-bottom", "4px solid #2263d3");

    $("#chat-active .title").css("color", "");
    $("#chat-active span").css("color", "");

    $("#chat-wait .title").css("color", "#9a9a9a");
    $("#chat-wait span").css("color", "#9a9a9a");

    $("#chat-internal .title").css("color", "#9a9a9a");
    $("#chat-internal span").css("color", "#9a9a9a");

    $("#chat-comment .title").css("color", "#9a9a9a");
    $("#chat-comment span").css("color", "#9a9a9a");

    $("#list-wait").hide();
    $("#list-internal").hide();
    $("#list-active").show();
  });


  $("#chat-internal").live("click", function () {

    $("#chat-active span").css("border-bottom", "0px");
    $("#chat-comment span").css("border-bottom", "0px");
    $("#chat-wait span").css("border-bottom", "0px");
    $("#chat-internal span").css("border-bottom", "4px solid #2263d3");

    $("#chat-internal .title").css("color", "");
    $("#chat-internal span").css("color", "");

    $("#chat-wait .title").css("color", "#9a9a9a");
    $("#chat-wait span").css("color", "#9a9a9a");

    $("#chat-active .title").css("color", "#9a9a9a");
    $("#chat-active span").css("color", "#9a9a9a");

    $("#chat-comment .title").css("color", "#9a9a9a");
    $("#chat-comment span").css("color", "#9a9a9a");

    $("#list-active").hide();
    $("#list-internal").show();
    $("#list-wait").hide();
  });


  $("#chat-wait").live("click", function () {

    $("#chat-active span").css("border-bottom", "0px");
    $("#chat-comment span").css("border-bottom", "0px");
    $("#chat-internal span").css("border-bottom", "0px");
    $("#chat-wait span").css("border-bottom", "4px solid #2263d3");

    $("#chat-wait .title").css("color", "");
    $("#chat-wait span").css("color", "");

    $("#chat-internal .title").css("color", "#9a9a9a");
    $("#chat-internal span").css("color", "#9a9a9a");

    $("#chat-active .title").css("color", "#9a9a9a");
    $("#chat-active span").css("color", "#9a9a9a");

    $("#chat-comment .title").css("color", "#9a9a9a");
    $("#chat-comment span").css("color", "#9a9a9a");

    $("#list-wait").show();
    $("#list-internal").hide();
    $("#list-active").hide();
  });


  $("#emoji").live("click", function () {
    $("#box-clip").hide();
    if ($(".emojipicker").css("display") == "none") {
      let emoji_bottom = document.getElementById("bottomEntryRectangle").clientHeight;
      $(".emojipicker").css("display", "block");
      $(".emojipicker").css("bottom", emoji_bottom);
    } else {
      ta.contact.queryInfo();
      $(".emojipicker").css("display", "none");
    }
  });

  document.addEventListener('keydown', (event) => {
    let keyNumber = event.keyCode;

    if (keyNumber == 27 && $(".emojipicker").css("display") == "block") {
      $(".emojipicker").css("display", "none");
    }
  });

  $(".emojipicker .items .item").live("click", function () {
    $(".input-text").append(processEmoji(parseInt($(this).attr("data-emoji"))));
    $(".input-text").focus();
  });

  $(".input-text").on("input", function () {
    if ($(this).text().trim() === "") {
      $(this).html("");
    }
  });

  $(".input-text").live("keyup", function () {

    if ($(".input-text").text().indexOf("/") != -1) {
      if ($(".input-text").text().length < 50) {
        $(".quick").show();
        clearTimeout(typingSearchQuick);
        typingSearchQuick = setTimeout(doneSearchQuick, doneSearchQuickInterval);
      } else {
        $(".quick").hide();
      }
    } else {
      $(".quick").hide();
    }
  });

  $("#search-product").live("keyup", function () {
    if ($("#search-product").val().length < 10) {
      clearTimeout(typingSearchProduct);
      typingSearchProduct = setTimeout(doneSearchProduct, doneSearchProductInterval);
    } else {
      $(".input-text").focus();
      $(".product").hide();
    }

  });

  $(".input-text").live("keyup", function (e) {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
  });

  $(".input-text").on("paste", function (e) {
    e.preventDefault();

    let clipboardData = (e.originalEvent || e).clipboardData || window.clipboardData;
    let bufferText = clipboardData.getData('text/plain'); // Pega apenas texto puro

    bufferText = bufferText.replace(/\r\n|\r|\n/g, "\n"); // Padroniza as quebras de linha

    document.execCommand('insertHTML', false, bufferText.replace(/\n/g, '<br>'));
  });

  $(".input-text").live("keypress paste keyup", function (e) {
    $(".emojipicker").css("display", "none");
    $(".box-clip").css("display", "none");

    buttonBottomScroll();

    if ($(".input-text").text().trim().length > 0) {

      const checkLimitCaracter = (score) => {

        if (e.shiftKey || e.keyCode != 13) {

          const inputContent = $(".input-text").text();
          const characterLimit = score;

          let dinamicLength = e.handleObj.type == 'paste' ? characterLimit : characterLimit - 1;

          if (inputContent.length > dinamicLength) {

            let str = inputContent;

            if (str.length > dinamicLength) {
              str = str.slice(0, dinamicLength);
            }

            document.querySelector(".input-text").innerHTML = str.trim();

            const el = document.querySelector(".input-text");
            const range = document.createRange();
            const sel = window.getSelection();

            range.setStart(el, 1);
            range.collapse(true);
            sel.removeAllRanges();
            sel.addRange(range);

            $(".characters-limit span").html(`${GLOBAL_LANG.messenger_character_limit.replace("{param}", score)}`);
            $(".characters-limit").fadeIn("slow").css({ display: "block" });

            setTimeout(() => { $(".characters-limit").fadeOut("slow").css({ display: "none" }); $(".characters-limit span").html(""); }, "4000");

            return;
          }
        }

      }

      if (messenger.Chat.is_type == 9) {
        checkLimitCaracter(1000);
      } else {
        checkLimitCaracter(4096);
      }

      if (parseInt(messenger.Chat.is_type) == 1) ta.chat.composing();

      if (e.keyCode == 13 && e.shiftKey) {

      } else {

        if (e.keyCode == 13) {

          if (alertCloseChat(messenger.ChatList.find(messenger.Chat.selected))) return;

          e.preventDefault();

          let textExtendedText = $(".input-text").text();

          if (quoted_id != 0) {
            switch (parseInt(messenger.Chat.is_type)) {
              case 1:
              case 2:
                let type_message = 0;
                let type_messege_class = $("#" + quoted_id).find("div")[1].className;
                let verify_type = type_messege_class.split(" ")[0];
                let creation_quoted = 0;
                var participant = 0;
                var media_url_ = 0;
                var file_name_ = 0;
                var media_mime_type_ = 0;
                switch (verify_type) {
                  case "textMessage":

                    type_message = 1;
                    creation_quoted = $("#" + quoted_id).attr("data-index");
                    participant = $("#" + quoted_id).find(".textMessage").find(".participant_message").text();
                    break;
                  case "AudioMessage":
                    type_message = 2;
                    creation_quoted = $("#" + quoted_id).attr("data-index");
                    media_url_ = $("#" + quoted_id).find(".AudioMessage").find(".body").find("audio").attr("src");
                    break;
                  case "ImageMessage":

                    type_message = 3;
                    creation_quoted = $("#" + quoted_id).attr("data-index");
                    media_url_ = $("#" + quoted_id).find(".ImageMessage").find(".body").find("img").attr("src");
                    break;
                  case "DocumentMessage":

                    type_message = 4;
                    creation_quoted = $("#" + quoted_id).attr("data-index");

                    media_url_ = $("#" + quoted_id).find(".DocumentMessage").attr("data-url");
                    file_name_ = $("#" + quoted_id).find(".DocumentMessage").find(".body").find("span").text();
                    media_mime_type_ = $("#" + quoted_id).find(".DocumentMessage").find(".body").find(".bodyDocument").find("img")[0].attributes[0].nodeValue;
                    break;
                  case "VideoMessage":

                    type_message = 5;
                    creation_quoted = $("#" + quoted_id).attr("data-index");
                    media_url_ = $(".VideoMessage").attr("data-url");
                    break;
                  case "GifMessage":
                    type_message = 6;
                    break;
                  case "LocationMessage":

                    media_url_ = $("#" + quoted_id).find(".LocationMessage").find(".body").find(".thumbnail").find("a").attr("href");
                    creation_quoted = $("#" + quoted_id).attr("data-index");
                    type_message = 7;
                    break;
                  case "ContactMessage":
                    type_message = 9;
                    break;
                  case "ZipMessage":

                    type_message = 10;
                    media_url_ = $(".ZipMessage").attr("data-url");
                    creation_quoted = $("#" + quoted_id).attr("data-index");
                    file_name_ = $("#" + quoted_id).find(".ZipMessage").find(".body").find("span").text();
                    media_mime_type_ = $("#" + quoted_id).find(".ZipMessage").find(".body").find(".icon_document").attr("src");
                    break;
                  case "StickerMessage":
                    type_message = 26;
                    break;
                  case "TemplateMessage":
                    type_message = 27;
                    break;
                  case "InteractiveMessage":
                    type_message = 30;
                    break;
                }

                $(".input-text").text("");
                $(".reply-message .quoted").html("");
                $(".reply-message").hide();

                ta.message.sendExtendedText(
                  messenger.Chat.key_remote_id,
                  textExtendedText,
                  quoted_id,
                  media_type_message = type_message,
                  media_url_message = media_url_,
                  media_title_message = file_name_,
                  participant_message = participant,
                  creation_message = creation_quoted,
                  media_mime_type = media_mime_type_,
                );

                break;
            }

          } else {
            ta.message.sendText(
              messenger.Chat.key_remote_id,
              getFormattedText(),
            );
          }

          quoted_id = 0;

          $(".input-text").text("");
          $(".reply-message .quoted").html("");
          $(".reply-message").hide();

        }

      }

    } else {
      if (e.keyCode == 13) {
        e.preventDefault();
      }
    }

  });

  $(".find input").on("keyup", function (event) {

    if (event.keyCode == 13) {

      let token = localStorage.getItem("openChat");

      let info = messenger.ChatList.findByKeyRemoteId(token);
      if (info != null) {
        switch ($("#" + info.hash).parent()[0].id) {
          case "list-active":

            $("#chat-wait span").css("border-bottom", "0px");
            $("#chat-comment span").css("border-bottom", "0px");
            $("#chat-internal span").css("border-bottom", "0px");
            $("#chat-active span").css("border-bottom", "4px solid #2263d3");

            $("#chat-active .title").css("color", "");
            $("#chat-active span").css("color", "");

            $("#chat-wait .title").css("color", "#9a9a9a");
            $("#chat-wait span").css("color", "#9a9a9a");

            $("#chat-internal .title").css("color", "#9a9a9a");
            $("#chat-internal span").css("color", "#9a9a9a");

            $("#chat-comment .title").css("color", "#9a9a9a");
            $("#chat-comment span").css("color", "#9a9a9a");

            $("#list-wait").hide();
            $("#list-internal").hide();
            $("#list-active").show();
            break;

          case "list-internal":

            $("#chat-active span").css("border-bottom", "0px");
            $("#chat-comment span").css("border-bottom", "0px");
            $("#chat-wait span").css("border-bottom", "0px");
            $("#chat-internal span").css("border-bottom", "4px solid #2263d3");

            $("#chat-internal .title").css("color", "");
            $("#chat-internal span").css("color", "");

            $("#chat-wait .title").css("color", "#9a9a9a");
            $("#chat-wait span").css("color", "#9a9a9a");

            $("#chat-active .title").css("color", "#9a9a9a");
            $("#chat-active span").css("color", "#9a9a9a");

            $("#chat-comment .title").css("color", "#9a9a9a");
            $("#chat-comment span").css("color", "#9a9a9a");

            $("#list-active").hide();
            $("#list-internal").show();
            $("#list-wait").hide();
            break;

          case "list-wait":

            $("#chat-active span").css("border-bottom", "0px");
            $("#chat-comment span").css("border-bottom", "0px");
            $("#chat-internal span").css("border-bottom", "0px");
            $("#chat-wait span").css("border-bottom", "4px solid #2263d3");

            $("#chat-wait .title").css("color", "");
            $("#chat-wait span").css("color", "");

            $("#chat-internal .title").css("color", "#9a9a9a");
            $("#chat-internal span").css("color", "#9a9a9a");

            $("#chat-active .title").css("color", "#9a9a9a");
            $("#chat-active span").css("color", "#9a9a9a");

            $("#chat-comment .title").css("color", "#9a9a9a");
            $("#chat-comment span").css("color", "#9a9a9a");

            $("#list-wait").show();
            $("#list-internal").hide();
            $("#list-active").hide();
            break;
        }
      }

    } else if (event.keyCode == 38) {

    } else if (event.keyCode == 40) {

    } else {

      if (this.value.length < 1) {
        $(".icon-search-contact").removeClass("rotate").addClass("show");
        $(".icon-clear").addClass("rotate").removeClass("show");
      } else {
        $(".icon-search-contact").addClass("rotate").removeClass("show");
        $(".icon-clear").removeClass("rotate").addClass("show");
      }

      if (this.value.length > 2) {
        $("#list-find").html("");

        let div = document.createElement("div");
        div.id = "load_container_svg";

        let i = document.createElement("i");
        let img = document.createElement("img");
        img.src = "./assets/img/loads/loading_1.gif";
        img.className = "load-img";

        let br = document.createElement("br");
        let span = document.createElement("span");
        span.innerHTML = GLOBAL_LANG.messenger_loading_ellipsis;

        i.append(img);
        div.append(i, br, span);
        $("#list-find").append(div);

        clearTimeout(searchTimer);
        searchTimer = setTimeout(doneSearch, doneSearchTimer);

        $("#list-find").show();
        $(".tabs").css("display", "none");
      } else {
        $("#list-find").html("");
        $("#list-find").hide();
        $(".tabs").css("display", "flex");
      }
    }
  });

  $("#list-find .item").live("click", function () {

    for (item of document.querySelectorAll("#list-find .item")) item.disabled = true;
    let key_contact = this.id;
    const attendance = true;

    ta.chat.open(key_contact, true, attendance);
    localStorage.setItem("openChat", "");

    $(".icon-search-contact").removeClass("rotate").addClass("show");
    $(".icon-clear").addClass("rotate").removeClass("show");
    $("#chat-active")[0].click();
    $(".icon-clear").click();
  });

  $("#list-find").scroll(function () {

    if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
      ta.contact.queryContact(
        $(".input-search-contact").val(),
        $("#list-find .item")[$("#list-find .item").length - 1].dataset.index
      );
    }
  });


  $("#close-chat").live("click", function () {
    if ($('#ok-record').is(':visible'))
      cancelRecording();

    ta.chat.queryCategories(localStorage.getItem("userToken"));

    if (messenger.Chat.is_private == 1) {

      const bgBoxMessenger = document.createElement("div");
      bgBoxMessenger.className = "bg-box-messenger def__closeModal";
      bgBoxMessenger.id = "bgBoxMessenger";

      const title = document.createElement("div");
      title.className = "title";
      title.innerHTML = `<span>${GLOBAL_LANG.messenger_modal_close_service_title}</span>`;

      const body = document.createElement("div");
      body.className = "def-body";

      const lbName = document.createElement("label");
      lbName.textContent = GLOBAL_LANG.messenger_modal_close_service_text;
      lbName.className = "def-label";
      lbName.style.height = '24px';
      lbName.style.fontWeight = "normal";

      const select = document.createElement("select");
      select.id = 'select_modal_close';
      select.style.padding = '8px';
      select.style.border = '1px solid #ccc';
      select.style.borderRadius = '5px';
      select.style.fontSize = '14px';
      select.style.width = '100%';
      select.style.marginTop = '8px';

      const option = document.createElement("option");
      option.innerHTML = GLOBAL_LANG.messenger_option_placeholder_category;
      option.value = null;

      const redirectionContainer = document.createElement("div");
      redirectionContainer.className = "def-label";
      redirectionContainer.style.marginTop = '16px';
      redirectionContainer.style.display = 'inline-block';
      redirectionContainer.style.fontSize = '13px';

      // Cria a parte do texto antes do link
      const redirectionText = document.createTextNode(GLOBAL_LANG.messenger_modal_text_redirection + ' ');

      // Cria o link
      const linkLabel = document.createElement("a");
      linkLabel.textContent = GLOBAL_LANG.messenger_modal_redirection;
      linkLabel.href = "https://app.talkall.com.br/category";
      linkLabel.target = "_blank";
      linkLabel.style.color = "#007bff";
      linkLabel.style.textDecoration = "underline";
      linkLabel.style.whiteSpace = "nowrap";

      body.appendChild(lbName);
      body.appendChild(select);
      select.appendChild(option);
      body.appendChild(redirectionContainer);
      redirectionContainer.appendChild(redirectionText);
      redirectionContainer.appendChild(linkLabel);

      const svg_close_icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg_close_icon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
      svg_close_icon.setAttribute("width", "15");
      svg_close_icon.setAttribute("height", "15");
      svg_close_icon.setAttribute("viewBox", "0 0 22 22");
      svg_close_icon.setAttribute("class", "icon-close-right");

      const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
      path_element.setAttribute(
        "d",
        "M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z"
      );
      path_element.setAttribute("fill", "#666666");

      svg_close_icon.appendChild(path_element);

      const iconClose = document.createElement("div");
      iconClose.className = "close def__closeModal";
      iconClose.innerHTML = "";
      iconClose.appendChild(svg_close_icon);

      const header = document.createElement("div");
      header.className = "def-header";
      header.appendChild(title);
      header.appendChild(iconClose);

      const footer = document.createElement("div");
      footer.className = "def-footer";

      const btnSave = document.createElement("button");
      btnSave.className = "def-btn-save";
      btnSave.id = "chat-close";
      btnSave.innerHTML = GLOBAL_LANG.messenger_modal_close_service_btn_close;

      const btnCancel = document.createElement("button");
      btnCancel.id = "btn-trans-cancel";
      btnCancel.className = "def-btn-cancel def__closeModal btn cancel";
      btnCancel.innerHTML = GLOBAL_LANG.messenger_modal_close_service_btn_cancel;

      footer.appendChild(btnCancel);
      footer.appendChild(btnSave);

      const modal = document.createElement("div");
      modal.className = "def-modal";
      modal.appendChild(header);
      modal.appendChild(body);
      modal.appendChild(footer);

      $("#bgBoxMessenger").remove();
      $(".def-modal").remove();

      document.querySelector("html body").appendChild(modal);
      document.querySelector("html body").appendChild(bgBoxMessenger);

      $(".window__favorite").css("display", "none");
      $(".window__search").css("display", "none");
      $(".window__gallery").css("display", "none");

    } else {
      ta.chat.close();
      messenger.Chat.selected = '';
      messenger.ChatList.Remove(ta.key_remote_id);

      $(".window__search").css("display", "none");
      $(".window__gallery").css("display", "none");
      $(".window__favorite").css("display", "none");

      $(".messenger").find(".right").find(".head").addClass("hide_max");
      $(".messenger").find(".right").find(".body").find(".chat").addClass("hide_max");
    }
  });


  $("#chat-close").live("click", function () {
    this.disabled = true;

    const id_category = document.getElementById("select_modal_close").value;

    let info = messenger.ChatList.find(messenger.Chat.selected);
    if (info != null) {
      closeModal();
      ta.chat.close(id_category);
      messenger.Chat.selected = '';
      messenger.ChatList.Remove(info.key_remote_id);
      messenger.Chat.key_remote_id = null;
    }
  });


  $(".cancel").live("click", function () {
    $("#modal").hide();
  });


  $(".close-view").live("click", function () {
    $("#modal").hide();
  });

  $(".icon-clear").on("click", function () {
    $(".input-search-contact").val("");
    $(".icon-clear").removeClass("show").addClass("rotate");
    $(".icon-search-contact").removeClass("rotate").addClass("show");
    $("#list-find").hide();
    $(".tabs").css("display", "flex");
  });


  $("#chat-report-span").live("click", function () {

    const bgBoxMessenger = document.createElement("div");
    bgBoxMessenger.className = "bg-box-messenger def__closeModal";
    bgBoxMessenger.id = "bgBoxMessenger";

    const title = document.createElement("div");
    title.className = "title";
    title.innerHTML = `<span>${GLOBAL_LANG.messenger_modal_spam_title}</span>`;

    const svg_close_icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg_close_icon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
    svg_close_icon.setAttribute("width", "15");
    svg_close_icon.setAttribute("height", "15");
    svg_close_icon.setAttribute("viewBox", "0 0 22 22");
    svg_close_icon.setAttribute("class", "icon-close-right");

    const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
    path_element.setAttribute(
      "d",
      "M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z"
    );
    path_element.setAttribute("fill", "#666666");

    svg_close_icon.appendChild(path_element);

    const iconClose = document.createElement("div");
    iconClose.className = "close def__closeModal";
    iconClose.innerHTML = "";
    iconClose.appendChild(svg_close_icon);

    const header = document.createElement("div");
    header.className = "def-header";
    header.appendChild(title);
    header.appendChild(iconClose);

    const body = document.createElement("div");
    body.className = "def-body";

    const lbName = document.createElement("label");
    lbName.textContent = GLOBAL_LANG.messenger_modal_spam_body;
    lbName.className = "def-label";
    lbName.style.fontWeight = "normal";
    body.appendChild(lbName)

    const footer = document.createElement("div");
    footer.className = "def-footer";

    const btnSave = document.createElement("button");
    btnSave.className = "def-btn-save";
    btnSave.id = "blocklist-ok";
    btnSave.innerHTML = GLOBAL_LANG.messenger_modal_spam_btn_save;

    const btnCancel = document.createElement("button");
    btnCancel.id = "btn-trans-cancel";
    btnCancel.className = "def-btn-cancel def__closeModal btn cancel";
    btnCancel.innerHTML = GLOBAL_LANG.messenger_modal_spam_btn_cancel;

    footer.appendChild(btnCancel);
    footer.appendChild(btnSave);

    const modal = document.createElement("div");
    modal.className = "def-modal";
    modal.appendChild(header);
    modal.appendChild(body);
    modal.appendChild(footer);

    $("#bgBoxMessenger").remove();
    $(".def-modal").remove();

    document.querySelector("html body").appendChild(modal);
    document.querySelector("html body").appendChild(bgBoxMessenger);
  });


  $("#blocklist-ok").live("click", function () {
    this.disabled = true;
    ta.blocklist.block();
    closeModal()
  });


  $(".GifMessage video").live("click", function () {
    this.play();
  });


  $("#record-audio").live("click", function () {
    navigator.permissions.query({
      name: 'microphone'
    }).then(function (result) {
      if (result.state == 'granted') {
        startRecording();
      } else if (result.state == 'prompt') {
        navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => { });
      } else if (result.state == 'denied') {
        //console.log(result.state);
      }
      result.onchange = function () { };
    });
  });

  $("#ok-record").live("click", function () {
    stopRecording();
  });

  $("#stop-record").live("click", function () {
    cancelRecording();
  });

  $("#file-upload").live("click", function () {
    if (messenger.Chat.is_type == 12 || messenger.Chat.is_type == 16) {
      $("#send-template").show();
      $("#send-template").live("click", function () {
        queryTemplates(messenger.Chat.is_type);
      });
    } else {
      $("#send-template").hide();
    }

    if ($('#ok-record').is(':visible'))
      cancelRecording();

    $("#emojipicker").hide();
    if ($("#box-clip").css("display") == "none") {
      let box_clip_bottom = document.getElementById("bottomEntryRectangle").clientHeight;
      $("#box-clip").show();
      $("#box-clip").css("bottom", box_clip_bottom);
      ta.contact.queryInfo();
    } else {
      $("#box-clip").hide();
    }
  });

  $("#send-image").live("click", function () {
    $('#arq').attr("accept", ".jpeg,.jpg,.png,.mp4");
    document.getElementById('arq').click();
  });

  $("#send-document").live("click", function () {
    $('#arq').attr("accept", "*.*");
    document.getElementById('arq').click();
  });

  $("#quick-answer-send").live("click", function () {
    const mediaPlaying = document.getElementById("media-preview-media");
    if (mediaPlaying && typeof mediaPlaying.pause === "function") {
      mediaPlaying.pause();
      mediaPlaying.currentTime = 0;
      mediaPlaying.src = "";
      mediaPlaying.load();
    }

    $("#quick-answer-send").attr("disabled", true);

    var ta_id = messenger.Chat.key_remote_id;
    var caption = $("#caption-quick-reply").val();
    var url = $("#media-preview-media").attr("file-to-send");
    var media_title = $("#media-preview-media").attr("media_title");
    var media_size = $("#media-preview-media").attr("media_size");
    var media_duration = $("#media-preview-media").attr("media_duration");
    var mimetype = $("#media-preview-media").attr("mimetype");
    let key_id = Util.makeId();

    $("#" + messenger.Chat.token).show();

    switch (mimetype) {
      case "image/jpeg":
      case "image/jpg":
      case "image/png":
        ta.message.sendImage(ta_id, caption, url, mimetype, media_size, '');
        break;
      case "application/pdf":
        ta.message.sendDocument(ta_id, key_id, media_title, media_size, 0, url, mimetype, '');
        break;
      case "video/mp4":
        ta.message.sendVideo(ta_id, key_id, media_title, media_duration, media_size, mimetype, url, '');
        break;
      case "audio/ogg":
        ta.message.sendAudio(ta_id, mimetype, 0, media_duration, url);
        break;
    }

    $(".quick-answer").hide();
    $(".input").show();
    $(".input-text").html("");
    $(".input-text").focus();
    $("#caption-quick-reply").val("");
    $("#quick-answer-send").attr("disabled", false);
  });

  $("#contact-add").live("click", function () {

    this.disabled = true;

    $("#modal .box .items").html("");
    $("#settings_tooltip").hide();

    ta.chat.queryParamsContact();
  });

  $('body').on("click", ".contact-name", function () {

    contact_name_view = "";
    contact_number_view = "";

    let id_name = this.id;

    contact_name_view = $("#" + id_name).text();
    contact_number_view = $("#" + id_name).parent("div").find(".button").find("input").attr("name");

    $("#modal .box .items").html("");

    let img = document.createElement("img");
    img.src = "assets/img/avatar.svg";
    img.style.width = "44px";

    let lbName = document.createElement("span");
    lbName.textContent = contact_name_view;
    lbName.style.cssFloat = "left";
    lbName.style.width = "100%";
    lbName.style.left = "10px";
    lbName.style.fontFamily = "sans-serif";
    lbName.style.fontSize = "16px";
    lbName.style.marginTop = "-40px";
    lbName.style.marginLeft = "60px";

    let lbNumber = document.createElement("span");

    let regExp = /[a-zA-Z]/g;
    let str = contact_number_view;

    if (regExp.test(str)) {
      lbNumber.textContent = contact_number_view;
      lbNumber.style.fontStyle = "italic";
    } else {
      lbNumber.textContent = "+55  " + contact_number_view;
    }

    lbNumber.style.cssFloat = "left";
    lbNumber.style.width = "100%";
    lbNumber.style.fontFamily = "sans-serif";
    lbNumber.style.fontSize = "17px";
    lbNumber.style.marginTop = "20px";

    let lbTel = document.createElement("span")
    lbTel.textContent = "Tel";
    lbTel.style.cssFloat = "left";;
    lbTel.style.width = "100%";
    lbTel.style.fontFamily = "serif";
    lbTel.style.fontSize = "17px";
    lbTel.style.marginTop = "-8px";
    lbTel.style.color = "rgb(66 183 76)";

    let btnCancel = document.createElement("input");
    btnCancel.type = "button";
    btnCancel.className = "close-view";
    btnCancel.value = GLOBAL_LANG.messenger_contact_card_btn_close;
    btnCancel.style.float = "right";
    btnCancel.style.border = "1px solid rgb(50, 153, 230)";
    btnCancel.style.width = "100px";

    $("#modal .box .items").append(img);
    $("#modal .box .items").append(lbName);
    $("#modal .box .items").append(lbNumber);
    $("#modal .box .items").append(lbTel);
    $("#modal .box .items").append(btnCancel);
    $("#modal .box .head span").html(`<i class="fas fa-id-badge"></i> ${GLOBAL_LANG.messenger_contact_card_view}`);

    $("#modal").show();
    $(".number").mask("(99)99999-999?9");
    $(".name").focus();

  });

  $('body').on("click", ".contact-add-chat", function () {
    // this.disabled = true
    let id = this.id;
    $("#" + id).attr("value", GLOBAL_LANG.messenger_loading_ellipsis);

    contact_name = "";
    contact_number = "";

    let numberContact = "55" + this.name.replace("-", "");
    ta.chat.open(numberContact);

    contact_name = $("#" + this.id).parent().parent().text();
    contact_number = this.name;
  });

  $(".ok").live("click", function () {

    if (document.querySelector(".iti__active") != null) {
      this.disabled = true;
      let ddi = document.querySelector(".iti__active .iti__dial-code").innerText.replace("+", "");
      let name = $("#contact_name").val();
      let number = ddi + $("#contact_number").val().replace(/[^\d]+/g, '');
      let user_key_remote_id = $('#user_key_remote_id').val();
      let channel_id = $("#channel_id").val();

      if (name.length > 0 && number.length < 21) {
        localStorage.setItem("lastSelectedFlag", `+${ddi}`);
        ta.contact.Exist(number, name, user_key_remote_id, channel_id);
      }

      closeModal()
    } else {
      alert(GLOBAL_LANG.messenger_contact_alert_country_select);
    }
  });

  $("#chat-trans").live("click", function () {
    showTransferModal()
  });

  $("#chat-ticket").live("click", function () {
    this.disabled = true;
    ta.ticket.queryTicket();
  });

  $("#btn-ticket-ok").live("click", function () {
    let ticket_type = $(".select-ticket-type").val();
    let ticket_status = $(".select-ticket-status").val();
    let companty = $(".select-ticket-company").val();
    let subtype = $(".select-ticket-subtype").val();
    if (ticket_type != 0 && ticket_status != 0 && companty != 0) {
      ta.ticket.add(ticket_type, ticket_status, companty, subtype, $("#comment").val());
      ta.contact.queryInfo();
      closeModal()
    } else {
      if (companty == 0) {
        alert(GLOBAL_LANG.messenger_modal_ticket_alert_company);
      } else if (ticket_type == 0) {
        alert(GLOBAL_LANG.messenger_modal_ticket_alert_ticket_type);
      } else if (ticket_status == 0) {
        alert(GLOBAL_LANG.messenger_modal_ticket_alert_ticket_status);
      }
    }

  });

  $("#btn-ticket-edit").live("click", function () {
    let ticket_type = $(".select-ticket-type").val();
    let ticket_status = $(".select-ticket-status").val();
    let companty = $(".select-ticket-company").val();
    let subtype = $(".select-ticket-subtype").val();
    if (ticket_type != 0 && ticket_status != 0 && companty != 0) {
      ta.ticket.edit(ticket_id, ticket_type, ticket_status, companty, subtype, $("#comment").val());
      ta.contact.queryInfo();
      closeModal()
    } else {
      if (companty == 0) {
        alert(GLOBAL_LANG.messenger_modal_ticket_alert_company);
      } else if (ticket_type == 0) {
        alert(GLOBAL_LANG.messenger_modal_ticket_alert_ticket_type);
      } else if (ticket_status == 0) {
        alert(GLOBAL_LANG.messenger_modal_ticket_alert_ticket_status);
      }
    }
  });

  $(".ticket .items .item").live("click", function () {
    ticket_id = $(this).attr("data-content");
    ta.ticket.queryInfoTicket(ticket_id);
  });

  $(".product .items .item").live("click", function () {

    product_id = $(this).attr("data-content");
    media_url = $(this).attr("data-media_url");
    description = $(this).attr("data-description");

    Util.toDataURL(
      media_url,
      function (dataUrl) {
        var canvas = document.getElementById("clipboard");
        var ctx = canvas.getContext("2d");
        var image = document.createElement("img");
        image.onload = function () {
          canvas.width = this.width;
          canvas.height = this.height;
          ctx.drawImage(image, 0, 0);
        };
        image.src = dataUrl;
      }
    );

    $("#caption").val(description);
    $(".input").hide();
    $(".product").hide();
    $("#" + messenger.Chat.token).hide();
    $(".emojipicker").hide();
    $(".clipboard").show();
    $("#caption").focus();
  });


  $("#contact-edit").live("click", function () {
    this.disabled = true;
    dialogUserBasicEdit();
  });


  $("#btn-contact-edit-ok").live("click", function () {

    if ($("#edit-name").val().trim() === '') {
      alert(`${GLOBAL_LANG.messenger_alert_name_contact}`)
    } else {

      ta.contact.change(
        $("#edit-name").val(),
        $("#edit-email").val()
      );
      closeModal()
    }
  });


  $('#alert_link, .close-button').live('click', () => {

    ta.chat.updatePaymentMessage(messenger.Chat.key_remote_id.split("-")[1]);
    $('#alert_container').empty();
  });


  $("#list-active .item").live("click", function () {
    if ($('#ok-record').is(':visible'))
      cancelRecording();

    const { last_message, last_info } = lastSelectedItem();

    unblock();
    activeChatNow();

    $("#alert-credit-minimum").hide();
    $(".chat")[0].style = "z-index: 1; height: calc(100% - 59px);";

    $(".option .note-contact").show();
    $(".option #optionGroupOne").show();
    $(".option #labelContact").css("display", "flex");
    $("#chat-attendence").hide();
    $("#chat-waiting").show();

    $("#more").show();
    $("#email").val("");
    $(".reply-message").hide();
    $("#settings_tooltip").hide();
    $(".span-search-empty").remove();
    $("#bottomEntryRectangle").hide();
    $(".search-input").attr("value", "");
    $("#load_search").find("i").remove();
    $(".span-searched-message").remove();
    $(".emojipicker").css("display", "none");
    $('#md_' + this.id).parent().find('.icone').css({ 'margin-right': 0 });
    $('#ball_' + this.id).parent().find('.iconFixar');
    $("#ball_" + this.id).addClass('checkModal');
    $(".itemSelected").removeClass("itemSelected");
    $(".selectedArrow").removeClass("selectedArrow");
    $("#" + this.id).addClass("itemSelected");
    $('#close-message-forward').trigger('click');
    $("#box-clip").hide();

    $('#alert_container').empty();

    messenger.Chat.hide();
    messenger.Chat.selected = this.id;
    messenger.Chat.show();
    quoted_id = "";

    $(".messenger .right .chat").css("transition", "none");
    setTimeout(() => $(".messenger .right .chat").css("transition", "0.3s ease-in"), 500);

    if (last_message && last_info) {
      last_message.innerHTML = messenger.ChatList.setLastMessage(last_info);
    }

    info = messenger.ChatList.find(this.id);

    if (alertCloseChat(info)) return;

    if (info.type == 16 || info.type == 12) {

      let channel_id;

      for (let i = 0; i < messenger.Chats.length; i++) {

        if (messenger.Chats[i].hash === this.id) {
          channel_id = messenger.Chats[i].channel_id;
        }
      }

      ta.chat.queryCreditConversation(channel_id);
      setTimeout(() => ta.chat.queryPaymentMessage(channel_id), 5000);
      localStorage.setItem('last_timestamp_chat', info.last_timestamp_client);
      localStorage.setItem('channel_id', channel_id);

    } else {
      $(".inputMsg").hide();
      document.getElementById("bottomEntryRectangle").style.display = "flex";
    }

    if ($(".window__gallery").is(":visible")) {
      $(".window__option").hide();
      $(".window__search").hide();
      $(".window__gallery").hide();
      $(".messenger .right")[0].style = "width: calc(100% - 360px)";
      setTimeout(() => contactFullName(), 200);
    }

    // verifica a algumas janelas abaixo está aberta para atualizar os dados
    if ($(".window__search").is(":visible") || $(".window__option").is(":visible")) {

      const push_name = $("#compressed_contact_name").text();

      ta.chat.queryPreviewGallery();
      decreaseFullName();

      $(".span-name-user-search").remove();
      $(".list-message").css({ "text-align": "center" });
      $(".list-message").prepend(`<span class="span-name-user-search">${GLOBAL_LANG.messenger_window_search_span_search_with.replace("{{name}}", messenger.Chat.push_name)}</span>`);
    } else {
      contactFullName();
    }

    $(".input-search-contact").val("");
    scrollChatMessenger();
    removeFieldAI();
    showIconAI();
  });


  $("#list-active .item .icone").live("click", function (e) {
    e.stopPropagation('onclick');
  });


  $("#list-internal .item .icone").live("click", function (e) {
    e.stopPropagation('onclick');
  });


  $("#list-wait .item .icone").live("click", function (e) {
    e.stopPropagation('onclick');
  });


  $("#list-internal .item").live("click", function () {
    if ($('#ok-record').is(':visible'))
      cancelRecording();

    const { last_message, last_info } = lastSelectedItem();

    unblock();
    activeChatNow();

    document.getElementById("bottomEntryRectangle").style.display = "flex";
    $("#alert-credit-minimum").hide();

    $(".option .note-contact").hide();
    $(".option #optionGroupOne").hide();
    $(".option #labelContact").hide();
    $("#chat-attendence").hide();
    $("#chat-waiting").show();

    $(".chat")[0].style = "z-index: 1; height: calc(100% - 59px);";
    $(".reply-message").hide();
    $("#settings_tooltip").hide();
    $(".span-search-empty").remove();
    $(".search-input").attr("value", "");
    $("#load_search").find("i").remove();
    $(".span-searched-message").remove();
    $(".emojipicker").css("display", "none");
    $('#md_' + this.id).parent().find('.icone').css({ 'margin-right': 0 })
    $('#ball_' + this.id).parent().find('.iconFixar');
    $("#ball_" + this.id).addClass('checkModal');
    $(".itemSelected").removeClass("itemSelected");
    $("#" + this.id).addClass("itemSelected");
    $('#close-message-forward').trigger('click');
    $("#box-clip").hide();

    if ($(".media-preview").is(':visible')) {
      $(".media-preview").hide();
      $(".input-text").html("");
    }

    if ($(".quick").is(':visible')) {
      $(".quick").hide();
      $(".input-text").html("");
    }

    messenger.Chat.hide();
    messenger.Chat.selected = this.id;
    messenger.Chat.show();
    quoted_id = "";

    $(".messenger .right .chat").css("transition", "none");
    setTimeout(() => $(".messenger .right .chat").css("transition", "0.3s ease-in"), 500);

    if (last_message && last_info) {
      last_message.innerHTML = messenger.ChatList.setLastMessage(last_info);
    }

    if ($(".window__gallery").is(":visible")) {
      $(".window__option").hide();
      $(".window__search").hide();
      $(".window__gallery").hide();
      $(".messenger .right")[0].style = "width: calc(100% - 360px)";
      setTimeout(() => contactFullName(), 200);
    }

    // verifica a algumas janelas abaixo está aberta para atualizar os dados
    if ($(".window__search").is(":visible") || $(".window__option").is(":visible")) {

      const push_name = $("#compressed_contact_name").text();

      ta.chat.queryPreviewGallery();
      decreaseFullName();

      $(".span-name-user-search").remove();
      $(".list-message").css({ "text-align": "center" });
      $(".list-message").prepend(`<span class="span-name-user-search">${GLOBAL_LANG.messenger_window_search_span_search_with.replace("{{name}}", push_name)}</span>`);
    } else {
      contactFullName();
    }

    $(".input-search-contact").val("");
    scrollChatMessenger();
    removeFieldAI();
    showIconAI();
  });


  $("#list-wait .item").live("click", function () {
    if ($('#ok-record').is(':visible'))
      cancelRecording();

    const { last_message, last_info } = lastSelectedItem();

    unblock();
    activeChatNow();

    $("#alert-credit-minimum").hide();
    $(".chat")[0].style = "z-index: 1;height: calc(100% - 59px);";

    $(".option .note-contact").show();
    $(".option #optionGroupOne").show();
    $(".option #labelContact").css("display", "flex");
    $("#chat-waiting").hide();
    $("#chat-attendence").show();

    $("#email").val("");
    $(".reply-message").hide();
    $("#settings_tooltip").hide();
    $(".span-search-empty").remove();
    $("#bottomEntryRectangle").hide();
    $(".search-input").attr("value", "");
    $("#load_search").find("i").remove();
    $(".span-searched-message").remove();
    $(".emojipicker").css("display", "none");
    $('#md_' + this.id).parent().find('.icone').css({ 'margin-right': 0 });
    $('#ball_' + this.id).parent().find('.iconFixar');
    $("#ball_" + this.id).addClass('checkModal');
    $(".itemSelected").removeClass("itemSelected");
    $("#" + this.id).addClass("itemSelected");
    $('#close-message-forward').trigger('click');
    $("#box-clip").hide();

    $('#alert_container').empty();

    messenger.Chat.hide();
    messenger.Chat.selected = this.id;
    messenger.Chat.show();

    $(".messenger .right .chat").css("transition", "none");
    setTimeout(() => $(".messenger .right .chat").css("transition", "0.3s ease-in"), 500);

    if (last_message && last_info) {
      last_message.innerHTML = messenger.ChatList.setLastMessage(last_info);
    }

    info = messenger.ChatList.find(this.id);

    if (info.type == 16) {

      let channel_id;

      for (let i = 0; i < messenger.Chats.length; i++) {

        if (messenger.Chats[i].hash === this.id) {
          channel_id = messenger.Chats[i].channel_id;
        }
      }

      ta.chat.queryCreditConversation(channel_id);
      setTimeout(() => ta.chat.queryPaymentMessage(channel_id), 5000);
      localStorage.setItem('last_timestamp_chat', info.last_timestamp_client);

    } else {
      document.getElementById("bottomEntryRectangle").style.display = "flex";
    }

    if ($(".window__gallery").is(":visible")) {
      $(".window__option").hide();
      $(".window__search").hide();
      $(".window__gallery").hide();
      $(".messenger .right")[0].style = "width: calc(100% - 360px)";
      setTimeout(() => contactFullName(), 200);
    }

    // verifica a algumas janelas abaixo está aberta para atualizar os dados
    if ($(".window__search").is(":visible") || $(".window__option").is(":visible")) {

      const push_name = $("#compressed_contact_name").text();

      ta.chat.queryPreviewGallery();
      decreaseFullName();

      $(".span-name-user-search").remove();
      $(".list-message").css({ "text-align": "center" });
      $(".list-message").prepend(`<span class="span-name-user-search">${GLOBAL_LANG.messenger_window_search_span_search_with.replace("{{name}}", push_name)}</span>`);
    } else {
      contactFullName();
    }

    $(".input-search-contact").val("");
    scrollChatMessenger();
    removeFieldAI();
    showIconAI();
  });


  $("#chat-waiting").live("click", function () {
    $('#md_' + messenger.Chat.selected).parent().find('.iconFixar').removeClass('fixed')
    $('#md_' + messenger.Chat.selected).parent().find('.iconFixar').hide()
    messenger.Chat.wait();
    messenger.ChatList.updateCountView();
  });

  $("#chat-attendence").live("click", function () {
    $('#md_' + messenger.Chat.selected).parent().find('.iconFixar').removeClass('fixed')
    $('#md_' + messenger.Chat.selected).parent().find('.iconFixar').hide()
    messenger.Chat.attendance();
  });
  $(".label-contact-tag").live("click", function () {
    ta.label.queryLabel();
  });


  $(".quick .items .item").live("click", function () {
    const type = $(this).attr("data-type");

    if (type === "text") {
      $(".quick").hide();
      $(".input-text").html($(this).attr("data-content"));

      const el = document.querySelector(".input-text");
      const range = document.createRange();
      const sel = window.getSelection();
      range.selectNodeContents(el);
      range.collapse(false);
      sel.removeAllRanges();
      sel.addRange(range);
      el.focus();
    }
  });


  $(".quick .head span").live("click", function () {
    $(".quick").hide();
  });


  $(".product .head span").live("click", function () {
    $(".product").hide();
  });


  $(".media-preview-close").live("click", function () {
    $(".media-preview").hide();
    $("#" + messenger.Chat.token).show();
    $(".input-text").html("");
    $(".input").show();
  });


  $("#select-label-confirm").live("click", function () {

    let first = 0;
    let ids = 0, color = "", name = "";

    for (let i = 0; i < $(".select.select-label").length; i++) {
      if ($("#" + $(".select.select-label")[i].id + " .checkbox").prop("checked") == true) {

        if (ids == 0) ids = $(".select.select-label")[i].id; else ids += "," + $(".select.select-label")[i].id;
        if (color == "") color = $("#" + $(".select.select-label")[i].id).data('color'); else color += "," + $("#" + $(".select.select-label")[i].id).data('color');
        if (name == "") name = $("#" + $(".select.select-label")[i].id).data('name'); else name += "," + $("#" + $(".select.select-label")[i].id).data('name');
      }
    }

    ta.label.set({ ids, labels_name: name, labels_color: color });

    $("#" + messenger.Chat.selected + " .body .short_name svg").css("fill", $("#" + first + " .color svg").css('fill'));

    for (let i = 0; i < messenger.Chats.length; i++) {
      if (messenger.Chats[i].key_remote_id == messenger.Chat.key_remote_id) {
        messenger.Chats[i].labels_name = name;
        messenger.Chats[i].label_color = color;
        messenger.Chat.show();
      }
    }

    if (ids == "") {
      $("#" + messenger.Chat.selected + " .body .short_name svg").hide();
    } else {
      $("#" + messenger.Chat.selected + " .body .short_name svg").show();
    }

    messenger.Chat.show();

    $("#bgBoxMessenger").remove();
    $(".def-modal").remove();
  });

  $(".select-label").live("click", function () {

    if ($("#lb_" + this.id).hasClass("mark") == false) {
      $("#lb_" + this.id).attr("checked", true);
      $("#lb_" + this.id).addClass("mark");
    } else {
      $("#lb_" + this.id).attr("checked", false);
      $("#lb_" + this.id).removeClass("mark");
    }
  });

  $("#clipboard-send").live("click", function () {

    $("#clipboard-send").attr("disabled", true);

    var canvasData = document.getElementById("clipboard").toDataURL("image/jpeg");
    var caption = $("#caption").val();
    $("#" + messenger.Chat.token).show();

    var data = new FormData();
    data.append("base64", canvasData);
    data.append("media_type", "image");
    data.append("ta_id", messenger.Chat.key_remote_id);

    $.ajax({
      type: "POST",
      url: "https://files.talkall.com.br:3000/upload/base64",
      data: data,
      success: function (json) {
        switch (json.mimetype) {
          case "image/jpeg":
            ta.message.sendImage(json.ta_id, caption, json.url, json.mimetype, json.size, json.thumbnail);
            break;
        }

        $(".clipboard").hide();
        $(".input").show();
        $(".input-text").html("");
        $(".input-text").focus();
        $("#clipboard-send").attr("disabled", false);
      },
      cache: false,
      contentType: false,
      processData: false,
      xhr: function () {
        var xmlHttp = $.ajaxSettings.xhr();
        xmlHttp.upload.onprogress = function (event) {
          // console.log(event);
        };
        return xmlHttp;
      }
    });
  });

  $("#caption").live("keypress", function (e) {

    if (e.keyCode == 13) {

      var canvasData = document.getElementById("clipboard").toDataURL("image/jpeg");
      var caption = $("#caption").val();

      var data = new FormData();
      data.append("base64", canvasData);
      data.append("media_type", "image");
      data.append("ta_id", messenger.Chat.key_remote_id);

      $.ajax({
        type: "POST",
        url: "https://files.talkall.com.br:3000/upload/base64",
        data: data,
        success: function (json) {
          switch (json.mimetype) {
            case "image/jpeg":
              ta.message.sendImage(json.ta_id, caption, json.url, json.mimetype, json.size, json.thumbnail);
              break;
          }
          $(".clipboard").hide();
          $("#" + messenger.Chat.token).show();
          $(".input").show();
          $(".input-text").html("");
          $(".input-text").focus();
        },
        cache: false,
        contentType: false,
        processData: false,
        xhr: function () {
          var xmlHttp = $.ajaxSettings.xhr();
          xmlHttp.upload.onprogress = function (event) {
            // console.log(event);
          };
          return xmlHttp;
        }
      });
    }
  });


  $(".right .head .caption span").live("click", function () {

    if ($(".option")[0].style.display == "none") {

      ta.chat.queryPreviewGallery();
      switchToDark("windowOption");
      decreaseFullName();

      $("#previewGallery").find(".container").remove();

      $(".messenger").find(".colors").find(".body").removeClass("_g2");

      $(".option")[0].style.display = "block";

      $(".messenger .right")[0].style = "width: calc(100% - 765px)";
      $(".messenger .right")[0].style.display = "block";
    } else {
      setTimeout(() => contactFullName(), 200);
      $(".option")[0].style.display = "none";
      $(".messenger .right")[0].style = "width: calc(100% - 360px)";
      $(".messenger .right")[0].style.display = "block";

    }

    if ($("#infoNoteContact").val() === "") {
      $(".note-contact").find(".subtitle").hide();
      $(".note-contact").find(".box-note").hide();
      $(".note-contact").find(".add-note-contact").show();
    } else {
      $(".note-contact").find(".subtitle").show();
      $(".note-contact").find(".box-note").hide();
      $("#infoNoteContact").val($("#note").val());
      $(".note-contact").find(".add-note-contact").hide();
    }
  });


  $("#profileMessenger").live("click", function () {

    $("#leftMessenger").hide();
    $("#left-settings-profile").show();
    loadPerfil(ta.config.ta);
    previewsProfile(ta.config.ta);
  });


  $(document).on("click", "#imgProfile", function () {
    $("#bgboxProfileUser").fadeIn("fast");
    $("#modalProfileUser").fadeIn("fast");

    $("#imgPreviewsProfile").show();
    previewsProfile(ta.config.ta);
  });


  $(document).on("click", "#bgboxProfileUser", function () {
    $("#bgboxProfileUser").fadeOut("fast");
    $("#modalProfileUser").fadeOut("fast");
  });


  $("#option-exit").live("click", function () {
    $("#bgboxProfileUser").fadeIn("fast");
    $("#modalProfileUser").fadeIn("fast");
  });


  $("#modalTean").live("click", function () {
    ta.teamTalkall.queryTeam();
  });


  $("#bgboxTeam").live("click", function () {
    $("#bgboxTeam").fadeOut("fast");
    $("#modalQueryTeam").fadeOut("fast");
  });


  $("#closeTeam").live("click", function () {
    $("#bgboxTeam").fadeOut("fast");
    $("#modalQueryTeam").fadeOut("fast");
  });


  $("#openModalClose").live("click", function () {
    modalCloseMessenger();
  });


  $("#bgboxCloseMessenger").live("click", function () {
    $("#bgboxCloseMessenger").fadeOut("fast");
    $("#modalCloseMessenger").fadeOut("fast");
  });


  $("#cancelModalClose").live("click", function () {
    $("#bgboxCloseMessenger").fadeOut("fast");
    $("#modalCloseMessenger").fadeOut("fast");
  });


  $(".team-item").live("click", function () {

    ta.chat.open(this.id);

    $("#bgboxTeam").fadeOut("fast");
    $("#modalQueryTeam").fadeOut("fast");

    $("#chat-active span").css("border-bottom", "0px");
    $("#chat-comment span").css("border-bottom", "0px");
    $("#chat-wait span").css("border-bottom", "0px");
    $("#chat-internal span").css("border-bottom", "4px solid #2263d3");

    $("#chat-internal .title").css("color", "");
    $("#chat-internal span").css("color", "");

    $("#chat-wait .title").css("color", "#9a9a9a");
    $("#chat-wait span").css("color", "#9a9a9a");

    $("#chat-active .title").css("color", "#9a9a9a");
    $("#chat-active span").css("color", "#9a9a9a");

    $("#chat-comment .title").css("color", "#9a9a9a");
    $("#chat-comment span").css("color", "#9a9a9a");

    $("#list-active").hide();
    $("#list-internal").show();
    $("#list-wait").hide();
  });


  $("#mod-dark-messenger").live("click", function () {

    $("#left-settings").hide();
    $("#settings_tooltip").hide();
    $("#sub-settings-tema").show();
  });


  $("#checkedClearSpan").live("click", function () {
    $("#checkedClear").attr("checked", true);
    $("#checkedDark").attr("checked", false);
    if (bNight == true) {
      bNight = false;
      localStorage.setItem("night", false);
      LoadMode();
    }
  });


  $("#checkedDarkSpan").live("click", function () {
    $("#checkedDark").attr("checked", true);
    $("#checkedClear").attr("checked", false);
    if (bNight == false) {
      bNight = true;
      localStorage.setItem("night", true);
      LoadMode();
    }
  });


  $("#checkedClear").live("click", function () {

    if ($("#checkedClear").attr("checked") == "checked") {
      $("#checkedDark").attr("checked", false);
      if (bNight) {
        bNight = false;
        localStorage.setItem("night", false);
        LoadMode();
      }
    } else
      $("#checkedClear").attr("checked", true);
  });


  $("#checkedDark").live("click", function () {

    if ($("#checkedDark").attr("checked") == "checked") {
      $("#checkedClear").attr("checked", false);
      if (!bNight) {
        bNight = true;
        localStorage.setItem("night", true);
        LoadMode();
      }
    } else
      $("#checkedDark").attr("checked", true);
  });


  $("#font-messenger").live("click", function () {

    $("#left-settings").hide();
    $("#settings_tooltip").hide();
    $("#sub-settings-font").show();
  });


  $("#checkedSmallSpan").live("click", function () {
    $("#checkedSmall").attr("checked", true);
    $("#checkedAverage").attr("checked", false);
    $("#checkedBig").attr("checked", false);
    $("#checkedXtraBig").attr("checked", false);
    $(".textMessage").css({ "font-size": "12px" });
    $(".locationName").css({ "font-size": "12px" });
    $(".LocationMessage").css({ "font-size": "12px" });
    $(".is-reply").css({ "font-size": "12px" });
    $(".revoke.Left").css({ "font-size": "12px" });
    $(".ImageMessage").css({ "font-size": "12px" });
    $(".VideoMessage").css({ "font-size": "12px" });
    $(".StoryMentionMessage.left").css({ "font-size": "11px" });
    $(".btnTemplate").css({ "font-size": "11px" });
    $(".btnInteractive").css({ "font-size": "11px" });
    $(".interactiveFooter").css({ "font-size": "11px" });
    $(".locationAddress").css({ "font-size": "11px" });
    $(".interactiveHeader").css({ "font-size": "13px" });
    let fontSizeHeader = "13";
    localStorage.setItem("fontInteractiveHeader", fontSizeHeader);
    let fontSizeBody = "12";
    localStorage.setItem("fontMessenger", fontSizeBody);
    localStorage.setItem("fontIsReplyStory", fontSizeBody);
    localStorage.setItem("fontRevokeMessage", fontSizeBody);
    let fontSizeFooter = "11";
    localStorage.setItem("fontStoryMentionMessage", fontSizeFooter);
    localStorage.setItem("fontBtnTemplateMessage", fontSizeFooter);
    localStorage.setItem("fontInteractiveFooter", fontSizeFooter);
  });

  $("#checkedAverageSpan").live("click", function () {
    $("#checkedSmall").attr("checked", false);
    $("#checkedAverage").attr("checked", true);
    $("#checkedBig").attr("checked", false);
    $("#checkedXtraBig").attr("checked", false);
    $(".textMessage").css({ "font-size": "14.2px" });
    $(".locationName").css({ "font-size": "14.2px" });
    $(".LocationMessage").css({ "font-size": "14.2px" });
    $(".is-reply").css({ "font-size": "14.2px" });
    $(".revoke.Left").css({ "font-size": "14.2px" });
    $(".ImageMessage").css({ "font-size": "14.2px" });
    $(".VideoMessage").css({ "font-size": "14.2px" });
    $(".StoryMentionMessage.left").css({ "font-size": "12.8px" });
    $(".btnTemplate").css({ "font-size": "12.8px" });
    $(".btnInteractive").css({ "font-size": "12.8px" });
    $(".interactiveFooter").css({ "font-size": "12.8px" });
    $(".locationAddress").css({ "font-size": "12.8px" });
    $(".interactiveHeader").css({ "font-size": "15px" });
    let fontSizeHeader = "15";
    localStorage.setItem("fontInteractiveHeader", fontSizeHeader);
    let fontSizeBody = "14.2";
    localStorage.setItem("fontMessenger", fontSizeBody);
    localStorage.setItem("fontIsReplyStory", fontSizeBody);
    localStorage.setItem("fontRevokeMessage", fontSizeBody);
    let fontSizeFooter = "12.8";
    localStorage.setItem("fontStoryMentionMessage", fontSizeFooter);
    localStorage.setItem("fontBtnTemplateMessage", fontSizeFooter);
    localStorage.setItem("fontInteractiveFooter", fontSizeFooter);
  });

  $("#checkedBigSpan").live("click", function () {
    $("#checkedSmall").attr("checked", false);
    $("#checkedAverage").attr("checked", false);
    $("#checkedBig").attr("checked", true);
    $("#checkedXtraBig").attr("checked", false);
    $(".textMessage").css({ "font-size": "17px" });
    $(".locationName").css({ "font-size": "17px" });
    $(".LocationMessage").css({ "font-size": "17px" });
    $(".is-reply").css({ "font-size": "17px" });
    $(".revoke.Left").css({ "font-size": "17px" });
    $(".ImageMessage").css({ "font-size": "17px" });
    $(".VideoMessage").css({ "font-size": "17px" });
    $(".StoryMentionMessage.left").css({ "font-size": "15px" });
    $(".btnTemplate").css({ "font-size": "15px" });
    $(".btnInteractive").css({ "font-size": "15px" });
    $(".interactiveFooter").css({ "font-size": "15px" });
    $(".locationAddress").css({ "font-size": "15px" });
    $(".interactiveHeader").css({ "font-size": "18px" });
    let fontSizeHeader = "18";
    localStorage.setItem("fontInteractiveHeader", fontSizeHeader);
    let fontSizeBody = "17";
    localStorage.setItem("fontMessenger", fontSizeBody);
    localStorage.setItem("fontIsReplyStory", fontSizeBody);
    localStorage.setItem("fontRevokeMessage", fontSizeBody);
    let fontSizeFooter = "15";
    localStorage.setItem("fontStoryMentionMessage", fontSizeFooter);
    localStorage.setItem("fontBtnTemplateMessage", fontSizeFooter);
    localStorage.setItem("fontInteractiveFooter", fontSizeFooter);
  });

  $("#checkedExtraBigSpan").live("click", function () {
    $("#checkedSmall").attr("checked", false);
    $("#checkedAverage").attr("checked", false);
    $("#checkedBig").attr("checked", false);
    $("#checkedXtraBig").attr("checked", true);
    $(".textMessage").css({ "font-size": "20px" });
    $(".locationName").css({ "font-size": "20px" });
    $(".LocationMessage").css({ "font-size": "20px" });
    $(".is-reply").css({ "font-size": "20px" });
    $(".revoke.Left").css({ "font-size": "20px" });
    $(".ImageMessage").css({ "font-size": "20px" });
    $(".VideoMessage").css({ "font-size": "20px" });
    $(".StoryMentionMessage.left").css({ "font-size": "18px" });
    $(".btnTemplate").css({ "font-size": "18px" });
    $(".btnInteractive").css({ "font-size": "18px" });
    $(".interactiveFooter").css({ "font-size": "18px" });
    $(".locationAddress").css({ "font-size": "18px" });
    $(".interactiveHeader").css({ "font-size": "21px" });
    let fontSizeHeader = "21";
    localStorage.setItem("fontInteractiveHeader", fontSizeHeader);
    let fontSizeBody = "20";
    localStorage.setItem("fontMessenger", fontSizeBody);
    localStorage.setItem("fontIsReplyStory", fontSizeBody);
    localStorage.setItem("fontRevokeMessage", fontSizeBody);
    let fontSizeFooter = "18";
    localStorage.setItem("fontStoryMentionMessage", fontSizeFooter);
    localStorage.setItem("fontBtnTemplateMessage", fontSizeFooter);
    localStorage.setItem("fontInteractiveFooter", fontSizeFooter);
  });

  $("#checkedSmall").live("click", function () {
    $("#checkedSmall").attr("checked", true);
    $("#checkedAverage").attr("checked", false);
    $("#checkedBig").attr("checked", false);
    $("#checkedXtraBig").attr("checked", false);
    $(".textMessage").css({ "font-size": "12px" });
    $(".locationName").css({ "font-size": "12px" });
    $(".LocationMessage").css({ "font-size": "12px" });
    $(".is-reply").css({ "font-size": "12px" });
    $(".revoke.Left").css({ "font-size": "12px" });
    $(".ImageMessage").css({ "font-size": "12px" });
    $(".VideoMessage").css({ "font-size": "12px" });
    $(".StoryMentionMessage.left").css({ "font-size": "11px" });
    $(".btnTemplate").css({ "font-size": "11px" });
    $(".btnInteractive").css({ "font-size": "11px" });
    $(".interactiveFooter").css({ "font-size": "11px" });
    $(".locationAddress").css({ "font-size": "11px" });
    $(".interactiveHeader").css({ "font-size": "13px" });
    let fontSizeHeader = "13";
    localStorage.setItem("fontInteractiveHeader", fontSizeHeader);
    let fontSizeBody = "12";
    localStorage.setItem("fontMessenger", fontSizeBody);
    localStorage.setItem("fontIsReplyStory", fontSizeBody);
    localStorage.setItem("fontRevokeMessage", fontSizeBody);
    let fontSizeFooter = "11";
    localStorage.setItem("fontStoryMentionMessage", fontSizeFooter);
    localStorage.setItem("fontBtnTemplateMessage", fontSizeFooter);
    localStorage.setItem("fontInteractiveFooter", fontSizeFooter);
  });

  $("#checkedAverage").live("click", function () {
    $("#checkedSmall").attr("checked", false);
    $("#checkedAverage").attr("checked", true);
    $("#checkedBig").attr("checked", false);
    $("#checkedXtraBig").attr("checked", false);
    $(".textMessage").css({ "font-size": "14.2px" });
    $(".locationName").css({ "font-size": "14.2px" });
    $(".LocationMessage").css({ "font-size": "14.2px" });
    $(".is-reply").css({ "font-size": "14.2px" });
    $(".revoke.Left").css({ "font-size": "14.2px" });
    $(".ImageMessage").css({ "font-size": "14.2px" });
    $(".VideoMessage").css({ "font-size": "14.2px" });
    $(".StoryMentionMessage.left").css({ "font-size": "12.8px" });
    $(".btnTemplate").css({ "font-size": "12.8px" });
    $(".btnInteractive").css({ "font-size": "12.8px" });
    $(".interactiveFooter").css({ "font-size": "12.8px" });
    $(".locationAddress").css({ "font-size": "12.8px" });
    $(".interactiveHeader").css({ "font-size": "15px" });
    let fontSizeHeader = "15";
    localStorage.setItem("fontInteractiveHeader", fontSizeHeader);
    let fontSizeBody = "14.2";
    localStorage.setItem("fontMessenger", fontSizeBody);
    localStorage.setItem("fontIsReplyStory", fontSizeBody);
    localStorage.setItem("fontRevokeMessage", fontSizeBody);
    let fontSizeFooter = "12.8";
    localStorage.setItem("fontStoryMentionMessage", fontSizeFooter);
    localStorage.setItem("fontBtnTemplateMessage", fontSizeFooter);
    localStorage.setItem("fontInteractiveFooter", fontSizeFooter);
  });

  $("#checkedBig").live("click", function () {
    $("#checkedSmall").attr("checked", false);
    $("#checkedAverage").attr("checked", false);
    $("#checkedBig").attr("checked", true);
    $("#checkedXtraBig").attr("checked", false);
    $(".textMessage").css({ "font-size": "17px" });
    $(".locationName").css({ "font-size": "17px" });
    $(".LocationMessage").css({ "font-size": "17px" });
    $(".is-reply").css({ "font-size": "17px" });
    $(".revoke.Left").css({ "font-size": "17px" });
    $(".ImageMessage").css({ "font-size": "17px" });
    $(".VideoMessage").css({ "font-size": "17px" });
    $(".StoryMentionMessage.left").css({ "font-size": "15px" });
    $(".btnTemplate").css({ "font-size": "15px" });
    $(".btnInteractive").css({ "font-size": "15px" });
    $(".interactiveFooter").css({ "font-size": "15px" });
    $(".locationAddress").css({ "font-size": "15px" });
    $(".interactiveHeader").css({ "font-size": "18px" });
    let fontSizeHeader = "18";
    localStorage.setItem("fontInteractiveHeader", fontSizeHeader);
    let fontSizeBody = "17";
    localStorage.setItem("fontMessenger", fontSizeBody);
    localStorage.setItem("fontIsReplyStory", fontSizeBody);
    localStorage.setItem("fontRevokeMessage", fontSizeBody);
    let fontSizeFooter = "15";
    localStorage.setItem("fontStoryMentionMessage", fontSizeFooter);
    localStorage.setItem("fontBtnTemplateMessage", fontSizeFooter);
    localStorage.setItem("fontInteractiveFooter", fontSizeFooter);
  });

  $("#checkedXtraBig").live("click", function () {
    $("#checkedSmall").attr("checked", false);
    $("#checkedAverage").attr("checked", false);
    $("#checkedBig").attr("checked", false);
    $("#checkedXtraBig").attr("checked", true);
    $(".textMessage").css({ "font-size": "20px" });
    $(".locationName").css({ "font-size": "20px" });
    $(".LocationMessage").css({ "font-size": "20px" });
    $(".is-reply").css({ "font-size": "20px" });
    $(".revoke.Left").css({ "font-size": "20px" });
    $(".ImageMessage").css({ "font-size": "20px" });
    $(".VideoMessage").css({ "font-size": "20px" });
    $(".StoryMentionMessage.left").css({ "font-size": "18px" });
    $(".btnTemplate").css({ "font-size": "18px" });
    $(".btnInteractive").css({ "font-size": "18px" });
    $(".interactiveFooter").css({ "font-size": "18px" });
    $(".locationAddress").css({ "font-size": "18px" });
    $(".interactiveHeader").css({ "font-size": "21px" });
    let fontSizeHeader = "21";
    localStorage.setItem("fontInteractiveHeader", fontSizeHeader);
    let fontSizeBody = "20";
    localStorage.setItem("fontMessenger", fontSizeBody);
    localStorage.setItem("fontIsReplyStory", fontSizeBody);
    localStorage.setItem("fontRevokeMessage", fontSizeBody);
    let fontSizeFooter = "18";
    localStorage.setItem("fontStoryMentionMessage", fontSizeFooter);
    localStorage.setItem("fontBtnTemplateMessage", fontSizeFooter);
    localStorage.setItem("fontInteractiveFooter", fontSizeFooter);
  });


  $("#notification").live("click", function () {

    $("#left-settings").hide();
    $("#settings_tooltip").hide();
    $("#sub-settings-notification").show()

  });


  $("#wallpaper").on("click", function () {

    const color_form = document.getElementById("colorForm");
    color_form.innerHTML = "";

    const bckgds = bNight ? bgColor.dark : bgColor.light;

    const default_color = document.createElement("div");
    default_color.classList.add("btnWallpaper", "col-4", "def");
    default_color.innerHTML = GLOBAL_LANG.messenger_settings_submenu_wallpaper_default;
    color_form.appendChild(default_color);

    bckgds.forEach((color, i) => {
      const div = document.createElement("div");
      div.classList.add("btnWallpaper", "col-4");
      div.style.backgroundColor = color;
      color_form.appendChild(div);
    });

    $("#left-settings").hide();
    $("#settings_tooltip").hide();
    $("#sub-settings-color").show();

    restoreBgColor();
    enablePreviewBgColor();
  });


  $("#helpSettings").live("click", function () {

    $("#left-settings").hide();
    $("#settings_tooltip").hide();
    $("#sub-settings-help").show();
  });


  $("#shortcut").live("click", function () {

    $("#left-settings").hide();
    $("#settings_tooltip").hide();
    $("#sub-settings-shortcut").show();
  });


  $(".openSearch").live("click", function () {

    const name = $("#compressed_contact_name").text();

    $(".window__search #title_form").text(GLOBAL_LANG.messenger_window_search_title);
    $(".window__search .list-message").find(".item-searched").remove();
    $(".window__search .search-input").attr("placeholder", GLOBAL_LANG.messenger_window_search_placeholder);
    $(".window__search .search-input").val("");

    $(".window__search .span-search-empty").remove();
    $(".window__search .span-name-user-search").remove();

    $(".window__search .list-message").css({ "text-align": "center" });
    $(".window__search .list-message").prepend(`<span class="span-name-user-search">${GLOBAL_LANG.messenger_window_search_span_search_with.replace("{{name}}", name)}</span>`);

    $(".window__option")[0].style.display = "none";
    $(".window__gallery")[0].style.display = "none";
    $(".window__search")[0].style.display = "block";

    $(".messenger .right")[0].style = "width: calc(100% - 765px)";
    $(".messenger .right")[0].style.display = "block";
    decreaseFullName();
    switchToDark("windowSearch");
  });

  $("#openStarred").live("click", function () {

    let creation = 0;
    groupStarred = 1;
    checkDataStar = 0;
    creationGroup = "true";
    ta.chat.queryStarred(creation);

    $(".ongrups").remove();
    $("._selected").remove();
    $(".load_starred_footer").remove();
    $(".settings_starred").remove();
    $(".title_starred").remove();
    $(".none_starred").remove();

    localStorage.setItem("load_starred", "true");

    $(".window__option")[0].style.display = "none";
    $(".window__gallery")[0].style.display = "none";
    $(".window__search")[0].style.display = "none";
    $(".window__favorite")[0].style.display = "block";

    $("#starred_box").prepend(`<img src="./assets/img/loads/loading_1.gif" class="load_starred">`);
    $(".messenger .right")[0].style = "width: calc(100% - 765px)";
    $(".messenger .right")[0].style.display = "block";

    $(".window__favorite").find(".head").append(`
      <span class='title_starred'>${GLOBAL_LANG.messenger_window_favorite_title}</span>
      <div class='settings_starred right-icon'>
        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.0001 4C13.1047 4 14.0001 3.10457 14.0001 2C14.0001 0.89543 13.1047 0 12.0001 0C10.8956 0 10.0001 0.89543 10.0001 2C10.0001 3.10457 10.8956 4 12.0001 4Z"></path>
            <path d="M12.0001 14.0003C13.1047 14.0003 14.0001 13.1049 14.0001 12.0003C14.0001 10.8957 13.1047 10.0003 12.0001 10.0003C10.8956 10.0003 10.0001 10.8957 10.0001 12.0003C10.0001 13.1049 10.8956 14.0003 12.0001 14.0003Z"></path>
            <path d="M12.0001 23.9997C13.1047 23.9997 14.0001 23.1043 14.0001 21.9997C14.0001 20.8951 13.1047 19.9997 12.0001 19.9997C10.8956 19.9997 10.0001 20.8951 10.0001 21.9997C10.0001 23.1043 10.8956 23.9997 12.0001 23.9997Z"></path>
        </svg>
      </div>
    `);

    switchToDark("windowFavorite");

  });

  $(".openGallery").live("click", function () {

    ta.chat.queryGallery(0, "midia");
    $(".window__gallery .container-gallery").remove();

    $(".window__gallery .gridDocument").hide();
    $(".window__gallery .titleSelected").hide();
    $(".window__gallery #view-tem-gallery").hide();
    $(".window__gallery #arrow-download-gallery").hide();

    $(".window__option")[0].style.display = "none";
    $(".window__search")[0].style.display = "none";
    $(".window__gallery")[0].style.display = "block";

    $(".messenger .right")[0].style = "width: calc(100% - 765px)";
    $(".messenger .right")[0].style.display = "block";
    $(".window__gallery #col-gallery").prepend(`<img src="./assets/img/loads/loading_2.gif" class="load_gallery">`);
  });

  $(".submenu-media").on("click", function () {

    $(".window__gallery .submenu-media span").css("border-bottom", "4px solid #2263d3");
    $(".window__gallery .submenu-documents span").css("border-bottom", "0px");
    $(".window__gallery .submenu-media div").css("color", "#2263d3");
    $(".window__gallery .submenu-documents div").css("color", "#9a9a9a");

    ta.chat.queryGallery(0, "midia");

    $(".window__gallery .gridGallery").show();
    $(".window__gallery .gridDocument").hide();
    $(".window__gallery #col-gallery").html("");
    $(".window__gallery .load_gallery").remove();
    $(".window__gallery .container-documents").remove();

    $(".window__gallery .titleSelected").css({ "display": "none" });
    $(".window__gallery #view-tem-gallery").css({ "display": "none" });
    $(".window__gallery #arrow-download-gallery").css({ "display": "none" });

    $(".window__gallery #col-gallery").prepend(`<img src="./assets/img/loads/loading_2.gif" class="load_gallery">`);
  });


  $(".submenu-documents").live("click", function () {

    $(".window__gallery .submenu-documents span").css("border-bottom", "4px solid #2263d3");
    $(".window__gallery .submenu-media span").css("border-bottom", "0px");
    $(".window__gallery .submenu-documents div").css("color", "#2263d3");
    $(".window__gallery .submenu-media div").css("color", "#9a9a9a");

    ta.chat.queryGallery(0, "document");

    $(".window__gallery .gridGallery").hide();
    $(".window__gallery .gridDocument").show();
    $(".window__gallery .load_gallery").remove();
    $(".window__gallery .container-gallery").remove();
    $(".window__gallery .container-documents").remove();

    $(".window__gallery .titleSelected").css({ "display": "none" });
    $(".window__gallery #view-tem-gallery").css({ "display": "none" });
    $(".window__gallery #arrow-download-gallery").css({ "display": "none" });

    $(".window__gallery .icon-img-gallery").attr("class", "far fa-check-circle icon-img-gallery");
    $(".window__gallery .box").find(".img-gallery").css({ "width": "100%", "height": "100%", "padding": "-1px" });
    $(".window__gallery #col-gallery").prepend(`<img src="./assets/img/loads/loading_2.gif" class="load_gallery">`);
  });


  $(".starredParticipant .fas fa-chevron-right").on('click', () => {
    document.getElementById("close_settings_toolbar").click();
    $(".window__favorite")[0].style.display = "none";

  });


  $("#close_settings_toolbar, #close_settings_favorite").live('click', () => {
    $(".window__option").hide();
    $(".window__search").hide();
    $(".window__favorite").hide();
    $(".settings_starred").remove();

    $(".messenger .right")[0].style = "width: calc(100% - 360px)";
    $(".messenger .right")[0].style.display = "block";
    setTimeout(() => contactFullName(), 200);
  });


  $('#list-active .item').live('click', () => {

    if ($(".window__favorite").is(":visible") || $(".window__search").is(":visible")) {
      document.getElementById("close_settings_toolbar").click();
      $(".window__favorite")[0].style.display = "none";
      $(".window__search")[0].style.display = "none";
      $(".settings_starred").remove();
    }

    if ($(".media-preview").is(':visible')) {
      $(".media-preview").hide();
      $(".input-text").html("");
    }

    if ($(".quick").is(':visible')) {
      $(".quick").hide();
      $(".input-text").html("");
    }
  });


  $('#list-wait .item').live('click', () => {

    if ($(".window__favorite").is(":visible") || $(".window__search").is(":visible")) {

      document.getElementById("close_settings_toolbar").click();

      $(".window__favorite")[0].style.display = "none";
      $(".window__search")[0].style.display = "none";
      $(".settings_starred").remove();
    }

    if ($(".media-preview").is(':visible')) {
      $(".media-preview").hide();
      $(".input-text").html("");
    }

    if ($(".quick").is(':visible')) {
      $(".quick").hide();
      $(".input-text").html("");
    }
  });


  $('#list-internal .item').live('click', () => {

    if ($(".window__favorite").is(":visible") || $(".window__search").is(":visible")) {

      document.getElementById("close_settings_toolbar").click();

      $(".window__favorite")[0].style.display = "none";
      $(".window__search")[0].style.display = "none";
      $(".settings_starred").remove();
    }
  });


  $("#close-gallery").live("click", function () {
    $(".window__option")[0].style.display = "block";
    $(".window__gallery")[0].style.display = "none";
  });


  $("#close_settings_toolbar, #close_settings_search").live('click', () => {
    $(".window__option").hide();
    $(".window__search").hide();

    $(".messenger .right")[0].style = "width: calc(100% - 360px)";
    $(".messenger .right")[0].style.display = "block";
    setTimeout(() => contactFullName(), 200);
  });

  $("#setting-messenger").live("click", function () {

    $("#leftMessenger").hide();
    $("#left-settings").show();
  });

  $(".settings-return-icon").live("click", function () {
    $("#leftMessenger").show();
    $("#left-settings-profile").hide();
    $("#left-settings").hide();
    $("#settings_tooltip").hide();
  });

  $(".sub-settings-return-icon").live("click", function () {
    $("#left-settings").show();
    $("#settings_tooltip").hide();
    $("#sub-settings-color").hide();
    $("#sub-settings-tema").hide();
    $("#sub-settings-notification").hide();
    $("#sub-settings-shortcut").hide();
    $("#sub-settings-help").hide();
    $("#sub-settings-font").hide();
  });

  $("#option-close").live("click", function () {
    $(".option")[0].style.display = "none";
    $(".colors")[0].style.display = "none";
    $(".messenger .right")[0].style = "width: calc(100% - 360px)";
  });

  $(".textMessage").live({
    mouseenter: function (e) {
      if (this.parentElement.id != "") {
        $("#" + this.parentElement.id + " .dropdown").show();
        e.stopPropagation();
      }
    },
  });

  $(".AudioMessage").live({
    mouseenter: function (e) {
      if (this.parentElement.id != "") {
        $("#" + this.parentElement.id + " .dropdown").show();
        e.stopPropagation();
      }
    },
  });

  $(".ImageMessage").live({
    mouseenter: function (e) {
      if (this.parentElement.id != "") {
        $("#" + this.parentElement.id + " .dropdown").show();
        e.stopPropagation();
      }
    },
  });

  $(".DocumentMessage").live({
    mouseenter: function (e) {
      if (this.parentElement.id != "") {
        $("#" + this.parentElement.id + " .dropdown").show();
        e.stopPropagation();
      }
    },
  });

  $(".ZipMessage").live({
    mouseenter: function (e) {
      if (this.parentElement.id != "") {
        $("#" + this.parentElement.id + " .dropdown").show()
        e.stopPropagation();
      }
    },
  });

  $(".VideoMessage").live({
    mouseenter: function (e) {
      if (this.parentElement.id != "") {
        $("#" + this.parentElement.id + " .dropdown").show();
        e.stopPropagation();
      }
    },
  });

  $(".LocationMessage").live({
    mouseenter: function (e) {
      if (this.parentElement.id != "") {
        $("#" + this.parentElement.id + " .dropdown").show();
        e.stopPropagation();
      }
    },
  });

  $(".ContactMessage").live({
    mouseenter: function (e) {
      if (this.parentElement.id != "") {
        $("#" + this.parentElement.id + " .dropdown").show();
        e.stopPropagation();
      }
    },
  });

  $(".messages .item").live({
    mouseleave: function (e) {
      if (this.id != "") {
        $("#" + this.id + " .dropdown").hide();
        e.stopPropagation();
      }
    }
  });

  $("#revoke").live("click", function () {
    this.disabled = true;
    ta.message.revoke(item_id);

    quoted_id = item_id;
    let status = 2;

    if ($("#" + quoted_id).find("div").hasClass("right")) {
      ta.chat.starred(quoted_id, status, 2, "false");
    } else {
      ta.chat.starred(quoted_id, status, 1, "false");
    }

    $("#" + quoted_id).find("div").find(".bottom").find("i").remove();

    $(".popmenu").remove();
    $("#boxPopMenu").remove();
    $("[name=" + "_str" + quoted_id + "]").hide(300);

    if ($(".starredMessage").length <= 1) {

      $(".settings_starred").hide();
      $("#starred_box").append(`<span class='none_starred'>${GLOBAL_LANG.messenger_window_favorite_no_messages}</span>`);
    }

    setTimeout(function () { $("[name=" + "_str" + quoted_id + "]").remove(); }, 1000);
    bShowPopMenu = false;
  });

  $("#forward").live("click", function () {
    for (let i = 0; i < $("#" + messenger.Chat.token + " .item").length; i++) {
      let item = $("#" + messenger.Chat.token + " .item").eq(i);

      if (item.find(".revoke.Left").length > 0 || item.find(".revoke.Right").length > 0) {
        item.find(".forward input").hide();
      } else {
        item.find(".forward input").show();
      }
    }

    $('#count-message-forward').text(1);
    $('#select-message-forward').text(GLOBAL_LANG.messenger_modal_forward_message_selected);

    $(".popmenu").remove();
    $("#boxPopMenu").remove();
    $(".str-popmenu").remove();

    $(".message-send").hide();
    $(".message-forward").css({ "display": "flex" });

    $('.messages:visible .item[id]').css({ 'cursor': 'pointer' });

    $("#" + item_id).find(".forward").find("input").prop("checked", true)
    $("#" + item_id).css({ "background-color": "rgb(0 0 0 / 10%)" });
  });

  $('body').on('click', '#close-message-forward', function () {
    for (let i = 0; i < $("#" + messenger.Chat.token + " .item .forward").length; i++) {
      $("#" + messenger.Chat.token + " .forward").css('width', '0px');
      $("#" + messenger.Chat.token + " .forward input").prop('checked', false).hide();
    }

    $(".message-send").show();
    $(".message-forward").css({ "display": "none" });
    $('.messages:visible .item[id]').css({ 'cursor': '', 'background-color': '' });
  });

  $('body').on('change', '.forward input', function () {
    var count = $('.forward input:checked').length;
    var select = count <= 1 ? GLOBAL_LANG.messenger_modal_forward_message_selected : GLOBAL_LANG.messenger_modal_forward_messages_selected;
    var color = $(this).prop('checked') ? 'rgb(0 0 0 / 10%)' : '';

    $('#count-message-forward').text(count);
    $('#select-message-forward').text(select);
    $(this).closest('.item').css('background-color', color);
  });

  $("body").on("click", ".send-message-forward", function () {

    let check = $(this).attr("name");

    switch (check) {
      case "Chat":

        if ($('.forward input:checked').length == 0) {
          Swal.fire({
            title: GLOBAL_LANG.messenger_modal_forward_alert_no_message_selected_title,
            text: GLOBAL_LANG.messenger_modal_forward_alert_no_message_selected_text,
            type: 'warning',
            confirmButtonColor: '#2263d3',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.messenger_modal_forward_alert_no_message_selected_btn_ok
          });
          $('.swal2-container').css('z-index', 10000);
          return false;
        }

        $('.modalContactForward .footer span').text('').attr('title', '');
        $("#search-contact-forward").val("");
        $("#sendContactForward").attr("class", "sendContactForward Chat");

        break;
      case "Gallery":
        $("#sendContactForward").attr("class", "sendContactForward Gallery");

        break;
      case "Document":
        $("#sendContactForward").attr("class", "sendContactForward Document");

        break;
      default:
        break;
    }

    $("#modalContactForward").fadeIn("fast");
    $("#bgboxContactForward").fadeIn("fast");

    $(".loadContactForward")
      .prepend(`
		<i><img src="./assets/img/loads/loading_1.gif"  style="position:absolute; margin-top:211px; margin-left:216px; width:60px;}"></i>`
      );

    ta.contact.queryContactForward();

  });

  $('body').on("click", "#closeContactForward", function () {
    $(".modalContactForward").fadeOut("fast");
    $(".bgboxContactForward").fadeOut("fast");

    $('.input-send-message-forward').prop('checked', false);
    $('.modalContactForward .footer span').text('').attr('title', '');
    $('.modalContactForward .header .search #search-contact-forward').val(undefined);
  });


  $('body').on("click", ".bgboxContactForward", function () {
    $('#closeContactForward').trigger('click');
  });


  $('body').on('click', '#sendContactForward', function () {

    let el = $("#sendContactForward").attr("class");
    check = el.split("sendContactForward ")[1];

    switch (check) {
      case "Chat":
        $('.forward input:checked').each(function (idx, elm) {
          var key_id = $(elm).parent('.forward').parent('.item').attr('id');
          $('.input-send-message-forward:checked').each(function (idx, elm) {
            var key_remote_id = $(elm).val();
            ta.message.queryInfoMsg(
              key_remote_id,
              key_id
            );
            messenger.ChatList.Open(key_remote_id);
          });
        });

        break;
      case "Gallery":

        $(".img_selected").each(function (idx, elm) {

          let id = elm.id;
          id = $("#" + id).parent().parent().parent().attr("name");
          let key_id = id.split("_grid")[1];

          $('.input-send-message-forward:checked').each(function (idx, elm) {
            var key_remote_id = $(elm).val();
            ta.message.queryInfoMsg(
              key_remote_id,
              key_id
            );
            messenger.ChatList.Open(key_remote_id);
          });
        });

        break;
      case "Document":

        $(".document_selected").each(function (idx, elm) {

          let id = elm.id;

          id = $("#" + id).parent().parent().parent().parent().attr("name");
          let key_id = id.split("_gridDoc")[1];

          $('.input-send-message-forward:checked').each(function (idx, elm) {
            var key_remote_id = $(elm).val();
            ta.message.queryInfoMsg(
              key_remote_id,
              key_id
            );
            messenger.ChatList.Open(key_remote_id);
          });
        });
        break;
      case "Dados":
        break;

      default:
        break;
    }

    var key_remote_id_open = $('.input-send-message-forward:checked').sort(function (a, b) {
      return parseInt($(b).attr('selected-time')) - parseInt($(a).attr('selected-time'));
    }).last().val();

    messenger.ChatList.Open(key_remote_id_open);

    $('#close-message-forward').trigger('click');
    $('#closeContactForward').trigger('click');

    closeModal()
  });

  $('body').on('click', '.messages:visible .item[id]', function (e) {
    if (e.target.classList.value != 'checkbox-forward') {
      $(this).find('.forward').find('input:visible').first().prop('checked', !$(this).find('.forward').find('input:visible').first().prop('checked'));
      $(this).find('.forward').find('input:visible').trigger('change');
    }
  });

  $('body').on('click', '.cards-contact', function (e) {
    if (e.target.classList.value != 'input-send-message-forward' && this.id != "disabledTrue") {
      $(this).find("input").first().prop('checked', !$(this).find("input").first().prop('checked'));
      $(this).find("input").first().trigger('change');
    }
  });

  $('body').on('change', '.input-send-message-forward', function (e) {

    if ($('.input-send-message-forward:checked').length > 5) {
      $(this).prop('checked', !$(this).prop('checked'));

      Swal.fire({
        title: 'Atenção',
        text: 'Selecione no máximo 5 contatos!',
        type: 'warning',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ok!'
      });
      $('.swal2-container').css('z-index', 10000);
    }
    else {
      var nomes = [];

      $('.input-send-message-forward:checked').parent().find('.name-cards-contact')
        .each(function (idx, elm) {
          nomes.push($(elm).text())
        });

      nomes = nomes.join(',');
      title = nomes;
      nomes = nomes.length > 45 ? nomes.substr(0, 45) + ' ...' : nomes;

      $('.modalContactForward .footer span').text(nomes).attr('title', title);

      $(this).attr('selected-time', new Date().getTime());
    }
  });

  //Função para deixar o contains insensitivo
  jQuery.expr[':'].contains = function (a, i, m) {
    return jQuery(a).text().toUpperCase()
      .indexOf(m[3].toUpperCase()) >= 0;
  };

  $("#search-contact-forward").on('keyup', function (e) {
    if (this.value.length >= 3) {
      $(`.name-cards-contact`).parent().hide();
      $(`.cards-contact`).hide();

      $(`.name-cards-contact:contains('')`).parent().hide();
      $(`.name-cards-contact:contains('')`).parent().parent().hide();

      $(`.name-cards-contact:contains(${this.value})`).parent().show();
      $(`.name-cards-contact:contains(${this.value})`).parent().parent().show();
    }
    else {
      $(`.name-cards-contact`).parent().show();
      $(`.cards-contact`).show();
    }
  });

  $("#quoted").live("click", function () {
    quoted_id = item_id;

    let text = Util.doTruncarStr($("#" + quoted_id + " .textMessage .body span").html(), 256);

    $(".reply-message .message").html("<span>" + text + "</span>");

    switch ($("#" + item_id).find("div")[1].className) {
      case "textMessage left":
        $(".reply-message .message").html("<span>" + text + "</span>").css({ "padding": 10 });

        break;
      case "ImageMessage left":

        var media_url = $("#" + quoted_id).find(".ImageMessage").find(".body").find("img").attr("src");
        $(".reply-message .message").html(`<img class="quotedImage" src=${media_url}>
                <span class="quotedImageSpan"><i class="fas fa-camera"></i> Imagem</span>`).css({ "padding": 0 });

        break;
      case "VideoMessage left":
        $(".reply-message .message").html(`<span><i class="fas fa-video"></i> Vídeo</span>`).css({ "padding": 10 });

        break;
      case "AudioMessage left":
        $(".reply-message .message").html(`<span><i class="fas fa-microphone"> Áudio</i></span>`).css({ "padding": 10 });

        break;
      case "DocumentMessage left":

        let urlDocumentLeft = $("#" + quoted_id).find(".DocumentMessage").attr("data-url");
        let tex_doc_left = $("#" + quoted_id).find(".DocumentMessage").find(".body").find("span").text();

        urlDocumentLeft = urlDocumentLeft.split("/f/")[1];

        switch (urlDocumentLeft.split(".")[1]) {
          // case "pdf":
          case "":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/pdf.svg">
						<span class="text">${tex_doc_left}</span>`).css({ "padding": 10 });

            break;
          case "":
            // case "doc":
            // case "csv":
            // case "docx":
            $(".reply-message .message").html(`
								<img class="quoted-arquive" src="${document.location.origin}/assets/icons/texto.svg">
								<span class="text">${tex_doc_left}</span>`).css({ "padding": 10 });

            break;
          default:
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/new-document.svg">
						<span class="text">${tex_doc_left}</span>`).css({ "padding": 10 });
            break;
        }
        break;

      case "ZipMessage left":

        let urlZipLeft = $("#" + quoted_id).find(".ZipMessage").attr("data-url");
        let text_arq_left = $("#" + quoted_id).find(".ZipMessage").find(".body").find("span").text();

        urlZipLeft = urlZipLeft.split("/f/")[1];

        switch (urlZipLeft.split(".")[1]) {
          // case "doc":
          // case "csv":
          // case "docx":
          case "":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/texto.svg">
						<span class="text">${text_arq_left}</span>`).css({ "padding": 10 });
            break;

          // case "xls":
          // case "xlsx":
          case "":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/excel.svg">
						<span class="text">${text_arq_left}</span>`).css({ "padding": 10 });

            break;
          // case "txt":
          case "":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/txt.svg">
						<span class="text">${text_arq_left}</span>`).css({ "padding": 10 });

            break;
          default:
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/new-document.svg">
						<span class="text">${text_arq_left}</span>`).css({ "padding": 10 });

            break;
        }
        break;

      case "ContactMessage left":
        $(".reply-message .message").html(`<span><i class="fas fa-id-badge"></i> Contato</i></span>`).css({ "padding": 10 });
        break;
      case "LocationMessage left":
        $(".reply-message .message").html(`<span><i class="fas fa-map-marked-alt"></i> Localização</i></span>`).css({ "padding": 10 });
        break;
      case "textMessage right":
        $(".reply-message .message").html("<span>" + text + "</span>").css({ "padding": 10 });
        break;
      case "ImageMessage right":
        media_url = $("#" + quoted_id).find(".ImageMessage").find(".body").find("img").attr("src");
        $(".reply-message .message").html(`<img class="quotedImage" src=${media_url}>
                <span class="quotedImageSpan"><i class="fas fa-camera"></i> Imagem</span>`).css({ "padding": 0 });
        break;
      case "VideoMessage right":
        $(".reply-message .message").html(`<span><i class="fas fa-video"></i> Vídeo</span>`).css({ "padding": 10 });
        break;
      case "AudioMessage right":
        $(".reply-message .message").html(`<span><i class="fas fa-microphone"> Áudio</i></span>`).css({ "padding": 10 });
        break;
      case "DocumentMessage right":

        let urlDocumentRight = $("#" + quoted_id).find(".DocumentMessage").attr("data-url");
        let tex_doc_right = $("#" + quoted_id).find(".DocumentMessage").find(".body").find("span").text();

        urlDocumentRight = urlDocumentRight.split("/f/")[1];

        switch (urlDocumentRight.split(".")[1]) {
          case "pdf":
            $(".reply-message .message").html(`<img class="quoted-arquive" src="${document.location.origin}/assets/icons/pdf.svg">
						 <span class="text">${tex_doc_right}</span>`).css({ "padding": 10 });
            break;

          // case "doc":
          // case "csv":
          // case "docx":
          case "":
            $(".reply-message .message").html(`<img class="quoted-arquive" src="${document.location.origin}/assets/icons/texto.svg">
					     <span class="text">${tex_doc_right}</span>`).css({ "padding": 10 });
            break;

          default:
            $(".reply-message .message").html(`<img class="quoted-arquive" src="${document.location.origin}/assets/icons/new-document.svg">
						<span class="text">${tex_doc_right}</span>`).css({ "padding": 10 });
            break;
        }
        break;

      case "ZipMessage right":

        let urlZipRight = $("#" + quoted_id).find(".ZipMessage").attr("data-url");
        let text_arq_right = $("#" + quoted_id).find(".ZipMessage").find(".body").find("span").text();

        urlZipRight = urlZipRight.split("/f/")[1];

        switch (urlZipRight.split(".")[1]) {
          // case "doc":
          // case "csv":
          // case "docx":
          case "":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/texto.svg">
						<span class="text">${text_arq_right}</span>`).css({ "padding": 10 });
            break;

          // case "xls":
          // case "xlsx":
          case "":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/excel.svg">
						<span class="text">${text_arq_right}</span>`).css({ "padding": 10 });

            break;
          // case "txt":
          case "":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/txt.svg">
						<span class="text">${text_arq_right}</span>`).css({ "padding": 10 });

            break;
          default:
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/new-document.svg">
						<span class="text">${text_arq_right}</span>`).css({ "padding": 10 });

            break;
        }
        break;

      case "ContactMessage right":
        $(".reply-message .message").html(`<span><i class="fas fa-id-badge"></i> Contato</i></span>`).css({ "padding": 10 });
        break;
      case "LocationMessage right":
        $(".reply-message .message").html(`<span><i class="fas fa-map-marked-alt"></i>${GLOBAL_LANG.messenger_media_types_location}</i></span>`).css({ "padding": 10 });
        break;
    }
    $(".messenger .right .chat .reply-message .message").css({ "border-left": "5px solid #4ec545" });
    $(".buttonBottomScroll").hide()
    $(".reply-message").show();
    $(".input-text").focus();
    $(".popmenu").remove();
    $("#" + item_id + " .dropdown").hide();
    $("#boxPopMenu").remove();
    bShowPopMenu = false;
  });

  $("#close").live("click", function () {
    quoted_id = 0;
    $(".reply-message").hide();
    $(".text .input-text").text("");
    $(".reply-message .message").html("");
  });

  $(".dropdown").live("click", function (e) {
    if ($('#ok-record').is(':visible'))
      cancelRecording();

    let is_type;
    is_type = messenger.Chat.is_type;


    var currentTime = Math.floor(new Date() / 1000);
    var timeMessage = $(this).parent('div').parent('.item').data('index');
    var diff = timeMessage - currentTime;
    diff /= 60;
    diff = Math.abs(Math.round(diff));

    if (bShowPopMenu == false && $('.messages:visible').find('.item').find('.forward').find('input:visible').length == 0) {

      $(".popmenu").remove();

      item_id = this.parentElement.parentElement.id;

      let popmenu = document.createElement("div");

      if ($('.messenger').hasClass("dark")) {
        popmenu.className = "popmenu dark";

      } else {
        popmenu.className = "popmenu";
      }
      popmenu.id = "popmenu";
      popmenu.style.zIndex = 999;
      popmenu.style.fontFamily = "system-ui";

      /*
      let info = document.createElement("span");
      info.className = "item";
      info.id = "info_msg";
      info.textContent = "Dado da mensagem";
      popmenu.appendChild(info);
      */

      switch (parseInt(messenger.Chat.is_type)) {
        case 1:
        case 2:
          if ($("#" + item_id + " div")[1].style.float == "right") {
            if (messenger.Chat.revoke == true) {

              if (messenger.Chat.reply == true) {

                if (parseInt(messenger.Chat.is_type) != "12") {

                  let bxitem = document.createElement("div");
                  bxitem.style.display = "none";
                  bxitem.className = "pop_item";
                  bxitem.id = "quoted";
                  let item = document.createElement("span");
                  item.style.display = "none";
                  item.className = "item";
                  item.textContent = "Responder";
                  popmenu.style.top = (event.clientY - 60) + "px";
                  popmenu.style.left = (event.clientX - 180) + "px";
                  bxitem.appendChild(item);
                  popmenu.appendChild(bxitem);
                }

                let bxforward = document.createElement("div");
                bxforward.className = "pop_item";

                let forward = document.createElement("span");
                forward.className = "item";
                forward.id = "forward";
                forward.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_forward;
                bxforward.appendChild(forward);
                popmenu.appendChild(bxforward);

                if ($("#" + item_id).find("div").find(".bottom").find("i").hasClass("icon-starred")) {

                  let bxstarred = document.createElement("div");
                  bxstarred.className = "pop_item";
                  bxstarred.id = "disStarredMessage";

                  let starred = document.createElement("span");
                  starred.className = "item";
                  starred.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_unfavorite;
                  popmenu.style.top = (event.clientY - 60) + "px";
                  popmenu.style.left = (event.clientX - 180) + "px";
                  bxstarred.appendChild(starred);
                  popmenu.appendChild(bxstarred);

                } else {

                  let bxstar = document.createElement("div");
                  bxstar.className = "pop_item";
                  bxstar.id = "starredMessage";

                  let star = document.createElement("span");
                  star.className = "item";
                  star.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_favorite;
                  popmenu.style.top = (event.clientY - 60) + "px";
                  popmenu.style.left = (event.clientX - 180) + "px";
                  bxstar.appendChild(star);
                  popmenu.appendChild(bxstar);
                }

                if (diff < 60) {

                  let bxitem = document.createElement("div");
                  bxitem.className = "pop_item";
                  bxitem.id = "revoke";

                  let item = document.createElement("span");
                  item.className = "item";
                  item.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_delete_message;
                  popmenu.style.top = (event.clientY - 60) + "px";
                  popmenu.style.left = (event.clientX - 180) + "px";
                  bxitem.appendChild(item);
                  popmenu.appendChild(bxitem);
                }
              }
            }
          } else {
            if (messenger.Chat.reply == true) {

              if (is_type != "12") {

                let bxitem = document.createElement("div");
                bxitem.className = "pop_item";
                bxitem.id = "quoted";

                let item = document.createElement("span");
                item.className = "item";
                item.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_reply;

                popmenu.style.top = event.clientY + "px";
                popmenu.style.left = event.clientX + "px";
                bxitem.appendChild(item);
                popmenu.appendChild(bxitem);

              }

              let bxforward = document.createElement("div");
              bxforward.className = "pop_item";

              let forward = document.createElement("span");
              forward.className = "item";
              forward.id = "forward";
              forward.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_forward;
              bxforward.appendChild(forward);
              popmenu.appendChild(bxforward);

              if ($("#" + item_id).find("div").find(".bottom").find("i").hasClass("icon-starred")) {

                let bxstarred = document.createElement("div");
                bxstarred.className = "pop_item";
                bxstarred.id = "disStarredMessage";

                let starred = document.createElement("span");
                starred.className = "item";
                starred.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_unfavorite;

                popmenu.style.top = event.clientY + "px";
                popmenu.style.left = event.clientX + "px";
                bxstarred.appendChild(starred);
                popmenu.appendChild(bxstarred);

              } else {

                let bxstar = document.createElement("div");
                bxstar.className = "pop_item";
                bxstar.id = "starredMessage";

                let star = document.createElement("span");
                star.className = "item";
                star.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_favorite;

                popmenu.style.top = event.clientY + "px";
                popmenu.style.left = event.clientX + "px";
                bxstar.appendChild(star)
                popmenu.appendChild(bxstar);
              }

            }
          }
          break;
        case 2:
        case 10:
        case 12:
        case 16:
          if ($("#" + item_id + " div")[1].style.float == "right") {

            if (messenger.Chat.reply == true) {

              let bxforward = document.createElement("div");
              bxforward.className = "pop_item";

              let forward = document.createElement("span");
              forward.className = "item";
              forward.id = "forward";
              forward.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_forward;
              bxforward.appendChild(forward);
              popmenu.appendChild(bxforward);

              if ($("#" + item_id).find("div").find(".bottom").find("i").hasClass("icon-starred")) {

                let bxstarred = document.createElement("div");
                bxstarred.className = "pop_item";

                let starred = document.createElement("span");
                starred.className = "item";
                starred.id = "disStarredMessage";
                starred.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_unfavorite;
                popmenu.style.top = (event.clientY - 60) + "px";
                popmenu.style.left = (event.clientX - 180) + "px";
                bxstarred.appendChild(starred);
                popmenu.appendChild(bxstarred);

              } else {

                let bxstar = document.createElement("div");
                bxstar.className = "pop_item";

                let star = document.createElement("span");
                star.className = "item";
                star.id = "starredMessage";
                star.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_favorite;
                popmenu.style.top = (event.clientY - 60) + "px";
                popmenu.style.left = (event.clientX - 180) + "px";
                bxstar.appendChild(star);
                popmenu.appendChild(bxstar);
              }
            }
          } else {
            if (messenger.Chat.reply == true) {

              let bxforward = document.createElement("div");
              bxforward.className = "pop_item";

              let forward = document.createElement("span");
              forward.className = "item";
              forward.id = "forward";
              forward.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_forward;
              bxforward.appendChild(forward);
              popmenu.appendChild(bxforward);

              if ($("#" + item_id).find("div").find(".bottom").find("i").hasClass("icon-starred")) {

                let bxstarred = document.createElement("div");
                bxstarred.className = "pop_item";

                let starred = document.createElement("span");
                starred.className = "item";
                starred.id = "disStarredMessage";
                starred.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_unfavorite;

                popmenu.style.top = event.clientY + "px";
                popmenu.style.left = event.clientX + "px";
                bxstarred.appendChild(starred);
                popmenu.appendChild(bxstarred);

              } else {

                let bxstar = document.createElement("div");
                bxstar.className = "pop_item";

                let star = document.createElement("span");
                star.className = "item";
                star.id = "starredMessage";
                star.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_favorite;

                popmenu.style.top = event.clientY + "px";
                popmenu.style.left = event.clientX + "px";
                bxstar.appendChild(star)
                popmenu.appendChild(bxstar);
              }

            }
          }
          break;
      }
      var boxPopMenu = document.createElement('div');
      boxPopMenu.style.display = "block";
      boxPopMenu.id = "boxPopMenu";
      boxPopMenu.style.zIndex = "149";
      boxPopMenu.style.top = "0px";
      boxPopMenu.style.left = "0px";
      boxPopMenu.style.position = "fixed";
      boxPopMenu.style.width = "100%";
      boxPopMenu.style.height = "100%";
      boxPopMenu.onclick = function () {
        $(".popmenu").remove();
        $("#boxPopMenu").remove();
        $("#" + item_id + " .dropdown").hide();
        $("#settings_tooltip").hide();
        bShowPopMenu = false;
      };
      document.body.appendChild(boxPopMenu);
      document.body.appendChild(popmenu);
      e.stopPropagation();
    } else {
      $(".popmenu").remove();
      $("#boxPopMenu").remove();
      $("#" + item_id + " .dropdown").hide();
      bShowPopMenu = false;
      e.stopPropagation();
    }
    // }
  });

  $(".conn-refresh").live("click", function () {
    location.reload();
  });

  $(".conn-close").live("click", function () {
    window.location.href = "/contact";
  });

  $(".btnLink").live("click", function () {
    if ($(this).attr("data-url") != null) {
      window.open($(this).attr("data-url"));
    }
  });

  $(".templateMessage").live("click", function () {
    if (bShowPopMenu == false) {
      if ($(this).attr("data-url") != null) {
        window.open($(this).attr("data-url"));
      }
    }
  });

  $(".DocumentMessage").live("click", function () {
    if (bShowPopMenu == false) {
      if ($(this).attr("data-url") != null) {
        window.open($(this).attr("data-url"));
      }
    }
  });

  $(".ZipMessage").live("click", function () {
    if ($(this).attr("data-url") != null) {
      window.open($(this).attr("data-url"));
    }
  });
  $(".UrlImageMessage").live("click", function () {
    if (bShowPopMenu == false) {
      if ($(this).attr("data-url") != null) {
        window.open($(this).attr("data-url"));
      }
    }
  });
  $(".VideoMessage").live("click", function () {
    if ($(this).attr("data-url") != null) {
      window.open($(this).attr("data-url"));
    }
  });
  $(".UrlStoryMention").live("click", function () {
    if (bShowPopMenu == false) {
      if ($(this).attr("data-url") != null) {
        window.open($(this).attr("data-url"));
      }
    }
  });
  $(".GifMessage video").live("click", function () {
    this.play();
  });

  $('.select-user-group').live('change', function () {
    ta.user.queryUsers(this.value);
  });

  $("#btn-trans-ok").live("click", function () {
    this.disabled = true;
    var id_user_group = $(".select-user-group").val();
    var user = $(".select-user").val();
    if (id_user_group != 0) {
      ta.chat.trans(user, id_user_group, $("#checkbox_default_setor").is(":checked"));
    }
    closeModal()
  });

  $("#options-setting").live('click', function (e) {
    $("#settings_tooltip").slideToggle(200);
    e.stopPropagation();
  });


  $(".messenger").live("click", function () {

    $("#settings_tooltip").hide();
    $(".modalMessenger").hide();

    $("#imgProfile").css({ "filter": "" });
  });


  $("#notification").on('click', () => {
    if ($("#notification-form").hasClass("hide")) {
      //$(".messenger .option")[0].css("display", "none");            
      $("#notification-form").addClass("animate__animated animate__bounceIn").removeClass("hide");
    } else {
      $("#notification-form").addClass("hide");
    }
  });
  $("#option-close-notify").on("click", () => {
    if ($("#notification-form").hasClass("hide")) {
      $("#notification-form").addClass("animate__animated animate__bounceIn").removeClass("hide");
    } else {
      $("#notification-form").addClass("hide");
    }
  });

  $("#close_settings_toolbar").on('click', () => {
    $(".colors").css("display", "none");
    $(".window__favorite")[0].style.display = "none";
    $(".messenger .right")[0].style = "width: calc(100% - 360px)";
    $(".messenger .right")[0].style.display = "block";
  });

  LoadMode();


  if (bNight == true) {
    $("#checkedDark").attr("checked", true);
  } else {
    $("#checkedClear").attr("checked", true);
  }


  let fontSizeBody = localStorage.getItem("fontMessenger")
  switch (fontSizeBody) {
    case "12":
      $("#checkedSmall").attr("checked", true);
      break;
    case "14.2":
      $("#checkedAverage").attr("checked", true);
      break;
    case "17":
      $("#checkedBig").attr("checked", true);
      break;
    case "20":
      $("#checkedXtraBig").attr("checked", true);
      break;
    default:
      $("#checkedAverage").attr("checked", true);
      break;
  }


  $(".openCardList").live("click", function () {
    let id_order = this.id;
    ta.chat.queryCardList(id_order);
    ta.chat.queryOrderStatus();
  });


  $(".btnSaveCard").live("click", function () {

    let order = $("#id_messages_order").attr("value");
    let id_order_status = $("#order_status").attr("value");

    ta.chat.saveCardList(order, id_order_status);
    $(".bgboxCardList").fadeOut("fast");
    $(".modalCardList").fadeOut("fast");
  });


  $(".closeCard").live("click", function () {
    $(".bgboxCardList").fadeOut("fast");
    $(".modalCardList").fadeOut("fast");
  });


  $('#galleryBox').bind('scroll', function () {
    if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {

      if (getMoreImageGallery) {
        getMoreImageGallery = false;

        let timetamp = $(".container-gallery").eq(-1)[0].attributes[1].nodeValue;
        timetamp = timetamp.split("_g")[1];

        if (timetamp.length === 10) ta.chat.queryGallery(timetamp, "midia");

        $(".window__gallery #load_more_images_galley").remove();
        $(".window__gallery #col-gallery").append(`
          <div class="container-gallery" id="load_more_images_galley" style="display: inline-block;">
              <div class="box-load">
                 <img class="img-gallery" src="./assets/img/loads/loading_2.gif" style="object-fit:fill;width:90px;height:90px;margin:auto;padding:12px;margin-top:3px;">
              </div>
          </div>`
        );
      }
    }
  });


  $('#documentBox').bind('scroll', function () {
    if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
      let timetamp = $(".container-documents").eq(-1)[0].attributes[1].nodeValue;
      timetamp = timetamp.split("_g")[1];
      if (timetamp.length === 10) ta.chat.queryGallery(timetamp, "document");
    }
  });


  $(".container-gallery").live("mouseover", function () {

    let id = $(this).attr("name");
    let code = Math.floor(Math.random() * 100000);

    $("[name=" + id + "]").find("a").find(".box").find(".icon-img-gallery").css({ "display": "" });
    $("[name=" + id + "]").find(".box").find(".icon-img-gallery").attr("id", code);
    $("[name=" + id + "]").find(".box").find(".shadow-top").css({
      "background": "linear-gradient(to right, rgb(0 0 0 / -52%) 0%, rgba(0, 0, 0, 0.799) 107%)"
    });
  });


  $(".container-gallery").live("mouseout", function () {

    let id = $(this).attr("name");

    if ($("[name=" + id + "]").find(".box").find("i").hasClass("img_selected") == false) {
      $("[name=" + id + "]").find("a").find(".box").find(".icon-img-gallery").css({ "display": "none" });
    }
    $("[name=" + id + "]").find(".box").find(".shadow-top").css({
      "background": "linear-gradient(to right, rgba(0, 0, 0, 0) 0%, rgb(0 0 0 / 0%) 107%)"
    });
  });


  $(".icon-img-gallery").live("click", function (e) {

    const id = this.id;

    if ($("#" + id).hasClass("img_selected")) {

      // image selecionada //
      $("#" + id).parent().find(".shaw_imgT").addClass("shadow-top");
      $("#" + id).attr("class", "fas fa-check-circle icon-img-gallery custon_icon_deselected");

    } else {

      $("#" + id).parent().find(".shaw_imgT").removeClass("shadow-top");
      $("#" + id).attr("class", "fas fa-check-circle icon-img-gallery img_selected");
    }

    const countItem = $(".window__gallery .img_selected").length;

    if (countItem === 0) {

      const titleSelected = document.getElementsByClassName("titleSelected")[0];
      if (titleSelected != null) {
        titleSelected.innerHTML = "";
        titleSelected.style.display = "none";
      }

      const arrow_download_gallery = document.getElementById("arrow-download-gallery");
      const view_item_gallery = document.getElementById("view-tem-gallery");

      arrow_download_gallery.style.display = "none";
      view_item_gallery.style.display = "none";

    } else {

      const titleSelected = document.getElementsByClassName("titleSelected")[0];
      if (titleSelected != null) {
        titleSelected.innerHTML = countItem === 1 ? `${countItem} ${GLOBAL_LANG.messenger_window_gallery_item_selected}` : `${countItem} ${GLOBAL_LANG.messenger_window_gallery_items_selected}`;
        titleSelected.style.display = "block";
      }

      const arrow_download_gallery = document.getElementById("arrow-download-gallery");
      arrow_download_gallery.style.display = "block";

      const id = $(".img_selected").parent().parent().parent().attr("id");
      const id_mesage = $(".img_selected").parent().parent().parent().attr("name").split("_grid")[1];
      const creation = id.split("_g")[1];

      const view_item_gallery = document.getElementById("view-tem-gallery");
      view_item_gallery.style.display = "block";
      view_item_gallery.dataset.id = id_mesage;
      view_item_gallery.dataset.creation = creation;

      if (countItem >= 2) view_item_gallery.style.display = "none";
    }
    e.preventDefault();
  });


  $('body').on('click', '#view-tem-gallery', function () {

    document.getElementById("view-tem-gallery").disabled = true;

    clearTimeout(QUERY_MESSAGES_CHAT);

    SCROLL_BLOCK = true;
    FORCE_SCROLL_DOWN = true;
    LOCK_FOCUS = false;

    ITEM_FOCUSED = this.dataset.id;

    $(".chat .messages").each((idx, elm) => {
      if (elm.style.display != "none") {
        $(elm).find("div").remove();
      }
    });

    ta.chat.queryMessages(ta.key_remote_id, this.dataset.creation, false);
    $("#load_bottom_chat").remove();
  });


  $('#arrow-download-gallery').live('click', function () {

    if ($(".document_selected").length === 0) {

      let images = document.querySelectorAll(".img_selected");

      (async () => {
        for (let i = 0; i < images.length; i++) {

          const image = await fetch($(images[i]).parent(".box").parent("a").attr("href"));
          const imageBlog = await image.blob();
          const imageURL = URL.createObjectURL(imageBlog);

          const url = $(images[i]).parent(".box").parent("a").attr("href");
          const name = url.split(/\/[a-zA-Z]\//)[1];
          const extension = name.split(".")[1];

          const link = document.createElement('a');

          if ($(images[i]).parent(".box").parent("a").parent(".container-gallery").hasClass("video")) {
            link.href = $(images[i]).parent(".box").parent("a").attr("href");
          } else {
            link.href = imageURL;
            link.download = `download.${extension}`;
          }

          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        }
      })();

    } else {

      const docum = $(".document_selected");

      for (let i = 0; i < docum.length; i++) {

        const url = $(docum[i]).parent(".box").parent("a").attr("href");
        const name = url.split("/v/")[1];
        const extension = name.split(".")[1];

        fetch(url)
          .then(resp => resp.blob())
          .then(blob => {

            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');

            a.style.display = 'none';
            a.href = url;
            a.download = `arquive.${extension}`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
          })
          .catch(() => alert(GLOBAL_LANG.messenger_window_gallery_alert_download_error));
      }
    }
  });


  $(".container-documents").live("mouseover", function () {
    $("#" + this.id).find(".document-message-gallery").find("a").find(".box").find(".checkbox_document").css({ "display": "block" });
  });


  $(".container-documents").live("mouseout", function () {
    if ($("#" + this.id).find(".document-message-gallery").find("a").find(".box").find(".checkbox_document").attr("checked") == "checked") {
    } else {
      $("#" + this.id).find(".document-message-gallery").find("a").find(".box").find(".checkbox_document").css({ "display": "none" });
    }
  });


  $(".checkbox_document").live("click", function () {

    const id = this.id;

    if ($("#" + id).attr("checked") == "checked") $("#" + id).addClass("document_selected");
    else $("#" + id).removeClass("document_selected");

    const countItem = $(".window__gallery .document_selected").length;

    if (countItem === 0) {

      const titleSelected = document.getElementsByClassName("titleSelected")[0];
      if (titleSelected != null) {
        titleSelected.innerHTML = "";
        titleSelected.style.display = "none";
      }

      const arrow_download_gallery = document.getElementById("arrow-download-gallery");
      const view_item_gallery = document.getElementById("view-tem-gallery");

      arrow_download_gallery.style.display = "none";
      view_item_gallery.style.display = "none";

    } else {

      const titleSelected = document.getElementsByClassName("titleSelected")[0];
      if (titleSelected != null) {
        titleSelected.innerHTML = countItem === 1 ? `${countItem} ${GLOBAL_LANG.messenger_window_gallery_item_selected}` : `${countItem} ${GLOBAL_LANG.messenger_window_gallery_items_selected}`;
        titleSelected.style.display = "block";
      }

      const arrow_download_gallery = document.getElementById("arrow-download-gallery");
      arrow_download_gallery.style.display = "block";

      const creation = $(".document_selected").parent().parent().parent().parent().attr("id").split("_g")[1];
      const id_mesage = $(".document_selected").parent().parent().parent().parent().attr("name").split("_gridDoc")[1];

      const view_item_gallery = document.getElementById("view-tem-gallery");
      view_item_gallery.style.display = "block";
      view_item_gallery.dataset.id = id_mesage;
      view_item_gallery.dataset.creation = creation;

      if (countItem >= 2) view_item_gallery.style.display = "none";
    }
  });


  $(".document-download").live("click", function () {
    let document = $(".document_selected");

    for (let i = 0; i < document.length; i++) {

      const url = $(document[i]).parent(".box").parent("a").attr("href");
      const xhr = new XMLHttpRequest();

      xhr.responseType = 'blob';
      xhr.onload = function () {
        const a = document.createElement('a');
        a.href = url;
        a.download = "";
        a.style.display = 'none';
        document.body.appendChild(a);
        a.click();
        a.remove()
      };
      xhr.open('GET', url);
      xhr.send();
    }
  });


  $("#send-catalog").live("click", function () {

    $(".bgboxCatalog").fadeIn("fast");
    $(".modalCatalog").fadeIn("fast");

    $(".not_found_catalog").remove();
    $(".title-catalog-footer").remove();
    $(".title-catalog-hide").remove();
    $(".modalCatalog .footer").hide();

    $(".reply-message").hide();
    $(".text .input-text").text("");
    $(".reply-message .message").html("");

    ta.chat.queryCatalog();
  });


  $(".bgboxCatalog").live("click", function () {

    $(".bgboxCatalog").fadeOut("fast");
    $(".modalCatalog").fadeOut("fast");
    $(".modalClockCatalog").fadeOut("fast");
    $(".modalRejectedCatalog").fadeOut("fast");
    $(".modalCatalog").fadeOut("fast");

  });

  $(".closeCatalog").live("click", function () {

    $(".bgboxCatalog").fadeOut("fast");
    $(".modalCatalog").fadeOut("fast");
    $(".modalClockCatalog").fadeOut("fast");
    $(".modalRejectedCatalog").fadeOut("fast");
    $(".modalCatalog").fadeOut("fast");

  });


  $(".modalCatalog .body .container").live("click", function () {

    var id = this.id;
    let is_status = $(this).attr("name");
    var checkCatalog = localStorage.getItem("checkCatalog");
    let url = $("#" + id).find(".container-inner").find(".box").find(".img").attr("src");
    let title = $("#" + id).find(".container-inner").find(".details").find(".title").find("span").text();

    $("#three_points").remove();

    if (checkCatalog == "true") {
      localStorage.setItem("checkCatalog", "false");

    } else {

      if (is_status == "is_clock") {

        $(".modalClockCatalog .body .container").remove();
        $(".modalClockCatalog").fadeIn("fast");

        $(".modalClockCatalog .body").append(`
				                    <div class="container">
				                         <div class="box">
					                        <img src="${url}">
										 </div>
										 <div class="text">
											 <div class="text-inner">
												<span>${GLOBAL_LANG.messenger_catalog_under_review}</span>
												<a target="_blank" href="https://www.whatsapp.com/policies/commerce-policy">${GLOBAL_LANG.messenger_catalog_learn_more}</a>
											 </div>
										 </div>	
										 <div class="title">
										    <span>${title}</span>
										 </div>
						         </div>`);


      } else if (is_status == "is_rejected") {

        let s_id = id;
        s_id = id.split("_check")[1];

        $(".modalRejectedCatalog .body .container").remove();
        $(".modalRejectedCatalog").fadeIn("fast");

        $(".modalRejectedCatalog .body").append(`
				                    <div class="container">
				                         <div class="box">
					                        <img src="${url}">
										 </div>
										 <div class="text">
										    <div class="text-inner">
										 	   <span>${GLOBAL_LANG.messenger_catalog_not_approved}</span>
											   <a target="_blank" href="https://www.whatsapp.com/policies/commerce-policy">${GLOBAL_LANG.messenger_catalog_learn_more}</a><p>
											   <span class="notGree">Caso não concorde, você pode</span><a id="close_modalRejectedCatalog" target="_blank" 
											   href="${document.location.origin + '/product/appeal/' + s_id}">${GLOBAL_LANG.messenger_catalog_request_another_review}</a>
											</div>
										 </div>	
										 <div class="title">
										    <span>${title}</span>
										 </div>
								 </div>`);


      } else if (is_status == "is_approved") {


        localStorage.setItem("checkCatalog", "false");

        if ($("#" + id).find(".container-inner").find(".box").find(".checkedCatalog").is(':checked')) {
          $("#" + id).find(".container-inner").find(".box").find(".checkedCatalog").attr("checked", false);
          $("#" + id).find(".container-inner").find(".box").find(".checkedCatalog").removeClass("selected");

          let id_title = id.split("_check")[1];
          $("#titl_" + id_title).remove();


          let tex = 0;
          tex = $(".title-catalog-footer").text();
          tex = tex.trim();

          if (tex.length < 40) {

            if ($(".modalCatalog").find("span").hasClass("title-catalog-hide")) {
              $(".title-catalog-hide")[0].attributes[2].nodeValue = "display:''";
              $(".title-catalog-hide").eq(0).addClass("title-catalog-footer");
              $(".title-catalog-hide").eq(0).removeClass("title-catalog-hide");
            }
          }

        } else {

          $("#" + id).find(".container-inner").find(".box").find(".checkedCatalog").attr("checked", true);
          $("#" + id).find(".container-inner").find(".box").find(".checkedCatalog").addClass("selected");


          let title = $("#" + id).find(".container-inner").find(".details").find(".title").find("span").text();
          let id_title = id.split("_check")[1];

          id_title = "titl_" + id_title;

          $(".modalCatalog .footer").prepend(`<span class="title-catalog-footer" id="${id_title}"> ${title}, </span>`);

        }
      }

      if ($(".modalCatalog").find(".body").find(".container").find(".container-inner").find(".box").find(".selected").length == 0) {

        $(".modalCatalog .footer").css({ "display": "none" });
        $("#iconHomeCatalog").attr("src", document.location.origin + "/assets/img/iconHomeCatalog.png");
      } else {
        $(".modalCatalog .footer").css({ "display": "block" });
        $("#iconHomeCatalog").attr("src", document.location.origin + "/assets/img/iconHomeCatalogDesected.png");
        $("#iconSendCatalog").show("fast");
      }

      let tex = 0;

      tex = $(".title-catalog-footer").text();
      tex = tex.trim();

      if (tex.length > 40) {

        $(".title-catalog-footer").eq(-1).hide();
        $(".title-catalog-footer").eq(-1).addClass("title-catalog-hide");
        $(".title-catalog-footer").eq(-1).removeClass("title-catalog-footer");
        $(".modalCatalog .footer").append(`<span id="three_points">...</span>`);
      }

    }

  });


  $(".checkedCatalog").live("click", function (e) {

    var id = this.id;
    let title = $("#" + id).parent().parent().find(".details").find(".title").find("span").text();
    let is_status = $("#" + id).parent(".box").parent().parent().attr("name");
    let url = $("#" + id).parent().find(".img").attr("src");


    localStorage.setItem("checkCatalog", "true");

    if (is_status == "is_clock") {

      $(".modalClockCatalog .body .container").remove();
      $(".modalClockCatalog").fadeIn("fast");

      $(".modalClockCatalog .body").append(`
								<div class="container">
									 <div class="box">
										<img src="${url}">
									 </div>
									 <div class="text">
										 <div class="text-inner">
											<span>${GLOBAL_LANG.messenger_catalog_under_review}</span>
											<a target="_blank" href="https://www.whatsapp.com/policies/commerce-policy">${GLOBAL_LANG.messenger_catalog_learn_more}</a>
										 </div>
									 </div>	
									 <div class="title">
										<span>${title}</span>
									 </div>
							 </div>`);

      $("#" + id).attr("checked", false);

    } else if (is_status == "is_rejected") {

      let s_id = id;
      s_id = id.split("_ck")[1];

      $(".modalRejectedCatalog .body .container").remove();
      $(".modalRejectedCatalog").fadeIn("fast");

      $(".modalRejectedCatalog .body").append(`
				                    <div class="container">
				                         <div class="box">
					                        <img src="${url}">
										 </div>
										 <div class="text">
										    <div class="text-inner">
										 	   <span>${GLOBAL_LANG.messenger_catalog_not_approved}</span>
											   <a target="_blank" href="https://www.whatsapp.com/policies/commerce-policy">${GLOBAL_LANG.messenger_catalog_learn_more}</a><p>
											   <span class="notGree">${GLOBAL_LANG.messenger_catalog_rejected_disagree}</span><a target="_blank" href="${document.location.origin + '/product/appeal/' + s_id}">
											   ${GLOBAL_LANG.messenger_catalog_request_another_review}.</a>
											</div>
										 </div>	
										 <div class="title">
										    <span>${title}</span>
										 </div>
								 </div>`);

      $("#" + id).attr("checked", false);

    } else if (is_status == "is_approved") {

      if ($("#" + id).is(":checked")) {

        $("#" + id).addClass("selected");

        let title = $("#" + id).parent().parent().find(".details").find(".title").text();
        let id_title = id.split("_ck")[1];

        title = title.trim();
        id_title = "titl_" + id_title;

        $(".modalCatalog .footer").prepend(`<span class="title-catalog-footer" id="${id_title}"> ${title}, </span>`);

      } else {

        let id_title = id.split("_ck")[1];

        $("#titl_" + id_title).remove();
        $("#" + id).removeClass("selected");

        let tex = 0;
        tex = $(".title-catalog-footer").text();
        tex = tex.trim();

        if (tex.length < 40) {

          if ($(".modalCatalog").find("span").hasClass("title-catalog-hide")) {

            $(".title-catalog-hide")[0].attributes[2].nodeValue = "display:''";
            $(".title-catalog-hide").eq(0).addClass("title-catalog-footer");
            $(".title-catalog-hide").eq(0).removeClass("title-catalog-hide");

          }
        }
      }

      if ($(".modalCatalog").find(".body").find(".container").find(".container-inner").find(".box").find(".selected").length == 0) {

        $(".modalCatalog .footer").css({ "display": "none" });
        $("#iconHomeCatalog").attr("src", document.location.origin + "/assets/img/iconHomeCatalog.png");
      } else {
        $(".modalCatalog .footer").css({ "display": "block" });
        $("#iconHomeCatalog").attr("src", document.location.origin + "/assets/img/iconHomeCatalogDesected.png");
        $("#iconSendCatalog").show("fast");
      }

      let tex = 0;

      tex = $(".title-catalog-footer").text();
      tex = tex.trim();

      if (tex.length > 40) {

        $(".title-catalog-footer").eq(-1).hide();
        $(".title-catalog-footer").eq(-1).addClass("title-catalog-hide");
        $(".title-catalog-footer").eq(-1).removeClass("title-catalog-footer");
        $(".modalCatalog .footer").append(`<span id="three_points">...</span>`);
      }

    }

  });

  $("#iconSendCatalog").live("click", function () {

    $(".bgboxCatalog").fadeOut("fast");
    $(".modalCatalog").fadeOut("fast");

    var _checked = $(".container").find(".container-inner").find(".box").find(".selected").length;

    for (let i = 0; i < _checked.length; i++) {

      let total = [];
      total.push = $(".container").find(".container-inner").find(".box").find(".selected")[i].name;
    }

  });

  $(".all-catalog").live("click", function () {
    ta.chat.queryChannel();
  });

  $("#search-catalog").live("keyup", function () {

    let data;
    data = $("#search-catalog").val();

    if (data.length > 2) {

      $(".icon-search-catalog").hide();
      $(".load_search_catalog").remove();
      $(".modalCatalog .search").prepend(`<img src="./assets/img/loads/loading_1.gif" class="load_search_catalog">`);
      ta.chat.queryCatalog(data);
    }

    if (data.length >= 1) {
      $(".icon-close-text-catalog").show();
    }

    if (data.length < 1) {

      ta.chat.queryCatalog();
      $(".not_found_catalog").remove();
      $(".icon-close-text-catalog").hide();
    }

  });

  $('body').on('click', '.icon-close-text-catalog', function () {

    $("#search-catalog").attr("value", "");
    $(".icon-close-text-catalog").hide();
    $(".not_found_catalog").remove();
    $(".icon-search-catalog").show();
    ta.chat.queryCatalog();

  });

  $('body').on('click', '.close-clock-catalog', function () {
    $(".modalClockCatalog").fadeOut("fast");
  });

  $('body').on('click', '.close-rejected-catalog', function () {
    $(".modalRejectedCatalog").fadeOut("fast");
  });

  $('body').on('click', '#close_modalRejectedCatalog', function () {

    $(".bgboxCatalog").fadeOut("fast");
    $(".modalCatalog").fadeOut("fast");
    $(".modalClockCatalog").fadeOut("fast");
    $(".modalRejectedCatalog").fadeOut("fast");

  });

  $('body').on('mouseover', '#send-catalog', function () {
    $(".title-send-catalog").css({ "display": "block" });
  });

  $('body').on('mouseout', '#send-catalog', function () {
    $(".title-send-catalog").css({ "display": "none" });
  });

  $('body').on('mouseover', '#send-document', function () {
    $(".title-send-document").css({ "display": "block" });
  });

  $('body').on('mouseout', '#send-document', function () {
    $(".title-send-document").css({ "display": "none" });
  });

  $('body').on('mouseover', '#send-image', function () {
    $(".title-send-image").css({ "display": "block" });
  });

  $('body').on('mouseout', '#send-image', function () {
    $(".title-send-image").css({ "display": "none" });
  });

  $('body').on('mouseover', '#send-template', function () {
    $(".title-send-template").css({ "display": "block" });
  });

  $('body').on('mouseout', '#send-template', function () {
    $(".title-send-template").css({ "display": "none" });
  });

  $('body').keydown(function (e) {

    /////// FECHAR ATENDIMENTO
    if (e.ctrlKey && e.altKey && e.keyCode == 8) {

      if (messenger.Chat.selected != null) {
        $("#close-chat").click();
      }
    }

    ///// TRANSFERIR ATENDIMENTO
    if (e.ctrlKey && e.altKey && e.keyCode == 84 && messenger.Chat.is_private < 2) {

      if (messenger.Chat.selected != null) {
        showTransferModal()
      }
    }

    ////////// FIXAR
    if (e.ctrlKey && e.altKey && e.keyCode == 80) {

      if (messenger.Chat.selected != null) {

        let idItem = messenger.Chat.selected;

        if ($("#" + idItem).find(".body").find(".iconFixar").hasClass("fixed")) {

          $('#md_' + idItem).parent().find('.iconFixar').removeClass('fixed')
          $('#md_' + idItem).parent().find('.iconFixar').hide()

          $('#md_' + idItem).parent('div').parent('div').data().fixed_timestamp = null;

          $('#list-active').find('.item').sort(function (a, b) {
            return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
          }).appendTo('#list-active');

          $('#list-internal').find('.item').sort(function (a, b) {
            return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
          }).appendTo('#list-internal');

          $('#list-wait').find('.item').sort(function (a, b) {
            return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
          }).appendTo('#list-wait');

          $('.modalMessenger').hide();

          ta.chat.makeNotFixed(info.key_remote_id);
          messenger.ChatList.updateCountView();

          e.stopPropagation();

          e.stopPropagation();

        } else {

          let count = parseInt($('#ball_' + idItem).find('.numberLabel').html())
          if ($('#ball_' + idItem).hasClass('checkModal') && count == 0) {
            $('#ball_' + idItem).parent().find('.iconFixar');
          } else {
            $('#ball_' + idItem).parent().find('.iconFixar');
          }

          $('#md_' + idItem).parent().find('.icone').hide()
          $('#md_' + idItem).parent().find('.iconFixar').addClass('fixed')
          $('#md_' + idItem).parent().find('.iconFixar').show()

          info = messenger.ChatList.find(idItem);
          $('#' + info.hash).data('index', 9999999999);

          $('#list-active').find('.item').sort(function (a, b) {
            return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
          }).appendTo('#list-active');

          $('#list-internal').find('.item').sort(function (a, b) {
            return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
          }).appendTo('#list-internal');

          $('#list-wait').find('.item').sort(function (a, b) {
            return $(b).data('fixed_timestamp') - $(a).data('fixed_timestamp') || $(b).data('index') - $(a).data('index');
          }).appendTo('#list-wait');

          $('.modalMessenger').hide();

          ta.chat.makeFixed(info.key_remote_id);

          e.stopPropagation();
        }

      }

    }

    /////////// COLOCAR ESPERA OU ATENDIMENTO
    if (e.ctrlKey && e.altKey && e.keyCode == 87 && messenger.Chat.is_private < 2) {

      if (messenger.Chat.selected != null) {
        if ($("#list-active").is(":visible")) {
          messenger.Chat.wait();
        } else {
          messenger.Chat.attendance();
        }
      }
    }

    ///////////// PESQUISA ///////////
    if (e.ctrlKey && e.altKey && e.keyCode == 83) {

      if (messenger.Chat.selected != null) {

        let visibleSerch = document.querySelector(".window__search").style.display == 'block';

        if (visibleSerch) {

          $(".window__option").hide();
          $(".window__search").hide();

          $(".messenger .right")[0].style = "width: calc(100% - 360px)";
          setTimeout(() => contactFullName(), 200);

        } else {

          const name = $("#compressed_contact_name").text();

          $(".window__search #title_form").text(GLOBAL_LANG.messenger_window_search_title);
          $(".window__search .list-message").find(".item-searched").remove();
          $(".window__search .search-input").attr("placeholder", GLOBAL_LANG.messenger_window_search_placeholder);
          $(".window__search .search-input").val("");

          $(".window__search .span-search-empty").remove();
          $(".window__search .span-name-user-search").remove();

          $(".window__search .list-message").css({ "text-align": "center" });
          $(".window__search .list-message").prepend(`<span class="span-name-user-search">${GLOBAL_LANG.messenger_window_search_span_search_with.replace("{{name}}", name)}</span>`);

          $(".window__option")[0].style.display = "none";
          $(".window__favorite")[0].style.display = "none";
          $(".window__gallery")[0].style.display = "none";
          $(".window__search")[0].style.display = "block";

          $(".messenger .right")[0].style = "width: calc(100% - 765px)";
          $(".messenger .right")[0].style.display = "block";
          decreaseFullName();
          switchToDark("windowSearch");

        }
      }

    }

    ///////////// INFORMAÇÃO CONTATO ///////////
    if (e.ctrlKey && e.altKey && e.keyCode == 73) {

      if (messenger.Chat.selected != null) {

        var openOption = document.querySelector(".window__option").style.display == "block";

        if (openOption) {

          $(".window__option").hide();
          $(".window__gallery").hide();
          $(".window__search").hide();
          $(".window__favorite").hide();

          $(".messenger .right")[0].style = "width: calc(100% - 360px)";
          setTimeout(() => contactFullName(), 200);

        } else {

          $(".window__option")[0].style.display = "block";
          $(".window__favorite")[0].style.display = "none";
          $(".window__search")[0].style.display = "none";
          $(".window__gallery")[0].style.display = "none";

          $(".messenger .right")[0].style = "width: calc(100% - 765px)";
          $(".messenger .right")[0].style.display = "block";
        }
      }
    }

    /////// GALERIA ////////////
    if (e.ctrlKey && e.altKey && e.keyCode == 71) {

      if (messenger.Chat.selected != null) {

        var openGallery = document.querySelector(".window__gallery").style.display == "block";

        if (openGallery) {

          $(".window__gallery")[0].style.display = "none";

          $(".messenger .right")[0].style = "width: calc(100% - 360px)";
          setTimeout(() => contactFullName(), 200);

        } else {

          ta.chat.queryGallery(0, "midia");
          $(".window__gallery .container-gallery").remove();

          $(".window__gallery .gridDocument").hide();
          $(".window__gallery .titleSelected").hide();
          $(".window__gallery #view-tem-gallery").hide();
          $(".window__gallery #arrow-download-gallery").hide();

          $(".window__option")[0].style.display = "none";
          $(".window__favorite")[0].style.display = "none";
          $(".window__search")[0].style.display = "none";
          $(".window__gallery")[0].style.display = "block";

          $(".messenger .right")[0].style = "width: calc(100% - 765px)";
          $(".messenger .right")[0].style.display = "block";
          $(".window__gallery #col-gallery").prepend(`<img src="./assets/img/loads/loading_2.gif" class="load_gallery">`);

        }
      }
    }

    /////// ETIQUETAS
    if (e.ctrlKey && e.altKey && e.keyCode == 69) {

      if ($(".messenger .body .chat").hasClass("hide_max") == false) {

        if (document.querySelector(".def-modal") == null) {

          this.disabled = true;
          ta.label.queryLabel();

        } else {

          $("#bgBoxMessenger").remove();
          $(".def-modal").remove();

        }
      }
    }

  });


  $('body').on("click", "#starredMessage", () => {

    let status = 1;
    let key_from_me;

    quoted_id = item_id;

    if ($("#" + quoted_id).find("div").hasClass("right")) {
      key_from_me = 2;
      ta.chat.starred(quoted_id, status, key_from_me, "false");
      $("#" + quoted_id).find("div").find(".bottom").append(`<i class="fas fa-star icon-starred white-star"></i>`);
    } else {
      key_from_me = 1
      ta.chat.starred(quoted_id, status, key_from_me, "false");
      $("#" + quoted_id).find("div").find(".bottom").append(`<i class="fas fa-star icon-starred"></i>`);
    }


    $(".popmenu").remove();
    $("#boxPopMenu").remove();
    $(".none_starred").remove();
    bShowPopMenu = false;

    let v;
    let longitude;
    let media_url;
    let media_type;
    let id_message;
    let media_title;
    let thumb_image;
    let media_caption;
    let msgQuoted = {};
    let media_mime_type;
    let creation = $("#" + quoted_id).attr("data-index");
    let data = $("#" + quoted_id).find("div").find(".body").find("span").text();

    let typeClass = $("#" + quoted_id).find("div")[1].className;

    switch (typeClass.split(" ")[0]) {
      case "textMessage":
        if ($("#" + quoted_id).find(".textMessage").find(".container-quoted")[0] != undefined) {
          msgQuoted = pushQuoted(quoted_id, $("#" + quoted_id).find(".textMessage").find(".container-quoted")[0].className);
        }
        media_type = 1;
        break;

      case "AudioMessage":
        media_type = 2;
        media_url = $("#" + quoted_id).find(".AudioMessage").find(".body").find("audio").attr("src");
        break;

      case "ImageMessage":
        media_type = 3;
        media_caption = $("#" + quoted_id).find(".ImageMessage").find(".body").find("span").text();
        media_url = $("#" + quoted_id).find(".ImageMessage").find(".body").find("img").attr("src");
        break;

      case "DocumentMessage":
        media_title = $("#" + quoted_id).find(".DocumentMessage").find(".body").find(".bodyDocument").find("span").text();
        media_url = $("#" + quoted_id).find(".DocumentMessage").attr("data-url");

        media_type = 4;
        v = media_url.split("/v/")[1];

        switch (v.split(".")[1]) {
          case "xls":
          case "xlsx":
            media_mime_type = "application/vnd.ms-excel";
            break;

          case "csv":
          case "doc":
          case "docx":
            media_mime_type = "application/octet-stream";
            break;

          case "pdf":
            media_mime_type = "application/pdf";
            thumb_image = $("#" + quoted_id).find(".documentMessage").find(".body").find(".thumbnail").find("img").attr("src");
            break;

          default:
            break;
        }
        break;

      case "VideoMessage":
        media_caption = $("#" + quoted_id).find(".VideoMessage").find(".bottom").find(".span").text();
        media_url = $("#" + quoted_id).find(".VideoMessage").find(".body").find("video").attr("src");
        media_type = 5;
        break;

      case "ZipMessage":
        media_title = $("#" + quoted_id).find(".ZipMessage").find(".body").find("span").text();
        media_url = $("#" + quoted_id).find(".ZipMessage").attr("data-url");

        media_type = 10;
        v = media_url.split("/f/")[1];

        switch (v.split(".")[1]) {
          case "xls":
          case "xlsx":
            media_mime_type = "application/vnd.ms-excel";
            break;

          case "csv":
          case "doc":
          case "docx":
            media_mime_type = "application/octet-stream";
            break;

          case "txt":
            media_mime_type = "text/plain";
            thumb_image = $("#" + quoted_id).find(".ZipMessage").attr("data-url");
            media_title = $("#" + quoted_id).find(".ZipMessage").find(".body").find("span").text();
            break;

          default:
            break;
        }

        break;
      case "LocationMessage":

        media_type = 7;
        longitude = $("#" + quoted_id).find(".locationMessage").find(".body").find(".thumbnail").find("a").attr("href");
        break;

      case "ContactMessage":

        media_type = 9;
        media_caption = $("#" + quoted_id).find("div").find(".body").find("span").text();
        id_mesage = $("#" + quoted_id).find(".ContactMessage").find(".body").find("span").attr("id").split("name_")[1];
        data = $("#" + quoted_id).find(".ContactMessage").find(".body").find(".button").find("input").attr("name");
        break;

      default:
        break;
    }

    updateStarred({
      data: data,
      token: quoted_id,
      creation: creation,
      longitude: longitude,
      media_url: media_url,
      media_type: media_type,
      id_message: id_message,
      media_title: media_title,
      thumb_image: thumb_image,
      key_from_me: key_from_me,
      media_caption: media_caption,
      media_mime_type: media_mime_type,
      key_remote_id: localStorage.getItem("userToken"),
      quoted: {
        data: msgQuoted.data,
        title: msgQuoted.title,
        media_url: msgQuoted.media_url,
        media_type: msgQuoted.media_type,
        media_mime_type: msgQuoted.media_mime_type
      }
    });

    $(".settings_starred").show();

  });


  $('body').on("click", "#disStarredMessage", () => {

    quoted_id = item_id;
    let status = 2;

    if ($("#" + quoted_id).find("div").hasClass("right")) {
      ta.chat.starred(quoted_id, status, 2, "false");
    } else {
      ta.chat.starred(quoted_id, status, 1, "false");
    }

    $("#" + quoted_id).find("div").find(".bottom").find("i").remove();

    $(".popmenu").remove();
    $("#boxPopMenu").remove();
    $("[name=" + "_str" + quoted_id + "]").hide(300);

    if ($(".starredMessage").length <= 1) {

      $(".settings_starred").hide();
      $("#starred_box").append(`<span class='none_starred'>${GLOBAL_LANG.messenger_window_favorite_no_messages}</span>`);
    }

    setTimeout(function () { $("[name=" + "_str" + quoted_id + "]").remove(); }, 1000);
    bShowPopMenu = false;
  });


  $('body').on("click", ".starredMessage", function (e) {

    let creation = this.id;
    let item = $(this).attr('name');

    clearTimeout(QUERY_MESSAGES_CHAT);

    ITEM_FOCUSED = item.split("_str")[1];
    creation = creation.split("_str")[1];

    SCROLL_BLOCK = true;
    FORCE_SCROLL_DOWN = true;
    LOCK_FOCUS = false;

    $(".chat .messages").each((idx, elm) => {
      if (elm.style.display != "none") {
        $(elm).find("div").remove();
      }
    });

    ta.chat.queryMessages(ta.key_remote_id, creation, false);

    e.stopPropagation();
  });

  $('body').on("click", ".starredMessage .cancel-click", function (e) {

    let container_id = this.parentNode.id;

    if (container_id == '' || container_id == undefined || container_id == null) {
      container_id = this.parentNode.parentNode.id;
    }

    let starredMessage = document.getElementById(container_id).parentNode;
    starredMessage.click();

  });

  $('.starred-body').bind('scroll', function () {

    if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {

      let load = localStorage.getItem("load_starred");

      if (load != "false") {
        $(".ongrups").eq(-1).find(".starredMessage").eq(-1).append(`<img src="./assets/img/loads/loading_1.gif" class="load_starred_footer check">`);
      }

      if ($(".starredMessage").length > 0) {

        let timetamp = $(".starredMessage").eq(-1)[0].attributes[1].nodeValue;
        timetamp = timetamp.split("_str")[1];

        ta.chat.queryStarred(timetamp);
      }
    }
  });


  $('body').on("mouseover", ".window__gallery .starred-body .starredMessage .container ", function () {

    let id = this.id;
    let _id2 = $("#" + id).parent().attr("id");

    $("#" + _id2).find(".container").find(".str-dropdown").css({ "display": "block" });

  });

  $('body').on("mouseout", ".window__gallery .starred-body .starredMessage .container ", function () {

    let id = this.id;
    let _id2 = $("#" + id).parent().attr("id");

    $("#" + _id2).find(".container").find(".str-dropdown").css({ "display": "none" });
  });


  $('body').on("click", '.str-dropdown', function (e) {

    $(".str-popmenu").remove();

    let id = this.id;
    let browser = $(document).width();
    let key_id = id.split("down")[1];

    let strPopmenu = document.createElement("div");
    strPopmenu.id = "popmenu";
    strPopmenu.style.zIndex = 999;

    let dark = localStorage.getItem("night");

    if (dark == "true") {
      strPopmenu.className = "str-popmenu _dk";
    } else {
      strPopmenu.className = "str-popmenu";
    }
    strPopmenu.style.marginLeft = event.clientX - 40 + "px";
    strPopmenu.style.marginTop = event.clientY - 12 + "px";

    let starred = document.createElement("div");
    starred.className = "item-prpmenu disfavor-element";
    starred.id = "_st" + key_id;
    starred.style.paddingTop = "9px";
    starred.style.paddingBottom = "9px";

    let starredItem = document.createElement("span");
    starredItem.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_unfavorite;
    starredItem.style.marginLeft = "15px";

    let quoted = document.createElement("div");
    quoted.className = "item-prpmenu quoted-element";
    quoted.id = "_resp" + key_id;
    quoted.style.paddingTop = "8px";
    quoted.style.paddingBottom = "9px";
    quoted.style.display = "none";

    let quotedItem = document.createElement("span");
    quotedItem.textContent = "Responder";
    quotedItem.style.marginLeft = "15px";
    quotedItem.style.display = "none";

    let forward = document.createElement("div");
    forward.className = "item-prpmenu";
    forward.id = "forward";
    forward.style.paddingTop = "8px";
    forward.style.paddingBottom = "9px";

    let forwardItem = document.createElement("span");
    forwardItem.textContent = GLOBAL_LANG.messenger_tooltip_popmenu_forward;
    forwardItem.style.marginLeft = "15px";

    starred.appendChild(starredItem);
    quoted.appendChild(quotedItem);
    forward.appendChild(forwardItem);

    let boxPopMenu = document.createElement('div');
    boxPopMenu.style.display = "block";
    boxPopMenu.id = "boxPopMenu";
    boxPopMenu.style.zIndex = "149";
    boxPopMenu.style.top = "0px";
    boxPopMenu.style.left = "0px";
    boxPopMenu.style.position = "fixed";
    boxPopMenu.style.width = "100%";
    boxPopMenu.style.height = "100%";
    boxPopMenu.onclick = function () {
      $("#boxPopMenu").remove();
      $(".str-popmenu").remove();
    };

    document.body.appendChild(boxPopMenu);
    strPopmenu.appendChild(quoted);
    strPopmenu.appendChild(forward);
    strPopmenu.appendChild(starred);
    document.body.appendChild(strPopmenu);

    let box = document.getElementById("_st" + key_id);
    let coordinates = box.getBoundingClientRect();

    browser = browser - 225;
    if (coordinates.x > browser) {
      $("#_st" + key_id).parent().css({ "margin-left": browser });
    }

    item_id = key_id;
    // e.stopPropagation();
  });

  $('body').on("click", ".disfavor-element", function (e) {

    let id;
    let status;
    let key_id;

    status = 2;
    id = this.id;
    key_id = id.split("_st")[1];

    if ($("[name=" + "_str" + key_id + " ]").find(".container").hasClass("_righ")) {
      ta.chat.starred(key_id, status, 2, "false");
    } else {
      ta.chat.starred(key_id, status, 1, "false");
    }

    $("#" + key_id).find("div").find(".bottom").find("i").remove();
    setTimeout(function () { $("[name=" + "_str" + key_id + "]").remove(); }, 1000);
    $("[name=" + "_str" + key_id + "]").hide(300);
    $("#_st" + key_id).remove();
    $(".str-popmenu").remove();
    $("#boxPopMenu").remove();

    if ($(".starredMessage").length <= 1) {

      $(".settings_starred").hide();
      $("#starred_box").append(`<span class='none_starred'>${GLOBAL_LANG.messenger_window_favorite_no_messages}</span>`);
    }

    e.stopPropagation();

  });

  $('body').on("click", ".quoted-element", function (e) {
    quoted_id = item_id;

    let text = Util.doTruncarStr($("#" + quoted_id + " .textMessage .body span").html(), 256);

    $(".reply-message .message").html("<span>" + text + "</span>");

    switch ($("#" + item_id).find("div")[1].className) {
      case "textMessage left":
        $(".reply-message .message").html("<span>" + text + "</span>").css({ "padding": 10 });

        break;
      case "ImageMessage left":

        var media_url = $("#" + quoted_id).find(".ImageMessage").find(".body").find("img").attr("src");
        $(".reply-message .message").html(`<img class="quotedImage" src=${media_url}>
                <span class="quotedImageSpan"><i class="fas fa-camera"></i> Imagem</span>`).css({ "padding": 0 });

        break;
      case "VideoMessage left":
        $(".reply-message .message").html(`<span><i class="fas fa-video"></i> Vídeo</span>`).css({ "padding": 10 });

        break;
      case "AudioMessage left":
        $(".reply-message .message").html(`<span><i class="fas fa-microphone"> Áudio</i></span>`).css({ "padding": 10 });

        break;
      case "DocumentMessage left":

        let urlDocumentLeft = $("#" + quoted_id).find(".DocumentMessage").attr("data-url");
        let tex_doc_left = $("#" + quoted_id).find(".DocumentMessage").find(".body").find("span").text();

        urlDocumentLeft = urlDocumentLeft.split("/f/")[1];

        switch (urlDocumentLeft.split(".")[1]) {
          case "pdf":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/pdf.svg">
						<span class="text">${tex_doc_left}</span>`).css({ "padding": 10 });

            break;
          case "doc":
          case "csv":
          case "docx":
            $(".reply-message .message").html(`
								<img class="quoted-arquive" src="${document.location.origin}/assets/icons/texto.svg">
								<span class="text">${tex_doc_left}</span>`).css({ "padding": 10 });

            break;
          default:
            $(".reply-message .message").html(`<span><i class="fas fa-file"></i></span>
						<span class="text">${tex_doc_left}</span>`).css({ "padding": 10 });
            break;
        }
        break;

      case "ZipMessage left":

        let urlZipLeft = $("#" + quoted_id).find(".ZipMessage").attr("data-url");
        let text_arq_left = $("#" + quoted_id).find(".ZipMessage").find(".body").find("span").text();

        urlZipLeft = urlZipLeft.split("/f/")[1];

        switch (urlZipLeft.split(".")[1]) {
          case "doc":
          case "csv":
          case "docx":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/texto.svg">
						<span class="text">${text_arq_left}</span>`).css({ "padding": 10 });
            break;

          case "xls":
          case "xlsx":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/excel.svg">
						<span class="text">${text_arq_left}</span>`).css({ "padding": 10 });

            break;
          case "txt":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/txt.svg">
						<span class="text">${text_arq_left}</span>`).css({ "padding": 10 });

            break;
          default:
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/new-document.svg">
						<span class="text">${text_arq_left}</span>`).css({ "padding": 10 });

            break;
        }
        break;

      case "ContactMessage left":
        $(".reply-message .message").html(`<span><i class="fas fa-id-badge"></i> Contato</i></span>`).css({ "padding": 10 });
        break;
      case "LocationMessage left":
        $(".reply-message .message").html(`<span><i class="fas fa-map-marked-alt"></i> Localização</i></span>`).css({ "padding": 10 });
        break;
      case "textMessage right":
        $(".reply-message .message").html("<span>" + text + "</span>").css({ "padding": 10 });
        break;
      case "ImageMessage right":
        media_url = $("#" + quoted_id).find(".ImageMessage").find(".body").find("img").attr("src");
        $(".reply-message .message").html(`<img class="quotedImage" src=${media_url}>
                <span class="quotedImageSpan"><i class="fas fa-camera"></i> Imagem</span>`).css({ "padding": 0 });
        break;
      case "VideoMessage right":
        $(".reply-message .message").html(`<span><i class="fas fa-video"></i> Vídeo</span>`).css({ "padding": 10 });
        break;
      case "AudioMessage right":
        $(".reply-message .message").html(`<span><i class="fas fa-microphone"> Áudio</i></span>`).css({ "padding": 10 });
        break;
      case "DocumentMessage right":

        let urlDocumentRight = $("#" + quoted_id).find(".DocumentMessage").attr("data-url");
        let tex_doc_right = $("#" + quoted_id).find(".DocumentMessage").find(".body").find("span").text();

        urlDocumentRight = urlDocumentRight.split("/f/")[1];

        switch (urlDocumentRight.split(".")[1]) {
          case "pdf":
            $(".reply-message .message").html(`<img class="quoted-arquive" src="${document.location.origin}/assets/icons/pdf.svg">
						 <span class="text">${tex_doc_right}</span>`).css({ "padding": 10 });
            break;

          case "doc":
          case "csv":
          case "docx":
            $(".reply-message .message").html(`<img class="quoted-arquive" src="${document.location.origin}/assets/icons/texto.svg">
					     <span class="text">${tex_doc_right}</span>`).css({ "padding": 10 });
            break;

          default:
            $(".reply-message .message").html(`<span><i class="fas fa-file"></i></span>
					      <span class="text">${tex_doc_right}</span>`).css({ "padding": 10 });
            break;
        }
        break;

      case "ZipMessage right":

        let urlZipRight = $("#" + quoted_id).find(".ZipMessage").attr("data-url");
        let text_arq_right = $("#" + quoted_id).find(".ZipMessage").find(".body").find("span").text();

        urlZipRight = urlZipRight.split("/f/")[1];

        switch (urlZipRight.split(".")[1]) {
          case "doc":
          case "csv":
          case "docx":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/texto.svg">
						<span class="text">${text_arq_right}</span>`).css({ "padding": 10 });
            break;

          case "xls":
          case "xlsx":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/excel.svg">
						<span class="text">${text_arq_right}</span>`).css({ "padding": 10 });

            break;
          case "txt":
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/txt.svg">
						<span class="text">${text_arq_right}</span>`).css({ "padding": 10 });

            break;
          default:
            $(".reply-message .message").html(`
						<img class="quoted-arquive" src="${document.location.origin}/assets/icons/new-document.svg">
						<span class="text">${text_arq_right}</span>`).css({ "padding": 10 });

            break;
        }
        break;

      case "ContactMessage right":
        $(".reply-message .message").html(`<span><i class="fas fa-id-badge"></i> Contato</i></span>`).css({ "padding": 10 });
        break;
      case "LocationMessage right":
        $(".reply-message .message").html(`<span><i class="fas fa-map-marked-alt"></i> Localização</i></span>`).css({ "padding": 10 });
        break;
    }
    $(".messenger .right .chat .reply-message .message").css({ "border-left": "5px solid #4ec545" });
    $(".buttonBottomScroll").hide()
    $(".reply-message").show();
    $(".str-popmenu").remove();
    $(".input-text").focus();
    $(".popmenu").remove();
    $("#" + item_id + " .dropdown").hide();
    $("#boxPopMenu").remove();
    bShowPopMenu = false;

    e.stopPropagation();
  });

  $('body').on('click', '.settings_starred', function (e) {
    $(".settings_star").remove();

    const OpenSettingsStar = function () {

      let dark = localStorage.getItem("night");

      let box = document.createElement('div');

      if (dark == "true") {
        box.className = "settings_star dark";
      } else {
        box.className = "settings_star";
      }

      let item = document.createElement('span');
      item.textContent = GLOBAL_LANG.messenger_window_favorite_unfavorite_all;

      let boxPopMenu = document.createElement('div');
      boxPopMenu.style.display = "block";
      boxPopMenu.id = "boxPopMenu";
      boxPopMenu.style.zIndex = "149";
      boxPopMenu.style.top = "0px";
      boxPopMenu.style.left = "0px";
      boxPopMenu.style.position = "fixed";
      boxPopMenu.style.width = "100%";
      boxPopMenu.style.height = "100%";
      boxPopMenu.onclick = function () {
        $("#boxPopMenu").remove();
        $(".settings_star").remove();
      };


      document.body.appendChild(boxPopMenu);
      box.appendChild(item);
      document.body.appendChild(box)

    }

    document.querySelectorAll(".settings_starred").forEach(b =>
      b.addEventListener("click", OpenSettingsStar())
    );

    e.stopPropagation();
  });

  $('body').on('click', '.settings_star', function (event) {

    let status = 2;
    $(".settings_star").remove();
    $(".icon-starred").remove();
    $("#boxPopMenu").remove();
    $(".ongrups").remove();
    $(".settings_starred").hide();
    $(".none_starred").remove();
    $("#starred_box").append(`<span class='none_starred'>${GLOBAL_LANG.messenger_window_favorite_no_messages}</span>`);

    ta.chat.starred(null, status, null, "true");
    event.stopPropagation();
  });


  $('body').on('keyup', '#teamSearch', () => {
    searchTeam();
  });


  $('body').on('click', '.team-search .icon-close', () => {
    clearSearchTeam();
  });


  $('body').on('change', '#select_ticket_type', (e) => {
    ta.contact.querySubtype(e.target.value);
  });


  $('body').on('click', '.def__closeModal', () => {
    closeModal()
  });


  $('body').on('mouseover', '.notify-form .box label', () => {
    if (bNight === true) {
      $(".notify-form .box .fa-plus").addClass("notify-focus");
    }
    $(".notify-form .box .fa-plus").css({ "box-shadow": "0 4px 4px rgb(0 0 0 / 30%)" });
    $(".notify-form .box .title").addClass("notify-focus");
  });


  $('body').on('mouseout', '.notify-form .box label', () => {
    if (bNight === true) {
      $(".notify-form .box .fa-plus").removeClass("notify-focus");
    }
    $(".notify-form .box .fa-plus").css({ "box-shadow": "0 4px 4px rgb(0 0 0 / 0%)" });
    $(".notify-form .box .title").removeClass("notify-focus");
  });


  $(".icon-label-info").live("click", function () {
    ta.label.queryLabel();
  });


  $("#iconAddLabelInfo").live("click", function () {
    this.disabled = true;
    ta.label.queryLabel();
  });


  $("#chat-labels").live("click", function () {
    $("#iconAddLabelInfo").click();
  });


  $("#label-hidden").live("click", function () {
    $("#iconAddLabelInfo").click();
  });


  $(document).on("click", ".item-label-hidden", function () {
    $("#iconAddLabelInfo").click();
  });

  $(document).on("click", ".item-label-contact", function () {
    $("#iconAddLabelInfo").click();
  });


  $('.buttonBottomScroll').on('click', () => {
    BUTTON_BOTTOM_SCROLL = true;
    ta.chat.queryMessages(ta.key_remote_id, 0);
  });


});


function closeModal() {
  $("#bgBoxMessenger").remove();
  $(".def-modal").remove();
  $(".bgboxContactForward").hide();
  $(".modalContactForward").hide();
  $(".team-bg-box").hide();
  $(".team-modal").hide();
  $(".bgbox").hide();
  $(".modalBoxTemplete").hide();
}


function listSubtype(json) {

  $(".option-subtype-ticket").remove();

  const select = document.getElementById("select_subtype");

  for (let i = 0; i < json.subtype.length; i++) {

    const option = document.createElement("option");
    option.innerHTML = json.subtype[i].name;
    option.className = "option-subtype-ticket";
    option.value = json.subtype[i].id_ticket_type;

    select.appendChild(option);
  }
}


function searchTeam() {

  const team_item = document.querySelectorAll(".team-item");
  const teamSearch = document.getElementById("teamSearch");

  const iconSearch = document.querySelector(".team-search .icon-search");
  const iconClose = document.querySelector(".team-search .icon-close");

  if (teamSearch.value.length > 0) {

    iconSearch.style.display = "none";
    iconClose.style.display = "block";

  } else {

    iconSearch.style.display = "block";
    iconClose.style.display = "none";
  }

  let word = [];
  let user, departament, search;

  for (elm of team_item) {

    user = elm.children[0].children[1].children[0].innerHTML;
    departament = elm.children[0].children[2].children[0].innerHTML;

    user = removeAccents(user);
    departament = removeAccents(departament);

    user = user.toLowerCase();
    departament = departament.toLowerCase();

    word = [user + " " + departament];

    search = removeAccents(teamSearch.value);
    search = search.toLowerCase();

    word.filter((word) => {
      if (word.indexOf(search) != -1) elm.style.display = "block"; else elm.style.display = "none";
    });

  }

}


function clearSearchTeam() {

  const team_item = document.querySelectorAll(".team-item");
  const teamSearch = document.getElementById("teamSearch");

  const iconSearch = document.querySelector(".team-search .icon-search");
  const iconClose = document.querySelector(".team-search .icon-close");

  iconSearch.style.display = "block";
  iconClose.style.display = "none";

  teamSearch.value = "";

  for (elm of team_item) {
    elm.style.display = "block";
  }

}


function createListTeam(json) {

  $("#bgboxTeam").fadeIn("fast");
  $("#modalQueryTeam").fadeIn("fast");
  $("#modalQueryTeam .icon-close").hide();

  const teamSearch = document.getElementById("teamSearch");
  teamSearch.value = "";

  const team_modal = document.querySelectorAll(".team-modal .team-item");
  for (elm of team_modal) elm.remove();

  const team_body = document.querySelector(".team-modal .team-body");

  const load = document.createElement("img");
  load.className = "load";
  load.id = "loadModalTeam";
  load.src = document.location.origin + "/assets/img/loads/loading_2.gif";
  load.style.width = "75px";
  load.style.margin = "0";
  load.style.position = "absolute";
  load.style.top = "47%";
  load.style.left = " 50%";
  load.style.marginRight = "-50 %";
  load.style.transform = "translate(-50%, -50%)";

  team_body.appendChild(load);

  document.getElementById("loadModalTeam").remove();

  for (let i = 0; i < json.items.length; i++) {

    const team_body = document.querySelector(".team-body");

    const item = document.createElement("div");
    item.className = "team-item";
    item.id = json.items[i].key_remote_id;

    const card = document.createElement("div");
    card.className = "team-card";

    const profile = document.createElement("div");
    profile.className = "profile";

    const img = document.createElement("img");
    img.src = "https://files.talkall.com.br:3000/p/" + json.items[i].key_remote_id + ".jpeg";
    img.id = json.items[i].key_remote_id + "_imgProfileTeam";

    const boxName = document.createElement("div");
    boxName.className = "name";

    const name = document.createElement("span");
    name.innerHTML = json.items[i].name;

    const boxSector = document.createElement("div");
    boxSector.className = "sector";

    const sector = document.createElement("span");
    sector.innerHTML = json.items[i].department;

    profile.appendChild(img);

    boxName.appendChild(name);
    boxSector.appendChild(sector);

    card.appendChild(profile);
    card.appendChild(boxName);
    card.appendChild(boxSector);

    item.appendChild(card);

    team_body.appendChild(item);

    switchToDark("modalQueryTeam");
  }
}


function previewsProfile(key_remote_id) {

  if (key_remote_id != null) {

    var imgurl = "https://files.talkall.com.br:3000/p/" + key_remote_id + ".jpeg";
    $.ajax({
      type: "GET",
      url: imgurl,
      error: function (response) {
        let url = document.location.origin + "/assets/img/avatar.svg"
        $("#imgProfile").attr("src", url);
        $("#imgPreviewsProfile").attr("src", url);
      },
      success: function (response) {
        $("#imgProfile").attr("src", imgurl);
        $("#imgPreviewsProfile").attr("src", imgurl);
      }
    });

  }
}


function doneTyping() {
  ta.chat.stoped();
}


function doneSearch() {
  ta.contact.queryContact($(".find input").val());
}


function doneSearchQuick() {
  if ($(".input-text").text().indexOf("/") != -1) {
    if ($(".input-text").text().length < 50) {
      ta.Quick.queryQuick($(".input-text").text());
    }
  }
}


function doneSearchProduct() {
  if ($("#search-product").val().length < 50) {
    ta.Product.queryProduct($("#search-product").val());
  }
}


function documentReady() {
  $("#left-settings-profile").hide();
  $("#left-settings").hide();
  $("#sub-settings-color").hide();
  $("#sub-settings-notification").hide();
  $("#sub-settings-tema").hide();
  $("#sub-settings-help").hide();
  $("#sub-settings-font").hide();
  $('#settings_tooltip').hide();
}


function openCardList(itens = null) {

  $(".bgboxCardList").fadeIn("fast");
  $(".modalCardList").fadeIn("fast");
  $("#itensCardList").find(".body").remove();
  $("#order_status").text("");

  $("#item_count").html(`<span>${itens[0].item_count} itens </span><br>`);
  $("#total_product").html(`<span>Total R$  ${itens[0].total_product}</span>`);
  $("#order_date").html(`<span>${itens[0].order_hour},  ${itens[0].order_date.replace(/-/gi, "/")}</span>`);
  $("#order_status").append(`<option value="${itens[0].id_order_status}">${itens[0].order_status}</option>`);
  $("#id_messages_order").attr("value", itens[0].id_messages_order);

  for (let i = 0; i < itens.length; i++) {
    $("#itensCardList").append(`
                            <div class="body">
                               <div class="itens">
                                    <div class="container_img">
									    <img src="${itens[i].media_url}" alt="" style="width: 100%; height: 100%">
                                    </div>
                                    <div class="title">
                                        <span>${itens[i].name}</span>
                                    </div>
                                    <div class="cards">
                                        <span class="spanPrice">R$  ${itens[i].price}  Qtd.: ${itens[i].quantity}</span>   
                                    </div>
                                </div>
                            </div>    
                         `);
  }
}


async function galleryMessenger(json) {

  $(".n_found_midia").remove();

  for (var i = 0; i < json.itens.length; i++) {

    switch (json.itens[i].media_type) {
      case 3:

        var container = document.createElement("div");
        container.className = "container-gallery";
        container.id = "_g" + json.itens[i].creation;
        container.setAttribute("name", "_grid" + json.itens[i].key_id);

        var linkImg = document.createElement("a");
        linkImg.target = "_blank";
        linkImg.href = json.itens[i].media_url == null ? document.location.origin + '/assets/img/photo_unavailable.png' : json.itens[i].media_url;

        var boxImg = document.createElement("div");
        boxImg.className = "box";

        var img = document.createElement("img");
        img.className = "img-gallery";

        if (json.itens[i].media_url != null) {

          if (json.itens[i].media_url.startsWith("https://files")) {
            const data = await fetch(json.itens[i].media_url);
            const blob = await data.blob();
            img.src = await getPreviewImg(blob);
          } else {
            img.src = json.itens[i].media_url;
          }

        } else {
          img.src = document.location.origin + '/assets/img/photo_unavailable.png';
        }

        var shadowTop = document.createElement("div");
        shadowTop.className = "shaw_imgT shadow-top";

        var shadowBottom = document.createElement("div");
        shadowBottom.className = "shadow-bottom";

        var iconImg = document.createElement("i");
        iconImg.className = "fas fa-check-circle icon-img-gallery custon_icon_deselected";

        boxImg.appendChild(img);
        boxImg.appendChild(shadowTop);
        boxImg.appendChild(shadowBottom);
        boxImg.appendChild(iconImg);
        linkImg.appendChild(boxImg);
        container.appendChild(linkImg);

        $("#_g" + json.itens[i].creation).remove();
        document.getElementById("col-gallery").appendChild(container);
        $(".window__gallery .custon_icon_deselected").hide();
        break;

      case 4:

        var container = document.createElement("div");
        container.className = "container-documents";
        container.id = "_g" + json.itens[i].creation;
        container.setAttribute("name", "_gridDoc" + json.itens[i].key_id);

        var document_message_gallery = document.createElement("div");
        document_message_gallery.className = "document-message-gallery";
        document_message_gallery.classList.add(json.itens[i].key_from_me == 2 ? 'doc-right' : 'doc-left');
        document_message_gallery.style.height = "85px";

        var link = document.createElement("a");
        link.target = "_blank";
        link.href = json.itens[i].media_url;

        document_message_gallery.appendChild(link);

        var box = document.createElement("div");
        box.className = "box";

        var icon_docum = document.createElement("img");

        switch (json.itens[i].media_mime_type) {
          case "application/pdf":
            icon_docum.className = "icon-pdf";
            icon_docum.src = document.location.origin + "/assets/icons/pdf.svg";
            break;
          case "application/vnd.ms-excel":
          case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
            icon_docum.className = "icon-exel";
            icon_docum.src = document.location.origin + "/assets/icons/excel.svg";
            break;

          case "application/octet-stream":
            icon_docum.className = "icon-exel";
            icon_docum.src = document.location.origin + "/assets/icons/texto.svg";
            break;

          case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
            icon_docum.className = "icon-exel";
            icon_docum.src = document.location.origin + "/assets/icons/texto.svg";
            break;

          case "text/plain":
            icon_docum.className = "icon-txt";
            icon_docum.src = document.location.origin + "/assets/icons/txt.svg";

            break;
          default:
            icon_docum.className = "icon-exel";
            icon_docum.src = document.location.origin + "/assets/icons/new-document.svg";
            break;
        }

        var icon_arrow = document.createElement("i");
        icon_arrow.className = "far fa-arrow-alt-circle-down";

        var date = document.createElement("span");
        date.innerHTML = formatDate(json.itens[i].date);
        date.className = "date";

        var checkbox = document.createElement("input");
        checkbox.className = "checkbox_document";
        checkbox.id = "_ck" + json.itens[i].id_message;
        checkbox.type = "checkbox";

        var title = document.createElement("span");
        title.className = "title";
        title.innerHTML = json.itens[i].media_name != undefined ? Util.doTruncarStr(json.itens[i].media_name, 15) : "";

        link.appendChild(box);
        box.appendChild(icon_docum);
        box.appendChild(icon_arrow);
        box.appendChild(date);
        box.appendChild(checkbox);
        box.appendChild(title);

        $("#_g" + json.itens[i].creation).remove();
        container.appendChild(document_message_gallery);
        document.getElementById("col-document").appendChild(container);
        break;

      case 5:

        var container = document.createElement("div");
        container.className = "container-gallery video";
        container.id = "_g" + json.itens[i].creation;
        container.setAttribute("name", "_grid" + json.itens[i].key_id);

        var linkImg = document.createElement("a");
        linkImg.target = "_blank";
        linkImg.href = json.itens[i].media_url == null ? document.location.origin + '/assets/img/photo_unavailable.png' : json.itens[i].media_url;

        var boxImg = document.createElement("div");
        boxImg.className = "box";

        var video = document.createElement("video");
        video.src = json.itens[i].media_url;
        video.style.width = "100%";
        video.style.height = "100%";
        video.style.borderRadius = "2px";
        video.autoplay = true;
        video.loop = true;
        video.muted = true;

        var shadowTop = document.createElement("div");
        shadowTop.className = "shaw_imgT shadow-top";

        var shadowBottom = document.createElement("div");
        shadowBottom.className = "shadow-bottom";

        var iconImg = document.createElement("i");
        iconImg.className = "fas fa-check-circle icon-img-gallery custon_icon_deselected";

        boxImg.appendChild(video);
        boxImg.appendChild(shadowTop);
        boxImg.appendChild(shadowBottom);
        boxImg.appendChild(iconImg);
        linkImg.appendChild(boxImg);
        container.appendChild(linkImg);

        $("#_g" + json.itens[i].creation).remove();
        document.getElementById("col-gallery").appendChild(container);
        $(".window__gallery .custon_icon_deselected").hide();
        break;

      case 10:

        var container = document.createElement("div");
        container.className = "container-documents";
        container.id = "_g" + json.itens[i].creation;
        container.setAttribute("name", "_gridDoc" + json.itens[i].key_id);

        var document_message_gallery = document.createElement("div");
        document_message_gallery.className = "document-message-gallery";
        document_message_gallery.classList.add(json.itens[i].key_from_me == 1 ? 'doc-right' : 'doc-left');

        var link = document.createElement("a");
        link.target = "_blank";
        link.href = json.itens[i].media_url;

        var box = document.createElement("div");
        box.className = "box";

        var icon_docum = document.createElement("img");

        switch (json.itens[i].media_mime_type) {
          case "application/vnd.ms-excel":
          case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
            icon_docum.className = "icon-exel";
            icon_docum.src = document.location.origin + "/assets/icons/excel.svg";
            break;

          case "application/octet-stream":
            icon_docum.className = "icon-exel";
            icon_docum.src = document.location.origin + "/assets/icons/texto.svg";
            break;

          case "text/plain":
            icon_docum.className = "icon-txt";
            icon_docum.src = document.location.origin + "/assets/icons/txt.svg";

            break;
          default:
            icon_docum.className = "icon-exel";
            icon_docum.src = document.location.origin + "/assets/icons/new-document.svg";
            break;
        }

        var icon_arrow = document.createElement("i");
        icon_arrow.className = "far fa-arrow-alt-circle-down";

        var date = document.createElement("span");
        date.innerHTML = Util.FormatShortTime(json.itens[i].date);
        date.className = "date";

        var checkbox = document.createElement("input");
        checkbox.className = "checkbox_document";
        checkbox.id = "_ck" + json.itens[i].id_message;
        checkbox.type = "checkbox";
        checkbox.style.marginTop = "24px";

        var title = document.createElement("span");
        title.className = "title";
        title.innerHTML = Util.doTruncarStr(json.itens[i].title, 11);

        link.appendChild(box);
        box.appendChild(icon_docum);
        box.appendChild(icon_arrow);
        box.appendChild(date);
        box.appendChild(checkbox);
        box.appendChild(title);

        $("#_g" + json.itens[i].creation).remove();
        container.appendChild(document_message_gallery);
        document.getElementById("col-document").appendChild(container);
        break;

      default:
        break;
    }
  }

  setTimeout(() => {
    $(".window__gallery .container-gallery").css({ "display": "inline-block" });
    $(".window__gallery #load_more_images_galley").remove();
    $(".window__gallery .load_gallery").remove();
    getMoreImageGallery = true;
  }, 400);
}


function getPreviewImg(blob) {

  return new Promise((resolve, reject) => {

    const reader = new FileReader();
    reader.readAsDataURL(blob);
    reader.onloadend = () => resolve(reader.result);
  });
}


async function galleryPreviewMessenger(json) {

  $("#previewGallery").find(".container").remove();

  for (let i = 0; i < json.itens.length; i++) {

    const url = json.itens[i].media_url;
    const previewGallery = document.getElementById("previewGallery");

    const container = document.createElement("div");
    container.className = "container";

    const shadow = document.createElement("div");
    shadow.className = "shadow-img";

    const img = document.createElement("img");
    img.className = "img-gallery";

    const video = document.createElement("video");
    video.className = "img-gallery";

    if (json.itens[i].media_type == 3) {
      img.src = url;
      previewGallery.append(container);
      container.append(img);
    } else if (json.itens[i].media_type == 5) {
      video.src = url;
      previewGallery.append(container);
      container.append(video);
    } else {
      img.src = document.location.origin + "/assets/img/empty_image.jpg";
      previewGallery.append(container);
      container.append(img);
    }
    container.append(shadow);

  }
}


function CatalogMessenger(json) {


  $(".modalCatalog").find(".body").find(".container").remove();
  $(".load_search_catalog").remove();
  $(".not_found_catalog").remove();
  $(".icon-search-catalog").show();

  for (let i = 0; i < json.itens.length; i++) {

    if (json.itens[i].is_approved == 2 && json.itens[i].is_rejected == 1 || json.itens[i].is_appealed == 2) {
      $(".modalCatalog").find(".body")
        .append(`<div class="container" name="is_clock" id="_check${json.itens[i].id_product}">
						 <div class="container-inner">
							 <div class="box">
							   <img class="iconClock" src="${document.location.origin}/assets/img/iconClock.svg">
							   <input class="checkedCatalog" name="${json.itens[i].wa_product_id}" type="checkbox" id="_ck${json.itens[i].id_product}">
							   <img class="img" src="${json.itens[i].media_url}" alt="">  
							 </div>
							 <div class="details">
								<div class="title">
							    	<span>${json.itens[i].title}</span>
								</div>
								<div class="price">
								   <span>${json.itens[i].price == undefined ? "" : "R$ " + json.itens[i].price}</span>
								</div>
							 </div>
						</div>
			     </div>`);

    }

    if (json.itens[i].is_approved == 1 && json.itens[i].is_rejected == 1) {
      $(".modalCatalog").find(".body")
        .append(` <div class="container" name="is_approved" id="_check${json.itens[i].id_product}">
						 <div class="container-inner">
							 <div class="box">
							   <input class="checkedCatalog" name="${json.itens[i].wa_product_id}" type="checkbox" id="_ck${json.itens[i].id_product}">
							   <img class="img" src="${json.itens[i].media_url}" alt="">  
							 </div>
							 <div class="details">
								<div class="title">
							    	<span>${json.itens[i].title}</span>
								</div>
								<div class="price">
								   <span>${json.itens[i].price == undefined ? "" : "R$ " + json.itens[i].price}</span>
								</div>
							 </div>
						</div>
			     </div>`);
    }

    if (json.itens[i].is_approved == 2 && json.itens[i].is_rejected == 2 && json.itens[i].is_appealed == 1) {
      $(".modalCatalog").find(".body")
        .append(` <div class="container conteiner-rejected" name="is_rejected" id="_check${json.itens[i].id_product}">
						 <div class="container-inner">
							 <div class="box">
							   <img class="iconExclamation" src="${document.location.origin}/assets/img/iconExclamation.svg">
							   <input class="checkedCatalog" name="${json.itens[i].wa_product_id}" type="checkbox" id="_ck${json.itens[i].id_product}">
							   <img class="img" src="${json.itens[i].media_url}" alt="">  
							 </div>
							 <div class="details">
								<div class="title">
							    	<span>${json.itens[i].title}</span>
								</div>
								<div class="price">
								   <span>${json.itens[i].price == undefined ? "" : "R$ " + json.itens[i].price}</span>
								</div>
							 </div>
						</div>
			     </div>`);
    }

  }

  $(".modalCatalog .body .container")[0].scrollIntoView();

}


function createGroup(item, timestamp) {

  let date = new Date(timestamp * 1000);
  let data = date.getFullYear() + '/' + date.getMonth() + '/' + date.getDate();

  if (checkDataStar == 0 || checkDataStar == data) {

    if (creationGroup == "true") {

      let group = document.createElement("div");
      group.id = "group" + groupStarred;
      group.dataset.id = "_gp" + timestamp;
      group.dataset.index = timestamp;
      group.className = "ongrups";

      let starredBox = document.getElementById("starred_box");

      group.append(item);
      starredBox.append(group);
      creationGroup = "false";

    } else {

      var group = document.getElementById("group" + groupStarred);
      group.append(item);
    }
    checkDataStar = data;

  } else {

    checkDataStar = data;
    groupStarred++;

    let group = document.createElement("div");
    group.id = "group" + groupStarred;
    group.dataset.id = "_gp" + timestamp;
    group.dataset.index = timestamp;
    group.className = "ongrups";
    creationGroup = "false";

    let starredBox = document.getElementById("starred_box");

    group.append(item);
    starredBox.append(group);
  }

  $("#starred_box").find(".ongrups").sort(function (a, b) {
    return $(b).attr('data-index') - $(a).attr('data-index');
  }).appendTo("#starred_box");

}

function initStarred(container, key_from_me, json) {

  let quoted = document.createElement("div");
  quoted.className = key_from_me == 1 ? "quotedStarred _lf" : "quotedStarred _rgh";

  switch (json.media_type) {
    case "1":

      let spanText = document.createElement("span");
      spanText.textContent = Util.doTruncarStr(json.data, 100);

      quoted.append(spanText);
      container.append(quoted);

      break;
    case "2":

      let audio = document.createElement("audio");
      audio.controls = true;
      audio.controlsList = "nodownload";
      audio.style.width = "237px";
      audio.src = json.media_url;

      let source = document.createElement("source");
      source.src = json.media_url;

      audio.append(source);
      quoted.append(audio);
      container.append(quoted);

      break;
    case "3":

      let box = document.createElement("div");
      box.style.float = "right";
      box.style.width = "116px";
      box.style.height = "103px";
      box.style.marginRight = "-4px";

      let iconImage = document.createElement("i");
      iconImage.className = "fas fa-camera icon-container";
      iconImage.style.fontSize = "17px";
      iconImage.style.float = "left";
      iconImage.style.marginTop = "41px";
      iconImage.style.marginLeft = "6px";
      iconImage.style.color = "rgb(2 2 2 / 52%)";

      let spanImage = document.createElement("span");
      spanImage.textContent = GLOBAL_LANG.messenger_media_types_photo;
      spanImage.style.float = "left";
      spanImage.style.marginTop = "39px";
      spanImage.style.marginLeft = "7px";
      spanImage.style.paddingLeft = "-1px";
      spanImage.style.paddingRight = "31px";
      spanImage.style.fontSize = "16px";
      spanImage.style.fontFamily = "system-ui";

      let linkImg = document.createElement("a");
      linkImg.href = json.media_url;
      linkImg.className = "cancel-click";
      linkImg.target = "_blank";

      let img = document.createElement("img");
      img.src = json.media_url;
      img.className = "imageLink";
      img.style.width = "100%";
      img.style.height = "100%";
      img.style.objectFit = "cover";
      img.style.borderRadius = "5px";

      quoted.style.height = "101px";

      quoted.append(iconImage);
      quoted.append(spanImage);

      linkImg.append(img);
      box.append(linkImg);
      quoted.append(box);
      container.append(quoted);

      break;
    case "4":

      let imgDocument = document.createElement("img");
      imgDocument.src = "assets/img/download.svg";
      imgDocument.className = "icon-container";
      imgDocument.style.width = "26px";
      imgDocument.style.float = "right";
      imgDocument.style.marginRight = "5px";
      imgDocument.style.marginTop = "11px";

      switch (json.media_mime_type) {
        case "assets/icons/excel.svg":
        case "assets/icons/texto.svg":
          var verify_media_meme = json.media_mime_type.replace("assets/icons/", "");
          break;
        default:
          verify_media_meme = json.media_mime_type;
          break;
      }

      let icon_document = document.createElement("img");
      let titleDocument = document.createElement("span");

      switch (verify_media_meme) {
        case "application/pdf":
          icon_document.src = "assets/icons/pdf.svg";
          icon_document.style.marginLeft = "1px";
          icon_document.style.marginTop = "6px";
          icon_document.style.float = "left";
          icon_document.style.width = "33px";
          icon_document.style.height = "37px";
          titleDocument.style.paddingTop = "21px";
          quoted.appendChild(icon_document);
          break;

        case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
        case "application/vnd.ms-excel":
        case "excel.svg":
          icon_document.className = "icon_document";
          icon_document.src = "assets/icons/excel.svg";
          icon_document.style.marginTop = "10px";
          icon_document.style.float = "left";
          icon_document.style.paddingLeft = "-17px";
          icon_document.style.width = "35px";
          icon_document.style.height = "31px";
          titleDocument.style.paddingTop = "21px";
          quoted.append(icon_document);
          break;

        case "application/octet-stream":
        case "texto.svg":
          icon_document.className = "icon_document";
          icon_document.src = "assets/icons/texto.svg";
          icon_document.style.marginTop = "10px";
          icon_document.style.float = "left";
          icon_document.style.paddingLeft = "-17px";
          icon_document.style.width = "35px";
          icon_document.style.height = "31px";
          titleDocument.style.paddingTop = "21px";
          quoted.append(icon_document);
          break;

        default:

          icon_document.className = "icon_document";
          icon_document.src = "assets/icons/new-document.svg";
          icon_document.style.marginTop = "10px";
          icon_document.style.float = "left";
          icon_document.style.paddingLeft = "-17px";
          icon_document.style.width = "35px";
          icon_document.style.height = "31px";
          titleDocument.style.paddingTop = "21px";
          quoted.append(icon_document);
          break;
      }

      titleDocument.textContent = json.title == null ? Util.doTruncarStr(json.file_name, 15) : Util.doTruncarStr(json.title, 15);
      titleDocument.style.float = "left";
      titleDocument.style.paddingLeft = "6px";
      titleDocument.style.paddingRight = "54px";
      titleDocument.style.fontSize = "12px";
      titleDocument.style.paddingBottom = "-8px";

      let linkDocument = document.createElement("a");
      linkDocument.className = "cancel-click";
      linkDocument.target = "_blank";
      linkDocument.href = json.media_url;

      quoted.style.height = "46px";

      linkDocument.appendChild(imgDocument);
      quoted.appendChild(titleDocument);
      quoted.appendChild(linkDocument);
      container.appendChild(quoted);

      break;
    case "5":

      let iconPlay = document.createElement("i");
      iconPlay.className = "fas fa-play-circle icon-container icon-focus cancel-click";
      iconPlay.style.fontSize = "30px";
      iconPlay.style.float = "right";
      iconPlay.style.position = "absolute";
      iconPlay.style.marginLeft = "25px";
      iconPlay.style.marginTop = "-45px";
      iconPlay.style.color = "rgb(2 2 2 / 52%)";

      let bodyVideo = document.createElement("div");
      bodyVideo.className = "icon-focus body-video";
      bodyVideo.style.width = "80px";
      bodyVideo.style.float = "right";
      bodyVideo.style.paddingTop = "65px";
      bodyVideo.style.marginRight = "-4px"
      bodyVideo.style.borderRadius = "7px";
      bodyVideo.style.backgroundColor = "rgb(190 183 183 / 56%)";

      let iconVideo = document.createElement("i");
      iconVideo.className = "fas fa-video icon-container";
      iconVideo.style.fontSize = "20px";
      iconVideo.style.marginLeft = "14px";
      iconVideo.style.marginTop = "24px";
      iconVideo.style.paddingBottom = "19px";
      iconVideo.style.color = "rgb(2 2 2 / 52%)";

      let titleVideo = document.createElement("span");
      titleVideo.textContent = "Vídeo";
      titleVideo.style.marginTop = "25px";
      titleVideo.style.marginLeft = "14px";
      titleVideo.style.marginRight = "62px";
      titleVideo.style.fontSize = "12px";

      let linkVideo = document.createElement("a");
      linkVideo.href = "#";
      linkVideo.target = "_blank";
      linkVideo.appendChild(iconPlay);

      bodyVideo.appendChild(linkVideo);
      quoted.appendChild(iconVideo);
      quoted.appendChild(titleVideo);
      quoted.appendChild(bodyVideo);
      container.append(quoted);

      break;
    case "7":

      let thumbnailLocation = document.createElement("div");
      thumbnailLocation.style.width = "122px";
      thumbnailLocation.style.height = "108px";
      thumbnailLocation.style.float = "right";
      thumbnailLocation.style.borderRadius = "8px";
      thumbnailLocation.style.cursor = "pointer";

      let a_locontaion = document.createElement("a");
      a_locontaion.href = "http://maps.google.com/maps?q=" + json.latitude + "," + json.longitude + "&hl=pt-BR";
      a_locontaion.target = "_blank";

      let imgLocation = document.createElement("img");
      imgLocation.src = "./assets/img/localizacao.jpg";
      imgLocation.style.width = "100%";
      imgLocation.style.width = "100%";
      imgLocation.style.height = "100%";
      imgLocation.style.objectFit = "cover";
      imgLocation.style.borderRadius = "4px";

      let iconLocation = document.createElement("i");
      iconLocation.className = "fas fa-map-marked-alt icon-container";
      iconLocation.style.fontSize = "17px";
      iconLocation.style.float = "left";
      iconLocation.style.position = "";
      iconLocation.style.marginLeft = "4px";
      iconLocation.style.marginTop = "43px";
      iconLocation.style.color = "rgb(2 2 2 / 52%)";

      let titleLocation = document.createElement("span");
      titleLocation.textContent = "Localização";
      titleLocation.style.float = "left";
      titleLocation.style.marginTop = "46px";
      titleLocation.style.marginLeft = "10px";
      titleLocation.style.fontSize = "12px";

      quoted.style.height = "108px";
      quoted.style.width = "238px";

      thumbnailLocation.appendChild(imgLocation);
      quoted.appendChild(iconLocation);
      quoted.appendChild(titleLocation);
      quoted.appendChild(a_locontaion);
      quoted.appendChild(thumbnailLocation);
      container.appendChild(quoted);

      break;
    case "9":

      let avatarContact = document.createElement("img");
      avatarContact.src = "assets/img/avatar.svg";
      avatarContact.style.width = "32px";
      avatarContact.style.height = "32px";
      avatarContact.style.float = "right";
      avatarContact.style.marginRight = "10px";
      avatarContact.style.marginTop = "6px";

      let iconContact = document.createElement("i");
      iconContact.className = "fas fa-user icon-container";
      iconContact.style.float = "left";
      iconContact.style.marginTop = "15px";
      iconContact.style.marginLeft = "10px";

      let captionContact = document.createElement("span");
      captionContact.textContent = json.data == undefined ? GLOBAL_LANG.messenger_media_types_contact : json.data;
      captionContact.style.float = "left";
      captionContact.style.marginTop = "15px";
      captionContact.style.marginLeft = "10px";

      quoted.style.height = "46px";
      quoted.style.width = "188px";

      quoted.appendChild(iconContact);
      quoted.appendChild(captionContact);
      quoted.appendChild(avatarContact);
      container.appendChild(quoted);
      break;

    case "10":

      let imgArchive = document.createElement("img");
      imgArchive.src = "assets/img/download.svg";
      imgArchive.className = "icon-container";
      imgArchive.style.width = "26px";
      imgArchive.style.float = "right";
      imgArchive.style.marginRight = "5px";
      imgArchive.style.marginTop = "11px";

      switch (json.media_mime_type) {
        case "assets/icons/excel.svg":
        case "assets/icons/texto.svg":
          var verify_media_meme = json.media_mime_type.replace("assets/icons/", "");
          break;
        default:
          verify_media_meme = json.media_mime_type;
          break;
      }

      let icon_arquive = document.createElement("img");
      let titleArchive = document.createElement("span");

      switch (verify_media_meme) {
        case "application/pdf":
          icon_arquive.src = "assets/icons/pdf.svg";
          icon_arquive.style.marginLeft = "-8px";
          icon_arquive.style.marginTop = "9px";
          icon_arquive.style.float = "left";
          icon_arquive.style.width = "34px";
          icon_arquive.style.height = "37px";
          quoted.appendChild(icon_arquive);
          break;

        case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
        case "application/vnd.ms-excel":
        case "excel.svg":
          icon_arquive.className = "icon_document";
          icon_arquive.src = "assets/icons/excel.svg";
          icon_arquive.style.marginTop = "10px";
          icon_arquive.style.float = "left";
          icon_arquive.style.paddingLeft = "-17px";
          icon_arquive.style.width = "35px";
          icon_arquive.style.height = "31px";
          quoted.append(icon_arquive);
          break;

        case "application/octet-stream":
        case "texto.svg":
          icon_arquive.className = "icon_document";
          icon_arquive.src = "assets/icons/texto.svg";
          icon_arquive.style.marginTop = "10px";
          icon_arquive.style.float = "left";
          icon_arquive.style.paddingLeft = "-17px";
          icon_arquive.style.width = "35px";
          icon_arquive.style.height = "31px";
          quoted.append(icon_arquive);
          break;

        case "text/plain":
          icon_arquive.className = "icon_document";
          icon_arquive.src = "assets/icons/txt.svg";
          icon_arquive.style.marginTop = "5px";
          icon_arquive.style.float = "left";
          icon_arquive.style.paddingLeft = "-17px";
          icon_arquive.style.width = "44px";
          icon_arquive.style.height = "41px";
          quoted.append(icon_arquive);
          break;

        default:

          icon_arquive.className = "icon_document";
          icon_arquive.src = "assets/icons/new-document.svg";
          icon_arquive.style.marginTop = "10px";
          icon_arquive.style.float = "left";
          icon_arquive.style.paddingLeft = "-17px";
          icon_arquive.style.width = "35px";
          icon_arquive.style.height = "31px";
          quoted.append(icon_arquive);
          break;
      }

      titleArchive.textContent = json.title == null ? Util.doTruncarStr(json.file_name, 15) : Util.doTruncarStr(json.title, 15);
      titleArchive.style.float = "left";
      titleArchive.style.paddingTop = "21px";
      titleArchive.style.paddingLeft = "6px";
      titleArchive.style.paddingRight = "54px";
      titleArchive.style.fontSize = "13px";
      titleArchive.style.paddingBottom = "-8px";

      let linkArchive = document.createElement("a");
      linkArchive.className = "cancel-click"; atShortDate
      linkArchive.target = "_blank";
      linkArchive.href = json.media_url;

      quoted.style.height = "46px";

      linkArchive.appendChild(imgArchive);
      quoted.appendChild(titleArchive);
      quoted.appendChild(linkArchive);
      container.appendChild(quoted);

      break;
    default:
      break;
  }

  return container;
}


function formatDate(creation) {

  const date = new Date(creation);
  const returnDate = date.toLocaleString();

  let dateFormat = returnDate.split(" ");
  let day = dateFormat[0].split("/")[0];
  let month = dateFormat[0].split("/")[1];
  let year = dateFormat[0].split("/")[2];

  if (day <= 9) day = "0" + day;
  dateFormat[0] = day + "/" + month + "/" + year;

  dateFormat[0] = Util.FormatShortDate(creation);

  return dateFormat[0];
}


function pushNameStarred(json) {

  let key_remote_id_chat = "https://files.talkall.com.br:3000/p/" + messenger.Chat.key_remote_id + ".jpeg";
  let key_remote_id = "https://files.talkall.com.br:3000/p/" + json + ".jpeg";

  let push_name = messenger.Chat.push_name;
  let you = GLOBAL_LANG.messenger_window_favorite_user

  let obj = {
    key_remote_id_chat: key_remote_id_chat,
    key_remote_id: key_remote_id,
    push_name: push_name,
    you: you
  }
  return obj;
}


function TextStarred(json) {

  let response = pushNameStarred(json.key_remote_id);

  let textStarred = document.createElement("div");
  textStarred.className = "textStarred starredMessage";
  textStarred.id = "_str" + json.creation;
  textStarred.dataset.index = json.creation;
  textStarred.setAttribute('name', "_str" + json.token);

  let starredParticipant = document.createElement("div");
  starredParticipant.className = "starredParticipant";

  let img = document.createElement("img");
  img.className = "img";
  img.src = json.key_from_me == 1 ? response.key_remote_id_chat : response.key_remote_id;

  let pushName = document.createElement("span");
  pushName.className = "push-name";
  pushName.textContent = json.key_from_me == 1 ? response.push_name : response.you;

  let time = document.createElement("span");
  time.className = "time";
  time.textContent = Util.FormatShortDate(json.creation);

  let chevron = document.createElement("i");
  chevron.className = "fas fa-chevron-right";

  let container = document.createElement("div");
  container.className = json.key_from_me == 1 ? "container _lef" : "container _righ";
  container.id = Math.floor(Math.random() * 10000);

  let tailOut = document.createElement("span");
  tailOut.className = json.key_from_me == 1 ? "tail-out-left" : "tail-out-right";

  let strDropdown = document.createElement("div");
  strDropdown.className = "strDropdow";

  let iconDropdown = document.createElement("i");
  iconDropdown.className = "fas fa-chevron-down str-dropdown";
  iconDropdown.id = "down" + json.token;

  let text = document.createElement("div");
  text.className = json.key_from_me == 1 ? "text" : "text white-text";
  text.style.whiteSpace = "break-spaces";
  text.style.marginBottom = "5px";

  let spanText = document.createElement("span");
  spanText.textContent = json.data;

  let button = document.createElement("div");
  button.className = "button";

  let spanTime = document.createElement("span");
  spanTime.className = json.key_from_me == 2 ? "span-white" : "";
  spanTime.textContent = Util.FormatShortTime(json.creation);

  let star = document.createElement("i");
  star.className = json.key_from_me == 1 ? "fas fa-star icon-starred" : "fas fa-star icon-starred white-star";

  text.appendChild(spanText);
  button.appendChild(spanTime);
  button.appendChild(star);
  strDropdown.appendChild(iconDropdown);

  starredParticipant.appendChild(img);
  starredParticipant.appendChild(pushName);
  starredParticipant.appendChild(time);
  starredParticipant.appendChild(chevron);

  if (json.quoted != null) {
    container = initStarred(container, json.key_from_me, json.quoted);
  }

  container.appendChild(tailOut);
  container.appendChild(strDropdown);
  container.appendChild(text);
  container.appendChild(button);

  textStarred.appendChild(starredParticipant);
  textStarred.appendChild(container);

  if (json.openWindow == "true") {
    if (json.idGroup != undefined) {
      let idGroup = document.getElementById(json.idGroup);
      idGroup.appendChild(textStarred);

      $("#" + json.idGroup).find(".starredMessage").sort(function (a, b) {
        return $(b).attr('data-index') - $(a).attr('data-index');
      }).appendTo("#" + json.idGroup);

    } else {
      createGroup(textStarred, json.creation);
    }

  } else {
    createGroup(textStarred, json.creation);
  }

}

function ImageStarred(json) {

  let response = pushNameStarred(json.key_remote_id);

  let imageStarred = document.createElement("div");
  imageStarred.className = "imageStarred starredMessage";
  imageStarred.id = "_str" + json.creation;
  imageStarred.dataset.index = json.creation;
  imageStarred.setAttribute('name', "_str" + json.token);

  let starredParticipant = document.createElement("div");
  starredParticipant.className = "starredParticipant";

  let img = document.createElement("img");
  img.className = "img";
  img.src = json.key_from_me == 1 ? response.key_remote_id_chat : response.key_remote_id;

  let pushName = document.createElement("span");
  pushName.className = "push-name";
  pushName.textContent = json.key_from_me == 1 ? response.push_name : response.you;

  let time = document.createElement("span");
  time.className = "time";
  time.textContent = formatDate(json.creation);

  let chevron = document.createElement("i");
  chevron.className = "fas fa-chevron-right";

  let container = document.createElement("div");
  container.className = json.key_from_me == 1 ? "container _lef" : "container _righ";
  container.id = Math.floor(Math.random() * 10000);

  let tailOut = document.createElement("span");
  tailOut.className = json.key_from_me == 1 ? "tail-out-left" : "tail-out-right";

  let strDropdown = document.createElement("div");
  strDropdown.className = "strDropdow";

  let iconDropdown = document.createElement("i");
  iconDropdown.className = "fas fa-chevron-down str-dropdown";
  iconDropdown.id = "down" + json.token;

  let box = document.createElement("div");
  box.className = "bx";
  box.style.width = "247px";
  box.style.maxHeight = "350px";
  box.style.paddingTop = "5px";
  box.style.paddingBottom = "5px";

  let a = document.createElement("a");
  a.href = "#";
  a.className = "cancel-click";

  let image = document.createElement("img");
  image.src = json.media_url;
  image.style.width = "100%";
  image.style.borderRadius = "2px";

  let boxText = document.createElement("div");
  let text = document.createElement("span");

  if (json.media_caption != 0) {
    boxText.className = "box-text";
    text.textContent = Util.doTruncarStr(json.media_caption, 200);
  }

  let button = document.createElement("div");
  button.className = "button";

  let spanTime = document.createElement("span");
  spanTime.textContent = Util.FormatShortTime(json.creation);

  let star = document.createElement("i");
  star.className = json.key_from_me == 1 ? "fas fa-star icon-starred" : "fas fa-star icon-starred white-star";

  boxText.appendChild(text);
  a.appendChild(image);
  box.appendChild(a);
  button.appendChild(spanTime);
  button.appendChild(star);
  strDropdown.appendChild(iconDropdown);

  starredParticipant.appendChild(img);
  starredParticipant.appendChild(pushName);
  starredParticipant.appendChild(time);
  starredParticipant.appendChild(chevron);

  if (json.quoted != null) {
    container = initStarred(container, json.key_from_me, json.quoted);
  }

  container.appendChild(tailOut);
  container.appendChild(strDropdown);
  container.appendChild(box);
  container.appendChild(boxText);
  container.appendChild(button);

  imageStarred.appendChild(starredParticipant);
  imageStarred.appendChild(container);

  if (json.openWindow == "true") {
    if (json.idGroup != undefined) {
      let idGroup = document.getElementById(json.idGroup);
      idGroup.appendChild(imageStarred);

      $("#" + json.idGroup).find(".starredMessage").sort(function (a, b) {
        return $(b).attr('data-index') - $(a).attr('data-index');
      }).appendTo("#" + json.idGroup);

    } else {
      createGroup(imageStarred, json.creation);
    }

  } else {
    createGroup(imageStarred, json.creation);
  }

}

function AudioStarred(json) {

  let media_id = "_str" + json.creation;
  let media_url = json.media_url;
  let mime_type = json.media_mime_type;

  let response = pushNameStarred(json.key_remote_id);

  let audioMessage = document.createElement("div");
  audioMessage.className = "audioStarred starredMessage";
  audioMessage.id = "_str" + json.creation;
  audioMessage.dataset.index = json.creation;
  audioMessage.setAttribute('name', "_str" + json.token);

  let starredParticipant = document.createElement("div");
  starredParticipant.className = "starredParticipant";

  let img = document.createElement("img");
  img.className = "img";
  img.src = json.key_from_me == 1 ? response.key_remote_id_chat : response.key_remote_id;

  let pushName = document.createElement("span");
  pushName.className = "push-name";
  pushName.textContent = json.key_from_me == 1 ? response.push_name : response.you;

  let time = document.createElement("span");
  time.className = "time";
  time.textContent = formatDate(json.creation);

  let chevron = document.createElement("i");
  chevron.className = "fas fa-chevron-right";

  let container = document.createElement("div");
  container.className = json.key_from_me == 1 ? "container _lef" : "container _righ";
  container.id = Math.floor(Math.random() * 10000);

  let tailOut = document.createElement("span");
  tailOut.className = json.key_from_me == 1 ? "tail-out-left" : "tail-out-right";

  let strDropdown = document.createElement("div");
  strDropdown.className = "strDropdow";

  let iconDropdown = document.createElement("i");
  iconDropdown.className = "fas fa-chevron-down str-dropdown";
  iconDropdown.id = "down" + json.token;

  let audio = document.createElement("audio");
  audio.controls = true;
  audio.controlsList = "nodownload";
  audio.style.width = "237px";
  audio.src = json.media_url;

  let source = document.createElement("source");
  source.src = json.media_url;

  let button = document.createElement("div");
  button.className = "button";

  let spanTime = document.createElement("span");
  spanTime.textContent = Util.FormatShortTime(json.creation);

  let star = document.createElement("i");
  star.className = json.key_from_me == 1 ? "fas fa-star icon-starred" : "fas fa-star icon-starred white-star";

  audio.appendChild(source);
  button.appendChild(spanTime);
  button.appendChild(star);
  strDropdown.appendChild(iconDropdown);

  starredParticipant.appendChild(img);
  starredParticipant.appendChild(pushName);
  starredParticipant.appendChild(time);
  starredParticipant.appendChild(chevron);

  if (json.quoted != null) {
    container = initStarred(container, json.key_from_me, json.quoted);
  }

  container.appendChild(tailOut);
  container.appendChild(strDropdown);
  container.appendChild(audio);
  container.appendChild(button);

  audioMessage.appendChild(starredParticipant);
  audioMessage.appendChild(container);

  Util.audioToBlob(media_id, media_url, mime_type)

  if (json.openWindow == "true") {
    if (json.idGroup != undefined) {
      let idGroup = document.getElementById(json.idGroup);
      idGroup.appendChild(audioMessage);

      $("#" + json.idGroup).find(".starredMessage").sort(function (a, b) {
        return $(b).attr('data-index') - $(a).attr('data-index');
      }).appendTo("#" + json.idGroup);

    } else {
      createGroup(audioMessage, json.creation);
    }

  } else {
    createGroup(audioMessage, json.creation);
  }

}

function VideoStarred(json) {

  let response = pushNameStarred(json.key_remote_id);

  let videoStarred = document.createElement("div");
  videoStarred.className = "videoStarred starredMessage";
  videoStarred.id = "_str" + json.creation;
  videoStarred.dataset.index = json.creation;
  videoStarred.setAttribute('name', "_str" + json.token);

  let starredParticipant = document.createElement("div");
  starredParticipant.className = "starredParticipant";

  let img = document.createElement("img");
  img.className = "img";
  img.src = json.key_from_me == 1 ? response.key_remote_id_chat : response.key_remote_id;

  let pushName = document.createElement("span");
  pushName.className = "push-name";
  pushName.textContent = json.key_from_me == 1 ? response.push_name : response.you;

  let time = document.createElement("span");
  time.className = "time";
  time.textContent = formatDate(json.creation);

  let chevron = document.createElement("i");
  chevron.className = "fas fa-chevron-right";

  let container = document.createElement("div");
  container.className = json.key_from_me == 1 ? "container _lef" : "container _righ";
  container.id = Math.floor(Math.random() * 10000);

  let tailOut = document.createElement("span");
  tailOut.className = json.key_from_me == 1 ? "tail-out-left" : "tail-out-right";

  let strDropdown = document.createElement("div");
  strDropdown.className = "strDropdow";

  let iconDropdown = document.createElement("i");
  iconDropdown.className = "fas fa-chevron-down str-dropdown";
  iconDropdown.id = "down" + json.token;

  let img_download = document.createElement("img");
  img_download.src = "assets/img/botao-play.svg";
  img_download.style.width = "60px";
  img_download.style.height = "50px";
  img_download.style.marginTop = "-53px";
  img_download.style.paddingBottom = "35px";
  img_download.style.cursor = "pointer";

  let a = document.createElement("a");
  a.className = "cancel-click"
  a.href = "#";

  let iconPlay = document.createElement("i");
  iconPlay.className = "fas fa-play-circle icon-play";
  iconPlay.style.cursor = "pointer";

  var thumbnail = document.createElement("div");
  thumbnail.className = "thumbnail";
  thumbnail.style.width = "100%";
  thumbnail.style.float = "left";
  thumbnail.style.backgroundPosition = "50% 50%";
  thumbnail.style.backgroundSize = "cover";

  let video_ = document.createElement("video");
  video_.src = json.media_url;
  video_.style.width = "100%";
  video_.style.borderRadius = "2px";
  video_.style.cursor = "pointer";
  video_.autoplay = false;
  video_.loop = true;
  video_.muted = true;
  video_.controls = true;
  video_.controlsList = "nodownload";

  let body = document.createElement("div");
  body.className = "body_";
  body.style.marginLeft = "87px";
  body.style.marginRight = "92px";

  let title = document.createElement("span");
  title.textContent = Util.doTruncarStr(json.media_caption, 200);
  title.style.float = "left";
  title.style.marginLeft = "5px";
  title.style.fontSize = "14px";

  thumbnail.appendChild(video_);
  a.appendChild(thumbnail);
  // body.appendChild(img_download);

  let button = document.createElement("div");
  button.className = "button";

  let spanTime = document.createElement("span");
  spanTime.textContent = Util.FormatShortTime(json.creation);

  let star = document.createElement("i");
  star.className = json.key_from_me == 1 ? "fas fa-star icon-starred" : "fas fa-star icon-starred white-star";

  button.appendChild(spanTime);
  button.appendChild(star);
  strDropdown.appendChild(iconDropdown);

  starredParticipant.appendChild(img);
  starredParticipant.appendChild(pushName);
  starredParticipant.appendChild(time);
  starredParticipant.appendChild(chevron);

  if (json.quoted != null) {
    container = initStarred(container, json.key_from_me, json.quoted);
  }

  container.appendChild(tailOut);
  container.appendChild(strDropdown);
  container.appendChild(a);
  container.appendChild(body);
  container.appendChild(title);
  container.appendChild(button);

  videoStarred.appendChild(starredParticipant);
  videoStarred.appendChild(container);

  if (json.openWindow == "true") {
    if (json.idGroup != undefined) {
      let idGroup = document.getElementById(json.idGroup);
      idGroup.appendChild(videoStarred);

      $("#" + json.idGroup).find(".starredMessage").sort(function (a, b) {
        return $(b).attr('data-index') - $(a).attr('data-index');
      }).appendTo("#" + json.idGroup);

    } else {
      createGroup(videoStarred, json.creation);
    }

  } else {
    createGroup(videoStarred, json.creation);
  }

}

function DocumentStarred(json) {

  let response = pushNameStarred(json.key_remote_id);

  let documentStarred = document.createElement("div");
  documentStarred.className = "documentStarred starredMessage";
  documentStarred.id = "_str" + json.creation;
  documentStarred.dataset.index = json.creation;
  documentStarred.setAttribute('name', "_str" + json.token);

  let starredParticipant = document.createElement("div");
  starredParticipant.className = "starredParticipant";

  let img = document.createElement("img");
  img.className = "img";
  img.src = json.key_from_me == 1 ? response.key_remote_id_chat : response.key_remote_id;

  let pushName = document.createElement("span");
  pushName.className = "push-name";
  pushName.textContent = json.key_from_me == 1 ? response.push_name : response.you;

  let time = document.createElement("span");
  time.className = "time";
  time.textContent = formatDate(json.creation);

  let chevron = document.createElement("i");
  chevron.className = "fas fa-chevron-right";

  let container = document.createElement("div");
  container.className = json.key_from_me == 1 ? "container _lef" : "container _righ";
  container.id = Math.floor(Math.random() * 10000);

  let tailOut = document.createElement("span");
  tailOut.className = json.key_from_me == 1 ? "tail-out-left" : "tail-out-right";

  let strDropdown = document.createElement("div");
  strDropdown.className = "strDropdow";

  let iconDropdown = document.createElement("i");
  iconDropdown.className = "fas fa-chevron-down str-dropdown";
  iconDropdown.id = "down" + json.token;

  // let img_download = document.createElement("img");
  // img_download.src = "assets/img/download.svg";
  // img_download.style.float = "right";
  // img_download.style.width = "32px";
  // img_download.style.height = "32px";
  // img_download.style.marginRight = "10px";
  // img_download.style.marginTop = "12px";
  // img_download.style.cursor = "pointer";

  let a = document.createElement("a");
  a.className = "cancel-click";
  a.target = "_blank";
  a.href = json.media_url;
  // a.appendChild(img_download);

  let body = document.createElement("div");
  body.className = json.key_from_me == 1 ? "bodyDocument-left" : "bodyDocument-right";
  body.style.marginBottom = "7px";
  body.style.marginTop = "2px";
  body.style.width = "243px";
  body.style.height = "58px";
  body.style.borderRadius = "4px";

  let icon = document.createElement("img");

  switch (json.media_mime_type) {
    case "application/pdf":
      icon.src = "assets/icons/pdf.svg";
      icon.style.marginLeft = "16px";
      icon.style.marginTop = "13px";
      icon.style.float = "left";
      icon.style.width = "32px";
      icon.style.height = "35px";

      break;
    case "application/octet-stream":
    case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
      icon.className = "icon_document";
      icon.src = "assets/icons/texto.svg";
      icon.style.marginLeft = "16px";
      icon.style.marginTop = "13px";
      icon.style.float = "left";
      icon.style.width = "32px";
      icon.style.height = "35px";

      break;
    case "application/vnd.ms-excel":
      icon.className = "icon_document";
      icon.src = "assets/icons/excel.svg";
      icon.style.marginLeft = "16px";
      icon.style.marginTop = "13px";
      icon.style.float = "left";
      icon.style.width = "32px";
      icon.style.height = "35px";

      break;
    default:
      icon.className = "icon_document";
      icon.src = "assets/icons/new-document.svg";
      icon.style.marginLeft = "16px";
      icon.style.marginTop = "13px";
      icon.style.float = "left";
      icon.style.width = "32px";
      icon.style.height = "35px";
      break;
  }

  let title = document.createElement("span");
  title.style.fontSize = "12px";
  title.style.float = "left";
  title.style.marginTop = "23px";
  title.style.marginLeft = "12px";

  title.textContent = Util.doTruncarStr(json.media_name, 20) == undefined ? GLOBAL_LANG.messenger_document_message : Util.doTruncarStr(json.media_name, 15);

  if (json.media_name == null || json.media_name == 0 || json.media_name == undefined) {
    title.textContent = json.file_name == null ? Util.doTruncarStr(json.media_title, 15) : Util.doTruncarStr(json.file_name, 15);
  } else {
    title.textContent = Util.doTruncarStr(json.media_name, 15);
  }

  body.appendChild(icon);
  body.appendChild(title);
  body.appendChild(a);

  let button = document.createElement("div");
  button.className = "button";

  let spanTime = document.createElement("span");
  spanTime.textContent = Util.FormatShortTime(json.creation);

  let star = document.createElement("i");
  star.className = json.key_from_me == 1 ? "fas fa-star icon-starred" : "fas fa-star icon-starred white-star";

  button.appendChild(spanTime);
  button.appendChild(star);
  strDropdown.appendChild(iconDropdown);

  starredParticipant.appendChild(img);
  starredParticipant.appendChild(pushName);
  starredParticipant.appendChild(time);
  starredParticipant.appendChild(chevron);

  if (json.quoted != null) {
    container = initStarred(container, json.key_from_me, json.quoted);
  }

  container.appendChild(tailOut);
  container.appendChild(strDropdown);

  let thumbnail = document.createElement("div");
  thumbnail.className = "thumbnail";
  thumbnail.style.marginTop = "7px";
  thumbnail.style.width = "241px";
  thumbnail.style.height = "110px";
  thumbnail.style.cursor = "pointer";
  thumbnail.style.overflow = "hidden";

  if (json.thumb_image != 0) {
    if (json.thumb_image != undefined) {
      let img_thumbnail = document.createElement("img");
      img_thumbnail.src = "data:image/jpeg;base64," + json.thumb_image;
      img_thumbnail.style.width = "109%";
      img_thumbnail.style.objectFit = "cover";
      img_thumbnail.style.float = "left";
      img_thumbnail.style.marginLeft = "-9px"

      thumbnail.appendChild(img_thumbnail);
      container.append(thumbnail);
    }
  }

  container.appendChild(body);
  container.appendChild(button);

  documentStarred.appendChild(starredParticipant);
  documentStarred.appendChild(container);

  if (json.openWindow == "true") {
    if (json.idGroup != undefined) {
      let idGroup = document.getElementById(json.idGroup);
      idGroup.appendChild(documentStarred);

      $("#" + json.idGroup).find(".starredMessage").sort(function (a, b) {
        return $(b).attr('data-index') - $(a).attr('data-index');
      }).appendTo("#" + json.idGroup);

    } else {
      createGroup(documentStarred, json.creation);
    }

  } else {
    createGroup(documentStarred, json.creation);
  }

}

function ZipStarred(json) {

  let response = pushNameStarred(json.key_remote_id);

  let ZipStarred = document.createElement("div");
  ZipStarred.className = "zipStarred starredMessage";
  ZipStarred.id = "_str" + json.creation;
  ZipStarred.dataset.index = json.creation;
  ZipStarred.setAttribute('name', "_str" + json.token);

  let starredParticipant = document.createElement("div");
  starredParticipant.className = "starredParticipant";

  let img = document.createElement("img");
  img.className = "img";
  img.src = json.key_from_me == 1 ? response.key_remote_id_chat : response.key_remote_id;

  let pushName = document.createElement("span");
  pushName.className = "push-name";
  pushName.textContent = json.key_from_me == 1 ? response.push_name : response.you;

  let time = document.createElement("span");
  time.className = "time";
  time.textContent = formatDate(json.creation);

  let chevron = document.createElement("i");
  chevron.className = "fas fa-chevron-right";

  let container = document.createElement("div");
  container.className = json.key_from_me == 1 ? "container _lef" : "container _righ";
  container.id = Math.floor(Math.random() * 10000);

  let tailOut = document.createElement("span");
  tailOut.className = json.key_from_me == 1 ? "tail-out-left" : "tail-out-right";

  let strDropdown = document.createElement("div");
  strDropdown.className = "strDropdow";

  let iconDropdown = document.createElement("i");
  iconDropdown.className = "fas fa-chevron-down str-dropdown";
  iconDropdown.id = "down" + json.token;

  let img_download = document.createElement("img");
  img_download.src = "assets/img/download.svg";
  img_download.style.float = "right";
  img_download.style.width = "32px";
  img_download.style.height = "32px";
  img_download.style.marginRight = "10px";
  img_download.style.marginTop = "12px";
  img_download.style.cursor = "pointer";

  let a = document.createElement("a");
  a.className = "cancel-click";
  a.target = "_blank";
  a.href = json.media_url;
  a.appendChild(img_download);

  let body = document.createElement("div");
  body.className = json.key_from_me == 1 ? "bodyDocument-left" : "bodyDocument-right";
  body.style.marginBottom = "7px";
  body.style.marginTop = "2px";
  body.style.width = "243px";
  body.style.height = "58px"

  let icon = document.createElement("img");

  switch (json.media_mime_type) {
    case "application/pdf":
      icon.src = "assets/icons/pdf.svg";
      icon.style.marginLeft = "18px";
      icon.style.marginTop = "9px";
      icon.style.float = "left";
      icon.style.width = "34px";
      icon.style.height = "37px";
      break;

    case "application/octet-stream":
      icon.className = "icon_document";
      icon.src = "assets/icons/texto.svg";
      icon.style.marginLeft = "16px";
      icon.style.marginTop = "13px";
      icon.style.float = "left";
      icon.style.width = "32px";
      icon.style.height = "35px";
      break;

    case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
    case "application/vnd.ms-excel":
    case "excel.svg":
      icon.className = "icon_document";
      icon.src = "assets/icons/excel.svg";
      icon.style.marginLeft = "16px";
      icon.style.marginTop = "13px";
      icon.style.float = "left";
      icon.style.width = "32px";
      icon.style.height = "35px";
      break;

    case "text/plain":
      icon.className = "icon_document";
      icon.src = "assets/icons/txt.svg";
      icon.style.marginLeft = "7px";
      icon.style.marginTop = "8px";
      icon.style.float = "left";
      icon.style.width = "46px";
      icon.style.height = "45px";
      break;

    default:
      icon.className = "icon_document";
      icon.src = "assets/icons/new-document.svg";
      icon.style.marginLeft = "16px";
      icon.style.marginTop = "13px";
      icon.style.float = "left";
      icon.style.width = "32px";
      icon.style.height = "35px";
      break;
  }

  let title = document.createElement("span");
  title.style.fontSize = "12px";
  title.style.float = "left";
  title.style.marginTop = "23px";
  title.style.marginLeft = "12px";

  title.textContent = Util.doTruncarStr(json.media_name, 20) == undefined ? GLOBAL_LANG.messenger_document_message : Util.doTruncarStr(json.media_name, 15);

  if (json.media_title == null || json.media_title == 0 || json.media_title == undefined) {
    title.textContent = json.file_name == 0 ? Util.doTruncarStr(json.title, 15) : Util.doTruncarStr(json.file_name, 15);
  } else {
    title.textContent = Util.doTruncarStr(json.media_title, 15);
  }

  body.appendChild(icon);
  body.appendChild(title);
  body.appendChild(a);

  let button = document.createElement("div");
  button.className = "button";

  let spanTime = document.createElement("span");
  spanTime.textContent = Util.FormatShortTime(json.creation);

  let star = document.createElement("i");
  star.className = json.key_from_me == 1 ? "fas fa-star icon-starred" : "fas fa-star icon-starred white-star";

  button.appendChild(spanTime);
  button.appendChild(star);
  strDropdown.appendChild(iconDropdown);

  starredParticipant.appendChild(img);
  starredParticipant.appendChild(pushName);
  starredParticipant.appendChild(time);
  starredParticipant.appendChild(chevron);

  if (json.quoted != null) {
    container = initStarred(container, json.key_from_me, json.quoted);
  }

  container.appendChild(tailOut);
  container.appendChild(strDropdown);
  container.appendChild(body);
  container.appendChild(button);

  ZipStarred.appendChild(starredParticipant);
  ZipStarred.appendChild(container);

  if (json.openWindow == "true") {
    if (json.idGroup != undefined) {
      let idGroup = document.getElementById(json.idGroup);
      idGroup.appendChild(ZipStarred);

      $("#" + json.idGroup).find(".starredMessage").sort(function (a, b) {
        return $(b).attr('data-index') - $(a).attr('data-index');
      }).appendTo("#" + json.idGroup);

    } else {
      createGroup(ZipStarred, json.creation);
    }

  } else {
    createGroup(ZipStarred, json.creation);
  }

}

function ContactStarred(json) {

  let response = pushNameStarred(json.key_remote_id);

  let ContactStarred = document.createElement("div");
  ContactStarred.className = "contactStarred starredMessage";
  ContactStarred.id = "_str" + json.creation;
  ContactStarred.dataset.index = json.creation;
  ContactStarred.setAttribute('name', "_str" + json.token);

  let starredParticipant = document.createElement("div");
  starredParticipant.className = "starredParticipant";

  let img = document.createElement("img");
  img.className = "img";
  img.src = json.key_from_me == 1 ? response.key_remote_id_chat : response.key_remote_id;

  let pushName = document.createElement("span");
  pushName.className = "push-name";
  pushName.textContent = json.key_from_me == 1 ? response.push_name : response.you;

  let time = document.createElement("span");
  time.className = "time";
  time.textContent = formatDate(json.creation);

  let chevron = document.createElement("i");
  chevron.className = "fas fa-chevron-right";

  let container = document.createElement("div");
  container.className = json.key_from_me == 1 ? "container _lef" : "container _righ";
  container.style.width = "240px";
  container.id = Math.floor(Math.random() * 10000);

  let tailOut = document.createElement("span");
  tailOut.className = json.key_from_me == 1 ? "tail-out-left" : "tail-out-right";

  let strDropdown = document.createElement("div");
  strDropdown.className = "strDropdow";

  let iconDropdown = document.createElement("i");
  iconDropdown.className = "fas fa-chevron-down str-dropdown";
  iconDropdown.id = "down" + json.token;

  let imgAvatar = document.createElement("img");
  imgAvatar.src = "assets/img/avatar.svg";
  imgAvatar.style.height = "45px";
  imgAvatar.style.float = "left";
  imgAvatar.style.marginLeft = "0px";
  imgAvatar.style.marginTop = "8px";
  imgAvatar.style.padding = "10px";

  let caption = document.createElement("span");
  caption.textContent = json.media_caption == undefined ? json.display_name : json.media_caption;
  caption.id = "name_" + json.id_message;
  caption.className = "contact-name cancel-click";
  caption.style.cursor = "pointer";
  caption.style.float = "left";
  caption.style.marginTop = "32px";
  caption.style.fontSize = "13px";

  let btn = document.createElement("div");
  btn.className = "button";

  let number_contact = json.data;
  let number_residencial;

  if (number_contact.split("waid")[1] !== undefined) {

    let separateNumber = number_contact.split("waid")[1];
    let numberDD = separateNumber.split(":")[0];
    var number_whatsapp = numberDD.split("=55")[1];

  } else {

    if (json.openWindow != "true") {
      let numberDD_residencial = number_contact.split("+55")[1];
      number_residencial = numberDD_residencial.split("END:VCARD")[0];

    } else {
      number_residencial = number_contact;
    }
  }

  let input = document.createElement("input");
  input.type = "button";
  input.className = json.key_from_me == 1 ? "contact-add-chat lf" : "contact-add-chat rg";
  input.id = "view_" + json.creation;
  input.name = number_whatsapp == undefined ? number_residencial : number_whatsapp;
  input.value = "Enviar mensagem";
  input.style.width = "100%";
  input.style.padding = "15px";
  input.style.fontSize = "16px";
  input.style.color = "#00a3cc";
  input.style.borderBottom = "solid 0px";
  input.style.borderLeft = "solid 0px";
  input.style.borderRight = "solid 0px";

  btn.appendChild(input);

  let button = document.createElement("div");
  button.className = "button";

  let spanTime = document.createElement("span");
  spanTime.textContent = Util.FormatShortTime(json.creation);

  let star = document.createElement("i");
  star.className = json.key_from_me == 1 ? "fas fa-star icon-starred" : "fas fa-star icon-starred white-star";

  button.appendChild(spanTime);
  button.appendChild(star);
  strDropdown.appendChild(iconDropdown);

  starredParticipant.appendChild(img);
  starredParticipant.appendChild(pushName);
  starredParticipant.appendChild(time);
  starredParticipant.appendChild(chevron);

  if (json.quoted != null) {
    container = initStarred(container, json.key_from_me, json.quoted);
  }

  container.appendChild(tailOut);
  container.appendChild(strDropdown);

  container.appendChild(imgAvatar);
  container.appendChild(caption);
  container.appendChild(btn);

  container.appendChild(button);

  ContactStarred.appendChild(starredParticipant);
  ContactStarred.appendChild(container);

  if (json.openWindow == "true") {
    if (json.idGroup != undefined) {
      let idGroup = document.getElementById(json.idGroup);
      idGroup.appendChild(ContactStarred);

      $("#" + json.idGroup).find(".starredMessage").sort(function (a, b) {
        return $(b).attr('data-index') - $(a).attr('data-index');
      }).appendTo("#" + json.idGroup);

    } else {
      createGroup(ContactStarred, json.creation);
    }

  } else {
    createGroup(ContactStarred, json.creation);
  }

}

function LocationStarred(json) {

  let response = pushNameStarred(json.key_remote_id);

  let LocationStarred = document.createElement("div");
  LocationStarred.className = "locationStarred starredMessage";
  LocationStarred.id = "_str" + json.creation;
  LocationStarred.dataset.index = json.creation;
  LocationStarred.setAttribute('name', "_str" + json.token);

  let starredParticipant = document.createElement("div");
  starredParticipant.className = "starredParticipant";

  let img = document.createElement("img");
  img.className = "img";
  img.src = json.key_from_me == 1 ? response.key_remote_id_chat : response.key_remote_id;

  let pushName = document.createElement("span");
  pushName.className = "push-name";
  pushName.textContent = json.key_from_me == 1 ? response.push_name : response.you;

  let time = document.createElement("span");
  time.className = "time";
  time.textContent = formatDate(json.creation);

  let chevron = document.createElement("i");
  chevron.className = "fas fa-chevron-right";

  let container = document.createElement("div");
  container.className = json.key_from_me == 1 ? "container _lef" : "container _righ";
  container.id = Math.floor(Math.random() * 10000);

  let tailOut = document.createElement("span");
  tailOut.className = json.key_from_me == 1 ? "tail-out-left" : "tail-out-right";

  let strDropdown = document.createElement("div");
  strDropdown.className = "strDropdow";

  let iconDropdown = document.createElement("i");
  iconDropdown.className = "fas fa-chevron-down str-dropdown";
  iconDropdown.id = "down" + json.token;

  let thumbnail = document.createElement("div");
  thumbnail.className = "thumbnail";
  thumbnail.style.marginTop = "4px";
  thumbnail.style.marginBottom = "12px";

  let a = document.createElement("a");
  a.className = "cancel-click";

  if (json.openWindow == "true") {
    a.href = json.longitude;
  } else {
    a.href = "http://maps.google.com/maps?q=" + json.latitude + "," + json.longitude + "&hl=pt-BR";
  }

  a.target = "_blank";

  let preview = document.createElement("img");
  preview.src = "./assets/img/localizacao.jpg";
  preview.style.width = "100%";

  let title = document.createElement("span");
  title.textContent = json.title;

  a.appendChild(preview);
  thumbnail.appendChild(a);

  let button = document.createElement("div");
  button.className = "button";

  let spanTime = document.createElement("span");
  spanTime.textContent = Util.FormatShortTime(json.creation);

  let star = document.createElement("i");
  star.className = json.key_from_me == 1 ? "fas fa-star icon-starred" : "fas fa-star icon-starred white-star";

  button.appendChild(spanTime);
  button.appendChild(star);
  strDropdown.appendChild(iconDropdown);

  starredParticipant.appendChild(img);
  starredParticipant.appendChild(pushName);
  starredParticipant.appendChild(time);
  starredParticipant.appendChild(chevron);

  if (json.quoted != null) {
    container = initStarred(container, json.key_from_me, json.quoted);
  }

  container.appendChild(tailOut);
  container.appendChild(strDropdown);
  container.appendChild(title);
  container.appendChild(thumbnail);
  container.appendChild(button);

  LocationStarred.appendChild(starredParticipant);
  LocationStarred.appendChild(container);

  if (json.openWindow == "true") {
    if (json.idGroup != undefined) {
      let idGroup = document.getElementById(json.idGroup);
      idGroup.appendChild(LocationStarred);

      $("#" + json.idGroup).find(".starredMessage").sort(function (a, b) {
        return $(b).attr('data-index') - $(a).attr('data-index');
      }).appendTo("#" + json.idGroup);

    } else {
      createGroup(LocationStarred, json.creation);
    }

  } else {
    createGroup(LocationStarred, json.creation);
  }
}

function openStarred(json) {

  for (let i = 0; i < json.itens.length; i++) {

    switch (json.itens[i].media_type) {
      case 1:
      case 27:
        TextStarred(json.itens[i]);
        break;
      case 2:
        AudioStarred(json.itens[i]);
        break;
      case 3:
        ImageStarred(json.itens[i]);
        break;
      case 4:
        DocumentStarred(json.itens[i]);
        break;
      case 7:
        LocationStarred(json.itens[i]);
        break;
      case 5:
        VideoStarred(json.itens[i]);
        break;
      case 9:
        ContactStarred(json.itens[i]);
        break;
      case 10:
        ZipStarred(json.itens[i]);
      default:
        break;
    }
  }
}


function verifyTypeStarred(idGroup, dtGroup, json) {

  switch (json.media_type) {
    case 1:

      TextStarred({
        key_remote_id: json.key_remote_id,
        key_from_me: json.key_from_me,
        creation: json.creation,
        dateGroup: dtGroup,
        token: json.token,
        idGroup: idGroup,
        data: json.data,
        openWindow: "true",
        quoted: {
          data: json.quoted.data,
          title: json.quoted.title,
          media_url: json.quoted.media_url,
          media_type: json.quoted.media_type,
          media_mime_type: json.quoted.media_mime_type
        }
      });
      break;
    case 2:

      AudioStarred({
        key_remote_id: json.key_remote_id,
        key_from_me: json.key_from_me,
        creation: json.creation,
        dateGroup: dtGroup,
        token: json.token,
        idGroup: idGroup,
        media_url: json.media_url,
        openWindow: "true",
        quoted: {
          data: json.quoted.data,
          media_url: json.quoted.media_url,
          media_type: json.quoted.media_type,
          media_mime_type: json.quoted.media_mime_type
        }
      });
      break;
    case 3:

      ImageStarred({
        key_remote_id: json.key_remote_id,
        media_caption: json.media_caption,
        key_from_me: json.key_from_me,
        creation: json.creation,
        dateGroup: dtGroup,
        token: json.token,
        idGroup: idGroup,
        data: json.data,
        media_url: json.media_url,
        openWindow: "true",
        quoted: {
          data: json.quoted.data,
          media_url: json.quoted.media_url,
          media_type: json.quoted.media_type,
          media_mime_type: json.quoted.media_mime_type
        }
      });
      break;
    case 4:

      DocumentStarred({
        key_remote_id: json.key_remote_id,
        media_mime_type: json.media_mime_type,
        key_from_me: json.key_from_me,
        thumb_image: json.thumb_image,
        media_title: json.media_title,
        creation: json.creation,
        dateGroup: dtGroup,
        token: json.token,
        idGroup: idGroup,
        data: json.data,
        media_url: json.media_url,
        openWindow: "true",
        quoted: {
          data: json.quoted.data,
          media_url: json.quoted.media_url,
          media_type: json.quoted.media_type,
          media_mime_type: json.quoted.media_mime_type
        }
      });
      break
    case 5:

      VideoStarred({
        key_remote_id: json.key_remote_id,
        media_caption: json.media_caption,
        key_from_me: json.key_from_me,
        creation: json.creation,
        dateGroup: dtGroup,
        token: json.token,
        idGroup: idGroup,
        data: json.data,
        media_url: json.media_url,
        openWindow: "true",
        quoted: {
          data: json.quoted.data,
          media_url: json.quoted.media_url,
          media_type: json.quoted.media_type,
          media_mime_type: json.quoted.media_mime_type
        }
      });
      break;


    case 7:

      LocationStarred({
        key_remote_id: json.key_remote_id,
        media_mime_type: json.media_mime_type,
        media_caption: json.media_caption,
        key_from_me: json.key_from_me,
        media_title: json.media_title,
        id_message: json.id_message,
        longitude: json.longitude,
        creation: json.creation,
        dateGroup: dtGroup,
        token: json.token,
        idGroup: idGroup,
        data: json.data,
        media_url: json.media_url,
        openWindow: "true",
        quoted: {
          data: json.quoted.data,
          media_url: json.quoted.media_url,
          media_type: json.quoted.media_type,
          media_mime_type: json.quoted.media_mime_type
        }
      });
      break

    case 9:

      ContactStarred({
        key_remote_id: json.key_remote_id,
        media_mime_type: json.media_mime_type,
        media_caption: json.media_caption,
        key_from_me: json.key_from_me,
        media_title: json.media_title,
        id_message: json.id_message,
        creation: json.creation,
        dateGroup: dtGroup,
        token: json.token,
        idGroup: idGroup,
        data: json.data,
        media_url: json.media_url,
        openWindow: "true",
        quoted: {
          data: json.quoted.data,
          media_url: json.quoted.media_url,
          media_type: json.quoted.media_type,
          media_mime_type: json.quoted.media_mime_type
        }
      });
      break

    case 10:

      ZipStarred({
        key_remote_id: json.key_remote_id,
        media_mime_type: json.media_mime_type,
        key_from_me: json.key_from_me,
        media_title: json.media_title,
        creation: json.creation,
        dateGroup: dtGroup,
        token: json.token,
        idGroup: idGroup,
        data: json.data,
        media_url: json.media_url,
        openWindow: "true",
        quoted: {
          data: json.quoted.data,
          media_url: json.quoted.media_url,
          media_type: json.quoted.media_type,
          media_mime_type: json.quoted.media_mime_type
        }
      });
      break
    default:
      break;
  }
}

function updateStarred(json) {

  let creation = new Date(json.creation * 1000);
  let date = creation.getFullYear() + '/' + creation.getMonth() + '/' + creation.getDate();

  let idGroup = null;
  let dtGroup = null;
  let exist = "false";
  let groups = $(".ongrups");

  if (groups[0] != undefined) {

    for (var i = 0; i < groups.length; i++) {

      dtGroup = $(".ongrups")[i].dataset.id;

      dateGroup = new Date(dtGroup.split("_gp")[1] * 1000);
      dateGroup = dateGroup.getFullYear() + '/' + dateGroup.getMonth() + '/' + dateGroup.getDate();

      if (dateGroup == date) {

        exist = "true";
        idGroup = $("[data-id=" + dtGroup + "]")[0].id;
        verifyTypeStarred(idGroup, dtGroup, json);
      }
    }
    if (groups.length == i && exist == "false") {
      checkDataStar = 0;
      creationGroup = "true";
      verifyTypeStarred(idGroup, dtGroup, json);
    }

  } else {
    groupStarred = 1;
    checkDataStar = 0;
    creationGroup = "true";
    verifyTypeStarred(idGroup, dtGroup, json);
  }
}

function pushQuoted(quoted_id, media_type) {

  let media_mime_type;
  let mediaUrl;
  let mediaType;
  let title;
  let data;
  let v;

  switch (media_type.split(" ")[0]) {
    case "container-message":

      data = $("#" + quoted_id).find(".textMessage").find(".container-quoted").find("span").text();
      mediaType = "1";
      break;
    case "container-audio":

      mediaUrl = $("#" + quoted_id).find(".textMessage").find(".container-quoted").find("div").find("a").attr("href");
      mediaType = "2";
      break;
    case "container-img":

      mediaUrl = $("#" + quoted_id).find(".textMessage").find(".container-quoted").find("div").find("a").attr("href");
      mediaType = "3";
      break;
    case "container-document":

      mediaUrl = $("#" + quoted_id).find(".textMessage").attr("data-url");
      title = $("#" + quoted_id).find(".textMessage").find(".container-document").find("span").text();
      mediaType = "4";

      if (mediaUrl != "null") {
        v = mediaUrl.split("/v/")[1];

        switch (v.split(".")[1]) {
          case "xls":
            media_mime_type = "application/vnd.ms-excel";
            break;

          case "doc":
          case "docx":
            media_mime_type = "application/octet-stream";
            break;

          case "pdf":
            media_mime_type = "application/pdf";
            break;

          default:
            break;
        }
      }
      break;

    case "container-video":
      mediaUrl = $("#" + quoted_id).find(".textMessage").attr("data-url");
      mediaType = "5";
      break;

    case "container-location":
      mediaType = "7";
      break;

    case "container-contact":
      mediaType = "9";
      break;

    case "container-archive":

      mediaUrl = $("#" + quoted_id).find(".textMessage").attr("data-url");
      title = $("#" + quoted_id).find(".textMessage").find(".container-archive").find("span").text();
      mediaType = "10";

      v = mediaUrl.split("/f/")[1];

      switch (v.split(".")[1]) {
        case "xls":
        case "xlsx":
          media_mime_type = "application/vnd.ms-excel";

          break;
        case "txt":
          media_mime_type = "text/plain";

          break;
        case "doc":
        case "docx":
          media_mime_type = "application/octet-stream";

          break;
        default:
          break;
      }
      break;
    default:
      break;
  }

  let obj = {
    data: data,
    title: title,
    media_url: mediaUrl,
    media_type: mediaType,
    media_mime_type: media_mime_type,

  }

  return obj;
}


function redirectMessage(focused) {
  const focused_element = document.getElementById(focused);
  if (!focused_element) return;

  focused_element.scrollIntoView({ behavior: 'smooth' });

  const class_name = Array.from(focused_element.querySelectorAll("div"))
    .map(div => div.className)
    .find(name => name.includes("left") || name.includes("right"));

  if (class_name) {
    const is_left = class_name.includes("left");
    const side_class = is_left ? "left" : "right";

    const is_dark_mode = document.body.classList.contains("darkMessenger");
    const primary_color = is_left ? (is_dark_mode ? "#444444" : "#dddddd") : (is_dark_mode ? "#172168" : "#1a4eaa");
    const secondary_color = is_left ? (is_dark_mode ? "#141414" : "#ffffff") : (is_dark_mode ? "#1b277c" : "#2263d3");
    const quoted_bg_color = is_left ? (is_dark_mode ? "#333333" : "#d4d4d4") : (is_dark_mode ? "#121a52" : "#0f3d7f");
    const quoted_reset_color = is_left ? (is_dark_mode ? "#1f1f1f" : "#f4f4f4") : (is_dark_mode ? "#172168" : "#1a4eaa");

    const apply_styles = (selector, styles, delay) => {
      setTimeout(() => {
        focused_element.querySelectorAll(selector).forEach(element => {
          Object.assign(element.style, styles);
        });
      }, delay);
    };

    apply_styles(`.${side_class}`, { backgroundColor: primary_color }, 1400);
    apply_styles(`.${side_class}`, { backgroundColor: secondary_color }, 2000);
    apply_styles(".container-quoted", { backgroundColor: quoted_bg_color }, 1400);
    apply_styles(".container-quoted", { backgroundColor: quoted_reset_color }, 2000);
    apply_styles(".bodyDocument", { backgroundColor: quoted_bg_color }, 1400);
    apply_styles(".bodyDocument", { backgroundColor: quoted_reset_color }, 2000);
    apply_styles(".btnTemplate", { backgroundColor: primary_color }, 1400);
    apply_styles(".btnTemplate", { backgroundColor: secondary_color }, 2000);
    apply_styles(".btnInteractive", { backgroundColor: primary_color }, 1400);
    apply_styles(".btnInteractive", { backgroundColor: secondary_color }, 2000);

    const tail_selector = is_left ? ".tailOutMessageLeft" : ".tailOutMessageRight";
    apply_styles(`${tail_selector} svg path`, { fill: primary_color }, 1400);
    apply_styles(`${tail_selector} svg path`, { fill: secondary_color }, 2000);

    document.querySelectorAll(`.item .${side_class}`).forEach(element => {
      element.style.backgroundColor = secondary_color;
    });
  }

  setTimeout(() => {
    document.querySelectorAll(".item-searched").forEach(item => (item.disabled = false));
    const gallery_button = document.getElementById("view-tem-gallery");
    if (gallery_button) gallery_button.disabled = false;
  }, 2400);
}


function alertMessage(json) {

  let alertsOnScreen = document.querySelectorAll('.notification-alert-template')
  let alertsToRemove = document.querySelectorAll('[counter="0"]')
  let alertsToStay = document.querySelectorAll('[counter="1"]')

  let counter = 0

  if (alertsOnScreen.length == 1) {
    counter++
  } else if (alertsOnScreen.length == 2) {

    alertsToRemove.forEach(element => {
      element.remove()
    });

    alertsToStay.forEach(element => {
      element.setAttribute("counter", "0")
    });
    counter++

  }

  lastAlert = json.items.slice(-2);

  let limit = 1

  if (json.Cmd == 'queryAlertNotifications') {

    alertsOnScreen.forEach(element => {
      element.remove()
    })

  }

  if (json.Cmd == 'queryAlertNotifications' && json.items.length > 1) {
    limit = 2
  }

  for (let i = 0; i < limit; i++) {
    var alert = document.createElement("div");
    alert.classList.add("notification-alert-template");
    alert.setAttribute("counter", counter);
    counter++

    var div_img = document.createElement("div");
    div_img.classList.add("img");

    var icon = document.createElement("i");
    icon.classList.add("fas");

    var div_body = document.createElement("div");
    div_body.classList.add("body");

    var div_title = document.createElement("div");
    div_title.classList.add("title");
    div_title.innerHTML = lastAlert[i].title;

    var div_text = document.createElement("div");
    div_text.classList.add("text");
    div_text.innerHTML = lastAlert[i].text;

    var div_link = document.createElement("div");
    div_link.classList.add("link");

    var link = document.createElement("a");
    link.href = lastAlert[i].media_url;
    link.innerHTML = lastAlert[i].link_text;
    link.target = "_blank";

    switch (lastAlert[i].messenger_template) {
      case '1':
        alert.classList.add("success");
        icon.classList.add("fa-bell");
        break;
      case '2':
        alert.classList.add("info");
        icon.classList.add("fa-info-circle");
        break;
      case '3':
        alert.classList.add("warning");
        icon.classList.add("fa-exclamation-circle");
        break;
      case '4':
        alert.classList.add("danger");
        icon.classList.add("fa-exclamation-circle");
        break;
    }

    div_img.appendChild(icon)
    div_link.appendChild(link)
    div_body.appendChild(div_title)
    div_body.appendChild(div_text)
    div_body.appendChild(div_link)
    alert.appendChild(div_img)
    alert.appendChild(div_body)
    $("#list-notification").prepend(alert);
  }

  $('#list-active, #list-internal, #list-wait').css({ height: 'calc(100% - 300px)' });

}


function showAlertMessages(json) {

  const alert_container = document.getElementById("alert_container");

  while (alert_container.firstChild) {
    alert_container.removeChild(alert_container.firstChild);
  }

  const alert_message_box = document.createElement("div");
  const icon_box = document.createElement("div");
  const alert_icon = document.createElement("img");
  const message_box = document.createElement("div");
  const alert_title = document.createElement("span");
  const alert_text = document.createElement("span");
  const alert_link = document.createElement("a");
  const close_button = document.createElement("button");

  alert_message_box.classList.add("alert-message");
  message_box.classList.add("message-box");
  alert_title.classList.add("alert-title");
  alert_text.classList.add("alert-text");
  alert_link.id = "alert_link";
  alert_link.target = "_blank";
  close_button.classList.add("close-button");
  close_button.innerHTML = "&times;";

  switch (json.Cmd) {
    case "paymentMessage":
      alert_message_box.classList.add("alert-danger");
      alert_icon.src = "/assets/icons/messenger/exclamation_triangle_fill.svg";
      alert_title.innerText = json.items.title;
      alert_text.innerText = json.items.text;
      alert_link.href = json.items.url;
      alert_link.innerHTML = json.items.link_text;
      break;
    default:
      break;
  }

  icon_box.appendChild(alert_icon);
  alert_message_box.appendChild(icon_box);
  message_box.appendChild(alert_title);
  message_box.appendChild(alert_text);
  message_box.appendChild(alert_link);
  alert_message_box.appendChild(message_box);
  alert_message_box.appendChild(close_button);

  setTimeout(() => {
    alert_container.appendChild(alert_message_box);
  }, 1000);
}


const openIcon = function (idIcon) {

  let counts = parseInt($('#ball_' + idIcon).find('.numberLabel').html());
  if ($('#ball_' + idIcon).hasClass('checkModal') && counts == 0) {
    $('#md_' + idIcon).parent().find('.icone').show();
  } else {
    $('#md_' + idIcon).parent().find('.icone').show();
  }
  $('#' + idIcon).mouseleave(function () {
    $('#md_' + idIcon).parent().find('.icone').hide()
  });
}


const openM = (idItem) => {

  quoted_id = "";

  const card_element = document.getElementById(idItem);
  const modal_element = document.getElementById(`md_${idItem}`);
  const info = messenger.ChatList.find(idItem);

  if (!card_element.classList.contains("itemSelected")) card_element.click();

  const chat_number = modal_element.closest(".item").dataset.numberchat >= 6;
  const selected_tab = modal_element.closest(".list").id;
  const position = chat_number ? event.clientY - (selected_tab === "list-internal" ? 145 : 190) : event.clientY + 10;

  const modal_content = `
  <div class="modalMessenger openModal" style="top: ${position}px">
    <li class="selectModal" id="chat-waiting_${idItem}" title="Ctrl+Alt+W">${GLOBAL_LANG.messenger_tooltip_chat_actions_put_on_hold}</li> 
    <li class="selectModal" id="chat-attendance_${idItem}" title="Ctrl+Alt+W">${GLOBAL_LANG.messenger_tooltip_chat_actions_resume_chat}</li>
    <li class="selectModal" id="chat-trans_${idItem}" title="Ctrl+Alt+T">${GLOBAL_LANG.messenger_tooltip_chat_actions_transfer_service}</li>
    <li class="selectModal" id="fixar_${idItem}" title="Ctrl+Alt+P">${GLOBAL_LANG.messenger_tooltip_chat_actions_fix_conversation}</li>
    <li class="selectModal" id="notFixar_${idItem}" title="Ctrl+Alt+P">${GLOBAL_LANG.messenger_tooltip_chat_actions_unpin_conversation}</li>
    <li class="selectModal" id="read_${idItem}">${GLOBAL_LANG.messenger_tooltip_chat_actions_mark_read}</li>
    <li class="selectModal" id="notRead_${idItem}">${GLOBAL_LANG.messenger_tooltip_chat_actions_mark_unread}</li>
    <li class="selectModal" id="close_${idItem}" title="Ctrl+Alt+Backspace">${GLOBAL_LANG.messenger_tooltip_chat_actions_close_conversation}</li>
  </div>`;

  modal_element.classList.remove("hide");
  modal_element.innerHTML = modal_content;

  const notification_alert = document.getElementById(`ball_${idItem}`);
  const number_label = notification_alert.querySelector('.numberLabel');
  const counts = parseInt(number_label.textContent, 10);
  const not_read_element = document.getElementById(`notRead_${idItem}`);
  const read_element = document.getElementById(`read_${idItem}`);

  if (notification_alert.classList.contains("checkModal") && counts == 0) {
    not_read_element.style.display = "";
    read_element.style.display = "none";
  } else {
    not_read_element.style.display = "none";
    read_element.style.display = "";
  }

  const pin_icon = modal_element.closest(".contact-config-actions").querySelector(".iconFixar");
  const pin_chat = document.getElementById(`fixar_${idItem}`);
  const unpin_chat = document.getElementById(`notFixar_${idItem}`);

  if (pin_icon.classList.contains("fixed")) {
    pin_chat.style.display = "none";
    unpin_chat.style.display = "";
  } else {
    pin_chat.style.display = "";
    unpin_chat.style.display = "none";
  }

  const listType = document.getElementById(idItem).parentElement.id;

  if (listType === "list-internal") {
    document.getElementById(`chat-attendance_${idItem}`).style.display = 'none';
    document.getElementById(`chat-trans_${idItem}`).style.display = 'none';
    document.getElementById(`chat-waiting_${idItem}`).style.display = 'none';
  } else if (listType === "list-wait") {
    document.getElementById(`chat-waiting_${idItem}`).style.display = 'none';
  } else if (listType === "list-active") {
    document.getElementById(`chat-attendance_${idItem}`).style.display = 'none';
  }

  read_element.addEventListener('click', (e) => {

    notification_alert.style.display = "none";
    notification_alert.classList.add("checkModal");

    if (info != null) {
      ta.chat.makeRead(info.key_remote_id);
      document.getElementById(info.hash).querySelector(".no-read-message label").innerHTML = 0;
      messenger.ChatList.updateCountView();
    }
    modal_element.querySelector(".modalMessenger").classList.add("hide");
    e.stopPropagation();
  });

  not_read_element.addEventListener("click", (e) => {

    notification_alert.style.display = "";
    notification_alert.classList.remove("checkModal");
    number_label.style.color = '#4ec545';

    if (info != null) {
      ta.chat.makeUnRead(info.key_remote_id);
      document.getElementById(info.hash).querySelector(".no-read-message label").innerHTML = 1;
      messenger.ChatList.updateCountView();
    }
    modal_element.querySelector(".modalMessenger").classList.add("hide");
    e.stopPropagation();
  });

  pin_chat.addEventListener('click', (e) => {

    pin_icon.classList.add("fixed");
    pin_icon.style.display = "block";

    $(`#md_${idItem}`).parent().parent().parent().parent().parent().parent().data().fixed_timestamp = new Date().getTime();

    messenger.ChatList.updateCountView();
    ta.chat.makeFixed(info.key_remote_id);

    modal_element.querySelector(".modalMessenger").classList.add("hide");
    e.stopPropagation();
  })

  unpin_chat.addEventListener('click', (e) => {

    pin_icon.classList.remove("fixed");
    pin_icon.style.display = "none";

    $(`#md_${idItem}`).parent().parent().parent().parent().parent().parent().data().fixed_timestamp = null;

    messenger.ChatList.updateCountView();
    ta.chat.makeNotFixed(info.key_remote_id);

    modal_element.querySelector(".modalMessenger").classList.add("hide");
    e.stopPropagation();
  })

  document.getElementById(`chat-trans_${idItem}`).addEventListener("click", () => {
    showTransferModal()
  });

  document.getElementById(`chat-waiting_${idItem}`).addEventListener("click", () => {
    document.getElementById("close_settings_toolbar").click();
    messenger.Chat.hide();
    messenger.Chat.selected = idItem;
    messenger.Chat.wait();
    modal_element.querySelector(".modalMessenger").classList.add("hide");
  });

  $("#chat-attendence" + idItem).live("click", function () {
    messenger.Chat.hide();
    messenger.Chat.selected = idItem;
    messenger.Chat.show();
    messenger.Chat.attendance();
  });

  document.getElementById(`chat-attendance_${idItem}`).addEventListener("click", () => {
    messenger.Chat.selected = idItem;
    messenger.Chat.show();
    messenger.Chat.attendance();
  });

  $("#close_" + idItem).live("click", function () {

    if (messenger.Chat.is_private == 1) {
      $("#close-chat").click();

    } else {
      ta.chat.close();
      messenger.Chat.selected = '';
      messenger.ChatList.Remove(ta.key_remote_id);
      $(".colors").css("display", "none");
      $(".messenger").find(".right").find(".head").addClass("hide_max");
      $(".messenger").find(".right").find(".body").find(".chat").addClass("hide_max");
    }
  });
}

function modalCloseMessenger() {

  const bgBoxMessenger = document.createElement("div");
  bgBoxMessenger.className = "bg-box-messenger def__closeModal";
  bgBoxMessenger.id = "bgBoxMessenger";

  const modal = document.createElement("div");
  modal.className = "def-modal close-modal";

  const body = document.createElement("div");
  body.className = "def-body";
  body.innerHTML = GLOBAL_LANG.messenger_modal_exit_text;

  const footer = document.createElement("div");
  footer.className = "def-footer";

  const btnSave = document.createElement("button");
  btnSave.className = "def-btn-save btn-close conn-close close-messenger-button";
  btnSave.innerHTML = GLOBAL_LANG.messenger_modal_exit_btn_exit;

  const btnCancel = document.createElement("button");
  btnCancel.className = "btn-cancel def-btn-cancel def__closeModal";
  btnCancel.innerHTML = GLOBAL_LANG.messenger_modal_exit_btn_cancel;

  footer.appendChild(btnCancel);
  footer.appendChild(btnSave);

  modal.appendChild(body);
  modal.appendChild(footer);

  $("#bgBoxMessenger").remove();
  $(".def-modal").remove();

  document.querySelector("html body").appendChild(modal);
  document.querySelector("html body").appendChild(bgBoxMessenger);

}

function showTransferModal() {

  const bgBoxMessenger = document.createElement("div");
  bgBoxMessenger.className = "bg-box-messenger def__closeModal";
  bgBoxMessenger.id = "bgBoxMessenger";

  const modal = document.createElement("div");
  modal.className = "def-modal";

  const header = document.createElement("div");
  header.className = "def-header";

  const title = document.createElement("div");
  title.className = "title";
  title.innerHTML = `<span>${GLOBAL_LANG.messenger_modal_transfer_service_title}</span>`;

  const svg_close_icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
  svg_close_icon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
  svg_close_icon.setAttribute("width", "15");
  svg_close_icon.setAttribute("height", "15");
  svg_close_icon.setAttribute("viewBox", "0 0 22 22");
  svg_close_icon.setAttribute("class", "icon-close-right");

  const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
  path_element.setAttribute(
    "d",
    "M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z"
  );
  path_element.setAttribute("fill", "#666666");

  svg_close_icon.appendChild(path_element);

  const iconClose = document.createElement("div");
  iconClose.className = "close def__closeModal";
  iconClose.innerHTML = "";
  iconClose.appendChild(svg_close_icon);

  header.appendChild(title);
  header.appendChild(iconClose);

  const body = document.createElement("div");
  body.className = "def-body";

  const lbSetor = document.createElement("label");
  lbSetor.textContent = GLOBAL_LANG.messenger_modal_transfer_service_setor;
  lbSetor.className = "def-label";

  const selectSetor = document.createElement("select");
  selectSetor.className = "def-select select select-user-group";

  const option = document.createElement("option");
  option.value = 0;
  option.text = GLOBAL_LANG.messenger_modal_transfer_service_setor_placeholder;
  selectSetor.appendChild(option);

  for (let i = 0; i < processUser.groups.length; i++) {
    let option = document.createElement("option");
    option.value = processUser.groups[i].id_user_group;
    option.text = processUser.groups[i].name;
    selectSetor.appendChild(option);
  }

  const lbUser = document.createElement("label");
  lbUser.textContent = GLOBAL_LANG.messenger_modal_transfer_service_user;
  lbUser.className = "def-label";

  const selectUser = document.createElement("select");
  selectUser.className = "def-select select-user";

  const checkbox = document.createElement("input");
  checkbox.type = "checkbox";
  checkbox.className = "def-checkbox";

  const lbCheckbox = document.createElement("span");
  lbCheckbox.textContent = GLOBAL_LANG.messenger_modal_transfer_service_set_sector_default;
  lbCheckbox.className = "def-span";

  body.appendChild(lbSetor)
  body.appendChild(selectSetor)
  body.appendChild(lbUser)
  body.appendChild(selectUser)
  body.appendChild(checkbox)
  body.appendChild(lbCheckbox)

  const footer = document.createElement("div");
  footer.className = "def-footer";

  const btnSave = document.createElement("button");
  btnSave.id = "btn-trans-ok";
  btnSave.className = "def-btn-save";
  btnSave.innerHTML = GLOBAL_LANG.messenger_modal_transfer_service_btn_transfer;

  const btnCancel = document.createElement("button");
  btnCancel.id = "btn-trans-cancel";
  btnCancel.className = "def-btn-cancel def__closeModal";
  btnCancel.innerHTML = GLOBAL_LANG.messenger_modal_transfer_service_btn_cancel;

  footer.appendChild(btnCancel);
  footer.appendChild(btnSave);

  modal.appendChild(header);
  modal.appendChild(body);
  modal.appendChild(footer);

  $("#bgBoxMessenger").remove();
  $(".def-modal").remove();

  document.querySelector("html body").appendChild(modal);
  document.querySelector("html body").appendChild(bgBoxMessenger);
}


function showContactForwardModal() {

  const bgBoxMessenger = document.createElement("div");
  bgBoxMessenger.className = "bg-box-messenger def__closeModal";
  bgBoxMessenger.id = "bgBoxMessenger";

  const modal = document.createElement("div");
  modal.className = "def-modal";

  const header = document.createElement("div");
  header.className = "def-header";

  const title = document.createElement("div");
  title.className = "title";
  title.innerHTML = `<span>${GLOBAL_LANG.messenger_modal_forward_title}</span>`;

  const svg_close_icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
  svg_close_icon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
  svg_close_icon.setAttribute("width", "15");
  svg_close_icon.setAttribute("height", "15");
  svg_close_icon.setAttribute("viewBox", "0 0 22 22");
  svg_close_icon.setAttribute("class", "icon-close-right");

  const path_element = document.createElementNS("http://www.w3.org/2000/svg", "path");
  path_element.setAttribute(
    "d",
    "M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z"
  );
  path_element.setAttribute("fill", "#666666");

  svg_close_icon.appendChild(path_element);

  const iconClose = document.createElement("div");
  iconClose.className = "close def__closeModal";
  iconClose.innerHTML = "";
  iconClose.appendChild(svg_close_icon);

  header.appendChild(title);
  header.appendChild(iconClose);

  const body = document.createElement("div");
  body.className = "def-body";

  const search = document.createElement("div");
  search.className = "def-search";
  search.innerHTML = `<i class="fas fa-search icon-search-catalog"></i>
    <i class="fas fa-arrow-left icon-close-text-catalog"></i>
    <input  class="def-input" type="text" name="" placeholder="${GLOBAL_LANG.messenger_modal_forward_placeholder}" id="search-contact-forward">`

  const body_content = document.createElement("div");
  body_content.className = "body";

  body.appendChild(search)
  body.appendChild(body_content)

  const footer = document.createElement("div");
  footer.className = "def-footer";

  const sendContactForward = document.createElement("div");
  sendContactForward.className = "sendContactForward"
  sendContactForward.id = "sendContactForward"

  const sendContactForwardImg = document.createElement("img");
  sendContactForwardImg.src = "assets/img/iconSendCatalog.png"

  sendContactForward.appendChild(sendContactForwardImg);
  footer.appendChild(sendContactForward);

  modal.appendChild(header);
  modal.appendChild(body);
  modal.appendChild(footer);

  $("#bgBoxMessenger").remove();
  $(".def-modal").remove();

  document.querySelector("html body").appendChild(modal);
  document.querySelector("html body").appendChild(bgBoxMessenger);

}


const handleFileInput = (e) => {

  let files = e.target.files

  files = e.target.files
  if (!files) return
  ([...files]).forEach(f => {
    if (f.type === 'image/jpeg' || f.type === 'image/png') {

      let img = localStorage.getItem('imagesCatalog')

      if (img < 10) {
        new Promise((resolve, reject) => {
          onChangeFileUploadProduct(f, resolve)
        })
          .then((result) => {
            this.files.push({
              base: f,
              ext: f.name.split('.')[1],
              sizeImg: formatBytes(f.size),
              url: URL.createObjectURL(f),
              urlOn: result['media_url'],
              thumbnail: result['base64'],
              mediaCaption: result['media_caption'],
              mediaMimeType: result['media_mime_type'],
              mediaSize: f.size,
              bg: colorBg
            });

            localStorage.setItem('imagesCatalog', parseInt(img) + 1)
          });
      }
    }
  });
}


const onChangeFileUploadProduct = (file, resolve) => {

  let urlUpload = '//localhost:8021'

  let data = new FormData()
  data.append("arq", file)

  let requestOptions = {
    method: 'POST',
    body: data,
    redirect: 'follow'
  }

  const res = fetch(`${urlUpload}`, requestOptions)
    .then(response => resolve(response.json()))
    .then(result => {
      csrf_token_talkall.value = Cookies.get("csrf_cookie_talkall")
    })
    .catch(error => console.log('error', error))

  return res
}


function switchToDark(elm) {

  // função para alterar icones, borda, fontes, etc... quando estiver no modo dark.
  if (bNight === true) {

    $(".btn_close .img-close").attr("src", document.location.origin + "/assets/icons/messenger/dark/close2.svg");

    switch (elm) {
      case "modalQueryTeam":
        $("#teamIconSearch").attr("src", document.location.origin + "/assets/icons/messenger/dark/search.svg");
        $("#modalQueryTeam .icon-close").attr("src", document.location.origin + "/assets/icons/messenger/dark/close2.svg");
        break;
      case "windowSearch":
        $("#iconSearchMessage").attr("src", document.location.origin + "/assets/icons/messenger/dark/search2.svg");
        $(".window__search #iconSearchLeft").attr("src", document.location.origin + "/assets/icons/messenger/dark/search.svg");
        $(".window__search #iconArrowLeft").attr("src", document.location.origin + "/assets/icons/messenger/dark/arrow-left.svg");
        $(".window__search #close_settings_search").attr("src", document.location.origin + "/assets/icons/messenger/dark/close2.svg");
        break;
      case "windowFavorite":
        $(".window__favorite .icon-close-right").attr("src", document.location.origin + "/assets/icons/messenger/dark/close2.svg");
        break;
      case "windowOption":
        $("#close_settings_toolbar").attr("src", document.location.origin + "/assets/icons/messenger/dark/close2.svg");
        break;
      case "modalBoxTemplete":
        $(".modalBoxTemplete .icon-close").attr("src", document.location.origin + "/assets/icons/messenger/dark/close2.svg");
        break;
    }

  } else {

    $(".btn_close .img-close").attr("src", document.location.origin + "/assets/icons/messenger/close3.svg");

    switch (elm) {
      case "modalQueryTeam":
        $("#teamIconSearch").attr("src", document.location.origin + "/assets/icons/messenger/search3.svg");
        $("#modalQueryTeam .icon-close").attr("src", document.location.origin + "/assets/icons/messenger/close3.svg");
        break;
      case "windowSearch":
        $("#iconSearchMessage").attr("src", document.location.origin + "/assets/icons/messenger/search2.svg");
        $(".window__search #iconSearchLeft").attr("src", document.location.origin + "/assets/icons/messenger/search.svg");
        $(".window__search #iconArrowLeft").attr("src", document.location.origin + "/assets/icons/messenger/arrow-left.svg");
        $(".window__search #close_settings_search").attr("src", document.location.origin + "/assets/icons/messenger/close2.svg");
        break;
      case "windowFavorite":
        $(".window__favorite .icon-close-right").attr("src", document.location.origin + "/assets/icons/messenger/close2.svg");
        break;
      case "windowOption":
        $("#close_settings_toolbar").attr("src", document.location.origin + "/assets/icons/messenger/close2.svg");
        break;
      case "modalBoxTemplete":
        $(".modalBoxTemplete .icon-close").attr("src", document.location.origin + "/assets/icons/messenger/close3.svg");
        break;
    }

  }

}


function formatNumber(number) {

  var CODE = 0, DD = 0, NUMBER = 0;

  for (let i = 0; i < number.length; i++) {

    if (i <= 1) {
      if (CODE === 0) CODE = number.charAt(i); else CODE += number.charAt(i);
    }

    if (i > 1 && i <= 3) {
      if (DD === 0)
        DD = number.charAt(i); else DD += number.charAt(i);
    }

    if (i > 3) {
      if (NUMBER === 0)
        NUMBER = number.charAt(i); else NUMBER += number.charAt(i);
    }
  }

  if (number.length < 15) {
    document.getElementById("optionNumber").style.display = "flex";
    return "+" + CODE + " " + DD + " " + NUMBER;
  } else {
    document.getElementById("optionNumber").style.display = "none";
  }

}


function verifyEmailContact(email) {

  if (email === "" || email === null || email == "null") {
    $("#optionEmail").hide();
    $("#email").html(email);
  } else {
    $("#email").html(email);
    $("#optionEmail").show();
  }
}


function verifyChannelContact(type) {

  switch (type) {
    case 1:
      $("#iconChannel").find("img").remove();
      $("#iconChannel").append(`<img src="${document.location.origin}/assets/img/talkall.png">`);
      break;
    case 2:
    case 12:
    case 16:
      $("#iconChannel").find("img").remove();
      $("#iconChannel").append(`<img src="${document.location.origin}/assets/icons/messenger/whatsapp-business.svg">`);
      break;
    case 8:
      $("#iconChannel").find("img").remove();
      $("#iconChannel").append(`<img src="${document.location.origin}/assets/img/facebook.png">`);
      break;
    case 9:
      $("#iconChannel").find("img").remove();
      $("#iconChannel").append(`<img src="${document.location.origin}/assets/img/instagram_integration.png">`);
      break;
    default:
      $("#iconChannel").find("img").remove();
      break;
  }
}


function showBoxNote(clear) {

  if (clear) $("#note").val("");
  $(".note-contact").find(".subtitle").hide();
  $(".note-contact").find(".add-note-contact").hide();
  $(".note-contact").find(".box-note").show();

  $(".box-note").find("textarea").focus();
  $(".messenger .option .body").scrollTop(+999);
}


function saveNote() {

  if ($("#note").val() != "") {
    this.disabled = true;
    ta.chat.note($("#note").val());

    $(".note-contact").find(".subtitle").show();
    $(".note-contact").find(".subtitle").show();
    $(".note-contact").find(".box-note").hide();
    $("#infoNoteContact").val($("#note").val());
    $(".note-contact").find(".add-note-contact").hide();
  } else {
    ta.chat.note($("#note").val());
    $(".note-contact").find(".subtitle").hide();
    $(".note-contact").find(".box-note").hide();
    $(".note-contact").find(".add-note-contact").show();
  }
}

function getTextColor(color) {
  let r, g, b;

  if (color.startsWith("#")) {
    r = parseInt(color.substring(1, 3), 16);
    g = parseInt(color.substring(3, 5), 16);
    b = parseInt(color.substring(5, 7), 16);
  } else if (color.startsWith("rgb")) {
    let rgbValues = color.match(/\d+/g);
    r = parseInt(rgbValues[0], 10);
    g = parseInt(rgbValues[1], 10);
    b = parseInt(rgbValues[2], 10);
  } else {
    return "#000000";
  }

  // Calculo da luminância relativa
  let luminance = 0.2126 * r + 0.7152 * g + 0.0722 * b;

  return luminance > 186 ? "#000000" : "#F4F4F4";
}

function labelContactInfo() {

  $(".item-label-contact").remove();
  $("#iconAddLabelInfo").remove();
  $("#iconLabelInfo").remove();
  $(".icon-label-info").remove();

  const chat_labels = document.querySelectorAll(".chat-labels");
  const labelContact = document.getElementById("labelContact");

  let is_label = false;
  let excess_count = 0;

  labelContact.innerHTML = "";

  for (let i = 0; i < chat_labels.length; i++) {
    const elm = chat_labels[i];

    if (i < 3) {
      const label = document.createElement("span");
      label.classList = "item-label-contact";
      label.innerHTML = elm.innerHTML;
      label.style.backgroundColor = elm.style.background;
      label.style.color = getTextColor(elm.style.background);

      labelContact.appendChild(label);
      is_label = true;
    } else {
      excess_count++;
    }
  }

  if (excess_count > 0) {
    const span_label_hidden = document.createElement("span");
    span_label_hidden.classList = "item-label-hidden";
    span_label_hidden.innerHTML = `+${excess_count}`;
    labelContact.appendChild(span_label_hidden);
  }

  if (is_label) {

    const svg = `
    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox = "-0.5 0 15 15">
      <path d="M13.6698 6.90566H7.72641V0.96226C7.72641 0.804631 7.6638 0.653458 7.55234 0.541998C7.44088 0.430538 7.2897 0.36792 7.13208 0.36792V0.36792C6.97445 0.36792 6.82327 0.430538 6.71181 0.541998C6.60035 0.653458 6.53774 0.804631 6.53774 0.96226V6.90566H0.59434C0.436711 6.90566 0.285538 6.96827 0.174078 7.07973C0.0626178 7.19119 0 7.34237 0 7.5H0C0 7.65762 0.0626178 7.8088 0.174078 7.92026C0.285538 8.03172 0.436711 8.09433 0.59434 8.09433H6.53774V14.0377C6.53774 14.1954 6.60035 14.3465 6.71181 14.458C6.82327 14.5695 6.97445 14.6321 7.13208 14.6321C7.2897 14.6321 7.44088 14.5695 7.55234 14.458C7.6638 14.3465 7.72641 14.1954 7.72641 14.0377V8.09433H13.6698C13.8274 8.09433 13.9786 8.03172 14.0901 7.92026C14.2015 7.8088 14.2642 7.65762 14.2642 7.5C14.2642 7.34237 14.2015 7.19119 14.0901 7.07973C13.9786 6.96827 13.8274 6.90566 13.6698 6.90566Z" fill="white"/>
    </svg>
    `;

    const div = document.createElement("div");
    div.id = "iconAddLabelInfo";

    div.insertAdjacentHTML("beforeend", svg);
    labelContact.appendChild(div);

    $(".info").find("#labelContact").removeClass("hover-item-label");
    $(".info").find("#labelContact").addClass("label-contact-right");

  } else {

    const svg = `
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="4 4 19.5 17.5" style="cursor: pointer;">
        <path d="M13.4763 4.21134L6.99575 6.13402C6.87486 6.16984 6.76437 6.2357 6.67471 6.32535C6.58506 6.415 6.51921 6.52549 6.48339 6.64638L4.56071 13.127C4.44791 13.5071 4.4381 13.9081 4.53231 14.2883C4.62652 14.6685 4.82132 15.0142 5.09652 15.2895L11.1801 21.373C11.863 22.0543 12.7949 22.4302 13.7712 22.4184C14.7476 22.4067 15.6888 22.0081 16.3884 21.3102L21.6596 16.039C22.3575 15.3395 22.756 14.3983 22.7678 13.4219C22.7796 12.4455 22.4036 11.5136 21.7224 10.8307L15.6388 4.74716C15.3635 4.47196 15.0179 4.27716 14.6377 4.18295C14.2574 4.08874 13.8565 4.09854 13.4763 4.21134ZM20.6681 11.8849C21.0775 12.2943 21.3036 12.8536 21.2965 13.4396C21.2894 14.0257 21.0498 14.5905 20.6304 15.0099L15.3593 20.2811C14.9399 20.7005 14.375 20.9401 13.789 20.9471C13.2029 20.9542 12.6437 20.7282 12.2343 20.3188L6.15075 14.2352C6.05848 14.1434 5.99312 14.0279 5.96149 13.9008C5.92987 13.7737 5.93314 13.6396 5.97096 13.5125L7.77793 7.42857L13.8624 5.62106C13.9895 5.58341 14.1235 5.58028 14.2505 5.61199C14.3775 5.64371 14.4929 5.70911 14.5846 5.80139L20.6681 11.8849Z" fill="#000000"></path>
        <path d="M11.0484 10.6992C11.4851 10.2625 11.4936 9.56298 11.0673 9.13671C10.641 8.71044 9.94146 8.71888 9.50478 9.15556C9.06811 9.59223 9.05967 10.2918 9.48594 10.7181C9.91221 11.1443 10.6118 11.1359 11.0484 10.6992Z" fill="#000000"></path>
    </svg>
    `;

    const div_svg = document.createElement("div");
    div_svg.className = "div-svg label-contact-tag";
    div_svg.id = "iconLabelInfo";

    const label = document.createElement("label");
    label.innerHTML = GLOBAL_LANG.messenger_window_option_add_tags;
    label.className = "icon-label-info";
    label.type = "text";
    label.style = "cursor: pointer;";

    div_svg.insertAdjacentHTML("beforeend", svg);
    labelContact.appendChild(div_svg);
    labelContact.appendChild(label);

    $(".info").find("#labelContact").removeClass("label-contact-right");
    $(".info").find("#labelContact").addClass("hover-item-label");
  }
}


function decreaseFullName() {
  $("#compressed_contact_name").show();
  $("#contact_full_name").hide();
}


function contactFullName() {
  $("#compressed_contact_name").hide();
  $("#contact_full_name").show();
}


function alertCloseChat(info) {

  const chat_open = $("#" + info.hash)[0].dataset.chat_open.split("__")[0];
  const name_user = $("#" + info.hash)[0].dataset.chat_open.split("__")[1];

  if (chat_open == "false") {

    Swal.fire({
      title: GLOBAL_LANG.messenger_contact_info_note_title,
      text: GLOBAL_LANG.messenger_contact_alert_close_chat.replace("{{name_user}}", name_user),
      type: 'warning',
      confirmButtonColor: '#1fbb78',
      confirmButtonText: GLOBAL_LANG.messenger_contact_modal_btn_ok,
      cancelButtonClass: "btn btn-secondary",

    }).then(t => {
      if (t.value == true) {
        $(".option")[0].style.display = "none";
        $(".messenger .right")[0].style = "width: calc(100% - 360px)";
        $(".right").hide();
        $(".option").hide();
        $("#modal").hide();
        $("#sub-more").hide();
        $("#" + info.hash).remove();
        $("#" + info.chat).remove();
        messenger.ChatList.updateCountView();
        messenger.ChatList.Remove(ta.key_remote_id);

      }
    });
  }

  if (chat_open == "true") {
    return false
  } else {
    return true
  }
}


function buttonBottomScroll() {

  const button_scroll = document.querySelector(".buttonBottomScroll");
  const input_text = document.querySelector(".input-text");
  const msg_block = document.querySelector(".msgBlock");
  const field_ai = document.querySelector(".field-ai");
  const chat_field_ai = document.querySelector(".chat .field-ai");

  if (msg_block && msg_block.classList.contains("msgBlock")) {
    button_scroll.style.marginBottom = "18px";

  } else if (field_ai && window.getComputedStyle(field_ai).display === "block") {
    let height = parseInt(window.getComputedStyle(chat_field_ai).height) + input_text.clientHeight;
    button_scroll.style.marginBottom = height + "px";

  } else {
    button_scroll.style.marginBottom = input_text.clientHeight + "px";
  }

}


function getFormattedText() {
  let input = document.querySelector(".input-text");

  let text = input.innerHTML
    .replace(/<div><br><\/div>/g, "\n")
    .replace(/<div>/g, "\n")
    .replace(/<\/div>/g, "")
    .replace(/<br>/g, "\n");

  return text.trim();
}

function openFlowModal({ title, evaluationNote, comment }) {

  function formatStars(starsText) {
    if (!starsText || starsText === "N/A") {
      return '<span style="color: #dee2e6;">☆☆☆☆☆</span>';
    }

    const filledStars = (starsText.match(/[★⭐]/g) || []).length;
    const emptyStars = 5 - filledStars;

    let starsHtml = '';

    for (let i = 0; i < filledStars; i++) {
      starsHtml += '<span style="color: #FFD700;">★</span>';
    }

    for (let i = 0; i < emptyStars; i++) {
      starsHtml += '<span style="color: #dee2e6;">☆</span>';
    }
    return starsHtml;
  }

  const modal = document.createElement("div");
  modal.className = "flow-modal";
  Object.assign(modal.style, {
    position: "fixed",
    top: "0",
    left: "0",
    width: "100vw",
    height: "100vh",
    display: "flex",
    justifyContent: "center",
    alignItems: "center",
    zIndex: "999",
    backdropFilter: "blur(3px)",
    animation: "fadeIn 0.3s ease"
  });

  const content = document.createElement("div");
  content.className = "flow-modal-body";
  Object.assign(content.style, {
    padding: "24px",
    borderRadius: "16px",
    width: "90%",
    maxWidth: "380px",
    boxShadow: "0 8px 32px rgba(0, 0, 0, 0.2)",
    fontFamily: "Arial, sans-serif",
    animation: "slideUp 0.3s ease",
    position: "relative"
  });

  content.innerHTML = `
        <style>
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideUp {
                from { transform: translateY(30px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
            .modal-stars {
                font-size: 28px;
                letter-spacing: 4px;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                display: inline-block;
                margin-left: 8px;
            }
            .modal-section {
                padding: 14px;
                border-radius: 10px;
                margin-bottom: 14px;
                border-left: 4px solid #2263d3;
            }
            .modal-label {
                font-size: 12px;
                color: #6c757d;
                text-transform: uppercase;
                font-weight: 600;
                letter-spacing: 0.5px;
                margin-bottom: 6px;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .modal-value {
                font-size: 15px;
                color: #212529;
                line-height: 1.5;
                word-wrap: break-word;
            }
            .modal-icon {
                font-size: 16px;
            }
            .modal-close-x {
                position: absolute;
                top: 16px;
                right: 16px;
                width: 32px;
                height: 32px;
                border: none;
                background-color: transparent;
                color: #adb5bd;
                border-radius: 50%;
                cursor: pointer;
                font-size: 28px;
                font-weight: 300;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
                line-height: 1;
                padding: 0;
            }
            .modal-close-x:hover {
                background-color: #f8f9fa;
                color: #495057;
            }
            .modal-close-x:active {
                transform: scale(0.95);
            }
        </style>
        
        <button class="modal-close-x" aria-label="Fechar">×</button>
        
        <h3 class="flow-modal-title" style="margin: 0 0 20px 0; font-size: 20px; color: #ffffffff; font-weight: 700; border-bottom: 2px solid #e9ecef; padding-bottom: 12px; padding-right: 40px;">
            ${title}
        </h3>
        
        <div class="modal-section">
            <div class="modal-label">
                <span class="modal-icon">⭐</span>
                ${GLOBAL_LANG.messenger_modal_assessment}
            </div>
            <div class="modal-value">
                <span class="modal-stars">${formatStars(evaluationNote)}</span>
            </div>
        </div>
        
        <div class="modal-section">
            <div class="modal-label">
                <span class="modal-icon">💬</span>
                ${GLOBAL_LANG.messenger_modal_comment}
            </div>
            <div class="modal-value">
                ${comment}
            </div>
        </div>
    `;

  content.querySelector(".modal-close-x").onclick = () => {
    modal.style.animation = "fadeOut 0.2s ease";
    setTimeout(() => modal.remove(), 200);
  };

  modal.onclick = (e) => {
    if (e.target === modal) {
      modal.style.animation = "fadeOut 0.2s ease";
      setTimeout(() => modal.remove(), 200);
    }
  };

  modal.appendChild(content);
  document.body.appendChild(modal);
}

function setOptionCategories(categorias) {
  const select = document.getElementById('select_modal_close');
  if (!select) return;

  select.innerHTML = '';

  const defaultOption = document.createElement("option");
  defaultOption.selected = true;
  defaultOption.innerText = `${GLOBAL_LANG.messenger_option_placeholder_category}`;
  defaultOption.value = null;
  select.appendChild(defaultOption);

  categorias.forEach(item => {
    const option = document.createElement("option");
    option.value = item.id_category;
    option.innerText = item.name;
    select.appendChild(option);
  });
}