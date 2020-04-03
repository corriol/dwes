<div id="content">

[Omet navegació](#main)

<div id="emptyHeader" class="section">

</div>

  - [4. PHP - Classes i objectes](index.html)
  - [1. Introducció](1_introducci.html)
  - [2. Característiques bàsiques de la utilització d'objectes en
    PHP](2_caracterstiques_bsiques_de_la_utilitzaci_dobjectes_en_php.html)
      - [1. Definició de classes](1_definici_de_classes.html)
          - <span id="active">[Constructor i
            destructor](constructor_i_destructor.html)</span>
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

[<span><span>« </span>Anterior</span>](1_definici_de_classes.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](2_utilitzaci_dobjectes.html)

</div>

<div id="main-wrapper">

<div id="main" class="section">

# Constructor i destructor

<div class="iDevice emphasis0">

<div id="ta5_85" class="block iDevice_content">

Com ja has vist, per a instanciar objectes de una classe s'utilitza la
paraula new:

<div class="highlighted-code language-php">

<div>

    $p = new Producte();

</div>

</div>

En PHP5 pots definir en les classes mètodes constructors, que s'executen
quan es crea l'objecte.

El constructor de una classe ha d'anomenar-se \_\_construct. Es poden
utilitzar, per exemple,  per assignar valors a atributs.

<div class="highlighted-code language-php">

<div>

    class Producte {
        private static $num_productes = 0;
        private $codigo;
        public function __construct()
        {
            self::$num_productes++;
        }
       …
    }

</div>

</div>

El constructor de una classe pot cridar a altres mètodes o tenir
paràmetres, en aquest cas hauran de passar-se quan es crea l'objecte.
No obstant açò, només pot haver-hi un mètode constructor en cada classe.

<div class="highlighted-code language-php">

<div>

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

</div>

</div>

Per exemple, si com en aquest exemple, definim un constructor en el qual
calga passar el codi, sempre que instancies un nou objecte d'aqueixa
classe hauràs de indicar el seu codi.

També és possible definir un mètode destructor, que ha d'anomenar-se
\_\_destruct()  que s'executarà quan s'elimine l'objecte. 

<div class="highlighted-code language-php">

<div>

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

</div>

</div>

  
  

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

[<span><span>« </span>Anterior</span>](1_definici_de_classes.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](2_utilitzaci_dobjectes.html)

</div>

</div>
