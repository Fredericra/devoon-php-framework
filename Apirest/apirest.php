<?php

use ProviderMain\Apirest\Request\Request;
use ProviderMain\Apirest\Route\RouteAs;
use webApp\Controller\Authenticate\AuthController;
use webApp\Controller\MainController;
use webApp\Controller\User\UserController;

RouteAs::GroupMiddle("client",function(){
    RouteAs::GET("/",[MainController::class,"home"])->name("home");
    RouteAs::GET("/guest",[MainController::class,"guest"])->name("get.guest");
    RouteAs::GET("/delete/{email}",[MainController::class,"deleteguest"])->name("delete.guest");
    RouteAs::GET("/signIn",[UserController::class,"signin"])->name("get.sigin");
    RouteAs::GET("/logIn",[UserController::class,"login"])->name("get.login");
    RouteAs::POST("/signIn",[UserController::class,"signin"])->name("post.sigin");
    RouteAs::POST("/logIn",[UserController::class,"login"])->name("post.login");
    RouteAs::GET("/forget/{email}",[UserController::class,"forget"])->name("get.forget");
    RouteAs::POST("/config",[UserController::class,"config"])->name("config");
    RouteAs::GET("/login/{username}",[MainController::class,"loginAdmin"])->name("login.guest");
});

RouteAs::GET("/admin/{id}/produit/{produitId}","webApp\Controller\MainController@home")->name("admin.id");

RouteAs::API("http://www.devspark.com",[MainController::class,"admin"])->name("documentation");
RouteAs::GET("/admin/auth","webApp\Controller\MainController@admin")->name("admin")->middleware("admin");

RouteAs::GroupMiddle("authenticate",function(){
    RouteAs::GET("/Authenticate",[AuthController::class,"main"])->name("main");
    RouteAs::POST("/Authenticate",[AuthController::class,"mains"])->name("post.main");
    RouteAs::GET("/logoout",[AuthController::class,"Destroy"])->name("out");
});

RouteAs::Run();