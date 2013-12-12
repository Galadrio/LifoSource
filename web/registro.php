<?php
$publico = 1;
$hidelogin = 1;
$form = 1;
include('core.php');
function iweb() {
  global $ident,$_REQUEST,$conforovisitahijo,$conforovisitanieto,$ip,$confregistro,$conftp;
  if (!$confregistro) {
    echo 'El registro no est� permitido en esta partida.<br/>';
    
  }
  if ($ident) {
    echo 'Ya est�s registrado. Debes salir antes de registrar otra cuenta.<br/><br/>';
    return;
  }
  if (isset($_REQUEST['invita']))
    $p = check_username($_REQUEST['invita']);
  else
    $p = '';
  if (isset($_REQUEST['f']))
    $f = $_REQUEST['f'];
  else
    $f = 0;

  $msgmail = "Ojo, la direcci�n de correo electr�nico tiene que ser v�lida y real. Al registrarte, tu primera contrase�a se env�a a esa direcci�n de correo electr�nico, y la cuenta del juego pertenecer� en todo caso a la persona que puede acceder al correo electr�nico de registro (que se puede cambiar m�s adelante).";
  if ($p) {
    if (antiflood('visita',$ip,1,86400)) {
      if ($f) {
        if (antiflood('visitalimit',$p,100,86400)) {
          $orodalep = $conforovisitahijo;
	  $orodalea = $conforovisitanieto;
	}
	else {
	  $orodalep = 0;
	  $orodalea = 0;
	}
        db_lock("{$conftp}jugadores WRITE");
        $retval = db_query("SELECT padre FROM {$conftp}jugadores WHERE nombrejug='{$p}'");
        if (!mysql_num_rows($retval))
          $p = '';
        else {
          db_query("UPDATE {$conftp}jugadores SET visitashijos=visitashijos+1,oro=oro+{$orodalep} WHERE nombrejug='{$p}'");
          $ret = mysql_fetch_row($retval);
          $abuelo = $ret[0];
          db_query("UPDATE {$conftp}jugadores SET visitasnietos=visitasnietos+1,oro=oro+{$orodalea} WHERE nombrejug='{$abuelo}'");
        }
        db_unlock();
        mysql_free_result($retval);
      }
      $visitado = 1;
    }
    else
      $visitado = 0;

    if ($visitado)
      techo("Mientras caminabas por una calle estrecha has chocado con alguien. Al momento te has dado cuenta de que te ha robado algunas monedas... Te decides a seguirlo, y cuando llegais a su casa se presenta como {$p}, te felicita por ser tan avispado y te ofrece trabajar para �l.<br/><br/>�Te apetece como a muchos otros, entrar a formar parte de esta aventura? Encontrar�s artefactos, mantendr�s combates, ganar�s poder... echa un vistazo a la <a href=\"guia.php\">gu�a del juego</a> o reg�strate (gratis) en el formulario de aqu� debajo y prueba...<br/><br/>{$msgmail}");
    else
      techo("Mientras caminabas por una calle estrecha has chocado con alguien. Al momento te has dado cuenta de que te est� haciendo se�ales... Te decides a seguirlo, y cuando llegais a su casa se presenta como {$p} y te ofrece trabajar para �l.<br/><br/>�Te apetece como a muchos otros, entrar a formar parte de esta aventura? Encontrar�s artefactos, mantendr�s combates, ganar�s poder... echa un vistazo a la <a href=\"guia.php\">gu�a del juego</a> o reg�strate (gratis) en el formulario de aqu� debajo y prueba...<br/><br/>{$msgmail}");
  }
  else {
      techo("Est�s a punto de entrar a formar parte de una aventura que cambiar� tu vida. Encontrar�s artefactos, mantendr�s combates, ganar�s oro y poder... Echa un vistazo a la <a href=\"guia.php\">gu�a del juego</a> o reg�strate (gratis) en el formulario de aqu� debajo y, �mucha suerte!<br/><br/>{$msgmail}");
  }
  echo "<form method=\"post\" action=\"registro.php\">";
  echo "<table id=\"login\"><tr><td>";
  echo '<a href="estado.php">-&gt; �Ya tengo una cuenta! &lt;-</a><br/><br/>';
  echo "Nuevo jugador:<br/><hr/><br/>";

  if (isset($_SESSION['lastuser'])) {
    $lastuser = $_SESSION['lastuser'];
    unset($_SESSION['lastuser']);
  }
  else
    $lastuser = '';
  if (isset($_SESSION['lastemail'])) {
    $lastemail = $_SESSION['lastemail'];
    unset($_SESSION['lastemail']);
  }
  else
    $lastemail = '';
  if (isset($_SESSION['lastpadre'])) {
    $lastpadre = $_SESSION['lastpadre'];
    unset($_SESSION['lastpadre']);
  }
  else
    $lastpadre = '';

  echo "<b>Nombre de usuario: *</b><br/>";
  echo "<input type=\"text\" name=\"username\" size=\"25\" maxlength=\"14\" value=\"{$lastuser}\"/><br/><br/>";

  echo "<b>E-mail: *</b><br/>";
  echo "<input type=\"text\" name=\"email\" size=\"25\" maxlength=\"80\" value=\"{$lastemail}\"/><br/><br/>";

  echo "<b>Contrase�a: *</b><br/>";
  echo "<input type=\"password\" name=\"newpass\" size=\"25\" maxlength=\"30\"/><br/><br/>";

  echo "<b>Repite la contrase�a: *</b><br/>";
  echo "<input type=\"password\" name=\"newpassc\" size=\"25\" maxlength=\"30\"/><br/><br/>";

  echo "Usuario que te invit�: <br/>";
  if ($p) {
    echo "<input type=\"hidden\" name=\"invita\" value=\"{$p}\"/>";
    echo "<input type=\"hidden\" name=\"padre\" value=\"{$p}\"/>";
    echo "<input type=\"text\" name=\"padred\" size=\"25\" disabled=\"disabled\" value=\"{$p}\"/><br/><br/>";
  }
  else
    echo "<input type=\"text\" name=\"padre\" size=\"25\" maxlength=\"30\" value=\"{$lastpadre}\"/><br/><br/>";

  echo "<input type=\"submit\" name=\"registra\" value=\"Registrarse\"/> <b>* obligatorio</b><br/>";
  
  echo "</td></tr></table></form>";
}

