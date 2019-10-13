<?php

interface PostRequestHandler
{

    public function preparePost();

    public function executePost();
}
?>