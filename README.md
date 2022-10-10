# Anera HealthCheck Bundle

Anera HealthCheck Bundle provides an endpoint which could be used to check if your application works properly.
It may be used to get information about application condition for monitoring or some systems which requires such information like HAProxy etc. 

## Installation

```
composer require anera/health-check
```

If you want to use a default ```/health-check/status``` endpoint just add route to you routing configuration:
```
anera_health_check:
  resource: "@AneraHealthCheckBundle/Resources/config/routes.yaml"
```

You can change it to your own route by pointing ```Anera\HealthCheck\Controller\HealthCheckController::status``` action.

## Configuration
All configuration variables are set to default values. You don't have to configure them if you don't want to. 
```
anera_health_check:

    # you can set here your own health-check class
    # ResponseBuilder must implement ResponseBuilderInterface
    # If you want to make it configurable by this config ResponseBuilderConfigurableInterface must be implemented 
    response_builder: Anera\HealthCheck\Response\ResponseBuilder
  
    # you can define here default response format which will be used where there is no request Content-Type 
    # or response content which supports requested Content-Type is not set
    default_response_format: 'application/json' 
  
    # http status of health-check response
    response_http_status: 200
  
    # you can define here content of health-check response depending of requested Contetnt-Type
    # by default application/json, application/xml and text/html are supported
    response_contents:
        - {content_type: 'application/json', content: '{"health_check_status":"ok"}'}
  
    # you can add here headers which you want to attach to health-check response
    response_additional_headers:
        - {name: 'some', value: 'my-header'}
```
## License

MIT
