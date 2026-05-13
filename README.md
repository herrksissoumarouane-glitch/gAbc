
gAbc – Laravel-basiertes Schul- und Ausbildungsmanagementsystem

## 📌 Projektbeschreibung
gAbc ist ein webbasiertes Verwaltungssystem, entwickelt mit **Laravel**, zur Organisation einer Schule bzw. eines Ausbildungszentrums.

Das System wurde im Rahmen eines Teamprojekts entwickelt und ermöglicht die Verwaltung von Formatoren, Stagiaires, Gruppen sowie Abwesenheiten. Zusätzlich unterstützt es den Import und Export von Excel-Dateien zur Datenverarbeitung.

---

## 👨‍💻 Projektteam
Das Projekt wurde von einem Team aus 4 Entwicklern unter der Betreuung eines Dozenten erstellt.

### Meine Aufgaben:
- Verwaltung der **Abwesenheiten**
- Verwaltung der **Formatoren**
- Verwaltung der **Gruppen**

---

## ⚙️ Hauptfunktionen

### 👥 Benutzerrollen & Authentifizierung
Das System unterstützt mehrere Rollen mit unterschiedlichen Rechten:
- Admin
- Direktor
- Gestionnaire
- Formateur
- Stagiaire

✔ Sichere Anmeldung und Zugriffskontrolle

---

### 👨‍🏫 Formatorenverwaltung
- Verwaltung von permanenten und vakanten Formatoren
- Import von Formatorendaten aus Excel-Dateien
- Zuweisung von Formatoren zu Gruppen
- Anzeige nach Kategorien und Typen

---

### 🎓 Gruppen- & Stagiaire-Verwaltung
- Erstellung und Verwaltung von Gruppen
- Zuweisung von Stagiaires zu Gruppen
- Strukturierte Organisation nach Ausbildungsbereichen

---

### 📅 Abwesenheitsverwaltung
- Erfassung von Abwesenheiten pro Person
- Verwaltung nach Gruppen
- Übersicht und Historie der Abwesenheiten

---

### 📤 Excel Import & Export
Das System verarbeitet mehrere Excel-Dateien:

- Stagiaires.xlsx  
- formateurPermanent.xlsx  
- formateurVacataire.xlsx  
- groupes.xlsx  
- filieres.xlsx  
- complexeEFP.xlsx  

Funktionen:
- Import von Daten in die Datenbank
- Export von Abwesenheiten nach Woche und Gruppe
- Download von Berichten für externe Nutzung

---'
## ⭐ Key Features
- Multi-Role System (Admin, Direktor, Formateur, etc.)
- Abwesenheitsmanagement
- Gruppen- und Benutzerverwaltung
- Excel Import/Export
- Laravel MVC Architektur
-----

## 🧱 Verwendete Technologien
- **Backend:** Laravel (PHP)
- **Frontend:** HTML, CSS, JavaScript, Bootstrap
- **Build Tools:** Vite, Tailwind CSS
- **Datenbank:** MySQL
- **Excel Verarbeitung:** Laravel Excel

---

## 📂 Projektstruktur
```
gAbs/
│
├── 📁 excelFiles/              # Dateien für Import/Export (Excel)
│   ├── Stagiaires.xlsx
│   ├── complexeEFP.xlsx
│   ├── filieres.xlsx
│   ├── formateurPermanent.xlsx
│   ├── formateurVacataire.xlsx
│   └── groupes.xlsx
│
├── 📁 gAbs/                    # Haupt-Laravel Projekt
│   ├── app/                    # Controller & Models
│   ├── bootstrap/              # Framework Bootstrap
│   ├── config/                 # Konfiguration
│   ├── database/               # Migrationen & Seeder
│   ├── public/                 # Entry Point (index.php)
│   ├── resources/             # Views (Frontend)
│   ├── routes/                # Web Routes
│   ├── storage/               # Dateien & Cache
│   └── tests/                 # Tests
│
├── 📄 .gitignore              # Git Ignore Regeln
├── 📄 README.md               # Projektdokumentation
├── 📄 gAbs 4.code-workspace   # VS Code Workspace Datei

````

---

## 🎯 Ziel des Projekts
Das Ziel dieses Projekts ist die Digitalisierung und Optimierung der Schul- und Ausbildungsverwaltung durch:
- effiziente Organisation von Formatoren und Stagiaires
- strukturierte Gruppenverwaltung
- automatisierte Abwesenheitskontrolle
- einfache Datenverarbeitung über Excel-Dateien

---

## 🚀 Installation & Setup

```bash
git clone https://github.com/herrksissoumarouane-glitch/gAbc.git
cd gAbc/gAbs
````

### 📦 Abhängigkeiten installieren

```bash
composer install
npm install
```

### ⚙️ Umgebung konfigurieren

* `.env` Datei erstellen oder anpassen
* Datenbank (MySQL) verbinden

### 🗄️ Migrationen ausführen

```bash
php artisan migrate
```

### ▶️ Projekt starten

```bash
php artisan serve
```

---

## 📌 Hinweis

Dieses Projekt wurde als **Teamprojekt** entwickelt und basiert auf realistischen Anforderungen einer Schul- und Ausbildungsverwaltung.

---

## 👨‍💻 Entwickler

Projekt erstellt von: Marouane

```

