---
layout: default
nav_order: 11
parent: 11. Introducció a Symfony
title: 11. Relacions molts a molts
has_children: false 
nav_exclude: false
---

# Relacions molts a molts
{:no_toc}

1. Taula de continguts
{:toc}

## Introducció

Fins ara hem treballat amb relacions senzilles *ManyToOne*, molts a una. En la següent 
sessió anem a implementar una relació molts a molts (*ManyToMany*) en Doctrine.

En el nostre cas volem que els usuaris de l'aplicació de 
pel·lícules puguen valorar-les de 1 a 5. Així, per reflectir la nova estructura
de dades necessitarem crear una relació N:N entre usuaris i pel·lícules.

<div markdown="1" class="alert-info alert">
Si a banda de les claus alienes, en la nova taula volem afegir nous camps
caldrà, des del punt de vista de Doctrine, convertir la relació N:N en dues 
relacions N:1 amb la taula central de la relació.
</div>

Així doncs, crearem una nova entitat `Rating` que a banda de les claus alienes
contindrà el camp `value` de tipus enter que emmagatzemarà la valoració
de l'usuari per a una pel·lícula determinada.

## Entitat Rating

Creem la entitat `Rating`:

```
php bin/console make:entity

 Class name of the entity to create or update (e.g. VictoriousPizza):
 > Rating

 created: src/Entity/Rating.php
 created: src/Repository/RatingRepository.php
 
 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > user

 Field type (enter ? to see all types) [string]:
 > relation

 What class should this entity be related to?:
 > User

What type of relationship is this?
 ------------ ------------------------------------------------------------------ 
  Type         Description                                                       
 ------------ ------------------------------------------------------------------ 
  ManyToOne    Each Rating relates to (has) one User.                            
               Each User can relate to (can have) many Rating objects            
                                                                                 
  OneToMany    Each Rating can relate to (can have) many User objects.           
               Each User relates to (has) one Rating                             
                                                                                 
  ManyToMany   Each Rating can relate to (can have) many User objects.           
               Each User can also relate to (can also have) many Rating objects  
                                                                                 
  OneToOne     Each Rating relates to (has) exactly one User.                    
               Each User also relates to (has) exactly one Rating.               
 ------------ ------------------------------------------------------------------ 

 Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:
 > ManyToOne

 Is the Rating.user property allowed to be null (nullable)? (yes/no) [yes]:
 > no

 Do you want to add a new property to User so that you can access/update 
 Rating objects from it - e.g. $user->getRatings()? (yes/no) [yes]:
 > yes

 A new property will also be added to the User class so that you can access the 
  related Rating objects from it.

 New field name inside User [ratings]:
 > ratings

 Do you want to activate orphanRemoval on your relationship?
 A Rating is "orphaned" when it is removed from its related User.
 e.g. $user->removeRating($rating)
 
 NOTE: If a Rating may *change* from one User to another, answer "no".

 Do you want to automatically delete orphaned App\Entity\Rating objects 
 (orphanRemoval)? (yes/no) [no]:
 > no

 updated: src/Entity/Rating.php
 updated: src/Entity/User.php

 Add another property? Enter the property name (or press <return> to stop 
  adding fields):
 > movie

 Field type (enter ? to see all types) [string]:
 > ManyToOne

 What class should this entity be related to?:
 > Movie

 Is the Rating.movie property allowed to be null (nullable)? (yes/no) [yes]:
 > no

 Do you want to add a new property to Movie so that you can access/update 
 Rating objects from it - e.g. $movie->getRatings()? (yes/no) [yes]:
 > yes

 A new property will also be added to the Movie class so that you can access 
 the related Rating objects from it.

 New field name inside Movie [ratings]:
 > ratings

 Do you want to activate orphanRemoval on your relationship?
 A Rating is "orphaned" when it is removed from its related Movie.
 e.g. $movie->removeRating($rating)
 
 NOTE: If a Rating may *change* from one Movie to another, answer "no".

 Do you want to automatically delete orphaned App\Entity\Rating objects 
  (orphanRemoval)? (yes/no) [no]:
 > no

 updated: src/Entity/Rating.php
 updated: src/Entity/Movie.php

 Add another property? Enter the property name (or press <return> to stop 
  adding fields):
 > value

 Field type (enter ? to see all types) [string]:
 > integer

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Rating.php

 Add another property? Enter the property name (or press <return> to stop 
  adding fields):
 > 
           
  Success! 
```

La nova entitat Rating serà així:

