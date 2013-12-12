<?php

/*  check_username($username) Transforma y comprueba si un nombre de usuario es v�lido o no. Devuelve el nuevo nombre de usuario. Puede no ser v�lido por contener caracteres distintos a letras o por ser m�s largo que 30 caracteres o m�s corto que 3. */
function check_username($username) {
  $username = trim($username);
  if ((ereg('^[a-zA-Z]+$',$username)) && (strlen($username) >= 3) && (strlen($username) <= 14))
    return strtoupper($username[0]).strtolower(substr($username,1));
}

function check_itemname($username) {
  $username = trim($username);
  if ((ereg('^[a-zA-Z�����]+$',$username)) && (strlen($username) >= 3) && (strlen($username) <= 14))
    return strtoupper($username[0]).strtolower(substr($username,1));
}

function check_clan($clan) {
  $clan = trim($clan);
  if ((ereg('^[a-zA-Z]{1}([a-zA-Z]+\ ?)*$',$clan)) && (strlen($clan) >= 8) && (strlen($clan) <= 20))
    return strtoupper($clan[0]).strtolower(substr($clan,1));
}

function check_subject($subject) {
  return htmlentities(trim($subject));
}

/* check_email(email) Transforma y comprueba si una direcci�n e-mail es v�lida o no. Devuelve el email si es v�lido. Puede no ser v�lido por contener caracteres inv�lidos, no tener forma de email v�lido, o por ser m�s largo que 80 caracteres. */
function check_email($email) {
  $email = trim($email);
  if ((ereg('^[\_\.a-zA-Z0-9-]*@[\_\.a-zA-Z0-9-]*\.[\_\.a-zA-Z0-9-]*$',$email)) && (strlen($email) <= 80)) {
    $email = strtolower($email);
    if (!ereg('(\.gov)|(\.su$)|(\.mil$)|(.fed.us)',$email))
      return $email;
  }
}
/* check_password(password) Transforma y comprueba si un password es v�lido o no. Devuelve la contrase�a si es v�lida. Puede no ser v�lido por ser m�s corta que 6 caracteres o m�s larga que 30. */
function check_password($password) {
  $password = trim($password);
  if ((strlen($password) >= 6) and (strlen($password) <= 30) and (ereg('^[a-zA-Z0-9\_\-\.\+\:\,\$\#\@\!\%\&\/\(\)\=\\\{\}\|\<\>\�\�\�\�\�]+$',$password))) 
return $password;
}

function check_text($mensaje) {
  $mensaje = trim($mensaje);
  return htmlentities(addslashes(wordwrap(trim(stripslashes($mensaje)),50,' ',1)));
}
?>
