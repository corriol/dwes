---
layout: default
title: 6. La classe Request. Filtratge
nav_order: 6
parent: 12. Tutorial de Symfony
has_children: false 
nav_exclude: true
---

# La classe Request. Filtratge
{: .no_toc }


## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

La majoria de _frameworks_ MVC disposen d'una classe Request per a gestionar les dades de les sol·licituds HTTP. Symfony no és l'excepció i inclou tot un seguit de classes per a gestionar-les.


Les trobarem en el component [HttpFoundation](The HttpFoundation Component).

L'object `Request` manté la informació de la sol·licitud del client. Aquest informació és accessible mitjançant diverses propietats públiques:

 * `request`: equivalent of $_POST;
 * `query`: equivalent of $_GET ($request->query->get('name'));
 * `cookies`: equivalent of $_COOKIE;
 * `attributes`: no equivalent - used by your app to store other data (see below);
 * `files`: equivalent of $_FILES;
 * `server`: equivalent of $_SERVER;
 * `headers`: mostly equivalent to a subset of $_SERVER ($request->headers->get('User-Agent')).

Cada propietat és una instància de ParameterBag (o una subclasse), el que és una classe mantenidora de dades:

 * `request`: ParameterBag;
 * `query`: ParameterBag;
 * `cookies`: ParameterBag;
 * `attributes`: ParameterBag;
 * `files`: FileBag;
 * `server`: ServerBag;
 * `headers`: HeaderBag.

Totes les instàncies de ParameterBag tenen mètodes per a recuperar i actualitzar les seues dades:

 * `all()`
   Returns the parameters.
 * `keys()`
   Returns the parameter keys.
 * `replace()`
   Replaces the current parameters by a new set.
 * `add()`
   Adds parameters.
 * `get()`
   Returns a parameter by name.
 * `set()`
   Sets a parameter by name.
 * `has()`
   Returns true if the parameter is defined.
 * `remove()`
   Removes a parameter.

La instància de `ParameterBag` també conté alguns mètodes per a filtrar els valors d'entrada:

 * `getAlpha()`
    Returns the alphabetic characters of the parameter value;
 * `getAlnum()`
    Returns the alphabetic characters and digits of the parameter value;
 * `getBoolean()`
    Returns the parameter value converted to boolean;
 * `getDigits()`
    Returns the digits of the parameter value;
 * `getInt()`
    Returns the parameter value converted to integer;
 * `filter()`
    Filters the parameter by using the PHP filter_var function.

{:.alert .alert-activity}
<div markdown="1">
### 1. Preparant el filtre

La aplicació diposarà d'un filtre que permetrà el filtrage dels enllaços per un text de búsqueda (`text`), que s'hagen actualitzat entre dues dates (`start-date` i `end-date`) i per una de les etiquetes existents (`tag`).

Emprant la classe `Request` implementa en la ruta `homepage` el codi necessari per a comprovar si s'han enviat les dades del formulari de filtratge mitjançant el querystring. Si no s'han enviat, estan buits o tenen un valor incorrecte la variable prendrà el valor `null`.

Les variables seran `$text`, `$startDate`, `$endDate` i `$tag`.
</div>

El seguent pas serà implementar el fitre en la funció `findLatest` al repositori.

{:.alert .alert-activity}
<div markdown="1">
### 2. Implementant el filtre

Per poder extraure les dades filtrades de la base de de dades implementarem el mètode `filter` en el repositori. Tindrà la seguent declaració:
```php
   public function findLatest(int $page = 1, string $text = null, DateTime $startDate = null, 
                        DateTime $endDate = null): Paginator
```
Hauràs d'implementar el codi necessari perquè segons els paràmetres de filtrat es genere una consulta SQL o un altra.
</div>

Ara provarem la nova funcionalitat.

{:.alert .alert-activity}
<div markdown="1">
### 3. Provant el filtre

Encara no tenim el formulari de filtratge però podem provar el funcionament assignant valors vàlids directament a les variables o generar solicituds mitjançant el _querystring_.
</div>

I per a acabar implementarem el formulari.

{:.alert .alert-activity}
<div markdown="1">
### 4. Implementant el formulari

Tenim dues formes d'implementar-lo. La primera és crear el formulari amb html. La segona, i mes interessant, usant el [FormBuilder de Symfony](https://symfony.com/doc/current/form/without_class.html). Tu tries.
</div>
