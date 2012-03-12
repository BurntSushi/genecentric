<?php

function cmd($str) {
  return "<p><code class=\"command\">$str</code></p>";
}

function gi($name) {
  return " $name.<a href=\"doc.php#file-gi\">gi</a>";
}

function bpm($name) {
  return " $name.<a href=\"doc.php#file-bpm\">bpm</a>";
}

function gobpm($name) {
  return " $name.<a href=\"doc.php#file-gobpm\">gobpm</a>";
}

function code($str) {
  return "<span class=\"code\">$str</span>";
}

?>

