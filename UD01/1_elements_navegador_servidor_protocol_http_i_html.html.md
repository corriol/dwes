::: {#content}
[Omet navegació](#main){.sr-av}

::: {#headerContent}
Introducció a la programació web
:::

-   [1. Introducció a la programació web](index.html){.daddy .main-node}
-   [1. Arquitectura d\'una aplicació
    web](1_arquitectura_duna_aplicaci_web.html){.current-page-parent
    .daddy}
    -   [[1. Elements: navegador, servidor, protocol HTTP i
        HTML](1_elements_navegador_servidor_protocol_http_i_html.html){.active
        .no-ch}]{#active}
    -   [2. El model
        client/servidor](2_el_model_clientservidor.html){.no-ch}
    -   [3. Pàgines web estàtiques i
        dinàmiques](3_pgines_web_esttiques_i_dinmiques.html){.no-ch}
    -   [4. Execució de codi en el servidor i en el
        client](4_execuci_de_codi_en_el_servidor_i_en_el_client.html){.no-ch}
-   [2. Tecnologies per a programació web del costat del
    servidor](2_tecnologies_per_a_programaci_web_del_costat_del_servidor.html){.no-ch}
-   [3. Llenguatges](3_llenguatges.html){.no-ch}
-   [4. Preparant l\'entorn de
    desenvolupament](4_preparant_lentorn_de_desenvolupament.html){.daddy}
    -   [Activitat 1. Instal·lació de
        XAMPP](activitat_1_installaci_de_xampp.html){.no-ch}
-   [5. Eines de
    desenvolupament](5_eines_de_desenvolupament.html){.daddy}
    -   [Activitat 2. Instal·lació de
        PHPStorm](activitat_2_installaci_de_phpstorm.html){.no-ch}
    -   [Pràctica 1. Prova de l\'entorn de
        desenvolupament](prctica_1_prova_de_lentorn_de_desenvolupament.html){.no-ch}
-   [6. Eines per a depuració de
    codi](6_eines_per_a_depuraci_de_codi.html){.daddy}
    -   [Pràctica 3. Instal·lació
        d\'Xdebug](prctica_3_installaci_dxdebug.html){.no-ch}
-   [Crèdits, fonts i
    bibliografia](crdits_fonts_i_bibliografia.html){.no-ch}

::: {#topPagination}
[« Anterior](1_arquitectura_duna_aplicaci_web.html){.prev} [\|
]{.sep}[Següent »](2_el_model_clientservidor.html){.next}
:::

::: {#main-wrapper}
::: {#main .section}
1. Elements: navegador, servidor, protocol HTTP i HTML {#nodeTitle}
======================================================

::: {.iDevice .emphasis0}
::: {#ta3_85 .block .iDevice_content}
El protocol de transferència d'hipertext (HTTP) és un protocol
client-servidor molt senzill que articula els intercanvis d\'informació
entre els clients web (navegadors) i els servidors HTTP.

**HTTP es basa en operacions senzilles de sol·licitud/resposta.** Quan
un client estableix una connexió amb un servidor i envia un missatge amb
les dades de la sol·licitud, el servidor respon amb un missatge similar
que conté l\'estat de l\'operació i el seu resultat de la sol·licitud.
Totes les operacions poden adjuntar un objecte o recurs sobre el qual
actuen; cada objecte web (document HTML, arxiu multimèdia o aplicació
CGI) és conegut pel seu localitzador uniforme de recursos (URL, uniform
resource locator). Els recursos poden ser arxius, el resultat de
l\'execució d\'un programa, una consulta a una base de dades, la
traducció automàtica d'un document, etc

**HTTP és un protocol sense estat**, és a dir, no guarda cap informació
sobre connexions anteriors. El desenvolupament d\'aplicacions web
freqüentment necessita mantenir estat. Per això s\'utilitzen les galetes
(*cookies*), és a dir, la informació que un servidor pot emmagatzemar en
el sistema client. Això permet que les aplicacions web institueixin la
noció de "sessió", i, alhora, permet rastrejar usuaris, ja que les
galetes es poden emmagatzemar en el client durant un temps indeterminat.

Amb la intenció de conèixer amb més profunditat el protocol HTTP podem
avaluar en què consisteix una transacció HTTP.

Cada vegada que un client fa una petició a un servidor, s'executen un
seguit d\'accions:

1.  Un usuari accedeix a una adreça d'Internet (URL) seleccionant un
    enllaç d'un document HTML o introduint-la directament a la barra de
    navegació d'un navegador web des de la perspectiva del client web.
    El client web descodifica l'adreça d'Internet (URL) separant-ne les
    diferents parts. És així com s\'identifiquen el protocol d\'accés,
    el node expressat amb el nom de domini o la seua adreça IP, el
    possible port opcional (el valor per defecte és el 80) i l\'objecte
    del servidor requerit.
2.  S'obre una connexió TCP/IP amb el servidor cridant el port TCP
    corresponent. Es fa la petició. En conseqüència, s'envien l'ordre
    necessària (GET, POST, HEAD, etc.), l'adreça de l'objecte requerit
    (el contingut de l'adreça d'Internet del servidor), la versió del
    protocol HTTP utilitzada (en la major part de les ocasions és
    HTTP/1.0) i un conjunt variable d'informació que inclou dades sobre
    les capacitats del navegador web, dades opcionals per al servidor,
    etc.
3.  El servidor localitza el recurs sol·licitat i torna la resposta al
    client.
4.  Aquesta resposta consisteix en un codi d'estat i el tipus de dada
    amb extensions multipropòsit de correu d'Internet (MIME,
    Multipurpose Internet Mail Extension) de la informació de tornada,
    seguit de la mateixa informació.
5.  El client formata i mostra el recurs rebut.
6.  Es tanca la connexió TCP.

Aquest procés es repeteix en cada accés que es faça al servidor HTTP.
Per exemple, si es recull un document HTML que conté quatre imatges, el
procés de transició mostrat amb anterioritat es repeteix cinc vegades,
és a dir, una pel document HTML i quatre per les imatges.

![Etapes d\'una transacció
HTTP](HTTP_Steps.png "Etapes d'una transacció HTTP"){width="829"
height="304"}

Si el recurs sol·licitat és un programa (CGI, ASP.NET, PHP, etc.) el
servidor HTTP redirigirà la petició a la llibreria o intèrpret adequat
que executarà el programa i tornarà el control al servidor web.
:::
:::

::: {.iDevice .emphasis0}
::: {.iDevice_destacadofpd}
::: {#ta34_151 .block .iDevice_content}
### Format de les URL

La sintaxi general de les URL consisteix en una seqüència jeràrquica de
5 components:

    URI = scheme:[//authority]path[?query][#fragment]

on el component authoriry es deivideix en tres subcomponents:

    authority = [userinfo@]host[:port]

![URL
Syntax](1068px-URI_syntax_diagram.svg.png "Sintaxi de les URL"){width="800"
height="75"}
:::
:::
:::

::: {#packageLicense .cc .cc-by-sa}
Llicenciat sota la [Llicència Creative Commons Reconeixement
CompartirIgual 4.0](http://creativecommons.org/licenses/by-sa/4.0/)
:::
:::
:::

::: {#bottomPagination}
[« Anterior](1_arquitectura_duna_aplicaci_web.html){.prev} [\|
]{.sep}[Següent »](2_el_model_clientservidor.html){.next}
:::
:::
