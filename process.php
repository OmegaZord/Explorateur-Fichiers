<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$info = $_POST['folder'];
$info();

/* Cette fonction générique sert a afficher soit les dossiers soit les fichiers */

function echoGenerique($tabl_dossier, $pathDossier, $i, $d) {

    /* is_dir lit seulement les dossier */
    if (is_dir($pathDossier)) {
        echo "<div id='".$tabl_dossier[$i]."' class='animated fadeInDown folder ligne col-md-3' style='animation-delay:".$d."s;' data-toggle='tooltip()' title='Ceci est un dossier' onclick='clickDossier(this.id)'><i class='fa fa-2x fa-folder-o'></i><p>".$tabl_dossier[$i]."</p></div>";
    } else {
        echo "<div id='".$tabl_dossier[$i]."' class='animated fadeInDown folder ligne col-md-3' style='animation-delay:".$d."s;' data-toggle='tooltip()' title='Ce fichier a été mofifié le : ".date('F d Y H:i:s', filemtime($pathDossier))."'><i class='fa fa-2x fa-file-o'></i><span class=' fa fa-pencil-square-o btnRennomer' title='Renommer' onclick='renommer(this.parentNode.id)'></span><span class='btnSuppression fa fa-trash-o' title='Supprimer' onclick='suppression(this.parentNode.id)'></span><p>".$tabl_dossier[$i]."</p></div>";
    }
}

/* Fonction générique qui sert a trier les dossiers/fichiers */

function triGenerique($pathTemp, $tabl_dossier) {
    $tabl_fichier = array();
    $longueurTableau = count($tabl_dossier);

    for ($compteur = 0; $compteur < $longueurTableau; $compteur++) {
        $pathDossier = $pathTemp . "/" . $tabl_dossier[$compteur];
        /* is_file lit seulement les fichiers */
        if (is_file($pathDossier)) {
            /* array_push empile les variables */
            array_push($tabl_fichier, $tabl_dossier[$compteur]);
            unset($tabl_dossier[$compteur]);
        }
    }
    /* sort sert a trier un tableau */
    sort($tabl_dossier, SORT_NATURAL | SORT_FLAG_CASE);
    sort($tabl_fichier, SORT_NATURAL | SORT_FLAG_CASE);

    foreach ($tabl_fichier as $file) {
        array_push($tabl_dossier, $file);
    }
    /* array_values reprend les valeurs du tableau et les affiche de facon numérique */
    return array_values($tabl_dossier);
}

/* Cette focntion sert a afficher les dossiers lors du chargement de la page */

function dossier() {
    $pathTemp = "/home/" . $_POST['user'];

    if (is_dir($pathTemp) == true) {
        /* scandir liste les fichiers et dossiers dans un dossiers */
        $tabl_dossier = scandir($pathTemp);
        $tabl_dossier = triGenerique($pathTemp, $tabl_dossier);
        $d = 0;

        /* count compte tout les elements d'un tableau */
        for ($i = 0; $i < count($tabl_dossier); $i++) {

            if ($tabl_dossier[$i] != null && $tabl_dossier[$i] != "" && $tabl_dossier[$i][0] != '.') {
                $pathDossier = $pathTemp . "/" . $tabl_dossier[$i];
                $d = $d + 0.1;
                echoGenerique($tabl_dossier, $pathDossier, $i, $d);
            }
        }
    } else {
        echo "Nom d'utilisateur invalide";
    }
}

/* Cette fonction sert a confirmer la recherche de dossier par l'input */

function envoyer() {
    $doss = "/home/" . $_POST['nameFolder'];

    if (is_dir($doss) == true) {
        $tabl_dossier = scandir($doss);
        $tabl_dossier = triGenerique($doss, $tabl_dossier);
        $d = 0;

        for ($i = 0; $i < count($tabl_dossier); $i++) {
            $pathDossier = $doss . '/' . $tabl_dossier[$i];

            if ($tabl_dossier[$i][0] != '.') {
                $d = $d + 0.1;
                echoGenerique($tabl_dossier, $pathDossier, $i, $d);
            }
        }
    } else {
        echo "Nom de dossier invalide";
    }
}

