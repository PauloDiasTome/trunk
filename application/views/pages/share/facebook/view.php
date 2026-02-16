<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Compartilhar com Facebook</title>

    <meta property="og:url" content="https://app.talkall.com.br/facebook/share/" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="https://app.talkall.com.br/assets/img/background/bg_temp_1.jpg" />
    <meta property="og:title" content="Teste compartilhamento" />
    <meta property="og:description" content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi accumsan sagittis dictum. Suspendisse commodo scelerisque pharetra. Mauris viverra eleifend est id laoreet." />
    <meta property="fb:app_id" content="1423271047711875" />

    <style>
        body {
            background: linear-gradient(119deg, rgba(58, 193, 140) 00%, rgba(53, 173, 191) 100%);
        }

        div {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
            text-align: center;
        }

        h1 {
            margin-top: 150px;
            color: white;
        }
    </style>
</head>

<body>
    <div>
        <h1>Estamos te redirecionando para o Facebook</h1>
    </div>

    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId: '1423271047711875',
                autoLogAppEvents: true,
                xfbml: true,
                version: 'v13.0'
            });
            FB.ui({
                method: 'share',
                href: 'http://whts.me/yDo5',
                display: 'popup',
                hashtag: '',
                quote: ''
            }, function(response) {

                console.log(response)

                if (typeof response == 'string') {
                    console.log('Não foi compartilhado')
                } else if (typeof response == 'object') {
                    console.log('Foi compartilhado')
                }
            });
        };
    </script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

</body>

</html>