# Anwesenheitsliste für Gottesdienste

Die Anwesenheitsliste erlaubt die automatisierte Anmeldung und Abmeldung vom Gottesdienst (für den jeweils nächsten Sonntag). Bei der Anmeldung erhält der angemeldete eine Anmeldenummer.
Die Anmeldenummer wird auch per E-Mail verschickt (s. E-Mail-Einstellungen in den FAQs).
Die Anmeldenummer hilft am Sonntag dem Begrüßungsteam in der Gemeinde beim Auffinden/Abhakten des Namens.
Sind keine freien Plätze verfügbar, kann man sich auf eine Warteliste setzen lassen und wird informiert, sobald sich eine andere Person abgemeldet hat. Dann muß man sich aber noch (schnell) anmelden, bevor die freien Plätze wieder vergeben sind.
Abmeldungen sind aus Datenschutzgründen und Sicherheitsgründen nur mit den vollständigen exakten Eingabedaten und der Anmeldenummer möglich.

Im Administrationsbereich kann man immer für den Folgesonntag die Maximal-Personenanzahl einstellen. Außerdem ist die sog. Uhrzeitgrenze einstellbar. Das ist die Zeit ab wann die Liste für den aktuellen Sonntag nicht mehr verfügbar ist (rechtzeitig VOR Beginn des Gottesdienstes) und die Liste für die nächste Woche angezeigt wird.
Im Administrationsbereich lassen sich alle Anwesenheitslisten als CSV (="Rohformat") anzeigen und ändern.
Außerdem läßt sich über eine <Drucken>-Button Funktion eine Liste für die Begrüßungsteams drucken.
Dafür gibt es auch eine papiersparende, elektronische Variante mit der <Mobil>-Button Funktion. Es wird ein temporärer Link erstellt der 2 Stunden gültig ist und auf allen Handys oder Tablets mit Internetanschluß funktioniert. Das Begrüßungsteam kann so elektronisch abhakten, welche der angemeldeten Personen wirklich erscheinen.

Die Anmeldelisten werden nach 5 Wochen automatisch gelöscht. Damit ist sichergestellt, daß sie mindestens die letzten 4 Wochen abdecken. Es sollte zur Sicherheit ein Backup-E-Mail-Konto eingerichtet werden (s.u.). Hierauf sollte nur die Gemeindeleitung Zugriff haben für den Fall, daß der Webserver defekt ist. Außerdem sollten Absprachen existieren, wie zu verfahren ist, wenn die Webseite einmal nicht erreichbar sein sollte (Notfallverfahren). Die Gemeinde sollte z.B. eine E-Mail-Adresse kennen, unter der sie sich notfalls auch manuell anmelden können (z.B. Gemeindebüro).

## Systemvoraussetzungen auf dem Webserver

* PHP >=5.X
* E-Mail: Die E-Mail-Funktion sollte in der php.ini aktiviert sein und ein gültiger SPF-Eintrag für den Server (Absender) beim Webseitenprovider eingestellt werden.

## Modifikationen

Die Sourcen sind f�r die FeG Kiel ausgelegt. Sie k�nnen mit wenig Aufwand f�r andere Gemeinden angepa�t werden:
* attend.php: Hier m�ssen die meisten Anpassungen f�r die Benutzer-Ansicht der Webseite gemacht werden
* attend-functions.php: 
** Hier m�ssen vor allem Anpassungen bzgl. der E-Mails gemacht werden (Text und E-Mail-Absender!)
** Die meisten �nderungen k�nnen am Anfang der Datei gemacht werden: Hier k�nnen ein Youtube-Link, ein Corona-Bestimmungen-Info-Link und die E-Mail-Absender-Adressen eingestellt werden.
* attend-cfg.php: 
** Hier muß die Basis-URL der Gemeindewebseite angepaßt werden. 
** Als Initialpasswort ist hier "admin" einzutragen und dann zu ändern
** Ein Backup-E-Mail-Konto sollte unter mail_to hinterlegt werden. Hier werden zu Backup-Zwecken alle Änderungen der Anwesenheitsliste geschickt. Aus Datenschutzgründen sollten die E-Mails nach einer gewissen Zeit mit einer Regel automatisch gelöscht werden (idR 4 Wochen). Es empfiehlt sich, ein eigenes E-Mail-Konto für die Backup-Emails anzulegen, z.B. kostenlos bei www.mail.de.
** Test_enabled sollte für den Produktionsmodus deaktiviert sein. Im Testmodus wird das Captcha deaktiviert, so daß automatische End-to-End-Tests möglich sind.

## Deployment

Eine Anleitung zum Deployment der Webseite findet sich hier: https://github.com/cmotika/fegkielattend/blob/master/deploy.txt

## Specification / Requirements

Die Requirements für die Anmeldeseite finden sich hier: https://github.com/cmotika/fegkielattend/blob/master/spec/spec.txt

Die Requirements geben eine Übersicht über die Funktionalität der Seite. Die Requirements sind zudem auch im Source-Code verlinkt, um die Stellen für ggf. Anpassungen schnell zu finden.

Für die meisten Requirements existieren auch End-to-End-Tests: https://github.com/cmotika/fegkielattend/blob/master/test/test.spec.js

## FAQs

* E-Mails kommen nicht an:
** GMX und Web.de erfordern einen g�ltigen SPF-Eintrag und nehmen den Spamschutz hier sehr genau!
** SPF-Eintrag pr�fen, z.B. mit https://www.spf-record.de/spf-lookup
** PHP.ini Einstellung zu E-Mails pr�fen, https://www.google.com/search?q=php.ini+email+configuration
** Sind in der attend-functions.php die Absender-Header (mail_from_header, ganz oben) der E-Mail-Funktionen korrekt? Eintr�ge der folgenden Art m�ssen angepa�t werden: $header = "From: FeG Kiel <noreply@feg-kiel.de>\r\n"; 


* Keine Backup-Mails: 
** attend-cfg.php-Eintragung "mail_to" muß angepaßt werden. Evtl. kommen auch die Backupmails nicht an, weil der SPF-Eintrag falsch ist (s.o.).
