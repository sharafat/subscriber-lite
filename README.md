<h1 align="center">
    <img src="https://raw.githubusercontent.com/sharafat/subscriber-lite/main/resources/images/logo.png" width="100">
    <br/>
    Subscriber Lite
</h1>


## About

This repository contains a simple Laravel-Vue.js application built with the purpose of demonstrating many of
Laravel and Vue.js features.

The Subscriber Lite application aims to simplify maintaining the subscribers list of your awesome newsletter.
Along with your subscriber's basic information such as name and email, Subscriber Lite allows you to create custom
fields for collecting more information from your subscribers. You can also specify the type of a custom field
(<i>string, number, date, boolean</i>), as well as indicate if the field is required to be filled-up.

The tech stack used to build this application is:

- PHP 8
- Laravel 9
- MySQL 8
- Vue.js 3
- Tailwind CSS 3
- Alpine.js 3 (only to make the HTML template mobile-responsive)

Feature showcases:

- HTTP JSON API
- Request validation
- Eloquent ORM and model relationships/associations
- Database migration and seeding
- Feature tests
- PSR-2/PSR-12 compliance of source code

<i><b>Note:</b> To keep things really simple and short, authentication and authorization have been avoided.
Also, for the same reason, multi-user scenario has been ignored.</i>

## Setup and Run

1. Make sure Docker is installed on the system. Installation instructions can be found at
[Docker Desktop](https://www.docker.com/products/docker-desktop/) (for Mac OS) and
[Docker Compose](https://docs.docker.com/compose/install/) (for Linux).
2. Clone the git repository:
    ```bash
    git clone https://github.com/sharafat/subscriber-lite.git
    ```
3. Navigate to the application directory:
    ```bash
    cd subscriber-lite
    ```
4. Install composer dependencies using docker to install Sail:
    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php81-composer:latest \
        composer install --ignore-platform-reqs
    ```
5. Create `.env` file from `.env.example`:
    ```bash
   cp .env.example .env
   ```
6. Fire up Docker containers in the background:
   ```bash
   ./vendor/bin/sail up -d
   ```
7. Enjoy a cup of coffee while your machine is working hard. 😊
8. Install Javascript dependencies and build front-end:
    ```bash
    ./vendor/bin/sail npm install
    ./vendor/bin/sail npm run dev
    ```
9. Run database migrations and populate database with seed data:
    ```bash
    ./vendor/bin/sail artisan migrate
    ./vendor/bin/sail artisan db:seed
    ```
10. Browse [http://localhost/](http://localhost/)

### Run Tests

To run PHP test cases, execute:
```bash
./vendor/bin/sail test
```


## Demo

![](https://i.imgur.com/ACLlXn9.png)
<p align="center">Subscriber List Page</p>
<br/>

![](https://i.imgur.com/SZTNj3D.png)
<p align="center">Subscriber Add/Edit Page</p>
<br/>

![](https://i.imgur.com/J3A9B1g.png)
<p align="center">Custom Field List Page</p>
<br/>

![](https://i.imgur.com/6wBD4ZC.png)
<p align="center">Custom Field Add/Edit Page</p>


## Contact

If you have questions/comments, please drop me a line at [sharafat_8271@yahoo.co.uk](mailto:sharafat_8271@yahoo.co.uk).
