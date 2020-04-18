<div id="content">

[Omet navegació](#main)

<div id="emptyHeader" class="section">

</div>

  - [Inici](index.html)
  - [1. Maneig de PHPMyAdmin](1_maneig_de_phpmyadmin.html)
  - [2. Accés a la base de dades mitjançant PHP amb la llibreria
    PDO](2_accs_a_la_base_de_dades_mitjanant_php_amb_la_llibreria_pdo.html)
      - [2.1. Connexió a la base de
        dades](21_connexi_a_la_base_de_dades.html)
      - [2.2. Execució de consultes](22_execuci_de_consultes.html)
      - [2.3. Consultes preparades](23_consultes_preparades.html)
      - [2.4. Inserció, modificació i eliminació de dades de la
        BBDD](24_inserci_modificaci_i_eliminaci_de_dades_de_la_bbdd.html)
      - [2.5. Gestió d'errors en
        l'accés](25_gesti_derrors_en_laccs.html)
      - [Exercicis pràctics](exercicis_prctics.html)
  - [3. Simplificant l'accés a la base de
    dades](3_simplificant_laccs_a_la_base_de_dades.html)
      - [Exercicis pràctics](exercicis_prctics0.html)
  - [4. Transaccions](4_transaccions.html)
      - <span id="active">[Exercicis
        pràctics](exercicis_prctics1.html)</span>

<div id="topPagination">

[<span><span>« </span>Anterior</span>](4_transaccions.html)

</div>

<div id="main-wrapper">

<div id="main" class="section">

# Exercicis pràctics

<div class="iDevice emphasis1">

# Activitat: Emprar transaccions.

<div class="iDevice_inner">

<div class="iDevice_content_wrapper">

<div id="ta18_49_2" class="block iDevice_content">

Com que l'esborrat de registres de la taula Post depén de la relació amb
la taula Tag implementa:

1.  Comprova si l'entrada (post) té alguna etiqueta (tag) vinculada.
2.  En cas de tenir-la:
    1.  Inicia una transacció.
    2.  Esborra les etiquetes relacionades.
    3.  Esborra l'entrada.
    4.  Si no hi ha cap error:
        1.  Accepta (commit) la transacció.
    5.  Si hi ha errors:
        1.  Aborta (rollback) els canvis.

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

[<span><span>« </span>Anterior</span>](4_transaccions.html)

</div>

</div>
