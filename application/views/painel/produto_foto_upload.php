<legend>
Upload de Fotos de Produtos: {NOMEPRODUTO} - ({CODPRODUTO})
	<div class="pull-right">
 		<a href="{URLLISTAR}" title="Listar produtos" class="btn">Listar</a>
	</div>
</legend>
<form id="frm-upload-foto" action="{URLUPLOAD}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="codproduto" value="{CODPRODUTO}">
	<div class="row-fluid fileupload-buttonbar">
		<div class="span7">
			<span class="btn btn-success fileinput-button">
				<em class="icon-plus-sign icon-white"></em>
				<span>Adicionar Imagens...</span>
				<input type="file" name="fotos[]" multiple="multiple">
			</span>
			<button type="submit" class="btn btn-primary start">
				<em class="icon-upload icon-white"></em>
				<span>Iniciar Upload</span>
			</button>
			<button type="submit" class="btn btn-warning cancel">
				<em class="icon-ban-circle icon-white"></em>
				<span>Cancelar Upload</span>
			</button>
			<button type="submit" class="btn btn-danger delete">
				<em class="icon-ban-circle icon-white"></em>
				<span>Excluir</span>
			</button>
			<input type="checkbox" class="toggle">
			
			<span class="fileupload-loading"></span>
		</div>
		<div class="span5">
			<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
				<div class="progress-bar progress-bar-success" style="width:0%"></div>
			</div>
			<div class="progress-extended">&nbsp;</div>
		</div>
	</div>
	<table role="presentation" class="table table-table-striped">
		<tbody class="files">
		</tbody>
	</table>
</form>
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <p class="size">{%=o.formatFileSize(file.size)%}</p>
            {% if (!o.files.error) { %}
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
            {% } %}
        </td>
        <td>
            {% if (!o.files.error && !i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Enviar</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
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
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Excluir</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancelar</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>