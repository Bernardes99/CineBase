//Adding to favorites when coming from movie_details
function addToFavorites_details(filmeid) {

    if (confirm("Confirme que quer adicionar este filme aos favoritos?")) {
        location.replace("add_favorites.php?filmeid=" + filmeid);
    } else {
        //alert("I am an alert box!");
    }

}

function edit_movie(filmeid) {

    location.replace("edit_movie.php?filmeid=" + filmeid);

}

function seeDetails(r) {

    var i = r.parentNode.parentNode.rowIndex;
    filmeid = document.getElementById("filme_table").rows[i].cells[0].innerHTML;

    location.replace("movie_details.php?filmeid=" + filmeid);
}

function removeFromFavorites_details(filmeid) {

    if (confirm("Confirme que quer remover este filme dos favoritos?")) {
        location.replace("remove_favorites.php?filmeid=" + filmeid);
    } else {
        //alert("I am an alert box!");
    }

}

function hide_movie(filmeid) {

    if (confirm("Confirme que quer esconder este filme?")) {
        location.replace("hide_movie.php?filmeid=" + filmeid);
    } else {
        //alert("I am an alert box!");
    }

}

function putVisible_movie(filmeid) {

    if (confirm("Confirme que quer voltar a tornar público este filme?")) {
        location.replace("put_visible_movie.php?filmeid=" + filmeid);
    } else {
        //alert("I am an alert box!");
    }

}

/*
function seeDetails_edited(filmeid) {

    location.replace("movie_details.php?filmeid=" + filmeid); //onde está o # substituir pela página onde abre o filme com os detalhes, 
    //e vai a variável filmeid para essa página
}

function removeFromFavorites(r) {

    var i = r.parentNode.parentNode.rowIndex;
    filmeid = document.getElementById("filme_table").rows[i].cells[0].innerHTML;

    if (confirm("Confirme que quer remover este filme dos favoritos?")) {
        location.replace("remove_favorites.php?filmeid=" + filmeid);
    } else {
        //alert("I am an alert box!");
    }

}*/

/*function addToFavorites(r) {

    var i = r.parentNode.parentNode.rowIndex;
    filmeid = document.getElementById("filme_table").rows[i].cells[0].innerHTML;

    if (confirm("Confirme que quer adicionar este filme aos favoritos?")) {
        location.replace("add_favorites.php?filmeid=" + filmeid);
    } else {
        //alert("I am an alert box!");
    }

}*/