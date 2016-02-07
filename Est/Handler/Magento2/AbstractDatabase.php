<?php

/**
 * abstract Magento 2 database handler class
 *
 * @author Christoph Frenes <c.frenes@reply.de>
 */
abstract class Est_Handler_Magento2_AbstractDatabase
    extends Est_Handler_Magento_AbstractDatabase
{
    /**
     * @var string
     */
    protected $_dbHost;

    /**
     * @var string
     */
    protected $_dbUser;

    /**
     * @var string
     */
    protected $_dbPassword;

    /**
     * @var string
     */
    protected $_dbDatabase;

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
            throw new Exception(
                sprintf('File "%s" not found', $dbCredentialFile)
            );
        }

        $envArray = file_get_contents($dbCredentialFile);

        require $this->getComposerAutoloaderFile();
        $phpParser = new PhpParser\Parser(new PhpParser\Lexer);

        try {
            $envConfig = $phpParser->parse($envArray);

            foreach ($envConfig[0]->expr->items AS $mainNode) {
                if ($mainNode->key->value == 'db') {
                    foreach ($mainNode->value->items AS $dbNode) {
                        if ($dbNode->key->value == 'table_prefix') {
                            $this->_tablePrefix = (string)$dbNode->value->value;
                        }

                        if ($dbNode->key->value == 'connection') {
                            foreach ($dbNode->value->items AS $conNode) {
                                if ($conNode->key->value == 'default') {
                                    foreach (
                                        $conNode->value->items AS $valueNode
                                    ) {
                                        if ($valueNode->key->value
                                            == 'host') {
                                            $this->_dbHost
                                                = $valueNode->value->value;
                                        }

                                        if ($valueNode->key->value
                                            == 'username'
                                        ) {
                                            $this->_dbUser
                                                = $valueNode->value->value;
                                        }

                                        if ($valueNode->key->value
                                            == 'password'
                                        ) {
                                            $this->_dbPassword
                                                = $valueNode->value->value;
                                        }

                                        if ($valueNode->key->value
                                            == 'dbname'
                                        ) {
                                            $this->_dbDatabase
                                                = $valueNode->value->value;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    break;
                }
            }
        } catch (Error $e) {
            throw new Exception(
                sprintf('File "%s" could not be parsed', $dbCredentialFile)
            );
        }

        return array(
            'host'     => (string)$this->_dbHost,
            'database' => (string)$this->_dbDatabase,
            'username' => (string)$this->_dbUser,
            'password' => (string)$this->_dbPassword
        );
    }

    protected function getComposerAutoloaderFile()
    {
        return sprintf(
            '%s%sautoload.php',
            dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))),
            DIRECTORY_SEPARATOR
        );
    }
}
