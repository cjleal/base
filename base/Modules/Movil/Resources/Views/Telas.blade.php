@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
    @include('admin::partials.botonera')
    
    @include('admin::partials.ubicacion', ['ubicacion' => ['Telas']])
    
    @include('admin::partials.modal-busqueda', [
        'titulo' => 'Buscar Telas.',
        'columnas' => [
            'Descripcion' => '33.333333333333',
		'Estacion' => '33.333333333333'
        ]
    ])
@endsection

@section('content')
    <div class="row">
        <form method="POST" action="http://localhost/cristian/Telas" accept-charset="UTF-8" id="formulario" name="formulario"><input name="_token" type="hidden" value="JPIJlHOVt363KHzmtL5Pn4V5rEwQTTmIIV0UqxTC">
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label for="descripcion">Descripcion:</label>
                <input placeholder="Descripcion del Telas" id="descripcion" class="form-control" name="descripcion" type="text">
            </div>
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label for="estacion_id">Estacion:</label>
                <select id="estacion_id" class="form-control" name="estacion_id">
                    <option selected="selected" value="">Seleccione</option>
                     @foreach($estaciones as $estacion)
                         <option value="{{ $estacion->id}}">{{ $estacion->descripcion}}</option>
                     @endforeach
                </select>
                 <input id="archivos" name="archivos" type="hidden" />
            </div>
        </form>
        <form id="fileupload" action="" method="POST" enctype="multipart/form-data" style="display: none;">
            <div class="fileupload-buttonbar">
                <div class="col-xs-12 ">
                    <span class="btn btn-success fileinput-button">
                        <i class="fa fa-plus"></i>
                        <span>Agregar Archivos...</span>
                        <input type="file" name="files[]" multiple>
                        <input type="hidden" name="rutas" value="">
                        <input type="hidden" class="estacion_id" name="estacion_id[]" value="">
                        <input type="hidden" class="tela_id" name="tela_id[]" value="">
                    </span>
                    <button type="submit" class="btn btn-primary start">
                        <i class="fa fa-upload"></i>
                        <span>Iniciar Carga</span>
                    </button>
                    <button type="reset" class="btn btn-warning cancel">
                        <i class="fa fa-times-circle"></i>
                        <span>Cancelar Carga</span>
                    </button>
                    <button type="button" class="btn btn-danger delete">
                        <i class="fa fa-trash"></i>
                        <span>Eliminar</span>
                    </button>
                    <input type="checkbox" class="toggle">
                    <span class="fileupload-process"></span>
                </div>
                <div class="col-lg-5 fileupload-progress fade">
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                    <div class="progress-extended">&nbsp;</div>
                </div>
            </div>
            <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
        </form>
    </div>
@endsection
@push('js')
    <!-- The template to display files available for upload -->
    <script id="template-upload" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr data-id="{%=file.id%}" class="template-upload fade">
                <td style="width: 120px;">
                    <span class="preview"></span>
                </td>
                <td style="width: 300px;">
                    <p class="name">{%=file.name%}</p>
                    <strong class="error text-danger"></strong>
                </td>
                <td>
                    <p class="size">Procesando...</p>
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                </td>
                <td style="width: 240px;">
                    {% if (!i && !o.options.autoUpload) { %}
                        <button class="btn btn-primary start" disabled>
                            <i class="fa fa-upload"></i>
                            <span>Iniciar</span>
                        </button>
                    {% } %}
                    {% if (!i) { %}
                        <button class="btn btn-warning cancel">
                            <i class="fa fa-times-circle"></i>
                            <span>Cancelar</span>
                        </button>
                    {% } %}
                </td>
            </tr>
        {% } %}
    </script>
    <!-- The template to display files available for download -->
    <script id="template-download" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr data-id="{%=file.id%}" class="template-download fade">
                <td style="width: 120px;">
                    <span class="preview">
                        {% if (file.thumbnailUrl) { %}
                            <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}">
                            <img width="80px" height="80px" src="{%=file.thumbnailUrl%}"></a>
                        {% } %}
                    </span>
                </td>
                <td colspan="2">
                    <p>
                        <span class="leyenda">{%=file.data.leyenda%}</span>
                    </p>
                    {% if (file.error) { %}
                        <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                    {% } %}
                </td>
                <td style="width: 240px;">
                    {% if (file.deleteUrl) { %}
                        <button class="btn btn-info" data-url="{%=file.url%}">
                            <i class="fa fa-pencil"></i>
                            <span>Editar</span>
                        </button>
                        <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                            <i class="fa fa-trash"></i>
                            <span>Eliminar</span>
                        </button>
                        <input type="checkbox" name="delete" value="1" class="toggle">
                    {% } else { %}
                        <button class="btn btn-warning cancel">
                            <i class="fa fa-times-circle"></i>
                            <span>Cancelar</span>
                        </button>
                    {% } %}
                </td>
            </tr>
        {% } %}
    </script>
@endpush
    </div>
