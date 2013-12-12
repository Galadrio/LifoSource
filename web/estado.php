<?php
include('core.php');
function iweb() {
  global $jug,$links,$time,$conftp,$_REQUEST,$us,$confregistro,$imgroot,$tag;

  db_lock("{$conftp}jugadores READ");
  $preret = db_query("SELECT email,emailnuevo,emailnuevocambio FROM {$conftp}jugadores WHERE nombrejug='{$jug}'");
  db_unlock();
  $ret = mysql_fetch_row($preret);
  if ($ret[1] != '') {
    if ($ret[2] < $time)
      echo '<p><font color="red"><b><blink>AVISO IMPORTANTE:</blink> Actualmente se est� procesando <font color="green">Y PUEDES CONFIRMAR</font> un cambio de direcci�n de correo electr�nico en tu cuenta. Si no lo has solicitado t�, puedes cancelarlo desde la secci�n <a href="micuenta.php">Mi Cuenta</a>.</b></font></p>';
    else 
      echo '<p><font color="red"><b><blink>AVISO IMPORTANTE:</blink> Actualmente se est� procesando un cambio de direcci�n de correo electr�nico en tu cuenta. Si no lo has solicitado t�, puedes cancelarlo desde la secci�n <a href="micuenta.php">Mi Cuenta</a>. Si lo has solicitado t�, este aviso cambiar� cuando puedas confirmar el cambio.</b></font></p>';
  }
  else if (ereg('(hotmail\.com$)|(msn\.com$)',$ret[0]))
    echo '<p><font color="red"><b><blink>AVISO IMPORTANTE:</blink> Actualmente est�s utilizando una direcci�n de correo electr�nico de hotmail o similar. No se puede recuperar una contrase�a utilizando una cuenta de hotmail (hotmail filtra los mails). Si no cambias tu cuenta de correo por una v�lida que no sea de hotmail desde la secci�n <a href="micuenta.php">Mi Cuenta</a> y olvidas o pierdes tu contrase�a, no podr�s recuperar nunca esta cuenta! Puedes conseguir una cuenta de correo gratuita en <a href="http://www.gmail.com" rel="external">GMail</a>.</b></font></p>';

  if (isset($_REQUEST['versub'])) {
    if (isset($_REQUEST['p']))
      $p = $_REQUEST['p'];
    else
      $p = 1; 
    if (!is_numeric($p))
      $p = 1;
    if ($p < 1)
      $p = 1;

    $ini = ($p-1)*15;
    $fin = $p*15;
    db_lock("{$conftp}jugadores READ");
    $retvol = db_query("SELECT {$conftp}jugadores.nombrejug,nivel,puntos,insignia,combates,vencedor,clan,ultimaact FROM {$conftp}jugadores WHERE padre='{$jug}' ORDER by puntos DESC LIMIT {$ini},15");
    $retvol2 = db_query("SELECT COUNT(*) FROM {$conftp}jugadores WHERE padre='{$jug}'");
    db_unlock();
    $ret2 = mysql_fetch_row($retvol2);
    mysql_free_result($retvol2);
    $numsubs = mysql_num_rows($retvol);
    if ($numsubs) {
      echo '<b>Estos son tus subordinados:</b><br/>';
      echo '<table class="highscores"><tr><th>Estado</th><th>Puntos</th><th>Insig.</th><th>Nombre</th><th>Nivel</th><th>Clan</th><th>Combates ganados/totales</th></tr>';
      for ($tx = 0;$tx < $numsubs;$tx++) {
        $r = mysql_fetch_row($retvol);
        $clan = $r[6];
        if ($time-$r[7]<600)
          $est = '<img src="'.$imgroot.'img/on1.png" alt="ONLINE"/>';
        else if ($time-$r[7]<1800)
          $est = '<img src="'.$imgroot.'img/on2.png" alt="POCA ACTIVIDAD"/>';
        else
          $est = '-';

        if ($r[6] == '(ninguno)')
          $clan = '&nbsp;';
        echo "<tr><td>{$est}</td><td>{$r[2]}</td><td><img src=\"{$imgroot}img/{$r[3]}.gif\" alt=\"insignia\"/></td><td>{$r[0]}</td><td>{$r[1]}</td><td>{$clan}</td><td>{$r[5]}/{$r[4]}</td>";
        echo "</tr>";
      }
      echo '</table><br/><br/><br/>';
    }
    mysql_free_result($retvol);
    $npags = $ret2[0]/15;
    $npag = floor($npags);
    if ($npag != $npags)
      $npag++;
    if ($npag > 1) {
      echo "P�gina: ";
      $ini = $p-3;
      $fin = $p+3;
      if ($ini < 1)
        $ini = 1;
      if ($fin > $npag)
        $fin = $npag;
      if ($ini > 1)
        echo '... ';
      for ($i = $ini;$i <= $fin;$i++) {
        if ($i != $p)
          echo "<a href=\"estado.php?versub=1&amp;p={$i}\">{$i}</a> ";
        else
          echo "<b>{$i}</b> ";
      }
      if ($fin < $npag)
        echo '... ';
      echo '<br/><br/>';
    }
    echo '<a href="estado.php">Volver</a><br/>';
    return;
  }
  techo('Desde esta p�gina puedes ver la informaci�n de tu personaje. Todas las estad�sticas, el estado actual, y una serie de <b>links para reclutar</b> nuevos trabajadores. Puedes cambiar tu insignia desde el inventario, cuando tengas alg�n objeto.');

  db_lock("{$conftp}tiene READ,{$conftp}objetos READ");
  $retval2 = db_query("SELECT SUM(ataq),SUM(prot) FROM {$conftp}tiene,{$conftp}objetos WHERE usado=1 AND {$conftp}tiene.nombreobj={$conftp}objetos.nombreobj AND nombrejug='{$jug}'");
  db_unlock();

  $ret2 = mysql_fetch_row($retval2);
  mysql_free_result($retval2);

  $dia = ahora_dia($us['creado']);
  $hora = ahora_hora($us['creado']);
  $expnext = expsignivel($us['nivel'],$us['puntosnivel'])-$us['puntos'];

  if ($us['fintrabajo']) {
    $dia_t = ahora_dia($us['fintrabajo']);
    $hora_t = ahora_hora($us['fintrabajo']);
    $trabajandohasta = "el {$dia_t} a las {$hora_t}";
  }
  else
    $trabajandohasta = '(no est�s trabajando)';

  if ($us['protegidohasta']>=$time) {
    $dia_p = ahora_dia($us['protegidohasta']);
    $hora_p = ahora_hora($us['protegidohasta']);
    $protegidohasta = "el {$dia_p} a las {$hora_p}";
  }
  else
    $protegidohasta = '(no est�s protegido)';

  if ($us['nocomerhasta']>=$time) {
    $dia_c = ahora_dia($us['nocomerhasta']);
    $hora_c = ahora_hora($us['nocomerhasta']);
    $nocomerhasta = "el {$dia_c} a las {$hora_c}";
  }
  else
    $nocomerhasta = '(puedes comer)';

  if ($us['noexplorarhasta']>=$time) {
    $dia_c = ahora_dia($us['noexplorarhasta']);
    $hora_c = ahora_hora($us['noexplorarhasta']);
    $noexplorarhasta = "el {$dia_c} a las {$hora_c}";
  }
  else
    $noexplorarhasta = '(puedes explorar)';


  if ($us['noatacarhasta']>=$time) {
    $dia_nt = ahora_dia($us['noatacarhasta']);
    $hora_nt = ahora_hora($us['noatacarhasta']);
    $noatacarhasta = "el {$dia_nt} a las {$hora_nt}";
  }
  else
    $noatacarhasta = '(puedes atacar si cumples los dem�s requisitos)';
  $insignia = "<img src=\"{$imgroot}img/{$us['insignia']}.gif\" alt=\"insignia\"/>";

  $tiempo = ahora_tiempo($us['trabajado']);
  $ataqb = 10+floor($us['nivel']/3);
  $protb = 10+floor($us['nivel']/10);
  $ataqo = $ret2[0];
  $proto = $ret2[1];
  if (!$ataqo)
    $ataqo = 0;
  if (!$proto)
    $proto = 0;
  $ataq = $ataqo+$ataqb;
  $prot = $proto+$protb;
  $prem = '';
  if (($us['moderador']) || ($us['enchufado'])) {
    $prem = "<tr><td><b>Premium ilimitado.</b></td></tr>";
  }
  else if ($us['tiempopremium']) {
    $phdia = ahora_dia($us['premiumhasta']);
    $phhora = ahora_hora($us['premiumhasta']);
    $prem .= "<tr><td><b>Premium hasta:</b></td><td>el {$phdia} a las {$phhora}</td></tr>";
    $espremium = 1;
  }
  else {
    $prem .= '<tr><td><b>No eres premium.</b></td><td>';
    $espremium = 0;
  }
  if ($us['tiempopremium'] > 86400*7) {
    $prem .= "<tr><td></td><td><form action=\"fabricar.php\" method=\"post\">";
    $prem .= "<input type=\"hidden\" name=\"tag\" value=\"{$tag}\"/>";
    $prem .= "<input type=\"submit\" onclick=\"return c()\" name=\"joyapremiume\" value=\"Convertir una semana premium en una joya premium\"/>";
    $prem .= "</form></td></tr>";
  }

  $enermax = 100+$us['nivel']*10;
  if ($us['espremium'])
    $enermax = $enermax*2;



  db_lock("{$conftp}tienemascotas READ,{$conftp}mascotas READ");
  $rg1 = db_query("SELECT {$conftp}tienemascotas.nombremascota,img,nivel,experiencia,alimento,ataquebase,defensabase,ataquenivel,defensanivel,expbase,expmult,expgana,maxnivel,usado FROM {$conftp}tienemascotas,{$conftp}mascotas WHERE {$conftp}tienemascotas.nombremascota={$conftp}mascotas.nombremascota AND nombrejug='{$jug}' AND usado=1");
  db_unlock();
  $rows = mysql_num_rows($rg1);
  if ($rows == 0) {
    $mascataq = 0;
    $mascprot = 0;
  }
  else {
    $rrr = mysql_fetch_row($rg1);
    $rg2 = db_query("SELECT img FROM objetos WHERE nombreobj='{$rrr[4]}'");
    $rg3 = db_query("SELECT cantidad FROM tiene WHERE nombreobj='{$rrr[4]}' AND nombrejug='{$jug}'");
    $rrs = mysql_fetch_row($rg2);
    if (mysql_num_rows($rg3)) {
      $rrt = mysql_fetch_row($rg3);
    }
    else
      $rrt[0] = 0;
    mysql_free_result($rg2);
    mysql_free_result($rg3);

    $mascataq = $rrr[5]+($rrr[7]*($rrr[2]-1));
    $mascprot = $rrr[6]+($rrr[8]*($rrr[2]-1));
  }
  mysql_free_result($rg1);

  $ataq = $ataq+$mascataq;
  $prot = $prot+$mascprot;

  echo "<table class=\"estado\">
<tr><td><b>Nombre del jugador:</b></td><td>{$jug}</td></tr>
<tr><td><b>Insignia:</b></td><td>{$insignia}</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td><b>Jugando desde:</b></td><td>el {$dia} a las {$hora}</td></tr>
<tr><td><b>Tiempo trabajado:</b></td><td>{$tiempo}</td></tr>
{$prem}
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td><b>Combates totales:</b></td><td>{$us['combates']}</td></tr>
<tr><td><b>Combates ganados:</b></td><td>{$us['vencedor']}</td></tr>
<tr><td><b>Combates perdidos:</b></td><td>{$us['vencido']}</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td><b>Trabajando hasta:</b></td><td>{$trabajandohasta}</td></tr>
<tr><td><b>Protegido hasta:</b></td><td>{$protegidohasta}</td></tr>
<tr><td><b>No puedes atacar hasta:</b></td><td>{$noatacarhasta}</td></tr>
<tr><td><b>No puedes comer hasta:</b></td><td>{$nocomerhasta}</td></tr>
<tr><td><b>No puedes explorar hasta:</b></td><td>{$noexplorarhasta}</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td><b>Nivel:</b></td><td>{$us['nivel']}</td></tr>
<tr><td><b>Experiencia:</b></td><td>{$us['puntos']}</td></tr>
<tr><td><b>Experiencia para el siguiente nivel:</b></td><td>{$expnext}</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td><b>Oro:</b></td><td>{$us['oro']}</td></tr>
<tr><td><b>Energ�a:</b></td><td>{$us['energia']} (M�xima: {$enermax})</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td><b>Subordinados:</b></td><td>{$us['hijos']}</td></tr>
<tr><td><b>Subordinados de subordinados:</b></td><td>{$us['nietos']}</td></tr>
<tr><td><b>Jefe:</b></td><td>{$us['padre']}</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td><b>Accesos a mis links de reclutar:</b></td><td>{$us['visitashijos']}</td></tr>
<tr><td><b>Accesos a links de reclutar<br/>de subordinados:</b></td><td>{$us['visitasnietos']}</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td><b>Ataque (nivel):</b></td><td>{$ataqb}</td></tr>
<tr><td><b>Protecci�n (nivel):</b></td><td>{$protb}</td></tr>
<tr><td><b>Ataque (objetos en uso):</b></td><td>{$ataqo}</td></tr>
<tr><td><b>Protecci�n (objetos en uso):</b></td><td>{$proto}</td></tr>
<tr><td><b>Ataque (mascota):</b></td><td>{$mascataq}</td></tr>
<tr><td><b>Protecci�n (mascota):</b></td><td>{$mascprot}</td></tr>
<tr><td><b>Ataque (total):</b></td><td>{$ataq}</td></tr>
<tr><td><b>Protecci�n (total):</b></td><td>{$prot}</td></tr>

</table><br/><br/><br/>
";
  
  echo '<a href="estado.php?versub=1">Ver lista de subordinados</a><br/><br/><br/>';
  if ($confregistro) {
  techo('Tienes una lista de links distintos para reclutar. Puedes utilizarlos para firmar en foros, en correos electr�nicos, o para ponerlos en el nick de tu programa de mensajer�a instantanea favorito (es tu responsabilidad no hacer spam). Cuando un jugador entra a uno de esos links, le robas algunas monedas. Aparte, si se decide a registrarse en el juego, se convierte en tu subordinado y ganas unas cuantas monedas m�s.<br/><br/>Atento, �esto es muy importante! �Cuando una persona entra en un link de alguno de tus subordinados, sin que t� tengas que hacer nada, ganas unas pocas monedas, y si esa persona se acaba registrando, se convierte en subordinado de tu subordinado y ganas otras tantas monedas de oro!<br/><br/>Recuerda: Cualquiera de los links para reclutar hace ex�ctamente lo mismo, el nombre var�a para que sea m�s f�cil hacer que una persona cualquiera lo visite.');
  echo '<b>Estos son tus links para reclutar:</b><br/><br/>';
  $jugcif = '';
  for ($i = 0;$i < strlen($jug);$i++) {
    $jugcif .= '%'.strtoupper(dechex(ord($jug[$i])));
  }
  $lin = sizeof($links);
  for ($i = 0;$i < $lin;$i++)
    echo "{$links[$i]}{$jug}<br/>";
  $lin = sizeof($links);
  for ($i = 0;$i < $lin;$i++)
    echo "{$links[$i]}{$jugcif}<br/>";
  echo '<br/><br/>';
  }
  else {
    echo 'No se permiten m�s registros en esta partida, por lo que los links de registrar est�n cerrados.';
  }
}

?>
