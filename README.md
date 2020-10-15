## Features

-   Register: `POST api/v1/auth/register`
-   Login: `POST api/v1/auth/login`
-   Update Profile: `PATCH api/v1/profile`
-   Get Profile: `GET api/v1/profile`
-   Add pizza: `POST api/v1/pizza`
-   Get menu/Get all: `GET api/v1/pizza`
-   Get single Pizza: `GET api/v1/pizza/{pizzaId}`
-   Delete pizza: `POST api/v1/pizza/{pizzaId}`
-   Update pizza: `POST api/v1/pizza/{pizzaId}`
-   Create unique cart id: `POST api/v1/cart/uniqueId`
-   Add pizza to cart: `POST api/v1/cart/{pizza_id}`
-   Update item in cart: `PATCH api/v1/cart/{cart_id}/{pizza_id}`
-   Remove item from cart: `DELETE api/v1/cart/{cart_id}/{pizza_id}`
-   Empty cart: `DELETE api/v1/cart/{cart_id}`
-   Get total amount in cart: `GET api/v1/cart/total/{cart_id}`
-   View cart: `GET api/v1/cart/{cart_id}`

## Author

-   Odumah Solomon

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
