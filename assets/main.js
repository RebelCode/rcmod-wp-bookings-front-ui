/*
 * This is entry point for application.
 *
 * Require JS will be used here to load all needed
 * dependencies (Vue at least).
 */
document.addEventListener('DOMContentLoaded', function () {
  /**
   * @var {object} EDDBK_WIZARD_REQUIRE_FILES
   *
   * @property {string} bookingWizard Link to JS file of compiled booking wizard application.
   */
  require.config({
    paths: Object.assign(EDDBK_WIZARD_REQUIRE_FILES, {
      cjs: 'https://rawgit.com/guybedford/cjs/master/cjs',
      'amd-loader': 'https://rawgit.com/guybedford/amd-loader/master/amd-loader',
      stdLib: 'https://unpkg.com/@rebelcode/std-lib@0.1.5/dist/std-lib.umd',
      bottle: 'https://cdnjs.cloudflare.com/ajax/libs/bottlejs/1.6.1/bottle.min',
      vue: 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.4/vue',
      vuex: 'https://cdnjs.cloudflare.com/ajax/libs/vuex/3.0.1/vuex.min',
      bookingWizardComponents: 'https://unpkg.com/@rebelcode/booking-wizard-components@0.1.6/dist/lib.min',
      formWizard: 'https://unpkg.com/vue-form-wizard/dist/vue-form-wizard',
      axios: 'https://cdn.jsdelivr.net/npm/axios@0.18.0/dist/axios.min',
      humanizeDuration: 'https://cdnjs.cloudflare.com/ajax/libs/humanize-duration/3.14.0/humanize-duration.min',
      uiFramework: 'https://unpkg.com/@rebelcode/ui-framework@0.1.1/dist/static/js/uiFramework',
      datepicker: 'https://cdn.jsdelivr.net/npm/vuejs-datepicker@0.9.26/dist/build.min',
      lodash: 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min',
      textFormatter: 'https://unpkg.com/sprintf-js@1.1.1/dist/sprintf.min',
      moment: 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min',
      momentTimezone: 'https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.17/moment-timezone-with-data-2012-2022.min',
      momentRange: 'https://cdnjs.cloudflare.com/ajax/libs/moment-range/4.0.1/moment-range',
    })
  })

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

  require(dependenciesList, function () {
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
