# RebelCode - WP Bookings Front UI

[![Build Status](https://travis-ci.org/RebelCode/rcmod-wp-bookings-front-ui.svg?branch=develop)](https://travis-ci.org/rebelcode/rcmod-wp-bookings-front-ui)
[![Code Climate](https://codeclimate.com/github/RebelCode/rcmod-wp-bookings-front-ui/badges/gpa.svg)](https://codeclimate.com/github/RebelCode/rcmod-wp-bookings-front-ui)
[![Test Coverage](https://codeclimate.com/github/RebelCode/rcmod-wp-bookings-front-ui/badges/coverage.svg)](https://codeclimate.com/github/RebelCode/rcmod-wp-bookings-front-ui/coverage)
[![Latest Stable Version](https://poser.pugx.org/rebelcode/rcmod-wp-bookings-front-ui/version)](https://packagist.org/packages/rebelcode/rcmod-wp-bookings-front-ui)
[![Latest Unstable Version](https://poser.pugx.org/rebelcode/rcmod-wp-bookings-front-ui/v/unstable)](https://packagist.org/packages/rebelcode/rcmod-wp-bookings-front-ui)

A RebelCode module that provides UI for booking appointments

[Dhii]: https://github.com/Dhii/dhii

## Assets building

### Dependencies

We are using RequireJS to manage all dependencies. They are loaded from CDN in runtime asyncroniously. In case when CDN is not available we are using Require JS fallback mechanism to handle this case. You can read more about this here: https://requirejs.org/docs/api.html#pathsfallbacks

### Build

For building assets of this package you need to have installed `node` and `npm`.

Before build you need to install all dependencies, including `bower`:

```bash
$ npm install
```

> Bower is used to download dependencies from CDN and hold them locally to use them as fallback.

After dependencies installation you are ready to build assets:

```bash
$ npm run build
```

Build command will build module's JS and CSS and store result in `dist` folder. After building `bower install` will be automatically called. It will copy all CDN scripts to `dist/scripts` folder. This copied scripts provide fallback ability for RequireJS.

## Usage
[WIP]