function procesaform() {
  global $_REQUEST,$time,$conforonuevohijo,$conforonuevonieto,$confmailname,$ip,$confurl,$ident,$confregistro,$conftp;
  if (isset($_REQUEST['registra'])) {
    $salirnow = 0;
    if (!$confregistro) {
      header("Location: registro.php");
      return 1;
    }
    if ($ident) 
      $_SESSION['error'] = 'Ya est�s identificado, tienes que salir antes de registrar una cuenta.';
    else {
      if ((isset($_REQUEST['username'])) && (isset($_REQUEST['email'])) && (isset($_REQUEST['newpass'])) && (isset($_REQUEST['newpassc']))) {
        $username = check_username($_REQUEST['username']);
        $email = check_email($_REQUEST['email']);
        $newpass = check_password($_REQUEST['newpass']);
        $newpassc = check_password($_REQUEST['newpassc']);
	if (isset($_REQUEST['padre']))
          $padre = check_username($_REQUEST['padre']);
	else
	  $padre = '';
	if ((!$username) && (!$email))
          $_SESSION['error'] = 'Tanto el usuario como el email no son v�lidos.<br/>El usuario s�lo puede estar formado por letras (may�sculas o min�sculas), entre 3 y 14 caracteres, y el email debe ser v�lido.';
        elseif (!$username) {
          $_SESSION['error'] = 'El usuario no es v�lido. S�lo puede estar formado por letras (may�sculas o min�sculas), entre 3 y 14 caracteres.';
          $_SESSION['lastemail'] = $email;
          $_SESSION['lastpadre'] = $padre;
        }
        elseif (!$email) {
          $_SESSION['error'] = 'El email no es v�lido. Debe ser v�lido!';
          $_SESSION['lastuser'] = $username;
          $_SESSION['lastpadre'] = $padre;
        }
        else {
          db_lock("{$conftp}jugadores READ");
          $retval = db_query("SELECT * FROM {$conftp}jugadores WHERE nombrejug='{$username}'");
          db_unlock();
          $a = mysql_num_rows($retval);
          mysql_free_result($retval);
          if ($a) {
            $_SESSION['error'] = 'Ese nombre de usuario ya est� registrado.';
            $_SESSION['lastemail'] = $email;
            $_SESSION['lastpadre'] = $padre;
          }
          else {

            if ((!$newpass) || (!$newpassc))
                $_SESSION['error'] = 'La nueva contrase�a debe tener al menos 6 caracteres y debe contener s�mbolos estandar (letras, n�meros, _-.+:,$#...)';
            else {

              if ($newpass != $newpassc)
                $_SESSION['error'] = 'La contrase�a nueva no coincide con su confirmaci�n.';

              else {

                if (antiflood('registro',$ip,2,3600)) {
                  $padre = '(desconocido)';
                  if (isset($_REQUEST['padre'])) {
                    $padre = check_username($_REQUEST['padre']);
                    if (!$padre)
                      $padre = '(desconocido)';
                  }
  
                  if ($padre != '(desconocido)') {
                    if (antiflood('regpadre',$ip,1,86400))
                      $daroro = 1;
                    else
                      $daroro = 0;
   		    if (!antiflood('reghijo',$padre,10,86400))
		      $daroro = 0;
                    db_lock("{$conftp}jugadores READ");
                    $retval = db_query("SELECT padre FROM {$conftp}jugadores WHERE nombrejug='{$padre}'");
                    db_unlock();
                    if (!mysql_num_rows($retval))
                      $padre = '(desconocido)';
                    else {
	              if ($daroro)
                        db_query("UPDATE {$conftp}jugadores SET hijos=hijos+1,oro=oro+{$conforonuevohijo} WHERE nombrejug='{$padre}'");
                      else
                        db_query("UPDATE {$conftp}jugadores SET hijos=hijos+1 WHERE nombrejug='{$padre}'");
                      $ret = mysql_fetch_row($retval);
                      $abuelo = $ret[0];
                      if ($daroro)
                        db_query("UPDATE {$conftp}jugadores SET nietos=nietos+1,oro=oro+{$conforonuevonieto} WHERE nombrejug='{$abuelo}'");
                      else
                        db_query("UPDATE {$conftp}jugadores SET nietos=nietos+1 WHERE nombrejug='{$abuelo}'");
                    }
                    mysql_free_result($retval);
                  }
                  $nuevapass = $newpass;
                  $pwdhash = pwdhash($username,$nuevapass);
                  db_lock("{$conftp}jugadores WRITE");
                  db_query("INSERT INTO {$conftp}jugadores (nombrejug,password,email,emailreg,creado,ipcreado,iplogin,padre) VALUES ('{$username}','{$pwdhash}','{$email}','{$email}',{$time},'{$ip}','(no)','{$padre}')");
                  db_unlock();
                  send_mail($email,"Registro en {$confmailname}","Desde la IP {$ip} te has registrado en {$confmailname}. <br/>\n <br/>\nEstos son tus datos de acceso: <br/>\nUsuario:    {$username} <br/>\nPassword:   {$nuevapass} <br/>\nE-mail:     {$email} <br/>\n <br/>\nRecuerda que puedes jugar en {$confurl} <br/>\n <br/>\nBienvenid@ a la comunidad. <br/>\n <br/>\nUn saludo. <br/>\n");
                  $_SESSION['mensaje'] = 'Ya est�s registrado! Recuerda tu contrase�a, por favor.';
                  $salirnow = 1;
                }
	        else {
    	          $_SESSION['error'] = 'Como m�ximo est�n permitidos dos registros por ip y hora. Espera alrededor de una hora e intenta de nuevo.';
                  $_SESSION['lastuser'] = $username;
                  $_SESSION['lastemail'] = $email;
                  $_SESSION['lastpadre'] = $padre;
	        }
              }
            }
	  }
        }
      }
    }
    if ($salirnow)
      header('Location: estado.php');
    else {
      if (isset($_REQUEST['invita'])) {
        $uname = check_username($_REQUEST['invita']);
        header("Location: registro.php?invita={$uname}");
      }
      else
        header('Location: registro.php');
    }
    return 1;
  }
}

?>
