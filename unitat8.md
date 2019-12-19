# 1.- Autentificació d'usuaris i control d'accés #

Moltes vegades és important verificar la identitat dels dos extrems d'una comunicació. al cas d'una comunicació web, hi ha mètodes per identificar tant a servidor en el qual s'allotja
el lloc web, com a l'usuari de el navegador que es troba en l'altre extrem.

Els llocs web que necessiten emprar identificació de servidor, com les botigues o els bancs, utilitzen el protocol HTTPS. Aquest protocol requereix d'un certificat vàlid, signat per una autoritat fiable, que és verificat pel navegador quan s'accedeix a la pàgina web. A més, HTTPS utilitza mètodes de xifrat per crear un canal segur entre el navegador i el servidor, de tal manera que no es pugui interceptar la informació que es transmet pel mateix.

Per identificar els usuaris que visiten un lloc web, es poden utilitzar diferents mètodes com el DNI digital o certificats digitals d'usuari (document digital que conté informació sobre l'usuari com el nom o l'adreça. Aquesta informació està signada per una altra entitat, anomenada entitat certificadora, que ha de ser de confiança i garanteix que la informació que conté és certa), però el més estès és sol·licitar a l'usuari certa informació que només ell coneix: la combinació d'un nom d'usuari i una contrasenya.

En les unitats anteriors vas aprendre a utilitzar aplicacions web per gestionar informació emmagatzemada en bases de dades. En la majoria dels casos és important implantar en aquest tipus de aplicacions web, les que accedeixen a bases de dades, algun mecanisme de control d'accés que obligui a l'usuari a identificar-se. Un cop identificat, es pot limitar l'ús que pot fer de la informació.

Així, pot haver llocs web en els quals els usuaris autenticats poden utilitzar només una part de la informació (com els bancs, que permeten als seus clients accedir únicament a la informació relativa als seus comptes). Altres llocs web necessiten separar en grups als usuaris autentificats, de tal manera que la informació a la qual accedeix un usuari depèn de el grup en què aquest es trobi. Per exemple, una aplicació de gestió d'una empresa pot tenir un grup d'usuaris a què permet visualitzar la informació, i un altre grup d'usuaris que, a més de visualitzar la informació, també la poden modificar.



> Has distingir l'autenticació dels usuaris i el control d'accés, de la utilització de mecanismes per assegurar les comunicacions entre l'usuari de el navegador i el servidor web. Encara que tots dos aspectes solen anar units, són independents.

> En els exemples d'aquesta unitat, la informació d'autenticació (nom i contrasenya de els usuaris) s'envia en text pla des del navegador fins al servidor web. aquesta pràctica és altament insegura i mai s'ha d'usar sense un protocol com HTTPS que permeti xifrar les comunicacions amb el lloc web. No obstant això, la configuració de servidors web que permetin fer servir el protocol HTTPS per xifrar la informació que reben i transmeten no forma part dels continguts d'aquest mòdul. Per aquest motiu, durant aquesta unitat utilitzarem únicament el protocol no segur HTTP.

## 1.1.- Mecanismes d'autenticació (I) ##

El protocol HTTP ofereix un mètode senzill per autenticar els usuaris. El procés és el següent:

* El servidor web ha de proveir algun mètode per definir els usuaris que s'utilitzaran i com es poden autentificar. A més, s'hauran de definir els recursos als quals es restringeix l'accés i quina llista de control d'accés (ACL - llista de permisos sobre un objecte (fitxer, directori, etc.), que indica quins usuaris poden utilitzar l'objecte i les accions concretes que poden realitzar amb el mateix (lectura, escriptura, esborrat, etc.)) s'aplica a cada un.
* Quan un usuari no autenticat intenta accedir a un recurs restringit, el servidor web respon amb un error de "Accés no autoritzat" (codi 401).
* El navegador rep l'error i obre una finestra per sol·licitar a l'usuari que es autentifiqui mitjançant el seu nom i contrasenya.
* La informació d'autenticació de l'usuari s'envia a servidor, que la verifica i decideix si permet o no l'accés a el recurs sol·licitat. Aquesta informació es manté en el navegador per utilitzar-se en posteriors peticions a aquest servidor.

Al servidor web Apache, el que has estat utilitzant en anteriors unitats, hi ha una utilitat en línia d'ordres, `htpasswd`, que permet emmagatzemar en un fitxer una llista d'usuaris i els seus respectives contrasenyes. La informació relativa a les contrasenyes s'emmagatzema xifrada; tot i així, és convenient crear aquest fitxer en un lloc no accessible pels usuaris de servidor web.

http://httpd.apache.org/docs/2.0/es/howto/auth.html

Per exemple, per crear el fitxer d'usuari i afegir-li el usuari "dwes", pots fer:

```
sudo htpasswd -c users dwes
```

i introduir la contrasenya corresponent a aquest usuari.

L'opció `-c` indica que s'ha de crear el fitxer, per la qual cosa només hauràs de fer-la servir quan introdueixis el primer usuari i contrasenya. Fixa't que en l'exemple anterior, el
fitxer es crea a la ruta /etc/apache2/users, que en principi no és accessible via web.

Per indicar-li a l'servidor Apache quins recursos tenen accés restringit, una opció és crear un fitxer `.htaccess` en el directori en què es trobin, amb les següents directives:

```c
AuthName "Contingut restringit"
AuthType Basic
AuthUserFile /etc/apache2/users
requereix valid-user
```

El significat de cadascuna de les directives anteriors és el següent:

| Directiva | significat |
| --- | --- |
| AuthName | Nom de domini que es farà servir en l'autenticació. Si el client s'autentica correctament, aquesta mateixa informació d'autenticació s'utilitzarà automàticament en la resta de les pàgines de el mateix domini. |
| AuthType | Mètode d'autenticació que es farà servir. A més de l'mètode Basic, Apache també permet utilitzar el mètode Digest. |
| AuthUserFile | Camí de l'arxiu de credencials que has creat amb htpasswd. |
|Require | Permet indicar que només puguin accedir alguns usuaris o grups d'usuaris concrets. Si indiquem "valid-user", podran accedir tots els usuaris que es s'autentifiquin correctament. |

A més hauràs de assegurar-te que en la configuració d'Apache s'utilitza la directiva AllowOverride perquè s'apliqui correctament la configuració que figura en els fitxers `.htaccess`.

http://httpd.apache.org/docs/2.0/es/mod/core.html#allowoverride

La sentència sudo htpasswd -c users admin afegeix un nou usuari amb nom "admin" al fitxer "users" que hem creat anteriorment.
veritable
fals

A l'incloure l'opció -c el que fem és crear un nou fitxer, amb la qual cosa eliminem el contingut anterior del mateix.

### 1.1.1.- Mecanismes d'autenticació (II). ###

Des PHP pots accedir a la informació d'autenticació HTTP que ha introduït l'usuari utilitzant l'array superglobal `$_SERVER`.

| valor | contingut |
| --- | --- |
| `$_SERVER['PHP_AUTH_USER']` | Nom d'usuari que s'ha introduït. |
| `$_SERVER['PHP_AUTH_PW']` | Clau introduïda. |
| `$_SERVER['AUTH_TYPE']`  | Mètode HTTP usat per autentificar. Pot ser Basic o Digest. | 

És a dir, que si crees una pàgina web que mostri els valors d'aquestes variables, i prepares el servidor web per utilitzar autenticació HTTP, quan accedeixis a aquesta pàgina amb l'usuari "dwes" obtindràs alguna cosa com el següent:

```html
<!-- DOCTYPE html PUBLIC "- // W3C // DTD HTML 4.01 Transitional // EN" "http://www.w3.org/TR/html4/loose.dtd ">
<!-- Desenvolupament Web a Entorn Servidor -->
<!-- Tema 4: Desenvolupament d'aplicacions web amb PHP -->
<!-- Exemple: Autenticació HTTP -->
<html>
<head>
    <meta http-equiv = "content-type" content = "text / html; charset = UTF-8">
    <title> Exemple Tema 4: Autenticació HTTP </ title>
    <link href = "dwes.css" rel = "stylesheet" type = "text / css">
</head>
<body>
    <?php
    echo "Nom d'usuari:". $_ SERVER [ 'PHP_AUTH_USER']. "<br />";
    echo "Contrasenya:". $_ SERVER [ 'PHP_AUTH_PW']. "<br />";
    echo "Mètode d'autenticació:". $_ SERVER [ 'AUTH_TYPE']. "<br />";
    ?>
</body>
</html>
```

Si no introdueixes un usuari/contrasenya vàlids, el navegador et mostrarà l'error 401.

A més, en PHP pots utilitzar la funció header per forçar que el servidor enviï un error de "Accés no autoritzat" (codi 401). D'aquesta manera no cal utilitzar fitxers .htaccess per indicar-li a Apache quins recursos estan restringits. En el seu lloc, pots afegir les següents línies en les teves pàgines PHP:

```php
<?php
if (!isset($_ SERVER['PHP_AUTH_USER'])) {
    header ('WWW-Authenticate: Basic Realm = "Contingut restringit"');
    header ( 'HTTP / 1.0 401 Unauthorized');
    echo "Usuari no reconegut!";
    exit;
}
?>
```

La funció `header` envia capçaleres HTTP (bloc de dades que forma part de l'protocol HTTP i s'envia abans de la informació pròpia que es transmet. Permet especificar codis d'estat, accions requerides a servidor, o el tipus d'informació que es transmet), però s'ha d'utilitzar abans que es mostre res per pantalla. En cas contrari, obtindràs un error.

http://es.php.net/manual/es/function.header.php

Amb el codi anterior, la pàgina envia un error 401, el que força a el navegador a sol·licitar les credencials d'accés (nom d'usuari i contrasenya). Si s'introdueixen, s'executa la resta de la pàgina i es mostra el seu contingut. En aquest cas, caldria afegir algun codi per comprovar que el nom d'usuari i contrasenya són vàlids, tal com veurem a continuació. Si es prem el botó "Cancel", es mostra el missatge d'error que s'indica. Modifica la pàgina anterior utilitzant la funció header perquè sol·liciti les credencials a l'usuari.

