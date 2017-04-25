
@extends('varas.layout')

@section('title', 'Criar Vara')

@section('content')
    {{ Form::open(array('url' => 'varas/save')) }}

       {{ Form::label('id', 'Numero') }}
       {{ Form::Text('id', '',["class" => "form-control"]) }}

       {{ Form::label('nome', 'Vara') }}
       {{ Form::Text('nome', '',["class" => "form-control"]) }}

       {{ Form::label('cidade', 'Cidade') }}
       {{ Form::Text('cidade', '',["class" => "form-control"]) }}

       {{ Form::submit('Enviar',["class"=>'btn btn-default']) }}
    {{ Form::close() }}
@endsection