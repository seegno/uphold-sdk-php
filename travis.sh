#!/bin/bash

config="/home/travis/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini"

# Disable xdebug.
function xdebug-disable() {
  if [[ $TRAVIS_PHP_VERSION =~ ^hhvm ]]; then
    echo 'xdebug.enable = Off' >> /etc/hhvm/php.ini
  elif [[ -f $config ]]; then
    mv $config "$config.bak"
  fi
}

# Enable xdebug.
function xdebug-enable() {
  if [[ $TRAVIS_PHP_VERSION =~ ^hhvm ]]; then
    echo 'xdebug.enable = On' >> /etc/hhvm/php.ini
  elif [[ -f "$config.bak" ]]; then
    mv "$config.bak" $config
  fi
}
