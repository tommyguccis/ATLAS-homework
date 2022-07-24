document.addEventListener("DOMContentLoaded", function(){
	$(function () {
		$.nette.init();
	});
	
	const btnAddFilm = document.getElementById("addNewFilmBtn");
	const filmForm = document.querySelector(".film-form");
	const defaultValueBtnAddFilm = btnAddFilm.innerHTML;
	var filmFormVisibile = true;

	if(btnAddFilm){
		btnAddFilm.addEventListener("click", function(){
			if(filmFormVisibile) {
				filmForm.style.display = "flex";
				btnAddFilm.innerHTML = "Close";
				filmFormVisibile = false;
			} else {
				filmForm.style.display = "none";
				btnAddFilm.innerHTML = defaultValueBtnAddFilm;
				filmFormVisibile = true;
			}
		});
	}

	const btnEditCreators = document.getElementById("editCreators");
	const btnRemoveCreator = document.querySelectorAll(".removeCreatorBtn");
	const creatorsListAdd = document.getElementById("creatorsListAdd");
	var editCreatorsVisibile = true;

	if(btnEditCreators) {
		btnEditCreators.addEventListener("click", function(){

			if(editCreatorsVisibile) {
				btnRemoveCreator.forEach(function(btn) {
					btn.style.display = "inline-block";
				});
				creatorsListAdd.style.display = "block";
				btnEditCreators.innerHTML = "Cancel";
				editCreatorsVisibile = false;
			} else {
				btnRemoveCreator.forEach(function(btn) {
					btn.style.display = "none";
				});
				creatorsListAdd.style.display = "none";
				btnEditCreators.innerHTML = "Edit creators";
				editCreatorsVisibile = true;
			}
		});
	}

	var messages = document.querySelector(".flash");

	if(messages) {
		setTimeout(function(){
			messages.className = 'flash-invisible';
		}, 3000);
	}
})