$(document).ready(function () {
	$('.set-numeric').maskMoney({thousands:'.', decimal:','});
	$('.set-peso').maskMoney({thousands:'.', decimal:',', precision:3});
	$('.set-integer').maskMoney({thousands:'', decimal:''});
	
	/*
	$('.set-departamento-pai').click(function () {
		var checado = false;
		
		if ($(this).is(':checked')) {
			checado = true;
		}
		
		$(".set-departamento-filho[data-pai='" + $(this).val() + "']").prop('checked', checado);
	});
	*/
	
	$('.set-departamento-filho').click(function () {
		
		if ($(this).is(':checked')) {			
			$('#departamento-' + $(this).attr('data-pai')).prop('checked', true);
		}
	});
	
	$('.set-quantidade-sku').change(function () {
		if ($(this).val() !== '') {
			$('#quantidade-sku').hide();
		} else {
			$('#quantidade-sku').show();
		}
	});
	
	//DEFINE AS CLASSES SELECT2 PARA O PLUGIN
	$('.select2').select2();
	
	$('.mask-cpf').mask('999.999.999-99');
	$('.mask-cep').mask('99999-999');
	//$('.set-date').mask('99/99/9999');
	$('.set-date').datepicker({
		format: 'dd/mm/yyyy'
	})
	
	function getSiteURL() {
		"use strict";
		return $('#siteURL').val();
	}
	
	$('.busca-cep').focusout(function () {
		var cep = $(this).val();
		$.ajax({
			url: getSiteURL()+"/painel/comprador/getCEP/" + cep,
			dataType: 'json',
			type: 'get'
		}).done(function (data) {
			$('#ufcomprador').val(data.uf);
			$('#cidadecomprador').val(data.cidade);
			$('#enderecocomprador').val(data.logradouro);
		});
	});
	
	$('#frm-upload-foto').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
    });


});