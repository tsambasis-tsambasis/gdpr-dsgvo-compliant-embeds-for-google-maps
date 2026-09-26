# Google-Maps-Plugin 1.1.2

Dieser Hotfix korrigiert die Darstellungsänderungen von 1.1.1. Die Gestaltung orientiert sich wieder an 1.1.0: vorhandene Kartenmaße, Schriftgrößen und Abstände sowie die Inhaltsbreite des Themes bleiben maßgeblich. Das Laden vergrößert die Karte nicht automatisch und zieht standardmäßig keine Höhe für Zusatzbedienelemente ab.

## Installation und bestehende Karten

Das Paket heißt `gdpr-dsgvo-compliant-embeds-for-google-maps-1.1.2.zip`. Unter **Plugins → Neues Plugin hinzufügen → Plugin hochladen** installieren und bei vorhandenem Plugin das Update bestätigen. Mindestversionen bleiben WordPress 6.2 und PHP 7.4. Bestehende Karten-IDs und `[dsgvo_map id="123"]` bleiben erhalten.

Sichere bestehende Größen-/Schriftwerte und unverändert abgesendete Editoreingaben bleiben erhalten. Bereits in 1.1.1 geänderte und gespeicherte Werte lassen sich nicht aus dem Nichts wiederherstellen; bei abweichenden gespeicherten Einstellungen gegebenenfalls mit einer Sicherung vergleichen. Die bestandenen Tests für 1.1.2 sind gesondert in `TESTING.md` dokumentiert, einschließlich des Vergleichs mit 1.1.0; frühere Ergebnisse werden nicht als neue Hotfix-Prüfungen ausgewiesen.

## Optionaler Zurücksetzen-Button

Der normale Shortcode fügt keinen zusätzlichen Button hinzu. Wer den Button zum Entladen der Karten und Zurücksetzen der gespeicherten Auswahl anzeigen möchte, aktiviert ihn ausdrücklich:

```text
[dsgvo_map id="123" show_reset="true"]
```

Nur bei dieser ausdrücklichen Aktivierung belegt der Button 64px innerhalb der eingestellten Kartenhöhe. Dieser Button löscht den Zustimmungscookie und setzt geladene Plugin-Karten auf der aktuellen Seite auf ihre Platzhalter zurück. Bereits an Google übertragene Daten werden dadurch nicht zurückgeholt. Ohne diesen Button können Besucher den Cookie dieser Website in den Browsereinstellungen entfernen; der Betreiber muss eine geeignete Widerrufsmöglichkeit und passende Hinweise vorsehen.

Die optionale Merkfunktion speichert weiterhin `dsgvo_gm_consent=1` für bis zu 180 Tage. Sie gilt websiteweit für Karten mit aktivierter Merkoption. Andere Karten benötigen weiterhin einen Klick. Das gemeinsame Laden betrifft nur entsprechend aktivierte Karten auf derselben Seite.

## Pakete und Veröffentlichung

`wordpress-org-assets-1.1.2.zip` ist das separate Grafikpaket für WordPress.org und kein installierbares Plugin. Vorhandene 1.1.1-Pakete bleiben unverändert erhalten. Aktuelle Prüfergebnisse stehen in `TESTING.md`, der Ablauf zur Veröffentlichung nach `trunk` und `tags/1.1.2` in `RELEASING.md`. Erst nach bestandenen Hotfix-Prüfungen paketieren und veröffentlichen.

Hersteller: **[Tsambasis & Tsambasis](https://tsambasis.net/)**. Der WordPress.org-Mitwirkendenname `solutionfirst` sowie die bestehenden [Plugininformationen](https://solutionfirst.m00dy.org/wp-plugin/) und die [Live-Demonstration](https://plugin-demo.m00dy.org/live-demonstration/) bleiben erhalten.