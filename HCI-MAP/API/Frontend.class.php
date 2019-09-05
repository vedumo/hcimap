<?php

class ECMS__Extension__Module__HCI__MAP__API__Frontend extends ECMS__Extension__Module__HCI__MAP__API
{
	public function Preprocess()
    {
    }


    public function main()
    {
        $this->Headers[] = 'Content-Type: application/json; charset=UTF-8';
        $this->Standalone = true;
        $this->Template = false;

        $x = new stdClass();

        $x->_Module = $this->ID;
        $x->_Method = 'metoda';
        $x->Username = 'korisnik';
        $x->Password = 'lozinka';
        $x->TimeOffset = 0.12345;
        $x->Source = 'eeg';
        $x->Data = [];
        $y = new stdClass();
        $y->Interest = rand(0,100);
        $y->Engagement = rand(0,100);
        $y->Excitement = rand(0,100);
        $y->Stress = rand(0,100);
        $y->Relaxation = rand(0,100);
        $y->Focus = rand(0,100);
        $y->Time = microtime(1);
        $x->Data[] = $y;
        $x->Data[] = $y;
        $x->Data[] = $y;
        $x->Data[] = $y;
        $this->Content = $x;

        $this->Content = json_encode($this->Content);
    }


    public function timeOffset()
    {
        $this->Headers[] = 'Content-Type: application/json; charset=UTF-8';
        $this->Standalone = true;
        $this->Template = false;

        $this->Content = [];
        $this->Content['request_sent'] = @$this->Input['request_sent'];
        $this->Content['request_received'] = $_SERVER['REQUEST_TIME_FLOAT'];
        $this->Content['response_sent'] = microtime(1);

        $this->Content = json_encode($this->Content);
    }


    public function createSession()
    {
        $this->Headers[] = 'Content-Type: application/json; charset=UTF-8';
        $this->Standalone = true;
        $this->Template = false;

        $DBB_Session = new ECMS__SQL($this->DB, "Module__HCI__MAP__Session");

        $Session_Name = trim(@$this->Input['Name']);
        if (!$Session_Name)
        {
            $this->Content = new stdClass();
            $this->Content->Session_ID = 0;
            $this->Content->Error = 'Invalid session name';
            $this->Content = json_encode($this->Content);
            return;
        }

        // Check if there is already active session
        if ($DBB_Session->Select(null, "StartTime>0 and StopTime=0")->NumRecords > 0)
        {
            $this->Content = new stdClass();
            $this->Content->Session_ID = 0;
            $this->Content->Error = 'There is already running session.';
            $this->Content = json_encode($this->Content);
            return;
        }

        // Insert database record and redirect
        $Record = [];
        $Record['Name'] = $Session_Name;
        $Record['StartTime'] = (abs((double) @$this->Input['StartTime'] - microtime(1)) < 86400) ? (double) @$this->Input['StartTime'] : microtime(1);
        $Record['StopTime'] = 0;
        $Result = $DBB_Session->Insert($Record);
        if ($Result->Error)
        {
            $this->Content = new stdClass();
            $this->Content->Session_ID = 0;
            $this->Content->Error = $Result->Error;
            $this->Content = json_encode($this->Content);
            return;
        }
        else
        {
            $this->Content = new stdClass();
            $this->Content->Session_ID = $Result->InsertID;
            $this->Content->Error = null;
            $this->Content = json_encode($this->Content);
            return;
        }
    }


