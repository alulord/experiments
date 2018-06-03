#Requirements
##Symfony requirements
Installed php 7, composer
##Project requirements
Installed `node` with `npm`

#Installation
Just run `composer install` 

Run server using `bin/console server:run`

#Description
On main page, scroll down to see greeter message showing some greeting and actual time (to display dynamic rendering using react). It should be with red background. As soon as it gets to you viewport, it should turn red.

#Source list
* webpack.config.js - Webpack configuration responsible for building assets (supporting asset versioning ;)
* assets/js/app.jsx - Main React JS file, which is responsible for rendering and refreshing
* templates/frontpage/index.html.twig - Main twig template, where the base code is initialized
