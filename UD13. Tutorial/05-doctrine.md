---
layout: default
parent: 11. Introducció a Symfony 
nav_order: 4
has_children: false 
---

# Gestió del model de dades amb Doctrine
{:no_toc}

1. Taula de continguts
{:toc}

## Introducció a Doctrine

### Què és un ORM?

Un ORM (_Object Relational Mapping_) és un _framework_ encarregat de tractar
amb una base de dades relacional (connectar amb ella, realitzar
operacions de consulta, inserció, etc.), de manera que, de cara a
l'aplicació, *es converteixen a objectes tots els elements que
s'extraguen de la base de dades*m i viceversa (els objectes de
l'aplicació es transformen en registres de la base de dades, arribat el
cas). D'aquesta forma, l'ORM s'encarregarà de realitzar aquesta
conversió o mapatge automàticament per nosaltres. Definint una sèrie de
regles, indicarem quines taules de la base de dades relacional es
corresponen amb quines classes del nostre model, i quins camps de cada
taula es corresponen amb quins atributs de cada classe. A partir d'ací,
l'ORM s'encarregarà d'extraure la informació de la base de dades i
crear els objectes corresponents, o de convertir els objectes amb els
seus atributs en registres de la base de dades, amb les seues
corresponents columnes.

El principal avantatge d'utilitzar un ORM com Doctrine és aïllar
l'aplicació del gestor de base de dades que hàgem triat (MySQL, Oracle,
PostgreSQL...) ja que a *nivell d'aplicació treballarem amb objectes*, i
serà Doctrine qui s'encarregue de connectar amb la base de dades
triada, i transformar els objectes per a adaptar-los a la mateixa.

### Configuració bàsica de Doctrine

Per a poder utilitzar Doctrine, hem d'indicar com connectar al servidor
de base de dades que anem a utilitzar. Aquests paràmetres de connexió es
poden configurar en l'arxiu `.env` del nostre projecte. Aquest és un arxiu
on es defineixen certes variables pròpies d'entorn, que després es
processen i es converteixen en variables reals. En el nostre cas,
definim una variable anomenada `DATABASE_URL`, amb una URL on s'especifiquen tant
l'adreça i port de connexió a la base de dades, com el _login_ i _password_
necessaris per a accedir, així com el nom de la base de dades a la qual
connectar. Per exemple, per a una base de dades MySQL, l'estructura
general serà aquesta:

```shell
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```

Tenint en compte l'usuari i contrasenya per defecte de phpMyAdmin per a
XAMPP (usuari _root_ i contrasenya buida), aquest podria ser un valor
vàlid per a connectar a la nostra base de dades de contactes:

```shell
DATABASE_URL=mysql://root@127.0.0.1:3306/movies-symfony
```

Aquest arxiu `.env` pot utilitzar-se tant en desenvolupament com en
producció, encara que en aquest últim cas es recomana definir variables
d'entorn reals en el sistema, per a evitar pèrdua de rendiment en haver
de traduir aquest arxiu per a cada petició.

<div markdown="1" class="alert-info alert">
Symfony suporta la noció d'entorns. Per defecte, té suport integrat per tres entorns, 
però pots afegir-ne tants com vulgues: `dev`, `prod` i `test`. Tots els entorns 
comparteixen el mateix codi, però treballen amb configuracions diferents.

Per exemple, totes les eines de depuració estan habilitades en l'entorn `dev`. 
En l'entorn `prod` l'aplicació està optimitzada per al rendiment.

El canvi d'un entorn a un altre pot realitzar-se modificant la variable d'entorn `APP_ENV`.

Un arxiu `.env` amb uns valors acuradament escollits es genera automàticament quan es crea el projecte:

```
###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=f307a57c2c035484820f08a0494076dc
###< symfony/framework-bundle ###

###> symfony/mailer ###
# MAILER_DSN=smtp://localhost
###< symfony/mailer ###

DATABASE_URL="mysql://root@127.0.0.1:3306/symfony-movies"
```

Qualsevol paquet pot afegir més variables d'entorn a aquesta imatge gràcies a la seva 
recepta utilitzada per Symfony Flex.

El fitxer `.env` s'envia al repositori i descriu els valors per defecte de l'entorn
 de producció. Podeu substituir aquests valors creant un fitxer `.env.local`. Aquest 
 fitxer no ha de ser enviat a l'repositori i és per això que el fitxer `.gitignore` ja l'està ignorant.

