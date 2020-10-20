# The Pizza Cafe 🍕

API to manage ordering Pizza

## Features

- Register: `POST api/v1/auth/register`
- Login: `POST api/v1/auth/login`
- Update Profile: `PATCH api/v1/profile`
- Get Profile: `GET api/v1/profile`
- Add pizza: `POST api/v1/pizza` (Admin)
- Get menu/Get all: `GET api/v1/pizza`
- Get single Pizza: `GET api/v1/pizza/{pizzaId}`
- Delete pizza: `POST api/v1/pizza/{pizzaId}` (Admin)
- Update pizza: `POST api/v1/pizza/{pizzaId}` (Admin)
- Create unique cart id: `POST api/v1/cart/uniqueId`
- Add pizza to cart: `POST api/v1/cart/{pizza_id}`
- Update item in cart: `PATCH api/v1/cart/{cart_id}/{pizza_id}`
- Remove item from cart: `DELETE api/v1/cart/{cart_id}/{pizza_id}`
- Empty cart: `DELETE api/v1/cart/{cart_id}`
- Get total amount in cart: `GET api/v1/cart/total/{cart_id}`
- View cart: `GET api/v1/cart/{cart_id}`
- Create order: `POST api/v1/order`
- View order summary: `POST api/v1/order/{id}`
- Cancel an order: `POST api/v1/order/{id}`
- View order history: `POST api/v1/order/history`

## Technologies

- Cloudinary
- Laravel
- Mysql
- phpunit

## Getting Started

- Install composer on your computer
- Clone this repository using git clone https://github.com/slimsolz/the-pizza-cafe.git
- Use the .env.example file to setup your environmental variables and rename the file to .env
- Run `composer install` to install all dependencies
- Run `php artisan migrate` to migrate tables
- Run `php artisan db:seed` to seed the tables
- Run `php artisan serve` to start the server
- Interact with localhost:[PORT] in POSTMAN to access the application

## Admin
- After running `php artisan db:seed`, an admin user was seeded
- Use `email: admin@gmail.com` and `password: testPassword123!` to access admin protected routes

## Testing

- run `composer test`, This will run test

## Using the Live App

- The live application is hosted at `https://the-pizza-app-api.herokuapp.com` (note: don't for get to include `/api/v1/` when a request to an endpoint)

## Contributing Guide

- Fork the repository
- Make your contributions
- Write Test Cases for your contribution with at least 80% coverage
- Create a pull request against the develop branch


## Author

- Odumah Solomon

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
