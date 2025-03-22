# Blade SaaS Kit

Mijn eigen Saas starter kit met Blade & Alphine.js. (GEEN LIVEWIRE)

## Command op deze starter kit te gebruiken

```
laravel new naamvandeapplicatie --using=rapporteermeer/blade-saas-kit
composer run dev
php artisan optimize
```

## Team Types aanpassen

De applicatie komt met voorgedefinieerde team types (Home Care, Housing Assistance, Outpatient Guidance). Om deze aan te passen:

1. **Bewerk het Seeder bestand**:
   - Open `database/seeders/RolesAndTeamTypesSeeder.php`
   - Pas de `$teamTypes` array aan met jouw gewenste team types:
   ```php
   $teamTypes = [
       ['name' => 'Jouw Team Type 1', 'description' => 'Beschrijving voor team type 1'],
       ['name' => 'Jouw Team Type 2', 'description' => 'Beschrijving voor team type 2'],
       // Voeg meer toe indien nodig
   ];
   ```

2. **Update Area Routes**:
   - Open `routes/areas.php`
   - Pas de route groepen aan zodat ze overeenkomen met je nieuwe team types:
   ```php
   Route::middleware(['auth', 'verified', EnsureUserHasSubscription::class, EnsureTeamTypeMatches::class . ':Jouw Team Type 1'])->group(function () {
       Route::get('jouw-route/dashboard', [JouwController::class, 'index'])->name('areas.jouw-route.index');
       // Voeg andere specifieke routes hier toe
   });
   ```

3. **Maak Controllers voor nieuwe gebieden**:
   - Maak een controller voor elk team type in `app/Http/Controllers/Area/`
   - Volg het patroon van bestaande controllers zoals `HomeCareController.php`

4. **Update Dashboard Doorverwijzing**:
   - Open `app/Http/Controllers/DashboardController.php`
   - Pas de `redirect()` methode's switch statement aan om je nieuwe team types op te nemen:
   ```php
   switch ($team->teamType->name) {
       case 'Jouw Team Type 1':
           return redirect()->route('areas.jouw-route.index');
       // Voeg cases toe voor andere team types
       default:
           return redirect()->route('teams.index');
   }
   ```

5. **Update Sidebar Navigatie**:
   - Open `resources/views/components/layouts/app/sidebar.blade.php`
   - Pas de conditionele navigatie-items aan zodat ze overeenkomen met jouw team types

6. **Voer Database Migraties en Seeders uit**:
   ```bash
   php artisan migrate:fresh --seed
   ```

## Gebruikersrollen aanpassen

De applicatie komt met voorgedefinieerde rollen (SuperAdmin, Owner, Employer, Employee). Om deze aan te passen:

1. **Bewerk het Seeder bestand**:
   - Open `database/seeders/RolesAndTeamTypesSeeder.php`
   - Pas de `$roles` array aan met jouw gewenste rollen:
   ```php
   $roles = [
       ['name' => 'SuperAdmin', 'description' => 'Heeft toegang tot alles'],
       ['name' => 'Owner', 'description' => 'Eigenaar van een team'],
       // Wijzig of voeg meer rollen toe indien nodig
   ];
   ```

2. **Update Rol-gebaseerde Logica**:
   - Controleer en update het `TeamPolicy.php` bestand om ervoor te zorgen dat permissies overeenkomen met je nieuwe rollen
   - Update de `invite()`, `updateMembers()`, en `removeMembers()` methodes om je rolstructuur weer te geven

3. **Update Controllers**:
   - In `InvitationController.php` en `TeamMemberController.php`, update de rolfiltering in methodes zoals `create()` om je nieuwe rollen op te nemen:
   ```php
   $roles = Role::whereIn('name', ['Jouw Rol 1', 'Jouw Rol 2'])->get();
   ```

4. **Update Views**:
   - Controleer uitnodigings- en ledenbewerkingsformulieren + RegistrationController om ervoor te zorgen dat ze de juiste rollen weergeven

5. **Voer Database Migraties en Seeders uit**:
   ```bash
   php artisan migrate:fresh --seed
   ```

## Abonnementsplannen aanpassen

De applicatie gebruikt Stripe voor abonnementsbeheer. Om abonnementsplannen aan te passen:

1. **Maak Plannen aan in Stripe Dashboard**:
   - Log in op je Stripe Dashboard
   - Maak nieuwe producten en prijsplannen aan zoals nodig
   - Noteer de prijs-ID's voor elk plan

2. **Update Environment Variabelen**:
   - Open je `.env` bestand
   - Update of voeg de volgende variabelen toe met je Stripe prijs-ID's:
   ```
   STRIPE_PRICE_MONTHLY_ID=price_xxxxx
   STRIPE_PRICE_THREE_MONTHLY_ID=price_xxxxx
   STRIPE_PRICE_YEARLY_ID=price_xxxxx
   ```
   - Je kunt meer prijs-ID's toevoegen indien nodig voor je abonnementsstructuur

3. **Update Billing View**:
   - Open `resources/views/billing/index.blade.php`
   - Pas de abonnementskaarten aan zodat ze overeenkomen met je prijsstructuur
   - Update de prijsweergave en plannamen

4. **Update BillingController**:
   - Als je nieuwe abonnementstypes hebt toegevoegd of de standaard hebt gewijzigd, update dan de `checkout()` methode in `app/Http/Controllers/BillingController.php`

5. **Wijzig Proefperiode (Optioneel)**:
   - Om de duur van de proefperiode te wijzigen, update de `store()` methode in `app/Http/Controllers/TeamController.php`:
   ```php
   $user->trial_ends_at = now()->addDays(JOUW_PROEF_DAGEN);
   ```
   - Update dit ook in `app/Http/Controllers/Auth/RegistrationController.php`

6. **Update Abonnementscontrole Logica (Optioneel)**:
   - Als je hebt gewijzigd hoe abonnementen werken, moet je mogelijk de `hasValidSubscriptionOrTrial()` methode in het `User` model updaten

## Aanvullende opmerkingen

- Na het maken van wijzigingen in seeders, moet je `php artisan migrate:fresh --seed` uitvoeren, wat je database zal resetten
- Voor productieomgevingen, overweeg het maken van een aangepaste seeder die bestaande gegevens niet wist
- Test abonnementsstromen altijd in Stripe testmodus voordat je live gaat
- Vergeet niet om gerelateerde documentatie of gebruikershandleidingen bij te werken om je wijzigingen weer te geven
