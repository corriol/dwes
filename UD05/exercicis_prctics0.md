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
      - <span id="active">[Exercicis
        pràctics](exercicis_prctics0.html)</span>
  - [4. Transaccions](4_transaccions.html)
      - [Exercicis pràctics](exercicis_prctics1.html)

<div id="topPagination">

[<span><span>«
</span>Anterior</span>](3_simplificant_laccs_a_la_base_de_dades.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](4_transaccions.html)

</div>

<div id="main-wrapper">

<div id="main" class="section">

# Exercicis pràctics

<div class="iDevice emphasis0">

<div id="ta12_85" class="block iDevice_content">

</div>

</div>

<div class="iDevice emphasis1">

# Activitat: DBConnection

<div class="iDevice_inner">

<div class="iDevice_content_wrapper">

<div id="ta13_52_2" class="block iDevice_content">

Per a unificar el procés de creació de connexió a la BBDD de la nostra
aplicació crearem una classe DBConnection responsable d'aquesta tasca.

La classe contindrà una funció anomenada *getConnection* que tornarà la
connexió establerta.

En aquesta funció controlarem l'excepció PDOException i farem servir la
funció `die` de PHP per mostrar el missatge de l'excepció

Aquesta classe la guardarem en un directori anomenat src.

</div>

</div>

</div>

</div>

<div class="iDevice emphasis1">

# Activitat: Separar les dades de connexió

<div class="iDevice_inner">

<div class="iDevice_content_wrapper">

<div id="ta14_48_2" class="block iDevice_content">

Modificarem la classe DBConnection perquè les dades de la connexió les
agafe d'un altre arxiu anomenat `app.json` que estarà en en la carpeta
`config`.

</div>

</div>

</div>

</div>

<div class="iDevice emphasis1">

# Activitat: Implentar el model Post

<div class="iDevice_inner">

<div class="iDevice_content_wrapper">

<div id="ta17_48_2" class="block iDevice_content">

Modifica el projecte de Blog fent ús del model PostModel i la interfície
Post.

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

[<span><span>«
</span>Anterior</span>](3_simplificant_laccs_a_la_base_de_dades.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](4_transaccions.html)

</div>

</div>