Mai guardis dades secretes o confidencials en aquests arxius. 
</div>


En el cas que la base de dades encara no existisca, _Doctrine_ pot
crear-la per nosaltres. Per a açò, n'hi ha prou amb escriure el següent
comando:

```shell
php bin/console doctrine:database:create

Created database 'movies-symfony' for connection named default
```
Automàticament, es prendrà el nom de la base de dades de la variable
d'entorn anterior, es connectarà al servidor i es crearà (sense taules,
de moment).

## Creació d'entitats

Les entitats són les classes que van a compondre el model de dades de la
nostra aplicació. Per exemple, per a la nostra aplicació de pel·licules,
necessitarem una entitat/classe anomenada `Movie` que emmagatzeme les
dades concretes de cada película (_id_, _title_, _tagline_, _poster_, _release_date_ 
i _overview_).

Per a crear una entitat, emprem el següent comando des del terminal
(dins de la carpeta principal del nostre projecte Symfony):

```shell
php bin/console make:entity
```

S'iniciarà un assistent que ens anirà demanant informació per a
construir l'entitat:

-   Nom de la classe o entitat
-   Propietats o atributs de la classe, per a cadascun, demanarà el nom
    (si directament premem `Intro` deixarà de demanar-nos més dades), el
    tipus de dada, la longitud o grandària del camp, si admet nuls...

Per exemple, per al cas de la nostra classe `Movie`, el procés quedaria
així:

```
php bin/console make:entity

 Class name of the entity to create or update (e.g. FiercePuppy):
 > Movie

 created: src/Entity/Movie.php
 created: src/Repository/MovieRepository.php
 
 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > title

 Field type (enter ? to see all types) [string]:
 > string

 Field length [255]:
 > 100

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Movie.php

 Add another property? Enter the property name (or press <return> 
 to stop adding fields):
 > tagline

 Field type (enter ? to see all types) [string]:
 > string

 Field length [255]:
 > 255

 Can this field be null in the database (nullable) (yes/no) [no]:
 > yes

 updated: src/Entity/Movie.php

 Add another property? Enter the property name (or press <return> 
 to stop adding fields):
 > poster

 Field type (enter ? to see all types) [string]:
 > string

 Field length [255]:
 > 100

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Movie.php

 Add another property? Enter the property name (or press <return> 
 to stop adding fields):
 > releaseDate 

 Field type (enter ? to see all types) [string]:
 > date

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Movie.php

 Add another property? Enter the property name (or press <return> 
 to stop adding fields):
 > overview

 Field type (enter ? to see all types) [string]:
 > text

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Movie.php

 Add another property? Enter the property name (or press <return> 
 to stop adding fields):
 > 
           
 Success! 
          
Next: When you're ready, create a migration with php bin/console make:migration

```

Com a resultat, es generarà una classe `Movie` dins de la carpeta
`src/Entity`. El codi queda com segueix:

```php
/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tagline;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $poster;

    /**
     * @ORM\Column(type="text")
     */
    private $overview;

    /**
     * @ORM\Column(type="date")
     */
    private $releaseDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    public function setTagline(?string $tagline): self
    {
        $this->tagline = $tagline;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }
}
```
Com podem observar, el camp `id` que usàvem en la nostra base de dades
de prova (el servei creat a tal efecte) l'hem reemplaçat per un camp enter 
 que es genera automàticament com a clau principal de la classe. Per tant, només hem
hagut d'especificar el títol, l'eslògan, la sinopsi, la data d'estrena i el pòster.

Quant als tipus de dades que podem especificar, si premem `?` i `Intro` quan
anem a especificar el tipus de dada, veurem un llistat complet dels
tipus disponibles (també ho podeu consultar ací). L'habitual serà
treballar amb cadenes de text d'una longitud determinada (_string_),
textos il·limitats (_text_), enters (_integer_), booleans (_boolean_), reals
(_float_), dates (_timestamp_ o _datetime_, depenent del que vulguem
emmagatzemar)...

### Generació de l'esquema

Una vegada hem definit l'entitat, podem generar la corresponent taula
en la base de dades. Per a açò, escrivim aquest comando:

```shell
php bin/console make:migration
```

