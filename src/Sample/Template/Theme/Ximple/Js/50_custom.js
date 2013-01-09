// JavaScript Document

// Syntax Highlight
hljs.tabReplace = '    ';
hljs.lineNodes = true;
hljs.initHighlightingOnLoad();

$(window).keydown(function(e) {
	switch(e.keyCode) {
		case 37:
		case 72:
			if($('.bar .new a')[0] != undefined)
				$('.bar .new a')[0].click();
			break;
		case 39:
		case 76:
			if($('.bar .old a')[0] != undefined)
				$('.bar .old a')[0].click();
			break;
		case 74:
			scrollBy(0, 40);
			break;
		case 75:
			scrollBy(0, -40);
			break;
	}
});

$('#show').click(function() {
	if($('#slider').attr('class') == 'hide') {
		if($.browser.chrome)
			$('#main').animate({ 'margin-left': '-=125px' }, function() {
				$('#main').css('margin', '0 auto');
			});
		$('body').animate({ 'padding-left': '+=250px' });
		$('#nav').animate({ 'left': '+=250px' });
		$('#slider').animate({
			'left': '+=250px'
		}, function() {
			$('#slider').removeClass('hide');
			$('.arrow-left').fadeIn();
			$('.arrow-right').fadeOut();
		});
	}
});

$('#hide').click(function() {
	if($('#slider').attr('class') != 'hide') {
		if($.browser.chrome)
			$('#main').animate({ 'margin-left': '+=125px' }, function() {
				$('#main').css('margin', '0 auto');
			});
		$('body').animate({ 'padding-left': '-=250px' });
		$('#nav').animate({ 'left': '-=250px' });
		$('#slider').animate({
			'left': '-=250px'
		}, function() {
			$('#slider').addClass('hide');
			$('.arrow-right').fadeIn();
			$('.arrow-left').fadeOut();
		});
	}
});
