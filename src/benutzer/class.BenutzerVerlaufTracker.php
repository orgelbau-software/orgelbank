<?php

class BenutzerVerlaufTracker
{

    public static function track()
    {
        if (! isset($_SESSION['user']) || ! isset($_SESSION['user']['id'], $_SESSION['user']['benutzername'])) {
            return;
        }
        $db = DB::getInstance();
        $db->connect();
        $track = new BenutzerVerlauf();
        $track->setBenutzerID($_SESSION['user']['id']);
        $track->setBenutzerName($_SESSION['user']['benutzername']);
        $track->setDuration(- 1);
        $track->setGet(addslashes(print_r($_GET, true)));
        if ($_POST) {
            $track->setPost(addslashes(substr(print_r($_POST, true), 0, 500)));
        } else {
            $track->setPost(NULL);
        }
        
        if (isset($_SERVER['HTTP_REFERER']))
            $track->setReferer($_SERVER['HTTP_REFERER']);
        $track->setRequestURI($_SERVER['REQUEST_URI']);
        
        $track->speichern(true);
        
        BenutzerVerlaufTracker::finishOldTrack();
        
        $_SESSION['request']['lasttrackid'] = $track->getID();
        
        // $db->disconnect();
    }

    private static function finishOldTrack()
    {
        if (! isset($_SESSION['request']['lasttrackid']) || $_SESSION['request']['lasttrackid'] == null || $_SESSION['request']['lasttrackid'] <= 0)
            return;
        $oldTrack = new BenutzerVerlauf($_SESSION['request']['lasttrackid']);
        $oldTrack->setDuration((time() - $_SESSION['request']['lastaction']));
        $oldTrack->speichern(false);
    }
}
?>