    public function endSession()
    {
        $this->Headers[] = 'Content-Type: application/json; charset=UTF-8';
        $this->Standalone = true;
        $this->Template = false;

        $DBB_Session = new ECMS__SQL($this->DB, "Module__HCI__MAP__Session");

        $Session_ID = (int) @$this->Input['ID'];
        if ($Session_ID<1)
        {
            $this->Content = new stdClass();
            $this->Content->Success = false;
            $this->Content->Error = 'Invalid session ID';
            $this->Content = json_encode($this->Content);
            return;
        }

        // Check if there is already active session
        $Session = @$DBB_Session->Select(null, "ID=$Session_ID")->Records[0];
        if (!$Session)
        {
            $this->Content = new stdClass();
            $this->Content->Success = false;
            $this->Content->Error = "Session doesn't exist";
            $this->Content = json_encode($this->Content);
            return;
        }

        // Check if session can be ended
        if ($Session->StartTime == 0)
        {
            $this->Content = new stdClass();
            $this->Content->Success = false;
            $this->Content->Error = "Session has not started yet";
            $this->Content = json_encode($this->Content);
            return;
        }
        if ($Session->StopTime > 0)
        {
            $this->Content = new stdClass();
            $this->Content->Success = false;
            $this->Content->Error = "Session already ended";
            $this->Content = json_encode($this->Content);
            return;
        }

        // Update database record
        $StopTime = (abs((double) @$this->Input['StopTime'] - microtime(1)) < 10) ? (double) @$this->Input['StopTime'] : microtime(1);
        $Result = $DBB_Session->Update(['StopTime'=>$StopTime], "ID={$Session->ID}");
        if ($Result->Error)
        {
            $this->Content = new stdClass();
            $this->Content->Success = false;
            $this->Content->Error = $Result->Error;
            $this->Content = json_encode($this->Content);
            return;
        }
        else
        {
            $this->Content = new stdClass();
            $this->Content->Success = true;
            $this->Content->Error = null;
            $this->Content = json_encode($this->Content);
            return;
        }
    }


    public function write()
    {
        $this->Headers[] = 'Content-Type: application/json; charset=UTF-8';
        $this->Standalone = true;
        $this->Template = false;

        $Input = @json_decode(@$this->Input['Data']);
        if (!is_object($Input))
        {
            $this->Content = 'Invalid input.';
            return;
        }

        $TimeOffset = (double) @$Input->TimeOffset;
        if (is_array(@$Input->Records))
        {
            // sql brokeri...
            $DBB_Record = new ECMS__SQL($this->DB, 'Module__HCI__MAP__Record');

            foreach ($Input->Records as $InputRecord)
            {
                $Record = [];
                $Record['Source'] = $Input->Source;
                $Record['Session'] = $Input->Session;
                $Record['Type'] = $InputRecord->Type;
                $Record['ClientTime'] = $InputRecord->Timestamp;
                $Record['RealTime'] = $InputRecord->Timestamp + $Input->TimeOffset;
                $Record['ServerTime'] = (double) @$_SERVER['REQUEST_TIME_FLOAT'];
                $Record['SequenceNumber'] = $Input->SequenceNumber ;
                $Record['IP'] = @$_SERVER['REMOTE_ADDR'];

                // Add parameters
                $Record['Data'] = new stdClass();
                foreach ($InputRecord as $Attribute=>$Value)
                {
                    if ( in_array($Attribute, ['Source','Session','Type','Timestamp'])) continue;
                    $Record['Data']->$Attribute = $Value;
                }
                $Record['Data'] = json_encode($Record['Data']);
                $Result = $DBB_Record->Insert($Record);
                if ($Result->Error)
                {
                    $this->Error = $Result->Error;
                    return;
                }
                $Event_ID = $Result->InsertID;
            }
        }
        $this->Content = 'OK';

        $this->Content = json_encode($this->Content);
    }


    public function getActiveSensors()
    {
        $this->Headers[] = 'Content-Type: application/json; charset=UTF-8';
        $this->Standalone = true;
        $this->Template = false;

        $DBB_Record = new ECMS__SQL($this->DB, 'Module__HCI__MAP__Record');
        $this->Content = [];
        foreach ($DBB_Record->Select('distinct Source as Sensor, Session', 'ServerTime>='.(time()-3), 'Source asc')->Records as $Sensor)
        {
            $this->Content[] = $Sensor->Sensor;
        }

        $this->Content = json_encode($this->Content);
    }


	public function Postprocess()
    {
    }
}