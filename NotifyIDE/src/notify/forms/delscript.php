<?php
namespace notify\forms;

use std, gui, framework, notify;


class delscript extends AbstractForm
{

    /**
     * @event buttonAlt.action 
     */
    function doButtonAltAction(UXEvent $e = null)
    {    
        $this->form(MainForm)->fragment->visible = false;
    }

    /**
     * @event button.action 
     */
    function doButtonAction(UXEvent $e = null)
    {    
        $this->form(MainForm)->fragment->visible = false;
        if(file_exists("Data/".$this->edit->text.".php")){
            fs::delete("Data/".$this->edit->text.".php");
            alert("Файл ".$this->edit->text." удалён.");
        }else{
          alert("Файл ".$this->edit->text." не найден;");
        }
        $this->edit->text = "";
    }

}
