<?php

use Novvai\Request\Request;

include_once base_path() . 'templates/layout/header.novvai.php';
?>
<section class="content">
    <div class="content-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1>Блог постове</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class=" card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><?= $post->title ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"><img src="<?= $post->thumbnail ?>" width="" alt="" class="img-thumbnail"></div>
                            <div class="col-md-3">
                                <dl>
                                    <dt>Заглавие</dt>
                                    <dd><?= $post->title ?></dd>
                                    <dt>Автор</dt>
                                    <dd><?= $post->author ?></dd>
                                    <dt>Създадена:</dt>
                                    <dd><?= $post->created_at ?></dd>
                                    <dt>Редактирана</dt>
                                    <dd><?= $post->updated_at ?></dd>
                                </dl>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Съдържание:</h3>
                                <?=$post->content?>
                            </div>
                            <div class="col-md-6">
                                <h3>Коментари:</h3>
                                <?php foreach ($post->comments() ?? [] as $comment) { ?>
                                <?php } ?>
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