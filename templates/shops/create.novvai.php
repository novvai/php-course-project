<?php
include_once base_path().'templates/layout/header.novvai.php';
?>
<section class="content">
    <div class="content-fluid">
        <?php
        if(session()->has('errors')){
            include_once load_template('common/error_modal');
        }
        ?>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Магазини</h1>
            </div>
        </div>
        <div class="col-md-6">

            <!-- general form elements -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Създаване</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" method="POST" action="/shops/create" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Адрес</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?=session()->get('inputs.title')?>">
                            <?php renderErr(session()->get('errors.title'))?>
                        </div>
                        <div class="form-group">
                            <label for="phone">Телефон</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?=session()->get('inputs.phone')?>">
                            <?php renderErr(session()->get('errors.phone'))?>
                        </div>
                        <div class="form-group">
                            <label for="work_time">Работно Време</label>
                            <input type="text" class="form-control" id="work_time" name="work_time" value="<?=session()->get('inputs.work_time')?>" >
                            <?php renderErr(session()->get('errors.work_time'))?>
                        </div>
                        <div class="form-group">
                            <label for="thumbnail">Снимка</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="thumnail" id="thumbnail">
                                    <label class="custom-file-label" for="thumbnail">Избери Файл</label>
                                </div>
                            </div>
                            <?php renderErr(session()->get('errors.files'))?>
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

<?php
include_once base_path().'templates/layout/footer.novvai.php';
?>