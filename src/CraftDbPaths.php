<?php

namespace samhernandez\craftdbpaths;

use Craft;

/**
 * Class Module
 *
 * @package samhernandez
 */
class CraftDbPaths extends \yii\base\Module
{
    /**
     * @inheritdoc
     * @throws \yii\base\ErrorException
     * @throws \yii\base\NotSupportedException
     */
    public function init()
    {
        // Set a @modules alias pointed to the modules/ directory
        Craft::setAlias('@samhernandez/craftdbpaths', __DIR__);

        // Set the controllerNamespace based on whether this is a console or web request
        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'samhernandez\\craftdbpaths\\console\\controllers';
        } else {
            $this->controllerNamespace = 'samhernandez\\craftdbpaths\\controllers';
        }

        if (Craft::$app->getRequest()->isCpRequest) {
            $this->initBackupCmd();
            $this->initRestoreCmd();
        }

        parent::init();
    }

    /**
     * @throws \yii\base\ErrorException
     * @throws \yii\base\NotSupportedException
     */
    protected function initBackupCmd()
    {
        if (Craft::$app->db->getDriverName() === 'mysql') {
            $pathToMysqldump = getenv('PATH_TO_MYSQLDUMP');

            if ($pathToMysqldump) {
                Craft::$app->config->general->backupCommand = str_replace(
                    'mysqldump',
                    $pathToMysqldump,
                    Craft::$app->db->getSchema()->getDefaultBackupCommand()
                );
            }
        } else {
            $pathToPgdump = getenv('PATH_TO_PGDUMP');

            if ($pathToPgdump) {
                Craft::$app->config->general->backupCommand = str_replace(
                    'pg_dump',
                    $pathToPgdump,
                    Craft::$app->db->getSchema()->getDefaultBackupCommand()
                );
            }
        }
    }

    /**
     * @throws \yii\base\ErrorException
     * @throws \yii\base\NotSupportedException
     */
    protected function initRestoreCmd()
    {
        if (Craft::$app->db->getDriverName() === 'mysql') {
            $pathToMysql = getenv('PATH_TO_MYSQL');

            if ($pathToMysql) {
                Craft::$app->config->general->restoreCommand = str_replace(
                    'mysql',
                    $pathToMysql,
                    Craft::$app->db->getSchema()->getDefaultRestoreCommand()
                );
            }
        } else {
            $pathToPsql = getenv('PATH_TO_PSQL');

            if ($pathToPsql) {
                Craft::$app->config->general->restoreCommand = str_replace(
                    'psql',
                    $pathToPsql,
                    Craft::$app->db->getSchema()->getDefaultRestoreCommand()
                );
            }
        }
    }
}
