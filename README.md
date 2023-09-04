# Тестовое задание
https://github.com/systemeio/test-for-candidates

# Установка и запуск
* `docker-compose up -d`
* `docker-compose exec php bash`
* (внутри контейнера) `composer install`
* (внутри контейнера) `php bin/console doctrine:migrations:migrate`
* (внутри контейнера) `php bin/console doctrine:fixtures:load`

# Решение
## Добавление новых процессоров оплат
1. Необходимо создать класс, реализующий интерфейс `App\Payment\PaymentProcessorInterface`
2. Вписать этот класс в **services.yaml** в конфигурации сервиса `App\Payment\PaymentService`

**services.yaml**:
```yaml
services:
  App\Payment\PaymentService:
    arguments:
      $paymentProcessors:
        - '@App\Payment\PaypalPaymentProcessor'
        - '@App\Payment\StripePaymentProcessor'
```

## Symfony Form
В этом решении нет использования форм, вместо них - **ValueResolver**'ы и DTO (`App\RequestDto`).

## Добавление новых Tax-номеров
Для добавления новых шаблонов tax-номеров необходимо прописать их в **service.yaml**. Там же доступна настройка налогов.

**services.yaml**:
```yaml
parameters:
  app.tax_patterns:
    - '/^DE[0-9]{9}$/'
    - '/^IT[0-9]{11}$/'
    - '/^FR[A-Z]{2}[0-9]{9}$/'
    - '/^GR[0-9]{9}$/'
  app.countries_tax:
    DE: 0.19
    IT: 0.22
    FR: 0.19
    GR: 0.24
services:
  App\Validator\IsTaxNumberValidator:
    arguments:
      $countriesTaxPatterns: '%app.tax_patterns%'
  App\Tax\TaxCalculator:
    arguments:
      $taxMap: '%app.countries_tax%'
```

## Как хранятся деньги
Я решил хранить деньги (и производить расчеты) в центах. Пользователь этого не видит: роут `/v1/get-price` возвращает значение в евро. В БД же все значения в центах.

1 евро = 100 евроцентов.

## Пример запросов и ответов
### Получение цены
Запрос:
```text
POST /v1/get-price
Content-Type: application/json
Accept: application/json

{
  "product": 1,
  "taxNumber": "DE123456789",
  "couponCode": "F15"
}
```

Результат:
```text
HTTP/1.1 200 OK

Content-Type: application/json

{
  "price": 101.15
}
```

### Покупка
Запрос:
```text
POST {{host}}/v1/buy
Content-Type: application/json
Accept: application/json

{
  "product": 1,
  "taxNumber": "DE123456789",
  "couponCode": "F15",
  "paymentProcessor": "stripe"
}
```

Результат:
```text
HTTP/1.1 200 OK

Content-Type: application/json

{
  "status": "ok"
}
```
