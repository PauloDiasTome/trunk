<html>
    <head>
    <script type="text/javascript" src="../assets/js/jquery-1.8.2.js"></script>
    <script type="text/javascript" src="../assets/js/jquery-ui.js"></script>
    <script src="../assets/vendor/js-cookie/js.cookie.js"></script>
    <meta property="og:title" content="<?php echo $title;?>">
    <meta property="og:site_name" content="<?php echo $description; ?>">
    <meta property="og:image" content="<?php echo $image; ?>">
    <meta property="og:description" content="<?php echo $description; ?>">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="800">
    <meta property="og:image:height" content="600">
    <script>
        var shortlink = "<?php echo $link; ?>";
        // $.get("https://ipinfo.io?token=8ef6cdc1cdbc41", function(response) {
            $.post("../link/register", {
                csrf_token_talkall: Cookies.get("csrf_cookie_talkall"),
                link: shortlink,
                is_facebook : <?php if(isset($_GET['fbclid'])){ echo "1"; } else { echo "2"; } ?>,
                is_instagram : <?php if(isset($_GET['fbclid'])){ echo "1"; } else { echo "2"; } ?>,
                is_widget : <?php if(isset($_GET['fbclid'])){ echo "1"; } else { echo "2"; } ?>
            }, function(url) {
                    window.location = url;
            });
        // }, "jsonp");
    </script>
</head>

<body>
</body>

</html>