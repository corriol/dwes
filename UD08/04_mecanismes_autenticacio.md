---
layout: default
nav_order: 4
title: 3. Gestió d'usuaris
parent: 8. Seguretat i control d'errors 
---

# Gestió d'usuaris 
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

## Introducció
Una part important de les aplicacions web és la gestió d'usuaris que 
ens permetrà establir mecanismes de control d'accés i autorització.

En l'actualitat hi ha diverses formes d'implementar-ho, entre elles
la més habitual continua sent l'ús d'un formulari d'inici de sessió que valida les dades
contra una base dades.

Hi ha d'altres, com l'autenticació basada en HTTP o l'ús d'un components de tercers com 
els comptes de Google o Facebook. També hi ha altres iniciatives com OpenId o WebId.
Nosaltres ens centrarem en l'autenticació basada en un formulari d'inici de sessió.

## Autenticació d'usuaris

Anem a implementar el sistema d'autenticació d'usuaris basats en un formulari d'inici de sessió.

El primer que caldrà serà crear la taula `User`.

```sql
--
-- Estructura de la taula `user`
--
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
```

```sql
--
-- Índexs per a la taula `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE_USERNAME` (`username`);
``` 

```sql
INSERT INTO `user` (`id`, `username`, `password`, `role`) VALUES
(1, 'user', 'user', 'ROLE_USER'),
(2, 'admin', 'admin', 'ROLE_ADMIN')
```

Una vegada actualitzada la base de dades caldrà crear el model.

```php
# src/Model/UserModel.php
...
class UserModel extends Model
{
  public function __construct(PDO $pdo, string $tableName = "user", 
    string $className = User::class)
    {
        parent::__construct($pdo, $tableName, $className);
    }
}
```
Ara ja podem implementar el formulari d'inici de sessió.

```php
<!-- views/auth/login.view.php -->
<div class="container" >
    <div class="row">
        <div class="col-sm-12">
            <h3>Login</h3>
            <form method="post" novalidate>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" 
                        name="username" id="username"
                        value="<?= null ?? "" ?>" 
                        placeholder="Username:" required>
                </div>
                <div class="form-group">
                    <label for="password">Contrasenya</label>
                    <input type="text" class="form-control" 
                    name="password" id="password"
                           value="<?= null ?? "" ?>" 
                           placeholder="Password:" required>
                </div>
                <input type="submit" value="Login">
            </form>
        </div>

    </div>
</div>
```

I el controlador `AuthController`

```php
# src/Controller/AuthController.php
...
class AuthController extends Controller
{
    public function login()
    {
        return $this->response->renderView('auth/login', 'default');
    }

    public function checkLogin()
    {
        $messages = [];
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        if (!empty($username) && !empty($password)) {
            // TODO: Authentication                                    
            
        }
        App::get('flash')->set("message", "No s'ha pogut iniciar sessió");
        App::get(Router::class)->redirect("login");
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        setcookie(session_name());
        App::get(Router::class)->redirect("");
    }
}
```

<div markdown="1" class="alert-activity alert">
**Activitat 7. Autenticació d'usuaris**

Seguint les indicacions anteriors implementa el sistema d'autenticació
amb formulari. 

El mètode `AuthController::checkLogin` l'has de completar. Si l'autenticació falla
cal redirigir de nou a la pàgina d'inici de sessió, si és correcta
redirigiràs a `/movies` i afegiràs a la variable de sessió `loggedUser` l'id de l'usuari.
</div>

## Autorització

Per a gestionar l'autorització crearem la classe `Security`. En aquesta classe implementarem
els mètodes que tinguen relació en la seguretat de la aplicació.

Començarem per un mètode bàsic `isAuthenticatedUser()`. Aquest mètode comprova
si existeix un `id` en la variable de sessió `loggedUser`. Si existeix, tracta de carregar
un usuari en eixe `id`. Si existeix l'usuari torna `true` si no `false`.

```php
# src/Core/Security.php
class Security
{    
    public static function isAuthenticatedUser(): bool
    {
        if (!empty($_SESSION["loggedUser"])) {
            $id = filter_var($_SESSION["loggedUser"], FILTER_VALIDATE_INT);
            if (!empty($id)) {
                try {
                    $user = App::getModel(UserModel::class)->find($id]);
                    return true;  
                } 
                catch (NotFoundException $notFoundException) {
                    return false;
                }   
            }                                  
        }
        return false;
    }
}
```
Imagina que per a accedir a `/movies` has de ser un usuari autenticat, modificaríem
el mètode del controlador afegint a l'inici el següent codi:

```php
# src/Controller/MovieController.php
...
public function index() {
    if (!Security::isAuthenticatedUser())
        App::get(Router::class)->redirect('login');
    ...
}
```
D'esta forma sols els usuaris autenticats podran accedir a `/movies`.

## Automatització de la gestió de l'usuari

Per a facilitar la gestió de l'usuari autenticat ens recolzarem en el 
contenidors de serveis, així quan hi haja una sol·licitud a la aplicació 
intentarem carregar la informació de l'usuari en cas d'estar autenticat.

