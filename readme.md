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

## Start-up guide

Voor het clonen van de repository verzoek ik u om het volgende command te gebruiken:

`git clone git@github.com:bmmrxx/devhub-lexact.git`

Mocht u hier problemen mee ondervinden dan kunt u deze repository downloaden via de volgende link:

## Installeren van de dependencies

Run het volgende command om de vereiste dependencies te installeren, zorg ervoor dat u in de hoofdmap van de project zit. Dit zal de docker container en vereisten voor het project installeren. Zolang de container actief is zal de applicatie op localhost:8000 bereikbaar zijn.

- `Verander de gegevens van de git config in de ![Dockerfile](./Dockerfile)
- `docker compose build`
- `composer install`
- `docker compose up`

## Configure the database

De database is geconfigureerd met een Docker container. Deze container wordt automatisch gestart wanneer u de comandos hierboven uitvoert.

### Het gebruik van Doctrine

1. Configureer de database connectie, dit gebeurt tijdens het starten van de Docker container. Ondervind u hier problemen mee? Sluit dan de Docker container af en pas de compose.yaml aan.
   ![Configuratie compose.yaml](./compose-yaml)
2. Maak de database aan, dit maakt de database met de juiste naam aan.
   `php bin/console doctrine:database:create`
3. Maak de tabellen aan, dit maakt de tabellen met de juiste naam en type aan.
   `php bin/console doctrine:migrations:migrate`
4. Het toevoegen van mock data (om het project goed te testen raad ik u aan de query hieronder te gebruiken ). Ik heb bewust gekozen om geen registratie optie te implementeren. Dit omdat het een interne omgeving is voor het bedrijf, daarom kan alleen de admin via het admin panel de gebruikers aanmaken. U heeft daarom ook de optie om alleen een admin account toe te voegen en via het admin panel de rest van de gebruikers aan te maken.

```
USE `devhub_lexact_db`;

INSERT INTO `user` (`name`, `email`, `password`, `roles`) VALUES
  ('Test Intern', 'intern@example.com', '$2a$12$mJBHPQ4Rv39fayJBHaasmuE0wwm5geTL7Dq2Eod70G5taafkGFod2', '["ROLE_INTERN"]'),
  ('Test Mentor', 'mentor@example.com', '$2a$12$mJBHPQ4Rv39fayJBHaasmuE0wwm5geTL7Dq2Eod70G5taafkGFod2', '["ROLE_MENTOR"]'),
  ('Test Admin', 'admin@example.com', '$2a$12$mJBHPQ4Rv39fayJBHaasmuE0wwm5geTL7Dq2Eod70G5taafkGFod2', '["ROLE_ADMIN"]');

