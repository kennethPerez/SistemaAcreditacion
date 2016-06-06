angular.module("app")

.controller("coordinadorController", function ($scope, $http, $location, $cookies, $timeout, $mdDialog, auth) {
    $scope.userData = $cookies.getObject('userData');
    $scope.tab = "Actividades";

    $scope.debilidades = [];
    $scope.dimensiones = [];
    $scope.componentes = [];
    $scope.criterios = [];
    $scope.actividades = [];
    $scope.filter = "";

    $scope.logout = function () {
        auth.logout();
    };

    var bool = 0;

    $scope.swicth = function (tab) {
        bool = 0
        $scope.debilidades = [];
        $scope.dimensiones = [];
        $scope.componentes = [];
        $scope.criterios = [];
        $scope.actividades = [];
        $scope.filter = "";

        switch (tab) {
            case 'Debilidades':
                $http.get('./php/Debilidades.php?action=get')
                    .success(function (response) {
                        $scope.debilidades = response;
                    });
                $http.get('./php/Criterios.php?action=get')
                    .success(function (response) {
                        $scope.criterios = response;
                    });
                $http.get('./php/Componentes.php?action=get')
                    .success(function (response) {
                        $scope.componentes = response;
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
            case 'Actividades':
                $http.get('./php/Debilidades.php?action=get')
                    .success(function (response) {
                        $scope.debilidades = response;
                    });
                $http.get('./php/Actividades.php?action=get')
                    .success(function (response) {
                        $scope.actividades = response;
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
    $scope.weaknessName = "";
    $scope.causes = "";
    $scope.locations = "";
    $scope.idComponentSelected = "";


    $scope.selectedItem = null;
    $scope.searchText = null;
    $scope.selectedCriterion = [];

    $scope.causesEdit = function (id, cause) {
        $scope.weaknessId = id;
        $scope.causes = cause;
    }
    $scope.locationsEdit = function (id, locations) {
        $scope.weaknessId = id;
        $scope.locations = locations;
    }


    $scope.criterionsEdit = function (id, criterios, d) {
        if (bool == 0) {
            $scope.filter = d;
            $scope.weaknessId = id;
            $scope.selectedCriterion = criterios;
            $scope.criterions = loadCriterios();
            bool = 1;
        } else {
            bool = 0
            $scope.filter = "";
        }


    }

    $scope.editCauses = function () {
        $http.get('./php/Debilidades.php?action=editCauses&weaknessId=' + $scope.weaknessId + '&causes=' + $scope.causes)
            .success(function (response) {
                $scope.debilidades = response;
                bool = 0;
                setTimeout(function () {
                    $scope.causes = "";
                    document.getElementById("btn_" + $scope.weaknessId).click();
                }, 150);
            });
    }

    $scope.editLocations = function () {
        $http.get('./php/Debilidades.php?action=editLocations&weaknessId=' + $scope.weaknessId + '&locations=' + $scope.locations)
            .success(function (response) {
                $scope.debilidades = response;
                bool = 0;
                setTimeout(function () {
                    document.getElementById("btn_" + $scope.weaknessId).click();
                    $scope.locations = "";
                }, 150);
            });
    }

    $scope.editCriterions = function () {
        var list = [];

        $scope.selectedCriterion.forEach(function (value) {
            list.push(value.num)
        });

        $http.get('./php/Debilidades.php?action=editCriterions&weaknessId=' + $scope.weaknessId + '&criterions=' + list)
            .success(function (response) {
                /*$scope.debilidades = response;
                                            setTimeout(function () {
                                                document.getElementById("btn_" + $scope.weaknessId).click();
                                            }, 150);*/
            });
    }

    $scope.querySearch = function (query) {
        var results = query ? $scope.criterions.filter(createFilterFor(query)) : [];
        return results;
    };

    $scope.$watch('weaknessName', function () {
        $scope.weaknessNameValidate();
    });
    $scope.weaknessNameValidate = function () {
        if (!$scope.weaknessName.length) {
            $scope.weaknessFail = true;
        } else {
            $scope.weaknessFail = false;
        }
    };


    $scope.addWeakness = function () {
        $scope.weaknessName = "";
        $scope.weaknessId = undefined;
        $scope.idComponentSelected = "";
        $scope.modifyWeakness = false;
        $scope.deleteWeakness = false;
        $scope.newWeakness = true;
    };

    $scope.editWeakness = function (id, weakness, idC) {
        $scope.weaknessName = weakness;
        $scope.weaknessId = id;
        $scope.idComponentSelected = idC;
        $scope.newWeakness = false;
        $scope.deleteWeakness = false;
        $scope.modifyWeakness = true;

    };

    $scope.saveWeakness = function () {
        if ($scope.newWeakness) {
            $http.get('./php/Debilidades.php?action=insert&weaknessName=' + $scope.weaknessName + '&idC=' + $scope.idComponentSelected)
                .success(function (response) {
                    $scope.debilidades = response;
                    $scope.alertWeakness = true;
                    $timeout(function () {
                        $scope.alertWeakness = false;
                    }, 3000);
                });
        } else if ($scope.modifyWeakness) {
            $http.get('./php/Debilidades.php?action=edit&weaknessId=' + $scope.weaknessId + '&weaknessName=' + $scope.weaknessName + '&idC=' + $scope.idComponentSelected)
                .success(function (response) {
                    $scope.debilidades = response;
                    bool = 0;
                    $scope.filter = $scope.weaknessName;
                    $scope.alertWeakness = true;
                    $timeout(function () {
                        $scope.alertWeakness = false;
                    }, 3000);
                });
        }
    };

    $scope.removeWeakness = function (ev, id) {
        $scope.newWeakness = false;
        $scope.deleteWeakness = true;
        $scope.modifyWeakness = false;

        var confirm = $mdDialog.confirm()
            .title('¿Desea eliminar esta debilidad?')
            .textContent('Si la debilidad es eliminada todo lo relacionado con la misma se eliminará.')
            .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Si')
            .cancel('No');
        $mdDialog.show(confirm).then(function () {
            $http.get('./php/Debilidades.php?action=remove&weaknessId=' + id)
                .success(function (response) {
                    $scope.debilidades = response;
                    bool = 0;
                    $scope.alertWeakness = true;
                    $timeout(function () {
                        $scope.alertWeakness = false;
                    }, 3000);
                });
        });
    };


    function createFilterFor(query) {
        var lowercaseQuery = angular.lowercase(query);

        return function filterFn(criterio) {
            return (criterio._lowernum.indexOf(lowercaseQuery) === 0) ||
                (criterio._lowerdesc.indexOf(lowercaseQuery) === 0);
        };

    }

    function loadCriterios() {
        var criterion = $scope.criterios;
        return criterion.map(function (cri) {
            cri._lowernum = cri.num.toLowerCase();
            cri._lowerdesc = cri.desc.toLowerCase();
            return cri;
        });
    }

    $scope.getSelectedChipIndex = function (event) {
        var selectedChip = angular.element(event.currentTarget).controller('mdChips').selectedChip;
        if (selectedChip != -1) {
            console.log($scope.selectedCriterion[selectedChip].id);
        }
    }

    $scope.ToWeakness = function (weakness) {
        $scope.swicth("Debilidades");
        $scope.filter = weakness;
    };


    $scope.TargetA = true;
    $scope.TargetE = false;
    $scope.targets = "";
    $scope.targetsId = "";

    $scope.addTarget = function (id) {
        $scope.targets = "";
        $scope.weaknessId = id;
        $scope.TargetA = true;
        $scope.TargetE = false;
    }

    $scope.editTarget = function (id, idO, o) {
        $scope.weaknessId = id;
        $scope.TargetA = false;
        $scope.TargetE = true;
        $scope.targets = o;
        $scope.targetsId = idO;
    }


    $scope.$watch('targets', function () {
        $scope.weaknessTargetsValidate();
    });
    $scope.weaknessTargetsValidate = function () {
        if (!$scope.targets.length) {
            $scope.targetsFail = true;
        } else {
            $scope.targetsFail = false;
        }
    };

    $scope.targetsAdd = function () {
        if ($scope.TargetA) {
            $http.get('./php/Debilidades.php?action=targetsAdd&weaknessId=' + $scope.weaknessId + '&targets=' + $scope.targets)
                .success(function (response) {
                    bool = 0;
                    $scope.debilidades = response;
                });
        } else if ($scope.TargetE) {
            $http.get('./php/Debilidades.php?action=targetsEdit&targets=' + $scope.targets + '&targetsId= ' + $scope.targetsId)
                .success(function (response) {
                    bool = 0;
                    $scope.debilidades = response;
                });

        }
        setTimeout(function () {
            $scope.targets = "";
            document.getElementById("btn_" + $scope.weaknessId).click();
        }, 150);
    }

    $scope.targetsDelete = function (ev) {
        var confirm = $mdDialog.confirm()
            .title('¿Desea eliminar este objetivo?')
            .textContent('Si el objetivo es eliminado todos los indicadores se eliminaran tambien.')
            .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Si')
            .cancel('No');
        $mdDialog.show(confirm).then(function () {
            $http.get('./php/Debilidades.php?action=removeTarget&targetId=' + $scope.targetsId)
                .success(function (response) {
                    bool = 0;
                    $scope.debilidades = response;
                });
            setTimeout(function () {
                $scope.targets = "";
                document.getElementById("btn_" + $scope.weaknessId).click();
            }, 150);
        });
    }








    $scope.IndicatorA = true;
    $scope.IndicatorE = false;
    $scope.indicator = "";
    $scope.IndicatorId = "";

    $scope.addIndicator = function (id) {
        $scope.indicator = "";
        $scope.targetsId = id;
        $scope.IndicatorA = true;
        $scope.IndicatorE = false;
    }

    $scope.editIndicator = function (id, i) {
        $scope.IndicatorId = id;
        $scope.IndicatorA = false;
        $scope.IndicatorE = true;
        $scope.indicator = i;
    }


    $scope.$watch('indicator', function () {
        $scope.weaknessIndicatorValidate();
    });
    $scope.weaknessIndicatorValidate = function () {
        if (!$scope.indicator.length) {
            $scope.indicatorFail = true;
        } else {
            $scope.indicatorFail = false;
        }
    };

    $scope.indicatorAdd = function () {
        if ($scope.IndicatorA) {
            $http.get('./php/Debilidades.php?action=indicatorAdd&targetsId=' + $scope.targetsId + '&indicator=' + $scope.indicator)
                .success(function (response) {
                    bool = 0;
                    $scope.debilidades = response;
                });
        } else if ($scope.IndicatorE) {
            $http.get('./php/Debilidades.php?action=indicatorEdit&indicator=' + $scope.indicator + '&IndicatorId= ' + $scope.IndicatorId)
                .success(function (response) {
                    bool = 0;
                    $scope.debilidades = response;
                });

        }
        setTimeout(function () {
            $scope.indicator = "";
            document.getElementById("btn_" + $scope.weaknessId).click();
        }, 150);
    }

    $scope.indicatorDelete = function (ev) {
        var confirm = $mdDialog.confirm()
            .title('¿Desea eliminar este indicador?')
            .textContent('Si el indicador es eliminado todo lo relacionado con el mismo se eliminara')
            .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Si')
            .cancel('No');
        $mdDialog.show(confirm).then(function () {
            $http.get('./php/Debilidades.php?action=removeIndicator&IndicatorId=' + $scope.IndicatorId)
                .success(function (response) {
                    bool = 0;
                    $scope.debilidades = response;
                });
            setTimeout(function () {
                $scope.indicator = "";
                document.getElementById("btn_" + $scope.weaknessId).click();
            }, 150);
        });
    }




    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/

    $scope.activity = {
        'idActividad': "",
        'fecha': "",
        'encargado': "",
        'actividad': "",
        'idDebilidad': "",
        'idObjetivo': ""
    };

    $scope.addActivity = function () {
        $scope.activity = {
            'idActividad': "",
            'fecha': "",
            'encargado': "",
            'actividad': "",
            'idDebilidad': "",
            'idObjetivo': ""
        };
        $scope.newActivity = true;
        $scope.modifyActivity = false;
        $scope.actObj = [];
        $scope.indicadoresActividad = [];
    };

    $scope.editActivity = function (activity) {
        $scope.activity = activity;
        $http.get('./php/Actividades.php?action=getO&idD=' + $scope.activity.idDebilidad)
            .success(function (response) {
                $scope.actObj = response;
            });
        $scope.indicadoresActividad = activity.indicadores;
        $scope.modifyActivity = true;
        $scope.newActivity = false;
    };


    $scope.saveActivity = function () {
        if ($scope.newActivity) {
            $http.get('./php/Actividades.php?action=insert&idDebilidad=' + $scope.activity.idDebilidad + '&idObjetivo=' + $scope.activity.idObjetivo + '&fecha=' + $scope.activity.fecha + '&encargado=' + $scope.activity.encargado + '&actividad=' + $scope.activity.actividad)
                .success(function (response) {

                    $scope.actividades = response;
                    console.log("Actividades despues de insertar sin ind", $scope.actividades)

                    $scope.indicadoresActividad.forEach(function (value) {
                        if (value.selected == "true") {
                            $http.get('./php/Actividades.php?action=act_ind&idActividad=' + $scope.actividades[$scope.actividades.length - 1].idActividad + '&Idindicador=' + value.idIndicador)
                                .success(function (response) {
                                    $scope.actividades = response;
                                    console.log("Actividades despues de insertar ind", $scope.actividades)
                                })
                        }
                    })
                    $scope.indicadoresActividad = []

                });
        } else {
            $scope.filter = $scope.activity.actividad;
            $http.get('./php/Actividades.php?action=update&idActividad=' + $scope.activity.idActividad + '&idDebilidad=' + $scope.activity.idDebilidad + '&idObjetivo=' + $scope.activity.idObjetivo + '&fecha=' + $scope.activity.fecha + '&encargado=' + $scope.activity.encargado + '&actividad=' + $scope.activity.actividad)
                .success(function (response) {

                    $scope.actividades = response;

                    $scope.indicadoresActividad.forEach(function (value) {
                        if (value.selected == "true") {
                            $http.get('./php/Actividades.php?action=act_ind&idActividad=' + $scope.activity.idActividad + '&Idindicador=' + value.idIndicador)
                                .success(function (response) {
                                    $scope.actividades = response;
                                })
                        }
                    })

                });


            setTimeout(function () {
                document.getElementById("btn_" + $scope.activity.idActividad).click();
            }, 150);
        }
    }

    $scope.selectWeek = function () {
        $http.get('./php/Actividades.php?action=getO&idD=' + $scope.activity.idDebilidad)
            .success(function (response) {
                $scope.actObj = response;
            });
    }

    $scope.selectObj = function () {
        $http.get('./php/Actividades.php?action=getI&idO=' + $scope.activity.idObjetivo + '&idA=' + $scope.activity.idActividad)
            .success(function (response) {
                $scope.indicadoresActividad = response;
            });
    }

    $scope.removeActivity = function (ev, id) {
        var confirm = $mdDialog.confirm()
            .title('¿Desea eliminar esta actividad?')
            .textContent('Si la actividad es eliminada todo lo relacionado con la misma se eliminara')
            .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Si')
            .cancel('No');
        $mdDialog.show(confirm).then(function () {
            $http.get('./php/Actividades.php?action=remove&activityId=' + id)
                .success(function (response) {
                    $scope.actividades = response;
                });
        });
    }

})
