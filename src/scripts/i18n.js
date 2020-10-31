function I18nViewModel() {
    ko.i18n.setBundles(bundles);
    ko.i18n.setLocale(navigator.language);

    self.setLanguage = function(language){
        ko.i18n.setLocale(language);
    }

    self.isCurrent = function(language){
        if (ko.i18n.locale()===language) {
            return "current";
        }
        else {
            return "";
        }
    }
}

ko.applyBindings(new I18nViewModel());