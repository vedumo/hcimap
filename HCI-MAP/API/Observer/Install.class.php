<?php

class ECMS__Extension__Module__HCI__MAP__API__Observer__Install
{
    public function __construct($DB)
    {
        $this->DB = $DB;
        $this->Error = null;
    }
    
    public function Install()
    {
        return true;
    }
}