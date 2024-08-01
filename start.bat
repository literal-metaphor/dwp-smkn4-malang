start cmd /k "cd db && docker compose -p dwp_smkn4_malang up"
start cmd /k "cd fe && npm run dev"
start cmd /k "cd be && php artisan serve"