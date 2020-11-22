---
layout: default
parent: 5. Accés a dades amb PHP PDO
nav_order: 3
has_children: false
---

# Simplificant l'accés a la base de dades
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

## La classe Database

La classe `Database` serà l'encarregada de gestionar la connexió amb la base de dades,
contindrà un mètode estàtic `getConnetion()` que tornarà una instància d'una connexió `PDO`. Els mètodes estàtics 
 són accessibles sense necessitat d'instanciar la classe. Així fent `$pdo = Database::getConnection()` obtindríem 
 una instància de PDO.

Avís: aquesta classe conté diverses males pràctiques que cal evitar. Temps tindrem d'arreglar-ho. De moment l'objectiu
és que siga senzilla d'usar.  

```php
# src/Database.php

class Database
{
    private PDO $connection;

    private function __construct()
    {
        try {           
            $name = "movies";
            $user = "dbuser";
            $pass = "1234";
            $connection = "mysql:host=localhost;charset=utf8";            
            $options = [                
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true
                ];
            $this->connection = new PDO("$connection;dbname=$name", $user, $pass, $options);
        } catch (PDOException $e) {
            die ("Eerror en intentar connectar al servidor de base de dades: " . $e->getMessage());
        } catch (Exception $e) {
            die("Error en intentar connectar al servidor de base de dades: " . $e->getMessage());
        }
    }

    public static function getConnection(): PDO
    {
        try {
            $PDO = new Database();
        } catch (Exception $e) {
            die("Error en intentar connectar al servidor de base de dades: " . $e->getMessage());
        }
        return $PDO->connection;
    }
}
```


## Arxiu de configuració

En l'exemple anterior les dades de la connexió estan posades directament, el que fa que sempre que vullguen 
reutilitzar la classe haguem de modificar manualment la classe.  

La solució habitual és  que totes les dades de configuració que siguen susceptibles
de canviar en els diferents entorns (desenvolupament, producció, test,
etc.) es separen en un o diversos fitxers de configuració.

Aquests fitxers es poden codificar en PHP o utilitzar un altre format
que després puga ser llegit per PHP (Per exemple, ara s'utilitza molt
YAML).


{: .alert .alert-info }
<div markdown="1" class="info">
### Dotenv
{:.no_toc .nocount}

Dotenv és una mena d'estàndard de facto per a emmagatzemar la informació sensible de les aplicacions. Mitjançant
 fitxers `.env` s'estableix la configuració que després es carrega com a variables d'entorn.

El seu origen està en el paquet Ruby dotenv i s'utilitza en diversos frameworks com Symfony i Nodejs.
</div>


Per exemple

```php
# config/config.php
$config = [
    'database' => [
        'username' => 'blog',
        'password' => '1234',
        'connection' => 'mysql:host=blog.local;dbname=blog;charset=utf8',
        'options' => [  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_PERSISTENT => true ]
    ]
];

```

En format JSON:

```json
{
    "database": {
        "username": "blog",
        "password": "blog",
        "connection": "mysql:host=blog.local;dbname=blog",
        "options": { }
      }
    }
```


## Creació de les entitats 

Les entitats són les classes que mapejaran les taules de la base de dades. Tindran un atribut per cada camp de la 
taula que mapegen. Aquest tipus de classes també poden anomenar-se dominis (_domains_) o DAO (Data Access Objects). 
Independentment del nom que se li done caldrà tenir en consideració la seua funcionalitat: representar 
registres d'una taula.

En la majoria dels casos només contindran:

  - *getters* i *setters* per accedir als atributs.
  - Constructor si cal.
  - `__toString` per convertir l'objecte a cadena si cal.
  - Etc.

No implementarem lògica de negoci en les entitats.

Les consultes que obtindran dades de la BBDD tornaran un array d'entitats.

Per exemple:

```php
class Book
{
    private $id;
    private $isbn;
    private $title;
    private $author;
    private $stock;
    private $price;
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getIsbn(): string
    {
        return $this->isbn;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function getAuthor(): string
    {
        return $this->author;
    }
    
    public function getStock(): int
    {
        return $this->stock;
    }
    
    public function getCopy(): bool
    {
        if ($this->stock < 1) {
            return false;
        } else {
            $this->stock--;
            return true;
        }
    }
    
    public function addCopy()
    {
        $this->stock++;
    }
    
    public function getPrice(): float
    {
        return $this->price;
    }
}
```

Com ja hem vist en l'apartat anterior tant el mètode `fetch` com el mètode `fetchAll` de la classe `PDOStatement`
tenen la possibilitat de retornar les dades de la BBDD com a objectes de la classe que li indiquem.

