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
            <div class="col-sm-6">
                <a href="/products/create" class="btn btn-success float-sm-right">Създай</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php if(session()->has('success_msg')){ include_once load_template('common/success_modal'); }?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Филтри:</h3>
                    </div>
                    <?php $activeFilters = Request::getInstance()->get('filters', []); ?>
                    <div class="card-body">
                        <form action="/products" method="GET">
                            <div class="form-row">
                                <div class="form-group clearfix mb-3">
                                    <div class="icheck-primary ml-2 d-inline">
                                        <input type="radio" id="filter_1" name="filters[featured]" value="1" <?= (($activeFilters["featured"] ?? null) === '1') ? 'checked="checked"' : ''; ?>>
                                        <label for="filter_1">
                                            Промоционални
                                        </label>
                                    </div>
                                    <div class="icheck-primary ml-2 d-inline">
                                        <input type="radio" id="filter_2" name="filters[featured]" value="0" <?= (($activeFilters["featured"] ?? null) === '0') ? 'checked="checked"' : ''; ?>>
                                        <label for="filter_2">
                                            Обикновенни
                                        </label>
                                    </div>
                                    <div class="icheck-primary ml-2 d-inline">
                                        <input type="checkbox" id="is_featured_false" name="filters[sortBy]" value="desc" <?= (($activeFilters["sortBy"] ?? null) === 'desc') ? 'checked="checked"' : '';  ?>>
                                        <label for="is_featured_false">
                                            Последно Редактирани
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="name">Име</label>
                                    <input type="text" class="form-control" id="name" name="filters[name]" value="<?= $activeFilters["name"] ?? '' ?>">
                                </div>
                                <div class="form-group  col-md-4">
                                    <label for="category">Категория:</label>
                                    <select name="filters[category]" id="category" class="form-control">
                                        <option value=""></option>
                                        <?php foreach ($categories as $category) { ?>
                                            <optgroup label="<?= $category->name ?>">
                                                <?php foreach ($category->subCategories ?? [] as $sub_cat) { ?>
                                                    <option value="<?= $sub_cat->id ?>" <?= (($activeFilters["category"] ?? null) == $sub_cat->id) ? 'selected="selected"' : '';  ?>><?= $sub_cat->name ?></option>
                                                <?php } ?>
                                            </optgroup>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <button class="btn btn-warning">Филтрирай</button>
                                <a href="/products" class="btn btn-primary">Изчисти</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Снимка</th>
                                    <th>Име</th>
                                    <th>Промоционален</th>
                                    <th>Цена</th>
                                    <th>Наличност</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($products ?? [] as $product) {
                                    ?>
                                    <tr>
                                        <td><?= $product->id ?></td>
                                        <td><img src="<?= $product->thumbnail ?>" width="50" height="50" alt=""></td>
                                        <td><?= $product->name ?></td>
                                        <td><?= $product->is_featured ? '<span class="fas fa-check-circle"></span>' : '<span class="fas fa-times-circle"></span>'; ?></td>
                                        <td><?= $product->price ?></td>
                                        <td><?= $product->quantity ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                                        <a class="dropdown-item" href="/products/<?= $product->id ?>/edit">Редакция</a>
                                                        <a class="dropdown-item" href="/products/<?= $product->id ?>">Детайли</a>
                                                        <div class="dropdown-divider"></div>
                                                        <form method="POST" action="/products/<?= $product->id ?>/delete">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button class="dropdown-item" type="submit">Изтриване</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <?php $productsPagination->render();?>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>

<?php
include_once base_path() . 'templates/layout/footer.novvai.php';
?>