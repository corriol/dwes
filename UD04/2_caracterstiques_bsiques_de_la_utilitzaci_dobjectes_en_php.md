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
Segurament tot, o la majoria de el que acabes de veure, ja ho coneixies,
i és fins i tot probable que sàpies utilitzar algun llenguatge de
programació orientat a objectes, així que anem a veure directament les
peculiaritats pròpies de PHP en el que fa referència a la POO.

Com ja has vist en les unitats anteriors, especialment amb les
extensions per a utilitzar bases de dades, amb PHP pots utilitzar dos
estils de programació: estructurada i orientada a objectes.

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

En la versió actual, PHP5, es ha reescrit i potenciat el suport
d'orientació a objectes del llenguatge, ampliant les seues
característiques i millorant el seu rendiment i el seu funcionament
general. Encara que anirem detallant i explicant cadascuna posteriorment
amb deteniment, les característiques de POO que suporta PHP5 inclouen:

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

    
## Definició de classes

La declaració de una classe en PHP es fa utilitzant la paraula `class`.
A continuació i entre claus, han de figurar els membres de la classe.
Convé fer-ho de forma ordenada, primer les propietats o atributs, i
després els mètodes, cadascun amb el seu codi respectiu.

```php
class Producto {
    private $codigo;
    public $nombre;
    public $pvp;
    public function muestra() {
        print "<p>" . $this->codigo . "</p>";
    }
}
```
Com comentàvem abans, és preferible que cada classe figure en el seu
propi fitxer (**`Producto.php`**). A més, molts programadors prefereixen
utilitzar per a les classes noms que comencen per lletra majúscula, per
a, d'aquesta forma, distingir-los dels objectes i altres variables.

Una vegada definida la classe, podem usar la paraula **`new`** per a
instanciar objectes de la següent forma:

```php
$producto = new Producto();
```

Perquè la línia anterior s'execute sense error, prèviament hem d'haver
declarat la classe. Per a açò, en aqueix mateix fitxer hauràs de
incloure la classe posant alguna cosa com:

```php
require('Producto.php');
```

Els atributs de una classe són similars a les variables de PHP. És
possible indicar un valor en la declaració de la classe. En aquest cas,
tots els objectes que s'instancien a partir de aqueixa classe, partiran
amb aqueix valor per defecte en l'atribut.

