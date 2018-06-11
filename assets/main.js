document.addEventListener('DOMContentLoaded', function () {
  // create new wizard using the configured service container (inside bottle)
  var wizardElements = document.querySelectorAll("[data-eddbk-widget-id]")

  for (var i = 0; i < wizardElements.length; ++i) {
    var config = JSON.parse(wizardElements[i].getAttribute('data-config'))
    var bottle = BookingWizardManager.getDefaultBottle()
    bottle.container.config.appEl = wizardElements[i]
    bottle.container.config.API_BASE_URL = config.apiBaseUrl
    bottle.container.config.redirectUrl = config.redirectUrl
    if (config.service) {
      bottle.container.config.service = config.service
    }
    BookingWizardManager.create(bottle);
  }
});