<?php

namespace Alloy\Db\ActiveRecord;

use Alloy\Core\CollectionInterface;
use Alloy\Core\IEquals;
use Alloy\Core\IID;
use Alloy\Type\ID;

interface IActiveRecord extends
    IID,
    IEquals,
    CollectionInterface
{
    /**
     * Save the changes (not all fields, just changes)
     *
     * @return void
     *
     * @throws \Exception
     */
    public function save();

    /**
     * Returns true, if data, represented by ActiveRecord is
     * mapped to existent database entry
     * For example, for non-existent or new entries this method
     * must return false
     *
     * @return boolean
     */
    public function exists();

    /**
     * Deletes current active record
     * Do not throw exception on NEW or EMPTY entries
     *
     * @return void
     *
     * @throws \Exception
     */
    public function delete();
}