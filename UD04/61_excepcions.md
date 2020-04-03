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
      - <span id="active">[6.1. Excepcions](61_excepcions.html)</span>
          - [Ampliant les excepcions](ampliant_les_excepcions.html)
      - [Exercicis pràctics](exercicis_prctics1.html)
  - [Crèdits, fonts i bibliografia](crdits_fonts_i_bibliografia.html)

<div id="topPagination">

[<span><span>«
</span>Anterior</span>](6_gesti_derrors_i_excepcions.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](ampliant_les_excepcions.html)

</div>

<div id="main-wrapper">

<div id="main" class="section">

# 6.1. Excepcions

<div class="iDevice emphasis0">

<div id="ta16_85" class="block iDevice_content">

A partir de la versió 5 es va introduir en PHP un model d'excepcions
similar a l'existent en altres llenguatges de programació:

  - El codi susceptible de produir algun error s'introdueix en un bloc
    `try`.
  - Quan es produeix algun error, es llança una excepció utilitzant la
    instrucció `throw`.
  - Després del bloc `try` ha d'haver com a mínim un bloc `catch`
    encarregat de processar el error.
  - Si un cop acabat el bloc `try` no s'ha llançat cap excepció, es
    continua amb la execució en la línia següent al bloc o blocs
    `catch`.

Per exemple, per llançar una excepció quan es produeix una divisió per
zero podries fer:

<div class="highlighted-code language-php">

<div>

    try {
        if ($divisor == 0)
            throw new Exception ( "Divisió per zero.");
        $resultat = $dividend/$divisor;
    }
    catch (Exception $e) {
        echo "S'ha produït el següent error:". $ e-> getMessage ();
    }

</div>

</div>

PHP ofereix una classe base `Exception` per utilitzar com a gestor
d'excepcions. Per llançar una excepció no cal indicar cap paràmetre,
encara que de forma opcional es pot passar un missatge d'error (com en
l'exemple anterior) i també un codi d'error.

Entre els mètodes que podeu utilitzar amb els objectes de la classe
`Exception` estan:

  - `getMessage`. Retorna el missatge, en cas que s'hagi posat algun.
  - `getCode`. Retorna el codi d'error si existeix.

Les funcions internes de PHP i moltes extensions com MySQLi usen el
sistema d'errors vist anteriorment. Només les extensions més modernes
orientades a objectes, com és el cas de PDO, utilitzen aquest model
d'excepcions. En aquest cas, el més comú és que l'extensió defineixi les
seves propis controladors d'errors heretant de la classe `Exception`
(veurem com utilitzar l'herència en una unitat posterior).

<div class="exe-block-info">

Utilitzant la classe `ErrorException` és possible traduir els errors a
excepcions.[](http://es.php.net/manual/es/class.errorexception.php)

<http://es.php.net/manual/es/class.errorexception.php>

</div>

Concretament, la classe PDO permet definir la fórmula que farà servir
quan es produeixi un error,  utilitzant l'atribut `PDO::ATTR_ERRMODE`.
Les possibilitats són:

  - PDO::ERRMODE\_SILENT. No es fa res quan ocorre un error. És el
    comportament per defecte.
  - PDO::ERRMODE\_WARNING. Genera un error de tipus E\_WARNING quan es
    produeix un error.
  - PDO::ERRMODE\_EXCEPTION. Quan es produeix un error llança una
    excepció utilitzant el gestor propi PDOException.

És a dir, que si vols utilitzar excepcions amb l'extensió PDO, has de
configurar la connexió fent:

<div class="highlighted-code language-php">

<div>

    $dwes-> setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

</div>

</div>

Per exemple, el següent
    codi:

<div class="highlighted-code language-php">

<div>

    $dwes = new PDO ( "mysql: host = localhost; dbname = dwes", "dwes", "abc123.");
    $dwes-> setAttribute (PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
    try {
        $sql ​​= "SELECT * FROM stox";
        $result = $dwes->query ($sql);
        ...
    }
    catch (PDOException $ p) {
        echo "Error". $ p-> getMessage (). "<br />";
    }

</div>

</div>

Captura l'excepció que llança PDO a causa de que la taula no existeix.
El bloc catch mostra el següent
    missatge: 

<div class="highlighted-code language-php">

<div>

    Error SQLSTATE [42S02]: Base table or view not found: 1146 Table 'dwes.stox' does not exist

</div>

</div>

</div>

</div>

<div class="iDevice emphasis0">

<div id="ta21_85" class="block iDevice_content">

</div>

</div>

<div class="iDevice emphasis0">

<div id="ta22_85" class="block iDevice_content">

## El bloc `finally`

En PHP 5.5 i posterior, es pot utilitzar un bloc `finally` després o en
lloc dels blocs `catch`. El codi de dins el bloc `finally` sempre
s'executarà després dels blocs `try` i `catch`, independentment que
s'haja llançat una excepció o no, i abans que l'execució normal
continue.

Com que el contingut del bloc `finally` sempre s'executa és ideal per a
alliberar recursos en cas d'error, com una connexió a base de dades o a
la connexió a un servei proporcionats per tercers.`  `

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
</span>Anterior</span>](6_gesti_derrors_i_excepcions.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](ampliant_les_excepcions.html)

</div>

</div>
