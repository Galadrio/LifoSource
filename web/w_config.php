<?php
  // direcci�n raiz de la partida, debe ser accesible desde fuera.
  $root = 'http://localhost/';

  // host del servidor MySQL
  $confdbhost = 'localhost';
  // puerto del servidor MySQL
  $confdbport = '3306';
  // usuario para el servidor MySQL
  $confdbuser = 'lifouser';
  // contrase�a para el usuario
  $confdbpass = 'password';
  // nombre de la base de datos
  $confdbname = 'lifosource';

  // cadena de texto con cualquier valor para mejorar el cifrado de las contrase�as
  // NO CAMBIAR despues de hacer el emerge
  $confpwdsalt = 'rellename con alguna palabra';

  // contrase�a por defecto para la cuenta Admin
  $confadminpass = 'cambiame';
  // direcci�n de correo del administrador
  $confmail = 'adminmail@localhost';
  
  // nombre del juego
  $conftitle = 'Partida de LifoSource';
  // nombre del administrador
  $confadminname = 'NOMBRE APELLIDOS REALES';
  
  // aviso legal
  $confavisolegal = 'Para dar cumplimiento con lo establecido en la Ley 34/2006, de 11 de Enero, de Servicios de la Sociedad de la Informaci�n y de Comercio Electr�nico, a continuaci�n se proporciona la informaci�n correspondiente a este sitio web: <br/>
<br/>
&quot;Partida WWWWWWWWWWWWW de LifoSource&quot;, accesible a trav�s de la direcci�n de internet WWWWWWWWWWWWWWWWWWWWWW es una p�gina web personal propiedad de:<br/>
<br/>
NOMBRE APELLIDOS.<br/>
Residente en la localidad de LOCALIDAD, provincia de PROVINCIA, PAIS.<br/>
N.I.F.: 00000000-X.<br/>
E-mail: EMAIL.<br/>
<br/>
Cualquier comunicaci�n puede realizarse de forma directa y efectiva a trav�s de la citada direcci�n de correo electr�nico.<br/>
<br/>
Este sitio esta sometido a las leyes europeas. La accesibilidad del lugar a menores de edad impone no hacer accesibles contenidos de car�cter sexualmente expl�cito, violento, intolerante, difamante, obsceno, de mal gusto o ilegales, as� como tampoco de aquellos que hagan apolog�a de violencia, racismo, terrorismo o xenofobia. Se borrar�n inmediatamente y se expulsar� del sitio al autor a la m�xima brevedad posible. As�mismo, el propietario del sitio web no se hace responsable de los contenidos publicados por terceros en el mismo, y se reserva el derecho a proporcionar a las autoridades toda la informaci�n posible sobre los usuarios que incumplan este c�digo de conducta.<br/>';




  //Zona horaria del servidor
  $zonaact = 7;

  // Cabeceras y pies de p�gina
  $confcontentstart = '';
  $conftitlebar = '<img src="/img/logo.png" alt="Logo" class="logo"/>';
  $confcontentend = '';
  $confcontentbeffooter = '';


  // Est� abierta la partida?
  $confabierto = 1;
  // Est� abierto el registro de jugadores?
  $confregistro = 1;

  // Modo debug?
  $confdebug = 0;

  // Experiencia necesaria para encontrar un objeto
  $confobj = 1200;
  // Experiencia necesaria para subir un nivel
  $confnivel = 1200;

  // Oro por cada nuevo hijo
  $conforonuevohijo = 30;
  // Oro por cada nuevo nieto
  $conforonuevonieto = 10;
  // Oro por cada visita a hijo
  $conforovisitahijo = 3;
  // Oro por cada visita a nieto
  $conforovisitanieto = 1;

  // Dias que se mantienen los mensajes leidos
  $confdiasmensajes = 30;
  // Longitud maxima de mensajes
  $confmaxmsg = 4096;

  // Intervalo en segundos entre ataques
  $confnoatacarhasta = 7200;
  // Intervalo en segundos entre ataques, jugadores premium
  $confnoatacarhastapremium = 3600;
  // Tiempo de protecci�n en segundos tras recibir ataque
  $confprotegidohasta = 14400;
  // Oro m�nimo para atacar
  $conforonivelataca = 50;
  // Diferencia m�xima de niveles para atacar
  $confdifnivel = 5;
  // M�xima experiencia a ganar en un combate
  $confganacombexpmax = 600;
  // M�nima experiencia a perder en un combate
  $confganacombexpmin = -600;

  // Factor de valor al vender al mercader
  $confvalorventa = 0.50;
  // Cada cuanto encuentra el mercader objetos
  $confmercencuentra = 1000;
  // Cu�ntas veces seguidas encuentra el mercader objetos, como m�ximo
  $confmercvecesmax = 10;
  // Cu�ntos instancias de un objeto puede guardar el mercader
  $confmaxoferta = 100;

  // Cu�ntos jugadores mostrar alrededor en la clasificaci�n
  $confalredhigh = 3;
  // Cu�ntos clanes mostrar alrededor en la clasificaci�n
  $confalredchigh = 3;

  // N�mero m�ximo de miembros de un clan
  $confclanmaxmem = 10;

  // Intervalo en segundos entre comer  
  $confnocomerhasta = 7200;
  // Intervalo en segundos entre comer, jugadores premium
  $confnocomerhastapremium = 3600;

  // Intervalo en segundos entre explorar 
  $confnoexplorarhasta = 4500;
  // Intervalo en segundos entre explorarm jugadores premium
  $confnoexplorarhastapremium = 1800;







  // no es necesario modificar estos valores
  $imgroot = $root;
  $confurl = $root;
  $conftitlebar = '<img src="'.$root.'/img/logo.png" alt="Logo" class="logo"/>';
  $confadminmail = $confmail;
  $confmailn = $conftitle;
  $confmailname = $conftitle;
  $confsmtpuser = '';
  $confsmtppass = '';
  $confsmtp = '127.0.0.1';
  $conftp = '';

?>
