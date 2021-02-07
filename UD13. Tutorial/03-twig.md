---
layout: default
parent: 11. Introducció a Symfony 
nav_order: 3
has_children: false 
---

# Gestió de plantilles amb Twig
{:.no_toc }

1. Taula de continguts
{:toc}


## Definint plantilles de vistes amb Twig

Els exemples de controladors vistos fins ara queden un poc
limitats, perquè el disseny brilla per la seua absència. Ens hem limitat
a mostrar un text pla amb les dades per a comprovar que el controlador
funciona, o en tot cas, a generar un HTML rudimentari en l'objecte
Response per a mostrar una llista. Però si volem generar una vista més
complicada, no és bona idea fer-ho afegint els elements en la cadena de
text per a la resposta. Ara veurem com podem generar vistes amb una mica
de disseny, gràcies al motor de plantilles Twig.

La filosofia d'utilitzar motors de plantilles com Twig és separar tot
el possible el codi PHP de l'estructura HTML de la pàgina, de manera
que tota la lògica de negoci queda fora de la vista (en el controlador,
normalment), i en aquesta deixem el necessari per a mostrar el contingut
al client.

Per a açò, el que sol fer és, des del controlador, accedir al
model per a obtenir o modificar les dades necessàries, emmagatzemar-los
en variables i passar-li aquestes variables a les vistes o plantilles,
de manera que aquestes només hagen d'encarregar-se de mostrar aqueixa
informació amb l'estructura i disseny adequats. Aïllem, per tant, el
treball del programador d'una banda (controlador i model), i el del
dissenyador per un altre (vistes)

### La nostra primera plantilla

Anem a veure com renderizar una plantilla amb Twig. En primer lloc, hem
de fer alguns petits canvis en el controlador que vaja a usar Twig:
farem que la classe del controlador herete d'AbstractController
(incorporant aquesta classe del seu corresponent espai de noms):

```php
namespace App;

... 
use Symfony;

class NameController extends AbstractController {
```

Açò ho farem per a poder utilitzar algunes de les facilitats que ens
dona `AbstractController`, com per exemple el mètode `render` per a
renderizar vistes. Com hem comentat en sessions anteriors, les vistes
s'emmagatzemen en la carpeta `templates` del nostre projecte Symfony, i
si emprem Twig com a motor de plantilles, tenen l'extensió `.html.twig.`
Per exemple, anem a crear una plantilla anomenada `home.html.twig` en la
nostra aplicació de movies, amb aquest codi:

```html
<html>
    <body>
        <h1>Movies</h1>
        <h2>Welcome to Movie FX</h2>
        <p>Home</p>
    </body>
</html>
```

Ara anem a modificar la classe `src/Controller/HomeController` perquè
herete d'`AbstractController`.

```php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class HomeController extends AbstractController
{
```
... 
i modifiquem també el mètode home perquè, en lloc de mostrar una
resposta de text pla, renderitze la vista `home.html.twig` que acabem de
fer. Per a açò, el codi serà el següent:

```php
/**
* @Route("/", name="home")
*/
public function home()
{
    return $this->render('home.html.twig');
}
```

Observa com emprem l'objecte `$this` (recorda, ara la nostra classe és
un subtipus d'`AbstractController`) per a accedir al mètode `render` i
renderizar la vista que li indiquem, que automàticament se cercarà des
de la carpeta `templates`.

### Plantilles amb parts variables

La plantilla anterior no és alguna cosa massa habitual, ja que únicament
conté text estàtic. El normal és que hi haja alguna part que varie, i
que li siga proporcionada des del controlador. Anem a veure un altre
exemple amb la fitxa de la pel·lícula: anem a la nostra classe
`src/Controller/MovieController` i fem que també herete
d'`AbstractController:`

