// add timezone plugin
dayjs.extend(window.dayjs_plugin_timezone);

function $_GET(param) {
    var vars = {};
    window.location.href.replace( location.hash, '' ).replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function( m, key, value ) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );
    // with slashes
    window.location.href.replace( location.hash, '' ).replace(
        /.*\/(.*)\/(.*\d)/gi, // regexp
        function( m, key, value ) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );

    if (param) {
        return vars[param] ? vars[param] : null;
    }
    return vars;
}

function Queue(event_id, cookie){
    var self = this;

    self.event_id  = ko.observable(event_id);
    self.firstname = ko.observable();
    self.lastname  = ko.observable();
    self.address   = ko.observable();
    self.birthday  = ko.observable();
    self.mobile    = ko.observable();
    self.email     = ko.observable();

    if (cookie!==undefined){
        queue = JSON.parse(cookie);
        self.firstname(queue.firstname);
        self.lastname(queue.lastname);
        self.address(queue.address);
        self.birthday(queue.birthday);
        self.mobile(queue.mobile);
        self.email(queue.email);
    }
}

function EventLight(event){
    var self = this;

    self.uid   = ko.observable();
    self.title = ko.observable();
    self.date  = ko.observable();

    if (event!==undefined){
        self.uid(event.uid);
        self.title(event.title);
        self.date(event.date);
    }
}

function AhoyViewModel() {
    var self = this;

    //self.baseUrl = 'https://apollo29.com/ahoy/api/v1/';
    self.baseUrl = 'http://localhost/ahoy-api/';
    self.loaded = ko.observable(false);
    self.timezone = ko.observable(dayjs.tz.guess());
    self.event = ko.observable(new EventLight());
    self.event.subscribe(function(event){
        if (event.uid()!==undefined) {
            self.loaded(true);
        }
    });
    self.remember = ko.observable(true);
    self.remember.subscribe(function(consent){
        if (!consent){
            Cookies.remove('profile');
        }
    });
    self.queue = ko.observable(new Queue($_GET('event'), Cookies.get('profile')));

    load($_GET('event'));

    function load(event_id){
        var url = self.baseUrl+'events/'+event_id;
        $.ajax({
            url: url,
            type: 'get',
            success: function(response) {
                self.event(new EventLight(response.data));
            },
            error: function (request, error) {
                window.location.href = 'error.html';
            },
        });
    }

    self.proceed = function (){
        if (self.remember()){
            Cookies.set('profile', ko.toJSON(self.queue));
        }
        $('#register').submit();
    };

    // i18n
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

ko.applyBindings(new AhoyViewModel());