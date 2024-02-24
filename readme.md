# Mail form - jednoduche odesilani formu ajaxem + antispam honey pot

formular funguje tak, ze odesilani je pomoci JS ajaxem, ktery po uspesnem odeslani form skryje a naopak zobrazi info o odeslani

formular ma jednoduchou antispamovou ochranu pomoci tzv. honey potu - je to extra generovany input, ktery nesmi byt vyplnen (navstevnik stranky ho nevidi, takze ho nevyplni)

## 1. send_formphp8.php přejmenuj jen na send_form.php


## 2. uprava stranky s formularem (zde index.html). Do index.html do <head>sem</head> vlož tenhle styl:
- je nutno pridat styl pro skryti honey potu
```html
  <style>
    .stdmail{
      display: none
    }
  </style>
```
(zde v head, ale samozrejme muze byt pridano do jiz existujicich CSS)

## 3. (Dale je nutno pridat JS generujici honey pot + odesilani mailu ajaxem) Do index.html před </body> vlož: 
```html
  <script src="js/jquery.ajax-forms.min.js" type="text/javascript"></script>
  <script type="text/javascript">
    if(window.addEventListener) {
      window.addEventListener("load",Form_Prepare, false);
    }
  </script>
```

## 4. Vlez do jquery.ajax-form.min.js a uprav ID formuláře (např.#wf-form-Email-input - název musí sedět z ID formu v index.html) BACHA ID TAM JE DVOU MÍSTECH
## 5. Zůstaň v jquery.ajax-form.min.js a uprav i url k php formu (např. {url:"/jana-kase/send_form.php") - pokud to zkoušíš na preview doméně tak tam bude /název/send_form.php, pokud na ostré tak jen /send_form.php

## 6. V index.html najdi formulář a přidej mu parametr action a metod - viz kód níže - pozor, zkontroluj že už tam jedna metoda není (webflow ten parametr má až na konci s předvyplněnou metodou GET - smaž) <form> 

## POZOR pokud web budeš nahrávat na preview doménu první, tak musíš do action napsat:="/jana-kase/send_form.php a až to budeš dávat na ostrou doménu tak musíš smazat tu podsložku!

```html - pokud to půjde na preview doménu
  <form action="/nazev-slozky/send_form.php" method="post" ...

```

```html - pokud to půjde na ostrou doménu doménu
  <form action="/send_form.php" method="post" ...

```

## 7. Jdi do send_form.php a uprav inputy co jsou required musí mít stejné jméno jako mají v index.html, takže pokud mám...
## ...ve formuláři required input email a name="Contact-1-Name", tak i v souboru send_form.php musí mít jméno Contact-1-Name atd. POZOR - inputy musíš změnit i níže ve scriptu, kde se opakují
- required inputs

(inputy u kterych je pozadovano vyplneni, kdyby se menil jejich nazev, tak je nutno zmenit i dale ve skriptu formuláře)


## 8. V send_form.php nastav příjemce ($recipientTo), kopii - pokud chceš ($recipientCc), skrytou kopii ($recipientBcc) a z jakého emailu se email odešle ($senderEmail)
- mail configurations

(prijemce, odesilatel, ...)

## 9. Do složky js vlož soubor jquery.ajax-forms.min.js
## Javascript (soubor /js/jquery.ajax-forms.js)
- jen nahrat (viz vyse), netreba modifikovat
- jquery.ajax-forms.js je zdrojak (nemusi se nahravat do projektu), jquery.ajax-forms.min.js minimalizovana potrebna verze

## 10.První web nahraj na preview doménu: link na bitbucket: https://bitbucket.org/rvlt/preview/src/master/ - repositář si pullni 
## a web nahraj do nové složky
## url preview domény: https://preview.rvlt.cz/doosan-cos/ - vlož název složky a uvidíš preview webu

## 11. Testování formuláře se sice dá i na preview / na jakekoliv domene ... problem ale nastavá, když to bylo zanorené v dalsim adresari, což jenáš případ - to pak musíš nejdřív upravit cestu/urlServer. Viz níže.



## Plausible

## 1. Jako poslední věc je potřeba upravit závorky z Plausible, Webflow z nějakýho důvodu vkládá špatné závorky tzn. v index.html v hlavičce najdi tento kód: <script defer="" data-domain="janakase.cz" src="https://plausible.io/js/script.js"></script> a pokud uvozovky neopovídají tomuto vzoru, uprav je tak, aby byly uvozovky totožné



## PŘI NAHRÁVÁNÍ NA OSTROU DOMÉNU

## 1. Nezapomeň v index.html u <form> smazat u atributu action podložku, takže místo např. "/jana-kase/send_form.php" bude jen "/send_form.php" Je to kvůli tomu, že na previe doméně je podsložka navíc, která není u ostrých webů

## 2. Nezapomeň v jquery.ajax-forms.min.js taky potřeba upravit cestu k formuláři, takže místo: {url:"jana-kase/send_form.php"... bude jen {url:"/send_form.php"  

## 3. Ted můžeš na ostrý doméně vyzkoušet jestli funguje odesílání emailů. 

## 4. Nakonec NEZAPOMEŇ vložit správné emaily klienta a odstranit se z kopie 




