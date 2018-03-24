<?php
/**
 * Created by PhpStorm.
 * User: nmoller
 * Date: 24/03/18
 * Time: 3:42 PM
 */

namespace local_uqmoveroles;

use \core_role_define_role_table_basic;

defined('MOODLE_INTERNAL') || die();

/**
 * Class RoleTreater
 * @package local_uqmoveroles
 * On doit simuler le traitement des paramètres recus dans le formulaires:
 * admin/roles/classes/capability_table_with_risks.php
 */
class RoleTreater extends \core_role_define_role_table_basic
{
    public function getCapabilities()
    {
        return $this->permissions;
    }

    public function read_submitted_permissions()
    {
        $this->changed = array();
        foreach ($this->permissions as $permission => $val) {
            if ($val !== 0) {
                $this->changed[] = $permission;
            }
        }
    }

    public function save_changes() {
        parent::save_changes();
        // Set the permissions.
        foreach ($this->changed as $changedcap) {
            assign_capability($changedcap, $this->permissions[$changedcap],
                $this->roleid, $this->context->id, true);
        }

        // Force accessinfo refresh for users visiting this context.
        $this->context->mark_dirty();
    }
}