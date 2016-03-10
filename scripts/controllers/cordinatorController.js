angular.module("app")

.controller("coordinadorController", function ($scope, $http, $location, $cookies, $timeout, $mdDialog, auth) {
    $scope.userData = $cookies.getObject('userData');
    $scope.tab = "Debilidades";

    $scope.debilidades = [];
    $scope.dimensiones = [];
    $scope.componentes = [];
    $scope.criterios = [];
    $scope.filter = "";

    $scope.logout = function () {
        auth.logout();
    };

    $scope.swicth = function (tab) {
        $scope.debilidades = [];
        $scope.dimensiones = [];
        $scope.componentes = [];
        $scope.criterios = [];
        $scope.filter = "";

        switch (tab) {
            case 'Debilidades':
                $http.get('./php/Debilidades.php?action=get')
                    .success(function (response) {
                        $scope.debilidades = response;
                    });
                break;

            case 'Dimensiones':
                $http.get('./php/Dimenciones.php?action=get')
                    .success(function (response) {
                        $scope.dimensiones = response;
                    });
                break;

            case 'Componentes':
                $http.get('./php/Componentes.php?action=get')
                    .success(function (response) {
                        $scope.componentes = response;
                    });
                $http.get('./php/Dimenciones.php?action=get')
                    .success(function (response) {
                        $scope.dimensiones = response;
                    });
                break;

            case 'Criterios':
                $http.get('./php/Criterios.php?action=get')
                    .success(function (response) {
                        $scope.criterios = response;
                    });
                break;

        }
        $scope.tab = tab;
    };
    $scope.swicth($scope.tab);

    $scope.isActive = function (tab) {
        if (tab === $scope.tab) {
            return true;
        }

        return false;
    };

    $scope.clean = function () {
        $scope.swicth($scope.tab);
    };


    /****************************************************************/
    $scope.dimentionNameFail = true;
    $scope.dimentionName = "";
    $scope.dimentionId = undefined;
    $scope.alertDimention = false;

    $scope.$watch('dimentionName', function () {
        $scope.dimentionNameValidate();
    });
    $scope.dimentionNameValidate = function () {
        if (!$scope.dimentionName.length) {
            $scope.dimentionNameFail = true;
        } else {
            $scope.dimentionNameFail = false;
        }
    };

    $scope.removeDimention = function (ev, id) {
        $scope.modifyDimention = false;
        $scope.deleteDimention = true;
        $scope.newDimention = false;
        var confirm = $mdDialog.confirm()
            .title('¿Desea eliminar esta dimesión?')
            .textContent('Si la dimensión es eliminada todo lo relacionado con la misma se eliminará.')
            .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Si')
            .cancel('No');
        $mdDialog.show(confirm).then(function () {
            $http.get('./php/Dimenciones.php?action=remove&dimentionId=' + id)
                .success(function (response) {
                    $scope.dimensiones = response;
                    $scope.alertDimention = true;
                    $timeout(function () {
                        $scope.alertDimention = false;
                    }, 3000);
                });
        });
    };

    $scope.addDimention = function () {
        $scope.dimentionName = "";
        $scope.dimentionId = undefined;
        $scope.modifyDimention = false;
        $scope.deleteDimention = false;
        $scope.newDimention = true;
    };

    $scope.editDimention = function (id, desc) {
        $scope.dimentionName = desc;
        $scope.dimentionId = id;
        $scope.newDimention = false;
        $scope.deleteDimention = false;
        $scope.modifyDimention = true;

    };

    $scope.saveDimention = function () {
        if ($scope.newDimention) {
            $http.get('./php/Dimenciones.php?action=insert&dimentionName=' + $scope.dimentionName)
                .success(function (response) {
                    $scope.dimensiones = response;
                    $scope.alertDimention = true;
                    $timeout(function () {
                        $scope.alertDimention = false;
                    }, 3000);
                });
        } else if ($scope.modifyDimention) {
            $http.get('./php/Dimenciones.php?action=edit&dimentionId=' + $scope.dimentionId + '&dimentionName=' + $scope.dimentionName)
                .success(function (response) {
                    $scope.dimensiones = response;
                    $scope.alertDimention = true;
                    $timeout(function () {
                        $scope.alertDimention = false;
                    }, 3000);
                });
        }
    };

    $scope.ToComponent = function (component) {
        $scope.swicth("Componentes");
        $scope.filter = component;
        setTimeout(function () {
            document.getElementById(component).click();
        }, 300);

    };


    /****************************************************************/

    $scope.idDimentionSelected = "";
    $scope.componentName = "";
    $scope.componentId = undefined;
    $scope.componentFail = true;
    $scope.alertComponent = false;


    $scope.$watch('componentName', function () {
        $scope.ComponentValidate();
    });
    $scope.ComponentValidate = function () {
        if (!$scope.componentName.length && !$scope.idDimentionSelected.length) {
            $scope.componentFail = true;
        } else {
            $scope.componentFail = false;
        }
    };

    $scope.ToDimention = function (dimention) {
        $scope.swicth("Dimensiones");
        $scope.filter = dimention;
        setTimeout(function () {
            document.getElementById(dimention).click();
        }, 300);

    };

    $scope.addComponent = function () {
        $scope.componentName = "";
        $scope.componentId = undefined;
        $scope.idDimentionSelected = "";
        $scope.modifyComponent = false;
        $scope.deleteComponent = false;
        $scope.newComponent = true;
    };

    $scope.editComponent = function (id, desc, idD) {
        $scope.componentName = desc;
        $scope.componentId = id;
        $scope.idDimentionSelected = idD;
        $scope.newComponent = false;
        $scope.deleteComponent = false;
        $scope.modifyComponent = true;
    };

    $scope.removeComponent = function (ev, id) {
        $scope.newComponent = false;
        $scope.deleteComponent = true;
        $scope.modifyComponent = false;
        var confirm = $mdDialog.confirm()
            .title('¿Desea eliminar este componente?')
            .textContent('Si el componente es eliminado todo lo relacionado con él se eliminará.')
            .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Si')
            .cancel('No');
        $mdDialog.show(confirm).then(function () {
            $http.get('./php/Componentes.php?action=remove&componentId=' + id)
                .success(function (response) {
                    $scope.componentes = response;
                    $scope.alertComponent = true;
                    $timeout(function () {
                        $scope.alertComponent = false;
                    }, 3000);
                });
        });
    };

    $scope.saveComponent = function () {
        if ($scope.newComponent) {
            $http.get('./php/Componentes.php?action=insert&componentName=' + $scope.componentName + '&dimentionId=' + $scope.idDimentionSelected)
                .success(function (response) {
                    $scope.componentes = response;
                    $scope.alertComponent = true;
                    $timeout(function () {
                        $scope.alertComponent = false;
                    }, 3000);
                });
        } else if ($scope.modifyComponent) {
            $http.get('./php/Componentes.php?action=edit&componentId=' + $scope.componentId + '&componentName=' + $scope.componentName + '&dimentionId=' + $scope.idDimentionSelected)
                .success(function (response) {
                    $scope.componentes = response;
                    $scope.alertComponent = true;
                    $timeout(function () {
                        $scope.alertComponent = false;
                    }, 3000);
                });
        }
    };


    /****************************************************************/
    $scope.criterionFail = true;
    $scope.criterionName = "";
    $scope.criterionDesc = "";
    $scope.criterionId = undefined;
    $scope.alertCriterion = false;

    $scope.$watch('criterionName', function () {
        $scope.criterionNameValidate();
    });
    $scope.criterionNameValidate = function () {
        if (!$scope.criterionName.length) {
            $scope.criterionFail = true;
        } else {
            $scope.criterionFail = false;
        }
    };

    $scope.removeCriterion = function (ev, id) {
        $scope.modifyCriterion = false;
        $scope.deleteCriterion = true;
        $scope.newCriterion = false;

        var confirm = $mdDialog.confirm()
            .title('¿Desea eliminar este criterio?')
            .textContent('Si el criterio es eliminad todo lo relacionado con el mismo se eliminará.')
            .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Si')
            .cancel('No');
        $mdDialog.show(confirm).then(function () {
            $http.get('./php/Criterios.php?action=remove&criterionId=' + id)
                .success(function (response) {
                    $scope.criterios = response;
                    $scope.alertCriterion = true;
                    $timeout(function () {
                        $scope.alertCriterion = false;
                    }, 3000);
                });
        });
    };

    $scope.addCriterion = function () {
        $scope.criterionName = "";
        $scope.criterionDesc = "";
        $scope.criterionId = undefined;
        $scope.modifyCriterion = false;
        $scope.deleteCriterion = false;
        $scope.newCriterion = true;
    };

    $scope.editCriterion = function (id, num, desc) {
        $scope.criterionName = num;
        $scope.criterionDesc = desc;
        $scope.criterionId = id;
        $scope.modifyCriterion = true;
        $scope.deleteCriterion = false;
        $scope.newCriterion = false;

    };

    $scope.saveCriterion = function () {
        if ($scope.newCriterion) {
            $http.get('./php/Criterios.php?action=insert&criterionName=' + $scope.criterionName + '&criterionDesc=' + $scope.criterionDesc)
                .success(function (response) {
                    $scope.criterios = response;
                    $scope.alertCriterion = true;
                    $timeout(function () {
                        $scope.alertCriterion = false;
                    }, 3000);
                });
        } else if ($scope.modifyCriterion) {
            $http.get('./php/Criterios.php?action=edit&criterionId=' + $scope.criterionId + '&criterionName=' + $scope.criterionName + '&criterionDesc=' + $scope.criterionDesc)
                .success(function (response) {
                    $scope.criterios = response;
                    $scope.alertCriterion = true;
                    $timeout(function () {
                        $scope.alertCriterion = false;
                    }, 3000);
                });
        }
    };

    $scope.ToCriterion = function (criterion) {
        $scope.swicth("Criterios");
        $scope.filter = criterion;
        setTimeout(function () {
            document.getElementById(criterion).click();
        }, 300);

    };


    /****************************************************************/
    $scope.weaknessId = undefined;
    $scope.causes = "";
    $scope.targets = "";
    $scope.indicators = "";

    $scope.causesEdit = function (id, cause) {
        $scope.weaknessId = id;
        $scope.causes = cause;
    }

    $scope.indicatorsEdit = function (id, indicator) {
        $scope.weaknessId = id;
        $scope.indicators = indicator;
    }

    $scope.targetsEdit = function (id, target) {
        $scope.weaknessId = id;
        $scope.targets = target;
    }

    $scope.editCauses = function () {
        $http.get('./php/Debilidades.php?action=editCauses&weaknessId=' + $scope.weaknessId + '&causes=' + $scope.causes)
            .success(function (response) {
                $scope.debilidades = response;
                setTimeout(function () {
                    document.getElementById("btn_" + $scope.weaknessId).click();
                }, 150);
            });
    }

    $scope.editTargets = function () {
        $http.get('./php/Debilidades.php?action=editTargets&weaknessId=' + $scope.weaknessId + '&targets=' + $scope.targets)
            .success(function (response) {
                $scope.debilidades = response;
                setTimeout(function () {
                    document.getElementById("btn_" + $scope.weaknessId).click();
                }, 150);
            });
    }

    $scope.editIndicators = function () {

        $http.get('./php/Debilidades.php?action=editIndicators&weaknessId=' + $scope.weaknessId + '&indicators=' + $scope.indicators)
            .success(function (response) {
                $scope.debilidades = response;
                setTimeout(function () {
                    document.getElementById("btn_" + $scope.weaknessId).click();
                }, 150);
            });
    }

    $scope.ToWeakness = function (weakness) {
        $scope.swicth("Debilidades");
        $scope.filter = weakness;
    };


})
