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
  - <span id="active">[5. Traits](5_traits.html)</span>
  - [6. Gestió d'errors i excepcions](6_gesti_derrors_i_excepcions.html)
      - [6.1. Excepcions](61_excepcions.html)
          - [Ampliant les excepcions](ampliant_les_excepcions.html)
      - [Exercicis pràctics](exercicis_prctics1.html)
  - [Crèdits, fonts i bibliografia](crdits_fonts_i_bibliografia.html)

<div id="topPagination">

[<span><span>« </span>Anterior</span>](exercicis_prctics0.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](6_gesti_derrors_i_excepcions.html)

</div>

<div id="main-wrapper">

<div id="main" class="section">

# 5\. Traits

<div class="iDevice emphasis0">

<div id="ta14_85" class="block iDevice_content">

Des de la seva versió 5.4.0, PHP implementa una metodologia de
reutilització de codi anomenada Traits.

Els trets ( «traits» en anglès) són un mecanisme de reutilització de
codi en llenguatges d'herència simple, com PHP. L'objectiu d'un tret és
el de reduir les limitacions pròpies de l'herència simple permetent que
els desenvolupadors reutilitzen a voluntat conjunts de mètodes sobre
diverses classes independents i pertanyents a classes jeràrquiques
diferents. La semàntica a l'hora combinar Traits i classes es defineix
de tal manera que redueixe la seva complexitat i s'eviten els problemes
típics associats a l'herència múltiple i als
[mixins](https://es.wikipedia.org/wiki/Mixin).

Un Trait és similar a una classe, però amb l'únic objectiu d'agrupar
funcionalitats molt específiques i d'una manera coherent. No es pot
instanciar directament un Trait. És per tant un afegit a l'herència
tradicional, i habilita la composició horitzontal de comportaments; és a
dir, permet combinar membres de classes sense haver d'usar herència.

<div class="highlighted-code language-php">

<div>

    <?php
    class Base {
        public function decirHola() {
            echo '¡Hola ';
        }
    }
    
    trait DecirMundo {
        public function decirHola() {
            parent::decirHola();
            echo 'Mundo!';
        }
    }
    
    class MiHolaMundo extends Base {
        use DecirMundo;
    }
    
    $o = new MiHolaMundo();
    $o->decirHola(); // Hola Mundo
    ?>

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

[<span><span>« </span>Anterior</span>](exercicis_prctics0.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](6_gesti_derrors_i_excepcions.html)

</div>

</div>
