<?php
echo "<p class=help>Произошла исключительная
        ситуация (ExceptionMember) - попытка
        обращения к несуществующему члену класса.
        {$e->getMessage()}.</p>";
  echo "<p class=help>Ошибка в файле {$e->getFile()}
        в строке {$e->getLine()}.</p>";
?>
