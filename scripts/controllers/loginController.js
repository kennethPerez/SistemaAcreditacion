angular.module("app")
    
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
                    console.log(response);
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

        $scope.enterLogin = function(e){
            if (e.keyCode === 13) {
                $scope.Login();
            }
        }

    })
