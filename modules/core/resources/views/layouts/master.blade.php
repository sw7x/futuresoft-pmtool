@include('core-module::includes.header')
@include('core-module::includes.side-nav')

<div id="page-wrapper" class="gray-bg">
    @include('core-module::includes.top-nav')

    <!-- page heading -->
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ $title ?? 'Default Title' }}</h2>
            {!!(\App\Common\Utils\BreadcrumbUtil::createBreadcrumb())!!}

        </div>
    </div>

    <div class="wrapper wrapper-content  animated fadeInRight">
        @yield('content')
        <!-- content -->
    </div>

@include('core-module::includes.footer')



