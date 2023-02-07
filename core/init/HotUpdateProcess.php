<?php


namespace core\init;


use core\helper\FileHelper;
use Swoole\Process;

class HotUpdateProcess
{
    private $fileMd5 = '';
    public function run()
    {
        return new Process(function(){
           while (true){
               sleep(3);
               $md5_value = FileHelper::getFileMd5(ROOT_PATH."/app/*",'/app/config');
               if($this->fileMd5 == ""){
                   $this->fileMd5 = $md5_value;
                   continue;
               }
               if(strcmp($this->fileMd5,$md5_value)!==0){
                   echo "reloading..";
                   $master_pid = intval(file_get_contents(ROOT_PATH."/guyue.pid"));
                   $this->fileMd5 = $md5_value;
                   Process::kill($master_pid,SIGUSR1);
                   echo "reloaded~";
               }
           }
        });
    }
}