```php
namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RatingRepository::class)
 */
class Rating
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ratings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="ratings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
```

Destacar l'argument `inversedBy="ratings"` que indica quin atribut
de la classe relacionada, Movie en l'exemple, podrem usar per tindre 
totes les valoracions dels usuaris.

Ara caldrà fer les migracions:

```php
public function up(Schema $schema) : void
{
    // this up() migration is auto-generated, please modify it to your needs
    $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, 
        movie_id INT NOT NULL, value INT NOT NULL, INDEX IDX_D8892622A76ED395 (user_id), 
        INDEX IDX_D88926228F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 
        COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE rating 
        ADD CONSTRAINT FK_D8892622A76ED395 
        FOREIGN KEY (user_id) REFERENCES user (id)');
    $this->addSql('ALTER TABLE rating 
        ADD CONSTRAINT FK_D88926228F93B6FC 
        FOREIGN KEY (movie_id) REFERENCES movie (id)');
}
```
A banda d'això caldrà afegir una clau única sobre els atributs *movie* i *user* perquè
restringir que un usuari no puga valorar dues vegades la mateixa pel·lícula.

```php
/*
 * @Table(name="rating",
 *      uniqueConstraints={
 *          @UniqueConstraint(name="IDX_USER_MOVIE_UNIQUE",
 *                  columns={"user_id", "movie_id"}
 *          )
 *      }
 * )
 */
```

Si observem les referències es realitzen als camps de la taula ja que
es defineixen sobre la taula relacionada amb l'entitat.

Ara crearem el formulari bàsic per permetre als usuaris valorar pel·lícules. 
Aquest formulari estarà en la pàgina de detall de la pel·lícula.

```php
// src/Form/RatingType.php
class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', ChoiceType::class,
                [   'label'=>false, 
                    'choices' => [1 => 1, 2=>2 , 3=>3, 4=>4, 5=>5],
                    'placeholder'=>'Select a value'
                ])
        ;
    }
   ...
}
```

En el formulari sols apareix l'atribut `value` perquè l'usuari i la pel·lícula
els obtindrem internament.

```php
 /**
     * @Route("/movies/{id}", name="movies_show", requirements={"id"="\d+"})
     */
    public function show(Movie $movie, Request $request)
    {
        $user = $this->getUser();

        $ratingRepository = $this->getDoctrine()->getRepository(Rating::class);
        // we find a related rating
        $ratings = $ratingRepository->findBy(["movie"=>$movie, "user"=>$user]);

        // if not we created a new one
        if (empty($ratings)) {
            $rating = new Rating();
            $rating->setMovie($movie);
            $rating->setUser($user);
        }
        // if yes we extract the first element from the returned array
        else
            $rating = $ratings[0];


        $form = $this->createForm(RatingType::class, $rating);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rating);
            $entityManager->flush();

            $this->addFlash('success', 'Your rating has been saved!');
        }

        if ($movie) {
            return $this->render('movies_show.html.twig',
                [
                    'movie' => $movie,
                    'form' => $form->createView()
                ]
            );
        }
    }
    ...
}
```
El que fem primer es veure si ja existeix una valoració de l'usuari d'eixa
pel·lícula, en cas d'existir la recuperem i si no hi ha en creem una. Amb eixa
informació generem el formulari:
{% raw %}
```twig
{% if app.user %}
    {{ form_start(form, {attr: {class: 'form-inline'}}) }}
    {{ form_widget(form) }}
        <button type="submit" class="btn btn-primary">Rate!</button>
    {{ form_end(form) }}
{% endif %}
```
{% endraw %}
El formulari té la classe `form-inline` perquè aparega tot en una línia.

### Calcular la valoració per pel·lícula

El càlcul de la mitjana de les valoracions el realitzarem mitjançant un camp calculat,
és a dir, no el tindrem en la base da dades sinó que el calcularem quan siga cridat.

Ho farem en l'entitat `Movie`, afegint el mètode `getRating()`.

```php
 public function getRating(): ?float {
        $sum = 0;
        foreach ($this->getRatings() as $rating) {
            $sum += $rating->getValue();
        }
        $count = count($this->getRatings());
        if (empty($count))
            return null;
        return ($sum/$count);
    }
```

Així podrem mostrar la valoració de cada pel·lícula en Twig indicat la propietat `rating`.

{% raw %}
```twig
<p class="text-muted">Rating: {{ movie.rating }}</p>
```
{% endraw %}
