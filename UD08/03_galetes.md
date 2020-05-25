---
layout: default
title: 2. Galetes (cookies)
nav_order: 2
parent: 8. Autenticació d'usuaris i control d'accés 
---
 

# Galetes (*cookies*) #
{: .no_toc .nocount}




Una galeta és un fitxer de text que un lloc web guarda a l'entorn de l'usuari de navegador. El seu ús més típic és l'emmagatzematge de les preferències de l'usuari (per exemple, l'idioma en que s'han de mostrar les pàgines), perquè no hagi de tornar a indicar-les la propera vegada que visiteu el lloc.

Si utilitzes Firefox com a navegador, pots accedir a `Desenvolupador web` &ndash; `Inspector d'emmagatzematge` des del menú principal. Entre les seves característiques et permet consultar i editar les galetes emmagatzemades en el mateix.

![Inspeccionar galetes en Firefox](images/galetes-firefox.png)

En PHP, per emmagatzemar una galeta al navegador de l'usuari, pots utilitzar la funció `setcookie`. L'únic paràmetre obligatori que has de fer servir és el nom de la galeta, però admet diversos paràmetres més opcionals.

{:.alert .alert-info }
<div markdown="1">
### Informació
{: .nocount .no_toc }
Per a més informació consulta: [https://www.php.net/manual/es/function.setcookie.php](https://www.php.net/manual/es/function.setcookie.php).
</div>

Per exemple, si vols emmagatzemar en una galeta el nom d'usuari que es va transmetre a les credencials HTTP (és només un exemple, no és en absolut aconsellable emmagatzemar informació relativa a la seguretat en les galetes), pots fer:

```php
setcookie ("nom_usuari", $_SERVER['PHP_AUTH_USER'], time () + 3600);
```

Els dos primers paràmetres són el nom de la galeta i el seu valor. El tercer és la data de caducitat de la mateixa (una hora des del moment en què s'execute). En cas de no figurar aquest paràmetre, la galeta s'eliminarà quan es tanqui el navegador. Tingues en compte que també es poden aplicar restriccions a les pàgines de el lloc que poden accedir a una galeta en funció de la ruta.

Les galetes es transmeten entre el navegador i el servidor web de la mateixa manera que les credencials que acabes de veure; utilitzant les capçaleres de el protocol HTTP. **Per això, les
sentències `setcookie` s'han d'enviar abans que el navegador mostri cap informació a pantalla.**

El procés de recuperació de la informació que emmagatzema una galeta és molt simple. quan accedeixes a un lloc web, el navegador li envia de forma automàtica tot el contingut de les galetes que emmagatzemi relatives a aquest lloc en concret. Des PHP pots accedir a aquesta informació per mitjà de l'array `$_COOKIE`.

{:.alert .alert-info}
<div markdown="1">
### Important
{: .nocount .no_toc }
Sempre que utilitzes galetes en una aplicació web, heu de tenir en compte que en última instància la seva disponibilitat està controlada pel client. Per exemple, alguns usuaris deshabiliten les galetes al navegador perquè pensen que la informació que emmagatzemen pot suposar un potencial problema de seguretat. O la informació que emmagatzemen pot arribar a perdre perquè el usuari decideix formatar l'equip o simplement eliminar-les de sistema.
</div>
Si un cop emmagatzemada una galeta al navegador vols eliminar abans que expire, pots utilitzar la mateixa funció `setcookie` però indicant una data de caducitat anterior a l'actual.

Sobre el mateix exercici anterior, emmagatzema en una galeta l'últim instant en què el usuari va visitar la pàgina. Si és la seva primera visita, mostra un missatge de benvinguda. En cas contrari, mostra la data i hora de la seva anterior visita.

Revisa la solució proposada. Hauràs utilitzar la funció `setcookie` per guardar l'instant de la seva anterior visita i mostrar el seu contingut utilitzant l'array `$_COOKIE`.

```php
<?php
// Si l'usuari encara no s'ha entrat, demanem les credencials
if (!isset ($_ SERVER['PHP_AUTH_USER'])) {
    header ('WWW-Authenticate: Basic realm = "Contingut restringit"');
    header ("HTTP / 1.0 401 Unauthorized");
    exit;
}
// Si ja ha enviat les credencials, les vam comprovar amb la base de dades
else {
    // Connectem a la base de dades
    @$dwes = new mysqli ( "localhost", "dwes", "abc123.", "Dwes");
    $error = $dwes->connect_errno;
    // Si es va establir la connexió
    if ($error == null) {
        date_default_timezone_set('Europe/Madrid');
        // Executem la consulta per comprovar si existeix
        // aquesta combinació d'usuari i contrasenya
        $sql ​​= "SELECT usuari FROM usuaris
            WHERE usuari = '${_ SERVER [' PHP_AUTH_USER ']}' AND
            contrasenya = md5 ( '${_ SERVER [' PHP_AUTH_PW ']}') ";
        $resultat = $dwes-> query ($sql);
        // Si no existeix, es tornen a demanar les credencials
        if ($resultat-> num_rows == 0) {
            header ( 'WWW-Authenticate: Basic realm = "Contingut restringit"');
            header ( "HTTP / 1.0 401 Unauthorized");
            exit;
        } else {
            if (isset($_ COOKIE [ 'ultimo_login'])) {
                $ultimo_login = $_COOKIE [ 'ultimo_login'];
            }
            setcookie ( "ultimo_login", time (), time () + 3600);
        }
        $resultat->close();
        $dwes->close();
    }
}
?>
<!DOCTYPE html PUBLIC "- // W3C // DTD HTML 4.01 Transitional // EN" " http://www.w3.org/TR/html4/loose.dtd ">
<!-- Desenvolupament Web a Entorn Servidor -->
<!-- Tema 4: Desenvolupament d'aplicacions web amb PHP -->
<!-- Exemple: Galetes al autenticació HTTP -->
<html>
<head>
<meta http-equiv = "content-type" content = "text / html; charset = UTF-8">
<title> Exemple Tema 4: Galetes al autenticació HTTP </ title>
<link href = "dwes.css" rel = "stylesheet" type = "text / css">
</head>
<body>
<?php
    if ($error == null) {
        echo "Nom d'usuari:". $_ SERVER['PHP_AUTH_USER']. "<br />";
        echo "Hash de la contrasenya:" .md5 ($_ SERVER [ 'PHP_AUTH_PW']). "<br />";
        if (isset ($ultimo_login))
            echo "Darrer login:". date ( "d/m/ i \ a \ l \ a \ s H: i", $ultimo_login);
        else
            echo "Benvingut. Aquesta és la seva primera visita.";
    }
    else
        echo "S'ha produït l'error $error. <br />";
?>
</body>
</html>
```

{: .alert .alert-info}
<div markdown="1">
### Reflexiona ###
{: .nocount .no_toc }
Quina és la durada per defecte d'una galeta si no s'indica la data de caducitat,
com en la següent crida a la funció setcookie?

```php
setcookie ("idioma", "espanyol");
```

1. Fins que es tanque el navegador de l'usuari.
2. 1 hora.
</div>
