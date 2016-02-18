angular.module("app", ["ngRoute", 'ngAnimate', 'ui.bootstrap', 'ngCookies', 'ngMaterial'])
    
    .config(function($routeProvider)
    {
        $routeProvider
            .when("/", {
                controller: "loginController",
                templateUrl: "views/Login.html"
            })
            .when("/coordinador", {
                controller: "coordinadorController",
                templateUrl: "views/Coordinador.html"
            })
            .otherwise({
                redirectTo: "/"
            });
    })
    
    .factory('auth', function ($cookies, $location) 
     {
        return {
            login : function(data)
            {                
                $cookies.putObject('userData', data);                

                if(data.tipo === "0")
                {                            
                    $location.path('/coordinador');
                }
                else
                {
                    alert("Tipo de usuario no registrado en el sistema");
                    $location.path("/");
                }
            },

            logout : function()
            {
                $cookies.remove('userData');                                
                $location.path("/");
            },

            checkStatus : function()
            {
                var privateRoutes = ["/coordinador"];
                var userData = $cookies.getObject('userData');
                
                if(this.in_array($location.path(), privateRoutes) && typeof(userData) == "undefined")
                {
                    $location.path("/");
                }

                if(this.in_array("/coordinador", privateRoutes) && typeof(userData) != "undefined" && userData.tipo === "1")
                {
                    $location.path("/coordinador");
                }
            },

            in_array : function(needle, haystack)
            {
                var key = '';
                for(key in haystack)
                {
                    if(haystack[key] == needle)
                    {
                        return true;
                    }
                }
                return false;
            }
        }    
    })
    
    .controller("loginController", function($scope, $http, auth) 
    {
        $scope.tagError = '';
        $scope.user = "admin";
        $scope.pass = "12345";
        
        $scope.Login = function() 
        {        
            $http.get('./php/Login.php?user='+$scope.user+"&pass="+$scope.pass)
                .success(function(response)
                {   
                    if(response !== "false")
                    {   
                        auth.login(response);
                    }
                    else
                    {
                        $scope.tagError = 'Login incorrecto.';
                    }
                });
        };
    })
    
    .controller("coordinadorController", function($scope, $http, $location, $cookies, $mdDialog, auth)
    {
        $scope.userData = $cookies.getObject('userData');    
        $scope.tab = "Debilidades";
        $scope.filter = "";
        
        $scope.debilidades = [];
        $scope.dimensiones = [];
        $scope.componentes = [];
        $scope.criterios = [];
        $scope.dimentionNameFail = true;
    
        $scope.dimentionName = "";
        $scope.dimentionId = undefined;
    
        $scope.$watch('dimentionName',function() {$scope.dimentionNameValidate();});

        $scope.dimentionNameValidate = function() 
        {
            if(!$scope.dimentionName.length) 
            {
                $scope.dimentionNameFail = true;
            }
            else
            {
                $scope.dimentionNameFail = false;
            }
        };
    
    
        $scope.removeDimention = function(ev,id) {
            
            var confirm = $mdDialog.confirm()
            .title('¿Desea eliminar esta dimesión?')
            .textContent('Si la dimensión es eliminada todo lo relacionado con la misma se eliminará.')
            .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Si')
            .cancel('No');
            $mdDialog.show(confirm).then(function() {
                $http.get('./php/Dimenciones.php?action=remove&dimentionId='+id)
                    .success(function(response) {   
                    $scope.dimensiones = response;
                });
            });
        };
    
            
        $scope.logout = function()
        {
            auth.logout();
        };
    
        $scope.addDimention = function()
        {
            $scope.dimentionName = "";
            $scope.dimentionId = undefined;
            $scope.modifyDimention = false;
            $scope.newDimention = true;
        };
    
        $scope.editDimention = function(id, desc)
        {
            $scope.dimentionName = desc;
            $scope.dimentionId = id;
            $scope.newDimention = false;
            $scope.modifyDimention = true;  
            
        };
    
        $scope.saveDimention = function()
        {
            if($scope.newDimention){
                $http.get('./php/Dimenciones.php?action=insert&dimentionName='+$scope.dimentionName)
                    .success(function(response){   
                    $scope.dimensiones = response;
                });
            }
            else if($scope.modifyDimention){
                $http.get('./php/Dimenciones.php?action=edit&dimentionId='+$scope.dimentionId+'&dimentionName='+$scope.dimentionName)
                    .success(function(response){   
                    $scope.dimensiones = response;
                });
            }
        };

        $scope.swicth = function(tab)
        {
            switch (tab) 
            {
                case 'Debilidades':
                    
                    break;
                
                case 'Dimensiones':
                    $http.get('./php/Dimenciones.php?action=get')
                        .success(function(response){   
                        $scope.dimensiones = response;
                    });
                    break;
                
                case 'Componentes':
                    $http.get('./php/Componentes.php?action=get')
                        .success(function(response){
                        $scope.componentes = response;
                    });
                    $http.get('./php/Dimenciones.php?action=get')
                        .success(function(response){   
                        $scope.dimensiones = response;
                    });
                    break;
                
                case 'Criterios':
                    break;

            }
            $scope.tab = tab;
        };
        
        $scope.isActive = function (tab) 
        {
            if (tab === $scope.tab) {
                return true;
            }

            return false;
        };
        
    })

    .run(function($rootScope, auth)
    {
        $rootScope.$on('$routeChangeStart', function()
        {
            auth.checkStatus();
        })
    });
