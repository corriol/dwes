---
parent: 6. Cap a un framework MVC
nav_order: 3
---

# La classe Request

La classe `Request` serà la responsable de gestionar les sol·licituds dels usuaris. 
Tindrà tres propietats `domain`, `path` i `method`. De moment
crearem el mètode `Request::getPath()` per obtenir la ruta sol·licitada.  

```php
public function __construct() {
    $this->domain = $_SERVER['HTTP_HOST'];
    $this->path = explode('?', $_SERVER['REQUEST_URI'])[0];
    $this->method = $_SERVER['REQUEST_METHOD'];
}
```
