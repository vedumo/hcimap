<?php

class ECMS__Extension__Module__HCI__MAP__API__Observer__Import
{
    public function __construct($DB)
    {
        $this->DB = $DB;
        $this->Error = null;
    }
    
    public function Import($Instance, $Content)
    {
        // Import content and return true/false;
        return true;
    }
}