Hauràs de crear una pàgina similar a l'anterior, i afegir el codi per forçar l'error 401 abans de qualsevol altre.

```html
<!-- DOCTYPE html PUBLIC "- // W3C // DTD HTML 4.01 Transitional // EN" " http://www.w3.org/TR/html4/loose.dtd ">
<!-- Desenvolupament Web a Entorn Servidor -->
<!-- Tema 4: Desenvolupament d'aplicacions web amb PHP -->
<!-- Exemple: Funció header per autenticació HTTP -->
<?php
    if (!isset($_ SERVER [ 'PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic Realm = "Contingut restringit"');
        header('HTTP / 1.0 401 Unauthorized');
        echo "Usuari no reconegut!";
        exit;
    }
?>
<html>
<head>
<meta http-equiv = "content-type" content = "text / html; charset = UTF-8">
<title> Exercici: Funció header per autenticació HTTP </ title>
<link href = "dwes.css" rel = "stylesheet" type = "text / css">
</head>
<body>
```php
<?php
    echo "Nom d'usuari:". $_ SERVER [ 'PHP_AUTH_USER']. "<br />";
    echo "Contrasenya:". $_ SERVER [ 'PHP_AUTH_PW']. "<br />";
?>
```
</body>
</html>
```

##1.2.- Incorporació de mètodes d'autenticació a una aplicació web.##
Si utilitzes la funció header per forçar el navegador a sol·licitar credencials HTTP, l'usuari introduirà un nom i una contrasenya. Però el servidor no ha de verificar aquesta informació; hauràs de ser
tu qui proveeixi un mètode per comprovar que les credencials que ha introduït l'usuari són correctes.

El mètode més simple és incloure en el codi PHP de la teva pàgina les sentències necessàries per comparar les dades introduïdes amb altres dades fixos. Per exemple, per a permetre l'accés a un
usuari "dwes" amb contrasenya "abc123.", pots fer:
```php
<? Php
if ($_SERVER [ 'PHP_AUTH_USER']! = 'dwes' || $_SERVER [ 'PHP_AUTH_PW']! = 'abc123.') {username@host:/path/to/repository
header ( 'WWW-Authenticate: Basic Realm = "Contingut restringit"');
header ( 'HTTP / 1.0 401 Unauthorized');
echo "Usuari no reconegut!";
exit;
}
?>
```
Recorda que el codi PHP no s'envia a el navegador, de manera que la informació d'autenticació que introdueixis en el codi no serà visible per l'usuari. No obstant això, això farà el teu codi menys
portable. Si necessites modificar el nom d'usuari o la contrasenya, hauràs de fer modificacions en el codi. A més, no podràs permetre a l'usuari introduir la seva pròpia contrasenya.
Una solució millor és utilitzar un emmagatzematge extern per als noms d'usuari i les seves
contrasenyes. 

Per això podries emprar un fitxer de text, o millor encara, una base de dades. la informació d'autenticació podrà estar aïllada en la seva pròpia base de dades, o compartir espai
d'emmagatzematge amb les dades que utilitzi la teva aplicació web. 

Si vols emmagatzemar la informació dels usuaris a la base de dades "dwes", has de crear una nova taula en la seva estructura. Per a això, revisa i executa aquestes sentències SQL.
- Seleccionem la base de dades 

```sql
USE dwes;

- Creem la taula

CREATE TABLE usuaris (
usuari VARCHAR (20) NOT NULL PRIMARY KEY,
contrasenya VARCHAR (32) NOT NULL
) ENGINE = InnoDB;
- Creem l'usuari dwes

INSERT INTO usuaris (usuari, contrasenya) VALUES
( 'Dwes', 'e8dc8ccd5e5f9e3a54f07350ce8a2d3d');

```
Tot i que es podrien emmagatzemar les contrasenyes en text pla, és millor fer-ho en format encriptat. En l'exemple anterior, per a l'usuari "dwes" s'emmagatzema el hash MD5 (mètode per generar un resum d'un text o un document, de tal manera que a partir de l'resum obtingut no és possible recuperar el text original ni estar un altre text a partir de el qual s'obtingui el mateix resum. Es diu hash a el resum obtingut a l'aplicar una funció hash. Una de les funcions hash més esteses és MD5, que genera 128 bits com a resum (normalment es representa mitjançant una cadena de text de 28 caràcters o mitjançant 32 dígits hexadecimals)) corresponent a la contrasenya "abc123.". En PHP pots utilitzar la funció md5 per calcular el hash MD5 d'una cadena de text.

http://es.php.net/manual/es/function.md5.php

Utilitza l'extensió MySQLi per modificar l'exercici anterior, de tal manera que les credencials de l'usuari es comproven amb la informació de la nova taula "usuaris" creada a la base de dades "dwes". Si no hi ha l'usuari, o la contrasenya és incorrecta, torna a demanar les credencials a l'usuari.

Revisa la solució proposada. Fixa't que s'ha d'usar la funció md5 per comprovar la contrasenya. Si introdueixes un usuari o contrasenya incorrectes, el comportament depèn de el navegador que utilitzis; alguns et demanaran les credencials de forma indefinida, i altres un nombre limitat de vegades.

