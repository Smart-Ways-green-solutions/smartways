<?php

namespace App\Model;

use App\Model\CustomerManagementFramework\PasswordRecoveryInterface;

class Customer extends \Pimcore\Model\DataObject\Customer implements PasswordRecoveryInterface
{

}
