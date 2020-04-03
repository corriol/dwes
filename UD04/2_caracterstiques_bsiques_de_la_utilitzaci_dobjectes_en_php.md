<div id="content">

[Omet navegació](#main)

<div id="emptyHeader" class="section">

</div>

  - [4. PHP - Classes i objectes](index.html)
  - [1. Introducció](1_introducci.html)
  - <span id="active">[2. Característiques bàsiques de la utilització
    d'objectes en
    PHP](2_caracterstiques_bsiques_de_la_utilitzaci_dobjectes_en_php.html)</span>
      - [1. Definició de classes](1_definici_de_classes.html)
          - [Constructor i destructor](constructor_i_destructor.html)
      - [2. Utilització d'objectes](2_utilitzaci_dobjectes.html)
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

[<span><span>« </span>Anterior</span>](1_introducci.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](1_definici_de_classes.html)

</div>

<div id="main-wrapper">

<div id="main" class="section">

# 2\. Característiques bàsiques de la utilització d'objectes en PHP

<div class="iDevice emphasis0">

<div id="ta2_85" class="block iDevice_content">

Segurament tot, o la majoria de el que acabes de veure, ja ho coneixies,
i és fins i tot probable que sàpies utilitzar algun llenguatge de
programació orientat a objectes, així que anem a veure directament les
peculiaritats pròpies de PHP en el que fa referència a la POO.

Com ja has vist en les unitats anteriors, especialment amb les
extensions per a utilitzar bases de dades, amb PHP pots utilitzar dos
estils de programació: estructurada i orientada a objectes.

<div class="highlighted-code language-php">

<div>

``` 
 // utilitzant programació estructurada
$dwes = mysqli_connect('localhost', 'dwes', 'abc123.', 'dwes');
// utilizant POO
$dwes = new mysqli();
$dwes->connect('localhost', 'dwes', 'abc123.', 'dwes');
```

</div>

</div>

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

[<span><span>« </span>Anterior</span>](1_introducci.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](1_definici_de_classes.html)

</div>

</div>
