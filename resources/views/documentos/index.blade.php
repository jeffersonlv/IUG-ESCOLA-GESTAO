@extends('app')

@section('content')
<div class="container">
    <h1>Documentos</h1>
    <ul>
    @forelse($documentos as $doc)
        <li>{{ $doc->nome }}</li>
    @empty
        <p>Nenhum documento disponível.</p>
    @endforelse
    </ul>
</div>
@endsection
