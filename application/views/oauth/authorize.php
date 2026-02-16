<?php
//$action = "/oauth2/Authorize?response_type=".$response_type."&client_id=".$client_id."&redirect_uri=".$redirect_uri."&state=".$state."&scope=".$scope;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talkall | Authorize App</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/argon.min.css?v=1.0.0">
</head>

<body style="background-color: #172b4d">
  <div class="mt-4 text-center">
    <img src="<?php echo base_url('/assets/img/brand/TalkAll-Branca.png') ?>" class=""  style="width: 20rem;">
  </div>
  <div class="card mx-auto mt-5" style="width: 25rem;">
     <!-- <img class="card-img-top" src="../../assets/img/theme/img-1-1000x600.jpg"> -->
    <div class="card-body">
      <h3 class="card-title ">Autoriza o app <span class="text-lowercase"><?php  echo "$name"; ?> </span> a utilizar sua conta?</h3>
      <p class="card-text">O app gostaria de obter a seguintes permissões: </p>
      <ul class="list-group list-group-flush text-center font-weight-bold">
        <?php 
          $scopes = explode(' ', $scope);
          foreach($scopes as $permission){ 
        ?>
        <li class="list-group-item text-capitalize"> <?php  echo "$permission"; ?></li>
        <?php  } ?>
      </ul>
      <form method="post" action="" class="form-signin">
        <input type="hidden" class="form-control" id="response_type" name="response_type" value="token">
        <input type="hidden" class="form-control" id="client_id" name="client_id" value="<?php echo $client_id ?>">
        <input type="hidden" class="form-control" id="redirect_uri" name="redirect_uri"
            value="<?php echo $redirect_uri ?>">
        <input name="authorized" value="yes" hidden>
        <div class="mt-3">
          <button type="submit" class="btn btn-default btn-block">Autorizar</button>
          <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-block">Cancelar</a>
        </div>
    </form>
    </div>
  </div>
    

    <script src="<?php echo base_url("assets/vendor/jquery/dist/jquery.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"); ?>"></script>
</body>

</html>