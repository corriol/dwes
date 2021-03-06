---
layout: default
title: 4. Seguretat
nav_order: 4
parent: 12. Tutorial de Symfony
has_children: false 
nav_exclude: true
---

# Seguretat
{: .no_toc }


## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

## Introducció

El component de seguretat de Symfony proporciona un sistema de seguretat complet per a l'aplicació web. Ofereix facilitats per autenticació mitjançant autenticació bàsica HTTP, inici de sessió de formulari o autenticació de certificat X.509, però també vos permet implementar les vostres estratègies d'autenticació. A més, el component proporciona formes d’autoritzar els usuaris autentificats en funció del seu rol.

Ens endinsar-nos en el component Security implementant un sistema d'autenticació i autorització basada en un formulari d'inici de sessió i en l'assignació de rols. Per a fer-ho seguirem la guia [Security](https://symfony.com/doc/current/security.html).

## Pas 1. Instal·lació del component
En el nostre cas ja tenim instal·lat el component ja que vam emprar el paràmetre `--full` per la qual cosa no caldra tornar-lo a instal·lar.

## Pas 2. Creació de la classe User
El sistema d'autenticació de Symfony requereix de disposar d'una entitat `User` que haurà d'implementar la interfície `UserInterface`.

Si no la tenim creada podem usar el comandament `php bin/console make:user`. Com nosaltres ja l'hem creat haurem de fer les modificacions manualment.


{: .alert .alert-info}
<div markdown="1">
### Interfícies ###
Les interfícies ens permeten crear codi que especifique quins mètodes ha d'implementar una classe *sense necessitat de definir com s'implementen aquests mètodes*.

Les interfícies es defineixen de la mateixa manera que una classe, però amb la paraula clau de _interface_ que substitueix la paraula clau _class_ i sense que cap mètode haja definit el seu contingut.

Tots els mètodes declarats en una interfície han de ser públics; aquesta és la naturalesa d’una interfície.

En altres llenguatges aquests tipus de classes s'anomenem protocols.
</div>

### UserInterface ###

Els mètodes que haurem d'implementar en la nostra classe User seran:

```php
interface UserInterface
{
    
    public function getRoles(): array;

    public function getPassword():?string;

    public function getSalt():?string;

    public function getUsername(): string;

    public function eraseCredentials(): void;
}
```