Utilitzarem el paràmetre `PDO::FETCH_CLASS` passant el nom de l'entitat com a segon paràmetre.

```php
$posts= $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
```

## Contenidors de serveis

Un contenidor de serveis (o contenidor d'injecció de dependències)
simplement és un objecte PHP que gestiona la creació d'instàncies dels
serveis (és a dir, dels objectes).

Suposem per exemple que tens una classe PHP senzilla que envia missatges
de correu electrònic. Sense un contenidor de serveis, has de crear
manualment l'objecte cada vegada que el necessites:

```php
use Acme\HelloBundle\Mailer;
    
$mailer = new Mailer ( 'sendmail');
$mailer-> send ('ryan@foobar.net ', ...);
```

Aquest codi és bastant fàcil, ja que la classe imaginària Mailer
s'encarrega de configurar el mètode utilitzat per enviar missatges de
correu electrònic (per exemple, sendmail, smtp, etc.). Què passa si has
d'utilitzar la classe Mailer en un altre punt de l'aplicació? Has de
copiar i enganxar el mateix codi en tots els llocs? I si has de canviar
la forma en què s'envien els correus electrònics? Has de buscar en el
codi de tota l'aplicació i canviar la mateixa configuració desenes de
vegades?

## Model

El model representa la lògica de negocis.  S’encarrega d’accedir de forma directa a les dades actuant com a 
“intermediari” amb la base de datos.

En Symfony, per exemple, no trobareu models, és per això que llegireu que no és un MVC "pur",en canvi disposa 
de repositoris d'entitats que en la pràtica són homologables als models. 
 
En el nostre MVC tindrem la classe abstracta `Model` que implementarà les operacions habituals amb la base de dades.

```php
abstract class Model
{
 
  protected string $className;

  protected string $tableName;

  protected PDO $pdo;
 
  public function __construct(PDO $pdo, string $tableName, string $className);

  public function findAll($order = []): array;
  
  public function find(int $id): Entity;

  public function findBy(array $data = [], $operator = "AND"): array;
  
  public function findOneBy(array $data = []): ?Entity ;

  public function update(Entity $entity): bool;
  
  public function save(Entity $entity): bool;
  
  public function delete(Entity $entity): bool;

  // Rep una sentència SELECT en paràmetres que seran passats com a un array on la clau serà
  // el nom del paràmetre i el valor el valor i torna un array amb el resultat. 
  // per exemple si $sql és "SELECT * FROM movie WHERE title LIKE :text" el paràmetre
  // passat  serà ["text"=>"%Ava%"]. 
  public function executeQuery(string $sql, array $parameters = []): array;
}
```

## Gestió de les relacions
A l'hora d'obtenir les dades de les entitats relacionades podem optar per dues estratègies: 
_lazy loading_ o _eager loading_.

* _Lazy loading_ o càrrega diferida és un patró de disseny que s’utilitza habitualment en programació,  
 sobretot en el disseny i desenvolupament de pàgines web per diferir la inicialització d’un objecte
  fins al punt en què es necessita.
  
 * _Eager loading_ o càrrega immediata és un patró de disseny pel qual una consulta per a un tipus d’entitat 
 també carrega entitats relacionades com a part de la consulta, de manera que no necessitem executar una
  consulta independent per a entitats relacionades. 

En el nostre cas optarem per la càrrega diferida per ser més senzilla. Aprofundirem més en aquestes conceptes
 quan parlem de _frameworks_ com Laravel o Symfony.


## Activitats

{: .alert .alert-activity }
<div markdown="1" class="activity">

### Implementació del model 
{:.no_toc .nocount}

#### Enunciat
{:.no_toc .nocount}

1. Implementa el model seguint les indicacions dels vídeos
2. Implementa els mètodes `delete`, `update` i `executeQuery`. En `update` pots usar `array_map`
per a generar la sentència `UPDATE` tenint en compte que la clau primària no pot canviar mai.
3. Fes ús del model sempre que calga interactuar en la base de dades. 
</div>


```php
/**
 * Class UploadedFile
 *
 * Classe que gestiona la pujada de fitxers al servidor mitjançant formularis
 */
class UploadedFile
{
    /**
     * @var array
     *
     * Array del fitxer pujat. $_FILES['nom_del_camp_del_formuari']
     */
    private $file;
    /**
     * @var string
     *
     * Nom amb que es guardarà el fitxer.
     */
    private $fileName;
    /**
     * @var int
     *
     * Mida màxima en bytes del fitxer, 0 indica que no hi ha límit.
     */

    private $maxSize;
    /**
     * @var array
     *
     * Array amb els MimeType acceptats. Per exemple ['image/jpg', 'image/gif', image/png']. 
     * Si l'array és buit  s'accepten tots els tipus.
     */
    private $acceptedTypes;

    /**
     * UploadedFile constructor. Comprova que s'ha pujat un fitxer, si no llançarà una excepció
     * UploadFileNoFileException.
     * En qualsevol altre error en la pujada llançarà l'excepció UploadFileException.
     *
     * El paràmetre $inputName rebrà la clau de $_FILES en que s'emmagatzemen les dades del 
     * fitxer pujat.     
     * 
     * El paràmetre $maxSize indica la grandària màxima en bytes permesa. És opcional, 
     * serà 0 per defecte.  Si és 0 la grandària és ilimitada.
     *
     * El paràmetre $acceptedTypes és opcional. Contindrà els mimetype (tipus de fitxers) permesos. 
     * Per defecte estarà buit, en eixe cas es podrà pujar qualsevol tipus de fitxer.
     *
     * @param string $inputName
     * @param int $maxSize
     * @param array $acceptedTypes
     * @throws UploadFileException
     * @throws UploadFileNoFileException
     */
    public function __construct(string $inputName, int $maxSize = 0, 
        array $acceptedTypes = array());

    /**
     * @return bool
     *
     * Comprova que el fitxer s'haja pujat correctament, que no supera el 
     * limit de grandària i és del tipus indicat.
     * Si no passa la validació llançarà una excepció.
     * @throws UploadFileException
     */
    public function validate(): bool;

    /**
     * @return string
     *
     * Tornarà el nom del fitxer.
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $directory
     * @param string $fileName
     * @return bool
     *
     * Guarda el fitxer en la ubicació indicada.
     * Si no s'indica nom es guardarà amb el mateix nom que s'ha penjat.
     * Si s'indica es guardarà en eixe nom, l'extensió s'obtindrà de forma automàtica a partir 
     * del nom del que s'ha pujat.
     *
     * Exemple: $path = '/public/images/', $fileName = 'prova.png'
     *
     * Torna true si s'ha pogut moure la imatge a la ubicació indicada.
     */
    public function save(string $directory, $fileName = ""): bool
    {
        if (!is_uploaded_file($this->file['tmp_name'])) {
            return false;
        }
        // TODO: Implementar el que falta
    }

    /**
     * Extrau del nom de fitxer pujat la seua extensió
     * @return string
     */
    private function getExtension(): string
    {
        $file = $this->file["name"];
        $extensionArray = explode(".", $file);
        $extension = end($extensionArray);
        return $extension ? ".$extension" : "";
    }
}
```

{: .alert .alert-activity }
<div markdown="1" class="activity">

### La classe FileUpload  
{:.no_toc .nocount}

#### Enunciat
{:.no_toc .nocount}

Fes una ullada a la classe FileUpload anterior.

1. A partir de la documentació en PHPDoc implementa els mètodes que falten de la classe `FileUpload`.
2. Implementa també les excepcions personalitzades.
3. Fes ús de la classe per a gestionar la pujada d'imatges en pel·lícules, limitant-les al format jpg i a la grandària
de 300KB.
4. En [Handling file uploads](https://www.php.net/manual/es/features.file-upload.php) teniu informació addicinal. 
No cal que es cree el directori si no existeix. Anem a suposar que ja existeix. 
</div>


{: .alert .alert-activity }
<div markdown="1" class="activity">

### Gèneres  
{:.no_toc .nocount}

#### Enunciat
{:.no_toc .nocount}

1. Implementa el model per a la taula `Genre`.
2. En `Movie` afig la propietat `$genre_id` que serà la clau aliena a la taula `Genere` i crea els mètodes 
`Movie::setGenreId()` i `Movie::getGenreId()` per a accedir a la propietat.
3. Modifica el formulari de creació de pel·lícules perquè es puga triar el gènere d'una llista desplegable.
4. En el llistat de pel·lícules (`movies.php`) afig una columna que mostre el `id` de gènere.
5. Modifica `Model::save()` perquè assigne l'últim id inserit a l'element Entity.  
</div>

{: .alert .alert-activity }
<div markdown="1" class="activity">

### Obtenint el gènere  
{:.no_toc .nocount}

#### Enunciat
{:.no_toc .nocount}

1. Implementa el mètode `MovieModel::getGenre()` de forma que en rebre l'id d'un gènere et torne 
l'objecte gènere relacionat.
2. Utilitza el mètode anterior perquè en `movies.php` aparega el nom del gènere enlloc del seu `id`.
</div>

