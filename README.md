Test

curl -X POST http://localhost:8000/app/example/aci \
-H "Content-Type: application/json" \
-d '{
"amount": "100.00",
"currency": "USD",
"card_number": "4111111111111111",
"card_exp_year": "2025",
"card_exp_month": "12",
"card_cvv": "123"
}'

php bin/console app:example aci \
--amount=100.00 \
--currency=USD \
--card_number=4111111111111111 \
--card_exp_year=2025 \
--card_exp_month=12 \
--card_cvv=123