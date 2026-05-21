[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/JXuiURJJ)
#  Kvitter - En mikrobloggplattform

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)

##  Om Projektet

**Kvitter** är en social medieplattform inspirerad av Twitter där användare kan dela korta meddelanden (kvitter). Projektet är utvecklat som ett slutprojekt för kurserna **Webbutveckling 2** och **Webbserverprogrammering 1** och uppfyller samtliga krav för **C-nivå**.

Live-demo: `http://localhost/weuweb2/slutprojekt/slutprojekt-Rahmah-TE23/public/`

---

## Funktioner

 Funktion = Beskrivning 

 **Användarhantering** = Registrering, inloggning, profil med statistik 
  **Kvitter** = Skapa, visa och ta bort korta inlägg (max 280 tecken) 
 **Like/Gilla** = Användare kan gilla andras kvitter 
  **Admin-panel** = Hantera användare, ta bort konton, se statistik 
  **Digital klocka** = Canvas-ritad klocka som uppdateras varje sekund 
  **Säkerhet** = XSS-skydd, SQL-injection skydd, CSRF-tokens, hashade lösenord 
  **Responsiv design** = Fungerar på mobil, surfplatta och dator 
  **GDPR** = Godkännande vid registrering, rätt att bli bortglömd 

---

##  Design & Färger

 Färg = HEX = Användning 

| **Vista Blue** - `#78A2D2` - Primärfärg - navbar, knappar, headers 
| **Mindaro** - `#FEFFAF` - Sekundärfärg - hover-effekter, accenter 

### Typografi
 Element - Storlek - Vikt 

Logotyp "KVITTER"  28px  Fet 
Rubriker - 24px - Fet 
Brödtext - 16px - Normal 
 Navigering - 16px - Normal 





## Folder Structure
```
slutprojekt /
├── config/
│ └── env.php # Function to read .env files
├── database/
│ ├── db.php # PDO database connection
│ └── user_queries.php # Functions for user-related queries
├── includes/
│ ├── header.php # Page header with navigation
│ ├── nav.php # Navigation menu
│ └── footer.php # Page footer
├── public/
│ ├── index.php # Home page
│ ├── about.php # About page
│ ├── contact.php # Contact form page
│ ├── login.php # Login page (optional)
│ ├── register.php # Registration page (optional)
│ ├── css/
│ │ └── styles.css # All styles
│ └── js/
│ └── app.js # JavaScript (deferred)
├── .env.example # Example environment file
└── README.md # Project documentation

```
---

## Requirements

- PHP 7.4+  
- MySQL or MariaDB  
- Web server (Apache, Nginx, or PHP built-in server)  
- Optional: Composer (if you want to add packages in the future)

---

## Getting Started

### 1. Clone the Template

Use the template

### 2. Configure the Environment
Copy .env.example to .env and update the database credentials:

DB_USER=root
DB_PASS=Root

Keep .env out of version control. Never commit it with real credentials.

USE .gitignore