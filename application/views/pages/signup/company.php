<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - TalkAll</title>
</head>

<body>
    <div class="form-container">
        <h2 class="title">Quase lá! Complete seu Cadastro</h2>
        <p class="subtitle">Faltam apenas alguns detalhes para liberar seu teste gratuito.</p>

        <form>

            <div class="input-group">
                <label for="name">Nome Completo</label>
                <input class="no-ai" type="text" id="name" placeholder="Seu nome e sobrenome">
                <small id="name-error" class="error-message"></small>
            </div>
            <div class="input-group">
                <label for="company">Nome da Empresa (Nome Fantasia)</label>
                <input class="no-ai" type="text" id="company" placeholder="Ex: Minha Empresa de Marketing">
                <small id="company-error" class="error-message"></small>
            </div>
            <div class="input-group">
                <label for="phone">Telefone (WhatsApp)</label>
                <input class="no-ai" type="tel" id="phone" placeholder="Ex: (XX) 9XXXX-XXXX">
                <small id="phone-error" class="error-message"></small>
            </div>

            <p class="note">
                Usaremos este número para enviar o código de validação via TalkAll.
            </p>

            <button type="submit">Finalizar Cadastro e Iniciar Teste</button>

        </form>
    </div>

    <!-- Tela de carregamento -->
    <div id="loading-screen" class="loading-screen" style="display:none;">
        <div class="loading-card">
            <div class="spinner"></div>
            <h2>Configurando seu ambiente...</h2>
            <p>Aguarde um instante enquanto preparamos tudo para você 🚀</p>
        </div>
    </div>
</body>

</html>