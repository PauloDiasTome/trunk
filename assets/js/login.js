let request = true;
let timeResendCode = null;
const CURRENT_URL = window.location.href;

const checkCapsLock = (event) => {

	var flag = event.getModifierState && event.getModifierState('CapsLock');

	if (flag == true) {

		const capsLock = document.querySelector(".capsLock");
		capsLock.style.display = "block";
		capsLock.innerHTML = "Caps Lock Ativado";

		const password = document.getElementById("password");
		password.style = "border-bottom:solid 1px #f7a71e";

	} else {
		const capsLock = document.querySelector(".capsLock");

		if (capsLock != null) {
			capsLock.style.display = "none";
			capsLock.innerHTML = "";

			const password = document.getElementById("password");
			password.style = "border-bottom: 1px solid rgb(34, 99, 211, 80%)";
		}
	}
}

window.addEventListener("keydown", checkCapsLock);


function validEmail(email) {

	if (email != undefined) {
		if (email != "") {
			user = email.substring(0, email.indexOf("@"));
			domain = email.substring(email.indexOf("@") + 1, email.length);

			if ((user.length >= 1) &&
				(domain.length >= 3) &&
				(user.search("@") == -1) &&
				(domain.search("@") == -1) &&
				(user.search(" ") == -1) &&
				(domain.search(" ") == -1) &&
				(domain.search(".") != -1) &&
				(domain.indexOf(".") >= 1) &&
				(domain.lastIndexOf(".") < domain.length - 1)) {

				console.log("email valido");
				$(".input-email").css({
					"border-bottom": "solid 1px rgb(34, 99, 211, 80%)"
				});
			} else {
				$(".input-email").css({
					"border-bottom": "solid 1px  #f7a71e"
				});
			}
		} else {
			removeBorderOrange();
		}
	}
}


function removeBorderOrange() {

	const input_email = document.querySelectorAll(".input-email");

	for (elm of input_email) {
		elm.style.borderBottom = "solid 1px rgb(34, 99, 211, 80%)";
	}
}


const textResendCode = (expired) => {

	clearTimeout(timeResendCode);

	timeResendCode = setTimeout(() => {

		if (expired) {
			$(".expired-code").delay(700).fadeIn(400);
			$(".login-difficulty").delay(700).fadeIn(400);
		} else {
			$(".resend-code").delay(700).fadeIn(400); (100)
			$(".login-difficulty").delay(700).fadeIn(400);
		}

	}, 12000);
}


function returnPage() {

	const host = document.location.origin;

	if (CURRENT_URL === host + "/account/logon") {
		return window.location.href = "/";
	}

	if (CURRENT_URL === host + "/account/ResetPassword") {
		return window.location.href = host + "/account/forgotPassword";
	}

	return window.location.href = CURRENT_URL;
}


const openForgot = () => window.location.href = document.location.origin + "/account/forgotPassword";
const returnLogin = () => window.location.href = document.location.origin + "/";

const bgbox = document.getElementById("bgbox");

if (bgbox != null) bgbox.addEventListener("click", returnPage);


