$(document).ready(function()	{
	$('#aj_viewposts').click(function()  {
		//Now we use the page from the php page.
		$.get('/DeltaTiger/admin/ajax/psgview.php', { input : 'viewposts'}, function(data) {
			//Put the content into the rightMainBar
			$('#rightMainBar').html(data);
			$('#aj_viewpost_id').click(function()	{
				//Another load over here.
				var postId = $(this).data('test');
				//TODO now we get the content from the db and display it properly.
				$.get('/DeltaTiger/admin/ajax/psgview.php', {input : 'viewindposts', id : postId}, function(data)	{
					$('#rightMainBar').html(data);
				});
			});
		});
	});
	$('#aj_viewstats').click(function()  {
		//Now we use the page from the php page.
		$.get('/DeltaTiger/admin/ajax/psgview.php', { input : 'viewstats'}, function(data) {
			//Put the content into the rightMainBar
			$('#rightMainBar').html(data);
		});
	});
	$('#aj_newposts').click(function()  {
		//Now we use the page from the php page.
		$.get('/DeltaTiger/admin/ajax/psgview.php', { input : 'newpost'}, function(data) {
			//Put the content into the rightMainBar
			$('#rightMainBar').html(data);
			$('#addMoreUpload').click(function()	{
				var currentCount = $('input[type="file"]').length;
				var nextCount = currentCount + 1;
				$('#newUploadHere').append('<input type="file" name="file_' + nextCount + '" />');
			});
			$('input[type="submit"]').click(function()	{
				var postTilteLen = $('#postTitle').val();
				var postDetailsLen = $('#postDetails').val();
				alert(postTitleLen + ' ' + postDetailsLen);
			});
		});
	});
	$('#aj_newmail').click(function()  {
		//Now we use the page from the php page.
		$.get('/DeltaTiger/admin/ajax/psgview.php', { input : 'newmail'}, function(data) {
			//Put the content into the rightMainBar
			$('#rightMainBar').html(data);
		});
	});
	
});


