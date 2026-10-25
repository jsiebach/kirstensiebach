# kirstensiebach.com

Laravel 13 + Filament 5 site, deployed with Docker on a single EC2 server.

## Local development

Needs PHP 8.4+, Composer and Node 22+. No Docker or database server: local runs use SQLite.

```sh
composer setup   # first time: installs, creates database/database.sqlite, seeds sample content
composer dev     # serves the site at http://127.0.0.1:8000
```

The admin is at http://127.0.0.1:8000/admin (local login: `jsiebach@gmail.com` / `admin`).
Run `php artisan migrate:fresh --seed` to reset the local data.

Frontend assets are built with Vite and **committed** (nginx serves them from the git checkout):

```sh
npm run dev      # live reload while editing resources/js or resources/sass
npm run build    # before committing; CI fails if public/build is out of date
```

## Tests

```sh
php artisan test                        # everything
php artisan test --testsuite=Unit,Feature
php artisan test --testsuite=Browser    # Playwright: npx playwright install chromium
```

## Deploying

Push to `dev`. GitHub Actions runs the tests, merges into `master`, builds the Docker image
and runs `deploy.sh` on the server, which finishes with a smoke check of the live site.

Server housekeeping runs from systemd timers: nightly backups (`backup.sh`, kept 14 days in
`/var/backups/kirstensiebach`) and twice-daily certificate renewal (`renew.sh`).
