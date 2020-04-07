---
layout: default
title: 3. Herència
parent: 4. Classes i objectes
nav_order: 3
has_children: false
---

# Herència
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}
## Conceptes bàsics

L'herència és un mecanisme de la POO que ens permet definir noves
classes sobre la base d'una altra ja existent. Les noves classes que
hereten també es coneixen amb el nom de subclasses. La classe de la qual
hereten es diu classe base o superclasse.

Per exemple, en la nostra tenda web anem a tenir productes de diferents
tipus. En principi hem creat per a manejar-los una classe anomenada
Producte, amb alguns atributs i un mètode que genera una eixida
personalitzada en format HTML del codi.

```php
    class Producte {
        public $codi;
        public $nom;
        public $nomCurt;
        public $php;
        
        public function mostra() {
            print "<p>" . $this->codi . "</p>";
        }
    }
```

Aquesta classe és molt útil si l'única informació que tenim dels
diferents productes és la que es mostra a dalt. Però, si vols
personalitzar la informació que vas a tractar de cada tipus de producte
(i emmagatzemar, per exemple per als televisors, les polzades que tenen
o la seua tecnologia de fabricació), pots crear noves classes que
hereten de `Producte`. Per exemple, `TV`, `Ordinador`, `Mòbil`.

```php
    class TV extends Producte {
        public $polzades;
        public $tecnologia;
    }
```

## La paraula reservada `extends`

Com pots veure, per a definir una classe que herete d'una altra,
simplement has de utilitzar la paraula `extends` indicant la superclasse. 
Els nous objectes que s'instancien a partir de la subclasse 
són també objectes de la classe base; es pot comprovar utilitzant l'operador
`instanceof`.

    ```php
    $tv = new TV();
    if ($tv instanceof Producte) {
        // Aquest codi s'executa doncs la condició és certa
        …
    }
    ```

La nova classe hereta tots els atributs i mètodes públics de la classe
base, però no els privats. Si vols crear en la classe base un mètode no
visible a l'exterior (com els privats) que s'herete a les subclasses,
has d'utilitzar la paraula protected en lloc de private. A més, pots
redefinir el comportament dels mètodes existents en la classe base,
simplement creant en la subclasse un nou mètode amb el mateix nom.

```php
    class TV extends Producte { 
        public $polzades;
        public $tecnologia;
        
        public function mostra() {
            print "<p>" . $this->polzades . " polzades</p>";
        }
    }
```

## La paraula reservada `final`

Existeix una forma d'evitar que les classes heretades puguen redefinir
el comportament dels mètodes existents en la superclasse: utilitzar la
paraula `final`.
Si en el nostre exemple haguérem fet:

```php
    class Producte { 
        public $codi; 
        public $nom;
        public $nom_curt; 
        public $PVP;
    
        public final function mostra() {
            print "<p>" . $this->codi . "</p>";
        }
    }
```

En aquest cas el mètode `mostra`no
podria redefinir-se en la classe TV.

Inclòs és pot declarar una classe utilitzant final. En aquest cas no
podran crear-se classes heretades utilitzant-les com a base.

```php
    final class Producte {
    ...
    }
```

## La paraula reservada `abstract`

Oposadament al modificador `final`, existeix també `abstract`.
S'utilitza de la mateixa forma, tant amb mètodes com amb classes
completes, però en lloc de prohibir l'herència, obliga a que s'herete.
És a dir,   una classe amb el modificador abstract  no pot tenir objectes que la
instancien, però sí podrà utilitzar-se de classe base i les seues
subclasses sí podran utilitzar-se per a instanciar objectes.

```php
    abstract class Producte {
    ...
    }
```

Un mètode en el qual s'indique abstract, ha de ser redefinit
obligatòriament per les subclasses, i no podrà contenir codi.

```php
class Producte {
        …
        abstract public function mostra();
    }
```

Anem a fer una petita modificació en la nostra classe Producte. Per a
facilitar la creació de nous objectes, crearem un constructor al que se
li passarà un array amb els valors dels atributs del nou producte.

```php
    class Producte {
        public $codige;
        public $nom;
        public $nom_curt;
        public $PVP;
    
        public function mostra() {
            print "<p>" . $this->codi . "</p>";
        }
        public function __construct($row) {
            $this->codi = $row['codi'];
            $this->nom = $row['nom'];
            $this->nom_curt = $row['nom_curt'];
            $this->PVP = $row['PVP'];
        }
    }
```

**Què passa ara amb la classe TV, què hereta de Producte? Quan crees un
nou objecte d'aqueixa classe, es cridarà al constructor de Producte?
Pots crear un nou constructor específic perquè redefinisca el
comportament de la classe base?**

Començant per aquesta última pregunta, òbviament pots definir un nou
constructor per a les classes heretades que redefinisquen el
comportament del que existeix en la classe base, tal com faries amb
qualsevol altre mètode. I depenent de si programes o no el constructor
en la classe heretada, es cridarà  o no automàticament al constructor de
la classe base.

En PHP5+, si la classe heretada no té constructor propi, es cridarà
automàticament al constructor de la classe base (si existeix). No
obstant açò, si la classe heretada defineix el seu propi constructor,
hauràs de ser tu el que realitze la crida al constructor de la classe
base si ho consideres necessari, utilitzant per a açò la paraula
`parent` i l'operador de resolució d'àmbit.

```php

    class TV extends Producte {
        public $polzades;
        public $tecnologia;
    
        public function mostra() {
            print "<p>" . $this->polzades . " polzades</p>";
        }
    
        public function __construct($row) {
            parent::__construct($row);
            $this->polzades = $row['polzades'];
            $this->tecnologia = $row['tecnologia'];
        }
    }
```

Ja has vist amb amb anterioritat com s'utilitzava la paraula clau `self`
per a tenir accés a la classe actual. La paraula parent és semblant. En
utilitzar parent fas referència a la classe base de la actual, ta com
apareix rere `extends`.