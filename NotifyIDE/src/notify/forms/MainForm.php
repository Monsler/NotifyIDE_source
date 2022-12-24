<?php
namespace notify\forms;

use php\compress\ZipFile;
use std, gui, framework, notify;


class MainForm extends AbstractForm
{

    /**
     * @event showing 
     */
    function doShowing(UXWindowEvent $e = null)
    {    
        $this->minWidth = 400;
        $this->minHeight = 300;
        global $c;
        $c = "res://main.php";
        $ca = str_ireplace("res://", "Data/", $c);
        if(file_exists($ca)){
        $get = file_get_contents($ca);
        $this->textArea->text = $get;
        $this->title = "NotifyIDE - ".$c;
        $this->edit->text = $c;
        $c = $ca;
        }else{
        $this->textArea->text = "<?php";
          $this->title = "NotifyIDE - "."Нет открытых скриптов";
        }
        
    }

    /**
     * @event button.click-Left 
     */
    function doButtonClickLeft(UXMouseEvent $e = null)
    {    
    $c = $this->edit->text;
    $ca = str_ireplace("res://", "Data/", $c);
        if(file_exists($ca)){
        $c = $this->edit->text;
        $ca = str_ireplace("res://", "Data/", $c);
            $get = file_get_contents($ca);
            $this->textArea->text = $get;
            global $c;
            $c= $ca;
            $this->title = "NotifyIDE - ".$this->edit->text;
        }else{
        UXDialog::show("Файл не найден / не существует.", 'WARNING');
        }
    }

    /**
     * @event buttonAlt.action 
     */
    function doButtonAltAction(UXEvent $e = null)
    {    
        global $c;
        if($c != false){
            file_put_contents($c, $this->textArea->text);
            alert("Файл сохранён.");
        }else{
        UXDialog::show("Вы ещё не открыли ни одного скрипта!", 'WARNING');
        }
    }

    /**
     * @event button3.click-Left 
     */
    function doButton3ClickLeft(UXMouseEvent $e = null)
    {    
        global $c;
        file_put_contents($c, $this->textArea->text);
        execute("TASKKILL /F /IM game.exe");
        sleep(1);
        execute("game.exe");
        
    }

    /**
     * @event button6.action 
     */
    function doButton6Action(UXEvent $e = null)
    {    
        $this->editAlt->visible = false;
        $this->button5->visible = false;
        $this->button6->visible = false;
    }

    /**
     * @event button4.action 
     */
    function doButton4Action(UXEvent $e = null)
    {    
        $this->editAlt->visible = true;
        $this->button5->visible = true;
        $this->button6->visible = true;
    }

