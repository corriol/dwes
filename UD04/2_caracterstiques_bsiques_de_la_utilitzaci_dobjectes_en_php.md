---
layout: default
title: 2. Característiques bàsiques de l'ús d'objectes en PHP
parent: 4. Classes i objectes
nav_order: 1
has_children: false
---

# Característiques bàsiques de l'ús d'objectes en PHP
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}


## Introducció
Segurament tot, o la majoria del que acabes de veure, ja ho coneixies,
i és fins i tot probable que sàpigues utilitzar algun llenguatge de
programació orientat a objectes, així que anem a veure directament les
peculiaritats pròpies de PHP en el que fa referència a la POO.

En PHP podemos utilitzar els dos paradigmes de la programació: estructurada i orientada a objectes.

```php
 // utilitzant programació estructurada
$dwes = mysqli_connect('localhost', 'dwes', 'abc123.', 'dwes');
// utilizant POO
$dwes = new mysqli();
$dwes->connect('localhost', 'dwes', 'abc123.', 'dwes');
```

No obstant açò, el llenguatge PHP original no es va dissenyar amb
característiques d'orientació a objectes. Només a partir de la versió 3,
es van començar a introduir alguns trets de POO en el llenguatge. Açò es
va potenciar en la versió 4, encara que encara de forma molt
rudimentària. Per exemple, en PHP4:

  - Els objectes es passen sempre per valor, no per referència.
  - No es pot definir el nivell d'accés per als membres de la classe.
    Tots són públics.
  - No existeixen les interfícies.
  - No existeixen mètodes destructors.

En PHP5 es va reescriure i potenciar el suport d'orientació a objectes del llenguatge, ampliant les seues
característiques i millorant el seu rendiment i el seu funcionament general. Les característiques de POO que 
suporta PHP5 inclouen:

  - Mètodes estàtics.
  - Mètodes constructors i destructors.
  - Herència.
  - Interfícies.
  - Classes abstractes.

Entre les característiques que no inclou PHP5, i que pots conèixer
d'altres llenguatges de programació, estan:

  - Herència múltiple.
  - Sobrecàrrega de mètodes (tenir diversos mètodes amb el mateix nom,
    però amb funcionalitats diferents. Els mètodes sobrecarregats han de
    tenir diferents paràmetres. Quan es diu al mètode, s'utilitza una o
    una altra versió en funció dels paràmetres amb que es realitze la
    trucada (podent distingir per el seu nombre i per el seu tipus,
    segons el llenguatge de programació utilitzat)) (inclosos els
    mètodes constructors).
  - Sobrecàrrega d'operadors (similar a la sobrecàrrega de mètodes. Es
    poden definir diverses funcionalitats a un mateix operador, i
    s'utilitzarà una o una altra en funció del tipus dels operands que
    s'usen en cada instant).

PHP 7 incorpora millores en el rendiment i el tipat d'arguments, valors de retorn i atributs (des de la versió 7.4)
## Definició de classes

La declaració de una classe en PHP es fa utilitzant la paraula `class`. A continuació i entre claus, han de 
figurar els membres de la classe.

Convé fer-ho de forma ordenada, primer les propietats o atributs, i
després els mètodes, cadascun amb el seu codi respectiu.

```php
class Product {
    private string $code;
    public string $name;
    public float $price;
    public function show() {
        print "<p>" . $this->code . "</p>";
    }
}
```
Com comentàvem abans, és preferible que cada classe figure en el seu
propi fitxer (**`Product.php`**). A més, molts programadors prefereixen
utilitzar per a les classes noms que comencen per lletra majúscula, per
a, d'aquesta forma, distingir-los dels objectes i altres variables.

Una vegada definida la classe, podem usar la paraula **`new`** per a
instanciar objectes de la següent forma:

```php
$product = new Product();
```

Perquè la línia anterior s'execute sense error, prèviament hem d'haver
declarat la classe. Per a açò, en aqueix mateix fitxer hauràs de
incloure la classe posant alguna cosa com:

```php
require('Product.php');
```

Els atributs de una classe són similars a les variables de PHP. És
possible indicar un valor en la declaració de la classe. En aquest cas,
tots els objectes que s'instancien a partir de aqueixa classe, partiran
amb aqueix valor per defecte en l'atribut.

