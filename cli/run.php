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
$helper = new local_uqmoveroles\CliHelper();

if (!$options['role'] || !$helper->roleExists($options['role'])) {
    echo "This role does not exist!!!" . PHP_EOL;
    die();
}

$defOptions = local_uqmoveroles\CliHelper::ARRAY_OPTIONS;

$systemcontext = context_system::instance();

$definitiontable = new local_uqmoveroles\RoleTreater($systemcontext, 0);
$xml = file_get_contents($helper->getRoleFile($options['role']));
$definitiontable->force_preset($xml, $defOptions);
$definitiontable->read_submitted_permissions();

if ($definitiontable->is_submission_valid()) {
    $definitiontable->save_changes();
    $tableroleid = $definitiontable->get_role_id();
    cli_writeln("Defined role id $tableroleid");
}


