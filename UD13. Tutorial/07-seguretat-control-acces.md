---
layout: default
parent: 11. Introducció a Symfony 
nav_order: 7
has_children: false 
---

# Seguretat i control d'accés

{:no_toc}

1. Taula de continguts
{:toc}


## Configuració de la seguretat en Symfony

El sistema de seguretat de Symfony és molt potent i versàtil, encara que
també pot resultar complicat d'entendre i configurar. En
aquesta sessió aprendrem a establir els elements principals del mateix:

-   **El mecanisme d'autenticació**, és a dir, establir on estan
    registrats els usuaris amb accés a l'aplicació, per poder accedir
    a ells i validar les credencials.
-   **El mecanisme d'autorització**, és a dir, una vegada s'ha validat
    l'usuari que accedeix, i sempre que aquest siga correcte,
    determinar els seus permisos i a quins recursos pot accedir i a
    quins no.

### L'arxiu "security.yaml". El firewall de Symfony

L'arxiu `config/packages/security.yaml` emmagatzema la configuració
general del sistema de seguretat de la nostra aplicació Symfony. El seu
contingut per defecte és aquest:

```yaml
security:
    providers:
        users_in_memory: { memory: null }
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            anonymous: true
            lazy: true
            provider: users_in_memory

            # activate different ways to authenticate
            # https://symfony.com/doc/current/security.html#firewalls-authentication

            # https://symfony.com/doc/current/security/impersonating_user.html
            # switch_user: true
```

La secció `firewalls` és el nucli principal del nostre sistema de
seguretat. Dins veiem dos subsecciones: `dev`, que simplement
s'assegura que el profiler de Symfony i la barra de depuració (WDT, _Web
Debug Toolbar_) no es veuen afectats pel nostre sistema de seguretat, 
de manera que puguem seguir tenint la informació d'estat en la barra 
inferior que apareix en provar l'aplicació:

![Web Debug Toolbar](assets/movies-wdt.png)

La resta d'elements de l'aplicació es gestionaran des de la subsecció
`main`, on posarem la lògica de seguretat de la nostra aplicació. De
fet, el no tenir una subsecció `pattern` implica que automàticament
absorbeix la resta d'URLs que no hagen coincidit amb cap patró anterior
del firewall.

La subsecció `anonymous: true` indica que es permet un accés anònim a les
seccions que no estiguen protegides. De fet, si accedim a
`movies-symfony` i examinem la barra inferior d'estat, veurem que
hem iniciat sessió com usuaris a anònims (_anon._).

Veurem a continuació com afegir elements d'autenticació en aquest
apartat de configuració.

### Establir la manera d'autenticació i origen de dades

Podem establir diferents formes d'autenticació, i diferents fonts de
dades d'on obtenir els usuaris per a validar. El mecanisme més simple
(i menys recomanat) consisteix a utilitzar una autenticació bàsica
d'HTTP (aquella que mostra un "prompt" bàsic per a introduir _login_ i
_password_), i emmagatzemar els usuaris en el propi arxiu `security.yaml`.
Els _passwords_ poden (han de) estar encriptats en aquest arxiu, però
encara així, no és un mecanisme molt recomanable d'emmagatzematge.

Ens saltarem aquesta opció, i anirem a la qual realment ens interessa:
tindrem els usuaris registrats en una taula d'una base de dades MySQL
(en aquest cas, de la nostra base de dades de pel·lícules), i definirem un
formulari d'inici de sessió que utilitzar per a validar-nos contra aqueixa taula
a l'hora d'accedir a recursos protegits.

#### Definir l'entitat i taula d'usuaris

