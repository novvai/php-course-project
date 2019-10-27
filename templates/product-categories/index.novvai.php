<?php
include_once base_path() . 'templates/layout/header.novvai.php';
?>

<section class="content">
    <div class="content-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1>Категории</h1>
            </div>
        </div>
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Добавяне</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="/product-categories/create">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Наименование</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <label for="parent_id" class="col-sm-2 col-form-label">Главна категория</label>
                        <div class="col-sm-4">
                            <select class="custom-select" name="parent_id" id="parent_id">
                                <option value="none">Нова главна категория</option>
                                <?php foreach ($productCategories ?? [] as $category) { ?>
                                    <option value="<?= $category->id ?>"><?= $category->name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Добави</button>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
        <div class="row">
            <div class="col-md-6" id="static_data">

                <div class="card">
                    <div class="card-body table p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($productCategories ?? [] as $category) {
                                    ?>
                                    <tr>
                                        <td><?= $category->id ?></td>
                                        <td>
                                            <span data-nv-el="category-<?= $category->id ?>"><?= $category->name ?></span>
                                            <form action="/product-categories/<?= $category->id ?>/edit" method="POST" class="d-none" data-nv-el="category-<?= $category->id ?>">
                                                <div class="input-group input-group-sm mb-3">
                                                    <input type="text" class="form-control" id="name" name="name" value="<?= $category->name ?>">

                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li class="dropdown-item"><button type="submit" class="btn">Запази</button></li>
                                                            <li class="dropdown-divider"></li>
                                                            <li class="dropdown-item"><a href="#" class="btn" data-nv-toggle="category-<?= $category->id ?>">Откажи</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                                        <a class="dropdown-item" style="cursor:pointer;" data-nv-toggle="category-<?= $category->id ?>">Редакция</a>
                                                        <a class="dropdown-item" style="cursor:pointer;" data-nv-fetch="sub_categories" data-nv-id="<?= $category->id ?>">Под категории</a>
                                                        <div class="dropdown-divider"></div>
                                                        <form method="POST" action="/product-categories/<?= $category->id ?>/delete">
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
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-6" id="dynamic_data">

                <div class="card">
                    <div class="card-body table p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody data-nv-container="sub_categories">

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        let add_listeners = function(containerId) {
            $(`#${containerId} [data-nv-toggle]`).each((index, el) => {
                $(el).on("click", (e) => {
                    e.preventDefault();
                    let target = $(e.currentTarget).attr("data-nv-toggle");
                    let elTargets = $("[data-nv-el='" + target + "']");
                    elTargets.each((_, el) => {
                        if ($(el).hasClass('d-none')) {
                            $(el).removeClass('d-none')
                        } else {
                            $(el).addClass('d-none')
                        }

                    })
                });
            });
        }
        add_listeners("static_data");
        $("[data-nv-fetch=\"sub_categories\"]").each((_, el) => {
            $(el).on("click", (e) => {
                e.preventDefault();
                let container = $(`[data-nv-container="sub_categories"]`);
                container.empty();
                let id = $(e.currentTarget).attr('data-nv-id');
                $.ajax(`/api/product-categories/${id}/sub-categories`, {
                    method: "GET"
                }).done((response) => {
                    if (response.success.code == 2000) {
                        response.payload.sub_categories.forEach(category => {
                            let row = document.createElement('tr');
                            row.innerHTML = `
                            <td>${category.id}</td>
                                        <td>
                                            <span data-nv-el="category-${category.id}">${category.name}</span>
                                            <form action="/product-categories/${category.id}/edit" method="POST" class="d-none" data-nv-el="category-${category.id}">
                                                <div class="input-group input-group-sm mb-3">
                                                    <input type="text" class="form-control" id="name" name="name" value="${category.name}">

                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li class="dropdown-item"><button type="submit" class="btn">Запази</button></li>
                                                            <li class="dropdown-divider"></li>
                                                            <li class="dropdown-item"><a href="#" class="btn" data-nv-toggle="category-${category.id}">Откажи</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                                        <a class="dropdown-item" style="cursor:pointer;" data-nv-toggle="category-${category.id}">Редакция</a>
                                                        <div class="dropdown-divider"></div>
                                                        <form method="POST" action="/product-categories/${category.id}/delete">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button class="dropdown-item" type="submit">Изтриване</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                            `
                            container.append(row);
                        })
                        add_listeners("dynamic_data");

                    }
                })
            })
        })
    })
</script>
<?php
include_once base_path() . 'templates/layout/footer.novvai.php';
?>