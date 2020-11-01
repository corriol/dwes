---
layout: default
title: 2. Accés a la base de dades mitjançant PHP amb la llibreria PDO
parent: 5. Accés a dades amb PHP
nav_order: 2
has_children: false
---

# Accés a la base de dades mitjançant PHP amb la llibreria PDO
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

## Introducció

Les sigles PDO (_PHP Data Objects_) fan referència a una interfície de PHP
que ens permet accedir a bases de dades de qualsevol tipus en PHP.

Cada controlador de bases de dades que implemente la interfície PDO pot
exposar característiques específiques de la base de dades, com les
funcions habituals de l'extensió. Cal observar que no es pot realitzar
cap de les funcions de la bases de dades utilitzant l'extensió PDO per
si mateixa; s'ha d'utilitzar-ne una de PDO específica de la base de
dades per tenir accés a un servidor de base de dades.

![](Pdo.png)

PDO proporciona una capa d'abstracció d'accés a dades, el que significa
que, independentment de la base de dades que s'estiga utilitzant, s'usen
les mateixes funcions per fer consultes i obtenir dades.

Per saber els controladors PDO disponibles en el nostre sistema:

```php
print_r (PDO :: getAvailableDrivers ());
```

Informació oficial sobre PDO en <http://php.net/manual/es/book.pdo.php>

## Classes PDO, PDOStatement i PDOException

  - Classe `PDO`: S'utilitza per representar la connexió entre PHP i un
    servidor de bases de dades. <http://php.net/manual/es/class.pdo.php>
  - Classe `PDOStatement`: representa una sentència preparada i també ens
    permet accedir al conjunt de resultats associat.
    <http://php.net/manual/es/class.pdostatement.php>
  - Classe `PDOException`: representa els errors generats PDO.
    <http://php.net/manual/es/class.pdoexception.php>

## Connexió a la base de dades


```php
public __construct(string $dsn [, string $username [, string $passwd [, array $options ]]] )
```

El constructor de PDO té els següents paràmetres:
* `dsn`: cadena de connexió
* `username`: nom d'usuari
* `passwd`: contrasenya d'usuari
* `options`: permet afegir altres opcions

```php
$pdo = new PDO("mysql:host=localhost; dbname=test", "dbuser", "1234");
```

En l'exemple anterior  (**mysql**) representa una connexió a una base de dades MySQL (
podria ser: MSSQL, Sybase, sqlite, etc.) anomenada `test` que es troba al servidor `localhost`. 
La connexió es realitzarà mitjançant l'usuari `dbuser` i la contrasenya `1234`.

```php
// Exemple de connexió a diferents tipus de bases de dades.
# Connectem a la base de dades
$host = 'www.local';
$hbname = 'cxbasex';
$user = 'cxbasex';
$pass = 'xxxxxx';
   
try {
    # MS SQL Server y Sybase amb PDO_DBLIB
    $pdo = new PDO("MSSQL:host=$host;dbname=$dbname, $user, $pass");
    $pdo = new PDO("Sybase:host=$host;dbname=$dbname, $user, $pass");
    
    /* MySQL amb PDO_MYSQL
     Perquè la connexió a mysql utilitzi les collation UTF-8 afegir charset = utf8 a string 
     de la connexió. */
    $pdo = new PDO  ("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    
    // Perquè generi excepcions a l'hora de reportar errors.
    $pdo->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    # SQLite Database
    $pdo = new PDO ("sqlite:my/database/path/database.db");
}
catch (PDOException $e) {
    die($e-> getMessage ());
}
    
// Si tot va bé en $pdo tindrem el objecte que gestionarà la connexió amb la base de dades.

```

### Tancar la connexió a la base de dades ###

Es recomana tancar sempre la connexió a la base de dades quan no es vaja
a utilitzar més durant el nostre procés.

Cal recordar que els recursos són limitats i quan hi ha pocs usuaris no
hi ha cap problema, però si tenim molts usuaris simultanis llavors és
quan sorgeixen problemes en haver assolit el nombre màxim de connexions
amb el servidor, per exemple.