En la nostra aplicació de pel·lícules anem a afegir una nova entitat per
a emmagatzemar els usuaris de l'aplicació, i la seua corresponent taula
associada, emprant Doctrine. Els usuaris tindran en aquest cas un identificador
(autonumèric generat per Doctrine), un nom d'usuari, una contrasenya, un correu electrònic i
un rol (més endavant veurem per a què s'utilitza el rol).

Per tant, crearem primer l'entitat d'aquesta forma:

```
 php bin/console make:entity

 Class name of the entity to create or update (e.g. TinyPizza):
 > User

 created: src/Entity/User.php
 created: src/Repository/UserRepository.php
 
 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > username

 Field type (enter ? to see all types) [string]:
 > string

 Field length [255]:
 > 100 

 Can this field be null in the database (nullable) (yes/no) [no]:
 > 

 updated: src/Entity/User.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > password

 Field type (enter ? to see all types) [string]:
 > string

 Field length [255]:
 > 255

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/User.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > email

 Field type (enter ? to see all types) [string]:
 > string

 Field length [255]:
 > 255

 Can this field be null in the database (nullable) (yes/no) [no]:
 > yes 

 updated: src/Entity/User.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > role

 Field type (enter ? to see all types) [string]:
 > string

 Field length [255]:
 > 20

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/User.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > 
           
  Success!         
```

Una vegada creada l'entitat, el següent pas és migrar els canvis a la
base de dades, com ja hem fet prèviament:

```shell
php bin/console make:migration

php bin/console doctrine:migration:migrate
```

Alternativament a aquests dos comandos, i en el cas que done algun
conflicte amb migracions prèvies, també podem executar aquests altres
dos, que gestionen millor les diferències o novetats a migrar:

```shell
php bin/console doctrine:migrations:diff

php bin/console doctrine:migrations:migrate
```

Podem afegir a mà un usuari de prova en la base de dades, amb aquests
atributs:

-   username: user
-   password: user
-   email: proves@proves.org
-   role: ROLE_USER

Insistim, tornarem a tractar els rols més endavant. De moment deixarem
establit aquest per a aquest usuari de prova.

#### Implementar les interfícies requerides

Per a poder utilitzar una entitat com a font d'usuaris que puguen iniciar sessió, és
necessari que aquesta entitat implemente la interfície `UserInterface`, la
qual cosa obliga a definir els mètodes:

-   `getRoles()`, que retornarà un array amb els rols de l'usuari (en
    aquest cas, cada usuari només tindrà un rol, que retornarem en un
    array)
-   `getPassword()`, que retornarà el password de l'usuari
-   `getUserName()`, que retornarà el login de l'usuari
-   `getSalt()`, que en ocasions no és necessari emprar. S'empra en
    mecanismes de codificació de passwords més avançats que els que
    veurem en el curs. Així que en el nostre exemple retornarem null.
-   `eraseCredentials()`, que s'empra per a eliminar informació sensible
    o privada de l'usuari. Pot ser útil si, per exemple, s'emmagatzema
    el password de l'usuari sense encriptar. En primera instància ho
    farem així, però després ho encriptarem, així que aquest mètode ho
    anem a deixar buit.

A més, convé implementar la interfície `Serializable` per a poder
serializar objectes de tipus `User` i enviar-los entre les parts de
l'aplicació (més endavant veurem com obtenir l'objecte `User` de
l'usuari que ha iniciat sessió). Açò implica afegir dos mètodes més: `serialize` (per
a convertir l'usuari en text que enviar entre components) i `unserialize`
(per a convertir un text en un objecte `User`).

Amb tot açò, la nostra entitat `User` queda així (eliminem els `getters` i
`setters` generats automàticament i que no anem a emprar en realitat):

```php
# src/Entity/User.php
    
    ...

     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[] The user roles
     */
    public function getRoles()
    {
        return [$this->role];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * String representation of object.
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string|null The string representation of the object or null
     * @throws Exception Returning other type than string or null
     */
    public function serialize(): ?string
    {
        return serialize([
            $this->getId(),
            $this->getUsername(),
            $this->getPassword()
        ]);
    }

    /**
     * Constructs the object.
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized)
    {
        list( $this->id, $this->username, $this->password) = 
            unserialize($serialized, ['allowed_classes' => false]);
    }
```