function openModal(data) {

	const bgbox = document.getElementById("bgbox");
	const modalAlert = document.getElementById("modalAlert");

	const modalTitle = document.querySelector("#modalAlert .title span");
	const modalSubtitle = document.querySelector("#modalAlert .subtitle span");
	const modalDescription = document.querySelector("#modalAlert .description span");
	const modalBtn = document.querySelector("#modalAlert .button input");


	switch (data.type) {
		case "success":

			var box_md_left = document.getElementById("box-md-left");
			box_md_left.classList.add("_ok-backg2");

			var icon_ok = document.getElementById("icon_ok");
			icon_ok.style.display = "block";

			var goToLogin = document.getElementById("goToLogin");
			goToLogin.classList.add("_ok-backg3");

			if (goToLogin != null) goToLogin.addEventListener("click", returnLogin);

			break;
		case "email":

			var box_md_left = document.getElementById("box-md-left");
			box_md_left.classList.add("_err-backg");

			var icon_error = document.getElementById("icon_error_login");
			icon_error.style.display = "block";

			var box_md_right = document.getElementById("box-md-right");
			box_md_right.classList.add("_err-font2");

			var goToLogin = document.getElementById("goToLogin");
			goToLogin.classList.add("_err-backg3");

			if (goToLogin != null) goToLogin.addEventListener("click", returnPage);

			break;
		case "error":

			var box_md_left = document.getElementById("box-md-left");
			box_md_left.classList.add("_err-backg");

			var icon_error = document.getElementById("icon_error_login_password");
			icon_error.style.display = "block";

			var box_md_right = document.getElementById("box-md-right");
			box_md_right.classList.add("_err-font2");

			var goToLogin = document.getElementById("goToLogin");
			goToLogin.classList.add("_err-backg3");

			if (goToLogin != null) goToLogin.addEventListener("click", returnPage);

			break;
		case "block":

			var box_md_left = document.getElementById("box-md-left");
			box_md_left.classList.add("_block-backg");

			var icon_error = document.getElementById("icon_error_login_block");
			icon_error.style.display = "block";

			var box_md_right = document.getElementById("box-md-right");
			box_md_right.classList.add("_block-font");

			var goToLogin = document.getElementById("goToLogin");
			goToLogin.classList.add("_block-backg2");

			if (goToLogin != null) goToLogin.addEventListener("click", openForgot);

			break;
		default:
			break;
	}

	bgbox.style.display = "block";
	modalAlert.style.display = "block";

	modalTitle.innerHTML = data.title != "" ? data.title : "";
	modalSubtitle.innerHTML = data.subtitle != "" ? data.subtitle : "";
	modalDescription.innerHTML = data.description != "" ? data.description : "";
	modalBtn.value = data.btn != "" ? data.btn : "";
}


function alert2FA(data) {

	const modalAuthen = document.getElementById("modalAuthen");
	modalAuthen.classList.add("anime");
	modalAuthen.style = "animation: treme 0.1s; animation-iteration-count: 3;";

	if (data.type == "alert") {

		const alert_ = document.querySelector(".md-bottom .alert_ span");
		alert_.innerHTML = data.message;

		const items = document.querySelectorAll(".container-el .item");

		for (item of items) {
			item.style = "transition: all 0.2s ease;border-bottom: 4px solid red!important";
			item.value = "";
		}

		document.getElementsByName("FA1")[0].focus();

	} else if (data.type == "user_block") {

		const alert_ = document.querySelector(".md-bottom .alert_ span");
		alert_.innerHTML = data.message;

		const itens = document.querySelectorAll(".container-el .item");

		for (item of itens) {
			item.disabled = true;
			item.style.backgroundColor = "#c4d8eb73";
			item.style.borderBottom = "solid 0px";
			item.style.color = "#0058aa8f";
		}

		document.querySelector(".resend-code").style.display = "none";
		document.querySelector(".login-difficulty").style.display = "none";

		clearTimeout(timeResendCode);
		return;
	}

	setTimeout(() => {
		modalAuthen.classList.remove("anime");
		modalAuthen.style = "";
	}, 750);
}


function resendCODE() {

	const email = document.getElementById("email2FA").value;
	const password = document.getElementById("password2FA").value;

	const formData = new FormData();
	formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));
	formData.append("email", email);
	formData.append("password", password);
	formData.append('type', 'resend-code');

	$.ajax({
		url: (document.location.origin + "/account/logon/2fa"),
		type: "POST",
		data: formData,
		processData: false,
		contentType: false,
		success: function (data) {

			if (data === 200) {
				textResendCode(false);
			} else if (data.type === "expired") {
				textResendCode(true);
			} else {
				alert2FA(data);
			}
		},
		error: (err) => console.log(err)
	});

	document.querySelector(".resend-code").style.display = "none";
	document.querySelector(".login-difficulty").style.display = "none";
}


function auth() {

	if (!request) return;

	request = false;
	clearTimeout(timeResendCode);

	const FA1 = document.getElementsByName("FA1")[0].value;
	const FA2 = document.getElementsByName("FA2")[0].value;
	const FA3 = document.getElementsByName("FA3")[0].value;
	const FA4 = document.getElementsByName("FA4")[0].value;
	const FA5 = document.getElementsByName("FA5")[0].value;
	const FA6 = document.getElementsByName("FA6")[0].value;

	const email = document.getElementById("email2FA").value;
	const password = document.getElementById("password2FA").value;

	const formData = new FormData();
	formData.append("code", FA1 + FA2 + FA3 + FA4 + FA5 + FA6);
	formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));
	formData.append("email", email);
	formData.append("password", password);
	formData.append('type', 'twofa-form');

	$.ajax({
		url: (document.location.origin + "/account/logon/2fa"),
		type: "POST",
		data: formData,
		processData: false,
		contentType: false,
		success: function (data) {

			request = true;
			textResendCode(false);

			if (data.type == "success") {
				window.location.href = document.location.origin + "/contact";

			} else if (data.type == "alert" || data.type == "user_block") {
				alert2FA(data);
			}
			document.querySelector(".load").style.display = "none";
		},
		error: (err) => console.log(err)
	});

	document.querySelector(".load").style.display = "block";
	document.querySelector(".resend-code").style.display = "none";
	document.querySelector(".login-difficulty").style.display = "none";
}


