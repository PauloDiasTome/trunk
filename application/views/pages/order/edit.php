<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("order_edit_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" id="order_id" name="order_id" value="<?php echo $data['id_messages_order']; ?>">
                    <?php echo form_open("order/save/$id"); ?>
                    <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("order_edit_information") ?></h6>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-creation"><?php echo $this->lang->line("order_creation") ?></label>
                                    <input type="text" id="input-creation" name="input-creation" class="form-control" disabled style="text-align: center;" value="<?php echo $data['creation']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-creation"><?php echo $this->lang->line("order_id_request") ?></label>
                                    <input type="text" id="input-order_id" name="input-order_id" class="form-control" readonly style="text-align: center;" value="<?php echo $data['order_id']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-creation"><?php echo $this->lang->line("order_channel") ?></label>
                                    <input type="text" id="input-order_id" name="input-order_id" class="form-control" readonly style="text-align: center;" value="<?php echo $data['name']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-creation"><?php echo $this->lang->line("order_talkall_id") ?></label>
                                    <input type="text" id="input-key_remote_id" name="input-key_remote_id" class="form-control" readonly style="text-align: center;" value="<?php echo $data['key_remote_id']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-creation"><?php echo $this->lang->line("order_name") ?></label>
                                    <input type="text" id="input-full_name" name="input-full_name" class="form-control" readonly value="<?php echo $data['full_name']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-control-label"><?php echo $this->lang->line("order_address") ?></label>
                                <hr style="width: 100; margin-top:0px">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-postal"><?php echo $this->lang->line("order_postal") ?></label>
                                    <input type="text" id="input-postal" name="input-postal" class="form-control" value="<?php echo $data['postal']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-address"><?php echo $this->lang->line("order_address_label") ?></label>
                                    <input type="text" id="input-address" name="input-address" class="form-control" value="<?php echo $data['address']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-number"><?php echo $this->lang->line("order_number") ?></label>
                                    <input type="text" id="input-number" name="input-number" class="form-control" value="<?php echo $data['number']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-district"><?php echo $this->lang->line("order_district") ?></label>
                                    <input type="text" id="input-district" name="input-district" class="form-control" value="<?php echo $data['district']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-city"><?php echo $this->lang->line("order_city") ?></label>
                                    <input type="text" id="input-city" name="input-city" class="form-control" value="<?php echo $data['city']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-complement"><?php echo $this->lang->line("order_complement") ?></label>
                                    <input type="text" id="input-complement" name="input-complement" class="form-control" value="<?php echo $data['complement']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-control-label"><?php echo $this->lang->line("order_calculated_distance") ?></label>
                                <hr style="width: 100; margin-top:0px">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-distance"><?php echo $this->lang->line("order_distance_km") ?></label>
                                    <input type="text" id="input-distance" name="input-distance" class="form-control" style="text-align: center;" value="<?php echo $data['distance']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-distance-time"><?php echo $this->lang->line("order_duration") ?></label>
                                    <input type="text" id="input-distance-time" name="input-distance-time" class="form-control" style="text-align: center;" value="<?php echo $data['distance_time']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <button id="btn-calculate-google-maps" class="btn btn-info" type="button" style="margin-top: 30px;"><?php echo $this->lang->line("order_btn_calc") ?></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-control-label"><?php echo $this->lang->line("order_product") ?></label>
                                <hr style="width: 100; margin-top:0px">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="datatable-items" cellspacing="0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th><?php echo $this->lang->line("order_edit_column_code") ?></th>
                                                <th><?php echo $this->lang->line("order_edit_column_name") ?></th>
                                                <th><?php echo $this->lang->line("order_edit_column_count") ?></th>
                                                <th><?php echo $this->lang->line("order_edit_column_price") ?></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-creation"><?php echo $this->lang->line("order_item_count") ?></label>
                                    <input type="text" id="input-item_count" name="input-item_count" class="form-control" readonly style="text-align: center;" value="<?php echo $data['item_count']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-subtotal"><?php echo $this->lang->line("order_subtotal") ?></label>
                                    <input type="text" prefix="R$" id="input-subtotal" name="input-subtotal" class="form-control" readonly style="text-align: center;" value="<?php echo $data['subtotal']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-total"><?php echo $this->lang->line("order_total") ?></label>
                                    <input type="text" id="input-total" name="input-total" class="form-control" style="text-align: center;" readonly value="<?php echo $data['total']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-lg-12">
                                <label class="form-control-label"><?php echo $this->lang->line("order_payment") ?></label>
                                <hr style="width: 100; margin-top:0px">
                                <select class="form-control" name="input-payment-method">
                                    <?php foreach ($payment as $row) { ?>
                                        <option value="<?php echo $row['id_payment_method']; ?>" <?php echo isset($row['id_payment_method']) && $row['id_payment_method'] == $data['id_payment_method'] ? "Selected" : ""; ?>>
                                            <?php echo $row['name']; ?> </option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-lg-12">
                                <label class="form-control-label"><?php echo $this->lang->line("order_request_status") ?></label>
                                <hr style="width: 100; margin-top:0px">
                                <select class="form-control" name="input-order-status">
                                    <?php foreach ($order_status as $row) { ?>
                                        <option value="<?php echo $row['id_order_status']; ?>" <?php echo isset($row['id_order_status']) && $row['id_order_status'] == $data['id_order_status'] ? "Selected" : ""; ?>>
                                            <?php echo $row['name']; ?> </option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?php echo base_url(); ?>order"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("order_btn_return") ?></button></a>
                                <button class="btn btn-success" type="submit"><?php echo $this->lang->line("order_btn_save") ?></button>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-lg-12">
                                <?php echo form_error('input-name', '<div class="alert alert-danger alert-dismissible fade show">', '</div>'); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="modal-edit-products" tabindex="-1" role="dialog" data-original-title="" title="">
    <div class="modal-dialog" role="document" data-original-title="" title="">
        <div class="modal-content" data-original-title="" title="">
            <div class="modal-header" data-original-title="" title="" style="background-color: #009688; height: 53px;">
                <span data-icon="x-light" class="" data-dismiss="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="margin-right: 20px; margin-top: -17px; margin-left: 4px; cursor: pointer;">
                        <path fill="#FFF" d="M19.058 17.236l-5.293-5.293 5.293-5.293-1.764-1.764L12 10.178 6.707 4.885 4.942 6.649l5.293 5.293-5.293 5.293L6.707 19 12 13.707 17.293 19l1.765-1.764z">
                        </path>
                    </svg></span>
                <h5 class="modal-title" style="color: white">
                    Editar produto
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body" style="padding-bottom: 0px; padding-top: 11px;" data-original-title="" title="">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="input-product-code">Código</label>
                            <input type="text" id="input-product-code" name="input-product-code" class="form-control" readonly style="text-align: center;" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="input-product-name">Produto</label>
                            <input type="text" id="input-product-name" name="input-product-name" class="form-control" readonly style="text-align: center;" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label" for="input-product-quantity">Quantidade</label>
                            <input type="text" id="input-product-quantity" name="input-product-quantity" class="form-control" readonly style="text-align: center;" value="1">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label" for="input-product-price">Preço</label>
                            <input type="text" id="input-product-price" name="input-product-price" class="form-control" readonly style="text-align: center;" value="0,00">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" data-original-title="" title="">
                <button type="button" class="btn btn-primary btn-add-participant" data-dismiss="modal">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>