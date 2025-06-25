@extends('layouts.app')

@section('content')
<style>
    body {
        background: #fff;
        color: #111;
        font-family: 'Inter', sans-serif;
    }
    .account-container {
        padding-top: 32px;
        padding-bottom: 40px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }
    .account-flex {
        display: flex;
        flex-direction: row;
        align-items: stretch;
        width: 100%;
        gap: 32px;
    }
    .sidebar {
        min-width: 200px;
        max-width: 260px;
        width: 100%;
        background: #fff;
        border: 1px solid #111;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        border-radius: 0 !important;
        display: flex;
        flex-direction: column;
        position: relative;
        z-index: 2;
        height: 100%;
    }
    .main-content {
        flex: 1 1 0%;
        min-width: 0;
        background: none;
        padding: 0;
        margin: 0;
    }
    @media (max-width: 1100px) {
        .account-container {
            max-width: 98vw;
            padding-left: 8px;
            padding-right: 8px;
        }
        .account-flex {
            gap: 18px;
        }
        .sidebar {
            min-width: 160px;
            max-width: 200px;
            border: 1px solid #111;
        }
    }
    @media (max-width: 991px) {
        .account-container {
            max-width: 100vw;
            padding-left: 0;
            padding-right: 0;
        }
        .account-flex {
            gap: 8px;
        }
        .sidebar {
            min-width: 120px;
            max-width: 140px;
            border: 1px solid #111;
        }
        .main-content {
            padding: 0;
        }
    }
    @media (max-width: 700px) {
        .account-flex {
            flex-direction: column;
            gap: 0;
        }
        .sidebar {
            min-width: 100%;
            max-width: 100%;
            margin-bottom: 18px;
        }
    }
</style>
<div class="account-container">
    <div class="account-flex">
        <div class="sidebar">
            @include('layouts.account.nav')
        </div>
        <div class="main-content">
            @yield('account_content')
        </div>
    </div>
</div>
@endsection
