---
layout: default
parent: 11. Introducció a Symfony 
nav_order: 3
has_children: false 
---

# El contenidor de serveis i la injecció de dependències
{: .no_toc}
1. Taula de continguts
{:toc }

## Introducció

La **injecció de dependències** és un concepte habitualment lligat
a _frameworks_ de desenvolupament web. Fa referència a un patró de
disseny orientat a relacionar adequadament els objectes que componen
l'aplicació de manera que es comparteixen certs recursos globals o es
subministren a cada classe que els necessite en lloc de ser la pròpia
classe qui els crea. Açò afavoreix el desacoblament de la nostra
aplicació, en permetre que els elements que la componen siguen més
independents entre sí. Per exemple, imaginem que hem d'accedir des de
diverses classes o fitxers font a una base de dades MySQL. Sense la
injecció de dependències, hauríem de crear la connexió a la base de
dades en cadascuna d'aqueixes classes o fitxers font. No obstant açò,
comptant amb aquesta característica, algun element de l'aplicació
s'encarregarà de crear la connexió, i facilitar-la als altres elements
que la necessiten.

Per a gestionar aquesta injecció de dependències Symfony utilitza un
objecte anomenat **contenidor de serveis** que és qui s'encarregarà
 de crear les instàncies de tots aqueixos elements que van a
ser compartits o accedits des de diferents llocs del codi, i que
cridarem **serveis**. 

Symfony ja compta amb una sèrie de serveis predefinits, i cada mòdul de
tercers (*bundle*) que afegim a l'aplicació incorpora els seus
propis.

### Configuració general dels serveis

De forma general, els serveis es configuren en l'arxiu
**services.yaml** dins de la carpeta *config* de la nostra aplicació. Si
fem una ullada a la configuració d'inici, podem distingir quatre
apartats:

1.  Configuració de paràmetres globals per a tots els serveis. Ací
    definirem les propietats que podran ser accedides per tots els
    serveis que utilitzem o creem. Per exemple, hi ha un paràmetre
    *locale* per a indicar que la localització de l'aplicació empra
    l'idioma anglés per defecte 
    ```yaml
    parameters:
        locale: 'en'
    ```
2.  Després es té la secció de *services*. El primer apartat dins
    d'aquesta secció indica la configuració per defecte que tindran els
    serveis: 
    ```yaml
    services: 
        _defaults: 
            autowire: true
            autoconfigure: true
            public: false
    ```
     _autowire_ fa referència al fet que els serveis
    s'auto-injecten, és a dir, quan es passa com a paràmetre un servei
    a un mètode indicant el nom de la classe, automàticament Symfony
    crea l'objecte corresponent i el passa com a paràmetre. Per
    exemple, si fem: 
    
    ```php
    use Psr;
    class ...

    {
        public function method(LoggerInterface $logger)

    ```
Symfony detectarà la classe _LoggerInterface_ com un servei existent,
crearà una instància del mateix i la passarà al mètode com a paràmetre.
    * _autoconfigure_ indica que els serveis que es creen es registren automàticament atenent al seu tipus. Per exemple, si vam crear una
classe que hereta de Command, es registrarà automàticament com un comando.
    * _public_ indica el nivell de visibilitat dels serveis, que per defecte
no és públic (opció recomanada).

3.  A continuació, hi ha una secció que permet que qualsevol cosa que
    definim en la carpeta *src* es puga utilitzar com a servei, i
    injectar-se en altres elements, a excepció dels elements indicats en
    la propietat *exclude*
    ```yaml
    App\: 
        resource: '../src/*'
        exclude:
            - '../src/DependencyInjection/'
            - '../src/Entity/'
            - '../src/Kernel.php'
            - '../src/Tests/'
    ```
4.  Finalment, hi ha un últim apartat dedicat als controladors, per a
    permetre que els serveis se'ls puguen injectar com a arguments,
    encara que no heretem de cap classe base de controlador. 
    ```yaml
    App\Controller\:
        resource: '../src/Controller' 
        tags: ['controller.service_arguments']
    ```

### Mostrar serveis actuals

En sessions anteriors, en parlar del comando *bin/console* que tenim
disponible en qualsevol projecte Symfony, vam posar com a exemple un
comando que mostra tots els serveis que es tenen actualment disponibles
en la nostra aplicació:

```shell
php bin/console debug:autowiring
```

## Utilitzar serveis

