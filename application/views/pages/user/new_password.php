<div class="container mt-8 pb-5">
    <!-- Table -->
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card bg-secondary shadow border-0">

                <div class="card-body px-lg-5 py-lg-5">
                    <div class="d-flex justify-content-center mb-5">
                        <img src="/assets/img/brand/blue.png" class="">
                    </div>
                    <div class="mb-3">
                            <small><?php echo validation_errors(); ?></small>
                    </div>
                    <?php echo form_open('setnewpassword'); ?>
                        <input type="hidden" value="<?php echo $data['token'] ?>">
                        <div class="form-group">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control" name="password" placeholder="Nova Senha" type="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control" name="passconf" placeholder="Repita Nova Senha" type="password">
                            </div>
                        </div>
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary mt-4" value="Denifir Nova Senha">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    body {
        background-color: #172b4d !important;
        overflow: hidden;
    }
</style>