# xHTTP.class.php
xHTTP Request library, Ajax for php.
Simple way to send request and read response.

# Methods

```php
<?php
setHeader($str); \\ add new header string like 'Content-Type: application/x-www-form-urlencoded'
clearHeaders();  \\ delete all headers
setFormUrlEncodedType(); \\ 'Content-Type: application/x-www-form-urlencoded'
setJsonType(); \\ 'Content-Type: application/json'
x_post($address, $data); \\ send post request
x_get($address, $data); \\ send get request
x_put($address, $data); \\ send put request
x_delete($address); \\ send delete  request
?>
```

## x_request response
```php
<?php
array('response' => $response, 
      'headers' => $headers);
?>
```
