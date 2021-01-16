---
layout: default 
title: 6. Gestió global d'excepcions
parent: 8. Seguretat i control d'errors
nav_order: 8 
---

# Gestió global d'excepcions
Per a complementar al nostre _framework_ MVC anem a crear un
gestor global d'excepcions.

El gestor global ens permetrà capturar tots els errors a nivell 
d'aplicació i mostrar als usuaris missatges més fàcils d'entendre. 

A més, usarem els [codi d'estat d'HTTP](https://en.wikipedia.org/wiki/List_of_HTTP_status_codes) per a informar al navegador  de quin tipus d'error s'ha produit.


## La classe AppException

```php
# src/Core/Exception/AppException.php
class AppException extends Exception
{
    /**
     * AppException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Internal server error", $code = 500, 
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

```
Per defecte establirem el codi d'error a 500, que és un error intern de servidor. 
Quan capturem els aquestes excepcions les gestionarem mitjançant el mètode
`AppExecption::handleException`.

Aquest mètode escriurà el codi d'estat en la capçalera HTTP i mostrarà 
una pàgina en l'error que s'ha produit.

```php

# src/Core/Exception/AppException.php
/**
  * @return string
  * @throws NotFoundException
*/
public function handleException(): string
    {
    $message = $this->getMessage();
    $code = $this->getCode();

    // get the http status related to the error code
    // for instancd: 404 -> 404 Not found
    $httpStatus = $this->getHttpStatus();

    // HTTP/1.1 404 Not found    
    header($_SERVER["SERVER_PROTOCOL"] . ' ' . $httpStatus);

    $response = App::get(Response::class);
    return $response->renderView('error', 'default', compact('message', 'code'));
}
```

```php
# src/Core/Exception/AppException.php
private function getHttpStatus(): string
{
    switch ($this->getCode()) {
        case 404:
            $status = "404 Not Found";
            break;

        case 403:
            $status = "403 Forbidden";
            break;

        default:
            $status = "500 Internal Server Error";

    }
    return $status;
}
```
Una vegada implementada la classe caldra fer-ne un en el controlador frontal
de forma que si hi ha excepcions globals `AppException`, o en alguna classe filla, 
en activar una ruta es capturen i es gestionen en el mètode `AppException::handleException`.

<div markdown="1" class="alert-activity alert">
** Activitat 11: Gestor global d'errors **

Implementa la classe `AppException` seguint les indicacions. Fes-ne ús 
en el controlador frontal.

Aquelles excepcions que podem considerar a nivell d'aplicació, com per exemple,
`NotFoundException` haurien d'heretar de `AppException` i així poder ser capturades
en el controlador frontal. Així si accedim a una ruta inexistent ens hauria de mostrar
 el típic error 404.

Recorda que les excepcions sols es poden capturar una vegada.
</div>