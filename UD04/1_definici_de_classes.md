<div id="content">

[Omet navegació](#main)

<div id="emptyHeader" class="section">

</div>

  - [4. PHP - Classes i objectes](index.html)
  - [1. Introducció](1_introducci.html)
  - [2. Característiques bàsiques de la utilització d'objectes en
    PHP](2_caracterstiques_bsiques_de_la_utilitzaci_dobjectes_en_php.html)
      - <span id="active">[1. Definició de
        classes](1_definici_de_classes.html)</span>
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

[<span><span>«
</span>Anterior</span>](2_caracterstiques_bsiques_de_la_utilitzaci_dobjectes_en_php.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](constructor_i_destructor.html)

</div>

<div id="main-wrapper">

<div id="main" class="section">

# 1\. Definició de classes

<div class="iDevice emphasis0">

<div id="ta3_85" class="block iDevice_content">

La declaració de una classe en PHP es fa utilitzant la paraula `class`.
A continuació i entre claus, han de figurar els membres de la classe.
Convé fer-ho de forma ordenada, primer les propietats o atributs, i
després els mètodes, cadascun amb el seu codi respectiu.

<div class="highlighted-code language-php">

<div>

    class Producto {
        private $codigo;
        public $nombre;
        public $PVP;
        public function muestra() {
            print "<p>" . $this->codigo . "</p>";
        }
    }

</div>

</div>

Com comentàvem abans, és preferible que cada classe figure en el seu
propi fitxer (**`producte.php`**). A més, molts programadors prefereixen
utilitzar per a les classes noms que comencen per lletra majúscula, per
a, d'aquesta forma, distingir-los dels objectes i altres variables.

Una vegada definida la classe, podem usar la paraula **`new`** per a
instanciar objectes de la següent forma:

<div class="highlighted-code language-php">

<div>

    $p = new Producto();

</div>

</div>

Perquè la línia anterior s'execute sense error, prèviament hem d'haver
declarat la classe. Per a açò, en aqueix mateix fitxer hauràs de
incloure la classe posant alguna cosa com:

<div class="highlighted-code language-php">

<div>

    require('producto.php');

</div>

</div>

Els atributs de una classe són similars a les variables de PHP. És
possible indicar un valor en la declaració de la classe. En aquest cas,
tots els objectes que s'instancien a partir de aqueixa classe, partiran
amb aqueix valor per defecte en l'atribut.

Per a accedir des de un objecte a els seus atributs o a els mètodes de
la classe, has d'utilitzar l'operador fletxa (fixa't que només es posa
el símbol $ davant de el nom de l'objecte):

<div class="highlighted-code language-php">

<div>

    $p->nombre = 'Samsung Galaxy S';
    $p->mostra();

</div>

</div>

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

<div class="highlighted-code language-php">

<div>

    var $nombre;

</div>

</div>

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

<div class="highlighted-code language-php">

<div>

    private $codigo;
    public function setCodigo($nuevo_codigo) {
        if (noExisteCodigo($nuevo_codigo)) {
            $this->codigo = $nuevo_codigo;
            return true;
        }
        return false;
    }
    public function getCodigo() { return $this->codigo; }

</div>

</div>

Encara que no és obligatori, el nom del mètode que ens permet obtenir el
valor de un atribut sol començar per get, i el que ens permet
modificar-ho per set.

En PHP5 es van introduir els anomenats mètodes màgics, entre ells
`__set` i `__get`. Si es declaren aquests dos mètodes en una classe, PHP
els invoca automàticament quan des de un objecte s'intenta usar un
atribut no existent o no accessible. Per exemple, el codi següent simula
que la classe Producte té qualsevol atribut que vulguem usar.

<div class="highlighted-code language-php">

<div>

    class Producto {
        private $atributos = array();
        public function __get($atributo) {
            return $this->atributos[$atributo];
        }
        public function __set($atributo, $valor) {
            $this->atributos[$atributo] = $valor;
        }
    }

</div>

</div>

### La pseuodovariable $this

Quan des de un objecte s'invoca un mètode de la classe, a aquest se li
passa sempre una referència a l'objecte que ha fet la crida. Aquesta
referència s'emmagatzema en la pseudovariable `$this.` S'utilitza, per
exemple, en el codi anterior per a tenir accés a els atributs privats de
l'objecte (que només són accessibles des dels mètodes de la classe).

<div class="highlighted-code language-php">

<div>

    print "<p>" . $this->codigo . "</p>";

</div>

</div>

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

<div class="highlighted-code language-php">

<div>

    class DB {
        const USUARIO = 'dwes';
        ...
    }
    echo DB::USUARIO;

</div>

</div>

És important ressaltar que no és necessari que existisca cap objecte de
una classe per a poder accedir al valor de les constants que definisca.
A més, els seus noms solen escriure's en majúscules.

### Mètodes o atributs estàtics

Tampoc s'han de confondre les constants amb els membres estàtics de una
classe. En PHP5, una classe pot tenir atributs o mètodes estàtics, també
cridats a voltes atributs o mètodes de classe. Es defineixen utilitzant
la paraula clau static.

<div class="highlighted-code language-php">

<div>

    class Producte {
        private static $num_productes = 0;
        public static function nuevoProducto() {
            self::$num_productes++;
        }
       …
    }

</div>

</div>

Els atributs i mètodes estàtics no poden ser cridats des d'un objecte de
la classe utilitzant l'operador `->`. Si el mètode o atribut és públic,
haurà d'accedir-se utilitzant el nom de la classe i l'operador de
resolució d'àmbit.

<div class="highlighted-code language-php">

<div>

    Producto::nuevoProducto();

</div>

</div>

Si és privat, com l'atribut `$num_productos` en l'exemple anterior,
només es podrà accedir a ell des dels mètodes de la pròpia classe,
utilitzant la paraula `self`. De la mateixa forma que `$this` referencia
a l'objecte actual, `self` fa referència a la classe actual.

<div class="highlighted-code language-php">

<div>

    self::$num_productes ++;

</div>

</div>

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

[<span><span>«
</span>Anterior</span>](2_caracterstiques_bsiques_de_la_utilitzaci_dobjectes_en_php.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](constructor_i_destructor.html)

</div>

</div>
