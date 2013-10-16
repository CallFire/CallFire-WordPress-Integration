This plugin loads CallFire's PHP SDK into WordPress, and provides a configuration interface and helper functions for creating REST and SOAP clients.

## Configuration

The SDK client can be configured from the administration interface of your WordPress project by navigating to `Settings -> CallFire`.

From there, you can enter your API login and password.

## Basic Usage

Having successfully configured your API credentials in your WordPress installation, you can make use of the `CallFire::rest` and `CallFire::soap` helpers to create REST or SOAP clients, respectively. The first parameter to either of these functions is the type of client you wish to create, e.g.:

```php
$client = CallFire::rest('Broadcast');
$request = $client::request('QueryBroadcasts');
$response = $client->QueryBroadcasts($request);
$broadcasts = $client::response($response);

foreach($broadcasts as $broadcast) {
    ...
}
```
