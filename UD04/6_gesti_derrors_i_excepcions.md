---
layout: default
title: 6. Gestió d'errors i excepcions
parent: 4. Classes i objectes
nav_order: 6
has_children: false
---

# Gestió d'errors i excepcions
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}


## Introducció
De ben segur que a mesura que has anat resolent exercicis o simplement
provant codi, t'has trobat amb errors de programació. Alguns són
reconeguts per l'entorn de desenvolupament i pots corregir-los abans
d'executar. Altres apareixen al navegador en forma de missatge d'error a
l'executar l'*script*.

A diferència d'altres llenguatges de programació que tenen una gestió d'excepcions
més completa PHP manté una gestió d'excepcions lleugera ja que distingeix entre errors i excepcions. 
Així mentre no es produisca una excepció o un error fatal l'execució del programa continua.


## Errors
PHP defineix una classificació dels errors que es poden produir en
l'execució d'un programa i ofereix mètodes per ajustar el tractament
dels mateixos. Per fer referència a cada un dels nivells d'error, PHP
defineix una sèrie de constants. Cada nivell s'identifica per una
constant. Per exemple, la constant `E_NOTICE` fa referència a avisos que
poden indicar un error en executar el programa, i la constant `E_ERROR`
engloba errors fatals que provoquen que s'interrompa forçosament l'execució.

La llista completa de constants la pots consultar al manual de PHP, on
també es descriu el tipus d'errors que representa :
[http://es.php.net/manual/es/errorfunc.constants.php](http://es.php.net/manual/es/errorfunc.constants.php)


### Canviar el comportament de PHP 
La configuració inicial de com va a tractar-se cada error segons el seu
nivell es realitza en `php.ini` el fitxer de configuració de PHP. Entre
els principals paràmetres que pots ajustar estan:

  - `error_reporting`. Indica quins tipus d'errors es notificaran. El
    seu valor es forma utilitzant els operadors a nivell de bit per
    combinar les constants anteriors. El seu valor per defecte és `E_ALL
    & ~ E_NOTICE` que indica que es notifiquin tots els errors (`E_ALL`)
    excepte els avisos a temps d'execució (`E_NOTICE`).
  - `display_errors`. En el seu valor per defecte (`On`), fa que els
    missatges s'envien a la sortida estàndard (i per tant es mostrin al
    navegador). S'ha de desactivar (`Off`) en els servidors que s'usen per 
    a producció.

Hi ha altres paràmetres que podem utilitzar en `php.ini` per ajustar el
comportament de PHP quan es produeix un error.
<http://es.php.net/manual/es/errorfunc.configuration.php>

Des codi, pots fer servir la funció `error_reporting` amb les constants
anteriors per establir el nivell de notificació en un moment determinat.
Per exemple, si en algun lloc del teu codi figura una divisió en la qual
hi haja la possibilitat que el divisor siga zero, quan això passe
obtindràs un missatge d'error al navegador. Per evitar-ho, pots
desactivar la notificació de errors de nivell `E_WARNING` abans de la
divisió i restaurar al seu valor normal a continuació:

```php
error_reporting(E_ALL & ~ E_NOTICE & ~ E_WARNING);
$resultat = $dividend/$divisor;
error_reporting (E_ALL & ~ E_NOTICE);
```


### Gestió global dels errors
En utilitzar la funció `error_reporting` només controles quin tipus
d'errors va a notificar PHP. A vegades pot ser suficient, però per
obtenir més control sobre el procés existeix també la possibilitat de
gestionar de forma personalitzada els errors. És a dir, pots programar
una funció perquè siga la que executa PHP cada vegada que es produeix un
error. El nom d'aquesta funció s'indica utilitzant `set_error_handler` i
ha de tenir com a mínim dos paràmetres obligatoris (el nivell de l'error
i el missatge descriptiu) i fins tres opcionals amb informació addicional 
sobre el error (el nom del fitxer en què es produeix, el
nombre de línia, i un bolcat de l'estat de les  
variables en aquest moment).

```php
    set_error_handler ("miGestorDeErrores");
    $resultat = $dividend / $divisor;
    restore_error_handler ();
    function miGestorDeErrores ($nivell, $missatge)
    {
        switch ($nivell) {
            case E_WARNING:
                echo "Error de tipus WARNING: $missatge. <br />";
                break;
            default:
                echo "Error de tipus no especificat: $ missatge. <br />";
        }
    }
```

La funció `restore_error_handler` restaura el gestor d'errors original
de PHP (més concretament, el que s'estava fent servir abans de la crida
a `set_error_handler`).

