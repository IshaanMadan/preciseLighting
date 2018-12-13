#ONE STEP CHECKOUT - GEOIP
###Main features
- Integrate MaxMind Database
- Auto complete address form with correct geolocation from database
###Installation
- Download zip file
- Unzip file and copy all file from folder 'One Step Checkout' to {Magento 2 root folder}
- Add MaxMind database
    + Download database (gzipped, binary format) at : https://dev.maxmind.com/geoip/geoip2/geolite2/
    + Unzip file and copy file GeoLite2-City.mmdb to vendor/yosto/geoip folder.
- Install extra library geoip2
    + Use composer: composer require geoip2/geoip2:~2.0
    + You can see this page for more details: https://github.com/maxmind/GeoIP2-php
- Run command: 
    + bin/magento setup:upgrade
    + bin/magento setup:static-content:deploy
- Go to admin and refresh cache.
###Support
Feel free to get support via email: support@x-mage2.com  