En el cas de la funció `unserialize`, se li passa com a segon paràmetre un
array d'opcions. L'opció `allowed_classes` que s'utilitza en aquest
exemple, posada a `false`, fa que no es permeta la serialización
d'objectes de cap classe (només els tipus simples que componen els
atributs de l'entitat `User`, en aquest cas).

#### Configurar l'origen de dades i la manera d'autenticació

Anem ara a indicar on ha de buscar Symfony els usuaris quan algú intente
accedir al sistema. Per a açò, anem a l'arxiu de configuració
`config/packages/security.yaml`, i afegim un nou proveïdor de dades,
enllaçat a l'entitat `User`:

```yaml
security:
    providers: 
        ... 
        user_provider: 
            entity: 
                class: App\Entity\User 
                property: username
```

Indiquem el nom de l'entitat, i el nom de l'atribut que s'usuarà com a nom d'usuari
en l'inici de sessió.

Després, una mica més a baix en aqueix mateix fitxer (subsección
`security > firewalls > main`), establim que inicie sessió mitjançant
un formulari, que s'activarà amb la ruta anomenada `login` que definirem
després:

```yaml
security:
    firewalls: 
        dev: 
        ... 
        main: 
            anonymous: ~
            form_login: 
                provider: user_provider 
                login_path: login 
                check_path: login
```

En aquest cas, hem anul·lat (posat a null) l'accés anònim (açò és el que
significa el símbol `"~"` al costat d'anonymous. D'altra banda,
indiquem que tant per a mostrar el formulari com per a verificar l'inici de
sessió, s'acudirà a la mateixa ruta `login`, emprant com a proveïdor
d'usuaris l'element `user_provider` que hem definit abans, basat en
l'entitat `User`.

