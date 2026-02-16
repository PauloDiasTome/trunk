<html>

<head>
    <title>TalkAll</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/chat.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script type="text/javascript" src="../../assets/js/jquery-1.8.2.js"></script>
    <script type="text/javascript" src="../../assets/js/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <title>TalkAll | Messenger</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/favicon.ico">
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
</head>

<body>
    <?php
    $file = fopen('config.txt', 'r');
    $server = fread($file, 1024);
    ?>
    <input id="webserver" type="hidden" value="<?php echo $server; ?>">
    <div id="talkall-widget" class="talkall-widget">
        <div class="caption">
            <div class="img" style="background-image: url('data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2210%22%20height%3D%2210%22%20viewBox%3D%220%200%2010%2010%22%3E%3Cpath%20fill%3D%22%23fff%22%20d%3D%22M.813.006l9.18%209.18-.806.807L.007.813z%22%2F%3E%3Cpath%20fill%3D%22%23fff%22%20d%3D%22M9.994.813l-9.18%209.18-.808-.806%209.18-9.18z%22%2F%3E%3C%2Fsvg%3E%0A');"></div>
            <div class="caption-text">Envie-nos uma mensagem</div>
            <div class="agent-status-offline"></div>
        </div>
        <div class="chat" style="display: none">
            <div class="messages" ></div>
            <div class="input">
                <input type="text" value="" pattern="Sua mensagem">
            </div>
        </div>
        <div class="form" id="form-contact" style="display:  none">
            <table width="100%" style="padding:20px;">
                <tr>
                    <td><label class="text">Deixe a sua mensagem no formulário abaixo que nós vamos recebê-la por e-mail!</label></td>
                </tr>
                <tr>
                    <td>
                        <textarea name="message" placeholder="Sua mensagem" rows="5" cols="10" style="resize: none; width: 100%; height: 100%"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="name" value="" placeholder="Nome">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="telefone" value="" placeholder="Telefone">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="email" value="" placeholder="E-mail">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="button" id="btn-open" value="Enviar" style="float:right; margin-top:10px">
                    </td>
                </tr>
            </table>
        </div>
        <div class="chat">
            <div class="messages">

            </div>
        </div>
        <div class="input">
            <input type="text" class="input-text" placeholder="Digite sua mensagem" value="">
        </div>
        <div class="widget-bottom">
            <img src="images/logo.png" width="12px">
            <label style="color:darkgrey">Chat desenvolvido por <b style="color:#666">TalkAll</b></label>
        </div>
    </div>
    <script src="jquery/chat.js" type="text/javascript"></script>
</body>

</html>