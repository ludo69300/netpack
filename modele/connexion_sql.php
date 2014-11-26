<?php

// Connexion à la base de données
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=netpack', 'root', 'k65oqh0i');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}
