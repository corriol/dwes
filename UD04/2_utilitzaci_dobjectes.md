<div id="content">

[Omet navegació](#main)

<div id="emptyHeader" class="section">

</div>

  - [4. PHP - Classes i objectes](index.html)
  - [1. Introducció](1_introducci.html)
  - [2. Característiques bàsiques de la utilització d'objectes en
    PHP](2_caracterstiques_bsiques_de_la_utilitzaci_dobjectes_en_php.html)
      - [1. Definició de classes](1_definici_de_classes.html)
          - [Constructor i destructor](constructor_i_destructor.html)
      - <span id="active">[2. Utilització
        d'objectes](2_utilitzaci_dobjectes.html)</span>
  - [3. Herència](3_herncia.html)
      - [Exercicis pràctics](exercicis_prctics.html)
  - [4. Interfícies](4_interfcies.html)
      - [Exercicis pràctics](exercicis_prctics0.html)
  - [5. Traits](5_traits.html)
  - [6. Gestió d'errors i excepcions](6_gesti_derrors_i_excepcions.html)
      - [6.1. Excepcions](61_excepcions.html)
          - [Ampliant les excepcions](ampliant_les_excepcions.html)
      - [Exercicis pràctics](exercicis_prctics1.html)
  - [Crèdits, fonts i bibliografia](crdits_fonts_i_bibliografia.html)

<div id="topPagination">

[<span><span>« </span>Anterior</span>](constructor_i_destructor.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](3_herncia.html)

</div>

<div id="main-wrapper">

<div id="main" class="section">

# 2\. Utilització d'objectes

<div class="iDevice emphasis0">

<div id="ta4_85" class="block iDevice_content">

Ja saps com instanciar un objecte utilitzant `new`, i com accedir a els
seus mètodes i atributs públics amb l'operador fletxa:

<div class="highlighted-code language-php">

<div>

    $p = new Producte();
    $p->nom = 'Samsung Galaxy S';
    $p->mostra();

</div>

</div>

Una vegada creat un objecte, pots utilitzar l'operador `instanceof` per
a comprovar si és o no una instància de una classe determinada.

<div class="highlighted-code language-php">

<div>

    if ($p instanceof Producte) {
        …
    }

</div>

</div>

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

<div class="highlighted-code language-php">

<div>

    public function venProducte(Producte $p) {
        …
    }

</div>

</div>

Si quan es realitza la crida, el paràmetre no és del tipus adequat, es
produeix un error que podries capturar. A més, tingues en compte que
només funciona amb objectes (i a partir de PHP5.1 també amb arrays).

Una característica de la POO que has de tenir molt en compte és què
succeeix amb els objectes quan els panses a una funció, o simplement
quan executes un codi com el següent:

<div class="highlighted-code language-php">

<div>

    $p = new Producte();
    $p->nom = 'Samsung Galaxy S';
    $a = $p;

</div>

</div>

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

</div>

</div>

<div class="iDevice emphasis0">

<div id="ta11_85" class="block iDevice_content">

## Comparació d'Objectes

En utilitzar l'operador de comparació (==), es comparen d'una manera
senzilla les variables de cada objecte, és a dir: dues instàncies d'un
objecte són iguals si tenen els mateixos atributs i valors (els valors
es comparen amb ==), i són instàncies de la mateixa classe.

Quan s'utilitza l'operador identitat (===), les variables d'un objecte
són idèntiques si i només sí fan referència a la mateixa instància de
la mateixa classe.

</div>

</div>

<div class="iDevice emphasis0">

<div id="ta12_85" class="block iDevice_content">

## Clonació d'Objectes

Per crear una còpia d'un objecte s'utilitza la paraula clau `clone` (que
invoca, si fos possible, al mètode \_\_clone () de l'objecte). No es pot
cridar el mètode \_\_clone () d'un objecte directament.

$ Copia\_objecte = clone $objecte;

Quan es clona un objecte, PHP5 durà a terme una còpia superficial de les
propietats de l'objecte. Les propietats que siguin referències a altres
variables, mantindran les referències.

Si els atributs són objectes no es clonaran.

</div>

</div>

<div class="iDevice emphasis0">

<div id="ta13_85" class="block iDevice_content">

## Iteració d'objectes

PHP 5 ofereix una manera per definir objectes, de manera que és possible
recórrer una llista d'elements amb, per exemple, una sentència foreach.
Per defecte, s'utilitzaran totes les propietats visibles per a la
iteració.

</div>

</div>

<div id="packageLicense" class="cc cc-by-sa">

<span>Llicenciat sota la </span> [Llicència Creative Commons
Reconeixement
CompartirIgual 4.0](http://creativecommons.org/licenses/by-sa/4.0/)

</div>

</div>

</div>

<div id="bottomPagination">

[<span><span>« </span>Anterior</span>](constructor_i_destructor.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](3_herncia.html)

</div>

</div>
