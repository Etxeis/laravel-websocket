# Laravel with Soketi

### Install Soketi Server
First download the required dependencies:
```
apt install -y git python3 gcc build-essential
```
After that, you can install Soketi CLI globally
```
npm install -g @soketi/soketi
```
### Start the Soketi Server
```
soketi start
```
### Install Composer Packages 
```
composer install
```
### Install NPM Packages 
```
npm install
```
### Create `.env` file from `.env.example`
```
cp .env.example .env
```
### Generate Laravel App Key
```
php artisan key:generate
```
### Package Configurations
```
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
```
### Token Encyption
```
php artisan jwt:secret
```
### Check database migrations
```
php artisan migrate
```
### Run the Vite Dev
```
npm run dev
```
### Run the App
```
php artisan serve
```
