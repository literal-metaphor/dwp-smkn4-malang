@echo off
start cmd /k "cd fe && npm install && npm run dev"
start cmd /k "cd be && composer global require leafs/cli && leaf serve"