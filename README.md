<h1>Devhub Lexact</h1>
Een webaplicatie gebouwd voor stagebegleiders en stagaire(s) bij het bedrijf Lexact. Waar projecten kunnen worden aangemaakt, notities kunnen worden toegevoegd en feedback kan worden gegeven. Momenteel is de applicatie geconfigureerd voor de software developers binnen Lexact. Bij verdere uitbreiding kan de applicatie ook gebruikt worden voor de IT stagaires.

# Features

- Gebruikers kunnen projecten aanmaken, notities toevoegen en feedback geven. Dit om een beter overzicht te kunnen bieden van alle lopende projecten waar de gebruiker aan deelneemt. Daarom dat per project en notitie kan worden bepaald of deze zichtbaar is voor de gebruiker.
- Mentoren en admins kunnen feedback toevoegen bij een notitie. Zo kan een stagair deze feedback overzichtelijk bewaren en toepassen zonder de originele notitie te verliezen.

# Tech Stack

- Backend: Symfony 7.2.4 (PHP Framework)
- Database: MySQL, Doctrine (ORM)
- Frontend: Twig (Template Engine)

# Installatie

## Vereisten

- PHP 8.4.4 (Latest)
- MySQL or MariaDB
- Docker Desktop of Docker Engine
- WSL extentie

## Start-up guide

Voor het clonen van de repository verzoek ik u om het volgende command te gebruiken:

`git clone git@github.com:bmmrxx/devhub-lexact.git`

Dan kunt u het zip bestand downloaden

## Installeren van de dependencies

Run het volgende command om de vereiste dependencies te installeren, zorg ervoor dat u in de hoofdmap van de project zit. Dit zal de docker container en vereisten voor het project installeren. Zolang de container actief is zal de applicatie op localhost:8000 bereikbaar zijn.

- `Verander de gegevens van de git config in de ![Dockerfile](./Dockerfile)
- Voer het volgende comando uit in de terminal, ga naar de root van de geclonde repository en voer hier uit `docker compose build --no-cache`
- Voer hierna `docker compose up` uit
- `Open de code in een code editor naar keuze en open een remote window` (attach to running container en selecteer /devhub-lexact-app-1)
- Voer het volgende comando in de terminal van de remote window uit: `composer install`

## Configure the database

De database is geconfigureerd met een Docker container. Deze container wordt automatisch gestart wanneer u de comandos hierboven uitvoert.

### Het gebruik van Doctrine

1. Configureer de database connectie, dit gebeurt tijdens het starten van de Docker container. Ondervind u hier problemen mee? Sluit dan de Docker container af en pas de compose.yaml aan.
   ![Configuratie compose.yaml](./compose-yaml)
2. Voer het comando `bin/console doctrine:migrations:execute --up 'DoctrineMigrations\Version20250408102459'`
3. Het toevoegen van mock data (om het project goed te testen raad ik u aan de query hieronder te gebruiken ). Ik heb bewust gekozen om geen registratie optie te implementeren. Dit omdat het een interne omgeving is voor het bedrijf, daarom kan alleen de admin via het admin panel de gebruikers aanmaken. U heeft daarom ook de optie om alleen een admin account toe te voegen en via het admin panel de rest van de gebruikers aan te maken.

```
USE `devhub_lexact_db`;

INSERT INTO `user` (`name`, `email`, `password`, `roles`) VALUES
  ('Test Intern', 'intern@example.com', 'testpassword', '["ROLE_INTERN"]'),
  ('Test Mentor', 'mentor@example.com', 'testpassword', '["ROLE_MENTOR"]'),
  ('Test Admin', 'admin@example.com', 'testpassword', '["ROLE_ADMIN"]');

