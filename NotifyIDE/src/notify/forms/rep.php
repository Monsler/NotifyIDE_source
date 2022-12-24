<?php
namespace notify\forms;

use std, gui, framework, notify;


class rep extends AbstractForm
{

    /**
     * @event button.action 
     */
    function doButtonAction(UXEvent $e = null)
    {    
        $text = $this->form(MainForm)->textArea->text;
        $rep = str_ireplace($this->edit->text, $this->editAlt->text, $text);
        $this->hide();
        $this->form(MainForm)->textArea->text  = $rep;
        $this->edit->text = "";
        $this->editAlt->text = "";
    }

}