En tancar la connexió de forma explícita accelerem l'alliberament de
recursos perquè estiguin disponibles per a altres usuaris.

```php
// Si vullguerem tancar la connexió amb la base de dades simplement podríem fer al final del fitxer.
$pdo = null;
```

## Execució de consultes

Per a realitzar consultes podem usar:
* `PDO::query()` per a consultes de recuperació de dades (SELECT).
* `PDO::exec()` per a consultes d'inserció, modificació i esborrat de dades (INSERT, UPDATE i DELETE)
* `PDO::prepare()` per a consultes preparades. Aquest és el mètode recomanat però això
hem dedicat un apartat específic.

Una vegada executada la consulta les dades s'obtenen a través del mètode `PDOStatement::fetch()` o 
`PDOStatement::fetchAll()`. 

* `fetch()`: Obté la següent fila d'un recordset (conjunt de
resultats). [http://php.net/manual/es/pdostatement.fetch.php](http://php.net/manual/es/pdostatement.fetch.php)  
* `fetchAll()`: Retorna un array que conté totes les files del conjunt
de resultats (el tipus de dades a retornar es pot indicar com a
paràmetre). [http://php.net/manual/es/pdostatement.fetchall.php](http://php.net/manual/es/pdostatement.fetchall.php)

### Tipus de resposta 

Abans de cridar al mètode `fetch()` una bona idea és indicar-li com
volem que ens retorne les dades de la base de dades.

Tindrem les següents opcions en el mètode `fetch()`:

  - `PDO::FETCH_ASSOC`: torna un array indexat pel nom de camp de la
    taula.
  - `PDO::FETCH_BOTH`: (per defecte): retorna un array indexat per nom
    de camp de la taula i per nombre de camp.
  - `PDO::FETCH_BOUND`: Assigna els valors retornats a les variables
    assignades amb el mètode `bindColumn()`.
  - `PDO::FETCH_CLASS`: Assigna els valors dels camps a les propietats
    d'una classe. Si les propietats no existeixen en aquesta classe, les
    crearà.
  - `PDO::FETCH_INTO`: Actualitza una instància existent d'una classe.
  - `PDO::FETCH_LAZY`: Combina PDO::FETCH_BOTH / PDO::FETCH_OBJ,
    creant les variables de l'objecte a mesura que es van fent servir.
  - `PDO::FETCH_NUM`: Retorna un array indexat pel nombre de camp.
  - `PDO::FETCH_OBJ`: Retorna un objecte anònim amb els noms de les
    propietats que es corresponen amb els noms de columnes.

Per ajustar la manera de resposta:

```php
$stmt->setFetchMode (PDO::FETCH_ASSOC);
```
També es pot indicar el tipus de resposta mitjançant un paràmetre en `PDOStatement::fetch()`
 i `PDOStatement::fechtall()`.
 
#### FETCH_ASSOC

Per executar la consulta SELECT si no tenim paràmetres en la consulta
podrem usar `PDO::query()`

Vegem un exemple de consulta SELECT:

```php
try {
    #Per executar la consulta SELECT si no tenim paràmetres en la consulta podrem usar -> query ()
    $stmt = $pdo->query ('SELECT name, addr, city from school');
    
    /* Indiquem en quin format volem obtenir les dades de la taula en format d'array associatiu.
       Si no indiquem res per defecte s'usarà FETCH_BOTH el que ens permetrà accedir com a vector 
        associatiu o array numèric. */
    $stmt-> setFetchMode (PDO::FETCH_ASSOC);
    
    #Llegim les dades del recordset amb el mètode -> fetch ()
    while ($row=$stmt->fetch ()) {
       echo $row['name']. "<br/>";
       echo $row['addr']. "<br/>";
       echo $row['city']. "<br/>";
    }
    
     #Per alliberar els recursos utilitzats en la consulta SELECT
     $stmt = null;
 } catch (PDOException $err) {
        // Mostrem un missatge genèric d'error.
    echo "Error: executant consulta SQL.";
 }
    
    
# ------------------------------------------------- ----------------------------------------------
# Per executar la consulta SELECT si tenim paràmetres ho faríem així:
   
# Preparem la consulta com sempre
$stmt = $pdo-> prepare( 'SELECT name, addr, city from school where city =: ciutat');
    
try {
      #indiquem en quin format volem obtenir les dades de la taula en format d'array associatiu.
        #Si no indiquem res per defecte s'usarà FETCH_BOTH el que ens permetrà accedir com a vector associatiu o array numèric.
     $stmt->execute(array( 'ciutat' => 'Santiago de Compostel·la');
        #Llegim les dades del recordset amb el mètode -> fetch ()
        #Per defecte ja els torna a forma de vector associatiu si no indiquem res amb setFetchMode ()
    
        while ($row = $stmt->fetch ())
        {
            echo $row['name']. "<br/>";
            echo $row['addr']. "<br/>";
            echo $row['city']. "<br/>";
        }
    
    #Per alliberar els recursos de la consulta SELECT
    $stmt = null;
    }
    catch (PDOException $err) {
    // Mostrem un missatge genèric d'error.
        echo "Error: executant consulta SQL.";
    }

```

#### FETCH_OBJ

En aquest tipus de manera de consulta es crearà un objecte estàndard per
cada fila que llegim del recordset.

exemple:

```php
    try {
        #Creem la consulta
        $stmt = $pdo->query('SELECT name, addr, city from col·legues');
        #Ajustem la manera d'obtenció de dades
        $stmt->setFetchMode(PDO::FETCH_OBJ);
    
        #Mostrem els resultats.
        #Fixeu-vos que es torna un objecte cada vegada que es llegeix una fila del recordset.
    
        while ($row = $stmt->fetch()) {
            echo $row->name. "<br/>";
            echo $row->addr. "<br/>";
            echo $row->city. "<br/>";
        }
    
        #Alliberem els recursos utilitzats per $stmt
        $stmt = null;
    }
    catch (PDOException $err)
    {
    // Mostrem un missatge genèric d'error.
    echo "Error: executant consulta SQL.";
    }
```

#### FETCH_CLASS
Paràmetre de `fetch()` i `fetchAll()` que torna una nova instància de la classe solicitada, 
fent correspondre les columnes del conjunt de resultats amb els noms de les propietats de la classe, 
i cridant  al constructor després, a menys que también es proporcione l'opció `PDO::FETCH_PROPS_LATE`.

```php
class Persona
{
    private $name;

    public function __construct()
    {
        $this->decir();
    }

    public function decir()
    {
        if (isset($this->name)) {
            echo "Soy {$this->name}.\n";
        } else {
            echo "Aún no tengo nombre.\n";
        }
    }
}

$sth = $dbh->query("SELECT * FROM people");
$sth->setFetchMode(PDO::FETCH_CLASS, 'Persona');
$persona = $sth->fetch();
$persona->decir();
$sth->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Persona');
$persona = $sth->fetch();
$persona->decir();
```

Mostrarà:

```
Soy Alice.
Soy Alice.
Aún no tengo nombre.
Soy Bob. 
```

## Consultes preparades

Com hem vist en l'apartat anterior les consultes poden acceptar
paràmetres. La millor forma de realitzar aquestes consultes és
mitjançant les consultes preparades.

Les consultes preparades ens aporten dues avantatges importants:

  - Per a sentències que seran executades en múltiples ocasions amb
    diferents paràmetres optimitza el rendiment de la aplicació.
  - Ajuda a prevenir injeccions SQL eliminant la necessitat de posar
    entre cometes manualment els paràmetres.

Per a construir una sentència preparada cal incloure uns marcadors en la
sentència SQL.

Hi ha tres formes de fer-ho:

```php
# Marcadores anónimos
$stmt = $pdo->prepare("INSERT INTO colegas (name, addr, city) values (?, ?, ?)");
    
# Marcadores conocidos
$stmt = $pdo->prepare("INSERT INTO colegas (name, addr, city) values (:name, :addr, :city)");
    
// Aquí no lleva marcadores - ideal para una inyección SQL! ('''no usar este método'''). 
// !! Hay que usar los marcadores !!
$stmt = $pdo->prepare("INSERT INTO colegas (name, addr, city) values ($name, $addr, $city)");
```

Hauràs d'usar el primer o el segon mètode dels que es mostren
anteriorment. El tipus de marcadors que utilitzem afectaran a la forma
d'assignar eixos marcadors.

### Assignació amb marcadors anònims

Per enllaçar els marcadors anònims amb el seu corresponent valor es pot
utilitzar `bindParam` o `bindValue`:

ATENCIÓ:` $pdo->prepare()` usant marcadors anònims `?`, tracta totes les
variables com si foren cadenes, per la qual cosa farà servir cometes
per delimitar els seus valors per defecte.

```php

# Marcadores anónimos
$stmt = $pdo->prepare("INSERT INTO friend (name, addr, city) values (?, ?, ?)");
    
# Assignem variables a cada marcador, indexats l'1 al 3
$stmt->bindParam(1, $name);
$stmt->bindParam(2, $addr);
$stmt->bindParam(3, $city);
    
# Insertamos una fila.
$name = "Daniel"
$addr = "1 Wicked Way";
$city = "Arlington Heights";
$stmt->execute();
    
# Insertamos otra fila con valores diferentes.
$name = "Steve"
$addr = "5 Circle Drive";
$city = "Schaumburg";
$stmt->execute();
```

Un altra forma d'assignació amb marcadors anònims és mitjançant un array
associatiu:

```php
 # Los datos que queremos insertar
 $datos = array('Cathy', '9 Dark and Twisty Road', 'Cardiff');
    
 $stmt = $pdo->prepare("INSERT INTO colegas (name, addr, city) values (?, ?, ?)");
 $stmt->execute($datos);
```


### Diferència entre l'ús de bindParam i bindValue

Amb `bindParam` es vincula la variable al paràmetre i en el moment de
fer l'*execute* és quan s'assigna realment el valor de la variable a
aquest paràmetre.

Amb `bindValue` s'assigna el valor de la variable a aquest paràmetre
just en el moment d'executar la instrucció `bindValue`.

Exemple de diferència entre `bindParam` i `bindValue`:

```php
// Ejemplo con bindParam:
$sex = 'hombre';
$s = $dbh->prepare('SELECT name FROM studiantes WHERE sexo = :sexo');
$s->bindParam(':sexo', $sex);
$sex = 'mujer';
$s->execute(); // se ejecutó con el valor WHERE sexo = 'mujer'
    
// El mismo ejemplo con bindValue:
$sex = 'hombre';
$s = $dbh->prepare('SELECT name FROM students WHERE sexo = :sexo');
$s->bindValue(':sexo', $sex);
$sex = 'mujer';
$s->execute(); // se ejecutó con el valor WHERE sexo = 'hombre'
```

### Assignació amb marcadors coneguts

Els marcadors coneguts són la forma més recomanable de treballar amb PDO, ja que a l'hora 
de fer el `bindParam` o el `bindValue` es pot especificar 
el tipus de dada i la seua longitud.

Format de `bindParam`  amb marcadors coneguts:

```php
bindParam(':marcador', $variableVincular, TIPO DATOS PDO)
```

Exemple d'ús de `bindParam`:

```php
$stmt->bindParam(':calorias', $misCalorias, PDO::PARAM_INT);
$stmt->bindParam(':apellidos', $misApellidos, PDO::PARAM_STR, 35); // 35 caracteres como máximo.
```

Amb marcadors coneguts quedaria de la següent forma:

```php
$stmt = $pdo->prepare("INSERT INTO colegas (name, addr, city) value (:name, :addr, :city)");
    
# El primer argumento de bindParam es el nombre del marcador y el segundo la variable 
# que contendrá los datos.
    
 # Los marcadores conocidos siempre comienzan con :
$stmt->bindParam(':name', $name);
$name='Pepito';
    
$stmt->bindParam(':addr', $addr);
$addr='Duanes, 17';
    
$stmt->bindParam(':city', $city);
$city = 'Pego';
    
$stmt->execute();
    
# Otra forma es creando un array asociativo con los marcadores y sus valores:
# Los datos a insertar en forma de array asociativo
$datos = array( 'name' => 'Cathy', 'addr' => '9 Dark and Twisty', 'city' => 'Cardiff' );
    
# Fijarse que se pasa el array de datos en execute().
$stmt = $pdo->prepare("INSERT INTO colegas (name, addr, city) value (:name, :addr, :city)");
$stmt->execute($datos);
    
# La última instrucción se podría poner también así:
$stmt->execute(array(
    'name' => 'Cathy',
    'addr' => '9 Dark and Twisty',
    'city' => 'Cardiff'
));
```

Una altra característica dels marcadors coneguts és que ens permetran
treballar amb objectes directament a la base de dades, assumint que les
propietats d'aquest objecte coincideixen amb els noms dels camps de la
taula a la base de dades.

Exemple d'ús de marcadors coneguts i objectes:

```php

# Un objeto sencillo
class person {
    public $name;
    public $addr;
    public $city;
    
    function __construct($n,$a,$c) {
        $this->name = $n;
        $this->addr = $a;
        $this->city = $c;
    }
    # etc ...
}
    
$cathy = new person('Cathy','9 Dark and Twisty','Cardiff');
    
# Preparación de la consulta
$stmt = $pdo->prepare("INSERT INTO colegas (name, addr, city) value (:name, :addr, :city)");
    
# Inserción del objeto
$stmt->execute((array)$cathy);
```

### Exemple d'ús
Per a utilitzar les consultes preparades seguirem sempre aquest esquema:

![](consultes-preparades.png)

```php
$pdo = new PDO('sqlite:/path/db/users.db');

# 1. Preparem la connexió
$stmt = $pdo->prepare('SELECT name FROM users WHERE id = :id');
    
$id = 5;
    
# 2. Vinculem els paràmetres
$stmt->bindParam(':id', $id, PDO::PARAM_INT); 
    
# 3. Executem la consulta
 $stmt->execute();
    
 # 4. Obtenim els registres
    
 # Un a un
 $row = $stmt->fetch();
    
 # Tots
 $rows = $stmt->fetchAll();

```
  
## Inserció, modificació i eliminació de dades de la BBDD

Insertar nuevos datos, actualizarlos o borrarlos son algunas de las
operaciones más comunes en una base de datos. Con PDO, se suele hacer en
un proceso de 2 pasos.

![Prepare Bind Execute](Prepare-bind-execute.png
"Prepare \> Bind \> Execute")

Exemple d'ús:

```php
# $stmt sería un objeto de tipo PDOStatement (consulta preparada)
$stmt = $pdo->prepare("INSERT INTO alumnos( nombre, apellidos) values ( 'Taylor','Swift' )");

# Ejecutamos la consulta con ->execute() método del objeto PDOStatement
# Este método devuelve true o false.
$stmt->execute();
    
// Como resultado de la ejecución tendríamos en $stmt un valor true o false indicado si 
// la instrucción se ha ejecutado correctamente.
    
// Se podría realizar lo anterior también con la sentencia query o exec del objeto PDO
//  ahorrándonos una instrucción:
// Es útil cuando son sentencias SQL que no reciben parámetros.
    
// $pdo->query("consulta SQL") -> Devolverá un objeto de tipo PDOStatement. (recomendable para SELECT)
// $pdo->exec("consulta SQL") -> Devolverá el número de registros afectados por 
// la consulta (recomendable para INSERT, UPDATE o DELETE).
    
// Como resultado de la ejecución tendríamos en $numregistros el número de filas afectadas por la instrucción.
$numregistros= $pdo->query("INSERT INTO alumnos( nombre, apellidos) values ( 'Taylor','Swift' )");
    
# Si queremos borrar registros se podría hacer con:
$pdo->exec("DELETE FROM colegas WHERE apellidos='Swift'");

```

### Exemples CRUD (**C**reate, **R**ead, **U**pdate, **D**elete): INSERT, UPDATE y DELETE

#### INSERT

```php
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare('INSERT INTO someTable VALUES(:name)');
    $stmt->execute(array(
    ':name' => 'Justin Bieber'
    ));
    
    # Affected Rows?
    echo $stmt->rowCount(); // 1
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

#### UPDATE

```php
$id = 5;
$name = "Joe the Plumber";
    
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare('UPDATE someTable SET name = :name WHERE id = :id');
    $stmt->execute(array(
    ':id' => $id,
    ':name' => $name
    ));
    
    echo $stmt->rowCount(); // 1
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

#### DELETE

```php
$id = 5; // From a form or something similar
    
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare('DELETE FROM someTable WHERE id = :id');
    $stmt->bindParam(':id', $id); // this time, we'll use the bindParam method
    $stmt->execute();
    
    echo $stmt->rowCount(); // 1
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

## Gestió d'errors en l'accés

PDO pot utilitzar les excepcions per gestionar els errors, el que
significa que qualsevol cosa que fem amb PDO podríem encapsular en un
bloc try/catch per gestionar si produeix algun error.

Podem forçar PDO perquè treballi en qualsevol de les tres maneres
següents:

  - `PDO::ERRMODE_SILENT`. És el mode per defecte. Aquí haurem de
    revisar els errors usant `errorCode()` i `errorInfo()`.
  - `PDO::ERRMODE_WARNING`. Genera errors de revisió de resultats
    PHP però permetria l'execució normal de l'aplicació.
  - `PDO::ERRMODE_EXCEPTION`. Serà la forma més utilitzada en PDO.
    Dispara una excepció permetent-nos gestionar l'error de forma
    amigable.

```php
//Activació de la manera de treball de PDO
$pdo-> setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
$pdo-> setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    
// Es recomana activar aquesta opció per gestionar els errors amb PDOException
    $pdo-> setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

**Exemple d'ús:**

```php
    
# Connectem a la base de dades
$host = 'www.local';
$dbname = 'cxbasex';
$user = 'cxbasex';
$pass = 'xxxxxx';
    
try {
    $pdo = new PDO ( "mysql: host = $ host; dbname = $ dbname; charset = utf8", $ user, $ pass);
    $pdo-> setAttribute (PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo "S'ha produït un error en intentar connectar al servidor MySQL:". $e-> getMessage ();
}
    
try {
    # Un altre Exemple d'error! DELECT en lloc de SELECT!
    $pdo-> exec ( 'DELECT name FROM people');
}
catch (PDOException $e) {
    echo "S'ha produït un error en l'ejecucion de la consulta:". $e->getMessage();
    
    # En aquest cas hem mostrat el missatge d'error i, a més emmagatzemem en un fitxer els errors generats.
    file_put_contents ('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
}
```   

{: .alert .alert-activity }
<div markdown="1">

### Accés a la base de dades del projecte
{:.no_toc .nocount}

Modifica el projecte perquè obtinga els posts de la base de
dades.

Canvia la pàgina principal i la pàgina `movies.php` perquè s'obtinguen les dades de la base de dades
  A més d'un enllaç a la pàgina `single-page.php` que mostrarà tots els camps d'una pel·lícula determinada.

**Utilitza consultes preparades sempre que calga usar paràmetres.**

** Obtin la resposta com un array d'objectes de la classe `Movie` amb el tipus de retorn `FETCH_CLASS` **. 

</div>

{: .alert .alert-activity }
<div markdown="1">
### Inserint noves pel·lícules
{:.no_toc .nocount}

  - Crea un formulari (`movie-create.php`) per a inserir noves pel·lícules.
  - Posa un enllaç al formulari en `movies.php`.
  - La pàgina (`movie-create.php`) processarà el formulari i després de validar tots
    els camps els inserirà a la base de dades.
    
   Cal tenir en compte la separació de la lògica i la presentació.
</div>

{: .alert .alert-activity }
<div markdown="1">
### Modificant i esborrants pel·lícules
{:.no_toc .nocount}

Crea un formulari per a editar i esborrar pel·lícules.

</div>