<nav class="main-header navbar navbar-expand navbar-{{setting('app_theme')}}">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link desktop-toggle" id="toggleClose" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        <a class="nav-link mobile-toggle" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
    </ul>

    @role('admin')
    <!-- SEARCH FORM -->
    <div class="d-none d-md-block d-lg-block d-xl-block">
    <form method="GET" action="/user" class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" name="search" placeholder="Search users" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </form>
    </div>
    @endrole

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      @impersonating
      <li class="nav-item">
        <a class="nav-link text-danger btn btn-none btn-outline-primary" href="{{ route('impersonate.leave') }}">
          <p><i class="fa fa-ban mr-2" aria-hidden="true"></i>{{'Exit Impersonation'}}</p>
        </a>
      </li>
      @endImpersonating
      <!-- User Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <img src="{{Auth::user()->avatar?Auth::user()->avatar:asset('uploads/avatar/avatar.png')}}" width="28px" class="img img-circle  img-responsive" alt="User Image">
          {{auth()->user()->fullname}}
          <i class="fa fa-angle-down right"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>

          @unlessrole('admin')
          <a href="/subscription" class="dropdown-item">
            <i class="fa fa-credit-card mr-2"></i>
            @if(auth()->user()->stripe_id)
                Subscription
            @else
            Not Subscribed
                @endif
          </a>
          @endunlessrole

          <div class="dropdown-divider"></div>
          <a href="/profile" class="dropdown-item">
            <i class="fa fa-user mr-2"></i> {{ __('app.profile') }}
          </a>

          <a href="/activity-log" class="dropdown-item">
            <i class="fa fa-list mr-2"></i> {{ __('app.activity_log') }}
          </a>

          @role('admin')
          <a href="/settings" class="dropdown-item">
            <i class="fa fa-gear mr-2"></i> {{ __('app.application_settings') }}
          </a>
          @endrole

          <div class="dropdown-divider"></div>
          <a href="/logout" class="dropdown-item dropdown-footer bg-gray"><i class="fa fa-sign-out mr-2"></i> {{ __('app.logout') }}</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-{{setting('app_sidebar')}}-light elevation-4">
    <!-- Brand Logo -->
    <div class="navbar-brand d-flex justify-content-center">
      <a class="nav-link toggleopen display-none"  data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      <a href="/" class="app-logo brand-link">
        @if(setting('app_dark_logo')||setting('app_light_logo'))
          <img src="{{(setting('app_sidebar')=='light')? asset('uploads/appLogo/app-logo-dark.png'):asset('uploads/appLogo/app-logo-light.png')}}" alt="App Logo" class=" img brand-image img-responsive opacity-8">

        @else
          <img src="{{(setting('app_sidebar')=='light')? asset('uploads/appLogo/logo-dark.png'):asset('uploads/appLogo/logo-light.png')}}" alt="App Logo" class="img brand-image img-responsive opacity-8">

        @endif
      </a>

    </div>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <a href="/profile"><img src="{{Auth::user()->avatar?Auth::user()->avatar:asset('uploads/avatar/avatar.png')}}" width="40px" class="img img-circle  img-responsive" alt="User Image"></a>
        </div>
        <div class="info">
          <a href="/profile" class="d-block">{{Auth::user()->firstname}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="/" class="nav-link {{request()->is('/')? 'sidebar-active':''}}">
              <i class="nav-icon fa fa-home"></i>
              <p>
                {{ __('app.dashboard') }}
              </p>
            </a>
          </li>

          @can('manage-user')
          <li class="nav-item has-treeview">
            <a href="{{route('user.index')}}" class="nav-link {{request()->is('user*')? 'sidebar-active':''}}">
              <i class="nav-icon fa fa-users"></i>
              <p>
              {{ __('app.users') }}
              </p>
            </a>
          </li>
          @endcan

          @role('admin')
          <li class="nav-item">
            <a href="{{route('activity-log.index')}}" class="nav-link {{request()->is('activity-log*')? 'sidebar-active':''}}">
              <i class="nav-icon fa fa-tasks"></i>
              <p>
                {{ __('app.activity_log') }}
              </p>
            </a>
          </li>
          @endrole

          @can('manage-role')
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{(request()->is('role*')||request()->is('permission*'))? 'sidebar-active':''}}">
              <i class="nav-icon fa fa-key"></i>
              <p>
                {{ __('app.roles_and_permissions') }}
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('manage-role')
              <li class="nav-item">
                <a href="{{route('role.index')}}" class="nav-link {{request()->is('role*')? 'sidebar-active':''}}">
                  <i class="fa fa-user-secret nav-icon"></i>
                  <p>{{ __('app.roles') }}</p>
                </a>
              </li>
              @endcan

               @can('manage-permission')
                <li class="nav-item has-treeview">
                  <a href="{{route('permission.index')}}" class="nav-link {{request()->is('permission*')? 'sidebar-active':''}}">
                    <i class="fa fa-user-secret nav-icon"></i>
                    <p>{{ __('app.permissions') }}</p>
                  </a>
                </li>
              @endcan
            </ul>
          </li>
          @endcan

          @role('admin')
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{(request()->is('subscription/plan')||request()->is('/subscription-income')||request()->is('/subscribed-users'))? 'sidebar-active':''}}">
              <i class="nav-icon fa fa-cart-plus"></i>
              <p>
                {{ __('app.payment') }}
                <i class="fa fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/subscription/plan" class="nav-link {{request()->is('subscription/plan')? 'sidebar-active':''}}">
                  <i class="fa fa-circle ml-3 mr-1"></i>
                  <p>{{ __('app.subscription_plan') }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/subscription-income" class="nav-link {{request()->is('subscription-income')? 'sidebar-active':''}}">
                  <i class="fa fa-circle ml-3 mr-1"></i>
                  <p>{{ __('app.manage_income') }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/subscribed-users" class="nav-link {{request()->is('subscribed-users')? 'sidebar-active':''}}">
                  <i class="fa fa-circle ml-3 mr-1"></i>
                  <p>{{ __('app.subscribed_users') }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/checkout-sample" class="nav-link {{request()->is('checkout-sample*')? 'sidebar-active':''}}">
                  <i class="fa fa-circle ml-3 mr-1"></i>
                  <p>{{ __('app.checkout_sample') }}</p>
                </a>
              </li>
            </ul>
          </li>
          @endrole

          @unlessrole('admin')
          @if(setting('stripe_status'))
          <li class="nav-item">
            <a href="{{route('/subscription')}}" class="nav-link {{request()->is('subscription*')? 'sidebar-active':''}}">
              <i class="nav-icon fa fa-credit-card"></i>
              <p>
                {{ __('app.subscription') }}
              </p>
            </a>
          </li>
          @endif
          @endunlessrole

          @role('admin')
          <li class="nav-item">
            <a href="{{route('settings.index')}}" class="nav-link {{request()->is('settings')? 'sidebar-active':''}}">
              <i class="nav-icon fa fa-gear"></i>
              <p>
                {{ __('app.application_settings') }}
              </p>
            </a>
          </li>
          @endrole

          @role('admin')
          <li class="nav-item">
            <a href="/crud-builder" class="nav-link {{request()->is('crud-builder')? 'sidebar-active':''}}">
              <i class="nav-icon fa fa-gear"></i>
              <p>
                {{ __('app.crud_builder') }}
              </p>
            </a>
          </li>
          @endrole

          <li class="nav-header">{{ __('app.premium_content_big') }}</li>

          @role('admin')
          <li class="nav-item has-treeview">
            <a href="/article" class="nav-link {{request()->is('article*')? 'sidebar-active':''}}">
              <i class="nav-icon fa fa-newspaper-o"></i>
              <p>
                {{ __('app.article') }}
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="/category-article" class="nav-link {{request()->is('category-article*')? 'sidebar-active':''}}">
              <i class="nav-icon fa fa-newspaper-o"></i>
              <p>
                {{ __('app.article_category') }}
              </p>
            </a>
          </li>
          @endrole
          @unlessrole('admin')
          <li class="nav-item">
            <a href="/premium-content" class="nav-link {{request()->is('premium-content*')? 'sidebar-active':''}}">
              <i class="nav-icon fa fa-file-o"></i>
              <p>
                    {{ __('app.content') }}
              </p>
            </a>
          </li>
          @endunlessrole
          <li class="nav-header">{{ __('app.crud_menu') }}</li>
            @include('layouts.menu')
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
