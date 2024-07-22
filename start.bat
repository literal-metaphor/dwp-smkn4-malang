@echo off
start cmd /k "cd fe && npm install && npm run dev"
start cmd /k "cd db && docker compose -p dwp-smkn4-malang up"
start cmd /k "cd be && php artisan serve"