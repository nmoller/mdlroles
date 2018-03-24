<?php
/**
 * Created by PhpStorm.
 * User: nmoller
 * Date: 24/03/18
 * Time: 3:55 PM
 */

namespace local_uqmoveroles;


class CliHelper
{
    const ROLES_DIR_HOME = __DIR__ . '/../roles/';
    const ARRAY_OPTIONS = [
        'shortname'     => 1,
        'name'          => 1,
        'description'   => 1,
        'permissions'   => 1,
        'archetype'     => 1,
        'contextlevels' => 1,
        'allowassign'   => 1,
        'allowoverride' => 1,
        'allowswitch'   => 1
    ];

    public function roleExists($role) {
        if (file_exists(self::ROLES_DIR_HOME . "${role}.xml")) {
            return true;
        }
        return false;
    }

    public function getRoleFile($role) {
        if ($this->roleExists($role)) {
            return self::ROLES_DIR_HOME . "${role}.xml";
        }
    }
}