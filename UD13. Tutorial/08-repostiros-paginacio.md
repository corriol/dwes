---
layout: default
nav_order: 8
parent: 11. Introducció a Symfony
has_children: false 
nav_exclude: false

---
# Extrendre els repositoris. Paginació
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

# Introducció
Com ja has vist, l'objecte `Repository` permet executar consultes senzilles sense pràcticament cap treball:

```php
// from inside a controller
$repository = $this->getDoctrine()->getRepository(Link::class);
$links = $repository->findAll();
```
Els mètodes diponibles són els següents:
 
```php
/**
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
```

Però, i si necessitem una consulta més complexa? Quan generem una entitat amb `make:entity`, el comandament també genera una classe anomenada ProductRepository.

```php
// src/Repository/LinkRepository.php
namespace App\Repository;

use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class LinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
}

```
Quan obtens el repositori (p.e. `->getRepository(Link::class)`), estàs obtenint realment una instància d'aquest objecte!

{:.alert .alert-warning}
<div markdown="1">
### Anotacions
{:.no_toc .nocount }
Cal assegurar-se que l'entitat té correctament vinculat el repositori mitjançant una anotació. En el nostre exemple l'entitat Link haurà d'incloure:

```php
@ORM\Entity(repositoryClass="App\Repository\LinkRepository")
```
</div>

## QueryBuilder 
Suposem que vols fer una consulta que obtinga tots els objectes `Link` amb data posterior a certa data. Hauries d'afegir un nou mètode dins del repositori:

```php
// src/Repository/LinkRepository.php

// ...
class LinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Link[]
     */
    public function findAllAfterDate(DateTime $date): array{
        $query = $this->createQueryBuilder('l')
            ->andwhere("l.createdAt >= :date")
            ->orderBy('l.createdAt', 'DESC')
            ->setParameter('date', $date)
            ->getQuery();

        return $query->getResult();
    }

```


*Query Builder*, és una forma orientada a objectes d'escriure consultes. Es recomana utilitzar-la quan les consultes es creen dinàmicament.


Ara, pots cridar el mètode del repositori:

```php
// from inside a controller
$date = new DateTime("2020-01-01");

$products = $this->getDoctrine()
    ->getRepository(Link::class)
    ->findAllAfterDate($date);

// ...
```

## Query 

També es possible utilitzar l'objecte Query, així com executar consultes directament amb PDO.

```php
   /**
     * @return Link[]
     */
    public function findAllAfterDate(DateTime $date): array{
        $query = $this->createQuery('
            SELECT l
            FROM App\Entity\Link l
            WHERE l.createdAt >= :date
            ORDER BY l.createdAt DESC')
            ->setParameter('date', $date);


        return $query->getResult();
    }
```


## Consultes SQL 


```php
// src/Repository/LinkRepository.php

// ...
public function findAllAfterDate(DateTime $date): array
{
    $conn = $this->getEntityManager()->getConnection();

    $sql = '
        SELECT * FROM link l
        WHERE l.created_at > :date
        ORDER BY l.created_at DESC
        ';
    $stmt = $conn->prepare($sql);
    $stmt->execute(['date' => $date]);

    // returns an array of arrays (i.e. a raw data set)
    return $stmt->fetchAll();
}
```
Aquestes consultes SQL tornen arrays, no objectes (a no ser que uses la funcionalitat [NativeQuery](https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/native-sql.html)).

Més informació en [Querying for objects the repository](https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository)

## Exemple
En el següent exemple pots observar un ús del nou mètode del repositori. Hem modificat la ruta `homepage` perquè comprove si ha rebut pel _querystring_ el paràmetre `start-date` si és així executarà el mètode `findAllAfterDate()`si no cridarà `findAll()`.

```php
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request)
    {
        $repo = $this->getDoctrine()
            ->getRepository(Link::class);

        if ($request->query->has("start-date")) {
            $date = new \DateTime($request->query->get("start-date"));
            $links = $repo->findAllAfterDate($date);
            // TODO: check if the paràmeter start-date is a valid date

        }
        else {
            $links = $repo->findAll();
        }
        // now pass the array of default object to the view

        return $this->render('default/index.html.twig', [
            'links' => $links,
        ]);

    }
```