```

Omdat ik gebruik maak van bycrypt voor het maken van de wachtwoorden is het nodig het wachtwoord in de query te veranderen. Bij dit voorbeeld is het wachtwoord "testpassword". Deze kunt u gebruiken om in te loggen.

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

## Endpoints

Dit zijn de endpoints die gebruikt worden in de web applicatie.

---

Name Method Scheme Host Path Controller

---

admin ANY ANY ANY /admin App\Controller\Admin\DashboardController::index()  
 admin_file_index GET ANY ANY /admin/file App\Controller\Admin\FileCrudController::index()  
 admin_file_new GET|POST ANY ANY /admin/file/new App\Controller\Admin\FileCrudController::new()  
 admin_file_batch_delete POST ANY ANY /admin/file/batch-delete App\Controller\Admin\FileCrudController::batchDelete()  
 admin_file_autocomplete GET ANY ANY /admin/file/autocomplete App\Controller\Admin\FileCrudController::autocomplete()  
 admin_file_render_filters GET ANY ANY /admin/file/render-filters App\Controller\Admin\FileCrudController::renderFilters()  
 admin_file_edit GET|POST|PATCH ANY ANY /admin/file/{entityId}/edit App\Controller\Admin\FileCrudController::edit()  
 admin_file_delete POST ANY ANY /admin/file/{entityId}/delete App\Controller\Admin\FileCrudController::delete()  
 admin_file_detail GET ANY ANY /admin/file/{entityId} App\Controller\Admin\FileCrudController::detail()  
 admin_note_index GET ANY ANY /admin/note App\Controller\Admin\NoteCrudController::index()  
 admin_note_new GET|POST ANY ANY /admin/note/new App\Controller\Admin\NoteCrudController::new()  
 admin_note_batch_delete POST ANY ANY /admin/note/batch-delete App\Controller\Admin\NoteCrudController::batchDelete()  
 admin_note_autocomplete GET ANY ANY /admin/note/autocomplete App\Controller\Admin\NoteCrudController::autocomplete()  
 admin_note_render_filters GET ANY ANY /admin/note/render-filters App\Controller\Admin\NoteCrudController::renderFilters()  
 admin_note_edit GET|POST|PATCH ANY ANY /admin/note/{entityId}/edit App\Controller\Admin\NoteCrudController::edit()  
 admin_note_delete POST ANY ANY /admin/note/{entityId}/delete App\Controller\Admin\NoteCrudController::delete()  
 admin_note_detail GET ANY ANY /admin/note/{entityId} App\Controller\Admin\NoteCrudController::detail()  
 admin_project_index GET ANY ANY /admin/project App\Controller\Admin\ProjectCrudController::index()  
 admin_project_new GET|POST ANY ANY /admin/project/new App\Controller\Admin\ProjectCrudController::new()  
 admin_project_batch_delete POST ANY ANY /admin/project/batch-delete App\Controller\Admin\ProjectCrudController::batchDelete()  
 admin_project_autocomplete GET ANY ANY /admin/project/autocomplete App\Controller\Admin\ProjectCrudController::autocomplete()  
 admin_project_render_filters GET ANY ANY /admin/project/render-filters App\Controller\Admin\ProjectCrudController::renderFilters()  
 admin_project_edit GET|POST|PATCH ANY ANY /admin/project/{entityId}/edit App\Controller\Admin\ProjectCrudController::edit()  
 admin_project_delete POST ANY ANY /admin/project/{entityId}/delete App\Controller\Admin\ProjectCrudController::delete()  
 admin_project_detail GET ANY ANY /admin/project/{entityId} App\Controller\Admin\ProjectCrudController::detail()  
 admin_user_index GET ANY ANY /admin/user App\Controller\Admin\UserCrudController::index()  
 admin_user_new GET|POST ANY ANY /admin/user/new App\Controller\Admin\UserCrudController::new()  
 admin_user_batch_delete POST ANY ANY /admin/user/batch-delete App\Controller\Admin\UserCrudController::batchDelete()  
 admin_user_autocomplete GET ANY ANY /admin/user/autocomplete App\Controller\Admin\UserCrudController::autocomplete()  
 admin_user_render_filters GET ANY ANY /admin/user/render-filters App\Controller\Admin\UserCrudController::renderFilters()  
 admin_user_edit GET|POST|PATCH ANY ANY /admin/user/{entityId}/edit App\Controller\Admin\UserCrudController::edit()  
 admin_user_delete POST ANY ANY /admin/user/{entityId}/delete App\Controller\Admin\UserCrudController::delete()  
 admin_user_detail GET ANY ANY /admin/user/{entityId} App\Controller\Admin\UserCrudController::detail()  
 _app_contact ANY ANY ANY /contact App\Controller\ContactController::index()_
_app_feedback ANY ANY ANY /feedback App\Controller\FeedbackController::index()_
_file ANY ANY ANY /file App\Controller\FileController::filePage()_
home ANY ANY ANY / App\Controller\HomeController::homePage()  
 app_notes GET ANY ANY /notes App\Controller\NoteController::index()  
 note_new GET|POST ANY ANY /notes/new App\Controller\NoteController::create()  
 note_add_feedback POST ANY ANY /notes/{id}/feedback App\Controller\NoteController::addFeedback()  
 note_delete POST ANY ANY /notes/{id}/delete App\Controller\NoteController::delete()  
 note_get GET ANY ANY /notes/{id} App\Controller\NoteController::getNote()  
 note_edit GET|POST ANY ANY /notes/{id}/edit App\Controller\NoteController::edit()  
 app_update_profile ANY ANY ANY /profile App\Controller\ProfileController::profile()  
 project_index GET ANY ANY /project App\Controller\ProjectController::index()  
 project_new GET|POST ANY ANY /project/new App\Controller\ProjectController::new()  
 project_show GET ANY ANY /project/{id} App\Controller\ProjectController::show()  
 project_edit GET|POST ANY ANY /project/{id}/edit App\Controller\ProjectController::edit()  
 project_delete POST ANY ANY /project/{id}/delete App\Controller\ProjectController::delete()  
 app_forgot_password_request ANY ANY ANY /reset-password App\Controller\ResetPasswordController::request()  
 app_check_email ANY ANY ANY /reset-password/check-email App\Controller\ResetPasswordController::checkEmail()  
 app_reset_password ANY ANY ANY /reset-password/reset/{token} App\Controller\ResetPasswordController::reset()  
 app_login ANY ANY ANY /login App\Controller\SecurityController::login()  
 app_logout ANY ANY ANY /logout App\Controller\SecurityController::logout()  
 app_settings ANY ANY ANY /settings App\Controller\SettingController::settingsPage()

---

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
