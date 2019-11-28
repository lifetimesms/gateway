# Lifetimesms SMS Sending Package
Lifeitmesms laravel package to send single or bulk text and voice sms and for balance inquiry

## Installation

```
composer require lifetimesms/gateway
```

## Laravel 5 and above

### Setup

In `app/config/app.php` add the following :

1- The ServiceProvider to the providers array :

```php
Lifetimesms\Gateway\LifetimesmsServiceProvider::class,
```

2- The class alias to the aliases array :

```php
'Lifetimesms' =>  Lifetimesms\Gateway\Facades\LifetimesmsFacade::class,
```

3- Publish the config file

```ssh
php artisan vendor:publish --tag="lifetimesms"
```

### Configuration

Add `LIFETIMESMS_API_TOKEN` and `LIFETIMESMS_API_SECRET` in **.env** file :

```
LIFETIMESMS_API_TOKEN=api-token
LIFETIMESMS_API_SECRET=api-secret
```

### Usage

Send Single SMS :

```php
$params = ['to' => '03348090100', 'from' => 'Lifetimesms', 'message' => 'Lifetimesms Testing Laravel Package', 'unicode' => false, 'date' => null, 'time' => null];
$response = Lifetimesms::singleSMS($params);
```

Send Bulk SMS :

```php
$params = ['to' => ['0334809000', '03008090100', '03448090100'], 'from' => 'Lifetimesms', 'message' => 'Lifetimesms Testing Laravel Package', 'unicode' => false, 'date' => null, 'time' => null];
$response = Lifetimesms::bulkSMS($params);
```

Send Personalized SMS :

```php
$params = ['data' => [['to' => '03348090100', 'message' => 'Hello david! its from lifetimesms'], ['to' => '03008090100', 'message' => 'Hello peter! its from lifetimesms']], 'from' => 'Lifetimesms', 'date' => null, 'time' => null];
$response = Lifetimesms::personalizedSMS($params);
```

Send Voice SMS :

```php
$params = ['to' => ['0334809000', '03008090100', '03448090100'], 'from' => 'Lifetimesms', 'voice_id' => '1', 'date' => null, 'time' => null];
$response = Lifetimesms::voiceSMS($params);
```

Check Delivery Status :

```php
$params = ['message_id' => '44a82f4e3dd9bd7a091c1127'];
$response = Lifetimesms::deliveryStatus($params);
```

Check Voice Status :

```php
$params = ['voice_id' => '1472'];
$response = Lifetimesms::voiceStatus($params);
```

Balance Inquiry :

```php
$response = Lifetimesms::balanceInquiry();
```