function focusClick() {

	const elm = this;

	const itens = document.querySelectorAll(".container-el .item");

	for (item of itens) {

		item.className = "item";
		if (item.value != "") {
			item.style = "transition: all 0.5s ease;border-bottom: 1.5px solid #fff;";
		}
	}

	elm.style = "transition: all 0.2s ease;border-bottom: 4px solid #0058aa;";
	elm.className = "item focused";
}


function preventBackspace(e) {
	var evt = e || window.event;
	if (evt) {
		var keyCode = evt.charCode || evt.keyCode;
		if (keyCode === 8) {
			if (evt.preventDefault) {
				evt.preventDefault();
			} else {
				evt.returnValue = false;
			}
		}
	}
}


function next(e) {

	if (e.keyCode == 8) {

		const item = document.querySelector(".focused");

		if (item != null) {
			if (item.value == "") {

				item.className = "item";
				item.style = "transition: all 0.2s ease;border-bottom: 4px solid #0058aa;";


				if (item.previousElementSibling != null) {

					const elementSibling = item.previousElementSibling;
					elementSibling.className = "item focused";
					elementSibling.style = "transition: all 0.2s ease;border-bottom: 4px solid #0058aa;";
					elementSibling.focus();
				}
			} else {
				item.value = "";
			}
		}

	} else {

		if (this.value == "") {
			return;
		}

		const itens = document.querySelectorAll(".container-el .item");

		this.style = "transition: all 0.5s ease;border-bottom: 1.5px solid rgb(34, 99, 211, 80%);";

		if (this.nextSibling.nextSibling != null) {

			for (item of itens) item.className = "item";

			const next = this.nextSibling.nextSibling;
			next.focus();
			next.classList.add("focused");
			next.style = "transition: all 0.2s ease;border-bottom: 4px solid #0058aa;";
		}
	}

	let validation_item = 0;
	const itens = document.querySelectorAll(".container-el .item");

	for (item of itens) {
		if (item.value != "") {
			validation_item++
		}
	}

	if (validation_item === 6) {
		auth();
	}
}


function openModal2fa(data) {

	const bgbox = document.getElementById("bgbox");
	const modalAuthen = document.getElementById("modalAuthen");

	var icon_ok = document.getElementById("icon_authen");
	icon_ok.style.display = "block";

	var title = document.querySelector(".md-authen .header .title span");
	title.innerHTML = data.title;

	var description = document.querySelector(".md-authen .header .description");
	description.innerHTML = data.description;

	var goToLogin = document.getElementById("goToLogin");

	if (goToLogin != null) goToLogin.addEventListener("click", returnPage);

	bgbox.style.display = "block";
	modalAuthen.style.display = "block";

	var input_email = document.createElement("input");
	input_email.type = "hidden";
	input_email.id = "email2FA";
	input_email.value = data.email;

	var input_password = document.createElement("input");
	input_password.type = "hidden";
	input_password.id = "password2FA";
	input_password.value = data.password;

	document.querySelector(".container").appendChild(input_email);
	document.querySelector(".container").appendChild(input_password);

	const item = document.querySelectorAll('.container-el .item');
	item.forEach((elm) => elm.addEventListener('keyup', next));
	item.forEach((elm) => elm.addEventListener('click', focusClick));

	document.getElementsByName("FA1")[0].focus();
	document.getElementById('classModal2FA').addEventListener('click', returnPage);

	document.getElementById("resendCode").addEventListener("click", resendCODE)

	textResendCode(false);
}


function viewPassw() {
	const password = document.getElementById("password");

	if (password.type === "password") {
		password.type = "text";
		document.getElementById("viewPassword").className = "far fa-eye view-password";
	} else {
		password.type = "password";
		document.getElementById("viewPassword").className = "far fa-eye-slash view-password";
	}
}