En Symfony existeixen multitud de serveis ja predefinits i llestos per a
utilitzar-se, com per exemple un *mailer* per a enviar correus
electrònics, o un *logger* per a generar missatges de log de diferent
índole (errors, *warnings* , etc). Per a utilitzar-los, n'hi ha prou
amb passar com a paràmetre al controlador que ho requerisca un objecte
del tipus corresponent. Per exemple, si volem utilitzar un *logger* ,
Symfony posa a la nostra disposició el *bundle* Monolog, a través de la
classe *LoggerInterface.* N'hi ha prou que passem un paràmetre
d'aquest tipus al nostre controlador per a utilitzar-ho.

Anem al nostre projecte de pel·lícules, en concret a la classe
`src/Controller/HomeController`, i utilitzem aquest *logger* per a
traure un missatge amb la data i hora de l'accés a la pàgina d'inici.
La classe quedarà així:

```php
<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $now = new \DateTime();

        $this->logger->info("Access on {$now->format("Y/m/d H:i:s")}");
        return $this->render("home.html.twig");
    }
}
```
<div markdown="1" class="alert-info alert">
NOTA: observa com hem emprat la classe DateTime de PHP, posant-li una barra 
invertida davant perquè la reconega com a pròpia de PHP.
</div>

Podríem haver passat directament l'objecte *LoggerInterface* al mètode
*home*, però és més habitual passar els serveis a un constructor de
la classe i guardar-los en atributs de la mateixa, per a poder ser
utilitzats per més d'un mètode.

Si accedim a l'arrel de l'aplicació (
[*http://movies-symfony*](http://movies-symfony/) ), es generarà
el corresponent missatge de registre (_log_). Aquests missatges es guarden per
defecte en la subcarpeta `var/log`, en concret en l'arxiu `dev.log` si estem en
mode desenvolupament, o en `prod.log` si estem en producció. Es poden
configurar aquests arxius i altres opcions, però l'ús d'aquesta
llibreria no forma part dels continguts d'aquest curs, l'emprarem
només com a exemple d'ús de serveis, i per a alguna depuració puntual
d'algun controlador.

A més del mètode *info* vist en l'exemple, existeixen altres mètodes
per a generar missatges de major o menor prioritat, com per exemple
*warning, error, critical...* Es pot obtenir un llistat fent una ullada
al codi de `LoggerInterface.`

## Crear serveis

Anem a vore ara com crear els nostres propis serveis. Seguint amb la nostra
aplicació d'exemple (movies), anem a extraure la "base de dades" de
pel·lícules a un servei. Si recordem, per a evitar de moment utilitzar una
base de dades real, havíem creat a mà un array de pel·lícules en la nostra
classe *src/Controller/MovieController*. El que farem ara serà
definir aquest array dins d'un servei, per a poder accedir a ell des de
qualsevol element de l'aplicació.

Els serveis es poden crear en qualsevol carpeta de *src* , ja que, com
hem vist, qualsevol element d'aquesta carpeta (excepte uns pocs
preconfigurats) automàticament es defineix com a servei. Per a
agrupar-los tots, podem crear-los, per exemple, en la subcarpeta
*src/Service*. En el nostre cas, anem a cridar a la classe del servei
*DBTest*. Definim dins l'array, i un mètode que ho retorne:

```php
namespace App\Service;


class DBTest
{
    private array $movies = [
        ["id"=>"2", "title" => "Ava", 
        "tagline" => "Kill. Or be killed",
            "release_date" => "25/09/2020"],
        ["id" => "3", 
        "title" => "Bill &Ted Face the Music",
        "tagline" => "The future awaits", 
        "release_date" => "24/09/2020"],
        ["id" => "4", "title" => "Hard Kill",
            "tagline" => "Take on a madman. Save the world.", 
            "release_date" => "14/09/2020"],
        ["id" => "5", "title" => "The Owners", "tagline" => "",
            "release_date" => "10/05/2020"],
        ["id" => "6", "title" => "The New Mutants",
            "tagline" => "It's time to face your demons.", 
            "release_date" => "20/04/2020"],
    ];

    public function get():array {
        return $this->movies;
    }
}
```


La nostra classe *src/Controller/MovieController* quedarà d'aquesta
manera:

```php
...
class MovieController extends AbstractController
{

    private array $movies;
    
    public function __construct(DBTest $data)
    {
        $this->movies = $data->get();
    }
    ...
```

Com veiem, el que s'ha fet és utilitzar un constructor per a
passar-li com a paràmetre el servei (objecte de tipus *DBTest* ), i
guardar les dades en un atribut intern de la classe, per a poder-lo usar
tant en el mètode *show* com en el mètode *filter*. Recorda afegir
també la corresponent línia *use* per a utilitzar la classe *DBTest*.

En aquest punt, pots realitzar l'exercici 1 dels proposats al final de
la sessió.

## Altres opcions

Ara que ja sabem com crear serveis propis i utilitzar-los, o bé
utilitzar serveis de Symfony o de *bundles* de tercers, anem a veure
algunes opcions una mica més avançades que afecten al contenidor de
serveis i als serveis que utilitzem i desenvolupem.

### Combinar serveis

Què ocorreria si en una classe o mètode determinat necessitem emprar més
d'un servei? Tenim dues alternatives:

1.  Passar tants paràmetres com a serveis es requerisquen, normalment al
    constructor de la classe. Per exemple, si necessitem un objecte de
    tipus *DBTest* i un altre de tipus *LoggerInterface* en una
    mateixa classe, podem fer açò: 
    ```php
    class MyClass
    {
        private array $movies;
        private LoggerInterface $logger;
        public function __construct(DBTest $data, LoggerInterface $logger)
        {
            $this->movies = $data->get();
            $this->logger = $logger;
        }
    }
    ```

2.  Com a segona alternativa, també es pot crear una classe que
    encapsule els objectes necessaris (para no passar-los per separat
    com a paràmetre), i després utilitzar un objecte d'aqueixa classe
    en el constructor. Així, per al mateix exemple anterior primer
    crearíem una classe que encapsulara un objecte `DBTest` i un altre
    `LoggerInterface`... 

    ```php
    class CombinedService
    {
        private array $movies;
        private LoggerInterface $logger;
        public function __construct(DBTest $datas, LoggerInterface $logger)
        {
            $this->movies = $data->get();
            $this->logger = $logger;
        }
        // create getters or methods to acces 
    ```
... i després l'utilitzariem en la classe:

    ```php
    class MyClass
    {
        private $service;
        public function __construct(CombinedService $service)
        {
            $this->$service = $service;
        }
        ...
    ```

### Arguments sense *"autowiring"* 

Hem vist que l'opció de configuració *autowiring* existent en l'arxiu
*config/services.yaml* fa referència al fet que quan passem un objecte
d'un servei determinat a un mètode (indicant el tipus d'objecte)
Symfony automàticament crea l'objecte per nosaltres i li'l passa al
mètode.

