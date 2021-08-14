
<?php if($orders): ?>
<div class="container">
    <div class="accordion" id="order">
        <?php foreach($orders as $order): 
            $oid = $order['id']?>
        <div class="card mb-2">
            <div class="card-header bg-secondary" id="order-<?=$order['id']?>">
                <h2>
                    <button class="btn btn-link btn-block text-left collapsed text-white" type="button" data-toggle="collapse" data-target="#collapse-<?=$order['id']?>" aria-expanded="false" aria-controls="collapse-<?=$order['id']?>">
                        <div class="col" style="font-size:2rem;">Šifra: <?=$order['id']?>
                            <span class="col float-right" style="font-size:1.5rem;">Datum izdaje: <?=format_date("d.m.Y",$order['date_created'])?></span>
                        </div>
                    </button>
                </h2>
            </div>
            <div id="collapse-<?=$order['id']?>" class="collapse" aria-labelledby="order-<?=$order['id']?>" data-parent="#order">
                <div class="card-body border-bottom">
                    <?php $orderItems = $pdo->query("SELECT * FROM order_item WHERE order_id = $oid")->fetchAll(PDO::FETCH_ASSOC);
                    $sum = 0;
                    $orderStatuses = $pdo->query("SELECT * FROM order_status");
                    ?>
                    <?php if($isPrivlage): ?>
                    <div clsas="row">
                        <div class="col form-group row">
                            <div class="col-3">
                                <h4><label for="orderStatus">Stanje naročila: </label><h4>
                            </div>
                            <div class="col-9">
                            <select id="orderStatus" class="form-control" name="orderStatus"required style="width: 250px;">
                                <?php foreach($orderStatuses as $status): ?>
                                    <?php if($status['id'] == $order['order_status_id']): ?>
                                    <option value="<?=$status['id']?>" selected data-oid="<?=$oid?>"><?=$status['status']?></option>
                                    <?php else: ?>
                                    <option value="<?=$status['id']?>" data-oid="<?=$oid?>"><?=$status['status']?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <?php $orderStatus = $pdo->query("SELECT * FROM order_status WHERE id = $order[order_status_id]")->fetch(PDO::FETCH_ASSOC); ?>
                    <div clsas="row">
                    <?php $delivery = $pdo->query("SELECT * FROM delivery_type WHERE id = $order[delivery_type_id]")->fetch(PDO::FETCH_ASSOC); ?>
                        <div class="col">
                            <h4>Stanje naročila: <b><?=$orderStatus['status']?></b></h4>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div clsas="row">
                        <?php $delivery = $pdo->query("SELECT * FROM delivery_type WHERE id = $order[delivery_type_id]")->fetch(PDO::FETCH_ASSOC); ?>
                        <div class="col text-right">
                            <h5>Tip dostave: <b><?=$delivery['type']?> - <?=$delivery['price']?> €</b></h5>
                        </div>
                    </div>
                    <?php foreach($orderItems as $item): 
                    $sum += $item['price']*$item['quantity'];?>
                    <div class="row align-items-center border-bottom border-top my-2 item">
                        <div class="col">
                            <?php $images = productImages($item['product_id']);
                            if($images): ?>
                                <img src="<?=$images[0]['image']?>" alt="<?=$images[0]['caption']?>" class="img-fluid" style="max-height:100px; ">
                            <?php else: ?>
                                <img src="./src/img/missing-image.png" alt="<?=$item['title']?>" class="img-fluid" style="max-height:100px; max-width:200px;">
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <div class="row cart-title"><a href="./product?pid=<?=$item['product_id']?>"><?=substr($item['title'], 0, 65).'...'?></a></div>
                            <div class="row text-muted">
                                SKU: <?=strtoupper($item['sku'])?>
                        </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                Količina:
                            </div>
                            <h5><b><span class="text-center quanity text-lg"><?=$item['quantity']?></span></b></h5>
                        </div>
                        <div class="col">
                            <div class="row">
                                Cena:
                            </div>
                            <div class="row">
                                <span class="price"><?=$item['price']?><b> &euro;</b></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                Skupna cena:
                            </div>
                            <span class="price"><?=$item['price']*$item['quantity']?><b> &euro;</b></span>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <div class="row align-items-center border-bottom mx-2 my-2">
                        <div class="col text-right ">
                            <h4>Skupna cena: <span class="total"><?=$sum?></span><b> &euro;</b></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>
<?php else: ?>
<div class="container h-100">
    <div class="row justify-content-center align-items-center h-100">
        <div class="alert w-100 my-3 text-center alert-info rounded">
            <h2>Trenutno ni nobenega naročila!</h2>
        </div>
    </div>
</div>
<?php endif; ?>