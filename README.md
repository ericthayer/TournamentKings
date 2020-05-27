# TournamentKings.com

This is the TournamentKings.com web app for managing tournaments.

# Installation

This is a standard [Laravel](http://laravel.com/) app, so the setup will be pretty basic:

    composer install # add "--no-dev" on production 
    npm install
    
On development, create the frontend files from Vue:

`npm run dev`

Or watch the Vue files and automatically create new frontend files:

`npm run watch`

Make files for production

`npm run production`

Running `composer install` should also run the migrations and copy the example config files.

Run `php artisan db:seed` to seed the DB.

# Configuration

The `.env` has PHP's config in addition to the files in the `config` folder. For JavaScript, use `resources/js/config.js`.
This file is treated just like a `.env` so that it is ignored from tracking and has an accompanying `.example` file that is committed.

## Square

To enable Square payments, you must set the following `.env` variables:

* `SQUARE_ACCESS_TOKEN`
* `SQUARE_LOCATION_ID`

Also, the following `resources/js/config.js` variable must be set:

* `SQUARE_APP_ID`

You must either set these all to Sandbox or prod credentials. You can only use the test payment data with the Sandbox settings.

# Tests

Before running tests, make sure to copy `.env.testing.example` to `.env.testing` and configure it to refer to a test DB,
such as a DB called "test" with the same credentials as the default one.

Dusk needs its own `.env`, such as `.env.dusk.local`, in order to guarantee that the values in the file are set while it runs.

Run the tests with:

    vendor/bin/phpunit && php artisan dusk

# Deployment

## Dev

One major deployment restriction for dev is that the Square payment form requires that the site host be `localhost` or otherwise
you must use a real SSL certificate.

[Laravel Queues](https://laravel.com/docs/5.8/queues) are used, so the system running this site must have the `cron`
program and related scripts available. 
