##Kniha návštěv

#Přehled

Jedná se o jednoduchou aplikaci v PHP pro správu knihy návštěv, která umožňuje uživatelům odesílat zprávy se svým jménem, prohlížet všechny zprávy, vidět nejaktivnější autory a seznam posledních návštěvníků. Aplikace využívá MySQL pro ukládání dat a dodržuje základní strukturu podobnou MVC s validací vstupů a ochranou proti XSS útokům.

#Funkce





Odesílání zpráv: Uživatelé mohou zadat své jméno a zprávu prostřednictvím formuláře.



Zobrazení zpráv: Všechny zprávy jsou zobrazeny s jménem autora a časovým razítkem.



Nejaktivnější autoři: Seznam autorů s počtem jejich zpráv.



Poslední návštěvníci: V patičce je zobrazeno posledních 5 jmen návštěvníků.



Bezpečnost: Zahrnuje validaci vstupů a ochranu proti XSS útokům pomocí escapování HTML.



Responzivní design: Základní stylování pro přehledné a uživatelsky přívětivé rozhraní (vyžaduje style.css).
