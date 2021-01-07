---
layout: default 
title: 2. Maneig de sessions 
parent: 8. Seguretat i control d'errors 
nav_order: 3
---

# Maneig de sessions 
{: .no_toc .nocount }


1. TOC 
{:toc}

## Introducció
Com acabes de veure, una forma per guardar informació particular de cada usuari és utilitzar galetes (*cookies*). No
obstant això, hi ha diversos problemes associats a les galetes, com el nombre d'elles que admet el navegador, o la seva
grandària màxima. Per solucionar aquests inconvenients, existeixen *les sessions*. El terme sessió fa referència al
conjunt d'informació relativa a un usuari concret.

Aquesta informació pot ser tan simple com el nom de l'usuari mateix, o més complexa, com els articles que ha dipositat a
la cistella de compra d'una botiga en línia.

## Identicadors de sessió 

Cada usuari diferent d'un lloc web té la seva pròpia informació de sessió. Per distingir una sessió d'una altra s'usen
els **identificadors de sessió** (SID). Un SID és un atribut que s'assigna a cada un dels visitants d'un lloc web i
l'identifica. D'aquesta manera, si el servidor web coneix el SID d'un usuari, pot relacionar-lo amb tota la informació
que posseeix sobre ell, que es manté en la sessió de l'usuari. Aquesta informació s'emmagatzema en el servidor web,
generalment en fitxers tot i que també es poden utilitzar altres mecanismes d'emmagatzematge com bases de dades. Com ja
hauràs suposat, la qüestió ara és: ¿i on s'emmagatzema aquest SID, identificador de la sessió, que és únic per a cada
usuari? Doncs hi ha dues maneres de mantenir el SID entre les pàgines d'un lloc web que visita l'usuari:

* Utilitzant galetes.
* Propagant el SID en un paràmetre de la URL. El SID s'afegeix com una part més de la URL, de la forma:
  * https://www.example.com/stor/list.php&amp;**PHPSESSID=34534fg4ffg34ty**


En l'exemple anterior, el SID és el valor del paràmetre PHPSESSID.

Cap de les dues maneres és perfecta. Ja saps els problemes que té la utilització de cookies. Malgrat això, és el millor
mètode i el més utilitzat. Propagar el SID com a part de la URL comporta majors desavantatges, com la impossibilitat de
mantenir el SID entre diferents sessions, o el fet que compartir la URL amb una altra persona implica compartir també
l'identificador de sessió.

## Maneig de sessions en PHP

La bona notícia, és que el procés de maneig de sessions en PHP està automatitzat en gran mida. Quan un usuari visita un
lloc web, no cal programar un procediment per veure si hi ha un SID previ i carregar les dades associades amb el mateix.
Tampoc has d'utilitzar la funció `setcookie` si vols emmagatzemar els SID en galetes, o anar passant el SID entre les
pàgines web del teu lloc si et decideixes per propagar. Tot això PHP ho fa automàticament.

<div markdown="1" class="alert-info alert"> 
La informació que s'emmagatzema en la sessió d'un usuari també es coneix com galetes en
la part de servidor (*server side cookies*). Has de tenir en compte que encara aquesta informació no viatja entre el
client i el servidor, sí que ho fa el SID, bé com a part de l'URL o en una capçalera HTTP si es guarda en una galeta. En
tots dos casos, això planteja un possible problema de seguretat. El SID pot ser aconseguit per una altra persona, i a
partir de la mateixa obtenir la informació de la sessió de l'usuari. La manera més segura d'utilitzar sessions és
emmagatzemant els SID en galetes i utilitzar HTTPS per a xifrar la informació que es transmet entre el servidor web i el
client.
</div>

## Configuració ##

Per defecte, PHP inclou suport de sessions incorporat. Abans, però, d'utilitzar sessions en el teu lloc web, has de
configurar correctament PHP utilitzant els següents directives en el fitxer `php.ini` segons corresponga:

| Directiva| significat |
| -------- | ---------- |
| session.use_cookies | Indica si s'han d'usar cookies (1) o propagació a la URL (0) per emmagatzemar el SID. |
| session.use_only_cookies | S'ha d'activar (1) quan fas servir cookies per emmagatzemar els SID, i a més no vols que es reconeguin els SID que es puguin passar com part de la URL (aquest mètode es pot usar per usurpar l'identificador d'un altre usuari).|
| session.save_handler | S'utilitza per indicar a PHP com ha de emmagatzemar les dades de la sessió de l'usuari. Hi ha quatre opcions: en fitxers (files), en memòria (Mm), en una base de dades SQLite (sqlite) o utilitzant per a això funcions que ha de definir el programador (user). El valor per defecte (Files) funcionarà sense problemes en la majoria dels casos.| 
| session.name | Determina el nom de la galeta que s'utilitzarà per guardar el SID. La seva valor per defecte és PHPSESSID.|
| session.auto_start | El seu valor per defecte és 0, i en aquest cas hauràs de fer servir la funció session_start per gestionar l'inici de les sessions. Si fas servir sessions al lloc web, pot ser bona idea canviar el seu valor a 1 per que PHP activi de forma automàtica el maneig de sessions.|
| session.cookie_lifetime | Si utilitzes l'URL per propagar el SID, aquest es perdrà quan tanqui el navegador. No obstant això, si utilitzes galetes, el SID es mantindrà mentre no es destrueixi la galeta. En el seu valor per defecte (0), les galetes es destrueixen quan es tanca el navegador. Si vols que es mantingui el SID durant més temps, has d'indicar en aquesta directiva aquest temps en segons. |
| session.gc_maxlifetime| Indica el temps en segons que s'ha de mantenir activa la sessió, encara que no hi hagi cap activitat per part de l'usuari. El seu valor per defecte és 1440. És a dir, passats 24 minuts des de l'última activitat per part de l'usuari, es tanca la sessió automàticament. |
| session.cookie_path | URL path prefix that must match for the cookie to be sent. |
| session.cookie_domain | Domain suffix that must match for the cookie to be sent. No value means the cookie is sent back only to the full hostname that sent it. |
| session.cookie_secure | Set to On to have the cookie only sent back with HTTPS URLs. |
| session.cookie_httponly | Set to On to tell browsers to prevent JavaScript from reading the cookie. |

