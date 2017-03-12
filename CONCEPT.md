# Konzept

Dieses Konzept stellt technische Lösungen zu den herausgearbeiteten Use-Cases dar. Dabei sollte dies als Starthilfe dienen und keinesfalls eine Vorgabe sein, wie man die Funktion schlussendlich implementiert.

## Allgemein

Nachfolgend wird allgemein für die nachfolgenden Titel einen technischen Lösungsansatz vorgestellt.

### Pluginaufbau

Das Plugin wird mehrere thematische Gebiete abdecken. Somit macht es Sinn, das Plugin in mehrere Bereiche zu trennen. Dies kann man einfach mit Unterordner im Root abbilden. Folgende Unterordner würden Sinn ergeben:

- bergclub-touren
- bergclub-adressen
- bergclub-rueckmeldung
- bergclub-export
- bergclub-rechte

### Adressverwaltung

Die Adressverwaltung wird im Plugin abgebildet. Dabei hat das Plugin folgende Aufgaben:

1. Datenbankänderungen ([Link](https://codex.wordpress.org/Creating_Tables_with_Plugins)) für zusätzliche Felder, welche nicht als [User Metadata](https://developer.wordpress.org/plugins/users/working-with-user-metadata/) abgebildet werden können. 
2. Exportfunktionen als [WP-Admin Menüpunkt.](https://developer.wordpress.org/plugins/administration-menus/)
3. Funktion um eigene Adresse zu bearbeiten [Hier.](https://developer.wordpress.org/plugins/users/roles-and-capabilities/)

### Tourdaten

Die Tourdaten werden mit Wordpress Posts inkl. Custom Fields abgearbeitet. Dabei hat das Theme folgende Aufgaben:

1. Definieren der Custom Fields (siehe [Hier](https://developer.wordpress.org/plugins/metadata/))
2. Korrektes Rendering der Metadaten.

Das Plugin hat folgende Aufgabe:

1. Die Touren sollten mit dem korrekten Workflow bearbeitet werden ([Loop anpassen](https://codex.wordpress.org/The_Loop) / [Custom Post](https://developer.wordpress.org/plugins/post-types/)).
2. Das Plugin definiert die Rollen und Rechte der Benutzer [Hier.](https://developer.wordpress.org/plugins/users/roles-and-capabilities/)


### Konfiguration WP

Die Konfiguration des Endsystems wird per Datenbank-Exports geschehen. Diese Exports werden anfänglich im GitHub Repository gespeichert, müssen jedoch später geschützt abgelegt werden.

### Import

Das Importieren der alten Daten wird automatisch per Script geschehen. Dieses Script sollte im Hauptrepo gesichert werden.


## UC Abdeckung

Dieses Kapitel dient dazu, die einzelnen Use-Cases zu beleuchten und dort u.U. technische Lösungsvorschläge vorzustellen.

### UC1

Dieser Use-Case wird allgemein abgedeckt mit den Datenbankänderungen im Plugin oder den User Metadata, welche im Plugin / Theme ausgeführt werden. 

### UC2

Siehe UC1. Die Rechte werden der Gruppe per Plugin / vordefinierter Rolle im DB-Dump definiert. 

### UC3

Siehe UC2.

### UC4

Das Plugin erweitert WP-Admin mit einem Menüpunkt und erstellt den Export nach den definierten Funktionen / Filter.

### UC5

Das Plugin sollte die Möglichkeit der Userbearbeitung erweitern [Hier.](https://developer.wordpress.org/plugins/hooks/actions/)

### UC6 / UC7

Das Theme definiert die Custom Fields. Dort wird ebenfalls die Darstellung geregelt. Das Plugin verarbeitet den Workflow.

### UC8 - UC13

Wordpress Artikelfunktion inkl. Custom Fields vom Theme.

### UC14

Das Plugin handhabt die Spesenfunktion im WP-Admin. Dabei wird im WP-Admin eine Seite generiert mit den aktuellen Tourenbeiträge.

### UC15 - UC17

Wordpress Funktion. 

## Linksammlung

### Basics	
- [WordPress Semantics](https://codex.wordpress.org/WordPress_Semantics)
- [WordPress Glossary](https://codex.wordpress.org/Glossary)
### Theme Development	
- [Getting Started Theme Development](https://developer.wordpress.org/themes/getting-started/)
- [Theme Development](https://codex.wordpress.org/Theme_Development)
- [The Loop](https://codex.wordpress.org/The_Loop)
- [Theme Unit Test](https://codex.wordpress.org/Theme_Unit_Test)
- [Bootstrap Navwalker](https://github.com/twittem/wp-bootstrap-navwalker)
### Plugin Development
- [Plugin Developer Handbook](https://developer.wordpress.org/plugins/)
- [Writing a Plugin](https://codex.wordpress.org/Writing_a_Plugin)
- [Plugin API/Action Reference](https://codex.wordpress.org/Plugin_API/Action_Reference)
- [Gutes Beispiel für Aktivierungs/Deaktivierungs Hooks](http://wordpress.stackexchange.com/questions/25910/uninstall-activate-deactivate-a-plugin-typical-features-how-to/25979#25979)
- [Unit Tests for WordPress Plugins](https://pippinsplugins.com/unit-tests-wordpress-plugins-setting-up-testing-suite/)
### Diverses
- [Creating a custom WordPress registration](https://code.tutsplus.com/tutorials/creating-a-custom-wordpress-registration-form-plugin--cms-20968)
- [Custom Fields](https://codex.wordpress.org/Custom_Fields)
- [Custom Post status](http://jamescollings.co.uk/blog/wordpress-create-custom-post-status/)
- [How to Create Custom User Roles](https://managewp.com/create-custom-user-roles-wordpress)
### PHPStorm
- [Lizenz für Studenten (mit Uni Email registrieren)](https://www.jetbrains.com/shop/eform/students)
- [Download](https://www.jetbrains.com/phpstorm/)
- [Integration von Vagrant](https://confluence.jetbrains.com/display/PhpStorm/Getting+started+with+Vagrant+in+PhpStorm)
- [Configuring Remote PHP Interpreters](https://www.jetbrains.com/help/phpstorm/2016.3/configuring-remote-php-interpreters.html)
- [Running PHPUnit tests over SSH](https://confluence.jetbrains.com/display/PhpStorm/Running+PHPUnit+tests+over+SSH+on+a+remote+server+with+PhpStorm)
