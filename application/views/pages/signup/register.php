<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalkAll | Registrar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="https://www.talkall.com.br/wp-content/uploads/2019/02/cropped-icon-32x32.png" sizes="32x32">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>
    <div class="main-container">
        <div class="register-card">

            <p class="card-subtitle">
                Sistema de atendimento via <strong>WhatsApp</strong> com <strong>Kanban</strong> e <strong>IA</strong>
            </p>

            <h1 class="card-title">
                <span class="highlight">TESTE GRÁTIS POR 14 DIAS</span>
            </h1>

            <div class="benefits-container">
                <p class="benefits-intro">Durante o teste, você terá acesso a:</p>

                <ul class="benefits">
                    <li>Atendimento via WhatsApp em equipe</li>
                    <li>Distribuição automática de atendimentos</li>
                    <li>Gestão de conversas em Kanban</li>
                    <li>Chatbot personalizado</li>
                    <li>Escrita, avaliação* e transcrição com IA</li>
                </ul>

                <p class="plan-notice">
                    O acesso ao plano e aos recursos pode ser<br>alterado a qualquer momento.
                </p>

                <p class="create-account-text">
                    <b>Crie sua conta <strong>TalkAll</strong> e descubra novas formas de se conectar com seus clientes:</b>
                </p>
            </div>

            <form id="signup-form" method="POST" action="/signup/post">

                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="exemplo@ex.com">
                    <small id="email-error" class="error-message"></small>
                </div>

                <div class="input-group">
                    <label for="password">
                        Senha<i class="fas fa-question-circle info-icon" title="A senha deve ter pelo menos 8 caracteres, com letras maiúsculas e minúsculas, números e caracteres especiais."></i>
                    </label>
                    <div class="password-wrapper">
                        <input class="no-ai" type="password" id="password" name="password" placeholder="Digite sua senha" autocomplete="new-password">
                        <i class="fas fa-eye-slash toggle-password" data-target="password"></i>
                    </div>
                    <small id="password-error" class="error-message"></small>
                    <div class="password-strength-meter">
                        <div id="strength-bar" class="strength-bar"></div>
                        <div id="strength-text" class="strength-text"></div>
                    </div>
                </div>

                <div class="input-group">
                    <label for="confirm_password">Confirmar senha</label>
                    <div class="password-wrapper">
                        <input class="no-ai" type="password" id="confirm_password" name="confirm_password" placeholder="Confirme sua senha">
                        <i class="fas fa-eye-slash toggle-password" data-target="confirm_password"></i>
                    </div>
                    <small id="confirm-password-error" class="error-message"></small>
                </div>

                <div class="terms-container">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">
                        Li e estou de acordo com os
                        <a href="https://talkall.com.br/pt-BR/termos-de-uso" target="_blank" rel="noopener noreferrer">Termos de Serviço</a> e
                        <a href="https://talkall.com.br/politica-de-privacidade" target="_blank" rel="noopener noreferrer">Políticas de Privacidade</a>
                    </label>
                </div>

                <button type="button" id="next-step-btn" class="main-button">
                    Próximo
                </button>

                <p class="link-login">
                    Já tem uma conta?
                    <a href="https://app.talkall.com.br">Entrar</a>
                </p>
            </form>

            <p class="footer-register">
                ©2026 TalkAll - Todos os direitos reservados.
            </p>

        </div>
    </div>
</body>

</html>