---
layout: default
title:  4. Bones pràctiques
parent: 8. Autenticació d'usuaris i control d'accés
nav_order: 4
marp: true
size: 4:3
theme: marjal
paginate: true
footer: Desenvolupament d'aplicacions web 2020-21
---

# Bones pràctiques 

## Seguretat i gestió d'errors


---
## Usa sempre https
--- 
## Usa sessions 
* Les dades emmagatzemades no s'envien, romanen al servidor.

* S'envia l'identificador de sessió. Cal protegir-lo.

Segons OWASP:
> Session settings are some of the MOST important values to concentrate on in configuring. It is a good practice to change `session.name` to something new.
---

## Usa sessions
### Configuració proposada per OWASP

```
 session.save_path                = /path/PHP-session/
 session.name                     = myPHPSESSID
 session.auto_start               = Off
 session.use_trans_sid            = 0
 session.cookie_domain            = full.qualified.domain.name
 session.use_strict_mode          = 1
 session.use_cookies              = 1
 session.use_only_cookies         = 1
 session.cookie_lifetime          = 14400 # 4 hours 
 session.cookie_secure            = 1
 session.cookie_httponly          = 1
 session.cookie_samesite          = Strict
 session.cache_expire             = 30 
 ...
```
---
# Control de la vida de sessió #

 1. **Fes que expire la sessió després d’un curt període d’inactivitat**: ajusteu el temps d’inactivitat a 5 minuts per a aplicacions altament protegides fins a no més de 30 minuts per a aplicacions de baix risc.

 2. **Habilita l’opció de tancament de sessió**: caduca i destrueix explícitament la sessió en tancar la sessió.

 3. **Eviteu els inicis de sessió persistents (opció "recordeu-me")**: opcionalment podeu restringir la informació conservada i revelada per l'aplicació, és a dir, obligar a l'usuari a tornar a iniciar sessió abans de realitzar cap operació crítica.

 4. **Fes que expire la sessió quan hi haja error de seguretat**: qualsevol error de seguretat de l'aplicació hauria de donar lloc a la finalització de la sessió.

 5. **Fes caducar la sessió quan siga de llarga durada**: obligació a reautentificar la sessió, que tot i estar actiu ha arribat al temps màxim permès, p.e. unes poques hores.

 6. **Elimineu la galeta de sessió en destruir una sessió**.

---
# Identificador de sessió

 1. **Utilitzeu només galetes per propagar l’ID de sessió**, ja que quan es transmeten mitjançant un paràmetre URL, les sol·licituds GET poden ser emmagatzemades a l’historial del navegador, a la memòria cau i als marcadors. Aleshores també es pot visualitzar fàcilment.

 2. **Regenereu l'identificador de sessió**: regenereu (substituïu-ne per un de nou) l'identificador de sessió, almenys cada cop que canvie el nivell de privilegi de l'usuari. Generalment es pot regenerar abans de qualsevol transacció important, després d’un determinat nombre de sol·licituds o després d’un període de temps.

 3. **Comproveu si l'identificador de sessió és vàlid** (de mida i tipus previst) i que ha estat generat per l'aplicació i no proporcionat per l'usuari.

 4. **L'identificador de sessió hauria de ser adequadament llarg, imprevisible, difícil de reproduir i creat a partir de fonts aleatòries d'alta qualitat**.

---

# Cookie de sessió 

 1. Definiu el domini de la galeta a quelcom més específic que el domini de primer nivell.

 2. No emmagatzeneu res a la galeta (almenys qualsevol informació sensible com el nom d’usuari o la contrasenya) sols’ID de sessió.

 3. Definiu el senyalador només http per desactivar la captura de l'identificador de sessió mitjançant XSS.

4. Quan siga possible, utilitzeu un xifrat fort (SSL) i l'atribut `cookie_secure` per permetre la transmissió de cookies només a través de https.

# Emmagatzematge de dades de sessió 

 1. Determineu on s'emmagatzemen les dades de sessió i si es tracta d’un sistema de fitxers o d’una base de dades, determineu qui més podria tenir accés a aquestes dades.

 2. Quan s'emmagatzemen les dades de sessió en fitxers, assegureu-vos que l'aplicació està configurada per utilitzar un directori privat independent (per exemple, la directiva `session.save_path`). Utilitzeu els permisos del sistema de fitxers per protegir aquests fitxers d’observació o modificació d’usuaris diferents dels comptes necessaris per operar el servidor web i les aplicacions. Si això no és possible, cal xifrar les dades de la sessió o contenir només dades no sensibles. Tingueu en compte que PHP utilitza de manera predeterminada el directori temporal del sistema públic.

 3. Totes les variables de sessió s’han de validar i sanejar per assegurar-se que siguem correctes i que no conté caràcters inesperats.

# No conèixer les contrasenyes 

