
@extends('contrarios.layout')

@section('title', 'Listar Contrarios')

@section('content')

    <a type="button" class="btn btn-primary btn-lg" href={{ url('/contrarios/criar') }} >Criar Contrarios</a>



    <table id="table" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Nome</th>
            <th>CPF/CNPF</th>
            <th>Telefone</th>
            <th>Email</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Nome</th>
            <th>CPF/CNPF</th>
            <th>Telefone</th>
            <th>Email</th>

        </tr>
        </tfoot>
        <tbody>

        @foreach ($contrarios as $contrario)
            <tr>
                <td>{{ $contrario->nome }}</td>
                <td>{{ $contrario->documents->number }}</td>
                <td>{{ $contrario->telefone }}</td>
                <td>{{ $contrario->email }}</td>
                <td></td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection