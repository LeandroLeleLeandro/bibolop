<?php

require_once 'dbInformations.php';

function UserDbConnection()
{
  static $dbb = null;

  if ($dbb === null) 
  {
      try 
      {
          $dbb = new PDO("mysql:host=" . SERVER . ";dbname=" . DATABASE_NAME, PSEUDO, PWD, array('charset' => 'utf8'));
          $dbb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } 
      catch (PDOException $e) 
      {
          die('Erreur : ' . $e->getMessage());
      }
  }

  return $dbb;
}
