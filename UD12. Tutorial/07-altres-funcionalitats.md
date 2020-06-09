---
layout: default
title: 7. Altres funcionalitats 
nav_order: 7
parent: 12. Tutorial de Symfony
has_children: false 

---

# Funcionalitats extra
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}


## Pujar arxius

El que recomana Symfony és treballar amb el component [VichUploader](https://github.com/dustin10/VichUploaderBundle)

{:.alert .alert-activity}
<div markdown="1">
### Implementació carregador d'imatges
{: .no_toc .nocount }

Implementa un nou atribut en la classe `Link` anomenat `imageName` que emmagatzemarà el nom de la imatge. Les imatges es guardaran en `public/images/screenshot`.
</div>

### Redimensionar imatges

Per a redimensionar les imatges podem usar el component [LiipImagineBundle](https://symfony.com/doc/2.0/bundles/LiipImagineBundle/index.html).

Seguint el següent tutorial: [Basic Usage](https://symfony.com/doc/2.0/bundles/LiipImagineBundle/basic-usage.html) crearem el grup de filtres `my_thumb` que realitza una miniatura de la imatge afegint-li una vora de color negre.

El funcionament és senzill:

1. Es crea un un grup de filtres, `my_thumb`, en l'exemple, on s'indiquen els filtres que s'aplicaran, `thumbnail` i `background` en l'exemple.
2. Després s'utilitza el filtre en les plantilles de Twig:

{% raw %}    
```ruby
<img src="{{ asset('/relative/path/to/image.jpg') | imagine_filter('my_thumb') }}" /> 
```
{% endraw %}

Combinant-ho amb VichUploader la plantilla quedaria així:

{% raw %}
```ruby
<img  src="{{ vich_uploader_asset(link, 'imageFile') | imagine_filter('my_thumb') }}" 
    alt="{{ link.title }}" />
```
{% endraw %}

En els formularis on s'usa VichUploader s'aplica de la següent forma:

```php
    ->add('imageFile', VichImageType::class, [
        'required' => false,
        'allow_delete' => true,
        'asset_helper' => true,
        'imagine_pattern' => 'my_thumb']);
```

El que fa `LiipImagine` és crear en un directori cache els resultats d'aplicar els filtres.

## Enviament de correu

Symfony provides a mailer feature based on the popular Swift Mailer library via the SwiftMailerBundle. This mailer supports sending messages with your own mail servers as well as using popular email providers like Mandrill, SendGrid, and Amazon SES.

Més informació: [Swift Mailer](https://symfony.com/doc/current/email.html)

{:.alert .alert-activity}
<div markdown="1">
### Implementació d'un formulari de contacte.

Implementa un formuari de contacte amb els camps nom, correu electrònic, assumpte i text que envie la informació per correu electrònic a l'adreça del administrador de la web.
</div>

## Frontend: Javascript i CSS

Symfony incorpora una llibreria Javascript - anomenada Webpack Encore - que fa que treballar amb CSS i JavaScript siga pura joia. Pots usar-la o no. També pots crear CSS static i arxius JS en el directori `/public` i després incloure'l en les plantilles.

**Webpack Encore** és una forma senzilla d'integrar Webpack en la teua aplicació. Envolta Webpack, que us proporciona una API neta i potent per agrupar mòduls JavaScript, pre-processar CSS i JS i recopilar i minificar actius (_assets_). Encore us ofereix un sistema d’actius professionals que és una delícia d’utilitzar.

Encore s’inspira en Webpacker i Mix, però es manté en l’esperit de Webpack: utilitzant les seves característiques, conceptes i convencions de denominació per a una sensació familiar. Té com a objectiu resoldre els casos d’ús de Webpack més comuns.

{:.alert .alert-info}
<div markdown="1">
### webpack

webpack és un empaquetador de mòduls. El seu objectiu principal és agrupar fitxers JavaScript per al seu ús en un navegador, però també és capaç de transformar, agrupar o empaquetar gairebé qualsevol recurs o actiu.
</div>

{:.alert .alert-warning}
<div markdown="1">
### Node.js i yarn

Cal instal·lar `nodejs` i [yarn](https://classic.yarnpkg.com/en/docs/install/#debian-stable) per poder usar Webpack Encore.

Node.js és un entorn de programació dissenyat per escriure aplicacions web escalables basat en javascript i yarn és un gestor de paquets semblant a composer però orientat al frontend.
</div>
Més informació: [Managing CSS and JavaScript](https://symfony.com/doc/current/frontend.html)

## Internatizonalització

La guia per internacionalitzar aplicacions de Symfony la trobareu en [Translations](https://symfony.com/doc/current/translation.html).