Per a més detalls: [UserInterface.php](https://github.com/symfony/symfony/blob/master/src/Symfony/Component/Security/Core/User/UserInterface.php)

### El proveïdor d'usuaris

El proveïdor d'usuaris és l'element del sistema on s'emmagatzemaran les dades dels usuaris.

En el nostre cas serà en la base de dades mitjantçant la classe `User` per la qual cosa haurem d'establir la següent configuració:

```yaml
# config/packages/security.yaml
    # ...

    providers:
        users:
            entity:
                # the class of the entity that represents users
                class: 'App\Entity\User'
                # the property to query by - e.g. username, email, etc
                property: 'username'
                # optional: if you're using multiple Doctrine entity
                # managers, this option defines which one to use
                # manager_name: 'customer'

    # ...
```

La secció proveïdors crea un "proveïdor d'usuaris" anomenat `users`que sap fer una consulta des de la propietat del `username` de la vostra entitat `App\Entity\User`. Podeu triar qualsevol nom per al proveïdor d'usuari, però es recomana triar un nom descriptiu perquè posteriorment s'utilitzarà en la configuració del tallafoc.

Per a més informació llegiu [Security User Providers](https://symfony.com/doc/current/security/user_provider.html#entity-user-provider)

## Pas 3. Password hashing

No totes les aplicacions tenen "usuaris" que necessiten contrasenya. Si els usuaris tenen contrasenyes, podeu controlar com es codifiquen aquestes contrasenyes a `security.yaml`. L'orde `make:user` configurarà prèviament aquesta opció:

Ara que Symfony sap com voleu codificar les contrasenyes, podeu utilitzar el servei `UserPasswordEncoderInterface` per a fer-ho abans de desar els usuaris a la base de dades.

```yaml
# config/packages/security.yaml
security:
    # ...

    encoders:
        # use your user class name here
        App\Entity\User:
            # Use native password encoder
            # This value auto-selects the best possible hashing algorithm
            # (i.e. Sodium when available).
            algorithm: auto
```

## Pas 4. El proveïdor d'autenticació 

### Firewalls
El sistema de seguretat es configura en  `config/packages/security.yaml`. La secció més important són els tallafocs (_firewalls_):

```yaml
# config/packages/security.yaml
security:
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            anonymous: lazy
```
Un "tallafoc" és un sistema d'autenticació: la configuració que hi ha a continuació defineix com els usuaris podran autenticar-se (per exemple, form_login, API token, etc.).

A cada sol·licitud només hi ha un tallafoc actiu: Symfony utilitza la clau `pattern` per trobar la primera coincidència (també podeu coincidir per host o per altres coses). El tallafocs `dev` és realment un fals tallafoc: només s’assegura que no bloquegeu accidentalment les eines de desenvolupament de Symfony, que viuen amb URL com `/_profiler` i `/_wdt`.

Tots les URL reals són gestionades pel tallafoc `main` (cap clau `pattern` significa que coincidisca en totes les URL). Però això no significa que cada URL requerisca autenticació. No, gràcies a la clau `anonymous`, aquest tallafoc és accessible de forma anònima.

De fet, si aneu a la pàgina principal ara mateix, tindreu accés i veureu que esteu "autenticats" com a anonims. No us deixeu enganyar pel "Sí" que hi ha al costat d'Autentificat. El tallafoc ha comprovat que desconeix la vostra identitat i, per tant, eres anònim.

### Proveïdors d'autenticació ###

Una vegada enllestida la classe `User` caldrà definir quin proveïdor d'autenticació utilitzarem, en el nostre cas usarem [form_login](https://symfony.com/doc/current/security/form_login.html) que incorpora Symfony. Més informació sobre [proveïdors d'autenticació](https://symfony.com/doc/current/security/auth_providers.html) 

{:.alert .alert-activity}
<div markdown="1">
### Implementació de form_login ###
Seguint les instruccions de Using the [form_login Authentication Provider](https://symfony.com/doc/current/security/form_login.html) implementa el proveïdor d'autenticació _form_login_
</div>

No t'oblides d'indicar també al _firewall_ `main` quin serà el proveïdor d'usuaris mitjançant la clau `provider: users` ja que `users` és el nom que li hem posat al nostre proveïdor d'usuaris.


{:.alert .alert-info}
<div markdown="1">
### _Guard Authenticators_ ###
Un autenticador Guard és una classe que proporciona un control complet sobre el procés d’autenticació. Aquest concepte s'excedeix de l'àmbit del curs.
</div>

## Pas 5. Formulari de registre 

Symfony ens proporciona el comandament `make:registration-form` per poder disposar d'un formulari de registre.

{:.alert .alert-activity }
<div markdown="1">
### Formulari de registre

Crea mitjançant `make:registration-form` el formulari de registre. Comprova que funciona. Si falten camps pots personalitzar la classe `App\Form\RegisterFormType.php`

Crea l'usuari `admin` amb contrasenya `admin`.
</div>

## Pas 6. Negar accés, rols i altres formes d'autorització

Els usuaris ja poden iniciar sessió a l'aplicació mitjançant el formulari d'inici de sessió. Genial! Ara, heu d’aprendre a restringir accés i a treballar amb l’objecte User. Això s’anomena autorització, i la seva tasca és decidir si un usuari pot accedir a algun recurs (un URL, un objecte model, una trucada al mètode, ...).

Hi ha dues formes de restringir l'accés a algun recurs:

 * access_control a security.yaml vos permet protegir els patrons d’URL (per exemple, /admin/*). Més senzill, però menys flexible.
 * Al controlador (o un altre codi).

### security.access_control

En l'exemple següent per poder accedir a una ruta que comence per `/admin` caldrà que l'usuari tinga el rol ROLE_ADMIN.

```yaml
# config/packages/security.yaml
security:
    # ...

    firewalls:
        # ...
        main:
            # ...

    access_control:
        # require ROLE_ADMIN for /admin*
        - { path: '^/admin', roles: ROLE_ADMIN }

        # the 'path' value can be any valid regular expression
        # (this one will match URLs like /api/post/7298 and /api/comment/528491)
        - { path: ^/api/(post|comment)/\d+$, roles: ROLE_USER }
```

### Control d'accés per controlador o mètodes

You can deny access from inside a controller:

```php
// src/Controller/AdminController.php
// ...

public function adminDashboard()
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    // or add an optional message - seen by developers
    $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
}
```

That's it! If access is not granted, a special AccessDeniedException is thrown and no more code in your controller is executed. Then, one of two things will happen:

 1. If the user isn't logged in yet, they will be asked to log in (e.g. redirected to the login page).
 2. If the user is logged in, but does not have the `ROLE_ADMIN` role, they'll be shown the 403 access denied page (which you can customize).

Thanks to the SensioFrameworkExtraBundle, you can also secure your controller using annotations:

```php
// src/Controller/AdminController.php
// ...

 use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

 /**
  * Require ROLE_ADMIN for *every* controller method in this class.
  *
  * @IsGranted("ROLE_ADMIN")
  */
class AdminController extends AbstractController
{
     /**
      * Require ROLE_ADMIN for only this controller method.
      *
      * @IsGranted("ROLE_ADMIN")
      */
    public function adminDashboard()
    {
        // ...
    }
}
```
### Access Control in Templates ###

If you want to check if the current user has a certain role, you can use the built-in `is_granted()` helper function in any Twig template:

{% raw %}
```ruby
{% if is_granted('ROLE_ADMIN') %}
    <a href="...">Delete</a>
{% endif %}
```
{% endraw %}

{:.alert .alert-activity}
<div markdown="1">
### Restriccions d'accés

Configura l'aplicació de forma que per poder crear, llegir, actualitzar i eliminar usuaris calga que l'usuari autenticat tinga el rol `ROLE_ADMIN`.
</div>

## Pas 7. Tancar sessió ##

Seguirem les següents indicacions per a implementar el [tancament de la sessió (_logout_)](https://symfony.com/doc/current/security.html#logging-out)


# Recursos

 * [Security Guide](https://symfony.com/doc/current/security.html)
 * [Security Component](https://symfony.com/doc/current/components/security.html)
 * [Security Best Practices](https://symfony.com/doc/current/best_practices.html#security)

