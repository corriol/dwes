---
layout: default
nav_order: 10
parent: 11. Introducció a Symfony
has_children: false 
nav_exclude: false
---

# Gestió de la pujada de fitxers
{:no_toc}

1. Taula de continguts
{:toc}


En el nostre projecte de pel·lícules tenim definida la propistat pòster que 
emmagatzema el nom del fitxer del pòster d'una pel·lícula, 
en la següent sessió anem a implementar la gestió de fitxers sense utilitzar
ningun _bunble_ de tercer i poder emmagatzemar i mostrar els posters.

En la següent sessió aprendràs a:
* Usar i gestionar les pujades de fitxers.
* Crear missatges _flash_.
* Crear paràmetres globals de la aplicació.


## La classe FileType

Per a generar el component de pujada de fitxers d'HTML5 Symfony disposa de la
classe `FileType`, que cal implementar en el formularis. Com que el camp 
no existirà en l'entitat, ja que en l'entitat emmagatzenem sols el nom del fiter
cal indicar que serà un camp _unmapped_ perquè Symfony no intente enllaçar-lo.

```php
    ->add('poster', TextType::class,
                ['disabled' => true,
                 'required' => false])
    ->add('posterFile', FileType::class, [
        'label' => 'Poster (image file)',
        'required' => false,
        'mapped' => false,
        'constraints' => [
            new Image(['maxSize' => '1024k',
                'mimeTypes' => [
                    'image/jpeg',
                    'image/jpg',
                ],])
            ],
```

Com es pot observar hi ha dos camps que fan referència al pòster:

1. `poster` de tipus `TextType` que representa la propietat de la classe `Movie`, hem indicat que
estarà deshabilitat perquè s'emplenarà automàticament en el controlador.
Altra opció possible era definir-lo com a `HiddenType` però hem optat per un camp de text
de sols lectura perquè és la forma més senzill per mostar el valor en el formulari d'edició.

2. `posterFile` que serà el quadre de text que ens permetrà pujar el fitxer, en el nostre cas
una image menor de 1MB i en format `jpg`. Aquest camp té la propietat `mapped` igual a `false` perquè
no es troba en l'entitat.

<div markdown="1" class="alert alert-warning">
Com que en el formulari de creació l'atribut `poster` s'enviarà buit, hem de llevar 
la restricció de `Assert\NotNull` de l'atribut.
</div>

## Els controladors

Per a gestionar la pujada de fitxer cal afegir el següent codi:

```php
  /**
     * @Route("/movies/create", name="movies_create")
     */
    public function create(Request $request)
    {
        ...        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            if ($posterFile = $form['posterFile']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();
           
                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/images/posters/', $filename);
                    $movie->setPoster($filename);
                } catch (FileException $e) {
                    $this->addFlash(
                        'danger',
                        $e->getMessage()
                    );
                    return $this->redirectToRoute('admin');
                }
            }

            ...
            return $this->redirectToRoute('admin');
        }
        return $this->render('movies_create.html.twig', array(
            'form' => $form->createView()));
    }
```

Obtenim en $posterFile el fitxer pujat des del formulari, recorda que tenia el 
nom `posterFile`. Si existeix generem un nom aleatori amb la combinació de les
funcions `random_bytes` i `bin2hex`. Amb el mètode `guessExtension` obtenim 
l'extensió del fitxer.

Després obtenim el directori del projecte i afegim la ruta on volem guardar
la imatge en el sistema de fitxers. Amb el mètode `move` movem el fitxer 
de la seua ubicació temporal a la carpeta i el nom indicat.

Si tot ha funcionat correctament guardem el nom del fitxer a l'entitat
`$movie->setPoster($filename)`.

En cas d'haver una excepció escrivim un missatge _flash_ i redigirim al panell 
de control.


## Missatges flash

Els missatges flaix ja el coneixes, en Symfony, funcionen de la mateixa forma, s'escriuen 
en una variable de sessió i una vegada són llegits en la pàgina de destí desapareixen.

Per exemple:

```php
   $this->addFlash(
            'success',
            'Your changes were saved!'
        );
```
El primer paràmetre pots qualsevol nom. En el nostre cas, com que usem
_bootstrap_ és convenient que la etiqueta coincidisca amb les classes preestarblertes:
`success`, `danger`,`warning`, `info`, etc.

### Canvis en la plantilla base

Per a integrar-los en la plantilla base pots usar aquest codi:

{% raw %}
```twig
 {# read and display all flash messages #}
    {% for label, messages in app.flashes %}
        {% for message in messages %}
            <div class="alert alert-{{ label }} alert-dismissible fade show" role="alert">
                {{ message }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        {% endfor %}
    {% endfor %}
```
{% endraw %}

Com es pot observar la variable `label`, serà la determinarà la classe que s'aplicarà.

Més informació en [Flash Messages](https://symfony.com/doc/current/controller.html#id2)

## Paràmeters globals

Com ja hem vist en les sessions introductòries podem crear paràmetres
globlals per poder-los usar des de qualsevol lloc de l'aplicació.

Anem a aplicar-ho al directori on es guarden els posters. Definirem un paràmetre
`posters_directory` que indicarà quin és el directori.

```yaml
parameters:
    posters_directory: '%kernel.project_dir%/public/images/posters'
```

La variable d'entorn `%kernel.project_dir%` representa el directori on tenim
el nostre projecte Symfony. 

Ara modificarem el controlador perquè agafe la ruta del paràmetre:

```php
   ...
    try {
        $postersDir = $this->getParameter('posters_directory');
        $posterFile->move($postersDir, $filename);
        $movie->setPoster($filename);
    } catch (FileException $e) {
    ...

```
També podem crear paràmetres globals per a usar-los en les plantilles de Twig. Per exemple,
per a indicar la ruta pública dels pòsters:

```yaml
# config/packages/twig.yaml
twig:
    ...
    globals:
        posters_public_directory: 'images/posters/'
```

I l'usariem així:

{% raw %}
```twig
<img class="card-img-top" src="{{ asset(posters_public_directory ~  movie.poster) }}" 
    title="{{ movie.title }}"></a>
```
{% endraw %}

## Més informació

* [How to Upload Files](https://symfony.com/doc/current/controller/upload_file.html)
* [Step 14: Accepting Feedback with Forms, _Symfony The Fast Track_](https://symfony.com/doc/current/the-fast-track/en/14-form.html)
* [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle)
