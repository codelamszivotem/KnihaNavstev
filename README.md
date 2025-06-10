Kniha návštěv

Přehled

Jedná se o jednoduchou aplikaci v PHP pro správu knihy návštěv, která umožňuje uživatelům odesílat zprávy se svým jménem, prohlížet všechny zprávy, vidět nejaktivnější autory a seznam posledních návštěvníků. Aplikace využívá MySQL pro ukládání dat a dodržuje základní strukturu podobnou MVC s validací vstupů a ochranou proti XSS útokům.

Funkce





Odesílání zpráv: Uživatelé mohou zadat své jméno a zprávu prostřednictvím formuláře.



Zobrazení zpráv: Všechny zprávy jsou zobrazeny s jménem autora a časovým razítkem.



Nejaktivnější autoři: Seznam autorů s počtem jejich zpráv.



Poslední návštěvníci: V patičce je zobrazeno posledních 5 jmen návštěvníků.



Bezpečnost: Zahrnuje validaci vstupů a ochranu proti XSS útokům pomocí escapování HTML.



Responzivní design: Základní stylování pro přehledné a uživatelsky přívětivé rozhraní (vyžaduje style.css).

Požadavky





PHP: Verze 7.4 nebo vyšší.



MySQL: Funkční MySQL server.



Webový server: Apache, Nginx nebo jiný server podporující PHP.



Composer: Volitelný, pro správu závislostí (v tomto projektu není použit, ale lze rozšířit).

Instalace





Klonování repozitáře:

git clone https://github.com/váš-uživatel/kniha-navstev.git
cd kniha-navstev



Nastavení databáze:





Vytvořte MySQL databázi (např. kniha_navstev).



Importujte následující SQL pro vytvoření tabulky messages:

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



Konfigurace připojení k databázi:





Vytvořte soubor db.php s vašimi databázovými údaji:

<?php
$host = 'localhost';
$dbname = 'kniha_navstev';
$username = 'váš_uživatel';
$password = 'vaše_heslo';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Připojení selhalo: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
} catch (Exception $e) {
    die("Chyba: " . $e->getMessage());
}
?>



Nasazení souborů:





Umístěte následující soubory do kořenového adresáře vašeho webového serveru (např. /var/www/html):





index.php



insert.php



db.php



style.css (vytvořte základní stylopis, není součástí repozitáře).



Ujistěte se, že webový server má práva pro zápis do adresáře.



Přístup k aplikaci:





Otevřete prohlížeč a přejděte na http://váš-server/kniha-navstev.

Struktura souborů

kniha-navstev/
├── db.php          # Konfigurace připojení k databázi
├── index.php       # Hlavní stránka s formulářem, zprávami a statistikami autorů
├── insert.php      # Zpracování odeslání zpráv
├── style.css       # Stylopis (není součástí, vytvořte vlastní)
└── README.md       # Tento soubor

Použití





Odeslání zprávy:





Na hlavní stránce (index.php) zadejte jméno a zprávu do formuláře.



Klikněte na „Odeslat“. Prázdné vstupy jsou přesměrovány zpět na formulář.



Zobrazení zpráv:





Zprávy jsou zobrazeny v sestupném pořadí podle času vytvoření.



Dlouhé zprávy (>200 znaků) jsou zkráceny s „...“ pro přehlednost.



Statistiky autorů:





Sekce „Nejaktivnější autoři“ vypisuje všechny autory a počet jejich zpráv.



Poslední návštěvníci:





V patičce je zobrazeno jméno posledních 5 návštěvníků, kteří odeslali zprávu.

Bezpečnostní poznámky





Validace vstupů: V insert.php jsou kontrolovány prázdné vstupy, aby se zabránilo prázdným zprávám.



Ochrana proti XSS: Všechny uživatelské vstupy jsou escapovány pomocí htmlspecialchars() před zobrazením.



SQL Injection: V insert.php jsou použity připravené dotazy (prepared statements) pro prevenci SQL injekcí.

Přizpůsobení





Stylování: Vytvořte soubor style.css pro úpravu vzhledu knihy návštěv.



Databáze: Upravte db.php pro připojení k jiné databázi.



Funkce: Rozšiřte index.php o stránkování, editaci zpráv nebo autentizaci uživatelů.

Přispívání





Vytvořte fork repozitáře.



Vytvořte novou větev (git checkout -b feature/vaše-funkce).



Proveďte změny a commitněte je (git commit -m "Přidána vaše funkce").



Pushněte větev (git push origin feature/vaše-funkce).



Vytvořte pull request.

Licence

Tento projekt je licencován pod MIT licencí. Podrobnosti naleznete v souboru LICENSE.

Kontakt

Pro nahlášení problémů nebo návrhy vytvořte issue na GitHubu nebo kontaktujte vlastníka repozitáře.
