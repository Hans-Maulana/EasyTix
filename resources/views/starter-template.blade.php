 @extends('layouts.master')
 
 @section('content')
 <div class="container">
          <div class="page-inner">
            <div class="page-header">
              <h4 class="page-title">Dashboard</h4>
              <ul class="breadcrumbs">
                <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Pages</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Starter Page</a>
                </li>
              </ul>
            </div>
            <div class="page-category">Inner page content goes here</div>
          </div>
        </div>
 @endsection

 @section('ExtraCSS')
.logo-header .logo .navbar-brand {
    padding-top: 0px;
    padding-bottom: 0px;
    margin-right: 0px;
}
 @endsection

 @section('ExtraJS')
 @endsection
