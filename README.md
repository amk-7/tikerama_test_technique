# LARAVEL TICKET API

## Installation

- Clone repository

`git clone ...`

- Duplicate .env.example to .env

`cp .env.example .env`

- Install php dependencies

`cd .. && composer install`

- Install nodejs dependencies

`npm install`

- Configure Google SMTP in your .env file

`MAIL_MAILER=smtp`
`MAIL_HOST=smtp-mail.outlook.com`
`MAIL_PORT=587`
`MAIL_USERNAME=your_outlook_email`
`MAIL_PASSWORD=your_outlook_password`
`MAIL_ENCRYPTION=tls`
`MAIL_FROM_ADDRESS="hello@example.com"`
`MAIL_FROM_NAME="${APP_NAME}"`

- Run Php Laravel migration and seeder

`php artisan migrate`
`php artisan db.seed`


- Run Php Laravel server

`php artisan serv`


## Api Endpoints 

*BASE URL* : `http://127.0.0.1:8000/api`

- Registrate to api 
`GET /`

- Login to api
`POST /login`

data example :
```code 
{
    "email": "abdoulmalikkondi8@gmail.com",
    "password": "123456789"
}
```

- Logout to api
`POST /logout`

- Api documentation 
`GET /documentation`

- Consult the list of all events with pagination.
`GET /events`

- Consult the list of current events with pagination.
`GET /events/upcoming`

- Consult the list of ticket types available for a given event
`GET /events/ticket/types/{event_id}`

- Create a new intent order.
`POST /orders/intent`

data example :
```code 
{
    "price": 15000,
    "type": "VIP",
    "user_email": "abdoul@gmail.com",
    "user_phone" : "+22893561240",
    "expiration_date" : "2024/07/15"
}
```

- validate an order intention : La réponse inclura une URL pour télécharger
les tickets de la commande.

- consulter toutes les commandes effectuées par le client (utilisateur de
l’API) : Pagination incluse et modification de la base de données pour intégrer cette
fonctionnalité.


