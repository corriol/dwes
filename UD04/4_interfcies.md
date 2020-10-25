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

## Introducció

Un interfície (*interface*) és com una classe buida que solament conté declaracions de mètodes. 
Es defineixen utilitzant la paraula `interface`. Per exemple, abans vam veure que podíem crear noves classes
heretades de `Product`, com `TV` o `Ordinador`. També vam veure que en les subclasses
podies redefinir el comportament del mètode perquè generara una eixida
en HTML diferent per a cada tipus de producte.

Si vols assegurar-te que tots els tipus de productes tinguen un mètode
`show`, pots crear un interface com el següent.

```php
interface IShow {
    public function show();
}
```

I quan crees les subclasses hauràs d'indicar amb la paraula `implements`
que han de implementar els mètodes declarats en aquest interface.

```php
class TV extends Product implements IShow {
            …
    public function show() {
        echo "<p>" . $this->size . " polzades</p>";
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

class TV extends Product implements IShow, Countable {
         …
}
```

L'única restricció és que els noms dels mètodes que s'hagen
d'implementar en els diferents interfícies no coincidisquen. És a dir,
en el nostre exemple, la interfície `IShow` no podria contenir el
mètode `count`, doncs aquest ja està declarat en `Countable`.

En PHP7 també es poden crear nous interfícies heretant d'uns altres ja
existents. Es fa de la mateixa forma que amb les classes, utilitzant la
paraula `extends`.

## Classes abstractes vs interfícies

Un dels dubtes més comuns en POO, és quina solució adoptar en algunes
situacions: interfícies o classes abstractes. Ambdues permeten definir
regles per a les classes que els implementen o hereten respectivament. I
cap permet instanciar objectes. Les diferències principals entre ambdues
opcions són:

  - En les classes abstractes, els mètodes poden contenir codi. Si van a
    existir diverses subclasses amb un comportament comú, es podria
    programar en els mètodes de la classe abstracta. Si s'opta per un
    interface, caldria repetir el codi en totes les classes que ho
    implemente.
  - Les classes abstractes poden contenir atributs, i les interfícies
    no.
  - No es pot crear una classe que herete de dues classes abstractes,
    però sí es pot crear una classe que implemente diverses interfícies.

Per exemple, en la botiga *online* va a haver-hi dos tipus d'usuaris:
clients i empleats. Si necessites crear en la teua aplicació objectes de
tipus Usuari (per exemple, per a manejar de forma conjunta als
clients i als empleats), hauries de crear una classe no abstracta amb
aqueix nom, de la qual heretarien Client i Empleat.

Podeu llegir també l'article
 [Clases abastractas e interfaces en PHP ](https://poesiabinaria.net/2010/06/clases-abstractas-e-interfaces-en-php/)


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

Però si no fóra així, hauries de decidir si crees o no `Usuari`, i si ho
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

Per a finalitzar amb les interfícies, a la llista de funcions de PHP
relacionades amb la POO pots afegir les següents.

| Funció                     | Exemple                                  | Significat                                                                  |
| -------------------------- | ---------------------------------------- | --------------------------------------------------------------------------- |
| get\_declared\_interfícies | print\_r (get\_declared\_interfícies()); | Retorna un array amb els noms dels interfícies declarats.                   |
| interface\_exists          | if (interface\_exists('iShow') { … }  | Retorna true si existeix l'interface que s'indica, o false en cas contrari. |


{:.alert .alert-activity}
<div markdown="1">

### Activitat 4.4: Interfície IControl
{:.no_toc .nocount}

#### Objectius
{:.no_toc .nocount}
Els objectius de l'activitat són els següents:
* Crear interfícies.
* Treballar amb classes abstractes
* Treballar el polimorfisme

#### Enunciat
{:.no_toc .nocount}

Crea una nova classe `Textarea` semblant a la classe `Input` que generarà 
un camp textarea de formulari. 

Podrem afegir objectes `Textarea` mitjançant el mètode `Form::add()`?;

Crea la interfície `IControl`:

```php
interface IControl {
    public render(): string;
}
```
Totes les classes creades implementaran eixa interfície.

Modifica `Form::add()` perquè admeta paràmetres de tipus `IControl`.

</div>