No obstant açò, existeixen alguns arguments per als quals Symfony no pot
aplicar aquest mecanisme. Per exemple, suposem que en la nostra classe
*HomeController* volem que el format de data per al missatge de registre (_log_)
siga personalizable, i per tant, es puga passar com a argument. La
classe quedaria així:

```php
...

class HomeController extends AbstractController
{

    private LoggerInterface $logger;
    private string $dateFormat;

    public function __construct(LoggerInterface $logger, string $dateFormat)
    {
        $this->logger = $logger;
        $this->dateFormat = $dateFormat;
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $now = new \DateTime();

        $this->logger->info("Access on {$now->format($this->dateFormat)}");
        return $this->render("home.html.twig");
    }
}

```

Però si intentem utilitzar-lo (accedint a *movies-symfony* ),
obtindrem el següent missatge d'error:

> Cannot autowire service "App\Controller\HomeController": argument "$dateFormat" 
> of method "__construct()" is type-hinted "string", you should 
> configure its value explicitly.

En realitat, el que ha ocorregut és bastant simple: per al primer
argument ( *LoggerInterface* ), Symfony sap on obtenir-lo i com
construir-lo, però per al segon, al no estar tipat (no s'ha indicat el tipus) 
ni tenir un valor per defecte, Symfony no sap què fer amb ell. Per a solucionar el
problema, podem definir arguments propis d'un servei, és a dir,
arguments que va a utilitzar un servei determinat i que no són auto-
injectables. Per a açò, editem l'arxiu *config/services.yaml* indicant
el nom de la classe afectada, i els paràmetres no auto-injectables que pot rebre el
constructor. En el nostre cas, afegim aquestes línies al final de
l'arxiu:

```yaml
    App\Controller\HomeController:
        arguments:
            $dateFormat: 'Y/m/d H:i:s'
```

Recorda no usar el tabulador per a indentar les propietats (si no quatre
espais). El que hem fet ha sigut indicar que la classe
*HomeController* tindrà un argument anomenat *formatoFecha* en el seu
constructor, el valor del qual per defecte és l'indicat.

### Paràmetres globals

És possible també definir paràmetres de configuració globals a tots els
serveis, en la secció *parameters* de l'arxiu *config/services.yaml*.
De fet, ja tenim un paràmetre global definit que indica la localització
o idioma general de la pàgina:

```yaml
parameters:
    locale: 'en'
```

Tornant a l'exemple anterior, podríem afegir un nou paràmetre que
indique que el format de data per defecte per a qualsevol servei que ho
requerisca serà el vist abans:

```yaml
parameters: 
    locale: 'en' 
    default_date_format: 'Y/m/d H:i:s'
```
I podríem utilitzar aquest paràmetre en *qualsevol* arxiu de
configuració YAML. Per a començar, ho podem utilitzar més a baix, quan
especifiquem l'argument *formatoFecha* per a la classe
*HomeController*. Ara farem que prenga el seu valor del paràmetre
global, en lloc de posar-li-ho a mà:

```yaml
App\Controller\HomeController:
    arguments: 
        $dateFormat: '%default_date_format%'
```

Podem accedir als paràmetres globals des de qualsevol controlador,
sempre que herete de la classe *Controller* (i no *AbstractController* )
accedint a la seua propietat *container* :

```php
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class MiController extends Controller
{
...
public function myFunction()
{
    $format = $this->container->getParameter('default_date_format');
```

### Associar arguments per nom o per tipus

Per a finalitzar amb aquest apartat, veurem que es poden associar o
establir els arguments dels serveis tant pel tipus d'argument (en el
cas que s'especifique tipus) com pel nom del mateix. Per a açò, dins de
l'arxiu `config/services.yaml` , i en concret dins de l'apartat
`services > _defaults` , afegim una propietat `bind` , i en ella
indiquem tantes associacions com vulguem.

Vegem un exemple amb l'objecte `logger` que hem usat per a mostrar
missatges de log. Aquest objecte és de tipus `LoggerInterface` , que en
realitat és una interfície, i qualsevol llibreria que la implemente pot
servir com a font per a generar aqueixos arxius de _log_. Una d'elles és
`Monolog`, i és la que s'utilitza per defecte, però podrien ser unes
altres.

#### Associació per nom

Per exemple, podem fer que, sempre que un servei tinga un argument
anomenat *logger*, s'utilitze la llibreria *Monolog* :

```yaml
services: 
    _defaults: 
    ... 
    bind: 
        $logger: '@monolog.logger.request'
```
Si definim un servei amb un constructor així:

```php
private $logger;

public function __construct($logger) 
{ 
    $this->logger = $logger; 
}
```

automàticament a l'argument *logger* se li assignarà un objecte del
tipus *LoggerInterface* de *Monolog* , encara que no especifiquem el
tipus.

#### Associació per tipus

Alternativament, en lloc d'usar el nom de l'argument, podem fer que,
sempre que un servei intente utilitzar la interfície *LoggerInterface*
(la que hem utilitzat en els exemples d'aquesta sessió), s'empre
per defecte la implementació que fa d'ella la llibreria *Monolog* :

```yaml 
services:
    _defaults:
    ...
    bind:
        Psr\Log\LoggerInterface: '@monolog.logger.request'
```

D'aquesta manera, sempre que utilitzem un objecte de tipus
*LoggerInterface*, se li associarà una instància del logger de
*Monolog* també.

## Exercicis

<div markdown="1" class="alert-activity alert">
### Exercici 4.1
Crea el servei `DBTest` vist en la sessió i fes-ne ús en `MovieController`. D'aquesta
forma l'array de pel·lícules l'obtindrem des del servei.

A més, modifica `HomeController` perquè en faça ús.

Finalment, modifica `home.html.twig`  perquè s'assemble a les de projecte `Movies`.
</div>

<div markdown="1" class="alert-activity alert">
### Exercici 4.2
Implementa el sistema de registre en `HomeController`, de forma que cada vegada
que s'accedisca a la pàgina principal quede registrada la data i l'hora d'accés.

El format de la data es podrà configurar globalment.
</div>