```

Omdat ik gebruik maak van bcrypt voor het maken van de wachtwoorden is het nodig het wachtwoord aan te passen nadat deze query is uitgevoerd. Verander de wachtwoorden daarom naar de bcrypte versie;

### bcrypt voor "testpassword"

$2a$12$mJBHPQ4Rv39fayJBHaasmuE0wwm5geTL7Dq2Eod70G5taafkGFod2



## Start de applicatie

1. Ga naar de website localhost:8000 om de applicatie te bekijken.
2. U wordt geredirect naar de login pagina, hier kunt u inloggen met de data die u net heeft ingevoegd.

## Hoe het werkt

Devhub lexact is ontworpen om een beter overzicht te kunnen bieden voor stagebegeleiders en stagaires. Naast dat dit mijn portfolio examen is, is het uiteindelijke doel dat deze applicatie echt gebruikt kan worden binnen het bedrijf Lexact.

1. **User**:

- Gebruikers kunnen worden toegevoegd door een admin
- Na de aanmaak van de gebruiker kan deze gebruiker inloggen en de applicatie bekijken met de toegeweszen rollen
- Voor het uitloggen kunt u de logout button in de navigatie bar gebruiken.

2. **Projecten**:

- De gebruiker kan een project toevoegen door bij de tab van projecten op de "Nieuw project" knop te klikken.
- De gebruiker kan een project bewerken door op de "Bewerken" knop te klikken.
- De gebruiker kan een project verwijderen door op de "Verwijderen" knop te klikken.
- De gebruiker kan een project bekijken door op de "Bekijken" knop te klikken.
- Als het project notities bevat wordt dit in het overzicht van projecten getoond als deze wordt bekeken.
- Als er een project verwijderd wordt die bestanden bevat wordt er een extra confirmatie getoond met de hoeveelheid bestanden die het project op dat moment bevat.

3. **Notities**:

- De notitie pagina is te vinden in de navigatie bar onder de "Notitie" knop.
- De gebruiker kan de notitie bewerken door op de "Bewerken" knop te klikken.
- De gebruiker kan de notitie verwijderen door op de "Verwijderen" knop te klikken.
- De gebruiken kan een notitie toevoegen door een project te bekijken en op de knop "Maak een neie notitie" te klikken.
- Als de gebruiker is toegevoegd aan een project & notitie en de functie **admin** of **mentor** heeft kan deze notitie worden gebruikt om feedback te geven aan de gebruiker.

4. **Feedback**:

- De gebruiker kan feedback geven aan een notitie als deze is ingelogd als mentor of admin en aan het project en notitie is toegevoegd.
- Feedback kan worden gegeven door een notitie te bekijken en op de feedback knop te klikken.
- Feedback kan worden verwijderd door op de feedback knop te klikken.
- Feedback wordt genoteerd met de gebruiker die feedback heeft gegeven en de datum waarop het feedback is gegeven.

5. **Profiel**:

- De profiel pagina is te vinden in de navigatie bar onder de "Profiel" knop.
- In de profiel pagina kan de gebruik zijn naam, email, rol en datum van aanmaaking zien.

6. **Instellingen**:

- De instellingen kunnen worden bekeken via de "Instellingen" knop in de navigatie bar.
- Er is een tab met het profiel van de gebruiker, naam en email deze zijn niet aanpasbaar, een over mij die aan pas baar is.
- Er is een tab met de beveiligingsinstellingen, hier kan de gebruiker zijn wachtwoord veranderen.


## Future Features and Improvements

Ik ben erg blij met hoe de aplicatie er tot nu toe uitziet. Voordat ik dit project in werking kan zetten wil ik de volgende features en verbeteringen toevoegen. Na de inlevering van dit project zal ik hier aan door werken in een andere repository. Mocht u hier geintreseerd in zijn dan hoor ik dit graag.

1. Het kunnen uploaden van bestanden
2. Het kunnen plannen van taken
3. Het kunnen krijgen van meldingen over wijzigingen en feedback
4. Het creeren van een uniforme styling en professioneel uiterlijk
5. Het verbeteren van fout meldingen
6. Mentors en interns speciefiek aan elkaar kunnen koppelen voor een duidelijker beeld van de relatie
7. Het uitbreiden van de applicatie voor zowel de software development stagaires als de it stagaires
8. Het beveiligen van de aplicatie voor XSS, de testen, resultaten, conclussies en verbeteringen hiervan kunnen worden terug gevonden bij de onderdelen testen en verbeter voorstellen.
9. Wachtwoord reset feature afmaken
10. Remember me feature afmaken
