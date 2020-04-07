---
layout: default
title: 5. Traits
parent: 4. Classes i objectes
nav_order: 4
has_children: false
---

# Interfícies
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}


Des de la seva versió 5.4.0, PHP implementa una metodologia de
reutilització de codi anomenada Traits.

Els trets ( «traits» en anglès) són un mecanisme de reutilització de
codi en llenguatges d'herència simple, com PHP. L'objectiu d'un tret és
el de reduir les limitacions pròpies de l'herència simple permetent que
els desenvolupadors reutilitzen a voluntat conjunts de mètodes sobre
diverses classes independents i pertanyents a classes jeràrquiques
diferents. La semàntica a l'hora combinar Traits i classes es defineix
de tal manera que redueixe la seva complexitat i s'eviten els problemes
típics associats a l'herència múltiple i als
[mixins](https://es.wikipedia.org/wiki/Mixin).

Un Trait és similar a una classe, però amb l'únic objectiu d'agrupar
funcionalitats molt específiques i d'una manera coherent. No es pot
instanciar directament un Trait. És per tant un afegit a l'herència
tradicional, i habilita la composició horitzontal de comportaments; és a
dir, permet combinar membres de classes sense haver d'usar herència.

```php

    <?php
    class Base {
        public function decirHola() {
            echo '¡Hola ';
        }
    }
    
    trait DecirMundo {
        public function decirHola() {
            parent::decirHola();
            echo 'Mundo!';
        }
    }
    
    class MiHolaMundo extends Base {
        use DecirMundo;
    }
    
    $o = new MiHolaMundo();
    $o->decirHola(); // Hola Mundo
    ?>

```