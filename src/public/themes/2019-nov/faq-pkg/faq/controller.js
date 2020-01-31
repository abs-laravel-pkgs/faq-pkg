app.config(['$routeProvider', function($routeProvider) {

    $routeProvider.
    when('/faq', {
        template: '<faqs></faqs>',
        title: 'Faqs',
    });
}]);

app.component('faqs', {
    templateUrl: faq_list_template_url,
    controller: function($http, $location, HelperService, $scope, $routeParams, $rootScope, $location) {
        $scope.loading = true;
        var self = this;
        self.hasPermission = HelperService.hasPermission;
        $http({
            url: laravel_routes['getFaqs'],
            method: 'GET',
        }).then(function(response) {
            self.faqs = response.data.faqs;
            $rootScope.loading = false;
        });
        $rootScope.loading = false;
    }
});
