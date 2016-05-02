function bindSearchFunction(data) {
	$('.search-title').on('input focus', function(event) {
		event.stopPropagation();
		var inputValue = $(this).val().toLowerCase();
		if(inputValue == '') {
			$(this).siblings('.search-option').fadeOut('fast', function(){$(this).siblings('.search-option').addClass('hidden');});
		}else{
			$('.search-result').remove();
			var dataCount = 0;
			for(var i = 0; i < data.length; i++) {
				var id = data[i].problem_id.toString();
				var title = data[i].title.toLowerCase();
				if(id.indexOf(inputValue) >= 0 ||	 title.indexOf(inputValue) >= 0) {
					$(this).siblings('.search-option').append('<span class="search-result">[' + data[i].problem_id + ']:' + data[i].title + '</span>');
					$(this).siblings('.search-option').find('.search-result').eq(dataCount).attr({dataTitle: data[i].title, dataId: data[i].problem_id});
					dataCount++;
				}
			};
			if(dataCount > 6) {
				$(this).siblings('.search-option').css('overflow-y', 'scroll')
			}else {
				$(this).siblings('.search-option').css('overflow-y', 'hidden')
				if(!dataCount) {
					$(this).siblings('.search-option').append('<span class="search-result">not found</span>');
				}
			}
			$(this).siblings('.search-option').fadeIn('fast');
			$(this).siblings('.search-option').removeClass('hidden');
			$('.search-result').click(function(event) {
				var dataId = $(this).attr('dataId');
				var dataTitle = $(this).attr('dataTitle');
				$(this).parent().parent().parent().find('.problem-id').val(dataId);
				$(this).parent().parent().parent().find('.problem-title').val(dataTitle);
				$(this).siblings('.search-option').fadeOut('fast', function(){$(this).siblings('.search-option').addClass('hidden');});
			});
		}
	});
	$('.search-title').blur(function() {
		$(this).siblings('.search-option').fadeOut('fast', function(){$(this).siblings('.search-option').addClass('hidden');});
	});
}