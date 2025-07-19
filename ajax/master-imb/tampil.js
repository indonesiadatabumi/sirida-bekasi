$(document).ready(function() {
    selesai();
});
 
function selesai() {
	setTimeout(function() {
		update();
		selesai();
	}, 200);
}
 
function update() {
	$.getJSON("tampil.php", function(data) {
		$("ul").empty();
		$.each(data.result, function() {
			$("ul").append("<li>"+this['no_registrasi']+"</li>");
		});
	});
}