# Plugin Bergclub Bern

## Struktur
```
bergclub-plugin
├── Commands
├── MVC
├── Tests
├── vendor
├── AssetHelper.php
├── bergclub-plugin.php
├── composer.json
├── FlashMessage.php
└── README.md
```

**Commands**

@Kevin kannst du das noch ergänzen

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
Für jeden Menüpunkt der im WordPress erstellt werden soll wird ein Controller erstellt, hierzu wird die
`Bergclub\MVCAbstractController` erweitert:

```php
namespace BergclubPlugin\MeinSubmodul;

use BergclubPlugin\MVC\AbstractController;

class MeinController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/../views';
    protected $view = 'pages.main';

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

Das Routing auf die Controller wird über die Erstellung der Menüpunkte in `app.php` generiert.

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