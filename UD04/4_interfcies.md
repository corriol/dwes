<div id="content">

[Omet navegació](#main)

<div id="emptyHeader" class="section">

</div>

  - [4. PHP - Classes i objectes](index.html)
  - [1. Introducció](1_introducci.html)
  - [2. Característiques bàsiques de la utilització d'objectes en
    PHP](2_caracterstiques_bsiques_de_la_utilitzaci_dobjectes_en_php.html)
      - [1. Definició de classes](1_definici_de_classes.html)
          - [Constructor i destructor](constructor_i_destructor.html)
      - [2. Utilització d'objectes](2_utilitzaci_dobjectes.html)
  - [3. Herència](3_herncia.html)
      - [Exercicis pràctics](exercicis_prctics.html)
  - <span id="active">[4. Interfícies](4_interfcies.html)</span>
      - [Exercicis pràctics](exercicis_prctics0.html)
  - [5. Traits](5_traits.html)
  - [6. Gestió d'errors i excepcions](6_gesti_derrors_i_excepcions.html)
      - [6.1. Excepcions](61_excepcions.html)
          - [Ampliant les excepcions](ampliant_les_excepcions.html)
      - [Exercicis pràctics](exercicis_prctics1.html)
  - [Crèdits, fonts i bibliografia](crdits_fonts_i_bibliografia.html)

<div id="topPagination">

[<span><span>« </span>Anterior</span>](exercicis_prctics.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](exercicis_prctics0.html)

</div>

<div id="main-wrapper">

<div id="main" class="section">

# 4\. Interfícies

<div class="iDevice emphasis0">

<div id="ta10_85" class="block iDevice_content">

Un interfície (*interface*) és com una classe buida que solament conté
declaracions de mètodes. Es defineixen utilitzant la paraula interface. 
Per exemple, abans vas veure que podies crear noves classes heretades de
Producte, com TV o Ordinador. També vas veure que en les subclasses
podies redefinir el comportament del mètode perquè generara una eixida
en HTML diferent per a cada tipus de producte.

Si vols assegurar-te que tots els tipus de productes tinguen un mètode
`mostra`, pots crear un interface com el següent.

<div class="highlighted-code language-php">

<div>

    interface IMostra {
        public function mostra();
    }

</div>

</div>

I quan crees les subclasses hauràs d'indicar amb la paraula `implements`
que han de implementar els mètodes declarats en aquest interface. 

<div class="highlighted-code language-php">

<div>

    class TV extends Producte implements IMostra {
            …
            public function mostra() {
                print "<p>" . $this->polzades . " polzades</p>";
            }
            …
    }

</div>

</div>

Tots els mètodes que es declaren en un `interface` han de ser públics. A
més de mètodes, els interfícies podran contenir constants però no
atributs.

Un interface és com un contracte que la classe ha de complir. En
implementar tots els mètodes declarats en la interfície s'assegura la
interoperabilitat entre classes. Si saps que una classe implementa un
interfície determinada saps quins noms tenen els seus mètodes, quins
paràmetres els has de passar i, probablement, podràs esbrinar fàcilment
amb quins objectiu han sigut escrits.

Per exemple, en la llibreria de PHP està definit la interfície
`countable`.

<div class="highlighted-code language-php">

<div>

    Countable {
        abstract public int count ( void )
    }

</div>

</div>

Si crees una classe per a la cistella de la compra en la tenda web,
podries implementar aquesta interfície per a contar els productes que
figuren en la mateixa.

Abans vas aprendre que en PHP5 una classe només pot heretar d'una altra.
En PHP5 no existeix l'herència múltiple. No obstant açò, sí és possible
crear classes que implementen diverses interfícies, simplement separant
la llista d'interfícies per comes després de la paraula `implements`.

<div class="highlighted-code language-php">

<div>

    class TV extends Producte implements IMostra, Countable {
         …
    }

</div>

</div>

L'única restricció és que els noms dels mètodes que s'hagen
d'implementar en els diferents interfícies no coincidisquen. És a dir,
en el nostre exemple, la interfície `IMostra` no podria contenir el
mètode `count`, doncs aquest ja està declarat en `Countable`.

En PHP5 també es poden crear nous interfícies heretant d'uns altres ja
existents. Es fa de la mateixa forma que amb les classes, utilitzant la
paraula `extends`.

Una dels dubtes més comuns en POO, és quina solució adoptar en algunes
situacions: interfícies o classes abstractes. Ambdues permeten definir
regles per a les classes que els implementen o hereten respectivament. I
cap permet instanciar objectes. Les diferències principals entre ambdues
opcions són:

  - En les classes abstractes, els mètodes poden contenir codi. Si van a
    existir diverses subclasses amb un comportament comú, es podria
    programar en els mètodes de la classe abstracta. Si s'opta per un
    interface, caldria repetir el codi en totes les classes que ho
    implemente.
  - Les classes abstractes poden contenir atributs, i els interfícies
    no.
  - No es pot crear una classe que herete de dues classes abstractes,
    però sí es pot crear una classe que implemente diverses interfícies.

Per exemple, en la botiga *online* va a haver-hi dos tipus d'usuaris:
clients i empleats. Si necessites crear en la teua aplicació objectes de
tipus Usuari  (per exemple, per a manejar de forma conjunta a els
clients i a els empleats), hauries de crear una classe no abstracta amb
aqueix nom, de la qual heretarien Client i Empleat.

`  ``  `

<div class="highlighted-code language-php">

<div>

    class Usuari {
        ...
    }
    class Client extends Usuari {
        ...
    }
    class Empleat extends Usuari {
        ...
    }

</div>

</div>

Però si no fóra així, hauries de decidir si crees o no Usuari, i si ho
faries com una classe abstracta o com una interfície.

Si per exemple, volgueres definir en un únic lloc els atributs comuns a
Client i a Empleat, hauries de crear una classe abstracta Usuari de la
qual hereten.

<div class="highlighted-code language-php">

<div>

    abstract class Usuari {
        public $dni;
    
        protected $nom;
        ...
    }

</div>

</div>

Però açò no podries fer-ho si ja tens planificada alguna relació
d'herència per a una d'aquestes dues classes.

Per a finalitzar amb els interfícies, a la llista de funcions de PHP
relacionades amb la POO pots afegir les
següents.

| Funció                     | Exemple                                  | Significat                                                                  |
| -------------------------- | ---------------------------------------- | --------------------------------------------------------------------------- |
| get\_declared\_interfícies | print\_r (get\_declared\_interfícies()); | Retorna un array amb els noms dels interfícies declarats.                   |
| interface\_exists          | if (interface\_exists('iMuestra') { … }  | Retorna true si existeix l'interface que s'indica, o false en cas contrari. |

</div>

</div>

<div id="packageLicense" class="cc cc-by-sa">

<span>Llicenciat sota la </span> [Llicència Creative Commons
Reconeixement
CompartirIgual 4.0](http://creativecommons.org/licenses/by-sa/4.0/)

</div>

</div>

</div>

<div id="bottomPagination">

[<span><span>« </span>Anterior</span>](exercicis_prctics.html)
<span class="sep">| </span>[<span>Següent<span>
»</span></span>](exercicis_prctics0.html)

</div>

</div>
