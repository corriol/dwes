---
layout: default
title: 4. Interfícies
parent: 4. Classes i objectes
nav_order: 4
has_children: false
---

# Interfícies
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

Un interfície (*interface*) és com una classe buida que solament conté
declaracions de mètodes. Es defineixen utilitzant la paraula interface. 
Per exemple, abans vas veure que podies crear noves classes heretades de
Producte, com TV o Ordinador. També vas veure que en les subclasses
podies redefinir el comportament del mètode perquè generara una eixida
en HTML diferent per a cada tipus de producte.

Si vols assegurar-te que tots els tipus de productes tinguen un mètode
`mostra`, pots crear un interface com el següent.

```php

    interface IMostra {
        public function mostra();
    }
```

I quan crees les subclasses hauràs d'indicar amb la paraula `implements`
que han de implementar els mètodes declarats en aquest interface. 

```php
    class TV extends Producte implements IMostra {
            …
            public function mostra() {
                print "<p>" . $this->polzades . " polzades</p>";
            }
            …
    }
```

Tots els mètodes que es declaren en un `interface` han de ser públics. A
més de mètodes, els interfícies podran contenir constants però no
atributs.

Un interface és com un contracte que la classe ha de complir. En
implementar tots els mètodes declarats en la interfície s'assegura la
interoperabilitat entre classes. Si saps que una classe implementa un
interfície determinada saps quins noms tenen els seus mètodes, quins
paràmetres els has de passar i, probablement, podràs esbrinar fàcilment
amb quins objectiu han sigut escrits.

Per exemple, en la llibreria de PHP està definit la interfície
`countable`.

```php
    Countable {
        abstract public int count ( void )
    }
```

Si crees una classe per a la cistella de la compra en la tenda web,
podries implementar aquesta interfície per a contar els productes que
figuren en la mateixa.

Abans vas aprendre que en PHP5 una classe només pot heretar d'una altra.
En PHP5 no existeix l'herència múltiple. No obstant açò, sí és possible
crear classes que implementen diverses interfícies, simplement separant
la llista d'interfícies per comes després de la paraula `implements`.

```php

    class TV extends Producte implements IMostra, Countable {
         …
    }
```

L'única restricció és que els noms dels mètodes que s'hagen
d'implementar en els diferents interfícies no coincidisquen. És a dir,
en el nostre exemple, la interfície `IMostra` no podria contenir el
mètode `count`, doncs aquest ja està declarat en `Countable`.

En PHP5 també es poden crear nous interfícies heretant d'uns altres ja
existents. Es fa de la mateixa forma que amb les classes, utilitzant la
paraula `extends`.

Una dels dubtes més comuns en POO, és quina solució adoptar en algunes
situacions: interfícies o classes abstractes. Ambdues permeten definir
regles per a les classes que els implementen o hereten respectivament. I
cap permet instanciar objectes. Les diferències principals entre ambdues
opcions són:

  - En les classes abstractes, els mètodes poden contenir codi. Si van a
    existir diverses subclasses amb un comportament comú, es podria
    programar en els mètodes de la classe abstracta. Si s'opta per un
    interface, caldria repetir el codi en totes les classes que ho
    implemente.
  - Les classes abstractes poden contenir atributs, i els interfícies
    no.
  - No es pot crear una classe que herete de dues classes abstractes,
    però sí es pot crear una classe que implemente diverses interfícies.

Per exemple, en la botiga *online* va a haver-hi dos tipus d'usuaris:
clients i empleats. Si necessites crear en la teua aplicació objectes de
tipus Usuari  (per exemple, per a manejar de forma conjunta a els
clients i a els empleats), hauries de crear una classe no abstracta amb
aqueix nom, de la qual heretarien Client i Empleat.

```php
    class Usuari {
        ...
    }
    class Client extends Usuari {
        ...
    }
    class Empleat extends Usuari {
        ...
    }

```

Però si no fóra així, hauries de decidir si crees o no Usuari, i si ho
faries com una classe abstracta o com una interfície.

Si per exemple, volgueres definir en un únic lloc els atributs comuns a
Client i a Empleat, hauries de crear una classe abstracta Usuari de la
qual hereten.

```php

    abstract class Usuari {
        public $dni;
    
        protected $nom;
        ...
    }
```


Però açò no podries fer-ho si ja tens planificada alguna relació
d'herència per a una d'aquestes dues classes.

Per a finalitzar amb els interfícies, a la llista de funcions de PHP
relacionades amb la POO pots afegir les
següents.

| Funció                     | Exemple                                  | Significat                                                                  |
| -------------------------- | ---------------------------------------- | --------------------------------------------------------------------------- |
| get\_declared\_interfícies | print\_r (get\_declared\_interfícies()); | Retorna un array amb els noms dels interfícies declarats.                   |
| interface\_exists          | if (interface\_exists('iMuestra') { … }  | Retorna true si existeix l'interface que s'indica, o false en cas contrari. |


# Exercicis pràctics

<div class="iDevice emphasis1">

# Activitat: Interfície IControl

<div class="iDevice_inner">

<div class="iDevice_content_wrapper">

<div id="ta20_50_2" class="block iDevice_content">

Adapta l'aplicació de la classe Formulari perquè  els controls
implementen una interfície anomenada IControl que serà:

<div class="highlighted-code language-php">

<div>

    interface IControl {
        public render(): string;
    }


