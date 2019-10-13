<?php

class DirectionsBean
{

    private $mDistance = 0;

    private $mDuration = 0;

    private $mMessage = "";

    private $RC = 0;

    public function getDistance()
    {
        return $this->mDistance;
    }

    public function getDuration()
    {
        return $this->mDuration;
    }

    public function setDistance($mDistance)
    {
        $this->mDistance = $mDistance;
    }

    public function setDuration($mDuration)
    {
        $this->mDuration = $mDuration;
    }

    public function __toString()
    {
        return "[RC: " . $this->getRC() . ", Distance: " . $this->getDistance() . ", Duration: " . $this->getDuration() . ", Message. " . $this->getMessage() . "]";
    }

    public function toArray()
    {
        $a = array();
        $a['rc'] = $this->getRC();
        $a['duration'] = $this->getDuration();
        $a['distance'] = $this->getDistance();
        $a['message'] = $this->getMessage();
        return $a;
    }

    public function toJSON()
    {
        return json_encode($this->toArray());
    }

    public function getMessage()
    {
        return $this->mMessage;
    }

    public function setMessage($mMessage)
    {
        $this->mMessage = $mMessage;
    }

    public function getRC()
    {
        return $this->RC;
    }

    public function setRC($RC)
    {
        $this->RC = $RC;
    }
}