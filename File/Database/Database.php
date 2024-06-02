<?php

use ProviderMain\Database\DB\DB;

DB::TableName('user')->
Create([
    "IdUser"=>"id",
    "email"=>"text:200|not null",
    "username"=>"text:200|not null",
    "password"=>"text:200|not null",
]);

DB::TableName('produit')->
Create([
    "produitId"=>"id",
    "list"=>"int:200|not null",
    "name"=>"text:200|not null",
    "quantite"=>"int:200|not null",
    "relation"=>"table:user|id:IdUser"
]);

DB::TableName("devsparks")->
Create([
    "Iddevsparks"=>"id",
    "app"=>"bool",
    "up"=>"date"
]);

DB::TableName("guests")->
Create([
    "IdGuests"=>"id",
    "user"=>"text:300|not null",
    "cookier"=>"text:300|not null"
]);