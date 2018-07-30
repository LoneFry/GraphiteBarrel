<?php
/**
 * Log Config Changes
 * File : /^BCP/models/Config.php
 *
 * PHP version 5.3
 *
 * @category BetterCarPeople
 * @package  BCP
 * @author   Tyler Uebele <tyler.uebele@bettercarpeople.com>
 * @license  Copyright Better Car People, LLC
 * @link     http://bettercarpeople.com
 */

namespace Graphite\Barrel\Data;

use Graphite\core\G;
use Graphite\core\data\Record;
use Graphite\core\data\Login;
use Graphite\core\data\DataBroker;

/**
 * Config class - for logging config changes
 *
 * @category BetterCarPeople
 * @package  BCP
 * @author   Tyler Uebele <tyler.uebele@bettercarpeople.com>
 * @license  Copyright Better Car People, LLC
 * @link     http://bettercarpeople.com
 * @see      Record.php
 */
class Config extends Record {
    protected static $table = G_DB_TABL.'ConfigLog';
    protected static $pkey  = 'log_id';
    protected static $query = '';
    protected static $vars  = array(
        'log_id'        => array('type' => 'i',  'min' => 1, 'guard' => true),
        'tableName'     => array('type' => 's',  'max' => 255),
        'login_id'      => array('type' => 'i',  'min' => 1, 'max' => 65535, 'def' => 0),
        'eventDate'     => array('type' => 'dt', 'def' => NOW),
        'data'          => array('type' => 'o',  'max' => 65535),
        'affected_id'   => array('type' => 'i',  'min' => 0),
        'recordChanged' => array('type' => 'dt', 'min' => 0, 'guard' => true),
    );

    protected $loginname    = '';

    /**
     * Wrap the parent constructor and set roles if passed
     *
     * @param bool|int|array $a pkey value|set defaults|set values
     * @param bool           $b Set defaults
     *
     * @throws \Exception
     */
    public function __construct($a = null, $b = null) {
        if ('' == static::$query) {
            self::$query = 'SELECT t.`log_id`, t.`tableName`, t.`login_id`, t.`eventDate`,'
                .' t.`data`, t.`affected_id`, l.`loginname`'
                .' FROM `'.self::$table.'` t LEFT JOIN `'.Login::getTable().'` l ON t.`login_id` = l.`login_id`';
        }
        if (G::$S->Login) {
            self::$vars['login_id']['def'] = G::$S->Login->login_id;
        }
        if (is_array($a) && isset($a['loginname'])) {
            $this->loginname = $a['loginname'];
        }
        parent::__construct($a, $b);
    }

    /**
     * Logs data into the config database
     *
     * @param string $table the table name
     * @param int    $key   the affected_id
     * @param string $data  the data being saved
     *
     * @return string
     */
    public static function log($table, $key, $data) {
        $C = new static(array('tableName' => $table,
                              'affected_id' => $key,
                              'data' => $data,
                              ),
                        true);
        return G::build(DataBroker::class)->save($C);
    }

    /**
     * Returns the last 100 log entries
     *
     * @param string $table the table name
     * @param int    $key   the affected ID
     *
     * @return array
     */
    public static function getLog($table, $key) {
        $C = new static(array('tableName' => $table, 'affected_id' => $key));
        return $C->search(100, 0, 'log_id', true);
    }

    /**
     * Sets the login name after load
     *
     * @param array $row the row
     *
     * @return array
     */
    public function onload(array $row = array()) {
        if (isset($row['loginname'])) {
            $this->loginname = $row['loginname'];
            unset($row['loginname']);
        }
        return $row;
    }

    /**
     * Returns the login name
     *
     * @return string
     */
    public function loginname() {
        return $this->loginname;
    }
}
