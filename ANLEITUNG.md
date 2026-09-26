# Google-Maps-Plugin 1.1.3

Neu sind ein frei platzierbarer Zurücksetzen-Button und ein optionales modernes Checkbox-Design. Bestehende Karten behalten ihr bisheriges Aussehen. Die in 1.1.2 korrigierten Kartenmaße, Schriftgrößen, Abstände und Theme-Breiten bleiben erhalten.

## Installation

Das installierbare Paket heißt `gdpr-dsgvo-compliant-embeds-for-google-maps-1.1.3.zip`. Unter **Plugins → Neues Plugin hinzufügen → Plugin hochladen** installieren und gegebenenfalls das Update bestätigen. Mindestversionen: WordPress 6.2 und PHP 7.4. Bestehende Karten-IDs und `[dsgvo_map id="123"]` bleiben erhalten.

## Separater Button an beliebiger Stelle

In einem Shortcode-Block oder im Seiteninhalt einfügen, beispielsweise auf der Datenschutzseite:

```text
[dsgvo_map_reset text="Auswahl zurücksetzen"]
```

Ohne eigenes `text` verwendet `[dsgvo_map_reset]` die übersetzte Standardbeschriftung. Der Button bleibt sichtbar und bedienbar, auch wenn die Seite keine Karte enthält oder noch keine Auswahl gespeichert ist. Er löscht den Zustimmungscookie, entlädt alle Plugin-Karten auf der aktuellen Seite und entfernt dort sämtliche Häkchen. Eine Statusmeldung bestätigt die Aktion; der Tastaturfokus bleibt am separaten Button. Bereits in anderen Tabs geöffnete Karten werden nicht entladen. Bereits an Google übertragene Daten lassen sich dadurch nicht zurückholen. JavaScript muss aktiviert sein.

## Checkbox gezielt modernisieren

Unter **Karten → Karte bearbeiten → Ladeverhalten → Checkbox-Design** die Option **Modernes Design (größere Checkbox)** auswählen und die Karte aktualisieren. Die Merkoption muss ebenfalls aktiviert sein. Der moderne Stil verwendet eine größere Checkbox (22px) mit gestalteter Beschriftungsfläche. **Bisheriges Design** bleibt der Standard; bestehende Karten ändern sich nicht automatisch.

Screenshot 5 zeigt genau diese Einstellung. Screenshots 6 und 7 zeigen das ausdrücklich aktivierte moderne Design. Diese Gestaltung ist keine automatische Änderung vorhandener Karten.

## Optionaler Button innerhalb einer Karte

Screenshot 8 zeigt einen geladenen Kartenausschnitt mit ausdrücklich aktiviertem Inline-Button. Dieser erscheint nicht beim normalen Karten-Shortcode. Dafür ist diese Option nötig:

```text
[dsgvo_map id="123" show_reset="true"]
```

Nur dann belegt der Button 64px innerhalb der eingestellten Kartenhöhe. Wer die volle Kartenfläche beibehalten möchte, kann stattdessen den separaten Shortcode außerhalb der Karte platzieren. Screenshot 9 zeigt diese zweite Möglichkeit.

## Zustimmung und Datenschutz

Die Merkfunktion setzt nur nach bewusster Auswahl `dsgvo_gm_consent=1` für bis zu 180 Tage. Sie gilt websiteweit für Karten mit aktivierter Merkoption. Andere Karten benötigen weiterhin einen Klick. Gemeinsames Laden betrifft nur entsprechend aktivierte Karten auf derselben Seite. Beide Zurücksetzen-Buttons löschen die gespeicherte Auswahl; alternativ lässt sich der Cookie über die Browsereinstellungen entfernen.

Nach dem Laden verbindet sich der Browser direkt mit Google. Passende Datenschutzhinweise, verständliche Zustimmungstexte und eine geeignete Widerrufsmöglichkeit bleiben erforderlich. Das Plugin allein garantiert keine DSGVO-Konformität einer ganzen Website.

## Pakete und Prüfungen

`wordpress-org-assets-1.1.3.zip` enthält separate WordPress.org-Grafiken und ist kein installierbares Plugin. Frühere Pakete bleiben unverändert erhalten. Aktuelle Prüfungen stehen in `TESTING.md`, der Veröffentlichungsablauf in `RELEASING.md`. Ergebnisse früherer Versionen werden nicht als neue 1.1.3-Prüfungen ausgegeben.

Hersteller: **[Tsambasis & Tsambasis](https://tsambasis.net/)**. Der WordPress.org-Mitwirkendenname `solutionfirst` sowie [Plugininformationen](https://solutionfirst.m00dy.org/wp-plugin/) und [Live-Demonstration](https://plugin-demo.m00dy.org/live-demonstration/) behalten ihre bestehenden Adressen.
