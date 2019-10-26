<?php
include_once base_path() . 'templates/layout/header.novvai.php';
?>
<section class="content">
    <div class="content-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1>Магазини</h1>
            </div>
            <div class="col-sm-6">
                <a href="/shops/create" class="btn btn-success float-sm-right">Създай</a>
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
                                    <th>Адрес</th>
                                    <th>Телефон</th>
                                    <th>Работно време</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($shops ?? [] as $shop) {
                                    ?>
                                    <tr>
                                        <td><?= $shop->id ?></td>
                                        <td><img src="<?= $shop->thumbnail ?>" width="50" height="50" alt=""></td>
                                        <td><?= $shop->title ?></td>
                                        <td><?= $shop->phone ?></td>
                                        <td><?= $shop->work_time ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                                        <a class="dropdown-item" href="/shops/<?=$shop->id?>/edit">Редакция</a>
                                                        <div class="dropdown-divider"></div>
                                                        <form method="POST" action="/shops/<?=$shop->id?>/delete">
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
        </div>
    </div>
</section>

<?php
include_once base_path() . 'templates/layout/footer.novvai.php';
?>