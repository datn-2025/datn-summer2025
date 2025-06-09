@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Sidebar -->
    @include('layouts.partials.user-sidebar')
    
    <!-- Main Content -->
    <main class="flex-1 py-8 overflow-x-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('account_content')
        </div>
    </main>
</div>

@stack('scripts')
@endsection