# Symfony FusionAuth task

## Steps to install project:
1) Git Init and `git clone https://github.com/dcroopev/symfony-fusionauth-task.git`
2) `main` is the main branch - `git checkout main`
3) `composer install`
4) `docker compose up`

###  _Commands that can come in handy:_

- `composer update`
- `docker compose up -d` for detached mode 
- `docker compose up -d --build` for rebuilding the containers from scratch 
- `docker compose down` for removing the containers
- `docker compose down -v`  with the `-v` option to remove the volumes associated with the containers in the docker-composer configuration. Very suitable if the `kickstart` fails for one reason, and you want to build the fusionauth from scratch.

## Configure the FusionAuth:


 ### Kickstart
Before running `docker compose up` command, you should configure your `.env` file. You can copy everything from `.env.example` and fill the following values:
````
###Credentials for the `Fusionauth` super-admin
FUSIONAUTH_ADMIN_EMAIL=
FUSIONAUTH_ADMIN_PASSWORD=


###Parameters for the SMTP configuration in the `Default` tenant
###Locally, I am using the google smtp - smtp.gmail.com with my own credentials (setup a Google App Password)

FUSIONAUTH_DEFAULT_FROM_EMAIL=
FUSIONAUTH_DEFAULT_SMTP_HOST=
FUSIONAUTH_DEFAULT_SMTP_USERNAME=
FUSIONAUTH_DEFAULT_SMTP_PASSWORD=
````

- By default, an application named "Application 1" should be created with refresh token settings `on`.
- Default tenant should be configured with the webhook event of 'User Registration' `on`
- Webhook with the appropriate url `http://local.docker.api:80/api/webhook/event`
- Email template `User Registration` for the webhook endpoint to use

<br>
<br>

### Manual
#### If the kickstart doesn't manage to apply the default settings here is a step-by-step setup guide:


1) Create Administrator account
2) Create API key from Settings -> API Keys and put it in your env variables as `FUSIONAUTH_API_KEY`. For the purpose of the exercise, make it a superkey (default)


#### _Tenant_ settings:
Edit the `Default` tenant:
- `Email` tab (for the webhook) - Personally, I used the Gmail smtp as I configured one with my own personal account and a `App passwords`
- `Webhook` tab - allow only the `user.registration.create` event

#### _Application_ settings:
1) In the Application menu we can add several `Applications`
2) On each of them go to `Security` submenu where and check `ON` all three options: `Require an API key`, `Generate refresh tokens` and `Enable JWT refresh`

#### _Webhooks_ settings:
Go to Settings -> Webhook menu and create one.
- URL - I used `http://local.docker.api:80/api/webhook/event` in order to call the `http://localhost:8001/api/webhook/event`
- I used `https://webhook.site/` in order to see what the event request body and work on the `/api/webhook/event` action

#### _Email_ template:
1) Go to Customizations -> Email templates
2) Add one with `User Registration` - The name is hardcoded in the `/api/webhook/event` action.
3) Fill in the `HTML template` and `Text template` text areas


Detailed Open API documentation is available at the OpenAPI [api/doc](http://localhost:8001/api/doc) route.

All endpoints except the `/api/login` and `/api/webhook/event` have additional `JWT Authorization` in the form of a `Authorization: Bearer` token.
