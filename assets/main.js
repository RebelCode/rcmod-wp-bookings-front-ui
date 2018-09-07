require('./styles/app.scss')

/*
 * This is entry point for application.
 *
 * Require JS will be used here to load all needed
 * dependencies (Vue at least).
 */
document.addEventListener('DOMContentLoaded', function () {
  function map (object, mapFn) {
    return Object.keys(object).reduce(function(result, key) {
      result[key] = mapFn(key, object[key])
      return result
    }, {})
  }

  /**
   * Libraries that should be required. It automatically synced with `bower.json` using
   * Webpack's DefinePlugin.
   *
   * @see ./webpack.config.js
   */
  var EDDBK_WIZARD_REQUIRE_LIBS = BOWER_DEPS

  var dependenciesList = [
    'bookingWizard',
    'formWizard',
    'textFormatter',
    'bookingWizardComponents',
    'uiFramework',
    'bottle',
    'vue',
    'vuex',
    'axios',
    'humanizeDuration',
    'cjs!datepicker',
    'moment',
    'momentTimezone',
    'lodash',
    'cjs!momentRange',
    'stdLib'
  ]

  /**
   * @var {object} EDDBK_WIZARD_REQUIRE_FILES
   *
   * @property {string} bookingWizard Link to JS file of compiled booking wizard application.
   */
  window.require.config({
    baseUrl: EDDBK_WIZARD_APP_STATE.scriptsBase,
    paths: Object.assign(EDDBK_WIZARD_REQUIRE_FILES, map(EDDBK_WIZARD_REQUIRE_LIBS, function (key, cdnUrl) {
      // load local versions of `cjs!` loader dependencies to prevent timeouts.
      var localUrl = key + '/index'
      return dependenciesList.indexOf('cjs!' + key) !== -1 ? [localUrl , cdnUrl] : [cdnUrl,  localUrl]
    }))
  })

  window.require(dependenciesList, function () {
    var dependencies = {}

    for (var i = 0; i < dependenciesList.length; i++) {
      dependencies[dependenciesList[i].replace('cjs!', '')] = arguments[i]
    }

    var di = new dependencies.bottle()
    defineServices(di, dependencies)

    var container = new dependencies.uiFramework.Container.Container(di)
    var app = new dependencies.uiFramework.Core.App(container)
    app.init()
  })

  function defineServices (di, dependencies) {
    var serviceList = dependencies.bookingWizard.makeServices(dependencies)

    serviceList['state'] = function () {
      /**
       * @typedef {Object} AppState
       *
       * @since [*next-version*]
       *
       * @property {string} applicationSelector Application selector for UI framework (UI framework will mount application to each of them).
       * @property {{bookings: string, services: string}} apiEndpointUrls Map of endpoint name to its API url.
       * @property {string} initialBookingTransition Name of booking transition for creating booking.
       * @property {object} datetimeFormats Map of datetime keys to datetime formats for formatting datetimes in application.
       */

      /**
       * @var {AppState} EDDBK_WIZARD_APP_STATE
       */
      return window.EDDBK_WIZARD_APP_STATE
    }
    serviceList['selectorList'] = function (container) {
      return [
        container.state.applicationSelector
      ]
    }
    serviceList['config'] = function (container) {
      return {
        endpoints: {
          sessions: {
            'fetch': {
              'method': 'get',
              'endpoint': container.state.apiEndpointUrls.sessions,
            }
          }
        },
        bookingsResourceUrl: container.state.apiEndpointUrls.bookings,
        servicesResourceUrl: container.state.apiEndpointUrls.services,
        bookingDataMap: container.state.bookingDataMap,
        initialBookingTransition: container.state.initialBookingTransition,
        datetime: container.state.datetimeFormats
      }
    }
    serviceList['document'] = function () {
      return document
    }
    serviceList['handleBookSuccess'] = function () {
      /**
       * Function for handling booking success.
       * `this` - is application Vue component inside function scope.
       *
       * @since [*next-version*]
       */
      return function () {
        if (this.config.redirectUrl) {
          window.location.href = this.config.redirectUrl
        }
      }
    }

    for (var i = 0; i < Object.keys(serviceList).length; i++) {
      var serviceName = Object.keys(serviceList)[i]
      di.factory(serviceName, serviceList[serviceName])
    }
  }

  function loadCss(url) {
    var link = document.createElement("link");
    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = url;
    document.getElementsByTagName("head")[0].appendChild(link);
  }

  if (EDDBK_WIZARD_REQUIRE_STYLES) {
    for (var i = 0; i < EDDBK_WIZARD_REQUIRE_STYLES.length; i++) {
      var styleLink = EDDBK_WIZARD_REQUIRE_STYLES[i]
      loadCss(styleLink)
    }
  }
})
