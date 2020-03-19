---
layout: default
title: 2. Maneig de cadenes de text i dates
parent: 2. Característiques bàsiques del llenguatge
nav_order: 2
has_children: false
---

# Maneig de cades de text i dates
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

## Maneig de cadenes de text

## Maneig de dates

### Funció `time()`

En PHP les dates s'emmagatzemen com a números enters. La funció `time()` retorna el nombre de segons transcorreguts des de l'1 de gener de 1970 (instant conegut com a època Unix). A aquesta forma d'expressar data i hora se li denomina **timestamp**.

### Funció `date()`

```php
date ( string $format [, int $timestamp = time() ] ) : string
```

La funció `date` retorna una cadena formatada segons els codi de format. Si no li passem la variable `timestamp` ens retorna la cadena formatada per a la data i l'hora actual.

Els codis de format més habituals  per a la funció `date` són:

|CODI|DESCRIPCIÓ|
|--- |--- |
|a|am o pm|
|A|AM o PM|
|d|Dia del mes amb zeros|
|D|Abreviatura del dia de la setmana (en anglès)|
|F|Nom del mes (en anglès)|
|h|Hora en format 1-12|
|H|Hora en format 0-23|
|i|Minuts|
|j|Dia del mes sense zeros|
|l|Dia de la setmana|
|m|Número de mes (1-12)|
|M|Abreviatura del mes (en anglès)|
|s|Segons|
|y|Any amb 2 dígits|
|Y|Any amb 4 dígits|
|z|Dia de l'any (1-365)|

La informació completa la pots trobar en el [manual oficial de PHP: date](https://www.php.net/manual/es/function.date.php)

Suposant que hui és 15 de setembre de 2019 i les 19 hores 20 minuts i 23 segons et mostrem alguns exemples:

```php
    date("d-m-Y"); // 15-09-2019

    date("H:i:s");  //  19:20:23

    date("Y"); // 2019

    date("YmdHis"); // 20190915192023

    date("d/m/y H:i a"); // 15/09/19 19:20 pm

    date("d-m-Y H:i", time()); // Moment actual
```



### Funció `mktime(hora, min, seg, mes, dia, any)`

La funció mktime permet obtenir la marca de temps Unix (_timestamp_) d'una data.

Per exemple:

```php
$data = mktime(0, 0, 0, 1, 1,2020);
echo date('d-m-Y', $data); // mostrarà 01-01-2020
```
Més informació en [https://www.php.net/manual/es/function.mktime.php](https://www.php.net/manual/es/function.mktime.php)

Un altra forma de crear dates és mitjançant la funció `strtotime()` on podem indicar les dates mitjançant una cadena de text. Per exemple:

```php
// posarem la data en el format any-mes-dia per a evitar confusions.
$date =strtotime("2020-01-01"); 
```

### Operar amb dates

Com hem dit al principi les dates s'emmagatzem com a enters on cada unitat representa un segon. Així podem sumar i restar dates (_timestamp_) per a afegir, llevar o calcular diferències entre ells.

Per exemple, per poder determinar els dies que falten per a l'1 de gener de 2020 faríem:

```php

   $data = mktime(0, 0, 0, 1, 1,2020); // timestamp que representa l'1 de gener de 2020
   $diferencia = $data - time(); // restem a l'1 de gener de 2020 el temps actual, 
                                 // el resultat estarà en segons.
   $diferenciaEnDies =((( $diferencia / 60) / 60 ) / 24 );
   /*  
       dividim els segons entre 60 i obtenim els minuts,
       els dividim entre 60 i obtenim les hores,
       els dividim entre 24 i obtenim els dies 

   */

```

### Classe DateTime

PHP disposa també de la classe DateTime per a representar les dates, la veurem més avant.


{: .alert .alert-activity }
<div markdown="1">

### Treballar amb dates
{:.nocount .no_toc}

Crea una pàgina anomenada dates.php i realitza les següents tasques:

1.  Mostra la data i hora actuals amb el format: `dd/mm/yyyy hh:mm:ss`
2.  Mostra el nom de la zona horària que s'utilitza per defecte.
3.  Mostra la data de que serà d’ací 45 dies.
4.  Mostra el nombre de dies que han passat des de l'1 de gener.
5.  Mostra la data i hora actuals de Nova York.
6.  Mostra el dia de la setmana que era l'1 de gener d'enguany.

En acabar penja el document a Moodle.

