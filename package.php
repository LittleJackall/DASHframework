<?php
function register($name, $pass, $welcome, $path) {
  $file_pointer = "$path/$name/";
  if (file_exists($file_pointer)) { 
    echo "account already exist";
    $n = "Ce compte existe déjà !";
  }
  else {
    if(empty(trim($pass))){
      echo "Mettez un mot de pass";
      $p = "Mettez un mot de passe";
    } 
    elseif(strlen(trim($pass)) < 6){
      echo "votre mot de passe fait moi de 6";
      $p = "Votre mot de passe fait moin de 6 charactères !";
    } 
    else {
      if(empty(trim($name))){
        $n = "Mettez un pseudo !";
        echo "Mettez un pseudo !";
      }
      elseif(strlen(trim($name)) < 4){
        $n = "Votre nom d'utilisateur doit faire au moin 4 charactères !";
        echo "Votre pseudo doit faire au moin 4 characters !";
      }
      else {
        mkdir("$path/" . $name);
        chmod("$path/" . $name, 0755);
        $myfile = fopen("$path/$name/$name.txt", "w") or die("Unable to open file!");
        $pass1 = hash("ripemd320", "$pass");
        fwrite($myfile, $pass1);
        fclose($myfile);
        chmod("$path/" . $name, 0755);
        session_start();
        $_SESSION["in"] = true;
        $_SESSION["username"] = "$name";
        header("location: $welcome");
      }
    }
  }
}

function login($name, $pass, $welcome, $path) {
  $file_pointer = "$path/$name/"; 
  if (file_exists($file_pointer)) { 
    chmod("$path/" . $name, 0755);
    $filereadcontent = fopen("$path/$name/$name.txt", "r") or die("Unable to open file!");
    $var1 = fread($filereadcontent,filesize("$path/$name/$name.txt"));
    $pass1 = hash("ripemd320", "$pass");
    if ($var1 === $pass1) {
      session_start();
      $_SESSION["in"] = true;
      $_SESSION["username"] = "$name";
      fclose($filereadcontent);
      chmod("$path/" . $name, 0600);
      header("location: $welcome");
    }
    else {
      echo "account not good pass and username !";
      $p = "Le mot de passe de ce compte n'est pas bon !";
      fclose($filereadcontent);
      chmod("account/" . $name, 0600);
    }
  } 
  else { 
    echo "account not exist !";
    $n = "Ce compte n'existe pas !";
  }
}

function logout($login) {
  session_start();
  $_SESSION = array();
  session_destroy();
  header("location: $login");
  exit;
}

function adminchmod($name, $pass, $welcome, $path) {
  chmod("$path/" . $name, 0755);
  $filereadcontent = fopen("$path/$name/$name.txt", "r") or die("Unable to open file!");
  $read = fread($filereadcontent,filesize("$path/$name/$name.txt"));
  echo "$read";
  fclose($filereadcontent);
}

function welcome($login) {
  session_start();
  if(!isset($_SESSION["in"]) || $_SESSION["in"] !== true){
    header("location: $login");
    exit;
  }
}

function varset($name, $var, $varset, $path) {
  chmod("$path/" . $name, 0755);
  $myfile = fopen("$path/$name/$var.txt", "w") or die("Unable to open file!");
  fwrite($myfile, $varset);
  fclose($myfile);
  chmod("$path/" . $name, 0600);
}

function varget($name, $varget, $base, $path) {
  chmod("$path/" . $name, 0755);
  $fileexist = "$path/$name/$varget.txt";
  if (file_exists($fileexist)) {
    $_SESSION["$varget"] = file_get_contents("$path/$name/$varget.txt");
    chmod("$path/" . $name, 0600);
  } else {
    $myfile = fopen("$path/$name/$varget.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $base);
    fclose($myfile);
    $_SESSION["$varget"] = file_get_contents("$path/$name/$varget.txt");
    chmod("$path/" . $name, 0600);
  }
}
?>
