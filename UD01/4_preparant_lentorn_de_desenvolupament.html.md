---
layout: default
title: 4. Preparant l'entorn de desenvolupament
parent: 1. Introducció a la programació web
nav_order: 4
has_children: false
---
# Preparant l'entorn de desenvolupament
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}

Per a poder provar els nostres llocs web necessitarem instal·lar una
sèrie d\'eines com són: el servidor web Apache, el mòdul PHP per a
aquest servidor, el SGBD MySql, un front-end per al SGBD (PHPMyAdmin),
etc.

Per simplificar les coses optarem per instal·lar-ho mitjançant XAMPP, un
paquet de programari lliure que conté el servidor HTTP Apache, la base
de dades de MySQL i eines necessàries per utilitzar el PHP i el
llenguatge de programació Perl.

{:.alert .alert-activity}
<div markdown="1">
{:.no_toc .nocount}
### Pràctica 1: Instal·lació de XAMPP 

1.  Descarreguem el paquet XAMPP per a la nostra màquina
    (https://www.apachefriends.org/es/index.html).
2.  Li donem permisos d\'execució a l\'arxiu descarregat:\
    `sudo chmod +x xampp-linux-x64-7.3.9-0-installer.run`
3.  Executem l\'assistent d\'instal·lació\
    `sudo ./xampp-linux-x64-7.3.9-0-installer.run`
4.  En l\'assistent d\'instal·lació, deixarem les opcions per defecte
    (excepte la qual ens parla de bitnami que la desmarcarem).
5.  Una vegada finalitzada la instal·lació, el nostre XAMPP es trobarà
    en el directori `/opt/lampp`
6.  Ara ja podem arrancar els serveis amb el següent comando:\
    `sudo /opt/lampp/lampp start`
7.  Quan els arranquem, si obrim el navegador i ens anem a la url
    http:/localhost , ens hauria d\'aparèixer la pàgina de benvinguda de
    XAMPP.
8.  Per a parar els serveis podem fer açò:\
    `sudo /opt/lampp/xampp stop`
9.  En qualsevol cas, és molt més còmode treballar amb el panell de
    control de XAMPP que podrem executar amb el següent comando:\
    `sudo /opt/lampp/manager-linux-x64.run`
10. Veurem que s\'inicia l\'aplicació que ens permet controlar els
    serveis de XAMPP.

![[*Panell de control de
XAMPP*]{.title}](Captura_de_pantalla_a_2019-09-13_21-03-02.png "Panell de control de XAMPP")
</div>