Per a accedir des d'un objecte als seus atributs o als mètodes de
la classe, has d'utilitzar l'operador fletxa (fixa't que només es posa
el símbol $ davant del nom de l'objecte):

```php
$product->name = 'Samsung Galaxy S';
$product->show();
```

Quan es declara una propietat o un mètode, s'ha d'indicar el seu nivell d'accés (o visibilitat). Els
principals nivells són:

  - `public`. Els atributs declarats com `public` poden utilitzar-se
    directament per els objectes de la classe. És el cas de l'atribut
    `$name` anterior.
  - `private`. Els atributs declarats com `private` només poden ser
    accedits i modificats per els mètodes definits en la classe, no
    directament per els objectes de la mateixa. És el cas de l'atribut
    `$code`.
  - `protected`. Una propietat o mètode `protected` sols pot ser accedida per la classe on es defineix o per un subclasse. No és accessible des de fora.  


Un dels motius per a crear atributs privats és que el seu valor forma
part de la informació interna de l'objecte i no ha de formar part de la
seua interfície. Un altre motiu és mantenir cert control sobre els seus
possibles valors. Per exemple, si no vols que es puga canviar lliurement
el valor del codi d'un producte. O necessites conèixer quin serà el nou
valor abans d'assignar-lo. En aquests casos, se solen definir aqueixos
atributs com a privats i a més es creen dins de la classe mètodes per a
permetre'ns obtenir i/o modificar els valors d'aqueixos atributs. Per
exemple:

```php
private $code;
public function setCode($newCode) {
    if (!existsCode($newCode)) {
        $this->code = $newCode;
        return true;
    }
    return false;
}
public function getCode() { return $this->code; }
```

Encara que no és obligatori, el nom del mètode que ens permet obtenir el
valor de un atribut sol començar per `get`, i el que ens permet
modificar-lo per `set`.

En PHP5 es van introduir els anomenats mètodes màgics, entre ells
`__set` i `__get`. Si es declaren aquests dos mètodes en una classe, PHP
els invoca automàticament quan des de un objecte s'intenta usar un
atribut no existent o no accessible. Per exemple, el codi següent simula
que la classe `Product` té qualsevol atribut que vulguem usar.

```php
class Product {
    private $atributos = array();
    public function __get($atributo) {
        return $this->atributos[$atributo];
    }
    public function __set($atributo, $valor) {
        $this->atributos[$atributo] = $valor;
    }
}
```

### Constructor i destructor

Com ja has vist, per a instanciar objectes de una classe s'utilitza la
paraula `new`:

```php
$product = new Product();
```

En PHP7 pots definir en les classes mètodes constructors, que s'executen
quan es crea l'objecte.

El constructor d'una classe ha d'anomenar-se `__construct`. Es poden utilitzar, per exemple, 
per assignar valors a atributs.

```php
class Product {
    private static $numProducts = 0;
    private $code;
    public function __construct()
    {
        self::$numPoducts++;
    }
    …
}
```

El constructor d'una classe pot cridar a altres mètodes o tenir
paràmetres, en aquest cas hauran de passar-se quan es crea l'objecte.
No obstant açò, només pot haver-hi un mètode constructor en cada classe.

```php
class Product {
    private static $numProducts = 0;
    private $code;
    public function __construct($code) {
        $this->$code = $code;
        self::$numProducts++;
    }
    ...
}
$p = new Product('GALAXYS');

```

Per exemple, si com en aquest exemple, definim un constructor en el qual
calga passar el codi, sempre que instancies un nou objecte d'aqueixa
classe hauràs de indicar el seu codi.

També és possible definir un mètode destructor, que ha d'anomenar-se
`__destruct()` que s'executarà quan s'elimine l'objecte.

```php

class Product {
private static $numProducts = 0;
    private $code;
    public function __construct($code) {
        $this->$code = $code;
        self::$numProducts++;
    }
    public function __destruct() {
        self::$numProducts--;
    }
    ...
}
$p = new Product('GALAXYS');

```


### La pseuodovariable $this

Quan des d'un objecte s'invoca un mètode de la classe, a aquest se li
passa sempre una referència a l'objecte que ha fet la crida. Aquesta
referència s'emmagatzema en la pseudovariable `$this.` S'utilitza, per
exemple, en el codi anterior per a tenir accés als atributs privats de
l'objecte (que només són accessibles des dels mètodes de la classe).

```php
print "<p>" . $this->code . "</p>";
```

En la documentació de PHP tens més informació sobre els mètodes màgics:
<http://es.php.net/manual/es/language.oop5.magic.php>

### Constants de classe

A més de mètodes i propietats, en una classe també es poden definir constants, 
utilitzant la paraula `const`. És important que no confongues els atributs amb
les constants. Són conceptes diferents: les constants no poden canviar el seu 
valor (òbviament, de ací el seu nom), no usen el caràcter `$`, s'escriuen en 
majúscules (per convenció)  i el seu valor està associat a la classe, és a dir, 
no existeix una còpia del mateix en cada objecte. 

Per tant, per a accedir a les constants d'una classe, s'ha d'utilitzar el 
nom de la classe i l'operador `::`, anomenat operador de resolució d'àmbit 
(que s'utilitza per a accedir a els elements de una classe).

```php
class DB {
    const USUARIO = 'dwes';
      ...
}
echo DB::USUARIO;
```

És important ressaltar que no és necessari que existisca cap objecte de
una classe per a poder accedir al valor de les constants que definisca.
 
{:.alert .alert-info}
<div markdown="1">

### PHP Standard Recommendations
{:.no_toc .nocount}

El PHP Framework Interop Group és un grup de treball on està involucrada gent que treballa en diferents _frameworks_ amb
l'objectiu de posar en comú les tècniques que usen en els diferents projectes per poder integrar-les i compartir-les 
i poder treballar millor junts.

Les recomanacions [PSR-1](https://www.php-fig.org/psr/psr-1/) i [PSR-12](https://www.php-fig.org/psr/psr-12/) 
són recomanacions sobre l'estil de programació amb l'objectiu d'aplicar estandards comuns i 
així facilitar la lectura del codi realitzar per altres.

Algunes recomancions que cal seguir:
1.  Class names MUST be declared in `StudlyCaps`. For instance: `Product`.
2.  Class constants MUST be declared in all upper case with underscore separators. For example: const IMAGE_PATH.      
3.  About property names the only recommendation is to be consistent. So, we SHOULD use `$camelCase`. The same recommendations applies for variables.
4.  Method names MUST be declared in `camelCase()`.

Més informació en [Estándares](http://coppeldev.github.io/php/standards/psr-1.html)
</div>

### Mètodes o atributs estàtics

Tampoc s'han de confondre les constants amb els membres estàtics d'una
classe. En PHP7, una classe pot tenir atributs o mètodes estàtics, també
anomenats a vegades atributs o mètodes de classe. Es defineixen utilitzant
la paraula clau `static`.

```php
class Product {
    private static $numProducts = 0;
    public static function newProduct() {
        self::$numProducts++;
    }
       …
}
```

Els atributs i mètodes estàtics no poden ser cridats des d'un objecte de
la classe utilitzant l'operador `->`. Si el mètode o atribut és públic,
haurà d'accedir-se utilitzant el nom de la classe i l'operador de
resolució d'àmbit.

```php
Product::newProduct();
```

Si és privat, com l'atribut `$numProducts` en l'exemple anterior,
només es podrà accedir a ell des dels mètodes de la pròpia classe,
utilitzant la paraula `self`. De la mateixa forma que `$this` referencia
a l'objecte actual, `self` fa referència a la classe actual.

```php
self::$numProductes++;
```

Els atributs estàtics d'una classe s'utilitzen per a guardar informació general sobre la mateixa, com pot ser 
el nombre d'objectes que s'han instanciat. Només existeix un valor de l'atribut, que s'emmagatzema a
nivell de classe.

Els mètodes estàtics solen realitzar alguna tasca específica o retornar un objecte concret. Per exemple, 
les classes matemàtiques solen tenir mètodes estàtics per a realitzar logaritmes o arrels quadrades. No té
sentit crear un objecte si l'única cosa que volem és realitzar una operació matemàtica.

Els mètodes estàtics es criden des de la classe. No és possible
cridar-los des d'un objecte i per tant, no podem usar `$this` dins d'un
mètode estàtic.

## Utilització d'objectes

Ja saps com instanciar un objecte utilitzant `new`, i com accedir a els
seus mètodes i atributs públics amb l'operador fletxa:

```php
$product = new Product();
$product->name= 'Samsung Galaxy S';
$product->show();
```

Una vegada creat un objecte, pots utilitzar l'operador `instanceof` per
a comprovar si és o no una instància de una classe determinada.

```php
if ($product instanceof Product) {
        …
}
```

Des de PHP7, pots indicar en les funcions i mètodes de quina classe han
de ser els objectes que es passen com a paràmetres. Per a açò, has
d'especificar el tipus abans del paràmetre.

```php
public function sellProduct(Product $product) {
    …
}
```

Si quan es realitza la crida, el paràmetre no és del tipus adequat, es
produeix un error que podries capturar. 

Una característica de la POO que has de tenir molt en compte és què
succeeix amb els objectes quan els passes a una funció, o simplement
quan executes un codi com el següent:

```php
$p = new Product();
$p->name = 'Samsung Galaxy S';
$a = $p;
```

El codi anterior simplement crearia un nou identificador del mateix objecte. Açò és,
quan s'utilitze qualsevol dels identificadors per a canviar el valor de algun atribut, aquest canvi es 
veuria també reflectit en accedir utilitzant l'altre identificador. Recorda que, encara que hi haja dos o
més identificadors del mateix objecte, en realitat tots es refereixen a l'única còpia que s'emmagatzema 
del mateix.

Per tant, a partir de PHP5 no pots copiar un objecte utilitzant
l'operador `=`. Si necessites copiar un objecte, has d'utilitzar `clone`. En
utilitzar `clone` sobre un objecte existent, es crea una còpia del mateix.

A més, existeix una forma senzilla de personalitzar la còpia per a cada
classe particular. Per exemple, pot succeir que vulgues copiar tots els
atributs menys algun. En el nostre exemple, almenys el codi de cada
producte ha de ser diferent i, per tant, potser no tinga sentit
copiar-lo en crear un nou objecte. Si aquest fóra el cas, pots crear un
mètode de nom `__clone` en la classe. Aquest mètode es cridarà
automàticament després de copiar tots els atributs en el nou objecte.

### Comparació d'Objectes

En utilitzar l'operador de comparació (==), es comparen d'una manera
senzilla les variables de cada objecte, és a dir: dues instàncies d'un
objecte són iguals si tenen els mateixos atributs i valors (els valors
es comparen amb ==), i són instàncies de la mateixa classe.

Quan s'utilitza l'operador identitat (===), les variables d'un objecte
són idèntiques si i només sí fan referència a la mateixa instància de
la mateixa classe.

### Clonació d'Objectes

Per crear una còpia d'un objecte s'utilitza la paraula clau `clone` (que
invoca, si fos possible, al mètode  `__clone()` de l'objecte). No es pot
cridar el mètode `__clone()` d'un objecte directament.

```php
$copia_objecte = clone $objecte;
```

Quan es clona un objecte, PHP7 durà a terme una còpia superficial de les
propietats de l'objecte. Les propietats que siguen referències a altres
variables (per exemple, objectes), mantindran les referències.

Compte: si els atributs són objectes no es clonaran.

### Iteració d'objectes

PHP 7 ofereix una forma de definir objectes, de manera que és possible
recórrer una llista d'elements amb, per exemple, una sentència `foreach`.
Per defecte, s'utilitzaran totes les propietats visibles per a la
iteració.

## Exercicis pràctics

{:.alert .alert-activity}
<div markdown="1">

### Activitat: Pel·lícules
{:.no_toc .nocount}

#### Objectius
Els objectius de l'activitat són els següents:
* Aprendre a crear classes
* Instanciar objectes
* Accedir a les seues propietats.

#### Enunciat
1. Crea la classe Movie en la carpeta src del projecte (**cal usar tipat esctricte**).
2. Els atributs seran:
    * id (enter)
    * title (string)
    * tagline (string)
    * releaseDate (DateTime)
    * starsRating (float)
    * poster (string)
3. Crea els _setters_ i els _getters_.
4. La constant de classe `POSTER_PATH` contindrà la ruta al directori dels posters.
5. Modifica el projecte de forma que `$movies` siga un array d’objectes `Movie`.
6. Modifica'l també perquè les pàgines index.php i movies.php funcionen correctament
</div>


{:.alert .alert-activity}
<div markdown="1">

### Activitat: Pel·licules II
{:.no_toc .nocount}

#### Objectius
Els objectius de l'activitat són els següents:
* Repassar el concepte de paràmetres per referència.
* Clonar objectes.

#### Enunciat

1. Crea una pel·lícula copiant-la (=) de la darrera pel·lícula de l'array.  
2. Comprova en movies.php que s'ha insertat i que tenen les mateixes dades.
3. Ara canvia el títol de la còpia afegint-li "(Còpia)" al títol que tenía. Per exemple si el títol era "Ava" ara serà 
"Ava (còpia)".
4. Comprova en `movies.php` que s'ha insertat i que és diferent a l'original. Tenen les mateixes dades? Per què?
5. Repassa els apunts i soluciona-ho. 
</div>

{:.alert .alert-activity}
<div markdown="1">

### Activitat: Pel·lícules III
{:.no_toc .nocount}

#### Objectius
Els objectius de l'activitat són els següents:
* Seguir construint el projecte.
* Treballar amb objectes.

#### Enunciat
1. El títol de la pel·licula  serà un enllaç a `single-page.php?id=[atribut id de la pel·lícula]`.
2. La pàgina `single-page.php` mostrarà les dades de la pel·licula que tinga l'`id` que s'ha passat pel 
querystring.
3. Post usar `array_filter` per trobar l'objecte que té l'id indicat.
</div>
