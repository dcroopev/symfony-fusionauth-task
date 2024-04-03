# Symfony FusionAuth task

## Steps to install project:
1) Git Init and `git clone https://github.com/dcroopev/symfony-fusionauth-task.git`
2) `git checkout main`
3) `composer install`
4) `docker compose up -d`



## Configure the FusionAuth:
 #### I succeeded configure a kickstart.json, so after a `docker compose up -d` command all the necessary components should be created  _

- By default, an application named "Application 1" should be created with refresh token settings `on`.
- Default tenant should be configured with the webhook event of 'User Registration' `on`
- Webhook with the appropriate url `http://local.docker.api:80/api/webhook/event`
- Email template `User Registration` for the webhook endpoint to use

<br><br>
<br><br>

#### If the kickstart doesn't manage to apply the default settings here a step-step-guide:


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
