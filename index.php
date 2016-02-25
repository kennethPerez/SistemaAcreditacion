<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Compromiso de mejora</title>
            
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.0.0/angular-material.min.css">
        
        <style>
            
            
            .box{
    
   // width: 300px;
    //min-height: 150px;
    padding: 10px;
    position:relative;
    background: -webkit-gradient(linear, 0% 20%, 0% 92%, from(#f3f3f3), to(#fff), color-stop(.1,#f3f3f3));
    border-top: 2px solid #ccc;
    border-right: 2px solid #ccc;
    -webkit-border-bottom-right-radius: 60px 60px;
    -webkit-box-shadow: -1px 2px 2px rgba(0, 0, 0, 0.2);
    
}
.box:before{
    content:'';    
    width: 25px;
    height: 20px;
    position: absolute;
    bottom:0;
    right:0;
    -webkit-border-bottom-right-radius: 30px;
    -webkit-box-shadow: -2px -2px 5px rgba(0, 0, 0, 0.3);
    -webkit-transform:
    rotate(-20deg)
    skew(-40deg,-3deg)
    translate(-13px,-13px);
}

.box:after{
    content: '';
    z-index: -1;
    width: 100px;
    height: 100px;
    position:absolute;
    bottom:0;
    right:0;
    background: rgba(0, 0, 0, 0.2);
    display: inline-block;
    -webkit-box-shadow: 20px 20px 8px rgba(0, 0, 0, 0.2);
    -webkit-transform: rotate(0deg)
                        translate(-45px,-20px)
                           skew(20deg);
}


.h5{
    font-size: 16px;
    //font-weight: bold;
    margin-top: 5px; 
    text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
}

        </style>
        
    </head>
    <body ng-app="app">
        
        <div ng-view></div>
        
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular-route.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular-animate.min.js"></script>
        <script src="https://code.angularjs.org/1.5.0/angular-aria.min.js"></script>
        <script src="https://code.angularjs.org/1.5.0/angular-messages.min.js"></script>
        <script src="https://code.angularjs.org/1.5.0/angular-cookies.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/1.1.2/ui-bootstrap-tpls.min.js"></script>
        <script src="https://code.jquery.com/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>           
        <script src="http://ajax.googleapis.com/ajax/libs/angular_material/1.0.0/angular-material.min.js"></script>
        
        <script src="scripts/app.js"></script>
        <script src="scripts/controllers/loginController.js"></script>
        <script src="scripts/controllers/cordinatorController.js"></script>
        
    </body>
</html>