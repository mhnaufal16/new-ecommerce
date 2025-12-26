{{-- resources/views/layouts/admin.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid py-5 px-lg-5">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            @include('layouts.admin-sidebar')
        </div>

        <!-- Main Admin Content -->
        <div class="col-lg-9">
            @yield('admin_content')
        </div>
    </div>
</div>

@stack('admin_scripts')
@endsection
