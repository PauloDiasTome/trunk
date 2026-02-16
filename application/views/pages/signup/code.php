<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação WhatsApp</title>
</head>

<body>
    <div class="card">
        <div class="check"></div>
        <h2>Valide seu Telefone</h2>
        <p>
            Enviamos um código de 5 dígitos via TalkAll para o seu número de WhatsApp:
            <strong><?= $company['phone_responsible1'] ?></strong>
            <i class="fas fa-pencil-alt edit-phone" id="edit-phone" title="Alterar número"></i>
        </p>
        <p>Informe o código recebido:</p>
        <div class="input-group">
            <input id="code" type="text" maxlength="5" placeholder="-----">
            <small id="code-error" class="error-message"></small>
        </div>
        <button id="validate-button">Validar Número</button>
        <div class="resend">
            <b>Não recebeu o código?</b>
            <br>
            <a href="#" class="whatsapp">
                <i class="fab fa-whatsapp"></i> Reenviar via WhatsApp
            </a>
            <a href="#" class="sms">
                <i class="fas fa-comment-dots"></i> Reenviar via SMS
            </a>
        </div>
    </div>
</body>
<script>
    const TOKEN = "<?= $token ?>";
</script>

</html>