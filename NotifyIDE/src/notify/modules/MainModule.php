<?php
namespace notify\modules;

use std, gui, framework, notify;


class MainModule extends AbstractModule
{

    /**
     * @event timer.action 
     */
    function doTimerAction(ScriptEvent $e = null)
    {    
        $this->stacktrace->width = $this->width;
    }

    /**
     * @event construct 
     */
    function doConstruct(ScriptEvent $e = null)
    {    
         $m = new UXMenu('Помощь');
        
        $j = new UXMenuItem('О программе...');
        $g = new UXMenuItem('Дискорд сервер');
        $m->items->add($g);
        $m->items->add($j);
        $g->on('action', function($e) use($g){
            browse('https://discord.gg/dFART28UP9');
        });
        $j->on('action', function($e) use($j){
            alert($this->labelAlt->text);
        });
        $f = new UXMenu('Система');
        $this->stacktrace->menus->add($f);
        $this->stacktrace->menus->add($m);
        $k = new UXMenuItem('Сохранить открытый скрипт');
        $f->items->add($k);
        $k->on('action', function($e) use($f){
            global $c;
            $text = $this->textArea->text;
           $f1 = file_put_contents($c, $text);
           $js = str_ireplace('res://', 'Data/', $c);
            if($f1 and file_exists($js)){
                alert("Скрипт сохранён.");
            }else{
            UXDialog::show('Ошибка при сохранении скрипта!', 'ERROR');
            }
        });
        $e1 = new UXMenuItem('Заменить в тексте...');
        $f->items->add($e1);
        $e1->on('action', function($e) use($e1){
            $this->form(rep)->show();
        });
        $run = new UXMenuItem('Запустить программу');
        $f->items->add($run);
        $run->on('action', function($e) use($run){
            global $c;
            $f = str_ireplace("res://", "Data/", $c);
            
        file_put_contents($f, $this->textArea->text);
        execute("TASKKILL /F /IM game.exe");
        sleep(1);
        execute("game.exe");
        });
    }

}
