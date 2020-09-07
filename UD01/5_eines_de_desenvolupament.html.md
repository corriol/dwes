---
layout: default
title: 5. Eines de desenvolupament
parent: 1. Introducció a la programació web
nav_order: 5
has_children: false
---

Per a programar en llenguatges encastats com PHP, ASP o Phython és
suficient amb un editor de text pla. No obstant sempre és recomanable
treballar en un entorn integrat de desenvolupament o IDE (acrònim en
anglès de *Integrated development environment*). Un IDE és una eina
informàtica per al desenvolupament de programari de manera còmoda i
ràpida. Així doncs és un entorn de desenvolupament que agrupa diferents
funcions en un sol programa, habitualment: editor de codi, compilador,
depurador i un programa de disseny d\'interfície gràfica.

El principal avantatge és que facilita la tasca del programador mentre
que l'inconvenient més important és que pot provocar mals hàbits a
l'hora de programar o provocar errors que a priori començant de zero no
es produirien.

Alguns dels IDE més emprats per al desenvolupament web són:

-   Eclipse
-   Netbeans
-   Atom
-   Sublime
-   PHPStorm
-   Brackets

Durant les activitats anteriors has preparat el teu propi entorn de
desenvolupament sobre una màquina virtual (o una real) tal com s\'ha
explicat en les anotacions. Després, has de crear un projecte php
utilitzant PHPStorm i visualitzar la pàgina principal a través del teu
servidor XAMPP.

{:.alert .alert-activity}
<div markdown="1">
{: .no_toc .nocount }
### Pràctica 2. Instal·lació de PHPStorm

Al llarg del curs, crearem els nostres projectes utilitzant l\'IDE
PHPStorm.

Aquesta eina és de pagament, però podem utilitzar-la de forma gratuïta
per a projectes OpenSource i amb finalitats educatives. En aqueixos
casos caldrà sol·licitar la llicència corresponent al fabricant del
programari. Una vegada obtinguda la llicència educativa pel professor,
serà renovable anualment i podran utilitzar-la tots els alumnes del
curs. És una eina molt completa que ens permetrà la integració amb
moltes de les funcionalitats que emprarem.

#### Instal·lar PHPStorm

Per a instal·lar l\'IDE PHPStorm seguirem els següents passos:

1.  Executarem\
    sudo snap install phpstorm \--classic
2.  Ens demanarà si volem importar dades d\'una instal·lació anterior,
    al que contestarem, òbviament, que no.
3.  Ens demanarà que acceptem la política de privadesa.
4.  En executar l\'aplicació per primera vegada ens demanarà les dades
    de llicència. Cada alumne s\'haurà de registrar utilitzant l\'enllaç
    que us passarà el professor en classe. en prémer l\'enllaç ens
    redirigirà a la pàgina web de jetbrains perquè ens registrem. Una
    vegada registrats, podrem accedir a l\'aplicació amb el nostre
    compte o amb el codi d\'activació que ens proporcionen.
5.  Finalment, indicarem la configuració inicial.

#### Integrant l\'executable de PHP

PHPStorm disposa d\'ajuda intel·ligent (IntelliJ IDEA). Perquè aquesta
ajuda es corresponga amb la versió de php que tenim en el nostre XAMPP
haurem de registrar l\'executable de PHP de XAMPP en el nostre PHPStorm.

Per a açò seguirem els següents passos:

1.  En la pantalla d\'inici anem a `Configure – Settings`.
2.  En la llista de categories de l\'esquerra anem a
    `Language & Frameworks - PHP`.
3.  Necessitem indicar quin és el nostre intèrpret. ja que la llista
    està buida anem a configurar un de la següent forma:
    1.  Fem clic en el botó \... de la llista d\'intèrprets
    2.  Premem el botó + de la cantonada superior esquerra per a
        afegir-ne un.
    3.  Li posem el nom PHP de XAMPP.
    4.  Indiquem instal·lat on nostre està XAMPP: `/opt/lampp/bin/php`
    5.  Immediatament ens indicarà la versió de l\'intèrpret que tenim
        instal·lat
    6.  Premem OK i seleccionem el nou interprete en la llista
        d\'intèrprets.
    7.  Finalment, seleccionem en l\'opció PHP *language level* aquell
        que es corresponga amb l\'intèrpret que acabem de configurar.
    8.  Ara el nostre PHPStorm farà ús del PHP de XAMPP i utilitzarà la
        versió de PHP que tenim instal·lada quan ens oferisca les
        ajudes.
    9.  Primer projecte amb PHPStorm Per a començar a treballar amb
        PHPStorm anem a crear el nostre primer projecte PHP buit.
        Tingueu en compte que el projecte heu de crear-ho en el
        directori `/opt/lampp/htdocs` i que necessitem tenir permisos
        d\'escriptura en aquest directori.

**Compte!** Pot ser que ho hàgem instal·lat com `root`, mentre que quan
executem PHPStorm ho fem amb l\'usuari `dwes`, per tant, l\'usuari
`dwes` necessita poder escriure en aquest directori.

Per a assegurar-nos que tenim permisos, anirem amb una finestra de
terminal al directori `/opt/lampp` i executarem la següent ordre:

`ls –la htdocs`

Aquesta ordre ens dirà qui és el propietari de la carpeta i quins
permisos té.

En el cas que el propietari siga root executarem el següent comando:

`sudo chown -hR dwes htdocs/`

Açò canviarà el propietari a l\'usuari `dwes`, amb el que tindrem permís
d\'escriptura en aquest directori des de PHPStorm.

Treballarem tenint els projectes en la mateixa carpeta.
</div>

{:.alert .alert-activity }
<div markdown="1">
{: .nocount .no_toc }
### Pràctica 3: Prova d\'Apache i PHP

L\'objectiu d\'aquesta pràctica és aprendre el maneig bàsic de l\'entorn
de desenvolupament PHPStorm creant una primera pàgina PHP que alhora
servirà per a comprovar la correcta instal·lació de XAMPP.

Per a aconseguir aquest objectiu, seguirem els passos que s\'indiquen a
continuació:

1. Arrancar XAMPP
2. Iniciar el mòdul Apache
3. Executar PHPStorm
4. Crear un projecte buit amb `Create New > Project PHP Empty Project`
5. Posar al projecte el nom `practica1` i situar-lo en el directori
`/opt/lampp/htdocs` . Amb açò es crea un directori de nom `practica1`
dintre de `/opt/lampp/htdocs`.
![](projecte.png)

6. En la finestra de l\'esquerra (explorador de projectes) apareix el
projecte creat. Dins d\'ell crearem un nou fitxer PHP prement botó dret
sobre el nom i seleccionant `New > PHP File` . Li posem de nom
`index.php` .

7. En el fitxer creat introduïm les següents línies de codi:
```php
    <!doctype html>
    <html lang="és">
    <head>
    <meta charset="utf-8">
    <title>Desenvolupament web en entorn servidor</title>
    <meta name="description" content="PHP, PHPStorm">
    <meta name="author" content="Homer Simpson">
    </head>
    <body>
    <?php
        phpinfo();
    ?>
    </body>
    </html>
```
8. Canviem l\'autor del document al nostre nom.
9. Obrim el navegador i teclegem en la barra d\'adreces la url:
<http://localhost/practica1>
10. Apareixerà una pàgina similar a la de la següent imatge:
![](phpinfo.png)
</div>