    /**
     * @event button5.click-Left 
     */
    function doButton5ClickLeft(UXMouseEvent $e = null)
    {    
    if($this->editAlt->text != ""){
        $file = $this->editAlt->text.".php";
        file_put_contents("Data/".$file, "<?php
alert('NotifyIDE!');");
        alert("Доступ к файлу: "."res://".$file);
        $t = $this->editAlt->text;
        $this->editAlt->text = "";
        $this->textArea->text .= "
include 'Data/".$t.".php';";
 $this->editAlt->visible = false;
        $this->button5->visible = false;
        $this->button6->visible = false;
        }else{
        $this->editAlt->visible = false;
        $this->button5->visible = false;
        $this->button6->visible = false;
        }
    }

    /**
     * @event button7.action 
     */
    function doButton7Action(UXEvent $e = null)
    {    
        $this->form(MainForm)->fragment->visible = true;
    }

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        if(!file_exists("Data/config.wu")){
        file_put_contents("Data/config.wu", "res://main.php");
    }
    if(!file_exists("Data/main.php")){
        $file = file_get_contents("Data/config.wu");
        global $c;
       $s = str_ireplace("res://", "Data/", $file);
      $get = file_get_contents($s);
      $this->textArea->text = $get;
      $this->edit->text = $file;
      $this->title = "NotifyIDE - ".$this->edit->text;
    }
        $menu = new UXMenuBar();
        $menu->width = $this->width;
        $item1 = new UXMenu('Программа');
        $menu->menus->add($item1);
        $this->add($menu);
        $itemi = new UXMenuItem('Новый скрипт');
        $item1->items->add($itemi);
        $itemi->on('action', function($e) use ($item1){
        
            $this->tabPane->selectedIndex = 1;
             $this->button5->visible = true;
        $this->button6->visible = true;
        $this->editAlt->visible = true;
        });
        $menu->id = "stacktrace";
       $menu->cursor = HAND;
       $itemg = new UXMenuItem('Сохранить скрипт и выйти из программы');
       $item1->items->add($itemg);
       $itemg->on('action', function($e) use($itemg){
       global $c;
       $file = $c;
       $s = str_ireplace("res://", "Data/", $file);
       file_put_contents($s, $this->textArea->text);
           app::shutdown();
       });
      $item3 = new UXMenuItem("Собрать в архив");
      $item1->items->add($item3);
      $item3->on('action', function($e) use($item3){
          $data = "Data/";
          $file = $this->fileChooser->execute();
          if($file){
          $this->zipFile->addDirectoryAsync($data);
         $x = $this->zipFile->path = $this->fileChooser->file;
          if($x){
              alert("Проект сохранён в ".$this->fileChooser->file);
              $this->fileChooser->initialDirectory = "C:/";
          }else{
          alert("Возникла ошибка при сохранении.");
          }
          }else{
          UXDialog::show("Сохранение отменено.", 'WARNING');
          }
      });
      $item4 = new UXMenuItem("Распаковать архив");
      $item1->items->add($item4);
      $item4->on('action', function($e) use($item4){
          $this->fileChooserAlt->initialDirectory = "C:/";
          $file = $this->fileChooserAlt->execute();
          if($file){
              $path = "Data/";
              $file = $this->fileChooserAlt->file;
              $this->zipFile->path = $file;
              fs::clean($path);
             $v = $this->zipFile->unpackAsync($path);
             if($v){
                 alert("Проект распакован из ".$this->fileChooserAlt->file);
                 global $c;
                 if(file_exists("Data/config.wu")){
                 global $c;
                     $vid = file_get_contents("Data/config.wu");
                     $c = $vid;
                     $ca = str_ireplace("res://", "Data/", $c);
                     $ex = file_get_contents($ca);
                     $this->textArea->text = $ex;
                     $c = $ca;
                     $this->edit->text = $vid;
                     $this->title = "NotifyIDE - ".$this->edit->text;
                     $derect = new File("Data/");
                 }
             }else{
             UXDialog::show("Что-то пошло не так при распаковке проекта", 'ERROR');
             
             }
          }else{
          UXDialog::show("Открытие отменено", 'WARNING');
          }
      });
    }

    /**
     * @event button9.action 
     */
    function doButton9Action(UXEvent $e = null)
    {    
          $this->fileChooserAlt->initialDirectory = "C:/";
          $file = $this->fileChooserAlt->execute();
          if($file){
              $path = "Data/";
              $file = $this->fileChooserAlt->file;
              $this->zipFile->path = $file;
              fs::clean($path);
             $v = $this->zipFile->unpackAsync($path);
             if($v){
                 alert("Проект распакован из ".$this->fileChooserAlt->file);
                 global $c;
                 if(file_exists("Data/config.wu")){
                 global $c;
                     $vid = file_get_contents("Data/config.wu");
                     $c = $vid;
                     $ca = str_ireplace("res://", "Data/", $c);
                     $ex = file_get_contents($ca);
                     $this->textArea->text = $ex;
                     $c = $ca;
                     $this->edit->text = $vid;
                     $this->title = "NotifyIDE - ".$this->edit->text;
                 }
             }else{
             UXDialog::show("Что-то пошло не так при распаковке проекта", 'ERROR');
             
             }
          }else{
          UXDialog::show("Открытие отменено", 'WARNING');
          }
    }

    /**
     * @event button10.action 
     */
    function doButton10Action(UXEvent $e = null)
    {    
        $data = "Data/";
          $file = $this->fileChooser->execute();
          if($file){
          $this->zipFile->addDirectoryAsync($data);
         $x = $this->zipFile->path = $this->fileChooser->file;
          if($x){
              alert("Проект сохранён в ".$this->fileChooser->file);
              $this->fileChooser->initialDirectory = "C:/";
          }else{
          alert("Возникла ошибка при сохранении.");
          }
          }else{
          UXDialog::show("Сохранение отменено.", 'WARNING');
          }
    }









}
