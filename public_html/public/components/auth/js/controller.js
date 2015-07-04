/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 13.04.2015
 * Time: 15:46
 */
var changePassApp = angular.module('changePassApp', []);
changePassApp.controller('changePasswordCtrl', function ($scope) {
    $scope.$watch('pass1', function () {
        console.log($scope.pass1);
        if($scope.pass1 != undefined)
        {
            $('.alert').css('display', '');
        }
    });
});
