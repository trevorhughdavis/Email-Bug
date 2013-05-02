<?php 
function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'MacOSX';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Win7';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'IE';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {
        $version="";
    }
    
    if ($platform=='MacOSX' && $bname=='Unknown'){ $bname='Safari'; }

    return array(
            'userAgent' => $u_agent,
            'browser'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
    );
}


require_once('Mobile_Detect.php');
$detect = new Mobile_Detect();
$append ='';

if ($detect->isMobile())
{
    if($detect->isIphone())
        {
            
            $append = '[iPhone]';
        }
    else if ($detect->isAndroidOS())
    {
        $append = '[Android]';
        
    }
    else if ($detect->isBlackBerry())
    {
        $append = '[Blackberry]';
    
    }
   else
   {
    $append = '[Mobile (Other)]';
   }
}
else 
{
   $browser = get_browser(null, true);
   if ($browser['browser']=='Default Browser')
   {
      $browser=  getBrowser();
   }
 $append =  '[Desktop - Browser: '.$browser['browser']."  Platform: ".$browser['platform']." Version: ".$browser['version'].']';
}


     $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];


    $mysqli = new mysqli('localhost','database','password','user');
    if (mysqli_connect_errno()){    exit();}
    
    $REQUEST_URI= $mysqli->real_escape_string($_SERVER['REQUEST_URI']);
    
    $HTTP_USER_AGENT = $append;
    $REMOTE_ADDR = $mysqli->real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
    
    
    
    
    $sql = 'INSERT INTO `analytics`.`web_tracker`(`id`,`HTTP_USER_AGENT`,`REMOTE_ADDR`,`REQUEST_URI`,`request_date`)';
    $stuff = "VALUES(null,'$HTTP_USER_AGENT','$REMOTE_ADDR','$REQUEST_URI', now());";
    $mysqli->query($sql.$stuff);

header("Content-type: image/gif");
header("Content-length: 43");
$fp = fopen("php://output","wb");
fwrite($fp,"GIF89a\x01\x00\x01\x00\x80\x00\x00\xFF\xFF",15);
fwrite($fp,"\xFF\x00\x00\x00\x21\xF9\x04\x01\x00\x00\x00\x00",12);
fwrite($fp,"\x2C\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02",12);
fwrite($fp,"\x44\x01\x00\x3B",4);
fclose($fp);
?>
