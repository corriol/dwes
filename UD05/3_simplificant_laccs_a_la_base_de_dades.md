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

## Arxiu de configuració

És important que totes les dades de configuració que siguen susceptibles
de canviar en els diferents entorns (desenvolupament, producció, test,
etc.) es separen en un o diversos fitxers de configuració.

Aquests fitxers es poden codificar en PHP o utilitzar un altre format
que després puga ser llegit per PHP (Per exemple, ara s'utilitza molt
YAML)

Per exemple

```php
#config.php
$config = [
    'database' => [
        'username' => 'blog',
        'password' => '1234',
        'connection' => 'mysql: host=blog.local, dbname=blog',
        'options' => []
    ]
];

```

En format JSON:

```json
{
    "database": {
        "username": "blog",
        "password": "blog",
        "connection": "mysql: host=blog.local; dbname=blog",
        "options": {
        }
      }
    }
```

## Creació de les entitats i la interfície a la base de dades

Anomenarem entitats a les classes que mapejaran les taules de la base de
dades, tindran un atribut per cada camp de la taula que mapegen.

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

Tant el mètode `fetch` com el mètode `fetchAll` de la classe `PDOStatement`
tenen la possibilitat de retornar les dades de la BBDD com a objectes de
la classe que li indiquem.

Utilitzarem el paràmetre `PDO::FETCH_CLASS` passant el nom de l'entitat
com a segon paràmetre.

```php
$classEntity = 'Post';
$posts= $stmt->fetchAll(PDO::FETCH_CLASS, $classEntity);
```

En aquest cas, obtindríem un array que conté totes les files del conjunt
de resultats com a objectes de l'entitat `Post`

Veure documentació del mètode:

  - <https://www.php.net/manual/es/pdostatement.fetchall.php>

Cal tenir en compte que si la classe té constructor caldrà emprar el 3
paràmetre del mètode i afegir-los mitjançant un `array` i en el primer
paràmetre afegir `PDO::FETCH_PROPS_LATE`.

`PDO::FETCH_PROPS_LATE` s'usa amb `PDO::FETCH_CLASS` i el que fa és cridar 
al constructor abans que s'assignen els atributs.


```php
class Persona
{
    private $name;
    
    public function __construct(string $name)
    {
        $this->name = $name;
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
    
$sth = $dbh->query("SELECT * FROM people"); // El name del primer és Paco.
$sth->setFetchMode(PDO::FETCH_CLASS, 'Persona', ['Alicia']); // Soy Paco
$persona = $sth->fetch(); 
$persona->decir();
    
$sth->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Persona', ['Alicia']); // Soy Alícia
$persona = $sth->fetch(); 
$persona->decir();

```
Altra opció més senzilla seria que els paràmetres del constructor tingueren valors per defecte.

```php
   
    public function __construct(string $name = '')
    {
        $this->name = $name;
        $this->decir();
    }

```

## Contenidors de serveis

Un contenidor de serveis (o contenidor d'injecció de dependències)
simplement és un objecte PHP que gestiona la creació d'instàncies dels
serveis (és a dir, dels objectes).

Suposem per exemple que tens una classe PHP senzilla que envia missatges
de correu electrònic. Sense un contenidor de serveis, has de crear
manualment l'objecte cada vegada que ho necessitis:

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

## Repositori d'entitats o models.

## Gestió de les relacions.