Al final, tothom crea una aplicació PHP que es basa en la sessió d’usuari. Els noms d’usuari i les contrasenyes s’emmagatzemen en una base de dades i s’utilitzen posteriorment per autentificar els usuaris després de l’inici de sessió.

És important que fer *hash* de les contrasenyes correctament abans d’emmagatzemar-les. El *hashing* i el xifrat són dues coses molt diferents que sovint es confonen.

El *hashing* és una funció irreversible i unidireccional. És produeix una cadena de longitud fixa que no es pot invertir. Això vol dir que podeu comparar un *hash* amb un altre per determinar si tots dos provenien de la mateixa cadena font, però no podeu determinar la cadena original. Si les contrasenyes no s'emmagatzemen amb *hashing* i un tercer no autoritzat accedeix a la vostra base de dades, tots els comptes d'usuari estaran compromesos.

A diferència del *hashing*, el xifrat és reversible (sempre que tingueu la clau). El xifratge és útil en altres àrees, però és una estratègia deficient per emmagatzemar contrasenyes de forma segura.

Les contrasenyes també han de *salar-se* individualment afegint una cadena aleatòria a cada contrasenya abans d'aplicar *hashing*. Això evita els atacs de diccionari i l'ús de "taules de l'arc de Sant Martí" (una llista inversa de *hash* criptogràfics per a contrasenyes comunes.)

El *hashing* i el salat són vitals, ja que sovint els usuaris utilitzen la mateixa contrasenya per a diversos serveis i la qualitat de la contrasenya és deficient.

Addicionalment, haureu d'utilitzar un algorisme especialitzat en *hashing* en lloc d'una funció de hash criptogràfic de propòsit general i ràpid (per exemple, SHA256). La llista breu d'algorismes de hashing de contrasenya acceptables (a juny de 2018) a utilitzar són:

* Argon2 (disponible a PHP 7.2 i versions posteriors)
* Scrypt
* Bcrypt (PHP us proporciona aquesta; vegeu més avall)
* PBKDF2 amb HMAC-SHA256 o HMAC-SHA512

Afortunadament, avui en dia PHP ens ho facilita.

# *Hashing* de contrasenyes amb `password_hash` 

A PHP 5.5 s'ha introduït `password_hash()`. En aquest moment està utilitzant BCrypt, l'algorisme més fort actualment suportat per PHP. Tot i això, s'actualitzarà en el futur per donar suport a més algoritmes. La biblioteca de `password_compat`  es va crear per proporcionar una compatibilitat de PHP> = 5.3.7.

A continuació hem aplicat *hashing* a una cadena i, a continuació, apliquem el *hashing* contra una nova cadena. Com que les dues cadenes d'origen són diferents ("contrasenya-secreta" i "contrasenya-errònia"), aquesta sessió fallarà.

```php
$passwordHash = password_hash("contrasenya-secreta", PASSWORD_DEFAULT);
if (password_verify("contrenya-errònia", $passwordHash)) {
    // Contrasenya correcta
} else {
    // Contrasenya incorrecta
}
```
`password_hash()` s'ocupa de la salificació de contrasenyes. La *sal* s'emmagatzema, juntament amb l'algorisme i el "cost", com a part del hash. `password_verify()` extreu això per determinar com comprovar la contrasenya, de manera que no necessiteu un camp de base de dades independent per emmagatzemar les vostres sals.

{: .alert .alert-info}
**PASSWORD_DEFAULT**. Actualment utilitza l’algoritme bcrypt (predeterminat a partir de PHP 5.5.0). Observeu que aquesta constant està pensada per a adaptar-se sempre que hi haja un algoritme nou i més fort a PHP. Per aquesta raó, la longitud del resultat d’utilitzar aquest identificador pot canviar amb el temps. Per tant, es recomana emmagatzemar el resultat en una columna d’una base de dades de més de 60 caracters (255 caracters seria una bona elecció).

{: .alert .alert-activity }
<div markdown="1">
### Activitat ###

Adapta el projecte de forma que:

 1. Cada 15 minuts es regenere la sessió.
 2. Sols use http per accedir a la *cookie* de sessió.
 3. Les constrasenyes s'encripten amb *bcrypt*.
 4. Es tanque la sessió tal com s'indica.
</div>



# Recursos 

 * [The 2018 Guide to Building Secure PHP Software](https://paragonie.com/blog/2017/12/2018-guide-building-secure-php-software)
 * [PHP: The Right Way. Security](https://phptherightway.com/#security)
 * [Session Management Basics](https://www.php.net/manual/en/features.session.security.management.php)
 * [Ultimate PHP Security Best Practices](https://www.cloudways.com/blog/php-security/)
 * [PHP Session Security Best Practices](https://github.com/sobstel/sesshin/wiki/PHP-Session-Security---Best-Practices)
 * [PHP Sessions in depth](https://www.phparch.com/2018/01/php-sessions-in-depth/)
 * [Session Management Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html)
