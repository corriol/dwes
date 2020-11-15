---
layout: default
parent: 5. Accés a dades amb PHP
nav_order: 5
has_children: false
---

# Errors freqüents

## Problemes amb $releaseDate

La funció `fechAll()` amb el mode `FETCH_CLASS` torna un array d'objectes de la classe que li hem passat
com a 2n paràmetre. El que fa és assignar cada camp de la taula a una propietat de la classe, *si la propietat no existeix
la crea*.  

Al treballar en tipat estricte `declare(strict_types=1)`, si el tipus d'una propietat no és escalar (tipus bàsic),
la funció `fetchAll()` amb el mode `FECHT_CLASS` falla.   

Eixe és el problema si usem `private DateTime $release_date`, que com la data la intenta guardar en `string` i la 
propietat és de tipus  `DateTime` mostra un error. 

La solució més elegant que he trobat i que ens permet usar el mode `FETCH_PROPS_LATE` en el `fechtAll()` es tractar 
d'aprofitar que quan recuperem dades d'una taula amb `FETCH_CLASS` si les propietats que no eixisteixen  
en la classe les crea.

Això dispara el mètode màgic `__set($name, $value)` i perment que puguem llegir el valor i convertir-lo en 
`DateTime` sobre la propietat $releaseDate.

Per això hem de llevar la propietat `$release_date` i gestionar-ho en  el `__set` de la següent manera:

```php
private int $id;
private string $title;
private string $overview;
// private string $release_date;
private DateTime $releaseDate;
private float $starsRating;
private ?string $tagline;
private string $poster;

public function __set(string $name , $value) {
    switch ($name) {
        case "release_date":
            $this->releaseDate = DateTime::createFromFormat("Y-m-d", $value);
            break;
    }
}
```
El constructor ja no serà necessari.

## Valors que poden ser null
Es possible que alguns valor en una taula puguen ser `null` (no ser obligatoris). Recordeu que `null` en PHP és un 
tipus així que també, a consequència del tipat estricte, fallarà.

La solució la podeu veure en l'exemple anterior, el símbol `?` india que la propietat `$tagline` potser `string` o `null`.

Òbviament, si un camp pot ser `null`, s'hauria d'indicar en la base de dades i no hauria de ser obligatori 
en els formularis. 
 
## Els fitxers es pugen quan hi ha errors
En els formularis d'inserció i edició si hi ha un error de validació en algun camp i s'ha pujat una imatge, la imatge
es puja i es guarda igualment.
 
## L'entitat `movie` o `partner` no es carrega
Hi ha alguns casos en que l'entitat `movie` o `partner` no es carrega adequadament si s'envia el formulari d'edició amb
errors. El motiu és perquè per a carregar-se depén el _querystring_.

Per exemple:

```php
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (empty($id)) {
    $errors[] = "404 Not found";
} else {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare('SELECT * FROM movie WHERE id=:id');
    $stmt->bindValue("id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, Movie::class);
    $movie = $stmt->fetch();
}
```
En el vídeo a mi en funcionava per casualitat (ho explicaré després) perquè per un error el formulari s'enviava a la
url amb querystring, per exemple, `http://00-vicent.local/movies-edit.php?id=245`.

## URL estranya  

El tema de la URL estranya està motivada perquè en alguns sistemes, en el meu per exemple, puc fer açò:

```php
<? $_SERVER["PHP_SELF"] ?>
```
Hem permet usat `<?` en lloc de `<?php`. El motiu el desconec. Per això a mi no m'escrivia res, m'havia oblidat de 
posar el `=` i. En el cas de Pepe i David no, perquè el seu sistema no ho interpreta com codi PHP 
i suposa que es HTML, per això en enviar el formulari els eixia la URL estranya.    


## Validació i separació de lògica i presentació

Tant la validació de les dades rebudes del formulari com la separació de la lògica i la presentació són bones
pràctiques que s'han de fer sempre. 

## Paràmetres

Els paràmetres de les consultes preparades no es poden usar com si foren les marques  `%s` de `sprintf()`, sols
es poden aplicar en el WHERE o el HAVING de la forma que hem vist.


```php
$stmt->bindValue("id","$id", PDO::PARAM_INT); 
```

En el codi anterior sobrnm les cometes de `"$id"` ja que $id és enter i el paràmetre també ho ha de ser, 
en este cas s'està passant un enter a string perquè `bindValue` el passe internament a enter.

L'error bé perquè en el filtre fèiem `"%$text%"`, ahí és correcte perquè estem fent una expansió de la varible (una mena
de concatenació), ja que és el paràmetre d'un LIKE.
