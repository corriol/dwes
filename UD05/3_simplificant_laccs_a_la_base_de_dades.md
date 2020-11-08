---
layout: default
title: 3. Simplificant l'accés a la base de dades
parent: 5. Accés a dades amb PHP
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
<div markdown="1">
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
 
  public function __construct(PDO $pdo, string $tableName, string $className) {}

  public function findAll($order = []): array {}
  
  public function find(int $id): Entity {}

  public function findBy(array $data = [], $operator = "AND"): array {}
  
  public function findOneBy(array $data = []): ?Entity  {}

  public function update(Entity $entity): bool {}
  
  public function save(Entity $entity): bool {}
  
  public function delete(Entity $entity): bool  { }

  public function executeQuery(string $sql, array $parameters = []): array {}
}
```

## Gestió de les relacions