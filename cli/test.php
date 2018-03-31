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

$userId =3;
$courseId = 2;
$t = enrol_get_plugin('manual');
$enrol_methods = enrol_get_instances($courseId, true);
$manual_instance = array_values(
        array_filter(
            $enrol_methods,
            function($method){ return $method->enrol === 'manual';}
        )
    )[0];
$course_ctx = context_course::instance($courseId);

$roles = get_all_roles($course_ctx);

$teacher_role = array_values(
        array_filter(
            $roles,
            function($role){ return $role->shortname === 'editingteacher';}
        )
    )[0];

$t->enrol_user($manual_instance, $userId, $teacher_role->id);
// Ajouter en suite l'autre rôle
$another_role = array_values(
        array_filter(
            $roles,
            function($role){ return $role->shortname === 'teacher';}
        )
    )[0];
role_assign($another_role->id, $userId, $course_ctx, '', NULL);