```php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
```
Anem a generar una plantilla anomenada `movies_show.html.twig` en la
nostra carpeta `templates`. Rebrà com a paràmetre del controlador la 
pel·lícula amb el codi indicat (després veurem com), i mostrarà en la
plantilla les seues dades. El codi de la plantilla pot quedar així:

{% raw %}
```ruby
<h1>Movie data</h1>

<h2>{{ movie.title }}</h2>

<p>Tagline: {{ movie.tagline }}</p>

<p>Release date: {{ movie.release_date }}</p>
```
{% endraw %}

Emprem la notació de la doble clau {%raw%}`{{ ... }}`{%endraw%} per a situar variables, que normalment són dades que esperem rebre de fora (del controlador, en aquest cas). Ens faltaria, en el mètode `show` de MovieController, obtenir la pel·lícula desitjada (açò ja ho tenim fet) i passar-li-la a la vista, d'aquesta manera:
```php
/**
  * @Route("/movies/{id}", name="movies_show", requirements={"id"="\d+"})
  */

    public function show($id = 2)
    {
        $result = array_filter($this->movies,
            function($movie) use ($id)
            {
                return $movie["id"] == $id;
            });
        if (count($result) > 0)
        {
            return $this->render('movies_show.html.twig', array(
                'movie' => array_shift($result)
            ));
        }
        else
            return new Response("Movie not found");
    }
```
Com veus, n'hi ha prou amb utilitzar un array de paràmetres en el
mètode `render` per a passar-li a la vista tot el que necessite,
identificant cada cosa amb el nom que vulguem, i que coincidirà amb el
qual s'utilitzarà en el codi de la vista (el paràmetre `movie`, en
aquest cas).

### Estructures de control en plantilles

La plantilla anterior és un exemple per a afegir parts dinàmiques en el
contingut de la mateixa, però està alguna cosa "coixa": què passa si no
trobem la pel·lícula? En aquest cas, el controlador es limita
a retornar una resposta de text pla que diu "Movie not found", però
podríem emprar la mateixa vista (o una altra) per a mostrar aquesta
informació més elaborada. Així, el controlador renderizará la mateixa
vista, passant-li una pel·lícula vàlida o `null`, segons el cas:

```php
    public function show($id = 2)
    {
        $result = array_filter($this->movies,
            function($movie) use ($id)
            {
                return $movie["id"] == $id;
            });
        if (count($result) > 0)
        {
            return $this->render('movies_show.html.twig', array(
                'movie' => array_shift($result)
            ));
        }
        else
            return $this->render('movies_show.html.twig', array(
                'movie' => null
        ));
    }
```
i la vista distingirà si hi ha o no pel·lícula, per a mostrar una o una
altra informació:

{% raw %}
```ruby
<html>
<body>
<h1>Movie data</h1>
    {% if movie %}
    <ul>
        <li><strong>{{ movie.title }}</strong></li>
        <li><strong>Tagline</strong>: {{ movie.tagline }}</li>
        <li><strong>Release date</strong>: {{ movie.release_date }}</li>
    </ul>
    {% else %}
        <p>Movie not found</p>
    {% endif %}
    </body>
</html>
```
{% endraw %}

Observa com hem inclòs un bloc {% raw %}`{% ... %}`{% endraw %}, que són 
blocs d'acció,  emprats per a definir certes sentències de control (condicions, bucles)
 i incloure dins el codi associat a aquesta sentència.

De la mateixa manera, per al controlador de filtratge de pel·lícules per títol,
podem crear una nova vista (per exemple, "movies_filter.html.twig"),
que mostre el llistat de pel·lícules que reba ja filtrat del controlador:


{% raw %}
```ruby
<h1>Movies</h1>
{% if movies %} 
    <ul>
    {% for movie in movies %}  
        <li><strong>{{ movie.title }}</strong></li>
        <li><strong>Tagline</strong>: {{ movie.tagline }}</li>
        <li><strong>Release date</strong>: {{ movie.release_date }}</li>
    </ul>
    {% endfor %} 
{% else %}
    <p>No movies found</p>
{% endif %}
```
{% endraw %}

