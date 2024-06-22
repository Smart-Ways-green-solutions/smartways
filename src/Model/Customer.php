<?php

namespace App\Model;

use App\Model\CustomerManagementFramework\PasswordRecoveryInterface;
use Carbon\Carbon;

class Customer extends \Pimcore\Model\DataObject\Customer implements PasswordRecoveryInterface
{

}
