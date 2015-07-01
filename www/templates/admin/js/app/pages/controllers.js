/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 04.06.2015
 * Time: 14:13
 */
var pagesApp = angular.module('pagesApp', []);

pagesApp.controller('pageListController', function ($scope) {
    $scope.pages = pagesJSON;
    $scope.selected = '';
    $scope.tabButton = '<li class="" data-id="{id}"><a data-toggle="tab" href="#tab-{id}">{name}</a><button class="close close-xs close-tab" type="button"><i class="fa fa-times"></i></button></li>';
    $scope.tabContent = '<div id="tab-{id}" class="tab-pane active">{content}</div>';
    $scope.selectRow = function (pageIndex, pageID) {
        $('.tab-content #tab-tooltip').remove();
        $('#pages-tabs').parent().removeAttr('hidden');
        $('.page-data').removeClass('selected');
        $(this).addClass('selected');
        $scope.selected = pageID;
        if (!$scope.isTabOpened(pageID)) {
            $scope.dataLoad(pageIndex, pageID);
        } else {
            $('#pages-tabs a[href="#tab-' + pageID + '"]').tab('show');
        }
    };
    $scope.dataLoad = function (pageIndex, pageID) {
        var tabs = $('#pages-tabs');
        var content = $('.tab-content');
        $.ajax({
            type: 'get',
            url: '/pages/loadpagedata/' + pageID,
            success: function (data) {
                if (!$scope.isTabOpened(pageID)) {
                    var tabButton = str_replace('{name}', $scope.pages[pageIndex].title, $scope.tabButton);
                    tabButton = str_replace('{id}', pageID, tabButton);
                    var tabContent = str_replace('{content}', data, $scope.tabContent);
                    tabContent = str_replace('{id}', pageID, tabContent);
                    tabs.append(tabButton);
                    content.append(tabContent);
                    $('#pages-tabs a:last').tab('show');
                    $scope.applyNewDOM(pageID);
                } else {
                    $('#pages-tabs a[href="#tab-' + pageID + '"]').tab('show');
                }
            }
        });
    };
    $scope.applyNewDOM = function (pageID) {
        var $injector = angular.element(document.querySelector('#tab-content-wrapper')).injector();
        var element = angular.element(document.querySelector('#form-wrapper-'+pageID));

        $injector.invoke(function ($compile) {
            var scope = element.scope();
            $compile(element)(scope);
        });
    };
    $scope.isTabOpened = function (pageID) {
        var count = $('#pages-tabs').find('a[href="#tab-' + pageID + '"]').length;
        if (count > 0) {
            return true;
        } else {
            return false;
        }
    };
    $scope.deletePage = function (pageIndex, pageID) {
        if (confirm('Точно удалить?')) {
            $.ajax({
                type: 'post',
                url: '/pages/delete/' + pageID,
                data: {pageID: pageID},
                success: function (data) {
                    if (data == 'ok') {
                        $('a[href="#tab-' + pageID + '"]').parent().remove();
                        $('.tab-content').find('#tab-' + pageID).remove();
                        $scope.pages.splice(pageIndex, 1);
                        $scope.$apply();
                    } else {
                        alert('Не удалось удалить. Обратитесь к администратору.');
                    }
                    $('#pages-tabs a:last').tab('show');
                }
            });
        }

    }
});
