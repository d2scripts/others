<?php

$URI = strtok($_SERVER['REQUEST_URI'], '?');

if($_GET)
{
    $_GET2 = $_GET;
    unset($_GET2['fbclid']);
    $URI_FULL = '?' . http_build_query($_GET2);
}

$DATA2_FLG_CACHE = false;

if(strpos($URI, '.') === false && strpos($URI, 'wp-') === false)
{
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {        
        $DATA2_FLG_CACHE = true;
    }    
}

if($DATA2_FLG_CACHE)
{
    if($_COOKIE)
    {
        foreach($_COOKIE as $cookie => $vlr)
        {

            if(strpos($cookie, 'wordpress_') !== false)
            {
                $DATA2_FLG_CACHE = false;
            }
        }
    }
}

#if(isset($_GET['data2']))
{
    if($DATA2_FLG_CACHE)
    {
        $MD5_CONTENT_KEY = $_SERVER['REQUEST_SCHEME'] . $_SERVER['HTTP_HOST'] . $URI . $URI_FULL;
        $MD5_KEY = md5($MD5_CONTENT_KEY);
        $KEY = '/dev/shm/cache/' . $_SERVER['HTTP_HOST'] . '_' . $MD5_KEY . '.cache';

        if(is_file($KEY))
        {
            $DIFF = time() - filemtime($KEY);
            if($DIFF < (60 * 7))
            {
                header('data2-cache-id: ' . $MD5_KEY);
                header('data2-cache-id-content: ' . urlencode($MD5_CONTENT_KEY));
                readfile($KEY);
                exit;
            }
        }

        ob_start();
    }
}

/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';

if($DATA2_FLG_CACHE)
{
    $HTML = ob_get_contents();
    echo htmlentities($HTML);
    file_put_contents($KEY, $HTML);
    ob_end_flush();
}
