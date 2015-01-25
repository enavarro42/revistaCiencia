 $(document).on("ready", function(){
	$("a.external").click(function() {
	    $('#myModal').modal('show');

	    url = $(this).attr("link");

	    $("#btn_dialog").on("click", function(){
	    	$('#myModal').modal('hide');
	    	window.open(url);
	    	return false;
	    });
	 });

});