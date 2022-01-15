## Package Creator

This was used as the example for creating a custom Laravel package for my Laravel projects.

# Requirements
Annotations package
`composer require laravelcollective/annotations`

- a "packages" directory
- Update composer.json PSR-4 with the "packages" path


`alias cc='clear;composer dump-autoload; php artisan cache:clear; php artisan view:clear; php artisan config:clear; php artisan config:clear; php artisan route:clear'`
`alias a_route='php artisan route:clear; php artisan route:scan;php artisan route:list'`


#### expects:
packages/

#### attempts to create:
packages/<package-name>/Http
packages/<package-name>/Http/Controllers
packages/<package-name>/Http/Controllers/<package-name>Controller.php

packages/<package-name>/Listeners

packages/<package-name>/Models
packages/<package-name>/Models/<model-name>.php
packages/<package-name>/Models/Logic
packages/<package-name>/Models/Logic/<model-name>Logic.php

packages/<package-name>/Observers
packages/<package-name>/Observers/<model-name>Observer.php

packages/<package-name>/Providers
packages/<package-name>/Providers/<package-name>ServiceProvider.php

packages/<package-name>/Tests
packages/<package-name>/Views
packages/<package-name>/Views/index.blade.php
