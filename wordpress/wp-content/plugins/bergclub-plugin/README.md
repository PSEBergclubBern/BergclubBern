# Plugin Bergclub Bern

## Struktur
```
bergclub-plugin
├── Commands
├── MVC
├── Tests
├── vendor
├── AssetHelper.php
├── TourenHelper.php
├── bergclub-plugin.php
├── composer.json
├── FlashMessage.php
└── README.md
```

**Commands**

Hier wird die WP-Cli erweitert. Die vorhandenen Klassen kümmern sich um den Import von den alten Website-Daten.

**MVC**

Klassen um Submodul mit dem MVC-Pattern zu erstellen.

**Tests**

Bitte Unterordner pro Submodul erstellen

**vendor**

Hier werden die von composer verwalteten Pakete gespeichert.

Siehe [https://getcomposer.org/doc/](https://getcomposer.org/doc/) und [https://packagist.org/](https://packagist.org/).

**AssetHelper.php**

Diese Klasse kann verwendet werden um in `app.php` des jeweiligen Submoduls Javascripts oder Stylesheets zur Admin Page
hinzuzufügen:

```
\BergclubPlugin\AssetHelper::addAsset($page, $url)
```

`$page` ist der slug der Seite (`page=slug` in der URL)

`$url` am besten mit `plugins_url` relativ zur aktuellen Datei bestimmen:
   
```
plugins_url('assets/css/meinsubmodul.css', __FILE__)
```
Wird der zweite Parameter nicht verwendet, muss die URL relativ zum `plugins` Verzeichnis angegeben werden.

***Hinweis:** Bei Verwendung des MVC Pattern bitte den AssetHelper nur verwenden um Assets hinzuzufügen die auf allen
Seiten verwendet werden sollen.*

**TourenHelper.php**

Die statischen methoden des TourenHelper werden über die Funktion `bcb_touren_meta` (in `bergclub-plugin.php`) zur
Verfügung gestellt.

Als Parameter wird die Post ID und der Meta Key (ohne führendes `_`) benötigt.

Beispiel:
```
while ($query->have_posts()) : $query->the_post();
    $dateFrom = bcb_touren_meta(get_the_ID(), 'dateFrom');
    [...]
}
```

Folgende Meta Keys stehen momentan zur Verfügung (Falls Eintrag nicht gefunden oder keine Zuordnung (id) vorgenommen werden kann, wird `null` zurückgeliefert)

* `dateFrom`: Liefert das "Von" Datum im Format "d.m.Y"
* `dateTo`: Liefert das "Bis" Datum im Format "d.m.Y" (falls vorhanden)
* `dateDisplayShort`: Liefert das Datum im Format "d.m." (Eintägig) resp. "d.m. - d.m." (Mehrtägig)
* `dateDisplayFull`: Liefert das Datum im Format "d.m.Y" (Eintägig) resp. "d.m.Y - d.m.Y" (Mehrtägig)
* `isSeveralDays`: Liefert true, falls Mehrtägig, ansonsten false
* `leader`: Liefert Name des Leiters (in DB ist user id gespeichert)
* `coLeader`: Liefert Name des Co-Leiters (in DB ist user id gespeichert)
* `signupUntil`: Liefert die Anmeldefrist im Format "d.m.Y"
* `signupTo`: Liefert den "Anmeldung an" Wert mit Name, E-Mail und Telefonnummern als kommaseparierten String (in DB ist user id gespeichert)
* `sleepOver`: Liefert die Angaben zur Übernachtung
* `meetpoint`: Liefert den vordefinierten Treffpunkt (in DB ist id gespeichert), liefert "Anderer Treffpunkt" falls festgelegt.
* `meetingPointTime`: Liefert die Zeit für den Treffpunkt im Format "G:i"
* `returnBack`: Liefert die Informationen zur Rückkehr (Freies Textfeld: Zeit, "abends", etc.)
* `food`: Liefert die Informationen zur Verpflegung.
* `type`: Liefert die Tourenart (in DB ist slug gespeichert)
* `requirementsTechnical`: Liefert die technischen Anforderungen (Abhängig von Tourenart)
* `requirementsConditionsl`: Liefert die konditionellen Anforderungen (in DB ist id gespeichert)
* `riseUpMeters`: Liefert die Höhenmeter (Aufstieg)
* `riseDownMeters`: Liefert die Höhenmeter (Abstieg)
* `duration`: Liefert die Gesamtzeit der Tour (ohne Pausen)
* `additionalInfo`: Liefert das zusätzliche Informationen (Ersetzt `\n` durch `<br>`) 
* `training`: Liefert Training (Ja/Nein)
* `jsEvent`: Liefert J+S-Event (Ja/Nein)
* `program`: Liefert das Programm (Ersetzt `\n` durch `<br>`)
* `equipment`: Liefert die Angaben zur Ausrüstung (Ersetzt `\n` durch `<br>`)
* `mapMaterial`: Liefert die Angaben zum benötigten Kartenmaterial
* `onlineMap`: Liefert die URL zur Online-Karte
* `costs`: Liefert die Kosten (Betrag ohne Währung)
* `costsFor`: Liefert die Angaben wofür die Kosten sind

**bergclub-plugin.php**

Hier werden die generellen Hooks und Funktionen für das Bergclub Plugin festgelegt.
Spezifisches für Submodule sollte in der `app.php` des entsprechenden Submoduls definiert werden.

Lädt `app.php`, `activate.php` und `deactivate.php` in den Submodulen, falls vorhanden.

**composer.json**

Siehe [https://getcomposer.org/doc/](https://getcomposer.org/doc/) und [https://packagist.org/](https://packagist.org/).

**FlashMessage.php**

Diese klasse kann verwenden werden um Nachrichten in der Session zwischenzuspeichern, die dann in der Admin Page
eingeblendet werden können.

*Nachricht erfassen:*
```
use \BergclubPlugin\FlashMessage;
FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Speichern erfolgreich');
```
Wird ein dritter Parameter mit Wert `true` übergeben lässt sich die Nachricht bei der Anzeige "wegklicken".

*Nachrichten als HTML ausgeben:*
```
\BergclubPlugin\FlashMessage::show();
```
Die Nachrichten werden beim ausgeben aus der Session entfernt.

## Submodul erstellen

Dazu muss zuerst unter `bergglub-plugin` ein neuer Ordner erstellt werden.
Falls prozedural gearbeitet wird ist die Namensgebung nicht so relevant. Wenn jedoch mit Klassen resp. dem MVC Pattern
gearbeit wird sollte der Ordner dem Namespace entsprechen der verwendet wird. z.B. wenn der Ordner `MeinSubmodul` heisst,
ist der Namespace für die Klassen `BergclubPlugin\MeinSubmodul`.

In diesem Ordner muss eine `app.php` erstellt werden. In dieser kann genau so gearbeitet werden wie in einer regulären
`-plugin.php` mit Ausnahme des auszuführenden Codes beim aktivieren resp. deaktivieren des Plugins. Es muss hierfür kein
Hook und keine Funktion erstellt werden, dieser wird einfach in `activate.php` resp. `deactivate.php` abgelegt.

### MVC Pattern verwenden
Für jeden Menüpunkt der im WordPress Admin erstellt werden soll, wird ein Controller erstellt. Hierzu wird die
`Bergclub\MVC\AbstractController` Klasse erweitert:

```php
namespace BergclubPlugin\MeinSubmodul;

use BergclubPlugin\MVC\AbstractController;

class MeinController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/../views';
    protected $view = 'view';

    protected function first(){}

    protected function get(){}

    protected function post(){}

    protected function last(){}

}
```

`$viewDirectory` und `$view` können auch erst in einer der Methoden gesetzt werden (bsp. `$this->view = 'view'`).

Beim Abrufen der entsprechenden Admin Seite werden die Methoden im Controller in folgender Reihenfolge abgerufen:

* first
* get (Nur falls GET Request)
* post (Nur falls POST Request)
* last

Daten für die Views kann in jeder Methode mit `$this->data['key'] = 'value'` übergeben werden.
In diesem Beispiel kann im View die Variable `$key` angesprochen werden.

Für die Views wird die Template Engine [Blade](https://laravel.com/docs/5.4/blade) von Laravel verwendet.
Alle views werden in einem Unterordner des Submoduls abgelegt und müssen die Endung `.blade.php` haben.
Falls im Ordner mit den Views weitere Unterordner verwendet werden, kann der Wert für `$view` im Controller mit '/' oder
 '.' angegeben werden (bsp. `unterordner.view`)

Es sollten im View keine `<html>`, `<head>` oder `<body>` Tags verwendet werden, da WordPress dies bereits macht.

Views können erweitert werden:

`template.blade.php`
```
<h1>{{ $title }}</h1>

<div class="content">
@yield('content')
</div>
```

`view.blade.php`
```
@extends('template')

@section('content')
Hallo Welt!
@endsection
```
 
Mit der Annahme, dass der Titel im Controller mit `$this->data['title'] = 'Mein Submodul'` hinzugefügt wurde und
`$view = 'view'`, ergibt dies
dann folgendes gerendertes HTML:
```html
<h1>Mein Submodul</h1>

<div class="content">
Hallo Welt!
</div>
```

Das Routing auf die Controller wird über die Erstellung der Menüpunkte in `app.php` generiert:

```php
use BergclubPlugin\MVC\Menu;
use BergclubPlugin\MVC\SubMenu;

$adminMenu = new Menu(
    'Mein Submodul',
    'manage_options',
    'BergclubPlugin\\MeinSubmodul\\MeinController'
);

$adminMenu->addSubMenu(new SubMenu(
    'Mein Submenu',
    'manage_options',
    'BergclubPlugin\\MeinSubmodul\\WeitererController'
));
```

Für `Menu` und `Submenu` sind die zwingenden Parameter identisch:
* Titel der im Menüpunkt erscheint
* WordPress capability (Recht das der User haben muss um die Seite anzuzeigen)
* Klassenname inkl. Namespace des Controllers.

Sowohl `Menu` und `Submenu` kann ein Array als weiterer Parameter übergeben werden, welches zu inkludierende Stylesheets
und Javascripts enthält (siehe auch `AssetHelper.php` weiter oben).

`Menu` akzeptiert einen fünften Parameter für das Icon. Hier kann ein
[Dashicon](https://developer.wordpress.org/resource/dashicons/) angegeben werden. Auf der Dashicon Seite hierzu auf das
gewünschte Icon klicken und danach auf "Copy HTML". Nun nur den Namen des Dashicons kopieren, welcher mit `dashicons-`
beginnt (z.B. `dashicons-admin-site`).
