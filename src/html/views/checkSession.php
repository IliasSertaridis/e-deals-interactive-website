<?php
function checkSession($userLocation, $adminLocation, $noLocation)
{
    session_start();
    if (isset($_SESSION['username']) && isset($_SESSION['user_type']))
    {
        if ($_SESSION['user_type'] == 'user') {
            if($userLocation != '')
            {
                header("Location: " . $userLocation);
            }
            else
            {
                return 'user';
            }
        }
        else
        {
            if($adminLocation != '')
            {
                header("Location: ". $adminLocation);
            }
            else
            {
                return 'administrator';
            }
        }
    }
    else
    {
        if($noLocation != '')
        {
            header("Location: ". $noLocation);
        }
        else
        {
            return('no');
        }
    }
}
?>
