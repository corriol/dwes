---
layout: default
parent: 5. Accés a dades amb PHP PDO
nav_order: 5
has_children: false
---

# Procediments emmagatzemats

## Introducció

Un procediment emmagatzemat és un programa (o procediment) emmagatzemat dins d'una base de dades.
 Estan escrits en llenguatges d'alt nivell (normalment propietaris, és a dir, cada SGBD implementa el seu)
  i contenen sentències d'SQL.
  
  El principal avantatge d'un procediment emmagatzemat és que quan s'executa, com a conseqüència
   d'una petició d'un usuari, ho fa directament en la base de dades i per tant és més ràpid processant dades que 
   si el mateix procés es realitzés fora de la base de dades (probablement en un altre servidor), 
   ja que estalvia la transmissió de les dades que tracta.
   
## Implementació

La sentència SELECT següent retorna totes les files de la taula `movie` la nostra base de dades:

```sql
SELECT 
    *    
FROM
    movie
ORDER BY title;
```

Si voleu desar aquesta consulta al servidor de base de dades per executar-la més endavant, una manera de fer-ho
 és utilitzar un procediment emmagatzemat.

La sentència CREATE PROCEDURE següent crea un nou procediment emmagatzemat que completa la consulta anterior:

```sql
DELIMITER $$

CREATE PROCEDURE GetMovies()
    BEGIN
    SELECT 
        *    
    FROM
    movie
    ORDER BY title;
END$$
DELIMITER ;
```
Per definició, un procediment emmagatzemat és un conjunt d’instruccions SQL emmagatzemades dins del 
servidor MySQL. En aquest exemple, acabem de crear un procediment emmagatzemat amb el nom `GetMovies()`.

Un cop desat el procediment emmagatzemat, el podeu invocar mitjançant la sentència CALL:

```sql
CALL GetMovies();
```
   
I la sentència retorna el mateix resultat que la consulta.

La primera vegada que invoqueu un procediment emmagatzemat, MySQL busca el nom al catàleg de la 
base de dades, compila el codi del procediment emmagatzemat, el col·loca en una àrea de memòria coneguda com a 
memòria cau i executa el procediment emmagatzemat.

Si torneu a invocar el mateix procediment emmagatzemat a la mateixa sessió,
 MySQL només executa el procediment emmagatzemat des de la memòria cau sense haver de recompilar-lo.

Un procediment emmagatzemat pot tenir paràmetres perquè pugueu passar-hi valors i recuperar-ne
 el resultat. Per exemple, podeu tenir un procediment emmagatzemat que retorne les pel·lícules  per
  gènere. En aquest cas, el gènere seria un paràmetre del procediment emmagatzemat.

Un procediment emmagatzemat pot contenir declaracions de flux de control, com ara IF, CASE i LOOP que li
 permeten implementar el codi en la forma de procediment.

Un procediment emmagatzemat pot cridar a altres procediments emmagatzemats o funcions
 emmagatzemades, cosa que permet modular el codi.
 
 
## Canvi temporal del delimitador

Com que el delimitador de sentències per defecte en MySQL és el punt i coma (;). Si intenteu crear un procediment
emmagatzemant amb diverses sentències el client MySQL interpretarà que cada sentència és diferent i
 podeu tenir problemes.
 
 La forma d'evitar aquest problema és canviar temporalment el delimitador.
 
```sql
-- Canvie el delimitador
DELIMITER //

-- Ací posaríem la declaració del procediment emmagatzemant
CREATE PROCEDURE  ...
 BEGIN
    SELECT * ...;
    SELECT * ...;
 END 

-- Tornem al delimitador per defecte 
DELIMITER ;
```

## Executar procediments emmagatzemats des de PHP

La forma d'executar procediments emmagatzemats des de PHP és molt semblant a executar qualsevol consulta:

En aquest exemple es crida un procediment emmagatzemant sense paràmetres:

```php
$sentencia = $mbd->prepare("CALL sp_update_counters()");
// llamar al procedimiento almacenado
$sentencia->execute();
```

{:.alert .alert-activity}
<div markdown="1">

### Procediment emmagatzemat
{:.nocount .no_toc}

#### Enunciat
{:.nocount .no_toc}

Crea un procediment emmagatzemant anomenat `sp_update_number_of_movies()`que actualitze els 
contadors de pel·lícues de gènere.

Aquest procediment serà excutat regularment perquè cada gènere tinga el número de pel·lícules del
gènere actualitat.

Crea en el projecte la pàgina `tasks.php` que incloga l'execució del procediment emmagatzemant.
</div>    
 
## Webgrafia
 
 * The PHP Group. _Sentencias preparadas y procedimientos almacenados_ [En línia]. Manual de PHP [Data de consulta:  
 20 de novembre de 2020]. Disponible en [https://www.php.net/manual/es/pdo.prepared-statements.php](https://www.php.net/manual/es/pdo.prepared-statements.php)
 
 * MySQLtutorial.org. _Introduction to MySQL Strored Procedures_ [En línia]. MySQLTutorial [Data de consulta: 20 de 
 novembre de 2020]. Disponible en [https://www.mysqltutorial.org/introduction-to-sql-stored-procedures.aspx](https://www.mysqltutorial.org/introduction-to-sql-stored-procedures.aspx)  