function showIcon() {
	const password = document.getElementById("password");

	if (this.value.length >= 1) {
		document.getElementById("viewPassword").style.display = "inline-block"
		document.getElementById("viewPassword").className = "far fa-eye-slash view-password";
	} else {
		document.getElementById("viewPassword").style.display = "none";
		document.getElementById("viewPassword").className = "far fa-eye-slash view-password";
	}

	if (password.type === "password") {
		document.getElementById("viewPassword").className = "far fa-eye-slash view-password";
	} else {
		document.getElementById("viewPassword").className = "far fa-eye view-password";
	}
}


function viewPasswConf() {
	const passconf = document.getElementById("passconf");

	if (passconf.type === "password") {
		passconf.type = "text";
		document.getElementById("viewPasswordConf").className = "far fa-eye view-password-conf";
	} else {
		passconf.type = "password";
		document.getElementById("viewPasswordConf").className = "far fa-eye-slash view-password-conf";
	}
}


function showIconConf() {

	if (this.value.length >= 1) {

		const password = document.getElementById("password").value;
		const passconf = document.getElementById("passconf").value;

		if (password !== passconf) {
			document.getElementById("passconf").style.borderBottom = "solid 1px #f7a71e";
		} else {
			document.getElementById("passconf").style.borderBottom = "solid 1px rgb(34, 99, 211, 80%)";
		}

		document.getElementById("viewPasswordConf").style.display = "inline-block"
		document.getElementById("viewPasswordConf").className = "far fa-eye-slash view-password-conf";
	} else {
		document.getElementById("viewPasswordConf").style.display = "none";
		document.getElementById("viewPasswordConf").className = "far fa-eye-slash view-password-conf";
		document.getElementById("passconf").style.borderBottom = "solid 1px rgb(34, 99, 211, 80%)";
	}

	const passconf = document.getElementById("passconf");
	if (passconf.type === "password") {
		document.getElementById("viewPasswordConf").className = "far fa-eye-slash view-password";
	} else {
		document.getElementById("viewPasswordConf").className = "far fa-eye view-password";
	}
}


const password = document.getElementById("password");
const passconf = document.getElementById("passconf");

const viewPassword = document.getElementById("viewPassword");
const viewPasswordConf = document.getElementById("viewPasswordConf");

if (password != null) password.addEventListener("keyup", showIcon);
if (passconf != null) passconf.addEventListener("keyup", showIconConf);
if (passconf != null) document.getElementById('passconf').onpaste = () => false;

if (viewPassword != null) viewPassword.addEventListener("click", viewPassw);
if (viewPasswordConf != null) viewPasswordConf.addEventListener("click", viewPasswConf);


function submit() {

	const password = document.getElementById("password").value;
	const passconf = document.getElementById("passconf").value;

	if (password.length <= 5) {

		const data = {
			type: "error",
			title: "Atenção",
			description: "O campo senha deve ter no mínimo 6 caracteres para ser válido.",
			subtitle: "",
			btn: "Voltar"
		}

		openModal(data);
		return;
	}

	if (password !== passconf) {

		const data = {
			type: "error",
			title: "Atenção",
			description: "O campo confirmação de senha não corresponde ao campo senha.",
			subtitle: "",
			btn: "Voltar"
		}

		openModal(data);
		return;
	}

	document.querySelector("form").submit();
}


if (document.getElementById("sendForm") != null) {

	const form = document.querySelector("form");
	const sendForm = document.querySelector("#sendForm");

	form.addEventListener("submit", function (event) {
		event.preventDefault();
	}, true);

	sendForm.addEventListener("click", submit);
}


function nextSlider() {

	const ball = document.querySelectorAll(".ball");

	const qtdeBall = ball.length - 1;

	for (i = 0; i < ball.length; i++) {

		if (ball[i].attributes.class.nodeValue == "ball selected") {

			document.getElementById("move-button").click();

			const idx = i + 1;

			ball[i].innerHTML = "&#9675;";
			ball[i].attributes.class.nodeValue = "ball";

			if (qtdeBall == i) {

				ball[0].innerHTML = "&#9679;";
				ball[0].attributes.class.nodeValue = "ball selected";
			} else {

				ball[idx].innerHTML = "&#9679;";
				ball[idx].attributes.class.nodeValue = "ball selected";

			}
			break;
		}
	}
}

const next_ball = document.getElementById("next-ball");

if (next_ball != null) {
	next_ball.addEventListener("click", nextSlider);
}
