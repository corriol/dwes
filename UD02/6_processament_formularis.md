---
layout: default
title: 6. Processament de formularis
parent: 2. Característiques bàsiques del llenguatge
nav_order: 6
has_children: false
---

# Processament de formularis
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}


## Introducció

### Què és una petició HTTP?

Una petició HTTP és una sol·licitud d'un recurs a un servidor. La petició es realitza a través d'una URL. Amb la petició s'envien també paràmetres.

Hi ha diferents mètodes (METHOD) per a realitzar una petició (GET, POST, PUT, DELETE, PATCH, etc.). Els més habituals són GET i POST. La resta són més utilitzats en les RESTful API.

![Sol·licitud HTTP](images/http-request.png)


### Mètodes GET i POST

#### Mètode GET

S'utilitza per a sol·licitar dades d'un recurs. Mostren els paràmetres que s'envien en la url. Es poden utilitzar directament en enllaços. El resultat es pot emmagatzemar en cache. Romanen en l'historial del navegador. La grandària dels paràmetres està limitat a 255 caràcters.

*Exemple de petició GET*
![Petició GET](images/get-example.png)

*Inspecció de la petició*
![Inspecció del petició GET](images/get-inspect.png)


#### Accedir a les dades de la petició GET

Per a accedir a les dades usem la variable superglobal `$_GET`.
    
`$_GET` és un array associatiu les claus del qual coincidiran amb els noms que li hem donat als paràmetres.

Per a accedir als paràmetres de la petició anterior:

```php
echo $_GET['nom'].' '.$_GET['cognom'];
```

{: .alert .alert-activity}
<div markdown="1">

### Exercici
{: .nocount .no_toc }

Crea una pàgina que reba com a paràmetre un nom i mostre el text ‘Benvingut [nom]!!!’ sent [nom] el nom has passat com a paràmetre.

Guarda-la amb el nom exemple_get.php.
</div>

#### Mètode POST

El mètode POST té les següents característiques:

* S'utilitza per a enviar dades a un recurs.
* Els paràmetres van en el cos de la petició, no són visibles per a l'usuari.
* La petició no es guarda en cache.
* No es pot utilitzar en un enllaç.
* No roman en l'historial.
* Se solen utilitzar en els formularis.
* No tenim la limitació de grandària dels paràmetres.

## Definició de formularis 

El formulari següent enviarà les dades a `index.php`.

Utilitza el mètode `post`:

```html
<form action="index.php" method="post">
    <label for="nom">Nom</label>
    <input type="text" name="nom" value="">
    <br />
    <label for="cognom">Cognom</label>
    <input type="text" name="cognom" value="">
    <br />
    Noms amb els quals podrem accedir als paràmetres en el servidor
    <br />
    <input type="submit" value="Enviar">
</form>
```
*Inspecció de la petició*
![Inspecció del petició POST](images/post-inspect.png)


### Accedir a les dades de la petició POST

Usem la variable superglobal `$_POST`. Funciona igual que `$_GET`, però amb els noms (atribut `name`) que li hem donat als camps del formulari.

En depuració podem mostar totes les dades rebudes:
        
```php
var_dump ($_POST);
print_r($_POST)
```

Per a mostrar les dades individualment:

```php
echo $_POST['nom'];
echo $_POST['cognom'];
```

{: .alert .alert-activity}
<div markdown="1">

### Creació d'un formulari
{: .nocount .no_toc }

Crea un formulari que tinga els següents camps:
        
* Data de naixement
* Email
* Observacions


En l'atribut `action` del formulari posarem el següent:

```php
action="<?= $_SERVER['PHP_SELF']; ?>"
```

Açò farà que siga la pròpia pàgina del formulari la que processe les dades del mateix.

En prémer enviar han d'aparèixer sota el formulari les dades que s'han introduït en el mateix.
</div>

### Accedir a paràmetres no existents

Açò ens diu que la clau date no existeix en l'array `$_POST`

### Verificar que el formulari s’ha enviat ###

Abans de mostrar les dades verificarem que s'haja enviat el formulari:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{

}
```

{: .alert .alert-activity}
<div markdown="1">

### Exercici
{: .nocount .no_toc }

Soluciona el problema dels paràmetres no enviats de l'exercici anterior.
</div>

https://developer.mozilla.org/en-US/docs/Learn/HTML/Forms/Form_validation


### Bones pràctiques en l’obtenció de dades des de l’exterior ($_GET, $_POST, etc.)

Cal seguir les següents bones pràctiques:

* No confieu mai (mai) en l’entrada des de l’exterior del vostre PHP.
* Sanegeu i valideu l’entrada de dades sempre.
* Les funcions filter_var () i filter_input () poden sanejar el text i validar els formats de text (per exemple, adreces de correu electrònic).
* Recordeu que l’entrada de dades no es limita a formularis enviats per l’usuari. Els fitxers carregats i descarregats, els valors de sessió, les dades de galetes i les dades de serveis web de tercers també són d’entrada estrangera.

## Valiadació de formularis 
### Bones pràctiques

1. Sanejar la entrada
   1. filter_input, filter_var
2. Valida les dades
   1. trim, empty, is_*, strlen
3. Escapa les eixides
   1. htmlspecialchars

### Validació del formulari

Hem de comprovar que les dades del formulari són correctes.

Validacions a realitzar:

1. Els camps requerits no han de quedar buits
2. Els camps email i data han de tenir el format esperat.
3. Tots els camps s'han de filtrar amb `htmlspecialchars`.

### Valors buits

1. Els camps requerits no deurien quedar-se buits.
2. Per a verificar que un valor no queda buit podem utilitzar la funció `empty` de PHP.

### Espais en blanc

1. Hem d'eliminar els espais en blanc del principi i final dels camps
2. S'utilitza la funció `trim`

### Escapar l'entrada

Sempre hem de filtrar l'entrada amb `htmlspecialchars` abans de mostrar el camp amb `echo` o similar.

### Comprovar l'email

Per a verificar si un email és correcte podem utilitzar la funció `filter_var` 

```php
filter_var($email, FILTER_VALIDATE_EMAIL)
```
o la funció `filter_input` quan obtenin les dades directament d'una font exterior.

Més informació en:

* [filter_var](http://php.net/manual/es/function.filter-var.php)
* [filter_input](http://php.net/manual/es/function.filter-input.php)

### Comprovar la data

* Per a comprovar la data devem crear una funció a aquest efecte.
* Podem utilitzar el mètode createFromFormat de la classe DateTime

http://php.net/manual/es/datetime.createfromformat.php

#### Exemple

```php
$data1 = "2001-05-02"; // Data és de tipus string
// DateTime::createFromFormat converteix una cadena de text a on objecte DateTime
// ens tornarà una instància de DateTime o FALSE en cas d'error.

$dt1 = DateTime::createFromFormat('Y-m-d', $data1);  

if ($dt1 === false) {
    echo "La data d'inici és incorrecta";
}
```

https://www.php.net/manual/es/function.strtotime.php

També és habitual trobar els controls de data separats en 3 elements input. Un per a l’any, l’altre per a mes i l’altre per al dia.

Altres comprovacions

    filter_input
        Obté una varible externa pel nom i la filtra.
    filter_var
        Filtra una variable en un filtre específic.

Exercici
    Modifica l'exercici anterior realitzant les validacions oportunes.