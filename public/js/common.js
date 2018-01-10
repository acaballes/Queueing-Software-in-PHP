$(function() {
	$(".delete-btn").on("click", function() {
		var c = confirm("Are you sure you want to delete this record?");
		var url = $(this).attr("data-url");
		if (c == true) {
			window.location.href = url;
		}
	});
});