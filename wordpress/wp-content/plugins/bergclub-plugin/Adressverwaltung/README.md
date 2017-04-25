# Submodul Adressverwaltung

## Aktivierung

Rollen mit Typ `Role::TYPE_ADDRESS`, die erstellt werden:

* `bcb_instituation`
* `bcb_inserent`
* `bcb_intressent`
* `bcb_inserent_jugend`
* `bcb_aktivmitglied`
* `bcb_aktivmitglied_jugend`
* `bcb_ehrenmitglied`
* `bcb_ehemalig`

Für jede Adressrolle werden ein paar Benutzer mit Fake-Daten generiert.

Rollen mit Typ `Role::TYPE_FUNCTIONARY`, die erstellt werden:

* `bcb_leiter`
* `bcb_leiter_jugend`
* `bcb_tourenchef`
* `bcb_tourenchef_jugend`
* `bcb_redaktion`
* `bcb_sekretariat`
* `bcb_mutationen`
* `bcb_kasse`
* `bcb_praesident`
* `bcb_internet`
* `bcb_materialchef`
* `bcb_materialchef_jugend`
* `bcb_js_coach`
* `bcb_versand`

Es wird ein Test-Benutzer mit Login für jede dieser Funktionsrolle generiert.
Benutzername und Passwort ist jeweils der oben genannte Slug ohne `bcb_` Prefix.

Neben vorhandenen WP Capabilities werden den Rollen auch folgende zugewiesen:

Bei den Capabilities für die Tourenbeiträge haben wir uns an den in WP vorhandenen Capabilities orientiert und diese
mit dem Prefix `touren_` resp. `touren_jugend_` versehen.

* `adressen_read`
* `adressen_edit`
* `rueckmeldungen_read`
* `rueckmeldungen_read_others`
* `rueckmeldungen_edit`
* `rueckmeldungen_edit_others`
* `rueckmeldungen_jugend_read`
* `rueckmeldungen_jugend_read_others`
* `rueckmeldungen_jugend_edit`
* `rueckmeldungen_jugend_edit_others`
* `touren_edit_posts`
* `touren_edit_posts_others`
* `touren_edit_published_posts`
* `touren_edit_published_posts_others`
* `touren_publish_posts`
* `touren_jugend_edit_posts`
* `touren_jugend_edit_posts_others`
* `touren_jugend_edit_published_posts`
* `touren_jugend_edit_published_posts_others`
* `touren_jugend_publish_posts`
* `stammdaten_read`
* `stammdaten_edit`

## App

Erstellt einen Menüpunkt Adressen im WP Admin.

## Deaktivierung

Löschen der unter "Aktivierung" erstellten Objekte.
