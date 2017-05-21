# Installation

## Voraussetzungen

- Installierter LAMP-Stack mit Apache, MySQL und PHP (mind. Version 7) 
- Lauffähige Wordpress installation

## Plugin und Theme

- Für die Installation benötigt es das Theme und das Plugin als ZIP-Datei. Diese sind [hier](https://github.com/PSEBergclubBern/BergclubBern/releases) zu finden.
- Verbinden Sie sich per FTP oder SFTP auf Ihren Hostinganbieter und laden Sie den Inhalt der zwei ZIP Dateien hoch unter folgenden Strukturen:
```
    Plugin: <wordpress>/wp-content/plugins
    Theme: <wordpress>/wp-content/themes
```
- Aktivieren Sie in der Wordpress Administration zuerst das Theme und dann das Plugin.

## Import der alten Daten (optional)

Um die Übernahme von den alten Daten zu vereinfachen, wurde ein Importscript geschrieben. 

* Laden Sie das gesamte Repository herunter und installieren Sie gemäss [README](https://github.com/PSEBergclubBern/BergclubBern/blob/master/README.md) die Entwicklungsumgebung.
* Speichern Sie die alte Datenbankdaten als PHP-Array mittels phpmyadmin in einer Datei (z.B. Importdaten.php) unter dem Verzeichnis, in welches Sie das Repository heruntergeladen haben.
* Verbinden Sie sich mit `vagrant ssh` mit Ihrer Entwicklermaschine.
* Führen Sie folgenden Befehl auf der Kommandozeile aus:

```
cd /vagrant
wp bergclub import Importdaten.php 
```

`Importdaten.php` muss mit dem jeweiligen Dateinamen aus Punkt 2 geändert werden.

