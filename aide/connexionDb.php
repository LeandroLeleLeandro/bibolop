<?php

function connectDB()
{
  try
  {

  //return new PDO('mysql:host=localhost;dbname=cfptqnshascript', 'root', '');
  return new PDO('mysql:host=cfptqnshascript.mysql.db;dbname=cfptqnshascript', 'cfptqnshascript', 'Leandro2017');
  }
  catch (Exception $e)
  {
    die('Erreur : ' . $e->getMessage());
  }
}