Per a accedir des de un objecte a els seus atributs o a els mètodes de
la classe, has d'utilitzar l'operador fletxa (fixa't que només es posa
el símbol $ davant de el nom de l'objecte):

```php
$producto->nombre = 'Samsung Galaxy S';
$producto->muestra();
```

Quan es declara un atribut, s'ha d'indicar el seu nivell d'accés. Els
principals nivells són:

  - `public`. Els atributs declarats com `public` poden utilitzar-se
    directament per els objectes de la classe. És el cas de l'atribut
    $nom anterior.
  - `private`. Els atributs declarats com `private` només poden ser
    accedits i modificats per els mètodes definits en la classe, no
    directament per els objectes de la mateixa. És el cas de l'atribut
    `$codigo`.

En PHP4 no es podia definir nivell d'accés per als atributs de una
classe, per la qual cosa tots es precedien de la paraula var.

```php
var $nombre;
```


Avui dia, encara que encara és acceptat per PHP5, no es recomana el seu
ús. Si trobes algun codi que ho utilitze, tingues en compte que té el
mateix efecte que `public`.

Un dels motius per a crear atributs privats és que el seu valor forma
part de la informació interna de l'objecte i no ha de formar part de la
seua interfície. Un altre motiu és mantenir cert control sobre els seus
possibles valors. Per exemple, si no vols que es puga canviar lliurement
el valor del codi de un producte. O necessites conèixer quin serà el nou
valor abans d'assignar-ho. En aquests casos, se solen definir aqueixos
atributs com a privats i a més es creen dins de la classe mètodes per a
permetre'ns obtenir i/o modificar els valors d'aqueixos atributs. Per
exemple:

```php
private $codigo;
public function setCodigo($nuevo_codigo) {
    if (noExisteCodigo($nuevo_codigo)) {
        $this->codigo = $nuevo_codigo;
        return true;
    }
    return false;
}
public function getCodigo() { return $this->codigo; }
```

Encara que no és obligatori, el nom del mètode que ens permet obtenir el
valor de un atribut sol començar per get, i el que ens permet
modificar-ho per set.

En PHP5 es van introduir els anomenats mètodes màgics, entre ells
`__set` i `__get`. Si es declaren aquests dos mètodes en una classe, PHP
els invoca automàticament quan des de un objecte s'intenta usar un
atribut no existent o no accessible. Per exemple, el codi següent simula
que la classe Producte té qualsevol atribut que vulguem usar.

```php
class Producto {
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

    $producte = new Producte();

```

En PHP5 pots definir en les classes mètodes constructors, que s'executen
quan es crea l'objecte.

El constructor de una classe ha d'anomenar-se __construct. Es poden
utilitzar, per exemple,  per assignar valors a atributs.

```php

    class Producte {
        private static $num_productes = 0;
        private $codigo;
        public function __construct()
        {
            self::$num_productes++;
        }
       …
    }
```

El constructor de una classe pot cridar a altres mètodes o tenir
paràmetres, en aquest cas hauran de passar-se quan es crea l'objecte.
No obstant açò, només pot haver-hi un mètode constructor en cada classe.

```php
    class Producto {
        private static $num_productos = 0;
        private $codigo;
        public function __construct($codigo) {
            $this->$codigo = $codigo;
            self::$num_productos++;
        }
      ...
    }
    $p = new Producto('GALAXYS');

```

Per exemple, si com en aquest exemple, definim un constructor en el qual
calga passar el codi, sempre que instancies un nou objecte d'aqueixa
classe hauràs de indicar el seu codi.

També és possible definir un mètode destructor, que ha d'anomenar-se
__destruct()  que s'executarà quan s'elimine l'objecte. 

```php

    class Producto {
        private static $num_productos = 0;
        private $codigo;
        public function __construct($codigo) {
            $this->$codigo = $codigo;
            self::$num_productos++;
        }
        public function __destruct() {
            self::$num_productos--;
        }
        ...
    }
    $p = new Producto('GALAXYS');

```


### La pseuodovariable $this

Quan des de un objecte s'invoca un mètode de la classe, a aquest se li
passa sempre una referència a l'objecte que ha fet la crida. Aquesta
referència s'emmagatzema en la pseudovariable `$this.` S'utilitza, per
exemple, en el codi anterior per a tenir accés a els atributs privats de
l'objecte (que només són accessibles des dels mètodes de la classe).

```php
print "<p>" . $this->codigo . "</p>";
```

En la documentació de PHP tens més informació sobre els mètodes màgics:
<http://es.php.net/manual/es/language.oop5.magic.php>

### Constants de classe

A més de mètodes i propietats, en una classe també es poden definir
constants, utilitzant la paraula const. És important que no confongues
els atributs amb les constants. Són conceptes diferents: les constants
no poden canviar el seu valor (òbviament, de ací el seu nom), no usen el
caràcter $ i, a més, el seu valor va sempre entre cometes i està
associat a la classe, és a dir, no existeix una còpia de el mateix en
cada objecte. Per tant, per a accedir a les constants de una classe,
s'ha d'utilitzar el nom de la classe i l'operador `::`, anomenat
operador de resolució d'àmbit (que s'utilitza per a accedir a els
elements de una classe).

```php
class DB {
    const USUARIO = 'dwes';
      ...
}
echo DB::USUARIO;
```

És important ressaltar que no és necessari que existisca cap objecte de
una classe per a poder accedir al valor de les constants que definisca.
A més, els seus noms solen escriure's en majúscules.

 
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

