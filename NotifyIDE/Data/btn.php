<?php
$text = new TLabel;
$text->align = alTop;
$text->parent = c("Forma");
$text->caption = 0;
$text->font->size = 20;
$text->name = "font";
if(file_exists("save.txt")){
global $get;
$get = file_get_contents("save.txt");
$text->caption = $get;
global $c;
$c = $get;
}
$btn = new TBitBtn;
$btn->parent = c("Forma");
$btn->align = alTop;
$btn->h = 30;
$btn->cursor = crHandPoint;
$btn->caption = "Click";
$btn->name = "alr";
$b2 = new TBitBtn;
$b2->align = alTop;
$b2->caption = "Delete save";
$b2->h = 30;
$b2->parent = c("Forma");
$b2->cursor = crHandPoint;
$b2->onClick = function($self){
global $c;
$c = 0;
file_put_contents("save.txt", $c);
c("font")->caption = $c;
};
c("alr")->onClick = function($self){
global $c;
$c++;
$text->caption = $c;
file_put_contents("save.txt", $c);
c("font")->caption = $c;
};