# Konzept

Dieses Konzept stellt technische Lösungen zu den herausgearbeiteten Use-Cases dar. Dabei sollte dies als Starthilfe dienen und keinesfalls eine Vorgabe sein, wie man die Funktion schlussendlich implementiert.

## Allgemein

Nachfolgend wird allgemein für die nachfolgenden Titel einen technischen Lösungsansatz vorgestellt.

### Adressverwaltung

Die Adressverwaltung wird im Plugin abgebildet. Dabei hat das Plugin folgende Aufgaben:

1) Datenbankänderungen ([Link](https://codex.wordpress.org/Creating_Tables_with_Plugins)) für zusätzliche Felder, welche nicht als [User Metadata](https://developer.wordpress.org/plugins/users/working-with-user-metadata/) abgebildet werden können. 
2) Exportfunktionen als [WP-Admin Menüpunkt.](https://developer.wordpress.org/plugins/administration-menus/)
3) Funktion um eigene Adresse zu bearbeiten [Hier.](https://developer.wordpress.org/plugins/users/roles-and-capabilities/)

### Tourdaten

Die Tourdaten werden mit Wordpress Posts inkl. Custom Fields abgearbeitet. Dabei hat das Theme folgende Aufgaben:

1) Definieren der Custom Fields (siehe [Hier](https://developer.wordpress.org/plugins/metadata/))
2) Korrektes Rendering der Metadaten.

Das Plugin hat folgende Aufgabe:

1) Die Touren sollten mit dem korrekten Workflow bearbeitet werden. <!-- TODO -->
2) Das Plugin definiert die Rollen und Rechte der Benutzer [Hier.](https://developer.wordpress.org/plugins/users/roles-and-capabilities/)


### Konfiguration WP

Die Konfiguration des Endsystems wird per Datenbank-Exports geschehen. Diese Exports werden anfänglich im GitHub Repository gespeichert, müssen jedoch später geschützt abgelegt werden.

### Import

Das Importieren der alten Daten wird automatisch per Script geschehen. Dieses Script sollte im Hauptrepo gesichert werden.


## UC Abdeckung

Dieses Kapitel dient dazu, die einzelnen Use-Cases zu beleuchten und dort u.U. technische Lösungsvorschläge vorzustellen.

### UC1

Dieser Use-Case wird allgemein abgedeckt mit den Datenbankänderungen im Plugin oder den User Metadata, welche im Plugin / Theme ausgeführt werden. <!-- TODO -->

### UC2

Siehe UC1. Die Rechte werden der Gruppe per Plugin / vordefinierter Rolle im DB-Dump definiert. <!-- TODO -->

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

Wordpress Funktion. <!-- TODO -->


## Linksammlung


