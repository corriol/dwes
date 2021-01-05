---
parent: 6. Cap a un framework MVC
nav_order: 2
---

# Routing
Una de les tasques que hem de realitzar a l'hora d'analitzar un projecte és com anem a
 organitzar la taula de rutes. Nosaltres seguirem la guia següent: [REST resource naming](https://restfulapi.net/resource-naming/). 

Una ruta serà la url que ens donarà accés a un controlador. Com que encara no disposem de controladors
el que farem serà associar les dades de la URL amb l'script encarregat de resoldre aquesta operació.

## La classe Router
És convenient encapsular l'operativa amb la taula de rutes dins d'una classe que siga la responsable 
d'aquesta tasca.

Aquesta classe tindrà l'array de rutes com a atribut. I tindrà els següents mètodes:

* `setRoutes()`, que ens permetrà carregar les rutes de la taula.
* `route()`, que activarà el controlador encarregat de gestionar una ruta.

La ubicarem dins del directori `Core`, ja que serà una classe del nostre _framework_.

La seva responsabilitat serà gestionar tot el que tinga a veure amb les rutes de l'aplicació.

```php
# config/routes.php
return [
        ""=>"index.php",
        "movies"=>"movies.php",
        "partners"=>"partners.php",
        "movies-show"=>"single-page" 
];
```