Podem, a més, definir quins recursos es van a protegir amb aquest
formulari. Convé, almenys, especificar un patró de ruta que excloga al
propi formulari d'inici de sessió, ja que en cas contrari entraríem en un bucle
infinit en el qual, per a accedir al formulari d'inici de sessió, hauríem 
d'iniciar sessió amb el formulari d'inici de sessió. Per a açò, editem la secció
`access_control` al final de l'arxiu `security.yaml`, i indiquem, almenys,
dues rutes: una sense protegir per a l'inici de sessió, i l'altra protegida per al
que vulguem (en aquest cas, la resta de l'aplicació):

```yaml
access_control:
-   { path: ^/login, rols: IS_AUTHENTICATED_ANONYMOUSLY }
-   { path: ^/, rols: ROLE_USER }
```

Finalment, també podem especificar el sistema de codificat del password.
Ara com ara no ho anem a codificar, per la qual cosa afegim aquest
subapartat al final (dins de la secció `security`):
```yaml
encoders: 
    App\User\Entity: 
        plaintext
```
#### Definir la ruta i el formulari d'inici de sessió

Finalment, anem a crear un nou controlador anomenat `SecurityController` en
la nostra carpeta de `src/Controller`, que definirà una nova ruta `/login`
per a mostrar el formulari d'inici de sessió i verificar l'error de validació,
si és el cas. Pot quedar més o menys així:

```php
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', array(
            'error' => $error,
            'lastUserName'=> $lastUsername
        ));
    }
}
```
Podríem obtenir altres dades del procés de validació. Per exemple, en
cas que l'usuari no es valide correctament, podríem recuperar el seu
nom d'usuari per a tornar-lo a introduir automàticament en la plantilla del
formulari, i així evitar que l'haja de tornar a escriure. Per a açò,
tenim el mètode `getLastUsername()` de l'objecte `AuthenticationUtils`.
N'hi ha prou amb recuperar la dada i passar-li-la a la plantilla:

```php
    ...
    $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', array(
            'error' => $error,
            'lastUserName'=> $lastUsername
        ));
```
El formulari d'inici de sessió estarà en `login.html.twig`, pot ser alguna
cosa així:

{% raw %}
```php
{% extends 'base.html.twig' %}
{% block title %}Movies - Login{% endblock %}

{% block body %}
<div class="container" >
    <div class="row">
        <div class="col-sm-12">
            <h3>Login</h3>

            <form method="post" novalidate>
                {% if error %}
                    <div class="alert alert-danger" role="alert">
                        {{ error.messageKey }}
                    </div>
                {% endif %}
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control"
                           name="_username" id="username"
                           value="{{ lastUserName }}"
                    placeholder="Username:" required>
                </div>
                <div class="form-group">
                    <label for="password">Contrasenya</label>
                    <input type="password" class="form-control"
                           name="_password" id="password"
                           value=""
                    placeholder="Password:" required>
                </div>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
</div>
{% endblock %}

```
{% endraw %}


Com veiem, es té un bloc `div` condicional per a mostrar un error de
validació si és el cas. A més, el formulari ha de complir unes normes:
el camp de login ha de cridar-se `_username` (atribut "name"), i el camp
de password ha de cridar-se `_password`. Aquestes opcions poden
configurar-se i personalitzar-se.

Després de tots aquests passos, ja tindrem llest el sistema
d'autenticació. Pot semblar estrany, però així és... En realitat,
Symfony es fa càrrec automàticament de la validació de l'usuari quan
aquest envia el formulari d'inici de sessió. Si hi ha algun error, es registrarà
dins del controlador `login` i es renderizará el formulari amb el missatge
d'error. Si tot és correcte, es redirigirà a l'usuari automàticament
cap a la pàgina que havia sol·licitat.

## Algunes opcions avançades

Ara que ja hem après a configurar una forma bàsica d'autenticació amb
formulari de login, vegem alguns aspectes una mica més avançats de la
configuració de la seguretat en Symfony.

### Encriptar les contrasenyes

És convenient que les contrasenyes dels usuaris registrats no estiguen
en text pla sense encriptar, com en l'exemple anterior. Podem emprar,
per exemple, un algorisme d'encriptació Argon o bcrype per a xifrar-les. En el nostre
cas, i seguint les recomanacions de la documentació oficial de Symfony deixarem que 
siga Symfony qui trie el millor algorisme possible. Açò
suposa dos passos extra:

-   Indicar a Symfony que els passwords estan encriptats 
    perquè aplique un algorisme per a encriptar qualsevol contrasenya,
    inclòs el que introduïsca l'usuari en iniciar sessió, i així poder
    comparar si les dos contrasenyes encriptades coincideixen. Per a fer
    açò, hem d'editar l'arxiu de configuració
    `config/packages/security.yaml` i indicar que l'entitat Usuari
    utilitzarà el mètode d'encriptació que hàgem escollit: 
    ```yaml
    security:
        ...
        encoders: 
            App\Entity\User: 
                algorithm: auto                
    ```
-   Encriptar de forma automàtica els passwords dels usuaris quan es
    registren. Si tinguérem un formulari de registre en la nostra
    aplicació, i tenim les dades de l'usuari guardats en un objecte
    usuari, i el password en sí en un objecte `password`, l'encriptació
    seria com segueix: 
    ```php
        use Symfony;
        public function register(UserPasswordEncoderInterface $encoder)
        {
            ...
            $user = new User();
            ...
            // Assignem ací la resta d'atributs de l'usuari
            $hashedPassword = $encoder->encodePassword($user, $password);

            $user->setPassword($hashedPassord);
            // Guardar en la base de dades, si escau
            ...

        }
    ```

El que fem és acudir a la configuració de l'arxiu `security.yaml`
anterior per a veure què codificador s'ha establit, i mitjançant l'objecte de tipus
`UserPasswordEncoderInterface` que rep com a paràmetre del mètode, i del seu mètode
`encodePassword`, codificar el password amb aqueix mateix algorisme.

Aquest mètode rep com a primer paràmetre l'usuari sobre el qual s'està treballant, i
com a segon paràmetre el password a codificar. Una vegada codificat, se li
assigna a l'usuari, i ja es podria guardar en la base de dades.

Per a provar el nostre exemple de pel·lícules, com no tenim formulari de
registre, anem a codificar manualment el/els password(s) que tinguem en
la base de dades. Podem emprar webs com [https://bcrypt-generator.com/](https://bcrypt-generator.com/). 

### Treballar amb rols

En els exemples fets fins ara, ens hem limitat a definir un camp `role` en
la nostra entitat `User`, i a emmagatzemar un usuari amb rol `ROLE_USER`,
però sense prestar massa atenció al que aqueix rol significa.

Per a començar, hem de saber que tots els rols que definim en la nostra
aplicació han de començar amb el prefix `ROLE_` perquè Symfony els tracte
com a tals. Existeix la possibilitat de configurar aquesta opció i posar
rols més flexibles o arbitraris.

Symfony permet definir diferents rols en una aplicació, i establir una
jerarquia entre ells, de manera que un rol puga fer tot el que fa un
altre més altres coses. A més, podem protegir l'accés a recursos per a
determinats rols, de manera que només certs rols (o rols que estiguen
per sobre d'ells en la jerarquia) puguen accedir.

#### Assignar diferents rols a diferents recursos protegits

En el cas que cada zona protegida de la nostra aplicació puga tenir
assignats rols diferents, n'hi ha prou amb indicar el rol (o rols entre
claudàtors) que tenen permís per a cada zona. Per exemple, en aquest cas
protegim l'accés a qualsevol ruta que comence per `/admin` perquè
només puguen accedir usuaris amb rol ROLE_ADMIN o ROLE_MANAGER, a més de
la configuració ja establida en exemples previs:

```yaml
security
    access_control:
        - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/, roles: ROLE_USER }
        - { path: ^/admin, roles: [ROLE_ADMIN, ROLE_MANAGER] }
```
#### Establir jerarquies entre rols

Pot ser necessari també establir una jerarquia entre rols, de manera
que, si es té un rol de nivell superior, es tindrà accés a tots els
recursos que exigisquen un rol de nivell inferior. Per a fer açò, afegim
un subapartat `role_hierarchy` en la nostra secció `security` de
`config/packages/security.yaml`, establint aquesta jerarquia. En el
següent exemple, el rol `ROLE_ADMIN` conté a `ROLE_USER`, i el
`ROLE_SUPERADMIN` conté tant a `ROLE_ADMIN` (i, per tant, a `ROLE_USER`), com
a `ROLE_MANAGER`.

```yaml
security: 
    ... 
    role_hierarchy: 
        ROLE_ADMIN: 
            ROLE_USER 
        ROLE_SUPER_ADMIN:
            [ROLE_ADMIN, ROLE_MANAGER]
``` 

#### Comprovar rols des dels controladors i vistes

Existeix també la possibilitat de forçar una comprovació de seguretat en
el codi dels controladors, basant-se en si l'usuari registrat té cert
rol o no, o simplement si s'ha autenticat o no. Per exemple, aquest
controlador permet l'accés al seu codi si l'usuari té el `ROLE_ADMIN`:
```php
   /**
     * @Route("/movies/create", name="movies_create")
     */
    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Accés restringit a administradors');
        ...
```
Aquest altre controlador simplement comprova si l'usuari s'ha
autenticat correctament abans de seguir:

```php
public function otherController() {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 
    ...
```
En qualsevol cas, el mètode `denyAccessUnlessGranted` provoca que:

-   Si l'usuari encara no s'ha autenticat, se li redirigeix a la
    pàgina d'inici de sessió.
-   Si s'ha autenticat però no té el rol requerit, es genera una
    pàgina d'error HTTP 403 (accés prohibit). Aquesta pàgina es pot
    personalitzar.

També és possible comprovar un determinat rol o autenticació des del
codi d'una plantilla Twig, mitjançant la funció `is_granted`. Açò pot
servir per a mostrar o no certs apartats de la vista.

{% raw %}
```twig
{% if is_granted('ROLE_ADMIN') %} 
    ... 

{% endif %}
```
{% endraw %}

### Obtenir l'objecte `user`

Una vegada autenticats, l'objecte `user` es pot obtenir a través del
mètode `getUser`, normalment des de dins d'un controlador. Així, podríem
fer:

```php
/**
* @Route("/admin", name="admin")
*/
public function admin()
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    $user = $this->getUser();
    return new Response("Bienvenido a /admin, " . $user->getUserName());
}
```

Des d'una plantilla Twig també és possible accedir a l'usuari
que ha iniciat sessió i obtenir, per exemple, el seu nom d'usuari, amb 
l'objecte `app.user`:

{% raw %}
```
Benvingut/a, {{ app.user.username }}
```
{% endraw %}

### Eixint de l'aplicació (logout)

Podem configurar el tancament de sessió o _logout_ a través d'arxius YAML
exclusivament. N'hi ha prou amb seguir aquests dos passos:

-   En primer lloc, establim que quan s'isca de l'aplicació es
    redirigirà, per exemple, a l'arrel de la mateixa. Açò es fa en
    l'arxiu `config/packages/security.yaml:`. Per exemple, per a
    permetre tancar sessió en el firewall `main`, amb el qual estem
    treballant en el nostre exemple, faríem açò: 
    ```yaml
    firewalls: 
        ... 
        main:
            logout:
                path: /logout
                target: /
    ```
-   En segon lloc, definim una ruta en l'arxiu `config/routes.yaml` que
    associe el nom "logout" (o com el vulguem cridar) amb la ruta que
    hàgem definit abans (en aquest cas, la ruta `/logout`): 
    ```yaml
    logout: 
        path:
            /logout
    ```
Amb açò, i algun enllaç en les nostres plantilles per a poder tancar
sessió (per exemple, en la plantilla base)...

{% raw %}
```php
    <a href="{{ path("logout") }}">Tancar sessió</a>
```
{% endraw %}
... ja podrem canviar d'usuari o eixir de l'aplicació.

## Exercicis

### Exercici 7.1

#### Movies
Afig la configuració de seguretat. Per a açò:

-   Crea una entitat `User` de forma similar a com hem fet per en els exemples, i la corresponent taula. 
    L'entitat tindrà els mateixos camps que en l'exemple.
-   Defineix un formulari de _login_ com el de l'exemple, i
    configura l'arxiu `config/packages/security.yaml` perquè utilitze
    aquest formulari, sota la ruta `/login` (defineix també el controlador
    associat a aqueixa ruta, com en l'exemple)
-   Protegeix l'accés a la creació de pel·licules (ruta `/movies/create`),
    perquè només usuaris de tipus `ROLE_ADMIN` puguen accedir. Crea un
    usuari d'aquest tipus en la base de dades manualment, per a
    poder-ho provar.
-   Crea la jerarquia de rols de l'exemple i fes que a la ruta `/admin`
    sols hi puguen accedir els usuaris amb `ROLE_ADMIN`
-   Canvia les routes dels CRUD de pel·lícules i gèneres de forma que comencen per
   `/admin` així s'aplicarà la restricció anterior.   

### Exercici 7.2

#### Movies

Sobre l'autenticació anterior, anem a afegir aquests canvis:

-   Codifica els passwords de l'aplicació amb l'algorisme triat per Symfony, i
    Codifica manualment els passwords dels usuaris que hages
    afegit a la base de dades, usant la web que s'ha proporcionat en
    els apunts.
-   Afig un enllaç per a "Iniciar sessió" en la plantilla.
-   Afig un enllaç "Tancar sessió" en la plantilla `base.html.twig` perquè
    es puga tancar sessió des de qualsevol vista de la web.
-   Els dos enllaços anteriors són incompatible, fes que si l'usuari ha iniciat
    sessió aparega el de tancar i viceversa.    
-   Opcionalment, fes que aparega el nom d'usuari al costat de l'enllaç de tancar sessió.     

### Exercici 7.3

Aplica els mateixos canvis en el projecte personal, respectant la funcionalitat de rols
que havies previst.

