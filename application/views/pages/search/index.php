<div class="d-flex align-items-start flex-column">
    <div class="d-flex align-self-center" style="max-width: 584px;width: 100%; <?php echo $data['q'] === "" ? "margin-top: 10%;" : ""; ?>">
        <div class="align-self-end" style="width: 100%;margin-bottom: 13px;">
            <div class='p-2'>
                <img class='img-fluid' src="<?php echo base_url().'assets/img/brand/blue.png' ?>" style="margin-bottom: 29px;margin: 10% 26%;justify-content: center;justify-items: center;">
                <div class="input-group input-group-alternative mt-2" style=''>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input class="form-control form-control-alternative" id='search' autofocus value='<?php echo urldecode($data['q']) ?>' placeholder="Search" type="text" style="max-width: 584px;">
                </div>
            </div>
        </div>
    </div>
</div>
<style>
body{
    overflow: hidden !important;
}
</style>


<?php if($data['erro'] == "" && isset($thead)){ ?>

    <?php //echo "Função: {$data['Fnc']} | Parametros: {$data['Parameters']}" ?>

<div class="container-fluid">
    <div class="row justify-content--center">
        <div class="col-12">
            <div class="card shadow">
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table id="example" class="table align-items-center table-flush" style="margin-top: 0px !important;">
                        <thead class="thead-light">
                            <tr>
                                <?php foreach($thead as $key => $value){ ?>
                                <th scope="col"><?php echo $value; ?></th>
                                <?php }?>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var colunas = [
<?php /// ALTERTA DE GAMBIARRA
foreach($thead as $key => $value){ 
    echo "{'data': '$key'},"; 
}?>
    ]
</script>
<input type="hidden" value="<?php echo $data['Fnc'] ?>" id="Fnc">
<input type="hidden" value="<?php echo $data['Parameters'] ?>" id="Parameters">
<?php }
else{
    if($data['erro'] != ""){
?>    
<div class="container mt-5"> 
    <div class="card" style="margin-bottom: 0px; text-align: center;">
    <div class="card-body">
        <h5 class="card-title"><?php echo $data['erro']; ?></h5>
    </div>
    </div>
</div>
<?php
}}
?>