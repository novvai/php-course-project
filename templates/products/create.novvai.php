<?php
include_once base_path() . 'templates/layout/header.novvai.php';
?>
<section class="content">
    <div class="content-fluid">
        <div class="row mb-2 pl-2">
            <div class="col-sm-6">
                <h1>Артикули</h1>
            </div>
        </div>
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Създаване</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" method="POST" action="/products/create" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="picture">Снимка</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="picture" id="picture">
                                    <label class="custom-file-label" for="picture">Избери Файл</label>
                                </div>
                            </div>
                            <?php renderErr(session()->get('errors.picture')) ?>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="name">Име</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= session()->get('inputs.name') ?>">
                                <?php renderErr(session()->get('errors.name')) ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="price">Цена</label>
                                <input type="number" class="form-control" min="0" step=".01" id="price" name="price" value="<?= session()->get('inputs.price', 0) ?>">
                                <?php renderErr(session()->get('errors.price')) ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="quantity">Наличност</label>
                                <input type="number" step="1" min='0' class="form-control" id="quantity" name="quantity" value="<?= session()->get('inputs.quantity', 0) ?>">
                                <?php renderErr(session()->get('errors.quantity')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Категория:</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <?php foreach ($categories as $category) { ?>
                                    <optgroup label="<?= $category->name ?>">
                                        <?php foreach ($category->subCategories() ?? [] as $sub_cat) { ?>
                                            <option value="<?= $sub_cat->id ?>" <?= (session()->get('inputs.category_id') == $sub_cat->id) ? "selected='selected'" : '' ?>><?= $sub_cat->name ?></option>
                                        <?php } ?>
                                    </optgroup>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="short_desc">Кратко описание:</label>
                            <textarea class="form-control" name="short_desc" width="100%" id="short_desc" rows="3" placeholder="Кратко описание тук...">
                            <?= session()->get('inputs.short_desc') ?>
                            </textarea>
                            <?php renderErr(session()->get('errors.short_desc')) ?>
                        </div>
                        <div class="form-group">
                            <label for="description">Пълно описание:</label>
                            <textarea class="form-control" id="description" name="description" rows="3" width="100%" placeholder="Пълно описание тук...">
                            <?= session()->get('inputs.description') ?>
                            </textarea>
                            <?php renderErr(session()->get('errors.description')) ?>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured">
                                <label class="custom-control-label" for="is_featured">Промоционален</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group  d-flex flex-row">
                                <h3 class="align-self-center">Допълнителни детайли</h3>
                                <a href="#" data-nv-click="add_detail_input" class="btn-success btn-sm ml-3 btn align-self-center">
                                    <span class="fas fa-plus"></span>
                                </a>
                            </div>
                        </div>
                        <div data-nv-container="additional_details">
                            <?php foreach (session()->get('inputs.additional', []) as $index => $additonal) { ?>
                                <div class="form-row additiona_info">
                                    <div class="form-group  col-sm-3">
                                        <input type="text" class="form-control " placeholder='Обем' name="additional[<?=$index?>][name]" id="" value="<?=$additonal['name']?>">
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <input type="text" class="form-control " placeholder='200 мл.' name="additional[<?=$index?>][value]" id=""value="<?=$additonal['value']?>">
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <a href="#" data-nv-action="remove" class="btn btn-danger"><span class="fas fa-times"></span></a>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Запази</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        $('#short_desc').summernote();
        $('#description').summernote();
        const additional_info_container = $('[data-nv-container="additional_details"]');
        $('[data-nv-click="add_detail_input"]').on('click', (e) => {
            e.preventDefault();
            let hashedName = randHash(6);
            const container = document.createElement('span');
            container.innerHTML = `<div class="form-row additiona_info">
            <div class="form-group  col-sm-3">
                <input type="text" class="form-control " placeholder='Обем' name="additional[${hashedName}][name]" id="">
            </div>
            <div class="form-group col-sm-3">
                <input type="text" class="form-control " placeholder='200 мл.' name="additional[${hashedName}][value]" id="">
            </div>
            <div class="form-group col-sm-3">
                <a href="#" data-nv-action="remove" class="btn btn-danger"><span class="fas fa-times"></span></a>
            </div>
        </div>`;
            additional_info_container.append(container.childNodes[0]);
        })
        additional_info_container.on("click", '[data-nv-action="remove"]', (e) => {
            e.preventDefault();
            $(e.currentTarget).parents('.additiona_info').remove();
        })

    });
</script>

<?php
include_once base_path() . 'templates/layout/footer.novvai.php'; 
?>