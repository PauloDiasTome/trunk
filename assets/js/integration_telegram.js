
const form = document.querySelector("form");
form.addEventListener("submit", function (event) {
    event.preventDefault();
    const token = document.getElementById("input-token-telegram").value;
    
    if(token == ''){
      document.getElementById("alert_input_token_telegram").style.display = "block";
      return;
    }
    
    if(!token.includes(':')){
        document.getElementById("alert_input_token_telegram").style.display = "none";
        document.getElementById("alert_token_invalid").style.display = "block";
      return;
    }

    form.submit();
});