```php
<?php
// Si l'usuari encara no s'ha entrat, demanem les credencials
if (!isset ($_SERVER [ 'PHP_AUTH_USER'])) {
    header ('WWW-Authenticate: Basic realm = "Contingut restringit"');
    header ("HTTP/1.0 401 Unauthorized");
    exit;
}
// Si ja ha enviat les credencials, les vam comprovar amb la base de dades
else {
    // Connectem a la base de dades
    @$dwes = new mysqli("localhost", "dwes", "abc123.", "Dwes");
    $error = $dwes-> connect_errno;
    // Si es va establir la connexió
    if ($error == null) {
        // Executem la consulta per comprovar si existeix
        // aquesta combinació d'usuari i contrasenya
        $sql ​​= "SELECT usuari FROM usuaris
            WHERE usuari = '$_ SERVER [PHP_AUTH_USER]' AND
            contrasenya = md5 ( '$_ SERVER [PHP_AUTH_PW]') ";
        $resultat = $dwes-> query ($sql);
        // Si no existeix, es tornen a demanar les credencials
        if ($resultat-> num_rows == 0) {
            header ( 'WWW-Authenticate: Basic realm = "Contingut restringit"');
            header ( "HTTP / 1.0 401 Unauthorized");
            exit;
        }
        $resultat->close();
        $dwes->close();
    }
}
?>
<! DOCTYPE html PUBLIC "- // W3C // DTD HTML 4.01 Transitional // EN" "
http://www.w3.org/TR/html4/loose.dtd ">
<! - Desenvolupament Web a Entorn Servidor ->
<! - Tema 4: Desenvolupament d'aplicacions web amb PHP ->
<! - Exemple: Utilització de MySQL per autenticació HTTP ->
<Html>
<Head>
<Meta http-equiv = "content-type" content = "text / html; charset = UTF-8">
<Title> Exemple Tema 4: Utilització de MySQL per autenticació HTTP </ title>
<Link href = "dwes.css" rel = "stylesheet" type = "text / css">
</ Head>
<Body>
<? Php
echo "Nom d'usuari:". $_ SERVER [ 'PHP_AUTH_USER']. "<br />";
echo "Hash de la contrasenya:" .md5 ($_ SERVER [ 'PHP_AUTH_PW']). "<br />";
?>
</ Body>
</ Html>
```
Les dues possibilitats que hem vist per sol·licitar a l'usuari que s'autentiqui
via HTTP són la creació de el fitxer .htaccess, i la utilització de la
funció header de PHP. Quina d'aquestes dues formes serà preferible si vols que els
privilegis d'accés a la teva aplicació variïn en funció del dia de la setmana (per exemple,
uns usuaris que puguin accedir de dilluns a divendres i altres diferents el cap de setmana)?
El fitxer .htaccess
La funció header
Si utilitzes la funció header per enviar les capçaleres HTTP, has d'escriure tu el codi que decideix si l'usuari s'ha entrat
correctament i se li permet accedir o no. Per tant, pots incloure les regles addicionals que vulguis, per exemple, donar-li o no accés
en funció del dia de la setmana.

# 2.- Galetes. #
## cas pràctic ##

