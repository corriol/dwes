---
layout: default
title: 4. Funcions
parent: 2. Característiques bàsiques del llenguatge
nav_order: 4
has_children: false
---

# Funcions
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }


{: .alert .alert-activity }
<div markdown="1">

### Activitat 7a: funció imatge
{:.nocount .no_toc }

Crea el fitxer funcions.php

Escriviu una funció per retornar una etiqueta HTML <img />. 

La funció hauria d’acceptar com a argument obligatori l’URL de la imatge i arguments opcionals per a un text 
alternatiu, alçada i amplada.

### Activitat 7b: funció imatge local
{:.nocount .no_toc }
Copieu la funció de l’exercici anterior i modifiqueu-la de manera que només es passe el nom de fitxer a la funció en
 lloc de l’URL completa. Dins de la funció, farem ús d’una variable global per fer l’URL completa.
  
Per exemple, si passem `photo.png` a la funció, i la variable global conté `/images`, llavors l’atribut `src` de 
l'etiqueta <img> retornada serà `/images/photo.png`. 

Una funció com aquesta és una forma senzilla de mantenir correctes les vostres etiquetes d’imatges, fins i tot si les
 imatges es mouen a un nou camí o servidor. Només cal canviar la variable global, per exemple, de `/images` a 
 `http://images.example.com/.`
</div>

{: .alert .alert-activity }
<div markdown="1">

### Activitat 8: Funcions
{:.nocount .no_toc }

Els colors web com `#ffffff` i `#cc3399` es realitzen concatenant els valors hexadecimals de color per a vermell, 
verd i blau. 

Escriu una funció que accepte 3 arguments: roig, verd i blau, i que retorne un string que conté el color adequat per
 utilitzar-lo en una pàgina web. 

Per exemple, si els arguments són 255, 0, i 255, llavors la cadena retornada hauria de ser #FF00FF.
 
Pot resultar útil utilitzeu la funció `dechex()` integrada, que es troba documentada a [http://www.php.net/](http://www.php.net/)

Assegureu-vos que els paràmetres reben valors enters i que són colors vàlids.

Implementa 3 exemples d’ús.
</div>

{: .alert .alert-activity }
<div markdown="1">

### Activitat 9: SQL
{:.nocount .no_toc }
Crea un fitxer anomenat `funcions_sql.php`.

Crea una funció anomenada `insert`  que ens genere una sentència INSERT INTO en SQL.  

Per a açò la funció rebrà dos paràmetres:  
1. El nom de la taula  
2. Un array associatiu que contindrà els noms i valors dels camps de la taula.
 
La sentència resultant tindrà la següent forma: 

```
“INSERT INTO nom_taula (nom dels camps separats per comes) VALUES (noms dels camps separats per comes amb el caràcter “:” davant)  
```
De moment, no farem res amb els valors dels camps. 

Ajuda: utilitza les funcions `sprintf`, `implode` i `array_keys`
</div>

{: .alert .alert-activity }
<div markdown="1">

### Activitat 10: SQL 
{:.nocount .no_toc }

A partir de l'exercici anterior crea una altra funció que reba els mateixos paràmetres més un paràmetre booleà 
per a indicar si volem generar la query amb els noms dels camps o no. 

El paràmetre tindrà el valor `true` per defecte.
 
Si el seu valor és `true` generarà la consulta igual que en l'exercici anterior, però si és `false` la generarà així: 

```sql
INSERT INTO nom_taula 
  VALUES (valors dels camps separats per comes amb el caràcter ‘:’ davant)
```
</div>

{: .alert .alert-activity }
<div markdown="1">

### Activitat 11: SQL 
{:.nocount .no_toc }
Repeteix l'exercici anterior amb els següents canvis: 

La cadena resultant es passarà per referència.

Passarem la cadena de la següent forma: 

```sql
INSERT INTO taula (camps) VALUES (valors) 
```

Dins de la funció substituirem el següent: 

1. El text taula pel nom de la taula. 
2. El text camps pels noms dels camps separats per comes
3. El text valors pels noms dels camps separats per comes i el caràcter ‘:’ davant. 

</div>