<?php

class ECMS__Extension__Module__HCI__MAP__API__Observer__Add
{
    public function __construct($DB)
    {
        $this->DB = $DB;
        $this->Error = null;
    }
    
    public function Add($Instance, $Page_ID)
    {
        return true;
    }
}