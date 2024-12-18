# Test Assignment - Payment Gateway

## Assignment specifications:

https://github.com/AntonAcc/payment-gateway/blob/master/specifications.md

## Testing:

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull --wait -d` to start the project

### Phpunit

```
docker compose exec -T php bin/phpunit 
```

### App

API

Open in your web browser

http://localhost:8000/app/example/shift4?amount=100.00&currency=USD&card_number=4111111111111111&card_exp_year=2025&card_exp_month=12&card_cvv=123
http://localhost:8000/app/example/aci?amount=100.00&currency=USD&card_number=4111111111111111&card_exp_year=2025&card_exp_month=12&card_cvv=123

CLI

docker compose exec -T php bin/console app:example shift4 --amount=100.00 --currency=USD --card_number=4111111111111111 --card_exp_year=2025 --card_exp_month=12 --card_cvv=123
docker compose exec -T php bin/console app:example aci --amount=100.00 --currency=USD --card_number=4111111111111111 --card_exp_year=2025 --card_exp_month=12 --card_cvv=123
