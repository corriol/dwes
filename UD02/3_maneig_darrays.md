---
layout: default
title: 3. Maneig d'arrays
parent: 2. Característiques bàsiques del llenguatge
nav_order: 3
has_children: false
---

# Maneig d'arrays
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

## Introducció

## _Arrays_ indexats

Alguns recursos: 

* [https://www.studytonight.com/php/indexed-array](https://www.studytonight.com/php/indexed-array)
* [https://aprende-web.net/php/php6_1.php](https://aprende-web.net/php/php6_1.php)

{: .alert .alert-activity }
<div markdown="1">
### Activitat 4: Arrays indexats
{:.nocount .no_toc }
Crea una pàgina anomenada arrays.php, copia el contingut de la pàgina index.php i resol els exercicis següents 
utilitzant funcions d'arrays:
1. Crea un array amb els noms de diversos alumnes de la classe incloent el teu. 
2. Mostra el nombre d'elements que té l'array (_count_). 
3. Crea una cadena de text que continga els noms dels alumnes existents en l'array separats per un espai i 
mostra-la (_implode_).
4. Mostra l'array en un ordre aleatori diferent al que ho vas crear (_shuffle_). 
5. Mostra l'array ordenat alfabèticament (_sort_). 
6. Mostra els alumnes el nom dels quals continga almenys una “a” (_array_filter_).
7. Mostra l'array en l'ordre invers al que es va crear (_rsort_). 
8. Mostra la posició que té el teu nom en l'array (_array_search_).
</div>

## Arrays associatius

{: .alert .alert-activity }
<div markdown="1">

### Activitat 5: Arrays associatius
{:.nocount .no_toc }

Segons l'INE les 7 ciutats més grans d’Espanya (per habitants) el 2018 van ser les següents:
- Madrid, MAD,	3.223.334
- Sevilla, AN	, 688.711
- Murcia, MU,	447.182
- Málaga, AN,	571.026
- Zaragoza, AR, 666.880
- València, CV,	 791.413
- Barcelona, CAT, 1.620.343

Copia index.php i crea un nou document `cituats.php`. Defineix un array que continga aquesta informació sobre ciutats i 
habitants. Imprimeix una taula d'ubicacions i habitants que incloga la població total de les 7 ciutats.

Opcional:
Modifica la solució de l’anterior exercici perquè mostre les ciutats ordenades per habitants. Després mostra-les per ordre alfabètic.

</div>

## Arrays multidimensionals

{: .alert .alert-activity }
<div markdown="1">

### Activitat 6: Arrays multidimensionals
{:.nocount .no_toc }

Crea una pàgina anomenada `arrays2.php`, copia el contingut de la pàgina index.php i resol els exercicis següents utilitzant funcions d'arrays: 

1. Crea un array d'alumnes on cada element siga un altre array que continga nom i edat de l'alumne. 
2. Crea una taula HTML en la qual es mostren totes les dades dels alumnes. 
3. Utilitza la funció `array_column` per a obtenir un array indexat que continga únicament els noms dels alumnes i 
mostra’ls per pantalla. 
4. Crea un array amb 10 números : 
   * utilitza la funció `array_sum` per a obtenir la suma dels 10 nombres. 
</div>

{: .alert .alert-activity }
<div markdown="1">
### Activitat 6b opcional
{:.nocount .no_toc }

Modifica la solució del exercici `ciutats.php` perquè la taula continga també la columna del total d’habitants de la 
comunitat autònoma de les ciutats de la llista i el percentatge sobre els habitants de la comunitat autònoma que representa.


Per exemple: 

| Ciutat | Habitants | Habitats CA | % sobre CA |
| -- | --: | --: | --: | 
| València | 791.413 |  5.003.769 | 15.81% | 

Pista: Caldrà modificar l'array  `$ciutats` i convertir-lo en multidimensional. Les dades de comunitats autònomes hauran
d'estar en un altre array. 

Dades: [Municipis de España](https://es.wikipedia.org/wiki/Anexo:Municipios_de_Espa%C3%B1a_por_poblaci%C3%B3n)

</div>


