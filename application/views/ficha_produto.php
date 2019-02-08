<div class="container-fluid">
	<div class="row-fluid">
		<div class="span8">
		  <img src="{FOTOPRINCIPAL}" id="foto-principal" data-zoom-imagem="{FOTOZOOM}">
		
		  {BLC_GALERIA}
		      <div id="{NOMEGAL}" class="{CLASSEGALERIA} galeria-foto">
		          {BLC_FOTOS}
		          <a href="#" data-image="{URLFOTONORMAL}" data-zoom-image="{URLFOTOZOOM}">
                    <img id="{CODPRODUTOFOTO}" src="{URLFOTOTHUMB}" />
                  </a>
                  {/BLC_FOTOS}
		      </div>
		  {/BLC_GALERIA}
		
		</div>
		<div class="span4">
			<h1>{NOMEPRODUTO}</h1>
			<hr>
			{DESCRICAOBASICA}
			<br>
			{BLC_PROMOCAO}
			De R$ <strong>{VALORANTIGO}</strong> por
			{/BLC_PROMOCAO}
			<h4>R$ {VALORFINALPRODUTO}</h4>
			<form action="<?php echo site_url('checkout/adicionar');?>" method="post">			 
			  <input type="hidden" value="{CODPRODUTO}" name="codproduto">
			  {BLC_SKUSIMPLES}
			  <input type="hidden" value="{CODSKU}" name="codsku">
			  {/BLC_SKUSIMPLES}
			  {BLC_SKUCOMPLEXO}
			  <label class="radio">
			     <input type="radio" data-disponivel="{DISPONIBILIDADE_SKU}" value="{CODSKU}" name="codsku" {SELECTEDSKU} class="set-sku">{NOMEATRIBUTO} <small style="font-size:8px;">({REFERENCIA})</small>
			  </label>
			  {/BLC_SKUCOMPLEXO}
		      <button type="submit" id="btn-comprar" class="btn btn-success btn-block btn-large {COMPRAR_VISIBILDADE}">Comprar</button>
			  <span id="aviso-indisponivel" class="{INDISPONIVEL_VISIBILDADE}">Produto Indisponível</span>
			</form>
		</div>
	</div>
	<div class="row-fluid">
        <div class="span12">
            {DESCRICAOCOMPLETA}
        </div>
	</div>
</div>