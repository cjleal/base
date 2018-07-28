@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
    @include('admin::partials.botonera')
    
    @include('admin::partials.ubicacion', ['ubicacion' => ['Api Preguntas Estilos']])
    
    @include('admin::partials.modal-busqueda', [
        'titulo' => 'Buscar ApiPreguntasEstilos.',
        'columnas' => [
            'Descripcion' => '50',
    		'Estilo' => '50'
        ]
    ])
@endsection

@section('content')
    <div class="row">
        <form method="POST" action="http://localhost/cristian/ApiPreguntasEstilos" accept-charset="UTF-8" id="formulario" name="formulario"><input name="_token" type="hidden" value="b88YHt7VG0qGKX3D2Fet16mQ6UPUjOf8YFRR4nVF">
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label for="estilo_id">Estilo:</label>
                <select id="estilo_id" class="form-control" name="estilo_id">
                    <option selected="selected" value="">Seleccione un estilo</option>
                    @foreach($estilos as $estilo)
                        <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group col-xs-12">
                <label for="descripcion">Descripción:</label>
                <textarea placeholder="Pregunta" cont_class="col-sm-12" 
                        id="descripcion" class="form-control" rows="6" name="descripcion" 
                        cols="50">
                </textarea>
            </div>
        </form>
    </div>
@endsection