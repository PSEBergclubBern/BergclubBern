# MVC

Namespace: `BergclubPlugin\MVC`

### AbstractController

Diesen Controller im entsprechenden Submodul für die eigenen Controller extenden.

Für Details siehe Anleitung MVC [Hier](../README.md)

### AbstractMenuItem

Benötigt für die Menüerstellung, implementierung gemeinsame Funktionalität für `Menu` und `SubMenu`.

### Helpers

Verschiedene Helper Methoden. Siehe PHPDoc für Details.

### Menu

Benötigt um im Submodul WP Admin Menüeinträge zu erstellen.

Für Details siehe Anleitung MVC [Hier](../README.md)

### SubMenu

Benötigt um im Submodul WP Admin Submenüeinträge zu erstellen.

Für Details siehe Anleitung MVC [Hier](../README.md)

### View

Benötigt um im Submodul die Ansicht zu generieren.

Für Details siehe Anleitung MVC [Hier](../README.md)

## Exceptions

### NotABergClubUserException

Wird geworfen, falls ein `Models\User` Objekt gespeichert wird ohne dass diesem eine Rolle des Typs `Role::TYPE_ADDRESS`
zugeordnet wurde.

## Models

### AbstractKeyValuePair

Kümmert sich um das Laden, Erstellen und Aktualisieren von WP Objekten die im "Key-Value" stil gespeichert sind.

Wenn diese Klasse extended wird müssen die private static Felder `$wpUpdateMethod`, `$wpDeleteMethod` und `$wpGetMethod`
überschrieben werden.

Beispiel:
```
    protected static $wpUpdateMethod = "update_option";
    protected static $wpDeleteMethod = "delete_option";
    protected static $wpGetMethod = "get_option";
```

Danach können über diese Klasse Werte erstellt, geändert und gespeichert werden.

Beispiel:

```
$myKeyValue = new MyKeyValue('key', 'value);
$myKeyValue->save();

[...]

$myKeyValue = MyKeyValue::find('key');
if($myKeyValue->getKey() == 'key'){
   echo $myKeyValue->getValue();
}
$myKeyValue->delete(); // non-static delete method

[...]

$myKeyValue::remove('key'); // static delete method
```

### IModel

Eigene Models im Submodul können dieses Interface implementieren.

Erweitert `IModelSingle`.

### IModelSingle

Interface welches nur die Methoden beinhaltet welche für "Key-Value Pair" Funktionalität benötigt werden.

### Option

Wrapper für WP Option. Siehe PHPDoc für weitere Details.

### Role

Wrapper für WP Role und erweitert diese um einen Typ welcher `Role::TYPE_ADDRESS`, `Role::TYPE_FUNCTIONARY` oder
`Role::TYPE_SYSTEM` sein kann. Siehe PHPDoc für weitere Details.

### User

Wrapper für WP User, welcher diesen um zusätzliche Funktionen erweitert, welche für die Adressverwaltung benötigt werden.
Ein User muss genau eine Rolle mit `Role::TYPE_ADDRESS` haben (wird eine weitere dieses Typs zugeordnet, wird die vorhandene überschrieben).
Ein User kann beliebig viele (auch keine) Rollen mit `Role::TYPE_FUNCTIONARY` haben. Dem User können keine Rollen mit `Role::TYPE_SYSTEM`
zugeordnet werden (andere bereits in WP definierte Rollen).

Der User hat folgende Felder welche via magic getter/setter (`$user->feld` resp. `$user->feld = 'value'`) angesprochen werden können:

- ID
- user_login
- user_pass (php password hash)
- leaving_reason (Austrittsgrund, siehe "getter/setter mit Konstanten")
- program_shipment (Versand Programm, siehe "getter/setter mit Konstanten")
- company
- gender (Verwendet für Anrede, siehe "getter/setter mit Konstanten")
- first_name
- last_name
- address_addition
- street
- zip
- location
- phone_private
- phone_work
- phone_mobile
- email
- birthdate
- comments
- history

**getter/setter mit Konstanten**

Zu diesen Werten befinden sich zugehörige Konstanten in der Klasse.

*Beispiel für `gender`:*

```
const GENDER_M = 'Herr';
const GENDER_F = 'Frau';
```

Im `User` Objekt ist entweder der Wert `null`, `M` oder `F` abgelegt.
Wird `$user->gender` verwendet liefert der magic setter den Wert der enstprechenden Konstante. Das heisst ist für das
Feld `gender` der Wert `M` abgelegt, liefert `$user->gender` `Herr` zurück.

Will man den Wert festlegen, ist der jeweilig effektiv zu speichernde Wert zu verwenden (z.B. `$user->gender = 'M'`).
Um sicherzugehend, dass kein falscher Wert in die Datenbank gelangt, prüft `User` in diesem Fall ob die Konstante
`GENDER_X` existiert (Wobei X der zu setzende Wert ist). Falls nicht wird eine `UnexpectedValueException` geworfen.

** Erstellen eines neuen User objekts **

Im Konstruktor kann ein Array mit key/value Paaren mitgeliefert werden. Entspricht der key einem der oben genannten
Felder wird der entsprechende Wert gesetzt.

```
$user = new User(['first_name' => 'Fritz', 'last_name' => 'Muster']);
```

**Zuordnen einer Rolle**

```
$user = new User(['first_name' => 'Fritz', 'last_name' => 'Muster']);
$user->addRole( Role::find('aktivmitglied') );
```

**Speichern**

Damit eine Änderung persistent wird, muss die save methode (`$user->save()`) aufgerufen werden.


**Finden bestehender User**

**Wichtig:** *Es werden nur Benutzer zurückgeliefert die eine Rolle mit `Role::TYPE_ADDRESS` zugeordnet haben, weitere WP User werden
nicht gefunden.*


Bestehenden Benutzer laden:
```
$user = User::find($id);
```

Aktuell angemeldeten Benutzer laden:
```
$user = User::findCurrent()
```

Alle Benutzer laden (Default Sortierung: Nachname, Vorname aufsteigend):

```
$user = User::findAll()
```

Für weitere Details siehe PHPDoc.

*Anmerkung:* Eventuell später noch benötigt `findByRole`, `findByRoleType`.