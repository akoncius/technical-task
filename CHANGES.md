# Changes

* Upgraded PHP and Symfony
* Used ArgumentValueResolver interface to get Address entity
* Moved google&hereapi code to Geocoder services with GeocoderInterface
* Moved Array and DBCache algo to GeocoderInterface
* Covered it with Unit tests + integration test for repo + api test
* Decorators for HttpRequestGeocoder: google and hereapi. It is used to create request and convert response to Coordinates
* Added fixtures for repo test

# What can be done better?

More API tests.
