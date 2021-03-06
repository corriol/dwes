---
layout: default
title: 1. El projecte en Symfony
nav_order: 1
parent: 12. Tutorial de Symfony
has_children: false 
nav_exclude: true
---


# Iniciant el projecte
{: .no_toc }


## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

## Creació del projecte Symfony
 1. Crea un nou projecte complet en la carpeta *link_manager* seguint les instruccions de [Installing & Setting up the Symfony Framework](https://symfony.com/doc/current/setup.html).
 2. Instal·la el binari `symfony` de forma global.
 3. Executa el servidor integrat de symfony.
 4. Comprova que s'ha instal·lat correctament.
 
## Configuració del repositori #
 1. Crea en github un repositori anomenat *link_manager* on guardaràs el projecte. 
 2. Comparteix-lo amb el professor.

Des de la carpeta del projecte executa:

```bash
git init
git add .
git commit -m "Fist commit"
git remote add origin URL_REPOSITORY
git push origin master
```
A partir d'ara en acabar el treballar hauràs d'actualitzar el repository.

## Configuració de la base de dades #

Seguint [Databases and the Doctrine ORM](https://symfony.com/doc/current/doctrine.html#configuring-the-database) configurarem la base de dades.

Per a establir la connexió amb la base de dades crear un fitxer `.env.local`. Aquest fitxer contindrà la configuració específica de l'entorn de treball local i s'inclou per defecte en el `.gitignore`.

Per a crear-lo executarem `touch .env.local` i l'editarem afegint:

```
APP_ENV=dev
# IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
```
Indicant que estem en un entorn de desenvolupament i la URL de la nostra base de dades.

En acabar, crea la base de dades:

```
php bin/console doctrine:database:create
```

{:.alert .alert-warning}
Si apareix l'error `An exception occurred in driver: could not find driver` és perque cal instal·lar el paquet `php-mysql`.

### Creació des les entitats
Seguint les instruccions de l'enllaç anterior crea les seguents entitats amb el comandament:

```
$php bin/console make:entity
```

![Diagrama UML](images/uml-link-manager.png)

Després de crear les entitats executarem:

    php bin/console make:migration

Que ens generarà un arxiu php amb els canvis generats per a la base de dades.

Amb `php bin/console doctrine:migrations:migrate` aplicarem els canvis.


### Emplenament de dades l'entitat Link

Per a emplenar de dades les entitats Symfony utilitza el component `doctrine/data-fixtures`.

L'instal·larem amb el seguent comandament:

```bash
composer require orm-fixtures --dev
```

Dos consideracions:

 * Com que disposem de Symfony Flex emprem la recepta `orm-fixtures` en lloc de `doctrine/data-fixtures`.
 * El paràmetre `--dev` indica que el component sols estarà disponible en l'entorn de desenvolupament.

Després executarem:

```bash
php bin/console make:fixtures
```

I indicarem el nom d'exemple.
```bash
The class name of the fixtures to create (e.g. AppFixtures):
 >> AppFixtures
```

Pots substituir el fitxer `AppFixtures.php` per aquest que ja inclou 3 enllaços: [AppFixtures](resources/AppFixtures.php).

Després executarem:

    php bin/console doctrine:fixtures:load


```shell
Careful, database "link_manager" will be purged. Do you want to continue? (yes/no) [no]:
 > yes

 > purging database
 > loading App\DataFixtures\AppFixtures
```

## Creació dels controladors 

Per a acabar crearem el nostre primer controlador i ho farem seguint les instruccions del següent enllaç: [Generating controllers](https://symfony.com/doc/current/controller.html#generating-controllers).

```bash
$php bin/console make:controller DefaultController

 created: src/Controller/LinkController.php
 created: templates/default/index.html.twig
           
  Success! 
           
 Next: Open your new controller class and add some pages!
```

Editarem la ruta del controlador perquè siga la pàgina d'inici de la nostra aplicació web.

Hauríem de veure alguna cosa semblant:

![Link controller](images/hello-link-controller.png)


## Obtenció de registres


Ara modificarem el métode `index()` de la següent forma:

```php
/**
 * @Route("/", name="homepage")
 */
public function index()
{
    // get the Link repository (it is like our model)
    $repository = $this->getDoctrine()->getRepository(Link::class);

    // retrieve all links
    $links = $repository->findAll();
        
    // now pass the array of link object to the view 
    return $this->render('default/index.html.twig', [
        'links' => $links,
    ]);
}
```

{:.alert .alert-info}
Ens quedarà modificar la plantilla de Twig, però en això ja teniu molta experiència!


Més informació: [Querying for objects](https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository)

## Recursos ##
Més informació: https://rogerdudler.github.io/git-guide/ 




