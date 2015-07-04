function getMenu()
{
	$.ajax({
		type: 'post',
		data: 'id=1',
		url: 'http://tazamandi.in/appsajax/menu.php',
		success: function(msg)
		{
			$("#navMenu").html(msg);
		}
	});
}

function getProduct()
{
	$.ajax({
		type: 'post',
		data: 'id=1',
		url: 'http://tazamandi.in/appsajax/product.php',
		success: function(msg)
		{
			$("#cartItem").html(msg);
		}
	});
}