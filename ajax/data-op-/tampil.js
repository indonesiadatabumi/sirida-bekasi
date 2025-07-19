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
		$("table").empty();
		$.each(data.result, function() {
			$("table").append(+this['no_skrd']+);
		});
	});
}