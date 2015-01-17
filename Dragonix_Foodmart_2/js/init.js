$( document ).ready(function() {
	
	$( ".cusID" ).click(function() {
		var info = $(this).attr('rel');
		var arr = info.split('|');
		for(var i=0;i<arr.length;i++)
			$(".cusVal:eq("+i+")").html(arr[i]);
	});
	
	$( ".orderID" ).click(function() {
		$("#orderContainer").empty();
		var count = $(this).parent().children().length;
		console.log(count);
		for(var i=1;i<count;i++) 
		$("#orderContainer").append("<div class='orderWrap'>"+$(this).parent().children().eq(i).html()+"</div>");
	});
});