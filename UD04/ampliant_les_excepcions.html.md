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
      - [2. Utilització d'objectes](2_utilitzaci_dobjectes.html)
  - [3. Herència](3_herncia.html)
      - [Exercicis pràctics](exercicis_prctics.html)
  - [4. Interfícies](4_interfcies.html)
      - [Exercicis pràctics](exercicis_prctics0.html)
  - [5. Traits](5_traits.html)
  - [6. Gestió d'errors i excepcions](6_gesti_derrors_i_excepcions.html)
      - [6.1. Excepcions](61_excepcions.html)
          - <span id="active">[Ampliant les
            excepcions](ampliant_les_excepcions.html)</span>
      - [Exercicis pràctics](exercicis_prctics1.html)
  - [Crèdits, fonts i bibliografia](crdits_fonts_i_bibliografia.html)

<div id="topPagination">

[<span><span>« </span>Anterior</span>](61_excepcions.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](exercicis_prctics1.html)

</div>

<div id="main-wrapper">

<div id="main" class="section">

# Ampliant les excepcions

<div class="iDevice emphasis0">

<div id="ta23_85" class="block iDevice_content">

És possible crear excepcions personalitzades heretant de la classe
Exception.

<div class="highlighted-code language-php">

<div>

    class NegativeIdException extends Exception {
        public function __construct($message = null) {
            $this->message = $message ?: 'ID Negatiu.';
            parent::__construct($message);
        }
    }

</div>

</div>

Després l'utilitzarem així:

<div class="highlighted-code language-php">

<div>

    try {
        $post = new Post(-1, "Hola món", "Aquest és el primer post del blog", new DateTime(), "Vicent");
    } catch (NegativeIdException $e) {
         echo "No es pot posar un ID negatiu\n";
    } catch (Exception $e) {
        echo "Excepció desconeguda".  $e->getMessage();
    }

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

[<span><span>« </span>Anterior</span>](61_excepcions.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](exercicis_prctics1.html)

</div>

</div>
