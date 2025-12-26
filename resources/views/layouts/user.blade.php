<!-- {{-- resources/views/layouts/user.blade.php --}} -->
@extends('layouts.app')

@section('content')
<div class="container-fluid py-5 px-lg-5" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            @include('layouts.user-sidebar')
        </div>

        <!-- Main User Content -->
        <div class="col-lg-9">
            @yield('user_content')
        </div>
    </div>
</div>

@stack('user_scripts')
@endsection
