---
layout: default
parent: 5. Accés a dades amb PHP PDO
nav_order: 7
has_children: false
---

# Triggers

Els disparadors (triggers) són procediments emmagatzemats a la base de dades que s’executen automàticament 
quan es du a terme una operació INSERT, DELETE o UPDATE sobre alguna taula en concret i permeten 
als usuaris executar funcions introduïdes per altres usuaris sense conèixer-ne la implementació.

 No es poden dur a terme en una operació SELECT.
 
 Aquesta acció podria servir per fer una comprovació de consistència en un conjunt de valors 
 per ser inserits, en el format de les dades abans de ser introduïdes, en una modificació 
 en una taula o en una modificació d’un conjunt de files.
 
 L’estàndard SQL defineix dos tipus d’activadors: activadors a nivell de fila i activadors a nivell d’instruccions.
  
 * S'activa un activador a nivell de fila per a cada fila que s'insereix, s'actualitza o se suprimeix. 
 Per exemple, si una taula té 100 files inserides, actualitzades o suprimides, el disparador 
 s'invoca automàticament 100 vegades per a les 100 files afectades.
       
* Un activador a nivell de sentència s'executa una vegada per a cada transacció
 independentment del nombre de files inserides, actualitzades o suprimides.
  
MySQL només admet activadors a nivell de fila. No admet els activadors a nivell de sentència.

En el [Tutorial de Triggers MySQL](https://www.mysqltutorial.org/mysql-triggers.aspx) teniu més informació.

En el nostre cas implementarem el següent disparador que actualitzarà el contador de pel·lícules de gènere quan hi 
haja una modificació en el gènere d'una pel·lícula.

Aquesta és la sintaxi d'un trigger BEFORE UPDATE

```sql
CREATE TRIGGER trigger_name
BEFORE UPDATE
ON table_name FOR EACH ROW
trigger_body
```

En aquest exemple el trigger executarà despŕes (AFTER UPDATE) d'actualitzar una pe·lícula:

```sql
DELIMITER $$

CREATE TRIGGER after_movie_update
AFTER UPDATE
ON movie FOR EACH ROW
BEGIN                           
    -- Si hi ha un canvi en el gènere
    IF new.genre_id <> old.genre_id THEN        
        -- Restem 1 al contador del gènere antic
        UPDATE genre SET number_of_movies = number_of_movies -1 WHERE id = old.genre_id;
        -- Sumem 1 al contador del gènere nou        
        UPDATE genre SET number_of_movies = number_of_movies +1 WHERE id = new.genre_id;
    END IF;
END$$

DELIMITER ;
```
   
{:.alert .alert-activity}
<div markdown="1">

### Trigger
{:.nocount .no_toc}

#### Enunciat
{:.nocount .no_toc}

1. Implementa un _trigger_ de forma que en actualitzar una pel·lícula es comprove si ha canviat
el gènere seleccionat. En cas afirmatiu que decremente el contador de pel·lícules en el gènere anterior
i augment en el nou. 
 
2. Implementa un _trigger_ actualitze el contador de pel·lícules en esborrar una pel·lícula.

Crea una carpeta anomenada `data` en el projecte i guarda en ella una exportació de la base de dades.
</div>    