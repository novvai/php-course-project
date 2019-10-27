<?php
include_once base_path() . 'templates/layout/header.novvai.php';
?>
<section class="content">
    <div class="content-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Блог Пост</h1>
            </div>
        </div>
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Редакция</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" method="POST" action="/posts/<?=$post->id?>/edit" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="picture">Снимка</label>
                            <div class="input-group">
                                <img src="<?=$post->thumbnail?>" class="img-thumbnail" alt="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="picture">Снимка</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="picture" id="picture">
                                    <label class="custom-file-label" for="picture">Избери Файл</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title">Заглавие</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?=$post->title?>">
                        </div>
                        <div class="form-group">
                            <label for="author">Автор</label>
                            <input type="text" class="form-control" id="author" name="author" value="<?=$post->author?>">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" <?=($post->is_featured)?'checked="checked"':'';?>>
                            <label class="custom-control-label" for="is_featured">Актуална</label>
                            </div>
                        </div>
                        <textarea class="textarea" name="content" placeholder="Place some text here"><?=$post->content?></textarea>
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
    document.addEventListener("DOMContentLoaded", ()=>{
        $('.textarea').summernote();
    });
</script>

<?php
include_once base_path() . 'templates/layout/footer.novvai.php';
?>