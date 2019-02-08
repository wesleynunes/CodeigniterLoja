<legend>
Comprador - {ACAO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar compradores" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<input type="hidden" name="codcomprador" id="codcomprador" value="{codcomprador}">
	<div class="control-group">
		<label class="control-label" for="nomecomprador">Nome <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="nomecomprador" name="nomecomprador" value="{nomecomprador}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="cpfcomprador">CPF <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="mask-cpf" id="cpfcomprador" name="cpfcomprador" value="{cpfcomprador}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="cepcomprador">CEP <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="mask-cep busca-cep" id="cepcomprador" name="cepcomprador" value="{cepcomprador}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="enderecocomprador">Endereço <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="enderecocomprador" name="enderecocomprador" value="{enderecocomprador}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="cidadecomprador">Cidade <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="cidadecomprador" name="cidadecomprador" value="{cidadecomprador}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="ufcomprador">UF <span class="required">*</span>:</label>
	    <div class="controls">
	    	<select name="ufcomprador" id="ufcomprador">
	    	  <option value="AC" {ufcomprador_AC}>AC</option>
	    	  <option value="AL" {ufcomprador_AL}>AL</option>
	    	  <option value="AP" {ufcomprador_AP}>AP</option>
	    	  <option value="AM" {ufcomprador_AM}>AM</option>
	    	  <option value="BA" {ufcomprador_BA}>BA</option>
	    	  <option value="CE" {ufcomprador_CE}>CE</option>
	    	  <option value="DF" {ufcomprador_DF}>DF</option>
	    	  <option value="ES" {ufcomprador_ES}>ES</option>
	    	  <option value="GO" {ufcomprador_GO}>GO</option>
	    	  <option value="MA" {ufcomprador_MA}>MA</option>
	    	  <option value="MT" {ufcomprador_MT}>MT</option>
	    	  <option value="MS" {ufcomprador_MS}>MS</option>
	    	  <option value="MG" {ufcomprador_MG}>MG</option>
	    	  <option value="PA" {ufcomprador_PA}>PA</option>
	    	  <option value="PB" {ufcomprador_PB}>PB</option>
	    	  <option value="PR" {ufcomprador_PR}>PR</option>
	    	  <option value="PE" {ufcomprador_PE}>PE</option>
	    	  <option value="PI" {ufcomprador_PI}>PI</option>
	    	  <option value="RJ" {ufcomprador_RJ}>RJ</option>
	    	  <option value="RN" {ufcomprador_RN}>RN</option>
	    	  <option value="RS" {ufcomprador_RS}>RS</option>
	    	  <option value="RO" {ufcomprador_RO}>RO</option>
	    	  <option value="RR" {ufcomprador_RR}>RR</option>
	    	  <option value="SC" {ufcomprador_SC}>SC</option>
	    	  <option value="SP" {ufcomprador_SP}>SP</option>
	    	  <option value="SE" {ufcomprador_SE}>SE</option>
	    	  <option value="TO" {ufcomprador_TO}>TO</option>
	    	</select>
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="emailcomprador">Email <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="emailcomprador" name="emailcomprador" value="{emailcomprador}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="telefonecomprador">Telefone:</label>
	    <div class="controls">
	    	<input type="text" id="telefonecomprador" name="telefonecomprador" value="{telefonecomprador}">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="sexocomprador">Sexo:</label>
	    <div class="controls">
	       <label class="radio">
	           <input type="radio" name="sexocomprador" id="sexocompradorM" value="M" {sexocomprador_M}> Masculino
	       </label>
	       <label class="radio">
	           <input type="radio" name="sexocomprador" id="sexocompradorF" value="F" {sexocomprador_F}> Feminino
	       </label>
	    </div>
	</div>
	<hr>
	<div class="control-group">
		<label class="control-label" for="senhacomprador">Senha:</label>
	    <div class="controls">
	    	<input type="text" id="senhacomprador" name="senhacomprador" value="{senhacomprador}">
	    </div>
	</div>
  	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>