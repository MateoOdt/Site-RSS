<?php declare(strict_types=1);

// Niveau d'erreur maximal pour développer
error_reporting(E_ALL | E_STRICT);
// Fuseau horaire par défaut pour ne pas avoir de problème avec les fonctions sur dates
date_default_timezone_set('Europe/Paris');

// Tentative de chargement magique du fichier contenant la classe non définie
spl_autoload_register(function ($nomclasse) 
{
    // fonction spl_autoload_register()
    $classe = __DIR__ . "/src/" . $nomclasse . ".php";
    // Nom du fichier = répertoire_de_ce_fichier/src/nom_de_la_classe.php
    // Existe t'il ?
    if (file_exists($classe)) 
    {
        // Si oui, il faut l'inclure
        require_once $classe;
    }
});