Així, el codi del controlador es limitarà a filtrar les i
a passar-li'ls a la vista:

```php
   public function filter($text)
    {
        $result = array_filter($this->movies,
            function($movie) use ($text)
            {
                return strpos($movie["title"], $text) !== false;                
            });
        $response = "";
        if (count($result) > 0)
        {
            foreach ($result as $movie) {
                $response .= "<ul><li>" . $movie["title"] . "</li>" .
            "<li>" . $movie["tagline"] . "</li>" .
            "<li>" . $movie["release_date"] . "</li></ul>";
            }
            return $this->render('movies_filter.html.twig', array(
                'movies' => $result
                ));        
        }        
    }
```
Observa com podem emprar bucles en les plantilles per a recórrer
col·leccions de dades passades des del controlador.

### Herència de plantilles

L'herència de plantilles ens permet reaprofitar el codi d'unes en
unes altres. En realitat, açò és alguna cosa molt habitual en el disseny
web: que totes les pàgines (o diverses) d'una web compartisquen la
mateixa capçalera i peu, per exemple. Així, podem definir una estructura
o _layout_ base en una plantilla, i fer que altres hereten d'ella
per a emplenar certs buits. Vegem un exemple amb la nostra web de
pel·lícules.

En primer lloc, definirem la plantilla base. Teniu un exemple en què
basar-vos ja fet, en l'arxiu `templates/base.html.twig`, que proporciona
un esquelet que podríem aprofitar per a moltes aplicacions:

{% raw %}
```ruby
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{% block title %}Welcome!{% endblock %}</title>
        {% block stylesheets %}{% endblock %}

        {% block javascripts %}{% endblock %}
    </head>
    <body>
        {% block body %}{% endblock %}
    </body>
</html>
```
{% endraw %}

Com podem observar, la part "emplenable" de la plantilla es defineix
mitjançant blocs (_blocks_), de manera que en les diferents subplantilles
podem indicar quins blocs de la plantilla pare volem emplenar. Per
exemple, anem a definir una subplantilla per a la pàgina d'inici.
Retoquem la nostra plantilla `home.html.twig` i la deixem així:

{% raw %}
```ruby
{% extends 'base.html.twig' %}

{% block title %}Movies{% endblock %} {% block body %}

<h1>Movies</h1>

<p>Welcome to Movies FX Site!</p>

{% endblock %}
``` 
{% endraw %}

És important que, si una plantilla hereta d'una altra, el primer codi
que hi haja en aqueixa plantilla (sense explicar comentaris previs) siga
una instrucció {% raw %}`{% extends ... %}` {% endraw %} per a indicar que és una herència.
Després, n'hi ha prou amb emplenar els blocs que el seu contingut
vulguem modificar o establir: en aquest exemple, els blocs _title_ i _body_,
definits en la plantilla base, encara que el bloc _title_ es podria deixar
predefinit com a "Movies" en la plantilla base també.

De la mateixa manera, definiríem les plantilles `movies_show.html.twig`.

{% raw %}
```ruby
{% extends 'base.html.twig' %}
{% block title %}Movies{% endblock %}

{% block body %}   

<h1>Movie data</h1>
    {% if movie %}
    <ul>
        <li><strong>{{ movie.title }}</strong></li>
        <li><strong>Tagline</strong>: {{ movie.tagline }}</li>
        <li><strong>Release date</strong>: {{ movie.release_date }}</li>
    </ul>
    {% else %}
        <p>Movie not found</p>
    {% endif %}
{% endblock %}
```
{% endraw %}


... i `movies_filter.html.twig`:

