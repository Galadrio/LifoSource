<?php
$publico = 1;
$hidelogin = 0;
$form = 1;
include('core.php');
function iweb() {
  global $ident,$conftp;
  echo 'Sistema desactivado.';
  return;
  if ($ident) {
    echo 'Ya est�s registrado. Debes salir antes de intentar recuperar tu contrase�a.<br/><br/>';
    return;
  }
  techo('�Has olvidado tu contrase�a? Introduce tu nombre de usuario y el correo electr�nico de tu cuenta en el formulario que hay aqu� debajo, y se generar� y enviar� una nueva contrase�a para ese usuario a tu correo.<br/><br/>El abuso de esta opci�n para molestar a otros jugadores no est� permitido y ser� sancionado.');
  echo "<form method=\"post\" action=\"recupera.php\">";
  echo "<table id=\"login\"><tr><td>";
  echo '<a href="estado.php">-&gt; �Record� mi contrase�a! &lt;-</a><br/><br/>';
  echo "Recuperar contrase�a:<br/><hr/><br/>";

  echo "Nombre de usuario:<br/>";
  echo "<input type=\"text\" name=\"username\" size=\"25\" maxlength=\"14\"/><br/><br/>";

  echo "E-mail:<br/>";
  echo "<input type=\"text\" name=\"email\" size=\"25\" maxlength=\"80\"/><br/><br/>";

  echo "<input type=\"submit\" name=\"recupera\" value=\"Recordar contrase�a\"/><br/>";
  
  echo "</td></tr></table></form>";
}

function procesaform() {
  global $_REQUEST,$time,$conforonuevohijo,$conforonuevonieto,$confmailname,$ip,$confurl,$conftp;
  if (isset($_REQUEST['recupera'])) {
    $salirnow = 0;
    if ((isset($_REQUEST['username'])) && (isset($_REQUEST['email']))) {
      $username = check_username($_REQUEST['username']);
      $email = check_email($_REQUEST['email']);
      if ((!$username) && (!$email))
        $_SESSION['error'] = 'Tanto el usuario como el email no son v�lidos.';
      elseif (!$username)
        $_SESSION['error'] = 'El usuario no es v�lido.';
      elseif (!$email)
        $_SESSION['error'] = 'El email no es v�lido.';
      else {
        if (antifloodcheck('recupfalla',"{$username}.{$ip}",2,600)) {
          //sistema desactivado
        }
	else
	  $_SESSION['error'] = 'Demasiados intentos fallidos, intentelo dentro de diez minutos.';
      }
    }
    if ($salirnow)
      header('Location: estado.php');
    else
    header('Location: recupera.php');
    return 1;

  }
}

?>
