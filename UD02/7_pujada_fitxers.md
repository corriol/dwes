---
layout: default
parent: 2. Característiques bàsiques del llenguatge
nav_order: 7
has_children: false
---

# 7. Pujada de fitxers
{: .no_toc }

## Taula de continguts
{: .no_toc .text-delta  .nocount }

1. TOC
{:toc}


## Introducció

Per a pujar fitxers PHP implementa un mecanisme senzill a través de la variable superglobal `$_FILES`.

En [Pujada d'arxius](https://www.php.net/manual/es/features.file-upload.php) disposeu de tota la informació necessària per a gestionar la pujada d'arxius.

## Formulari

Per poder utilitzar el tipus `file` en l'element `input` cal que el formulari incloga l'atribut `enctype` amb el valor `multipart/form-data`. 

El valor de l'atribut `name` de l'element `input` serà l'índex de l'array associatiu `$_FILES` que ens permetrà obtenir tota la informació del procés de pujada del fitxer.

En el següent exemple:

```html
<form action="upload.php" enctype="multipart/form-data" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="10240">
    <input type="file" name="image" />
    <input type="submit" value="Upload" />
</form>
```
Emprarem `$_FILES['image']` per obtenir les dades de l'arxiu penjat.

## Variable superglobal `$_FILES`

Cada element en `$_FILES` és un array que aporta informació sobre el fitxer pujat.
Les claus més importants són:
 * `name`. El nom original del fitxer pujat. No és massa útil perquè el sistema original pot tindre convencions diferents i pot generar col·lisions si l'utilitzem per a emmagatzemar-lo en la seua ubicació definitiva.
 * `type`. El tipus MIME del fitxer deduït pel navegador.
 * `size`. La grandària en bytes del fitxer. Si el fitxer és massa gran el valor enviat serà 0.
 * `tmp_name`. El nom temporal del fitxer en el servidor on s'ha emmagatzemat el fitxer pujat. 

### Moure el fitxer

Com que el fitxer pujat es guarda en una carpeta temporal hem d'emprar la funció [`move_uploaded_file`](https://www.php.net/manual/es/function.move-uploaded-file.php) per a guardar-lo en la ubicació definitiva. 

La funció [`is_uploaded_file`](https://www.php.net/manual/es/function.is-uploaded-file.php) ens permet a assegurar-nos que el fitxer ha estat pujat usant HTTP POST i no es tracta d'un fitxer maliciós.

`move_uploaded_file` ja fa eixa comprovació en executar-se.

### Gestió d'errors

PHP torna un codi d'error en `$_FILES`. El codi es pot trabar en la clau `error`. Per exemple: `$_FILES['image']['error']`. 

Els missates d'error més importants són:

* `UPLOAD_ERR_OK`: La pujada ha sigut correcta.
* `UPLOAD_ERR_INI_SIZE`: La grandària del fitxer que s'intenta pujar és major que el valor indicat en la directiva `upload_max_filesize`.
* `UPLOAD_ERR_FORM_SIZE`: La grandària del fitxer que s'intenta pujar és major que el valor indicat en el formulari en `max_file_size`. 
* `UPLOAD_ERR_NO_FILE`: No s'ha enviat cap fitxer.

En [Explicació dels missatges d'error](https://www.php.net/manual/es/features.file-upload.errors.php) teniu més informació.

{: .alert .alert-activity}
<div markdown="1">

### Activitat 12 D: Pujada d'imatges (opcional)  
{: .nocount .no_toc }

Modifica l'activitat anterior afegint un camp de tipus `FILE` per a pujar una imatge al servidor. Es guardarà en la
carpeta `uploads` i es mostrarà amb la resta de dades. 
</div>