{% raw %}
```ruby
{% extends 'base.html.twig' %}
{% block title %}Movies{% endblock %}

{% block body %}   
<h1>Movies</h1>
{% if movies %} 
    {% for movie in movies %}  
    <ul>
        <li><strong>{{ movie.title }}</strong></li>
        <li><strong>Tagline</strong>: {{ movie.tagline }}</li>
        <li><strong>Release date</strong>: {{ movie.release_date }}</li>
    </ul>
    {% endfor %} 
{% else %}
    <p>No movies found</p>
{% endif %}
{% endblock %}
```
{% endraw %}


#### Incloure plantilles dins d'altres

Altra opció interessant, a part de l'herència, és la de poder incloure
una plantilla com a part del contingut d'una altra. N'hi ha prou amb
utilitzar la instrucció `include`, seguida del nom de la plantilla i, si
els necessita, els seus paràmetres associats. Per exemple, podríem
traure la llista de pel·lícules d'una plantilla anomenada
`movie_data.html.twig`:

{% raw %}
```ruby
<ul>
    <li><strong>{{ movie.title }}</strong></li>
    <li><strong>Tagline</strong>: {{ movie.tagline }}</li>
    <li><strong>Release date</strong>: {{ movie.release_date }}</li>
</ul>
```ruby
{% endraw %}

I incloure-la tant en `movies_show`...

{% raw %}
```ruby
{% extends 'base.html.twig' %}
{% block title %}Movies{% endblock %}

{% block body %}   

<h1>Movie data</h1>
    {% if movie %}
        {{ include ('movie_data.html.twig', {'movie': movie }) }}
    {% else %}
        <p>Movie not found</p>
    {% endif %}
{% endblock %}
```
{% endraw %}

... com en `movies_filter`:

{% raw %}
```ruby
{% extends 'base.html.twig' %}
{% block title %}Movies{% endblock %}

{% block body %}   
<h1>Movies</h1>
{% if movies %} 
    {% for movie in movies %}  
        {{ include ('movie_data.html.twig', {'movie': movie }) }}
    {% endfor %} 
{% else %}
    <p>No movies found</p>
{% endif %}
{% endblock %}
```
{% endraw %}

### Enllaços a rutes i a elements estàtics

Per a finalitzar aquest apartat d'edició de plantilles, ens queden dos
aspectes importants a tractar:

-   Com incloure contingut estàtic (fulles d'estil, imatges... i tot el
    que, en general, estiga dins de la carpeta "public" del projecte)
-   Com afegir enllaços a altres rutes

#### Afegir contingut estàtic en plantilles

