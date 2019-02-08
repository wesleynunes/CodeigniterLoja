$(document).ready(function() {
	
	function campoInteiro(event) {
		if (event.keyCode === 46 || event.keyCode === 8 || event.keyCode === 9 || event.keyCode === 27 || event.keyCode === 13 ||
			(event.keyCode === 64 && event.ctrlKey === true) ||
			(event.keyCode >= 35 && event.keyCode <= 39)
			) {
			return;
		} else {
			if (event.shiftKey || ((event.keyCode < 48 || event.keyCode > 57)
				&& (event.keyCode < 96 || event.keyCode > 105))) {
				event.preventDefault();
			}
		}
	}
	
	function mostraGaleria() {
		$("#foto-principal:visible").elevateZoom({
			gallery : 'fotos-sem-sku',
			cursor : 'pointer',
			galleryActiveClass : 'active',
			imageCrossfade : true
		});
		
		$(".galeria-foto:visible").bind("click", function(e) {  
			var ez =   $("#foto-principal:visible").data('elevateZoom');
			return false;
		});
	}
	
	function mostraGaleriaSKU(codsku) {
		$("#foto-principal:visible").elevateZoom({
			gallery : 'gal-sku-' + codsku,
			cursor : 'pointer',
			galleryActiveClass : 'active',
			imageCrossfade : true
		});
		
		$(".galeria-foto:visible").bind("click", function(e) {  
			var ez =   $("#foto-principal:visible").data('elevateZoom');
			return false;
		});
		
		$('.galeria-foto:visible').find('a:first-child').click();
	}
	
	$('.mask-cpf').mask('999.999.999-99');
	$('.mask-cep').mask('99999-999');
	// $('.set-date').mask('99/99/9999');
	/*
	 * $('.set-date').datepicker({ format : 'dd/mm/yyyy' })
	 */
	
	
	$('.set-sku').change(function () {
		$('.galeria-foto').addClass('hide');
		$('#gal-sku-' + $(this).val()).removeClass('hide');
		
		mostraGaleriaSKU($(this).val());
		
		if ($(this).attr('data-disponivel') === "1") {
			$('#btn-comprar').removeClass('hide');
			$('#aviso-indisponivel').addClass('hide');
		} else {
			$('#btn-comprar').addClass('hide');
			$('#aviso-indisponivel').removeClass('hide');
		}
	});
	
	if ($('.set-sku').length > 0) {
		$('.galeria-foto').addClass('hide');
		$('#gal-sku-' + $('.set-sku:checked').val()).removeClass('hide');
		
		mostraGaleriaSKU($('.set-sku:checked').val());
		
		
	} else {
		mostraGaleria();
	}
	
	$('.set-integer').change(function (event) {
		campoInteiro(event);
    });
	
	$('.set-integer').keydown(function (event) {
		campoInteiro(event);
    });
	
	$('.set-formapagamento').change(function (){
		if ($(this).attr('data-tipo') != "2") {
			$('.info-cartao-credito').addClass("hide");
		} else {
			$('.info-cartao-credito').removeClass("hide");			
		}
	});
	
});