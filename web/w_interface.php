<?php

function iface() {
  global $conftitle,$confcontentstart,$confcontentend,$jug,$ident,$publico,$form,$path,$tags,$hidelogin,$confcontentbeffooter,$time,$confabierto,$conftitlebar,$conftp,$us,$_SESSION,$ultimaact,$vmen,$vfor,$imgroot,$ip;
  if ((isset($form)) && ($confabierto))
    if (procesaform())
      return;
  echo "<!DOCTYPE html PUBLIC \"-/"."/W3C/"."/DTD XHTML 1.0 Strict/"."/EN\" \"http:/"."/www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html>
<head>
<meta name=\"Title\" content=\"{$conftitle}\"/>
<meta name=\"Subject\" content=\"Juego de rol/estrategia medieval gratis online\"/>
<meta name=\"Keywords\" content=\"juego, rol, estrategia, medieval, gratis, online, multijugador, {$conftitle}\"/>
<meta name=\"Language\" content=\"Spanish\"/>
<title>{$conftitle}</title>
<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\"/>
<style type=\"text/css\" title=\"currentStyle\" media=\"screen\">@import \"{$imgroot}estilo.css\";</style>
<link rel=\"shortcut icon\" href=\"{$imgroot}favicon.ico\" type=\"image/x-icon\"/>
<script type=\"text/javascript\" src=\"{$imgroot}ext.js\"></script>
</head>";
  if (!$confabierto) {
    echo "<body><div>Cerrado tempor�lmente por mantenimiento.</div></body></html>";
    return;
  }
  $esadmin = '';
  $espremium = '';
  if ($ident) {
    if (!isset($nosetact)) {
      if ($time-$ultimaact > 300) {
        db_lock("{$conftp}jugadores WRITE");
        $retval = db_query("UPDATE {$conftp}jugadores SET ultimaact={$time} WHERE nombrejug='{$jug}'");
        db_unlock();
        $ultimaact = $time;
        $_SESSION['ultimaact'] = $time;
      }
    }
    $esadmin = $us['admin'];
    $espremium = $us['espremium'];
  }
  echo "<body><div>{$conftitlebar}</div>";
  echo "{$confcontentstart}";
  db_lock("{$conftp}textos READ");
  $retva = db_query("SELECT texto FROM {$conftp}textos WHERE nombre='noticiaspag'");
  db_unlock();
  $notis = mysql_fetch_row($retva);
  mysql_free_result($retva);
  echo $notis[0];


  echo '<div id="menu">';
  if ($ident) {
    echo "Jugador: <b>{$jug}</b> ";
    if ($espremium)
      echo '(premium) ';
    echo "- ";
  }
  else
    echo 'No est�s identificado - ';
  $ahora_d = ahora_dia($time);
  $ahora_h = ahora_hora($time);
  echo " Fecha: {$ahora_d} {$ahora_h}";
  echo '<br/><br/>';
  imenuitem('Estado','estado.php');
  echo ' - ';
  imenuitem('Gu�a del juego','guia.php');
  echo ' - ';
  $vtrue = 0;
  if ($ident) {
    db_lock("{$conftp}trueques READ");
    $retval = db_query("SELECT COUNT(*) FROM {$conftp}trueques WHERE inicia='{$jug}' OR recibe='{$jug}'");
    db_unlock();
    $ret = mysql_fetch_row($retval);
    mysql_free_result($retval);
    if ($ret[0]) {
      $vtrue = 1;
    }
    if ($vmen == 0) {
      db_lock("{$conftp}mensajes READ");
      $retval = db_query("SELECT COUNT(*) FROM {$conftp}mensajes WHERE nombrejug='{$jug}' AND visto=0 AND reportado=0");
      db_unlock();
      $ret = mysql_fetch_row($retval);
      mysql_free_result($retval);
      if ($ret[0]) {
        $vmen = 1;
        $_SESSION['vmen'] = 1;
      }
     }
    if ($vfor == 0) { 
      db_lock("{$conftp}forohebras READ,{$conftp}jugadores READ"); 
      $retval = db_query("SELECT COUNT(*) FROM {$conftp}forohebras WHERE hora>={$us['ultimoforo']}"); 
      db_unlock(); 
      $ret = mysql_fetch_row($retval); 
      mysql_free_result($retval);
      if ($ret[0]) { 
        $vfor = 1; 
        $_SESSION['vfor'] = 1;  
      }  
    }
  }
  if ($vmen)
    $noleidos = '*';
  else
    $noleidos = '';
  imenuitemi("Mensajer�a {$noleidos}",'mensajeria.php');
  echo ' - ';
  if ($vfor)
    $nolforo = '*'; 
  else 
  $nolforo = '';  
  imenuitemi("Foro {$nolforo}",'foro.php');
  echo ' - ';
  imenuitemi('Clasificaci�n','clasificacion.php');
  echo ' - ';
  imenuitemi('Mi cuenta','micuenta.php');
  echo ' - ';
  imenuitemi('Salir',"estado.php?salir={$tags}");
  echo '<br/>';
  imenuitemi('Trabajar','trabajar.php');
  echo ' - ';
  imenuitemi('Inventario','inventario.php');
  echo ' - ';
  imenuitemi('Fabricar','fabricar.php');
  echo ' - ';
  imenuitemi('Mercader','mercader.php');
  echo ' - ';
  if ($vtrue)
    $trueques = '*';
  else
    $trueques = '';
  imenuitemi("Trueques {$trueques}",'trueques.php');
  echo ' - ';
  imenuitemi('Mascotas','mascotas.php');
  echo ' - ';
  imenuitemi('Clanes','clanes.php');
  echo ' - ';
  imenuitemi('Combate','combate.php');
  if (($us['admin']) || ($us['moderador']))
    $esmodera = 1;
  else
    $esmodera = '';

  if ($esmodera) {
    echo ' - ';
    imenuitemi('<b>(MOD)</b>','mod.php');
  }
  if ($esadmin) {
    echo ' - ';
    imenuitemi('<b>(ADMIN)</b>','admin.php');
  }
  echo '</div>';

  echo '<div id="contenido">';

  if ((!$publico) && (!$ident) && (!isset($_SESSION['error'])) && (!isset($_SESSION['mensaje'])) && (!isset($_SESSION['error'])))
    $_SESSION['error'] = 'Parece ser que tu sesi�n ha caducado. Ten en cuenta que necesitas tener activadas las cookies en tu navegador para poder jugar.';
  $nomensaje = 0;
  $noerror = 0;
  if (isset($_SESSION['error'])) {
    $nomensaje = 1;
    unset($_SESSION['mensaje']);
  }
  else 
    $noerror = 1;

  if (!$nomensaje) {
    echo "<div id=\"mensaje\">";
    if (isset($_SESSION['mensaje'])) {
      echo $_SESSION['mensaje'];
      unset($_SESSION['mensaje']);
    }
    else
      echo '&nbsp;';
    echo "</div><br/>";
  }
  if (!$noerror) {
    echo "<div id=\"error\">";
    if (isset($_SESSION['error'])) {
      echo $_SESSION['error'];
      unset($_SESSION['error']);
    }
    else
      echo '&nbsp;';
    echo "</div><br/>";
  }

  if ((!$ident) && (!isset($hidelogin))) {
    techo('�S� bienvenido! Si has llegado hasta aqu� por casualidad te comento: Este es un juego de rol y estrategia online. Artefactos m�gicos, combates, poder... echa un vistazo a la <a href="guia.php">gu�a del juego</a> o <a href="registro.php">reg�strate (gratis)</a> y prueba...<br/><br/>Si ya tienes una cuenta, tienes que teclear tu usuario y contrase�a en el formulario que hay aqu� debajo para volver a continuar por donde lo dejaste.');
    echo "<form method=\"post\" action=\"estado.php\"><table id=\"login\"><tr><td><div><b><span class=\"linkreg\"><a href=\"registro.php\">-&gt; �Quiero una cuenta! &lt;-</a></span></b><br/><br/>Identif�cate:<br/><hr/><br/>Usuario:<br/><input type=\"text\" name=\"username\" size=\"30\" maxlength=\"30\"/><br/><br/>Contrase�a:<br/><input type=\"password\" name=\"password\" size=\"30\" maxlength=\"30\"/><br/><br/><input type=\"submit\" name=\"login\" value=\"Entrar\"/><br/><br/>";
    //desactivado... echo "<a href=\"recupera.php\">Olvid� mi contrase�a</a>";
    echo "<br/></div></td></tr></table></form>";
    echo '<div><br/><br/></div>';
  }
  if (isset($publico) || ($ident))
    iweb();
  echo $confcontentbeffooter;
  //NO MODIFICAR
  echo '<div id="rights">Copyright 2006-2014. Todos los derechos reservados. <a href="/avisolegal.php" rel="external">Aviso legal</a>. Este proyecto es libre, puedes obtener el <a href="https://github.com/lquesada" rel="external">c�digo fuente</a><br/><br/><a href="http:/'.'/validator.w3.org/check?uri=referer" rel="external"><img src="'.$imgroot.'img/xhtml.png" alt="Valid XHTML 1.0 Strict" height="31" width="88"/></a> <a href="http:/'.'/jigsaw.w3.org/css-validator/check/referer" rel="external"><img height="31" width="88" src="'.$imgroot.'img/css.png" alt="Valid CSS"/></a></div>';
  echo "{$confcontentend}";
  echo "</div></body></html>";
}
function imenuitem($texto,$url) {
  global $path;
  if ($path != $url)
    echo "<a href=\"{$url}\">{$texto}</a>";
  else {
    $relcode = pwdgen();
    echo "<a href=\"{$url}?reload={$relcode}\"><b>{$texto}</b></a>";
  }
}
function imenuitemi($texto,$url) {
  global $ident;
  if ($ident)
    imenuitem($texto,$url);
  else
    echo $texto;
}
?>
