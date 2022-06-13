<?php
namespace App\Enum;
enum PermissionType:string{
    case Create= "CREATE";
    case Delete= "DELETE";
    case Update= "UPDATE";
    case None= "NONE";
}