<?php

// Connexion � la base de donn�es
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=netpack', 'root', 'k65oqh0i');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}