Tampoc s'han de confondre les constants amb els membres estàtics de una
classe. En PHP5, una classe pot tenir atributs o mètodes estàtics, també
cridats a voltes atributs o mètodes de classe. Es defineixen utilitzant
la paraula clau static.

```php

class Producte {
    private static $num_productes = 0;
    public static function nuevoProducto() {
        self::$num_productes++;
    }
       …
}
```

Els atributs i mètodes estàtics no poden ser cridats des d'un objecte de
la classe utilitzant l'operador `->`. Si el mètode o atribut és públic,
haurà d'accedir-se utilitzant el nom de la classe i l'operador de
resolució d'àmbit.

```php
Producto::nuevoProducto();
```

Si és privat, com l'atribut `$num_productos` en l'exemple anterior,
només es podrà accedir a ell des dels mètodes de la pròpia classe,
utilitzant la paraula `self`. De la mateixa forma que `$this` referencia
a l'objecte actual, `self` fa referència a la classe actual.

```php
self::$num_productes ++;
```

Els atributs estàtics de una classe s'utilitzen per a guardar informació
general sobre la mateixa, com pot ser el nombre d'objectes que s'han
instanciat. Només existeix un valor de l'atribut, que s'emmagatzema a
nivell de classe.

Els mètodes estàtics solen realitzar alguna tasca específica o retornar
un objecte concret. Per exemple, les classes matemàtiques solen tenir
mètodes estàtics per a realitzar logaritmes o arrels quadrades. No té
sentit crear un objecte si l'única cosa que volem és realitzar una
operació matemàtica.

Els mètodes estàtics es criden des de la classe. No és possible
cridar-los des d'un objecte i per tant, no podem usar `$this` dins d'un
mètode estàtic.

## Utilització d'objectes

Ja saps com instanciar un objecte utilitzant `new`, i com accedir a els
seus mètodes i atributs públics amb l'operador fletxa:

```php
    $producte = new Producte();
    $producte->nom = 'Samsung Galaxy S';
    $producte->mostra();
```

Una vegada creat un objecte, pots utilitzar l'operador `instanceof` per
a comprovar si és o no una instància de una classe determinada.

```php
if ($producte instanceof Producte) {
        …
}
```

A més, en PHP5 s'inclouen una sèrie de funcions útils per al
desenvolupament d'aplicacions utilitzant POO.

Funcions d'utilitat per a objectes i classes en PHP5

  - get\_class
  - class\_exists
  - get\_declared\_classes
  - class\_alias
  - get\_class\_methods
  - method\_exists
  - get\_class\_vars
  - get\_object\_vars
  - property\_exists

Des de PHP5, pots indicar en les funcions i mètodes de quina classe han
de ser els objectes que es passen com a paràmetres. Per a açò, has
d'especificar el tipus abans del paràmetre.

```php
    public function venProducte(Producte $producte) {
        …
    }
```

Si quan es realitza la crida, el paràmetre no és del tipus adequat, es
produeix un error que podries capturar. A més, tingues en compte que
només funciona amb objectes (i a partir de PHP5.1 també amb arrays).

Una característica de la POO que has de tenir molt en compte és què
succeeix amb els objectes quan els passes a una funció, o simplement
quan executes un codi com el següent:

```php
    $p = new Producte();
    $p->nom = 'Samsung Galaxy S';
    $a = $p;
```

En PHP4, l'última línia del codi anterior crea un nou objecte amb els
mateixos valors de l'original, de la mateixa forma que es copia
qualsevol altre tipus de variable. Si després de fer la còpia es
modifica, per exemple, l'atribut 'nom' de un dels objectes, l'altre
objecte no es veuria modificat.

