//https://wemo.tech/2101
//https://qiita.com/nightyknite/items/668c112c40931515ed67
//https://q-az.net/none-jquery-eq-not-slice-children-parent-next-prev/
window.addEventListener('load', function () {
	var toglewrap = document.getElementsByClassName("psao_toglewrap");
	for (var i = 0; i < toglewrap.length; i++) {
		toglewrap[i].querySelector(".psao_showbutton").addEventListener("click", function () {
			this.parentNode.querySelector('.psao_checkbox').classList.toggle('psao_hiddenwrap');
		});
	}

	jQuery('.psao_select').select2({
		width: 'resolve',
		language: "ja"
	});
});