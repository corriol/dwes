---
layout: default 
title: 5. Autenticació d'usuaris
parent: 8. Seguretat i control d'errors 
nav_order: 5
---

# Autenticació d'usuaris
{: .no_toc .nocount }


1. TOC 
{:toc}



## Autenticació basada en HTTP

El protocol HTTP ofereix un mètode senzill per autenticar els usuaris. El procés és el següent:

* El servidor web ha de proveir algun mètode per definir els usuaris que s'utilitzaran i com es poden autentificar. A més, s'hauran de definir els recursos als quals es restringeix l'accés i quina llista de control d'accés (ACL - llista de permisos sobre un objecte (fitxer, directori, etc.), que indica quins usuaris poden utilitzar l'objecte i les accions concretes que poden realitzar amb el mateix (lectura, escriptura, esborrat, etc.)) s'aplica a cada un.
* Quan un usuari no autenticat intenta accedir a un recurs restringit, el servidor web respon amb un error de "Accés no autoritzat" (codi 401).
* El navegador rep l'error i obre una finestra per sol·licitar a l'usuari que es autentifiqui mitjançant el seu nom i contrasenya.
* La informació d'autenticació de l'usuari s'envia a servidor, que la verifica i decideix si permet o no l'accés a el recurs sol·licitat. Aquesta informació es manté en el navegador per utilitzar-se en posteriors peticions a aquest servidor.

Al servidor web Apache, el que has estat utilitzant en anteriors unitats, hi ha una utilitat en línia d'ordres, `htpasswd`, que permet emmagatzemar en un fitxer una llista d'usuaris i els seus respectives contrasenyes. La informació relativa a les contrasenyes s'emmagatzema xifrada; tot i així, és convenient crear aquest fitxer en un lloc no accessible pels usuaris de servidor web.

http://httpd.apache.org/docs/2.0/es/howto/auth.html

Per exemple, per crear el fitxer d'usuari i afegir-li el usuari "dwes", pots fer:

```console
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

| Directiva    | significat                                                                                                                                                                                                       |
| ------------ | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| AuthName     | Nom de domini que es farà servir en l'autenticació. Si el client s'autentica correctament, aquesta mateixa informació d'autenticació s'utilitzarà automàticament en la resta de les pàgines de el mateix domini. |
| AuthType     | Mètode d'autenticació que es farà servir. A més de l'mètode Basic, Apache també permet utilitzar el mètode Digest.                                                                                               |
| AuthUserFile | Camí de l'arxiu de credencials que has creat amb htpasswd.                                                                                                                                                       |
| Require      | Permet indicar que només puguin accedir alguns usuaris o grups d'usuaris concrets. Si indiquem "valid-user", podran accedir tots els usuaris que es s'autentifiquin correctament.                                |

A més hauràs de assegurar-te que en la configuració d'Apache s'utilitza la directiva AllowOverride perquè s'apliqui correctament la configuració que figura en els fitxers `.htaccess`.

http://httpd.apache.org/docs/2.0/es/mod/core.html#allowoverride

La sentència sudo htpasswd -c users admin afegeix un nou usuari amb nom "admin" al fitxer "users" que hem creat anteriorment.
veritable
fals

A l'incloure l'opció -c el que fem és crear un nou fitxer, amb la qual cosa eliminem el contingut anterior del mateix.

## Mecanismes d'autenticació ##

Des PHP pots accedir a la informació d'autenticació HTTP que ha introduït l'usuari utilitzant l'array superglobal `$_SERVER`.

| valor                       | contingut                                                  |
| --------------------------- | ---------------------------------------------------------- |
| `$_SERVER['PHP_AUTH_USER']` | Nom d'usuari que s'ha introduït.                           |
| `$_SERVER['PHP_AUTH_PW']`   | Clau introduïda.                                           |
| `$_SERVER['AUTH_TYPE']`     | Mètode HTTP usat per autentificar. Pot ser Basic o Digest. |

És a dir, que si crees una pàgina web que mostre els valors d'aquestes variables, i prepares el servidor web per utilitzar autenticació HTTP, quan accedisques a aquesta pàgina amb l'usuari "dwes" obtindràs alguna cosa com el següent:

```html
<!DOCTYPE html PUBLIC "- // W3C // DTD HTML 4.01 Transitional // EN" "http://www.w3.org/TR/html4/loose.dtd ">
<!-- Desenvolupament Web a Entorn Servidor -->
<!-- Tema 4: Desenvolupament d'aplicacions web amb PHP -->
<!-- Exemple: Autenticació HTTP -->
<html>
<head>
    <meta http-equiv = "content-type" content = "text / html; charset = UTF-8">
    <title>Exemple Tema 4: Autenticació HTTP </ title>
    <link href = "dwes.css" rel = "stylesheet" type = "text / css">
</head>
<body>
    <?php
    echo "Nom d'usuari:". $_ SERVER['PHP_AUTH_USER']. "<br />";
    echo "Contrasenya:". $_ SERVER['PHP_AUTH_PW']. "<br />";
    echo "Mètode d'autenticació:". $_SERVER['AUTH_TYPE']. "<br />";
    ?>
</body>
</html>
```

Si no introdueixes un usuari/contrasenya vàlids, el navegador et mostrarà l'error 401.

A més, en PHP pots utilitzar la funció `header` per forçar que el servidor envie un error de "Accés no autoritzat" (codi 401). D'aquesta manera no cal utilitzar fitxers `.htaccess` per indicar-li a Apache quins recursos estan restringits. En el seu lloc, pots afegir les següents línies en les teves pàgines PHP:

```php
<?php
if (!isset($_ SERVER['PHP_AUTH_USER'])) {
    header ('WWW-Authenticate: Basic Realm = "Contingut restringit"');
    header ('HTTP/1.0 401 Unauthorized');
    echo "Usuari no reconegut!";
    exit;
}
?>
```

La funció `header` envia capçaleres HTTP (bloc de dades que forma part de l'protocol HTTP i s'envia abans de la informació pròpia que es transmet. Permet especificar codis d'estat, accions requerides a servidor, o el tipus d'informació que es transmet), però s'ha d'utilitzar abans que es mostre res per pantalla. En cas contrari, obtindràs un error.

http://es.php.net/manual/es/function.header.php

Amb el codi anterior, la pàgina envia un error 401, el que força al navegador a sol·licitar les credencials d'accés (nom d'usuari i contrasenya). Si s'introdueixen, s'executa la resta de la pàgina i es mostra el seu contingut. En aquest cas, caldria afegir algun codi per comprovar que el nom d'usuari i contrasenya són vàlids, tal com veurem a continuació. Si es prem el botó "Cancel", es mostra el missatge d'error que s'indica. Modifica la pàgina anterior utilitzant la funció header perquè sol·liciti les credencials a l'usuari.

Hauràs de crear una pàgina similar a l'anterior, i afegir el codi per forçar l'error 401 abans de qualsevol altre.

```php
<!DOCTYPE html PUBLIC "- // W3C // DTD HTML 4.01 Transitional // EN" " http://www.w3.org/TR/html4/loose.dtd ">
<!-- Desenvolupament Web a Entorn Servidor -->
<!-- Tema 4: Desenvolupament d'aplicacions web amb PHP -->
<!-- Exemple: Funció header per autenticació HTTP -->
<?php
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
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
<?php
    echo "Nom d'usuari:". $_ SERVER['PHP_AUTH_USER']. "<br />";
    echo "Contrasenya:". $_ SERVER['PHP_AUTH_PW']. "<br />";
?>
</body>
</html>
```

## Incorporació de mètodes d'autenticació a una aplicació web 
Si utilitzes la funció `header` per forçar el navegador a sol·licitar credencials HTTP, l'usuari introduirà un nom i una contrasenya. Però el servidor no ha de verificar aquesta informació; hauràs de ser tu qui proveïsca un mètode per comprovar que les credencials que ha introduït l'usuari són correctes.

El mètode més simple és incloure en el codi PHP de la teva pàgina les sentències necessàries per comparar les dades introduïdes amb altres dades fixos. Per exemple, per a permetre l'accés a un usuari "dwes" amb contrasenya "abc123.", pots fer:

```php
<?php
    if ($_SERVER['PHP_AUTH_USER']!='dwes' || $_SERVER['PHP_AUTH_PW']!='abc123.') {
        header('WWW-Authenticate: Basic Realm = "Contingut restringit"');
        header('HTTP / 1.0 401 Unauthorized');
        echo "Usuari no reconegut!";
    exit;
    }
?>
```

Recorda que el codi PHP no s'envia al navegador, de manera que la informació d'autenticació que introdueixis en el codi no serà visible per l'usuari. No obstant això, això farà el teu codi menys portable. Si necessites modificar el nom d'usuari o la contrasenya, hauràs de fer modificacions en el codi. A més, no podràs permetre a l'usuari introduir la seva pròpia contrasenya.

Una solució millor és utilitzar un emmagatzematge extern per als noms d'usuari i les seves
contrasenyes. Per això podries emprar un fitxer de text, o millor encara, una base de dades. La informació d'autenticació podrà estar aïllada en la seva pròpia base de dades, o compartir espai d'emmagatzematge amb les dades que utilitze la teva aplicació web.

Si vols emmagatzemar la informació dels usuaris a la base de dades "`dwes`", has de crear una nova taula en la seva estructura. Per a això, revisa i executa aquestes sentències SQL.

```sql
-- Seleccionem la base de dades
USE dwes;

-- Creem la taula
CREATE TABLE usuaris (
usuari VARCHAR (20) NOT NULL PRIMARY KEY,
contrasenya VARCHAR (32) NOT NULL
) ENGINE = InnoDB;

-- Creem l'usuari dwes
INSERT INTO usuaris (usuari, contrasenya) VALUES
('dwes', 'e8dc8ccd5e5f9e3a54f07350ce8a2d3d');

```

Tot i que es podrien emmagatzemar les contrasenyes en text pla, és millor fer-ho en format encriptat. En l'exemple anterior, per a l'usuari "dwes" s'emmagatzema el hash MD5 (mètode per generar un resum d'un text o un document, de tal manera que a partir de l'resum obtingut no és possible recuperar el text original ni estar un altre text a partir de el qual s'obtingui el mateix resum. Es diu hash a el resum obtingut a l'aplicar una funció hash. Una de les funcions hash més esteses és MD5, que genera 128 bits com a resum (normalment es representa mitjançant una cadena de text de 28 caràcters o mitjançant 32 dígits hexadecimals)) corresponent a la contrasenya "abc123.". En PHP pots utilitzar la funció md5 per calcular el hash MD5 d'una cadena de text.

http://es.php.net/manual/es/function.md5.php

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
            WHERE usuari = '$_ SERVER[PHP_AUTH_USER]' AND
            contrasenya = md5 ( '$_ SERVER[PHP_AUTH_PW]') ";
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
<!DOCTYPE html PUBLIC "- // W3C // DTD HTML 4.01 Transitional // EN" "http://www.w3.org/TR/html4/loose.dtd " >
<!-- Desenvolupament Web a Entorn Servidor -->
<!-- Tema 4: Desenvolupament d'aplicacions web amb PHP -->
<!-- Exemple: Utilització de MySQL per autenticació HTTP -->
<html>
    <head>
        <meta http-equiv = "content-type" content = "text / html; charset = UTF-8">
        <title> Exemple Tema 4: Utilització de MySQL per autenticació HTTP </ title>
        <link href = "dwes.css" rel = "stylesheet" type = "text / css">
    </head>
    <body>
    <?php
        echo "Nom d'usuari:". $_ SERVER [ 'PHP_AUTH_USER']. "<br />";
        echo "Hash de la contrasenya:" .md5 ($_ SERVER [ 'PHP_AUTH_PW']). "<br />";
    ?>
    </body>
</html>
```

Les dues possibilitats que hem vist per sol·licitar a l'usuari que s'autentiqui via HTTP són la creació de el fitxer .htaccess, i la utilització de la funció header de PHP. Quina d'aquestes dues formes serà preferible si vols que els privilegis d'accés a la teva aplicació variïn en funció del dia de la setmana (per exemple, uns usuaris que puguin accedir de dilluns a divendres i altres diferents el cap de setmana)?

* El fitxer .htaccess
* La funció header

Si utilitzes la funció header per enviar les capçaleres HTTP, has d'escriure tu el codi que decideix si l'usuari s'ha entrat correctament i se li permet accedir o no. Per tant, pots incloure les regles addicionals que vulguis, per exemple, donar-li o no accés en funció del dia de la setmana.
