---
layout: default 
title: 8. Seguretat i control d'errors
nav_order: 8 
has_children: true


keywords: [books,programming]
language: ca-ES
cover-image: /home/vicent/projectes/pandoc-scripts/img/example-book-cover.png
lof: false
lof-own-page: true
toc-own-page: true
titlepage-text-color: "333333"
titlepage-rule-color: "00737C"
papersize: letter
prepend-titlepage: img/example-book-cover.pdf
colorlinks: true
book: false
toc-title: "Índex de continguts"
secnumdepth: 2
markdown_in_html_blocks: false

header-includes:
- |
  ```{=latex}
  \usepackage{tcolorbox}

  \newtcolorbox{info-box}{colback=cyan!5!white,arc=0pt,outer arc=0pt,colframe=cyan!60!black}
  \newtcolorbox{warning-box}{colback=orange!5!white,arc=0pt,outer arc=0pt,colframe=orange!80!black}
  \newtcolorbox{error-box}{colback=red!5!white,arc=0pt,outer arc=0pt,colframe=red!75!black}
  \newtcolorbox{activity-box}{colback=green!5!white,arc=0pt,outer arc=0pt,colframe=green!75!black} 
  ```
pandoc-latex-environment:
  tcolorbox: [box]
  info-box: [info]
  warning-box: [warning]
  error-box: [error]
  activity-box: [activity]
---


# Seguretat i control d'errors
{: .no_toc }

Moltes vegades és important verificar la identitat dels dos extrems d'una comunicació, hi ha mètodes per identificar
tant al servidor en el qual s'allotja el lloc web, com a l'usuari del navegador que es troba en l'altre extrem.

Els llocs web que necessiten emprar identificació de servidor, com les botigues o els bancs, utilitzen el **protocol
HTTPS**. Aquest protocol requereix d'un certificat digital vàlid, signat per una autoritat fiable, que és verificat pel
navegador quan s'accedeix a la pàgina web. A més, HTTPS utilitza mètodes de xifrat per crear un canal segur entre el
navegador i el servidor, de tal manera que no es puga interceptar la informació que es transmet pel mateix.

## Autenticació d'usuaris
Per identificar els usuaris que visiten un lloc web, es poden utilitzar diferents mètodes com el DNI digital o
certificats digitals d'usuari (document digital que conté informació sobre l'usuari com el nom o l'adreça. Aquesta
informació està signada per una altra entitat, anomenada entitat certificadora, que ha de ser de confiança i garanteix
que la informació que conté és certa), però el més estès és sol·licitar a l'usuari certa informació que només ell
coneix: la combinació d'un nom d'usuari i una contrasenya.

En les unitats anteriors vas aprendre a utilitzar aplicacions web per gestionar informació emmagatzemada en bases de
dades. En la majoria dels casos és important implantar en aquest tipus de aplicacions web, les que accedeixen a bases de
dades, algun mecanisme de control d'accés que obliga a l'usuari a identificar-se. Un cop identificat, es pot limitar
l'ús que pot fer de la informació.

Així, pot haver llocs web en els quals els usuaris autenticats poden utilitzar només una part de la informació (com els
bancs, que permeten als seus clients accedir únicament a la informació relativa als seus comptes). Altres llocs web
necessiten separar en grups als usuaris autentificats, de tal manera que la informació a la qual accedeix un usuari
depèn del grup en què aquest es trobe. Per exemple, una aplicació de gestió d'una empresa pot tenir un grup d'usuaris
als qui permet visualitzar la informació, i un altre grup d'usuaris que, a més de visualitzar la informació, també la
poden modificar.

<div markdown="1" class="alert-info alert">
Has distingir l'autenticació dels usuaris i el control d'accés, de la utilització de mecanismes per assegurar les
comunicacions entre l'usuari de el navegador i el servidor web. Encara que tots dos aspectes solen anar units, són
independents.
</div>

En els exemples d'aquesta unitat, la informació d'autenticació (nom i contrasenya dels usuaris) s'envia en text pla des
del navegador fins al servidor web. Aquesta pràctica és altament insegura i mai s'ha d'usar sense un protocol com HTTPS
que permeta xifrar les comunicacions amb el lloc web. No obstant això, la configuració de servidors web que permeten fer
servir el protocol HTTPS per xifrar la informació que reben i transmeten no forma part dels continguts d'aquest mòdul.
Per aquest motiu, durant aquesta unitat utilitzarem únicament el protocol no segur HTTP.

## Manteniment de l'estat

Recorda que HTTP és un protocol sense estat, el que significa que un cop un servidor web
completa la sol·licitud d'un client d'un recurs, la connexió entre els dos acaba. Dit d’una altra manera, 
no hi ha manera que un servidor recorde una que una seqüència de sol·licituds prové del mateix client.

<div markdown="1" class="alert alert-info"> 
Mantenir l'estat és poder fer el seguiment d'una sequència de sol·licituds d'un client.
</div>

No obstant això mantenir l'estat, poder fer el seguiment d'una seqüència de sol·licituds d'un client, és molt útil. 
No podeu crear una aplicació que tinga cistella de la compra, per exemple, si no podeu mantenir l'estat. Heu de saber 
quan un usuari afegeix articles a la cistella o els elimina i el contingut final de la cistella si el client 
decideix adquirir els productes.

Per solucionar aquesta manca d’estat de la web tenim disponibles diverses tècniques com l'ús cookies o de sessions
que veurem a continuació.

Combinant mecanismes d'autenticació i tècniques per a mantenir l'estat podem crear aplicacions web segures.