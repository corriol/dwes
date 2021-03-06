---
layout: default
title: 2. Creant els CRUD
nav_order: 2
parent: 12. Tutorial de Symfony
has_children: false 
nav_exclude: true
---

# CRUD i assets
{: .no_toc }


## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

## CRUD
En [The Symfony MakerBundle](https://symfony.com/doc/current/bundles/SymfonyMakerBundle/index.html) trobarem com el comandament que permet crear els mètodes CRUD (*create*, *read*, *update*, *delete*).

Per a crear els mètodes de les nostres entitats executarem:

```shell
 php bin/console make:crud

 The class name of the entity to create CRUD (e.g. TinyElephant):
 > Link

 created: src/Controller/LinkController.php
 created: src/Form/LinkType.php
 created: templates/link/_delete_form.html.twig
 created: templates/link/_form.html.twig
 created: templates/link/edit.html.twig
 created: templates/link/index.html.twig
 created: templates/link/new.html.twig
 created: templates/link/show.html.twig

  Success! 

 Next: Check your new CRUD by going to /link/
```

Com es pot observar ens crearà el controlador, el formulari i les plantilles.

{:.alert .alert-info}
Les plantilles que comencen amb `_` són blocs de codi que s'insertaran dintre d'altres plantilles.

El fitxer src/Controller/LinkController quedarà així:

```php
<?php
namespace App\Controller;
use ...

/**
 * @Route("/link")
 */
class LinkController extends AbstractController
{
    /**
     * @Route("/", name="link_index", methods={"GET"})
     */
    public function index(): Response
    {
        $links = $this->getDoctrine()
            ->getRepository(Link::class)
            ->findAll();

        return $this->render('link/index.html.twig', [
            'links' => $links,
        ]);
    }

    /**
     * @Route("/new", name="link_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('link_indhttps://github.com/corriol/dwes/blob/master/UD11/resources/_header.html.twigex');
        }

        return $this->render('link/new.html.twig', [
            'link' => $link,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="link_show", methods={"GET"})
     */
    public function show(Link $link): Response
    {
        return $this->render('link/show.html.twig', [
            'link' => $link,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="link_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Link $link): Response
    {
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('link_index');
        }

        return $this->render('link/edit.html.twig', [
            'link' => $link,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="link_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Link $link): Response
    {
        if ($this->isCsrfTokenValid('delete'.$link->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($link);
            $entityManager->flush();
        }

        return $this->redirectToRoute('link_index');
    }
}

```

{:.alert .alert-activity}
Crea el controlador CRUD de l'entitat User.

## Estils i javascript

La forma més senzilla d'incloure fitxers css i js al nostre projecte és modificant la plantilla `base.html.twig` perquè els incloga directament des de la web.

### _style.html.twig ###

```css
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
```

També inclourem el menú superior. 

{: .alert .alert-activity }
Inclou els fitxers [`_style.html.twig`](https://github.com/corriol/dwes/blob/master/UD11/resources/_style.html.twig) i [`_header.html.twig`](https://github.com/corriol/dwes/blob/master/UD11/resources/_header.html.twig) al projecte. Comprova que tot funciona bé.


{: .alert .alert-warning }
Hem optat per una forma senzilla d'incloure CSS i javascript al nostre projecte, però dista molt de ser la més recomanable. Més endavant en endinsarem en la gestió de paquets per al frontend de la mà de [Webpack Encore](https://symfony.com/doc/current/frontend.html).


### Activant bootstrap 4 als formularis

Per a millorar l'aspecte dels formularis Symfony incorpora suport per a Bootstrap.

Modifica el fitxer `twig.yaml` perquè quede de la següent manera:

```yaml
# config/packages/twig.yaml
twig:
    form_themes: ['bootstrap_4_layout.html.twig']
```








