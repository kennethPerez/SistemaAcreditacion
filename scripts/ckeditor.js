'use strict';
angular.module('ckeditor', []).directive('ckeditor', function () {
    return {
        require: '?ngModel',
        link: function (scope, element, attrs, ngModel) {
            var config, editor, updateModel;

            config = {};


            CKEDITOR.config.entities = false;

            editor = CKEDITOR.replace(element[0], config);

            if (!ngModel) {
                return;
            };

            editor.on('instanceReady', function () {

                return editor.setData(ngModel.$viewValue);
            });

            updateModel = function () {
                if(!scope.$$phase) {
                    return scope.$apply(function () {
                        return ngModel.$setViewValue(editor.getData());
                    });
                }
            }

            editor.on('change', updateModel);
            editor.on('dataReady', updateModel);
            editor.on('key', updateModel);
            editor.on('paste', updateModel);
            editor.on('selectionChange', updateModel);

            return ngModel.$render = function () {
                return editor.setData(ngModel.$viewValue);
            };
        }
    };
});
