<?php

class ECMS__Extension__Module__HCI__MAP__API__Observer__Export
{
    public function __construct($DB)
    {
        $this->DB = $DB;
        $this->Error = null;
    }
    
    public function Export($Instance)
    {
        // Important!!! Do not export registry values!
        // Try to avoid exporting/importing primary keys if not neccessary
            // Hierarchical object with subobjects array is recommented
        
        // Load and return content
        return '';
    }
}