{:.alert .alert-activity}
<div markdown="1">
### Implementa el mètode findAllAfterDate
{: .no_toc .nocount }
Implementa el mètode `findAllAfterDate()` del repositori i modifica la ruta _homepage_ perquè en cas que reba el paràmetre `start-date` pel _querystring_ mostre sols aquells enllaços amb data d'actualització posterior a la data indicada.
</div>

# Paginació

Symfony no inclou un paginador de forma nativa però al incloure *Doctrine* permet implementar-ho fàcilment sense necessitat d'instal·lar nous components. A continuació presentarem tres possibles opcions d'implementació.

## La classe Paginator de Doctrine

En aquest cas crearíem nous mètodes en el repositori. `findAllPaginated` crea la consulta i la passa al mètode 
`paginate` que serà el que farà la paginació.

El mètode `paginate` ens tornarà un objecte Paginator que conté en la propietat Paginator::results els resultats.

Ens faltaria obtenir el total de registres la qual cosa és ben senzilla ja que la classe `Paginator` implementa la 
interfície `Countable` i simplement usant el mètode `count()` obtindrem el total de registres.

```php
use Doctrine\ORM\Tools\Pagination\Paginator;
...
public function findAllPaginated($currentPage = 1):?Paginator
{
    $query = $this->createQueryBuilder('l')
        ->orderBy('l.createdAt', 'DESC')
        ->getQuery();

    // No need to manually get get the result ($query->getResult())
    $paginator = $this->paginate($query, $currentPage);

    return $paginator;
}

// Paginate results. 
public function paginate($dql, $page = 1, $limit = 5):?Paginator
{
    $paginator = new Paginator($dql);

    $paginator->getQuery()
    ->setFirstResult($limit * ($page - 1)) // Offset
    ->setMaxResults($limit); // Limit

    return $paginator;
}
```

Més informació  en [Pagination](https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/tutorials/pagination.html) i
 [Symfony and Doctrine pagination with Twig](https://anil.io/blog/symfony/doctrine/symfony-and-doctrine-pagination-with-twig/)

## Classe Paginator de symfony/demo
Un altra opció és fer ús de la classe `Paginator` que s'utilitza en el projecte d'exemple de Symfony. Empra la classe
`Paginator` de Doctrine i ens proporciona tots els mètodes necessaris per a fer la paginació.

 * Classe [Paginator](https://github.com/symfony/demo/blob/master/src/Pagination/Paginator.php).
 * Repositori [PostRepositori:findLatest](https://github.com/symfony/demo/blob/master/src/Repository/PostRepository.php).
 * Mètode [BlogController::index()](https://github.com/symfony/demo/blob/master/src/Controller/BlogController.php).
 * Plantilla de Twig [blog/index.html.twig](https://github.com/symfony/demo/blob/master/templates/blog/index.html.twig).

## knplabs/knp-paginator-bundle
Paginator per a Symfony automatitza la paginació i simplifica l'ordenació i altres característiques.

En la descripció del paquet [knplabs/knp-paginator-bundle](https://packagist.org/packages/knplabs/knp-paginator-bundle) 
obtindreu més informació.

{:.alert .alert-activity}
<div markdown="1">
### Implementa el paginador 
{:.no_toc .nocount}
Implementa la paginació de links en la pàgina principal de forma que mostre 10 enllaços ordenats des del més actual al més antic. Pots emprar qualsevol dels mètodes que hem demostrat.
</div>

# Recursos
* [https://anil.io/blog/symfony/doctrine/symfony-and-doctrine-pagination-with-twig/](https://anil.io/blog/symfony/doctrine/symfony-and-doctrine-pagination-with-twig/)
* [https://www.drauta.com/como-paginar-los-resultados-con-doctrine](https://www.drauta.com/como-paginar-los-resultados-con-doctrine)
* [https://symfony.com/doc/current/components/http_foundation.html#request](https://symfony.com/doc/current/components/http_foundation.html#request)
* [http://zetcode.com/symfony/request/](http://zetcode.com/symfony/request/)

