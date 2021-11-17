<p align = "center"> <img src = "https://repository-images.githubusercontent.com/209717891/f5218080-de0f-11e9-90d7-22ed2fa88e90"> </p>

## Laravel Payment Processing [Multi Payment Platform System]

Integrate payment gateways such as PayPal and Stripe to receive online payments using their APIs from Laravel.

- Integrate and receive payments with PayPal, directly using the PayPal API
- Integrate and process payments with Stripe, directly using the Stripe API
- Build a payment platform that integrates MULTIPLE payment gateways at once
- Understand the essential steps to process payments from Laravel, not only with - PayPal and Stripe, but with any other platform.

### How to use

- Clone the project with `git clone`
- Copy `.env.example` file to `.env` and set database, stripe and paypal credentials there
- Run `composer install`
- Run `php artisan key:generate`
- Run `php artisan migrate --seed` (it has some seeded data for your testing)
- That's it: launch the main URL
