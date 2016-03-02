angular.module("app", ["ngRoute", 'ngAnimate', 'ui.bootstrap', 'ngCookies', 'ngMaterial', 'ngTextTruncate'])
    
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

                if(this.in_array("/coordinador", privateRoutes) && typeof(userData) != "undefined" && userData.tipo === "0")
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

    .run(function($rootScope, auth)
    {
        $rootScope.$on('$routeChangeStart', function()
        {
            auth.checkStatus();
        })
    })

;
