
{{ Form::open(array('url' => 'pericias/save',"class" => "pericias")) }}

{{ Form::label('type', 'Tipo') }}
{{ Form::Text('type', '',["class" => "form-control"]) }}

{{ Form::submit('Enviar',["class"=>'btn btn-default']) }}
{{ Form::close() }}