Per a il·lustrar com afegir contingut estàtic en plantilles, anem a
definir en la nostra carpeta `public` de la web de pel·lícules una
subcarpeta `css`, i dins un arxiu `styles.css` (que quedarà, per tant, en
`public/css/styles.css`. Definim dins un estil bàsic per a provar. Per
exemple:

```css
    body { background-color: #99ccff; } 
    h1 { border-bottom: 1px solid black; }
}
```

Ara, anem a afegir aquest estil a la nostra web. Com tenim un bloc
`stylesheets` en la nostra plantilla `base.html.twig`, podem aprofitar-ho i
incloure el CSS dins d'aquest bloc, perquè ho utilitzen totes les
subplantillas:

{% raw %}
```php
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{% block title %}Welcome!{% endblock %}</title>
        {% block stylesheets %}
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        {% endblock %}
        {% block javascripts %}{% endblock %}
    </head>
    <body>
        {% block body %}{% endblock %}
    </body>
</html>
```
{% endraw %}


De la mateixa manera, si tinguérem una imatge, per exemple,
`public/images/image.png`, podríem afegir-la en la plantilla  amb un cosa així:

{% raw %}
```ruby
<img src="{{ asset('images/image.png') }}" />
```
{% endraw %}

Podem també emprar rutes absolutes, emprant la instrucció `absolute_url`:

{% raw %}
```ruby
<img src="{{ absolute_url(asset('images/image.png')) }}" />
```
{% endraw %}

En el cas d'arxius javascript, s'afegirien en el bloc `javascripts` de la plantilla base (o
d'alguna subplantilla, si es vol). Per exemple, suposant que tenim un
arxiu `library.js` penjant de la subcarpeta `public/js`, faríem alguna cosa
així:

{% raw %}
```ruby
{% block javascripts %}
<script src="{{ asset('js/library.js') }}"></script>
{% endblock %}
```
{% endraw %}

#### Enllaçar a altres rutes de l'aplicació

Si el que volem és definir un enllaç a una altra ruta o pàgina de la
nostra aplicació, en aqueix cas utilitzem la funció `path` per a indicar
el nom (`name`) que hàgem assignat a la ruta a la qual volem anar. Per
exemple, si volem anar a la fitxa d'una pel·lícula el codi del qual està
emmagatzemat en la variable `id`, faríem alguna cosa així:

{% raw %}
```ruby
<a href="{{ path('movies_show', {'id': id}) }}">...</a>
```
{% endraw %}

### Altres característiques interessants de Twig

A més de tot l'exposat durant aquest apartat, existeixen altres
característiques interessants de Twig. Vegem algunes d'elles ràpidament
en aquesta subsección.

#### Ús de filtres

Quan volem mostrar informació en una plantilla amb la sintaxi de la doble
clau {% raw %}{{...}}{% endraw %}, podem emprar **filtres** per a processar la informació a
mostrar i donar-li cert format. Els filtres en Twig s'activen
mitjançant la barra vertical (`|`), seguida del filtre a aplicar. Per exemple,
si volem mostrar el nom del títol  en majúscules, faríem alguna cosa
així:
{% raw %}
```
{{ movie.title | upper }}
```
{% endraw %}

Existeixen altres filtres útils, com **lower** (per a mostrar la
informació en minúscules), o **date** , per a formatar dates amb el
format que es vulga:
{% raw %}
```ruby
{{ dada_de_tipus_data | date("d/m/Y")}}
```
{% endraw %}

#### Comentaris

És possible també afegir línies de comentaris en les plantilles Twig,
mitjançant la sintaxi `{# ... #}`:

```ruby
{# Açò és un comentari #}
```

#### Reutilitzar contingut de plantilles pare

Hem vist que podem sobreescriure el contingut d'un bloc (block) d'una
plantilla pare, simplement definint el mateix bloc en la subplantilla
filla. Però també és possible reutilitzar el contingut del pare i afegir
el propi de la filla, cridant al pare amb `parent`. Per exemple, imaginem
que, a més dels estils CSS que tinguem definits en `base.html.twig`, volem
afegir altres particulars per a una plantilla, sense perdre els de el
pare. Ho faríem així:


{% raw %}
```ruby
{% block stylesheets %}
    {{ parent() }}
    <link href="{{ asset('css/some_styles.css') }}" rel="stylesheet" />
{% endblock %}
{% endraw %}
```

#### Cicles

L'opció _cycle_ és molt útil quan volem alternar cíclicament certs valors
en un bucle. Per exemple, per a mostrar un llistat amb 10 files i estils
de fila alterns, podem fer alguna cosa així:

{% raw %}
```ruby
{% for i in 1..10 %}
    <div class="{{ cycle(['par', 'impar'], i) }}">
    ...
    </div>
{% endfor %}
```
{% endraw %}

#### Altres opcions

Existeixen altres opcions que no hem vist i podeu consultar
en la [web oficial de Twig]([https://twig.symfony.com/).

## 4. Exercicis

<div markdown="1" class="alert-activity alert">
### Exercici 2.1

Adapta la plantilla base basant-te en la del projecte `Movies`.

Afig a l'array de pel·lícules la clau `poster` amb el nom del fitxer 
i canvia el format de la data a `DateTime` o `timestamp`. 

Modifica les plantilles `home.html.twig` i  `movies_show.html.twig` 
    perque s'assemble a les de projecte `Movies`.
</div>