No obstant açò, en PHP5 aquest comportament varia. El codi anterior
simplement crearia un nou identificador de el mateix objecte. Açò és,
quan s'utilitze un qualsevol dels identificadors per a canviar el valor
de algun atribut, aquest canvi es veuria també reflectit en accedir
utilitzant l'altre identificador. Recorda que, encara que hi haja dos o
més identificadors de el mateix objecte, en realitat tots es refereixen
a l'única còpia que s'emmagatzema del mateix.

Per tant, a partir de PHP5 no pots copiar un objecte utilitzant
l'operador =. Si necessites copiar un objecte, has d'utilitzar clone. En
utilitzar els atributs del mateix en un nou objecte.  
sobre un objecte existent, es crea una còpia de

A més, existeix una forma senzilla de personalitzar la còpia per a cada
classe particular. Per exemple, pot succeir que vulgues copiar tots els
atributs menys algun. En el nostre exemple, almenys el codi de cada
producte ha de ser diferent i, per tant, potser no tinga sentit
copiar-ho en crear un nou objecte. Si aquest fóra el cas, pots crear un
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
invoca, si fos possible, al mètode \_\_clone () de l'objecte). No es pot
cridar el mètode \_\_clone () d'un objecte directament.

```php
$copia\_objecte = clone $objecte;
```

Quan es clona un objecte, PHP5 durà a terme una còpia superficial de les
propietats de l'objecte. Les propietats que siguin referències a altres
variables, mantindran les referències.

Si els atributs són objectes no es clonaran.

### Iteració d'objectes

PHP 5 ofereix una manera per definir objectes, de manera que és possible
recórrer una llista d'elements amb, per exemple, una sentència foreach.
Per defecte, s'utilitzaran totes les propietats visibles per a la
iteració.


## Exercicis pràctics

{:.alert .alert-activity}
<div markdown="1">

### Activitat: Bloc I
{:.no_toc .nocount}

1.  Crea una classe anomenada `Post` que continga els següents camps:
    `id`, `titol`, `contingut`, `data de publicació` i `autor`.
2.  Has d'implementar el constructor, els getters, els setters i els
    mètodes de còpia i conversió a cadena corresponents.
3.  Guarda-la en un fitxer anomenat Post.php
4.  Crea una classe anomenada `Blog` que continga un array d'objectes
    `Post`.
5.  Has de crear un mètode per a afegir un post al Blog i un altre per a
    eliminar-lo.
6.  També has de crear un mètode per a convertir l'objecte Blog a
    string, de manera que en HTML es mostre un post en cada línia
    (hauràs de posar \<br /\> per als salts de línia)
7.  Guarda aquesta classe en un fitxer anomenat Blog.php.
8.  Finalment, crea una pàgina PHP anomenada index.php que creu un
    objecte Bloc, afegisca tres posts a la mateixa i la mostre en la
    pàgina.
9.  Després, elimina el primer Post, fes una còpia del segon, i
    afegeix-lo al bloc i torna a mostrar el Bloc en la pàgina.
</div>



{:.alert .alert-activity}
<div markdown="1">

### Activitat: Bloc II
{:.no_toc .nocount}


1.  A partir d'una còpia de l'exercici anterior canvia la classe Blog i
    afig un mètode de còpia.
2.  Després modifica index.php perquè afegisca tres post en el Blog,
    faça una còpia del Blog, elimine el primer post i modifique el
    títol del segon post.
3.  Finalment, mostra els dos blocs un a continuació de l'altre.

</div>

{:.alert .alert-activity}
<div markdown="1">

### Activitat: Bloc III
{:.no_toc .nocount}

1.  A partir d'una còpia de l'exercici anterior canvia la classe Blog de
    manera que continga inicialment tres objectes Post.
2.  El fitxer index.php mostrarà la llista de posts del bloc i al costat
    de cada títol, mostrarà un enllaç que diga “Veure entrada”.
3.  En prémer l'enllaç anirem a un document anomenat post.php que
    mostrarà la informació del post seleccionat.
</div>




