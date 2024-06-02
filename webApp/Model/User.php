<?php

namespace webApp\Model;

use ProviderMain\Database\Module\ConfigModule;
use ProviderMain\Database\Module\Module;
use webApp\Model\Produit\Produit;
use ProviderMain\Database\Module\Relation;

class User extends Module
{
    use ConfigModule,Relation;
    public function user()
    {
        $this->MuchAs(Produit::class,"idUser");
    }


}