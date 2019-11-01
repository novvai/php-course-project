<?php

use Novvai\Request\Request;

include_once base_path() . 'templates/layout/header.novvai.php';
?>
<section class="content">
    <div class="content-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1>Артикули</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class=" card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><?= $product->name ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"><img src="<?= $product->thumbnail ?>" width="" alt="" class="img-thumbnail"></div>
                            <div class="col-md-3">
                                <dl>
                                    <dt>Име</dt>
                                    <dd><?= $product->name ?></dd>
                                    <dt>Категория</dt>
                                    <?php $cat = $product->category();?>
                                    <dd><?= "{$cat->parent()->name} - {$cat->name}" ?></dd>
                                    <dt>Цена</dt>
                                    <dd><?= $product->price ?></dd>
                                    <dt>Наличност</dt>
                                    <dd><?= $product->quantity ?></dd>
                                    <dt>Промоционална</dt>
                                    <dd>
                                        <?= $product->is_featured ? '<span class="fas fa-check-circle text-success"></span>' : '<span class="fas fa-times-circle text-danger"></span>'; ?>
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h4>Продуктови детайли: </h4>
                                <dl>
                                    <?php foreach($product->details() as $detail) {?>
                                        <dt><?= $detail->name ?></dt>
                                    <dd><?= $detail->value ?></dd>
                                    <?php } ?>
                                </dl>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Кратко описание:</h3>
                                <?=$product->short_desc?>
                                <h3>Пълно описание:</h3>
                                <?=$product->description?>
                            </div>
                           
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include_once base_path() . 'templates/layout/footer.novvai.php';
?>