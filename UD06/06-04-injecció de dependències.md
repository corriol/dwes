---
parent: 6. Cap a un framework MVC
nav_order: 4
---

# Què necessites? La injecció de dependències

La injecció de dependències (_dependency injection_) és un patró de disseny orientat a objectes en
 què es subministren els objectes a una classe en lloc de ser la pròpia classe la que creu aquests objectes. 

Aquests objectes compleixen contractes (interfícies, classes abstractes) que necessiten les nostres
 classes per poder funcionar (d'aquí el concepte de dependència). 

Les nostres classes no creen els objectes que necessiten, sinó que se'ls subministra una altra 
classe 'contenidora' que injectarà la implementació desitjada al nostre contracte.

Simplificant:

> La injecció de dependències proporciona a una classe les seves dependències ja siga mitjançant la injecció del constructor, crides a mètodes o l’establiment de propietats.

Exemple:
https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md

## Contenidor de serveis

El contenidor de serveis és una utilitat que ajuda a la implementació de la injecció de dependències. 
S'encarrega de registrar totes les depedències (objectes) de l'aplicació web en un array. L'aplicació web les extraurà i 
injectarà  quan les necessite.

En el nostre cas el contenidor de serveis serà la classe `App`. 

``` php

# src/Core/App.php

class App
{
    private static $container = array();
    public static function bind($key, $value) {
        static:: $container [$key] = $value;
    }

    public static function get($key)
    {
        if(! array_key_exists ($key, static:: $container )) {
            throw new Exception("The $key doesn't in the container");
        }
        return static:: $container [$key];
    }
}
```

## Autowiring

_Autowiring_ és una palabra exótica que representa una cosa molt senzilla: la capacitat del contenidor per a crear i 
injectar dependències automàticament.

La idea bàsica és que quan es cride un mètode d'un classe, si s'ha indicat el tipus de dada de l'argument en la 
declaració del mètode, el contenidor busque una instància d'eixa classe i 
la injecte automàticament. 

Per exemple:

```php
class DatabaseConnection
{
    // ...
}

class MovieModel
{
    public function __construct(DatabaseConnection $DBconnection)
    {
        // ...
    }
}
```

Quan el contenidor necessite crear un objecte MovieModel, detectarà que aquesta classe necessita un objecte 
DatabaseConnection i l'injectarà automàticament.

Aquesta és una funcionalitat molt important en els frameworks PHP moderns, la posarem en pràctica quan treballem en un d'ells. 
En el nostre projecte accedirem a les instàncies mitjançant el mètode `App::get()`; 

Més informació:
* [https://php-di.org/doc/autowiring.html](https://php-di.org/doc/autowiring.html)