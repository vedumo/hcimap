<?php

class ECMS__Extension__Module__HCI__MAP__API__Observer__Remove
{
    public function __construct($DB)
    {
        $this->DB = $DB;
        $this->Error = null;
    }
    
    public function Remove($Instance, $Page_ID)
    {
        return true;
    }
}