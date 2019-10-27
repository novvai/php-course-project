<?php
include_once base_path() . 'templates/layout/guest-header.novvai.php';
?>

<section class="content">
  <div class="content-fluid">
    <div class="row">
      <div class="col-md-6 offset-md-3 mt-5 ">
        <div class="card card-primary ">
          <div class="card-header">
            <h3 class="card-title">Администраторски Вход</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" method="POST" action="/login">
            <div class="card-body">
              <div class="form-group">
                <label for="username">Е-поща</label>
                <input type="email" class="form-control" id="username" name="username" placeholder="test@test.com">
              </div>
              <div class="form-group">
                <label for="password">Парола</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="********">
              </div>

            </div>
            <!-- /.card-body -->

            <div class="card-footer text-center">
              <button type="submit" class="btn btn-primary">Влез</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>



<?php
include_once base_path() . 'templates/layout/guest-footer.novvai.php';
?>