I have used Symfony, rather than Lumen and Svelte rather than React.

#### Install

`docker-compose up -d`

On boot the php container runs `composer install` internally, so you might need to wait 15-30 seconds for the service to be
ready.

`docker-compose logs -f php`

Watch until you see the "GET /ping" log entrries every 10 seconds.

There are two commands you can try:

`docker-compose exec php bin/console app:import-csv:league`

`docker-compose exec php bin/console app:import-csv:internal`

The first command uses the league/csv package (which is what I would use for an actual production site).
It handles character encodings and supports streams which are useful for much larger files.

The second command uses a minimal CSV parser I wrote for this challenge (as I assume that's what you want to see).
It basically assumes the CSV column order never changes and that the data is always properly formatted.

To reset the database to try different import commands use:

`docker-compose exec php bin/dev/reset`

To run tests:

`docker-compose exec php bin/phpunit` <-- Execute the actual tests

The tests just verify that the validation attributes on the entities are correct.
There are no functional tests.

`https://localhost` to access the frontend. Accept the self-signed certificate.

There are two entry points:

`https://localhost/html`

This is server generated HTML (no JS). Quicker for the user (less time to first meaningful paint).
Fewer HTTP connections (no second call for API). Works fine on (rare) devices that don't have JS enabled.

`https://localhost/js`

This is a JS version (written in Svelte). Uses JS fetch method for loading data from API.
Allows page transition without reload. Works like a single page app (SPA), so no changes to url (e.g History.pushState)
but that could be done.

#### Notes on implementation choices and suggestions for future improvements

I use 3 different entities, Jobs, Applicants and JobApplicants.

Both console commands immediately hand off the actual CSV processing to a service class, which could be used elsewhere
in a controller for example. The parsing of the CSV file and conversion to the entities is handled by different classes
to keep the business logic (e.g. extracting the location from the description column) separate.

The console command assumes that the file is always in the /import/data-files.csv location.
It could be modified to accept a file path from the user, but as with any user input, you then need to check it more
thoroughly.
For example, checking that the path argument doesn't use directory traversal to try importing sensitive files, such as
`docker-compose exec php bin/console app:import-csv:league ./../../some/credentials.txt`

Not such a big concern as it's CLI only, but in general less options means less user error or security issues.

I use [caddy](https://caddyserver.com/) web server as it provides an HTTPS environment in development
(so no CORS issues to deal with).
In production mode it also automatically generates a [Let's Encrypt](https://letsencrypt.org/) certificate
for https (so less DevOps time spent on server infrastructure).

None of the entities have unique attributes set, so re-running the CLI import will add duplicate entries.
Unique constraints should be added and validated as records are processed.

In this code, the date field is saved without a timezone.
In a production environment I would store this as UTC in the database and only display it in the users
local timezone (e.g. so a Perth user applying for a Perth job sees the time that they applied, not the time for Sydney).
I would suggest that the tool that generates the CSV used ISO8601 format for the date column as well.

For the Svelte JS frontend, I use API Platform, which provides a REST API (and GraphQL) at `/api`
Only the Jobs entity is exposed, applicants are access via their Doctrine relationship mapping.
Only the GET item and collection operations are enabled as well (no POST, PATCH, DELETE).

Turning the entities into API resources and providing validation is all done via PHP8 attributes.
