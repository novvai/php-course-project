<?php
      use App\Services\Auth;

      $auth = Auth::getInstance();
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <span class="brand-text font-weight-light">Lilly CMS</span>
    </a>
    
    <!-- Sidebar -->
    <div class="sidebar">
     
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?=$auth->user()->username?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
              <a href="/dashboard" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Табло</p>
              </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-dolly"></i>
              <p>
                Продукти
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
              <li class="nav-item">
                <a href="/product-categories" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Категории</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/products" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Артикули</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
              <a href="/posts" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>Блог</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="/shops" class="nav-link">
                <i class="nav-icon fas fa-store-alt"></i>
                <p>Магазини</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="/logout" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Изход</p>
              </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>