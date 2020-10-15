---
layout: default
title: 1. Introducció
parent: 4. Classes i objectes
nav_order: 1
has_children: false
---

# Introducció
{: .no_toc }

La programació orientada a objectes (OOP, per les seues sigles en
anglès) és un paradigma de programació.

Paradigma:

> Teoria \[…\] el nucli central de la qual \[…\] suministra la base i el
> model per resoldre problemes i avançar en el coneixement.

Per la qual cosa podem definir la programació orientada a objecte com
un mètode -provat i estudiat- el qual es basa en les interaccions
d'objectes per resoldre les necessitats d'un sistema informàtic.

Un objecte és una estructura que conté dades i el codi que els maneja.

L'estructura dels objectes es defineix en les classes. En elles s'escriu
el codi que defineix el comportament dels objectes i s'indiquen els
membres que formaran part dels objectes d'aquesta classe. Entre els
membres de una classe pot haver-hi:

  - **Mètodes**. Són els membres de la classe que contenen el codi de la
    mateixa. Un mètode és com una funció. Pot rebre paràmetres i
    retornar valors.
  - **Atributs o propietats**. Emmagatzemen informació sobre el estat de
    l'objecte al que pertanyen (i per tant, el seu valor pot ser
    diferent per a cadascun dels objectes de la mateixa classe).

A la creació de un objecte basat en una classe se li anomena instanciar
una classe i a l'objecte obtingut també se li coneix com a instància
d'aqueixa classe.

Els pilars fonamentals de la POO són:

  - **Herència**. És el procés de crear una classe a partir de una altra,
    heretant el seu comportament i característiques i podent
    redefinir-los.
  - **Abstracció**. Fa referència al fet que cada classe oculta en el seu
    interior les peculiaritats de la seua implementació, i presenta a
    l'exterior una sèrie de mètodes (interface) el comportament dels
    quals està ben definit. Vist des de l'exterior, cada objecte és un
    ens abstracte que realitza un treball.
  - **Polimorfisme**. Un mateix mètode pot tenir comportaments diferents en
    funció de l'objecte amb que s'utilitze.
  - **Encapsulació**. En la POO s'ajunten en un mateix lloc les dades i el
    codi que els manipula.

Els avantatges més importants que aporta la programació orientada a
objectes són:

  - **Modularitat**. La POO permet dividir els programes en parts o
    mòduls més xicotets, que són independents uns d'uns altres però
    poden comunicar-se entre ells.
  - **Extensibilitat**. Si es desitgen afegir noves característiques a
    una aplicació, la POO facilita aquesta tasca de dues formes: afegint
    nous mètodes al codi, o creant nous objectes que estenguen el
    comportament dels ja existents.
  - **Manteniment**. Els programes desenvolupats utilitzant POO són més
    senzills de mantenir, a causa de la modularitat abans comentada.
    També ajuda seguir certes convencions en escriure'ls , per exemple,
    escriure cada classe en un fitxer propi. No ha d'haver-hi dues
    classes en un mateix fitxer, ni un altre codi a part de el propi de
    la classe.