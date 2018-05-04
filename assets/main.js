document.addEventListener('DOMContentLoaded', function () {
  // create new wizard using the configured service container (inside bottle)
  var wizardElements = document.querySelectorAll("[data-eddbk-widget-id]")

  for (var i = 0; i < wizardElements.length; ++i) {
    var bottle = BookingWizardManager.getDefaultBottle()
    bottle.container.config.appEl = wizardElements[i]
    BookingWizardManager.create(bottle);
  }
});