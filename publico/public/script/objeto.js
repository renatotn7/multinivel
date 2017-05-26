$(function(){


			$('#prof-imagem-p').on('mouseover', '.prof-row', function () {
				$('.prof-row').not(this).stop().animate({ opacity: 0.4 }, 250);
			}).bind('mouseout', function () {
				$('.prof-row').stop().animate({ opacity: 1 }, 250);
			});

			$('#grid-fazemos').on('mouseover', '.rows-fazemos', function () {
				$('.rows-fazemos').not(this).stop().animate({ opacity: 0.4 }, 250);
			}).bind('mouseout', function () {
				$('.rows-fazemos').stop().animate({ opacity: 1 }, 250);
			});

$("#banner").css('display','block');

$("#banner_imagem_fundo").jCarouselLite({
    btnNext: ".next",
    btnPrev: ".prev",
    speed:500,
	auto: 5000,
	vertical:true,
	visible:1,
	mouseWheel: false
});


$("#banner-texto").jCarouselLite({
    btnNext: ".next",
    btnPrev: ".prev",
    speed:500,
	auto: 5000,
	vertical:true,
	visible:1,
	mouseWheel: false
});


$("#banner-logomarca").jCarouselLite({
    btnNext: ".next",
    btnPrev: ".prev",
    speed:500,
	auto: 5000,
	vertical:true,
	visible:1,
	mouseWheel: false
});


});