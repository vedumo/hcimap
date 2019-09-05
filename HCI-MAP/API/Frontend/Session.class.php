<?php

class ECMS__Extension__Module__HCI__MAP__API__Frontend__Session extends ECMS__Extension__Module__HCI__MAP__API__Frontend
{
    public function get()
    {
        $this->Headers[] = 'Content-Type: application/json; charset=UTF-8';
        $this->Standalone = true;
        $this->Template = false;

        $DBB_Session = new ECMS__SQL($this->DB, "Module__HCI__MAP__Session");

        $offset = (int) @$this->Input['offset'];
        $limit = (int) @$this->Input['limit'];

        if ($limit)
        {
            $this->Content = $DBB_Session->Select(null, null, 'ID desc', "$offset,$limit")->Records;
        }
        else
        {
            $this->Content = $DBB_Session->Select(null, null, 'ID desc')->Records;
        }
        $this->Content = json_encode($this->Content);
        return;
    }
}