El que fa aquest comando és aplicar els canvis entre el nostre model
d'entitats i l'esquema de la base de dades, i generar un arxiu PHP que
s'encarregarà de bolcar aqueixos canvis a la base de dades. Per consola
se'ns informarà d'on està aquest arxiu perquè ho comprovem (estarà en
la carpeta `src/Migrations`), i si tot és correcte, executant aquest altre
comando es reflectiran els canvis en la base de dades:

```shell
php bin/console doctrine:migration:migrate
```

Com podem comprovar, cada atribut de la nostra entitat `Movie` es
correspon amb un camp del mateix nom en la taula `movie`.

![movies structure](assets/symfony-movies-phpMyAdmin.png)

Convé esmentar també que aquest últim comando executarà
tots els arxius de migració que encara no s'hagen migrat efectivament a la base 
de dades, per la qual cosa si hi ha algun que no
vulguem migrar (perquè s'haja generat equivocadament, per exemple),
haurem d'eliminar-la abans.

<div markdown="1" class="alert-activity alert">
### Exercici 5.1

En l'aplicació de pel·lícules, crea una nova entitat anomenada `Movie` amb
els següents atributs:

-   title (_string_ de grandària 255)
-   tagline (_string_ de grandària 100)
-   poster (_string_ de grandària 255)
-   overview (_text_)
-   releaseDate(_DateTime_)

Sol el camp _tagline_ admetra nuls. Recorda definir prèviament la
variable `DATABASE_URL` en les variables d'entorn, crear la base de
dades, i després migrar aquests canvis a la base de dades, emprant els
comandos vistos en aquesta sessió.

El camp `id` (_enter_, autonumèric) es crea automàticament.
</div>

<div markdown="1" class="alert-activity alert">
### Exercici 2

Realitza l'exercici anterior en el teu projecte Symfony, creant la base de dades,
l'entitat principai i la taula relacionada en la base de dades.
</div>


### Editar entitats

Què passa si, després de crear una entitat, volem modificar la seua
estructura? Podem editar la classe de l'entitat manualment per a
afegir, modificar o esborrar camps, però també podem tornar a executar
el comando `make:entity`, indicar el mateix nom de classe que volem
modificar, i especificar els nous camps que vulguem afegir (en el cas
que el que vulguem siga afegir camps).

Després de definir els canvis en la(s) entitat(s) desitjada(es), haurem
de generar una nova migració amb els comandos vistos en el subapartat
anterior.

### Establir altres claus primàries

Per defecte, hem vist que Doctrine agrega un camp `id` a les entitats,
que és autonuméric i actua com a clau primària. En el cas que no
vulguem que siga així, i preferim triar un altre camp no autonumèric
com a clau primària, hem de seguir aquests passos:

- Eliminar l'atribut `id` i el seu `getter` corresponent de l'entitat.
- Afegir la següent anotació a l'atribut que hàgem triat com a clau primària: 

```php
 /**
   * @ORM\Id()
   * ...
 */
  private $fieldName
```
En el cas que siga una clau primària composta per més d'un camp, haurem
d'afegir aquesta anotació en cada camp que forme part de la clau
primària.

## Operacions contra la base de dades

Ara que ja hem vist com definir entitats simples, vegem com realitzar
operacions amb elles, tals com a insercions, esborrats, modificacions i
consultes. Per a realitzar aquestes operacions, ens valdrem d'un
objecte molt important en Doctrine, el seu entity manager, a través del
com farem les insercions, esborrats, etc. També utilitzarem el
repositori de l'entitat corresponent, per a realitzar les cerques.

### Inserir objectes

Si volem afegir objectes nous a la nostra base de dades, n'hi ha prou
que creiem un objecte de l'entitat corresponent en el mètode oportú, i
cridem al mètode persist i flush de l'entity manager de Doctrine.

Per exemple, per a provar, anem a crear un controlador en la nostra
classe `MovieController` associat a una ruta `/movies/create`, que de
moment serà de proves fins que fem un formulari d'inserció. Dins
d'aquest mètode, crearem un objecte `Movie` amb dades prefixades,
obtenim l'entity manager de Doctrine i persistim l'objecte:

```php
/**
* @Route("/movies/create", name="movies_create")
*/
public function create()
{
    $entityManager = $this->getDoctrine()->getManager();
    $movie = new Movie();
    $movie->setTitle("Ava");
    $movie->setTagline("Kill. Or be killed.");
    $movie->setOverview("A black ops assassin is forced to fight for her 
        own survival after a job goes dangerously wrong.");
    $movie->setReleaseDate(new DateTime("2020-09-25"));
    $movie->setPoster("noposter.jpg");
    ...
    $entityManager->persist($movie);
    $entityManager->flush();
    return new Response("The movie with id " . $movie->getId(). 
        " has been inserted successfully!" );
}
```

Si accedim des del navegador a `movies-symfony/movies/create`
podrem veure el resultat en la taula
`movie` de la nostra base de dades:



És important recalcar que la cridada `persist` per si sola no actualitza
la base de dades, sinó que indica que es vol persistir l'objecte
indicat. És la crida a `flush` la que fa efectiva aqueixa persistència.

#### Detectant errors en la inserció

Si ocorre algun error en la inserció (per exemple, perquè algun camp és
nul i no puga ser-ho, o perquè es duplica la clau primària), es
provocarà una excepció en cridar al mètode `flush`. Podem tractar aquest
error simplement capturant l'excepció i generant la resposta apropiada:

```php
$entityManager->persist($object);
try
{
    $entityManager->flush();
    return new Response("Object inserted");
} catch (Exception $e) {
    return new Response("Error inserting objects");
}
```

### Obtenir objectes

A l'hora d'obtenir objectes d'una taula, existeixen diferents mètodes
que podem emprar. Per exemple:

-   El mètode `find` localitza l'objecte per la clau primària (normalment
    l'`id`) que se li passa com a paràmetre. Així cercaríem la pel·lícula
    amb id 1: `$movie = $repositori->find(1)`;
-   El mètode `findOneBy` localitza un objecte que complisca els criteris
    de cerca passats com a paràmetre. Així cercaríem la pel·lícula el títol
    del qual siga "Ava": 
    ```php
    $movie = $repositori->findOneBy(["title" => "Ava"]); 
    ```
    En el cas
    de voler definir més criteris de cerca, es passarien un darrere    
    l'altre en l'array, separats per comes.
-   El mètode `findBy` localitza tots els objectes que complisquen els
    criteris de cerca passats com a paràmetre. Aquesta instrucció és com
    l'anterior, però retorna un array de pel·lícules amb tots els
    resultats coincidents (en el cas que hi haja un sol resultat,
    retorna un array d'un element):
    ```php
     $movies = $repositori->findBy(["title" => "Ava"]);
    ```
-   El mètode `findAll` (sense paràmetres), obté tots els objectes de la
    col·lecció. 
    ```php
    $movies = $repositori->findAll();
    ```

Tots aquests mètodes s'obtenen a partir d'un repositori de la classe,
que ve a ser alguna cosa així com un assistent que ens ajuda a obtenir
objectes que pertanguen a aqueixa classe.

Vegem un exemple amb la nostra classe `MovieController` anem a
modificar el nostre mètode `show` perquè, en lloc de cercar en la base de
dades de prova que hem vingut emprant en sessions anteriors, cerque per
aneu en la base de dades real. Per a açò, obtenim el repositori de la nostra 
classe `Movie` i cerquem (_find_)
la pel·lícua amb l'id que hem rebut com a paràmetre:

```php
    /**
     * @Route("/movies/{id}", name="movies_show", requirements={"id"="\d+"})
     */
    public function show(int $id)
    {
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $movieRepository->find($id);
        if ($movie)
        {
            return $this->render('movies_show.html.twig', ["movie"=>$movie]
            );
        }
        else
            return $this->render('movies_show.html.twig', [
                'movie' => null]
        );
    }
```

<div markdown="1" class="alert-activity alert">
### Exercici 5.3

#### Movies
Implementa el mètode `create` vist anteriorment en el projecte `Movies`.


#### Projecte personal
Sobre la base de dades del teu projecte i l'entitat/taula principal que hem creat
en l'exercici anterior, implementa mitjançant un controlador per a l'entitat:

1.  Implementa un nou mètode del controlador anomenat `create`, que cree un element en la base de dades
3.  Implementa el mètode `show` que obtindrà un element de la base de dades pel seu id.
4.  Implementa el mètodo `home()` en la classe `HomeController`
    perquè mostre el llistat de la taula principal de MySQL en lloc de la
    base de dades de proves.
</div>

### Consultes més avançades

Amb els mètodes de consulta anteriors podem realitzar consultes que es
limiten a comprovar si un o diversos camps d'un objecte són iguals a
uns criteris de cerca determinats. Però, com podríem, per exemple,
cercar les pel·lícules el títol dels quals continga un cert text, o les pel·licules
estrenades en un període determinat? Per a aquest tipus de consultes,
necessitem ampliar el repositori de la nostra entitat.

Per exemple, per a la nostra entitat `Movie`, imaginem que volem cercar
les pel·lícules el títol dels quals coincideix en un cert text. Per a aconseguir
açò, necessitem editar el repositori de l'entitat, que està en
`src/Repository/MovieRepository.php`. Aquest arxiu conté comentats un
parell de mètodes de prova que podríem definir per a ampliar les
capacitats de l'entitat.

En el nostre cas, anem a afegir un mètode que s'encarregarà d'obtenir
les pel·lícules el títol dels quals continga un text determinat que li
passem com a paràmetre:
```php
    /**
      * @return Movie[] Returns an array of Movie objects
      */

    public function findByTitle(string $value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.title LIKE :value')
            ->setParameter('value', "%".$value."%")
            ->orderBy('m.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
```
Emprem el _query builder_ de Doctrine per a construir la consulta amb
aqueixa sintaxi específica. En primer lloc, definim un element que hem
cridat `m` (de `Movie`) que usarem per a referenciar les propietats de
les pel·lícules, per exemple, en la clàusula `where`. El que ve a fer aquest codi és cercar aquelles
pel·lícules `m` el títol de les quals coincideixca amb el paràmetre `text`, i a
continuació específica que aquest paràmetre `text` és igual al paràmetre
que rebem en el mètode, tancat entre símbols '%', per a indicar que és
igual el que hi haja davant o darrere del text.

Ara, ja podríem utilitzar aquest mètode des d'on ho necessitem. Per
exemple, podem modificar el mètode `filter` de `MovieController` perquè
cerque pel·lícules per títol emprant aquest nou mètode:

```php
    /**
     * @Route("/movies/{text}", name="movies_filter")
     */
    public function filter(string $text)
    {

        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $movieRepository->findByTitle($text);
        return $this->render('movies_filter.html.twig', array(
            'movies' => $movies
            ));        
                
    }

```
Si, per exemple, volguérem cercar per una propietat numèrica (per
exemple, persones l'edat de les quals siga major que una donada),
usaríem una sintaxi com aquesta (també molt similar a SQL):

```php
$qb = $this->createQueryBuilder('p') 
    ->andWhere('p.edat > :edat')
    ->setParameter('edat', $edat)
    ->getQuery();
```

Alternativament, també podem emprar un llenguatge anomenat DQL (Doctrine
Query Language) per a realitzar la consulta anterior:

```php
    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery('SELECT m FROM App\Movie m WHERE m.title LIKE
    :text' )->setParameter('text', '%'. $text. '%');
    return $query->execute();
```

I, com a tercera via, també podem emprar SQL estàndard, però en aquest
cas el que obtindríem ja no seria un array d'objectes, sinó un array de
registres, com si empràrem la llibreria PDO de PHP per a accedir a la
base de dades.

Ací teniu enllaços per a consultar informació addicional tant de [Query
Builder](https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/query-builder.html) 
com del [llenguatge DQL](https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/dql-doctrine-query-language.html).

<div markdown="1" class="alert-activity alert">
### Exercici 5.4 

Modifica el mètode `filter` anterior de la classe `MovieController`
 i perquè cerque els elements que continguen una text determinat 
però enviat pel _query string_. Anirà associat a la URI `/movies/filter` i 
renderizará una vista (pot ser la pàgina principal), 
passant-li com a paràmetre el llistat filtrat.

Per a obtenir els paràmetres del query string pots usar la classe
[Request](https://symfony.com/doc/current/components/http_foundation.html#request) creant 
l'objecte posant-lo com a paràmetre tipat.

Empra per a fer la consulta o bé el _Query Builder_ de Doctrine o bé el
seu llenguatge DQL, afegint el mètode corresponent en el repositori de
l'entitat.
</div>

### Actualitzar i esborrar objectes

#### Actualitzar objectes

Per a actualitzar un objecte en una base de dades, hem de seguir tres
passos:

1.  Obtenir l'objecte de la base de dades (típicament fent un find per
    la seua clau primària)
2.  Modificar les dades necessàries amb els respectius setters de
    l'objecte
3.  Fer un _flush_ per a actualitzar els canvis en la base de dades.

Si, per exemple, volguérem actualitzar les dades de la pel·lícula amb `id` =
1, faríem açò:

```php
    $entityManager = $this->getDoctrine()->getManager();
    $repository = $this->getDoctrine()->getRepository(Movie::class); 
    $movie = $repository->find( 1 );

    if ($movie) {
        $movie->setTitle("Updated movie");
        $entityManager->flush(); 
    }
```
#### Esborrar objectes

L'esborrat d'objectes és similar a l'actualització: hem d'obtenir
l'objecte també, però després cridem al mètode `remove` per a
esborrar-lo, i finalment a `flush`. Així esborraríem la pel·lícula amb
id 1

```php
$entityManager =$this->getDoctrine()->getManager();
$repository = $this->getDoctrine()->getRepository(Movie::class); 
$movie = $repository->find(1);

if ($movie) { 
    $entityManager->remove($movie);
    $entityManager->flush(); 
}
```
Novament, tant en l'actualització com en l'esborrat, el mètode `flush`
pot provocar una excepció si l'operació no ha pogut dur-se a terme. Hem
de tenir-ho en compte per a capturar-la i generar la resposta oportuna.

## Relacions entre entitats

Fins ara les operacions que hem fet s'han centrat en una única taula o
entitat (l'entitat/taula de pel·lícules en l'exemple de les anotacions). 
Vegem ara com podem treballar amb més d'una taula/entitat que estiguen relacionades
entre sí.

Existeixen dos tipus principals de relacions entre entitats:

-   Molts a un: en aquest tipus s'englobarien les relacions "un a
    molts", "molts a un" i "un a un", ja que en qualsevol dels tres
    casos, la relació es reflecteix afegint una clau ajeno en una de les
    dues entitats que referencie a l'altra.
-   Molts a molts: en aquest tipus de relacions, es necessita d'una
    taula addicional per a reflectir la relació entre les entitats.

Anem a definir una relació molts a un en la nostra base de dades de
pel·lícules. Per a açò, anem a crear primer una entitat anomenada
`Genre`, que només continga un id autoincremental i un nom (_string_):

```shell
php bin/console make:entity

Class name of the entity to create or update (i.g. AgreeableGnome):
>Genre

created: src/Entity/Genre.php
created: src/Repository/GenreRepository.php

New property name (press to stop adding fields):
>name

Field type (enter? to see all types) [string]:
>string

Field length [255]:
>255

Can this field be null in the database (nullable) (yes/no) [no]:
>no

updated: src/Entity/Genre.php

Add another property? Enter the property name (or press to stop adding
fields):

Success!
```
Després de generar la nova entitat, crearem la corresponent taula en
la base de dades a través de la migració.

```shell
php bin/console make:migration

php bin/console doctrine:migration:migrate
```

Ara, anem a fer que les pel·lícules tinguen un gènere associat. Per a
açò, editem l'entitat `Movie` i li afegim un nou camp, anomenat
gènere, que serà de tipus **relació molts a un** (una pel·lícula pertanyerà
a una categoria, i un gènere pot tenir moltes pel·lícules).

```shell
php bin/console make:entity

Class name of the entity to create or update (i.g. DeliciousPuppy):
> Movie
Your entity already exists! So let's add some new fields!

New property name (press to stop adding fields):
>genre

Field type (enter? to see all types) \[string\]:
>relation

What class should this entity be related to?:
>Genre

What type of relationship is this?
---------------------------------------------------------------------
Type        Description
---------------------------------------------------------------------
ManyToOne   Each Movie relates to (has) one Genre.
            Each Genre can relate/has to (have) many Movie objects

OneToMany   Each Movie can relate to (have) many Genre objects.
            Each Genre relates to (has) one Movie

ManyToMany  Each Movie can relate to (have) many Genre objects.
            Each Província can also relate to (have) many Contacte objects

OneToOne    Each Movie relates to (has) exactly one Genre.
            Each Genre also relates to (has) exactly one Movie.

Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:
> ManyToOne

Is the Movie.genre property allowed to be null (nullable)?
(yes/no) [yes]:
> no

Do you want to add a new property to Genre so that you can
access/update Movie objects from it - i.g. $genre-\>getMovies()? (yes/no) [yes]:
>no

updated: src/Entity/Movie.php

Add another property? Enter the property name (or press to stop adding
fields):

Success!
```

Com pot veure's, a l'hora de triar el tipus de camp, indiquem que és
una relació (_relation_), en aquest cas ens demana indicar a quina entitat
està vinculada (Genre, en aquest cas), i quin tipus de relació és
(_ManyToOne_ en el nostre cas, però podem triar qualsevol de les altres
tres opcions _OneToMany_, _OneToOne_ o _ManyToMany_). També podem comprovar
que l'assistent ens pregunta si volem afegir un camp en l'altra
entitat perquè la relació siga bidireccional (és a dir, perquè des d'un
objecte de qualsevol de les dues entitats puguem consultar el/els
objecte(s) associat(s) de l'altra. En aquest cas indiquem que no per a
simplificar el codi.

Després d'aquests canvis, realitzem de nou la migració, i ja tindrem el
nou camp afegit en la nostra entitat Movie i a la taula movie de
la base de dades:

```php
/**
* @ORM\ManyToOne(targetEntity="App\Entity\Genre")
* @ORM\JoinColumn(nullable=false)
*/
```
### Treballar amb entitats relacionades

Ara que ja sabem relacionar entitats entre sí. Com podem inserir una
entitat que depèn d'una altra, o accedir a les dades d'una entitat des
de l'altra?

#### Inserció d'entitats relacionades

Per exemple, si volguérem inserir un pel·lícula assignant-li un
gènere:

-   Si el gènere no existeix, crearem un objecte de tipus
    Genre, i després un altre de tipus Movie, establint com a
    `genre` l'objecte `Genre` recentment creat: 
    
    ```php
    $entityManager = $this->getDoctrine()->getManager();
    $genre = new Genre();
    $genre->setName("Western");

    $movie = new Movie();
    $movie->setTitle("A test title");
    $movie->setTagline("A test tagline");
    $movie->setReleaseDate(new \DateTime());
    $movie->setPoster->setPoster("noposter.jpg");
    $movie->setOverview->setOverview("A test overview");
    $movie->setGenre($genre);

    $entityManager->persist($genre);
    $entityManager->persist($movie);

    $entityManager->flush();
    ```

-   Si el gènere sí existeix, el recuperem de la base de dades (amb
    algun mètode `find` o similar) i després crearem crear l'objecte 
    Movie i li assignem aqueix objecte Genre: 
    
    ```php
    $entityManager = $this->getDoctrine()->getManager();
    $repositori = $this->getDoctrine()->getRepository(Genre::class);
    $genre = $repository->find(1);

    $movie = new Movie();
    $movie->setTitle("A test title");
    $movie->setTagline("A test tagline");
    $movie->setReleaseDate(new \DateTime());
    $movie->setPoster->setPoster("noposter.jpg");
    $movie->setOverview->setOverview("A test overview");
    $movie->setGenre($genre);

    $entityManager->persist($genre);
    $entityManager->persist($movie);

    $entityManager->flush();
    ```  
  
#### Obtenir entitats relacionades

En el cas que recuperem una entitat que està relacionada amb una
altra, l'accés a aqueixa altra entitat és immediat des de la primera.
Per exemple, si volguérem saber el nom del gènere d'una pel·lícula amb
id 1, faríem alguna cosa així:

```php
$repositori = $this->getDoctrine()->getRepository(Movie::class);
$movie = $repositori->find(1); 
$genreName = $movie->getGenre()->getName();
```

En qualsevol cas, és important recalcar que Doctrine no recupera les
dades de l'entitat relacionada (la província, en aquest cas), fins que
es demanen efectivament (és a dir, fins que no preguntem pel nom del genre, 
Doctrine no tracta d'obtenir l'objecte Genre complet). Aquesta estratègia
de recuperació d'entitats relacionades s'anomena _lazy loading_ o càrrega
diferida.


<div markdown="1" class="alert-activity alert">
### Exercici 5

Implementa la relació de Genre i Movie en el projecte `movies-symfony`.

En el projecte personal crea una nova entitat relacionada i
implementa la inserció amb d'un elementa amb l'entitat relacionada.
</div>