La funció `phpinfo`, de la qual ja vam parlar amb anterioritat, t'ofereix informació sobre la configuració actual de les
directives de sessió.

En la documentació de PHP tens informació sobre aquestes i altres directives que permeten configurar el maneig de
sessions.

[https://www.php.net/manual/es/session.configuration.php](https://www.php.net/manual/es/session.configuration.php)

{: .alert .alert-question}
<div markdown="1">
Si la informació de l'usuari que vols emmagatzemar inclou contingut privat com una contrasenya, 
¿què utilitzaries, galetes o la sessió de l'usuari?
</div>

## Inici i fi d'una sessió ##

L'inici d'una sessió pot tenir lloc de dues maneres. Si has activat la directiva `session.auto_start` en la configuració
de PHP, la sessió començarà automàticament quan un usuari es connecte al teu lloc web. En el cas que aquest usuari ja
haja obert una sessió amb anterioritat, i aquesta no s'haja eliminat, en lloc d'obrir una nova sessió es reprendrà la
anterior. Per a això s'utilitzarà el SID anterior, que estarà emmagatzemat en una galeta (recorda que si fas servir
propagació de l'SID, no podràs restaurar sessions anteriors; el SID figura a la URL i es perd quan tanques el navegador).

Si per contra, decideixes no utilitzar l'inici automàtic de sessions, hauràs executar la funció `session_start()` per
indicar a PHP que inicie una nova sessió o reprenga l'anterior. Anteriorment aquesta funció tornava sempre `true`, a
partir de la versió 5.3.0 de PHP el seu comportament és més coherent: retorna `false` en cas de no poder iniciar o
restaurar la sessió.

<div markdown="1" class="alert-warning alert">
Com l'inici de sessió requereix utilitzar `cookies`, i aquestes es transmeten en els encapçalats HTTP, heu de tenir en
compte que per poder iniciar una sessió utilitzant `session_start`, hauràs de fer les cridades a aquesta funció abans
que la pàgina web mostre informació al navegador.
</div>

A més, totes les pàgines que necessiten utilitzar la informació emmagatzemada en la sessió, hauran d'executar la
funció `session_start`.

Mentre la sessió estiga oberta, pots utilitzar la variable superglobal `$_SESSION` per afegir informació a la sessió de
l'usuari, o per accedir a la informació emmagatzemada en la sessió. Per exemple, per comptar el nombre de vegades que
l'usuari visita la pàgina, pots fer:

```php
<?php
    // Iniciamos la sesión o recuperamos la anterior sesión existente
    session_start();
    // Comprobamos si la variable ya existe
    if (isset($_SESSION['visits']))
        $_SESSION['visits']++;
    else
        $_SESSION['visits'] = 0;
?>
```

Si en lloc de el nombre de visites, voldries emmagatzemar l'instant en què es produeix cadascuna, la variable de
sessió "visites" ha de ser una matriu i per tant hauràs de canviar el codi anterior per:

```php
// Iniciamos la sesión o recuperamos la anterior sesión existente
 session_start();
 // En cada visita añadimos un valor al array "visits"
 $_SESSION['visits'][] = time();
```

### Tancar sessió

Encara que com ja has vist, pots configurar PHP perquè elimine de forma automàtica les dades de una sessió passat cert
temps, en ocasions pot ser necessari tancar-la de forma manual en un moment determinat. Per exemple, si utilitzes
sessions per recordar la informació d'autenticació hauràs donar-li a l'usuari de la pàgina web la possibilitat de tancar
la sessió quan ho crega convenient.

En PHP tens dues funcions per eliminar la informació emmagatzemada en la sessió:

* `session_unset`. Elimina les variables emmagatzemades a la sessió actual, però no elimina la informació de la sessió
  del dispositiu d'emmagatzematge usat.
* `session_destroy`. Elimina completament la informació de la sessió del dispositiu de emmagatzematge.

<div markdown="1" class="alert-question alert">
Amb les funcions anteriors eliminem la informació però encara ens quedaria eliminar alguna cosa més. Saps quina?
</div>


<div markdown="1" class="alert-activity alert">
**Activitat 3. Registres de visites**

Modifica la pàgina `index.php` emmagatzemant en la sessió d'usuari els instants de totes les seves últimes visites.
Si és la seva primera visita, mostra un missatge de benvinguda. En cas contrari, mostra la data i hora de totes 
les seves visites anteriors. A més a més, afegeix un botó a la pàgina que permeta esborrar el registre de visites.
</div>

### Solució proposada

```php
# index.php

// We turn on the session support
session_start();

$sessionKey = "visits";

// check if is the first visit
$visits = $_SESSION[$sessionKey]??[];

// if not empty generate an HTML Unordered List
if (!empty($visits))
    $messageSession =  "<ul><li>" . implode("</li><li>", array_map(function($v) {
            return date("d/m/Y h:i:s", $v);
        }, $visits)) . "</li></ul>";
else
    $messageSession = "Welcome to our reservation system (session version)!";

$_SESSION[$sessionKey][] = time();
```

## Gestió de la informació de la sessió ##

Anem a utilitzar la informació de la sessió per a millorar alguns aspectes de la gestió del formulari de reserves. 

### Errors de validació 

La pàgina de processament del formulari `reservations-store.php` mostra els errors de validació però no el formulari, que es troba en `reservations-create.php`. 

El que farem serà controlar si hi ha errors en el processament i, en cas d'haver-ne, redireccionar a la pàgina `reservations-create.php`, emmagatzemant prèviament en la clau `errors` de la sessió els errors detectats.

Serà `reservations-create.php` qui controlarà si hi ha errors en la variable de sessió i en eixe cas els mostrarà.

<div markdown="1" class="alert-info alert">
### Redireccions

Una redirecció és una tècnica que permet fer que el servidor web responga amb una URL diferent a l'actual.

Per a aconseguir s'utilitza la funció `header()`.

```php
// Send to the browser a new url and send a HTTP redirect code (302)
header('Location: /index.php')

// this function terminate the current script
exit();
```
</div>

<div markdown="1" class="alert-activity alert">
**Activitat 4. Errors de validació**

Modifica les pàgines `reservations-create.php` i `reservations-store.php` per a gestionar els errors tal com s'indica
en l'explicació anterior.

Una vegada s'ha obtés la informació dels errors en la variable de sessió caldrà eliminar-la.
</div>

### Missatges entre pàgines
Una altra qüestió a resoldre és la de la recàrrega de pàgines que han processat un formulari. Per exemple, què passa si després d'afegir una reserva actualitzem la pàgina? Es torna a intentar crear una reserva.

Per a solucionar-ho podem combinar de nou variables de sessió i redireccions. Si la reserva s'ha realitzat correctament 
podem redireccionar a la pàgina `index.php` per exemple, i usar una variable de sessió `message` per a passar a `index.php` la confirmació de que s'ha creat correctament la reserva.

<div markdown="1" class="alert-activity alert">
**Activitat 5. Missatges entre pàgines**

Modifica les pàgines `reservations-store.php` i `index.php` per a gestionar els missatges tal com s'indica
en l'explicació anterior.

Una vegada s'ha obtés la informació dels missatges en la variable de sessió caldrà eliminar-la.
</div>

Més informació sobre les sessions en PHP: [Maneig de sessions (PHP)](https://www.php.net/manual/es/book.session.php)

## Millores en el _framework_ MVC

Com ja has vist, combinant redireccions i variables de sessió podem
millorar la gestió dels nostres formularis i, per extensió, la nostra
aplicació web.

Es per això que en el projecte `Movies` implementarem la classe `FlashMessage` que s'encarregarà de 
de gestionar tant els errors de validació com els missatges entre pàgines mitjançant 
variables de sessió.

```php
# src/Core/Helpers/FlashMessage.php

/**
 * Class FlashMessage
 * 
 */
class FlashMessage
{
    /**
     * @var string
     */
    private string $sessionKey;

    /**
     * FlashMessage constructor.
     * Session key is the $_SESSION key name.
     * @param string $sessionKey
     */
    public function __construct(string $sessionKey = "flash-message");

    /**
     * get the value of the key passed as argument.
     * after reading the value unsets it
     * @param string $key
     * @param string $defaultValue
     * @return mixed|string
     */
    public static function get(string $key, $defaultValue = '');

    /**
     * @param string $key
     * @param $value
     */
    public static function set(string $key, $value);

    /**
     * @param string $key
     */
    public static function unset(string $key);      
}
```

<div markdown="1" class="alert-activity alert">
**Activitat 6. La classe FlashMessage**

A partir de la declaració anterior implementa la classe `FlashMessage`. Instància-la i 
afig-la al contenidor de serveis. 

Utilitza l'objecte `FlashMessage` per a gestionar els errors en la creació de partners (`partners/create`) i mostrar el missatge de confirmació en l'índex de partners (`/partners`).

Com que usem Bootstrap en el projecte poder fer ús de les seues formats d'avís.
[Alerts in Bootsrap 4.5](https://getbootstrap.com/docs/4.5/components/alerts/)
</div>
