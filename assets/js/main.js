$(function () {
	$('.msg').delay(2000).slideUp('slow');


	var pathnames = window.location.pathname.split('/');
	var filename = pathnames[pathnames.length -1];

	$('.nav-sidebar')
			.find('li').removeClass('active')
			.end()
			.find('a[href="' +filename + '"]').parent().addClass('active');

	$("#change-major-setting").on('click',function () {
		$("#setting-table").find('.lbl').hide().end().find('.editing').prop('disabled',false).show();
		$(this).hide();
		$("#cancel-setting-btn").show();
	});

	$("#cancel-setting-btn").on('click',function () {
		$("#setting-table").find('.lbl').show().end().find('.editing').prop('disabled',true).hide();
		$(this).hide();
		$("#change-major-setting").show();
	});
});