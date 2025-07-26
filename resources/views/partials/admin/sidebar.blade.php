<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link">
    <img src="{{asset($setting->logo)}}" alt="THT Media" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{$setting->name}}</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <!--begin::Sidebar Menu-->
      <x-sidebar-menu />
      <!--end::Sidebar Menu-->
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
