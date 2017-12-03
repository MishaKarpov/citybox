<!DOCTYPE html>
<html ng-app="cityApp">
<head>
  <title>Городская коробка - Kiev citybox</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-route.js"></script>

<link rel="stylesheet" href="main.css">
<link rel="icon" href="home.ico" type="image/x-icon"/>

</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top" ng-controler="menuCtrl">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a class="navbar-brand" style="color: #fff;" href="#"><span class="glyphicon glyphicon-home" > </span> CityBox</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav" >
        <li><a href="#">InstaКиев</a></li>


        <li><a href="#!/chat">Чат</a></li>


      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#!/add"><span class="glyphicon glyphicon-plus"></span> Добавить</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid text-center" style="margin-top:52px;">
  <div class="row content">
    <div class="col-sm-2 side-left">

    </div>


    <div class="col-sm-8 text-left" >

<div ng-view autoscroll="true" ></div>



    </div>
    <div class="col-sm-2 sidenav">
<!--
      <div class="well">
        <p>ADS</p>
      </div>
      <div class="well">
        <p>ADS</p>
      </div>

-->

    </div>
  </div>
</div>


<footer class="container-fluid text-center" >
  <p>@CityBox 2017</p>
</footer>







<script>


function instatrim(a)
{

  var reg=/.com\/[a-zA-Z_.]+(\/|$)/g;
a=a.match(reg);
a=a.replace(".com/","");
a=a.replace("/","");
return a;
}


const PROXY="insta.php";

//const PROXY="https://www.instagram.com/"

var app = angular.module("cityApp", ["ngRoute"]);


app.factory('$accounts', function ($http) {


  var factory={};
  var accounts=[];
  var currentPage=0;
  var accountsPerPage=10;
  var isLoading={status:true};


  function instatrim(a)
  {

    var reg=/.com\/[a-zA-Z0-9_.]+(\/|$)/g;
  a=a.match(reg);
  a=a[0].replace(".com/","");
  a=a.replace("/","");
  return a;
  };

factory.getNewAccounts = function()
{
var min=1+currentPage*accountsPerPage;
var max=min+accountsPerPage-1;
isLoading.status=true;
  $http.get("https://spreadsheets.google.com/feeds/cells/1ZrhpN78ulEeRjOeub_ggxuxUvdVeuvdW8kvL-XUCiAc/od6/public/values?alt=json&min-col=2&max-col=2&min-row="+min+"&max-row="+max)
      .then(function (response) {
isLoading.status=false;
      response.data.feed.entry.forEach(function(entry) {
  var el = {};
   el.username=instatrim(entry.content.$t);
   el.isLoad=true;
      accounts.push(el);



  $http.get(PROXY+"?user="+el.username)
      .then(function (response) {

        if(!response.data.full_name) {el.isLoad=false; return;}
        el.isLoad=true;
        el.name=response.data.full_name;
        el.avaurl=response.data.profile_pic_url;
        el.is_private=response.data.is_private;
        el.is_empty=response.data.is_empty;

        el.photos=[];
        if(!response.data.is_empty && !response.data.is_private)
        response.data.media.forEach(function(node) {

          el.photos.push(
            {"icon" : node.thumbnail_src,
             "link" : node.code
            });

        });





                });







              });

          });
currentPage++;
return accounts;
}


factory.start = function()
{
  if(currentPage>0) return accounts;
  return this.getNewAccounts();
}


factory.loadingStatus = function()
{
  return isLoading;
}

        return factory;
    });



app.config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : "insta.html"
    })
    .when("/chat", {
        templateUrl : "chat.html"
    })
    .when("/add", {
        templateUrl : "form.html"
    })
    .when("/blue", {
        templateUrl : "blue.htm"
    });
  });


app.controller("instCtrl", function($scope, $accounts) {

$scope.accounts=$accounts.start();
$scope.isLoading=$accounts.loadingStatus();

$scope.loadMore= function(){
accounts=$accounts.getNewAccounts();
}

});









</script>



<script type="text/javascript">


$(document).on('click','.navbar-collapse.in',function(e) {
    if( $(e.target).is('a') && $(e.target).attr('class') != 'dropdown-toggle' ) {
        $(this).collapse('hide');
    }
});


</script>

</body>
</html>
