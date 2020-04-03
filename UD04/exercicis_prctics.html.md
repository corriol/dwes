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
      - <span id="active">[Exercicis
        pràctics](exercicis_prctics.html)</span>
  - [4. Interfícies](4_interfcies.html)
      - [Exercicis pràctics](exercicis_prctics0.html)
  - [5. Traits](5_traits.html)
  - [6. Gestió d'errors i excepcions](6_gesti_derrors_i_excepcions.html)
      - [6.1. Excepcions](61_excepcions.html)
          - [Ampliant les excepcions](ampliant_les_excepcions.html)
      - [Exercicis pràctics](exercicis_prctics1.html)
  - [Crèdits, fonts i bibliografia](crdits_fonts_i_bibliografia.html)

<div id="topPagination">

[<span><span>« </span>Anterior</span>](3_herncia.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](4_interfcies.html)

</div>

<div id="main-wrapper">

<div id="main" class="section">

# Exercicis pràctics

<div class="iDevice emphasis1">

# Activitat: Bloc I

<div class="iDevice_inner">

<div class="iDevice_content_wrapper">

<div id="ta7_48_2" class="block iDevice_content">

1.  Crea una classe anomenada `Post` que continga els següents camps:
    id, titol, contingut, data de publicació i autor.
2.  Has d'implementar el constructor, els getters, els setters i els
    mètodes de còpia i conversió a cadena corresponents.
3.  Guarda-la en un fitxer anomenat Post.php
4.  Crea una classe anomenada Blog que continga un array d'objectes
    Post.
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

</div>

</div>

</div>

<div class="iDevice emphasis1">

# Activitat: Bloc II

<div class="iDevice_inner">

<div class="iDevice_content_wrapper">

<div id="ta8_49_2" class="block iDevice_content">

1.  A partir d'una còpia de l'exercici anterior canvia la classe Blog i
    afig un mètode de còpia.
2.  Després modifica index.php perquè afegisca tres post en el Blog,
    faça una còpia del Blog, elimine el primer post i modifique el
    títol del segon post.
3.  Finalment, mostra els dos blocs un a continuació de l'altre.

</div>

</div>

</div>

</div>

<div class="iDevice emphasis1">

# Activitat: Bloc III

<div class="iDevice_inner">

<div class="iDevice_content_wrapper">

<div id="ta9_50_2" class="block iDevice_content">

1.  A partir d'una còpia de l'exercici anterior canvia la classe Blog de
    manera que continga inicialment tres objectes Post.
2.  El fitxer index.php mostrarà la llista de posts del bloc i al costat
    de cada títol, mostrarà un enllaç que diga “Veure entrada”.
3.  En prémer l'enllaç anirem a un document anomenat post.php que
    mostrarà la informació del post seleccionat.

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

[<span><span>« </span>Anterior</span>](3_herncia.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](4_interfcies.html)

</div>

</div>
