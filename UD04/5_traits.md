---
layout: default
title: 5. Traits
parent: 4. Classes i objectes
nav_order: 5
has_children: false
---

# Trets (Traits)
{: .no_toc }


Des de la seva versió 5.4.0, PHP implementa una metodologia de
reutilització de codi anomenada `Traits`.


Els trets («traits» en anglès) són un mecanisme de reutilització de
codi en llenguatges d'herència simple, com PHP. L'objectiu d'un tret és
el de reduir les limitacions pròpies de l'herència simple permetent que
els desenvolupadors reutilitzen a voluntat conjunts de mètodes sobre
diverses classes independents i pertanyents a classes jeràrquiques
diferents. 

Un Trait és similar a una classe, però amb l'únic objectiu d'agrupar
funcionalitats molt específiques i d'una manera coherent. No es pot
instanciar directament un `Trait`. És per tant un afegit a l'herència
tradicional, i habilita la composició horitzontal de comportaments; és a
dir, permet combinar membres de classes sense haver d'usar herència.

```php   
trait Saludar {
    function decirHola(){
        return "hola";
    }
}
trait Despedir {
    function decirAdios(){
        return "adiós";
    }
}
class Comunicacion {
    use Saludar, Despedir;
}
$comunicacion = new Comunicacion;
echo $comunicacion->decirHola() . ", que tal. " . $comunicacion->decirAdios();
```

En l'exemple anterior la classe `Comunicacion` necessita reutilitzar els mètodes `Saludar::decirHola()` i 
`Despedir::decirAdios()` com que en PHP no hi ha herència múltiple mitjançant els `trait` es pot aconseguir 
reutilitzar-les.  