<div class="row-fluid">
    <div class="span12">
        <legend>Forma de Entrega</legend>
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="well">
            <h5>Endereço de Entrega:</h5>
            Endereço: {ENDERECO}<br>
            Cidade: {CIDADE}/{UF}<br>
            Cep: {CEP}<br>
        </div>
        <div class="well">
            <h5>Alterar Endereço de Entrega:</h5>
            <form action="{URLALTERAENTREGA}" method="post" class="form-horizontal">
                <div class="control-group">
            		<label class="control-label" for="enderecocomprador">Endereço <span class="required">*</span>:</label>
            	    <div class="controls">
            	    	<input type="text" id="enderecocomprador" name="enderecocomprador" value="" required="required">
            	    </div>
            	</div>
                <div class="control-group">
            	   <label class="control-label" for="cepcomprador">CEP <span class="required">*</span>:</label>
            	    <div class="controls">
            	    	<input type="text" class="mask-cep busca-cep" id="cepcomprador" name="cepcomprador" value="{cepcomprador}" required="required">
            	    </div>
            	</div>
            	<div class="control-group">
            		<label class="control-label" for="cidadecomprador">Cidade <span class="required">*</span>:</label>
            	    <div class="controls">
            	    	<input type="text" id="cidadecomprador" name="cidadecomprador" value="" required="required">
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
            	<button type="submit" class="btn btn-info">Alterar</button>
            </form>
        </div>
    </div>
    <div class="span6">
        <div class="well">
            <h5>Opções de Entrega:</h5>
            <form action="{URLPAGAMENTO}" method="post">
            {BLC_FORMAENTREGA}
            <label>
                <input name="codformaentrega" {CHECKED_FE} type="radio" value="{CODFORMAENTREGA}">{NOMEFORMAENTREGA} - {DIASENTREGA} dias úteis para entrega - R$ {VALOR}
            </label>
            {/BLC_FORMAENTREGA}
            {BLC_PERMITECOMPRAR}
            <button type="submit" class="btn btn-success pull-right">Finalizar</button>
            {/BLC_PERMITECOMPRAR}
            </form>
        </div>
    </div>
</div>