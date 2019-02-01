<?php

namespace Deployer;

set('ssh_type', 'native');
set('ssh_multiplexing', true);

set('local/bin/wp', function () {
    $wpCliBin = null;
    if (testLocally('[ -e \'{{deploy_path}}/vendor/bin/wp\' ]')) {
        $wpCliBin = parse('{{deploy_path}}/vendor/bin/wp');
    } else {
        $wpCliBin = runLocally('which wp')->toString();
    }
    if (!$wpCliBin) {
        throw new \Exception('Can not determine wp_cli path. Make it available inside you PATH or use composer version.');
    }
    return $wpCliBin;
});

set('shared_dirs', [
        'web/app/uploads',
    ]
);

set('shared_files', [
    'web/wp-config.php',
    '.env',
]);

set('writable_dirs', [
        'web/app/uploads',
    ]
);

set('clear_paths', [
    '.editorconfig',
    '.env.example',
    '.git',
    '.gitattributes',
    '.gitignore',
    'CHANGELOG.md',
    'CODE_OF_CONDUCT.md',
    'LICENSE.md',
    'CONTRIBUTING.md',
    'README.md',
    'composer.json',
    'composer.lock',
    'composer.phar',
    'dependencies.yml',
    'phpcs.xml',
    'ruleset.xml',
    'travis.yml'
]);

// Look on https://github.com/sourcebroker/deployer-extended#buffer-start for docs
set('buffer_config', [
        'index.php' => [
            'entrypoint_filename' => 'web/index.php',
        ],
    ]
);

// Look https://github.com/sourcebroker/deployer-extended-media for docs
set('media',
    [
        'filter' => [
            '+ web/',
            '+ web/app/',
            '+ web/app/uploads/',
            '+ web/app/uploads/**',
            '- *'
        ]
    ]);

// Look https://github.com/sourcebroker/deployer-extended-database for docs
set('db_instance', function () {
    return (new \SourceBroker\DeployerExtendedWordpressComposer\Drivers\WordpressDriver)
        ->getInstanceName(getcwd());
});

set('default_stage', function () {
    return (new \SourceBroker\DeployerExtendedWordpressComposer\Drivers\WordpressDriver)
        ->getInstanceName(getcwd());
});

set('db_default', [
    'ignore_tables_out' => [],
    'post_sql_in' => '',
    'post_command' => ['{{local/bin/deployer}} db:import:post_command:wp_domains']
]);

set('db_databases',
    [
        'database_default' => [
            get('db_default'),
            function () {
                return (new \SourceBroker\DeployerExtendedWordpressComposer\Drivers\WordpressDriver)
                    ->getDatabaseConfig(getcwd());
            }
        ]
    ]
);

// Look https://github.com/sourcebroker/deployer-extended-database#db-dumpclean for docs
set('db_dumpclean_keep', [
    '*' => 5,
    'live' => 10,
]);