---
layout: default 
title: 1. Galetes (cookies)
nav_order: 1 
parent: 8. Seguretat i control d'errors
---

# Galetes (*cookies*)

{: .no_toc .nocount}

Una galeta és un fitxer de text que un lloc web guarda a l'entorn de l'usuari de navegador. El seu ús més típic és
l'emmagatzematge de les preferències de l'usuari (per exemple, l'idioma en que s'han de mostrar les pàgines), perquè no
hagi de tornar a indicar-les la propera vegada que visiteu el lloc.

Si utilitzes Firefox com a navegador, pots accedir a `Desenvolupador web` &ndash; `Inspector d'emmagatzematge` des del
menú principal. Entre les seves característiques et permet consultar i editar les galetes emmagatzemades en el mateix.

![Inspeccionar galetes en Firefox](images/galetes-firefox.png)

En PHP, per emmagatzemar una galeta al navegador de l'usuari, pots utilitzar la funció `setcookie`. L'únic paràmetre
obligatori que has de fer servir és el nom de la galeta, però admet diversos paràmetres més opcionals.

```php
setcookie ($name [, 
    $value = " " [,
    $expires = 0 [, 
    $path = "" [,
    $domain = "" [, $secure = FALSE [,
    $httponly = FALSE ]]]]]] ) : bool
```

{:.alert .alert-info }
<div markdown="1">
Per a més informació consulta: [https://www.php.net/manual/es/function.setcookie.php](https://www.php.net/manual/es/function.setcookie.php).
</div>

Per exemple, si vols emmagatzemar en una galeta la data del darrer accés d'un usuari, pots fer:

```php
setcookie ("last_visit_date", (string) time(), time() + 3600);
```

Els dos primers paràmetres són el nom de la galeta i el seu valor. El tercer és la data de caducitat de la mateixa en format `timestamp` (en l'exemple, un hora des del moment en què s'execute). En cas de no figurar aquest paràmetre, la galeta s'eliminarà quan es tanque el navegador. Tingues en compte que també es poden aplicar restriccions a les pàgines del lloc que poden accedir a una galeta en funció de la ruta.

Les galetes es transmeten entre el navegador i el servidor web utilitzant les capçaleres del protocol 
HTTP. **Per això, les sentències `setcookie` s'han d'enviar abans que el navegador mostre cap informació a pantalla.**

![Enviament de cookies HTTP](images/cookie-http-transfer.png)

Podeu trobar més informació en [Ultimate Guide to HTTP Cookies](https://blog.webf.zone/ultimate-guide-to-http-cookies-2aa3e083dbae)

El procés de recuperació de la informació que emmagatzema una galeta és molt simple. quan accedeixes a un lloc web, el
navegador li envia de forma automàtica tot el contingut de les galetes que emmagatzema relatives a aquest lloc en
concret. Des PHP pots accedir a aquesta informació per mitjà de l'array `$_COOKIE`.

<div markdown="1" class="alert-warning alert">
Sempre que utilitzeu galetes en una aplicació web, heu de tenir en compte que en última instància la seva 
disponibilitat està controlada pel client. Per exemple, alguns usuaris deshabiliten les galetes al navegador perquè 
pensen que la informació que emmagatzemen pot suposar un potencial problema de seguretat. O que la informació que
emmagatzemen pot arribar a perdre perquè el usuari decideix formatar l'equip o simplement eliminar-les de sistema.
</div>

Si un cop emmagatzemada una galeta al navegador vols eliminar-la abans que expire, pots utilitzar 
la mateixa funció `setcookie` però indicant una data de caducitat anterior a l'actual.

<div markdown="1" class="alert-activity alert">
### Activitat 1: Última visita 

Modifica la pàgina `index.php` de forma que emmagatzeme en una galeta l'últim instant en què l'usuari 
va visitar la pàgina. 

Si és la seva primera visita, mostra un missatge de benvinguda. En cas contrari, mostra 
la data i hora de la seva anterior visita. 

La galeta tindrà una durada d'una setmana.

Comprova que la galeta s'ha creat correctament.
</div>


#### Solució proposada
```php
declare(strict_types=1);

// it's a good practise to save the cookie name in a variable 
// to avoid misspelling errors.
$cookieName = "last_visit_date";

// we get the current cookie value
$lastVisit = filter_input(INPUT_COOKIE, $cookieName, FILTER_VALIDATE_INT);

// we can also use the coalsece operator
/* $lastVisit =(int)($_COOKIE[$cookieName] ?? null); //
// or the traditional isset
/* if (isset($_COOKIE[$cookieName])) {
    $lastVisit = (int)$_COOKIE[$cookieName];
} else
    $lastVisit = null;
*/

// if null we show a welcome message
if (empty($lastVisit))
    $message = "Welcome to our reservation system!";
else
    $message = "Welcome back, your last visit was on " . 
        date("d/m/Y h:i:s", $lastVisit);

// we register the current time and set the expiration date to the next week.
setcookie($cookieName, (string)time(), time() + 7 * 24 * 60 * 60);
```


<div markdown="1" class="alert-activity alert">
### Activitat 2. Emmagatzematge del nom de l'usuari

L'objectiu és que quan un usuari faça una reserva s'emmagatzeme el nom introduït en un galeta,
 de forma que quan torne a accedir al formulari de reserva el nom aparega automàticament.

El nom de la _cookie_ serà `last_used_name` i la durada serà de 30 dies.

Nota: l'estructura és semblant a l'anterior però estarà divida en dues pàgines.

Crea la pàgina `logout.php` que en accedir eliminarà la _cookie_.
</div>


<div markdown="1" class="alert-question alert">
Quina és la durada per defecte d'una galeta si no s'indica la data de caducitat,
com en la següent crida a la funció `setcookie`?

```php
setcookie ("idioma", "espanyol");
```

1. Fins que es tanque el navegador de l'usuari.
2. 1 hora.
</div>