/* Cette fonction sert a parcourir dans les dossier lors d'un clique l'un d'eux */

function testClickDossier() {
    $tabl_dossier = scandir($_POST['repertoire'] . '/' . $_POST['dossier']);
    $tabl_dossier = triGenerique($_POST['repertoire'] . '/' . $_POST['dossier'], $tabl_dossier);
    $d = 0;

    for ($i = 0; $i < count($tabl_dossier); $i++) {
        $pathDossier = $_POST['repertoire'] . '/' . $_POST['dossier'] . "/" . $tabl_dossier[$i];

        if ($tabl_dossier[$i][0] != '.') {
            $d = $d + 0.1;
            echoGenerique($tabl_dossier, $pathDossier, $i, $d);
        }
    }
}

/* Cette fonction sert a rafraichir une div specifique lors d'un clique sur un dossier */

function refreshInput() {
    $repertoire = $_POST['repertoire'];
    $command = $repertoire . '/' . $_POST['dossier'];
    echo '<p>' . $command . '<p/>';
}

/* Cette fonction sert a la creation d'un nouveau fichier (.txt pour l'instant) */

function creation() {
    $element = $_POST["dossier"];
    $repert = $_POST['repertoire'];
    /* shell_exec execute une commande et met le résultat sous forme de chaine */
    $command = shell_exec('touch ' . $_POST['repertoire'] . '/' . $element . '.txt' . ' 2>&1');
    $tabl_dossier = scandir($_POST['repertoire']);
    $tabl_dossier = triGenerique($repert, $tabl_dossier);
    $d = 0;

    for ($i = 0; $i < count($tabl_dossier); $i++) {
        $pathDossier = $_POST['repertoire'] . '/' . $tabl_dossier[$i];

        if ($tabl_dossier[$i][0] != '.') {
            $d = $d + 0.1;
            echoGenerique($tabl_dossier, $pathDossier, $i, $d);
        }
    }
}

/* Cette fonction sert a renommer un fichier */

function renommer() {
    $command = shell_exec('mv ' . $_POST['fichier'] . ' ' . $_POST['nom']);
    $tabl_dossier = scandir($_POST['repertoire']);
    $tabl_dossier = triGenerique($_POST['repertoire'], $tabl_dossier);
    $d = 0;

    for ($i = 0; $i < count($tabl_dossier); $i++) {
        $pathDossier = $_POST['repertoire'] . '/' . $tabl_dossier[$i];

        if ($tabl_dossier[$i][0] != '.') {
            $d = $d + 0.1;
            echoGenerique($tabl_dossier, $pathDossier, $i, $d);
        }
    }
}

/* Cette fonction sert a supprimer un fichier */

function suppression() {
    $command = shell_exec('rm ' . $_POST['fichier']);
    $tabl_dossier = scandir($_POST['repertoire']);
    $tabl_dossier = triGenerique($_POST['repertoire'], $tabl_dossier);
    $d = 0;

    for ($i = 0; $i < count($tabl_dossier); $i++) {
        $pathDossier = $_POST['repertoire'] . '/' . $tabl_dossier[$i];

        if ($tabl_dossier[$i][0] != '.') {
            $d = $d + 0.1;
            echoGenerique($tabl_dossier, $pathDossier, $i, $d);
        }
    }
}

/* Cette fonction utiliser le boutton de retour au dossier précédent */

function clickRetour() {
    $tabl_dossier = scandir($_POST['repertoire']);
    $tabl_dossier = triGenerique($_POST['repertoire'], $tabl_dossier);
    $d = 0;

    for ($i = 0; $i < count($tabl_dossier); $i++) {
        $pathDossier = $_POST['repertoire'] . '/' . $tabl_dossier[$i];

        if ($tabl_dossier[$i][0] != '.') {
            $d = $d + 0.1;
            echoGenerique($tabl_dossier, $pathDossier, $i, $d);
        }
    }
}

/* Cette fonction sert a afficher le système d'exploitation utilisé */

function getOS() {
    echo PHP_OS;
}

?>
