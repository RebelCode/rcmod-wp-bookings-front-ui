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
      stdLib: 'https://unpkg.com/@rebelcode/std-lib@0.1.4/dist/std-lib.umd',
      bottle: 'https://cdnjs.cloudflare.com/ajax/libs/bottlejs/1.6.1/bottle.min',
      vue: 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.4/vue',
      bookingWizardComponents: '/dist/js/bwc.min',
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
    var serviceList = dependencies.bookingWizard.services(dependencies, document)

    serviceList['state'] = function () {
      /**
       * @typedef {Object} AppState
       *
       * @since [*next-version*]
       *
       * @property {string} applicationSelector Application selector for UI framework (UI framework will mount application to each of them).
       * @property {{bookings: string, services: string}} apiBaseUrls Map of resources names to their urls.
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
              'endpoint': 'http://scotchbox.local/index.php?rest_route=/eddbk/v1/sessions/',
            }
          }
        },
        bookingsResourceUrl: container.state.apiBaseUrls.bookings,
        servicesResourceUrl: container.state.apiBaseUrls.services,

        initialBookingTransition: container.state.initialBookingTransition,
        datetime: container.state.datetimeFormats
      }
    }
    serviceList['document'] = function () {
      return document
    }

    for (var i = 0; i < Object.keys(serviceList).length; i++) {
      var serviceName = Object.keys(serviceList)[i]
      di.factory(serviceName, serviceList[serviceName])
    }
  }
})
