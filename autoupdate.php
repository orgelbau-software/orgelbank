<?php
try {
    $hash = `git log -1 --pretty=%h`;
    echo "Previous CommitID: $hash";
    $out = `git pull`;
    print_r($out);
    
    $hash = `git log -1 --pretty=%h`;
    echo "New CommitID: $hash";
} catch(Exception $e) {
    echo "Error: ".$e;
}