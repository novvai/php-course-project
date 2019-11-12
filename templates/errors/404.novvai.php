<?php
include_once base_path() . 'templates/layout/guest-header.novvai.php';
?>

<section class="content">
  <div class="content-fluid">
    <div class="row">
      <div class="col-md-6 offset-md-3 mt-5 ">
        <div class="error-page">
          <h2 class="headline text-warning"> 404</h2>

          <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>

            <p>
              We could not find the page you were looking for.
              Meanwhile, you may <a href="/dashboard">return to dashboard</a> or try using the search form.
            </p>
          </div>
          <!-- /.error-content -->
        </div>
      </div>
    </div>
  </div>
</section>



<?php
include_once base_path() . 'templates/layout/guest-footer.novvai.php';
?>