Un cop resolt el tema de l'autenticació, el següent objectiu de Carlos és aprendre a gestionar
les galetes. Joan li ha explicat què són i com funcionen, i segurament les hagin d'utilitzar en
l'aplicació que estan desenvolupant.
Sap que molts dels llocs web que visita les utilitzen, perquè ha provat a desactivar-les en la seva
navegador, i li han començat a aparèixer missatges demanant-li que les torni a activar. s'ha
proposat esbrinar per què les fan servir, i com les podran gestionar des de PHP.
Una galeta és un fitxer de text que un lloc web guarda a l'entorn de l'usuari de navegador.
El seu ús més típic és l'emmagatzematge de les preferències de l'usuari (per exemple, l'idioma en
que s'han de mostrar les pàgines), perquè no hagi de tornar a indicar-les la propera vegada que
visiteu el lloc.
Si utilitzes Firefox com a navegador, pots instal·lar l'extensió Web Developer per obtenir
informació sobre les pàgines web que visites. Entre les seves característiques et permet consultar i editar
les galetes emmagatzemades en el mateix.
https://addons.mozilla.org/es/firefox/addon/web-developer/
En PHP, per emmagatzemar una galeta al navegador de l'usuari, pots utilitzar la funció setcookie.
L'únic paràmetre obligatori que has de fer servir és el nom de la galeta, però admet diversos
paràmetres més opcionals.
http://es.php.net/manual/es/function.setcookie.php
Per exemple, si vols emmagatzemar en una galeta el nom d'usuari que es va transmetre a les
credencials HTTP (és només un exemple, no és en absolut aconsellable emmagatzemar informació relativa
a la seguretat en les galetes), pots fer:
setcookie ( "nom_usuari", $_SERVER [ 'PHP_AUTH_USER'], time () + 3600);
Els dos primers paràmetres són el nom de la galeta i el seu valor. El tercer és la data de
caducitat de la mateixa (una hora des del moment en què s'executi). En cas de no figurar aquest
paràmetre, la galeta s'eliminarà quan es tanqui el navegador. Tingues en compte que també es
poden aplicar restriccions a les pàgines de el lloc que poden accedir a una galeta en funció de la
ruta.
Les galetes es transmeten entre el navegador i el servidor web de la mateixa manera que les
credencials que acabes de veure; utilitzant les capçaleres de el protocol HTTP. Per això, les
sentències setcookie s'han d'enviar abans que el navegador mostri cap informació a
pantalla.
El procés de recuperació de la informació que emmagatzema una galeta és molt simple. quan
accedeixes a un lloc web, el navegador li envia de forma automàtica tot el contingut de les
galetes que emmagatzemi relatives a aquest lloc en concret. Des PHP pots accedir a aquesta
informació per mitjà de l'array $_COOKIE.
Sempre que utilitzis galetes en una aplicació web, heu de tenir en compte que en última instància la seva
disponibilitat està controlada pel client. Per exemple, alguns usuaris deshabiliten
les galetes al navegador perquè pensen que la informació que emmagatzemen pot suposar un
potencial problema de seguretat. O la informació que emmagatzemen pot arribar a perdre perquè el
usuari decideix formatar l'equip o simplement eliminar-les de sistema.

Si un cop emmagatzemada una galeta al navegador vols eliminar abans que expiri, pots
utilitzar la mateixa funció setcookie però indicant una data de caducitat anterior a l'actual.
Sobre el mateix exercici anterior, s'emmagatzema en una galeta l'últim instant en què el
usuari va visitar la pàgina. Si és la seva primera visita, mostra un missatge de benvinguda. en cas
contrari, mostra la data i hora de la seva anterior visita.
Revisa la solució proposada. Hauràs utilitzar la funció setcookie per guardar l'instant
de la seva anterior visita i mostrar el seu contingut utilitzant l'array $_COOKIE.

```php
<? Php
// Si l'usuari encara no s'ha entrat, demanem les credencials
if (
! Isset ($_ SERVER [ 'PHP_AUTH_USER'])) {
header ( 'WWW-Authenticate: Basic realm = "Contingut restringit"');
header ( "HTTP / 1.0 401 Unauthorized");
exit;
}
// Si ja ha enviat les credencials, les vam comprovar amb la base de dades
else {
// Connectem a la base de dades
@ $Dwes = new mysqli ( "localhost", "dwes", "abc123.", "Dwes");
$Error = $dwes-> connect_errno;
// Si es va establir la connexió
if ($error == null) {
date_default_timezone_set ( 'Europe / Madrid');
// Executem la consulta per comprovar si existeix
// aquesta combinació d'usuari i contrasenya
$Sql ​​= "SELECT usuari FROM usuaris
WHERE usuari = '${_ SERVER [' PHP_AUTH_USER ']}' AND
contrasenya = md5 ( '${_ SERVER [' PHP_AUTH_PW ']}') ";
$Resultat = $dwes-> query ($sql);
// Si no existeix, es tornen a demanar les credencials
if ($resultat-> num_rows == 0) {
header ( 'WWW-Authenticate: Basic realm = "Contingut restringit"');
header ( "HTTP / 1.0 401 Unauthorized");
exit;
} Else {
if (isset ($_ COOKIE [ 'ultimo_login'])) {
$Ultimo_login = $_COOKIE [ 'ultimo_login'];
}
setcookie ( "ultimo_login", time (), time () + 3600);
}
$Resultat-> close ();
$Dwes-> close ();
}
}
?>
<! DOCTYPE html PUBLIC "- // W3C // DTD HTML 4.01 Transitional // EN" "
http://www.w3.org/TR/html4/loose.dtd ">
<! - Desenvolupament Web a Entorn Servidor ->
<! - Tema 4: Desenvolupament d'aplicacions web amb PHP ->
<! - Exemple: Galetes al autenticació HTTP ->
<Html>
<Head>
<Meta http-equiv = "content-type" content = "text / html; charset = UTF-8">
<Title> Exemple Tema 4: Galetes al autenticació HTTP </ title>
<Link href = "dwes.css" rel = "stylesheet" type = "text / css">
</ Head>
<Body>
<? Php
if ($error == null) {
echo "Nom d'usuari:". $_ SERVER [ 'PHP_AUTH_USER']. "<br />";
echo "Hash de la contrasenya:" .md5 ($_ SERVER [ 'PHP_AUTH_PW']). "<br />";
if (isset ($ultimo_login))
echo "Darrer login:". date ( "d / m / i \ a \ l \ a \ s H: i", $ultimo_login);
else
echo "Benvingut. Aquesta és la seva primera visita.";
}
else
echo "S'ha produït l'error $error. <br />";
?>
</ Body>
</ Html>
```

Quina és la durada per defecte d'una galeta si no s'indica la data de caducitat,
com en la següent crida a la funció setcookie?

setcookie ( "idioma", "espanyol");

Fins que es tanqui el navegador d'l'usuari.

1 hora.

Quan s'acaba la sessió de l'usuari, a l'tancar el navegador, s'esborra la galeta.

#3.- Maneig de sessions.#
cas pràctic
Carles ha après a utilitzar les galetes, i quines són les seves limitacions. Està clar que els seran de
utilitat, però també que no cobreixen les necessitats d'una aplicació web. A l'viatjar en les
capçaleres HTTP, la informació que poden mantenir està molt limitada.
Encara que sap que encara li queda molt per aprendre, Juan li ha comentat que amb els
coneixements que acaba d'adquirir ja té una bona base per donar les primeres passes
programant en PHP. Diu que només li falta aprendre com funcionen les sessions per començar a
desenvolupar aplicacions web completes pel seu compte.
Animat per aquests comentaris, Carlos decideix fer un pas més. Vegem com funcionen les
sessions en PHP.
Com acabes de veure, una forma per guardar informació particular de cada usuari és
utilitzar cookies. No obstant això, hi ha diversos problemes associats a les galetes, com el nombre de
elles que admet el navegador, o la seva grandària màxima. Per solucionar aquests inconvenients, hi ha les
sessions. El terme sessió fa referència a el conjunt d'informació relativa a un usuari concret.
Aquesta informació pot ser tan simple com el nom de l'usuari mateix, o més complexa, com els
articles que ha dipositat a la cistella de compra d'una botiga en línia.
Cada usuari diferent d'un lloc web té la seva pròpia informació de sessió. Per distingir una
sessió d'una altra s'usen els identificadors de sessió (SID). Un SID és un atribut que s'assigna a cada
un dels visitants d'un lloc web i l'identifica. D'aquesta manera, si el servidor web coneix el SID de
un usuari, pot relacionar-lo amb tota la informació que posseeix sobre ell, que es manté en la
sessió de l'usuari. Aquesta informació s'emmagatzema en el servidor web, generalment en fitxers
tot i que també es poden utilitzar altres mecanismes d'emmagatzematge com a bases de dades.
Com ja hauràs suposat, la qüestió ara és: ¿i on s'emmagatzema aquest SID, l'identificador de la
sessió, que és únic per a cada usuari? Doncs hi ha dues maneres de mantenir el SID entre les
pàgines d'un lloc web que visita l'usuari:
 Utilitzant galetes, tal com ja vas veure.
 Propagant el SID en un paràmetre de la URL. El SID s'afegeix com una part més de la URL, de
la forma:
http://www.misitioweb.com/tienda/listado.php&amp;PHPSESSID=34534fg4ffg34ty
En l'exemple anterior, el SID és el valor de l'paràmetre PHPSESSID.
Cap de les dues maneres és perfecta. Ja saps els problemes que té la utilització de cookies.
Malgrat això, és el millor mètode i el més utilitzat. Propagar el SID com a part de la URL comporta
majors desavantatges, com la impossibilitat de mantenir el SID entre diferents sessions, o el fet
que compartir la URL amb una altra persona implica compartir també l'identificador de sessió.
La bona notícia, és que el procés de maneig de sessions en PHP està automatitzat en gran
mida. Quan un usuari visita un lloc web, no cal programar un procediment per veure
si hi ha un SID previ i carregar les dades associades amb el mateix. Tampoc has d'utilitzar la
funció setcookie si vols emmagatzemar els SID en galetes, o anar passant el SID entre les
pàgines web del teu lloc si et decideixes per propagar. Tot això PHP ho fa automàticament.
A la informació que s'emmagatzema en la sessió d'un usuari també se li coneix
com a galetes de la banda de servidor (server side galetes). Has de tenir en compte que encara
aquesta informació no viatja entre el client i el servidor, sí que ho fa el SID, bé com a part de
l'URL o en una capçalera HTTP si es guarda en una galeta. En tots dos casos, això planteja
un possible problema de seguretat. El SID pot ser aconseguit per una altra persona, i a partir
- 10 -Desenvolupament d'aplicacions web amb PHP
tema 4
de la mateixa obtenir la informació de la sessió de l'usuari. La manera més segura d'utilitzar
sessions és emmagatzemant els SID en galetes i utilitzar HTTPS per a xifrar la informació
que es transmet entre el servidor web i el client.
3.1.- Configuració.
Per defecte, PHP inclou suport de sessions incorporat. Abans, però, d'utilitzar sessions en
el teu lloc web, has de configurar correctament PHP utilitzant els següents directives en el fitxer
php.ini segons correspongui:
Directiva
significat
Indica si s'han d'usar cookies (1) o propagació a la URL (0) per
session.use_cookies
emmagatzemar el SID.
S'ha d'activar (1) quan fas servir cookies per emmagatzemar els SID, i
a més no vols que es reconeguin els SID que es puguin passar com
session.use_only_cookies
part de la URL (aquest mètode es pot usar per usurpar l'identificador
d'un altre usuari).
S'utilitza per indicar a PHP com ha de emmagatzemar les dades de la sessió
de l'usuari. Hi ha quatre opcions: en fitxers (files), en memòria
session.save_handler
(Mm), en una base de dades SQLite (sqlite) o utilitzant per a això
funcions que ha de definir el programador (user). El valor per defecte
(Files) funcionarà sense problemes en la majoria dels casos.
Determina el nom de la galeta que s'utilitzarà per guardar el SID. La seva
session.name
valor per defecte és PHPSESSID.
El seu valor per defecte és 0, i en aquest cas hauràs de fer servir la
funció session_start per gestionar l'inici de les sessions. si fas servir
session.auto_start
sessions al lloc web, pot ser bona idea canviar el seu valor a 1 per
que PHP activi de forma automàtica el maneig de sessions.
Si utilitzes l'URL per propagar el SID, aquest es perdrà quan tanqui el
navegador. No obstant això, si utilitzes galetes, el SID es mantindrà
mentre no es destrueixi la galeta. En el seu valor per defecte (0),
session.cookie_lifetime
les galetes es destrueixen quan es tanca el navegador. Si vols que es
mantingui el SID durant més temps, has d'indicar en aquesta directiva aquest
temps en segons.
Indica el temps en segons que s'ha de mantenir activa la sessió,
encara que no hi hagi cap activitat per part de l'usuari. El seu valor per
session.gc_maxlifetime
defecte és 1440. És a dir, passats 24 minuts des de l'última activitat
per part de l'usuari, es tanca la sessió automàticament.
La funció phpinfo, de la qual ja vam parlar amb
anterioritat, t'ofereix informació sobre la
configuració actual de les directives de sessió.
En la documentació de PHP tens informació
sobre aquestes i altres directives que permeten
configurar el maneig de sessions.

http://es.php.net/manual/es/session.configuration.php

Si la informació de l'usuari que vols emmagatzemar inclou contingut privat com
una contrasenya, ¿què utilitzaries, galetes o la sessió de l'usuari?
La sessió de l'usuari.
Galetes.
La sessió de l'usuari s'emmagatzema i es processa a la web, de manera que no cal transmetre-fins a l'ordinador de l'usuari,
el que augmenta la seva seguretat.

##3.2.- Inici i fi d'una sessió.##

L'inici d'una sessió pot tenir lloc de dues maneres. Si has activado la directiva
session.auto_start en la configuración de PHP, la sesión comenzará automáticamente en cuanto un
usuario se conecte a tu sitio web. En caso de que ese usuario ya haya abierto una sesión con
anterioridad, y esta no se haya eliminado, en lugar de abrir una nueva sesión se reanudará la
anterior. Para ello se utilizará el SID anterior, que estará almacenado en una cookie (recuerda que si
usas propagación del SID, no podrás restaurar sesiones anteriores; el SID figura en la URL y se pierde
cuando cierras el navegador).
Si por el contrario, decides no utilizar el inicio automático de sesiones, deberás ejecutar la
función session_start para indicar a PHP que inicie una nueva sesión o reanude la anterior. Aunque
anteriormente esta función devolvía siempre true , a partir de la versión 5.3.0 de PHP su
comportamiento es más coherente: devuelve false en caso de no poder iniciar o restaurar la sesión.
Como el inicio de sesión requiere utilizar cookies, y éstas se transmiten en los
encabezados HTTP, debes tener en cuenta que para poder iniciar una sesión utilizando
session_start , tendrás que hacer las llamadas a esta función antes de que la
página web muestre información en el navegador.
Además, todas las páginas que necesiten utilizar la información almacenada en la sesión,
deberán ejecutar la función session_start .
Mientras la sesión permanece abierta, puedes utilizar la variable superglobal $_SESSION para añadir
información a la sesión del usuario, o para acceder a la información almacenada en la sesión. Por
ejemplo, para contar el número de veces que el usuario visita la página, puedes hacer:
```php
<?php
// Iniciamos la sesión o recuperamos la anterior sesión existente
session_start();
// Comprobamos si la variable ya existe
if (isset($_SESSION['visitas']))
$_SESSION['visitas']++;
else
$_SESSION['visitas'] = 0;
?>
Si en lugar del número de visitas, quisieras almacenar el instante en que se produce cada una, la
variable de sesión "visitas" deberá ser un array y por tanto tendrás que cambiar el código anterior
por:
<?php
// Iniciamos la sesión o recuperamos la anterior sesión existente
session_start();
// En cada visita añadimos un valor al array "visitas"
$_SESSION['visitas'][] = mktime();
?>
```

Aunque como ya viste, puedes configurar PHP para que elimine de forma automática los datos de
una sesión pasado cierto tiempo, en ocasiones puede ser necesario cerrarla de forma manual en un
momento determinado. Por ejemplo, si utilizas sesiones para recordar la información de
autentificación, deberás darle al usuario del sitio web la posibilidad de cerrar la sesión cuando lo crea
conveniente.

En PHP tienes dos funciones para eliminar la información almacenada en la sesión:
* session_unset . Elimina las variables almacenadas en la sesión actual, pero no elimina la
información de la sesión del dispositivo de almacenamiento usado.
* session_destroy . Elimina completamente la información de la sesión del dispositivo de
almacenamiento.
Crea una página similar a la anterior, almacenando en la sesión de usuario los instantes de
todas sus últimas visitas. Si es su primera visita, muestra un mensaje de bienvenida. En
caso contrario, muestra la fecha y hora de todas sus visitas anteriores. Añade un botón a la
página que permita borrar el registro de visitas.
Utiliza también una variable de sesión para comprobar si el usuario se ha autentificado
correctamente. De esta forma no hará falta comprobar las credenciales con la base de
datos constantemente.
Revisa la solución propuesta. Acuérdate de que debes hacer una llamada a la
función session_start() antes de utilizar la variable superglobal $_SESSION para acceder a la
información de la sesión.

```php
<?php
// Si el usuario aún no se ha autentificado, pedimos las credenciales
if (!isset($_SERVER['PHP_AUTH_USER'])) {
header('WWW-Authenticate: Basic realm="Contenido restringido"');
header("HTTP/1.0 401 Unauthorized");
exit;
}
// Vamos a guardar el usuario en una variable de sesión
// si no existe, aún no se ha autentificado
session_start();
if (!isset($_SESSION['usuario'])) {
// Conectamos a la base de datos
@ $dwes = new mysqli("localhost", "dwes", "abc123.", "dwes");
$error = $dwes->connect_errno;
// Si se estableció la conexión
if ($error == null) {
// Ejecutamos la consulta para comprobar si existe
// esa combinación de usuario y contraseña
$sql = "SELECT usuario FROM usuarios
WHERE usuario='${_SERVER['PHP_AUTH_USER']}' AND
contrasena=md5('${_SERVER['PHP_AUTH_PW']}')";
$resultado = $dwes->query($sql);
// Si no existe, se vuelven a pedir las credenciales
if($resultado->num_rows == 0) {
header('WWW-Authenticate: Basic realm="Contenido restringido"');
header("HTTP/1.0 401 Unauthorized");
exit;
}else
$_SESSION['usuario'] = $_SERVER['PHP_AUTH_USER'];
$resultado->close();
$dwes->close();
}
}
// Si ya está autentificado
else {
// Comprobamos si se ha enviado el formulario de limpiar el registro
if (isset($_POST['limpiar']))
unset($_SESSION['visita']);
else
$_SESSION['visita'][] = time();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "
http://www.w3.org/TR/html4/loose.dtd">
<!-- Desarrollo Web en Entorno Servidor -->
<!-- Tema 4 : Desarrollo de aplicaciones web con PHP -->
<!-- Ejemplo: Cookies en autentificación HTTP -->
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Ejemplo Tema 4: Cookies en autentificación HTTP</title><link
rel="stylesheet" type="text/css">
href="dwes.css"
</head>
<body>
<?php
if ($error == null) {
echo "Nombre de usuario: ".$_SERVER['PHP_AUTH_USER']."<br />";
echo "Hash de la contraseña: ".md5($_SERVER['PHP_AUTH_PW'])."<br />";
if (count($_SESSION['visita']) == 0)
echo "Bienvenido. Esta es su primera visita.";
else {
date_default_timezone_set('Europe/Madrid');
foreach($_SESSION['visita'] as $v)
echo date("d/m/y \a \l\a\s H:i", $v) . "<br />";
?>
<form id='vaciar' action='<?php echo $_SERVER['PHP_SELF'];?>' method='post'>
<input type='submit' name='limpiar' value='Limpiar registro'/>
</form>
<?php
}
}else
echo "Se ha producido el error $error.<br />";
?>
</body>
</html>
```

Si usamos el inicio de sesión automático, la sesión de un usuario se inicia en cuanto se autentifica correctamente en el servidor web.
Verdadero
Falso

Con el inicio de sesión automático, la sesión del usuario se inicia en cuanto se conecta al sitio web, esté o no autentificado. No tiene nada
que ver una cosa con otra. Un usuario no autentificado puede tener una sesión de la misma forma que otro que sí se haya autentificado
correctamente

## 3.3.- Gestión de la información de la sesión (I). ##
En este punto vas a ver paso a paso un ejemplo de utilización de sesiones para almacenar la
información del usuario. Utilizarás la base de datos " dwes ", creada anteriormente, para crear un
prototipo de una tienda web dedicada a la venta de productos de informática.
Las páginas de que constará tu tienda online son las siguientes:

* Login ( login.php ). Su función es autentificar al usuario de la aplicación web. Todos los usuarios de la aplicación deberán autentificarse utilizando esta página antes de poder acceder al resto de páginas.

DIAGRAMA CESTA COMPRA

* Listado de productos ( productos.php ). Presenta un listado de los productos de la Login tienda, y permite al usuario seleccionar aquellos que va a comprar. Listado de productos Logoff
* Cesta de compra ( cesta.php ). Muestra un
resumen de los productos escogidos por el
usuario para su compra y da acceso a la página
Cesta de compra
de pago.
*  Pagar ( pagar.php ). Una vez confirmada la Pagar
compra, la última página debería ser la que permitiera al usuario escoger el método de pago y la forma de envío. En este ejemplo no la vas a implementar como tal. Simplemente mostrará un mensaje de tipo "Gracias por su compra" y ofrecerá un enlace para comenzar una nueva compra.
* Logoff ( logoff.php ). Esta página desconecta al usuario de la aplicación y redirige al usuario de forma automática a la pantalla de autentificación. No muestra ninguna información en pantalla, por lo que no es visible para el usuario.

Recuerda poner a las páginas los nombres que aquí figuran, almacenando todas en la misma carpeta. Si cambias algún nombre o mueves alguna página de lugar, los enlaces internos no funcionarán.

Aunque el aspecto de la aplicación no es importante para nuestro objetivo, utilizaremos una hoja de estilos, tienda.css , común a todas las páginas y una imagen, cesta.png , para ofrecer un interface más amigable.

```css
.error {
font-family: Verdana, Arial, sans-serif;
font-size: 0.7em;
color: #900;
background-color : #ffff00;
}
margin: 0;
padding: 0;
text-align: center;
color: #000000;
}
.divisor {
clear:both;
height:0;
font-size: 1px;
line-height: 0px;
} #contenedor {
width: 90%;
background: #fff;
margin: 0 auto;
border: 1px solid #000;
text-align: left;
}
#login fieldset {
position: absolute;
left: 50%;
top: 50%;
width: 230px;
margin-left: -115px;
height: 160px;
margin-top: -80px;
padding:10px;
border:1px solid #ccc;
background-color: #eee;
} #encabezado {
padding: 0 10px;
border-top-width: thin;
border-right-width: thin;
border-bottom-width: thin;
border-left-width: thin;
border-top-style: solid;
border-right-style: solid;
border-bottom-style: solid;
border-left-style: solid;
background-color: #9cf;
}
#login legend {
font-family : Arial, sans-serif;
font-size: 1.3em;
font-weight:bold;
color:#333;
} #encabezado h1 {
margin: 0;
padding: 10px 0;
}
.pagproductos #cesta {
float: right;
width: 12em;
background-color: #588;
}
.pagproductos #cesta h3 {
margin-left: 10px;
margin-right: 10px;
}
.pagproductos #cesta p {
margin-left: 10px;
margin-right: 10px;
font-size: 10px;
}
#login .campo {
margin-top:8px;
margin-bottom: 10px;
}
#login label {
font-family : Arial, sans-serif;
font-size:0.8em;
font-weight: bold;
}
#login
input[type="text"],
#login
input[type="password"] {
font-family : Arial, Verdana, sans-serif;
font-size: 0.8em;
line-height:140%;
color : #000;
padding : 3px;
border : 1px solid #999;
height:18px;
width:220px;
}
.pagproductos #cesta input[type="submit"] {
margin-left: 10px;
margin-right: 10px;
margin-bottom: 3px;
width:100px;
height:30px;
padding-left:0px;
}
.pagproductos #productos {
margin: 0 10em 0 10px;
}
#login input[type="submit"] {
width:100px;
height:30px;
padding-left:0px;
} .pagcesta #productos {
margin: 10px;
font-size: 12px;
}
.pagproductos body, .pagcesta body {
font: 100% Verdana, Arial, Helvetica,
sans-serif;
background: #666; .pagproductos
#productos
.pagproductos #productos p {
font-size: 10px;
}
input,
- 15 -Diseño Web Entorno Servidor
José Luis Comesaña
DAW
}
#productos .codigo {
width: 20%;
float: left;
}
#productos .nombre {
width: 60%;
float: left;
}
#productos .precio {
width: 20%;
text-align: right;
font-weight: bold;
#pie {
padding: 0 10px;
background-color: #99ccff;
border-top-width: thin;
border-right-width: thin;
border-bottom-width: thin;
border-left-width: thin;
border-top-style: solid;
border-right-style: solid;
border-bottom-style: solid;
border-left-style: solid;
}
cesta.png
```

Antes de comenzar ten en cuenta que la aplicación que vas a desarrollar no es completamente funcional. Además de no desarrollar la página con la información de pago, habrá algunas opciones
que no tendrás en cuenta para simplificar el código. Por ejemplo: 
* No tendrás en cuenta la posibilidad de que el usuario compre varias unidades de un mismo
producto.
* Una vez añadido un producto a la cesta de compra, no se podrá retirar de la misma. La única
posibilidad será vaciar toda la cesta y comenzar de nuevo añadiendo productos.
* No se mostrarán imágenes de los productos, ni será posible ver el total de la compra hasta que
ésta haya finalizado.
* Se muestran todos los productos en una única página. Sería preferible filtrarlos por familia y mostrarlos en varias páginas, limitando a 10 o 20 productos el número máximo de cada página.

Aunque reduzcamos en este ejemplo la funcionalidad de la tienda, te animamos a que una vez finalizado el mismo, añadas por tu cuenta todas aquellas opciones que quieras. recorda que la mejor forma de aprender
 programación es... ¡programando!

### 3.3.1.- Gestión de la información de la sesión (II). ###
La primera página que vas a programar es la de autentificación del
usuario ( login.php ). Para variar, utilizarás las capacidades de manejo de
sesiones de PHP para almacenar la identificación de los usuarios.
Además, utilizaremos la información de la tabla " usuarios " en la base
de datos " dwes ", accediendo mediante PDO en lugar de MySQLi.
Vas a crear en la página un formulario con dos campos, uno de tipo text para el usuario, y otro de
tipo password para la contraseña. Al pulsar el botón Enviar, el formulario se enviará a esta misma
página, donde se compararán las credenciales proporcionadas por el usuario con las almacenadas en
la base de datos. Si los datos son correctos, se iniciará una nueva sesión y se almacenará en ella el
nombre del usuario que se acaba de conectar.
Vamos por pasos. El código HTML para crear el formulario, que irá dentro del cuerpo de la página
(entre las etiquetas <body> ) será el siguiente:
```php
<div id='login'>
<form action='login.php' method='post'>
<fieldset >
<legend>Login</legend>
- 16 -Desarrollo de aplicaciones web con PHP
Tema 4
<div><span class='error'><?php echo $error; ?></span></div>
<div class='campo'>
<label for='usuario' >Usuario:</label><br/>
<input type='text' name='usuario' id='usuario' maxlength="50" /><br/>
</div>
<div class='campo'>
<label for='password' >Contraseña:</label><br/>
<input type='password' name='password' id='password' maxlength="50" /><br/>
</div>
<div class='campo'>
<input type='submit' name='enviar' value='Enviar' />
</div>
</fieldset>
</form>
</div>
Fíjate que existe un espacio para poner los posibles mensajes de error que se produzcan, como la
falta de algún dato necesario, o un error de credenciales erróneas.
El código PHP que debe figurar al comienzo de esta misma página (antes de que se muestre cualquier
texto), se encargará de:
Comprobar que se han introducido tanto el nombre de usuario como la contraseña.
if (isset($_POST['enviar'])) {
$usuario = $_POST['usuario'];
$password = $_POST['password'];
if (empty($usuario) || empty($password))
$error = "Debes introducir un nombre de usuario y una contraseña";
else {
Conectarse a la base de datos.
try {
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
$dsn = "mysql:host=localhost;dbname=dwes";
$dwes = new PDO($dsn, "dwes", "abc123.", $opc);
}
catch (PDOException $e) {
die("Error: " . $e->getMessage());
}
Comprobar las credenciales.
$sql = "SELECT usuario FROM usuarios WHERE usuario='$usuario' " .
"AND contrasena='" . md5($password) . "'";
if($resultado = $dwes->query($sql)) {
$fila = $resultado->fetch();
if ($fila != null) {
Iniciar la sesión y almacenar en la variable de sesión $_SESSION['usuario'] el nombre de usuario.
session_start();
$_SESSION['usuario']=$usuario;
Redirigir a la página del listado de productos.
header("Location: productos.php");
Revisa el código completo de la página login.php y comprueba su funcionamiento antes de seguir con
las demás.
<?php
// Comprobamos si ya se ha enviado el formulario
if (isset($_POST['enviar'])) {
$usuario = $_POST['usuario'];
$password = $_POST['password'];
if (empty($usuario) || empty($password))
$error = "Debes introducir un nombre de usuario y una contraseña";
else {
// Comprobamos las credenciales con la base de datos
// Conectamos a la base de datos
try {
- 17 -Diseño Web Entorno Servidor
José Luis Comesaña
DAW
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
$dsn = "mysql:host=localhost;dbname=dwes";
$dwes = new PDO($dsn, "dwes", "abc123.", $opc);
}
catch (PDOException $e) {
die("Error: " . $e->getMessage());
}
// Ejecutamos la consulta para comprobar las credenciales
$sql = "SELECT usuario FROM usuarios " .
"WHERE usuario='$usuario' " .
"AND contrasena='" . md5($password) . "'";
if($resultado = $dwes->query($sql)) {
$fila = $resultado->fetch();
if ($fila != null) {
session_start();
$_SESSION['usuario']=$usuario;
header("Location: productos.php");
}
else {
// Si las credenciales no son válidas, se vuelven a pedir
$error = "Usuario o contraseña no válidos!";
}
unset($resultado);
}
unset($dwes);
}
}
?>
<!DOCTYPE
html
PUBLIC
"-//W3C//DTD
HTML
4.01
"http://www.w3.org/TR/html4/loose.dtd">
<!-- Desarrollo Web en Entorno Servidor -->
<!-- Tema 4 : Desarrollo de aplicaciones web con PHP -->
<!-- Ejemplo Tienda Web: login.php -->
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Ejemplo Tema 4: Login Tienda Web</title>
<link href="tienda.css" rel="stylesheet" type="text/css">
</head>
Transitional//EN"
<body>
<div id='login'>
<form action='login.php' method='post'>
<fieldset >
<legend>Login</legend>
<div><span class='error'><?php echo $error; ?></span></div>
<div class='campo'>
<label for='usuario' >Usuario:</label><br/>
<input type='text' name='usuario' id='usuario' maxlength="50" /><br/>
</div>
<div class='campo'>
<label for='password' >Contraseña:</label><br/>
<input type='password' name='password' id='password' maxlength="50" /><br/>
</div>
<div class='campo'>
<input type='submit' name='enviar' value='Enviar' />
</div>
</fieldset>
</form>
</div>
</body>
</html>
```
En el código anterior, la sesión del usuario se inicia solo si proporciona un nombre de
usuario y contraseña correctos. ¿Se podría haber iniciado la sesión al principio del
código, aunque el usuario no proporcione credenciales?
Sí
No
Sí, se puede iniciar la sesión de igual forma, aunque si el usuario no se autentifica no tendríamos ninguna información que incluir dentro
de la misma.

### 3.3.2.- Gestión de la información de la sesión (III). ###
Cuando un usuario proporciona unas credenciales
de inicio de sesión correctas (recuerda que tú ya
habías añadido el usuario "dwes" con contraseña
"abc123."), se le redirige de forma automática a la
página del listado de productos ( productos.php )
para que pueda empezar a hacer la compra. Esa es
la página que vas a programar a continuación.
<?php
// Recuperamos la información de la sesión
session_start();
// Y comprobamos que el usuario se haya autentificado
if (!isset($_SESSION['usuario'])) {
die("Error - debe <a href='login.php'>identificarse</a>.<br />");
}
?>
<!DOCTYPE
html
PUBLIC
"-//W3C//DTD
HTML
4.01
"http://www.w3.org/TR/html4/loose.dtd">
<!-- Desarrollo Web en Entorno Servidor -->
<!-- Tema 4 : Desarrollo de aplicaciones web con PHP -->
<!-- Ejemplo Tienda Web: productos.php -->
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Ejemplo Tema 4: Listado de Productos</title>
<link href="tienda.css" rel="stylesheet" type="text/css">
</head>
Transitional//EN"
<body class="pagproductos">
<div id="contenedor">
<div id="encabezado">
<h1>Listado de productos</h1>
</div>
<div id="cesta">
<h3><img src="cesta.png" alt="Cesta" width="24" height="21"> Cesta</h3>
<hr />
<?php
// Comprobamos si se ha enviado el formulario de vaciar la cesta
if (isset($_POST['vaciar'])) {
unset($_SESSION['cesta']);
}
// Comprobamos si se ha enviado el formulario de añadir
if (isset($_POST['enviar'])) {
// Creamos un array con los datos del nuevo producto
$producto['nombre'] = $_POST['nombre'];
$producto['precio'] = $_POST['precio'];
// y lo añadimos
$_SESSION['cesta'][$_POST['producto']] = $producto;
}
// Si la cesta está vacía, mostramos un mensaje
$cesta_vacia = true;
if (count($_SESSION['cesta'])==0) {
print "<p>Cesta vacía</p>";
}
// Si no está vacía, mostrar su contenido
else {
foreach ($_SESSION['cesta'] as $codigo => $producto)
print "<p>$codigo</p>";
$cesta_vacia = false;
- 19 -Diseño Web Entorno Servidor
José Luis Comesaña
DAW
}
?>
<form id='vaciar' action='productos.php' method='post'>
<input type='submit' name='vaciar' value='Vaciar Cesta'
<?php if ($cesta_vacia) print "disabled='true'"; ?>
/>
</form>
<form id='comprar' action='cesta.php' method='post'>
<input type='submit' name='comprar' value='Comprar'
<?php if ($cesta_vacia) print "disabled='true'"; ?>
/>
</form>
</div>
<div id="productos">
<?php
try {
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
$dsn = "mysql:host=localhost;dbname=dwes";
$dwes = new PDO($dsn, "dwes", "abc123.", $opc);
}
catch (PDOException $e) {
$error = $e->getCode();
$mensaje = $e->getMessage();
}
if (!isset($error)) {
$sql = <<<SQL
SELECT cod, nombre_corto, PVP
FROM producto
SQL;
$resultado = $dwes->query($sql);
if($resultado) {
// Creamos un formulario por cada producto obtenido
$row = $resultado->fetch();
while ($row != null) {
echo "<p><form id='${row['cod']}' action='productos.php' method='post'>";
// Metemos ocultos los datos de los productos
echo "<input type='hidden' name='producto' value='".$row['cod']."'/>";
echo "<input type='hidden' name='nombre' value='".$row['nombre_corto']."'/>";
echo "<input type='hidden' name='precio' value='".$row['PVP']."'/>";
echo "<input type='submit' name='enviar' value='Añadir'/>";
echo " ${row['nombre_corto']}: ";
echo $row['PVP']." euros.";
echo "</form>";
echo "</p>";
$row = $resultado->fetch();
}
}
}
?>
</div>
<br class="divisor" />
<div id="pie">
<form action='logoff.php' method='post'>
<input
type='submit'
name='desconectar'
value='Desconectar
$_SESSION['usuario']; ?>'/>
</form>
<?php
if (isset($error)) {
print "<p class='error'>Error $error: $mensaje</p>";
}
?>
</div>
</div>
</body>
</html>
usuario
<?php
echo
La página se divide en varias zonas, cada una definida por una etiqueta < div> en el código HTML:
 encabezado. Contiene únicamente el título de la página.
 productos. Contiene el listado de todos los productos tal y como figuran en la base de datos.
Cada producto figura en una línea (nombre y precio).
Se crea un formulario por cada producto, con un botón "Añadir" que envía a esta misma página
los datos código, nombre y precio del producto. Cuando se abre la página, se comprueba si se ha
- 20 -Desarrollo de aplicaciones web con PHP
Tema 4
enviado este formulario, y si fuera así se añade un elemento al array asociativo
$_SESSION['cesta'] con los datos del nuevo producto.
// Comprobamos si se ha enviado el formulario de añadir
if (isset($_POST['enviar'])) {
// Creamos un array con los datos del nuevo producto
$producto['nombre'] = $_POST['nombre'];
$producto['precio'] = $_POST['precio'];
// y lo añadimos
$_SESSION['cesta'][$_POST['producto']] = $producto;
}
El array $_SESSION['cesta'] es la variable de sesión en la que guardaremos los datos de todos
los productos que va a comprar el usuario. Fíjate que los datos del nuevo producto se incluyen a
su vez como un array con dos elementos, el precio y el nombre.
 cesta. Muestra el código de los productos que se van añadiendo a la cesta.
// Si la cesta está vacía, mostramos un mensaje
$cesta_vacia = true;
if (count($_SESSION['cesta'])==0) {
print "<p>Cesta vacía</p>";
}
// Si no está vacía, mostrar su contenido
else {
foreach ($_SESSION['cesta'] as $codigo => $producto)
print "<p>$codigo</p>";
$cesta_vacia = false;
}
Contiene dos formularios. Uno para vaciar la cesta (botón "Vaciar Cesta"), dirigido a esta misma
página, y otro para realizar la compra (botón "Comprar"), que dirige a la página cesta.php . Para
vaciar la cesta, se incluye en la página el siguiente código:
// Comprobamos si se ha enviado el formulario de vaciar la cesta
if (isset($_POST['vaciar'])) {
unset($_SESSION['cesta']);
}

pie. Contiene un botón para desconectar al usuario actual. Llama a la página logoff.php , que
borra la sesión actual.
Además, tanto en esta página como en todas las demás, es necesario comprobar la variable de
sesión $_SESSION['usuario'] para verificar que el usuario se ha autentificado correctamente.
Para ello, debes incluir el siguiente código al inicio de la página.

```php
<?php
// Recuperamos la información de la sesión
session_start();
// Y comprobamos que el usuario se haya autentificado
if (!isset($_SESSION['usuario'])) {
die("Error - debe <a href='login.php'>identificarse</a>.<br />");
}
?>
```

Si el usuario no se ha autentificado, se muestra un mensaje de error junto con un enlace a la página login.php .
### 3.3.3.- Gestión de la información de la sesión (IV). ###

Si desde la página del listado de productos, el usuario pulsa sobre el botón "Comprar", se le dirige a la página de la Cesta de la compra ( cesta.php ), en la que se le muestra unresumen de los productos que ha seleccionado junto al importe total de los mismos.

```php
<?php
// Recuperamos la información de la sesión
session_start();
// Y comprobamos que el usuario se haya autentificado
if (!isset($_SESSION['usuario'])) {
die("Error - debe <a href='login.php'>identificarse</a>.<br />");
}
?>
<!DOCTYPE
html
PUBLIC
"-//W3C//DTD
HTML
4.01
"http://www.w3.org/TR/html4/loose.dtd">
<!-- Desarrollo Web en Entorno Servidor -->
<!-- Tema 4 : Desarrollo de aplicaciones web con PHP -->
<!-- Ejemplo Tienda Web: cesta.php -->
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Ejemplo Tema 4: Cesta de la Compra</title>
<link href="tienda.css" rel="stylesheet" type="text/css">
</head>
Transitional//EN"
<body class="pagcesta">
<div id="contenedor">
<div id="encabezado">
<h1>Cesta de la compra</h1>
</div>
<div id="productos">
<?php
$total = 0;
foreach($_SESSION['cesta'] as $codigo => $producto) {
echo "<p><span class='codigo'>$codigo</span>";
echo "<span class='nombre'>${producto['nombre']}</span>";
echo "<span class='precio'>${producto['precio']}</span></p>";
$total += $producto['precio'];
}
?>
<hr />
<p><span class='pagar'>Precio total: <?php print $total; ?> €</span></p>
<form action='pagar.php' method='post'>
<p>
<span class='pagar'>
<input type='submit' name='pagar' value='Pagar'/>
</span>
</p>
</form>
</div>
<br class="divisor" />
<div id="pie">
<form action='logoff.php' method='post'>
<input
type='submit'
name='desconectar'
value='Desconectar
usuario
$_SESSION['usuario']; ?>'/>
</form>
</div>
</div>
</body>
</html>
<?php
echo
En la página figuran dos formularios que simplemente redirigen a otras páginas. El que contiene el
botón "Pagar", que redirige a la página pagar.php , que en nuestro caso lo único que debe hacer es
eliminar la sesión del usuario. Y el que contiene el botón de desconexión, que es similar al que
figuraba en la página productos.php , y dirige a la página logoff.php, que cierra la sesión del usuario.
<?php
// Recuperamos la información de la sesión
session_start();
unset($_SESSION['cesta']);
die("Gracias por su compra.<br />Quiere <a href='productos.php'>comenzar de nuevo</a>?");
?>
Los datos que figuran en la página se obtienen todos de la información almacenada en la sesión del
usuario. No es necesario establecer conexiones con la base de datos. El código que sirve para
mostrar el listado de los productos seleccionados es el siguiente:
<?php
$total = 0;
foreach($_SESSION['cesta'] as $codigo => $producto) {
echo "<p><span class='codigo'>$codigo</span>";
echo "<span class='nombre'>${producto['nombre']}</span>";
echo "<span class='precio'>${producto['precio']}</span></p>";
$total += $producto['precio'];
}
?>
<hr />
<p><span class='pagar'>Precio total: <?php print $total; ?> €</span></p>
Recuerda que al principio de esta página, también será necesario verificar la variable de
sesión $_SESSION['usuario'] para comprobar que el usuario se ha autentificado correctamente.
Tanto desde esta página como desde la página del listado de productos, se le ofrece al usuario la
posibilidad de cerrar la sesión. Para ello se le dirige a la página logoff.php, que no muestra nada en
pantalla. Su código es el siguiente:
<?php
// Recuperamos la información de la sesión
session_start();
// Y la eliminamos
session_unset();
header("Location: login.php");
?>
```
Tras eliminar la sesión, redirige al usuario a la página de autentificación donde puede iniciar una
nueva sesión con el mismo o con otro usuario distinto.
En las páginas anteriores, productos.php y cesta.php, se ha incluido un botón para
desconectar al usuario cerrando su sesión. ¿Se podría utilizar un enlace a la página
logoff.php en lugar de crear un botón dentro de un formulario?
Sí
No
La única función del botón es abrir la página logoff.php. No necesitamos enviar nada a ésta página, por lo que se podría haber logrado lo
mismo con un simple enlace.</pre></body></html>