```php
# src/bootstrap.php

// we use the coalesce operator to check if loggedUser is set
// if not we assign 0 to $loggedUser.
$loggedUser = $_SESSION["loggedUser] ?? 0;

// we check if $loggedUser is a valid integer 
$id = filter_var($loggedUser, FILTER_VALIDATE_INT);
if (!empty($id)) {
    try {
        App::bind('user', App::getModel(UserModel::class)->find($id));            
    } 
    catch (NotFoundException $notFoundException) {
        App::bind('user',null);            
    }
}
else
    App::bind('user', null);
```

El primer que fem és obtenir el valor de la variable de sessió `loggedUser`, si existeix
és perquè l'usuari està autenticat, així que obtenim la instància de `User`  i la afegim al contenidor de
serveis amb la clau `user`. De no existir afegirem el valor `null` a la clau `user`.

Ara podem canviar la implementació de `Security::isAuthenticatedUser`

```php
# src/Core/Security.php
class Security
{    
    public static function isAuthenticatedUser(): bool
    {
        if (App::get('user')!==null)) 
            return true;
        return false;
    }
}
```

<div markdown="1" class="alert-activity alert">
**Activitat 8. Autorització d'usuaris**

Seguint les indicacions anteriors implementa el sistema d'autorització bàsic
de forma que per a poder crear, editar o esborrar pel·lícules 
s'ha d'estar autenticat. Si no ho estan, caldrà enviar-los a la pàgina `/login`.
</div>

## Seguretat per rol

En la majoria de sistemes operatius, per a simplificar la gestió de l'autorització és 
creen grups administratius. 

En les aplicacions web s'empra un sistema semblant basat en **rols**. Cada rol té associat
una sèrie de privilegis, coses que poden fer els usuaris que tenen eixe rol.

En el nostre projecte usarem un sistema de rols senzill:

* `ROLE_ANONYMOUS`. Un usuari que no s'ha autenticat.
* `ROLE_USER`. Serà el rol mínim que tindrà un usuari autenticat. 
* `ROLE_ADMIN`. Un rol destinat a l'usuari administrador.

Els rols seran jeràrquics, és a dir, un usuari amb `ROLE_ADMIN` tindrà els privilegis
de `ROLE_USER` i `ROLE_ANONYMOUS`. 

El rol de cada usuari s'emmagatzemarà en el camp `role` de la taula `user`.

### Definició de rols

Els rols els definirem el fitxer de configuració `config.php` en la clau
`security`:

```php
  # src/config.php
  ...
  "security" => ["roles" =>
                     [
                        "ROLE_ADMIN" => 3,
                        "ROLE_USER" => 2,
                        "ROLE_ANONYMOUS" => 1
                    ]
                ]
   ...
```
Cal destacar que a major valor, més privilegis.

### Esquema de privilegis

En la nostra aplicació els privilegis seran els següents:

* ROLE_ADMIN. Tindrà tots els privilegis.
* ROLE_USER. Sols podrà crear i editar pel·licules.
* ROLE_ANONYMOUS. Podrà navegar per les pàgines que no requereixen autenticació.

### Implementació de la seguretat basada en rols

Els rols els associarem a cada ruta. Sent el rol mínim (`ROLE_ANONYMOUS`)
el rol per defecte. Haurem de fer canvis en els mètodes `Router::get`
i `Router::post`.

```php
# src/Core/Router.php
public function get(string $path, string $controller, string $action,
                        array $parameters = [], string $name = "",
                        string $role = 'ROLE_ANONYMOUS'): void
    {
        
        $this->routes["GET"][$path] = ["controller" => $controller, 
                        "action" => $action, "parameters" => $parameters, 
                        "name" => $name, "role"=>$role];
    }
```

En el mètode `Router::route()` obtindrem el rol mínin associat a la ruta i 
li'l passarem al mètode `Security::isUserGranted()`. Aquest mètode tornarà
`true` en cas que l'usuari autenticat tinga rol amb major o igual valor que 
el rol mínim associat a la ruta.

```php
# src/Core/Router.php
public function route(Request $request): string
    ...
    foreach ($this->routes[$method] as $route => $data) {
            // movies/\d+/show
            $regexRoute = $this->getRegexRoute($route, $data);
            if (preg_match("@^$regexRoute$@", $requestedUrl)) {
                $role = $data['role'];
                if (!Security::isUserGranted($role))
                    throw new 
                        AuthorizationException('You do not have access permissions');
        ...
```

Si el rol mínim de la ruta és `ROLE_ANONYMOUS` tornarà `true`
immediatament. Si no, obtindrem l'usuari que ha iniciat sessió
i comprovarem si el rol de l'usuari és major o igual que el rol de
la ruta. 


```php
# src/Core/Security.php
public static function isUserGranted(string $minRole): bool
{
    if ($minRole === 'ROLE_ANONYMOUS')
        return true;
    $user = App::get('user');
    if ($user === null) {
        App::get(Router::class)->redirect('/login');
    } else
    $userRole = $user->getRole();

    // we load the app roles
    $roles = App::get("config")["security"]["roles"];
    
    // we get the role values
    $userRoleValue = $roles[$userRole]; //ROLE_USER => 2
    $minRoleValue = $roles[$minRole]; //ROLE_ADMIN => 3
    
    // we return the comparison
    return ($userRoleValue >= $minRoleValue);
}
```

<div markdown="1" class="alert-activity alert">
**Activitat 9. Seguretat per rol**

Seguint les indicacions anteriors implementa la seguretat basada en rols basada 
en l'esquema indicat.
</div>