---
layout: default
parent: 5. Accés a dades amb PHP PDO
nav_order: 4
has_children: false
---

# Transaccions
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

## Introducció

Una transacció en un Sistema de Gestió de Bases de Dades és un conjunt
d'ordres que s'executen formant una unitat de treball, és a dir, en
forma indivisible o atòmica.

Un SGBD es diu transaccional si és capaç de mantenir la integritat de
dades, fent que aquestes transaccions no puguin finalitzar en un estat
intermedi. Quan per alguna causa el sistema ha de cancel·lar la
transacció, comença a desfer les ordres executades fins a deixar la base
de dades en el seu estat inicial (anomenat punt d'integritat), com si
l'ordre de la transacció mai s'hagués realitzat. Una transacció ha de
comptar amb ACID (un acrònim anglès) que vol dir: Atomicidad,
consistència, aïllament i durabilitat.

## Transaccions en PDO

Com ja hem comentat, no tots els SGBD són transaccionals. Però també es
possible que tot i que el SGBD ho siga el motor d'emmagatzemat no ho
suporte. Per la qual cosa si necessites utilitzar transaccions hauràs
d'assegurar-se que estiguen suportades pel motor d'emmagatzematge que
gestiona les teves taules en MySQL. Per exemple el motor MyISAM no les
suporta.

Per defecte PDO treballa en mode autocommit. Confirma automàticament
cada sentència que executa el servidor.

Per treballar amb transaccions, PDO incorpora tres mètodes:

  - `beginTransaction`. Deshabilita la manera autocommit i comença una
    nova transacció, que finalitzarà quan executis un dels dos mètodes  
    següents.
  - ` commit`. Confirma la transacció actual.
  - `rollback`. Reverteix els canvis duts a terme a la transacció
    actual.

Un cop executat un `commit` o `rollback`, es tornarà al mode de
confirmació automàtica.

Si utilitzem un motor que no suporta transaccions PDO no mostrarà cap
error, simplement serà incapaç de realitzar un `rollback` si fora
necessari.

### Esquema bàsic d'ús de transaccions amb PDO

```php

    #Esquema bàsic d'ús de PDO
    try{ 
        $pdo->beginTransaction(); 
        $pdo->exec('DELETE ...'); 
        $pdo->exec('UPDATE ...'); 
        $pdo->commit(); 
    }
    catch(PDOException $exception) { 
        $pdo->rollback(); 
    }
```

### Exemple

```php
    $mbd = new PDO('odbc:SAMPLE', 'db2inst1', 'ibmdb2',
            array(PDO::ATTR_PERSISTENT => true));
        echo "Conectado\n";
    } catch (Exception $e) {
        die("No se pudo conectar: " . $e->getMessage());
    }
    
    try {
        $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $mbd->beginTransaction();
        $mbd->exec("insert into staff (id, first, last) values (23, 'Joe', 'Bloggs')");
        $mbd->exec("insert into salarychange (id, amount, changedate) 
          values (23, 50000, NOW())");
        $mbd->commit();
    
    } catch (Exception $e) {
        $mbd->rollBack();
        echo "Fallo: " . $e->getMessage();
    }

```