/*Fonction qui sert a enclencher les fonctions dossiers et getOS dans process.php*/
/*Cette fonction s'enclenche lorsque le chargement de la page se termine*/

function demander(){
    var person = prompt("Entrez votre nom d'utilisateur");
    
    if (person != null && person != "") {
        $("#repertoireCourant").html("/home/" + person);
        $.ajax({
            type: "post",
            url: "process.php",
            data: {folder: 'dossier', user: person},
            success: function (reponse) {
                if (reponse == "Nom d'utilisateur invalide"){
                    alert(reponse);
                    document.getElementsByTagName('input')[0].disabled = true;
                    document.getElementsByTagName('input')[1].disabled = true;
                    document.getElementsByTagName('input')[2].disabled = true;
                    document.getElementsByTagName('input')[3].disabled = true;
                    $("#repertoireCourant").html("Vous avez entré un nom d'utilisateur incorrect, veuillez rafraîchir la page");
                } 
                else{
                    $('#dossier').html(reponse);
                    $.ajax({
                        type: "post",
                        url: "process.php",
                        data: {folder: 'getOS'},
                        success: function (reponse) {
                            $('#OS').html(reponse);
                        }
                    });
                }
            }
        });
    }
    else{
        $("#repertoireCourant").html("Vous avez oublié d'entrer votre nom d'utilisateur, veuillez rafraîchir la page");
        document.getElementsByTagName('input')[0].disabled = true;
        document.getElementsByTagName('input')[1].disabled = true;
        document.getElementsByTagName('input')[2].disabled = true;
        document.getElementsByTagName('input')[3].disabled = true;
    }
    $('i').addClass('animated fadeInDown');
}
/*Fonction qui sert a enclencher la fonction dossier dans process.php*/
/*Cette fonction s'enclenche lors du clique sur le bouton envoyer*/

function envoyer(){
    $.ajax({
        type: "post",
        url: "process.php",
        data: {folder: 'envoyer', nameFolder: document.getElementsByTagName('input')[2].value},
        success: function (reponse){
            if (reponse == "Nom de dossier invalide"){
                $('#repertoireCourant').html("/home");
                alert(reponse);
                $.ajax({
                    type: "post",
                    url: "process.php",
                    data: {folder: 'dossier', user: ""},
                    success: function (reponse) {
                        $('#dossier').html(reponse);
                    }
                });
            }
            else{
                $('#dossier').html(reponse);
                $('#repertoireCourant').html("/home/" + document.getElementsByTagName('input')[2].value);
            }
        }
    });
}
/*Fonction qui sert a enclencher les fonctions testClickDossier et refreshInput dans process.php*/
/* Cette fonction s'enclenche lors du clique sur un dossier */
function clickDossier(id){
    $.ajax({
        url: "process.php",
        type: "post",
        data: {folder: "testClickDossier", dossier: id, repertoire: $('#repertoireCourant').text()},
        success: function (reponse){
            $('#dossier').html(reponse);
        }
    });
    $.ajax({
        url: "process.php",
        type: "post",
        data: {folder: "refreshInput", dossier: id, repertoire: $('#repertoireCourant').text()},
        success: function (reponse){
            $('#repertoireCourant').html(reponse);
        }
    });
}
/*Fonction qui enclenche la fonction clickRetour dans process.php*/
/*Cette fonction s'enclenche lors du clique sur le bouton retour*/
function clickRetour(){
    var path = $('#repertoireCourant').text();
    if (path != "/home"){
        var tableauDossiers = path.split("/");
        var newPath = "";
        for (var i = 1; i < tableauDossiers.length - 1; i++){
            newPath += "/" + tableauDossiers[i];
        }
        /*Refresh repertoire courant*/
        $('#repertoireCourant').html(newPath);
        /*Refresh dossiers*/
        $.ajax({
            url: "process.php",
            type: "post",
            data: {folder: "clickRetour", repertoire: newPath},
            success: function (reponse){
                $('#dossier').html(reponse);
            }
        });
    }
    else{
        alert("Vous ne pouvez pas remonter plus haut que /home");
    }
}
/*Fonction qui sert a afficher un title*/
/*Cette fonction s'enclenche lorsque la page a fini son chargement*/
$(document).ready(function (){
    $('[data-toggle="tooltip"]').tooltip();
});
/*Fonction qui sert a enclencher la fonction creation dans process.php*/
/*cette fonction s'enclenche lors du clique sur le bouton d'ajout d'un fichier*/
function creation(){
    var dde = prompt("Quel nom donnez-vous à ce fichier?");
    $.ajax({
        url: "process.php",
        type: "post",
        data: {folder: "creation", dossier: dde, repertoire: $('#repertoireCourant').text()},
        success: function (reponse){
            $("#dossier").html(reponse);
        }
    });
}
/*Fonction qui sert a enclencher la fonction renommer dans process.php*/
/*Cette fonction s'enclenche lors d'un clique sur le bouton renommer*/
function renommer(nomFichier){
    var path = $('#repertoireCourant').text() + '/' + nomFichier;
    var nouveauNom = prompt("Quel nouveau nom voulez-vous donner au fichier " + path + " (n'oubliez pas de préciser l'extension)");
    if (nouveauNom != "" && nouveauNom != null){
        var pathNouveauNom = $('#repertoireCourant').text() + '/' + nouveauNom;
        $.ajax({
            url: "process.php",
            type: "post",
            data: {folder: "renommer", repertoire: $('#repertoireCourant').text(), fichier: path, nom: pathNouveauNom},
            success: function (reponse){
                $("#dossier").html(reponse);
                alert("Le fichier" + nomFichier + " a été renommé en " + nouveauNom);
            }
        });
    }
    else if (nouveauNom == ""){
        alert("Vous ne pouvez pas entrer un nouveau nom qui soit vide.");
    }
}
/*Fonction qui sert a enclencher la fonction suppression dans process.php*/
/*Cette fonction s'enclenche lors d'un clique sur le bouton renommer*/
function suppression(nomFichier){
    var path = $('#repertoireCourant').text() + '/' + nomFichier;
    if (confirm("Attention: Etes-vous certain de vouloir supprimer ce fichier? Suppression de " + path)){
        $.ajax({
            url: "process.php",
            type: "post",
            data: {folder: "suppression", repertoire: $('#repertoireCourant').text(), fichier: path},
            success: function (reponse){
                $("#dossier").html(reponse);
                alert("Le fichier" + path + " a été supprimé");
            }
        });
    }
}
