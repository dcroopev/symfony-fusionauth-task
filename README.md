# Symfony FusionAuth task

### Steps to install project:
1) Git Init and `git clone https://github.com/dcroopev/symfony-fusionauth-task.git`
2) `git checkout main`
3) `composer install`
4) `docker compose up -d`

### Configure the FusionAuth on http://localhost:9011/admin 
 _I tried to expose a kickstart.json but with little success_

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
- URL - I used `http://host.docker.internal:8000/api/webhook/event` in order to call the `http://localhost:8000/api/webhook/event` but the only progress I managed to make was to go from a `Connection refused` to a `Connection timeout` error.
- I used `https://webhook.site/` in order to see what the event request body and work on the `/api/webhook/event` action

#### _Email_ template:
1) Go to Customizations -> Email templates
2) Add one with `User Registration` - The name is hardcoded in the `/api/webhook/event` action.
3) Fill in the `HTML template` and `Text template` text areas


Detailed Open API documentation is available at the OpenAPI [api/doc](http://localhost:8000/api/doc) route.

All endpoints except the `/api/login` and `/api/webhook/event` have additional `JWT Authorization` in the form of a `Authorization: Bearer` token.
