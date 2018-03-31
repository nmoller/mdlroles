<?php
/**
 * Created by PhpStorm.
 * User: nmoller
 * Date: 24/03/18
 * Time: 1:55 PM
 */

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/clilib.php');      // cli only functions


// now get cli options
list($options, $unrecognized) = cli_get_params(
                                    [ 'help' => false, 'role' => 0],
                                    ['h' => 'help']
                                );

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help']) {
    $help = <<<EOF

Options:
-h, --help            Print out this help
-r=accescla ou --role=accescla pour lire le fichier.

Example:
 	php cli/run.php --role=accescla 
EOF;

    echo $help;
    die;
}

/**
 * On obtient le premier valeur pour après avoir filtré $array par la fonction $callback
 * @param $array
 * @param $callback
 * @return mixed
 */
function filterArray($array, $callback) {
    return array_values(
        array_filter(
            $array,
            $callback
        )
    )[0];
}

$userId =3;
$courseId = 2;
$t = enrol_get_plugin('manual');
$enrol_methods = enrol_get_instances($courseId, true);
$manual_instance = filterArray($enrol_methods, function($method){ return $method->enrol === 'manual';});
$course_ctx = context_course::instance($courseId);

$roles = get_all_roles($course_ctx);

$teacher_role = filterArray($roles, function($role){ return $role->shortname === 'editingteacher';});
$t->enrol_user($manual_instance, $userId, $teacher_role->id);
// Ajouter en suite l'autre rôle
$another_role = filterArray($roles, function($role){ return $role->shortname === 'teacher';});
role_assign($another_role->id, $userId, $course_ctx, '', NULL);