### ErrorException

Gràcies a la combinació de la classe `ErrorException` i el mètode `set_error_handler` podem convertir fàcilment els errors en excepcions de tipus `ErrorException`:

```php
<?php
function exception_error_handler($severidad, $mensaje, $fichero, $línea) {
    if (!(error_reporting() & $severidad)) {
        // Este código de error no está incluido en error_reporting
        return;
    }
    throw new ErrorException($mensaje, 0, $severidad, $fichero, $línea);
}
set_error_handler("exception_error_handler");

/* Desencadenar la excepción */
strpos();
?>
```

## Excepcions

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

```php
    try {
        if ($divisor == 0)
            throw new Exception ( "Divisió per zero.");
        $resultat = $dividend/$divisor;
    }
    catch (Exception $e) {
        echo "S'ha produït el següent error:". $ e-> getMessage ();
    }
```


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
d'excepcions. En aquest cas, el més comú és que l'extensió definisca els
seues propis controladors d'errors heretant de la classe `Exception`.

{:.alert .alert-info}
<div markdown="1">
{:.nocount .no_toc}
### ErrorExecption
Com hem dit abans, utilitzant la classe `ErrorException` és possible traduir els errors a
excepcions.[http://es.php.net/manual/es/class.errorexception.php](http://es.php.net/manual/es/class.errorexception.php)
</div>


Concretament, la classe PDO permet definir la fórmula que farà servir
quan es produeixi un error,  utilitzant l'atribut `PDO::ATTR_ERRMODE`.
Les possibilitats són:

  - `PDO::ERRMODE\_SILENT`. No es fa res quan ocorre un error. És el
    comportament per defecte.
  - `PDO::ERRMODE\_WARNING`. Genera un error de tipus E\_WARNING quan es
    produeix un error.
  - `PDO::ERRMODE\_EXCEPTION`. Quan es produeix un error llança una
    excepció utilitzant el gestor propi PDOException.

És a dir, que si vols utilitzar excepcions amb l'extensió PDO, has de
configurar la connexió fent:

```php
$dwes-> setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```


Per exemple, el següent codi:
```php
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
```

Captura l'excepció que llança PDO a causa de que la taula no existeix.
El bloc catch mostra el següent missatge: 

```php
Error SQLSTATE [42S02]: Base table or view not found: 1146 Table 'dwes.stox' does not exist
```

### El bloc `finally`

En PHP 5.5 i posterior, es pot utilitzar un bloc `finally` després o en
lloc dels blocs `catch`. El codi de dins el bloc `finally` sempre
s'executarà després dels blocs `try` i `catch`, independentment que
s'haja llançat una excepció o no, i abans que l'execució normal
continue.

Com que el contingut del bloc `finally` sempre s'executa és ideal per a
alliberar recursos en cas d'error, com una connexió a base de dades o a
la connexió a un servei proporcionats per tercers.`  `

### Múltiples catch

És possible afegir més d'un `cath` de la següent manera:

```php
try {
   ...
} catch (FooException e) {
   ...
} catch (BarException e) {
   ...
} catch (Exception e) {
   ...   
}
```

A partir de PHP 7.1 també és possible fer-ho així:

```php
try {
   ...
} catch (FooException | BarException | Exception e) {
   ...
}
```

## Ampliant les excepcions

És possible crear excepcions personalitzades heretant de la classe
Exception.

```php
class NegativeIdException extends Exception {
  public function __construct($message = null) {
    $this->message = $message ?: 'ID Negatiu.';
    parent::__construct($message);
  }
}
```

Després l'utilitzarem així:

```php
try {
  $post = new Post(-1, "Hola món", "Aquest és el primer post del blog", new DateTime(), "Vicent");
} catch (NegativeIdException $e) {
    echo "No es pot posar un ID negatiu\n";
} catch (Exception $e) {
  echo "Excepció desconeguda".  $e->getMessage();
}
```

# Exercicis pràctics

# Activitat: gestió d'errors i excepcions

1.  Modifica les classes de l'aplicació del formulari perquè en cas que
    el atribut $name no tinga valor llance una excepció amb el missatge
    "L'atribut name no pot estar buit".
2.  Captura l'excepció.
3.  Crea una excepció personalitzada `OptionsNotFoundException` en cas
    que el paràmetre $options del control Select estiga buit.
4.  Captura l'excepció.
5.  Crea una excepció personalitzada InvalidFormatOptionsException en
    cas que el paràmetre $options del control Selecte tinga un array
    però les claus no siguen name i value.
6.  Captura l'excepció.