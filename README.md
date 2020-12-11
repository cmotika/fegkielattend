## Anwesenheitsliste f�r Gottesdienste

Die Anwesenheitsliste erlaubt die automatisierte Anmeldung und Abmeldung vom Gottesdienst (f�r den jeweils n�chsten Sonntag). Bei der Anmeldung erh�lt der angemeldete eine Anmeldenummer.
Die Anmeldenummer wird auch per E-Mail verschickt (s. E-Mail-Einstellungen in den FAQs).
Die Anmeldenummer hilft am Sonntag dem Begr��ungsteam in der Gemeinde beim Auffinden/Abhakten des Namens.
Sind keine freien Pl�tze verf�gbar, kann man sich auf eine Warteliste setzen lassen und wird informiert, sobald sich eine andere Person abgemeldet hat. Dann mu� man sich aber noch (schnell) anmelden, bevor die freien Pl�tze wieder vergeben sind.
Abmeldungen sind aus Datenschutzgr�nden und Sicherheitsgr�nden nur mit den vollst�ndigen exakten Eingabedaten und der Anmeldenummer m�glich.

Im Administrationsbereich kann man immer f�r den Folgesonntag die Maximal-Personenanzahl einstellen. Au�erdem ist die sog. Uhrzeitgrenze einstellbar. Das ist die Zeit ab wann die Liste f�r den aktuellen Sonntag nicht mehr verf�gbar ist (rechtzeitig VOR Beginn des Gottesdienstes) und die Liste f�r die n�chste Woche angezeigt wird.
Im Administrationsbereich lassen sich alle Anwesenheitslisten als CSV (="Rohformat") anzeigen und �ndern.
Au�erdem l��t sich �ber eine <Drucken>-Button Funktion eine Liste f�r die Begr��ungsteams drucken.
Daf�r gibt es auch eine papiersparende, elektronische Variante mit der <Mobil>-Button Funktion. Es wird ein tempor�rer Link erstellt der 2 Stunden g�ltig ist und auf allen Handys oder Tablets mit Internetanschlu� funktioniert. Das Begr��ungsteam kann so elektronisch abhakten, welche der angemeldeten Personen wirklich erscheinen.

Die Anmeldelisten werden nach 5 Wochen automatisch gel�scht. Damit ist sichergestellt, da� sie mindestens die letzten 4 Wochen abdecken. Es sollte zur Sicherheit ein Backup-E-Mail-Konto eingerichtet werden (s.u.). Hierauf sollte nur die Gemeindeleitung Zugriff haben f�r den Fall, da� der Webserver defekt ist. Au�erdem sollten Absprachen existieren, wie zu verfahren ist, wenn die Webseite einmal nicht erreichbar sein sollte (Notfallverfahren). Die Gemeinde sollte z.B. eine E-Mail-Adresse kennen, unter der sie sich notfalls auch manuell anmelden k�nnen (z.B. Gemeindeb�ro).

# Systemvoraussetzungen auf dem Webserver

* PHP >=5.X
* E-Mail: Die E-Mail-Funktion sollte in der php.ini aktiviert sein und ein g�ltiger SPF-Eintrag f�r den Server (Absender) beim Webseitenprovider eingestellt werden.

# Modifikationen

Die Sourcen sind f�r die FeG Kiel ausgelegt. Sie k�nnen mit wenig Aufwand f�r andere Gemeinden angepa�t werden:
* attend.php: Hier m�ssen die meisten Anpassungen f�r die Benutzer-Ansicht der Webseite gemacht werden
* attend-functions.php: 
** Hier m�ssen vor allem Anpassungen bzgl. der E-Mails gemacht werden (Text und E-Mail-Absender!)
** Die meisten �nderungen k�nnen am Anfang der Datei gemacht werden: Hier k�nnen ein Youtube-Link, ein Corona-Bestimmungen-Info-Link und die E-Mail-Absender-Adressen eingestellt werden.
* attend-cfg.php: 
** Hier mu� die Basis-URL der Gemeindewebseite angepa�t werden. 
** Als Initialpasswort ist hier "admin" einzutragen und dann zu �ndern
** Ein Backup-E-Mail-Konto sollte unter mail_to hinterlegt werden. Hier werden zu Backup-Zwecken alle �nderungen der Anwesenheitsliste geschickt. Aus Datenschutzgr�nden sollten die E-Mails nach einer gewissen Zeit mit einer Regel automatisch gel�scht werden (idR 4 Wochen). Es empfiehlt sich, ein eigenes E-Mail-Konto f�r die Backup-Emails anzulegen, z.B. kostenlos bei www.mail.de.
** Test_enabled sollte f�r den Produktionsmodus deaktiviert sein. Im Testmodus wird das Captcha deaktiviert, so da� automatische End-to-End-Tests m�glich sind.

# Deployment

Eine Anleitung zum Deployment der Webseite findet sich hier: https://github.com/cmotika/fegkielattend/blob/master/deploy.txt

# Specification / Requirements

Die Requirements f�r die Anmeldeseite finden sich hier: https://github.com/cmotika/fegkielattend/blob/master/spec/spec.txt

Die Requirements geben eine �bersicht �ber die Funktionalit�t der Seite. Die Requirements sind zudem auch im Source-Code verlinkt, um die Stellen f�r ggf. Anpassungen schnell zu finden.

F�r die meisten Requirements existieren auch End-to-End-Tests: https://github.com/cmotika/fegkielattend/blob/master/test/test.spec.js

# FAQs

* E-Mails kommen nicht an:
** GMX und Web.de erfordern einen g�ltigen SPF-Eintrag und nehmen den Spamschutz hier sehr genau!
** SPF-Eintrag pr�fen, z.B. mit https://www.spf-record.de/spf-lookup
** PHP.ini Einstellung zu E-Mails pr�fen, https://www.google.com/search?q=php.ini+email+configuration
** Sind in der attend-functions.php die Absender-Header (mail_from_header, ganz oben) der E-Mail-Funktionen korrekt? Eintr�ge der folgenden Art m�ssen angepa�t werden: $header = "From: FeG Kiel <noreply@feg-kiel.de>\r\n"; 

* Keine Backup-Mails: 
** attend-cfg.php-Eintragung "mail_to" mu� angepa�t werden. Evtl. kommen auch die Backupmails nicht an, weil der SPF-Eintrag falsch ist (s.o.).
