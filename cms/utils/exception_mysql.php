<?php
echo "<p class=help>Произошла исключительная
        ситуация (ExceptionMySQL) при обращении
        к СУБД MySQL.</p>";
  echo "<p class=help>{$e->getMySQLError()}<br>
       ".nl2br($exc->getSQLQuery())."</p>";
  echo "<p class=help>Ошибка в файле {$e->getFile()}
        в строке {$e->getLine()}.</p>";
?>
