# RebelCode - WP Bookings Front UI

[![Build Status](https://travis-ci.org/rebelcode/wp-bookings-front-ui.svg?branch=develop)](https://travis-ci.org/rebelcode/wp-bookings-front-ui)
[![Code Climate](https://codeclimate.com/github/rebelcode/wp-bookings-front-ui/badges/gpa.svg)](https://codeclimate.com/github/rebelcode/wp-bookings-front-ui)
[![Test Coverage](https://codeclimate.com/github/rebelcode/wp-bookings-front-ui/badges/coverage.svg)](https://codeclimate.com/github/rebelcode/wp-bookings-front-ui/coverage)
[![Latest Stable Version](https://poser.pugx.org/rebelcode/wp-bookings-front-ui/version)](https://packagist.org/packages/rebelcode/wp-bookings-front-ui)
[![Latest Unstable Version](https://poser.pugx.org/rebelcode/wp-bookings-front-ui/v/unstable)](https://packagist.org/packages/rebelcode/wp-bookings-front-ui)
[![This package complies with Dhii standards](https://img.shields.io/badge/Dhii-Compliant-green.svg?style=flat-square)][Dhii]

A RebelCode module that provides UI for booking appointments

[Dhii]: https://github.com/Dhii/dhii

## Make It Works

This module provide WP wrapper for bookings wizard. So to make it works you need to pull in `bookings-client` as front-end dependency:
```bash
$ npm install
```
It will install all front-end dependencies, with **pre-built** sources.

## Usage

Module exposes two methods in order to make everything works:
```php
render($params = [])

// usage
echo $c->get('wp_bookings_front_ui')->render($someAttrs);
```
Where `$params` is array that will be passed to `BookingWizard` application.

And
```php
enqueueAssets()

// usage
echo $c->get('wp_bookings_front_ui')->enqueueAssets();
```
This method enqueuing assets using WP enqueue system and must be called on some WP enqueue hook.
