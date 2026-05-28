@extends('app')

@section('content')
<div class="admin-dashboard">
    <h1>Dashboard Admin</h1>
    <p>Bem-vindo, {{ auth()->user()->name }}!</p>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div>
@endsection
