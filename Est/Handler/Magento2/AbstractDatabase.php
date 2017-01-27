<?php

/**
 * abstract Magento 2 database handler class
 *
 * @author Christoph Frenes <c.frenes@reply.de>
 */
abstract class Est_Handler_Magento2_AbstractDatabase extends Est_Handler_Magento_AbstractDatabase
{
    /**
     * Read database connection parameters from env.php file
     *
     * @return array
     * @throws Exception
     */
    protected function _getDatabaseConnectionParameters()
    {
        $dbCredentialFile = 'app/etc/env.php';

        if (!is_file($dbCredentialFile)) {
            throw new Exception(sprintf('File "%s" not found', $dbCredentialFile));
        }

        $config = (include $dbCredentialFile);

        if (!is_array($config) || empty($config['db'])) {
            throw new Exception(sprintf('DB credentials not found in file %s', $dbCredentialFile));
        }

        $db                 = $config['db'];
        $credentials        = $db['connection']['default'];
        $this->_tablePrefix = (string)$db['table_prefix'];

        return array(
            'host'     => (string)$credentials['host'],
            'database' => (string)$credentials['dbname'],
            'username' => (string)$credentials['username'],
            'password' => (string)$credentials['password']
        );
    }
}
