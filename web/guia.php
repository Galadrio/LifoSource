<?php
$publico = 1;
$hidelogin = 1;
include('core.php');
function iweb() {
  global $confadminmail,$confclanmaxmem,$conforonivelataca,$conftp,$imgroot;

  techo('<a name="arriba"></a>Estas son las preguntas m�s frecuentes sobre el juego y su funcionamiento:');
  gitem('encendido','<b>��Tengo que dejar mi ordenador encendido y conectado mientras juego?!</b>');
  gitem('reg','�C�mo me registro?');
  gitem('hora','Soy de un pa�s distinto a Espa�a y mi hora local no coincide, �qu� hago?');
  gitem('reglas','�En qu� consiste el juego?');
  gitem('guardar','�Tengo que guardar mi partida para seguir jugando?');
  gitem('trabajar','�En qu� consiste trabajar?');
  gitem('inventario','�En qu� consiste el inventario?');
  gitem('mercader','�En qu� consiste el mercader?');
  gitem('trueque','�En qu� consisten los trueques?');
  gitem('clanes','�En qu� consisten los clanes?');
  gitem('objetos','Objetos extra�os y artefactos. Objetos de un s�lo uso.');
  gitem('fabricar','�C�mo fabrico objetos?');
  gitem('comida','�Para qu� sirven la comida (alimentos) y la energ�a?');
  gitem('mapas','�Para qu� sirven los mapas?');
  gitem('online','�Qu� significan los carteles "online" en la p�gina de clan?');
  gitem('combatir','�En qu� consiste combatir? �Cu�ndo puedo combatir?');
  gitem('ataqtrab','�Puedo ser atacado mientras trabajo?');
  gitem('ganaroro','�C�mo puedo ganar oro?');
  gitem('ganarexp','�C�mo puedo ganar experiencia?');
  gitem('invitar','�C�mo invito a una persona a jugar? �Gano algo haci�ndolo? �Qu� son los links para reclutar?');
  gitem('quejas','�Puedo quejarme por el comportamiento de un jugador?');
  gitem('cambiarc','�C�mo cambio mi direcci�n de correo electr�nico o mi contrase�a?');
  gitem('condificil','Mi contrase�a es muy dificil de recordar, �qu� hago?');
  gitem('insignias','�Qu� y para qu� son las insignias?');
  gitem('mascotas','�Qu� y para qu� son las mascotas?');
  gitem('bug','He encontrado un fallo en el juego, �qu� hago?');
  gitem('contacto','�C�mo contacto con el administrador?');
  gitem('multicuentas','�Se permite usar multicuentas (�varias cuentas por persona?)?');
  gitem('ccuentas','�Se permite comerciar, intercambiar, o compartir o prestar cuentas (aunque sea para cuidarlas)?');
  gitem('prohibido','�Qu� est� prohibido?');
  
  echo '<br/><br/>';

  gshow('encendido','��Tengo que dejar mi ordenador encendido y conectado mientras juego?!','�NO!, puedes empezar a trabajar y desconectarte de internet o apagar el ordenador. El juego continuar� en marcha y cuando vuelvas a conectar habr�s trabajado el tiempo que haya pasado.');
  gshow('reg','�C�mo me registro?','Te registras introduciendo el nombre de usuario que quieras y tu direcci�n de correo (que debe ser v�lida) en <a href="registro.php">esta p�gina</a>. Sigue el resto de instrucciones que encuentres ah�.');
  gshow('hora','Soy de un pa�s distinto a Espa�a y mi hora local no coincide, �qu� hago?','Ve a la secci�n Mi cuenta y elige tu zona horaria. Todas las horas del juego se adaptar�n autom�ticamente.');
  gshow('reglas','�En qu� consiste el juego?','El juego consiste en trabajar, combatir, encontrar y comerciar con objetos, y comunicarte con otros jugadores.');
  gshow('guardar','�Tengo que guardar mi partida para seguir jugando?','No, la partida est� en continuo desarrollo, aunque t� no est�s conectado a internet otros jugadores pueden atacarte y jugar. No puedes anular acciones ya realizadas ni "volver a cargar".');
  $times = '<br/><br/>Actualmente es posible trabajar durante una serie de tiempos determinados:<br/>';
  db_lock("{$conftp}trabajos READ");
  $retval = db_query("SELECT nombre FROM {$conftp}trabajos WHERE premium=0 ORDER BY segundos");
  db_unlock();
  $numrows = mysql_num_rows($retval);
  for ($i = 0;$i < $numrows;$i++) {
    $ret = mysql_fetch_row($retval);
    $times .= "{$ret[0]}<br/>";
  }
  mysql_free_result($retval);
  gshow('trabajar','�En qu� consiste trabajar?',"Al trabajar, indicas una cantidad de tiempo a trabajar. Durante ese tiempo no puedes combatir, y puedes desconectar de internet o visitar otras p�ginas. Al pasar ese tiempo, est�s o no est�s conectado, se te otorgar� experiencia y oro, y es posible que encuentres objetos. Sabr�s que as� ha sido porque aparecer� un nuevo mensaje en mensajer�a, indic�ndote lo sucedido. Puedes dejar de trabajar en cualquier momento y ganar�s oro y experiencia proporcional al tiempo que has trabajado. {$times}");
  gshow('inventario','�En qu� consiste el inventario?','El inventario te muestra los objetos que tienes. Desde ah� puedes decidir qu� objeto de cada clase utilizar�s en tus combates, bas�ndose en su ataque y protecci�n. Tambi�n te permite escoger como insignia uno de tus objetos.');
  gshow('mercader','�En qu� consiste el mercader?','El mercader vende unos tipos determinados de objetos, y compra cualquier clase de objeto. Las compras y ventas se realizan de una en una (es decir, si tienes dos armas de una clase y eliges vender, s�lo vender�s una) y siempre son a cambio de oro. El mercader te ense�ar� los objetos que puedes pagar o usar. Hay una serie de objetos, ya sean artefactos u objetos con atributos m�gicos, que s�lo pueden ser encontrados, pero el mercader los vender� si alg�n otro jugador se los ha vendido.');
  gshow('trueque','�En qu� consisten los trueques?','Los trueques son intercambios de objetos con otros jugadores. Para realizar un trueque, inicia un trueque nuevo en la secci�n de trueques con el jugador que te interese. Luego a�ade los objetos que te interese cambiar y/o espera a que �l a�ada los objetos que le interese cambiar. Una vez est�is conformes, ambos deb�is aceptar el trueque y los objetos ser�n intercambiados entre vuestros inventarios. Para que el trueque no pueda aprovecharse para pasar objetos entre un mismo jugador se toman dos medidas: Se igualan los valores del total de objetos intercambiados entre los dos jugadores utilizando oro como relleno, y aparte el mercader cobra un 10% en total del valor de los objetos intercambiados por los jugadores.');
  gshow('clanes','�En qu� consisten los clanes?',"En cualquier momento puedes fundar un clan, los dem�s jugadores podr�n solicitar entrar al clan y t� podr�s concederles o denegarles la entrada. Entre miembros de un mismo clan disponeis de una opci�n en mensajer�a (especificando como destino una arroba (@)) para comunicaros entre vosotros, y no podreis atacaros los unos a los otros. Debe verse como un equipo de jugadores que se defender� si resultan atacados por el resto de jugadores. El l�mite de jugadores que forman parte de un clan es de {$confclanmaxmem}, y el lider o fundador del clan puede delegar el liderazgo en otro miembro del clan o expulsar a miembros del mismo.");
  gshow('objetos','Objetos extra�os y artefactos. Objetos de un s�lo uso.','Existen una serie de objetos extra�os y artefactos que no pueden ser adquiridos en el mercader si este no los pos�e, es decir, en principio �nicamente pueden ser encontrados. La posibilidad de encontrar alguno de estos objetos depende del objeto en s�. Estos objetos tienen caracter�sticas distintas a los objetos normales, por ejemplo ataque muy elevado, protecci�n muy elevada, o precio de venta muy elevado. Ten en cuenta que los objetos tienen niveles m�nimos, necesitas tener o superar ese nivel para poder usar ese objeto. Hay una serie de objetos de un s�lo uso como pociones y hechizos, al combatir mientras se est�n usando se consume una unidad de ese objeto y te proporciona los bonus que ofrece.');
  gshow('fabricar','�C�mo fabrico objetos?','Para fabricar objetos, primero debes aprender a fabricarlos, leyendo libros. Una vez sabes fabricar un objeto y dispones de los ingredientes y la energ�a necesarios, puedes fabricarlos desde la secci�n "Fabricar".');
  gshow('comida','�Para qu� sirven la comida (alimentos) y la energ�a?','La comida te da energ�a, y la energ�a sirve para llevar a cabo acciones especiales (fabricar objetos por ejemplo) e influye levemente en los combates. S�lo puedes comer una vez cada cierto tiempo.');
  gshow('mapas','�Para qu� sirven los mapas?','Los mapas te permiten explorar zonas del mundo. Seg�n qu� zonas explores y cu�ntas veces lo hagas podr�s encontrar objetos distintos. S�lo puedes explorar una vez cada cierto tiempo.');
  gshow('online','�Qu� significan los carteles "online" en la p�gina de clan?','Un cartel ONLINE brillante significa que el jugador indicado est� activo en la p�gina. Esto es, que hace menos de 5 minutos que se ha desplazado de una secci�n a otra. Un cartel ONLINE poco brillante significa que el jugador indicado ha estado activo hace 30 minutos o menos en la p�gina.');
  gshow('combatir','�En qu� consiste combatir? �Cu�ndo puedo combatir?',"Combatiendo te enfrentas contra otro jugador. Para combatir necesitas cumplir una serie de requisitos (no haber atacado a nadie hace poco tiempo, y poseer al menos {$conforonivelataca} monedas de oro por cada nivel que tengas). S�lo puedes atacar a los jugadores que tengan dos niveles de diferencia contigo como mucho, y al menos posean {$conforonivelataca} monedas de oro por nivel. Al atacar a alguien, esta persona quedar� protegida por alg�n tiempo. Ambos recib�s un informe del ataque en vuestra mensajer�a, donde veis entre otras cosas la experiencia ganada o perdida, el oro ganado o perdido, y los objetos que utilizaba el contrincante. Durante el combate no se pueden perder objetos, �nicamente oro y experiencia. Si est�s protegido y atacas a alguien, pierdes tu protecci�n. Puedes consultar cu�nto tiempo te queda sin atacar o cu�nto tiempo te queda protegido en el Estado. Aun si pierdes un combate, puede que aprendas de �l y ganes experiencia. No necesariamente si eres de nivel superior a un jugador y tienes mejores objetos ganar�s el combate, aunque es bastante probable que lo hagas. Tambi�n puedes ser atacado mientras est�s trabajando. En el caso de que un combate sea largo, se rendir� aquel que m�s puntos de resistencia ha perdido, no aquel que menos puntos de resistencia conserva.");
  gshow('ataqtrab','�Puedo ser atacado mientras trabajo?','S�, puedes ser atacado mientras trabajas, y tu personaje se defender� como siempre.');
  gshow('ganaroro','�C�mo puedo ganar oro?','- Trabajando.<br/>- Combatiendo<br/>- Invitando a una persona a jugar o haciendo que visiten uno de tus <a href="#invitar">links de reclutamiento</a> (m�ximo una vez por ip al d�a).<br/>- Cuando uno de tus subordinados invite a una persona a jugar o haga que visiten uno de sus links de reclutamiento.');
  gshow('ganarexp','�C�mo puedo ganar experiencia?','- Trabajando.<br/>- Combatiendo<br/>- Encontrando objetos.');
  gshow('invitar','�C�mo invito a una persona a jugar? �Gano algo haci�ndolo? �Qu� son los links para reclutar?','En la p�gina de Estado, abajo del todo, dispones de una serie de links trampa de reclutamiento. Puedes utilizarlos como firma de foro, firma de e-mails, como nick de tu cliente de mensajer�a instantanea favorito, o dir�ctamente d�rselos a gente. Al acceder alguien a ese link te proporcionar� una cantidad determinada de oro, y si se registra en el juego, te volver� a proporcionar oro, y adem�s ser� tu subordinado. Cuando una persona entra a un link de reclutar de uno de tus subordinados, t� tambi�n ganas oro, y si esa persona se acaba registrando en el juego, vuelves a ganar oro (sin t� haber hecho nada). S�lo es necesario que una persona entre a un link cualquiera de los disponibles, se facilitan varios para que los puedas usar en diferentes lugares sin levantar sospechas (de todas formas, es tu responsabilidad no hacer publicidad indiscriminada).');
  gshow('quejas','�Puedo quejarme por el comportamiento de un jugador?','�nica y exclus�vamente si el jugador te env�a mensajes insultantes, o en los casos de amenazas no relacionadas con el juego, debes utilizar el bot�n "Informar al administrador de este mensaje" que aparece en la mensajer�a para cada mensaje enviado por otro usuario.');
  gshow('cambiarc','�C�mo cambio mi direcci�n de correo electr�nico o mi contrase�a?','Desde la secci�n "Mi cuenta", estando identificado, y facilitando tu contrase�a actual y bien la direcci�n de correo electr�nico nueva, la contrase�a nueva, o ambas.');
  gshow('condificil','Mi contrase�a es muy dificil de recordar, �qu� hago?','Desde la secci�n "Mi cuenta" puedes cambiarla por una m�s f�cil, aunque se recomienda no utilizar una contrase�a que ya est�s usando en otro lugar o cuenta.');
  gshow('insignias','�Qu� y para qu� son las insignias?','Las insignias son una marca con la que firmar�s los mensajes que env�es a otros usuarios, tus combates, y tu nombre en la clasificaci�n. La puedes elegir en el inventario a partir de un objeto cualquiera que tengas, y la perder�s si vendes la �ltima unidad que tengas de ese objeto. Cualquier usuario puede escoger cualquier insignia de entre sus objetos, as� que un objeto raro con pocas posibilidades de aparecer ser� utilizado como insignia por muy pocos usuarios.');
  gshow('mascotas','�Qu� y para qu� son las mascotas?','Las mascotas son unas criaturas que te ayudar�n en los combates. Para obtener una mascota debes hacerte primero con ella (fabric�ndola si es viable, o encontr�ndola) y utilizarla en tu inventario. A partir de ese momento podr�s mantenerla a tu lado en los combates, donde la mascota ganar� experiencia. Cada vez que la mascota alcanza una experiencia l�mite puedes alimentarla utilizando su comida preferida para que suba de nivel. Puedes tener tantas mascotas como quieras, pero s�lo una de cada tipo.');
  gshow('bug','He encontrado un fallo en el juego, �qu� hago?',"S�lo se considerar� fallo aquello que sea incorrecto desde el punto de vista l�gico de un juego. Por ejemplo:<br/>- No es un fallo perder un combate contra un contrincante m�s d�bil, puede haber tenido suerte.<br/>- Ser�a un fallo que al vender un objeto determinado tu oro disminuya en lugar de aumentar.<br/>En caso de que aun creas haber encontrado un fallo, envia un email a {$confadminmail}.");
  gshow('contacto','�C�mo contacto con el administrador?',"Puedes contactar con el administrador enviando un e-mail a {$confadminmail}. No se aceptar�n peticiones de privilegios sobre el juego ni en el juego.");
  gshow('multicuentas','�Se permite usar multicuentas (�varias cuentas por persona?)?',"S�, no habr� problema, siempre y cuando el hacerlo no te de beneficios que no tendr�as si no las utilizaras. Pueden ser sancionadas con una reducci�n del n�mero de puntos, entre otras, las siguientes actuaciones: numerosos combates con multicuentas, combates con multicuentas desarmadas, etc. Puedes intuir cuando algo que est�s haciendo con multicuentas est� prohibido si el hacerlo te da ventaja.<br><br>Por su naturaleza limitada, s� est� permitido cualquier tipo de comercio entre multicuentas.");
  gshow('ccuentas','�Se permite comerciar, intercambiar, o compartir o prestar cuentas (aunque sea para cuidarlas)?','El comercio con cuentas, intercambio de cuentas, y la compartici�n o prestado (aunque sea para "cuidarlas") o regalado de cuentas NO EST�N SOPORTADOS. Todos los mensajes de este estilo que aparezcan en los foros ser�n causa de ban. Se castigar� a los ladrones de cuentas y no se devolver� ninguna cuenta robada a nadie, no se admitir� ninguna reclamaci�n.<br/><br/>No DEIS cuentas ni ACEPTEIS cuentas que os quieran dar, podr� provocar un ban.<br/><br/>Aclaraci�n final: Esto viene a significar que si hay alg�n problema con una cuenta, todas las cuentas que yo considere relacionadas (por parte de cualquiera de los jugadores que la usan) podr�n ser baneadas indefinidamente. Si prest�is cuentas sab�is a qu� ateneros.');
  gshow('prohibido','�Qu� est� prohibido?',"Los ataques al servidor, el robo de cuentas, los insultos y palabras malsonantes, comentarios racistas, sexistas, y dem�s, parafernalia pol�tica o militar, provocaciones, abuso de multicuentas, env�o masivo de mensajes al foro o a otros jugadores, la publicidad, provocaciones a otros jugadores, amenazas, y aprovechamiento de fallos del juego. Todas estas actividades podr�n ser sancionadas con ban (prohibici�n de entrada al juego) o reducciones de experiencia, eliminaci�n de objetos, y dem�s medidas, seg�n la gravedad de la actividad.");

  echo '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
}
function gitem($id,$item) {
  echo "- <a href=\"#{$id}\">{$item}</a><br/>";
}
function gshow($id,$item,$texto) {
  echo "<a name=\"{$id}\"></a><b>{$item}</b><br/><br/>{$texto}<br/><br/><a href=\"#arriba\">Volver arriba</a><br/><br/><br/>";

}
?>
